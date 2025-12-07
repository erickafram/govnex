<?php
require_once __DIR__ . '../../conf/db_connection.php';

// Configurações de CORS
$allowedOrigins = [
    'https://govnex.site',
    'https://infovisa.gurupi.to.gov.br'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Verificar token de autenticação
if (!isset($_GET['token'])) {
    http_response_code(401);
    echo json_encode(["error" => "Token de autenticação necessário"]);
    exit;
}

$token = $_GET['token'];

// Validar token
try {
    $database = new Database();
    $conn = $database->getConnection();

    $stmt = $conn->prepare("SELECT * FROM api_tokens WHERE token = :token AND is_active = TRUE");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        http_response_code(403);
        echo json_encode(["error" => "Token inválido ou expirado"]);
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao validar token"]);
    exit;
}

// Verificar se o identificador foi fornecido (CNPJ, CPF ou ID)
if (!isset($_GET['cnpj']) && !isset($_GET['cpf']) && !isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "É necessário fornecer um identificador (CNPJ, CPF ou ID)"]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Preparar a consulta com base no parâmetro fornecido
    if (isset($_GET['cnpj'])) {
        $identificador = $_GET['cnpj'];
        $stmt = $conn->prepare("SELECT id, nome, cnpj, credito FROM usuarios WHERE cnpj = :identificador");
    } elseif (isset($_GET['cpf'])) {
        $identificador = $_GET['cpf'];
        $stmt = $conn->prepare("SELECT id, nome, cpf, credito FROM usuarios WHERE cpf = :identificador");
    } else {
        $identificador = $_GET['id'];
        $stmt = $conn->prepare("SELECT id, nome, cnpj, cpf, credito FROM usuarios WHERE id = :identificador");
    }
    
    $stmt->bindParam(':identificador', $identificador);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["error" => "Usuário não encontrado"]);
        exit;
    }
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Registrar a consulta de saldo
    $dominio = 'Desconhecido';
    if (!empty($_SERVER['HTTP_ORIGIN'])) {
        $dominio = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);
    } elseif (!empty($_SERVER['HTTP_REFERER'])) {
        $dominio = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $dominio = $_SERVER['REMOTE_ADDR'];
    }
    
    // Registrar log da consulta de saldo (opcional)
    $stmt = $conn->prepare(
        "INSERT INTO consultas_log (cnpj_consultado, dominio_origem, custo) 
         VALUES (:cnpj_consultado, :dominio, 0.00)"
    );
    // Usamos o CNPJ do usuário ou o ID se não tiver CNPJ
    $cnpj_para_log = isset($usuario['cnpj']) ? $usuario['cnpj'] : 'ID-'.$usuario['id'];
    $stmt->bindParam(':cnpj_consultado', $cnpj_para_log);
    $stmt->bindParam(':dominio', $dominio);
    $stmt->execute();
    
    // Preparar resposta
    $resposta = [
        "status" => "success",
        "usuario" => [
            "id" => $usuario['id'],
            "nome" => $usuario['nome']
        ],
        "saldo" => [
            "credito" => floatval($usuario['credito']),
            "moeda" => "BRL",
            "formato" => number_format($usuario['credito'], 2, ',', '.')
        ]
    ];
    
    // Adicionar identificadores à resposta
    if (isset($usuario['cnpj'])) {
        $resposta['usuario']['cnpj'] = $usuario['cnpj'];
    }
    if (isset($usuario['cpf'])) {
        $resposta['usuario']['cpf'] = $usuario['cpf'];
    }
    
    // Retornar resposta
    header('Content-Type: application/json');
    echo json_encode($resposta);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao consultar saldo: " . $e->getMessage()]);
    exit;
}
