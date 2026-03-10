# OMR Infrastructure News – Article Draft (Feb 2025)

**Purpose:** Collated and elaborated infrastructure news about OMR and its areas, ready for the MyOMR news feature. Use the **Article content** and **Metadata** sections below to add this via `weblog/ADD-NEW-ARTICLE.php` or by inserting into the `articles` table.

**MyOMR news system (from codebase):**
- Articles are stored in the `articles` table and displayed at `/local-news/{slug}`.
- `article.php` renders title, summary, content (HTML), image, date, author; SEO and NewsArticle schema come from `core/article-seo-meta.php`.
- Home page cards use `weblog/home-page-news-cards.php` and show title, summary, date, image; "Read More" links to `/local-news/{slug}`.
- Content supports `<p>`, `<h2>`, `<h3>`, and the styled divs below (`.info-box` / `.alert-box` as in `article.php`).

---

## Article content (HTML – paste into Content field)

```html
<p>Old Mahabalipuram Road (OMR) is seeing a mix of new infrastructure openings, ongoing Metro work, and planned road repairs. Here’s a collated round-up of what’s happening along the IT corridor and how it affects residents and commuters.</p>

<h2>New U-Shaped Flyover and Foot Overbridge at Tidel Park</h2>

<p>In late February 2025, Deputy Chief Minister Udhayanidhi Stalin inaugurated a second U-shaped flyover and a foot overbridge at the Tidel Park junction on OMR. The flyover spans 510 metres and was built at a cost of around ₹27.5 crore. It allows vehicles from ECR to reach Madhya Kailash without stopping at signals and connects to CSIR Road in Taramani.</p>

<p>A 155-metre foot overbridge with escalators was also opened at an estimated cost of ₹11.3 crore. It connects Thiruvanmiyur MRTS, Tidel Park, and nearby IT companies, making it easier for pedestrians and office-goers to cross OMR safely.</p>

<div class="info-box">
<strong>At a glance</strong><br>
• Flyover: 510 m, ~₹27.5 crore; ECR → Madhya Kailash / CSIR Road<br>
• Foot overbridge: 155 m with escalators, ~₹11.3 crore<br>
• Location: Tidel Park junction, OMR
</div>

<h2>Chennai Metro Phase II and OMR Traffic</h2>

<p>Phase II of the Chennai Metro Rail project is affecting traffic and road conditions on OMR. The stretch between SRP Tools and Navalur is especially congested during peak hours. The road surface has deteriorated in several places, and many footpaths have been removed or blocked by construction, making it harder for pedestrians.</p>

<p>Motorists report longer commute times. Once Metro work is fully completed and the remaining stretch is handed over to the Tamil Nadu Road Development Company (TNRDC), a full relaying of the road is planned.</p>

<h2>OMR–ECR Link Road Near Sholinganallur</h2>

<p>The OMR–ECR link road near the Sholinganallur junction is in poor condition. Construction debris, dust, and a reduced road width are making commutes difficult. The road level has dipped in places due to construction, and there have been reports of accidents, including a motorcycle fall and a car overturn at the SRP Tools junction. Potholes are widespread; some temporary repairs using steel frames have themselves been linked to safety concerns.</p>

<div class="alert-box">
<strong>Commuter note</strong><br>
Drive with extra care on the OMR–ECR link and around SRP Tools. Expect delays and uneven surfaces until repairs and Metro handover are complete.
</div>

<h2>5 km of OMR to Be Relaid by September 2025</h2>

<p>The Tamil Nadu Road Development Company (TNRDC) plans to re-lay about 5 km of OMR by the end of September 2025. TNRDC has called four tenders worth around ₹40 crore to repair service lanes, correct road levels, and improve drainage. The full road will be completely re-laid once the remaining stretch is handed over to TNRDC after about one-and-a-half years.</p>

<h2>Road Widening: Siruseri SIPCOT to Padur</h2>

<p>A separate ₹42-crore road-widening project is under way on a 2.5 km stretch between Siruseri SIPCOT and Padur. It is expected to be completed by February 2026 and will ease traffic on this part of OMR.</p>

<h2>Broader Infrastructure Concerns on the IT Corridor</h2>

<p>A joint inspection of 2,513 buildings on the IT corridor highlighted wider issues: inadequate water and sewage connections, poor road infrastructure, ineffective stormwater drainage, and solid-waste management problems. The area saw severe flooding even with minimal rainfall in 2024, which led to a Chief Secretary-level inspection. Residents have indicated they will take these infrastructure concerns to the State government.</p>

<p>These findings underline that, alongside new flyovers and Metro work, OMR still needs sustained investment in water, drainage, and roads to become a fully resilient corridor.</p>

<h2>Summary for OMR Residents</h2>

<p>New infrastructure such as the Tidel Park flyover and foot overbridge is improving connectivity and safety. At the same time, Metro Phase II work is causing congestion and damaged roads, especially between SRP Tools and Navalur and on the OMR–ECR link. Planned measures include about 5 km of OMR relaying by September 2025 and the Siruseri–Padur widening by February 2026. Residents can expect a mix of short-term disruption and medium-term improvement as these projects are completed.</p>

<p><strong>Sources:</strong> The Hindu, The New Indian Express, Times of India, Live Chennai (February 2025).</p>
```

---

## Metadata for MyOMR news (articles table / ADD-NEW-ARTICLE)

Use these values when adding the article via the Add New Article form or SQL.

| Field | Value |
|-------|--------|
| **title** | OMR Infrastructure Update: New Flyover at Tidel Park, Metro Impact, and Road Repairs |
| **slug** | omr-infrastructure-update-flyover-metro-road-repairs-feb-2025 |
| **summary** | A round-up of OMR infrastructure: new U-shaped flyover and foot overbridge at Tidel Park, Chennai Metro Phase II traffic impact between SRP Tools and Navalur, poor condition of the OMR–ECR link road, 5 km relaying by September 2025, and Siruseri–Padur widening by Feb 2026. |
| **category** | Local News |
| **author** | MyOMR Editorial Team |
| **status** | published |
| **published_date** | 2025-02-24 |
| **image_path** | /My-OMR-Logo.png |
| **tags** | OMR, Old Mahabalipuram Road, Chennai, infrastructure, flyover, Tidel Park, Chennai Metro, road repair, Sholinganallur, Navalur, SRP Tools, TNRDC |

**Meta description (≤155 chars):**  
`OMR infrastructure round-up: new Tidel Park flyover and FOB, Metro Phase II traffic impact, road repairs by Sept 2025, Siruseri–Padur widening by Feb 2026.`

**Notes:**
- Use the **Meta description** above in the summary field if your form stores it for meta; otherwise use the longer summary for the homepage card.
- **image_path:** Replace with a dedicated image under `/local-news/omr-news-images/` when you have one (e.g. Tidel Park flyover or OMR road).
- **Single quotes in HTML:** If you paste into ADD-NEW-ARTICLE and see SQL issues, replace any single quote in the HTML with `&apos;` as per the form instructions.

---

## SQL insert (optional)

If you add the article directly via phpMyAdmin or a script, use the same metadata and escape the HTML content for your database (e.g. `mysqli_real_escape_string` or prepared statement). Example structure:

```sql
INSERT INTO articles (title, slug, summary, content, category, author, status, published_date, image_path, tags, is_featured, created_at, updated_at)
VALUES (
  'OMR Infrastructure Update: New Flyover at Tidel Park, Metro Impact, and Road Repairs',
  'omr-infrastructure-update-flyover-metro-road-repairs-feb-2025',
  'A round-up of OMR infrastructure: new U-shaped flyover and foot overbridge at Tidel Park, Chennai Metro Phase II traffic impact between SRP Tools and Navalur, 5 km relaying by September 2025, and Siruseri–Padur widening by Feb 2026.',
  '<p>Old Mahabalipuram Road (OMR) is seeing...</p>...',  -- full HTML content here, escaped
  'Local News',
  'MyOMR Editorial Team',
  'published',
  '2025-02-24',
  '/My-OMR-Logo.png',
  'OMR, Old Mahabalipuram Road, Chennai, infrastructure, flyover, Tidel Park, Chennai Metro, road repair, Sholinganallur, Navalur, SRP Tools, TNRDC',
  0,
  NOW(),
  NOW()
);
```

---

## How to use this in MyOMR

1. **Via ADD-NEW-ARTICLE.php:** Open the form, enter password, then paste **title**, **slug**, **summary**, **Article content (HTML)** into the Content textarea, and fill **category**, **author**, **status**, **published_date**, **image_path**, **tags**. Submit.
2. **Via admin/articles (if available):** Use the same fields in your admin add-article screen.
3. **Direct DB:** Run the SQL insert with the full HTML content properly escaped.
4. After publishing, the article will appear on the homepage news cards and at **https://myomr.in/local-news/omr-infrastructure-update-flyover-metro-road-repairs-feb-2025**.
