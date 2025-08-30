<?php $page_title = "Contact — Dubai Space";
include 'header.php'; ?>

<style>
    /*  Contact Page Styling */
    .contact-page {
        padding: 30px 0 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    /*  Form Container */
    .contact-form {
        max-width: 760px;
        width: 100%;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: #47f99657;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
    }

    /*  Grid for First & Last Name */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    /*  Labels */
    label {
        display: flex;
        flex-direction: column;
        gap: 6px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    /*  Inputs & Textarea */
    input,
    textarea {
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        background: #f9fafb;
        transition: all 0.3s ease;
        outline: none;
    }

    input:focus,
    textarea:focus {
        border-color: #007bff;
        background-color: #fff;
        box-shadow: 0 0 6px rgba(0, 123, 255, 0.2);
    }

    /*  Fieldset Styling */
    fieldset {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px;
    }

    legend {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    /*  Radio Buttons */
    .contact-interested {
        display: flex;
        flex-direction: row;
        gap: 8px;
        justify-content: flex-start;
        align-items: center;
        font-weight: 500;
        cursor: pointer;
    }

    .contact-interested input {
        margin-left: 5px;
        accent-color: #007bff;
    }

    /*  Submit Button */
    .btn.btn-primary {
        padding: 12px;
        background: #007bff;
        border: none;
        color: #fff;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn.btn-primary:hover {
        background: #0056b3;
        box-shadow: 0 5px 12px rgba(0, 123, 255, 0.4);
    }

    /*  Alert Messages */
    .alert {
        padding: 12px 15px;
        margin-bottom: 15px;
        border-radius: 6px;
        font-size: 14px;
        text-align: center;
        font-weight: 500;
    }

    .alert.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /*  Responsive Design */
    @media (max-width: 600px) {
        .grid-2 {
            grid-template-columns: 1fr;
        }

        .contact-form {
            padding: 20px;
        }
    }

    @media (max-width: 825px) {
        .contact-page {
            padding: 30px 15px 60px 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    }
</style>

<section class="container contact-page">
    <h1 style="margin-bottom: 15px; font-size: 28px; color: #222; text-align: center;">Contact Us</h1>

    <!--  Success & Error Messages -->
    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] === 'success') {
            echo '<div class="alert success"> Your message has been sent successfully!</div>';
        } elseif ($_GET['status'] === 'error') {
            echo '<div class="alert error"> Please fill all required fields correctly.</div>';
        } elseif ($_GET['status'] === 'failed') {
            echo '<div class="alert error"> Something went wrong. Please try again.</div>';
        }
    }
    ?>

    <!--  Contact Form -->
    <form class="contact-form" method="post" action="insert_contact.php">
        <div class="grid-2">
            <label>First Name*
                <input required type="text" name="first_name" placeholder="Enter your first name" />
            </label>
            <label>Last Name*
                <input required type="text" name="last_name" placeholder="Enter your last name" />
            </label>
        </div>

        <label>Email*
            <input required type="email" name="email" placeholder="Enter your email" />
        </label>

        <label>Phone*
            <input required type="tel" name="phone" pattern="[0-9]{10}" maxlength="10" placeholder="Enter 10-digit number" />
        </label>

        <fieldset>
            <legend>Interested in</legend>
            <label class="contact-interested">Buy
                <input type="radio" name="interest" value="buy" checked />
            </label>
            <label class="contact-interested">Rent
                <input type="radio" name="interest" value="rent" />
            </label>
            <label class="contact-interested">Other
                <input type="radio" name="interest" value="other" />
            </label>
        </fieldset>

        <label>Message
            <textarea name="message" rows="4" placeholder="How can we help?"></textarea>
        </label>

        <button class="btn btn-primary" type="submit">Submit</button>
    </form>
</section>

<?php include 'footer.php'; ?>