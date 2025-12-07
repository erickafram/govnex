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

// Obter todos os pagamentos do usuário
$stmt = $conn->prepare(
    "SELECT * FROM pagamentos
    WHERE usuario_id = :usuario_id
    ORDER BY data_criacao DESC"
);
$stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stmt->execute();
$todosPagamentos = $stmt->fetchAll();
?>

<div class="container mx-auto mt-5 px-4">
    <h1 class="mb-4 text-center text-2xl font-bold">Histórico de Pagamentos</h1>

    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-lg shadow-sm mb-4 p-6">
                <h5 class="text-lg font-semibold mb-4">Todos os Pagamentos</h5>
                <?php if (count($todosPagamentos) > 0): ?>
                    <div class="overflow-x-auto">
                        <div class="min-w-full divide-y divide-gray-200">
                            <div class="bg-gray-50">
                                <div class="grid grid-cols-4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div>Data</div>
                                    <div>Valor</div>
                                    <div>Status</div>
                                    <div>Código</div>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-200">
                                <?php foreach ($todosPagamentos as $pagamento): ?>
                                    <div class="grid grid-cols-4 px-6 py-4 whitespace-nowrap text-sm text-gray-900 hover:bg-gray-50">
                                        <div><?= date('d/m/Y H:i', strtotime($pagamento['data_criacao'])) ?></div>
                                        <div>R$ <?= number_format($pagamento['valor'], 2, ',', '.') ?></div>
                                        <div>
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                                <?= $pagamento['status'] === 'pago' ? 'bg-green-100 text-green-800' : ($pagamento['status'] === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst($pagamento['status']) ?>
                                            </span>
                                        </div>
                                        <div><small><?= substr($pagamento['codigo_transacao'], 0, 8) ?>...</small></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">Nenhum pagamento realizado ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../assets/footer.php'; ?>