<!DOCTYPE html>
<html>
<head>
  <title>DB Users Check</title>
</head>
<body>
  <h1>Database Users</h1>
  <button onclick="checkUsers()">Check Users in DB</button>
  <pre id="output"></pre>

  <script>
    async function checkUsers() {
      const res = await fetch('../controller/api_read_reviews.php', {
        credentials: 'include'
      });
      const data = await res.json();
      document.getElementById('output').textContent = JSON.stringify(data, null, 2);
    }
  </script>
</body>
</html>
