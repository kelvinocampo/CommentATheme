<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    http_response_code(403);
    exit('Acceso no autorizado');
}

$tema_id = $_POST['tema_id'] ?? null;
$accion = $_POST['accion'] ?? '';

if ($tema_id && in_array($accion, ['activar', 'desactivar'])) {
    $estado = $accion === 'activar' ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE tema SET activo = ? WHERE tema_id = ?");
    $stmt->execute([$estado, $tema_id]);
}

header('Location: temas.php');
exit;
