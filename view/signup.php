<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up — Appetitus</title>
  <link rel="icon"
    href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
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

  <div class="auth-page" style="align-items:flex-start;padding-top:3rem;padding-bottom:3rem;">
    <div class="auth-card" style="max-width:500px;">
      <div class="auth-logo-wrap">
        <div class="auth-logo-icon">🍽️</div>
        <div class="auth-logo-name">Appetitus</div>
      </div>
      <h1 class="auth-title">Sign Up</h1>
      <p class="auth-subtitle">Join the community and unlock loyalty badges by leaving reviews!</p>

      <div class="form-group user-type-selector" style="--i: 1;">
        <label class="form-label">Sign up as</label>
        <select class="form-input" id="userType" onchange="updateForm()">
          <option value="user">👤 User</option>
          <option value="restaurant">🏪 Restaurant</option>
        </select>
      </div>

      <div id="userFields">
        <div class="form-group" style="--i: 2;">
          <label class="form-label">Full name</label>
          <input class="form-input" type="text" id="name" placeholder="Mary Smith" autocomplete="name" />
        </div>
        <div class="form-group" style="--i: 3;">
          <label class="form-label">Email address</label>
          <input class="form-input" type="email" id="email" placeholder="you@example.com" autocomplete="email" />
        </div>
        <div class="form-group" style="--i: 4;">
          <label class="form-label">Password</label>
          <input class="form-input" type="password" id="password" placeholder="Minimum 6 characters"
            autocomplete="new-password" />
        </div>
        <div class="form-group" style="--i: 5;">
          <label class="form-label">Confirm password</label>
        <input class="form-input" type="password" id="password2" placeholder="Repeat your password" />
      </div>

      <!-- Cuisine preferences -->
      <div class="form-group" style="--i: 6;">
        <label class="form-label">Your favorite cuisines (optional)</label>
        <div class="cuisine-chips" id="cuisine-chips">
          <span class="cuisine-chip" data-v="gastronomique" onclick="toggleChip(this)" style="--i: 1;">⭐ Fine Dining</span>
          <span class="cuisine-chip" data-v="asiatique" onclick="toggleChip(this)" style="--i: 2;">🥢 Asian</span>
          <span class="cuisine-chip" data-v="italien" onclick="toggleChip(this)" style="--i: 3;">🍕 Italian</span>
          <span class="cuisine-chip" data-v="fruits-de-mer" onclick="toggleChip(this)" style="--i: 4;">🦞 Seafood</span>
          <span class="cuisine-chip" data-v="street-food" onclick="toggleChip(this)" style="--i: 5;">🌮 Street Food</span>
          <span class="cuisine-chip" data-v="brasserie" onclick="toggleChip(this)" style="--i: 6;">🍺 Pubs</span>
          <span class="cuisine-chip" data-v="africaine" onclick="toggleChip(this)" style="--i: 7;">🌍 African</span>
        </div>
      </div>

      </div> <!-- end userFields -->

      <div id="restaurantFields" style="display:none;">
        <div class="form-group" style="--i: 2;">
          <label class="form-label">Restaurant Name</label>
          <input class="form-input" type="text" id="restaurant-name" placeholder="Your Restaurant Name" />
        </div>
        <div class="form-group" style="--i: 3;">
          <label class="form-label">Owner/Manager Name</label>
          <input class="form-input" type="text" id="owner-name" placeholder="Your Full Name" />
        </div>
        <div class="form-group" style="--i: 4;">
          <label class="form-label">Email Address</label>
          <input class="form-input" type="email" id="restaurant-email" placeholder="restaurant@example.com" autocomplete="email" />
        </div>
        <div class="form-group" style="--i: 5;">
          <label class="form-label">Phone Number</label>
          <input class="form-input" type="tel" id="phone" placeholder="+216 XX XXX XXX" />
        </div>
        <div class="form-group" style="--i: 6;">
          <label class="form-label">City</label>
          <input class="form-input" type="text" id="city" placeholder="Tunis, La Marsa, etc." />
        </div>
        <div class="form-group" style="--i: 7;">
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
        <div class="form-group" style="--i: 8;">
          <label class="form-label">Password</label>
          <input class="form-input" type="password" id="restaurant-password" placeholder="Create a strong password" autocomplete="new-password" />
        </div>
        <div class="form-group" style="--i: 9;">
          <label class="form-label">Confirm Password</label>
          <input class="form-input" type="password" id="restaurant-password2" placeholder="Confirm your password" autocomplete="new-password" />
        </div>
      </div> <!-- end restaurantFields -->
      <div id="badgePreview" class="badge-preview"
        style="background:var(--bg-surface);border:1.5px solid var(--border);border-radius:var(--radius-md);padding:1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:.75rem;">
        <span style="font-size:1.8rem;">🌱</span>
        <div>
          <div style="font-weight:700;font-size:.85rem;">Starting badge: Curious</div>
          <div style="font-size:.75rem;color:var(--text-muted);">Leave your first reviews and earn more badges!</div>
        </div>
      </div>

      <button class="btn btn-primary btn-full" style="border-radius:var(--radius-sm);" onclick="doSignup()">
        🌱 Create my Appetitus account
      </button>

      <div class="auth-switch" style="margin-top:1.25rem;">
        Already have an account? <a href="login.php">Log In</a>
      </div>
    </div>
  </div>

  <script src="../script/app.js"></script>
  <script>
    function toggleChip(el) {
      el.classList.toggle('selected');
    }

    function updateForm() {
      const userType = document.getElementById('userType').value;
      const userFields = document.getElementById('userFields');
      const restaurantFields = document.getElementById('restaurantFields');
      const badgePreview = document.getElementById('badgePreview');
      const button = document.querySelector('.btn-primary');
      const selector = document.querySelector('.user-type-selector');
      
      selector.setAttribute('data-type', userType);
      
      if (userType === 'restaurant') {
        userFields.style.opacity = '0';
        userFields.style.transform = 'translateY(-10px)';
        setTimeout(() => {
          userFields.style.display = 'none';
          restaurantFields.style.display = 'block';
          restaurantFields.style.opacity = '0';
          restaurantFields.style.transform = 'translateY(10px)';
          badgePreview.style.display = 'none';
          button.textContent = '🏪 Register Restaurant';
          setTimeout(() => {
            restaurantFields.style.opacity = '1';
            restaurantFields.style.transform = 'translateY(0)';
          }, 50);
        }, 200);
      } else {
        restaurantFields.style.opacity = '0';
        restaurantFields.style.transform = 'translateY(-10px)';
        setTimeout(() => {
          restaurantFields.style.display = 'none';
          userFields.style.display = 'block';
          userFields.style.opacity = '0';
          userFields.style.transform = 'translateY(10px)';
          badgePreview.style.display = 'flex';
          button.textContent = '🌱 Create my Appetitus account';
          setTimeout(() => {
            userFields.style.opacity = '1';
            userFields.style.transform = 'translateY(0)';
          }, 50);
        }, 200);
      }
    }

    async function doSignup() {
      const userType = document.getElementById('userType').value;
      
      const button = document.querySelector('.btn-primary');
      button.classList.add('btn-loading');
      button.disabled = true;
      
      if (userType === 'user') {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const pass = document.getElementById('password').value;
        const pass2 = document.getElementById('password2').value;

        if (!name) { if (typeof toast === 'function') toast('⚠️ Enter your full name.', 'error'); button.classList.remove('btn-loading'); button.disabled = false; return; }
        if (!email) { if (typeof toast === 'function') toast('⚠️ Enter your email address.', 'error'); button.classList.remove('btn-loading'); button.disabled = false; return; }
        if (pass.length < 6) { if (typeof toast === 'function') toast('⚠️ Password too short (min. 6 characters).', 'error'); button.classList.remove('btn-loading'); button.disabled = false; return; }
        if (pass !== pass2) { if (typeof toast === 'function') toast('⚠️ Passwords do not match.', 'error'); button.classList.remove('btn-loading'); button.disabled = false; return; }

        // 1. Send data to the PHP Backend to insert into MySQL
        try {
          const response = await fetch('../controller/api_signup.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ name: name, email: email, password: pass })
          });
          const result = await response.json();
          
          if(!response.ok) {
             if (typeof toast === 'function') toast('⚠️ Error saving to database: ' + (result.error || 'Unknown error'), 'error');
             button.classList.remove('btn-loading');
             button.disabled = false;
             return;
          }
        } catch (err) {
          if (typeof toast === 'function') toast('⚠️ Network error connecting to database.', 'error');
          button.classList.remove('btn-loading');
          button.disabled = false;
          return;
        }

        // 2. Now log in to create the session
        let loginResult;
        try {
          const loginResponse = await fetch('../controller/api_login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ email: email, password: pass })
          });
          loginResult = await loginResponse.json();
          
          if (!loginResult.success) {
            if (typeof toast === 'function') toast('⚠️ Signup successful but login failed. Please log in manually.', 'error');
            setTimeout(() => window.location.href = 'login.php', 1500);
            return;
          }
        } catch (err) {
          if (typeof toast === 'function') toast('⚠️ Signup successful but auto-login failed.', 'error');
          setTimeout(() => window.location.href = 'login.php', 1500);
          return;
        }

        const favCuisines = [...document.querySelectorAll('.cuisine-chip.selected')].map(c => c.dataset.v);
        localStorage.removeItem('appetitus_reviews');

        // 3. Keep the local session alive for the UI (like badges)
        const user = {
          id: loginResult.data.id,
          name: loginResult.data.name,
          email: loginResult.data.email,
          joinDate: new Date().toISOString(),
          reviewCount: 0,
          favCuisines
        };
        localStorage.setItem('appetitus_user', JSON.stringify(user));
        if (typeof toast === 'function') toast(`🎉 Welcome ${name}! Your 🌱 Curious badge awaits.`);
        setTimeout(() => window.location.href = 'profile.php', 1400);
      } else if (userType === 'restaurant') {
        const restaurantName = document.getElementById('restaurant-name').value.trim();
        const ownerName = document.getElementById('owner-name').value.trim();
        const email = document.getElementById('restaurant-email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const city = document.getElementById('city').value.trim();
        const cuisine = document.getElementById('cuisine').value;
        const pass = document.getElementById('restaurant-password').value.trim();
        const confirmPass = document.getElementById('restaurant-password2').value.trim();

        if (!restaurantName || !ownerName || !email || !phone || !city || !cuisine || !pass) {
          if (typeof toast === 'function') toast('⚠️ Please fill in all fields.', 'error');
          button.classList.remove('btn-loading');
          button.disabled = false;
          return;
        }

        if (pass.length < 6) {
          if (typeof toast === 'function') toast('⚠️ Password too short (min. 6 characters).', 'error');
          button.classList.remove('btn-loading');
          button.disabled = false;
          return;
        }

        if (pass !== confirmPass) {
          if (typeof toast === 'function') toast('⚠️ Passwords do not match.', 'error');
          button.classList.remove('btn-loading');
          button.disabled = false;
          return;
        }

        // Check if restaurant already exists
        const existing = JSON.parse(localStorage.getItem('appetitus_restaurant') || 'null');
        if (existing && existing.email === email) {
          if (typeof toast === 'function') toast('❌ Restaurant already registered with this email.', 'error');
          button.classList.remove('btn-loading');
          button.disabled = false;
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
        if (typeof toast === 'function') toast(`🎉 Welcome ${restaurantName}! Your dashboard is ready.`);
        setTimeout(() => window.location.href = 'restaurant-dashboard.php', 1400);
      }
    }
  </script>
</body>

</html>