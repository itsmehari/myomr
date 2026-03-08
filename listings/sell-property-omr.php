<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sell Your Property in OMR - List Your House or Land | MyOMR</title>
    <meta name="description" content="Sell your house or land in OMR, Chennai. Find the right buyers quickly.">
    <meta name="keywords" content="sell house OMR, property sale Chennai, OMR real estate, house for sale OMR">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="components/nav-footer-styles.css">
</head>
<body>
    
    <!-- Brevo Conversations {literal} -->
<script>
    (function(d, w, c) {
        w.BrevoConversationsID = '67d9748a229e8b4212065491';
        w[c] = w[c] || function() {
            (w[c].q = w[c].q || []).push(arguments);
        };
        var s = d.createElement('script');
        s.async = true;
        s.src = 'https://conversations-widget.brevo.com/brevo-conversations.js';
        if (d.head) d.head.appendChild(s);
    })(document, window, 'BrevoConversations');
</script>
<!-- /Brevo Conversations {/literal} -->



<!-- Include Navigation Bar -->
<?php include 'components/nav-bar.php'; ?>

<!-- Include Action Links -->
<?php include 'components/action-links.php'; ?>

<!-- Main Content -->
<div class="container">
    <h1>Sell Your Property in OMR</h1>
    <p>Fill in the details below to proceed.</p>

    <form action="process-listing.php" method="POST">
        <input type="hidden" name="service_type" value="sell-property">
        
        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="details">Details:</label>
        <textarea id="details" name="details" required></textarea>

        <button type="submit">Submit</button>
    </form>
</div>

<!-- Include Footer -->
<?php include 'components/footer.php'; ?>

</body>
</html>
