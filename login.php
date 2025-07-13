<?php
session_start();
require 'config/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['usuario_id'] = $usuario['usuario_id'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-100 via-white to-blue-100 flex items-center justify-center min-h-screen px-4">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-3xl font-extrabold text-center text-blue-700 mb-6">🔐 Iniciar sesión</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Correo electrónico</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    placeholder="tucorreo@ejemplo.com"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Contraseña</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    placeholder="********"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-semibold py-2 rounded-lg">
                Iniciar sesión
            </button>

            <p class="text-sm text-center mt-4 text-gray-600">
                ¿No tienes una cuenta?
                <a href="register.php" class="text-blue-600 hover:underline font-medium">Regístrate aquí</a>
            </p>
        </form>
    </div>

</body>

</html>
