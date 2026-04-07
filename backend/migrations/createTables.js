// migrations/createTables.js
// Run this file with: node migrations/createTables.js
// It creates all the tables in the correct order respecting FK constraints

const { sequelize } = require('../models/index');

async function migrate() {
  try {
    console.log('Connecting to database...');
    await sequelize.authenticate();
    console.log('Connected!');

    // sync({ force: true }) drops and recreates tables
    // In production use { alter: true } to not lose data
    await sequelize.sync({ force: true });

    console.log('All tables created successfully!');
    console.log('Tables: roles, users, order_status, orders, order_photos');
    process.exit(0);
  } catch (error) {
    console.error('Error creating tables:', error);
    process.exit(1);
  }
}

migrate();
