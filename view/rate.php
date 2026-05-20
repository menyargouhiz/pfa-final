<?php // Converted to PHP ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rate Restaurant — Appetitus</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .rate-page { max-width: 800px; margin: 0 auto; padding: 2rem 1rem; }
    .restaurant-selector { margin-bottom: 2rem; }
    .restaurant-card { display: flex; gap: 1rem; padding: 1rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 1rem; }
    .restaurant-card img { width: 80px; height: 80px; object-fit: cover; border-radius: var(--radius-sm); }
    .restaurant-card-info { flex: 1; }
    .restaurant-card-name { font-weight: 600; margin-bottom: 0.25rem; }
    .restaurant-card-meta { color: var(--text-muted); font-size: 0.9rem; }
    .rating-section { background: var(--bg-surface); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem; }
    .rating-section h2 { margin-bottom: 1.5rem; text-align: center; }
    .star-rating { display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; }
    .star { font-size: 2.5rem; cursor: pointer; color: var(--text-muted); transition: var(--transition); }
    .star:hover, .star.active { color: #ffd700; }
    .review-form { display: grid; gap: 1.5rem; }
    .form-group { display: grid; gap: 0.5rem; }
    .form-label { font-weight: 500; }
    .form-textarea { min-height: 120px; resize: vertical; }
    .rating-display { text-align: center; margin-bottom: 1rem; }
    .rating-display span { font-size: 1.2rem; font-weight: 600; }
    .submit-section { text-align: center; margin-top: 2rem; }
    .already-reviewed { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 1rem; border-radius: var(--radius-md); text-align: center; margin-bottom: 2rem; }
    .photo-upload { display: flex; flex-direction: column; gap: 1rem; }
    .photo-preview { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 0.5rem; margin-top: 1rem; }
    .photo-item { position: relative; }
    .photo-item img { width: 100%; height: 100px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border); }
    .photo-remove { position: absolute; top: -5px; right: -5px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; font-size: 12px; display: flex; align-items: center; justify-content: center; }
    .photo-remove:hover { background: #c0392b; }
  </style>
</head>
<body>

<nav id="navbar">
  <a class="nav-logo" href="index.php"><span class="logo-icon">🍽️</span> Appetitus</a>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="explore.php">Explore</a></li>
    <li><a href="user-search.php">Reviewers</a></li>
  </ul>
  <div class="nav-actions" id="nav-actions"></div>
</nav>

<div class="section">
  <div class="container">
    <div class="rate-page" id="rate-page">
      <!-- Content will be rendered by JavaScript -->
    </div>
  </div>
</div>

<script src="../script/app.js"></script>
<script>
let selectedRestaurant = null;
let currentRatings = { ambiance: 0, cleanliness: 0, quality: 0, service: 0 };
let selectedPhotos = [];

document.addEventListener('DOMContentLoaded', initRatePage);
window.initRate = initRatePage;

function initRatePage() {
  const user = getCurrentUser();
  const container = document.getElementById('rate-page');

  if (!user) {
    container.innerHTML = `
      <div style="text-align:center;padding:4rem 1rem;">
        <div style="font-size:4rem;margin-bottom:1rem;">⭐</div>
        <h2 style="margin-bottom:.75rem;">Log in to rate restaurants</h2>
        <p style="color:var(--text-muted);margin-bottom:2rem;">You need to be logged in to leave reviews and earn badges.</p>
        <div style="display:flex;gap:1rem;justify-content:center;">
          <a href="login.php" class="btn btn-primary btn-lg">Log In</a>
          <a href="signup.php" class="btn btn-ghost btn-lg">Sign Up</a>
        </div>
      </div>`;
    return;
  }

  if (!window.state?.restaurants?.length) {
    container.innerHTML = `
      <div style="text-align:center;padding:4rem 1rem;">
        <div style="font-size:3rem;margin-bottom:1rem;">⏳</div>
        <h2 style="margin-bottom:.75rem;">Loading restaurants...</h2>
        <p style="color:var(--text-muted);">Your rating form will appear in a moment.</p>
      </div>`;
    return;
  }

  // Check URL parameter for restaurant ID
  const urlParams = new URLSearchParams(window.location.search);
  const restaurantId = urlParams.get('restaurant');

  if (restaurantId) {
    selectedRestaurant = window.state.restaurants.find(r => r.id == restaurantId);
    if (selectedRestaurant) {
      renderRatingForm(selectedRestaurant, user);
      return;
    }
  }

  // Show restaurant selector
  renderRestaurantSelector(user);
}

function renderRestaurantSelector(user) {
  const container = document.getElementById('rate-page');

  // Check if user has already reviewed all restaurants
  const reviewedRestaurantIds = window.state.userReviews
    .filter(review => review.userId === user.id)
    .map(review => review.restaurantId);

  const unreviewedRestaurants = window.state.restaurants.filter(r => !reviewedRestaurantIds.includes(r.id));

  container.innerHTML = `
    <div class="restaurant-selector">
      <h1>Rate a Restaurant</h1>
      <p style="color:var(--text-muted);margin-bottom:2rem;">Choose a restaurant you've visited to leave a review and earn badges!</p>

      ${unreviewedRestaurants.length === 0 ? `
        <div class="already-reviewed">
          <div style="font-size:2rem;margin-bottom:0.5rem;">🎉</div>
          <strong>You've reviewed all restaurants!</strong><br>
          Check out <a href="explore.php">more restaurants</a> or visit your <a href="profile.php">profile</a> to see your reviews.
        </div>
      ` : ''}

      <div id="restaurant-list">
        ${unreviewedRestaurants.map(r => `
          <div class="restaurant-card" onclick="selectRestaurant(${r.id})">
            <img ${restaurantImageAttrs(r)} />
            <div class="restaurant-card-info">
              <div class="restaurant-card-name">${r.name}</div>
              <div class="restaurant-card-meta">📍 ${r.city} · ${r.priceRange} · ${r.cuisine}</div>
              <div class="stars">${renderStars(r.avg)} <span class="rating-count">${r.avg.toFixed(1)} (${r.reviews.length} reviews)</span></div>
            </div>
          </div>
        `).join('')}
      </div>
    </div>`;
}

function selectRestaurant(id) {
  selectedRestaurant = window.state.restaurants.find(r => r.id === id);
  const user = getCurrentUser();
  renderRatingForm(selectedRestaurant, user);
}

function renderRatingForm(restaurant, user) {
  const container = document.getElementById('rate-page');

  // Check if user already reviewed this restaurant
  const existingReview = window.state.userReviews.find(review =>
    review.userId === user.id && review.restaurantId === restaurant.id
  );

  if (existingReview) {
    container.innerHTML = `
      <div class="already-reviewed">
        <div style="font-size:2rem;margin-bottom:0.5rem;">📝</div>
        <strong>You've already reviewed ${restaurant.name}!</strong><br>
        You can only leave one review per restaurant. Check your <a href="profile.php">profile</a> to see your reviews.
        <br><br>
        <a href="rate.php" class="btn btn-outline">Rate Another Restaurant</a>
      </div>`;
    return;
  }

  container.innerHTML = `
    <div class="rating-section">
      <h2>Rate ${restaurant.name}</h2>

      <div class="restaurant-card" style="margin-bottom:2rem;">
        <img ${restaurantImageAttrs(restaurant)} />
        <div class="restaurant-card-info">
          <div class="restaurant-card-name">${restaurant.name}</div>
          <div class="restaurant-card-meta">📍 ${restaurant.city} · ${restaurant.priceRange} · ${restaurant.cuisine}</div>
          <div class="stars">${renderStars(restaurant.avg)} <span class="rating-count">${restaurant.avg.toFixed(1)} (${restaurant.reviews.length} reviews)</span></div>
        </div>
      </div>

      <form class="review-form" id="review-form">
        <div class="form-group">
          <label class="form-label">Ambiance</label>
          <div class="star-rating rating-controls" data-category="ambiance">
            <span class="star" data-category="ambiance" data-rating="1">★</span>
            <span class="star" data-category="ambiance" data-rating="2">★</span>
            <span class="star" data-category="ambiance" data-rating="3">★</span>
            <span class="star" data-category="ambiance" data-rating="4">★</span>
            <span class="star" data-category="ambiance" data-rating="5">★</span>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Cleanliness</label>
          <div class="star-rating rating-controls" data-category="cleanliness">
            <span class="star" data-category="cleanliness" data-rating="1">★</span>
            <span class="star" data-category="cleanliness" data-rating="2">★</span>
            <span class="star" data-category="cleanliness" data-rating="3">★</span>
            <span class="star" data-category="cleanliness" data-rating="4">★</span>
            <span class="star" data-category="cleanliness" data-rating="5">★</span>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Quality</label>
          <div class="star-rating rating-controls" data-category="quality">
            <span class="star" data-category="quality" data-rating="1">★</span>
            <span class="star" data-category="quality" data-rating="2">★</span>
            <span class="star" data-category="quality" data-rating="3">★</span>
            <span class="star" data-category="quality" data-rating="4">★</span>
            <span class="star" data-category="quality" data-rating="5">★</span>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Service</label>
          <div class="star-rating rating-controls" data-category="service">
            <span class="star" data-category="service" data-rating="1">★</span>
            <span class="star" data-category="service" data-rating="2">★</span>
            <span class="star" data-category="service" data-rating="3">★</span>
            <span class="star" data-category="service" data-rating="4">★</span>
            <span class="star" data-category="service" data-rating="5">★</span>
          </div>
        </div>
        <div class="form-group">
          <div class="rating-display">
            <span id="rating-text">Rate all four categories</span>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Facture code</label>
          <input class="form-input" id="facture-code" placeholder="Code on your receipt..." maxlength="100" autocomplete="off" />
        </div>

        <div class="form-group">
          <label class="form-label">Review</label>
          <textarea class="form-textarea" id="review-text" placeholder="Share your experience..." maxlength="1000"></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Photos (Optional)</label>
          <div class="photo-upload">
            <input type="file" id="photo-input" accept="image/*" multiple style="display:none;" />
            <button type="button" class="btn btn-outline" onclick="document.getElementById('photo-input').click()">
              📷 Add Photos
            </button>
            <div id="photo-preview" class="photo-preview"></div>
          </div>
          <small style="color:var(--text-muted);font-size:0.85rem;">You can upload up to 5 photos. Max 5MB each.</small>
        </div>

        <div class="submit-section">
          <button type="button" class="btn btn-ghost" onclick="cancelRating()">Cancel</button>
          <button type="submit" class="btn btn-primary btn-lg" id="submit-btn" disabled>Submit Review</button>
        </div>
      </form>
    </div>`;
}

// Star rating functionality
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('star')) {
    const category = e.target.dataset.category;
    const rating = parseInt(e.target.dataset.rating, 10);
    setRating(category, rating);
  }
});

function setRating(category, rating) {
  currentRatings[category] = rating;
  const stars = document.querySelectorAll(`.rating-controls[data-category="${category}"] .star`);
  const ratingText = document.getElementById('rating-text');

  stars.forEach((star, index) => {
    if (index < rating) {
      star.classList.add('active');
    } else {
      star.classList.remove('active');
    }
  });

  const values = Object.values(currentRatings);
  const complete = values.every(v => v > 0);
  if (complete) {
    const avg = (values.reduce((sum, value) => sum + value, 0) / values.length).toFixed(1);
    ratingText.textContent = `Overall ${avg} / 5 — Ambiance ${currentRatings.ambiance}, Cleanliness ${currentRatings.cleanliness}, Quality ${currentRatings.quality}, Service ${currentRatings.service}`;
  } else {
    ratingText.textContent = 'Rate all four categories';
  }

  updateSubmitState();
}

function updateSubmitState() {
  const submitBtn = document.getElementById('submit-btn');
  if (!submitBtn) return;

  const ratingsComplete = Object.values(currentRatings).every(value => value > 0);
  const reviewText = document.getElementById('review-text')?.value.trim() || '';
  const factureCode = document.getElementById('facture-code')?.value.trim() || '';
  submitBtn.disabled = !(ratingsComplete && reviewText.length >= 5 && factureCode.length >= 4);
}

// Photo upload functionality
document.addEventListener('change', (e) => {
  if (e.target.id === 'photo-input') {
    handlePhotoSelection(e.target.files);
  }
});

document.addEventListener('input', (e) => {
  if (e.target.id === 'review-text' || e.target.id === 'facture-code') {
    updateSubmitState();
  }
});

function handlePhotoSelection(files) {
  const maxFiles = 5;
  const maxSize = 5 * 1024 * 1024; // 5MB

  for (let file of files) {
    if (selectedPhotos.length >= maxFiles) {
      toast(`Maximum ${maxFiles} photos allowed`, 'error');
      break;
    }

    if (file.size > maxSize) {
      toast(`File ${file.name} is too large. Max 5MB per file.`, 'error');
      continue;
    }

    if (!file.type.startsWith('image/')) {
      toast(`File ${file.name} is not an image`, 'error');
      continue;
    }

    // Convert to data URL
    const reader = new FileReader();
    reader.onload = (e) => {
      selectedPhotos.push({
        name: file.name,
        dataUrl: e.target.result,
        file: file
      });
      updatePhotoPreview();
    };
    reader.readAsDataURL(file);
  }

  // Clear input
  document.getElementById('photo-input').value = '';
}

function updatePhotoPreview() {
  const preview = document.getElementById('photo-preview');
  preview.innerHTML = selectedPhotos.map((photo, index) => `
    <div class="photo-item">
      <img src="${photo.dataUrl}" alt="${photo.name}" />
      <button class="photo-remove" onclick="removePhoto(${index})">×</button>
    </div>
  `).join('');
}

function removePhoto(index) {
  selectedPhotos.splice(index, 1);
  updatePhotoPreview();
}

// Form submission
document.addEventListener('submit', (e) => {
  if (e.target.id === 'review-form') {
    e.preventDefault();
    submitReview();
  }
});

async function submitReview() {
  const user = getCurrentUser();
  const reviewText = document.getElementById('review-text').value.trim();
  const factureCode = document.getElementById('facture-code').value.trim();
  const ratings = currentRatings;
  const missing = Object.values(ratings).some(value => value === 0);

  if (!selectedRestaurant || missing || !reviewText || factureCode.length < 4) {
    toast('Please fill in all fields', 'error');
    return;
  }

  const overallRating = Math.round((ratings.ambiance + ratings.cleanliness + ratings.quality + ratings.service) / 4);
  const submitBtn = document.getElementById('submit-btn');
  submitBtn.disabled = true;

  try {
    const res = await fetch('../controller/api_create_review.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        restaurant_id: selectedRestaurant.id,
        author: user.name,
        rating: overallRating,
        ambiance: ratings.ambiance,
        cleanliness: ratings.cleanliness,
        quality: ratings.quality,
        service: ratings.service,
        facture_code: factureCode,
        text: reviewText
      })
    });
    const data = await res.json();
    if (!data.success) {
      throw new Error(data.error || 'Failed to submit review');
    }
  } catch (err) {
    submitBtn.disabled = false;
    toast(err.message, 'error');
    return;
  }

  const existingIds = window.state.userReviews.map(r => Number(r.id) || 0);
  const newReview = {
    id: (existingIds.length ? Math.max(...existingIds) : 0) + 1,
    restaurantId: selectedRestaurant.id,
    userId: user.id,
    rating: overallRating,
    ambiance: ratings.ambiance,
    cleanliness: ratings.cleanliness,
    quality: ratings.quality,
    service: ratings.service,
    facture_verified: true,
    text: reviewText,
    photos: selectedPhotos.map(photo => ({ name: photo.name, dataUrl: photo.dataUrl })),
    date: new Date().toISOString().split('T')[0]
  };

  // Add to reviews
  window.state.userReviews.push(newReview);

  // Update restaurant stats
  selectedRestaurant.reviews = window.state.userReviews.filter(review => review.restaurantId === selectedRestaurant.id);
  selectedRestaurant.avg = selectedRestaurant.reviews.reduce((sum, review) => sum + review.rating, 0) / selectedRestaurant.reviews.length;
  selectedRestaurant.score = Math.round(selectedRestaurant.avg * 20);

  // Update user review count
  user.reviewCount = window.state.userReviews.filter(review => review.userId === user.id).length;
  localStorage.setItem('appetitus_user', JSON.stringify(user));

  toast('✅ Review submitted successfully!', 'success');

  // Redirect to profile after a delay
  setTimeout(() => {
    window.location.href = 'profile.php';
  }, 1500);
}

function cancelRating() {
  selectedRestaurant = null;
  currentRatings = { ambiance: 0, cleanliness: 0, quality: 0, service: 0 };
  selectedPhotos = [];
  const user = getCurrentUser();
  renderRestaurantSelector(user);
}
</script>

</body>
</html>



