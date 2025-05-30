<?php

// starts session
session_start();

// redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: petlog.php");
    exit();
}

// connects to the database
require 'db.php';

$user_id = $_SESSION['user_id'];

// fetch current user data
$userId = $_SESSION['user_id'];
$userQuery = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();
$userProfilePic = $userData['profile_pic'] ?? 'uploads/default.png';

// fetch posts in order
$sql = "SELECT posts.*, users.first_name, users.last_name, users.profile_pic 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PetPals - Feed</title>
  <link rel="icon" href="assets/logo.png">
  <link rel="stylesheet" href="css/newsfeed.css">
</head>
<body>

  <!-- navigation bar -->
  <header class="feedHeader">
  <h1><img src="assets/logo.png" alt="PetPals Logo" class="logo"> PetPals</h1>
  <nav>
    <a href="petfeed.php" class="navBtn">Home</a>
    <a href="logout.php" class="navBtn logoutBtn">Logout</a>
  </nav>
</header>

  <main class="feedMain">

    <!-- post creation section -->
    <section class="newPost">
      <form action="create_post.php" method="POST" enctype="multipart/form-data">
        <img src="<?= htmlspecialchars($userProfilePic) ?>" class="profilePic" alt="Your Profile Picture">
        <textarea name="content" placeholder="What are you and your pet up to?" rows="3" required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Post</button>
      </form>
    </section>

    <!-- display posts -->
    <section class="posts">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="postCard">
          <div class="postHeader">
            <img src="<?= htmlspecialchars($row['profile_pic']) ?>" class="profilePic" alt="Profile">
            <div>
              <strong>@<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong><br> 
              <span><?= htmlspecialchars($row['created_at']) ?></span>
            </div>
          </div>

          <p><?= htmlspecialchars($row['content']) ?></p>
          <?php if (!empty($row['image_url'])): ?>
            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Post image">
          <?php endif; ?>
          <div class="postActions">
            <button class="likeBtn" onclick="likePost(this)">❤️ Like (<span class="likeCount">0</span>)</button>
            <button class="viewBtn" onclick="viewPost(this)">👁️ View Post</button>
          </div>
        </div>
      <?php endwhile; ?>
    </section>
  </main>

  <!-- view post -->
  <div id="postModal" class="modal">
  <div class="modal-content">
    <span class="closeBtn">&times;</span>
    
    <div class="modalHeader">
      <img id="modalProfilePic" class="modalProfilePic" src="" alt="Profile Pic" />
      <div>
        <h2 id="modalUser"></h2>
        <p id="modalDate" style="font-size: 0.9em; color: #666;"></p>
      </div>
    </div>

    <p id="modalContent"></p>
    <img id="modalImage" src="" alt="Full Post Image" style="max-width: 100%; display: none;">
  </div>
</div>

  <script src="js/petpals.js"></script>
</body>
</html>