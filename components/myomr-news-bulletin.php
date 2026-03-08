<?php
if (!defined('MYOMR_NEWS_BULLETIN')) {
    define('MYOMR_NEWS_BULLETIN', true);
    echo '<link rel="stylesheet" href="/assets/css/myomr-news-bulletin.css">';
}
?>

<section class="omr-newsroom" aria-label="OMR Latest News">

    <!-- ── Section Header ── -->
    <div class="newsroom-header">
        <div class="newsroom-header__left">
            <span class="newsroom-header__eyebrow">Hyperlocal</span>
            <h2 class="newsroom-header__title">OMR News Bulletin</h2>
        </div>
        <a href="/omr-news.php" class="newsroom-viewall">
            View All Stories
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <!-- ── Main Layout: Left Main + Right Sidebar ── -->
    <div class="newsroom-layout">

        <!-- ══ LEFT: Hero + 2 Medium Cards ══ -->
        <div class="newsroom-main">

            <!-- HERO CARD -->
            <article class="news-hero">
                <a href="Mylapore-Kabaleeswarar-Arubathu-Moovar-Festival-2025-Timetable-63-Naayanmar.php" class="news-hero__link">
                    <div class="news-hero__image-wrap">
                        <img
                            src="https://swarajya.gumlet.io/swarajya/2023-04/a39e0b00-8a2a-44cf-a0c9-b6c435a0a1d7/WhatsApp_Image_2023_04_03_at_20_37_56.jpeg?"
                            alt="Mylapore Panguni Festival 2025"
                            class="news-hero__img"
                            loading="eager"
                            fetchpriority="high">
                        <span class="news-badge badge--local">Local</span>
                    </div>
                    <div class="news-hero__body">
                        <h3 class="news-hero__title">Panguni Peruvizha 2025 Begins at Kapaleeshwarar Temple, Mylapore</h3>
                        <p class="news-hero__excerpt">The grand Panguni Festival at Mylapore Sri Kapaleeshwarar Temple runs from April 3–12, 2025, featuring the sacred Therottam chariot procession, 63 Moovar celebrations, and the divine Thirukalyanam celestial wedding ceremony.</p>
                        <div class="news-meta">
                            <span class="news-meta__source">OMR News Desk</span>
                            <span class="news-meta__sep" aria-hidden="true">·</span>
                            <time class="news-meta__date" datetime="2025-04-01">Apr 1, 2025</time>
                        </div>
                    </div>
                </a>
            </article>

            <!-- 2 MEDIUM CARDS -->
            <div class="newsroom-medium-row">

                <article class="news-medium">
                    <a href="news-old-mahabalipuram-road-omr/EPIC-Eco-Park-and-Information-Center-Seminar-Organic-Farming-Soft-Skills-Padur-Village-EPIC-Day-2024.php" class="news-medium__link">
                        <div class="news-medium__image-wrap">
                            <img
                                src="news-old-mahabalipuram-road-omr/omr-news-images/EPIC-Padur-Epic-Day-2024-Farmers-SelfHelpGroups-Seminar.png"
                                alt="EPIC Padur Farmers Seminar"
                                class="news-medium__img"
                                loading="lazy">
                            <span class="news-badge badge--community">Community</span>
                        </div>
                        <div class="news-medium__body">
                            <h4 class="news-medium__title">EPIC, Padur Empowers Farmers &amp; SHGs with Organic Farming Training</h4>
                            <div class="news-meta">
                                <span class="news-meta__source">OMR News Desk</span>
                                <span class="news-meta__sep" aria-hidden="true">·</span>
                                <time class="news-meta__date" datetime="2024-04-12">Apr 12, 2024</time>
                            </div>
                        </div>
                    </a>
                </article>

                <article class="news-medium">
                    <a href="news-old-mahabalipuram-road-omr/Elections-2024-Chennai-South-Lok-Sabha-Constitency-Fiery-Battle.php" class="news-medium__link">
                        <div class="news-medium__image-wrap">
                            <img
                                src="news-old-mahabalipuram-road-omr/omr-news-images/Elections-2024-Chennai-South-Lok-Sabha-Constitency-Fiery-Battle.jpg"
                                alt="Elections 2024 Chennai South"
                                class="news-medium__img"
                                loading="lazy">
                            <span class="news-badge badge--local">Local</span>
                        </div>
                        <div class="news-medium__body">
                            <h4 class="news-medium__title">OMR in Focus: Chennai South Lok Sabha Elections 2024 — A Fiery Battle</h4>
                            <div class="news-meta">
                                <span class="news-meta__source">OMR News Desk</span>
                                <span class="news-meta__sep" aria-hidden="true">·</span>
                                <time class="news-meta__date" datetime="2024-04-03">Apr 3, 2024</time>
                            </div>
                        </div>
                    </a>
                </article>

            </div><!-- /.newsroom-medium-row -->
        </div><!-- /.newsroom-main -->

        <!-- ══ RIGHT: Sidebar Compact List ══ -->
        <aside class="newsroom-sidebar" aria-label="More OMR stories">

            <div class="sidebar-more-label">More Stories</div>

            <article class="news-compact">
                <a href="news-old-mahabalipuram-road-omr/Omr-Radial-Road-Storm-Water-Drain-Work.php" class="news-compact__link">
                    <div class="news-compact__thumb">
                        <img src="news-old-mahabalipuram-road-omr/omr-news-images/Omr-Road-Link-Road-Storm-Water-Drain-Project-Works.jpg" alt="Radial Road Work" loading="lazy">
                    </div>
                    <div class="news-compact__body">
                        <span class="news-badge badge--infra">Infra</span>
                        <h5 class="news-compact__title">Incomplete Work Stalls Progress on OMR Radial Road</h5>
                        <time class="news-meta__date" datetime="2023-10-06">Oct 6, 2023</time>
                    </div>
                </a>
            </article>

            <article class="news-compact">
                <a href="news-old-mahabalipuram-road-omr/HappyStreets-OMR-Road.php" class="news-compact__link">
                    <div class="news-compact__thumb">
                        <img src="happy-streets-omr-photo-gallery/Happy-Streets-OMR-Road-Photo-Gallery-06.jpg" alt="Happy Streets OMR" loading="lazy">
                    </div>
                    <div class="news-compact__body">
                        <span class="news-badge badge--events">Events</span>
                        <h5 class="news-compact__title">Happy Streets Festival Brings Joy to OMR Road</h5>
                        <time class="news-meta__date" datetime="2022-09-21">Sep 21, 2022</time>
                    </div>
                </a>
            </article>

            <article class="news-compact">
                <a href="news-old-mahabalipuram-road-omr/Silver-Jubliee-Celebraations-Ambedkar-Law-University-CM-Stalin-Sept-20th-2022.php" class="news-compact__link">
                    <div class="news-compact__thumb">
                        <img src="https://myomr.in/first-page-images/CM-Stalin-Addresses-The%20-Gathering-At-Ambedkar-Law-Univeristy-Tharamani-OMR.webp" alt="CM Stalin at Ambedkar Law University" loading="lazy">
                    </div>
                    <div class="news-compact__body">
                        <span class="news-badge badge--local">Local</span>
                        <h5 class="news-compact__title">TN CM Inaugurates Silver Jubilee at Ambedkar Law University, OMR</h5>
                        <time class="news-meta__date" datetime="2022-09-20">Sep 20, 2022</time>
                    </div>
                </a>
            </article>

            <article class="news-compact">
                <a href="news-old-mahabalipuram-road-omr/Fire-Breaks-Out-At-Corporation-Dump-Yard-Perungudi-2022.php" class="news-compact__link">
                    <div class="news-compact__thumb">
                        <img src="first-page-images/Perungudi-Dump-Yard-Fire-Incident-28-04-2022.webp" alt="Fire at Perungudi Dump Yard" loading="lazy">
                    </div>
                    <div class="news-compact__body">
                        <span class="news-badge badge--alert">Alert</span>
                        <h5 class="news-compact__title">Fire Breaks Out at Perungudi Dumpyard, Raises Safety Concerns</h5>
                        <time class="news-meta__date" datetime="2022-04-28">Apr 28, 2022</time>
                    </div>
                </a>
            </article>

            <article class="news-compact">
                <a href="news-old-mahabalipuram-road-omr/Amazon-Office-World-Trace-Center-OMR-Road-2022.php" class="news-compact__link">
                    <div class="news-compact__thumb">
                        <img src="first-page-images/WTC-Amazon-Business-Center-World-Trade-Center-OMR-Road-Perungudi.webp" alt="Amazon WTC OMR Road" loading="lazy">
                    </div>
                    <div class="news-compact__body">
                        <span class="news-badge badge--business">Business</span>
                        <h5 class="news-compact__title">Amazon Strengthens Tamil Nadu Presence with New OMR Office</h5>
                        <time class="news-meta__date" datetime="2022-03-30">Mar 30, 2022</time>
                    </div>
                </a>
            </article>

        </aside><!-- /.newsroom-sidebar -->
    </div><!-- /.newsroom-layout -->

    <!-- ── Section Footer ── -->
    <div class="newsroom-footer">
        <a href="/omr-news.php" class="newsroom-footer__link">
            View All OMR News Stories
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</section>
