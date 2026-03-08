<?php
// omr-road-social-media.php
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyOMR Social Media - Facebook, Instagram, Twitter & More</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/events/Omr-Road-Events-and-Happenings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f8; }
        .social-media-section { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 2rem; margin-top: 2rem; }
        .social-title { color: #008552; font-family: 'Playfair Display', serif; }
    </style>
</head>
<body>
<?php include 'components/navbar.php'; ?>
<div class="container">
    <div class="social-media-section">
        <h1 class="social-title mb-4 text-center"><i class="fab fa-facebook-square mr-2"></i>MyOMR Social Media</h1>
        <p class="lead text-center mb-5">Follow MyOMR on Facebook and stay updated with the latest news, events, and community happenings. More social media channels coming soon!</p>
        <div class="row justify-content-center">
            <div class="col-lg-8 mb-4">
                <div id="fb-root"></div>
                <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v22.0&appId=1889036664772365"></script>
                <div class="fb-page" data-href="https://www.facebook.com/myomrCommunity" data-tabs="timeline" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                    <blockquote cite="https://www.facebook.com/myomrCommunity" class="fb-xfbml-parse-ignore">
                        <a href="https://www.facebook.com/myomrCommunity">My OMR News Portal for Old Mahabalipuram Road</a>
                    </blockquote>
                </div>
            </div>
        </div>
        <!-- Future: Add Instagram, Twitter, YouTube, WhatsApp, etc. -->
    </div>
</div>
<?php include 'components/footer.php'; ?>
</body>
</html> 