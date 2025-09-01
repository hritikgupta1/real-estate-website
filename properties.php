<?php
$page_title = "Dubai Space - Properties";
include 'db.php';
include 'header.php';

$type = $_GET['type'] ?? '';
$q    = trim($_GET['q'] ?? '');
$min  = $_GET['min'] ?? '';
$max  = $_GET['max'] ?? '';
$bed  = $_GET['bed'] ?? '';
$bath = $_GET['bath'] ?? '';
$sqft = $_GET['sqft'] ?? '';

$sql = "SELECT * FROM properties WHERE 1=1";
$params = [];

// Type filter (buy/rent)
if ($type === 'sale' || $type === 'rent') {
  $sql .= " AND type = :type";
  $params['type'] = $type;
}

// Search filter (title or address)
if ($q !== '') {
  $sql .= " AND (title LIKE :q1 OR address LIKE :q2)";
  $params['q1'] = "%$q%";
  $params['q2'] = "%$q%";
}

// Min price filter
if ($min !== '' && is_numeric($min)) {
  $sql .= " AND price >= :min";
  $params['min'] = (float)$min;
}

// Max price filter
if ($max !== '' && is_numeric($max)) {
  $sql .= " AND price <= :max";
  $params['max'] = (float)$max;
}

// Bedrooms filter
if ($bed !== '' && is_numeric($bed)) {
  $sql .= " AND bedrooms >= :bed";
  $params['bed'] = (int)$bed;
}

// Bathrooms filter
if ($bath !== '' && is_numeric($bath)) {
  $sql .= " AND bathrooms >= :bath";
  $params['bath'] = (int)$bath;
}

// Sqft filter
if ($sqft !== '' && is_numeric($sqft)) {
  $sql .= " AND sqft >= :sqft";
  $params['sqft'] = (int)$sqft;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>

<style>
  .filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 20px 0;
  }
  .filters input,
  .filters select,
  .filters button,
  .filters a {
    padding: 10px 14px;
    border-radius: 6px;
    border: 1px solid #ddd;
  }
  .filters button {
    background: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
  }
  .filters button:hover {
    background: #0056b3;
  }
  .filters a {
    background: #f8f9fa;
    text-decoration: none;
    color: #333;
  }

  @media (max-width: 790px) {
    .filters {
      flex-direction: column;
      align-items: stretch;
    }
    .filters input,
    .filters select,
    .filters button,
    .filters a {
      width: 100%;
    }
  }

  .cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }
  .property-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    overflow: hidden;
    transition: transform 0.2s;
  }
  .property-card:hover {
    transform: translateY(-4px);
  }
  .property-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }
  .property-card .content {
    padding: 15px;
  }
  .badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 10px;
    background: #28a745;
    color: #fff;
  }
  .badge.rent {
    background: #dc3545;
  }
  .price {
    font-size: 18px;
    font-weight: bold;
    color: #007bff;
  }
  .muted {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 8px;
  }
  .meta {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    font-size: 13px;
    margin-top: 10px;
    color: #555;
  }
  .meta .highlight {
    font-weight: bold;
    color: #000;
  }
</style>

<section class="container">
  <h1>Properties <?= $type ? '(' . htmlspecialchars(strtoupper($type)) . ')' : '' ?></h1>

  <!-- Filters -->
  <form class="filters" method="get">

    <!-- Buy / Rent -->
    <select name="type">
      <option value="">All Types</option>
      <option value="sale" <?= $type==='sale'?'selected':'' ?>>Buy</option>
      <option value="rent" <?= $type==='rent'?'selected':'' ?>>Rent</option>
    </select>

    <input type="text" name="q" placeholder="Search by title or address..."
           value="<?= htmlspecialchars($q) ?>" />

    <input type="number" name="min" placeholder="Min Price"
           value="<?= htmlspecialchars($min) ?>" />

    <input type="number" name="max" placeholder="Max Price"
           value="<?= htmlspecialchars($max) ?>" />

    <!-- Bedrooms -->
    <select name="bed">
      <option value="">Bedrooms (Any)</option>
      <option value="1" <?= $bed==='1'?'selected':'' ?>>1+ Bedrooms</option>
      <option value="2" <?= $bed==='2'?'selected':'' ?>>2+ Bedrooms</option>
      <option value="3" <?= $bed==='3'?'selected':'' ?>>3+ Bedrooms</option>
      <option value="4" <?= $bed==='4'?'selected':'' ?>>4+ Bedrooms</option>
    </select>

    <!-- Bathrooms -->
    <select name="bath">
      <option value="">Bathrooms (Any)</option>
      <option value="1" <?= $bath==='1'?'selected':'' ?>>1+ Bathrooms</option>
      <option value="2" <?= $bath==='2'?'selected':'' ?>>2+ Bathrooms</option>
      <option value="3" <?= $bath==='3'?'selected':'' ?>>3+ Bathrooms</option>
    </select>

    <!-- Sqft -->
    <select name="sqft">
      <option value="">Min Sqft</option>
      <option value="500" <?= $sqft==='500'?'selected':'' ?>>500+</option>
      <option value="1000" <?= $sqft==='1000'?'selected':'' ?>>1000+</option>
      <option value="1500" <?= $sqft==='1500'?'selected':'' ?>>1500+</option>
      <option value="2000" <?= $sqft==='2000'?'selected':'' ?>>2000+</option>
      <option value="3000" <?= $sqft==='3000'?'selected':'' ?>>3000+</option>
    </select>

    <button type="submit">Filter</button>
    <a href="properties.php" style="text-align: center;">Reset</a>
  </form>

  <!-- Property Cards -->
  <div class="cards">
    <?php foreach ($rows as $row): ?>
      <article class="property-card">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Property image" />
        <div class="content">
          <div class="badge <?= $row['type'] === 'rent' ? 'rent' : '' ?>">
            <?= strtoupper($row['type'] === 'rent' ? 'RENT' : 'BUY') ?>
          </div>
          <h4><?= htmlspecialchars($row['title']) ?></h4>
          <p class="muted"><?= htmlspecialchars($row['address']) ?></p>
          <p class="price">
            ₹<?= number_format((float)str_replace(['₹', ',', '$'], '', $row['price'])) ?>
          </p>
          <div class="meta">
            <span><span class="highlight"><?= (int)$row['bedrooms'] ?></span> Bedroom</span>
            <span><span class="highlight"><?= (int)$row['bathrooms'] ?></span> Bathroom</span>
            <span><span class="highlight"><?= (int)$row['floors'] ?></span> Floor</span>
            <span><span class="highlight"><?= (int)$row['sqft'] ?></span> Sqft</span>
          </div>
        </div>
      </article>
    <?php endforeach; ?>

    <?php if (empty($rows)): ?>
      <p>No properties match your filters.</p>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>
