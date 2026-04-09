# Deployment Setup

This repository uses GitHub Actions to automatically deploy to production when code is merged to the `main` branch.

## What happens on deployment

1. **Code deployment**: Latest code is pulled from `main` branch via SSH
2. **Dependency update**: Composer dependencies are updated
3. **Cache clearing**: All caches are purged (Kirby, nginx, Cloudflare)
4. **Cache warming**: Site is crawled via sitemap.xml to warm caches

## Required GitHub Secrets

To enable automated deployment, configure these secrets in your GitHub repository settings:

### Server SSH Access

- `DEPLOY_HOST`: Your production server hostname/IP (e.g., `example.com` or `192.168.1.100`)
- `DEPLOY_USER`: SSH username (e.g., `deploy` or `www-data`)
- `DEPLOY_SSH_KEY`: Private SSH key for authentication (see setup below)
- `DEPLOY_PORT`: SSH port (optional, defaults to 22)
- `DEPLOY_PATH`: Full path to web root on server (e.g., `/var/www/brianjgoodwin.net`)

### Domain

- `DEPLOY_DOMAIN`: Your production domain without protocol (e.g., `brianjgoodwin.net`)

### Cloudflare API

- `CLOUDFLARE_ZONE_ID`: Your Cloudflare zone ID
- `CLOUDFLARE_API_TOKEN`: Cloudflare API token with cache purge permissions

## Setup Instructions

### 1. Create SSH Deploy Key

Generate a new SSH key pair specifically for deployment:

```bash
# On your local machine
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/deploy_key

# This creates two files:
# ~/.ssh/deploy_key (private key - add to GitHub secrets)
# ~/.ssh/deploy_key.pub (public key - add to server)
```

### 2. Configure Server

Add the public key to your server's authorized_keys:

```bash
# On your production server, as the deploy user
mkdir -p ~/.ssh
chmod 700 ~/.ssh

# Add the public key
cat >> ~/.ssh/authorized_keys << 'EOF'
[paste contents of deploy_key.pub here]
EOF

chmod 600 ~/.ssh/authorized_keys
```

Ensure the deploy user has sudo access for nginx cache clearing:

```bash
# Add to /etc/sudoers.d/deploy-user (replace 'deploy' with your username)
deploy ALL=(ALL) NOPASSWD: /bin/rm -rf /var/cache/nginx/*
deploy ALL=(ALL) NOPASSWD: /usr/sbin/nginx -s reload
```

Verify the deploy path has correct permissions:

```bash
# The deploy user should own the web root
sudo chown -R deploy:www-data /var/www/brianjgoodwin.net
```

### 3. Get Cloudflare Credentials

**Zone ID:**
1. Log into Cloudflare Dashboard
2. Select your domain
3. Scroll down to "API" section on the right sidebar
4. Copy the "Zone ID"

**API Token:**
1. Go to https://dash.cloudflare.com/profile/api-tokens
2. Click "Create Token"
3. Use "Edit zone DNS" template or create custom token with:
   - Permissions: Zone → Cache Purge → Purge
   - Zone Resources: Include → Specific zone → [your domain]
4. Create token and copy it (you won't see it again!)

### 4. Add Secrets to GitHub

1. Go to your repository on GitHub
2. Navigate to Settings → Secrets and variables → Actions
3. Click "New repository secret" and add each of the following:

| Secret Name | Example Value |
|-------------|---------------|
| `DEPLOY_HOST` | `example.com` |
| `DEPLOY_USER` | `deploy` |
| `DEPLOY_SSH_KEY` | `-----BEGIN OPENSSH PRIVATE KEY-----`<br>`[full private key contents]`<br>`-----END OPENSSH PRIVATE KEY-----` |
| `DEPLOY_PORT` | `22` (or leave empty for default) |
| `DEPLOY_PATH` | `/var/www/brianjgoodwin.net` |
| `DEPLOY_DOMAIN` | `brianjgoodwin.net` |
| `CLOUDFLARE_ZONE_ID` | `abc123def456...` |
| `CLOUDFLARE_API_TOKEN` | `your-cloudflare-api-token` |

### 5. Server Git Configuration

Ensure your production server has the repository set up:

```bash
# On your production server
cd /var/www/brianjgoodwin.net

# Initialize if needed
git init
git remote add origin https://github.com/brianjgoodwin/PersonalSite.git

# Or if already set up, verify remote
git remote -v
```

## Testing the Deployment

To test the deployment without merging to main:

1. Temporarily modify `.github/workflows/deploy.yml` to trigger on a test branch:
   ```yaml
   on:
     push:
       branches: [main, test-deploy]  # Add test-deploy
   ```

2. Create and push to test branch:
   ```bash
   git checkout -b test-deploy
   git push origin test-deploy
   ```

3. Watch the Actions tab on GitHub to see the deployment run

4. After testing, remove the test branch trigger

## Monitoring Deployments

- **GitHub Actions**: View deployment logs at https://github.com/brianjgoodwin/PersonalSite/actions
- **Deployment Summary**: Each deployment creates a summary showing:
  - Number of URLs warmed
  - Any failures
  - Cache clearing status

## Troubleshooting

**SSH Connection Failed:**
- Verify `DEPLOY_HOST` is accessible from GitHub (no firewall blocking)
- Check SSH key has correct permissions on server
- Test SSH manually: `ssh -i ~/.ssh/deploy_key user@host`

**nginx cache not clearing:**
- Verify sudo permissions are configured
- Check nginx cache path matches your configuration
- May need to adjust path in workflow file

**Cloudflare purge failed:**
- Verify API token has correct permissions
- Check zone ID is correct
- Token may have expired (they can be set to expire)

**Cache warming incomplete:**
- Check sitemap.xml is accessible
- Verify URLs in sitemap are valid
- May need to adjust sleep delay if server is rate-limiting

## Manual Deployment

To deploy manually without triggering the workflow:

```bash
# SSH to server
ssh deploy@example.com

# Navigate to web root
cd /var/www/brianjgoodwin.net

# Pull latest
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader

# Clear caches manually
rm -rf site/cache/*
sudo rm -rf /var/cache/nginx/*
sudo nginx -s reload

# Purge Cloudflare (use API or dashboard)
```

## Workflow Improvements

Consider these future enhancements:

- **Slack/Discord notifications**: Add notification step for deployment status
- **Rollback capability**: Keep previous release for quick rollback
- **Health checks**: Verify critical pages load after deployment
- **Selective cache purge**: Purge only changed pages instead of everything
- **Database migrations**: Add migration step if needed
- **Asset compilation**: Run build step for compiled assets
