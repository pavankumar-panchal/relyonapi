# Google OAuth 2.0 Setup

Set environment variables (e.g., in Apache vhost or systemd):

- GOOGLE_CLIENT_ID
- GOOGLE_CLIENT_SECRET
- GOOGLE_REDIRECT_URI (e.g., https://yourdomain.com/Single_email/backend/public/index.php/api/auth/google/callback)
- GOOGLE_SCOPES (optional, default: "openid email profile")

Flow:
1. GET /api/auth/google/start -> redirects to Google Consent
2. Google redirects to /api/auth/google/callback?code=...
3. Server exchanges code, verifies id_token via JWKS, upserts user, and issues oauth_code
4. Client uses oauth_code as Bearer to call protected endpoints (e.g., /api/single-email)

Security Notes:
- Use HTTPS only
- Validate `state` cookie for CSRF (enabled by default)
- Restrict allowed `redirect_to` domains if using redirect mode
