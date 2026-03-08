# Errors & Logging — MyOMR

## Overview
- Centralized error bootstrap: `core/error-handler.php` (auto-prepended via `.htaccess`)
- Environment detection: `core/env.php` (`MYOMR_ENV` from server env)
- Production: display_errors=0; Development: display_errors=1
- Logs written to `logs/app-YYYY-MM-DD.log`

## Rotate/Manage Logs
- Logs rotate daily by filename
- Keep last 14 days; delete older files via cron (optional):
```
find /home/USER/public_html/logs -type f -name 'app-*.log' -mtime +14 -delete
```

## Troubleshooting
- If hosting does not allow `php_value auto_prepend_file`, include `require __DIR__.'/core/error-handler.php';` at the top of entry scripts
- Confirm env with `echo getenv('MYOMR_ENV');`
