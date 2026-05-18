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
// Get current user data - in a real app, this would come from a backend
const users = [];
const currentUser = getCurrentUser();
if (currentUser) {
  users.push(currentUser);
} else {
  // Fallback mock data for demo
  users.push(
    { id: 1, name: "John Doe", email: "john@example.com", joinDate: "2024-01-01", reviewCount: 2, favorites: [1, 2], wishlist: [3, 4] },
    { id: 2, name: "Jane Smith", email: "jane@example.com", joinDate: "2024-01-05", reviewCount: 1, favorites: [2], wishlist: [1, 5] },
    { id: 3, name: "Alice Johnson", email: "alice@example.com", joinDate: "2024-01-03", reviewCount: 2, favorites: [], wishlist: [2, 6] },
    { id: 4, name: "Bob Wilson", email: "bob@example.com", joinDate: "2024-01-08", reviewCount: 1, favorites: [4], wishlist: [7] },
    { id: 5, name: "Charlie Brown", email: "charlie@example.com", joinDate: "2024-01-10", reviewCount: 1, favorites: [3, 5], wishlist: [] }
  );
}

let currentEditingId = null;

document.addEventListener('DOMContentLoaded', () => {
  updateOverview();
  renderRestaurantsTable();
  renderRestaurantOwnersTable();
  renderReviewsTable();
  renderUsersTable();
  renderFavoritesTable();
});

function showSection(section) {
  document.querySelectorAll('.admin-section').forEach(s => s.style.display = 'none');
  document.getElementById(section + '-section').style.display = 'block';
  document.querySelectorAll('.admin-nav a').forEach(a => a.classList.remove('active'));
  document.querySelector(`.admin-nav a[href="#${section}"]`).classList.add('active');
}

function updateOverview() {
  document.getElementById('total-restaurants').textContent = window.state.restaurants.length;
  document.getElementById('total-reviews').textContent = window.state.userReviews.length;
  document.getElementById('total-users').textContent = users.length;

  // Count restaurant owners
  let restaurantOwnerCount = 0;
  for (let i = 0; i < localStorage.length; i++) {
    const key = localStorage.key(i);
    if (key && key.startsWith('appetitus_restaurant')) {
      restaurantOwnerCount++;
    }
  }
  document.getElementById('total-restaurant-owners').textContent = restaurantOwnerCount;

  const avgRating = window.state.restaurants.reduce((sum, r) => sum + r.avg, 0) / window.state.restaurants.length;
  document.getElementById('avg-rating').textContent = avgRating.toFixed(1);

  // Calculate total favorites and wishlist items
  const totalFavorites = users.reduce((sum, user) => sum + (user.favorites ? user.favorites.length : 0), 0);
  const totalWishlist = users.reduce((sum, user) => sum + (user.wishlist ? user.wishlist.length : 0), 0);

  document.getElementById('total-favorites').textContent = totalFavorites;
  document.getElementById('total-wishlist').textContent = totalWishlist;
}

function renderRestaurantsTable() {
  const tbody = document.getElementById('restaurants-table-body');
  tbody.innerHTML = window.state.restaurants.map(r => `
    <tr>
      <td>${r.name}</td>
      <td>${r.city}</td>
      <td>${r.cuisine}</td>
      <td>${r.avg.toFixed(1)} ⭐</td>
      <td>${r.reviews.length}</td>
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
    const restaurant = window.state.restaurants.find(r => r.id === review.restaurantId);
    const user = users.find(u => u.id === review.userId);
    const photosHtml = review.photos && review.photos.length ?
      `<div style="display:flex;gap:2px;">${review.photos.slice(0,3).map(photo =>
        `<img src="${photo.dataUrl}" alt="${photo.name}" style="width:30px;height:30px;object-fit:cover;border-radius:2px;border:1px solid var(--border);" />`
      ).join('')}${review.photos.length > 3 ? `<span style="font-size:10px;color:var(--text-muted);">+${review.photos.length - 3}</span>` : ''}</div>` :
      '<span style="color:var(--text-muted);">None</span>';
    return `
      <tr>
        <td>${restaurant ? restaurant.name : 'Unknown'}</td>
        <td>${user ? user.name : 'Unknown'}</td>
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
      <td>${u.name}</td>
      <td>${u.email}</td>
      <td>${new Date(u.joinDate).toLocaleDateString()}</td>
      <td>${u.reviewCount}</td>
      <td>
        <button class="btn-admin btn-delete" onclick="deleteUser(${u.id})">Delete</button>
      </td>
    </tr>
  `).join('');
}

function renderFavoritesTable() {
  const tbody = document.getElementById('favorites-table-body');
  tbody.innerHTML = users.map(u => {
    const favoritesCount = u.favorites ? u.favorites.length : 0;
    const wishlistCount = u.wishlist ? u.wishlist.length : 0;

    const favoritesList = u.favorites ? u.favorites.map(id => {
      const restaurant = window.state.restaurants.find(r => r.id === id);
      return restaurant ? restaurant.name : `ID:${id}`;
    }).join(', ') : 'None';

    const wishlistList = u.wishlist ? u.wishlist.map(id => {
      const restaurant = window.state.restaurants.find(r => r.id === id);
      return restaurant ? restaurant.name : `ID:${id}`;
    }).join(', ') : 'None';

    return `
      <tr>
        <td>${u.name}</td>
        <td>${favoritesCount}</td>
        <td>${wishlistCount}</td>
        <td style="max-width:200px;word-wrap:break-word;">${favoritesList}</td>
        <td style="max-width:200px;word-wrap:break-word;">${wishlistList}</td>
      </tr>
    `;
  }).join('');
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

document.getElementById('restaurant-form').addEventListener('submit', (e) => {
  e.preventDefault();

  const restaurant = {
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

  if (currentEditingId) {
    // Edit existing
    const index = window.state.restaurants.findIndex(r => r.id === currentEditingId);
    restaurant.id = currentEditingId;
    restaurant.avg = window.state.restaurants[index].avg;
    restaurant.reviews = window.state.restaurants[index].reviews;
    restaurant.score = window.state.restaurants[index].score;
    window.state.restaurants[index] = restaurant;
  } else {
    // Add new
    restaurant.id = Math.max(...window.state.restaurants.map(r => r.id)) + 1;
    window.state.restaurants.push(restaurant);
  }

  updateOverview();
  renderRestaurantsTable();
  closeModal();
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

function deleteRestaurant(id) {
  if (!confirm('Are you sure you want to delete this restaurant?')) return;
  window.state.restaurants = window.state.restaurants.filter(r => r.id !== id);
  // Also remove associated reviews
  window.state.userReviews = window.state.userReviews.filter(review => review.restaurantId !== id);
  updateOverview();
  renderRestaurantsTable();
  renderReviewsTable();
}

function deleteReview(id) {
  if (!confirm('Are you sure you want to delete this review?')) return;
  window.state.userReviews = window.state.userReviews.filter(review => review.id !== id);
  // Update restaurant stats
  window.state.restaurants.forEach(r => {
    r.reviews = window.state.userReviews.filter(review => review.restaurantId === r.id);
    r.avg = r.reviews.length ? r.reviews.reduce((sum, review) => sum + review.rating, 0) / r.reviews.length : 0;
    r.score = Math.round(r.avg * 20); // Simple scoring
  });
  updateOverview();
  renderReviewsTable();
  renderRestaurantsTable();
}

function deleteUser(id) {
  if (!confirm('Are you sure you want to delete this user?')) return;
  // Remove user
  const index = users.findIndex(u => u.id === id);
  users.splice(index, 1);
  // Remove their reviews
  window.state.userReviews = window.state.userReviews.filter(review => review.userId !== id);
  // Update restaurant stats
  window.state.restaurants.forEach(r => {
    r.reviews = window.state.userReviews.filter(review => review.restaurantId === r.id);
    r.avg = r.reviews.length ? r.reviews.reduce((sum, review) => sum + review.rating, 0) / r.reviews.length : 0;
    r.score = Math.round(r.avg * 20);
  });
  updateOverview();
  renderUsersTable();
  renderReviewsTable();
  renderRestaurantsTable();
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



