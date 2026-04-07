const express = require('express');
const router = express.Router();
const orderController = require('../controllers/orderController');
const { requireLogin } = require('../middleware/auth');

router.use(requireLogin); // All order routes require login

router.get('/', orderController.list);
router.get('/archived', orderController.listArchived);
router.get('/create', orderController.showCreate);
router.post('/create', orderController.create);
router.get('/:id', orderController.view);
router.get('/:id/edit', orderController.showEdit);
router.post('/:id/edit', orderController.upload.single('foto'), orderController.update);
router.post('/:id/delete', orderController.softDelete);
router.post('/:id/restore', orderController.restore);

module.exports = router;
