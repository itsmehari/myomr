<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rent Your Land in OMR - List Your Property | MyOMR</title>
    <meta name="description" content="List your land for rent in OMR, Chennai. Get verified tenants and the best rental deals. Easy listing, quick responses.">
    <meta name="keywords" content="rent land OMR, rental land Chennai, OMR land for lease, property listing OMR">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="components/nav-footer-styles.css">
</head>
<body>

<!-- Include Navigation Bar -->
<?php include 'components/nav-bar.php'; ?>

<!-- Include Action Links -->
<?php include 'components/action-links.php'; ?>

<!-- Main Content -->
<div class="container">
    <h1>Rent Your Land in OMR</h1>
    <p>Fill in the details below to proceed.</p>

    <form action="process-listing.php" method="POST">
        <input type="hidden" name="service_type" value="rent-land">
        
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
