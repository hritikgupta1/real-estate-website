<?php $page_title = "Contact — Dwell Properties"; include 'header.php'; ?>
<section class="container contact-page">
  <h1>Contact Us</h1>
  <form class="contact-form" method="post" action="insert_contact.php">
    <div class="grid-2">
      <label>First Name*<input required type="text" name="first_name" /></label>
      <label>Last Name*<input required type="text" name="last_name" /></label>
    </div>
    <label>Email*<input required type="email" name="email" /></label>
    <fieldset>
      <legend>Interested in</legend>
      <label class="contact-interested">Buy<input type="radio" name="interest" value="buy" checked /> </label>
      <label class="contact-interested">Rent<input type="radio" name="interest" value="rent" /> </label>
      <label class="contact-interested">Other<input type="radio" name="interest" value="other" /> </label>
    </fieldset>
    <label>Message<textarea name="message" rows="4" placeholder="How can we help?"></textarea></label>
    <button class="btn btn-primary" type="submit">Submit</button>
  </form>
</section>
<?php include 'footer.php'; ?>
