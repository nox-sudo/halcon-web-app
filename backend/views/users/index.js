const layout = require('../layout');

function list(users, currentUser) {
  const rows = users.map(u => `
    <tr>
      <td>${u.id}</td>
      <td>${u.nombre}</td>
      <td>${u.email}</td>
      <td>${u.rol ? u.rol.nombre : 'No role'}</td>
      <td>
        ${u.activo === 1
          ? '<span class="badge badge-delivered">Active</span>'
          : '<span class="badge badge-inactive">Inactive</span>'
        }
      </td>
      <td>
        <a href="/users/edit/${u.id}" class="btn btn-sm btn-info">Edit</a>
      </td>
    </tr>
  `).join('');

  const content = `
    <h1>Users</h1>
    <a href="/users/create" class="btn btn-success">+ New User</a>
    <br><br>
    <table>
      <thead>
        <tr>
          <th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>${rows}</tbody>
    </table>
    <br>
    <a href="/dashboard">Back to Dashboard</a>
  `;
  return layout('Users', content, currentUser);
}

function create(roles, currentUser, errorMsg = '') {
  const roleOptions = roles.map(r => `<option value="${r.id}">${r.nombre}</option>`).join('');
  const content = `
    <h1>New User</h1>
    ${errorMsg ? `<p class="error">${errorMsg}</p>` : ''}
    <form method="POST" action="/users/create">
      <label>Full Name:</label>
      <input type="text" name="nombre" required>
      <label>Email:</label>
      <input type="email" name="email" required>
      <label>Password:</label>
      <input type="password" name="password" required>
      <label>Role:</label>
      <select name="rol_id">
        <option value="">-- No role --</option>
        ${roleOptions}
      </select>
      <br><br>
      <button type="submit" class="btn btn-success">Create User</button>
      <a href="/users" class="btn" style="margin-left:10px">Cancel</a>
    </form>
  `;
  return layout('New User', content, currentUser);
}

function edit(user, roles, currentUser) {
  const roleOptions = roles.map(r =>
    `<option value="${r.id}" ${user.rol_id === r.id ? 'selected' : ''}>${r.nombre}</option>`
  ).join('');

  const content = `
    <h1>Edit User: ${user.nombre}</h1>
    <form method="POST" action="/users/edit/${user.id}">
      <label>Full Name:</label>
      <input type="text" name="nombre" value="${user.nombre}" required>
      <label>Email:</label>
      <input type="email" name="email" value="${user.email}" required>
      <label>New Password (leave blank to keep current):</label>
      <input type="password" name="password" placeholder="Leave blank to keep current password">
      <label>Role:</label>
      <select name="rol_id">
        <option value="">-- No role --</option>
        ${roleOptions}
      </select>
      <label>Status:</label>
      <select name="activo">
        <option value="1" ${user.activo === 1 ? 'selected' : ''}>Active</option>
        <option value="0" ${user.activo === 0 ? 'selected' : ''}>Inactive</option>
      </select>
      <br><br>
      <button type="submit" class="btn btn-success">Save Changes</button>
      <a href="/users" class="btn" style="margin-left:10px">Cancel</a>
    </form>
  `;
  return layout('Edit User', content, currentUser);
}

module.exports = { list, create, edit };
