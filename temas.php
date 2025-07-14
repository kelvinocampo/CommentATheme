<?php
session_start();
require 'config/db.php';

$busqueda = $_GET['busqueda'] ?? '';

// Buscar por nombre o descripción
if (!empty($busqueda)) {
    $stmt = $pdo->prepare("SELECT * FROM tema WHERE nombre LIKE ? OR descripcion LIKE ?");
    $stmt->execute(["%$busqueda%", "%$busqueda%"]);
    $temas = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT * FROM tema");
    $temas = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Temas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen font-sans">
    <?php include 'partials/header.php'; ?>

    <main class="max-w-6xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">📚 Temas disponibles</h1>

        <!-- Buscador -->
        <form method="GET" class="mb-8 flex flex-col sm:flex-row items-center gap-4">
            <input type="text" name="busqueda" placeholder="Buscar por nombre o descripción"
                value="<?= htmlspecialchars($busqueda) ?>"
                class="w-full sm:w-2/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                🔍 Buscar
            </button>
        </form>

        <?php if (empty($temas)): ?>
            <p class="text-center text-gray-500">No se encontraron temas.</p>
        <?php else: ?>
            <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($temas as $tema): ?>
                    <li>
                        <a href="tema.php?id=<?= $tema['tema_id'] ?>"
                            class="block bg-white rounded-xl shadow hover:shadow-lg transition duration-300 p-6 h-full relative">
                            <h2 class="text-xl font-semibold mb-2 <?= $tema['activo'] ? 'text-blue-700' : 'text-gray-400' ?>">
                                <?= htmlspecialchars($tema['nombre']) ?>
                            </h2>
                            <p class="text-sm <?= $tema['activo'] ? 'text-gray-600' : 'text-gray-400' ?>">
                                <?= htmlspecialchars($tema['descripcion']) ?>
                            </p>

                            <!-- Etiqueta de estado -->
                            <div class="absolute top-4 right-4 text-xs px-2 py-1 rounded-full 
                    <?= $tema['activo'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= $tema['activo'] ? 'Activo' : 'Inactivo' ?>
                            </div>
                        </a>

                        <!-- Botón para admin -->
                        <?php if (isset($_SESSION['nombre']) && $_SESSION['rol'] === 'administrador'): ?>
                            <form action="toggle_tema.php" method="POST" class="mt-2 text-right">
                                <input type="hidden" name="tema_id" value="<?= $tema['tema_id'] ?>">
                                <input type="hidden" name="accion" value="<?= $tema['activo'] ? 'desactivar' : 'activar' ?>">
                                <button type="submit"
                                    class="<?= $tema['activo'] ? 'text-red-500' : 'text-green-600' ?> hover:underline text-sm">
                                    <?= $tema['activo'] ? '🚫 Desactivar' : '✅ Activar' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php endif; ?>
    </main>
</body>

</html>