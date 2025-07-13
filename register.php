<?php
require 'config/db.php';
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $error = "Este correo ya está registrado.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuario (nombre, email, password) VALUES (?, ?, ?)");
        $success = $stmt->execute([$nombre, $email, $password]);

        if ($success) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Ocurrió un error al registrarse.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-md rounded-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Crear cuenta</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" id="nombre" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition text-sm font-semibold">
                ✅ Registrarse
            </button>

            <p class="text-sm text-center mt-4 text-gray-500">
                ¿Ya tienes una cuenta?
                <a href="login.php" class="text-blue-600 hover:underline font-medium">Inicia sesión</a>
            </p>
        </form>
    </div>

</body>
</html>
