# Security Policy

## Reporting Vulnerabilities

**Please report security vulnerabilities privately to:** security@<your-domain>

We take security seriously and will respond to all reports within 48 hours.

## Security Measures

### API Key Protection
- ✅ Stripe secret keys are never stored in the repository
- ✅ Environment variables are used for production keys
- ✅ Admin interface shows password fields for keys
- ✅ Keys are sanitized and validated before use

### Input Validation
- ✅ All REST API inputs are sanitized and validated
- ✅ Country codes are whitelisted (AU/NZ only)
- ✅ Plan and support levels are validated against allowed values
- ✅ User counts are validated as positive integers
- ✅ URLs are validated and sanitized

### Webhook Security
- ✅ Webhook signatures are validated using Stripe's signature verification
- ✅ Raw payload is used for signature verification
- ✅ Webhook secret is stored in environment variables
- ✅ Invalid signatures result in 400 Bad Request responses

### Error Handling
- ✅ No sensitive information is exposed in error messages
- ✅ Stripe API errors are sanitized before returning to client
- ✅ Secrets are never logged or returned in responses
- ✅ Generic error messages for production environments

### Data Protection
- ✅ No customer data is stored locally
- ✅ All payment processing is handled by Stripe
- ✅ Webhook events are processed securely
- ✅ No sensitive data is cached or logged

## Key Rotation Policy

If a security compromise is suspected:
1. **Immediate:** Rotate affected Stripe API keys
2. **Within 24 hours:** Update all production environments
3. **Within 48 hours:** Notify affected customers if necessary
4. **Within 1 week:** Conduct security audit and implement additional measures

## Security Best Practices

### For Developers
- Never commit API keys or secrets to version control
- Use environment variables for all sensitive configuration
- Validate all inputs from external sources
- Keep dependencies updated and audit regularly
- Follow WordPress security guidelines

### For Administrators
- Use strong, unique API keys
- Regularly rotate API keys (quarterly recommended)
- Monitor Stripe dashboard for unusual activity
- Keep WordPress and plugins updated
- Use HTTPS for all communications

## Compliance

This plugin follows:
- WordPress security best practices
- Stripe security guidelines
- OWASP security recommendations
- GDPR data protection principles (no personal data stored)

## Contact

For security-related questions or to report vulnerabilities:
- **Email:** security@<your-domain>
- **Response time:** Within 48 hours
- **Disclosure:** We follow responsible disclosure practices
