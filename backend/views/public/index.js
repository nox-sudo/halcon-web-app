const layout = require('../layout');

function statusBadge(statusName) {
  const map = { 'Ordered': 'ordered', 'In Process': 'process', 'In Route': 'route', 'Delivered': 'delivered' };
  const cls = map[statusName] || 'ordered';
  return `<span class="badge badge-${cls}">${statusName}</span>`;
}

function home(order, errorMsg) {
  let result = '';

  if (errorMsg) {
    result = `<p class="error">${errorMsg}</p>`;
  }

  if (order) {
    const statusName = order.estado ? order.estado.nombre : 'Unknown';
    result = `
      <h2>Order #${order.numero_factura}</h2>
      <p><strong>Client:</strong> ${order.nombre_cliente}</p>
      <p><strong>Status:</strong> ${statusBadge(statusName)}</p>
      <p><strong>Delivery Address:</strong> ${order.direccion_entrega}</p>
    `;

    if (statusName === 'In Process') {
      result += `
        <p><strong>Current process:</strong> In Process</p>
        <p><strong>Date:</strong> ${new Date(order.fecha_hora).toLocaleDateString()}</p>
      `;
    }

    if (statusName === 'Delivered') {
      const deliveryPhotos = order.fotos ? order.fotos.filter(f => f.tipo === 'entrega') : [];
      if (deliveryPhotos.length > 0) {
        result += `<h3>Delivery Photo(s):</h3>`;
        deliveryPhotos.forEach(foto => {
          result += `<img src="${foto.url_foto}" alt="Delivery photo" style="max-width:400px; display:block; margin-bottom:10px; border:1px solid #ccc;">`;
        });
      } else {
        result += `<p>No delivery photos available yet.</p>`;
      }
    }
  }

  const content = `
    <h1>Track Your Order</h1>
    <p>Enter your invoice number to check the status of your order.</p>
    <form method="GET" action="/search">
      <label for="numero_factura">Invoice Number:</label>
      <input type="number" id="numero_factura" name="numero_factura" placeholder="e.g. 1001" required style="max-width:300px">
      <br><br>
      <button type="submit" class="btn">Search</button>
    </form>
    <br>
    ${result}
  `;

  return layout('Track Order', content);
}

module.exports = { home };
