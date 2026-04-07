const layout = require('../layout');

function statusBadge(statusName) {
  const map = { 'Ordered': 'ordered', 'In Process': 'process', 'In Route': 'route', 'Delivered': 'delivered' };
  const cls = map[statusName] || 'ordered';
  return `<span class="badge badge-${cls}">${statusName}</span>`;
}

function list(orders, currentUser) {
  const rows = orders.map(o => `
    <tr>
      <td>${o.numero_factura}</td>
      <td>${o.nombre_cliente}</td>
      <td>${o.numero_cliente}</td>
      <td>${o.estado ? statusBadge(o.estado.nombre) : '-'}</td>
      <td>${new Date(o.fecha_hora).toLocaleDateString()}</td>
      <td>
        <a href="/orders/${o.id}" class="btn btn-sm btn-info">View</a>
        <a href="/orders/${o.id}/edit" class="btn btn-sm btn-warning">Edit</a>
        <form method="POST" action="/orders/${o.id}/delete" style="display:inline">
          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this order?')">Delete</button>
        </form>
      </td>
    </tr>
  `).join('');

  const content = `
    <h1>Orders</h1>
    <a href="/orders/create" class="btn btn-success">+ New Order</a>
    <a href="/orders/archived" class="btn btn-warning" style="margin-left:10px">Archived</a>
    <br><br>
    <table>
      <thead>
        <tr>
          <th>Invoice #</th><th>Client</th><th>Client #</th><th>Status</th><th>Date</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>${rows.length ? rows : '<tr><td colspan="6">No orders found.</td></tr>'}</tbody>
    </table>
    <br>
    <a href="/dashboard">Back to Dashboard</a>
  `;
  return layout('Orders', content, currentUser);
}

function archived(orders, currentUser) {
  const rows = orders.map(o => `
    <tr>
      <td>${o.numero_factura}</td>
      <td>${o.nombre_cliente}</td>
      <td>${o.estado ? statusBadge(o.estado.nombre) : '-'}</td>
      <td>${new Date(o.fecha_hora).toLocaleDateString()}</td>
      <td>
        <form method="POST" action="/orders/${o.id}/restore" style="display:inline">
          <button type="submit" class="btn btn-sm btn-success">Restore</button>
        </form>
      </td>
    </tr>
  `).join('');

  const content = `
    <h1>Archived Orders</h1>
    <table>
      <thead>
        <tr>
          <th>Invoice #</th><th>Client</th><th>Status</th><th>Date</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>${rows.length ? rows : '<tr><td colspan="5">No archived orders.</td></tr>'}</tbody>
    </table>
    <br>
    <a href="/orders" class="btn">Back to Orders</a>
  `;
  return layout('Archived Orders', content, currentUser);
}

function create(statuses, currentUser, errorMsg = '') {
  const content = `
    <h1>New Order</h1>
    ${errorMsg ? `<p class="error">${errorMsg}</p>` : ''}
    <form method="POST" action="/orders/create">
      <label>Invoice Number:</label>
      <input type="number" name="numero_factura" required>
      <label>Client Name:</label>
      <input type="text" name="nombre_cliente" required>
      <label>Client Number:</label>
      <input type="text" name="numero_cliente" required>
      <label>Tax Info (optional):</label>
      <textarea name="datos_fiscales" rows="2"></textarea>
      <label>Delivery Address:</label>
      <textarea name="direccion_entrega" rows="2" required></textarea>
      <label>Notes (optional):</label>
      <textarea name="notas" rows="2"></textarea>
      <br><br>
      <button type="submit" class="btn btn-success">Create Order</button>
      <a href="/orders" class="btn" style="margin-left:10px">Cancel</a>
    </form>
  `;
  return layout('New Order', content, currentUser);
}

function edit(order, statuses, currentUser) {
  const statusOptions = statuses.map(s =>
    `<option value="${s.id}" ${order.status_id === s.id ? 'selected' : ''}>${s.nombre}</option>`
  ).join('');

  const currentStatusId = order.status_id;
  const showPhotoUpload = currentStatusId === 3 || currentStatusId === 4;
  const photoLabel = currentStatusId === 3 ? 'Upload Loading Photo (In Route)' : 'Upload Delivery Photo (Delivered)';

  const content = `
    <h1>Edit Order #${order.numero_factura}</h1>
    <form method="POST" action="/orders/${order.id}/edit" enctype="multipart/form-data">
      <label>Client Name:</label>
      <input type="text" name="nombre_cliente" value="${order.nombre_cliente}" required>
      <label>Client Number:</label>
      <input type="text" name="numero_cliente" value="${order.numero_cliente}" required>
      <label>Tax Info:</label>
      <textarea name="datos_fiscales" rows="2">${order.datos_fiscales || ''}</textarea>
      <label>Delivery Address:</label>
      <textarea name="direccion_entrega" rows="2" required>${order.direccion_entrega}</textarea>
      <label>Notes:</label>
      <textarea name="notas" rows="2">${order.notas || ''}</textarea>
      <label>Status:</label>
      <select name="status_id">
        ${statusOptions}
      </select>
      ${showPhotoUpload ? `
        <label>${photoLabel}:</label>
        <input type="file" name="foto" accept="image/*">
      ` : ''}
      <br><br>
      <button type="submit" class="btn btn-success">Save Changes</button>
      <a href="/orders/${order.id}" class="btn" style="margin-left:10px">Cancel</a>
    </form>
  `;
  return layout('Edit Order', content, currentUser);
}

function view(order, currentUser) {
  const statusName = order.estado ? order.estado.nombre : 'Unknown';
  const fotos = order.fotos || [];
  const fotosHtml = fotos.length
    ? fotos.map(f => `
        <div style="margin-bottom:15px">
          <strong>Type:</strong> ${f.tipo} |
          <strong>Uploaded by:</strong> ${f.uploader ? f.uploader.nombre : 'Unknown'} |
          <strong>Date:</strong> ${new Date(f.fecha_subida).toLocaleDateString()}<br>
          <img src="${f.url_foto}" alt="Order photo" style="max-width:400px; margin-top:8px; border:1px solid #ccc;">
        </div>
      `).join('')
    : '<p>No photos for this order.</p>';

  const content = `
    <h1>Order #${order.numero_factura}</h1>
    <p><strong>Client:</strong> ${order.nombre_cliente}</p>
    <p><strong>Client #:</strong> ${order.numero_cliente}</p>
    <p><strong>Tax Info:</strong> ${order.datos_fiscales || 'N/A'}</p>
    <p><strong>Delivery Address:</strong> ${order.direccion_entrega}</p>
    <p><strong>Notes:</strong> ${order.notas || 'N/A'}</p>
    <p><strong>Status:</strong> ${statusBadge(statusName)}</p>
    <p><strong>Created by:</strong> ${order.creador ? order.creador.nombre : 'Unknown'}</p>
    <p><strong>Date:</strong> ${new Date(order.fecha_hora).toLocaleDateString()}</p>
    <hr>
    <h2>Photos</h2>
    ${fotosHtml}
    <hr>
    <div class="links">
      <a href="/orders/${order.id}/edit" class="btn btn-warning">Edit / Update Status</a>
      <form method="POST" action="/orders/${order.id}/delete" style="display:inline">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Archive this order?')">Archive</button>
      </form>
      <a href="/orders" class="btn" style="margin-left:10px">Back to Orders</a>
    </div>
  `;
  return layout('Order ' + order.numero_factura, content, currentUser);
}

module.exports = { list, archived, create, edit, view };
