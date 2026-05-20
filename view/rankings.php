<?php // Converted to PHP ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Top 10 Restaurants — Appetitus</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .rankings-hero {
      background: linear-gradient(135deg, var(--accent), var(--accent-light));
      color: white;
      padding: 3rem 0;
      text-align: center;
    }
    .rankings-hero h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }
    .rankings-hero p {
      opacity: 0.9;
      font-size: 1.1rem;
    }
    .rankings-nav {
      background: var(--bg-surface);
      border-bottom: 1px solid var(--border);
      padding: 0;
    }
    .rankings-nav .container {
      display: flex;
      gap: 2rem;
      padding: 1rem 0;
      justify-content: center;
    }
    .rankings-nav a {
      color: var(--text-secondary);
      text-decoration: none;
      padding: 0.5rem 1rem;
      border-radius: var(--radius-sm);
      transition: var(--transition);
      font-weight: 500;
    }
    .rankings-nav a.active {
      background: var(--accent);
      color: white;
    }
    .rankings-nav a:hover {
      background: var(--accent-light);
      color: var(--text-primary);
    }
    .rankings-grid {
      display: grid;
      gap: 2rem;
      margin-bottom: 3rem;
    }
    .ranking-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      overflow: hidden;
      transition: var(--transition);
      position: relative;
    }
    .ranking-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .ranking-badge {
      position: absolute;
      top: 1rem;
      left: 1rem;
      width: 3rem;
      height: 3rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      font-weight: 700;
      color: white;
      z-index: 2;
    }
    .ranking-badge.gold {
      background: linear-gradient(135deg, #ffd700, #ffb347);
      box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
    }
    .ranking-badge.silver {
      background: linear-gradient(135deg, #c0c0c0, #a8a8a8);
      box-shadow: 0 2px 8px rgba(192, 192, 192, 0.3);
    }
    .ranking-badge.bronze {
      background: linear-gradient(135deg, #cd7f32, #a0522d);
      box-shadow: 0 2px 8px rgba(205, 127, 50, 0.3);
    }
    .ranking-badge.normal {
      background: var(--accent);
      box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
    }
    .ranking-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .ranking-content {
      padding: 1.5rem;
    }
    .ranking-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1rem;
    }
    .ranking-title {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--text-primary);
      margin: 0;
    }
    .ranking-score {
      background: var(--accent);
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: var(--radius-sm);
      font-size: 0.9rem;
      font-weight: 600;
    }
    .ranking-meta {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
      font-size: 0.9rem;
      color: var(--text-muted);
    }
    .ranking-location {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }
    .ranking-cuisine {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }
    .ranking-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
      margin-bottom: 1rem;
    }
    .ranking-stat {
      text-align: center;
    }
    .ranking-stat-value {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--accent);
      display: block;
    }
    .ranking-stat-label {
      font-size: 0.8rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .ranking-description {
      color: var(--text-secondary);
      line-height: 1.4;
      margin-bottom: 1rem;
    }
    .ranking-promotions {
      background: #fff3cd;
      border: 1px solid #ffeaa7;
      border-radius: var(--radius-sm);
      padding: 0.75rem;
      margin-bottom: 1rem;
    }
    .ranking-promotions-title {
      font-weight: 600;
      color: #856404;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }
    .ranking-promotion {
      color: #856404;
      font-size: 0.85rem;
      margin-bottom: 0.25rem;
    }
    .ranking-actions {
      display: flex;
      gap: 0.5rem;
    }
    .btn-ranking {
      flex: 1;
      text-align: center;
    }
    .filter-section {
      background: var(--bg-surface);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 1.5rem;
      margin-bottom: 2rem;
    }
    .filter-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--text-primary);
    }
    .filter-options {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }
    .filter-btn {
      padding: 0.5rem 1rem;
      border: 1px solid var(--border);
      background: white;
      border-radius: var(--radius-sm);
      cursor: pointer;
      transition: var(--transition);
      font-size: 0.9rem;
    }
    .filter-btn.active {
      background: var(--accent);
      color: white;
      border-color: var(--accent);
    }
    .filter-btn:hover {
      background: var(--accent-light);
      color: var(--text-primary);
    }
  </style>
</head>
<body>

<nav id="navbar">
  <a class="nav-logo" href="index.php"><span class="logo-icon">🍽️</span> Appetitus</a>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="explore.php">Explore</a></li>
    <li><a href="rankings.php" class="active">Top 10</a></li>
    <li><a href="user-search.php">Reviewers</a></li>
  </ul>
  <div class="nav-actions" id="nav-actions"></div>
</nav>

<div class="rankings-hero">
  <div class="container">
    <h1>🏆 Top 10 Restaurants</h1>
    <p>Discover the highest-rated restaurants in our community</p>
  </div>
</div>

<div class="rankings-nav">
  <div class="container">
    <a href="#overall" class="active" onclick="showRankingCategory('overall')">Overall</a>
    <a href="#rating" onclick="showRankingCategory('rating')">By Rating</a>
    <a href="#reviews" onclick="showRankingCategory('reviews')">By Reviews</a>
    <a href="#trending" onclick="showRankingCategory('trending')">Trending</a>
  </div>
</div>

<div class="container">

  <div class="filter-section">
    <div class="filter-title">Filter by Cuisine</div>
    <div class="filter-options">
      <button class="filter-btn active" onclick="filterByCuisine('all')">All Cuisines</button>
      <button class="filter-btn" onclick="filterByCuisine('gastronomique')">Fine Dining</button>
      <button class="filter-btn" onclick="filterByCuisine('asiatique')">Asian</button>
      <button class="filter-btn" onclick="filterByCuisine('italien')">Italian</button>
      <button class="filter-btn" onclick="filterByCuisine('fruits-de-mer')">Seafood</button>
      <button class="filter-btn" onclick="filterByCuisine('street-food')">Street Food</button>
      <button class="filter-btn" onclick="filterByCuisine('brasserie')">Pub</button>
      <button class="filter-btn" onclick="filterByCuisine('africaine')">African</button>
    </div>
  </div>

  <div class="rankings-grid" id="rankings-grid">
    <!-- Rankings will be populated here -->
  </div>

</div>

<script src="../script/app.js"></script>
<script>
let currentCategory = 'overall';
let currentCuisine = 'all';

document.addEventListener('DOMContentLoaded', () => {
  if (window.state?.restaurants?.length) {
    renderRankings();
  } else {
    document.getElementById('rankings-grid').innerHTML = '<div style="text-align:center; padding:3rem; color:var(--text-muted);">Loading rankings...</div>';
  }
});

window.initRankings = renderRankings;

function showRankingCategory(category) {
  currentCategory = category;
  document.querySelectorAll('.rankings-nav a').forEach(a => a.classList.remove('active'));
  document.querySelector(`.rankings-nav a[href="#${category}"]`).classList.add('active');
  renderRankings();
}

function filterByCuisine(cuisine) {
  currentCuisine = cuisine;
  document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
  event.target.classList.add('active');
  renderRankings();
}

function renderRankings() {
  if (!window.state?.restaurants?.length) {
    document.getElementById('rankings-grid').innerHTML = '<div style="text-align:center; padding:3rem; color:var(--text-muted);">Loading rankings...</div>';
    return;
  }

  const restaurants = [...window.state.restaurants];

  // Filter by cuisine if needed
  let filteredRestaurants = restaurants;
  if (currentCuisine !== 'all') {
    filteredRestaurants = restaurants.filter(r => r.category === currentCuisine);
  }

  // Sort based on category
  switch (currentCategory) {
    case 'rating':
      filteredRestaurants.sort((a, b) => b.avg - a.avg);
      break;
    case 'reviews':
      filteredRestaurants.sort((a, b) => b.reviews.length - a.reviews.length);
      break;
    case 'trending':
      // Sort by recent reviews (last 30 days)
      const thirtyDaysAgo = new Date();
      thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
      filteredRestaurants.sort((a, b) => {
        const aRecent = a.reviews.filter(r => new Date(r.date) > thirtyDaysAgo).length;
        const bRecent = b.reviews.filter(r => new Date(r.date) > thirtyDaysAgo).length;
        return bRecent - aRecent;
      });
      break;
    default: // overall
      filteredRestaurants.sort((a, b) => b.score - a.score);
  }

  // Take top 10
  const top10 = filteredRestaurants.slice(0, 10);

  const rankingsHtml = top10.map((restaurant, index) => {
    const rank = index + 1;
    let badgeClass = 'normal';
    if (rank === 1) badgeClass = 'gold';
    else if (rank === 2) badgeClass = 'silver';
    else if (rank === 3) badgeClass = 'bronze';

    const stars = '★'.repeat(Math.floor(restaurant.avg)) + '☆'.repeat(5 - Math.floor(restaurant.avg));

    // Get active promotions for this restaurant
    const promotions = getRestaurantPromotions(restaurant.name);

    return `
      <div class="ranking-card">
        <div class="ranking-badge ${badgeClass}">#${rank}</div>
        <img class="ranking-image" ${restaurantImageAttrs(restaurant)}>
        <div class="ranking-content">
          <div class="ranking-header">
            <h3 class="ranking-title">${restaurant.name}</h3>
            <div class="ranking-score">${restaurant.score}/100</div>
          </div>

          <div class="ranking-meta">
            <div class="ranking-location">📍 ${restaurant.city}</div>
            <div class="ranking-cuisine">🍽️ ${restaurant.cuisine}</div>
          </div>

          <div class="ranking-stats">
            <div class="ranking-stat">
              <span class="ranking-stat-value">${restaurant.avg.toFixed(1)}</span>
              <span class="ranking-stat-label">Rating</span>
            </div>
            <div class="ranking-stat">
              <span class="ranking-stat-value">${restaurant.reviews.length}</span>
              <span class="ranking-stat-label">Reviews</span>
            </div>
            <div class="ranking-stat">
              <span class="ranking-stat-value">${restaurant.priceRange}</span>
              <span class="ranking-stat-label">Price</span>
            </div>
          </div>

          ${promotions.length > 0 ? `
            <div class="ranking-promotions">
              <div class="ranking-promotions-title">🎉 Active Promotions</div>
              ${promotions.slice(0, 2).map(p => `<div class="ranking-promotion">${p.title}</div>`).join('')}
              ${promotions.length > 2 ? `<div class="ranking-promotion">+${promotions.length - 2} more...</div>` : ''}
            </div>
          ` : ''}

          <div class="ranking-actions">
            <a href="explore.php?restaurant=${restaurant.id}" class="btn btn-primary btn-ranking">View Details</a>
            <a href="rate.php?restaurant=${restaurant.id}" class="btn btn-outline btn-ranking">Write Review</a>
          </div>
        </div>
      </div>
    `;
  }).join('');

  document.getElementById('rankings-grid').innerHTML = rankingsHtml || '<div style="text-align:center; padding:3rem; color:var(--text-muted);">No restaurants found matching your criteria</div>';
}

function getRestaurantPromotions(restaurantName) {
  // Check all restaurant localStorage entries for promotions
  const promotions = [];
  for (let i = 0; i < localStorage.length; i++) {
    const key = localStorage.key(i);
    if (key && key.startsWith('appetitus_restaurant')) {
      try {
        const restaurant = JSON.parse(localStorage.getItem(key));
        if (restaurant.name.toLowerCase() === restaurantName.toLowerCase() && restaurant.promotions) {
          const now = new Date();
          const activePromotions = restaurant.promotions.filter(p => {
            const startDate = new Date(p.startDate);
            const endDate = new Date(p.endDate);
            return now >= startDate && now <= endDate;
          });
          promotions.push(...activePromotions);
        }
      } catch (e) {
        // Skip invalid data
      }
    }
  }
  return promotions;
}
</script>
</body>
</html>



