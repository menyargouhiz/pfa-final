<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Favorites — Appetitus</title>
  <meta name="description" content="Your favorite restaurants on Appetitus. Browse the places you love." />
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
<div class="page-hero" style="background: linear-gradient(135deg, #FDE8EF 0%, #FCE4EC 50%, #F8BBD0 100%); border-bottom: 1px solid #F48FB1;">
  <div class="container">
    <div class="page-hero-title">❤️ My Favorites</div>
    <div class="page-hero-sub">The restaurants you absolutely love.</div>
  </div>
</div>

<!-- FAVORITES CONTENT -->
<div class="section">
  <div class="container">
    <div id="fav-count" class="results-count" style="margin-bottom:1.5rem; font-size:.9rem; color:var(--text-muted);"></div>
    <div class="restaurant-grid" id="favorites-grid">
      <div style="grid-column:1/-1; text-align:center; padding:3rem;">
        <div style="font-size:2.5rem; margin-bottom:1rem;">⏳</div>
        <p style="color:var(--text-muted)">Loading your favorites...</p>
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
async function loadFavorites() {
  const grid = document.getElementById('favorites-grid');
  const countEl = document.getElementById('fav-count');
  const u = getCurrentUser();

  if (!u) {
    grid.innerHTML = `
      <div style="grid-column:1/-1; text-align:center; padding:4rem 1rem;">
        <div style="font-size:4rem; margin-bottom:1rem;">❤️</div>
        <h2 style="margin-bottom:.75rem; font-family:'Playfair Display',serif;">Sign in to see your favorites</h2>
        <p style="color:var(--text-muted); margin-bottom:2rem;">Log in to start saving your favorite restaurants.</p>
        <div style="display:flex; gap:1rem; justify-content:center;">
          <a href="login.php" class="btn btn-primary btn-lg">Log In</a>
          <a href="signup.php" class="btn btn-ghost btn-lg">Sign Up</a>
        </div>
      </div>`;
    countEl.textContent = '';
    return;
  }

  try {
    const res = await fetch('../controller/api_favorites.php');
    const data = await res.json();

    if (!data.success || !data.data || data.data.length === 0) {
      grid.innerHTML = `
        <div style="grid-column:1/-1; text-align:center; padding:4rem 1rem;">
          <div style="font-size:4rem; margin-bottom:1rem;">💔</div>
          <h2 style="margin-bottom:.75rem; font-family:'Playfair Display',serif;">No favorites yet</h2>
          <p style="color:var(--text-muted); margin-bottom:2rem;">Start exploring and tap the ❤️ button on restaurants you love!</p>
          <a href="explore.php" class="btn btn-primary btn-lg">🔍 Explore restaurants</a>
        </div>`;
      countEl.textContent = '0 favorites';
      return;
    }

    const favorites = data.data;
    countEl.textContent = `${favorites.length} favorite${favorites.length !== 1 ? 's' : ''}`;

    grid.innerHTML = favorites.map((f, i) => `
      <div class="restaurant-card reveal" data-category="${f.category}" onclick="openCasserole(${f.restaurant_id})" style="animation-delay:${i * 0.05}s">
        <div class="card-img-wrap">
          <img ${restaurantImageAttrs(f, 'loading="lazy"')} />
          <div class="card-steam">
            <div class="steam-wisp"></div><div class="steam-wisp"></div>
            <div class="steam-wisp"></div><div class="steam-wisp"></div>
          </div>
          <div class="card-cuisine">${f.cuisine}</div>
          <button class="fav-wish-btn fav-btn card-fav-btn active" data-fav-id="${f.restaurant_id}" onclick="removeFavoriteFromPage(${f.restaurant_id}, event)" title="Remove from favorites">❤️</button>
        </div>
        <div class="card-body">
          <div class="card-name">${f.name}</div>
          <div class="card-loc">📍 ${f.city}</div>
          <p style="font-size:.82rem; color:var(--text-secondary); margin:.5rem 0;">${(f.description || '').slice(0, 100)}${(f.description || '').length > 100 ? '…' : ''}</p>
          <div class="card-footer">
            <span class="card-price">${f.priceRange}</span>
            <span style="font-size:.72rem; color:var(--text-muted);">Added ${new Date(f.created_at).toLocaleDateString()}</span>
          </div>
        </div>
        <div class="casserole-hint">🫕</div>
      </div>`).join('');

    setupReveal();
    document.querySelectorAll('.reveal').forEach(el => {
      const rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight) el.classList.add('visible');
    });

  } catch (err) {
    console.error('Error loading favorites:', err);
    grid.innerHTML = `
      <div style="grid-column:1/-1; text-align:center; padding:3rem; color:var(--text-muted);">
        <div style="font-size:2.5rem; margin-bottom:.75rem;">⚠️</div>
        <p>Failed to load favorites. Please try again later.</p>
      </div>`;
  }
}

async function removeFavoriteFromPage(restaurantId, event) {
  event.stopPropagation();
  try {
    const res = await fetch('../controller/api_favorites.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ restaurant_id: restaurantId })
    });
    const data = await res.json();
    if (data.success) {
      state.favorites = state.favorites.filter(id => id !== restaurantId);
      toast('💔 Removed from favorites');
      loadFavorites(); // Reload the list
    }
  } catch (err) {
    console.error('Error:', err);
    toast('⚠️ Failed to remove', 'error');
  }
}

// Load favorites after restaurants are loaded
const originalInitFav = window.initExplore;
window.initExplore = null; // prevent explore init
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(loadFavorites, 500); // Wait for app.js to load restaurants
});
</script>
</body>
</html>
