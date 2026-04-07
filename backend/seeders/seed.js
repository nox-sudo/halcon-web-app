// seeders/seed.js
// Run with: node seeders/seed.js
// Populates the database with test data

const bcrypt = require('bcryptjs');
const { sequelize, Role, User, OrderStatus, Order, OrderPhoto } = require('../models/index');

async function seed() {
  try {
    await sequelize.authenticate();
    console.log('Connected to database. Starting seed...');

    // 1. Seed Roles
    const roles = await Role.bulkCreate([
      { nombre: 'Admin' },
      { nombre: 'Sales' },
      { nombre: 'Purchasing' },
      { nombre: 'Warehouse' },
      { nombre: 'Route' }
    ]);
    console.log('Roles created:', roles.length);

    // 2. Seed Order Statuses
    const statuses = await OrderStatus.bulkCreate([
      { nombre: 'Ordered' },
      { nombre: 'In Process' },
      { nombre: 'In Route' },
      { nombre: 'Delivered' }
    ]);
    console.log('Order statuses created:', statuses.length);

    // 3. Seed Users
    const password = await bcrypt.hash('password123', 10);
    const users = await User.bulkCreate([
      {
        nombre: 'Admin User',
        email: 'admin@halcon.com',
        password_hash: password,
        rol_id: 1, // Admin
        activo: 1
      },
      {
        nombre: 'Carlos Ventas',
        email: 'carlos@halcon.com',
        password_hash: password,
        rol_id: 2, // Sales
        activo: 1
      },
      {
        nombre: 'Laura Almacen',
        email: 'laura@halcon.com',
        password_hash: password,
        rol_id: 4, // Warehouse
        activo: 1
      },
      {
        nombre: 'Pedro Ruta',
        email: 'pedro@halcon.com',
        password_hash: password,
        rol_id: 5, // Route
        activo: 1
      },
      {
        nombre: 'Maria Inactiva',
        email: 'maria@halcon.com',
        password_hash: password,
        rol_id: 2, // Sales
        activo: 0 // Inactive user
      }
    ], { individualHooks: false }); // skip hooks since we already hashed
    console.log('Users created:', users.length);

    // 4. Seed Orders
    const orders = await Order.bulkCreate([
      {
        numero_factura: 1001,
        nombre_cliente: 'Constructora ABC',
        numero_cliente: 'CLI-001',
        datos_fiscales: 'RFC: ABC123456XYZ',
        direccion_entrega: 'Calle Reforma 123, Culiacán, Sinaloa',
        notas: 'Entregar en la mañana',
        status_id: 4, // Delivered
        eliminado: 0,
        creado_por: 2
      },
      {
        numero_factura: 1002,
        nombre_cliente: 'Obras Perez',
        numero_cliente: 'CLI-002',
        datos_fiscales: 'RFC: OPE789012MNO',
        direccion_entrega: 'Av. Insurgentes 456, Culiacán, Sinaloa',
        notas: null,
        status_id: 3, // In Route
        eliminado: 0,
        creado_por: 2
      },
      {
        numero_factura: 1003,
        nombre_cliente: 'Ferreter Garza',
        numero_cliente: 'CLI-003',
        datos_fiscales: null,
        direccion_entrega: 'Blvd. Leyva 789, Culiacán, Sinaloa',
        notas: 'Llamar antes de entregar',
        status_id: 2, // In Process
        eliminado: 0,
        creado_por: 2
      },
      {
        numero_factura: 1004,
        nombre_cliente: 'Hotel Los Pinos',
        numero_cliente: 'CLI-004',
        datos_fiscales: 'RFC: HLP345678PQR',
        direccion_entrega: 'Calle Aquiles 321, Culiacán, Sinaloa',
        notas: null,
        status_id: 1, // Ordered
        eliminado: 0,
        creado_por: 2
      },
      {
        numero_factura: 1005,
        nombre_cliente: 'Remodelaciones Lopez',
        numero_cliente: 'CLI-005',
        datos_fiscales: null,
        direccion_entrega: 'Calle 5 de Mayo 111, Culiacán, Sinaloa',
        notas: 'Pedido cancelado por cliente',
        status_id: 1,
        eliminado: 1, // Logically deleted
        creado_por: 2
      }
    ]);
    console.log('Orders created:', orders.length);

    // 5. Seed Photos for delivered order (1001)
    await OrderPhoto.bulkCreate([
      {
        pedido_id: 1,
        url_foto: '/uploads/foto_carga_1001.jpg',
        tipo: 'carga',
        subida_por: 4
      },
      {
        pedido_id: 1,
        url_foto: '/uploads/foto_entrega_1001.jpg',
        tipo: 'entrega',
        subida_por: 4
      }
    ]);
    console.log('Photos created for order 1001');

    console.log('\nSeed complete! You can now log in with:');
    console.log('  admin@halcon.com / password123');
    console.log('  carlos@halcon.com / password123');
    console.log('  pedro@halcon.com / password123');
    process.exit(0);
  } catch (error) {
    console.error('Seed error:', error);
    process.exit(1);
  }
}

seed();
