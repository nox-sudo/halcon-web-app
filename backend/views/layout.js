// views/layout.js
// Shared HTML wrapper for all pages

function layout(title, content, user = null) {
  return `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${title} - Halcon</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
    nav { background: #333; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
    nav a { color: white; text-decoration: none; margin-right: 15px; }
    nav a:hover { text-decoration: underline; }
    .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
    h1 { color: #333; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
    th { background: #333; color: white; }
    tr:nth-child(even) { background: #f9f9f9; }
    .btn { display: inline-block; padding: 6px 12px; background: #333; color: white; text-decoration: none; border: none; cursor: pointer; border-radius: 3px; font-size: 14px; }
    .btn-sm { padding: 4px 8px; font-size: 12px; }
    .btn-danger { background: #c0392b; }
    .btn-success { background: #27ae60; }
    .btn-info { background: #2980b9; }
    .btn-warning { background: #e67e22; }
    form { background: white; padding: 20px; border-radius: 4px; max-width: 600px; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input, select, textarea { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 3px; }
    .error { color: red; margin-bottom: 10px; }
    .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 12px; }
    .badge-ordered { background: #bdc3c7; }
    .badge-process { background: #f39c12; color: white; }
    .badge-route { background: #2980b9; color: white; }
    .badge-delivered { background: #27ae60; color: white; }
    .badge-inactive { background: #e74c3c; color: white; }
    .alert { padding: 10px; background: #dff0d8; border: 1px solid #3c763d; color: #3c763d; margin-bottom: 15px; border-radius: 3px; }
    .links { margin-top: 15px; }
    .links a { margin-right: 10px; }
  </style>
</head>
<body>
  <nav>
    <div>
      <strong>Halcon</strong>
      ${user ? `
        <a href="/dashboard" style="margin-left:20px">Dashboard</a>
        <a href="/orders">Orders</a>
        <a href="/users">Users</a>
        <a href="/orders/archived">Archived</a>
      ` : ''}
    </div>
    <div>
      ${user
        ? `<span style="margin-right:15px">Hello, ${user.nombre}</span><a href="/auth/logout">Logout</a>`
        : `<a href="/">Home</a> <a href="/auth/login">Login</a>`
      }
    </div>
  </nav>
  <div class="container">
    ${content}
  </div>
</body>
</html>`;
}

module.exports = layout;
