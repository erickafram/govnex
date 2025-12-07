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
$stmt = $conn->prepare("SELECT nivel_acesso FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch();

// Verifica se o usuário é administrador
if ($usuario['nivel_acesso'] !== 'administrador') {
    header('Location: /govnex/views/dashboard/index.php');
    exit;
}

// Processar alterações de status se enviadas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar_status') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_UNSAFE_RAW);
    $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
    
    if (!empty($id) && !empty($status)) {
        $stmt = $conn->prepare("UPDATE formularios_interesse SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $mensagem = 'Status atualizado com sucesso!';
        $tipo_mensagem = 'sucesso';
    }
}

// Configuração de paginação
$registros_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Filtros
$filtro_status = isset($_GET['status']) ? $_GET['status'] : '';
$filtro_data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$filtro_data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';
$filtro_busca = isset($_GET['busca']) ? $_GET['busca'] : '';

// Construir a consulta SQL com filtros
$sql_where = [];
$params = [];

if (!empty($filtro_status)) {
    $sql_where[] = "status = :status";
    $params[':status'] = $filtro_status;
}

if (!empty($filtro_data_inicio)) {
    $sql_where[] = "data_cadastro >= :data_inicio";
    $params[':data_inicio'] = $filtro_data_inicio . ' 00:00:00';
}

if (!empty($filtro_data_fim)) {
    $sql_where[] = "data_cadastro <= :data_fim";
    $params[':data_fim'] = $filtro_data_fim . ' 23:59:59';
}

if (!empty($filtro_busca)) {
    $sql_where[] = "(nome LIKE :busca OR email LIKE :busca OR municipio LIKE :busca)";
    $params[':busca'] = '%' . $filtro_busca . '%';
}

$where_clause = !empty($sql_where) ? 'WHERE ' . implode(' AND ', $sql_where) : '';

// Consulta para contar o total de registros
$sql_count = "SELECT COUNT(*) as total FROM formularios_interesse $where_clause";
$stmt_count = $conn->prepare($sql_count);
foreach ($params as $key => $value) {
    $stmt_count->bindValue($key, $value);
}
$stmt_count->execute();
$total_registros = $stmt_count->fetch()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta para buscar os registros da página atual
$sql = "SELECT * FROM formularios_interesse $where_clause ORDER BY data_cadastro DESC LIMIT :offset, :limit";
$stmt = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$formularios = $stmt->fetchAll();

// Inclui o cabeçalho após todas as verificações de redirecionamento
require_once '../../assets/header.php';
?>

<div class="container mx-auto px-4 lg:px-8 pb-12">
    <!-- Dashboard Header -->
    <div class="pt-6 pb-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Formulários de Interesse</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Gerencie os formulários de interesse enviados pelos usuários.
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="/govnex/views/dashboard/admin.php" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>

    <?php if (isset($mensagem)): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $tipo_mensagem === 'sucesso' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
            <div class="flex items-center">
                <i class="<?php echo $tipo_mensagem === 'sucesso' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500'; ?> mr-2"></i>
                <?php echo $mensagem; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h2>
        <form action="" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Todos</option>
                    <option value="pendente" <?php echo $filtro_status === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                    <option value="contatado" <?php echo $filtro_status === 'contatado' ? 'selected' : ''; ?>>Contatado</option>
                    <option value="aprovado" <?php echo $filtro_status === 'aprovado' ? 'selected' : ''; ?>>Aprovado</option>
                    <option value="recusado" <?php echo $filtro_status === 'recusado' ? 'selected' : ''; ?>>Recusado</option>
                </select>
            </div>
            <div>
                <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                <input type="date" id="data_inicio" name="data_inicio" value="<?php echo $filtro_data_inicio; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                <input type="date" id="data_fim" name="data_fim" value="<?php echo $filtro_data_fim; ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label for="busca" class="block text-sm font-medium text-gray-700 mb-1">Busca</label>
                <div class="relative">
                    <input type="text" id="busca" name="busca" value="<?php echo htmlspecialchars($filtro_busca); ?>" placeholder="Nome, email ou município" class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="/govnex/views/admin/formularios_interesse.php" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Limpar Filtros
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de Formulários -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">
                Formulários de Interesse 
                <span class="text-sm font-normal text-gray-500">(<?php echo $total_registros; ?> registros encontrados)</span>
            </h3>
        </div>

        <?php if (count($formularios) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Município/UF</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dados</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($formularios as $form): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($form['nome']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($form['email']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($form['telefone']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($form['municipio']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($form['estado']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Funcionários: <?php echo htmlspecialchars($form['qtd_funcionarios']); ?></div>
                                    <div class="text-sm text-gray-900">Estabelecimentos: <?php echo htmlspecialchars($form['qtd_estabelecimentos']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d/m/Y H:i', strtotime($form['data_cadastro'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $status_classes = [
                                        'pendente' => 'bg-yellow-100 text-yellow-800',
                                        'contatado' => 'bg-blue-100 text-blue-800',
                                        'aprovado' => 'bg-green-100 text-green-800',
                                        'recusado' => 'bg-red-100 text-red-800'
                                    ];
                                    $status_class = $status_classes[$form['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>">
                                        <?php echo ucfirst($form['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" class="text-primary-600 hover:text-primary-900 mr-3" onclick="abrirDetalhes(<?php echo $form['id']; ?>)">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                    <button type="button" class="text-indigo-600 hover:text-indigo-900" onclick="abrirModalStatus(<?php echo $form['id']; ?>, '<?php echo $form['status']; ?>')">
                                        <i class="fas fa-edit"></i> Status
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <?php if ($total_paginas > 1): ?>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Mostrando <span class="font-medium"><?php echo $offset + 1; ?></span> a 
                            <span class="font-medium"><?php echo min($offset + $registros_por_pagina, $total_registros); ?></span> de 
                            <span class="font-medium"><?php echo $total_registros; ?></span> resultados
                        </div>
                        <div class="flex space-x-1">
                            <?php
                            // Parâmetros da URL para paginação
                            $params = $_GET;
                            unset($params['pagina']); // Remove o parâmetro de página atual
                            $query_string = http_build_query($params);
                            $url_base = '?' . ($query_string ? $query_string . '&' : '');
                            ?>
                            
                            <?php if ($pagina_atual > 1): ?>
                                <a href="<?php echo $url_base; ?>pagina=<?php echo $pagina_atual - 1; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Anterior
                                </a>
                            <?php endif; ?>
                            
                            <?php
                            // Mostrar no máximo 5 links de página
                            $start_page = max(1, $pagina_atual - 2);
                            $end_page = min($total_paginas, $start_page + 4);
                            
                            if ($end_page - $start_page < 4) {
                                $start_page = max(1, $end_page - 4);
                            }
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                                <a href="<?php echo $url_base; ?>pagina=<?php echo $i; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md <?php echo $i === $pagina_atual ? 'z-10 bg-primary-50 border-primary-500 text-primary-600' : 'text-gray-700 bg-white hover:bg-gray-50'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($pagina_atual < $total_paginas): ?>
                                <a href="<?php echo $url_base; ?>pagina=<?php echo $pagina_atual + 1; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Próxima
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="p-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <i class="fas fa-search text-2xl"></i>
                </div>
                <p class="text-gray-500">Nenhum formulário de interesse encontrado com os filtros selecionados.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Detalhes -->
<div id="modalDetalhes" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10">
            <h3 class="text-lg font-semibold text-gray-800">Detalhes do Formulário</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500" onclick="fecharModal('modalDetalhes')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6" id="conteudoDetalhes">
            <div class="flex justify-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Alteração de Status -->
<div id="modalStatus" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Alterar Status</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500" onclick="fecharModal('modalStatus')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="formStatus" method="POST" action="">
            <div class="p-6">
                <input type="hidden" name="acao" value="atualizar_status">
                <input type="hidden" id="formId" name="id" value="">
                
                <div class="mb-4">
                    <label for="statusSelect" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="statusSelect" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <option value="pendente">Pendente</option>
                        <option value="contatado">Contatado</option>
                        <option value="aprovado">Aprovado</option>
                        <option value="recusado">Recusado</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors" onclick="fecharModal('modalStatus')">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Função para abrir o modal de detalhes
    function abrirDetalhes(id) {
        document.getElementById('modalDetalhes').classList.remove('hidden');
        document.getElementById('conteudoDetalhes').innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div></div>';
        
        // Fazer uma requisição AJAX para buscar os detalhes
        fetch(`/govnex/views/admin/ajax_detalhes_formulario.php?id=${id}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('conteudoDetalhes').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('conteudoDetalhes').innerHTML = '<div class="text-red-500">Erro ao carregar os detalhes. Por favor, tente novamente.</div>';
                console.error('Erro:', error);
            });
    }
    
    // Função para abrir o modal de alteração de status
    function abrirModalStatus(id, status) {
        document.getElementById('formId').value = id;
        document.getElementById('statusSelect').value = status;
        document.getElementById('modalStatus').classList.remove('hidden');
    }
    
    // Função para fechar modais
    function fecharModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    
    // Fechar modais quando clicar fora deles
    window.addEventListener('click', function(event) {
        if (event.target.id === 'modalDetalhes') {
            fecharModal('modalDetalhes');
        }
        if (event.target.id === 'modalStatus') {
            fecharModal('modalStatus');
        }
    });
</script>

<?php require_once '../../assets/footer.php'; ?>
