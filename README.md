# websitetlfoto

**Timor-Leste Activity Gallery** — a lightweight photo & activity gallery website for showcasing tourism destinations, local traditions, beaches, and community activities in Timor-Leste.

Built with plain **PHP + SQLite** (no framework, no Composer), so it runs out of the box on XAMPP / any PHP host.

---

## ✨ Features

### Public side
- **Public gallery** (`index.php`) — responsive card grid of all activities, newest first
- **Detail page** (`detail.php`) — full-size image, location, activity date, and complete description
- Hero section introducing Timor-Leste tourism

### Admin side
- **Admin panel** (`admin.php`) — create, edit, and delete activities
- **Image upload** — upload photos (JPEG, PNG, WebP, GIF) or use an external image URL
- Automatic cleanup — old uploaded files are deleted when an entry is updated or removed
- Success/error feedback messages and delete confirmation dialog
- Seeded with 4 sample activities on first run (Cristo Rei, Atauro Island, Comoro Market, Jaco Island)

### Technical
- **SQLite database** (`data/gallery.db`) — created and auto-migrated on first request, zero setup
- **Prepared statements** everywhere (PDO) — safe from SQL injection
- **Output escaping** (`htmlspecialchars`) on all user-provided output
- Auto-creates `data/` and `uploads/` directories if missing
- Timezone set to `Asia/Dili`
- Mobile-friendly responsive layout (single stylesheet)

---

## 📁 Project structure

```
.
├── index.php        # Public gallery (home page)
├── detail.php       # Single activity detail view
├── admin.php        # Admin panel: add / edit / list / delete
├── save.php         # Handles form POST: create & update + image upload
├── delete.php       # Deletes an activity and its uploaded image
├── config.php       # Paths, timezone, asset helper
├── db.php           # PDO/SQLite connection, schema, seed data
├── assets/
│   └── style.css    # All styling
├── data/
│   └── gallery.db   # SQLite database (auto-created)
└── uploads/         # Uploaded images (auto-created)
```

---

## 🚀 Getting started

### Requirements
- PHP **8.0+** with the `pdo_sqlite` extension (enabled by default in XAMPP)
- No database server, no Composer, no build step

### Run locally with XAMPP

1. Copy the project folder into `htdocs`:
   ```
   C:\xampp\htdocs\websitetlfoto
   ```
2. Start **Apache** from the XAMPP control panel.
3. Open the site:
   ```
   http://localhost/websitetlfoto/
   ```
   The database and folders are created automatically on first load.

### Run with the PHP built-in server

```bash
php -S localhost:8000
```

Then visit `http://localhost:8000`.

---

## 🖥️ Usage

| Page | URL | Description |
|------|-----|-------------|
| Public gallery | `/index.php` | Browse all activities |
| Activity detail | `/detail.php?id=1` | View a single activity |
| Admin panel | `/admin.php` | Add / edit / delete activities |
| Edit an entry | `/admin.php?edit=1` | Prefill the form with existing data |

**Adding an activity:** go to **Admin** → fill in *Title*, *Location*, *Date*, *Description*, upload a **Photo** → **Save Activity**.

---

## 🗃️ Database schema

Single table `activities`:

| Column | Type | Notes |
|--------|------|-------|
| `id` | INTEGER | Primary key, auto-increment |
| `title` | TEXT | Required |
| `location` | TEXT | Required |
| `activity_date` | TEXT | `YYYY-MM-DD` |
| `description` | TEXT | Required |
| `image` | TEXT | `uploads/...` path or full `http(s)://` URL |
| `created_at` | TEXT | Defaults to `CURRENT_TIMESTAMP` |

---

## 🔒 Security notes

- All SQL uses **prepared statements**; IDs are cast to `int`.
- All echoed output is escaped with `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- Uploads are validated against an allow-list of MIME types.
- The admin panel has **no authentication** — do not deploy it publicly without adding a login (or protect the `admin.php` / `save.php` / `delete.php` files with server-side auth / `.htaccess`).

---

## 📦 Pushing to GitHub

```bash
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/Luisfrancis23/websitetlfoto.git
git push -u origin main
```

> Tip: commit the source code, but consider keeping `data/gallery.db` and `uploads/*` out of the repo by adding a `.gitignore`.

---

## 📄 License

Feel free to use and modify for your own projects.
