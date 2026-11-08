<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/session.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    sendResponse(false, "Invalid request method.", null, 405);
}

if (!isset($_SESSION["user_id"])) {
    sendResponse(false, "You are not logged in.", null, 401);
}

try {

    $stmt = $pdo->prepare(
        "SELECT id, full_name, email, phone, created_at
         FROM users
         WHERE id = :id"
    );

    $stmt->execute([
        ":id" => $_SESSION["user_id"]
    ]);

    $user = $stmt->fetch();

    if (!$user) {
        session_unset();
        session_destroy();

        sendResponse(false, "User not found.", null, 404);
    }

    sendResponse(true, "User data retrieved successfully.", $user);

} catch (PDOException $e) {

    error_log($e->getMessage());

    sendResponse(false, "Something went wrong.", null, 500);
}