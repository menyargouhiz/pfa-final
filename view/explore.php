<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Explore — Appetitus</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav id="navbar">
  <a class="nav-logo" href="index.php"><span class="logo-icon">🍽️</span> Appetitus</a>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="explore.php" class="active">Explore</a></li>
    <li><a href="rankings.php">Top 10</a></li>
  </ul>
  <div class="nav-actions" id="nav-actions"></div>
</nav>

<!-- PAGE HERO / SEARCH -->
<div class="page-hero">
  <div class="container">
    <div class="page-hero-title">🔍 Explore Restaurants</div>
    <div class="page-hero-sub" style="margin-bottom:1.5rem;">Filter, sort and find your next table.</div>
    <div class="search-bar" style="max-width:580px;">
      <input class="search-input" id="search-q" placeholder="Restaurant, cuisine, dish..." autocomplete="off" />
      <div class="search-divider"></div>
      <input class="search-input" id="search-city" placeholder="City..." autocomplete="off" style="max-width:160px;" />
      <button class="btn btn-primary" id="search-btn" style="border-radius:50px;">Search</button>
    </div>
  </div>
</div>

<!-- EXPLORE LAYOUT -->
<div class="section">
  <div class="container">
    <div class="explore-layout">

      <!-- SIDEBAR FILTERS -->
      <aside class="filter-sidebar">
        <div class="filter-heading">Filters</div>

        <div class="filter-group">
          <div class="filter-group-label">🍽️ Cuisine</div>
          <div id="cuisine-filters">
            <div class="filter-option active" data-cat="all" onclick="setCat('all',this)">
              <span class="filter-dot" style="background:var(--accent)"></span> All
            </div>
            <div class="filter-option" data-cat="gastronomique" onclick="setCat('gastronomique',this)" data-category="gastronomique">
              <span class="filter-dot"></span> ⭐ Fine Dining
            </div>
            <div class="filter-option" data-cat="asiatique" onclick="setCat('asiatique',this)" data-category="asiatique">
              <span class="filter-dot"></span> 🥢 Asian
            </div>
            <div class="filter-option" data-cat="italien" onclick="setCat('italien',this)" data-category="italien">
              <span class="filter-dot"></span> 🍕 Italian
            </div>
            <div class="filter-option" data-cat="fruits-de-mer" onclick="setCat('fruits-de-mer',this)" data-category="fruits-de-mer">
              <span class="filter-dot"></span> 🦞 Seafood
            </div>
            <div class="filter-option" data-cat="street-food" onclick="setCat('street-food',this)" data-category="street-food">
              <span class="filter-dot"></span> 🌮 Street Food
            </div>
            <div class="filter-option" data-cat="brasserie" onclick="setCat('brasserie',this)" data-category="brasserie">
              <span class="filter-dot"></span> 🍺 Pubs
            </div>
            <div class="filter-option" data-cat="africaine" onclick="setCat('africaine',this)" data-category="africaine">
              <span class="filter-dot"></span> 🌍 African
            </div>
          </div>
        </div>

        <div class="filter-group">
          <div class="filter-group-label">💰 Price</div>
          <div id="price-filters">
            <div class="filter-option active" data-price="all" onclick="setPrice('all',this)"><span class="filter-dot" style="background:var(--accent)"></span> All</div>
            <div class="filter-option" data-price="€" onclick="setPrice('€',this)"><span class="filter-dot"></span> € Low Budget</div>
            <div class="filter-option" data-price="€€" onclick="setPrice('€€',this)"><span class="filter-dot"></span> €€ Medium</div>
            <div class="filter-option" data-price="€€€" onclick="setPrice('€€€',this)"><span class="filter-dot"></span> €€€ High-end</div>
          </div>
        </div>

        <div class="filter-group">
          <div class="filter-group-label">⭐ Minimum Rating</div>
          <div id="rating-filters">
            <div class="filter-option active" data-rating="0" onclick="setMinRating(0,this)"><span class="filter-dot" style="background:var(--accent)"></span> All</div>
            <div class="filter-option" data-rating="3" onclick="setMinRating(3,this)"><span class="filter-dot"></span> ★★★ and up</div>
            <div class="filter-option" data-rating="4" onclick="setMinRating(4,this)"><span class="filter-dot"></span> ★★★★ and up</div>
            <div class="filter-option" data-rating="4.5" onclick="setMinRating(4.5,this)"><span class="filter-dot"></span> ★★★★½ and up</div>
          </div>
        </div>

        <div class="filter-group">
          <div class="filter-group-label">📍 Region</div>
          <div id="city-filters">
            <div class="filter-option active" data-city="all" onclick="setCityFilter('all',this)"><span class="filter-dot" style="background:var(--accent)"></span> All</div>
            <div class="filter-option" data-city="Tunis" onclick="setCityFilter('Tunis',this)"><span class="filter-dot"></span> 🕌 Tunis</div>
            <div class="filter-option" data-city="Nabeul" onclick="setCityFilter('Nabeul',this)"><span class="filter-dot"></span> 🏖️ Nabeul</div>
            <div class="filter-option" data-city="Sousse" onclick="setCityFilter('Sousse',this)"><span class="filter-dot"></span> 🌊 Sousse</div>
            <div class="filter-option" data-city="Sfax" onclick="setCityFilter('Sfax',this)"><span class="filter-dot"></span> ⚓ Sfax</div>
            <div class="filter-option" data-city="Tozeur" onclick="setCityFilter('Tozeur',this)"><span class="filter-dot"></span> 🌴 Tozeur</div>
          </div>
        </div>

        <button class="btn btn-ghost btn-full" onclick="resetFilters()" style="border-radius:var(--radius-sm);margin-top:.5rem;">
          ↺ Reset Filters
        </button>
      </aside>

      <!-- GRID -->
      <div>
        <div class="sort-row">
          <select class="sort-select" id="sort-select" onchange="renderGrid()">
            <option value="score">⚡ Relevance</option>
            <option value="rating">⭐ Rating</option>
            <option value="reviews">💬 Reviews</option>
            <option value="name">🔤 Name A→Z</option>
            <option value="price-asc">💰 Price ↑</option>
            <option value="price-desc">💰 Price ↓</option>
          </select>
          <div class="results-count" id="results-count">— restaurants</div>
        </div>
        <div class="restaurant-grid" id="restaurant-grid"></div>
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
    <a href="login.php">Log In</a><a href="signup.php">Sign Up</a>
  </div>
  <div class="footer-copy">© 2026 Appetitus</div>
</footer>

<script src="../script/app.js"></script>
<script>
const filters = { cat:'all', price:'all', minRating:0, city:'all', query:'', queryCity:'' };

function setCat(v, el) {
  filters.cat = v;
  document.querySelectorAll('#cuisine-filters .filter-option').forEach(e => e.classList.remove('active'));
  el.classList.add('active'); renderGrid();
}
function setPrice(v, el) {
  filters.price = v;
  document.querySelectorAll('#price-filters .filter-option').forEach(e => e.classList.remove('active'));
  el.classList.add('active'); renderGrid();
}
function setMinRating(v, el) {
  filters.minRating = parseFloat(v);
  document.querySelectorAll('#rating-filters .filter-option').forEach(e => e.classList.remove('active'));
  el.classList.add('active'); renderGrid();
}
function setCityFilter(v, el) {
  filters.city = v;
  document.querySelectorAll('#city-filters .filter-option').forEach(e => e.classList.remove('active'));
  el.classList.add('active'); renderGrid();
}
function resetFilters() {
  filters.cat = 'all'; filters.price = 'all'; filters.minRating = 0; filters.city = 'all';
  filters.query = ''; filters.queryCity = '';
  document.getElementById('search-q').value = '';
  document.getElementById('search-city').value = '';
  document.querySelectorAll('.filter-option').forEach(e => e.classList.remove('active'));
  document.querySelectorAll('.filter-option[data-cat="all"], .filter-option[data-price="all"], .filter-option[data-rating="0"], .filter-option[data-city="all"]').forEach(e => e.classList.add('active'));
  renderGrid();
}

function getFiltered() {
  let list = [...state.restaurants];
  if (filters.cat !== 'all')      list = list.filter(r => r.category === filters.cat);
  if (filters.price !== 'all')    list = list.filter(r => r.priceRange === filters.price);
  if (filters.minRating > 0)      list = list.filter(r => r.avg >= filters.minRating);
  if (filters.city !== 'all')     list = list.filter(r => r.region === filters.city);
  if (filters.query) {
    const q = filters.query.toLowerCase();
    list = list.filter(r => r.name.toLowerCase().includes(q) || r.cuisine.toLowerCase().includes(q) || r.tags.some(t => t.toLowerCase().includes(q)));
  }
  if (filters.queryCity) {
    const c = filters.queryCity.toLowerCase();
    list = list.filter(r => r.city.toLowerCase().includes(c));
  }
  const sort = document.getElementById('sort-select')?.value || 'score';
  switch(sort) {
    case 'score':      list.sort((a,b) => b.score - a.score); break;
    case 'rating':     list.sort((a,b) => b.avg - a.avg); break;
    case 'reviews':    list.sort((a,b) => b.reviews.length - a.reviews.length); break;
    case 'name':       list.sort((a,b) => a.name.localeCompare(b.name)); break;
    case 'price-asc':  list.sort((a,b) => a.priceRange.length - b.priceRange.length); break;
    case 'price-desc': list.sort((a,b) => b.priceRange.length - a.priceRange.length); break;
  }
  return list;
}

function renderGrid() {
  const list = getFiltered();
  const grid = document.getElementById('restaurant-grid');
  const countEl = document.getElementById('results-count');
  countEl.textContent = `${list.length} restaurant${list.length!==1?'s':''} found`;
  if (!list.length) {
    grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:4rem 1rem;color:var(--text-muted);">
      <div style="font-size:3rem;margin-bottom:1rem;">🍽️</div>
      <p>No restaurants match your filters.</p>
      <button class="btn btn-outline" style="margin-top:1rem;border-radius:var(--radius-sm)" onclick="resetFilters()">Reset</button>
    </div>`;
    return;
  }
  grid.innerHTML = list.map((r, i) => `
    <div class="restaurant-card reveal" data-category="${r.category}" onclick="openCasserole(${r.id})">
      <div class="card-img-wrap">
        <img src="${r.image}" alt="${r.name}" loading="lazy" />
        <div class="card-steam">
          <div class="steam-wisp"></div><div class="steam-wisp"></div>
          <div class="steam-wisp"></div><div class="steam-wisp"></div>
          <div class="steam-wisp"></div><div class="steam-wisp"></div>
        </div>
        <div class="card-cuisine">${r.cuisine}</div>
        <div style="position:absolute;top:.7rem;right:.7rem;display:flex;gap:.35rem;z-index:10;">
          <button class="fav-wish-btn fav-btn ${isFavorited(r.id)?'active':''}" data-fav-id="${r.id}" onclick="event.stopPropagation(); toggleFavorite(${r.id}, event)" title="${isFavorited(r.id)?'Remove from favorites':'Add to favorites'}" style="width:32px;height:32px;font-size:.9rem;">${isFavorited(r.id)?'❤️':'🤍'}</button>
          <button class="fav-wish-btn wish-btn ${isWishlisted(r.id)?'active':''}" data-wish-id="${r.id}" onclick="event.stopPropagation(); toggleWishlist(${r.id}, event)" title="${isWishlisted(r.id)?'Remove from wishlist':'Add to wishlist'}" style="width:32px;height:32px;font-size:.9rem;">${isWishlisted(r.id)?'🔖':'📑'}</button>
        </div>
      </div>
      <div class="card-body">
        <div class="card-name">${r.name}</div>
        <div class="card-loc">📍 ${r.city}</div>
        <div class="stars">${renderStars(r.avg)} <span class="rating-count">${r.avg.toFixed(1)} · ${r.reviews.length} reviews</span></div>
        <div class="card-tags">${r.tags.map(t => `<span class="tag">${t}</span>`).join('')}</div>
        <div class="card-footer">
          <span class="score-pill">⚡ ${r.score}/100</span>
          <span class="card-price">${r.priceRange}</span>
        </div>
      </div>
      <div class="casserole-hint">🫕</div>
    </div>`).join('');
  setupReveal();
  document.querySelectorAll('.reveal').forEach(el => {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight) el.classList.add('visible');
  });
}

// Search button
document.getElementById('search-btn').addEventListener('click', () => {
  filters.query = document.getElementById('search-q').value.trim();
  filters.queryCity = document.getElementById('search-city').value.trim();
  renderGrid();
});
document.getElementById('search-q').addEventListener('keydown', e => { if (e.key==='Enter') document.getElementById('search-btn').click(); });
document.getElementById('search-city').addEventListener('keydown', e => { if (e.key==='Enter') document.getElementById('search-btn').click(); });

// Handled by app.js async loader
window.initExplore = function() {
  const params = new URLSearchParams(window.location.search);
  const cat = params.get('cat');
  if (cat) {
    const el = document.querySelector(`[data-cat="${cat}"]`);
    if (el) { setCat(cat, el); return; }
  }
  renderGrid();
};
</script>
</body>
</html>
