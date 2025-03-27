# 🚀 Smart Inventory Management System (SIMS)

<div align="center">
  <img src="https://ucarecdn.com/e21998fa-f2ff-4d75-a96e-2a878cbf1582/sims.png" alt="SIMS Dashboard">
  <p><em>Live Demo: <a href="http://sims.ct.ws/" target="_blank">http://sims.ct.ws/</a></em></p>
</div>

## 📖 Table of Contents
- [Project Overview](#-project-overview)
- [Features](#-features)
- [Database Structure](#-database-structure)
- [File Structure](#-file-structure)
- [Setup Guide](#setup-guide)
- [Areas for Improvement](#-areas-for-improvement)

## 🌟 Project Overview

**Smart Inventory Management System (SIMS)** is a comprehensive PHP-based web application designed to streamline inventory tracking, sales management, and supplier relationships for small to medium businesses. The system provides real-time insights into stock levels, sales performance, and supply chain management.

### Key Objectives:
- 📊 Centralized inventory tracking
- 💰 Sales and revenue monitoring
- 🤝 Supplier relationship management
- 🔒 User authentication and access control
- 📈 Data visualization and reporting

## ✨ Features

### Core Modules
| Module | Description |
|--------|-------------|
| **Dashboard** | Overview of key metrics and performance indicators |
| **Inventory** | Product management with stock level monitoring |
| **Sales** | Transaction recording and revenue tracking |
| **Suppliers** | Vendor management and supply chain coordination |
| **User Management** | Secure authentication and access control |

### Technical Highlights
- PHP backend with MySQL database
- Responsive HTML/CSS interface
- Chart.js for data visualization
- Form validation and security measures
- Session-based authentication

## 🗃️ Database Structure

### ER Diagram
![ER Diagram](https://ucarecdn.com/eee307cc-80fe-41cd-af34-8401462369a6/_inventorymanagementsystem_db.png) 

Key Tables:
- `admin_logins` - User authentication credentials
- `sales` - Sales transaction records
- `inventory` - Product stock information
- `suppliers` - Vendor details and relationships

### Data Flow
![Flow Diagram](https://ucarecdn.com/ad163bd7-ebec-4a5c-85ea-1b4410ea152d/flow_diagram.png) 
The system follows a MVC-like pattern with:
1. User requests → PHP controllers
2. Data processing → MySQL queries
3. Response rendering → HTML/CSS views

## 📂 File Structure

### Core Application Files
| File | Purpose |
|------|---------|
| `index.php` | Login page and authentication handler |
| `dashboard.php` | Main dashboard with analytics |
| `inventory.php` | Product inventory management |
| `sales.php` | Sales recording and tracking |
| `suppliers.php` | Vendor management interface |
| `db_connection.php` | Database connection configuration |

### Support Files
| Directory/Files | Contents |
|-----------------|----------|
| `css/` | Stylesheets for the application |
| `img/` | Application images and assets |
| `vendor/` | Third-party dependencies (PHPMailer, etc.) |
| `automations/` | Scripts for automated tasks |
| `sims.sql` | Database schema and initial data |

## 🛠️ Setup Guide
<a id = 'setup-guide'></a>

### Prerequisites
- XAMPP/WAMP/MAMP stack
- PHP 8+
- MySQL 5.7+
- Composer (for dependencies)

### Installation Steps
1. **Clone the repository**
   ```bash
   git clone https://github.com/samukelok/sims.git
   cd sims
2. **Set up the database**
   ```bash
   Import sims.sql into your MySQL server 
   Configure credentials inside db_connection.php
3. **Install dependencies**
   ```bash
   composer install

4. **Configure the environment**
   ```bash
   Set up virtual host or run on htdocs
   Ensure proper file permissions

5. **Access the application**
   ```bash
   Visit http://localhost/sims on your browser
   Use default credentials or register new account

## 🔧 Areas for Improvement
## Security Enhancements
- 🔐 Implement password hashing (bcrypt/Argon2)
- 🛡️ Add CSRF protection for forms
- 🔒 Implement role-based access control
- 📝 Add password complexity requirements
- 🕵️‍♂️ Regular security audits

## Functional Improvements
- 🛒 E-commerce integration for a more automatic feel on managing inventory and sales
- 📱 Mobile-responsive redesign
- 📦 Barcode scanning capability for stock taking
- 📊 Advanced reporting module
- 🔄 Real-time inventory sync

## Technical Upgrades
- 🏗️ Migrate to Laravel framework
- 🗃️ Implement proper MVC architecture
- 🧪 Add unit/integration tests
- 📦 Dockerize the application
- 🔄 More of CI/CD pipeline

### Thank you for visiting.
