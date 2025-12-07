<?php
session_start();
// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['usuario_id'])) {
    echo '<div class="text-red-500">Acesso negado. Você precisa estar logado como administrador.</div>';
    exit;
}

// Inclui arquivo de conexão com o banco de dados
require_once '../../conf/db_connection.php';

// Cria uma nova conexão com o banco de dados
$database = new Database();
$conn = $database->getConnection();

// Verifica se o usuário é administrador
$stmt = $conn->prepare("SELECT nivel_acesso FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch();

if ($usuario['nivel_acesso'] !== 'administrador') {
    echo '<div class="text-red-500">Acesso negado. Você precisa ser administrador para visualizar estes detalhes.</div>';
    exit;
}

// Verifica se o ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="text-red-500">ID do formulário não fornecido.</div>';
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Busca os detalhes do formulário
$stmt = $conn->prepare("SELECT * FROM formularios_interesse WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo '<div class="text-red-500">Formulário não encontrado.</div>';
    exit;
}

$form = $stmt->fetch();

// Formata o status para exibição
$status_classes = [
    'pendente' => 'bg-yellow-100 text-yellow-800',
    'contatado' => 'bg-blue-100 text-blue-800',
    'aprovado' => 'bg-green-100 text-green-800',
    'recusado' => 'bg-red-100 text-red-800'
];
$status_class = $status_classes[$form['status']] ?? 'bg-gray-100 text-gray-800';
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <h4 class="text-lg font-semibold text-gray-800 mb-3">Informações Pessoais</h4>
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="mb-3">
                <p class="text-sm font-medium text-gray-500">Nome</p>
                <p class="text-base text-gray-900"><?php echo htmlspecialchars($form['nome']); ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm font-medium text-gray-500">Email</p>
                <p class="text-base text-gray-900"><?php echo htmlspecialchars($form['email']); ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm font-medium text-gray-500">Telefone</p>
                <p class="text-base text-gray-900"><?php echo htmlspecialchars($form['telefone']); ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm font-medium text-gray-500">Município/UF</p>
                <p class="text-base text-gray-900"><?php echo htmlspecialchars($form['municipio']); ?>/<?php echo htmlspecialchars($form['estado']); ?></p>
            </div>
        </div>
    </div>
    
    <div>
        <h4 class="text-lg font-semibold text-gray-800 mb-3">Dados da Vigilância Sanitária</h4>
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="mb-3">
                <p class="text-sm font-medium text-gray-500">Quantidade de Funcionários</p>
                <p class="text-base text-gray-900"><?php echo htmlspecialchars($form['qtd_funcionarios']); ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm font-medium text-gray-500">Quantidade de Estabelecimentos</p>
                <p class="text-base text-gray-900"><?php echo htmlspecialchars($form['qtd_estabelecimentos']); ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm font-medium text-gray-500">Data de Cadastro</p>
                <p class="text-base text-gray-900"><?php echo date('d/m/Y H:i', strtotime($form['data_cadastro'])); ?></p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status</p>
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>">
                    <?php echo ucfirst($form['status']); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($form['observacoes'])): ?>
<div class="mt-6">
    <h4 class="text-lg font-semibold text-gray-800 mb-3">Observações</h4>
    <div class="bg-gray-50 rounded-lg p-4">
        <p class="text-base text-gray-900 whitespace-pre-line"><?php echo htmlspecialchars($form['observacoes']); ?></p>
    </div>
</div>
<?php endif; ?>

<div class="mt-6 border-t border-gray-200 pt-6">
    <div class="flex justify-between items-center">
        <div>
            <a href="mailto:<?php echo htmlspecialchars($form['email']); ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-envelope mr-2"></i> Enviar Email
            </a>
            <a href="tel:<?php echo htmlspecialchars(preg_replace('/[^0-9]/', '', $form['telefone'])); ?>" class="ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-phone mr-2"></i> Ligar
            </a>
        </div>
        <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-md hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" onclick="abrirModalStatus(<?php echo $form['id']; ?>, '<?php echo $form['status']; ?>')">
            <i class="fas fa-edit mr-2"></i> Alterar Status
        </button>
    </div>
</div>
