// middleware/auth.js
// Checks if user is logged in before accessing protected routes

function requireLogin(req, res, next) {
  if (!req.session.user) {
    return res.redirect('/auth/login');
  }
  next();
}

module.exports = { requireLogin };
