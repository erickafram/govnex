<?php
session_start();
// Verifica se o usuário está logado, redireciona para o login se não estiver
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /govnex/login_usuario.php');
    exit;
}

// Inclui arquivos de configuração e cabeçalho
require_once '../../conf/db_connection.php';

// Cria uma nova conexão com o banco de dados
$database = new Database();
$conn = $database->getConnection();

// Busca informações do usuário logado
$stmt = $conn->prepare("SELECT nivel_acesso, dominio, credito, nome FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch();

// Redireciona administradores
if ($usuario['nivel_acesso'] === 'administrador') {
    header('Location: /govnex/views/dashboard/admin.php');
    exit;
}

// Inclui o cabeçalho após todas as verificações de redirecionamento
require_once '../../assets/header.php'; // Contém <head>, Tailwind CSS e abertura de <body> com um fundo (ex: bg-gray-50)

// Obtém o domínio do usuário
$dominio = $usuario['dominio'];

// Consulta os gastos dos últimos 30 dias
$stmt = $conn->prepare("
    SELECT
        DATE(data_consulta) as dia,
        SUM(custo) as total_dia,
        COUNT(*) as total_consultas
    FROM consultas_log
    WHERE dominio_origem = :dominio
    AND data_consulta >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(data_consulta)
    ORDER BY dia ASC
");
$stmt->bindParam(':dominio', $dominio);
$stmt->execute();
$gastos_30dias = $stmt->fetchAll();

// Obter estatísticas gerais
$stmt = $conn->prepare("
    SELECT COUNT(*) as total_consultas 
    FROM consultas_log 
    WHERE dominio_origem = :dominio
");
$stmt->bindParam(':dominio', $dominio);
$stmt->execute();
$estatisticas = $stmt->fetch();
$total_consultas = $estatisticas['total_consultas'];

// Obter consultas do último dia
$stmt = $conn->prepare("
    SELECT COUNT(*) as consultas_hoje 
    FROM consultas_log 
    WHERE dominio_origem = :dominio 
    AND DATE(data_consulta) = CURDATE()
");
$stmt->bindParam(':dominio', $dominio);
$stmt->execute();
$consultas_hoje = $stmt->fetch()['consultas_hoje'];

// Prepara os dados para o gráfico
$labels = [];
$data = [];
$consultas_data = [];
foreach ($gastos_30dias as $gasto) {
    $labels[] = date('d/m', strtotime($gasto['dia']));
    $data[] = (float)$gasto['total_dia'];
    $consultas_data[] = (int)$gasto['total_consultas'];
}

// Calcula o total gasto
$total_gasto = 0;
$stmt = $conn->prepare("SELECT custo FROM consultas_log WHERE dominio_origem = :dominio");
$stmt->bindParam(':dominio', $dominio);
$stmt->execute();
$consultas = $stmt->fetchAll();
foreach ($consultas as $consulta) {
    $total_gasto += $consulta['custo'];
}

// Busca últimas 5 consultas
$stmt = $conn->prepare("
    SELECT * FROM consultas_log 
    WHERE dominio_origem = :dominio 
    ORDER BY data_consulta DESC 
    LIMIT 5
");
$stmt->bindParam(':dominio', $dominio);
$stmt->execute();
$ultimas_consultas = $stmt->fetchAll();
?>

<div class="container mx-auto px-4 lg:px-8 pb-12">
    <!-- Dashboard Header -->
    <div class="pt-6 pb-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Bem-vindo, <span class="font-medium"><?php echo htmlspecialchars($usuario['nome'] ?? 'Usuário'); ?></span>. 
                    Confira o resumo de suas atividades.
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-3">
                <a href="/govnex/views/logs/log_consultas.php" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Ver Logs Completos
                </a>
                <a href="/govnex/views/pagamentos/index.php" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg shadow-sm hover:from-primary-600 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Adicionar Créditos
                </a>
            </div>
        </div>
    </div>

    <?php if (empty($dominio)): // Mensagem para usuário sem domínio 
    ?>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8 text-center shadow-md animate-fadeIn">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Configuração Necessária</h3>
            <p class="text-blue-700 mb-4">Você ainda não tem um domínio vinculado à sua conta.</p>
            <p class="text-sm text-blue-600 mb-6">Entre em contato com o administrador para configurar seu acesso e começar a utilizar o sistema.</p>
            <a href="#" onclick="window.location.href='mailto:suporte@govnex.com.br?subject=Solicitar vinculação de domínio'" class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Contatar Suporte
            </a>
        </div>
    <?php else: // Conteúdo principal para usuário com domínio 
    ?>
        <!-- Alertas de Saldo -->
        <?php if ($usuario['credito'] < 0.20): ?>
            <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 rounded-lg p-4 mb-6 animate-pulse shadow-sm" role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Saldo Crítico!</h3>
                        <div class="mt-1 text-sm text-red-700">
                            Seu saldo está criticamente baixo (R$ <?php echo number_format($usuario['credito'], 2, ',', '.'); ?>). 
                            <a href="/govnex/views/pagamentos/index.php" class="font-semibold text-red-800 hover:text-red-900 underline">Recarregue agora</a> para evitar interrupção dos serviços.
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($usuario['credito'] < 10.00): ?>
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-400 rounded-lg p-4 mb-6 shadow-sm" role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Saldo Baixo</h3>
                        <div class="mt-1 text-sm text-yellow-700">
                            Seu saldo (R$ <?php echo number_format($usuario['credito'], 2, ',', '.'); ?>) está ficando baixo. 
                            <a href="/govnex/views/pagamentos/index.php" class="font-semibold text-yellow-800 hover:text-yellow-900 underline">Recarregue seus créditos</a> em breve para continuar utilizando nossos serviços sem interrupções.
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Card 1: Saldo -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Saldo Atual</div>
                            <div class="mt-1 text-2xl font-bold <?php echo ($usuario['credito'] < 10.00) ? (($usuario['credito'] < 0.20) ? 'text-red-600' : 'text-yellow-600') : 'text-emerald-600'; ?>">
                                R$ <?php echo number_format($usuario['credito'], 2, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="rounded-full p-3 <?php echo ($usuario['credito'] < 10.00) ? (($usuario['credito'] < 0.20) ? 'bg-red-100 text-red-500' : 'bg-yellow-100 text-yellow-500') : 'bg-emerald-100 text-emerald-500'; ?>">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="/govnex/views/pagamentos/index.php" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors">
                            Adicionar Créditos
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total gasto -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Total Gasto (Histórico)</div>
                            <div class="mt-1 text-2xl font-bold text-gray-800">
                                R$ <?php echo number_format($total_gasto, 2, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="rounded-full p-3 bg-blue-100 text-blue-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="/govnex/views/logs/log_consultas.php" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors">
                            Ver Histórico Completo
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total de consultas -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Total de Consultas</div>
                            <div class="mt-1 text-2xl font-bold text-gray-800">
                                <?php echo number_format($total_consultas, 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="rounded-full p-3 bg-purple-100 text-purple-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            <?php echo $consultas_hoje; ?> hoje
                        </span>
                    </div>
                    <div class="mt-2 pt-4 border-t border-gray-100">
                        <a href="/govnex/views/logs/log_consultas.php" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors">
                            Ver Detalhes
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 4: Domínio -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Domínio</div>
                            <div class="mt-1 text-xl font-bold text-gray-800 truncate max-w-[180px]" title="<?php echo htmlspecialchars($dominio); ?>">
                                <?php echo htmlspecialchars($dominio); ?>
                            </div>
                        </div>
                        <div class="rounded-full p-3 bg-green-100 text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Ativo
                        </span>
                    </div>
                    <div class="mt-2 pt-4 border-t border-gray-100">
                        <a href="#" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors">
                            Informações do Domínio
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Chart Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Histórico de Consumo</h3>
                            <p class="text-sm text-gray-500">Últimos 30 dias</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="px-3 py-1.5 bg-primary-50 text-primary-600 text-sm font-medium rounded-lg hover:bg-primary-100 transition-colors duration-200 active-chart-btn" data-chart="cost">
                                Custo
                            </button>
                            <button class="px-3 py-1.5 bg-gray-50 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-100 transition-colors duration-200" data-chart="queries">
                                Consultas
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="graficoGastos"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Activity -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden h-full">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Atividade Recente</h3>
                        <p class="text-sm text-gray-500">Últimas 5 consultas</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <?php if (count($ultimas_consultas) > 0): ?>
                            <?php foreach ($ultimas_consultas as $consulta): ?>
                                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <?php 
                                            // Determine o tipo de consulta para exibir o ícone apropriado
                                            $icone = 'search';
                                            $icone_cor = 'blue';
                                            $tipo_consulta = 'Consulta';
                                            
                                            // Verificar se o campo tipo_consulta existe no resultado
                                            $tipo_campo = isset($consulta['tipo_consulta']) ? $consulta['tipo_consulta'] : '';
                                            
                                            if (strpos(strtolower($tipo_campo), 'cpf') !== false) {
                                                $icone = 'user';
                                                $icone_cor = 'green';
                                                $tipo_consulta = 'CPF';
                                            } elseif (strpos(strtolower($tipo_campo), 'cnpj') !== false) {
                                                $icone = 'building';
                                                $icone_cor = 'purple';
                                                $tipo_consulta = 'CNPJ';
                                            } elseif (!empty($consulta['cnpj_consultado'])) {
                                                $icone = 'building';
                                                $icone_cor = 'purple';
                                                $tipo_consulta = 'CNPJ';
                                            }
                                            ?>
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-<?php echo $icone_cor; ?>-100 text-<?php echo $icone_cor; ?>-500 mr-3">
                                                    <i class="fas fa-<?php echo $icone; ?> text-sm"></i>
                                                </span>
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        Consulta de <?php echo $tipo_consulta; ?>
                                                    </span>
                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                        <?php 
                                                        // Verificar qual campo usar para o valor da consulta
                                                        $consulta_valor = '';
                                                        if (isset($consulta['consulta_valor'])) {
                                                            $consulta_valor = $consulta['consulta_valor'];
                                                        } elseif (isset($consulta['cnpj_consultado'])) {
                                                            $consulta_valor = $consulta['cnpj_consultado'];
                                                        }
                                                        
                                                        echo htmlspecialchars(substr($consulta_valor, 0, 25)); 
                                                        if (strlen($consulta_valor) > 25): ?>...<?php endif; 
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs inline-block px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                                R$ <?php echo number_format($consulta['custo'], 2, ',', '.'); ?>
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <?php echo date('d/m H:i', strtotime($consulta['data_consulta'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-8 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-400 mb-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Nenhuma consulta realizada ainda.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (count($ultimas_consultas) > 0): ?>
                        <div class="p-4 bg-gray-50 border-t border-gray-100">
                            <a href="/govnex/views/logs/log_consultas.php" class="text-center block w-full text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors">
                                Ver Todas as Consultas
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php endif; // Fim da verificação de domínio 
    ?>
</div>

<?php require_once '../../assets/footer.php'; // Fechamento do HTML, scripts comuns ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<!-- Luxon for date handling -->
<script src="https://cdn.jsdelivr.net/npm/luxon@3.0.1/build/global/luxon.min.js"></script>
<!-- Chart.js adapter for Luxon -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.2.0/dist/chartjs-adapter-luxon.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = <?php echo json_encode($data); ?>;
        const chartLabels = <?php echo json_encode($labels); ?>;
        const consultasData = <?php echo json_encode($consultas_data); ?>;
        let chartInstance = null;
        
        // Configuração de cores
        const costGradient = {
            start: 'rgba(16, 185, 129, 0.7)',  // Emerald 500
            end: 'rgba(6, 95, 70, 0.3)',       // Emerald 800
            border: 'rgba(16, 185, 129, 1)'    // Emerald 500
        };
        
        const queriesGradient = {
            start: 'rgba(79, 70, 229, 0.7)',   // Indigo 500
            end: 'rgba(49, 46, 129, 0.3)',     // Indigo 800
            border: 'rgba(79, 70, 229, 1)'     // Indigo 500
        };

        // Função para criar ou atualizar o gráfico
        function updateChart(dataType = 'cost') {
            const ctx = document.getElementById('graficoGastos');
            if (!ctx) return;
            
            const chartContext = ctx.getContext('2d');
            
            // Destruir gráfico existente se houver
            if (chartInstance) {
                chartInstance.destroy();
            }
            
            // Configurar dados e cores baseado no tipo de gráfico
            let displayData, gradientColors, chartTitle, chartUnit;
            
            if (dataType === 'cost') {
                displayData = chartData;
                gradientColors = costGradient;
                chartTitle = 'Gastos Diários (R$)';
                chartUnit = 'R$';
            } else {
                displayData = consultasData;
                gradientColors = queriesGradient;
                chartTitle = 'Consultas Diárias';
                chartUnit = '';
            }
            
            // Criar gradiente
            const gradient = chartContext.createLinearGradient(0, 0, 0, ctx.offsetHeight * 1.5);
            gradient.addColorStop(0, gradientColors.start);
            gradient.addColorStop(1, gradientColors.end);
            
            // Configuração do Gráfico
            chartInstance = new Chart(chartContext, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: chartTitle,
                        data: displayData,
                        backgroundColor: gradient,
                        borderColor: gradientColors.border,
                        borderWidth: 1.5,
                        borderRadius: {
                            topLeft: 6,
                            topRight: 6
                        },
                        hoverBackgroundColor: gradientColors.start.replace('0.7', '0.9'),
                        hoverBorderColor: gradientColors.border,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (dataType === 'cost') {
                                        return chartUnit + ' ' + value.toLocaleString('pt-BR', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    } else {
                                        return value;
                                    }
                                },
                                color: '#6b7280',
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                color: '#e5e7eb',
                                borderDash: [3, 3],
                                drawBorder: false,
                            }
                        },
                        x: {
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 11
                                },
                                maxRotation: 0,
                                minRotation: 0
                            },
                            grid: {
                                display: false,
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#f9fafb',
                            bodyColor: '#d1d5db',
                            titleFont: {
                                weight: 'bold'
                            },
                            displayColors: false,
                            padding: 10,
                            cornerRadius: 4,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        if (dataType === 'cost') {
                                            label += 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                        } else {
                                            label += context.parsed.y + ' consultas';
                                        }
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    onHover: (event, chartElement) => {
                        const target = event.native ? event.native.target : event.target;
                        target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                    }
                }
            });
        }
        
        // Inicializar gráfico com dados de custo
        if (chartData && chartData.length > 0) {
            updateChart('cost');
            
            // Adicionar event listeners para os botões de alternar gráficos
            document.querySelectorAll('[data-chart]').forEach(button => {
                button.addEventListener('click', function() {
                    const chartType = this.getAttribute('data-chart');
                    
                    // Atualizar classes dos botões
                    document.querySelectorAll('[data-chart]').forEach(btn => {
                        btn.classList.remove('bg-primary-50', 'text-primary-600', 'active-chart-btn');
                        btn.classList.add('bg-gray-50', 'text-gray-600');
                    });
                    
                    this.classList.remove('bg-gray-50', 'text-gray-600');
                    this.classList.add('bg-primary-50', 'text-primary-600', 'active-chart-btn');
                    
                    // Atualizar gráfico
                    updateChart(chartType);
                });
            });
        } else {
            // Mensagem se não houver dados
            const chartContainer = document.getElementById('graficoGastos')?.parentNode;
            if (chartContainer) {
                chartContainer.innerHTML = '<div class="flex items-center justify-center h-full"><div class="text-center text-gray-500"><svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg><p>Sem dados de gastos nos últimos 30 dias.</p></div></div>';
            }
        }
    });
</script>