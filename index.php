<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GovNex - Tecnologia e Inovação</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
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
                        dark: '#0f172a',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        slideRight: {
                            '0%': { transform: 'translateX(-30px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        },
                        wave: {
                            '0%': { transform: 'rotate(0.0deg)' },
                            '10%': { transform: 'rotate(14.0deg)' },
                            '20%': { transform: 'rotate(-8.0deg)' },
                            '30%': { transform: 'rotate(14.0deg)' },
                            '40%': { transform: 'rotate(-4.0deg)' },
                            '50%': { transform: 'rotate(10.0deg)' },
                            '60%': { transform: 'rotate(0.0deg)' },
                            '100%': { transform: 'rotate(0.0deg)' }
                        },
                        blob: {
                            '0%': {
                                transform: 'translate(0px, 0px) scale(1)'
                            },
                            '33%': {
                                transform: 'translate(30px, -50px) scale(1.1)'
                            },
                            '66%': {
                                transform: 'translate(-20px, 20px) scale(0.9)'
                            },
                            '100%': {
                                transform: 'translate(0px, 0px) scale(1)'
                            }
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'slide-right': 'slideRight 0.8s ease-out forwards',
                        'wave': 'wave 2s linear infinite',
                        'blob': 'blob 7s infinite'
                    },
                    backgroundImage: {
                        'hero-pattern': 'url("data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%2393C5FD\' fill-opacity=\'0.1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E")'
                    }
                }
            }
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 5rem;
        }

        /* Estilos Hero Section */
        .hero-section {
            /* Gradiente sobre a imagem de fundo */
            /* Substitua 'assets/imagem/fundo.webp' pelo caminho correto da sua imagem */
            background: linear-gradient(rgba(13, 110, 253, 0.8), rgba(13, 110, 253, 0.9)), url('assets/imagem/fundo.webp');
            background-size: cover;
            /* Garante que a imagem cubra toda a área */
            color: #fff;
            /* Cor do texto */
            text-align: center;
            /* Alinhamento do texto */
            padding: 120px 20px;
            /* Espaçamento interno */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            /* Sombra */
            opacity: 0;
            /* Inicialmente invisível para animação */
            transform: translateY(20px);
            /* Posição inicial para animação */
            animation: fadeInUp 0.8s ease-out forwards;
            /* Animação de entrada */
            transition: all 0.4s ease;
            /* Transição suave para hover */
        }

        .hero-section:hover {
            transform: translateY(-5px);
            /* Efeito de elevação no hover */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            /* Sombra mais pronunciada no hover */
        }

        .hero-section h1 {
            font-weight: 700;
            /* Peso da fonte */
            font-size: 2.5rem;
            /* Tamanho da fonte base */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            /* Sombra no texto */
            transition: transform 0.3s ease;
            /* Transição suave */
        }

        /* Ajuste de tamanho para telas maiores */
        @media (min-width: 768px) {
            .hero-section h1 {
                font-size: 3.5rem;
            }
        }


        .hero-section p {
            font-size: 1.1rem;
            /* Tamanho da fonte base */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            /* Sombra no texto */
            transition: transform 0.3s ease;
            /* Transição suave */
            max-width: 600px;
            /* Limita a largura do parágrafo */
            margin-left: auto;
            margin-right: auto;
        }

        /* Ajuste de tamanho para telas maiores */
        @media (min-width: 768px) {
            .hero-section p {
                font-size: 1.2rem;
            }
        }


        /* Efeito Hover para Cards */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            /* Transição suave */
            transform: translateY(0);
            /* Estado inicial */
        }

        .card-hover:hover {
            transform: translateY(-5px);
            /* Efeito de elevação */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            /* Sombra */
        }

        /* Animação Fade In Up */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Transições gerais para botões e links */
        button,
        a {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Estilos e Transições para Modal (Alpine.js) */
        [x-cloak] {
            display: none !important;
        }

        /* Esconde elementos antes do Alpine inicializar */

        .modal-transition {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .modal-enter-active,
        .modal-leave-active {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .modal-enter-from,
        .modal-leave-to {
            opacity: 0;
            transform: scale(0.95);
        }

        .modal-enter-to,
        .modal-leave-from {
            opacity: 1;
            transform: scale(1);
        }

        .modal-bg-enter-active,
        .modal-bg-leave-active {
            transition: opacity 0.3s ease;
        }

        .modal-bg-enter-from,
        .modal-bg-leave-to {
            opacity: 0;
        }

        .modal-bg-enter-to,
        .modal-bg-leave-from {
            opacity: 1;
        }

        /* Ajuste para garantir que o conteúdo do modal seja rolável se necessário */
        .modal-content-scroll {
            max-height: 80vh;
            /* Define uma altura máxima */
            overflow-y: auto;
            /* Adiciona barra de rolagem vertical se o conteúdo exceder */
        }

        /* Blob Animation */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.6;
            z-index: -1;
            transform-origin: center;
        }

        /* Custom rotating animation for cards */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.215, 0.61, 0.355, 1);
            transform: perspective(1000px) rotateY(0) translateZ(0);
        }

        .card-hover:hover {
            transform: perspective(1000px) rotateY(5deg) translateZ(10px);
            box-shadow: -6px 10px 30px rgba(0, 0, 0, 0.08);
        }

        /* Gradient Button Hover Effect */
        .gradient-button {
            background-size: 200% auto;
            transition: 0.5s;
        }

        .gradient-button:hover {
            background-position: right center;
        }

        /* Testimonial Cards */
        .testimonial-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
        }

        /* Custom Underline Animation */
        .custom-underline {
            position: relative;
            display: inline-block;
        }

        .custom-underline::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #0d6efd;
            transition: width 0.3s;
        }

        .custom-underline:hover::after {
            width: 100%;
        }

        /* Features Hover */
        .feature-box {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
        }

        /* Staggered animation for list items */
        .stagger-in > * {
            opacity: 0;
            transform: translateY(20px);
        }

        .stagger-in.appear > *:nth-child(1) {
            animation: slideUp 0.4s 0.1s forwards;
        }

        .stagger-in.appear > *:nth-child(2) {
            animation: slideUp 0.4s 0.2s forwards;
        }

        .stagger-in.appear > *:nth-child(3) {
            animation: slideUp 0.4s 0.3s forwards;
        }

        .stagger-in.appear > *:nth-child(4) {
            animation: slideUp 0.4s 0.4s forwards;
        }

        .stagger-in.appear > *:nth-child(5) {
            animation: slideUp 0.4s 0.5s forwards;
        }

        .stagger-in.appear > *:nth-child(6) {
            animation: slideUp 0.4s 0.6s forwards;
        }

        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="font-sans bg-slate-50 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow-sm fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="#" class="text-2xl font-bold text-primary-500 flex items-center space-x-1">
                        <span class="text-3xl"><i class="fas fa-poll"></i></span>
                        <span>GovNex</span>
                    </a>
                </div>

                <nav class="hidden md:flex space-x-8 items-center">
                    <a href="#hero" class="text-gray-700 hover:text-primary-500 px-3 py-2 rounded-md text-sm font-medium custom-underline">Início</a>
                    <a href="#features" class="text-gray-700 hover:text-primary-500 px-3 py-2 rounded-md text-sm font-medium custom-underline">Recursos</a>
                    <a href="#pricing" class="text-gray-700 hover:text-primary-500 px-3 py-2 rounded-md text-sm font-medium custom-underline">Preços</a>
                    <a href="#testimonials" class="text-gray-700 hover:text-primary-500 px-3 py-2 rounded-md text-sm font-medium custom-underline">Depoimentos</a>
                    <a href="#faq" class="text-gray-700 hover:text-primary-500 px-3 py-2 rounded-md text-sm font-medium custom-underline">FAQ</a>
                    <a href="/govnex/views/interesse/formulario.php" class="text-primary-600 hover:text-primary-700 px-3 py-2 rounded-md text-sm font-medium custom-underline">Demonstrar Interesse</a>
                </nav>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="/govnex/login_usuario.php" class="px-4 py-2 border border-primary-500 text-primary-500 rounded-lg text-sm font-medium hover:bg-primary-50 transition-all">Entrar</a>
                    <a href="/govnex/cadastro_usuario.php" class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg text-sm font-medium hover:from-primary-600 hover:to-primary-700 transition-all shadow-md hover:shadow-lg gradient-button">Começar Grátis</a>
                </div>

                <div class="md:hidden flex items-center" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-700 focus:outline-none p-2 rounded-md hover:bg-gray-100">
                        <svg class="h-6 w-6" x-show="!open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg class="h-6 w-6" x-show="open" x-cloak fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute top-20 inset-x-0 p-4 bg-white border-b border-gray-200 z-50">
                        <div class="flex flex-col space-y-2">
                            <a href="#hero" @click="open = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Início</a>
                            <a href="#features" @click="open = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Recursos</a>
                            <a href="#pricing" @click="open = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Preços</a>
                            <a href="#testimonials" @click="open = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">Depoimentos</a>
                            <a href="#faq" @click="open = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">FAQ</a>
                            <a href="/govnex/views/interesse/formulario.php" @click="open = false" class="px-4 py-2 text-primary-600 hover:bg-primary-50 rounded-md">Demonstrar Interesse</a>
                            <div class="border-t border-gray-200 my-2 pt-2 flex space-x-3">
                                <a href="/govnex/login_usuario.php" class="flex-1 px-4 py-2 border border-primary-500 text-primary-500 text-center rounded-lg text-sm font-medium hover:bg-primary-50 transition-all">Entrar</a>
                                <a href="/govnex/cadastro_usuario.php" class="flex-1 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center rounded-lg text-sm font-medium hover:from-primary-600 hover:to-primary-700 transition-all shadow-sm">Cadastrar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="pt-32 pb-20 relative overflow-hidden bg-gradient-to-b from-blue-50 to-white">
        <!-- Background Blobs -->
        <div class="absolute top-40 left-10 w-72 h-72 bg-primary-200 blob animate-blob opacity-20"></div>
        <div class="absolute bottom-10 right-10 w-72 h-72 bg-purple-200 blob animate-blob animation-delay-2000 opacity-20"></div>
        <div class="absolute top-1/2 left-1/3 w-72 h-72 bg-yellow-200 blob animate-blob animation-delay-4000 opacity-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-8" data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-block px-3 py-1 rounded-full bg-blue-100 text-primary-600 font-medium text-sm">
                        A melhor plataforma para consultas governamentais
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight text-gray-900">
                        Consultas de dados <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-primary-700">governamentais</span> em um só lugar
                    </h1>
                    <p class="text-xl text-gray-600 leading-relaxed">
                        Acesso simplificado a informações de CPF, CNPJ e outros dados essenciais para sua prefeitura ou empresa. Rápido, seguro e sem burocracia.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/govnex/cadastro_usuario.php" class="px-8 py-4 bg-gradient-to-r from-primary-500 to-primary-700 text-white font-medium rounded-xl shadow-md hover:shadow-xl transition-all transform hover:-translate-y-1 text-center gradient-button">
                            <span class="flex items-center justify-center gap-2">
                                <span>Começar Agora</span>
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                        <a href="#demo" class="px-8 py-4 bg-white text-gray-700 font-medium rounded-xl shadow-sm hover:shadow-md transition-all border border-gray-200 text-center flex items-center justify-center gap-2">
                            <i class="fas fa-play-circle text-primary-500"></i>
                            <span>Ver Demonstração</span>
                        </a>
                    </div>
                    <div class="pt-6">
                        <div class="flex items-center">
                            <div class="flex -space-x-2">
                                <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Cliente" class="w-10 h-10 rounded-full border-2 border-white">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Cliente" class="w-10 h-10 rounded-full border-2 border-white">
                                <img src="https://randomuser.me/api/portraits/women/22.jpg" alt="Cliente" class="w-10 h-10 rounded-full border-2 border-white">
                                <img src="https://randomuser.me/api/portraits/men/42.jpg" alt="Cliente" class="w-10 h-10 rounded-full border-2 border-white">
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-primary-100 flex items-center justify-center text-sm font-medium text-primary-700">+58</div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-700">Mais de 62 prefeituras</div>
                                <div class="flex items-center text-amber-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span class="ml-1 text-gray-600">4.9/5 avaliações</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary-500/30 to-purple-500/20 rounded-3xl transform rotate-3 blur-xl opacity-40"></div>
                    <div class="relative bg-white p-6 md:p-8 rounded-3xl shadow-xl border border-gray-100 card-hover">
                        <img src="https://dummyimage.com/600x400/4e80ff/ffffff&text=Dashboard+GovNex" alt="Dashboard GovNex" class="w-full h-auto rounded-xl shadow-sm">
                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div class="p-4 bg-blue-50 rounded-xl">
                                <div class="text-primary-500 text-lg font-semibold">3.9M+</div>
                                <div class="text-sm text-gray-600">Consultas Mensais</div>
                            </div>
                            <div class="p-4 bg-amber-50 rounded-xl">
                                <div class="text-amber-500 text-lg font-semibold">99.9%</div>
                                <div class="text-sm text-gray-600">Uptime Garantido</div>
                            </div>
                            <div class="p-4 bg-green-50 rounded-xl">
                                <div class="text-green-500 text-lg font-semibold">0.2s</div>
                                <div class="text-sm text-gray-600">Tempo de Resposta</div>
                            </div>
                            <div class="p-4 bg-purple-50 rounded-xl">
                                <div class="text-purple-500 text-lg font-semibold">+62</div>
                                <div class="text-sm text-gray-600">Prefeituras</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Trusted By -->
            <div class="mt-24 text-center">
                <h2 class="text-xl text-gray-600 mb-6" data-aos="fade-up">Utilizado e aprovado por:</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-8 justify-items-center items-center">
                    <img src="https://dummyimage.com/120x60/e5e7eb/6b7280&text=Prefeitura+A" alt="Logo Prefeitura" class="h-12 opacity-60 hover:opacity-100 transition-opacity" data-aos="fade-up" data-aos-delay="100">
                    <img src="https://dummyimage.com/120x60/e5e7eb/6b7280&text=Prefeitura+B" alt="Logo Prefeitura" class="h-12 opacity-60 hover:opacity-100 transition-opacity" data-aos="fade-up" data-aos-delay="200">
                    <img src="https://dummyimage.com/120x60/e5e7eb/6b7280&text=Prefeitura+C" alt="Logo Prefeitura" class="h-12 opacity-60 hover:opacity-100 transition-opacity" data-aos="fade-up" data-aos-delay="300">
                    <img src="https://dummyimage.com/120x60/e5e7eb/6b7280&text=Prefeitura+D" alt="Logo Prefeitura" class="h-12 opacity-60 hover:opacity-100 transition-opacity" data-aos="fade-up" data-aos-delay="400">
                    <img src="https://dummyimage.com/120x60/e5e7eb/6b7280&text=Prefeitura+E" alt="Logo Prefeitura" class="h-12 opacity-60 hover:opacity-100 transition-opacity" data-aos="fade-up" data-aos-delay="500">
                </div>
            </div>
        </div>
    </section>

    <section id="sobre" class="py-16 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Sobre a GovNex</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Somos especializados em desenvolver soluções tecnológicas inovadoras para a gestão pública e privada.
                    Nosso compromisso é oferecer ferramentas eficientes, seguras e transparentes para transformar a administração.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all card-hover text-center">
                    <div class="text-primary mb-4">
                        <i class="fas fa-lightbulb text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Inovação</h3>
                    <p class="text-gray-600">Utilizamos tecnologia de ponta para desenvolver soluções disruptivas.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all card-hover text-center">
                    <div class="text-primary mb-4">
                        <i class="fas fa-cogs text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Eficiência</h3>
                    <p class="text-gray-600">Sistemas integrados para otimizar processos e aumentar a produtividade.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition-all card-hover text-center">
                    <div class="text-primary mb-4">
                        <i class="fas fa-eye text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Transparência</h3>
                    <p class="text-gray-600">Facilitamos o acesso à informação, promovendo clareza e confiança.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="portfolio" class="py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12 md:mb-16">Nossos Produtos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                <div x-data="{ openModal: false }" class="flex flex-col">
                    <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col flex-grow">
                        <div class="p-8 text-center flex flex-col flex-grow">
                            <div class="flex justify-center text-primary mb-6">
                                <i class="fas fa-laptop-medical text-5xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Sistema Infovisa</h3>
                            <p class="text-gray-600 mb-6 flex-grow">Gestão completa e eficiente da Vigilância Sanitária para municípios.</p>
                            <button @click="openModal = true"
                                class="mt-auto px-6 py-2 border-2 border-primary text-primary font-medium rounded-lg hover:bg-blue-50 transition duration-200 self-center">
                                Saiba Mais
                            </button>
                        </div>
                    </div>
                    <div x-show="openModal"
                        x-cloak
                        @keydown.escape.window="openModal = false"
                        class="fixed inset-0 z-[999] overflow-y-auto"
                        aria-labelledby="modal-title-infovisa" aria-modal="true" role="dialog">
                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="openModal"
                                x-transition:enter="modal-bg-enter-active"
                                x-transition:enter-start="modal-bg-enter-from"
                                x-transition:enter-end="modal-bg-enter-to"
                                x-transition:leave="modal-bg-leave-active"
                                x-transition:leave-start="modal-bg-leave-from"
                                x-transition:leave-end="modal-bg-leave-to"
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                                @click="openModal = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div x-show="openModal"
                                x-transition:enter="modal-enter-active"
                                x-transition:enter-start="modal-enter-from"
                                x-transition:enter-end="modal-enter-to"
                                x-transition:leave="modal-leave-active"
                                x-transition:leave-start="modal-leave-from"
                                x-transition:leave-end="modal-leave-to"
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <i class="fas fa-laptop-medical text-primary text-xl"></i>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title-infovisa">
                                                Sistema Infovisa - Gestão da Vigilância Sanitária
                                            </h3>
                                            <div class="mt-2 modal-content-scroll text-sm text-gray-600 space-y-4">
                                                <p>
                                                    O <strong>Sistema Infovisa</strong> é uma plataforma robusta e integrada, projetada para modernizar e otimizar a gestão dos processos de vigilância sanitária em municípios, atendendo às necessidades do setor público e facilitando a interação com o setor privado.
                                                </p>
                                                <div>
                                                    <h4 class="text-md font-semibold text-primary mb-2">Para a Vigilância Sanitária (Setor Público):</h4>
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li><strong>Monitoramento Completo:</strong> Acompanhamento detalhado de inspeções, fiscalizações, autos de infração e outras ações.</li>
                                                        <li><strong>Gestão de Processos:</strong> Controle eficiente de licenciamentos, análise de projetos arquitetônicos, processos administrativos e tratamento de denúncias.</li>
                                                        <li><strong>Ordens de Serviço Inteligentes:</strong> Geração, distribuição e acompanhamento de ordens de serviço vinculadas a estabelecimentos e atividades específicas.</li>
                                                        <li><strong>Avaliação de Produtividade:</strong> Sistema de pontuação para monitorar e incentivar a produtividade dos fiscais e colaboradores.</li>
                                                        <li><strong>Relatórios Gerenciais:</strong> Geração de relatórios customizáveis para análise de dados e suporte à tomada de decisão.</li>
                                                        <li><strong>Alertas e Notificações:</strong> Sistema de alertas para prazos de licenças, pendências e tarefas importantes.</li>
                                                        <li><strong>Assinatura Digital Integrada:</strong> Facilita a assinatura eletrônica de documentos, garantindo agilidade e validade jurídica.</li>
                                                    </ul>
                                                </div>
                                                <div>
                                                    <h4 class="text-md font-semibold text-primary mt-4 mb-2">Para Empresas e Cidadãos (Setor Privado):</h4>
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li><strong>Portal do Cidadão/Empresa:</strong> Interface online para abertura e acompanhamento de processos (licenciamento, projetos, denúncias).</li>
                                                        <li><strong>Cadastro Integrado via API:</strong> Possibilidade de integração com sistemas municipais para cadastro automático de estabelecimentos e pessoas físicas/jurídicas.</li>
                                                        <li><strong>Acompanhamento Online:</strong> Consulta do status dos processos em tempo real, aumentando a transparência.</li>
                                                        <li><strong>Comunicação Facilitada:</strong> Canal direto para envio de documentos e comunicação com a vigilância.</li>
                                                    </ul>
                                                </div>
                                                <div>
                                                    <h4 class="text-md font-semibold text-primary mt-4 mb-2">Benefícios Gerais:</h4>
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li><strong>Agilidade e Eficiência:</strong> Redução da burocracia e do tempo de tramitação dos processos.</li>
                                                        <li><strong>Transparência:</strong> Maior clareza nos procedimentos e fácil acesso à informação.</li>
                                                        <li><strong>Padronização:</strong> Uniformização dos fluxos de trabalho e dos documentos gerados.</li>
                                                        <li><strong>Segurança da Informação:</strong> Armazenamento seguro e controle de acesso aos dados.</li>
                                                        <li><strong>Integração:</strong> Capacidade de se conectar a outros sistemas governamentais (Tributário, Protocolo, etc.).</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <a href="https://govnex.site/visamunicipal" target="_blank"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                                        Acessar Sistema
                                    </a>
                                    <button @click="openModal = false" type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ openModal: false }" class="flex flex-col">
                    <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col flex-grow">
                        <div class="p-8 text-center flex flex-col flex-grow">
                            <div class="flex justify-center text-primary mb-6">
                                <i class="fas fa-database text-5xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">API Consulta CNPJ</h3>
                            <p class="text-gray-600 mb-6 flex-grow">Serviço de API RESTful para consulta de dados cadastrais de CNPJ.</p>
                            <button @click="openModal = true"
                                class="mt-auto px-6 py-2 border-2 border-primary text-primary font-medium rounded-lg hover:bg-blue-50 transition duration-200 self-center">
                                Saiba Mais
                            </button>
                        </div>
                    </div>
                    <div x-show="openModal"
                        x-cloak
                        @keydown.escape.window="openModal = false"
                        class="fixed inset-0 z-[999] overflow-y-auto"
                        aria-labelledby="modal-title-cnpj" aria-modal="true" role="dialog">
                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="openModal"
                                x-transition:enter="modal-bg-enter-active"
                                x-transition:enter-start="modal-bg-enter-from"
                                x-transition:enter-end="modal-bg-enter-to"
                                x-transition:leave="modal-bg-leave-active"
                                x-transition:leave-start="modal-bg-leave-from"
                                x-transition:leave-end="modal-bg-leave-to"
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                                @click="openModal = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div x-show="openModal"
                                x-transition:enter="modal-enter-active"
                                x-transition:enter-start="modal-enter-from"
                                x-transition:enter-end="modal-enter-to"
                                x-transition:leave="modal-leave-active"
                                x-transition:leave-start="modal-leave-from"
                                x-transition:leave-end="modal-leave-to"
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <i class="fas fa-database text-primary text-xl"></i>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title-cnpj">
                                                API Consulta CNPJ
                                            </h3>
                                            <div class="mt-2 modal-content-scroll text-sm text-gray-600 space-y-4">
                                                <p>
                                                    A <strong>API Consulta CNPJ da GovNex</strong> oferece uma solução rápida, confiável e fácil de integrar para acessar informações cadastrais completas de empresas brasileiras diretamente da base de dados oficial.
                                                </p>
                                                <div>
                                                    <h4 class="text-md font-semibold text-primary mb-2">Funcionalidades e Vantagens:</h4>
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li><strong>Consulta Abrangente:</strong> Acesso a dados como Razão Social, Nome Fantasia, CNPJ formatado, Endereço completo (CEP, Logradouro, Número, Complemento, Bairro, Município, UF), Situação Cadastral, Data de Abertura, CNAE Principal e Secundários, Natureza Jurídica, Porte da Empresa, Capital Social, Quadro Societário (QSA), Telefones e E-mail (quando disponíveis).</li>
                                                        <li><strong>Dados Atualizados:</strong> Informações obtidas diretamente de fontes oficiais, garantindo alta precisão e atualização.</li>
                                                        <li><strong>Formato RESTful JSON:</strong> Resposta padronizada em JSON, facilitando a integração com qualquer linguagem de programação e sistema.</li>
                                                        <li><strong>Documentação Clara:</strong> Documentação completa com exemplos de requisição/resposta, códigos de status e guia de integração.</li>
                                                        <li><strong>Alta Disponibilidade e Performance:</strong> Infraestrutura robusta para garantir que a API esteja sempre disponível e responda rapidamente.</li>
                                                        <li><strong>Segurança:</strong> Comunicação via HTTPS (SSL/TLS) para proteger a troca de informações.</li>
                                                        <li><strong>Planos Flexíveis:</strong> Opções de planos de assinatura ou pacotes de consulta para atender diferentes demandas.</li>
                                                        <li><strong>Suporte Especializado:</strong> Equipe técnica disponível para auxiliar em dúvidas e no processo de integração.</li>
                                                    </ul>
                                                </div>
                                                <div>
                                                    <h4 class="text-md font-semibold text-primary mt-4 mb-2">Casos de Uso Comuns:</h4>
                                                    <ul class="list-disc pl-5 space-y-1">
                                                        <li><strong>Validação Cadastral:</strong> Confirmação e enriquecimento de dados de clientes e fornecedores.</li>
                                                        <li><strong>Automação de Onboarding:</strong> Preenchimento automático de informações em processos de cadastro.</li>
                                                        <li><strong>Análise de Crédito e Risco:</strong> Obtenção de informações essenciais para avaliação de crédito e compliance (KYC/KYB).</li>
                                                        <li><strong>Inteligência de Mercado:</strong> Coleta de dados para prospecção, análise de concorrência e estudos de mercado.</li>
                                                        <li><strong>Sistemas de CRM e ERP:</strong> Integração para manter a base de dados de empresas atualizada.</li>
                                                        <li><strong>Plataformas de E-commerce:</strong> Verificação de dados de vendedores e compradores PJ.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <a href="http://govnex.site/govnex/cadastro_usuario.php" target="_blank"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                                        Acessar Documentação/API
                                    </a>
                                    <button @click="openModal = false" type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="contato" class="py-16 md:py-20 px-4 sm:px-6 lg:px-8 text-center bg-gray-100">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Entre em Contato</h2>
            <p class="text-lg text-gray-600 mb-8">Tem alguma dúvida ou quer saber mais sobre nossas soluções? Fale conosco diretamente pelo WhatsApp!</p>
            <a href="https://wa.me/5562981013083?text=Ol%C3%A1%2C%20visitei%20o%20site%20da%20GovNex%20e%20gostaria%20de%20mais%20informa%C3%A7%C3%B5es." target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center justify-center px-8 py-3 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 transition duration-200 shadow-md hover:shadow-lg">
                <i class="fab fa-whatsapp text-2xl mr-3"></i>
                <span class="text-lg">Conversar no WhatsApp (62 98101-3083)</span>
            </a>
        </div>
    </section>


    <script>
        // Opcional: Se o @click="open = false" nos links não funcionar como esperado,
        // pode-se adicionar um listener global.
        document.addEventListener('alpine:init', () => {
            Alpine.data('mobileMenu', () => ({
                open: false,
                toggle() {
                    this.open = !this.open
                },
                closeMenu() {
                    this.open = false
                }
            }))
        });


        // Opcional: Animações suaves ao rolar para seções
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const hrefAttribute = this.getAttribute('href');
                // Verifica se o link é realmente uma âncora interna
                if (hrefAttribute && hrefAttribute.startsWith('#') && hrefAttribute.length > 1) {
                    const targetElement = document.querySelector(hrefAttribute);
                    if (targetElement) {
                        e.preventDefault(); // Previne o comportamento padrão apenas para âncoras válidas
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start' // Alinha o topo do elemento ao topo da viewport
                        });
                    }
                }
            });
        });
    </script>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white relative">
        <div class="absolute top-0 inset-x-0 h-40 bg-gradient-to-b from-blue-50 to-white"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Tudo que você precisa em uma única plataforma</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Nossa API simplifica o acesso aos dados governamentais, trazendo eficiência para sua instituição.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-box bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-search text-primary-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Consulta de CPF e CNPJ</h3>
                    <p class="text-gray-600 mb-4">Acesse informações detalhadas sobre pessoas físicas e jurídicas de forma instantânea e segura.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Validação de documentos</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Dados cadastrais completos</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Verificação de situação fiscal</span>
                        </li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div class="feature-box bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Segurança de Dados</h3>
                    <p class="text-gray-600 mb-4">Suas consultas são protegidas pelos mais avançados protocolos de segurança do mercado.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Criptografia de ponta a ponta</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Conformidade com LGPD</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Auditoria de acessos</span>
                        </li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="feature-box bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-bolt text-purple-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Alta Performance</h3>
                    <p class="text-gray-600 mb-4">Respostas rápidas e confiáveis, mesmo em momentos de alto volume de consultas.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Tempo de resposta < 0.2s</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Disponibilidade de 99.9%</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Infraestrutura escalável</span>
                        </li>
                    </ul>
                </div>

                <!-- Feature 4 -->
                <div class="feature-box bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-amber-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Relatórios e Estatísticas</h3>
                    <p class="text-gray-600 mb-4">Acompanhe o uso da plataforma e obtenha insights valiosos sobre suas consultas.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Dashboard personalizado</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Histórico de consultas</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Exportação de dados</span>
                        </li>
                    </ul>
                </div>

                <!-- Feature 5 -->
                <div class="feature-box bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-headset text-red-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Suporte Especializado</h3>
                    <p class="text-gray-600 mb-4">Equipe técnica pronta para ajudar em qualquer questão que surgir durante o uso.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Atendimento em horário comercial</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Documentação completa</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Tutoriais e treinamentos</span>
                        </li>
                    </ul>
                </div>

                <!-- Feature 6 -->
                <div class="feature-box bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="600">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-code text-indigo-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">API Intuitiva</h3>
                    <p class="text-gray-600 mb-4">Fácil integração com seus sistemas existentes, sem dores de cabeça.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Documentação clara e completa</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Endpoints RESTful</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>SDKs para diversas linguagens</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-white relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-40 bg-gradient-to-b from-gray-50 to-white"></div>
        <!-- Background pattern -->
        <div class="absolute inset-0 bg-hero-pattern opacity-5"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">O que nossos clientes dizem</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Veja como a GovNex tem transformado a forma como prefeituras e empresas acessam dados governamentais.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-4">
                        <div class="text-amber-400 text-lg">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-gray-600 text-sm">5.0</span>
                    </div>
                    <p class="text-gray-700 mb-6">"A GovNex revolucionou o processo de validação de documentos em nossa prefeitura. Antes, levávamos dias para verificar informações que agora são acessadas em segundos."</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Cliente" class="w-12 h-12 rounded-full">
                        <div class="ml-3">
                            <h4 class="font-semibold text-gray-900">Roberto Silva</h4>
                            <p class="text-sm text-gray-600">Secretário de Administração, Prefeitura de Palmas-TO</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-4">
                        <div class="text-amber-400 text-lg">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-gray-600 text-sm">5.0</span>
                    </div>
                    <p class="text-gray-700 mb-6">"A plataforma é intuitiva e os dados fornecidos são extremamente confiáveis. O suporte técnico sempre está disponível quando precisamos. Recomendo fortemente."</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Cliente" class="w-12 h-12 rounded-full">
                        <div class="ml-3">
                            <h4 class="font-semibold text-gray-900">Ana Luiza Mendes</h4>
                            <p class="text-sm text-gray-600">Diretora de TI, Prefeitura de Gurupi-TO</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card bg-white p-6 rounded-2xl shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-4">
                        <div class="text-amber-400 text-lg">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="ml-2 text-gray-600 text-sm">4.5</span>
                    </div>
                    <p class="text-gray-700 mb-6">"Conseguimos reduzir em mais de 70% o tempo de processamento de documentos fiscais. A GovNex proporcionou uma economia significativa de recursos para nossa prefeitura."</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/62.jpg" alt="Cliente" class="w-12 h-12 rounded-full">
                        <div class="ml-3">
                            <h4 class="font-semibold text-gray-900">Carlos Eduardo Fonseca</h4>
                            <p class="text-sm text-gray-600">Secretário de Finanças, Prefeitura de Araguaína-TO</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-center" data-aos="fade-up">
                <a href="#contato" class="inline-flex items-center justify-center px-6 py-3 border border-primary-500 text-primary-600 font-medium rounded-lg hover:bg-primary-50 transition-colors">
                    <span>Ver mais depoimentos</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Perguntas Frequentes</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Encontre respostas para as dúvidas mais comuns sobre nossos serviços.</p>
            </div>

            <div class="max-w-3xl mx-auto" x-data="{selected:null}">
                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                        <button 
                            @click="selected !== 1 ? selected = 1 : selected = null" 
                            class="flex items-center justify-between w-full p-5 text-left bg-white hover:bg-gray-50 transition-colors"
                        >
                            <span class="text-lg font-medium text-gray-900">Como funciona o sistema de créditos?</span>
                            <svg :class="{'rotate-180': selected == 1}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="selected == 1" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="p-5 border-t border-gray-200 bg-gray-50"
                        >
                            <p class="text-gray-700">Nosso sistema funciona com base em créditos pré-pagos. Cada consulta consome um valor específico de créditos, dependendo do tipo de informação acessada. Você pode adquirir pacotes de créditos conforme sua necessidade, e eles não expiram enquanto sua conta estiver ativa.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden" data-aos="fade-up" data-aos-delay="150">
                        <button 
                            @click="selected !== 2 ? selected = 2 : selected = null" 
                            class="flex items-center justify-between w-full p-5 text-left bg-white hover:bg-gray-50 transition-colors"
                        >
                            <span class="text-lg font-medium text-gray-900">Quais dados posso consultar na plataforma?</span>
                            <svg :class="{'rotate-180': selected == 2}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="selected == 2" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="p-5 border-t border-gray-200 bg-gray-50"
                        >
                            <p class="text-gray-700">Nossa plataforma oferece consultas de CPF, CNPJ, endereço, situação cadastral, dados de contato, entre outras informações. Todos os dados são obtidos de fontes oficiais e estão em conformidade com a LGPD e demais regulamentações aplicáveis.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                        <button 
                            @click="selected !== 3 ? selected = 3 : selected = null" 
                            class="flex items-center justify-between w-full p-5 text-left bg-white hover:bg-gray-50 transition-colors"
                        >
                            <span class="text-lg font-medium text-gray-900">Como garantem a segurança dos dados?</span>
                            <svg :class="{'rotate-180': selected == 3}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="selected == 3" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="p-5 border-t border-gray-200 bg-gray-50"
                        >
                            <p class="text-gray-700">Utilizamos tecnologias de ponta para garantir a proteção dos dados, incluindo criptografia de ponta a ponta, autenticação multi-fator, monitoramento contínuo e auditorias de segurança regulares. Nossa infraestrutura está em conformidade com os mais altos padrões de segurança do mercado.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden" data-aos="fade-up" data-aos-delay="250">
                        <button 
                            @click="selected !== 4 ? selected = 4 : selected = null" 
                            class="flex items-center justify-between w-full p-5 text-left bg-white hover:bg-gray-50 transition-colors"
                        >
                            <span class="text-lg font-medium text-gray-900">Posso integrar a API com meus sistemas?</span>
                            <svg :class="{'rotate-180': selected == 4}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="selected == 4" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="p-5 border-t border-gray-200 bg-gray-50"
                        >
                            <p class="text-gray-700">Sim, nossa API é totalmente integrável com seus sistemas existentes. Fornecemos documentação detalhada, SDKs para diversas linguagens de programação e suporte técnico para auxiliar no processo de integração.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="border border-gray-200 rounded-xl overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                        <button 
                            @click="selected !== 5 ? selected = 5 : selected = null" 
                            class="flex items-center justify-between w-full p-5 text-left bg-white hover:bg-gray-50 transition-colors"
                        >
                            <span class="text-lg font-medium text-gray-900">Como iniciar na plataforma?</span>
                            <svg :class="{'rotate-180': selected == 5}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div 
                            x-show="selected == 5" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="p-5 border-t border-gray-200 bg-gray-50"
                        >
                            <p class="text-gray-700">Começar é simples! Basta criar uma conta gratuita, escolher o plano mais adequado para sua necessidade, adquirir créditos e começar a realizar consultas. Oferecemos um período de teste para que você possa explorar todas as funcionalidades antes de adquirir um pacote completo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contato" class="py-20 bg-white relative">
        <div class="absolute top-0 inset-x-0 h-40 bg-gradient-to-b from-gray-50 to-white"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Entre em Contato</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Estamos à disposição para ajudar com suas dúvidas e fornecer todo o suporte necessário.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                <div class="rounded-2xl overflow-hidden shadow-xl" data-aos="fade-right">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3922.7282460432853!2d-49.0684393!3d-10.5182279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9324acb064e5e3d3%3A0xbb19d3b1af6c8511!2sGurupi%2C%20TO!5e0!3m2!1spt-BR!2sbr!4v1635190967253!5m2!1spt-BR!2sbr" 
                        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>

                <div class="space-y-8" data-aos="fade-left">

                    <div class="feature-box bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone-alt text-primary-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Telefone</h3>
                            <p class="text-gray-600">(63) 98101-3083</p>
                        </div>
                    </div>

                    <div class="feature-box bg-white p-6 rounded-2xl shadow-md border border-gray-100 flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-primary-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">govnexsuporte@gmail.com</p>
                        </div>
                    </div>
                    
                    <!-- Novo botão para formulário de interesse -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-6 rounded-2xl shadow-lg text-center">
                        <h3 class="text-lg font-semibold text-white mb-3">Tem interesse em utilizar o GovNex?</h3>
                        <p class="text-blue-100 mb-4">Preencha nosso formulário de interesse e receba mais informações sobre como o GovNex pode ajudar sua vigilância sanitária.</p>
                        <a href="/govnex/views/interesse/formulario.php" class="inline-flex items-center justify-center px-6 py-3 bg-white text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors shadow-md">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            <span>Preencher Formulário de Interesse</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-10">
                <div>
                    <h3 class="text-2xl font-bold mb-4 text-white flex items-center space-x-1">
                        <span class="text-3xl"><i class="fas fa-poll"></i></span>
                        <span>GovNex</span>
                    </h3>
                    <p class="text-gray-400 mb-4">Transformando o acesso a dados governamentais com tecnologia e inovação.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#hero" class="text-gray-400 hover:text-white transition-colors">Início</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Recursos</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-white transition-colors">Preços</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-white transition-colors">Depoimentos</a></li>
                        <li><a href="#faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#contato" class="text-gray-400 hover:text-white transition-colors">Contato</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Serviços</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Consulta de CPF</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Consulta de CNPJ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Validação de Documentos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Situação Cadastral</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">API de Consultas</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Integrações</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Inscreva-se</h4>
                    <p class="text-gray-400 mb-4">Receba novidades e ofertas exclusivas.</p>
                    <form class="space-y-2">
                        <div>
                            <input type="email" placeholder="Seu e-mail" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-white">
                        </div>
                        <button type="submit" class="w-full px-4 py-3 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                            Inscrever-se
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">&copy; 2023 GovNex. Todos os direitos reservados.</p>
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Termos de Uso</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Política de Privacidade</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Cookies</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <button
        id="back-to-top"
        class="fixed bottom-8 right-8 bg-primary-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transform transition-transform hover:scale-110 opacity-0"
    >
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Initialize AOS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            once: true
        });

        // Back to top button
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopButton = document.getElementById('back-to-top');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('opacity-100');
                    backToTopButton.classList.remove('opacity-0');
                } else {
                    backToTopButton.classList.add('opacity-0');
                    backToTopButton.classList.remove('opacity-100');
                }
            });
            
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Staggered animations for lists
            const staggeredElements = document.querySelectorAll('.stagger-in');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('appear');
                    }
                });
            }, { threshold: 0.1 });

            staggeredElements.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>

</html>