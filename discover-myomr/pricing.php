<?php
$page_title       = 'Pricing Plans - MyOMR Community Portal | OMR Chennai';
$page_description = 'Browse OMR Chennai\'s #1 local portal free. Upgrade your business with Verified or Featured listings for maximum local visibility.';
$canonical_url    = 'https://myomr.in/discover-myomr/pricing.php';
$og_image         = 'https://myomr.in/My-OMR-Logo.jpg';
$og_title         = $page_title;
$og_description   = $page_description;
$og_url           = $canonical_url;
$breadcrumbs      = [['https://myomr.in/','Home'],['https://myomr.in/discover-myomr/pricing.php','Pricing']];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../components/meta.php'; ?>
<?php include '../components/analytics.php'; ?>
<?php include '../components/head-resources.php'; ?>
  <style>
    body { font-family: 'Poppins', sans-serif; }
    :root { --brand: #14532d; --brand-mid: #166534; --brand-accent: #22c55e; --brand-light: #f0fdf4; }

    /* Hero */
    .pricing-hero {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      color: #fff; padding: 72px 0 56px;
    }
    .pricing-hero h1 { font-weight: 700; }
    .hero-badge-pill {
      display: inline-block; background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.3); border-radius: 20px;
      padding: 5px 18px; font-size: .85rem; margin-bottom: 1.25rem;
    }

    /* Plan cards */
    .plan-card {
      border-radius: 16px; border: 2px solid #e5e7eb;
      padding: 36px 28px; background: #fff; height: 100%;
      transition: transform .2s, box-shadow .2s; position: relative;
    }
    .plan-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(20,83,45,.12); }
    .plan-card.popular { border-color: var(--brand); box-shadow: 0 8px 30px rgba(20,83,45,.18); }
    .popular-badge {
      position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
      background: var(--brand); color: #fff; font-size: .75rem; font-weight: 600;
      padding: 4px 20px; border-radius: 20px; white-space: nowrap; text-transform: uppercase; letter-spacing: .04em;
    }
    .plan-name { font-size: 1.1rem; font-weight: 600; color: #374151; }
    .plan-tagline { color: #6b7280; font-size: .875rem; }
    .plan-price { font-size: 2.75rem; font-weight: 700; color: var(--brand); line-height: 1; }
    .plan-price sup { font-size: 1.35rem; vertical-align: top; padding-top: .4rem; }
    .plan-price .period { font-size: 1rem; color: #6b7280; font-weight: 400; }
    .plan-sub { font-size: .8rem; color: #9ca3af; }
    .plan-feature { font-size: .875rem; color: #374151; padding: .3rem 0; }
    .plan-feature i { color: var(--brand-accent); width: 20px; }
    .btn-plan {
      background: var(--brand); color: #fff; border: none; border-radius: 8px;
      padding: 12px 0; font-weight: 600; width: 100%; font-size: 1rem;
      transition: background .2s; text-decoration: none; display: block; text-align: center;
    }
    .btn-plan:hover { background: #0f3d1f; color: #fff; }
    .btn-plan-outline {
      background: transparent; color: var(--brand); border: 2px solid var(--brand); border-radius: 8px;
      padding: 10px 0; font-weight: 600; width: 100%; font-size: 1rem;
      transition: all .2s; text-decoration: none; display: block; text-align: center;
    }
    .btn-plan-outline:hover { background: var(--brand); color: #fff; }

    /* Areas */
    .areas-strip { background: #f9fafb; border-radius: 12px; }
    .area-pill {
      display: inline-block; background: #fff; border: 1px solid #d1fae5;
      color: var(--brand); font-size: .78rem; font-weight: 500;
      padding: 4px 14px; border-radius: 20px; margin: 3px;
    }

    /* FAQ */
    .faq-item { border-radius: 10px !important; border: 1px solid #e5e7eb !important; margin-bottom: .75rem; }
    .faq-item .accordion-button:not(.collapsed) { background: var(--brand-light); color: var(--brand); box-shadow: none; }
    .faq-item .accordion-button { font-weight: 500; color: #374151; border-radius: 10px !important; }

    /* CTA block */
    .contact-cta-block {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
      border-radius: 16px; color: #fff;
    }
    .contact-cta-block h2 { font-weight: 700; }
    .section-heading { font-weight: 700; color: var(--brand); }
  </style>
</head>
<body>

<?php include '../components/main-nav.php'; ?>

<!-- HERO -->
<section class="pricing-hero">
  <div class="container text-center">
    <div class="hero-badge-pill"><i class="fas fa-map-marker-alt me-1"></i> OMR Chennai's Trusted Local Portal</div>
    <h1 class="display-5 mb-3">Simple, Transparent Plans</h1>
    <p class="lead mb-0 mx-auto" style="max-width:580px;opacity:.92;">
      Browsing MyOMR is always free. Upgrade your business listing for verified status,
      priority placement, and maximum local visibility in OMR.
    </p>
  </div>
</section>

<!-- PRICING CARDS -->
<section class="py-5">
  <div class="container">
    <div class="row g-4 justify-content-center">

      <!-- Free -->
      <div class="col-lg-3 col-md-6">
        <div class="plan-card">
          <div class="plan-name mb-1">Free</div>
          <div class="plan-tagline mb-3">Always free, forever</div>
          <div class="plan-price mb-4"><sup>₹</sup>0</div>
          <ul class="list-unstyled mb-4">
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Browse all news, events &amp; jobs</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Search the business directory</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>View coworking &amp; PG listings</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Apply to jobs directly</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Submit community events</li>
          </ul>
          <a href="/" class="btn-plan-outline">Start Browsing Free</a>
        </div>
      </div>

      <!-- Verified -->
      <div class="col-lg-3 col-md-6">
        <div class="plan-card">
          <div class="plan-name mb-1">Verified Listing</div>
          <div class="plan-tagline mb-3">For local businesses</div>
          <div class="plan-price mb-1"><sup>₹</sup>999<span class="period">/yr</span></div>
          <div class="plan-sub mb-4">Less than ₹84/month</div>
          <ul class="list-unstyled mb-4">
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Verified trust badge on listing</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Priority in locality filter results</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Full contact info displayed</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Google Maps link integration</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Annual listing review &amp; update</li>
          </ul>
          <a href="#contact-cta" class="btn-plan">Get Verified</a>
        </div>
      </div>

      <!-- Featured — Most Popular -->
      <div class="col-lg-3 col-md-6">
        <div class="plan-card popular">
          <div class="popular-badge"><i class="fas fa-star me-1"></i> Most Popular</div>
          <div class="plan-name mb-1">Featured</div>
          <div class="plan-tagline mb-3">Maximum local visibility</div>
          <div class="plan-price mb-1"><sup>₹</sup>2,499<span class="period">/yr</span></div>
          <div class="plan-sub mb-4">Less than ₹209/month</div>
          <ul class="list-unstyled mb-4">
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Everything in Verified</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Featured card on directory homepage</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Custom business blurb &amp; logo</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Sponsored placement in search results</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Promoted in OMR community posts</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Monthly performance summary</li>
          </ul>
          <a href="#contact-cta" class="btn-plan">Get Featured</a>
        </div>
      </div>

      <!-- Lifetime -->
      <div class="col-lg-3 col-md-6">
        <div class="plan-card">
          <div class="plan-name mb-1">Lifetime</div>
          <div class="plan-tagline mb-3">One-time, own it forever</div>
          <div class="plan-price mb-1"><sup>₹</sup>4,999<span class="period"> once</span></div>
          <div class="plan-sub mb-4">Pay once, never again</div>
          <ul class="list-unstyled mb-4">
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Everything in Featured — forever</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>VIP community badge</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Priority support access</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>All future feature upgrades included</li>
            <li class="plan-feature"><i class="fas fa-check me-2"></i>Unlimited job &amp; event posts/year</li>
          </ul>
          <a href="#contact-cta" class="btn-plan">Claim Lifetime</a>
        </div>
      </div>

    </div><!-- /row -->

    <p class="text-center text-muted mt-4" style="font-size:.86rem;">
      <i class="fas fa-shield-alt text-success me-1"></i>
      No hidden charges. Payment via UPI/bank transfer after inquiry — online gateway coming soon.
    </p>
  </div>
</section>

<!-- AREAS COVERED -->
<section class="py-4">
  <div class="container">
    <div class="areas-strip p-4">
      <h5 class="section-heading mb-3"><i class="fas fa-map-marker-alt me-2"></i>Covering the Entire OMR Corridor</h5>
      <div>
        <?php
        $areas = ['Perungudi','Thoraipakkam','Karapakkam','Kandhanchavadi','Sholinganallur',
                  'Navalur','Thazhambur','Kelambakkam','Siruseri','Mettukuppam',
                  'Semmancheri','Perumbakkam','Egattur','Padur'];
        foreach ($areas as $a): ?>
          <span class="area-pill"><?= htmlspecialchars($a) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="section-heading text-center mb-4">Frequently Asked Questions</h2>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="accordion" id="pricingFaq">

          <div class="faq-item accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                Is browsing MyOMR really free forever?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#pricingFaq">
              <div class="accordion-body text-muted">
                Yes — all news, events, jobs, business directory, coworking spaces, and PG listings are free to browse. No account required.
              </div>
            </div>
          </div>

          <div class="faq-item accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                What exactly does a Verified listing include?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
              <div class="accordion-body text-muted">
                A Verified listing adds a trust badge, priority placement in locality filters, your full contact details, Google Maps integration, and an annual review by our team.
              </div>
            </div>
          </div>

          <div class="faq-item accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                How do I pay?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
              <div class="accordion-body text-muted">
                We currently accept UPI or bank transfer after processing your inquiry. A secure online payment gateway is coming soon. Contact us below — we'll have you live within 24 hours.
              </div>
            </div>
          </div>

          <div class="faq-item accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                Can I upgrade from Verified to Featured later?
              </button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
              <div class="accordion-body text-muted">
                Yes, at any time. We'll pro-rate the difference based on your remaining listing period.
              </div>
            </div>
          </div>

          <div class="faq-item accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                Which businesses can get a Featured listing?
              </button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
              <div class="accordion-body text-muted">
                Any local business in the OMR corridor — restaurants, schools, IT companies, hospitals, coworking spaces, gyms, clinics, and service providers.
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-5" id="contact-cta">
  <div class="container">
    <div class="contact-cta-block p-5 text-center">
      <h2 class="mb-3">Ready to Get Listed?</h2>
      <p class="mb-4" style="opacity:.9;">Message us on WhatsApp or email — we'll set up your listing within 24 hours.</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="https://wa.me/919445088028?text=Hi%2C+I%27m+interested+in+getting+my+business+listed+on+MyOMR.in"
           target="_blank" rel="noopener" class="btn btn-light btn-lg fw-semibold">
          <i class="fab fa-whatsapp me-2 text-success"></i>WhatsApp Us
        </a>
        <a href="mailto:info@myomr.in?subject=MyOMR%20Listing%20Enquiry" class="btn btn-outline-light btn-lg fw-semibold">
          <i class="fas fa-envelope me-2"></i>Email Us
        </a>
      </div>
      <p class="mt-3 mb-0" style="font-size:.84rem;opacity:.75;">info@myomr.in &nbsp;·&nbsp; +91&nbsp;94450&nbsp;88028</p>
    </div>
  </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
