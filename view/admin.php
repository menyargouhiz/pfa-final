<?php // Converted to PHP ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard — Appetitus</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .admin-nav { background: var(--bg-surface); border-bottom: 1px solid var(--border); padding: 0; }
    .admin-nav .container { display: flex; gap: 2rem; padding: 1rem 0; }
    .admin-nav a { color: var(--text-secondary); text-decoration: none; padding: 0.5rem 1rem; border-radius: var(--radius-sm); transition: var(--transition); }
    .admin-nav a.active { background: var(--accent); color: white; }
    .admin-nav a:hover { background: var(--accent-light); color: var(--text-primary); }
    .admin-section { margin-bottom: 3rem; }
    .admin-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.5rem; text-align: center; }
    .stat-num { font-size: 2rem; font-weight: 700; color: var(--accent); margin-bottom: 0.5rem; }
    .stat-label { color: var(--text-muted); font-size: 0.9rem; }
    .admin-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    .admin-table th, .admin-table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid var(--border); }
    .admin-table th { background: var(--bg-surface); font-weight: 600; }
    .admin-table tr:hover { background: var(--bg-surface); }
    .btn-admin { padding: 0.5rem 1rem; border: none; border-radius: var(--radius-sm); cursor: pointer; font-size: 0.9rem; transition: var(--transition); }
    .btn-edit { background: var(--accent); color: white; }
    .btn-edit:hover { background: #d66f4a; }
    .btn-delete { background: #e74c3c; color: white; }
    .btn-delete:hover { background: #c0392b; }
    .btn-add { background: var(--accent); color: white; padding: 0.75rem 1.5rem; }
    .btn-add:hover { background: #d66f4a; }
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
    .modal-content { background: white; margin: 5% auto; padding: 2rem; border-radius: var(--radius-md); width: 90%; max-width: 600px; max-height: 80%; overflow-y: auto; }
    .modal h2 { margin-bottom: 1rem; }
    .form-group { margin-bottom: 1rem; }
    .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
    .form-input, .form-textarea, .form-select { width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 1rem; }
    .form-textarea { resize: vertical; min-height: 100px; }
    .modal-actions { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; }
  </style>
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

<div class="admin-nav">
  <div class="container">
    <a href="#overview" class="active" onclick="showSection('overview')">Overview</a>
    <a href="#restaurants" onclick="showSection('restaurants')">Restaurants</a>
    <a href="#restaurant-owners" onclick="showSection('restaurant-owners')">Restaurant Owners</a>
    <a href="#reviews" onclick="showSection('reviews')">Reviews</a>
    <a href="#users" onclick="showSection('users')">Users</a>
    <a href="#favorites" onclick="showSection('favorites')">Favorites</a>
  </div>
</div>

<div class="section">
  <div class="container">

    <!-- OVERVIEW -->
    <div id="overview-section" class="admin-section">
      <h1>Admin Dashboard</h1>
      <div class="admin-stats">
        <div class="stat-card">
          <div class="stat-num" id="total-restaurants">0</div>
          <div class="stat-label">Total Restaurants</div>
        </div>
        <div class="stat-card">
          <div class="stat-num" id="total-reviews">0</div>
          <div class="stat-label">Total Reviews</div>
        </div>
        <div class="stat-card">
          <div class="stat-num" id="total-users">0</div>
          <div class="stat-label">Registered Users</div>
        </div>
        <div class="stat-card">
          <div class="stat-num" id="total-restaurant-owners">0</div>
          <div class="stat-label">Restaurant Owners</div>
        </div>
        <div class="stat-card">
          <div class="stat-num" id="avg-rating">0.0</div>
          <div class="stat-label">Average Rating</div>
        </div>
        <div class="stat-card">
          <div class="stat-num" id="total-favorites">0</div>
          <div class="stat-label">Total Favorites</div>
        </div>
        <div class="stat-card">
          <div class="stat-num" id="total-wishlist">0</div>
          <div class="stat-label">Total Wishlist Items</div>
        </div>
      </div>
    </div>

    <!-- RESTAURANTS -->
    <div id="restaurants-section" class="admin-section" style="display:none;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
        <h2>Manage Restaurants</h2>
        <button class="btn btn-add" onclick="openAddRestaurantModal()">+ Add Restaurant</button>
      </div>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>City</th>
            <th>Cuisine</th>
            <th>Rating</th>
            <th>Reviews</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="restaurants-table-body"></tbody>
      </table>
    </div>

    <!-- RESTAURANT OWNERS -->
    <div id="restaurant-owners-section" class="admin-section" style="display:none;">
      <h2>Restaurant Owners</h2>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Restaurant Name</th>
            <th>Owner</th>
            <th>Email</th>
            <th>City</th>
            <th>Cuisine</th>
            <th>Join Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="restaurant-owners-table-body"></tbody>
      </table>
    </div>

    <!-- REVIEWS -->
    <div id="reviews-section" class="admin-section" style="display:none;">
      <h2>Manage Reviews</h2>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Restaurant</th>
            <th>User</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Photos</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="reviews-table-body"></tbody>
      </table>
    </div>

    <!-- USERS -->
    <div id="users-section" class="admin-section" style="display:none;">
      <h2>Manage Users</h2>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Join Date</th>
            <th>Reviews</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="users-table-body"></tbody>
      </table>
    </div>

    <!-- FAVORITES & WISHLIST -->
    <div id="favorites-section" class="admin-section" style="display:none;">
      <h2>User Favorites & Wishlist</h2>
      <table class="admin-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Favorites Count</th>
            <th>Wishlist Count</th>
            <th>Favorites List</th>
            <th>Wishlist List</th>
          </tr>
        </thead>
        <tbody id="favorites-table-body"></tbody>
      </table>
    </div>

  </div>
</div>

<!-- MODAL -->
<div id="restaurant-modal" class="modal">
  <div class="modal-content">
    <h2 id="modal-title">Add Restaurant</h2>
    <form id="restaurant-form">
      <div class="form-group">
        <label class="form-label">Name</label>
        <input class="form-input" type="text" id="restaurant-name" required>
      </div>
      <div class="form-group">
        <label class="form-label">City</label>
        <input class="form-input" type="text" id="restaurant-city" required>
      </div>
      <div class="form-group">
        <label class="form-label">Cuisine</label>
        <select class="form-select" id="restaurant-category" required>
          <option value="gastronomique">Fine Dining</option>
          <option value="asiatique">Asian</option>
          <option value="italien">Italian</option>
          <option value="fruits-de-mer">Seafood</option>
          <option value="street-food">Street Food</option>
          <option value="brasserie">Pubs</option>
          <option value="africaine">African</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Price Range</label>
        <select class="form-select" id="restaurant-price" required>
          <option value="€">€ Low Budget</option>
          <option value="€€">€€ Medium</option>
          <option value="€€€">€€€ High-end</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Image URL</label>
        <input class="form-input" type="url" id="restaurant-image" required>
      </div>
      <div class="form-group">
        <label class="form-label">Tags (comma-separated)</label>
        <input class="form-input" type="text" id="restaurant-tags" placeholder="e.g. pizza, italian, family-friendly">
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script src="app.js"></script>
<script>
let users = [];
let adminStats = {};
let adminFavorites = [];
let currentEditingId = null;

document.addEventListener('DOMContentLoaded', async () => {
  await loadAdminDashboard();
});

async function adminRequest(action, payload = null) {
  const options = { credentials: 'include' };
  if (payload) {
    options.method = 'POST';
    options.headers = { 'Content-Type': 'application/json' };
    options.body = JSON.stringify(payload);
  }

  const res = await fetch(`../controller/api_admin.php?action=${encodeURIComponent(action)}`, options);
  const data = await res.json();
  if (!data.success) throw new Error(data.error || 'Admin request failed');
  return data.data || {};
}

async function loadAdminDashboard() {
  try {
    const data = await adminRequest('dashboard');
    adminStats = data.stats || {};
    users = data.users || [];
    adminFavorites = data.favorites || [];
    window.state.restaurants = (data.restaurants || []).map(r => ({
      ...r,
      avg: Number(r.avg || 0),
      review_count: Number(r.review_count || 0),
      reviews: []
    }));
    window.state.userReviews = data.reviews || [];

    updateOverview();
    renderRestaurantsTable();
    renderRestaurantOwnersTable();
    renderReviewsTable();
    renderUsersTable();
    renderFavoritesTable();
  } catch (err) {
    toast(err.message, 'error');
  }
}

function showSection(section) {
  document.querySelectorAll('.admin-section').forEach(s => s.style.display = 'none');
  document.getElementById(section + '-section').style.display = 'block';
  document.querySelectorAll('.admin-nav a').forEach(a => a.classList.remove('active'));
  document.querySelector(`.admin-nav a[href="#${section}"]`).classList.add('active');
}

function updateOverview() {
  document.getElementById('total-restaurants').textContent = adminStats.total_restaurants ?? window.state.restaurants.length;
  document.getElementById('total-reviews').textContent = adminStats.total_reviews ?? window.state.userReviews.length;
  document.getElementById('total-users').textContent = adminStats.total_users ?? users.length;

  // Count restaurant owners
  let restaurantOwnerCount = 0;
  for (let i = 0; i < localStorage.length; i++) {
    const key = localStorage.key(i);
    if (key && key.startsWith('appetitus_restaurant')) {
      restaurantOwnerCount++;
    }
  }
  document.getElementById('total-restaurant-owners').textContent = restaurantOwnerCount;

  document.getElementById('avg-rating').textContent = Number(adminStats.avg_rating || 0).toFixed(1);
  document.getElementById('total-favorites').textContent = adminStats.total_favorites ?? 0;
  document.getElementById('total-wishlist').textContent = adminStats.total_wishlist ?? 0;
}

function renderRestaurantsTable() {
  const tbody = document.getElementById('restaurants-table-body');
  tbody.innerHTML = window.state.restaurants.map(r => `
    <tr>
      <td>${r.name}</td>
      <td>${r.city}</td>
      <td>${r.cuisine}</td>
      <td>${r.avg.toFixed(1)} ⭐</td>
      <td>${r.review_count ?? r.reviews.length}</td>
      <td>
        <button class="btn-admin btn-edit" onclick="editRestaurant(${r.id})">Edit</button>
        <button class="btn-admin btn-delete" onclick="deleteRestaurant(${r.id})">Delete</button>
      </td>
    </tr>
  `).join('');
}

function renderRestaurantOwnersTable() {
  const tbody = document.getElementById('restaurant-owners-table-body');

  // Get all restaurant owners from localStorage (in a real app, this would be from a database)
  const restaurantOwners = [];
  for (let i = 0; i < localStorage.length; i++) {
    const key = localStorage.key(i);
    if (key && key.startsWith('appetitus_restaurant')) {
      try {
        const restaurant = JSON.parse(localStorage.getItem(key));
        restaurantOwners.push(restaurant);
      } catch (e) {
        // Skip invalid data
      }
    }
  }

  if (restaurantOwners.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:2rem;">No restaurant owners registered yet</td></tr>';
    return;
  }

  tbody.innerHTML = restaurantOwners.map(r => `
    <tr>
      <td>${r.name}</td>
      <td>${r.ownerName}</td>
      <td>${r.email}</td>
      <td>${r.city}</td>
      <td>${getCuisineName(r.cuisine)}</td>
      <td>${new Date(r.joinDate).toLocaleDateString()}</td>
      <td>
        <button class="btn-admin btn-edit" onclick="editRestaurantOwner('${r.email}')">Edit</button>
        <button class="btn-admin btn-delete" onclick="deleteRestaurantOwner('${r.email}')">Delete</button>
      </td>
    </tr>
  `).join('');
}

function renderReviewsTable() {
  const tbody = document.getElementById('reviews-table-body');
  tbody.innerHTML = window.state.userReviews.map(review => {
    const photosHtml = review.photos && review.photos.length ?
      `<div style="display:flex;gap:2px;">${review.photos.slice(0,3).map(photo =>
        `<img src="${photo.dataUrl}" alt="${photo.name}" style="width:30px;height:30px;object-fit:cover;border-radius:2px;border:1px solid var(--border);" />`
      ).join('')}${review.photos.length > 3 ? `<span style="font-size:10px;color:var(--text-muted);">+${review.photos.length - 3}</span>` : ''}</div>` :
      '<span style="color:var(--text-muted);">None</span>';
    return `
      <tr>
        <td>${review.restaurant_name || 'Unknown'}</td>
        <td>${review.user_name || review.author || 'Unknown'}</td>
        <td>${review.rating} ⭐</td>
        <td>${review.text.substring(0, 50)}${review.text.length > 50 ? '...' : ''}</td>
        <td>${photosHtml}</td>
        <td>${new Date(review.date).toLocaleDateString()}</td>
        <td>
          <button class="btn-admin btn-delete" onclick="deleteReview(${review.id})">Delete</button>
        </td>
      </tr>
    `;
  }).join('');
}

function renderUsersTable() {
  const tbody = document.getElementById('users-table-body');
  tbody.innerHTML = users.map(u => `
    <tr>
      <td>${u.nom}</td>
      <td>${u.email}</td>
      <td>-</td>
      <td>${u.review_count || 0}</td>
      <td>
        <button class="btn-admin btn-delete" onclick="deleteUser(${u.id})">Delete</button>
      </td>
    </tr>
  `).join('');
}

function renderFavoritesTable() {
  const tbody = document.getElementById('favorites-table-body');
  tbody.innerHTML = adminFavorites.map(row => `
      <tr>
        <td>${row.user_name}</td>
        <td>${row.favorites_count}</td>
        <td>${row.wishlist_count}</td>
        <td style="max-width:200px;word-wrap:break-word;">${row.favorites_list}</td>
        <td style="max-width:200px;word-wrap:break-word;">${row.wishlist_list}</td>
      </tr>
  `).join('');
}

function openAddRestaurantModal() {
  currentEditingId = null;
  document.getElementById('modal-title').textContent = 'Add Restaurant';
  document.getElementById('restaurant-form').reset();
  document.getElementById('restaurant-modal').style.display = 'block';
}

function editRestaurant(id) {
  const restaurant = window.state.restaurants.find(r => r.id === id);
  if (!restaurant) return;

  currentEditingId = id;
  document.getElementById('modal-title').textContent = 'Edit Restaurant';
  document.getElementById('restaurant-name').value = restaurant.name;
  document.getElementById('restaurant-city').value = restaurant.city;
  document.getElementById('restaurant-category').value = restaurant.category;
  document.getElementById('restaurant-price').value = restaurant.priceRange;
  document.getElementById('restaurant-image').value = restaurant.image;
  document.getElementById('restaurant-tags').value = restaurant.tags.join(', ');
  document.getElementById('restaurant-modal').style.display = 'block';
}

function closeModal() {
  document.getElementById('restaurant-modal').style.display = 'none';
  currentEditingId = null;
}

document.getElementById('restaurant-form').addEventListener('submit', async (e) => {
  e.preventDefault();

  const restaurant = {
    id: currentEditingId,
    name: document.getElementById('restaurant-name').value,
    city: document.getElementById('restaurant-city').value,
    category: document.getElementById('restaurant-category').value,
    priceRange: document.getElementById('restaurant-price').value,
    image: document.getElementById('restaurant-image').value,
    tags: document.getElementById('restaurant-tags').value.split(',').map(t => t.trim()),
    cuisine: getCuisineFromCategory(document.getElementById('restaurant-category').value),
    avg: 0,
    reviews: [],
    score: 0
  };

  try {
    await adminRequest('save_restaurant', restaurant);
    toast(currentEditingId ? 'Restaurant updated successfully' : 'Restaurant added successfully');
    closeModal();
    await loadAdminDashboard();
  } catch (err) {
    toast(err.message, 'error');
  }
});

function getCuisineFromCategory(category) {
  const mapping = {
    'gastronomique': 'French',
    'asiatique': 'Asian',
    'italien': 'Italian',
    'fruits-de-mer': 'Seafood',
    'street-food': 'Street Food',
    'brasserie': 'Pub',
    'africaine': 'African'
  };
  return mapping[category] || 'Other';
}

async function deleteRestaurant(id) {
  if (!confirm('Are you sure you want to delete this restaurant?')) return;
  try {
    await adminRequest('delete_restaurant', { id });
    toast('Restaurant deleted successfully');
    await loadAdminDashboard();
  } catch (err) {
    toast(err.message, 'error');
  }
}

async function deleteReview(id) {
  if (!confirm('Are you sure you want to delete this review?')) return;
  try {
    await adminRequest('delete_review', { id });
    toast('Review deleted successfully');
    await loadAdminDashboard();
  } catch (err) {
    toast(err.message, 'error');
  }
}

async function deleteUser(id) {
  if (!confirm('Are you sure you want to delete this user?')) return;
  try {
    await adminRequest('delete_user', { id });
    toast('User deleted successfully');
    await loadAdminDashboard();
  } catch (err) {
    toast(err.message, 'error');
  }
}

function editRestaurantOwner(email) {
  // In a real app, this would open an edit modal
  toast('Edit functionality coming soon!', 'info');
}

function deleteRestaurantOwner(email) {
  if (!confirm('Are you sure you want to delete this restaurant owner? This will remove their access to the dashboard.')) return;

  // Remove from localStorage
  localStorage.removeItem('appetitus_restaurant');

  toast('Restaurant owner deleted successfully');
  renderRestaurantOwnersTable();
  updateOverview();
}
</script>

</body>
</html>



