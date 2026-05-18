<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile — Appetitus</title>
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
    <li><a href="profile.php" class="active">My Profile</a></li>
  </ul>
  <div class="nav-actions" id="nav-actions"></div>
</nav>

<div class="section">
  <div class="container" style="max-width:900px;">
    <div id="profile-content">
      <div style="text-align:center;padding:5rem 0;">
        <div class="spinner"></div>
        <p style="margin-top:1rem;color:var(--text-muted);">Loading your profile...</p>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="footer-logo">🍽️ Appetitus</div>
  <div class="footer-links">
    <a href="index.php">Home</a><a href="explore.php">Explore</a>
    <a href="favorites.php">Favorites</a><a href="wishlist.php">Wishlist</a>
  </div>
  <div class="footer-copy">© 2026 Appetitus</div>
</footer>

<script src="../script/app.js?v=1.1"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const content = document.getElementById('profile-content');
  if (!content) return;

  const u = getCurrentUser();
  if (!u) {
    content.innerHTML = `
      <div style="text-align:center;padding:4rem 1rem;">
        <div style="font-size:4rem;margin-bottom:1rem;">🍽️</div>
        <h2 style="margin-bottom:.75rem;">You are not logged in</h2>
        <p style="color:var(--text-muted);margin-bottom:2rem;">Log in to access your profile and badges.</p>
        <div style="display:flex;gap:1rem;justify-content:center;">
          <a href="login.php" class="btn btn-primary btn-lg">Log In</a>
          <a href="signup.php" class="btn btn-ghost btn-lg">Sign Up</a>
        </div>
      </div>`;
    return;
  }

  // 1. Initial render with local data
  renderProfileUI(u);

  // 2. Background sync
  (async () => {
    try {
      if (typeof syncUserReviewCount === 'function') {
        console.log("Starting syncUserReviewCount...");
        await syncUserReviewCount();
        const updated = getCurrentUser();
        console.log("Sync complete. Updated user stats:", updated);
        renderProfileUI(updated);
        if (typeof updateNavUser === 'function') updateNavUser();
      }
    } catch (e) {
      console.error("Sync failed:", e);
    }
  })();
});

function renderProfileUI(u) {
  const content = document.getElementById('profile-content');
  if (!content || !u) return;

  const reviewCount = u.reviewCount || 0;
  const currentBadge = getBadge(reviewCount);

  content.innerHTML = `
    <!-- Profile header -->
    <div class="profile-header">
      <div class="profile-avatar">${(u.name || 'U').charAt(0).toUpperCase()}</div>
      <div>
        <div class="profile-name">${u.name || 'User'}</div>
        <div class="profile-email">📧 ${u.email || ''}</div>
        <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:.6rem;">
          Member since ${u.joinDate ? new Date(u.joinDate).toLocaleDateString() : 'recently'}
        </div>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;">
          <span class="badge ${currentBadge.class} badge-lg">${currentBadge.icon} ${currentBadge.name}</span>
          <span style="font-size:.82rem;color:var(--text-muted);">${reviewCount} published reviews</span>
        </div>
        ${u.favCuisines && u.favCuisines.length ? `
          <div style="margin-top:.6rem;font-size:.78rem;color:var(--text-muted);">
            🍽️ Favorite cuisines: ${u.favCuisines.join(', ')}
          </div>` : ''}
      </div>
      <button class="btn btn-ghost" style="margin-left:auto;align-self:flex-start;border-radius:var(--radius-sm)" onclick="logout()">
        Log Out
      </button>
    </div>

    <!-- Quick links to Favorites & Wishlist -->
    <div class="profile-quick-links">
      <a href="favorites.php" class="quick-link-card" id="ql-favorites">
        <div class="quick-link-icon" style="background:#FDE8EF;">❤️</div>
        <div>
          <div class="quick-link-title">My Favorites</div>
          <div class="quick-link-sub" id="fav-count-profile">Loading...</div>
        </div>
      </a>
      <a href="wishlist.php" class="quick-link-card" id="ql-wishlist">
        <div class="quick-link-icon" style="background:#E3F2FB;">🔖</div>
        <div>
          <div class="quick-link-title">My Wishlist</div>
          <div class="quick-link-sub" id="wish-count-profile">Loading...</div>
        </div>
      </a>
      <a href="explore.php" class="quick-link-card">
        <div class="quick-link-icon" style="background:#E8F5E9;">🔍</div>
        <div>
          <div class="quick-link-title">Explore</div>
          <div class="quick-link-sub">Discover new restaurants</div>
        </div>
      </a>
    </div>

    <!-- Badges showcase -->
    <div style="margin-bottom:2.5rem;">
      <div class="section-eyebrow" style="margin-bottom:.6rem;">🎖️ My badges</div>
      <h2 class="section-title" style="font-size:1.4rem;margin-bottom:1rem;">Badge progression</h2>
      <div class="badges-showcase" id="badges-grid"></div>
    </div>

    <!-- Progress to next badge -->
    <div id="next-badge-section" style="margin-bottom:2.5rem;background:var(--bg-surface);border-radius:var(--radius-md);padding:1.25rem;">
    </div>

    <!-- My reviews -->
    <div>
      <div class="section-eyebrow" style="margin-bottom:.6rem;">💬 My reviews</div>
      <h2 class="section-title" style="font-size:1.4rem;margin-bottom:1rem;">Published reviews (<span id="review-count-label">${reviewCount}</span>)</h2>
      <div id="my-reviews-list"></div>
    </div>`;

  renderBadgesGrid(reviewCount, currentBadge);
  renderNextBadge(reviewCount);
  renderMyReviewsList();
  loadProfileCounts();
}

function renderBadgesGrid(count, currentBadge) {
  const grid = document.getElementById('badges-grid');
  if (!grid) return;
  
  // Try to find BADGES in different spots
  const bList = window.BADGES || (typeof BADGES !== 'undefined' ? BADGES : null);
  
  if (!bList) {
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:1rem;color:var(--text-muted);">Syncing badges...</div>';
    // Retry in 500ms if not loaded yet
    setTimeout(() => renderBadgesGrid(count, currentBadge), 500);
    return;
  }

  grid.innerHTML = bList.map(b => {
    const earned = count >= b.min;
    return `
    <div class="badge-card ${earned ? 'earned' : 'locked'}">
      <div class="bicon">${b.icon}</div>
      <div class="bname">${b.name}</div>
      <div class="bdesc">${b.desc}</div>
      ${earned ? '<div style="margin-top:.4rem;font-size:.68rem;color:var(--accent);font-weight:700;">✓ Unlocked</div>' : '<div style="margin-top:.4rem;font-size:.68rem;color:var(--text-muted);">🔒 Locked</div>'}
    </div>`;
  }).join('');
}

function renderNextBadge(count) {
  const section = document.getElementById('next-badge-section');
  if (!section) return;
  
  const bList = window.BADGES || (typeof BADGES !== 'undefined' ? BADGES : null);
  if (!bList) {
    setTimeout(() => renderNextBadge(count), 500);
    return;
  }

  const next = bList.find(b => count < b.min);
  if (!next) {
    section.innerHTML = `<div style="text-align:center;font-size:.95rem;">🎉 <strong>Congratulations!</strong> You have reached the maximum badge: 💎 Maestro Platinum!</div>`;
    return;
  }
  const pct = Math.min(100, (count / next.min) * 100);
  section.innerHTML = `
    <div style="font-weight:700;margin-bottom:.5rem;">Next badge: ${next.icon} ${next.name}</div>
    <div style="font-size:.83rem;color:var(--text-muted);margin-bottom:.75rem;">
      ${count} / ${next.min} reviews · need ${next.min - count} more reviews to unlock ${next.name}
    </div>
    <div style="height:8px;background:var(--border);border-radius:4px;overflow:hidden;">
      <div style="height:100%;width:${pct}%;background:linear-gradient(90deg,var(--accent),#FF8A65);border-radius:4px;transition:width 1s ease;"></div>
    </div>`;
}

async function renderMyReviewsList() {
  const container = document.getElementById('my-reviews-list');
  if (!container) return;
  
  try {
    const u = getCurrentUser();
    if (!u) { showEmptyReviews(container); return; }
    
    let myReviews = [];
    try {
      const sessionRes = await fetch('../controller/api_read_reviews.php?mine=1', { credentials: 'include' });
      const sessionData = await sessionRes.json();
      if (sessionData.success && sessionData.data) {
        myReviews = sessionData.data;
      }
    } catch (e) {
      console.warn("My reviews fetch error:", e);
    }
    
    const countLabel = document.getElementById('review-count-label');
    if (countLabel) countLabel.textContent = myReviews.length;
    
    if (!myReviews.length) {
      showEmptyReviews(container);
      return;
    }
    
    // Fetch restaurants to get names/images
    const restaurantsRes = await fetch('../controller/get_restaurants.php', { credentials: 'include' });
    const restaurants = await restaurantsRes.json();
    const restaurantMap = {};
    restaurants.forEach(r => { restaurantMap[r.id] = r; });
    
    container.innerHTML = `<div class="my-reviews-list">` + [...myReviews].reverse().map(ur => {
      const r = restaurantMap[ur.restaurant_id];
      if (!r) return '';
      return `
      <div class="my-review-item" style="position:relative;">
        <img class="my-review-thumb" src="${r.image}" alt="${r.name}" />
        <div style="flex:1;">
          <div class="my-review-name">${r.name}</div>
          <div class="stars" style="margin:3px 0;">${typeof renderStars === 'function' ? renderStars(ur.rating) : '★'.repeat(ur.rating)}</div>
          <div class="my-review-text">${ur.text.slice(0,120)}${ur.text.length>120?'…':''}</div>
          <div style="font-size:.72rem;color:var(--text-muted);margin-top:.3rem;">📅 ${typeof fmtDate === 'function' ? fmtDate(ur.date) : ur.date}</div>
        </div>
        <button onclick="deleteReviewFromProfile(${ur.id}, event)" style="background:#dc3545;color:white;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;font-size:0.75rem;align-self:flex-start;margin-left:auto;">Delete</button>
      </div>`;
    }).join('') + `</div>`;
    
  } catch (err) {
    console.error('Error loading reviews:', err);
  }
}

function showEmptyReviews(container) {
  container.innerHTML = `
    <div style="text-align:center;padding:3rem;color:var(--text-muted);">
      <div style="font-size:2.5rem;margin-bottom:.75rem;">✍️</div>
      <p>You haven't left any reviews yet.</p>
      <a href="explore.php" class="btn btn-outline" style="margin-top:1rem;border-radius:var(--radius-sm);">Explore restaurants →</a>
    </div>`;
}

async function deleteReviewFromProfile(reviewId, event) {
  event.stopPropagation();
  if (!confirm('Are you sure you want to delete this review?')) return;
  try {
    const res = await fetch('../controller/api_delete_review.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ review_id: reviewId })
    });
    const data = await res.json();
    if (data.success) {
      renderMyReviewsList();
      if (typeof syncUserReviewCount === 'function') {
        await syncUserReviewCount();
        renderProfileUI(getCurrentUser());
      }
    }
  } catch (err) {
    console.error('Error:', err);
  }
}

async function loadProfileCounts() {
  try {
    const favRes = await fetch('../controller/api_favorites.php', { credentials: 'include' });
    const favData = await favRes.json();
    const favCount = (favData.success && favData.data) ? favData.data.length : 0;
    const favEl = document.getElementById('fav-count-profile');
    if (favEl) favEl.textContent = `${favCount} restaurant${favCount !== 1 ? 's' : ''}`;
  } catch (e) {}
  
  try {
    const wishRes = await fetch('../controller/api_wishlist.php', { credentials: 'include' });
    const wishData = await wishRes.json();
    const wishCount = (wishData.success && wishData.data) ? wishData.data.length : 0;
    const wishEl = document.getElementById('wish-count-profile');
    if (wishEl) wishEl.textContent = `${wishCount} restaurant${wishCount !== 1 ? 's' : ''}`;
  } catch (e) {}
}
</script>
</body>
</html>
