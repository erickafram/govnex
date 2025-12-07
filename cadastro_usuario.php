<?php
require_once 'conf/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $empresa = isset($_POST['empresa']) ? $_POST['empresa'] : '';
    $termos = isset($_POST['termos']) ? $_POST['termos'] : '';

    // Validação básica
    if (empty($nome) || empty($email) || empty($cpf) || empty($senha)) {
        $erro = "Todos os campos obrigatórios devem ser preenchidos!";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif (!isset($_POST['termos'])) {
        $erro = "Você precisa aceitar os termos de uso para continuar!";
    } else {
        try {
            $database = new Database();
            $conn = $database->getConnection();

            // Verificar se email ou CPF já existem
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email OR cpf = :cpf");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $erro = "Email ou CPF já cadastrado!";
            } else {
                // Hash da senha
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                // Inserir novo usuário
                $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, telefone, cpf, senha, empresa) VALUES (:nome, :email, :telefone, :cpf, :senha, :empresa)");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':senha', $senhaHash);
                $stmt->bindParam(':empresa', $empresa);
                $stmt->execute();

                // Definir a mensagem de sucesso na sessão antes de redirecionar
                session_start();
                $_SESSION['cadastro_sucesso'] = true;
                header('Location: login_usuario.php');
                exit;
            }
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}

$page_title = "Cadastre-se | GovNex";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
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
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards'
                    }
                }
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00');
            $('#telefone').mask('(00) 00000-0000');
            
            // Password strength meter
            $('#senha').on('input', function() {
                const password = $(this).val();
                const meter = $('#password-strength');
                
                // Calculate strength
                let strength = 0;
                if (password.length >= 8) strength += 1;
                if (password.match(/[a-z]+/)) strength += 1;
                if (password.match(/[A-Z]+/)) strength += 1;
                if (password.match(/[0-9]+/)) strength += 1;
                if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
                
                // Update UI
                switch(strength) {
                    case 0:
                    case 1:
                        meter.css('width', '20%').removeClass().addClass('bg-red-500');
                        $('#password-text').text('Fraca').removeClass().addClass('text-red-500');
                        break;
                    case 2:
                    case 3:
                        meter.css('width', '60%').removeClass().addClass('bg-yellow-500');
                        $('#password-text').text('Média').removeClass().addClass('text-yellow-500');
                        break;
                    case 4:
                    case 5:
                        meter.css('width', '100%').removeClass().addClass('bg-green-500');
                        $('#password-text').text('Forte').removeClass().addClass('text-green-500');
                        break;
                }
            });
            
            // Show/hide password
            $('.toggle-password').click(function() {
                const input = $($(this).data('toggle'));
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                } else {
                    input.attr('type', 'password');
                    $(this).html('<i class="fas fa-eye"></i>');
                }
            });
            
            // Form steps
            let currentStep = 1;
            const totalSteps = 2;
            
            // Show only first step initially
            showStep(currentStep);
            
            $('#next-step').click(function(e) {
                e.preventDefault();
                // Validate first step fields
                if (validateStep(1)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
            
            $('#prev-step').click(function(e) {
                e.preventDefault();
                currentStep--;
                showStep(currentStep);
            });
            
            function showStep(step) {
                // Hide all steps
                $('.step').hide();
                
                // Show the current step
                $(`#step-${step}`).show();
                
                // Update progress
                const progress = ((step - 1) / (totalSteps - 1)) * 100;
                $('#progress-bar').css('width', `${progress}%`);
                
                // Update buttons
                if (step === 1) {
                    $('#prev-step').hide();
                    $('#next-step').show();
                    $('#submit-button').hide();
                } else if (step === totalSteps) {
                    $('#prev-step').show();
                    $('#next-step').hide();
                    $('#submit-button').show();
                } else {
                    $('#prev-step').show();
                    $('#next-step').show();
                    $('#submit-button').hide();
                }
                
                // Animate the current step
                $(`#step-${step}`).css('opacity', 0);
                setTimeout(() => {
                    $(`#step-${step}`).css('opacity', 1);
                }, 50);
            }
            
            function validateStep(step) {
                let isValid = true;
                
                if (step === 1) {
                    // Validate first step fields
                    if ($('#nome').val() === '') {
                        $('#nome').addClass('border-red-500');
                        $('#nome-error').text('Nome é obrigatório').show();
                        isValid = false;
                    } else {
                        $('#nome').removeClass('border-red-500');
                        $('#nome-error').hide();
                    }
                    
                    // Validate email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if ($('#email').val() === '' || !emailRegex.test($('#email').val())) {
                        $('#email').addClass('border-red-500');
                        $('#email-error').text('Email válido é obrigatório').show();
                        isValid = false;
                    } else {
                        $('#email').removeClass('border-red-500');
                        $('#email-error').hide();
                    }
                    
                    // Validate CPF
                    if ($('#cpf').val() === '' || $('#cpf').val().length < 14) {
                        $('#cpf').addClass('border-red-500');
                        $('#cpf-error').text('CPF válido é obrigatório').show();
                        isValid = false;
                    } else {
                        $('#cpf').removeClass('border-red-500');
                        $('#cpf-error').hide();
                    }
                }
                
                return isValid;
            }
        });
    </script>
    <style>
        .form-input {
            @apply w-full px-4 py-3 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors;
            border: 1px solid #e5e7eb;
        }
        
        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }
        
        .error-text {
            @apply text-red-500 text-xs mt-1 hidden;
        }
        
        .step {
            transition: all 0.3s ease;
        }
        
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.4;
            z-index: -1;
            animation: blob 7s infinite;
        }
        
        @keyframes blob {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }
    </style>
</head>
<body class="font-sans bg-gradient-to-b from-blue-50 to-white">
    <!-- Back Navigation -->
    <header class="w-full py-4 px-6 bg-white shadow-sm">
        <div class="container mx-auto max-w-4xl flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-primary-500">GovNex</a>
            <a href="index.php" class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-primary-500 transition-colors">
                <i class="fas fa-arrow-left"></i> Voltar para o início
            </a>
        </div>
    </header>

    <div class="min-h-screen pt-10 pb-16 px-4 relative overflow-hidden">
        <!-- Background blobs -->
        <div class="absolute top-40 left-20 w-72 h-72 bg-primary-200 blob"></div>
        <div class="absolute bottom-0 right-20 w-64 h-64 bg-purple-200 blob" style="animation-delay: 2s;"></div>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden" data-aos="fade-up">
                <!-- Form header -->
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 py-6 px-8 text-white">
                    <h1 class="text-2xl font-bold">Criar uma Conta</h1>
                    <p class="text-blue-100 mt-1">Junte-se à plataforma GovNex para acesso a dados governamentais</p>
                    
                    <!-- Progress bar -->
                    <div class="w-full bg-blue-200 h-2 rounded-full mt-6 overflow-hidden">
                        <div id="progress-bar" class="bg-white h-full rounded-full w-0 transition-all duration-300"></div>
                    </div>
                </div>
                
                <div class="p-8">
                    <?php if (isset($erro)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-md" data-aos="fade-up">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800"><?php echo $erro; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="signup-form">
                        <!-- Step 1: Personal Information -->
                        <div id="step-1" class="step space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nome" class="form-label">Nome Completo <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" id="nome" name="nome" class="form-input pl-10 bg-gray-50" value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>">
                                    </div>
                                    <p id="nome-error" class="error-text"></p>
                                </div>
                                <div>
                                    <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" id="email" name="email" class="form-input pl-10 bg-gray-50" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                                    </div>
                                    <p id="email-error" class="error-text"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="cpf" class="form-label">CPF <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-id-card text-gray-400"></i>
                                        </div>
                                        <input type="text" id="cpf" name="cpf" class="form-input pl-10 bg-gray-50" value="<?php echo isset($cpf) ? htmlspecialchars($cpf) : ''; ?>">
                                    </div>
                                    <p id="cpf-error" class="error-text"></p>
                                </div>
                                <div>
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                        <input type="tel" id="telefone" name="telefone" class="form-input pl-10 bg-gray-50" value="<?php echo isset($telefone) ? htmlspecialchars($telefone) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="empresa" class="form-label">Prefeitura/Empresa (opcional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                    <input type="text" id="empresa" name="empresa" class="form-input pl-10 bg-gray-50" value="<?php echo isset($empresa) ? htmlspecialchars($empresa) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 2: Security Information -->
                        <div id="step-2" class="step space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="senha" class="form-label">Senha <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" id="senha" name="senha" class="form-input pl-10 pr-10 bg-gray-50">
                                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 toggle-password" data-toggle="#senha">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Password strength meter -->
                                    <div class="mt-2">
                                        <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div id="password-strength" class="h-full w-0 transition-all duration-300"></div>
                                        </div>
                                        <div class="flex justify-end mt-1">
                                            <span id="password-text" class="text-xs"></span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="confirmar_senha" class="form-label">Confirmar Senha <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-input pl-10 pr-10 bg-gray-50">
                                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 toggle-password" data-toggle="#confirmar_senha">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <p class="text-sm text-blue-800 mb-2 font-medium">Sua senha deve conter:</p>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> No mínimo 8 caracteres</li>
                                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Pelo menos uma letra maiúscula</li>
                                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Pelo menos uma letra minúscula</li>
                                    <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Pelo menos um número</li>
                                </ul>
                            </div>
                            
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="termos" class="rounded border-gray-300 text-primary-500 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" <?php echo isset($termos) ? 'checked' : ''; ?>>
                                    <span class="ml-2 text-sm text-gray-700">Eu li e aceito os <a href="#" class="text-primary-600 hover:underline">Termos de Uso</a> e <a href="#" class="text-primary-600 hover:underline">Política de Privacidade</a></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Form Navigation -->
                        <div class="mt-8 flex justify-between">
                            <button id="prev-step" type="button" class="px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i> Anterior
                            </button>
                            
                            <div class="flex space-x-3">
                                <a href="login_usuario.php" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                    Já tem conta? Entrar
                                </a>
                                
                                <button id="next-step" type="button" class="px-6 py-3 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    Próximo <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                                
                                <button id="submit-button" type="submit" class="px-6 py-3 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors hidden">
                                    Cadastrar <i class="fas fa-user-plus ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Benefits -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                    <div class="w-12 h-12 bg-primary-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-primary-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Segurança Garantida</h3>
                    <p class="text-gray-600 text-sm">Seus dados estão protegidos com criptografia de última geração.</p>
                </div>
                
                <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-bolt text-green-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Acesso Instantâneo</h3>
                    <p class="text-gray-600 text-sm">Comece a usar os serviços imediatamente após a confirmação.</p>
                </div>
                
                <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-headset text-purple-500 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Suporte Dedicado</h3>
                    <p class="text-gray-600 text-sm">Nossa equipe está pronta para ajudar você em cada etapa.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Initialize AOS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>