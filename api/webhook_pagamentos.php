<?php
header('Content-Type: application/json');
$logDir = __DIR__ . '/../logs';
$logFile = $logDir . '/webhook_pagamentos.log';

// Configuração inicial
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

function logMessage($message)
{
    global $logFile;
    file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] " . $message . PHP_EOL, FILE_APPEND);
}

logMessage("=== INÍCIO DA REQUISIÇÃO ===");

// Verificar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    logMessage("Erro: Método " . $_SERVER['REQUEST_METHOD'] . " não permitido");
    http_response_code(405);
    exit(json_encode(['error' => 'Método não permitido']));
}

// Obter dados JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    logMessage("Erro ao decodificar JSON: " . json_last_error_msg());
    http_response_code(400);
    exit(json_encode(['error' => 'JSON inválido']));
}

logMessage("Dados recebidos do Digitopay: " . print_r($data, true));

// Verificar campos obrigatórios do Digitopay
if (!isset($data['id'], $data['status'])) {
    logMessage("Dados incompletos: id ou status faltando");
    http_response_code(400);
    exit(json_encode(['error' => 'Campos obrigatórios faltando']));
}

try {
    require_once __DIR__ . '/../conf/db_connection.php';
    $database = new Database();
    $conn = $database->getConnection();
    logMessage("Conexão com o banco de dados estabelecida");

    // Mapeamento de status do Digitopay
    $statusMapping = [
        'REALIZADO' => 'pago',
        'PENDENTE' => 'pendente',
        'CANCELADO' => 'cancelado'
    ];

    $status = $statusMapping[$data['status']] ?? 'pendente';

    // Atualizar pagamento usando o ID do Digitopay como codigo_transacao
    $stmt = $conn->prepare(
        "UPDATE pagamentos 
         SET status = :status 
         WHERE codigo_transacao = :codigo_transacao"
    );
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':codigo_transacao', $data['id']);
    $stmt->execute();

    logMessage("Status atualizado para: " . $status);

    // Se o status for 'pago', atualizar créditos do usuário
    if ($status === 'pago') {
        $stmt = $conn->prepare(
            "UPDATE usuarios 
             SET credito = credito + (SELECT valor FROM pagamentos WHERE codigo_transacao = :codigo_transacao) 
             WHERE id = (SELECT usuario_id FROM pagamentos WHERE codigo_transacao = :codigo_transacao)"
        );
        $stmt->bindParam(':codigo_transacao', $data['id']);
        $stmt->execute();

        logMessage("Créditos atualizados para transação: " . $data['id']);

        // Obter ID do usuário para redirecionamento
        $stmt = $conn->prepare("SELECT usuario_id FROM pagamentos WHERE codigo_transacao = :codigo_transacao");
        $stmt->bindParam(':codigo_transacao', $data['id']);
        $stmt->execute();
        $usuarioId = $stmt->fetchColumn();

        // Registrar redirecionamento
        logMessage("Usuário $usuarioId será redirecionado após pagamento confirmado");
    }

    // Resposta para o Digitopay
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Webhook processado com sucesso',
        'transaction_id' => $data['id'],
        'status' => $status
    ]);
} catch (PDOException $e) {
    logMessage("Erro no banco de dados: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro no processamento']);
} catch (Exception $e) {
    logMessage("Erro: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

logMessage("=== FIM DA REQUISIÇÃO ===");
