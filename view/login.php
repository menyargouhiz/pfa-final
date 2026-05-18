<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log In — Appetitus</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav id="navbar">
  <a class="nav-logo" href="index.php"><span class="logo-icon">🍽️</span> Appetitus</a>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="explore.php">Explore</a></li>
  </ul>
  <div class="nav-actions" id="nav-actions"></div>
</nav>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo-wrap">
      <div class="auth-logo-icon">🍽️</div>
      <div class="auth-logo-name">Appetitus</div>
    </div>
    <h1 class="auth-title">Welcome back!</h1>
    <p class="auth-subtitle">Log in to manage your reviews and unlock badges.</p>

    <!-- Social login (decorative) -->
    <button type="button" class="social-btn">
      <span>🌐</span> Continue with Google
    </button>
    <button type="button" class="social-btn">
      <span>📘</span> Continue with Facebook
    </button>

    <div class="auth-divider"><span>or with email</span></div>

    <form id="loginForm" onsubmit="event.preventDefault(); doLogin();">
      <div class="form-group user-type-selector" style="--i: 1;">
        <label class="form-label">Login as</label>
        <select class="form-input" id="userType" onchange="updateForm()">
          <option value="user">👤 User</option>
          <option value="restaurant">🏪 Restaurant</option>
          <option value="admin">⚙️ Admin</option>
        </select>
      </div>
      <div class="form-group" style="--i: 2;">
        <label class="form-label">Email address</label>
        <input class="form-input" type="email" id="email" placeholder="you@example.com" autocomplete="email" required />
      </div>
      <div class="form-group" style="--i: 3;">
        <label class="form-label">Password</label>
        <input class="form-input" type="password" id="password" placeholder="Your password" autocomplete="current-password" required />
      </div>

      <div style="text-align:right;margin-bottom:1.25rem;">
        <a href="#" style="font-size:.82rem;color:var(--accent);">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-primary btn-full" style="border-radius:var(--radius-sm);">
        Log In →
      </button>
    </form>
    
    <script>
      function updateForm() {
        const userType = document.getElementById('userType').value;
        const emailInput = document.getElementById('email');
        const selector = document.querySelector('.user-type-selector');
        
        selector.setAttribute('data-type', userType);
        
        if (userType === 'restaurant') {
          emailInput.placeholder = 'restaurant@example.com';
        } else if (userType === 'admin') {
          emailInput.placeholder = 'admin@appetitus.com';
        } else {
          emailInput.placeholder = 'you@example.com';
        }
      }

      async function doLogin() {
        const userType = document.getElementById('userType').value;
        const email = document.getElementById('email').value.trim();
        const pass = document.getElementById('password').value;
        if (!email || !pass) return;

        const button = document.querySelector('.btn-primary');
        button.classList.add('btn-loading');
        button.disabled = true;

        if (userType === 'user') {
          console.log('Attempting user login for:', email);
          try {
            const res = await fetch('../controller/api_login.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              credentials: 'include',
              body: JSON.stringify({ email, password: pass })
            });
            console.log('Login response status:', res.status);
            const data = await res.json();
            console.log('Login response data:', data);
            
            if (!data.success) {
              if (typeof toast === 'function') toast('❌ ' + (data.error || 'Login failed'), 'error');
              button.classList.remove('btn-loading');
              button.disabled = false;
              return;
            }
            
            // Save user to localStorage
            localStorage.removeItem('appetitus_reviews');
            const user = {
              id: data.data.id,
              name: data.data.name,
              email: data.data.email,
              joinDate: new Date().toISOString(),
              reviewCount: 0,
              favCuisines: []
            };
            localStorage.setItem('appetitus_user', JSON.stringify(user));
            console.log('Login successful, user saved to localStorage:', user);
            
            if (typeof toast === 'function') toast('🎉 Welcome back ' + data.data.name + '!');
            setTimeout(() => window.location.href = 'profile.php', 1400);
          } catch (err) {
            console.error('Login error:', err);
            if (typeof toast === 'function') toast('⚠️ Login failed', 'error');
          }
        } else if (userType === 'restaurant') {
          // Restaurant login from localStorage
          const stored = JSON.parse(localStorage.getItem('appetitus_restaurant') || 'null');
          if (stored && stored.email === email) {
            if (stored.password === pass) {
              if (typeof toast === 'function') toast(`✅ Welcome, ${stored.name}!`);
              setTimeout(() => window.location.href = 'restaurant-dashboard.php', 1000);
            } else {
              if (typeof toast === 'function') toast('❌ Invalid password.', 'error');
              button.classList.remove('btn-loading');
              button.disabled = false;
            }
          } else {
            if (typeof toast === 'function') toast('❌ Restaurant not found. Please check your email or register.', 'error');
            button.classList.remove('btn-loading');
            button.disabled = false;
          }
        } else if (userType === 'admin') {
          // Simple admin check
          if (email === 'admin@appetitus.com' && pass === 'admin123') {
            if (typeof toast === 'function') toast('✅ Welcome Admin!');
            setTimeout(() => window.location.href = 'admin.php', 1000);
          } else {
            if (typeof toast === 'function') toast('❌ Invalid admin credentials.', 'error');
            button.classList.remove('btn-loading');
            button.disabled = false;
          }
        }
      }
    </script>

    <div class="auth-switch">
      Don't have an account yet? <a href="signup.php">Sign Up</a>
    </div>
  </div>
</div>

<script src="../script/app.js"></script>
</body>
</html>
</html>
