<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /govnex/login_usuario.php');
    exit;
}

require_once __DIR__ . '/../../assets/header.php';
?>

<div class="container mx-auto mt-4 px-4">
    <h1 class="text-2xl font-bold mb-4">Status da API</h1>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h5 class="text-lg font-semibold mb-2">Monitoramento</h5>
        <p class="text-gray-600 mb-4">Verifique o status dos serviços da API:</p>

        <div class="space-y-2">
            <div class="bg-gray-50 p-3 rounded">
                <div class="flex justify-between items-center">
                    <span>API de CNPJ</span>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Online</span>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <div class="flex justify-between items-center">
                    <span>API de Pagamentos</span>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Online</span>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded">
                <div class="flex justify-between items-center">
                    <span>Webhook de Pagamentos</span>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Online</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../assets/footer.php';
?>