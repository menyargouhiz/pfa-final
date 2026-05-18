// ===== APPETITUS APP.JS =====

// State data
window.state = {
  restaurants: [],
  userReviews: JSON.parse(localStorage.getItem('appetitus_reviews') || '[]')
};

// Badges system
window.BADGES = [
  { id: 'nouveau', name: 'Nouveau', desc: 'First steps in the community', icon: '👶', min: 0, max: 0 },
  { id: 'critique', name: 'Critique', desc: 'Left your first review', icon: '✍️', min: 1, max: 4 },
  { id: 'gourmet', name: 'Gourmet', desc: '5 reviews shared', icon: '🍽️', min: 5, max: 9 },
  { id: 'expert', name: 'Expert', desc: '10 reviews and counting', icon: '⭐', min: 10, max: 19 },
  { id: 'maitre', name: 'Maître', desc: '20+ reviews, true foodie', icon: '👨‍🍳', min: 20, max: 49 },
  { id: 'maestro', name: 'Maestro', desc: '50+ reviews, community leader', icon: '💎', min: 50, max: Infinity }
];

// Utility functions
function getCurrentUser() {
  const stored = localStorage.getItem('appetitus_user');
  return stored ? JSON.parse(stored) : null;
}

function getCurrentRestaurant() {
  const stored = localStorage.getItem('appetitus_restaurant');
  return stored ? JSON.parse(stored) : null;
}

function getBadge(reviewCount) {
  return BADGES.find(b => reviewCount >= b.min && (b.max === Infinity || reviewCount <= b.max)) || BADGES[0];
}

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

function renderStars(rating) {
  const full = Math.floor(rating);
  const half = rating % 1 >= 0.5 ? 1 : 0;
  const empty = 5 - full - half;
  return '★'.repeat(full) + '☆'.repeat(half) + '☆'.repeat(empty);
}

function animateCounters() {
  document.querySelectorAll('.num[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count);
    const suffix = el.dataset.suffix || '';
    let current = 0;
    const increment = target / 100;
    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      el.textContent = Math.floor(current) + suffix;
    }, 20);
  });
}

function setupReveal() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
}

function toast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${type === 'error' ? '#e74c3c' : '#27ae60'};
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    z-index: 1000;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
  `;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

function logout() {
  localStorage.removeItem('appetitus_user');
  window.location.href = 'index.php';
}

function fmtDate(dateStr) {
  return new Date(dateStr).toLocaleDateString();
}

function loadRestaurants() {
  const url = '../controller/get_restaurants.php';
  try {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', url, false);
    xhr.send(null);
    if (xhr.status === 200) {
      const restaurants = JSON.parse(xhr.responseText);
      if (Array.isArray(restaurants)) {
        window.state.restaurants = restaurants.map(r => {
          const reviews = Array.isArray(r.reviews) ? r.reviews : [];
          const avg = reviews.length ? reviews.reduce((sum, rv) => sum + Number(rv.rating || 0), 0) / reviews.length : 0;
          const count = reviews.length;
          const days = count && reviews[0]?.date ? (Date.now() - new Date(reviews[0].date)) / 86400000 : 0;
          const freshness = Math.max(0, 1 - days / 180);
          const score = Math.round((avg / 5) * 60 + Math.min(count / 20, 1) * 30 + freshness * 10);
          return {
            ...r,
            tags: Array.isArray(r.tags) ? r.tags : (typeof r.tags === 'string' ? r.tags.split(',').map(t => t.trim()).filter(Boolean) : []),
            reviews,
            avg,
            score
          };
        });
      } else {
        console.error('Invalid restaurants response', restaurants);
      }
    } else {
      console.error('Could not load restaurants from DB: ', xhr.status, xhr.statusText);
    }
  } catch (err) {
    console.error('Error loading restaurants from DB:', err);
  }
}

loadRestaurants();

// Initialize nav actions
document.addEventListener('DOMContentLoaded', () => {
  const user = getCurrentUser();
  const navActions = document.getElementById('nav-actions');
  if (navActions && !navActions.innerHTML.trim()) {
    const themeButton = `
      <button id="theme-toggle" class="btn btn-outline theme-toggle" type="button" onclick="toggleTheme()">🌙 Dark</button>`;
    if (user) {
      navActions.innerHTML = `
        ${themeButton}
        <span style="color:var(--text-secondary);font-size:.9rem;margin-right:1rem;">Hi, ${user.name}!</span>
        <a href="profile.php" class="btn btn-ghost">Profile</a>
        <button class="btn btn-ghost" onclick="logout()">Logout</button>
      `;
    } else {
      navActions.innerHTML = `
        ${themeButton}
        <a href="login.php" class="btn btn-ghost">Log In</a>
        <a href="signup.php" class="btn btn-primary">Sign Up</a>
      `;
    }
    applyTheme(getStoredTheme());
  }
});


