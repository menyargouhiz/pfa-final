<?php // Converted to PHP ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurant Registration — Appetitus</title>
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
    <h1 class="auth-title">Register Your Restaurant</h1>
    <p class="auth-subtitle">Join Appetitus and get insights into your customer reviews.</p>

    <div class="auth-divider"><span>restaurant details</span></div>

    <div class="form-group">
      <label class="form-label">Restaurant Name</label>
      <input class="form-input" type="text" id="restaurant-name" placeholder="Your Restaurant Name" />
    </div>
    <div class="form-group">
      <label class="form-label">Owner/Manager Name</label>
      <input class="form-input" type="text" id="owner-name" placeholder="Your Full Name" />
    </div>
    <div class="form-group">
      <label class="form-label">Email Address</label>
      <input class="form-input" type="email" id="email" placeholder="restaurant@example.com" autocomplete="email" />
    </div>
    <div class="form-group">
      <label class="form-label">Phone Number</label>
      <input class="form-input" type="tel" id="phone" placeholder="+216 XX XXX XXX" />
    </div>
    <div class="form-group">
      <label class="form-label">City</label>
      <input class="form-input" type="text" id="city" placeholder="Tunis, La Marsa, etc." />
    </div>
    <div class="form-group">
      <label class="form-label">Cuisine Type</label>
      <select class="form-input" id="cuisine">
        <option value="">Select cuisine type</option>
        <option value="gastronomique">Fine Dining</option>
        <option value="asiatique">Asian</option>
        <option value="italien">Italian</option>
        <option value="fruits-de-mer">Seafood</option>
        <option value="street-food">Street Food</option>
        <option value="brasserie">Pub</option>
        <option value="africaine">African</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <input class="form-input" type="password" id="password" placeholder="Create a strong password" autocomplete="new-password" />
    </div>
    <div class="form-group">
      <label class="form-label">Confirm Password</label>
      <input class="form-input" type="password" id="confirm-password" placeholder="Confirm your password" autocomplete="new-password" />
    </div>

    <button class="btn btn-primary btn-full" style="border-radius:var(--radius-sm);" onclick="doRestaurantSignup()">
      Register Restaurant →
    </button>

    <div class="auth-switch">
      Already registered? <a href="restaurant-login.php">Log In</a>
    </div>
    <div class="auth-switch">
      <a href="login.php">← Back to User Login</a>
    </div>
  </div>
</div>

<script src="app.js"></script>
<script>
function doRestaurantSignup() {
  const restaurantName = document.getElementById('restaurant-name').value.trim();
  const ownerName = document.getElementById('owner-name').value.trim();
  const email = document.getElementById('email').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const city = document.getElementById('city').value.trim();
  const cuisine = document.getElementById('cuisine').value;
  const pass = document.getElementById('password').value.trim();
  const confirmPass = document.getElementById('confirm-password').value.trim();

  if (!restaurantName || !ownerName || !email || !phone || !city || !cuisine || !pass) {
    toast('⚠️ Please fill in all fields.', 'error');
    return;
  }

  if (pass.length < 6) {
    toast('⚠️ Password too short (min. 6 characters).', 'error');
    return;
  }

  if (pass !== confirmPass) {
    toast('⚠️ Passwords do not match.', 'error');
    return;
  }

  // Check if restaurant already exists
  const existing = JSON.parse(localStorage.getItem('appetitus_restaurant') || 'null');
  if (existing && existing.email === email) {
    toast('❌ Restaurant already registered with this email.', 'error');
    return;
  }

  const restaurant = {
    id: Date.now(), // Simple ID generation
    name: restaurantName,
    ownerName,
    email,
    phone,
    city,
    cuisine,
    password: pass, // In real app, this would be hashed
    joinDate: new Date().toISOString(),
    analytics: {
      totalReviews: 0,
      averageRating: 0,
      reviewTrends: [],
      popularTags: [],
      customerFeedback: []
    }
  };

  localStorage.setItem('appetitus_restaurant', JSON.stringify(restaurant));
  toast(`🎉 Welcome ${restaurantName}! Your dashboard is ready.`);
  setTimeout(() => window.location.href = 'restaurant-dashboard.php', 1400);
}
</script>
</body>
</html>



