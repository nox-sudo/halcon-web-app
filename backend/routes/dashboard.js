const express = require('express');
const router = express.Router();
const { requireLogin } = require('../middleware/auth');

router.get('/', requireLogin, (req, res) => {
  const view = require('../views/dashboard/index');
  res.send(view(req.session.user));
});

module.exports = router;
