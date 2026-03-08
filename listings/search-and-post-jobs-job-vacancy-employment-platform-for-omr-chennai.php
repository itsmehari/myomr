<?php include 'weblog/log.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../components/analytics.php'; ?>

    <!-- Meta tags for SEO -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Find local jobs on OMR Road, Chennai. Apply for teaching jobs, digital marketing positions, and more. Updated listings with direct contact information.">
    <meta name="keywords" content="Jobs in OMR Road, Chennai Jobs, Digital Marketing Jobs, Teacher Jobs, Python Developer Jobs, Machine Learning Jobs">
    <meta name="author" content="Krishnan">
    <meta property="og:title" content="Jobs in OMR - Local Job Listings on OMR Road, Chennai" />
    <meta property="og:description" content="Find jobs on OMR Road, including teaching, IT, and digital marketing roles. Apply directly with detailed contact info." />
    <meta property="og:image" content="https://myomr.in/My-OMR-Logo.jpg" />
    <meta property="og:url" content="https://myomr.in/" />
    <meta property="og:site_name" content="My OMR Jobs Portal" />

    <!-- Title -->
    <title>Job Listings on OMR Road - My OMR Jobs Portal</title>

    <!-- External CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400&display=swap" rel="stylesheet">

    <!-- Internal CSS -->
    <style>
        body {
            font-family: 'Josefin Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            margin-top: 30px;
        }

        /* Job Grid Layout */
        .job-grid {
            display: flex;
            flex-wrap: wrap; /* Allows wrapping of job cards on smaller screens */
            gap: 20px; /* Gap between the job cards */
            justify-content: space-between; /* Ensures even spacing between job cards */
        }

        .job-card {
            flex: 1 1 48%; /* Each job card takes up about half the width, with some gap */
            min-height: 100%; /* Ensures all job cards have the same minimum height */
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            box-sizing: border-box; /* Ensures padding does not affect width */
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Keeps content evenly distributed */
        }

        .job-card h3 {
            color: #4c516D;
            font-size: 18px;
        }

        .job-card p {
            color: #666;
            font-size: 14px;
        }

        .job-card .btn-primary {
            background-color: #0056b3;
            border: none;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .job-card .location {
            font-weight: bold;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .job-card {
                flex: 1 1 100%; /* On smaller screens, each card takes up full width */
            }
        }

        /* Footer styling */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        .footer-social-icon {
            margin-top: 10px;
        }

        .footer-social-icon a {
            margin-right: 10px;
        }
    </style>
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



    <!-- Header & Navigation -->
    <?php include '../components/main-nav.php'; ?>

    <!-- Floating WhatsApp Icon -->
    <a href="https://chat.whatsapp.com/Eixz1mmURuFLvnNZzCfGDi" class="float" target="_blank">
        <i class="fa fa-whatsapp my-float"></i>
    </a>

    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <h2>My OMR</h2>
                <h5>Local Community News Portal</h5>
                <div><img src="My-OMR-Idhu-Namma-OMR-Logo.jpg" style="width:inherit; position:relative;max-width: 280px; padding-bottom:10px;" alt="My OMR Logo"></div>

                <!-- Navigation Links -->
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item"><a class="nav-link active" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="local-news/news-highlights-from-omr-road.php">Highlights</a></li>
                    <li class="nav-item"><a class="nav-link" href="old-mahabalipuram-road-news-image-video-gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link disabled" href="contact-my-omr-team.php">Contact</a></li>
                </ul>

                <!-- Ads -->
                <img src="5th-12th-Tutions-All-Subjects-In-OMR.jpg" style="width:inherit;max-width: 280px; padding-bottom:10px;" alt="Tuitions Advertisement" />
                <img src="DTP-TYPING-Advertisement-MyOMR.jpg" style="width:inherit;max-width: 280px; padding-bottom:10px;" alt="Typing Advertisement" />
                <img src="Place-Ads-myomr-Website-OMRnews.jpg" style="width:inherit;max-width: 280px; padding-bottom:10px;" alt="Place Ads Advertisement" />
            </div>

            <!-- Job Listings Section -->
            <div class="col-sm-8">
                <div class="job-grid">
                    <!-- Job Post 1 -->
                    <div class="job-card">
                        <h3>Hiring Teachers for Reputed School in Keelakatalai</h3>
                        <h4>Employer: Baalya Schools</h4>
                        <p class="location">Location: Keelakatalai</p>
                        <h5>Job Vacancies:</h5>
                        <p>● Teacher for LKG & UKG<br>● Subject Teacher for Maths</p>
                        <img src="images/jobs/Baalya-School-Vacancy-For-Teachers-Sept-2024.jpg" alt="Teacher Job Vacancy 2024" />
                        <p>Contact Person: <a href="https://www.facebook.com/baalyaa.baalyaa/"><strong>Mrs. Mani Umamageshwari</strong></a></p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <h6>Call: 8072327909 (Between 9 A.M to 7 P.M)</h6>
                    </div>

                    <!-- Job Post 2 -->
                    <div class="job-card">
                        <h3>Hiring Python for Machine Learning</h3>
                        <h4>Employer: Prozenics</h4>
                        <p class="location">Location: Remote / WFH</p>
                        <h5>Job Responsibilities:</h5>
                        <p>● Develop, train, and deploy machine learning models using TensorFlow and/or PyTorch<br>● Implement models using Scikit-learn, including SVM, Decision Trees, etc.</p>
                        <h5>Job Requirements:</h5>
                        <p>● 2+ years of experience in Python-based frameworks<br>● Proficiency in TensorFlow/PyTorch<br>● Strong knowledge of machine learning algorithms</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Contact Person: <a href="https://www.facebook.com/prasad.mani.5"><strong>Prasad Mani</strong></a></p>
                    </div>

                    <!-- Job Post 3 -->
                    <div class="job-card">
                        <h3>Digital Marketing Intern / Assistant</h3>
                        <h4>Employer: Cleanbios Constructions</h4>
                        <p class="location">Location: Guindy, Chennai</p>
                        <h5>Job Description:</h5>
                        <p>Join our dynamic team at CleanBios as a Digital Marketing Intern/Assistant! Hands-on experience in data gathering, content creation, and more.</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Call: 9884785845 (Between 9 A.M to 7 P.M)</p>
                        <a href="https://wa.me/919884785845" class="btn btn-primary">Apply via WhatsApp</a>
                    </div>

                    <!-- Job Post 4 -->
                    <div class="job-card">
                        <h3>Job Opportunities for Women</h3>
                        <h4>Employer: HUMAART, Thoraipakkam</h4>
                        <p class="location">Location: Thoraipakkam, OMR</p>
                        <h5>Job Description:</h5>
                        <p>● Food Cook (5 Women)<br>● Counter Helper (5 Women)</p>
                        <p>Shifts:<br>1) 7:30 AM - 5:30 PM | Rs. 13,000<br>2) 1:30 PM - 9:30 PM | Rs. 10,000</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Call: 9042950282 (Between 9 A.M to 7 P.M)</p>
                    </div>

                    <!-- Job Post 5 -->
                    <div class="job-card">
                        <h3>Requirement for .NET Developer</h3>
                        <h4>Employer: Teampro HR & IT Services Pvt Ltd</h4>
                        <p class="location">Location: Padur, OMR</p>
                        <h5>Job Description:</h5>
                        <p>● Proficient in ASP.NET, C#, JavaScript, SQL Server<br>● 6+ years experience in .NET</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Call: 8754843108 (Between 9 A.M to 7 P.M)</p>
                    </div>

                    <!-- Job Post 6 -->
                    <div class="job-card">
                        <h3>Field Sales Executives</h3>
                        <h4>Employer: Teampro HR & IT Services Pvt Ltd</h4>
                        <p class="location">Location: Porur, Chennai</p>
                        <h5>Job Description:</h5>
                        <p>● Walk-in interviews for Field Sales Executives. Immediate joining available.</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Call: 7305992805 (Between 9 A.M to 7 P.M)</p>
                    </div>

                    <!-- Job Post 7 -->
                    <div class="job-card">
                        <h3>Female Cook - Veg</h3>
                        <h4>Employer: OMR Resident</h4>
                        <p class="location">Location: Perumbakkam, Chennai</p>
                        <h5>Job Description:</h5>
                        <p>Looking for a vegetarian cook for evening snacks and dinner for 4 people. Only female required.</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Contact: 9577407007 (Between 6 PM to 9 PM)</p>
                    </div>

                    <!-- Job Post 8 -->
                    <div class="job-card">
                        <h3>Senior Web Designer/Developer</h3>
                        <h4>Employer: Reputed College Group, OMR</h4>
                        <p class="location">Location: Sholinganallur, Chennai</p>
                        <h5>Job Description:</h5>
                        <p>● 3-5 years of experience in web design<br>● Proficient in WordPress, HTML, PHP, MySQL</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Send CV to: adsathaktrust@gmail.com</p>
                        <p>WhatsApp: +919003141499 (Apply before 1st October)</p>
                    </div>

                    <!-- Job Post 9 -->
                    <div class="job-card">
                        <h3>Senior Medical Coders</h3>
                        <h4>Employer: Foxibe Innovations Pvt Ltd</h4>
                        <p class="location">Location: Velachery, Chennai</p>
                        <h5>Job Description:</h5>
                        <p>● IPDRG, E&M, Lab & Radiology Coding<br>● Minimum 3+ years of experience</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Email: shyam@foxibe.com</p>
                        <p>WhatsApp: 9500087164</p>
                    </div>

                    <!-- Job Post 10 -->
                    <div class="job-card">
                        <h3>AR Callers</h3>
                        <h4>Employer: Foxibe Innovations Pvt Ltd</h4>
                        <p class="location">Location: Velachery, Chennai</p>
                        <h5>Job Description:</h5>
                        <p>● Freshers position as AR Executive for international voice process<br>● 18k-20k CTC, US Shift</p>
                        <h6>Current Status: <span style="color:green;font-weight:bold;">OPEN</span></h6>
                        <p>Email: shyam@foxibe.com</p>
                        <p>WhatsApp: 9500087164</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <?php include '../components/footer.php'; ?>

    <!-- Bootstrap and JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
