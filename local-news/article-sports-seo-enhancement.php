<?php
/**
 * Additional SEO Structured Data for Sports Articles
 * Include this after article-seo-meta.php for sports-related articles
 * ONLY adds schemas when relevant data is detected (safe for all articles)
 */

// This file is conditionally included only for sports articles
// Check was already done in article.php before including this file

// Extract athlete name and event details from article (if available)
$athlete_name = '';
$event_name = '';
$event_date = $article_date;
$is_kabaddi_article = false;

// Try to extract athlete name from title (various patterns)
if (preg_match('/R\s+Karthika|Karthika\s+R/', $article['title'], $matches)) {
    $athlete_name = trim($matches[0]);
    $is_kabaddi_article = true;
} elseif (preg_match('/([A-Z][a-z]+\s+[A-Z][a-z]+)\s+(player|athlete|won|won gold|won silver|won bronze|led|captain|vice-captain)/i', $article['title'], $matches)) {
    // Generic pattern: "Firstname Lastname player/athlete/won/etc"
    $athlete_name = trim($matches[1]);
}

// Try to extract event name
if (preg_match('/Asian Youth Games|U-?18|Youth Games/', $article['title'], $matches)) {
    $event_name = trim($matches[0]);
}

// Check if this is about kabaddi
if (stripos($article['title'], 'kabaddi') !== false || 
    stripos($article['tags'] ?? '', 'kabaddi') !== false) {
    $is_kabaddi_article = true;
}

// ONLY add Person Schema if we have a specific athlete name
if (!empty($athlete_name) && $is_kabaddi_article):
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "<?php echo addslashes($athlete_name); ?>",
  "jobTitle": "Kabaddi Player, Vice-Captain",
  "worksFor": {
    "@type": "SportsTeam",
    "name": "Indian U-18 Girls Kabaddi Team",
    "sport": "Kabaddi"
  },
  "birthPlace": {
    "@type": "City",
    "name": "Chennai",
    "containedIn": {
      "@type": "State",
      "name": "Tamil Nadu"
    }
  },
  "homeLocation": {
    "@type": "Place",
    "name": "Kannagi Nagar, Chennai",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Kannagi Nagar",
      "addressRegion": "Tamil Nadu",
      "addressCountry": "IN"
    }
  },
  "sameAs": [
    "https://myomr.in/local-news/<?php echo htmlspecialchars($article['slug']); ?>"
  ],
  "description": "<?php echo addslashes($athlete_name); ?> is an Indian kabaddi player who served as vice-captain of the Indian U-18 girls' kabaddi team that won gold at the 2025 Asian Youth Games in Bahrain."
}
</script>

<!-- SportsEvent Schema - ONLY for Asian Youth Games articles -->
<?php if (!empty($event_name) && stripos($event_name, 'Asian Youth Games') !== false && $is_kabaddi_article): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SportsEvent",
  "name": "2025 Asian Youth Games - Kabaddi (Girls U-18)",
  "description": "Asian Youth Games 2025 Kabaddi competition for Girls U-18 category held in Bahrain. India defeated Iran 75-21 to win the gold medal.",
  "startDate": "2025-01-08",
  "endDate": "2025-01-15",
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "location": {
    "@type": "Place",
    "name": "Bahrain",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "BH"
    }
  },
  "organizer": {
    "@type": "Organization",
    "name": "Asian Youth Games Organizing Committee"
  },
  "sport": "Kabaddi",
  "competitor": [
    {
      "@type": "SportsTeam",
      "name": "Indian U-18 Girls Kabaddi Team",
      "sport": "Kabaddi",
      "position": 1,
      "award": "Gold Medal"
    },
    {
      "@type": "SportsTeam",
      "name": "Iran U-18 Girls Kabaddi Team",
      "sport": "Kabaddi",
      "position": 2,
      "award": "Silver Medal"
    }
  ]
}
</script>
<?php endif; ?>

<!-- FAQPage Schema - ONLY for R Karthika/Kannagi Nagar kabaddi articles -->
<?php if (!empty($athlete_name) && stripos($athlete_name, 'Karthika') !== false && $is_kabaddi_article): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Who is R Karthika?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "R Karthika is an Indian kabaddi player from Kannagi Nagar, Chennai, who served as vice-captain of the Indian U-18 girls' kabaddi team. She led the team to a historic gold medal victory at the 2025 Asian Youth Games in Bahrain, defeating Iran with a score of 75-21."
      }
    },
    {
      "@type": "Question",
      "name": "What did R Karthika achieve at the Asian Youth Games 2025?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "R Karthika, as vice-captain, led the Indian U-18 girls' kabaddi team to win the gold medal at the 2025 Asian Youth Games in Bahrain. The team defeated Iran with a commanding score of 75-21 in the final, marking the largest margin in a Youth Games kabaddi final since 2013."
      }
    },
    {
      "@type": "Question",
      "name": "Where is R Karthika from?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "R Karthika hails from Kannagi Nagar, a resettlement community in Chennai's Old Mahabalipuram Road (OMR) corridor. She trained in local open areas and community spaces before reaching international competition level."
      }
    },
    {
      "@type": "Question",
      "name": "What recognition did R Karthika receive?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Following the victory, R Karthika received formal recognition including a cheque of ₹25 lakh from Tamil Nadu Chief Minister M.K. Stalin. She also received ₹5 lakh from the Sports Development Authority of Tamil Nadu, ₹3 lakh from Chennai Corporation, and ₹2 lakh from her school, bringing the total recognition to ₹35 lakh."
      }
    },
    {
      "@type": "Question",
      "name": "What is the significance of R Karthika's achievement?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "R Karthika's achievement is significant as she became Tamil Nadu's first female kabaddi athlete to represent India at such a level in nine years. Her journey from Kannagi Nagar to international glory demonstrates the transformative power of sport and challenges stereotypes about resettlement colonies."
      }
    }
  ]
}
</script>
<?php endif; ?>

<?php
endif; // athlete_name && is_kabaddi_article check
// Note: This file is only included for sports articles (checked in article.php)
// But individual schemas are only added when relevant data is detected
?>

