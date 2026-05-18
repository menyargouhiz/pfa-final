<!DOCTYPE html>
<html>
<head>
  <title>Session Debug Test</title>
</head>
<body>
  <h1>Session Debug Test</h1>
  <button onclick="fixDbSchema()">🔧 FIX: Expand Password Column</button>
  <button onclick="checkUsers()">Check Users in DB</button>
  <button onclick="checkUsersPwd()">Check Users & Passwords</button>
  <button onclick="checkSession()">Check Session</button>
  <button onclick="testLogin()">Test Login</button>
  <pre id="output"></pre>

  <script>
    async function fixDbSchema() {
      const res = await fetch('../controller/api_fix_db_schema.php', {
        credentials: 'include'
      });
      const data = await res.json();
      document.getElementById('output').textContent = JSON.stringify(data, null, 2);
      alert('Database schema fixed! Now please sign up again to create a new account with proper password storage.');
    }

    async function checkUsers() {
      const res = await fetch('../controller/api_check_users.php', {
        credentials: 'include'
      });
      const data = await res.json();
      document.getElementById('output').textContent = JSON.stringify(data, null, 2);
    }

    async function checkUsersPwd() {
      const res = await fetch('../controller/api_check_users_pwd.php', {
        credentials: 'include'
      });
      const data = await res.json();
      document.getElementById('output').textContent = JSON.stringify(data, null, 2);
    }

    async function checkSession() {
      const res = await fetch('../controller/api_debug_session.php', {
        credentials: 'include'
      });
      const data = await res.json();
      document.getElementById('output').textContent = JSON.stringify(data, null, 2);
    }

    async function testLogin() {
      // Try to login with test credentials
      const res = await fetch('../controller/api_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ 
          email: 'test@test.com', 
          password: 'password123' 
        })
      });
      const data = await res.json();
      console.log('Login response:', data);
      document.getElementById('output').textContent = JSON.stringify(data, null, 2);
      
      // Then check session
      setTimeout(() => {
        checkSession();
      }, 500);
    }
  </script>
</body>
</html>
