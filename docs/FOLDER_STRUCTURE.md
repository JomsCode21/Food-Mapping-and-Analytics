# Folder Structure Guide

This project is organized to keep runtime modules separate from setup and maintenance utilities.

## Main App Areas

- `users/`: User-facing pages and user APIs
- `fbusinessowner/`: Food business owner dashboard and APIs
- `admin_folder/`: BPLO admin pages and APIs
- `uploads/`: Uploaded files (photos, menus, requirements)
- `vendors/`: Frontend assets and third-party vendor files
- `PHPMailer/`: Mailer library

## Setup and Maintenance

- `tools/diagnostics.php`: Browser diagnostic page for environment/database checks
- `tools/test_connection.php`: JSON database connectivity check
- `tools/setup_database.php`: Executes migration SQL for reviews table
- `tools/cron_job.php`: Scheduled maintenance task (updates stale `is_new` flags)
- `tools/password_hasher.php`: Utility to hash admin password from `.env`
- `tools/sendMailSample.php`: SMTP test utility

- `database/TASTELIBMANAN.sql`: Primary database dump for full import
- `database/create_reviews_table.sql`: Migration SQL for reviews table

## Compatibility Wrappers

Legacy root utility paths are intentionally kept for compatibility:

- `diagnostics.php`
- `test_connection.php`
- `setup_database.php`
- `cron_job.php`
- `password_hasher.php`
- `sendMailSample.php`

These wrappers simply load the corresponding script from `tools/`.

## Notes for New Files

- Add user-only APIs/pages under `users/`.
- Add owner-only APIs/pages under `fbusinessowner/`.
- Add admin-only APIs/pages under `admin_folder/`.
- Add SQL scripts under `database/`.
- Add one-off maintenance/debug scripts under `tools/`.
