
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123b2a">
    <title>Sign In | Jay Durga Krishi Sahakari Sanstha</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>
<body>
<main class="login-page">
    <section class="brand-panel">
        <div class="brand-content">
            <a href="{{ url('/') }}" class="brand">
                    <span class="brand-mark">
                        <svg viewBox="0 0 48 48" fill="none" aria-hidden="true">
                            <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M24 35V20M24 27C15 27 12 21 12 16C21 16 24 21 24 27ZM24 23C24 15 29 11 36 11C36 19 32 23 24 23Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 36H33" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </span>
                <span class="brand-name">
                        <strong>JAY DURGA</strong>
                        <small>KRISHI SAHAKARI SANSTHA</small>
                    </span>
            </a>

            <div class="hero-copy">
                <span class="eyebrow"><span></span> YOUR TRUSTED COOPERATIVE PARTNER</span>
                <h1>Growing together.<br>Building a <em>better future.</em></h1>
                <p>Empowering farmers, strengthening communities, and helping every member move forward with confidence.</p>
                <div class="hero-divider"></div>
                <div class="trust-points">
                    <div class="trust-item">
                            <span class="trust-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M12 3L20 6V11C20 16 16.5 19.5 12 21C7.5 19.5 4 16 4 11V6L12 3Z" stroke="currentColor" stroke-width="1.6"/><path d="M8.5 12L11 14.5L15.5 9.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                        <span><strong>Trusted & Secure</strong><small>Your membership matters</small></span>
                    </div>
                    <div class="trust-item">
                            <span class="trust-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M3 20H21M5 20V10L12 5L19 10V20M9 20V14H15V20M8 10H8.01M16 10H16.01" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                        <span><strong>Community First</strong><small>Stronger, together</small></span>
                    </div>
                </div>
            </div>

            <div class="brand-footer">
                <span>ROOTED IN TRUST. GROWING WITH YOU.</span>
                <span class="footer-line"></span>
            </div>
        </div>
    </section>

    <section class="form-panel">
        <div class="mobile-brand">
            <span class="mobile-brand-icon">JD</span>
            <span><strong>JAY DURGA</strong><small>KRISHI SAHAKARI SANSTHA</small></span>
        </div>

        <div class="login-card">
            <div class="form-heading">
                <span class="welcome-label">MEMBER PORTAL</span>
                <h2>Welcome back<span>.</span></h2>
                <p>Sign in to access your cooperative account.</p>
            </div>

            @if ($errors->any())
                <div class="error-alert" role="alert">
                    <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 8V12M12 16H12.01" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    <div>
                        <strong>Unable to sign in</strong>
                        <p>{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="success-alert" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="/login" class="login-form">
                @csrf

                <div class="field-group">
                    <label for="email">Email address</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M4 7L12 13L20 7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            autocomplete="username"
                            required
                            autofocus
                            class="@error('email') input-error @enderror"
                        >
                    </div>
                    @error('email')
                    <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-group">
                    <div class="label-row">
                        <label for="password">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        @endif
                    </div>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2.5" stroke="currentColor" stroke-width="1.6"/><path d="M8 10V7A4 4 0 0 1 16 7V10M12 14V17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                            class="@error('password') input-error @enderror"
                        >
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2.5 12S6 5.5 12 5.5S21.5 12 21.5 12S18 18.5 12 18.5S2.5 12 2.5 12Z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
                        </button>
                    </div>
                    @error('password')
                    <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <label class="remember-option">
                    <input type="checkbox" name="remember" id="remember">
                    <span class="custom-checkbox"></span>
                    <span>Keep me signed in</span>
                </label>

                <button type="submit" class="submit-button">
                    <span>Sign in to your account</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12H19M13 6L19 12L13 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </form>

            <div class="secure-note">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 10V7A4 4 0 0 1 16 7V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <span>Your information is protected by secure sign-in.</span>
            </div>
        </div>

        <footer class="page-footer">
            <span>© {{ date('Y') }} Jay Durga Krishi Sahakari Sanstha</span>
            <span class="footer-separator">·</span>
            <span>All rights reserved</span>
        </footer>
    </section>
</main>
</body>
</html>

