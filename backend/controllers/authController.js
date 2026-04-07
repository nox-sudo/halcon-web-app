const { User, Role } = require('../models/index');

const authController = {

  // Show login form
  showLogin(req, res) {
    if (req.session.user) return res.redirect('/dashboard');
    res.send(require('../views/auth/login')());
  },

  // Handle login form submission
  async handleLogin(req, res) {
    const { email, password } = req.body;

    try {
      const user = await User.findOne({
        where: { email, activo: 1 },
        include: [{ model: Role, as: 'rol' }]
      });

      if (!user || !user.verificarPassword(password)) {
        return res.send(require('../views/auth/login')('Email o contraseña incorrectos'));
      }

      // Save user info in session
      req.session.user = {
        id: user.id,
        nombre: user.nombre,
        email: user.email,
        rol: user.rol ? user.rol.nombre : 'Sin rol'
      };

      res.redirect('/dashboard');
    } catch (error) {
      console.error(error);
      res.send(require('../views/auth/login')('Error del servidor'));
    }
  },

  // Logout
  logout(req, res) {
    req.session.destroy();
    res.redirect('/auth/login');
  }
};

module.exports = authController;
