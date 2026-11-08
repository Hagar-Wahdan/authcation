<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../config/session.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request method.", null, 405);
}

if (!isset($_SESSION["user_id"])) {
    sendResponse(false, "Please login first.", null, 401);
}

$userId = $_SESSION["user_id"];

$fullName = trim($_POST["full_name"] ?? "");
$email = strtolower(trim($_POST["email"] ?? ""));
$phone = trim($_POST["phone"] ?? "");

if ($fullName === "" || $email === "" || $phone === "") {
    sendResponse(false, "All fields are required.", null, 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, "Invalid email address.", null, 422);
}

if (strlen($fullName) > 100 || strlen($email) > 150 || strlen($phone) > 20) {
    sendResponse(false, "One or more fields are too long.", null, 422);
}

try {

    // Check if email or phone belongs to another user
    $check = $pdo->prepare(
        "SELECT id FROM users
         WHERE (email = :email OR phone = :phone)
         AND id != :id"
    );

    $check->execute([
        ":email" => $email,
        ":phone" => $phone,
        ":id" => $userId
    ]);

    if ($check->fetch()) {
        sendResponse(
            false,
            "Email or phone number is already used by another account.",
            null,
            409
        );
    }

    // Update user data
    $stmt = $pdo->prepare(
        "UPDATE users
         SET full_name = :full_name,
             email = :email,
             phone = :phone
         WHERE id = :id"
    );

    $stmt->execute([
        ":full_name" => $fullName,
        ":email" => $email,
        ":phone" => $phone,
        ":id" => $userId
    ]);

    sendResponse(true, "Profile updated successfully.");

} catch (PDOException $e) {

    error_log($e->getMessage());

    sendResponse(false, "Something went wrong.", null, 500);
}