<?php
require_once __DIR__ . '../../conf/db_connection.php';

// Configurações de CORS
$allowedOrigins = [
    'https://govnex.site',
    'https://infovisa.gurupi.to.gov.br',
    'http://infovisa.gurupi.to.gov.br',
    'http://localhost',
    'https://localhost'
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

// Verificar se o CNPJ foi fornecido
if (!isset($_GET['cnpj'])) {
    http_response_code(400);
    echo json_encode(["error" => "CNPJ não fornecido"]);
    exit;
}

$cnpj = $_GET['cnpj'];

// Validar formato do CNPJ
if (!preg_match('/^\d{14}$/', $cnpj)) {
    http_response_code(400);
    echo json_encode(["error" => "CNPJ inválido"]);
    exit;
}

// Registrar a consulta e verificar créditos
try {
    $database = new Database();
    $conn = $database->getConnection();

    // Capturar domínio de origem
    $dominio = 'Desconhecido';
    if (!empty($_SERVER['HTTP_ORIGIN'])) {
        $dominio = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);
    } elseif (!empty($_SERVER['HTTP_REFERER'])) {
        $dominio = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $dominio = $_SERVER['REMOTE_ADDR'];
    }
    
    // Log para debug
    error_log("API GovNex - Domínio capturado: {$dominio}");
    error_log("API GovNex - HTTP_ORIGIN: " . ($_SERVER['HTTP_ORIGIN'] ?? 'não definido'));
    error_log("API GovNex - HTTP_REFERER: " . ($_SERVER['HTTP_REFERER'] ?? 'não definido'));

    // Obter usuário vinculado ao domínio
    $stmt = $conn->prepare("SELECT id, credito FROM usuarios WHERE dominio = :dominio");
    $stmt->bindParam(':dominio', $dominio);
    $stmt->execute();
    $usuario = $stmt->fetch();

    if (!$usuario) {
        http_response_code(402);
        error_log("API GovNex - Usuário não encontrado para o domínio: {$dominio}");
        echo json_encode([
            "error" => "Créditos insuficientes para realizar a consulta",
            "details" => "Domínio não cadastrado: {$dominio}"
        ]);
        exit;
    }
    
    if ($usuario['credito'] < 0.05) {
        http_response_code(402);
        error_log("API GovNex - Créditos insuficientes. Domínio: {$dominio}, Crédito atual: {$usuario['credito']}");
        echo json_encode([
            "error" => "Créditos insuficientes para realizar a consulta",
            "details" => "Saldo atual: R$ " . number_format($usuario['credito'], 2, ',', '.')
        ]);
        exit;
    }

    // Iniciar transação
    $conn->beginTransaction();

    try {
        // Registrar a consulta
        $stmt = $conn->prepare(
            "INSERT INTO consultas_log (cnpj_consultado, dominio_origem, custo) 
                 VALUES (:cnpj, :dominio, 0.05)"
        );
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':dominio', $dominio);
        $stmt->execute();

        // Atualizar créditos do usuário e verificar se foi atualizado
        $stmt = $conn->prepare(
            "UPDATE usuarios SET credito = credito - 0.05 
                 WHERE id = :usuario_id AND credito >= 0.05"
        );
        $stmt->bindParam(':usuario_id', $usuario['id']);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            throw new Exception("Falha ao atualizar créditos");
        }

        // Commit da transação
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
} catch (PDOException $e) {
    error_log("Erro ao processar consulta: " . $e->getMessage());
}

// Consultar API externa
$apiUrl = "https://minhareceita.org/{$cnpj}";
#$apiUrl = "http://167.99.153.48:8000/{$cnpj}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(["error" => "Erro ao acessar a API: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Retornar resposta
header('Content-Type: application/json');
echo $response;
