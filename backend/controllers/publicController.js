const { Order, OrderStatus, OrderPhoto } = require('../models/index');
const views = require('../views/public/index');

const publicController = {

  // Show home page with search form
  showHome(req, res) {
    res.send(views.home(null, null));
  },

  // Handle invoice search
  async searchInvoice(req, res) {
    const { numero_factura } = req.query;

    if (!numero_factura) {
      return res.send(views.home(null, 'Please enter an invoice number'));
    }

    try {
      const order = await Order.findOne({
        where: { numero_factura, eliminado: 0 },
        include: [
          { model: OrderStatus, as: 'estado' },
          { model: OrderPhoto, as: 'fotos' }
        ]
      });

      if (!order) {
        return res.send(views.home(null, `No order found for invoice #${numero_factura}`));
      }

      res.send(views.home(order, null));
    } catch (error) {
      console.error(error);
      res.send(views.home(null, 'Server error, please try again'));
    }
  }
};

module.exports = publicController;
