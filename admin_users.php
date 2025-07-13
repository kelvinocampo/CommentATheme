<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['nombre']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['promote_id'])) {
    $stmt = $pdo->prepare("UPDATE usuario SET rol = 'administrador' WHERE usuario_id = ?");
    $stmt->execute([$_GET['promote_id']]);
    header("Location: admin_users.php");
    exit;
}

$busqueda = $_GET['busqueda'] ?? '';

// Buscar por nombre o email
if (!empty($busqueda)) {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE nombre LIKE ? OR email LIKE ?");
    $stmt->execute(["%$busqueda%", "%$busqueda%"]);
    $usuarios = $stmt->fetchAll();
} else {
    $usuarios = $pdo->query("SELECT * FROM usuario")->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <?php include 'partials/header.php'; ?>

    <main class="max-w-5xl mx-auto mt-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center">
            👥 Gestión de usuarios
        </h1>

        <!-- Formulario de búsqueda -->
        <form method="GET" class="mb-6 flex flex-col sm:flex-row items-center gap-4">
            <input type="text" name="busqueda" placeholder="Buscar por nombre o correo"
                value="<?= htmlspecialchars($busqueda) ?>"
                class="w-full sm:w-2/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
            >
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
                🔍 Buscar
            </button>
        </form>

        <?php if (empty($usuarios)): ?>
            <p class="text-center text-gray-500">No se encontraron usuarios con ese criterio.</p>
        <?php else: ?>
            <ul class="space-y-4">
                <?php foreach ($usuarios as $u): ?>
                    <li class="bg-white shadow rounded-xl p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div class="flex flex-col">
                            <span class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($u['nombre']) ?></span>
                            <span class="text-sm text-gray-500"><?= htmlspecialchars($u['email']) ?></span>
                        </div>

                        <?php if ($u["rol"] === "administrador"): ?>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
                                ✅ Administrador
                            </span>
                        <?php else: ?>
                            <a href="?promote_id=<?= $u['usuario_id'] ?>"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition whitespace-nowrap">
                                ➕ Promover a admin
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>

</body>
</html>
