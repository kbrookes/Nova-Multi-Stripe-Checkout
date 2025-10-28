# Contributing to Nova Multi-Stripe Checkout

## Local Setup

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd nova-multi-stripe-checkout
   ```

2. **Install dependencies:**
   ```bash
   cd plugin
   composer install
   ```

3. **Set up WordPress development environment:**
   - Ensure PHP 8.1+ and Composer are installed
   - Set up a local WordPress installation
   - Copy or symlink the `plugin/` directory to `/wp-content/plugins/nova-multi-stripe-checkout`

4. **Activate the plugin:**
   - Go to WordPress Admin → Plugins
   - Activate "Nova Multi-Stripe Checkout"

5. **Configure settings:**
   - Go to Settings → Nova Checkout
   - Add your Stripe secret keys (AU and NZ)
   - Set success and cancel URLs for each region

6. **Configure Stripe price IDs:**
   - Edit `plugin/includes/class-nova-prices.php`
   - Replace placeholder price IDs with actual Stripe price IDs

## Stripe Setup

### Environment Variables (Recommended)
Set these in your environment or `wp-config.php`:
```php
define('STRIPE_AU_SK', 'sk_test_...');
define('STRIPE_NZ_SK', 'sk_test_...');
define('STRIPE_WEBHOOK_SECRET', 'whsec_...');
```

### Stripe Price Configuration
Create Stripe **Prices** in both AU (AUD) and NZ (NZD) accounts for:
- **Plans:** Plus/Pro/Ultimate × {quarterly, annual}
- **Support:** Basic/Plus/Ultimate × {quarterly, annual}

The plugin maps `(country → billing → plan|support)` → price IDs.

## Testing

### API Endpoint Test
Test the checkout endpoint with cURL:
```bash
curl -X POST https://your-site.com/wp-json/nova/v1/checkout \
  -H "Content-Type: application/json" \
  -d '{
    "country": "AU",
    "plan": "plus",
    "support": "basic",
    "billing": "annual",
    "users": 5
  }'
```

Expected response:
```json
{
  "url": "https://checkout.stripe.com/...",
  "session_id": "cs_..."
}
```

### Webhook Testing
Use Stripe CLI to test webhooks locally:
```bash
stripe listen --forward-to localhost:8080/wp-json/nova/v1/stripe-webhook
```

## Development Workflow

### Code Quality Checks
Run these commands before committing:
```bash
cd plugin
composer lint      # PHP syntax check
composer phpcs     # WordPress Coding Standards
composer phpstan   # Static analysis
composer dupe      # Duplicate code check
composer audit     # Security audit
```

### Git Workflow
1. Create a feature branch from `main`
2. Make your changes
3. Run quality checks
4. Commit with descriptive messages
5. Push and create a pull request

## Release Process

### Version 0.1.1 Example
1. **Update plugin version:**
   - Edit `plugin/nova-multi-stripe-checkout.php`
   - Change `Version: 0.1.0` to `Version: 0.1.1`

2. **Update project status:**
   - Edit `PROJECT_STATUS.md`
   - Document what changed in the new version

3. **Commit and push:**
   ```bash
   git add .
   git commit -m "Release v0.1.1"
   git push origin main
   ```

4. **Create and push tag:**
   ```bash
   git tag -a v0.1.1 -m "v0.1.1"
   git push origin v0.1.1
   ```

5. **Git Updater will detect the new tag and offer updates to client sites**

### Versioning
- Use semantic versioning: `MAJOR.MINOR.PATCH`
- Keep tags aligned with the plugin header Version
- Document breaking changes in major versions

## Acceptance Criteria

Before any code is merged, ensure:
- [ ] All quality checks pass (`composer lint`, `phpcs`, `phpstan`, `dupe`, `audit`)
- [ ] Code follows WordPress Coding Standards
- [ ] All functions have proper docblocks
- [ ] Input validation and sanitization is implemented
- [ ] Error handling is comprehensive
- [ ] No secrets are exposed in logs or responses
- [ ] Tests pass (when available)

## Support

For questions or issues:
- Open a GitHub issue
- Contact: <SUPPORT_EMAIL>
- Check existing documentation first
