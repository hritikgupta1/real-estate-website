<?php 
$page_title = "About Us — Dwell Properties";
include 'db.php';
include 'header.php'; 
?>
<style>
  .contact-page{padding:30px 0 60px} 
  .contact-form{max-width:760px;margin:0 auto;display:flex;flex-direction:column;gap:14px} 
  .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px} 
  fieldset{border:1px solid #e2e8f0;border-radius:10px;padding:10px} 
  label{display:flex;flex-direction:column;gap:6px} 
  .contact-interested{display:flex;flex-direction:row;gap:6px; justify-content: space-between;}
</style>
<section class="hero" style="background: url('images/about.jpg') no-repeat center 5% /cover fixed;">
  <div class="overlay"></div>
  <div class="hero-content container">
    <h1>About Dwell Properties</h1>
    <p>Your trusted partner in real estate</p>
  </div>
</section>

<section class="container contact-page">
  <h2>Who We Are</h2>
  <p>
    At <strong>Dwell Properties</strong>, we believe that a home is more than just a place 
    to live—it’s where life happens. With years of expertise in the real estate market, 
    we specialize in connecting people with their dream homes and investment opportunities. 
    Whether you’re looking to <em>buy, rent, or sell</em>, our team is committed to making 
    the process seamless and stress-free.
  </p>

  <div class="grid-2" style="margin:40px 0;">
    <div>
      <img src="images/our-team.jpg" alt="Our Team" style="width:100%; border-radius:16px;">
    </div>
    <div>
      <h3>Our Mission</h3>
      <p>
        Our mission is simple: <strong>to redefine the real estate experience</strong>. 
        By combining cutting-edge technology, personalized service, and a vast 
        network of properties, we empower clients to make confident decisions.
      </p>
      <ul>
        <li>✔ Customer-first approach</li>
        <li>✔ Wide network of trusted listings</li>
        <li>✔ Transparency & integrity</li>
        <li>✔ Personalized guidance at every step</li>
      </ul>
    </div>
  </div>
</section>

<section class="stories">
  <div class="container">
    <h2 style="text-align:center;">Why Choose Us?</h2>
    <div class="chooser-grid" style="margin-top:30px;">
      <div class="panel">
        <img src="images/trusted_service.jpg" alt="Trusted Service" />
        <span style="color: white;">Trusted</span>
      </div>
      <div class="panel">
        <img src="images/Personalised-Guidance.jpg" alt="Personalized Guidance" />
        <span style="color: white;">Guidance</span>
      </div>
    </div>
  </div>
</section>

<section class="container listings">
  <h2>Meet Our Team</h2>
  <p>
    Behind every successful transaction is a team of dedicated professionals. 
    At Dwell Properties, we bring together agents, market analysts, and customer 
    support experts who share one vision: helping you find the perfect home. 
  </p>
  <div class="cards" style="margin-top:20px;">
    <div class="property-card">
      <img src="https://i.pravatar.cc/300?img=12" alt="CEO" />
      <div class="content">
        <h4>Sarah Johnson</h4>
        <p class="muted">Founder & CEO</p>
      </div>
    </div>
    <div class="property-card">
      <img src="https://i.pravatar.cc/300?img=33" alt="Agent" />
      <div class="content">
        <h4>Michael Lee</h4>
        <p class="muted">Lead Property Advisor</p>
      </div>
    </div>
    <div class="property-card">
      <img src="https://i.pravatar.cc/300?img=56" alt="Manager" />
      <div class="content">
        <h4>Priya Sharma</h4>
        <p class="muted">Customer Relations Manager</p>
      </div>
    </div>
  </div>
</section>

<section class="container contact-page">
  <h2>Our Promise</h2>
  <p>
    When you choose Dwell Properties, you’re not just getting a real estate agency—you’re 
    partnering with a team that values trust, dedication, and results. We don’t just sell houses; 
    we help you find the place you’ll call home.
  </p>
  <div class="center" style="margin-top:20px;">
    <a href="properties.php" class="btn btn-primary">Browse Properties</a>
  </div>
</section>

<?php include 'footer.php'; ?>
