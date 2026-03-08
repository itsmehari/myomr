<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rent Your House in OMR - List Your Property | MyOMR</title>
    <meta name="description" content="List your house for rent in OMR, Chennai. Get verified tenants and the best rental deals. Easy listing, quick responses.">
    <meta name="keywords" content="rent house OMR, rental property Chennai, OMR apartments for rent, property listing OMR">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- External CSS -->
    <link rel="stylesheet" href="assets/css/action-links.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header>
        <h1>Rent Your House in OMR</h1>
        <p>Find tenants easily by listing your rental property in Old Mahabalipuram Road (OMR).</p>
    </header>

    <section>
        <h2>Why List Your House on MyOMR?</h2>
        <ul>
            <li>✅ <b>High Visibility</b> - Reach tenants actively searching for rental homes in OMR.</li>
            <li>✅ <b>Verified Tenants</b> - Connect with genuine renters.</li>
            <li>✅ <b>Fast Responses</b> - Get inquiries from interested tenants.</li>
        </ul>
    </section>

    <section>
        <h2>Post Your Rental Property</h2>
        <form action="submit-rental.php" method="POST">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="property-details">Property Details:</label>
            <textarea id="property-details" name="property_details" required></textarea>

            <button type="submit">Submit Listing</button>
        </form>
    </section>

    <!-- Include the Action Links Component -->
    <?php include 'components/action-links.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/action-links.js"></script>

</body>
</html>
