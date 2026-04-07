const bcrypt = require('bcryptjs');
const { User, Role } = require('../models/index');
const views = require('../views/users/index');

const userController = {

  // List all users (active and inactive)
  async list(req, res) {
    try {
      const users = await User.findAll({
        include: [{ model: Role, as: 'rol' }],
        order: [['id', 'ASC']]
      });
      res.send(views.list(users, req.session.user));
    } catch (error) {
      console.error(error);
      res.send('Error loading users');
    }
  },

  // Show create user form
  async showCreate(req, res) {
    try {
      const roles = await Role.findAll();
      res.send(views.create(roles, req.session.user));
    } catch (error) {
      console.error(error);
      res.send('Error loading form');
    }
  },

  // Handle user creation
  async create(req, res) {
    const { nombre, email, password, rol_id } = req.body;
    try {
      const password_hash = await bcrypt.hash(password, 10);
      await User.create({ nombre, email, password_hash, rol_id, activo: 1 }, { individualHooks: false });
      res.redirect('/users');
    } catch (error) {
      console.error(error);
      const roles = await Role.findAll();
      res.send(views.create(roles, req.session.user, 'Error: ' + error.message));
    }
  },

  // Show edit user form
  async showEdit(req, res) {
    try {
      const user = await User.findByPk(req.params.id, {
        include: [{ model: Role, as: 'rol' }]
      });
      if (!user) return res.redirect('/users');
      const roles = await Role.findAll();
      res.send(views.edit(user, roles, req.session.user));
    } catch (error) {
      console.error(error);
      res.send('Error loading user');
    }
  },

  // Handle user update
  async update(req, res) {
    const { nombre, email, rol_id, activo, password } = req.body;
    try {
      const user = await User.findByPk(req.params.id);
      if (!user) return res.redirect('/users');

      user.nombre = nombre;
      user.email = email;
      user.rol_id = rol_id;
      user.activo = activo === '1' ? 1 : 0;

      // Only update password if a new one was provided
      if (password && password.trim() !== '') {
        user.password_hash = await bcrypt.hash(password, 10);
      }

      await user.save({ hooks: false });
      res.redirect('/users');
    } catch (error) {
      console.error(error);
      res.send('Error updating user: ' + error.message);
    }
  }
};

module.exports = userController;
