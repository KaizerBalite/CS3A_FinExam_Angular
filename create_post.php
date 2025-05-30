<?php

// starts the session
session_start();

// redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: petlog.php");
    exit();
}

// connects to the database
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get form data
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']);
    $image_url = '';

    // handle image upload if any
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "assets/uploads";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image_url = $targetFile;
        }
    }

    // insert post into database
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $content, $image_url);

    if ($stmt->execute()) {
        header("Location: petfeed.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>