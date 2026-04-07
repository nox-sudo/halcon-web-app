const path = require('path');
const multer = require('multer');
const { Order, OrderStatus, User, OrderPhoto } = require('../models/index');
const views = require('../views/orders/index');

// Multer config for photo uploads
const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, path.join(__dirname, '../public/uploads')),
  filename: (req, file, cb) => {
    const ext = path.extname(file.originalname);
    cb(null, `foto_${Date.now()}${ext}`);
  }
});
const upload = multer({ storage });

const orderController = {

  upload, // export multer middleware

  // List all active orders (not deleted), newest first
  async list(req, res) {
    try {
      const orders = await Order.findAll({
        where: { eliminado: 0 },
        include: [
          { model: OrderStatus, as: 'estado' },
          { model: User, as: 'creador' }
        ],
        order: [['id', 'DESC']]
      });
      res.send(views.list(orders, req.session.user));
    } catch (error) {
      console.error(error);
      res.send('Error loading orders');
    }
  },

  // List archived (logically deleted) orders
  async listArchived(req, res) {
    try {
      const orders = await Order.findAll({
        where: { eliminado: 1 },
        include: [
          { model: OrderStatus, as: 'estado' },
          { model: User, as: 'creador' }
        ],
        order: [['id', 'DESC']]
      });
      res.send(views.archived(orders, req.session.user));
    } catch (error) {
      console.error(error);
      res.send('Error loading archived orders');
    }
  },

  // Show create order form
  async showCreate(req, res) {
    try {
      const statuses = await OrderStatus.findAll();
      res.send(views.create(statuses, req.session.user));
    } catch (error) {
      console.error(error);
      res.send('Error loading form');
    }
  },

  // Handle order creation
  async create(req, res) {
    const { numero_factura, nombre_cliente, numero_cliente, datos_fiscales, direccion_entrega, notas } = req.body;
    try {
      await Order.create({
        numero_factura,
        nombre_cliente,
        numero_cliente,
        datos_fiscales,
        direccion_entrega,
        notas,
        status_id: 1, // Always starts as "Ordered"
        eliminado: 0,
        creado_por: req.session.user.id
      });
      res.redirect('/orders');
    } catch (error) {
      console.error(error);
      const statuses = await OrderStatus.findAll();
      res.send(views.create(statuses, req.session.user, 'Error: ' + error.message));
    }
  },

  // View a single order
  async view(req, res) {
    try {
      const order = await Order.findByPk(req.params.id, {
        include: [
          { model: OrderStatus, as: 'estado' },
          { model: User, as: 'creador' },
          { model: OrderPhoto, as: 'fotos', include: [{ model: User, as: 'uploader' }] }
        ]
      });
      if (!order) return res.redirect('/orders');
      res.send(views.view(order, req.session.user));
    } catch (error) {
      console.error(error);
      res.send('Error loading order');
    }
  },

  // Show edit/update order form
  async showEdit(req, res) {
    try {
      const order = await Order.findByPk(req.params.id, {
        include: [{ model: OrderPhoto, as: 'fotos' }]
      });
      if (!order) return res.redirect('/orders');
      const statuses = await OrderStatus.findAll();
      res.send(views.edit(order, statuses, req.session.user));
    } catch (error) {
      console.error(error);
      res.send('Error loading order');
    }
  },

  // Handle order update (status change + basic data)
  async update(req, res) {
    const { nombre_cliente, numero_cliente, datos_fiscales, direccion_entrega, notas, status_id } = req.body;
    try {
      const order = await Order.findByPk(req.params.id);
      if (!order) return res.redirect('/orders');

      order.nombre_cliente = nombre_cliente;
      order.numero_cliente = numero_cliente;
      order.datos_fiscales = datos_fiscales;
      order.direccion_entrega = direccion_entrega;
      order.notas = notas;
      order.status_id = status_id;
      order.modificado_en = new Date();
      await order.save();

      // If a photo was uploaded and status is "In Route" or "Delivered"
      if (req.file) {
        const tipo = parseInt(status_id) === 3 ? 'carga' : 'entrega';
        await OrderPhoto.create({
          pedido_id: order.id,
          url_foto: '/uploads/' + req.file.filename,
          tipo,
          subida_por: req.session.user.id
        });
      }

      res.redirect('/orders/' + order.id);
    } catch (error) {
      console.error(error);
      res.send('Error updating order: ' + error.message);
    }
  },

  // Logical delete (soft delete)
  async softDelete(req, res) {
    try {
      const order = await Order.findByPk(req.params.id);
      if (!order) return res.redirect('/orders');
      order.eliminado = 1;
      order.modificado_en = new Date();
      await order.save();
      res.redirect('/orders');
    } catch (error) {
      console.error(error);
      res.send('Error deleting order');
    }
  },

  // Restore archived order
  async restore(req, res) {
    try {
      const order = await Order.findByPk(req.params.id);
      if (!order) return res.redirect('/orders/archived');
      order.eliminado = 0;
      order.modificado_en = new Date();
      await order.save();
      res.redirect('/orders/archived');
    } catch (error) {
      console.error(error);
      res.send('Error restoring order');
    }
  }
};

module.exports = orderController;
