<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Review.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
        }
        .review-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .review-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
        }
        .restaurant-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .rating {
            color: #ffc107;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .review-text {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .review-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #999;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: white;
        }
        .empty-state h2 {
            margin-bottom: 20px;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: #333;
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Header -->
        <div class="header">
            <h1>📝 My Published Reviews</h1>
            <p class="text-muted mb-0">View all the reviews you've posted</p>
        </div>

        <!-- Reviews List -->
        <div id="reviewsContainer">
            <div class="text-center text-white" style="padding: 40px;">
                <p>Loading your reviews...</p>
            </div>
        </div>

    </div>

    <script>
        async function loadMyReviews() {
            try {
                // Get all reviews from the database
                const res = await fetch('../controller/api_read_reviews.php');
                const data = await res.json();

                if (!data.success || !data.data) {
                    showEmpty();
                    return;
                }

                // Get current user from session or localStorage
                const userJson = localStorage.getItem('appetitus_user');
                const user = userJson ? JSON.parse(userJson) : null;
                const currentUserName = user?.name || 'Unknown';

                // Filter reviews by current user (author name)
                const myReviews = data.data.filter(review => review.author === currentUserName);

                if (myReviews.length === 0) {
                    showEmpty();
                    return;
                }

                // Fetch restaurant data to show names
                const restaurantsRes = await fetch('../controller/get_restaurants.php');
                const restaurants = await restaurantsRes.json();

                // Create a map of restaurant id to name
                const restaurantMap = {};
                restaurants.forEach(r => {
                    restaurantMap[r.id] = r.name;
                });

                renderReviews(myReviews, restaurantMap);

            } catch (err) {
                console.error('Error:', err);
                showError('Failed to load reviews');
            }
        }

        function renderReviews(reviews, restaurantMap) {
            const container = document.getElementById('reviewsContainer');
            
            container.innerHTML = reviews.map(review => `
                <div class="review-card">
                    <div class="restaurant-name">
                        🍽️ ${restaurantMap[review.restaurant_id] || 'Restaurant #' + review.restaurant_id}
                    </div>
                    <div class="rating">
                        ${'⭐'.repeat(review.rating)} (${review.rating}/5)
                    </div>
                    <div class="review-text">
                        "${review.text}"
                    </div>
                    <div class="review-footer">
                        <span>📅 ${formatDate(review.date)}</span>
                        <button class="btn-delete" onclick="deleteReview(${review.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }

        function showEmpty() {
            const container = document.getElementById('reviewsContainer');
            container.innerHTML = `
                <div class="empty-state">
                    <h2>No reviews yet</h2>
                    <p>You haven't published any reviews. Start exploring restaurants and share your thoughts!</p>
                    <a href="explore.php" class="btn btn-light" style="margin-top: 20px;">📍 Explore Restaurants</a>
                </div>
            `;
        }

        function showError(message) {
            const container = document.getElementById('reviewsContainer');
            container.innerHTML = `
                <div class="empty-state">
                    <h2>⚠️ Error</h2>
                    <p>${message}</p>
                </div>
            `;
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        async function deleteReview(reviewId) {
            if (!confirm('Are you sure you want to delete this review?')) {
                return;
            }

            try {
                const res = await fetch('../controller/api_delete_review.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ review_id: reviewId })
                });

                const data = await res.json();

                if (!data.success) {
                    alert('Failed to delete review: ' + data.error);
                    return;
                }

                alert('Review deleted successfully');
                loadMyReviews(); // Reload the list

            } catch (err) {
                console.error('Error:', err);
                alert('Failed to delete review');
            }
        }

        // Load reviews when page loads
        loadMyReviews();
    </script>

</body>
</html>