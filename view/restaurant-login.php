<?php // Converted to PHP ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurant Login — Appetitus</title>
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
      <div class="auth-logo-icon">🏪</div>
      <div class="auth-logo-name">Restaurant Portal</div>
    </div>
    <h1 class="auth-title">Restaurant Login</h1>
    <p class="auth-subtitle">Access your restaurant analytics and manage your business.</p>

    <div class="auth-divider"><span>login with email</span></div>

    <div class="form-group">
      <label class="form-label">Restaurant Email</label>
      <input class="form-input" type="email" id="email" placeholder="restaurant@example.com" autocomplete="email" />
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <input class="form-input" type="password" id="password" placeholder="Your password" autocomplete="current-password" />
    </div>

    <div style="text-align:right;margin-bottom:1.25rem;">
      <a href="#" style="font-size:.82rem;color:var(--accent);">Forgot password?</a>
    </div>

    <button class="btn btn-primary btn-full" style="border-radius:var(--radius-sm);" onclick="doRestaurantLogin()">
      Log In to Dashboard →
    </button>

    <div class="auth-switch">
      New restaurant? <a href="restaurant-signup.php">Register Here</a>
    </div>
    <div class="auth-switch">
      <a href="login.php">← Back to User Login</a>
    </div>
  </div>
</div>

<script src="app.js"></script>
<script>
function doRestaurantLogin() {
  const email = document.getElementById('email').value.trim();
  const pass = document.getElementById('password').value.trim();

  if (!email || !pass) {
    toast('⚠️ Please fill in all fields.', 'error');
    return;
  }

  // Check stored restaurant
  const stored = JSON.parse(localStorage.getItem('appetitus_restaurant') || 'null');
  if (stored && stored.email === email) {
    // In a real app, you'd verify the password hash
    if (stored.password === pass) {
      toast(`✅ Welcome, ${stored.name}!`);
      setTimeout(() => window.location.href = 'restaurant-dashboard.php', 1000);
    } else {
      toast('❌ Invalid password.', 'error');
    }
  } else {
    toast('❌ Restaurant not found. Please check your email or register.', 'error');
  }
}

document.getElementById('password')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') doRestaurantLogin();
});
</script>
</body>
</html>



