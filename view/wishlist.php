<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Wishlist — Appetitus</title>
  <meta name="description" content="Your restaurant wishlist on Appetitus. Track the places you want to try." />
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav id="navbar">
  <a class="nav-logo" href="index.php"><span class="logo-icon">🍽️</span> Appetitus</a>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="explore.php">Explore</a></li>
    <li><a href="rankings.php">Top 10</a></li>
  </ul>
  <div class="nav-actions" id="nav-actions"></div>
</nav>

<!-- PAGE HERO -->
<div class="page-hero" style="background: linear-gradient(135deg, #E3F2FB 0%, #E8F0FE 50%, #C5CAE9 100%); border-bottom: 1px solid #9FA8DA;">
  <div class="container">
    <div class="page-hero-title">🔖 My Wishlist</div>
    <div class="page-hero-sub">Restaurants you want to try — don't forget any!</div>
  </div>
</div>

<!-- WISHLIST CONTENT -->
<div class="section">
  <div class="container">
    <div id="wish-count" class="results-count" style="margin-bottom:1.5rem; font-size:.9rem; color:var(--text-muted);"></div>
    <div id="wishlist-container">
      <div style="text-align:center; padding:3rem;">
        <div style="font-size:2.5rem; margin-bottom:1rem;">⏳</div>
        <p style="color:var(--text-muted)">Loading your wishlist...</p>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="footer-logo">🍽️ Appetitus</div>
  <div class="footer-tagline">The community for food lovers</div>
  <div class="footer-links">
    <a href="index.php">Home</a><a href="explore.php">Explore</a>
    <a href="favorites.php">Favorites</a><a href="wishlist.php">Wishlist</a>
  </div>
  <div class="footer-copy">© 2026 Appetitus</div>
</footer>

<script src="../script/app.js"></script>
<script>
async function loadWishlist() {
  const container = document.getElementById('wishlist-container');
  const countEl = document.getElementById('wish-count');
  const u = getCurrentUser();

  if (!u) {
    container.innerHTML = `
      <div style="text-align:center; padding:4rem 1rem;">
        <div style="font-size:4rem; margin-bottom:1rem;">🔖</div>
        <h2 style="margin-bottom:.75rem; font-family:'Playfair Display',serif;">Sign in to see your wishlist</h2>
        <p style="color:var(--text-muted); margin-bottom:2rem;">Log in to start building your restaurant wishlist.</p>
        <div style="display:flex; gap:1rem; justify-content:center;">
          <a href="login.php" class="btn btn-primary btn-lg">Log In</a>
          <a href="signup.php" class="btn btn-ghost btn-lg">Sign Up</a>
        </div>
      </div>`;
    countEl.textContent = '';
    return;
  }

  try {
    const res = await fetch('../controller/api_wishlist.php');
    const data = await res.json();

    if (!data.success || !data.data || data.data.length === 0) {
      container.innerHTML = `
        <div style="text-align:center; padding:4rem 1rem;">
          <div style="font-size:4rem; margin-bottom:1rem;">📋</div>
          <h2 style="margin-bottom:.75rem; font-family:'Playfair Display',serif;">Your wishlist is empty</h2>
          <p style="color:var(--text-muted); margin-bottom:2rem;">Tap the 🔖 button on any restaurant to add it to your wishlist!</p>
          <a href="explore.php" class="btn btn-primary btn-lg">🔍 Explore restaurants</a>
        </div>`;
      countEl.textContent = '0 restaurants';
      return;
    }

    const wishlist = data.data;
    countEl.textContent = `${wishlist.length} restaurant${wishlist.length !== 1 ? 's' : ''} to try`;

    container.innerHTML = `<div class="wishlist-list">` + wishlist.map((w, i) => `
      <div class="wishlist-item reveal" data-category="${w.category}" style="animation-delay:${i * 0.04}s">
        <div class="wishlist-img-wrap" onclick="openCasserole(${w.restaurant_id})">
          <img ${restaurantImageAttrs(w, 'loading="lazy"')} />
        </div>
        <div class="wishlist-info" onclick="openCasserole(${w.restaurant_id})">
          <div class="wishlist-name">${w.name}</div>
          <div class="wishlist-meta">
            <span>📍 ${w.city}</span>
            <span>🍽️ ${w.cuisine}</span>
            <span>💰 ${w.priceRange}</span>
          </div>
          <p class="wishlist-desc">${(w.description || '').slice(0, 150)}${(w.description || '').length > 150 ? '…' : ''}</p>
          <div class="wishlist-date">🔖 Added ${new Date(w.created_at).toLocaleDateString('en-US', { day:'2-digit', month:'long', year:'numeric' })}</div>
        </div>
        <div class="wishlist-actions">
          <button class="btn btn-primary" style="font-size:.8rem; padding:.5rem 1rem;" onclick="openCasserole(${w.restaurant_id})">View →</button>
          <button class="btn btn-ghost" style="font-size:.8rem; padding:.5rem 1rem;" onclick="removeFromWishlist(${w.restaurant_id}, event)">✕ Remove</button>
        </div>
      </div>`).join('') + `</div>`;

    setupReveal();
    document.querySelectorAll('.reveal').forEach(el => {
      const rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight) el.classList.add('visible');
    });

  } catch (err) {
    console.error('Error loading wishlist:', err);
    container.innerHTML = `
      <div style="text-align:center; padding:3rem; color:var(--text-muted);">
        <div style="font-size:2.5rem; margin-bottom:.75rem;">⚠️</div>
        <p>Failed to load wishlist. Please try again later.</p>
      </div>`;
  }
}

async function removeFromWishlist(restaurantId, event) {
  if (event) event.stopPropagation();
  try {
    const res = await fetch('../controller/api_wishlist.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ restaurant_id: restaurantId })
    });
    const data = await res.json();
    if (data.success) {
      state.wishlist = state.wishlist.filter(id => id !== restaurantId);
      toast('📌 Removed from wishlist');
      loadWishlist();
    }
  } catch (err) {
    console.error('Error:', err);
    toast('⚠️ Failed to remove', 'error');
  }
}

// Prevent explore init and load wishlist after restaurants
window.initExplore = null;
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(loadWishlist, 500);
});
</script>
</body>
</html>
