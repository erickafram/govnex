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

// Processar adição de créditos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $credito = $_POST['credito'];

    $stmt = $conn->prepare("UPDATE usuarios SET credito = credito + :credito WHERE id = :id");
    $stmt->bindParam(':credito', $credito);
    $stmt->bindParam(':id', $usuario_id);

    if ($stmt->execute()) {
        $sucesso = "Créditos adicionados com sucesso!";
    } else {
        $erro = "Erro ao adicionar créditos!";
    }
}

// Obter lista de usuários
$stmt = $conn->prepare("SELECT id, nome, email, credito FROM usuarios ORDER BY nome");
$stmt->execute();
$usuarios = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Gerenciar Créditos</h1>
        <a href="/govnex/views/admin/gerenciar_usuarios.php" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            ← Voltar para Usuários
        </a>
    </div>

    <?php if (isset($erro)): ?>
        <div class="p-4 mb-6 rounded-lg bg-red-100 text-red-700 border border-red-200"><?php echo $erro; ?></div>
    <?php endif; ?>

    <?php if (isset($sucesso)): ?>
        <div class="p-4 mb-6 rounded-lg bg-green-100 text-green-700 border border-green-200"><?php echo $sucesso; ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700">Lista de Usuários</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Créditos</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-800"><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td class="px-6 py-4 text-sm font-medium <?php echo $usuario['credito'] < 0.05 ? 'text-red-600' : 'text-green-600'; ?>">
                                R$ <?php echo number_format($usuario['credito'], 2, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    onclick="document.getElementById('modal-<?php echo $usuario['id']; ?>').classList.remove('hidden')"
                                    class="px-3 py-1.5 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                    Adicionar
                                </button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div id="modal-<?php echo $usuario['id']; ?>"
                            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-xl w-full max-w-md">
                                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="text-lg font-semibold">Adicionar Créditos</h3>
                                    <button onclick="document.getElementById('modal-<?php echo $usuario['id']; ?>').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <form method="POST">
                                    <div class="p-6 space-y-4">
                                        <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">R$</span>
                                                <input
                                                    type="number"
                                                    name="credito"
                                                    step="0.01"
                                                    min="0"
                                                    required
                                                    class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="Ex: 100.00"
                                                    x-model="valor"
                                                    @input="validarValor">
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1" x-show="valor > 0">
                                                Valor digitado: R$ <span x-text="new Intl.NumberFormat('pt-BR', {minimumFractionDigits: 2}).format(valor)"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                                        <button
                                            type="button"
                                            @click="openModal = false"
                                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                                            Cancelar
                                        </button>
                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                            Salvar Alterações
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php require_once '../../assets/footer.php'; ?>