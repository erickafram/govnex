<?php
require_once __DIR__ . '../../conf/db_connection.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Gerar token único
    $token = bin2hex(random_bytes(32));

    // Inserir token no banco
    $stmt = $conn->prepare("INSERT INTO api_tokens (token) VALUES (:token)");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    echo json_encode(["token" => $token]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao gerar token"]);
}
