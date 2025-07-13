<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['nombre']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $stmt = $pdo->prepare("INSERT INTO tema (nombre, descripcion) VALUES (?, ?)");
    $stmt->execute([$nombre, $descripcion]);
    header("Location: temas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Tema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <?php include 'partials/header.php'; ?>

    <main class="max-w-2xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-xl p-8">
            <h1 class="text-3xl font-extrabold text-green-700 mb-6 text-center">📝 Crear nuevo tema</h1>

            <form method="POST" class="space-y-6">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del tema</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        required
                        placeholder="Ej: Programación en PHP"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                    >
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="4"
                        required
                        placeholder="Describe brevemente el tema que deseas crear..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-green-400"
                    ></textarea>
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition shadow">
                        ➕ Crear tema
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
