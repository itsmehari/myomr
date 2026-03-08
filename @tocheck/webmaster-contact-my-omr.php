<?php include 'components/main-nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Webmaster - MyOMR</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .contact-container { max-width: 700px; margin: 2rem auto; padding: 2rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .contact-container h1 { color: #14532d; font-size: 2.2rem; margin-bottom: 1rem; }
        .contact-container p { color: #333; line-height: 1.7; }
        .contact-form { margin-top: 2rem; }
        .contact-form label { display: block; margin-bottom: 0.5rem; color: #14532d; }
        .contact-form input, .contact-form textarea { width: 100%; padding: 0.7rem; margin-bottom: 1rem; border: 1px solid #ccc; border-radius: 4px; }
        .contact-form button { background: #22c55e; color: #fff; border: none; padding: 0.7rem 2rem; border-radius: 4px; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
        .contact-form button:hover { background: #14532d; }
        .contact-details { margin-top: 2rem; }
        .contact-details li { margin-bottom: 0.5rem; }
    </style>
</head>
<body>
    <main class="contact-container">
        <h1>Contact Webmaster</h1>
        <p>If you have any technical issues, suggestions, or need to reach the MyOMR webmaster, please use the form below or contact us directly.</p>
        <form class="contact-form" method="post" action="#">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required>
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" required></textarea>
            <button type="submit">Send Message</button>
        </form>
        <div class="contact-details">
            <h2>Contact Details</h2>
            <ul>
                <li><strong>Email:</strong> <a href="mailto:myomrnews@gmail.com">myomrnews@gmail.com</a></li>
                <li><strong>Support:</strong> <a href="mailto:support@myomr.in">support@myomr.in</a></li>
                <li><strong>Phone:</strong> +91 XXXXXXXXXX</li>
            </ul>
        </div>
    </main>
    <?php include 'components/footer.php'; ?>
</body>
</html> 