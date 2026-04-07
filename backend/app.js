require('dotenv').config();
const express = require('express');
const session = require('express-session');
const path = require('path');
const app = express();

// Body parser
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Static files
app.use(express.static(path.join(__dirname, 'public')));

// Session
app.use(session({
  secret: process.env.SESSION_SECRET || 'halcon_secret_key',
  resave: false,
  saveUninitialized: false
}));

// Make user session available in all views
app.use((req, res, next) => {
  res.locals.user = req.session.user || null;
  next();
});

// View engine (plain HTML with template strings - no extra package needed)
app.set('views', path.join(__dirname, 'views'));

// Routes
app.use('/', require('./routes/public'));
app.use('/auth', require('./routes/auth'));
app.use('/dashboard', require('./routes/dashboard'));
app.use('/users', require('./routes/users'));
app.use('/orders', require('./routes/orders'));

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Halcon app running on http://localhost:${PORT}`);
});
