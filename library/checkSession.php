<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo json_encode([
        "status" => "logged_in",
        "user" => [
            "first_name" => $_SESSION['first_name'],
            "last_name" => $_SESSION['last_name'],
            "username" => $_SESSION['username'],
            "user_type" => $_SESSION['user_type'],
            "profile_image" => $_SESSION['profile_image'] ?? null
        ]
    ]);
} else {
    echo json_encode(["status" => "not_logged_in"]);
}
?>