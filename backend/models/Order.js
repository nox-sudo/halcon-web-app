const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const Order = sequelize.define('Order', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  numero_factura: {
    type: DataTypes.INTEGER,
    allowNull: false,
    unique: true
  },
  nombre_cliente: {
    type: DataTypes.STRING(150),
    allowNull: false
  },
  numero_cliente: {
    type: DataTypes.STRING(50),
    allowNull: false
  },
  datos_fiscales: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  fecha_hora: {
    type: DataTypes.DATE,
    defaultValue: DataTypes.NOW
  },
  direccion_entrega: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  notas: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  status_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: { model: 'order_status', key: 'id' }
  },
  eliminado: {
    type: DataTypes.TINYINT,
    defaultValue: 0
  },
  creado_por: {
    type: DataTypes.INTEGER,
    allowNull: true,
    references: { model: 'users', key: 'id' }
  },
  modificado_en: {
    type: DataTypes.DATE,
    allowNull: true
  }
}, {
  tableName: 'orders',
  timestamps: false
});

// Instance method: check if order is delivered
Order.prototype.estaEntregado = function() {
  return this.status_id === 4; // 4 = Delivered
};

// Instance method: check if order is in route
Order.prototype.estaEnRuta = function() {
  return this.status_id === 3; // 3 = In Route
};

// Instance method: check if order is active (not deleted)
Order.prototype.estaActivo = function() {
  return this.eliminado === 0;
};

module.exports = Order;
