<?php
/**
 * Legacy event submission form — deprecated.
 * All event submissions should use the omr-local-events module (event_submissions → event_listings).
 * @see docs/workflows-pipelines/events-workflow.md
 */
header('Location: /omr-local-events/post-event-omr.php?legacy=1', true, 302);
exit;
