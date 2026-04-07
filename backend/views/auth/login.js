const layout = require('../layout');

function login(errorMsg = '') {
  const content = `
    <h1>Login</h1>
    ${errorMsg ? `<p class="error">${errorMsg}</p>` : ''}
    <form method="POST" action="/auth/login">
      <label>Email:</label>
      <input type="email" name="email" required>
      <label>Password:</label>
      <input type="password" name="password" required>
      <br><br>
      <button type="submit" class="btn">Login</button>
    </form>
    <br>
    <a href="/">Back to Home</a>
  `;
  return layout('Login', content);
}

module.exports = login;
