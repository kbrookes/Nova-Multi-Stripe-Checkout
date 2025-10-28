# Git Updater Deployment Checklist

## Pre-Deployment Checklist ✅

### 1. Version Management
- [x] **Plugin Header Version**: Updated to `0.1.1` in `nova-multi-stripe-checkout.php`
- [x] **Plugin Constant**: Updated `NOVA_MSC_VER` to `0.1.1`
- [x] **Readme.txt Stable Tag**: Updated to `0.1.1`
- [x] **Changelog**: Added v0.1.1 entry with changes

### 2. Git Updater Headers ✅
- [x] **GitHub Plugin URI**: Set to `kbrookes/Nova-Multi-Stripe-Checkout`
- [x] **Primary Branch**: Set to `main`
- [x] **Plugin URI**: Set to GitHub repository URL
- [x] **Author URI**: Set to GitHub profile

### 3. Code Quality ✅
- [x] **PHP Syntax**: `composer lint` passes
- [x] **Security Audit**: `composer audit` passes
- [x] **Code Standards**: Ready for deployment (cosmetic issues only)
- [x] **Static Analysis**: PHPStan level 7 compliant
- [x] **Duplicate Code**: PHPMD shows no duplicates

### 4. Documentation ✅
- [x] **PROJECT_STATUS.md**: Updated with v0.1.1 completion
- [x] **readme.txt**: Complete with installation, configuration, and FAQ
- [x] **CONTEXT.md**: Project scope and requirements
- [x] **CONTRIBUTING.md**: Development guidelines
- [x] **SECURITY.md**: Security policy and best practices

## Deployment Commands

### 1. Commit All Changes
```bash
git add .
git commit -m "Release v0.1.1: Git Updater compatibility and code quality improvements"
```

### 2. Push to GitHub
```bash
git push origin main
```

### 3. Create Git Tag
```bash
git tag -a v0.1.1 -m "Release v0.1.1: Git Updater compatibility and code quality improvements"
git push origin v0.1.1
```

## Git Updater Installation

### On WordPress Test Sites:

1. **Install Git Updater Plugin** (if not already installed):
   - Download from: https://github.com/afragen/git-updater
   - Or install via WordPress admin

2. **Add Repository**:
   - Go to Settings → Git Updater
   - Add Repository: `kbrookes/Nova-Multi-Stripe-Checkout`
   - Branch: `main`
   - Type: Plugin

3. **Install/Update**:
   - Go to Plugins → Installed Plugins
   - Find "Nova Multi-Stripe Checkout"
   - Click "Install" or "Update" if already installed

## Testing Checklist

### 1. Plugin Activation
- [ ] Plugin activates without errors
- [ ] Admin menu appears under Settings → Nova Checkout
- [ ] No PHP errors in WordPress debug log

### 2. Configuration
- [ ] Settings page loads correctly
- [ ] Can save configuration options
- [ ] Environment variables override settings

### 3. API Endpoints
- [ ] REST endpoint accessible: `/wp-json/nova/v1/checkout`
- [ ] Webhook endpoint accessible: `/wp-json/nova/v1/stripe-webhook`
- [ ] Proper error handling for invalid requests

### 4. Stripe Integration
- [ ] Can create test checkout sessions
- [ ] Webhook signature validation works
- [ ] AU/NZ routing functions correctly

## Rollback Plan

If issues occur:
1. **Immediate**: Deactivate plugin in WordPress admin
2. **GitHub**: Revert to previous tag: `git revert v0.1.1`
3. **WordPress**: Install previous version via Git Updater

## Next Release (v0.2.0)

### Planned Features:
- [ ] Admin UI polish (readonly env overrides display)
- [ ] Add Customer Portal endpoint
- [ ] Robust logging + error surfaces
- [ ] Unit tests
- [ ] Integration tests with Stripe test mode
- [ ] Documentation improvements
- [ ] Performance optimizations

## Support

- **GitHub Issues**: https://github.com/kbrookes/Nova-Multi-Stripe-Checkout/issues
- **Documentation**: See CONTEXT.md, CONTRIBUTING.md, SECURITY.md
- **Configuration**: See readme.txt for setup instructions
