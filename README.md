Here is a complete, professional **`README.md`** file for your GitHub repository. It explains exactly how to set up the project, install dependencies, and configure the critical Firebase + Database connection.

You can copy this directly into your repository's root folder.

---

# 🎓 Gestion des Étudiants ENSAT (Laravel + Firebase)

A student management system built with **Laravel 12** and **Docker**.
This project uses a **Hybrid Authentication** system:

-   **Data:** Stored in **MariaDB** (SQL) for relational integrity (Students, Filières).
-   **Authentication:** Managed by **Firebase Auth** (Login, Password Reset).

---

## 🚀 Prerequisites

Before starting, ensure you have the following installed:

-   [Docker](https://www.docker.com/) (or Podman)
-   [Composer](https://getcomposer.org/) (Optional, can be run inside Docker)
-   A Google Firebase Project

---

## 🛠️ Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name

```

### 2. Install PHP Dependencies

We need to install Laravel framework and the Firebase SDK (`kreait/laravel-firebase`).

```bash
composer install

```

_(If you don't have PHP installed locally, you can run this step after starting Docker)._

### 3. Environment Configuration (`.env`)

Duplicate the example file to create your local configuration:

```bash
cp .env.example .env

```

Open `.env` and configure the **Database** (to match your Docker compose services):

```ini
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=your_password

```

### 4. Firebase Configuration (Critical Step)

You need to connect your Firebase project.

1. **Get the Service Account Key:**

-   Go to Firebase Console -> **Project Settings** -> **Service Accounts**.
-   Click **Generate New Private Key**.
-   Save the file as `storage/firebase_credentials.json` (Make sure this file is in `.gitignore` so it's not pushed to GitHub!).

2. **Get the Web API Key:**

-   Go to **Project Settings** -> **General**.
-   Scroll to "Your apps" and copy the **Web API Key**.

3. **Update `.env`:**
   Add these lines to your `.env` file:

```ini
FIREBASE_CREDENTIALS=storage/firebase_credentials.json
FIREBASE_API_KEY=AIzaSyDxxxxxxxxxxxxxxxxxxxxxxxxxxxx

```

---

## 🐳 Docker Setup

Start the application containers (App + Database).

```bash
docker-compose up -d --build

```

_(Use `podman-compose` if you are using Podman)._

---

## 🗄️ Database Setup

Once the containers are running, you need to create the tables.

1. **Enter the Container:**

```bash
docker exec -it laravel_app bash
# OR for Podman:
podman exec -it laravel_app bash

```

2. **Run Migrations:**

```bash
php artisan migrate

```

3. **Set Permissions (If you get Permission Denied errors):**

```bash
chmod -R 777 storage bootstrap/cache

```

---

## 👤 Creating the First Admin (Manual Setup)

Since there is no public registration page, you must create the first Admin account using the command line (Tinker). This script creates the user in **Firebase** first, then syncs them to **MariaDB**.

Run this inside the container:

```bash
php artisan tinker

```

Then paste this script:

```php
// 1. Define User Info
$email = 'admin@ensat.ac.ma';
$pass = 'password123';
$name = 'Super Admin';

// 2. Create in Firebase
$auth = app('firebase.auth');
try {
    $user = $auth->createUser(['email' => $email, 'password' => $pass, 'displayName' => $name]);
    $uid = $user->uid;
} catch (\Exception $e) {
    // If exists, get UID
    $uid = $auth->getUserByEmail($email)->uid;
}

// 3. Create in MariaDB
\App\Models\User::create([
    'name' => $name,
    'email' => $email,
    'firebase_uid' => $uid,
    'role' => 'admin',
    'password' => null
]);
exit

```

---

## 🖥️ Usage

-   **Access the App:** [http://localhost:8080](https://www.google.com/search?q=http://localhost:8080)
-   **Login Credentials:**
-   **Email:** `admin@ensat.ac.ma`
-   **Password:** `password123`

### Features

-   **Admin:** Can Add, Edit, Delete Students (CRUD).
-   **Student:** Can only view their own profile.
-   **Forgot Password:** Uses Firebase email service to send reset links.
