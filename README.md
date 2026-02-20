# 🍔 TasteLibmanan: Food Mapping & Analytics System

![Project Status](https://img.shields.io/badge/Status-Active-success?style=for-the-badge)
![Tech Stack](https://img.shields.io/badge/Tech_Stack-PHP_|_MySQL_|_Tailwind-blue?style=for-the-badge)
![Academic](https://img.shields.io/badge/Project-BSIT_Capstone-orange?style=for-the-badge)

Welcome to **TasteLibmanan**, a comprehensive web-based platform designed to map, explore, and analyze local food establishments in Libmanan. 

This system serves as a bridge between local food enthusiasts, food business owners, and the Business Permits and Licensing Office (BPLO) of Libmanan, streamlining both the discovery of local flavors and the administrative processing of business permits.

---

## 📖 About the Project

Developed as a Bachelor of Science in Information Technology capstone project, TasteLibmanan goes beyond a simple directory. It is an interactive mapping system equipped with detailed analytics, an online permit application portal, and a communication system between vendors and municipal admins.

## ✨ Key Features

* **👨‍🍳 For Food Enthusiasts:** Interactive map, reviews & ratings, and favorites bookmarking.
* **🏪 For Food Business Owners:** Online permit applications (New/Renewal), menu management, gallery uploads, and real-time application status tracking.
* **🛡️ For Administrators (BPLO):** Analytics dashboard, vendor management (approve/reject permits), and direct messaging with business owners.

---

## 🛑 What You Need (Prerequisites)

If you want to download, modify, or run this code on your local machine, you will need the following tools and accounts:

1. **Local Server Environment:** * [XAMPP](https://www.apachefriends.org/index.html), WAMP, or MAMP installed on your machine.
2. **PHP & MySQL:** * PHP version 7.4 or higher.
   * MySQL (comes pre-packaged with XAMPP).
3. **Web Browser:** * Google Chrome, Microsoft Edge, or Mozilla Firefox.
4. **Google Account (For Email Features):**
   * The system uses **PHPMailer** to send OTPs, email verifications, and permit approval notifications.
   * You will need a standard Gmail account and you **MUST generate a Google App Password** (Found in your Google Account Security settings under 2-Step Verification) to use as your SMTP password.

---

## 🚀 Installation Guide

Follow these step-by-step instructions to get the system running locally:

### 1. Download the Code
* Clone the repository or download the ZIP file.
* Move the `TasteLibmanan` folder into your local server's web directory (e.g., `C:\xampp\htdocs\`).

### 2. Set Up the Database
* Open your XAMPP Control Panel and start **Apache** and **MySQL**.
* Open your browser and go to `http://localhost/phpmyadmin`.
* Click **New** to create a database and name it exactly: `tastelibmanan`.
* Click on the `Import` tab, select the `TASTELIBMANAN.sql` file located in the project's root folder, and click **Go**.

### 3. Configure the Environment Variables (Crucial Step)
To keep the system secure, credentials are not hardcoded. You must create an environment file.
* In the root folder of the project, find the file named `.env.example`.
* Duplicate this file and rename the copy to `.env` (just `.env`, nothing before the dot).
* Open the `.env` file in a text editor (like VS Code or Notepad) and update the credentials:

```env
# Database Credentials
DB_HOST=localhost
DB_NAME=tastelibmanan
DB_USER=root
DB_PASS=          # Leave blank if your local XAMPP root user has no password

# Email Setup (PHPMailer)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your_actual_email@gmail.com
SMTP_PASSWORD=your_google_app_password      # Use the 16-character App Password, NOT your real password!
SMTP_ENCRYPTION=tls
SMTP_FROM_EMAIL=your_actual_email@gmail.com
SMTP_FROM_NAME=Tastelibmanan Admin
