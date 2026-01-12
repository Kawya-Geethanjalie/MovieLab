<?php
// Database සම්බන්ධතාවය ලබා ගැනීම
require_once("../include/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['newsletter_email']);

    // Email එක හිස්දැයි පරීක්ෂා කිරීම
    if (empty($email)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?news_status=empty");
        exit();
    }

    try {
        // SQL Query එක - Email එක දැනටමත් තිබේදැයි පරීක්ෂා කර ඇතුළත් කිරීම
        $sql = "INSERT INTO newsletter_subscribers (email) VALUES (:email)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
// newsletter_process.php ඇතුළත සාර්ථක වූ විට
if ($stmt->execute()) {
    header("Location: ../Site/index.php?news_status=success"); // මෙහි ඔබේ index පිටුවේ නම නිවැරදිව යොදන්න
    exit();
}
    } catch (PDOException $e) {
        // Email එක දැනටමත් ඇතුළත් කර ඇත්නම් (Duplicate Entry)
        if ($e->getCode() == 23000) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?news_status=exists");
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?news_status=error");
        }
        exit();
    }
}
?>