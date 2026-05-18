<?php // Converted to PHP ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restaurant Dashboard — Appetitus</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .dashboard-header {
      background: linear-gradient(135deg, var(--accent), var(--accent-light));
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
    }
    .dashboard-header .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .dashboard-welcome {
      font-size: 1.5rem;
      font-weight: 600;
    }
    .dashboard-subtitle {
      opacity: 0.9;
      font-size: 0.9rem;
    }
    .dashboard-nav {
      background: var(--bg-surface);
      border-bottom: 1px solid var(--border);
      padding: 0;
    }
    .dashboard-nav .container {
      display: flex;
      gap: 2rem;
      padding: 1rem 0;
    }
    .dashboard-nav a {
      color: var(--text-secondary);
      text-decoration: none;
      padding: 0.5rem 1rem;
      border-radius: var(--radius-sm);
      transition: var(--transition);
    }
    .dashboard-nav a.active {
      background: var(--accent);
      color: white;
    }
    .dashboard-nav a:hover {
      background: var(--accent-light);
      color: var(--text-primary);
    }
    .dashboard-section {
      margin-bottom: 3rem;
    }
    .metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    .metric-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 1.5rem;
      text-align: center;
    }
    .metric-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--accent);
      margin-bottom: 0.5rem;
    }
    .metric-label {
      color: var(--text-muted);
      font-size: 0.9rem;
    }
    .chart-container {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .chart-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--text-primary);
    }
    .reviews-list {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      overflow: hidden;
    }
    .review-item {
      padding: 1rem;
      border-bottom: 1px solid var(--border);
    }
    .review-item:last-child {
      border-bottom: none;
    }
    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }
    .review-rating {
      font-weight: 600;
      color: var(--accent);
    }
    .review-date {
      color: var(--text-muted);
      font-size: 0.8rem;
    }
    .review-text {
      color: var(--text-secondary);
      line-height: 1.4;
    }
    .promotions-list {
      display: grid;
      gap: 1rem;
    }
    .promotion-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 1.5rem;
      position: relative;
    }
    .promotion-card.active {
      border-color: var(--accent);
      box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.1);
    }
    .promotion-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
    }
    .promotion-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-primary);
      margin: 0;
    }
    .promotion-status {
      padding: 0.25rem 0.75rem;
      border-radius: var(--radius-sm);
      font-size: 0.8rem;
      font-weight: 500;
      text-transform: uppercase;
    }
    .promotion-status.active {
      background: #d4edda;
      color: #155724;
    }
    .promotion-status.expired {
      background: #f8d7da;
      color: #721c24;
    }
    .promotion-status.upcoming {
      background: #fff3cd;
      color: #856404;
    }
    .promotion-description {
      color: var(--text-secondary);
      margin-bottom: 1rem;
      line-height: 1.4;
    }
    .promotion-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 1rem;
      margin-bottom: 1rem;
    }
    .promotion-detail {
      font-size: 0.9rem;
    }
    .promotion-detail-label {
      color: var(--text-muted);
      font-weight: 500;
    }
    .promotion-detail-value {
      color: var(--text-primary);
      margin-top: 0.25rem;
    }
    .promotion-actions {
      display: flex;
      gap: 0.5rem;
      justify-content: flex-end;
    }
    .btn-promotion {
      padding: 0.4rem 0.8rem;
      border: none;
      border-radius: var(--radius-sm);
      cursor: pointer;
      font-size: 0.85rem;
      transition: var(--transition);
    }
    .btn-edit-promotion {
      background: var(--accent);
      color: white;
    }
    .btn-edit-promotion:hover {
      background: #d66f4a;
    }
    .btn-delete-promotion {
      background: #e74c3c;
      color: white;
    }
    .btn-delete-promotion:hover {
      background: #c0392b;
    }
    .insights-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
    }
    .insight-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 1.5rem;
    }
    .insight-title {
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--text-primary);
    }
    .insight-list {
      list-style: none;
      padding: 0;
    }
    .insight-list li {
      padding: 0.5rem 0;
      border-bottom: 1px solid var(--border-light);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .insight-list li:last-child {
      border-bottom: none;
    }
    .tag-count {
      background: var(--accent-light);
      color: var(--accent);
      padding: 0.2rem 0.5rem;
      border-radius: var(--radius-sm);
      font-size: 0.8rem;
      font-weight: 500;
    }
    .logout-btn {
      background: #e74c3c;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: var(--radius-sm);
      cursor: pointer;
      font-size: 0.9rem;
      transition: var(--transition);
    }
    .logout-btn:hover {
      background: #c0392b;
    }
  </style>
</head>
<body>

<nav id="navbar">
  <a class="nav-logo" href="index.php"><span class="logo-icon">🍽️</span> Appetitus</a>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="explore.php">Explore</a></li>
  </ul>
  <div class="nav-actions">
    <button class="logout-btn" onclick="logoutRestaurant()">Logout</button>
  </div>
</nav>

<div class="dashboard-header">
  <div class="container">
    <div>
      <div class="dashboard-welcome" id="restaurant-name">Loading...</div>
      <div class="dashboard-subtitle">Restaurant Analytics Dashboard</div>
    </div>
    <div>
      <div style="text-align: right; opacity: 0.9;">
        <div>Last updated: <span id="last-updated">Just now</span></div>
      </div>
    </div>
  </div>
</div>

<div class="dashboard-nav">
  <div class="container">
    <a href="#overview" class="active" onclick="showSection('overview')">Overview</a>
    <a href="#reviews" onclick="showSection('reviews')">Reviews</a>
    <a href="#promotions" onclick="showSection('promotions')">Promotions</a>
    <a href="#insights" onclick="showSection('insights')">Insights</a>
    <a href="#settings" onclick="showSection('settings')">Settings</a>
  </div>
</div>

<div class="container">

  <!-- OVERVIEW -->
  <div id="overview-section" class="dashboard-section">
    <div class="metrics-grid">
      <div class="metric-card">
        <div class="metric-value" id="total-reviews">0</div>
        <div class="metric-label">Total Reviews</div>
      </div>
      <div class="metric-card">
        <div class="metric-value" id="avg-rating">0.0</div>
        <div class="metric-label">Average Rating</div>
      </div>
      <div class="metric-card">
        <div class="metric-value" id="rating-trend">+0.0</div>
        <div class="metric-label">Rating Trend (30 days)</div>
      </div>
      <div class="metric-card">
        <div class="metric-value" id="review-growth">0%</div>
        <div class="metric-label">Review Growth</div>
      </div>
    </div>

    <div class="chart-container">
      <div class="chart-title">Rating Trends Over Time</div>
      <canvas id="ratingChart" width="400" height="200"></canvas>
    </div>

    <div class="chart-container">
      <div class="chart-title">Reviews by Day of Week</div>
      <canvas id="weeklyChart" width="400" height="200"></canvas>
    </div>
  </div>

  <!-- REVIEWS -->
  <div id="reviews-section" class="dashboard-section" style="display:none;">
    <h2>Recent Reviews</h2>
    <div class="reviews-list" id="reviews-list">
      <!-- Reviews will be populated here -->
    </div>
  </div>

  <!-- PROMOTIONS -->
  <div id="promotions-section" class="dashboard-section" style="display:none;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
      <h2>Manage Promotions</h2>
      <button class="btn btn-primary" onclick="openAddPromotionModal()">+ Create Promotion</button>
    </div>

    <div class="promotions-list" id="promotions-list">
      <!-- Promotions will be populated here -->
    </div>
  </div>

  <!-- INSIGHTS -->
  <div id="insights-section" class="dashboard-section" style="display:none;">
    <h2>Customer Insights</h2>
    <div class="insights-grid">
      <div class="insight-card">
        <div class="insight-title">Most Mentioned Tags</div>
        <ul class="insight-list" id="popular-tags">
          <!-- Tags will be populated here -->
        </ul>
      </div>
      <div class="insight-card">
        <div class="insight-title">Common Feedback Themes</div>
        <ul class="insight-list" id="feedback-themes">
          <!-- Feedback themes will be populated here -->
        </ul>
      </div>
      <div class="insight-card">
        <div class="insight-title">Peak Review Times</div>
        <ul class="insight-list" id="peak-times">
          <!-- Peak times will be populated here -->
        </ul>
      </div>
      <div class="insight-card">
        <div class="insight-title">Rating Distribution</div>
        <canvas id="ratingDistributionChart" width="300" height="200"></canvas>
      </div>
    </div>
  </div>

  <!-- SETTINGS -->
  <div id="settings-section" class="dashboard-section" style="display:none;">
    <h2>Restaurant Settings</h2>
    <div class="insight-card">
      <div class="insight-title">Restaurant Information</div>
      <div style="margin-bottom: 1rem;">
        <strong>Name:</strong> <span id="settings-name"></span>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>Owner:</strong> <span id="settings-owner"></span>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>Email:</strong> <span id="settings-email"></span>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>Phone:</strong> <span id="settings-phone"></span>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>City:</strong> <span id="settings-city"></span>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>Cuisine:</strong> <span id="settings-cuisine"></span>
      </div>
      <div style="margin-bottom: 1rem;">
        <strong>Joined:</strong> <span id="settings-joined"></span>
      </div>
    </div>
  </div>

</div>

<!-- PROMOTION MODAL -->
<div id="promotion-modal" class="modal">
  <div class="modal-content">
    <h2 id="promotion-modal-title">Create Promotion</h2>
    <form id="promotion-form">
      <div class="form-group">
        <label class="form-label">Promotion Title</label>
        <input class="form-input" type="text" id="promotion-title" required placeholder="e.g. 20% Off Lunch Special">
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-input" id="promotion-description" required placeholder="Describe your promotion..." rows="3"></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Discount Type</label>
        <select class="form-input" id="promotion-type" required>
          <option value="percentage">Percentage Off</option>
          <option value="fixed">Fixed Amount Off</option>
          <option value="buy-one-get-one">Buy One Get One</option>
          <option value="free-item">Free Item</option>
          <option value="special-offer">Special Offer</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Discount Value</label>
        <input class="form-input" type="text" id="promotion-value" placeholder="e.g. 20 or $10 or BOGO">
      </div>
      <div class="form-group">
        <label class="form-label">Start Date</label>
        <input class="form-input" type="datetime-local" id="promotion-start" required>
      </div>
      <div class="form-group">
        <label class="form-label">End Date</label>
        <input class="form-input" type="datetime-local" id="promotion-end" required>
      </div>
      <div class="form-group">
        <label class="form-label">Terms & Conditions</label>
        <textarea class="form-input" id="promotion-terms" placeholder="Any terms and conditions..." rows="2"></textarea>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" onclick="closePromotionModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Promotion</button>
      </div>
    </form>
  </div>
</div>

<script src="app.js"></script>
<script>
let currentRestaurant = null;
let ratingChart = null;
let weeklyChart = null;
let ratingDistributionChart = null;

document.addEventListener('DOMContentLoaded', () => {
  loadRestaurantData();
  showSection('overview');
});

function loadRestaurantData() {
  currentRestaurant = JSON.parse(localStorage.getItem('appetitus_restaurant') || 'null');
  if (!currentRestaurant) {
    window.location.href = 'restaurant-login.php';
    return;
  }

  // Update header
  document.getElementById('restaurant-name').textContent = currentRestaurant.name;

  // Load analytics data
  updateAnalytics();
  renderCharts();
  renderRecentReviews();
  renderPromotions();
  renderInsights();
  renderSettings();
}

function updateAnalytics() {
  // Find restaurant in global state
  const restaurant = window.state.restaurants.find(r => r.name.toLowerCase() === currentRestaurant.name.toLowerCase());
  if (!restaurant) {
    // If restaurant not found in state, create mock data
    document.getElementById('total-reviews').textContent = '0';
    document.getElementById('avg-rating').textContent = '0.0';
    document.getElementById('rating-trend').textContent = '+0.0';
    document.getElementById('review-growth').textContent = '0%';
    return;
  }

  document.getElementById('total-reviews').textContent = restaurant.reviews.length;
  document.getElementById('avg-rating').textContent = restaurant.avg.toFixed(1);

  // Calculate trend (mock data for demo)
  const trend = (Math.random() - 0.5) * 0.4;
  document.getElementById('rating-trend').textContent = (trend >= 0 ? '+' : '') + trend.toFixed(1);

  // Calculate growth (mock data)
  const growth = Math.floor(Math.random() * 40) - 10;
  document.getElementById('review-growth').textContent = (growth >= 0 ? '+' : '') + growth + '%';
}

function renderCharts() {
  const restaurant = window.state.restaurants.find(r => r.name.toLowerCase() === currentRestaurant.name.toLowerCase());
  if (!restaurant) return;

  // Rating trends chart
  const ctx1 = document.getElementById('ratingChart').getContext('2d');
  const ratingData = generateRatingTrendData(restaurant.reviews);

  if (ratingChart) ratingChart.destroy();
  ratingChart = new Chart(ctx1, {
    type: 'line',
    data: {
      labels: ratingData.labels,
      datasets: [{
        label: 'Average Rating',
        data: ratingData.values,
        borderColor: '#ff6b35',
        backgroundColor: 'rgba(255, 107, 53, 0.1)',
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          max: 5
        }
      }
    }
  });

  // Weekly distribution chart
  const ctx2 = document.getElementById('weeklyChart').getContext('2d');
  const weeklyData = generateWeeklyData(restaurant.reviews);

  if (weeklyChart) weeklyChart.destroy();
  weeklyChart = new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: 'Reviews',
        data: weeklyData,
        backgroundColor: '#ff6b35',
        borderRadius: 4
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Rating distribution chart
  const ctx3 = document.getElementById('ratingDistributionChart').getContext('2d');
  const distributionData = generateRatingDistribution(restaurant.reviews);

  if (ratingDistributionChart) ratingDistributionChart.destroy();
  ratingDistributionChart = new Chart(ctx3, {
    type: 'doughnut',
    data: {
      labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
      datasets: [{
        data: distributionData,
        backgroundColor: [
          '#e74c3c',
          '#f39c12',
          '#f1c40f',
          '#27ae60',
          '#2ecc71'
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
}

function generateRatingTrendData(reviews) {
  // Generate mock trend data for the last 30 days
  const labels = [];
  const values = [];
  const now = new Date();

  for (let i = 29; i >= 0; i--) {
    const date = new Date(now);
    date.setDate(date.getDate() - i);
    labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));

    // Calculate average rating for that day (mock data)
    const dayReviews = reviews.filter(r => {
      const reviewDate = new Date(r.date);
      return reviewDate.toDateString() === date.toDateString();
    });

    if (dayReviews.length > 0) {
      const avg = dayReviews.reduce((sum, r) => sum + r.rating, 0) / dayReviews.length;
      values.push(avg);
    } else {
      values.push(null);
    }
  }

  return { labels, values };
}

function generateWeeklyData(reviews) {
  const weekly = [0, 0, 0, 0, 0, 0, 0]; // Mon to Sun

  reviews.forEach(review => {
    const date = new Date(review.date);
    const day = date.getDay(); // 0 = Sunday, 1 = Monday, etc.
    const index = day === 0 ? 6 : day - 1; // Convert to Mon-Sun order
    weekly[index]++;
  });

  return weekly;
}

function generateRatingDistribution(reviews) {
  const distribution = [0, 0, 0, 0, 0];

  reviews.forEach(review => {
    const rating = Math.floor(review.rating);
    if (rating >= 1 && rating <= 5) {
      distribution[rating - 1]++;
    }
  });

  return distribution;
}

function renderRecentReviews() {
  const restaurant = window.state.restaurants.find(r => r.name.toLowerCase() === currentRestaurant.name.toLowerCase());
  if (!restaurant) {
    document.getElementById('reviews-list').innerHTML = '<div class="review-item">No reviews yet</div>';
    return;
  }

  const recentReviews = restaurant.reviews.slice(-10).reverse(); // Last 10 reviews

  const reviewsHtml = recentReviews.map(review => {
    const user = { name: 'Anonymous User' }; // In real app, get user data
    const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);

    return `
      <div class="review-item">
        <div class="review-header">
          <div class="review-rating">${stars} ${review.rating}/5</div>
          <div class="review-date">${new Date(review.date).toLocaleDateString()}</div>
        </div>
        <div class="review-text">${review.text}</div>
      </div>
    `;
  }).join('');

  document.getElementById('reviews-list').innerHTML = reviewsHtml || '<div class="review-item">No reviews yet</div>';
}

function renderInsights() {
  const restaurant = window.state.restaurants.find(r => r.name.toLowerCase() === currentRestaurant.name.toLowerCase());
  if (!restaurant) return;

  // Popular tags
  const tagCounts = {};
  restaurant.reviews.forEach(review => {
    // Extract tags from review text (simple keyword matching)
    const keywords = ['food', 'service', 'ambiance', 'price', 'quality', 'fresh', 'delicious', 'clean', 'friendly', 'atmosphere'];
    keywords.forEach(keyword => {
      if (review.text.toLowerCase().includes(keyword)) {
        tagCounts[keyword] = (tagCounts[keyword] || 0) + 1;
      }
    });
  });

  const sortedTags = Object.entries(tagCounts)
    .sort(([,a], [,b]) => b - a)
    .slice(0, 5);

  const tagsHtml = sortedTags.map(([tag, count]) => `
    <li>
      <span style="text-transform: capitalize;">${tag}</span>
      <span class="tag-count">${count} mentions</span>
    </li>
  `).join('');

  document.getElementById('popular-tags').innerHTML = tagsHtml || '<li>No tag data available</li>';

  // Feedback themes (mock data)
  const themes = [
    { theme: 'Positive food quality', percentage: 75 },
    { theme: 'Good service', percentage: 68 },
    { theme: 'Great ambiance', percentage: 55 },
    { theme: 'Value for money', percentage: 45 },
    { theme: 'Would recommend', percentage: 40 }
  ];

  const themesHtml = themes.map(theme => `
    <li>
      <span>${theme.theme}</span>
      <span class="tag-count">${theme.percentage}%</span>
    </li>
  `).join('');

  document.getElementById('feedback-themes').innerHTML = themesHtml;

  // Peak times (mock data)
  const peakTimes = [
    { time: 'Friday 7-9 PM', reviews: 12 },
    { time: 'Saturday 8-10 PM', reviews: 10 },
    { time: 'Thursday 6-8 PM', reviews: 8 },
    { time: 'Sunday 12-2 PM', reviews: 6 },
    { time: 'Wednesday 7-9 PM', reviews: 5 }
  ];

  const peakTimesHtml = peakTimes.map(time => `
    <li>
      <span>${time.time}</span>
      <span class="tag-count">${time.reviews} reviews</span>
    </li>
  `).join('');

  document.getElementById('peak-times').innerHTML = peakTimesHtml;
}

function renderSettings() {
  document.getElementById('settings-name').textContent = currentRestaurant.name;
  document.getElementById('settings-owner').textContent = currentRestaurant.ownerName;
  document.getElementById('settings-email').textContent = currentRestaurant.email;
  document.getElementById('settings-phone').textContent = currentRestaurant.phone;
  document.getElementById('settings-city').textContent = currentRestaurant.city;
  document.getElementById('settings-cuisine').textContent = getCuisineName(currentRestaurant.cuisine);
  document.getElementById('settings-joined').textContent = new Date(currentRestaurant.joinDate).toLocaleDateString();
}

function getCuisineName(cuisineCode) {
  const mapping = {
    'gastronomique': 'Fine Dining',
    'asiatique': 'Asian',
    'italien': 'Italian',
    'fruits-de-mer': 'Seafood',
    'street-food': 'Street Food',
    'brasserie': 'Pub',
    'africaine': 'African'
  };
  return mapping[cuisineCode] || cuisineCode;
}

function showSection(section) {
  document.querySelectorAll('.dashboard-section').forEach(s => s.style.display = 'none');
  document.getElementById(section + '-section').style.display = 'block';
  document.querySelectorAll('.dashboard-nav a').forEach(a => a.classList.remove('active'));
  document.querySelector(`.dashboard-nav a[href="#${section}"]`).classList.add('active');
}

function renderPromotions() {
  if (!currentRestaurant.promotions) {
    currentRestaurant.promotions = [];
    localStorage.setItem('appetitus_restaurant', JSON.stringify(currentRestaurant));
  }

  const promotionsHtml = currentRestaurant.promotions.map((promotion, index) => {
    const now = new Date();
    const startDate = new Date(promotion.startDate);
    const endDate = new Date(promotion.endDate);
    let status = 'upcoming';
    let statusText = 'Upcoming';

    if (now >= startDate && now <= endDate) {
      status = 'active';
      statusText = 'Active';
    } else if (now > endDate) {
      status = 'expired';
      statusText = 'Expired';
    }

    return `
      <div class="promotion-card ${status === 'active' ? 'active' : ''}">
        <div class="promotion-header">
          <h3 class="promotion-title">${promotion.title}</h3>
          <span class="promotion-status ${status}">${statusText}</span>
        </div>
        <div class="promotion-description">${promotion.description}</div>
        <div class="promotion-details">
          <div class="promotion-detail">
            <div class="promotion-detail-label">Type</div>
            <div class="promotion-detail-value">${promotion.type.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase())}</div>
          </div>
          <div class="promotion-detail">
            <div class="promotion-detail-label">Value</div>
            <div class="promotion-detail-value">${promotion.value || 'N/A'}</div>
          </div>
          <div class="promotion-detail">
            <div class="promotion-detail-label">Start Date</div>
            <div class="promotion-detail-value">${new Date(promotion.startDate).toLocaleDateString()}</div>
          </div>
          <div class="promotion-detail">
            <div class="promotion-detail-label">End Date</div>
            <div class="promotion-detail-value">${new Date(promotion.endDate).toLocaleDateString()}</div>
          </div>
        </div>
        <div class="promotion-actions">
          <button class="btn-promotion btn-edit-promotion" onclick="editPromotion(${index})">Edit</button>
          <button class="btn-promotion btn-delete-promotion" onclick="deletePromotion(${index})">Delete</button>
        </div>
      </div>
    `;
  }).join('');

  document.getElementById('promotions-list').innerHTML = promotionsHtml || '<div style="text-align:center; padding:2rem; color:var(--text-muted);">No promotions created yet</div>';
}

function openAddPromotionModal() {
  document.getElementById('promotion-modal-title').textContent = 'Create Promotion';
  document.getElementById('promotion-form').reset();
  document.getElementById('promotion-modal').style.display = 'block';
}

function closePromotionModal() {
  document.getElementById('promotion-modal').style.display = 'none';
}

function editPromotion(index) {
  const promotion = currentRestaurant.promotions[index];
  if (!promotion) return;

  document.getElementById('promotion-modal-title').textContent = 'Edit Promotion';
  document.getElementById('promotion-title').value = promotion.title;
  document.getElementById('promotion-description').value = promotion.description;
  document.getElementById('promotion-type').value = promotion.type;
  document.getElementById('promotion-value').value = promotion.value || '';
  document.getElementById('promotion-start').value = new Date(promotion.startDate).toISOString().slice(0, 16);
  document.getElementById('promotion-end').value = new Date(promotion.endDate).toISOString().slice(0, 16);
  document.getElementById('promotion-terms').value = promotion.terms || '';

  document.getElementById('promotion-modal').style.display = 'block';

  // Store the index for editing
  document.getElementById('promotion-form').dataset.editIndex = index;
}

function deletePromotion(index) {
  if (!confirm('Are you sure you want to delete this promotion?')) return;

  currentRestaurant.promotions.splice(index, 1);
  localStorage.setItem('appetitus_restaurant', JSON.stringify(currentRestaurant));
  renderPromotions();
  toast('Promotion deleted successfully');
}

// Handle promotion form submission
document.getElementById('promotion-form').addEventListener('submit', (e) => {
  e.preventDefault();

  const promotion = {
    title: document.getElementById('promotion-title').value,
    description: document.getElementById('promotion-description').value,
    type: document.getElementById('promotion-type').value,
    value: document.getElementById('promotion-value').value,
    startDate: document.getElementById('promotion-start').value,
    endDate: document.getElementById('promotion-end').value,
    terms: document.getElementById('promotion-terms').value,
    createdAt: new Date().toISOString()
  };

  const editIndex = document.getElementById('promotion-form').dataset.editIndex;

  if (editIndex !== undefined && editIndex !== null) {
    // Edit existing promotion
    currentRestaurant.promotions[editIndex] = promotion;
    delete document.getElementById('promotion-form').dataset.editIndex;
    toast('Promotion updated successfully');
  } else {
    // Add new promotion
    if (!currentRestaurant.promotions) currentRestaurant.promotions = [];
    currentRestaurant.promotions.push(promotion);
    toast('Promotion created successfully');
  }

  localStorage.setItem('appetitus_restaurant', JSON.stringify(currentRestaurant));
  renderPromotions();
  closePromotionModal();
});

function logoutRestaurant() {
  localStorage.removeItem('appetitus_restaurant');
  window.location.href = 'index.php';
}
</script>
</body>
</html>



