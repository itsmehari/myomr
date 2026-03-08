<!DOCTYPE html>
<html lang="en">
<head>
    <title>List Your Business in OMR Directory | MyOMR</title>
    <meta name="description" content="Add your business to the OMR directory and increase your visibility among local customers.">
    <meta name="keywords" content="OMR business directory, list business Chennai, local business listings OMR">
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
    <h1>List Your Business in OMR</h1>
    <p>Fill in the details below to proceed.</p>

    <form action="process-listing.php" method="POST">
        <input type="hidden" name="service_type" value="add-business-directory">
        
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
