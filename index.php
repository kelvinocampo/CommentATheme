<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit;
}

$nombre = htmlspecialchars($_SESSION['nombre']);
$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <?php include 'partials/header.php'; ?>

    <main class="max-w-5xl mx-auto mt-12 px-4 sm:px-6 lg:px-8">
        <!-- Saludo -->
        <section class="bg-white shadow-md rounded-2xl p-6 sm:p-10 text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-2 sm:mb-4">¡Hola, <?= $nombre ?>!</h1>
            <p class="text-base sm:text-lg text-gray-600">
                Rol: <span class="font-semibold text-blue-600"><?= $rol ?></span>
            </p>
        </section>

        <!-- Acciones -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <a href="temas.php" class="bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-4 px-6 rounded-xl shadow transition duration-200">
                📚 Ver temas y comentar
            </a>

            <?php if ($rol === 'administrador'): ?>
                <a href="crear_tema.php" class="bg-green-600 hover:bg-green-700 text-white text-center font-medium py-4 px-6 rounded-xl shadow transition duration-200">
                    📝 Crear nuevo tema
                </a>

                <a href="admin_users.php" class="bg-purple-600 hover:bg-purple-700 text-white text-center font-medium py-4 px-6 rounded-xl shadow transition duration-200">
                    🔧 Gestionar usuarios
                </a>
            <?php endif; ?>

            <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white text-center font-medium py-4 px-6 rounded-xl shadow transition duration-200">
                🔒 Cerrar sesión
            </a>
        </section>
    </main>

</body>
</html>
