<?php
// Development diagnostics panel for Events pages (HTML pages only)
if (!defined('DEVELOPMENT_MODE')) { define('DEVELOPMENT_MODE', true); }
if (!DEVELOPMENT_MODE) { return; }

// Show DB connection status if unavailable
try {
    global $conn;
    if (!isset($conn) || !$conn) {
        echo "<div style='background:#fff3cd;border:1px solid #ffeeba;padding:10px;margin:10px;border-radius:6px;'>";
        echo "<strong>Dev Diagnostics:</strong> DB connection not initialized.";
        echo "</div>";
    } elseif ($conn->connect_error) {
        echo "<div style='background:#f8d7da;border:1px solid #f5c6cb;padding:10px;margin:10px;border-radius:6px;'>";
        echo "<strong>Dev Diagnostics:</strong> DB connection error – " . htmlspecialchars($conn->connect_error) . "";
        echo "</div>";
    }
} catch (Throwable $e) {
    echo "<div style='background:#f8d7da;border:1px solid #f5c6cb;padding:10px;margin:10px;border-radius:6px;'>";
    echo "<strong>Dev Diagnostics:</strong> Exception while checking DB – " . htmlspecialchars($e->getMessage()) . "";
    echo "</div>";
}


