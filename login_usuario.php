<?php
require_once 'conf/db_connection.php';

$erro = null; // Inicializa a variável de erro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $erro = "Email e senha são obrigatórios!";
    } else {
        try {
            $database = new Database();
            $conn = $database->getConnection();

            // Buscar usuário pelo email
            $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $usuario = $stmt->fetch();

                // Verificar senha
                if (password_verify($senha, $usuario['senha'])) {
                    // Iniciar sessão
                    session_start();
                    $_SESSION['usuario_id'] = $usuario['id'];
                    header('Location: /govnex/views/dashboard/index.php');
                    exit;
                } else {
                    $erro = "Credenciais inválidas!";
                }
            } else {
                $erro = "Credenciais inválidas!";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao fazer login: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0d6efd',
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md border border-gray-200">
        <div class="flex flex-col items-center mb-8 space-y-4">
            <a href="#" class="text-2xl font-bold text-primary">GovNex</a>
            <h1 class="text-2xl font-semibold text-gray-900">Acesse sua conta</h1>
            <p class="text-sm text-gray-500">Entre com seu email e senha</p>
        </div>

        <?php if (isset($erro)): ?>
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 flex items-start gap-3">
                <svg class="h-5 w-5 text-red-500 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm"><?php echo $erro; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="#" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="email" name="email" required
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="seu@email.com">
                </div>
            </div>

            <div>
                <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="senha" name="senha" required
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Entrar
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Não tem uma conta? <a href="cadastro_usuario.php"
                    class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">Cadastre-se</a></p>
        </div>

        <button onclick="window.location.href='index.php'"
            class="mt-4 w-full text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-md hover:bg-gray-100 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Voltar para o início
        </button>
    </div>
</body>

</html>