<?php
include 'db.php';
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $role  = $_POST['role'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Upload image
    $targetDir = "images/agents/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $imageName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $imageName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO agents (name, role, phone, email, image) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $role, $phone, $email, $targetFile])) {
            $message = " Agent added successfully!";
        } else {
            $message = "❌ Error adding agent.";
        }
    } else {
        $message = "❌ Error uploading image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Agent</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px; }
    .form-container { background:#fff; padding:20px; border-radius:10px; max-width:500px; margin:auto; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
    h2 { text-align:center; margin-bottom:20px; }
    label { display:block; margin-top:10px; font-weight:bold; }
    input, select { width:100%; padding:10px; margin-top:5px; border-radius:6px; border:1px solid #ccc; }
    button { margin-top:15px; padding:12px; width:100%; border:none; background:#0b1220; color:#fff; font-size:16px; border-radius:6px; cursor:pointer;}
    button:hover { background:#1f2937; }
    .message { margin:10px 0; padding:10px; border-radius:6px; text-align:center;}
    .success { background:#d1fae5; color:#065f46;}
    .error { background:#fee2e2; color:#991b1b;}
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Add New Agent</h2>
    <?php if($message): ?>
      <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
      <label for="name">Full Name</label>
      <input type="text" name="name" id="name" required>

      <label for="role">Role</label>
      <input type="text" name="role" id="role" placeholder="e.g. Senior Agent" required>

      <label for="phone">Phone</label>
      <input type="text" name="phone" id="phone" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>

      <label for="image">Profile Image</label>
      <input type="file" name="image" id="image" accept="image/*" required>

      <button type="submit">Add Agent</button>
    </form>
  </div>
</body>
</html>
