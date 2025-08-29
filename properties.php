<?php
$page_title = "Browse Properties";
include 'db.php';
include 'header.php';

$type = $_GET['type'] ?? '';
$q    = trim($_GET['q'] ?? '');
$min  = $_GET['min'] ?? '';
$max  = $_GET['max'] ?? '';

$sql = "SELECT * FROM properties WHERE 1=1";
$params = [];

// Type filter
if ($type === 'sale' || $type === 'rent') {
  $sql .= " AND type = :type";
  $params['type'] = $type;
}

// Search filter (fixed!)
if ($q !== '') {
  $sql .= " AND (title LIKE :q1 OR address LIKE :q2)";
  $params['q1'] = "%$q%";
  $params['q2'] = "%$q%";
}

// Min price filter
if ($min !== '' && is_numeric($min)) {
  $sql .= " AND REPLACE(price, '$', '') + 0 >= :min";
  $params['min'] = (float)$min;
}

// Max price filter
if ($max !== '' && is_numeric($max)) {
  $sql .= " AND REPLACE(price, '$', '') + 0 <= :max";
  $params['max'] = (float)$max;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<section class="container">
  <h1>Properties <?= $type ? '(' . htmlspecialchars(strtoupper($type)) . ')' : '' ?></h1>
  <form class="filters" method="get">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>" />
    <input type="text" name="q" placeholder="Search title or address..." value="<?= htmlspecialchars($q) ?>" />
    <input type="number" name="min" placeholder="Min Price ($)" value="<?= htmlspecialchars($min) ?>" />
    <input type="number" name="max" placeholder="Max Price ($)" value="<?= htmlspecialchars($max) ?>" />
    <button class="btn" type="submit">Filter</button>
    <a class="btn btn-light" href="properties.php<?= $type ? ('?type=' . urlencode($type)) : '' ?>">Reset</a>
  </form>
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
            <span>🛏 <?= (int)$row['beds'] ?></span>
            <span>🛁 <?= (int)$row['baths'] ?></span>
            <span>🏠 <?= (int)$row['levels'] ?></span>
            <span>📐 <?= (int)$row['sqft'] ?></span>
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