<?php
// verificar_pagamento.php

// Definir cabeçalho JSON antes de qualquer output
header('Content-Type: application/json');

// Inclui o arquivo de configuração do banco de dados
require_once '../conf/db_connection.php';

try {
    // Cria uma nova conexão com o banco de dados
    $database = new Database();
    $conn = $database->getConnection();

    // Verifica se o código de transação foi enviado
    if (!isset($_GET['codigo_transacao'])) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Código de transação não fornecido'
        ]);
        exit;
    }

    $codigoTransacao = $_GET['codigo_transacao'];

    // Prepara a consulta para buscar o pagamento pelo código de transação
    $stmt = $conn->prepare("SELECT status FROM pagamentos WHERE codigo_transacao = :codigo_transacao");
    $stmt->bindParam(':codigo_transacao', $codigoTransacao);

    if (!$stmt->execute()) {
        throw new Exception("Erro ao executar consulta no banco de dados");
    }

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        // Retorna o status do pagamento como JSON no formato esperado pelo frontend
        echo json_encode([
            'status' => $resultado['status'],
            'mensagem' => $resultado['status'] === 'pago'
                ? 'Pagamento confirmado'
                : 'Pagamento pendente'
        ]);
    } else {
        // Se o pagamento não foi encontrado, retorna um erro
        http_response_code(404); // Not Found
        echo json_encode([
            'status' => 'erro',
            'mensagem' => 'Pagamento não encontrado'
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Erro no banco de dados',
        'detalhes' => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'erro',
        'mensagem' => $e->getMessage()
    ]);
}
