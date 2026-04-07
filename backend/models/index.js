const sequelize = require('../config/database');
const Role = require('./Role');
const User = require('./User');
const OrderStatus = require('./OrderStatus');
const Order = require('./Order');
const OrderPhoto = require('./OrderPhoto');

// --- Relationships ---

// One Role has many Users
Role.hasMany(User, { foreignKey: 'rol_id', as: 'usuarios' });
User.belongsTo(Role, { foreignKey: 'rol_id', as: 'rol' });

// One User creates many Orders
User.hasMany(Order, { foreignKey: 'creado_por', as: 'pedidos' });
Order.belongsTo(User, { foreignKey: 'creado_por', as: 'creador' });

// One OrderStatus is assigned to many Orders
OrderStatus.hasMany(Order, { foreignKey: 'status_id', as: 'pedidos' });
Order.belongsTo(OrderStatus, { foreignKey: 'status_id', as: 'estado' });

// One Order has many Photos
Order.hasMany(OrderPhoto, { foreignKey: 'pedido_id', as: 'fotos' });
OrderPhoto.belongsTo(Order, { foreignKey: 'pedido_id', as: 'pedido' });

// One User uploads many Photos
User.hasMany(OrderPhoto, { foreignKey: 'subida_por', as: 'fotos_subidas' });
OrderPhoto.belongsTo(User, { foreignKey: 'subida_por', as: 'uploader' });

module.exports = { sequelize, Role, User, OrderStatus, Order, OrderPhoto };
