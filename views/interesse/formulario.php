<?php
// Iniciar a sessão se ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir arquivos necessários
require_once '../../conf/db_connection.php';

// Criar uma nova conexão com o banco de dados
$database = new Database();
$conn = $database->getConnection();

// Processar o formulário quando enviado
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar e sanitizar os dados do formulário
    $nome = filter_input(INPUT_POST, 'nome', FILTER_UNSAFE_RAW);
    $nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_UNSAFE_RAW);
    $telefone = htmlspecialchars($telefone, ENT_QUOTES, 'UTF-8');
    
    $municipio = filter_input(INPUT_POST, 'municipio', FILTER_UNSAFE_RAW);
    $municipio = htmlspecialchars($municipio, ENT_QUOTES, 'UTF-8');
    
    $estado = filter_input(INPUT_POST, 'estado', FILTER_UNSAFE_RAW);
    $estado = htmlspecialchars($estado, ENT_QUOTES, 'UTF-8');
    
    $qtd_funcionarios = filter_input(INPUT_POST, 'qtd_funcionarios', FILTER_SANITIZE_NUMBER_INT);
    $qtd_estabelecimentos = filter_input(INPUT_POST, 'qtd_estabelecimentos', FILTER_SANITIZE_NUMBER_INT);
    
    $observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_UNSAFE_RAW);
    $observacoes = htmlspecialchars($observacoes, ENT_QUOTES, 'UTF-8');

    // Validar campos obrigatórios
    if (empty($nome) || empty($email) || empty($telefone) || empty($municipio) || empty($estado) || empty($qtd_funcionarios) || empty($qtd_estabelecimentos)) {
        $mensagem = 'Por favor, preencha todos os campos obrigatórios.';
        $tipo_mensagem = 'erro';
    } else {
        // Verificar se o email já está cadastrado
        $stmt = $conn->prepare("SELECT id FROM formularios_interesse WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $mensagem = 'Este email já está cadastrado em nosso sistema.';
            $tipo_mensagem = 'erro';
        } else {
            // Inserir os dados no banco de dados
            $stmt = $conn->prepare("INSERT INTO formularios_interesse (nome, email, telefone, municipio, estado, qtd_funcionarios, qtd_estabelecimentos, observacoes) VALUES (:nome, :email, :telefone, :municipio, :estado, :qtd_funcionarios, :qtd_estabelecimentos, :observacoes)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':municipio', $municipio);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':qtd_funcionarios', $qtd_funcionarios);
            $stmt->bindParam(':qtd_estabelecimentos', $qtd_estabelecimentos);
            $stmt->bindParam(':observacoes', $observacoes);
            
            if ($stmt->execute()) {
                $mensagem = 'Seu interesse foi registrado com sucesso! Em breve entraremos em contato.';
                $tipo_mensagem = 'sucesso';
                
                // Limpar os campos do formulário após o envio bem-sucedido
                $nome = $email = $telefone = $municipio = $estado = $qtd_funcionarios = $qtd_estabelecimentos = $observacoes = '';
            } else {
                $mensagem = 'Ocorreu um erro ao registrar seu interesse. Por favor, tente novamente mais tarde.';
                $tipo_mensagem = 'erro';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Interesse - GovNex</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jQuery e jQuery Mask -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold">GovNex</h1>
                        <p class="text-blue-100">Soluções para Vigilância Sanitária</p>
                    </div>
                    <div>
                        <a href="/govnex/index.php" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-100 transition duration-300">
                            <i class="fas fa-home mr-1"></i> Voltar ao Início
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white py-4 px-6">
                    <h2 class="text-2xl font-bold">Formulário de Interesse</h2>
                    <p class="text-blue-100">Preencha o formulário abaixo para demonstrar interesse em utilizar o sistema GovNex</p>
                </div>
                
                <?php if (!empty($mensagem)): ?>
                    <div class="p-4 <?php echo $tipo_mensagem === 'sucesso' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-md mb-4 mx-6 mt-4">
                        <div class="flex items-center">
                            <i class="<?php echo $tipo_mensagem === 'sucesso' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500'; ?> mr-2"></i>
                            <?php echo $mensagem; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nome" class="block text-gray-700 font-medium mb-2">Nome Completo *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" id="nome" name="nome" value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="telefone" class="block text-gray-700 font-medium mb-2">Telefone *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="text" id="telefone" name="telefone" value="<?php echo isset($telefone) ? htmlspecialchars($telefone) : ''; ?>" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="municipio" class="block text-gray-700 font-medium mb-2">Município *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-city text-gray-400"></i>
                                </div>
                                <input type="text" id="municipio" name="municipio" value="<?php echo isset($municipio) ? htmlspecialchars($municipio) : ''; ?>" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="estado" class="block text-gray-700 font-medium mb-2">Estado (UF) *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                </div>
                                <select id="estado" name="estado" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                                    <option value="">Selecione...</option>
                                    <option value="AC" <?php echo (isset($estado) && $estado === 'AC') ? 'selected' : ''; ?>>Acre</option>
                                    <option value="AL" <?php echo (isset($estado) && $estado === 'AL') ? 'selected' : ''; ?>>Alagoas</option>
                                    <option value="AP" <?php echo (isset($estado) && $estado === 'AP') ? 'selected' : ''; ?>>Amapá</option>
                                    <option value="AM" <?php echo (isset($estado) && $estado === 'AM') ? 'selected' : ''; ?>>Amazonas</option>
                                    <option value="BA" <?php echo (isset($estado) && $estado === 'BA') ? 'selected' : ''; ?>>Bahia</option>
                                    <option value="CE" <?php echo (isset($estado) && $estado === 'CE') ? 'selected' : ''; ?>>Ceará</option>
                                    <option value="DF" <?php echo (isset($estado) && $estado === 'DF') ? 'selected' : ''; ?>>Distrito Federal</option>
                                    <option value="ES" <?php echo (isset($estado) && $estado === 'ES') ? 'selected' : ''; ?>>Espírito Santo</option>
                                    <option value="GO" <?php echo (isset($estado) && $estado === 'GO') ? 'selected' : ''; ?>>Goiás</option>
                                    <option value="MA" <?php echo (isset($estado) && $estado === 'MA') ? 'selected' : ''; ?>>Maranhão</option>
                                    <option value="MT" <?php echo (isset($estado) && $estado === 'MT') ? 'selected' : ''; ?>>Mato Grosso</option>
                                    <option value="MS" <?php echo (isset($estado) && $estado === 'MS') ? 'selected' : ''; ?>>Mato Grosso do Sul</option>
                                    <option value="MG" <?php echo (isset($estado) && $estado === 'MG') ? 'selected' : ''; ?>>Minas Gerais</option>
                                    <option value="PA" <?php echo (isset($estado) && $estado === 'PA') ? 'selected' : ''; ?>>Pará</option>
                                    <option value="PB" <?php echo (isset($estado) && $estado === 'PB') ? 'selected' : ''; ?>>Paraíba</option>
                                    <option value="PR" <?php echo (isset($estado) && $estado === 'PR') ? 'selected' : ''; ?>>Paraná</option>
                                    <option value="PE" <?php echo (isset($estado) && $estado === 'PE') ? 'selected' : ''; ?>>Pernambuco</option>
                                    <option value="PI" <?php echo (isset($estado) && $estado === 'PI') ? 'selected' : ''; ?>>Piauí</option>
                                    <option value="RJ" <?php echo (isset($estado) && $estado === 'RJ') ? 'selected' : ''; ?>>Rio de Janeiro</option>
                                    <option value="RN" <?php echo (isset($estado) && $estado === 'RN') ? 'selected' : ''; ?>>Rio Grande do Norte</option>
                                    <option value="RS" <?php echo (isset($estado) && $estado === 'RS') ? 'selected' : ''; ?>>Rio Grande do Sul</option>
                                    <option value="RO" <?php echo (isset($estado) && $estado === 'RO') ? 'selected' : ''; ?>>Rondônia</option>
                                    <option value="RR" <?php echo (isset($estado) && $estado === 'RR') ? 'selected' : ''; ?>>Roraima</option>
                                    <option value="SC" <?php echo (isset($estado) && $estado === 'SC') ? 'selected' : ''; ?>>Santa Catarina</option>
                                    <option value="SP" <?php echo (isset($estado) && $estado === 'SP') ? 'selected' : ''; ?>>São Paulo</option>
                                    <option value="SE" <?php echo (isset($estado) && $estado === 'SE') ? 'selected' : ''; ?>>Sergipe</option>
                                    <option value="TO" <?php echo (isset($estado) && $estado === 'TO') ? 'selected' : ''; ?>>Tocantins</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="qtd_funcionarios" class="block text-gray-700 font-medium mb-2">Quantidade de Funcionários na Vigilância Sanitária *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-users text-gray-400"></i>
                                </div>
                                <input type="number" id="qtd_funcionarios" name="qtd_funcionarios" min="1" value="<?php echo isset($qtd_funcionarios) ? htmlspecialchars($qtd_funcionarios) : ''; ?>" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <label for="qtd_estabelecimentos" class="block text-gray-700 font-medium mb-2">Quantidade Estimada de Estabelecimentos no Município *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>
                                <input type="number" id="qtd_estabelecimentos" name="qtd_estabelecimentos" min="1" value="<?php echo isset($qtd_estabelecimentos) ? htmlspecialchars($qtd_estabelecimentos) : ''; ?>" required class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="observacoes" class="block text-gray-700 font-medium mb-2">Observações ou Perguntas</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <i class="fas fa-comment text-gray-400"></i>
                            </div>
                            <textarea id="observacoes" name="observacoes" rows="4" class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo isset($observacoes) ? htmlspecialchars($observacoes) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition duration-300 flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Enviar Formulário
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Seção de Benefícios -->
            <div class="max-w-3xl mx-auto mt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Por que escolher o GovNex?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-5 rounded-lg shadow-md border border-gray-100">
                        <div class="text-blue-500 mb-3">
                            <i class="fas fa-tachometer-alt text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Eficiência</h4>
                        <p class="text-gray-600 text-sm">Aumente a produtividade da sua equipe com ferramentas otimizadas para a vigilância sanitária.</p>
                    </div>
                    <div class="bg-white p-5 rounded-lg shadow-md border border-gray-100">
                        <div class="text-blue-500 mb-3">
                            <i class="fas fa-shield-alt text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Segurança</h4>
                        <p class="text-gray-600 text-sm">Dados protegidos e seguros, com controle de acesso e backups automáticos.</p>
                    </div>
                    <div class="bg-white p-5 rounded-lg shadow-md border border-gray-100">
                        <div class="text-blue-500 mb-3">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Análise de Dados</h4>
                        <p class="text-gray-600 text-sm">Relatórios e estatísticas para ajudar na tomada de decisões estratégicas.</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-12">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-xl font-bold">GovNex</h3>
                        <p class="text-gray-400">Soluções para Vigilância Sanitária</p>
                    </div>
                    <div class="flex space-x-4 mb-4 md:mb-0">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    <div class="text-center md:text-right">
                        <p>&copy; <?php echo date('Y'); ?> GovNex. Todos os direitos reservados.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        $(document).ready(function() {
            // Máscara para telefone
            $('#telefone').mask('(00) 00000-0000');
        });
    </script>
</body>
</html>
