<?php $page_title = "Dubai Space — Home";
include 'db.php';
include 'header.php'; ?>

<style>
  /* Responsive */
  /* Search Bar Styling */
  .hero-search {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    align-items: stretch;
    gap: 10px;
    flex-wrap: wrap;
    max-width: 600px;
  }

  .hero-search input {
    flex: 1;
    padding: 12px 15px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    min-width: 300px;
  }

  .hero-search button {
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    white-space: nowrap;
  }

  /* Responsive */
  @media (max-width:768px) {
    .hero-search {
      flex-direction: column;
      align-items: stretch;
      font-size: 16px;
      min-width: 270px;
    }

    .hero-search input,
    .hero-search button {
      width: 100%;
    }
  }

  @media (max-width:1224px) {
    .cards {
      grid-template-columns: repeat(2, 1fr)
    }

    .listings {
      padding: 30px 25px 50px 25px;
    }

    .chooser-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      padding: 0 15px 0 15px;
    }
  }

  @media (max-width:768px) {

    .panel img {
      height: 220px;
      padding: 10px;
      border-radius: 30px;
    }

    .listings {
      padding: 30px 10px 50px 10px;
    }


    .menu.show {
      display: flex
    }

    .menu-toggle {
      display: block
    }

    .chooser-grid {
      grid-template-columns: 1fr
    }

    .cards {
      grid-template-columns: repeat(2, 1fr)
    }

    .grid-3 {
      grid-template-columns: 1fr
    }

    .grid-2 {
      grid-template-columns: 1fr
    }

  }

  @media (max-width:500px) {
    .cards {
      grid-template-columns: 1fr
    }

    .hero h1 {
      font-size: 36px
    }

    .panel img {
      height: 220px;
      padding: 10px;
      border-radius: 30px;
    }

    .listings {
      padding: 30px 10px 50px 10px;
    }

  }
</style>
<section class="hero" style="background-image: url('images/hero.jpg');">
  <div class="overlay"></div>
  <div class="hero-content container">
    <h1>New Properties</h1>
    <p>Exclusively by Dwell</p>

    <!-- Search Bar -->
    <form class="hero-search" method="get" action="properties.php">
      <input
        type="text"
        name="q"
        placeholder="Search properties by address or title..."
        required />
      <button type="submit" class="btn btn-primary">Search</button>
    </form>
  </div>
</section>

<section class="chooser container">
  <h2>What are you looking for?</h2>
  <div class="chooser-grid">
    <a href="properties.php?type=sale" class="panel panel-left">
      <img src="images/house_sale.jpg" alt="Buy a home" />
      <span>Buy</span>
    </a>
    <a href="properties.php?type=rent" class="panel panel-right">
      <img src="images/house_rent.jpg" alt="Rent a home" />
      <span>Rent</span>
    </a>
  </div>
</section>

<section class="container listings">
  <h3 class="eyebrow">New Properties</h3>
  <h2>For Sale</h2>
  <div class="cards">
    <?php
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE type='sale' ORDER BY id DESC LIMIT 4");
    $stmt->execute();
    foreach ($stmt as $row): ?>
      <article class="property-card">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Property image" />
        <div class="content">
          <div class="badge">BUY</div>
          <h4><?= htmlspecialchars($row['title']) ?></h4>
          <p class="muted"><?= htmlspecialchars($row['address']) ?></p>
          <p class="price">
            ₹<?= number_format((float)str_replace(['₹', ',', '$'], '', $row['price'])) ?>
          </p>
          <div class="meta">
            <span title="Bedrooms"><?= (int)$row['bedrooms'] ?> Bedroom</span>
            <span title="Bathrooms"><?= (int)$row['bathrooms'] ?> Bathroom</span>
            <span title="Floors"><?= (int)$row['floors'] ?> Floor</span>
            <span title="Sqft"><?= (int)$row['sqft'] ?> Sqft</span>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
  <div class="center"><a class="btn" href="properties.php?type=sale">View More</a></div>
</section>

<section class="container listings">
  <h3 class="eyebrow">New Properties</h3>
  <h2>For Rent</h2>
  <div class="cards">
    <?php
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE type='rent' ORDER BY id DESC LIMIT 4");
    $stmt->execute();
    foreach ($stmt as $row): ?>
      <article class="property-card">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Property image" />
        <div class="content">
          <div class="badge rent">RENT</div>
          <h4><?= htmlspecialchars($row['title']) ?></h4>
          <p class="muted"><?= htmlspecialchars($row['address']) ?></p>
          <p class="price">
            ₹<?= number_format((float)str_replace(['₹', ',', '$'], '', $row['price'])) ?>
          </p>
          <div class="meta">
            <span title="Bedrooms"><?= (int)$row['bedrooms'] ?> Bedroom</span>
            <span title="Bathrooms"><?= (int)$row['bathrooms'] ?> Bathroom</span>
            <span title="Floors"><?= (int)$row['floors'] ?> Floor</span>
            <span title="Sqft"><?= (int)$row['sqft'] ?> Sqft</span>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
  <div class="center"><a class="btn" href="properties.php?type=rent">View More</a></div>
</section>

<section id="stories" class="stories">
  <div class="container">
    <div class="carousel">
      <blockquote class="active">“Dwell helped us find our dream home quickly and stress-free.” <cite>— Alexis Willis, NJ</cite></blockquote>
      <blockquote>“Smooth process and great support from start to finish.” <cite>— Marco A., CA</cite></blockquote>
      <blockquote>“Fantastic selection of properties and responsive team.” <cite>— Priya R., TX</cite></blockquote>
      <blockquote>“Highly recommend Dwell for anyone looking to buy or rent.” <cite>— Ayesha K., NY</cite></blockquote>
      <div class="dots"></div>
    </div>
  </div>
</section>



<?php include 'footer.php'; ?>