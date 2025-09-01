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
  $stmt = $pdo->prepare("SELECT image FROM properties WHERE id=?");
  $stmt->execute([$id]);
  $img = $stmt->fetchColumn();
  if ($img && file_exists($img)) unlink($img);
  $pdo->prepare("DELETE FROM properties WHERE id=?")->execute([$id]);
  header("Location: manage_properties.php");
  exit();
}

// EDIT or ADD
$editMode = false;
$editProperty = null;
if (isset($_GET['edit'])) {
  $editMode = true;
  $id = intval($_GET['edit']);
  $stmt = $pdo->prepare("SELECT * FROM properties WHERE id=?");
  $stmt->execute([$id]);
  $editProperty = $stmt->fetch();
}

// SAVE form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $address = $_POST['address'];
  $price = $_POST['price'];
  $bedrooms = $_POST['bedrooms'];
  $bathrooms = $_POST['bathrooms'];
  $floors = $_POST['floors'];
  $sqft = $_POST['sqft'];
  $type = $_POST['type'];
  $img = $editMode ? $editProperty['image'] : null;

  if (!empty($_FILES['image']['name'])) {
    if (!is_dir("images/properties")) {
      mkdir("images/properties", 0777, true);
    }
    if ($img && file_exists($img)) unlink($img);
    $target = "images/properties/" . time() . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    $img = $target;
  }

  if ($editMode) {
    $pdo->prepare("UPDATE properties SET title=?,address=?,price=?,bedrooms=?,bathrooms=?,floors=?,sqft=?,type=?,image=? WHERE id=?")
      ->execute([$title, $address, $price, $bedrooms, $bathrooms, $floors, $sqft, $type, $img, $editProperty['id']]);
  } else {
    $pdo->prepare("INSERT INTO properties (title,address,price,bedrooms,bathrooms,floors,sqft,type,image) VALUES (?,?,?,?,?,?,?,?,?)")
      ->execute([$title, $address, $price, $bedrooms, $bathrooms, $floors, $sqft, $type, $img]);
  }
  header("Location: manage_properties.php");
  exit();
}

$properties = $pdo->query("SELECT * FROM properties")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Properties</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9fafb;
      margin: 0;
      color: #111827;
    }

    .container {
      padding: 20px;
    }

    h2 {
      margin-top: 20px;
      color: #111827;
    }

    /* Form Box */
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
      margin-top: 12px;
      font-weight: bold;
      font-size: 14px;
    }

    input,
    select {
      width: 95%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ddd;
      border-radius: 6px;
    }

    button {
      margin-top: 15px;
      padding: 12px 20px;
      background: #2563eb;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 15px;
      font-weight: bold;
    }

    button:hover {
      background: #1e40af;
    }

    /* Table View (Desktop) */
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      margin-top: 20px;
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

    img.thumb {
      border-radius: 17px;
      cursor: pointer;
      padding: 10px;
    }

    a.btn {
      padding: 6px 10px;
      background: #2563eb;
      color: #fff;
      border-radius: 5px;
      text-decoration: none;
      margin: 2px;
      display: inline-block;
    }

    a.delete {
      background: #dc2626;
    }

    /* Card View (Mobile) */
    .property-cards {
      display: none;
      margin-top: 20px;
    }

    .property-card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      padding: 15px;
      margin-bottom: 15px;
    }

    .property-card img {
      max-width: 100%;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .property-card h4 {
      margin: 0 0 10px;
      font-size: 18px;
      color: #111827;
    }

    .property-card p {
      margin: 5px 0;
      font-size: 14px;
      color: #374151;
    }

    .property-card .actions {
      display: flex;
      gap: 8px;
      margin-top: 10px;
      flex-wrap: wrap;
    }

    .property-card .actions a {
      flex: 1;
      text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .form-box {
        padding: 15px;
        margin: 10px;
      }

      input,
      select {
        width: 100%;
        box-sizing: border-box;
      }

      button {
        width: 100%;
        font-size: 16px;
        padding: 12px;
      }

      a.btn {
        display: block;
        margin: 5px 0;
        text-align: center;
      }

      table {
        display: none;
        /* hide table */
      }

      .property-cards {
        display: block;
        /* show cards */
      }
    }

    @media (max-width: 480px) {
      .property-card h4 {
        font-size: 16px;
      }

      .property-card p {
        font-size: 13px;
      }

      button {
        font-size: 14px;
        padding: 10px;
      }
    }
  </style>
</head>

<body>
  <?php include 'admin_nav.php'; ?>
  <div class="container">
    <h2><?= $editMode ? "Edit Property" : "Add New Property" ?></h2>
    <div class="form-box">
      <form method="POST" enctype="multipart/form-data">
        <label>Title</label>
        <input type="text" name="title"
          value="<?= $editMode ? htmlspecialchars($editProperty['title']) : '' ?>"
          placeholder="Enter property title"
          required>

        <label>Address</label>
        <input type="text" name="address"
          value="<?= $editMode ? htmlspecialchars($editProperty['address']) : '' ?>"
          placeholder="Enter property address"
          required>

        <label>Price</label>
        <input type="number" step="0.01" name="price"
          value="<?= $editMode ? $editProperty['price'] : '' ?>"
          placeholder="Enter price"
          required>

        <label>Bedrooms</label>
        <input type="number" name="bedrooms"
          value="<?= $editMode ? $editProperty['bedrooms'] : '' ?>"
          placeholder="Enter number of bedrooms"
          required>

        <label>Bathrooms</label>
        <input type="number" name="bathrooms"
          value="<?= $editMode ? $editProperty['bathrooms'] : '' ?>"
          placeholder="Enter number of bathrooms"
          required>

        <label>Floors</label>
        <input type="number" name="floors"
          value="<?= $editMode ? $editProperty['floors'] : '' ?>"
          placeholder="Enter number of floors"
          required>

        <label>Square Feet</label>
        <input type="number" name="sqft"
          value="<?= $editMode ? $editProperty['sqft'] : '' ?>"
          placeholder="Enter total square feet"
          required>

        <label>Type</label>
        <select name="type" required>
          <option value="">-- Select Type --</option>
          <option value="sale" <?= $editMode && $editProperty['type'] == 'sale' ? 'selected' : '' ?>>Sale</option>
          <option value="rent" <?= $editMode && $editProperty['type'] == 'rent' ? 'selected' : '' ?>>Rent</option>
        </select>

        <label>Image</label>
        <input type="file" name="image" accept="image/*"
          <?= $editMode ? '' : 'required' ?>>

        <?php if ($editMode && $editProperty['image']): ?>
          <div class="preview">
            <a href="<?= $editProperty['image'] ?>" target="_blank">
              <img src="<?= $editProperty['image'] ?>" width="100" class="thumb">
            </a>
          </div>
        <?php endif; ?>
        <button type="submit"><?= $editMode ? "Update Property" : "Add Property" ?></button>
      </form>
    </div>

    <h2>All Properties</h2>

    <!-- Table for Desktop -->
    <table>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Address</th>
        <th>Price</th>
        <th>Bedrooms</th>
        <th>Bathrooms</th>
        <th>Floors</th>
        <th>Sqft</th>
        <th>Type</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($properties as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['title']) ?></td>
          <td><?= htmlspecialchars($p['address']) ?></td>
          <td><?= $p['price'] ?></td>
          <td><?= $p['bedrooms'] ?></td>
          <td><?= $p['bathrooms'] ?></td>
          <td><?= $p['floors'] ?></td>
          <td><?= $p['sqft'] ?></td>
          <td><?= $p['type'] ?></td>
          <td>
            <?php if ($p['image']): ?>
              <a href="<?= $p['image'] ?>" target="_blank"><img src="<?= $p['image'] ?>" width="70" class="thumb"></a>
            <?php endif; ?>
          </td>
          <td>
            <a href="manage_properties.php?edit=<?= $p['id'] ?>" class="btn">✏ Edit</a>
            <a href="manage_properties.php?delete=<?= $p['id'] ?>" class="btn delete" onclick="return confirm('Delete this property?')">🗑 Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <!-- Cards for Mobile -->
    <div class="property-cards">
      <?php foreach ($properties as $p): ?>
        <div class="property-card">
          <?php if ($p['image']): ?>
            <img src="<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['title']) ?>">
          <?php endif; ?>
          <h4><?= htmlspecialchars($p['title']) ?></h4>
          <p><strong>Address:</strong> <?= htmlspecialchars($p['address']) ?></p>
          <p><strong>Price:</strong> <?= $p['price'] ?></p>
          <p><strong>Bedrooms:</strong> <?= $p['bedrooms'] ?></p>
          <p><strong>Bathrooms:</strong> <?= $p['bathrooms'] ?></p>
          <p><strong>Floors:</strong> <?= $p['floors'] ?></p>
          <p><strong>Sqft:</strong> <?= $p['sqft'] ?></p>
          <p><strong>Type:</strong> <?= ucfirst($p['type']) ?></p>
          <div class="actions">
            <a href="manage_properties.php?edit=<?= $p['id'] ?>" class="btn">✏ Edit</a>
            <a href="manage_properties.php?delete=<?= $p['id'] ?>" class="btn delete" onclick="return confirm('Delete this property?')">🗑 Delete</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>

</html>