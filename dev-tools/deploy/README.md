Deploy from Cursor to cPanel (FTPS/SFTP)

Overview
- Semi-automated deployment from your Windows dev to HostGator/BigRock cPanel.
- Single command from Cursor terminal: dry-run, partial directory deploys, excludes.

Quick Start
1) Install deps (once):
   - cd dev-tools/deploy
   - npm install
2) Copy config and fill credentials:
   - copy deploy.config.json.example deploy.config.json
   - Edit host, user, password (or key), remotePath
3) Dry run (see what will upload):
   - node deploy.js --env=prod --dry --only=omr-local-job-listings
4) Deploy:
   - node deploy.js --env=prod --only=omr-local-job-listings

Arguments
- --env: config environment key (default: prod)
- --dry: do not upload, just print plan
- --only: comma-separated list of paths to deploy (relative to repo root)
- --protocol: force "sftp" or "ftps" (overrides config)

Notes
- Credentials are read from deploy.config.json or environment variables.
- The script honors .deployignore patterns.
- For shared hosting, FTPS usually works; if SSH is enabled, SFTP is preferred.

Security
- Never commit deploy.config.json (contains secrets). A .gitignore entry is included.


