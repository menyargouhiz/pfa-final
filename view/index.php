<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Appetitus — Find the best restaurants</title>
  <meta name="description" content="Appetitus — Discover, rate and review the best restaurants. Community-powered intelligent ranking." />
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<nav id="navbar">
  <a class="nav-logo" href="index.php"><span class="logo-icon">🍽️</span> Appetitus</a>
  <ul class="nav-links">
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="explore.php">Explore</a></li>
    <li><a href="rankings.php">Top 10</a></li>
    <li><a href="user-search.php">Reviewers</a></li>
  </ul>
  <div class="nav-actions" id="nav-actions"></div>
</nav>

<!-- HERO -->
<section id="hero">
  <div class="hero-bg">
    <div class="hero-circle hero-circle-1"></div>
    <div class="hero-circle hero-circle-2"></div>
    <div class="hero-circle hero-circle-3"></div>
    <span class="food-float">🍕</span>
    <span class="food-float">🍜</span>
    <span class="food-float">🥗</span>
    <span class="food-float">🦞</span>
    <span class="food-float">🍷</span>
    <span class="food-float">🧁</span>
  </div>
  <!-- Hero steam decoration -->
  <div class="hero-steam-container">
    <div class="hero-steam-wisp"></div><div class="hero-steam-wisp"></div>
    <div class="hero-steam-wisp"></div><div class="hero-steam-wisp"></div>
    <div class="hero-steam-wisp"></div>
  </div>

  <div class="hero-inner">
    <div class="hero-eyebrow">✨ The foodie community</div>
    <h1 class="hero-title">
      Every meal deserves to be<br/><span class="cursive">unforgettable</span>
    </h1>
    <p class="hero-subtitle">
      Browse, rate, and review your local restaurants. Our algorithm highlights the best spots based on community feedback.
    </p>
    <div class="hero-cta">
      <a href="explore.php" class="btn btn-primary btn-lg">🔍 Explore Restaurants</a>
      <a href="signup.php" class="btn btn-ghost btn-lg">Join Appetitus</a>
    </div>
    <div class="hero-stats">
      <div class="hero-stat"><div class="num" data-count="36" data-suffix="+">0</div><div class="lbl">Restaurants</div></div>
      <div class="hero-stat"><div class="num" data-count="125" data-suffix="+">0</div><div class="lbl">Verified Reviews</div></div>
      <div class="hero-stat"><div class="num" data-count="24" data-suffix="">0</div><div class="lbl">Regions</div></div>
    </div>
  </div>
</section>

<!-- CATEGORIES -->
<section class="section" style="padding-top:2rem;padding-bottom:3rem;">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-eyebrow">🎨 Explore by style</div>
      <h2 class="section-title">Choose a cuisine</h2>
    </div>
    <div class="category-grid reveal">
      <div class="category-tile" data-category="gastronomique" onclick="window.location='explore.php?cat=gastronomique'">
        <div class="tile-icon">⭐</div><div class="tile-label">Fine Dining</div>
      </div>
      <div class="category-tile" data-category="asiatique" onclick="window.location='explore.php?cat=asiatique'">
        <div class="tile-icon">🥢</div><div class="tile-label">Asian</div>
      </div>
      <div class="category-tile" data-category="italien" onclick="window.location='explore.php?cat=italien'">
        <div class="tile-icon">🍕</div><div class="tile-label">Italian</div>
      </div>
      <div class="category-tile" data-category="fruits-de-mer" onclick="window.location='explore.php?cat=fruits-de-mer'">
        <div class="tile-icon">🦞</div><div class="tile-label">Seafood</div>
      </div>
      <div class="category-tile" data-category="street-food" onclick="window.location='explore.php?cat=street-food'">
        <div class="tile-icon">🌮</div><div class="tile-label">Street Food</div>
      </div>
      <div class="category-tile" data-category="brasserie" onclick="window.location='explore.php?cat=brasserie'">
        <div class="tile-icon">🍺</div><div class="tile-label">Pubs</div>
      </div>
      <div class="category-tile" data-category="africaine" onclick="window.location='explore.php?cat=africaine'">
        <div class="tile-icon">🌍</div><div class="tile-label">African</div>
      </div>
    </div>
  </div>
</section>

<!-- TOP PICKS -->
<section class="section" style="background:var(--bg-surface);padding-top:4rem;">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-eyebrow">🏆 Relevance Algorithm</div>
      <h2 class="section-title">Top picks of the moment</h2>
      <p class="section-sub">Automatic selection: rating, popularity, and review freshness.</p>
    </div>
    <div class="top-picks-grid reveal" id="top-picks"></div>
    <div style="text-align:center;margin-top:2rem;" class="reveal">
      <a href="explore.php" class="btn btn-outline btn-lg">See all restaurants →</a>
    </div>
  </div>
</section>

<!-- BADGES SECTION -->
<section class="section">
  <div class="container">
    <div class="section-header reveal">
      <div class="section-eyebrow">🎖️ Loyalty Program</div>
      <h2 class="section-title">Unlock badges</h2>
      <p class="section-sub">The more you review, the higher you rank. Join the community!</p>
    </div>
    <div class="badges-showcase reveal" id="badges-showcase"></div>
  </div>
</section>

<footer>
  <div class="footer-logo">🍽️ Appetitus</div>
  <div class="footer-tagline">The community for food lovers</div>
  <div class="footer-links">
    <a href="index.php">Home</a>
    <a href="explore.php">Explore</a>
    <a href="user-search.php">Reviewers</a>
    <a href="favorites.php">Favorites</a>
    <a href="wishlist.php">Wishlist</a>
    <a href="login.php">Log In</a>
    <a href="signup.php">Sign Up</a>
  </div>
  <div class="footer-copy">© 2026 Appetitus — Made with ❤️ and a lot of culinary curiosity</div>
</footer>

<script src="../script/app.js"></script>
<script>
function renderTopPicks() {
  const top = [...state.restaurants].sort((a,b) => b.score - a.score).slice(0, 4);
  const medals = ['🥇','🥈','🥉','🏅'];
  document.getElementById('top-picks').innerHTML = top.map((r,i) => `
    <div class="top-pick" data-category="${r.category}" style="--reveal-delay:${i * 80}ms" onclick="openCasserole(${r.id})">
      <div class="top-pick-img"><img src="${r.image}" alt="${r.name}" loading="lazy" /></div>
      <div class="top-pick-body">
        <div class="top-pick-medal">${medals[i]}</div>
        <div class="top-pick-name">${r.name}</div>
        <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:.4rem;">📍 ${r.city} · ${r.priceRange}</div>
        <div class="stars">${renderStars(r.avg)} <span class="rating-count">${r.avg.toFixed(1)}</span></div>
        <div class="score-bar" style="margin-top:.6rem;">
          <div class="score-bar-fill" style="width:${r.score}%"></div>
        </div>
        <div class="score-label">Score ${r.score}/100 · ${r.reviews.length} reviews</div>
        <div class="top-pick-actions" style="margin-top:1rem; display:flex; gap:.75rem; flex-wrap:wrap;">
          <a href="rate.php?restaurant=${r.id}" class="btn btn-outline" style="flex:1; text-align:center;" onclick="event.stopPropagation()">Rate</a>
          <a href="explore.php?restaurant=${r.id}" class="btn btn-primary" style="flex:1; text-align:center;" onclick="event.stopPropagation()">Details</a>
        </div>
      </div>
    </div>`).join('');
}

function renderBadgesShowcase() {
  const userCount = (state.userReviews || []).length;
  document.getElementById('badges-showcase').innerHTML = BADGES.map((b, i) => {
    const earned = userCount >= b.min && (b.max === Infinity || userCount <= b.max) || (b.id === 'nouveau' && userCount === 0);
    return `
    <div class="badge-card ${earned ? 'earned' : 'locked'}" style="--reveal-delay:${i * 55}ms">
      <div class="bicon">${b.icon}</div>
      <div class="bname">${b.name}</div>
      <div class="bdesc">${b.desc}</div>
      ${earned ? '<div style="margin-top:.4rem;font-size:.68rem;color:var(--accent);font-weight:700;">✓ Unlocked</div>' : ''}
    </div>`;
  }).join('');
}
</script>
</body>
</html>
