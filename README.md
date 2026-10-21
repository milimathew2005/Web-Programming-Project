# 📚 Library Management & Fine Tracking System

A full-featured, modern web-based Library Management and Circulation System built with **Laravel 12**, **Tailwind CSS**, and **Vite**. The system supports role-based access for Students and Librarians, handling book reservations, circulation loans, copy-level inventory tracking, automated overdue fine calculation, and printable receipts.

---

## ✨ Key Features

### 🎓 Student Portal
- **Book Catalog & Search:** Search books by title, author, category, or ISBN with live copy availability indicators.
- **Online Reservations:** Reserve available copies online with an automatic hold expiration window (default 48 hours).
- **Personal Dashboard:** Track active reservations, borrowed books, due dates, and outstanding fines.
- **Self-Service Actions:** Cancel pending reservations directly from the student dashboard.

### 🏛️ Librarian & Admin Panel
- **Statistics Dashboard:** Overview of total catalog titles, physical copies in circulation, active reservations, and uncollected fines.
- **Book & Copy Inventory Management:** Add, edit, and delete books; manage individual physical copy codes (e.g., `BK1001-C1`) and copy statuses (`available`, `reserved`, `borrowed`, `lost`).
- **Category Management:** Organize books across academic and literary categories.
- **Reservation Desk:** Review pending student reservations and instantly fulfill them into active circulation loans or cancel expired holds.
- **Circulation Desk (Loans):** Directly issue books to students, process book returns, and log return timestamps.
- **Fine Tracker & Printable Receipts:** Automated fine calculation for overdue loans with one-click payment processing and clean, printable PDF-ready payment receipts.

---

## 🛠️ Tech Stack

- **Backend:** [PHP 8.2+](https://php.net) & [Laravel 12](https://laravel.com)
- **Frontend:** Laravel Blade, [Tailwind CSS](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev), [Vite](https://vitejs.dev)
- **Database:** MySQL / SQLite
- **Authentication:** Laravel Breeze (Role-based: `student` & `librarian`)

---

## 🚀 Getting Started

### Prerequisites
Make sure you have the following installed on your machine:
- **PHP >= 8.2** (with `pdo`, `mbstring`, `openssl`, and `sqlite`/`mysql` extensions enabled)
- **Composer**
- **Node.js (>= 18)** & **npm**
- **MySQL** (or SQLite)

---

### Installation Steps

#### 1. Clone the Repository & Open Directory
```bash
git clone <repository-url>
cd web
```

#### 2. Install Dependencies
```bash
composer install
npm install
```

#### 3. Environment Configuration
Copy the example environment file and generate the application key:
```bash
# Windows PowerShell / CMD:
copy .env.example .env

# macOS / Linux:
cp .env.example .env

# Generate application key:
php artisan key:generate
```

Configure your database connection in `.env`:
* **For SQLite (quickest):**
  ```env
  DB_CONNECTION=sqlite
  ```
* **For MySQL:**
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=library_system
  DB_USERNAME=root
  DB_PASSWORD=your_password
  ```

#### 4. Run Migrations & Seed Sample Data
Creates all database tables, sample categories, 40+ books with multiple copies, and test accounts:
```bash
php artisan migrate:fresh --seed
```

#### 5. Start the Application

You can start both backend and frontend servers together using:
```bash
composer run dev
```

> **Alternative (Separate Terminals):**
> * **Terminal 1 (Backend):**
>   ```bash
>   php artisan serve
>   ```
> * **Terminal 2 (Frontend / Vite):**
>   ```bash
>   npm run dev
>   ```

#### 6. Open in Browser
Visit **[http://127.0.0.1:8000](http://127.0.0.1:8000)** in your web browser.

---

## 🔑 Demo & Test Credentials

The database seeder pre-populates the following accounts (all passwords are `password`):

| Role | Email | Password | Details |
|---|---|---|---|
| **Librarian** | `librarian@library.test` | `password` | Full access to circulation, inventory, and fines |
| **Student 1** | `student1@library.test` | `password` | Alice Smith (STU001) - Has active loans & reservations |
| **Student 2** | `student2@library.test` | `password` | Bob Johnson (STU002) - Has an overdue loan with fine |
| **Student 3** | `student3@library.test` | `password` | Charlie Brown (STU003) |

---

## ⚙️ Library Business Rules Configuration

You can customize core library rules via your `.env` file:

| Environment Variable | Default | Description |
|---|---|---|
| `LIBRARY_LOAN_DAYS` | `14` | Default checkout duration in days |
| `LIBRARY_FINE_PER_DAY` | `2.00` | Overdue fine charged per day past due date |
| `LIBRARY_GRACE_DAYS` | `0` | Number of grace days before fines start |
| `LIBRARY_MAX_FINE` | `500.00` | Maximum cap on fine per borrowed item |
| `LIBRARY_MAX_ACTIVE_ITEMS`| `3` | Maximum active books a student can reserve/borrow simultaneously |
| `LIBRARY_RESERVATION_HOLD_HOURS` | `48` | Hours a reservation remains valid before expiring |
| `LIBRARY_CURRENCY_SYMBOL` | `₹` | Currency symbol used on UI and receipts |

---

## 🗄️ Database Architecture

The system models six core entities:
1. **`users`** — Student and Librarian accounts with role scopes.
2. **`categories`** — Subject classifications.
3. **`books`** — Master catalog details (Title, Author, ISBN, Publisher, Year).
4. **`book_copies`** — Physical/identifiable inventory items with unique barcodes and statuses (`available`, `reserved`, `borrowed`, `lost`).
5. **`reservations`** — Queue of student hold requests with auto-expiration timestamps.
6. **`loans`** — Circulation records tracking issue dates, due dates, return dates, and fine amounts.

For the full Entity Relationship Diagram and schema details, see [`docs/er-diagram.md`](docs/er-diagram.md).

---

## 📄 License
This project is open-source and licensed under the [MIT License](LICENSE).
