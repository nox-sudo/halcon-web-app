const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const OrderPhoto = sequelize.define('OrderPhoto', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  pedido_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: { model: 'orders', key: 'id' }
  },
  url_foto: {
    type: DataTypes.STRING(500),
    allowNull: false
  },
  tipo: {
    type: DataTypes.ENUM('carga', 'entrega'),
    allowNull: false
  },
  fecha_subida: {
    type: DataTypes.DATE,
    defaultValue: DataTypes.NOW
  },
  subida_por: {
    type: DataTypes.INTEGER,
    allowNull: true,
    references: { model: 'users', key: 'id' }
  }
}, {
  tableName: 'order_photos',
  timestamps: false
});

module.exports = OrderPhoto;
