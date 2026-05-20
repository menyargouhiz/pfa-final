<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reviewers - Appetitus</title>
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
    <li><a href="user-search.php" class="active">Reviewers</a></li>
  </ul>
  <div class="nav-actions" id="nav-actions"></div>
</nav>

<div class="page-hero">
  <div class="container user-search-hero">
    <div>
      <div class="page-hero-title">Find Reviewers</div>
      <div class="page-hero-sub">Search community members by name or email.</div>
    </div>
    <div class="user-search-bar">
      <input class="search-input" id="user-search-input" placeholder="Name or email..." autocomplete="off" />
      <button class="btn btn-primary" id="user-search-btn">Search</button>
    </div>
  </div>
</div>

<main class="section">
  <div class="container user-search-layout">
    <section>
      <div class="sort-row">
        <div class="results-count" id="user-results-count">Loading users...</div>
        <button class="btn btn-ghost" id="user-clear-btn" type="button">Clear</button>
      </div>
      <div class="user-results-grid" id="user-results"></div>
    </section>

    <aside class="user-review-panel" id="user-review-panel">
      <div class="empty-user-panel">
        <div class="empty-user-avatar">U</div>
        <h2>Select a reviewer</h2>
        <p>Open a profile card to see their reviews and favorite places.</p>
      </div>
    </aside>
  </div>
</main>

<footer>
  <div class="footer-logo">🍽️ Appetitus</div>
  <div class="footer-tagline">The community for food lovers</div>
  <div class="footer-links">
    <a href="index.php">Home</a><a href="explore.php">Explore</a><a href="user-search.php">Reviewers</a>
    <a href="favorites.php">Favorites</a><a href="wishlist.php">Wishlist</a>
    <a href="login.php">Log In</a><a href="signup.php">Sign Up</a>
  </div>
  <div class="footer-copy">© 2026 Appetitus</div>
</footer>

<script src="../script/app.js"></script>
<script>
let users = [];
let selectedUserId = null;

function escapeHtml(value) {
  return String(value ?? '').replace(/[&<>"']/g, char => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  }[char]));
}

<<<<<<< HEAD
function escapeJsString(value) {
  return String(value ?? '').replace(/\\/g, '\\\\').replace(/'/g, "\\'");
}

=======
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
function initials(name) {
  return String(name || 'U').trim().split(/\s+/).slice(0, 2).map(part => part[0]).join('').toUpperCase();
}

async function fetchUsers(query = '') {
  const body = new FormData();
  body.append('action', query ? 'search' : 'getAll');
  if (query) body.append('name', query);

  const res = await fetch('../controller/traitement.php', {
    method: 'POST',
    body
  });
  const data = await res.json();
  if (!data.success) throw new Error(data.error || 'Could not load users');
  return data.data || [];
}

async function loadUsers(query = '') {
  const grid = document.getElementById('user-results');
  const count = document.getElementById('user-results-count');
  grid.innerHTML = '<div class="user-loading">Loading...</div>';
  count.textContent = 'Loading users...';

  try {
    users = await fetchUsers(query);
    renderUsers(query);
  } catch (err) {
    grid.innerHTML = `<div class="user-empty-state">${escapeHtml(err.message)}</div>`;
    count.textContent = 'No users loaded';
  }
}

function renderUsers(query = '') {
  const grid = document.getElementById('user-results');
  const count = document.getElementById('user-results-count');
  count.textContent = `${users.length} reviewer${users.length === 1 ? '' : 's'} ${query ? 'matched' : 'available'}`;

  if (!users.length) {
    grid.innerHTML = '<div class="user-empty-state">No reviewers found.</div>';
    return;
  }

  grid.innerHTML = users.map((user, index) => `
<<<<<<< HEAD
    <button class="user-result-card ${String(user.id) === selectedUserId ? 'active' : ''}" style="--reveal-delay:${Math.min(index, 8) * 45}ms" type="button" onclick="selectUser('${escapeJsString(user.id)}')">
      <span class="user-avatar">${escapeHtml(initials(user.nom))}</span>
      <span class="user-card-main">
        <strong>${escapeHtml(user.nom)}</strong>
        <span>${escapeHtml(user.email || 'Community reviewer')}</span>
=======
    <button class="user-result-card ${Number(user.id) === selectedUserId ? 'active' : ''}" style="--reveal-delay:${Math.min(index, 8) * 45}ms" type="button" onclick="selectUser(${Number(user.id)})">
      <span class="user-avatar">${escapeHtml(initials(user.nom))}</span>
      <span class="user-card-main">
        <strong>${escapeHtml(user.nom)}</strong>
        <span>${escapeHtml(user.email)}</span>
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
      </span>
      <span class="user-review-count">${Number(user.review_count || 0)} reviews</span>
    </button>
  `).join('');
}

async function selectUser(userId) {
<<<<<<< HEAD
  selectedUserId = String(userId);
  renderUsers(document.getElementById('user-search-input').value.trim());

  const user = users.find(item => String(item.id) === selectedUserId);
=======
  selectedUserId = userId;
  renderUsers(document.getElementById('user-search-input').value.trim());

  const user = users.find(item => Number(item.id) === userId);
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
  const panel = document.getElementById('user-review-panel');
  panel.innerHTML = '<div class="user-loading">Loading reviews...</div>';

  try {
<<<<<<< HEAD
    const params = selectedUserId.startsWith('author:')
      ? `author=${encodeURIComponent(selectedUserId.slice(7))}`
      : `user_id=${encodeURIComponent(selectedUserId)}`;
    const res = await fetch(`../controller/api_read_reviews.php?${params}`, {
=======
    const res = await fetch(`../controller/api_read_reviews.php?user_id=${encodeURIComponent(userId)}`, {
>>>>>>> 9f33d9882a7e691571e96025575b7eef87d6352b
      credentials: 'include'
    });
    const data = await res.json();
    if (!data.success) throw new Error(data.error || 'Could not load reviews');
    renderUserReviews(user, data.data || []);
  } catch (err) {
    panel.innerHTML = `<div class="user-empty-state">${escapeHtml(err.message)}</div>`;
  }
}

function findRestaurant(review) {
  return state.restaurants.find(restaurant => Number(restaurant.id) === Number(review.restaurant_id));
}

function renderUserReviews(user, reviews) {
  const panel = document.getElementById('user-review-panel');
  const average = reviews.length
    ? reviews.reduce((sum, review) => sum + Number(review.rating || 0), 0) / reviews.length
    : 0;

  panel.innerHTML = `
    <div class="user-panel-header">
      <div class="user-avatar large">${escapeHtml(initials(user?.nom))}</div>
      <div>
        <h2>${escapeHtml(user?.nom || 'Reviewer')}</h2>
        <p>${escapeHtml(user?.email || '')}</p>
      </div>
    </div>
    <div class="user-panel-stats">
      <div><strong>${reviews.length}</strong><span>Reviews</span></div>
      <div><strong>${average.toFixed(1)}</strong><span>Avg rating</span></div>
    </div>
    <div class="user-review-list">
      ${reviews.length ? reviews.map(review => {
        const restaurant = findRestaurant(review);
        return `
          <article class="user-review-item">
            <div>
              <h3>${escapeHtml(restaurant?.name || `Restaurant #${review.restaurant_id}`)}</h3>
              <div class="stars">${renderStars(Number(review.rating || 0))}<span class="rating-count">${escapeHtml(review.date || '')}</span></div>
            </div>
            <p>${escapeHtml(review.text || '')}</p>
            ${restaurant ? `<button class="btn btn-outline" type="button" onclick="openCasserole(${Number(restaurant.id)})">View restaurant</button>` : ''}
          </article>
        `;
      }).join('') : '<div class="user-empty-state">This reviewer has not posted any reviews yet.</div>'}
    </div>
  `;
}

document.getElementById('user-search-btn').addEventListener('click', () => {
  selectedUserId = null;
  const query = document.getElementById('user-search-input').value.trim();
  const url = new URL(window.location.href);
  if (query) url.searchParams.set('q', query);
  else url.searchParams.delete('q');
  history.replaceState(null, '', url);
  loadUsers(query);
});

document.getElementById('user-search-input').addEventListener('keydown', event => {
  if (event.key === 'Enter') document.getElementById('user-search-btn').click();
});

document.getElementById('user-clear-btn').addEventListener('click', () => {
  selectedUserId = null;
  document.getElementById('user-search-input').value = '';
  document.getElementById('user-review-panel').innerHTML = `
    <div class="empty-user-panel">
      <div class="empty-user-avatar">U</div>
      <h2>Select a reviewer</h2>
      <p>Open a profile card to see their reviews and favorite places.</p>
    </div>
  `;
  loadUsers();
});

document.addEventListener('DOMContentLoaded', () => {
  const query = new URLSearchParams(window.location.search).get('q') || '';
  document.getElementById('user-search-input').value = query;
  loadUsers(query);
});
</script>
</body>
</html>
