<?php
session_start();
require 'db.php';
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit();
}

// DELETE
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $pdo->prepare("SELECT image FROM agents WHERE id=?");
  $stmt->execute([$id]);
  $img = $stmt->fetchColumn();
  if ($img && file_exists($img)) unlink($img);
  $pdo->prepare("DELETE FROM agents WHERE id=?")->execute([$id]);
  header("Location: manage_agents.php");
  exit();
}

// EDIT
$editMode = false;
$editAgent = null;
if (isset($_GET['edit'])) {
  $editMode = true;
  $id = intval($_GET['edit']);
  $stmt = $pdo->prepare("SELECT * FROM agents WHERE id=?");
  $stmt->execute([$id]);
  $editAgent = $stmt->fetch();
}

// SAVE form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $role = $_POST['role'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $img = $editMode ? $editAgent['image'] : null;

  if (!empty($_FILES['image']['name'])) {
    if ($img && file_exists($img)) unlink($img);
    $target = "images/agents/" . time() . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    $img = $target;
  }

  if ($editMode) {
    $pdo->prepare("UPDATE agents SET name=?,role=?,phone=?,email=?,image=? WHERE id=?")
      ->execute([$name, $role, $phone, $email, $img, $editAgent['id']]);
  } else {
    $pdo->prepare("INSERT INTO agents (name,role,phone,email,image) VALUES (?,?,?,?,?)")
      ->execute([$name, $role, $phone, $email, $img]);
  }
  header("Location: manage_agents.php");
  exit();
}

$agents = $pdo->query("SELECT * FROM agents")->fetchAll();
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
  <title>Manage Agents</title>
  <style>
    body {
      font-family: Arial;
      background: #f9fafb;
      margin: 0;
    }

    .container {
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      margin-top: 20px;
    }

    .agent-cards {
      display: none;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    th {
      background: #111827;
      color: #fff;
    }

    a.btn {
      padding: 6px 10px;
      background: #2563eb;
      color: #fff;
      border-radius: 5px;
      text-decoration: none;
    }

    a.delete {
      background: #dc2626;
    }

    img.thumb {
      border-radius: 5px;
      cursor: pointer;
    }

    .form-box {
      max-width: 800px;
      margin: 20px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }

    input {
      width: 95%;
      padding: 10px;
      margin-top: 5px;
    }

    button {
      margin-top: 15px;
      padding: 10px 20px;
      background: #2563eb;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .preview {
      margin: 15px 0;
    }

    .form-actions {
      margin-top: 20px;
      text-align: left;
      /* centers button */
    }

    .form-actions button {
      display: inline-block;
      padding: 12px 25px;
      font-size: 16px;
      font-weight: bold;
      background: #2563eb;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .form-actions button:hover {
      background: #1e40af;
    }

    /* ✅ Mobile Responsiveness */
    @media (max-width: 768px) {
      .container {
        padding: 15px;
      }

      .form-box {
        padding: 15px;
        margin: 10px;
      }

      input {
        width: 100%;
        box-sizing: border-box;
      }


      th,
      td {
        font-size: 14px;
        padding: 8px;
      }

      a.btn {
        display: block;
        margin: 5px 0;
        text-align: center;
      }

      button {
        width: 100%;
      }
    }

    @media (max-width: 480px) {

      th,
      td {
        font-size: 12px;
      }

      button {
        font-size: 14px;
        padding: 10px;
      }
    }

    /* --- Mobile Card View for Agents --- */
    @media (max-width: 768px) {
      table {
        display: none;
        /* Hide table on mobile */
      }

      .agent-cards {
        display: grid;
        gap: 15px;
        margin-top: 20px;
      }

      .agent-card {
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      }

      .agent-card img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
      }

      .agent-card h4 {
        margin: 0 0 5px;
        font-size: 18px;
        color: #111827;
      }

      .agent-card p {
        margin: 3px 0;
        font-size: 14px;
        color: #4b5563;
      }

      .agent-card .actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
      }

      .agent-card .actions a {
        flex: 1;
        text-align: center;
        padding: 8px;
        font-size: 14px;
      }
    }
  </style>
</head>

<body>
  <?php include 'admin_nav.php'; ?>
  <div class="container">
    <h2><?= $editMode ? "Edit Agent" : "Add New Agent" ?></h2>
    <div class="form-box">
      <form method="POST" enctype="multipart/form-data">
        <label>Name</label>
        <input type="text" name="name"
          value="<?= $editMode ? htmlspecialchars($editAgent['name']) : '' ?>"
          placeholder="Enter full name"
          required>

        <label>Role</label>
        <input type="text" name="role"
          value="<?= $editMode ? htmlspecialchars($editAgent['role']) : '' ?>"
          placeholder="Enter role (e.g. Sales Manager)"
          required>

        <label>Phone</label>
        <input type="tel" name="phone"
          value="<?= $editMode ? htmlspecialchars($editAgent['phone']) : '' ?>"
          placeholder="Enter 10-digit phone number"
          pattern="[0-9]{10}"
          maxlength="10"
          required>

        <label>Email</label>
        <input type="email" name="email"
          value="<?= $editMode ? htmlspecialchars($editAgent['email']) : '' ?>"
          placeholder="Enter email address"
          required>

        <label>Image</label>
        <input type="file" name="image" accept="image/*"
          <?= $editMode ? '' : 'required' ?>>


        <?php if ($editMode && $editAgent['image']): ?>
          <div class="preview">
            <a href="<?= $editAgent['image'] ?>" target="_blank">
              <img src="<?= $editAgent['image'] ?>" width="100" class="thumb">
            </a>
          </div>
        <?php endif; ?>

        <div class="form-actions">
          <button type="submit"><?= $editMode ? "Update Agent" : "Add Agent" ?></button>
        </div>

      </form>
    </div>

    <h2>All Agents</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Role</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($agents as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= htmlspecialchars($a['name']) ?></td>
          <td><?= htmlspecialchars($a['role']) ?></td>
          <td><?= htmlspecialchars($a['phone']) ?></td>
          <td><?= htmlspecialchars($a['email']) ?></td>
          <td>
            <?php if ($a['image']): ?>
              <a href="<?= $a['image'] ?>" target="_blank"><img src="<?= $a['image'] ?>" width="70" class="thumb"></a>
            <?php endif; ?>
          </td>
          <td>
            <a href="manage_agents.php?edit=<?= $a['id'] ?>" class="btn">✏ Edit</a>
            <a href="manage_agents.php?delete=<?= $a['id'] ?>" class="btn delete" onclick="return confirm('Delete this agent?')">🗑 Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <!-- for Mobile -->
    <div class="agent-cards">
      <?php foreach ($agents as $a): ?>
        <div class="agent-card">
          <?php if ($a['image']): ?>
            <img src="<?= $a['image'] ?>" alt="<?= htmlspecialchars($a['name']) ?>">
          <?php endif; ?>
          <h4><?= htmlspecialchars($a['name']) ?></h4>
          <p><strong>Role:</strong> <?= htmlspecialchars($a['role']) ?></p>
          <p><strong>Phone:</strong> <?= htmlspecialchars($a['phone']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($a['email']) ?></p>
          <div class="actions">
            <a href="manage_agents.php?edit=<?= $a['id'] ?>" class="btn">✏ Edit</a>
            <a href="manage_agents.php?delete=<?= $a['id'] ?>" class="btn delete" onclick="return confirm('Delete this agent?')">🗑 Delete</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</body>

</html>