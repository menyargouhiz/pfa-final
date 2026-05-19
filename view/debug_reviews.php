<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/review.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Reviews</title>
</head>
<body>

<h1>Debug: API Response</h1>

<h2>Raw Reviews from Database:</h2>
<pre id="debug-output">Loading...</pre>

<h2>Current User (from localStorage):</h2>
<pre id="user-output">Loading...</pre>

<script>
// Show current user
const user = JSON.parse(localStorage.getItem('appetitus_user') || 'null');
document.getElementById('user-output').textContent = JSON.stringify(user, null, 2);

// Fetch and show API response
fetch('../controller/api_read_reviews.php')
  .then(r => r.json())
  .then(data => {
    document.getElementById('debug-output').textContent = JSON.stringify(data, null, 2);
  })
  .catch(err => {
    document.getElementById('debug-output').textContent = 'Error: ' + err.message;
  });
</script>

</body>
</html>
