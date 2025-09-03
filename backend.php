<?php
header('Content-Type: application/json; charset=utf-8');

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

// Intentar decodificar JSON
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// Validación básica de formato
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "JSON inválido"]);
    exit;
}

// Extraer parámetros
$username = isset($data['username']) ? trim($data['username']) : '';
$password = isset($data['password']) ? $data['password'] : '';

// Reglas de verificación backend (ejemplo)
$errors = [];

if ($username === '' || strlen($username) < 3 || strlen($username) > 20) {
    $errors[] = "El nombre de usuario debe tener entre 3 y 20 caracteres.";
}

if ($password === '' || strlen($password) < 6 || strlen($password) > 50) {
    $errors[] = "La contraseña debe tener entre 6 y 50 caracteres.";
}

// (Opcional) Reglas extra: solo letras/números/guiones bajos
if ($username !== '' && !preg_match('/^[A-Za-z0-9_]+$/', $username)) {
    $errors[] = "El nombre de usuario solo puede contener letras, números y guion bajo.";
}

// Devolver resultado
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Validación fallida", "errors" => $errors], JSON_UNESCAPED_UNICODE);
    exit;
}

// Si todo ok, responder éxito (simulado)
echo json_encode([
    "status"  => "ok",
    "message" => "Datos recibidos correctamente",
    "user"    => ["username" => $username]
], JSON_UNESCAPED_UNICODE);
