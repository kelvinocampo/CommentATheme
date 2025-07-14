<?php
session_start();
require 'config/db.php';

if (!isset($_GET['id'])) {
    header("Location: temas.php");
    exit;
}

$tema_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM tema WHERE tema_id = ?");
$stmt->execute([$tema_id]);
$tema = $stmt->fetch();

if (!$tema) {
    echo "Tema no encontrado";
    exit;
}

// Guardar nuevo comentario o respuesta
if ($tema['activo'] && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['nombre'])) {
    if (isset($_POST['contenido'])) {
        $contenido = $_POST['contenido'];
        $respondido_a = !empty($_POST['respondido_a']) ? $_POST['respondido_a'] : null;
        $stmt = $pdo->prepare("INSERT INTO comentario (tema_id, usuario_id, contenido, respondido_a) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tema_id, $_SESSION['usuario_id'], $contenido, $respondido_a]);
        header("Location: tema.php?id=" . $tema_id);
        exit;
    }

    if (isset($_POST['editar_id']) && isset($_POST['nuevo_contenido'])) {
        $editar_id = $_POST['editar_id'];
        $nuevo_contenido = $_POST['nuevo_contenido'];
        $stmt = $pdo->prepare("UPDATE comentario SET contenido = ? WHERE comentario_id = ? AND usuario_id = ?");
        $stmt->execute([$nuevo_contenido, $editar_id, $_SESSION['usuario_id']]);
        header("Location: tema.php?id=" . $tema_id);
        exit;
    }
}

if ($tema['activo'] && isset($_GET['eliminar']) && isset($_SESSION['usuario_id'])) {
    $eliminar_id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM comentario WHERE comentario_id = ? AND usuario_id = ?");
    $stmt->execute([$eliminar_id, $_SESSION['usuario_id']]);
    header("Location: tema.php?id=" . $tema_id);
    exit;
}

$stmt = $pdo->prepare("SELECT c.*, u.nombre FROM comentario c JOIN usuario u ON c.usuario_id = u.usuario_id WHERE c.tema_id = ? AND c.respondido_a IS NULL ORDER BY c.creado_en DESC");
$stmt->execute([$tema_id]);
$comentarios_padres = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT c.*, u.nombre FROM comentario c JOIN usuario u ON c.usuario_id = u.usuario_id WHERE c.tema_id = ? AND c.respondido_a IS NOT NULL");
$stmt->execute([$tema_id]);
$respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$respuestasAgrupadas = [];
foreach ($respuestas as $r) {
    $respuestasAgrupadas[$r['respondido_a']][] = $r;
}

function puedeEditar($creado_en)
{
    return strtotime($creado_en) + 900 > time(); // 15 minutos
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($tema['nombre']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleResponder(id) {
            document.querySelectorAll('.form-respuesta').forEach(f => f.classList.add('hidden'));
            document.getElementById('form-' + id).classList.toggle('hidden');
        }

        function toggleEditar(id) {
            document.querySelectorAll('.form-editar').forEach(f => f.classList.add('hidden'));
            document.getElementById('edit-' + id).classList.toggle('hidden');
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen font-sans">
    <?php include 'partials/header.php'; ?>

    <main class="max-w-4xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-xl p-6 sm:p-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-blue-700 mb-2"><?= htmlspecialchars($tema['nombre']) ?></h1>
            <p class="text-gray-700 mb-6 text-sm sm:text-base"><?= htmlspecialchars($tema['descripcion']) ?></p>

            <?php if (!$tema['activo']): ?>
                <div class="mb-8 p-4 bg-yellow-100 text-yellow-800 text-sm rounded">
                    Este tema está inactivo. No se permiten nuevos comentarios ni respuestas.
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['nombre']) && $tema['activo']): ?>
                <form method="POST" class="space-y-4 mb-10">
                    <input type="hidden" name="respondido_a" value="">
                    <textarea name="contenido" required class="w-full p-3 border rounded-lg resize-none focus:ring-2 focus:ring-blue-400 text-sm" placeholder="Escribe tu comentario..."></textarea>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">💬 Comentar</button>
                </form>
            <?php endif; ?>

            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4">🗨️ Comentarios</h2>

            <?php if ($comentarios_padres): ?>
                <ul class="space-y-6">
                    <?php foreach ($comentarios_padres as $comentario): ?>
                        <li class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-2">
                                <p class="text-sm text-gray-500">
                                    <strong><?= htmlspecialchars($comentario['nombre']) ?></strong> |
                                    <?= $comentario['creado_en'] ?>
                                </p>
                                <?php if ($tema['activo']): ?>
                                    <div class="flex flex-wrap gap-2 text-sm mt-2 sm:mt-0">
                                        <?php if ($_SESSION['usuario_id'] == $comentario['usuario_id']): ?>
                                            <?php if (puedeEditar($comentario['creado_en'])): ?>
                                                <button onclick="toggleEditar(<?= $comentario['comentario_id'] ?>)" class="text-blue-600 hover:underline">✏️ Editar</button>
                                            <?php endif; ?>
                                            <a href="?id=<?= $tema_id ?>&eliminar=<?= $comentario['comentario_id'] ?>" class="text-red-600 hover:underline">🗑️ Eliminar</a>
                                        <?php endif; ?>
                                        <button onclick="toggleResponder(<?= $comentario['comentario_id'] ?>)" class="text-green-600 hover:underline">↪️ Responder</button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <p class="text-gray-800 text-sm sm:text-base"><?= htmlspecialchars($comentario['contenido']) ?></p>

                            <!-- Editar -->
                            <form id="edit-<?= $comentario['comentario_id'] ?>" method="POST" class="form-editar hidden mt-3 space-y-2">
                                <input type="hidden" name="editar_id" value="<?= $comentario['comentario_id'] ?>">
                                <textarea name="nuevo_contenido" class="w-full p-2 border rounded-lg resize-none text-sm focus:ring-2 focus:ring-yellow-400"><?= htmlspecialchars($comentario['contenido']) ?></textarea>
                                <button type="submit" class="bg-yellow-500 text-white px-4 py-1 rounded hover:bg-yellow-600 transition text-sm">💾 Guardar</button>
                            </form>

                            <!-- Responder -->
                            <form id="form-<?= $comentario['comentario_id'] ?>" method="POST" class="form-respuesta hidden mt-3 space-y-2">
                                <input type="hidden" name="respondido_a" value="<?= $comentario['comentario_id'] ?>">
                                <textarea name="contenido" required class="w-full p-2 border rounded-lg resize-none text-sm focus:ring-2 focus:ring-green-400" placeholder="Responder a este comentario..."></textarea>
                                <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700 transition text-sm">Responder</button>
                            </form>

                            <!-- Respuestas -->
                            <?php if (isset($respuestasAgrupadas[$comentario['comentario_id']])): ?>
                                <ul class="ml-4 mt-3 space-y-3 border-l-4 border-blue-200 pl-4">
                                    <?php foreach ($respuestasAgrupadas[$comentario['comentario_id']] as $respuesta): ?>
                                        <li class="bg-blue-50 p-3 rounded-lg">
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-1">
                                                <p class="text-sm text-blue-700 font-semibold">
                                                    <?= htmlspecialchars($respuesta['nombre']) ?> respondió el <?= $respuesta['creado_en'] ?>
                                                </p>
                                                <?php if ($tema['activo'] && $_SESSION['usuario_id'] == $respuesta['usuario_id']): ?>
                                                    <div class="flex flex-wrap gap-2 mt-1 sm:mt-0 text-sm">
                                                        <?php if (puedeEditar($respuesta['creado_en'])): ?>
                                                            <button onclick="toggleEditar(<?= $respuesta['comentario_id'] ?>)" class="text-blue-600 hover:underline">✏️ Editar</button>
                                                        <?php endif; ?>
                                                        <a href="?id=<?= $tema_id ?>&eliminar=<?= $respuesta['comentario_id'] ?>" class="text-red-600 hover:underline">🗑️ Eliminar</a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <p class="text-gray-800 text-sm sm:text-base"><?= htmlspecialchars($respuesta['contenido']) ?></p>

                                            <!-- Editar respuesta -->
                                            <form id="edit-<?= $respuesta['comentario_id'] ?>" method="POST" class="form-editar hidden mt-2 space-y-2">
                                                <input type="hidden" name="editar_id" value="<?= $respuesta['comentario_id'] ?>">
                                                <textarea name="nuevo_contenido" class="w-full p-2 border rounded-lg resize-none text-sm focus:ring-2 focus:ring-yellow-400"><?= htmlspecialchars($respuesta['contenido']) ?></textarea>
                                                <button type="submit" class="bg-yellow-500 text-white px-4 py-1 rounded hover:bg-yellow-600 transition text-sm">💾 Guardar</button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500">No hay comentarios aún. ¡Sé el primero en participar!</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>