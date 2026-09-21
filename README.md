# FTTH Self-Reporting System

A web-based application designed to allow fiber-to-the-home (FTTH) internet customers to self-report network issues while optimizing the ticket management workflow for technicians and management teams.

## 🚀 Key Features
* **Customer Self-Reporting:** Customers can log network issues instantly and track the real-time progress of their tickets.
* **Dynamic Multi-Role System:** * **Admin & Manager:** Oversee the main dashboard, dispatch tasks (assign tickets) to field technicians, manage system users, and export comprehensive performance reports (PDF/Excel).
  * **Technician:** Receive assignments and update ticket resolution progress directly from the field (from *assigned* status to *resolved*).
  * **Customer:** Create complaints and monitor the maintenance live feed.
* **Native Security Architecture:** Secure page access control handled seamlessly via native Laravel Middleware tied directly to the database `role` column.

## 🛠️ Tech Stack
* **Framework:** Laravel 11
* **Database:** MySQL / MariaDB (Managed via DBeaver)
* **CSS Framework:** Tailwind CSS
