<?php
/**
 * Homepage Elections 2026 section — high-impact, SEO-strong block.
 * Designed to grab attention and drive clicks to the election guide.
 * Semantic structure: one H2, countdown, descriptive links, primary CTA.
 */
$poll_date = new DateTime('2026-04-23');
$today = new DateTime('today');
$days_to_poll = $today->diff($poll_date)->days;
$is_past_poll = ($today > $poll_date);
$elections_base = '/elections-2026';
$elections_full_url = 'https://myomr.in' . $elections_base . '/';
?>
<section id="elections-2026" class="elections-2026-homepage homepage-section" aria-labelledby="elections-2026-heading">
  <div class="el-inner">
    <span class="el-badge" aria-hidden="true">
      <i class="fas fa-vote-yea" aria-hidden="true"></i> Your vote matters
    </span>
    <h2 id="elections-2026-heading" class="el-title">
      Tamil Nadu Election 2026 — Your OMR Voter Guide
    </h2>
    <?php if (!$is_past_poll): ?>
    <p class="el-countdown">
      <strong><?php echo (int) $days_to_poll; ?> days to poll</strong> · 23 April 2026 · Counting 4 May 2026
    </p>
    <?php else: ?>
    <p class="el-countdown">
      Poll was on 23 April 2026 · <a href="<?php echo $elections_base; ?>/results-2026.php" class="el-cta-secondary">See results</a>
    </p>
    <?php endif; ?>
    <p class="el-lead">
      One place for Old Mahabalipuram Road and Chennai South: Sholinganallur, Velachery &amp; Thiruporur. Know your constituency, find your BLO, key dates, candidates and how to vote.
    </p>
    <div class="el-actions">
      <a href="<?php echo $elections_base; ?>/know-your-constituency.php" class="el-action-card" title="Find which assembly constituency you belong to — Sholinganallur, Velachery or Thiruporur">
        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
        <span>Know your constituency</span>
      </a>
      <a href="<?php echo $elections_base; ?>/dates.php" class="el-action-card" title="Tamil Nadu election 2026 key dates — poll and counting">
        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
        <span>Key dates</span>
      </a>
      <a href="<?php echo $elections_base; ?>/how-to-vote.php" class="el-action-card" title="How to vote — EPIC, ID and voter checklist">
        <i class="fas fa-check-circle" aria-hidden="true"></i>
        <span>How to vote</span>
      </a>
      <a href="<?php echo $elections_base; ?>/find-blo.php" class="el-action-card" title="Find your Block Level Officer — Sholinganallur AC">
        <i class="fas fa-user-tie" aria-hidden="true"></i>
        <span>Find BLO</span>
      </a>
    </div>
    <div class="el-cta-wrap">
      <a href="<?php echo $elections_base; ?>/" class="el-cta-primary" title="View full Tamil Nadu Election 2026 guide for OMR">
        <i class="fas fa-vote-yea" aria-hidden="true"></i>
        View full election guide
      </a>
      <a href="<?php echo $elections_base; ?>/index-tamil.php" class="el-cta-secondary" title="தேர்தல் 2026 தமிழில்">தமிழில்</a>
    </div>
  </div>
</section>
<script type="application/ld+json">
<?php
echo json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'WebPage',
  '@id' => $elections_full_url . '#webpage',
  'name' => 'Tamil Nadu Election 2026 – OMR & Chennai South Guide',
  'description' => 'Tamil Nadu Assembly election 2026: poll 23 April, counting 4 May. Know your constituency (Sholinganallur, Velachery, Thiruporur), find BLO, key dates, how to vote.',
  'url' => $elections_full_url,
  'isPartOf' => ['@id' => 'https://myomr.in/#website'],
  'about' => [
    '@type' => 'Event',
    'name' => 'Tamil Nadu Assembly Election 2026 – Poll',
    'startDate' => '2026-04-23',
    'location' => ['@type' => 'Place', 'name' => 'Tamil Nadu'],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
</script>
