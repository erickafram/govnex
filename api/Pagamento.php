<?php
require_once __DIR__ . '/../conf/db_connection.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

class Pagamento
{
    private $conn;
    private $clientUri = 'https://api.digitopayoficial.com.br/';
    private $clientId = '41b9547d-1053-47ee-8b57-322ca8fd67b1';
    private $clientSecret = '1697c51a-7b58-4370-b5dd-f54183169523';
    private $logFile = '../logs/digitopay_pagamentos.log';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function criarPagamento($usuarioId, $valor, $dadosUsuario)
    {
        try {
            if ($valor < 20.00) {
                throw new Exception("Valor mínimo de R$ 20,00 para recarga");
            }

            $token = $this->getAuthToken();
            if (!$token) {
                throw new Exception("Falha na autenticação com a API de pagamentos");
            }

            // Validar e formatar CPF
            $cpf = preg_replace('/[^0-9]/', '', $dadosUsuario['cpf']);

            if (strlen($cpf) !== 11 || !$this->validarCPF($cpf)) {
                $this->logError("CPF inválido fornecido: " . $dadosUsuario['cpf']);
                throw new Exception("CPF inválido. Por favor, verifique o número informado.");
            }

            $this->logError("CPF formatado para envio: $cpf"); // Log para debug

            $paymentData = [
                "dueDate" => date('Y-m-d\TH:i:s', strtotime('+1 day')),
                "paymentOptions" => ["PIX"],
                "person" => [
                    "cpf" => $cpf,
                    "name" => $dadosUsuario['nome']
                ],
                "value" => $valor,
                "callbackUrl" => "https://govnex.site/api/webhook_pagamentos.php"
            ];

            $paymentResponse = $this->callDigitopayAPI(
                $this->clientUri . 'api/deposit',
                $token,
                $paymentData
            );

            // Gerar QR Code
            $qrCode = new QrCode($paymentResponse['pixCopiaECola']);
            $writer = new PngWriter();
            $qrImage = $writer->write($qrCode);

            // Salvar QR Code temporariamente
            $qrPath = '../temp/qrcode_' . $paymentResponse['id'] . '.png';
            if (!file_exists('../temp')) {
                mkdir('../temp', 0755, true);
            }
            file_put_contents($qrPath, $qrImage->getString());

            // Registrar a transação no banco de dados
            $this->registrarPagamentoNoBanco(
                $usuarioId,
                $valor,
                $paymentResponse['id']
            );

            return [
                'qr_code' => $qrPath,
                'codigo_transacao' => $paymentResponse['id'],
                'pix_copia_cola' => $paymentResponse['pixCopiaECola']
            ];
        } catch (Exception $e) {
            $errorMessage = "Erro ao criar pagamento: " . $e->getMessage();
            $this->logError($errorMessage);
            error_log($errorMessage);
            return false;
        }
    }

    private function getAuthToken()
    {
        $ch = curl_init($this->clientUri . 'api/token/api');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode([
                'clientId' => $this->clientId,
                'secret' => $this->clientSecret
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            throw new Exception("Falha na autenticação com a Digitopay");
        }

        $tokenData = json_decode($response, true);
        return $tokenData['accessToken'] ?? null;
    }

    private function callDigitopayAPI($url, $token, $data)
    {
        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token
                ],
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if (!$response) {
                $this->logError("CURL Error: $error | URL: $url");
                throw new Exception("Erro na comunicação com a Digitopay: $error");
            }

            $responseData = json_decode($response, true);

            if ($httpCode !== 200) {
                $errorDetails = json_encode([
                    'http_code' => $httpCode,
                    'response' => $responseData,
                    'request' => $data
                ]);
                $this->logError("API Error Response: $errorDetails");
                throw new Exception($responseData['message'] ?? "Erro na API (HTTP $httpCode)");
            }

            if (!isset($responseData['pixCopiaECola'])) {
                $this->logError("Invalid API Response: " . json_encode($responseData));
                throw new Exception($responseData['message'] ?? "Resposta inválida da API");
            }

            return $responseData;
        } catch (Exception $e) {
            $this->logError("Exception in callDigitopayAPI: " . $e->getMessage());
            throw $e;
        }
    }

    private function registrarPagamentoNoBanco($usuarioId, $valor, $codigoTransacao)
    {
        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO pagamentos 
                 (usuario_id, valor, status, codigo_transacao) 
                 VALUES (:usuario_id, :valor, 'pendente', :codigo_transacao)"
            );
            $stmt->bindParam(':usuario_id', $usuarioId);
            $stmt->bindParam(':valor', $valor);
            $stmt->bindParam(':codigo_transacao', $codigoTransacao);
            $stmt->execute();
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            throw new Exception("Erro ao registrar pagamento: " . $e->getMessage());
        }
    }

    private function validarCPF($cpf)
    {
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação do dígito verificador
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function logError($message)
    {
        // Verificar se o arquivo de log pode ser criado
        if (!file_exists($this->logFile)) {
            $logDir = dirname($this->logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            touch($this->logFile);
            chmod($this->logFile, 0644);
        }

        error_log("[" . date("Y-m-d H:i:s") . "] ERRO: " . $message . PHP_EOL, 3, $this->logFile);
    }
}
