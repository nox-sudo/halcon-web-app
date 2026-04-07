const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');
const { requireLogin } = require('../middleware/auth');

router.use(requireLogin); // All user routes require login

router.get('/', userController.list);
router.get('/create', userController.showCreate);
router.post('/create', userController.create);
router.get('/edit/:id', userController.showEdit);
router.post('/edit/:id', userController.update);

module.exports = router;
