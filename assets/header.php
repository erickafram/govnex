<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'GovNex - Sistema de Consultas'; ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef9ff',
                            100: '#dcf2ff',
                            200: '#b3e7ff',
                            300: '#5ed4ff',
                            400: '#37c0ff',
                            500: '#0d6efd',
                            600: '#0054e6',
                            700: '#0042b3',
                            800: '#003994',
                            900: '#002970'
                        },
                        secondary: '#6c757d',
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        dark: '#1f2937',
                        light: '#f9fafb'
                    },
                    boxShadow: {
                        'ts': '0 2px 4px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(0, 0, 0, 0.05)',
                        'card': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)'
                    },
                    gridTemplateColumns: {
                        'sidebar': '64px auto',
                    }
                }
            }
        }
        
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidebarMenu', () => ({
                mobileMenuOpen: false,
                isDesktop: window.innerWidth >= 768,
                
                init() {
                    this.$watch('mobileMenuOpen', () => {
                        document.body.style.overflow = this.mobileMenuOpen ? 'hidden' : '';
                    });
                    
                    window.addEventListener('resize', () => {
                        this.isDesktop = window.innerWidth >= 768;
                    });
                }
            }))
        })
    </script>
</head>

<body class="font-poppins flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php 
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Declaração das variáveis de usuário e saldo
    $usuario = null;
    $saldo = 0;
    
    // Buscar dados do usuário logado se houver uma sessão ativa
    if (isset($_SESSION['usuario_id'])) {
        // Conexão com o banco de dados
        if (!isset($conn)) {
            require_once __DIR__ . '/../conf/db_connection.php';
            $database = new Database();
            $conn = $database->getConnection();
        }
        
        // Buscar dados completos do usuário
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['usuario_id']);
        $stmt->execute();
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            $saldo = $usuario['credito'];
        }
    }
    
    if (isset($_SESSION['usuario_id'])): ?>
    <!-- Vertical Side Menu for Logged In Users -->
    <div x-data="sidebarMenu" 
         class="bg-white border-r border-gray-200 md:w-64 shadow-ts flex flex-col justify-between text-sm flex-shrink-0 md:h-screen h-auto">
        <!-- Top Section -->
        <div>
            <!-- Logo -->
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-primary-500/10 to-white">
                <a href="/govnex/index.php" class="text-2xl font-bold text-primary-500">GovNex</a>
                
                <!-- Mobile Menu Button - Only Visible on Mobile -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-900 focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Navigation Menu - Hidden on Mobile unless toggled -->
            <nav class="flex flex-col py-2" x-bind:class="{'hidden md:flex': !mobileMenuOpen, 'flex': mobileMenuOpen}">
                <a href="/govnex/views/dashboard/index.php" class="py-2.5 px-6 text-gray-700 hover:text-primary-500 hover:bg-gray-50 no-underline rounded-md mx-2 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Início</span>
                </a>
                <a href="/govnex/views/logs/log_consultas.php" class="py-2.5 px-6 text-gray-700 hover:text-primary-500 hover:bg-gray-50 no-underline rounded-md mx-2 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Logs</span>
                </a>
                <a href="/govnex/views/dashboard/status_api.php" class="py-2.5 px-6 text-gray-700 hover:text-primary-500 hover:bg-gray-50 no-underline rounded-md mx-2 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Status</span>
                </a>
                <div class="mx-2" x-data="{ pagamentosOpen: false }">
                    <button @click="pagamentosOpen = !pagamentosOpen" class="w-full flex items-center justify-between py-2.5 px-6 text-gray-700 hover:text-primary-500 hover:bg-gray-50 rounded-md no-underline focus:outline-none transition-colors duration-200">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Pagamentos</span>
                        </span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{'transform rotate-90': pagamentosOpen}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="pagamentosOpen" x-transition class="ml-6 pl-2 border-l-2 border-gray-100 mt-1">
                        <a href="/govnex/views/pagamentos/index.php" class="block py-2 px-4 text-gray-600 hover:text-primary-500 hover:bg-gray-50 rounded-md no-underline transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Recarga via Pix</span>
                        </a>
                        <a href="/govnex/views/pagamentos/boleto.php" class="block py-2 px-4 text-gray-600 hover:text-primary-500 hover:bg-gray-50 rounded-md no-underline transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Boleto</span>
                        </a>
                    </div>
                </div>
                <a href="/govnex/views/pagamentos/pagamentos.php" class="py-2.5 px-6 text-gray-700 hover:text-primary-500 hover:bg-gray-50 no-underline rounded-md mx-2 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span>Histórico</span>
                </a>

                <?php if (isset($usuario) && $usuario['nivel_acesso'] === 'administrador'): ?>
                <a href="/govnex/views/admin/formularios_interesse.php" class="py-2.5 px-6 text-gray-700 hover:text-primary-500 hover:bg-gray-50 no-underline rounded-md mx-2 flex items-center gap-2 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span>Interesse</span>
                </a>
                <?php endif; ?>
                
                <div class="mt-2 pt-2 border-t border-gray-100 mx-4" x-data="{ accountOpen: false }">
                    <button @click="accountOpen = !accountOpen" class="w-full flex items-center justify-between px-4 py-2.5 text-gray-700 hover:text-primary-500 hover:bg-gray-50 rounded-md no-underline focus:outline-none transition-colors duration-200">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Minha Conta</span>
                        </span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{'transform rotate-90': accountOpen}">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    <div x-show="accountOpen" x-transition class="ml-6 pl-2 border-l-2 border-gray-100 mt-1">
                        <a href="/govnex/views/conta/editar.php" class="block py-2 px-4 text-gray-600 hover:text-primary-500 hover:bg-gray-50 rounded-md no-underline transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            <span>Editar Dados</span>
                        </a>
                        <a href="/govnex/logout.php" class="block py-2 px-4 text-gray-600 hover:text-danger hover:bg-gray-50 rounded-md no-underline transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Sair</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Balance Card - Bottom Section -->
        <div class="mt-auto p-4" x-show="mobileMenuOpen || isDesktop" x-transition>
            <?php
            // Saldo e dados do usuário já foram buscados no início do arquivo
            ?>
            <div class="rounded-xl bg-gradient-to-r from-primary-500/90 to-primary-600 shadow-card p-4 text-white overflow-hidden relative">
                <div class="absolute -right-4 -bottom-6 opacity-20">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="white" stroke-width="2"/>
                        <path d="M14.5 9.5C14.5 8.11929 13.3807 7 12 7C10.6193 7 9.5 8.11929 9.5 9.5C9.5 10.8807 10.6193 12 12 12" stroke="white" stroke-width="2"/>
                        <path d="M12 12C13.3807 12 14.5 13.1193 14.5 14.5C14.5 15.8807 13.3807 17 12 17C10.6193 17 9.5 15.8807 9.5 14.5" stroke="white" stroke-width="2"/>
                        <path d="M12 7V5" stroke="white" stroke-width="2"/>
                        <path d="M12 19V17" stroke="white" stroke-width="2"/>
                    </svg>
                </div>
                <div class="relative">
                    <div class="text-xs font-light opacity-80 mb-1">Saldo Atual</div>
                    <div class="text-2xl font-bold mb-4">R$ <?php echo number_format($saldo, 2, ',', '.'); ?></div>
                    <div class="flex gap-2">
                        <a href="/govnex/views/pagamentos/index.php" class="inline-flex items-center gap-1 text-white/90 hover:text-white text-xs bg-white/20 hover:bg-white/30 transition-colors px-3 py-1.5 rounded-full no-underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Via Pix</span>
                        </a>
                        <a href="/govnex/views/pagamentos/boleto.php" class="inline-flex items-center gap-1 text-white/90 hover:text-white text-xs bg-white/20 hover:bg-white/30 transition-colors px-3 py-1.5 rounded-full no-underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Via Boleto</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-2 text-xs text-center text-gray-500">
                Última atualização: <?php echo date('d/m/Y H:i'); ?>
            </div>
        </div>
        
        <!-- Mobile Balance Mini Card - Only visible when menu is collapsed -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white p-3 border-t border-gray-200 shadow-lg z-10" 
             x-show="!mobileMenuOpen && !isDesktop" x-transition>
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-500">Saldo Atual</div>
                    <div class="text-lg font-bold text-primary-500">R$ <?php echo number_format($saldo, 2, ',', '.'); ?></div>
                </div>
                <div class="flex gap-2">
                    <a href="/govnex/views/pagamentos/index.php" class="inline-flex items-center gap-1 text-white bg-primary-500 hover:bg-primary-600 transition-colors px-3 py-1.5 rounded-full no-underline text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Pix</span>
                    </a>
                    <a href="/govnex/views/pagamentos/boleto.php" class="inline-flex items-center gap-1 text-primary-500 border border-primary-500 hover:bg-primary-50 transition-colors px-3 py-1.5 rounded-full no-underline text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Boleto</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 <?php echo isset($_SESSION['usuario_id']) ? 'overflow-x-hidden pb-16 md:pb-0' : ''; ?>">
    <?php else: ?>
    <!-- Header for Non-Logged In Users -->
    <header class="sticky top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/govnex/index.php" class="text-2xl font-bold text-primary-500 flex items-center">
                        GovNex
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center justify-center flex-1 space-x-8">
                    <a href="/govnex/index.php" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors duration-200">
                        Início
                    </a>
                    <a href="#features" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors duration-200">
                        Recursos
                    </a>
                    <a href="#faq" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors duration-200">
                        FAQ
                    </a>
                    <a href="#contact" class="text-sm font-medium text-gray-700 hover:text-primary-500 transition-colors duration-200">
                        Contato
                    </a>
                </nav>
                
                <!-- Login/Register Buttons (Desktop) -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/govnex/login_usuario.php" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors duration-200">
                        Entrar
                    </a>
                    <a href="/govnex/cadastro_usuario.php" class="px-4 py-2 text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 rounded-lg shadow-sm transition-colors duration-200">
                        Cadastre-se
                    </a>
                    </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileMenuOpen" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        </div>
                    </div>
            
            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" class="md:hidden pb-3" x-transition>
                <div class="flex flex-col space-y-2 pt-2 pb-3">
                    <a href="/govnex/index.php" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-500 hover:bg-gray-50 rounded-md">
                        Início
                    </a>
                    <a href="#features" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-500 hover:bg-gray-50 rounded-md">
                        Recursos
                    </a>
                    <a href="#faq" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-500 hover:bg-gray-50 rounded-md">
                        FAQ
                    </a>
                    <a href="#contact" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-primary-500 hover:bg-gray-50 rounded-md">
                        Contato
                    </a>
                    <div class="border-t border-gray-200 my-1 pt-1"></div>
                    <a href="/govnex/login_usuario.php" class="block px-3 py-2 text-base font-medium text-primary-600 hover:text-primary-700 hover:bg-gray-50 rounded-md">
                        Entrar
                    </a>
                    <a href="/govnex/cadastro_usuario.php" class="block px-3 py-2 text-base font-medium text-white bg-primary-500 hover:bg-primary-600 rounded-md text-center">
                        Cadastre-se
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content for Non-Logged In Users -->
    <main>
    <?php endif; ?>
</body>
</html>