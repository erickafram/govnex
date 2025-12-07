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

// Verificar se há pagamentos confirmados para este usuário
$stmt = $conn->prepare(
    "SELECT * FROM pagamentos
    WHERE usuario_id = :usuario_id
    AND status = 'pago'
    ORDER BY data_atualizacao DESC
    LIMIT 1"
);
$stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stmt->execute();
$pagamentoConfirmado = $stmt->fetch();

// Obter dados do usuário
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch();

// Obter últimos 5 pagamentos do usuário
$stmt = $conn->prepare(
    "SELECT * FROM pagamentos
    WHERE usuario_id = :usuario_id
    ORDER BY data_criacao DESC
    LIMIT 5"
);
$stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stmt->execute();
$ultimosPagamentos = $stmt->fetchAll();

// Processar solicitação de pagamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../api/Pagamento.php';

    $valor = (float)$_POST['valor'];
    if ($valor < 200.00) { // Alterado para 200.00
        $erro = "O valor mínimo para recarga é R$ 200,00";
    } else {
        $pagamento = new Pagamento();
        $resultado = $pagamento->criarPagamento(
            $usuario['id'],
            $valor,
            [
                'cpf' => $usuario['cpf'],
                'nome' => $usuario['nome']
            ]
        );

        if ($resultado) {
            $sucesso = "Pagamento criado com sucesso!";
            $qrCode = $resultado['qr_code'];
            $codigoTransacao = $resultado['codigo_transacao'];

            // Atualizar a lista de pagamentos após novo pagamento
            $stmt->execute();
            $ultimosPagamentos = $stmt->fetchAll();
        } else {
            $erro = "Erro ao processar pagamento";
        }
    }
}
?>

<div class="container mx-auto mt-6 px-4 md:px-6 pb-16 md:pb-10">
    <div class="flex flex-col items-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Recarregar Créditos</h1>
        <div class="w-20 h-1 bg-primary rounded-full mb-6"></div>
    </div>

    <?php if (isset($erro)): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-md shadow-sm animate-fadeIn">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-700"><?php echo $erro; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($sucesso)): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-md shadow-sm animate-fadeIn">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-700"><?php echo $sucesso; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex flex-col lg:flex-row gap-6 justify-center">
        <div class="w-full lg:w-1/2 xl:w-2/5">
            <!-- Card de Recarga -->
            <div class="bg-white rounded-xl shadow-card mb-6 overflow-hidden">
                <div class="bg-gradient-to-r from-primary/10 to-white p-5 border-b border-gray-100">
                    <h5 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Realizar Recarga
                    </h5>
                </div>
                <div class="p-6">
                    <form method="POST" class="space-y-5">
                        <div>
                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">R$</span>
                                </div>
                                <input type="number" 
                                    class="pl-10 w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                    id="valor" name="valor" min="200" step="1" value=200" required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Valor mínimo: R$ 200,00
                            </p>
                        </div>
                        <div class="pt-2">
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 border border-transparent rounded-lg font-medium text-white shadow-sm hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
    <div class="flex justify-center items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
        </svg>
        Gerar QR Code
    </div>
</button>

                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600">Ao gerar o QR Code, você poderá realizar o pagamento via PIX.</p>
                </div>
            </div>

            <!-- Card de Histórico de Pagamentos -->
            <div class="bg-white rounded-xl shadow-card overflow-hidden" id="historico-pagamentos">
                <div class="bg-gradient-to-r from-primary/10 to-white p-5 border-b border-gray-100">
                    <h5 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Seus Pagamentos
                    </h5>
                </div>
                
                <?php if (count($ultimosPagamentos) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($ultimosPagamentos as $pagamento): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('d/m/Y H:i', strtotime($pagamento['data_criacao'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            R$ <?= number_format($pagamento['valor'], 2, ',', '.') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= $pagamento['status'] === 'pago' ? 'bg-green-100 text-green-800' : 
                                                   ($pagamento['status'] === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-red-100 text-red-800') ?>">
                                                <?= ucfirst($pagamento['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-mono"><?= substr($pagamento['codigo_transacao'], 0, 8) ?>...</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 mb-2">Nenhum pagamento realizado ainda.</p>
                        <p class="text-sm text-gray-400">Seus pagamentos aparecerão aqui após a primeira transação.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($qrCode)): ?>
            <!-- Card de QR Code -->
            <div class="w-full lg:w-1/2 xl:w-2/5 animate-fadeIn" id="qrcode-container">
                <div class="bg-white rounded-xl shadow-card mb-4 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary/10 to-white p-5 border-b border-gray-100">
                        <h5 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            QR Code PIX
                        </h5>
                    </div>
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <div class="qr-code-wrapper p-3 border-2 border-dashed border-gray-200 rounded-lg bg-white inline-block">
                                <img src="<?php echo $qrCode; ?>" alt="QR Code PIX" class="mx-auto max-w-[220px]">
                            </div>
                        </div>
                        
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="text-xl font-bold text-gray-800 mb-1">R$ <?= number_format($valor, 2, ',', '.') ?></div>
                            <div class="text-sm text-gray-500 mb-1">Código da transação:</div>
                            <div class="text-xs font-mono bg-white p-2 rounded border border-gray-200"><?php echo $codigoTransacao; ?></div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="pix-code" class="block text-sm font-medium text-gray-700 mb-2">Código PIX Copia e Cola</label>
                            <div class="flex rounded-md shadow-sm">
                                <input type="text" 
                                    class="flex-1 min-w-0 block w-full px-3 py-3 bg-gray-50 border border-r-0 border-gray-300 rounded-l-md focus:ring-primary focus:border-primary sm:text-sm font-mono"
                                    id="pix-code" value="<?php echo $resultado['pix_copia_cola']; ?>" readonly>
                                <button type="button" id="copy-pix-btn"
                                    class="inline-flex items-center px-4 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Clique no botão para copiar o código PIX</p>
                        </div>
                        
                        <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r text-left">
                            <div class="flex">
                                <div class="flex-shrink-0 w-5 h-5 text-blue-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Estamos aguardando seu pagamento. Após a confirmação, seus créditos serão adicionados automaticamente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="relative w-2 h-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </div>
                            <p class="text-sm text-gray-600">Verificando pagamento automaticamente...</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../assets/footer.php'; ?>

<script>
    // Adicionar animações personalizadas ao Tailwind
    tailwind.config.theme = {
        ...tailwind.config.theme,
        extend: {
            ...tailwind.config.theme.extend,
            animation: {
                fadeIn: 'fadeIn 0.5s ease-in-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                }
            }
        }
    };

    // Função para verificar o status do pagamento
    function verificarStatusPagamento(codigoTransacao) {
        fetch('/govnex/api/verificar_pagamento.php?codigo_transacao=' + codigoTransacao)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'pago') {
                    // Criar uma notificação toast em vez de um alert
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fadeIn';
                    toast.innerHTML = `
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Pagamento confirmado! Redirecionando...</span>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    // Redirecionar após 2 segundos
                    setTimeout(() => {
                        window.location.href = '/govnex/views/dashboard/index.php';
                    }, 2000);
                } else if (data.status === 'pendente') {
                    // Se pendente, verifica novamente após 5 segundos
                    setTimeout(() => verificarStatusPagamento(codigoTransacao), 5000);
                } else if (data.status === 'erro') {
                    console.error('Erro ao verificar pagamento:', data.mensagem);
                    // Tenta novamente após 10 segundos em caso de erro
                    setTimeout(() => verificarStatusPagamento(codigoTransacao), 10000);
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                // Tenta novamente após 10 segundos em caso de falha na requisição
                setTimeout(() => verificarStatusPagamento(codigoTransacao), 10000);
            });
    }

    // Verifica se o código de transação está presente e inicia a verificação
    <?php if (isset($codigoTransacao)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            verificarStatusPagamento("<?php echo $codigoTransacao; ?>");

            // Adiciona funcionalidade de copiar código PIX
            document.getElementById('copy-pix-btn').addEventListener('click', function() {
                const pixCode = document.getElementById('pix-code');
                pixCode.select();
                pixCode.setSelectionRange(0, 99999);
                document.execCommand('copy');

                // Feedback visual
                this.classList.add('bg-green-100');
                this.innerHTML = `
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                
                setTimeout(() => {
                    this.classList.remove('bg-green-100');
                    this.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                        </svg>
                    `;
                }, 2000);
            });
        });
    <?php endif; ?>
</script>