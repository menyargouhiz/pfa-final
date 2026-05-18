// ===== DATA — RESTAURANTS TUNISIA =====
window.RESTAURANTS = [];

// ===== BADGE SYSTEM =====
// Make it globally accessible immediately
window.BADGES = [
  { id:'nouveau',  icon:'🌱', name:'Curious',          class:'badge-nouveau', desc:'Welcome!',              min:0,  max:0 },
  { id:'bronze',   icon:'🥉', name:'Bronze Loyal',     class:'badge-bronze',  desc:'1 to 3 reviews',        min:1,  max:3 },
  { id:'argent',   icon:'🥈', name:'Silver Gourmet',   class:'badge-argent',  desc:'4 to 9 reviews',        min:4,  max:9 },
  { id:'or',       icon:'🥇', name:'Gold Expert',      class:'badge-or',      desc:'10 to 19 reviews',      min:10, max:19 },
  { id:'platine',  icon:'💎', name:'Maestro Platinum', class:'badge-platine', desc:'20 reviews or more',  min:20, max:Infinity }
];
const BADGES = window.BADGES;

function getBadge(reviewCount) {
  return [...BADGES].reverse().find(b => reviewCount >= b.min) || BADGES[0];
}

// ===== SCORE =====
function calcScore(r) {
  if (!r.reviews || r.reviews.length === 0) return 0;
  const avg = avgRating(r);
  const count = r.reviews.length;
  const days = (Date.now() - new Date(r.reviews[0].date)) / 86400000;
  const freshness = Math.max(0, 1 - days / 180);
  return Math.round((avg / 5) * 60 + Math.min(count / 20, 1) * 30 + freshness * 10);
}

function getReviewAverage(rv) {
  if (rv && rv.ambiance && rv.cleanliness && rv.quality && rv.service) {
    return (parseInt(rv.ambiance, 10) + parseInt(rv.cleanliness, 10) + parseInt(rv.quality, 10) + parseInt(rv.service, 10)) / 4;
  }
  return rv && rv.rating ? parseFloat(rv.rating) : 0;
}

function avgRating(r) {
  if (!r.reviews || !r.reviews.length) return 0;
  return r.reviews.reduce((a, rv) => a + getReviewAverage(rv), 0) / r.reviews.length;
}

// ===== STATE =====
const state = {
  restaurants: [],
  userReviews: [],
  user: JSON.parse(localStorage.getItem('appetitus_user') || 'null'),
  pendingRatings: {
    ambiance: 0,
    cleanliness: 0,
    quality: 0,
    service: 0
  },
  favorites: [],   // array of restaurant IDs
  wishlist: []     // array of restaurant IDs
};

const THEME_STORAGE_KEY = 'appetitus_theme';
function getStoredTheme() {
  return localStorage.getItem(THEME_STORAGE_KEY) || 'light';
}
function applyTheme(theme) {
  document.body.classList.toggle('dark', theme === 'dark');
  const btn = document.getElementById('theme-toggle');
  if (!btn) return;
  btn.textContent = theme === 'dark' ? '☀️ Light' : '🌙 Dark';
  btn.title = theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme';
}
function toggleTheme() {
  const nextTheme = document.body.classList.contains('dark') ? 'light' : 'dark';
  localStorage.setItem(THEME_STORAGE_KEY, nextTheme);
  applyTheme(nextTheme);
}

async function loadRestaurants() {
  try {
    const res = await fetch('../controller/get_restaurants.php', {
      credentials: 'include'
    });
    if (!res.ok) throw new Error('Network error');
    RESTAURANTS = await res.json();
    state.restaurants = RESTAURANTS.map(r => {
      const myReviews = state.userReviews.filter(ur => ur.restaurantId === r.id);
      const allReviews = [...myReviews, ...(r.reviews || [])];
      return { ...r, reviews: allReviews, score: 0, avg: 0 };
    }).map(r => ({ ...r, score: calcScore(r), avg: avgRating(r) }));

    if (typeof window.initExplore === 'function') window.initExplore();
    else if (typeof renderGrid === 'function') renderGrid();
    if (typeof renderTopPicks === 'function') renderTopPicks();
    if (typeof renderBadgesShowcase === 'function') renderBadgesShowcase();
  } catch (err) {
    console.error("Failed to load restaurants:", err);
  }
}

// ===== FAVORITES & WISHLIST =====
async function loadUserFavoritesAndWishlist() {
  if (!state.user) return;
  try {
    const [favRes, wishRes] = await Promise.all([
      fetch('../controller/api_favorites.php', { credentials: 'include' }),
      fetch('../controller/api_wishlist.php', { credentials: 'include' })
    ]);
    const favData = await favRes.json();
    const wishData = await wishRes.json();
    if (favData.success && favData.data) {
      state.favorites = favData.data.map(f => parseInt(f.restaurant_id));
    }
    if (wishData.success && wishData.data) {
      state.wishlist = wishData.data.map(w => parseInt(w.restaurant_id));
    }
  } catch (err) {
    console.error('Failed to load favorites/wishlist:', err);
  }
}

function isFavorited(restaurantId) {
  return state.favorites.includes(parseInt(restaurantId));
}

function isWishlisted(restaurantId) {
  return state.wishlist.includes(parseInt(restaurantId));
}

async function toggleFavorite(restaurantId, event) {
  if (event) { event.stopPropagation(); event.preventDefault(); }
  if (!state.user) { toast('💡 Log in to save favorites!', 'error'); return; }
  
  const rid = parseInt(restaurantId);
  console.log('Toggling favorite for restaurant:', rid);
  console.log('Current user:', state.user);
  try {
    const res = await fetch('../controller/api_favorites.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({ restaurant_id: rid })
    });
    console.log('Response status:', res.status);
    const data = await res.json();
    console.log('Response data:', data);
    if (data.success) {
      if (data.data.action === 'added') {
        state.favorites.push(rid);
        toast('❤️ Added to favorites!');
      } else {
        state.favorites = state.favorites.filter(id => id !== rid);
        toast('💔 Removed from favorites');
      }
      updateFavWishButtons(rid);
    } else {
      toast('⚠️ ' + (data.error || 'Error'), 'error');
    }
  } catch (err) {
    console.error('Toggle favorite error:', err);
    toast('⚠️ Failed to update favorites', 'error');
  }
}

async function toggleWishlist(restaurantId, event) {
  if (event) { event.stopPropagation(); event.preventDefault(); }
  if (!state.user) { toast('💡 Log in to save your wishlist!', 'error'); return; }

  const rid = parseInt(restaurantId);
  try {
    const res = await fetch('../controller/api_wishlist.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({ restaurant_id: rid })
    });
    const data = await res.json();
    if (data.success) {
      if (data.data.action === 'added') {
        state.wishlist.push(rid);
        toast('🔖 Added to wishlist!');
      } else {
        state.wishlist = state.wishlist.filter(id => id !== rid);
        toast('📌 Removed from wishlist');
      }
      updateFavWishButtons(rid);
    } else {
      toast('⚠️ ' + (data.error || 'Error'), 'error');
    }
  } catch (err) {
    console.error('Toggle wishlist error:', err);
    toast('⚠️ Failed to update wishlist', 'error');
  }
}

function updateFavWishButtons(restaurantId) {
  // Update all favorite buttons for this restaurant
  document.querySelectorAll(`[data-fav-id="${restaurantId}"]`).forEach(btn => {
    const active = isFavorited(restaurantId);
    btn.classList.toggle('active', active);
    btn.innerHTML = active ? '❤️' : '🤍';
    btn.title = active ? 'Remove from favorites' : 'Add to favorites';
  });
  // Update all wishlist buttons for this restaurant
  document.querySelectorAll(`[data-wish-id="${restaurantId}"]`).forEach(btn => {
    const active = isWishlisted(restaurantId);
    btn.classList.toggle('active', active);
    btn.innerHTML = active ? '🔖' : '📑';
    btn.title = active ? 'Remove from wishlist' : 'Add to wishlist';
  });
}

// ===== RENDER STARS =====
function renderStars(rating, size = '') {
  const full = Math.floor(rating), half = rating % 1 >= 0.5 ? 1 : 0, empty = 5 - full - half;
  const s = (cls) => `<span class="star ${cls} ${size}">★</span>`;
  return s('filled').repeat(full) + (half ? s('half') : '') + s('').repeat(empty);
}

// ===== DATE FORMAT =====
function fmtDate(d) {
  return new Date(d).toLocaleDateString('en-US', { day:'2-digit', month:'long', year:'numeric' });
}

// ===== TOAST =====
function toast(msg, type = 'success') {
  const t = document.getElementById('toast');
  if (!t) return;
  t.textContent = msg; t.className = `show ${type}`;
  clearTimeout(toast._t);
  toast._t = setTimeout(() => t.className = '', 3500);
}

// ===== CASSEROLE MODAL — 3 PHASES =====
function openCasserole(id) {
  const r = state.restaurants.find(x => x.id === id);
  if (!r) return;
  state.pendingRatings = { ambiance: 0, cleanliness: 0, quality: 0, service: 0 };

  const overlay  = document.getElementById('casserole-overlay');
  const panelContent = document.getElementById('casserole-content');
  if (!overlay || !panelContent) return;

  // Build content
  const dist = [5,4,3,2,1].map(star => {
    const count = r.reviews.filter(rv => rv.rating === star).length;
    return { star, count, pct: r.reviews.length ? (count/r.reviews.length)*100 : 0 };
  });

  const favActive = isFavorited(r.id);
  const wishActive = isWishlisted(r.id);

  panelContent.innerHTML = `
    <div class="casserole-hero" data-category="${r.category}">
      <img src="${r.image}" alt="${r.name}" />
      <div class="casserole-hero-overlay"></div>
      <span class="casserole-hero-badge">🍽️ ${r.cuisine}</span>
      <div class="casserole-hero-actions">
        <button class="fav-wish-btn fav-btn ${favActive ? 'active' : ''}" data-fav-id="${r.id}" onclick="toggleFavorite(${r.id}, event)" title="${favActive ? 'Remove from favorites' : 'Add to favorites'}">${favActive ? '❤️' : '🤍'}</button>
        <button class="fav-wish-btn wish-btn ${wishActive ? 'active' : ''}" data-wish-id="${r.id}" onclick="toggleWishlist(${r.id}, event)" title="${wishActive ? 'Remove from wishlist' : 'Add to wishlist'}">${wishActive ? '🔖' : '📑'}</button>
      </div>
      <button class="casserole-close" onclick="closeCasserole()">✕</button>
    </div>
    <div class="casserole-body" data-category="${r.category}">
      <div class="casserole-name">${r.name}</div>
      <div class="casserole-meta">
        <span>📍 ${r.address}</span><span>📞 ${r.phone||'—'}</span>
        <span>🕐 ${r.openHours}</span><span>💰 ${r.priceRange}</span>
        <span>⚡ Score ${r.score}/100</span>
      </div>
      <p class="casserole-desc">${r.description}</p>
      <div class="hr"></div>
      <div class="rating-overview">
        <div>
          <div class="rating-big-num">${r.avg.toFixed(1)}</div>
          <div class="stars" style="margin-top:4px">${renderStars(r.avg)}</div>
          <div style="font-size:.75rem;color:var(--text-muted);margin-top:3px">${r.reviews.length} reviews</div>
        </div>
        <div class="rating-detail">
          ${dist.map(d=>`<div class="rbar-row"><span>${d.star}★</span><div class="rbar-track"><div class="rbar-fill" style="width:${d.pct}%"></div></div><span>${d.count}</span></div>`).join('')}
        </div>
      </div>
      <div class="hr"></div>
      <div style="font-weight:700;font-size:.85rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:1rem">
        💬 Reviews (${r.reviews.length})
      </div>
      <div class="reviews-list">${r.reviews.map(rv=>`
        <div class="review-card">
          <div class="review-header">
            <div class="review-avatar">${rv.author.charAt(0)}</div>
            <div>
              <div class="review-author">${rv.author}</div>
              <div class="stars">${renderStars(getReviewAverage(rv))}</div>
            </div>
            <div class="review-date">${fmtDate(rv.date)}</div>
          </div>
          <div class="review-text">${rv.text}</div>
          <div class="review-detail" style="font-size:.8rem;color:var(--text-muted);margin-top:.75rem;">
            Ambiance ${rv.ambiance || rv.rating} · Cleanliness ${rv.cleanliness || rv.rating} · Quality ${rv.quality || rv.rating} · Service ${rv.service || rv.rating}
          </div>
        </div>`).join('')}
      </div>
      <div class="hr"></div>
      <div class="add-review">
        <h4>✍️ Leave a review</h4>
        ${state.user?`<div style="margin-bottom:.75rem"><span class="badge ${getBadge((state.userReviews||[]).length).class}">${getBadge((state.userReviews||[]).length).icon} ${getBadge((state.userReviews||[]).length).name}</span> &nbsp;<span style="font-size:.82rem;color:var(--text-muted)">Hello <strong>${state.user.name}</strong></span></div>`
          :'<p style="font-size:.85rem;color:var(--text-muted);margin-bottom:1rem">💡 <a href="login.php" style="color:var(--accent);font-weight:600">Log in</a> to post a review and unlock badges!</p>'}
        <div class="form-group">
          <label class="form-label">Your name</label>
          <input class="form-input" id="rv-author" placeholder="Your first name..." value="${state.user?state.user.name:''}" ${state.user?'readonly':''} />
        </div>
        <div class="form-group">
          <label class="form-label">Ambiance</label>
          <div class="star-picker" data-group="ambiance">${[1,2,3,4,5].map(n=>`<span class="star-pick" data-group="ambiance" data-v="${n}" onclick="setRating('ambiance', ${n})">★</span>`).join('')}</div>
        </div>
        <div class="form-group">
          <label class="form-label">Cleanliness</label>
          <div class="star-picker" data-group="cleanliness">${[1,2,3,4,5].map(n=>`<span class="star-pick" data-group="cleanliness" data-v="${n}" onclick="setRating('cleanliness', ${n})">★</span>`).join('')}</div>
        </div>
        <div class="form-group">
          <label class="form-label">Quality</label>
          <div class="star-picker" data-group="quality">${[1,2,3,4,5].map(n=>`<span class="star-pick" data-group="quality" data-v="${n}" onclick="setRating('quality', ${n})">★</span>`).join('')}</div>
        </div>
        <div class="form-group">
          <label class="form-label">Service</label>
          <div class="star-picker" data-group="service">${[1,2,3,4,5].map(n=>`<span class="star-pick" data-group="service" data-v="${n}" onclick="setRating('service', ${n})">★</span>`).join('')}</div>
        </div>
        <div class="form-group">
          <label class="form-label">Overall</label>
          <div id="review-rating-summary" style="font-weight:600;color:var(--text-secondary);">Choose your scores above.</div>
        </div>
        <div class="form-group">
          <label class="form-label">Comment</label>
          <textarea class="form-textarea" id="rv-text" placeholder="Share your experience..." maxlength="1000"></textarea>
        </div>
        <button class="btn btn-primary btn-full" style="border-radius:var(--radius-sm)" onclick="submitReview(${r.id})">🚀 Post my review</button>
      </div>
    </div>`;

  // Reset & start 3-phase animation
  overlay.className = '';
  void overlay.offsetWidth; // force reflow

  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
  setTimeout(() => overlay.classList.add('lid-open'),      100);
  setTimeout(() => overlay.classList.add('steam-on'),      350);
  setTimeout(() => overlay.classList.add('steam-off'),     700);
  setTimeout(() => overlay.classList.add('show-content'),  850);
}



function closeCasserole() {
  const overlay = document.getElementById('casserole-overlay');
  // 1 — panel content leaves
  overlay.classList.remove('show-content');
  // 2 — lid closes (lid returns to 0)
  setTimeout(() => overlay.classList.remove('lid-open', 'steam-on', 'steam-off'), 420);
  // 3 — casserole + overlay disappear
  setTimeout(() => overlay.classList.remove('open'), 900);
  document.body.style.overflow = '';
}

function setRating(category, v) {
  state.pendingRatings[category] = v;
  document.querySelectorAll(`.star-pick[data-group="${category}"]`).forEach(s => {
    s.classList.toggle('active', parseInt(s.dataset.v, 10) <= v);
  });
  const summary = document.getElementById('review-rating-summary');
  if (summary) {
    const values = Object.values(state.pendingRatings);
    const filled = values.filter(val => val > 0).length;
    if (filled === values.length) {
      const avg = (values.reduce((sum, val) => sum + val, 0) / values.length).toFixed(1);
      summary.textContent = `Overall ${avg} / 5 — Ambiance ${state.pendingRatings.ambiance}, Cleanliness ${state.pendingRatings.cleanliness}, Quality ${state.pendingRatings.quality}, Service ${state.pendingRatings.service}`;
    } else {
      summary.textContent = 'Choose your scores above.';
    }
  }
}

async function submitReview(restaurantId) {
  const author = document.getElementById('rv-author')?.value.trim();
  const text   = document.getElementById('rv-text')?.value.trim();
  const ratings = state.pendingRatings;
  const missing = Object.entries(ratings).filter(([_, value]) => value === 0);
  if (!author) { toast('⚠️ Enter your name.', 'error'); return; }
  if (missing.length) { toast('⚠️ Choose a rating for ambiance, cleanliness, quality and service.', 'error'); return; }
  if (text.length < 20) { toast('⚠️ Comment too short (min. 20 characters).', 'error'); return; }

  // Save to database via API
  try {
    const res = await fetch('../controller/api_create_review.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify({
        restaurant_id: restaurantId,
        author: author,
        ambiance: ratings.ambiance,
        cleanliness: ratings.cleanliness,
        quality: ratings.quality,
        service: ratings.service,
        text: text
      })
    });
    const data = await res.json();
    
    if (!data.success) {
      toast('⚠️ ' + (data.error || 'Failed to save review'), 'error');
      return;
    }
  } catch (err) {
    console.error('Error saving review to DB:', err);
    toast('⚠️ Failed to save review', 'error');
    return;
  }

  // Also update state for immediate display
  const review = {
    restaurantId,
    author,
    rating: Math.round((ratings.ambiance + ratings.cleanliness + ratings.quality + ratings.service) / 4),
    ambiance: ratings.ambiance,
    cleanliness: ratings.cleanliness,
    quality: ratings.quality,
    service: ratings.service,
    text,
    date: new Date().toISOString().split('T')[0]
  };
  state.userReviews.push(review);

  const r = state.restaurants.find(x => x.id === restaurantId);
  if (r) {
    r.reviews.unshift({ ...review });
    r.avg = avgRating(r);
    r.score = calcScore(r);
  }

  closeCasserole();
  if (typeof renderGrid === 'function') renderGrid();
  if (typeof renderTopPicks === 'function') renderTopPicks();
  toast('✅ Review published successfully!');

  if (state.user) {
    state.user.reviewCount = (state.user.reviewCount || 0) + 1;
    localStorage.setItem('appetitus_user', JSON.stringify(state.user));
  }
}

// ===== AUTH HELPERS =====
function getCurrentUser() { return JSON.parse(localStorage.getItem('appetitus_user') || 'null'); }
function logout() {
  localStorage.removeItem('appetitus_user');
  // Also call server-side logout
  fetch('../controller/api_logout.php', { credentials: 'include' }).catch(() => {});
  window.location.href = 'index.php';
}

// ===== NAV USER STATE =====
function updateNavUser() {
  const u = getCurrentUser();
  const actionsEl = document.getElementById('nav-actions');
  if (!actionsEl) return;
  const themeButton = `
      <button id="theme-toggle" class="btn btn-outline theme-toggle" type="button" onclick="toggleTheme()">🌙 Dark</button>`;
  if (u) {
    const badge = getBadge(u.reviewCount || 0);
    actionsEl.innerHTML = `${themeButton}
      <a href="favorites.php" class="nav-icon-link" title="My Favorites">❤️</a>
      <a href="wishlist.php" class="nav-icon-link" title="My Wishlist">🔖</a>
      <a href="profile.php" class="badge ${badge.class}" style="text-decoration:none;">${badge.icon} ${badge.name}</a>
      <a href="profile.php" class="btn btn-ghost">👤 ${u.name}</a>
      <button class="btn btn-outline" onclick="logout()">Log Out</button>`;
  } else {
    actionsEl.innerHTML = `${themeButton}
      <a href="login.php" class="btn btn-ghost">Log In</a>
      <a href="signup.php" class="btn btn-primary">Sign Up →</a>`;
  }
  applyTheme(getStoredTheme());
}

// ===== REVEAL ANIMATION =====
function setupReveal() {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
}

// ===== COUNTER ANIMATION =====
function animateCounters() {
  document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count);
    const suffix = el.dataset.suffix || '';
    let cur = 0, inc = target / 60;
    const tick = () => { cur = Math.min(cur + inc, target); el.textContent = Math.floor(cur) + suffix; if (cur < target) requestAnimationFrame(tick); };
    tick();
  });
}

// ===== INIT CASSEROLE OVERLAY =====
function initCasseroleOverlay() {
  if (document.getElementById('casserole-overlay')) return;
  const el = document.createElement('div');
  el.id = 'casserole-overlay';
  el.innerHTML = `
    <!-- Phase 1: Grande casserole centrée -->
    <div id="pot-stage">
      <div class="big-pot-wrap">
        <div class="big-steam-wrap">
          <div class="big-steam-w"></div><div class="big-steam-w"></div>
          <div class="big-steam-w"></div><div class="big-steam-w"></div>
          <div class="big-steam-w"></div>
        </div>
        <div class="big-lid"></div>
        <div class="big-body">
          <div class="big-handle big-handle-l"></div>
          <div class="big-handle big-handle-r"></div>
        </div>
      </div>
    </div>
    <!-- Phase 3: Panel contenu -->
    <div id="casserole-panel">
      <div class="casserole-panel-inner" id="casserole-content"></div>
    </div>`;
  document.body.appendChild(el);
  el.addEventListener('click', e => {
    if (e.target === el || e.target.id === 'casserole-panel') closeCasserole();
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCasserole(); });
}

// ===== FETCH REAL REVIEW COUNT FROM DB =====
async function syncUserReviewCount() {
  if (!state.user) return;
  try {
    const res = await fetch('../controller/api_read_reviews.php?mine=1', { credentials: 'include' });
    const data = await res.json();
    if (res.status === 401) {
      console.warn('Session expired or unauthorized, logging out.');
      logout();
      return;
    }
    
    // Always sync if we have a state.user, even if data.data is []
    if (data.success && Array.isArray(data.data)) {
      const realCount = data.data.length;
      
      // Update state
      if (!state.user) {
         state.user = JSON.parse(localStorage.getItem('appetitus_user') || '{}');
      }
      
      state.user.reviewCount = realCount;
      state.userReviews = data.data;
      
      // Persist
      localStorage.setItem('appetitus_user', JSON.stringify(state.user));
      
      console.log(`Synced: ${realCount} reviews found for user.`);
      
      // Refresh components
      updateNavUser();
    }
  } catch (err) {
    console.error('Failed to sync review count:', err);
  }
}

// ===== SHARED INIT =====
document.addEventListener('DOMContentLoaded', () => {
  updateNavUser();
  initCasseroleOverlay();
  setupReveal();
  if (typeof animateCounters === 'function') animateCounters();
  if (!document.getElementById('toast')) {
    const t = document.createElement('div'); t.id = 'toast'; document.body.appendChild(t);
  }
  
  applyTheme(getStoredTheme());
  // Run these in parallel so they don't block each other
  loadRestaurants();
  loadUserFavoritesAndWishlist();
  syncUserReviewCount();
});
