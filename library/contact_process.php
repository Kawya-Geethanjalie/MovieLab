<?php
// 1. Database සම්බන්ධතාවය ලබා ගැනීම
require_once("../include/connection.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Form එකෙන් එන දත්ත ලබා ගැනීම
    $name    = $_POST['user_name'];
    $email   = $_POST['user_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    try {
        // 3. SQL Query එක සකස් කිරීම (PDO Prepared Statements භාවිතා කර ඇත)
        // මෙහි $pdo යනු ඔබේ connection.php හි ඇති variable එකයි
        $sql = "INSERT INTO massages_and_comment (name, email, subject, massage, created_at) 
                VALUES (:name, :email, :subject, :message, NOW())";
        
        $stmt = $pdo->prepare($sql);

        // 4. දත්ත සම්බන්ධ කිරීම (Binding parameters)
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        // 5. Query එක ක්‍රියාත්මක කිරීම
        if ($stmt->execute()) {
            header("Location: ../Site/contact_us.php?status=success");
            exit();
        } else {
            header("Location: ../Site/contact_us.php?status=error");
            exit();
        }

    } catch (PDOException $e) {
        // ගැටලුවක් ආවොත් මෙතැනින් බලාගත හැක
        error_log("Insert failed: " . $e->getMessage());
        header("Location: ../Site/contact_us.php?status=error");
        exit();
    }
}
?>