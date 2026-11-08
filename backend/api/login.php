<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/session.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request method.", null, 405);
}

$email = strtolower(trim($_POST["email"] ?? ""));
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    sendResponse(false, "Email and password are required.", null, 422);
}

try {

    $stmt = $pdo->prepare(
        "SELECT id, full_name, email, phone, password
         FROM users
         WHERE email = :email"
    );

    $stmt->execute([":email" => $email]);

    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user["password"])) {
        sendResponse(false, "Invalid email or password.", null, 401);
    }

    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["id"];

    unset($user["password"]);

    sendResponse(true, "Login successful.", $user);

} catch (PDOException $e) {

    error_log($e->getMessage());

    sendResponse(false, "Something went wrong.", null, 500);
}