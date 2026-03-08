#!/usr/bin/env node
import fs from 'fs';
import path from 'path';
import minimist from 'minimist';
import pc from 'picocolors';
import ignore from 'ignore';
import FTP from 'basic-ftp';
import SftpClient from 'ssh2-sftp-client';

const args = minimist(process.argv.slice(2));
const envKey = String(args.env || 'prod');
const isDryRun = Boolean(args.dry || false);
const onlyArg = args.only ? String(args.only) : '';
const forceProtocol = args.protocol ? String(args.protocol) : '';

const scriptDir = path.dirname(new URL(import.meta.url).pathname);
const projectRoot = path.resolve(scriptDir, '../../');
const configPath = path.resolve(scriptDir, 'deploy.config.json');
const ignoreFilePath = path.resolve(projectRoot, '.deployignore');

function readJSON(filePath) {
  const content = fs.readFileSync(filePath, 'utf8');
  return JSON.parse(content);
}

function loadConfig() {
  if (!fs.existsSync(configPath)) {
    console.error(pc.red(`Missing deploy.config.json at ${configPath}`));
    console.error(pc.yellow('Copy deploy.config.json.example and fill your credentials.'));
    process.exit(1);
  }
  const all = readJSON(configPath);
  const cfg = all[envKey];
  if (!cfg) {
    console.error(pc.red(`Environment key '${envKey}' not found in deploy.config.json`));
    process.exit(1);
  }
  // Allow env overrides
  cfg.host = process.env.DEPLOY_HOST || cfg.host;
  cfg.user = process.env.DEPLOY_USER || cfg.user;
  cfg.password = process.env.DEPLOY_PASS || cfg.password;
  cfg.port = Number(process.env.DEPLOY_PORT || cfg.port || (cfg.protocol === 'sftp' ? 22 : 21));
  cfg.remotePath = process.env.DEPLOY_REMOTE || cfg.remotePath;
  cfg.protocol = forceProtocol || cfg.protocol || 'ftps';
  cfg.basePath = path.resolve(projectRoot);
  return cfg;
}

function loadIgnore() {
  const ig = ignore();
  const defaults = [
    'node_modules/',
    '.git/',
    'dev-tools/deploy/node_modules/',
    'dev-tools/deploy/deploy.config.json',
    'dev-tools/deploy/*.log',
    'weblog/*.log',
  ];
  ig.add(defaults);
  if (fs.existsSync(ignoreFilePath)) {
    const txt = fs.readFileSync(ignoreFilePath, 'utf8');
    ig.add(txt.split(/\r?\n/));
  }
  return ig;
}

function listFiles(startPaths, ig) {
  const result = [];
  const stack = [];
  const startList = startPaths.length ? startPaths : ['.'];
  for (const rel of startList) {
    stack.push(path.resolve(projectRoot, rel));
  }
  while (stack.length) {
    const fp = stack.pop();
    const rel = path.relative(projectRoot, fp).replace(/\\/g, '/');
    if (rel && ig.ignores(rel)) continue;
    const stat = fs.statSync(fp);
    if (stat.isDirectory()) {
      const items = fs.readdirSync(fp);
      for (const it of items) stack.push(path.join(fp, it));
    } else if (stat.isFile()) {
      result.push({ abs: fp, rel });
    }
  }
  return result.sort((a, b) => a.rel.localeCompare(b.rel));
}

function formatBytes(bytes) {
  const sizes = ['B', 'KB', 'MB', 'GB'];
  if (bytes === 0) return '0 B';
  const i = Math.floor(Math.log(bytes) / Math.log(1024));
  return `${(bytes / Math.pow(1024, i)).toFixed(1)} ${sizes[i]}`;
}

async function ensureRemoteDirSftp(sftp, remoteDir) {
  const parts = remoteDir.split('/').filter(Boolean);
  let current = remoteDir.startsWith('/') ? '/' : '';
  for (const p of parts) {
    current += (current.endsWith('/') ? '' : '/') + p;
    try {
      // eslint-disable-next-line no-await-in-loop
      await sftp.mkdir(current, true);
    } catch (_) {
      // ignore if exists
    }
  }
}

async function deploySftp(cfg, files) {
  const sftp = new SftpClient();
  const connectCfg = {
    host: cfg.host,
    port: cfg.port || 22,
    username: cfg.user,
  };
  if (cfg.privateKeyPath) {
    connectCfg.privateKey = fs.readFileSync(path.resolve(cfg.privateKeyPath));
    if (cfg.passphrase) connectCfg.passphrase = cfg.passphrase;
  } else {
    connectCfg.password = cfg.password;
  }
  await sftp.connect(connectCfg);

  for (const f of files) {
    const remote = path.posix.join(cfg.remotePath, f.rel.replace(/\\/g, '/'));
    const remoteDir = path.posix.dirname(remote);
    // eslint-disable-next-line no-await-in-loop
    await ensureRemoteDirSftp(sftp, remoteDir);
    // eslint-disable-next-line no-await-in-loop
    await sftp.fastPut(f.abs, remote);
    console.log(pc.green('✔'), remote);
  }
  await sftp.end();
}

async function deployFtps(cfg, files) {
  const client = new FTP.Client(0);
  client.ftp.verbose = false;
  try {
    await client.access({
      host: cfg.host,
      port: cfg.port || 21,
      user: cfg.user,
      password: cfg.password,
      secure: cfg.secure !== false,
      secureOptions: { rejectUnauthorized: false }
    });

    for (const f of files) {
      const remote = path.posix.join(cfg.remotePath, f.rel.replace(/\\/g, '/'));
      const remoteDir = path.posix.dirname(remote);
      // eslint-disable-next-line no-await-in-loop
      await client.ensureDir(remoteDir);
      // eslint-disable-next-line no-await-in-loop
      await client.uploadFrom(f.abs, remote);
      console.log(pc.green('✔'), remote);
    }
  } finally {
    client.close();
  }
}

async function main() {
  const cfg = loadConfig();
  const ig = loadIgnore();

  const onlyPaths = onlyArg
    ? onlyArg.split(',').map((p) => p.trim()).filter(Boolean)
    : [];

  const files = listFiles(onlyPaths, ig);
  const totalBytes = files.reduce((sum, f) => sum + fs.statSync(f.abs).size, 0);

  console.log(pc.cyan(`Environment: ${envKey}`));
  console.log(pc.cyan(`Protocol: ${forceProtocol || cfg.protocol}`));
  console.log(pc.cyan(`Remote base: ${cfg.remotePath}`));
  console.log(pc.cyan(`Files: ${files.length}, Size: ${formatBytes(totalBytes)}`));

  if (isDryRun) {
    console.log(pc.yellow('\nDry run - planned uploads:'));
    for (const f of files) {
      console.log('•', f.rel);
    }
    return;
  }

  console.log(pc.magenta('\nUploading...'));
  if ((forceProtocol || cfg.protocol) === 'sftp') {
    await deploySftp(cfg, files);
  } else {
    await deployFtps(cfg, files);
  }
  console.log(pc.bold(pc.green('\nDone.')));
}

main().catch((err) => {
  console.error(pc.red(err?.message || String(err)));
  process.exit(1);
});


