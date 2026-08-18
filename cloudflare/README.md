# Cloudflare Email Worker Setup

This directory contains the Cloudflare Worker script that receives incoming emails via Cloudflare Email Routing and forwards them to this Laravel application backend.

## Deployment & Setup

You can set up and deploy the Cloudflare Worker directly from the command line or from the Admin settings dashboard.

### 1. Configure Credentials

Before deploying, ensure you configure the required credentials. They can be set in the Admin Panel (**Settings**) or directly in your `.env` file:

- **Cloudflare API Token** (`cloudflare_api_token` / `CLOUDFLARE_API_TOKEN`)
  Create an API token with `Account.Workers Scripts: Edit` permissions.
- **Cloudflare Account ID** (`cloudflare_account_id` / `CLOUDFLARE_ACCOUNT_ID`)
  Found on your Cloudflare dashboard overview page.

### 2. Deployment via Command Line

Run the Artisan command to generate local configuration and deploy:

```bash
# Generate local wrangler .dev.vars configuration
php artisan cloudflare:setup

# Generate configuration AND deploy to Cloudflare Worker
php artisan cloudflare:setup --deploy
```

### 3. Deployment via Admin Panel

1. Log in to the Admin Dashboard.
2. Go to **Settings**.
3. Fill in **Cloudflare API Token** and **Cloudflare Account ID**.
4. Click **Save Settings**.
5. Click **Deploy Cloudflare Worker** button.
