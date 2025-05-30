<?php

// starts the session
session_start();

// connects to the database
require_once 'db.php';

// handles form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = $_POST['pass'];

    // checks user in database
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $password_hash);

    // checks if the account exists
    if ($stmt->num_rows === 1) {
        $stmt->fetch();
        if (password_verify($pass, $password_hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_email'] = $email;
            header('Location: petfeed.php');
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that email.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PetPals - Log in</title>
  <link rel="icon" href="assets/logo.png">
  <link rel="stylesheet" href="css/petpals.css" />
</head>
<body>
  <div class="ppContainer">
    <h1>
      <img src="assets/logo.png" alt="PetPals Logo" style="height: 40px;">
      PetPals
    </h1>

    <!-- error display -->
    <?php if (!empty($error)) : ?>
      <p style="color:red; font-weight:bold;"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>

    <!-- login form -->
    <form method="POST" id="ppLoginForm" autocomplete="off">
      <input type="text" name="email" placeholder="Pet Lover Email" required />
      <input type="password" name="pass" placeholder="Secret Paw-word" required />
      <button type="submit">Sniff In</button>
    </form>

    <!-- navigation links -->
    <div class="ppOptions">
      <a href="homepage.php">Home Page</a>
      <a href="petsign.php">Join the Pack</a>
    </div>
  </div>
</body>
</html>