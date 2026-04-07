const express = require('express');
const router = express.Router();
const publicController = require('../controllers/publicController');

router.get('/', publicController.showHome);
router.get('/search', publicController.searchInvoice);

module.exports = router;
