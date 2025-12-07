<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /govnex/login_usuario.php');
    exit;
}

require_once '../../conf/db_connection.php';
require_once '../../assets/header.php';

$database = new Database();
$conn = $database->getConnection();

// Verificar se é administrador
$stmt = $conn->prepare("SELECT nivel_acesso FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch();

if ($usuario['nivel_acesso'] !== 'administrador') {
    session_destroy();
    ob_clean();
    header('Location: /govnex/login_usuario.php');
    exit;
}

// Verificar se é administrador
$stmt = $conn->prepare("SELECT nivel_acesso FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch();

if ($usuario['nivel_acesso'] !== 'administrador') {
    header('Location: /govnex/views/dashboard/index.php');
    exit;
}

// Obter estatísticas
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM usuarios");
$stmt->execute();
$total_usuarios = $stmt->fetch()['total'];

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE dominio IS NOT NULL AND dominio != ''");
$stmt->execute();
$usuarios_vinculados = $stmt->fetch()['total'];
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Painel Administrativo</h1>
        <div class="flex space-x-4">
            <a href="/govnex/views/admin/gerenciar_usuarios.php" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Gerenciar Usuários
            </a>
            <a href="/govnex/views/admin/gerenciar_creditos.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                Gerenciar Créditos
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Total de Usuários</h3>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-gray-800"><?php echo $total_usuarios; ?></p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Usuários Vinculados</h3>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-gray-800"><?php echo $usuarios_vinculados; ?></p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Ações Rápidas</h3>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="space-y-3">
                <a href="/govnex/views/logs/log_consultas.php" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-sm font-medium text-gray-700">
                    Ver Logs de Consultas
                </a>
                <a href="/govnex/views/dashboard/status_api.php" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-sm font-medium text-gray-700">
                    Ver Status da API
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../assets/footer.php'; ?>