<?php
if (!defined('SCROLL_STORY_DEPENDENCIES')) {
  define('SCROLL_STORY_DEPENDENCIES', true);
?>
  <!-- ✅ External CSS Dependencies (Loaded only once) -->
  <link rel="stylesheet" href="https://unpkg.com/open-props@1.6.14/normalize.dark.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/open-props@1.6.14" />
  <link rel="stylesheet" href="https://www.unpkg.com/layout-craft@0.1.1/dist/utilities.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@200..900&family=Pacifico&display=swap" />
  <style>
    <?php include __DIR__ . '/scroll-story.css'; ?>
  </style>
<?php
}
?>

<!-- ✅ Scroll Story Component Markup -->
<section class="Hero inline gap-2">
  <div class="Wrapper block content-3">
    <div class="Visual block-center-center">
      <?php
      $images = [
        ["class" => "FirstPic", "alt" => "Stories Unveiled", "desktop" => "img-desktop-3", "mobile" => "img-mobile-3"],
        ["class" => "SecondPic", "alt" => "Celebrating Life Together", "desktop" => "img-desktop-2", "mobile" => "img-mobile-2"],
        ["class" => "ThirdPic", "alt" => "The Art of Giving", "desktop" => "img-desktop-1", "mobile" => "img-mobile-1"]
      ];
      foreach ($images as $img) {
        echo "<picture class='{$img['class']}'>
                <source srcset='https://raw.githubusercontent.com/mobalti/open-props-interfaces/main/stories-with-scroll-driven/images/{$img['desktop']}.avif' media='(width >= 1024px)' type='image/avif' />
                <source srcset='https://raw.githubusercontent.com/mobalti/open-props-interfaces/main/stories-with-scroll-driven/images/{$img['mobile']}.avif' type='image/avif' />
                <source srcset='https://raw.githubusercontent.com/mobalti/open-props-interfaces/main/stories-with-scroll-driven/images/{$img['desktop']}.webp' media='(width >= 1024px)' type='image/webp' />
                <img src='https://raw.githubusercontent.com/mobalti/open-props-interfaces/main/stories-with-scroll-driven/images/{$img['mobile']}.webp' alt='{$img['alt']}' />
              </picture>";
      }
      ?>
    </div>
  </div>

  <div class="Content block">
    <div id="StoriesUnveiled" class="FirstLockup block-center-start">
      <div class="block gap-3">
        <h3>Stories Unveiled</h3>
        <div class="subhead">Capture the essence of family celebration.</div>
        <p>Share the moments that weave your family tale.</p>
      </div>
    </div>
    <div id="CelebratingLifeTogether" class="SecondLockup block-center-start">
      <div class="block gap-3">
        <h3>Celebrating Life Together</h3>
        <div class="subhead">Embrace the significance of shared joy.</div>
        <p>In every celebration, find the heartwarming stories.</p>
      </div>
    </div>
    <div id="TheArtofGiving" class="ThirdLockup block-center-start">
      <div class="block gap-3">
        <h3>The Art of Giving</h3>
        <div class="subhead">Explore the stories within each present.</div>
        <p>Every gift is a chapter in your family's narrative.</p>
      </div>
    </div>
  </div>

  <div class="SmallScreenContent block-center-center">
    <p class="FirstStory">The Art of Giving</p>
    <p class="SecondStory">Celebrating Life Together</p>
    <p class="ThirdStory">Stories Unveiled</p>
  </div>
</section>

<div class="pagination block-center-center content-full">
  <div class="inline gap-3">
    <a href="#StoriesUnveiled"></a>
    <a href="#CelebratingLifeTogether"></a>
    <a href="#TheArtofGiving"></a>
  </div>
</div>
