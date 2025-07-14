<?php
require_once 'load_env.php';
loadEnv(__DIR__ . "/../.env");

$uri = getenv("DB_URI");

$parsed = parse_url($uri);

$host = $parsed["host"];
$port = $parsed["port"];
$user = $parsed["user"];
$pass = $parsed["pass"];
$db   = ltrim($parsed["path"], '/');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
