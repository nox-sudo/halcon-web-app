# Halcón Web App

Web application for order management at Halcón Distributor of Construction Materials.

---

## Evidence 2 - Logical Part of the Project

This update adds the complete backend logic for the application including models, migrations, controllers, seeders, and basic views.

### What was built

**Models** (located in `/backend/models/`):
- `Role.js` — stores employee roles (Admin, Sales, Purchasing, Warehouse, Route)
- `User.js` — stores users with password hashing and a `verificarPassword()` method
- `OrderStatus.js` — stores possible order statuses
- `Order.js` — stores orders with soft delete and helper methods like `estaEntregado()`
- `OrderPhoto.js` — stores photos associated with orders
- `index.js` — defines all relationships between models

**Relationships defined:**
- Role → hasMany Users
- User → hasMany Orders, hasMany Photos
- OrderStatus → hasMany Orders
- Order → hasMany Photos

**Migrations** (located in `/backend/migrations/`):
- `createTables.js` — creates all tables using Sequelize `sync()`, respecting FK order

**Controllers** (located in `/backend/controllers/`):
- `authController.js` — login and logout
- `userController.js` — list, create, edit users
- `orderController.js` — list, create, view, edit, soft delete, restore orders + photo upload
- `publicController.js` — public home and invoice search

**Seeders** (located in `/backend/seeders/`):
- `seed.js` — populates the database with 5 roles, 5 users, 4 statuses, 5 orders, 2 photos

**Views** (located in `/backend/views/`):
- Public home with invoice search form — shows delivery photos if delivered, or process info if in process
- Login form
- Dashboard with quick links (protected)
- Users list, create form, edit form (protected)
- Orders list, create form, edit form, view page, archived list (protected)

---

## Project Structure

```
halcon-web-app/
├── backend/
│   ├── app.js                  # Entry point
│   ├── package.json
│   ├── .env.example            # Environment variables template
│   ├── config/
│   │   └── database.js         # Sequelize connection
│   ├── models/
│   │   ├── index.js            # Associations
│   │   ├── Role.js
│   │   ├── User.js
│   │   ├── OrderStatus.js
│   │   ├── Order.js
│   │   └── OrderPhoto.js
│   ├── controllers/
│   │   ├── authController.js
│   │   ├── userController.js
│   │   ├── orderController.js
│   │   └── publicController.js
│   ├── routes/
│   │   ├── auth.js
│   │   ├── public.js
│   │   ├── dashboard.js
│   │   ├── users.js
│   │   └── orders.js
│   ├── middleware/
│   │   └── auth.js             # requireLogin middleware
│   ├── migrations/
│   │   └── createTables.js
│   ├── seeders/
│   │   └── seed.js
│   ├── views/
│   │   ├── layout.js           # Shared HTML wrapper
│   │   ├── auth/login.js
│   │   ├── dashboard/index.js
│   │   ├── public/index.js
│   │   ├── users/index.js
│   │   └── orders/index.js
│   └── public/
│       └── uploads/            # Uploaded photos stored here
├── /docs
├── .gitignore
└── README.md
```

---

## Technologies

- **Backend:** Node.js / Express
- **ORM:** Sequelize
- **Database:** MySQL
- **Authentication:** express-session + bcryptjs
- **File Upload:** Multer
- **Views:** Plain HTML rendered via JS template functions (no extra template engine)

---

## How to Run

### 1. Install dependencies
```bash
cd backend
npm install
```

### 2. Set up environment variables
```bash
cp .env.example .env
# Edit .env with your MySQL credentials
```

### 3. Create database
```sql
CREATE DATABASE halcon_db;
```

### 4. Run migration (creates tables)
```bash
node migrations/createTables.js
```

### 5. Seed the database with test data
```bash
node seeders/seed.js
```

### 6. Start the server
```bash
npm start
# or for development:
npm run dev
```

### 7. Open in browser
```
http://localhost:3000
```

---

## Test Credentials (after seeding)

| Email | Password | Role |
|---|---|---|
| admin@halcon.com | password123 | Admin |
| carlos@halcon.com | password123 | Sales |
| pedro@halcon.com | password123 | Route |

---

## Methodology

**SCRUM** — Sprints of 2 weeks

| Sprint | Goal |
|---|---|
| Sprint 0 | Analysis, DB design, diagrams |
| Sprint 1 | Login, users, roles |
| Sprint 2 | Orders CRUD and status changes |
| Sprint 3 | Photo upload, public tracking page |
| Sprint 4 | Archived orders, restore, final testing |
