const layout = require('../layout');

function dashboard(user) {
  const content = `
    <h1>Dashboard</h1>
    <p>Welcome, <strong>${user.nombre}</strong> — Role: ${user.rol}</p>
    <hr>
    <h2>Quick Links</h2>
    <div class="links">
      <a href="/orders" class="btn">All Orders</a>
      <a href="/orders/create" class="btn btn-success">New Order</a>
      <a href="/orders/archived" class="btn btn-warning">Archived Orders</a>
      <a href="/users" class="btn btn-info">Users</a>
      <a href="/users/create" class="btn">New User</a>
    </div>
  `;
  return layout('Dashboard', content, user);
}

module.exports = dashboard;
