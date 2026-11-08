<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$fullName = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$password = $_POST["password"] ?? "";
$confirmPassword = $_POST["confirm_password"] ?? "";

// Validate required fields
if (
    $fullName === "" ||
    $email === "" ||
    $phone === "" ||
    $password === "" ||
    $confirmPassword === ""
) {
    http_response_code(422);
    echo json_encode([
        "success" => false,
        "message" => "All fields are required."
    ]);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode([
        "success" => false,
        "message" => "Invalid email address."
    ]);
    exit;
}

// Check password confirmation
if ($password !== $confirmPassword) {
    http_response_code(422);
    echo json_encode([
        "success" => false,
        "message" => "Passwords do not match."
    ]);
    exit;
}

// Check password length
if (strlen($password) < 8) {
    http_response_code(422);
    echo json_encode([
        "success" => false,
        "message" => "Password must be at least 8 characters."
    ]);
    exit;
}

try {

    // Check duplicate email or phone
    $check = $pdo->prepare(
        "SELECT id FROM users
         WHERE email = :email OR phone = :phone"
    );

    $check->execute([
        ":email" => $email,
        ":phone" => $phone
    ]);

    if ($check->fetch()) {
        http_response_code(409);
        echo json_encode([
            "success" => false,
            "message" => "Email or phone number already exists."
        ]);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // Insert user
    $stmt = $pdo->prepare(
        "INSERT INTO users
        (full_name, email, phone, password)
        VALUES
        (:full_name, :email, :phone, :password)"
    );

    $stmt->execute([
        ":full_name" => $fullName,
        ":email" => $email,
        ":phone" => $phone,
        ":password" => $hashedPassword
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Account created successfully."
    ]);

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Something went wrong."
    ]);

}