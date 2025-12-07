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

// Obter dados do usuário
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch();
?>

<div class="container mx-auto mt-6 px-4 md:px-6 pb-16 md:pb-10">
    <div class="flex flex-col items-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Pagamento por Boleto</h1>
        <div class="w-20 h-1 bg-primary rounded-full mb-6"></div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 justify-center">
        <div class="w-full lg:w-2/3 xl:w-1/2">
            <!-- Card de Boleto -->
            <div class="bg-white rounded-xl shadow-card mb-6 overflow-hidden">
                <div class="bg-gradient-to-r from-primary/10 to-white p-5 border-b border-gray-100">
                    <h5 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Dados do Boleto
                    </h5>
                </div>
                
                <div class="p-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-md shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">Pague o boleto bancário até a data de vencimento em qualquer banco ou lotérica.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800">Informações do Pagamento</h3>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">Aguardando pagamento</span>
                        </div>
                        
                        <div class="flex justify-between mb-3 pb-3 border-b border-gray-100">
                            <span class="text-gray-500">Valor:</span>
                            <span class="font-medium">R$ 1.500,00</span>
                        </div>
                        
                        <div class="flex justify-between mb-3 pb-3 border-b border-gray-100">
                            <span class="text-gray-500">Pagador:</span>
                            <span class="font-medium">Fundo Municipal de Saúde de Gurupi</span>
                        </div>
                        
                        <div class="flex justify-between mb-3 pb-3 border-b border-gray-100">
                            <span class="text-gray-500">Vencimento:</span>
                            <span class="font-medium">02/05/2025</span>
                        </div>
                        
                        <div class="flex justify-between mb-3 pb-3 border-b border-gray-100">
                            <span class="text-gray-500">Beneficiário:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($usuario['nome']); ?></span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-5 rounded-lg mb-6">
                        <h4 class="text-sm font-semibold mb-4 text-gray-700">Código do Boleto</h4>
                        <div class="flex rounded-md shadow-sm">
                            <input type="text" 
                                class="flex-1 min-w-0 block w-full px-3 py-3 bg-white border border-r-0 border-gray-300 rounded-l-md focus:ring-primary focus:border-primary sm:text-sm font-mono"
                                id="boleto-code" value="26091.00686 72000.268176 64300.000003 6 10690000150000" readonly>
                            <button type="button" id="copy-boleto-btn"
                                class="inline-flex items-center px-4 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Clique no botão para copiar o código do boleto</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="https://nubank.com.br/cobranca/VPCUQFD1wlgewrv2" 
                            target="_blank" 
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 border border-transparent rounded-lg font-medium text-white shadow-sm hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Abrir Boleto Online
                        </a>
                        
                        <a href="https://nubank.com.br/cobranca/VPCUQFD1wlgewrv2" 
                            target="_blank" 
                            class="flex-1 px-4 py-3 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Baixar PDF do Boleto
                        </a>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-blue-500 mr-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">O prazo de compensação do boleto é de até 3 dias úteis após o pagamento.</p>
                    </div>
                </div>
            </div>
            
            <!-- Instruções -->
            <div class="bg-white rounded-xl shadow-card mb-6 overflow-hidden">
                <div class="bg-gradient-to-r from-primary/10 to-white p-5 border-b border-gray-100">
                    <h5 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Como pagar
                    </h5>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 bg-blue-100 text-blue-700 h-7 w-7 rounded-full flex items-center justify-center font-semibold">1</div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Internet Banking ou Aplicativo</h4>
                                <p class="text-gray-600 text-sm">Acesse seu Internet Banking ou aplicativo do seu banco, localize a opção "Pagamento de Boletos" e digite o código de barras ou escaneie o código.</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 bg-blue-100 text-blue-700 h-7 w-7 rounded-full flex items-center justify-center font-semibold">2</div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Agências Bancárias ou Lotéricas</h4>
                                <p class="text-gray-600 text-sm">Imprima o boleto e efetue o pagamento em qualquer agência bancária ou casa lotérica até a data de vencimento.</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 bg-blue-100 text-blue-700 h-7 w-7 rounded-full flex items-center justify-center font-semibold">3</div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Após o Pagamento</h4>
                                <p class="text-gray-600 text-sm">Após a confirmação do pagamento, que pode levar até 3 dias úteis, seus créditos serão liberados automaticamente no sistema.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botão voltar -->
            <div class="flex justify-center">
                <a href="/govnex/views/pagamentos/index.php" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar para Pagamentos
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../assets/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Adicionar funcionalidade de copiar código do boleto
        const copyBoletoBtn = document.getElementById('copy-boleto-btn');
        if (copyBoletoBtn) {
            copyBoletoBtn.addEventListener('click', function() {
                const boletoCode = document.getElementById('boleto-code');
                boletoCode.select();
                boletoCode.setSelectionRange(0, 99999);
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
        }
    });
</script> 