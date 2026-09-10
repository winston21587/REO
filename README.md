<p align="center">
  <img src="https://raw.githubusercontent.com/winston21587/REO/master/public/images/reoc-nobg.png" alt="WMSU-REO Logo" width="180">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Python-3.x-3776AB?style=for-the-badge&logo=python&logoColor=white" alt="Python 3.x">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4.x-38BDF8?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS 4.x">
  <img src="https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite 7.x">
  <img src="https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

<h1 align="center">WMSU REO</h1>
<h3 align="center">Research Ethics Oversight Committee Portal</h3>

<p align="center">
  A unified digital platform for managing the complete lifecycle of research ethics review at Western Mindanao State University — from proposal submission through committee evaluation to clearance certification.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Status-Active%20Development-22C55E?style=flat-square" alt="Status">
  <img src="https://img.shields.io/badge/Architecture-Full%20Stack-8B5CF6?style=flat-square" alt="Architecture">
  <img src="https://img.shields.io/badge/AI%20Enabled-Yes-F59E0B?style=flat-square" alt="AI Enabled">
  <img src="https://img.shields.io/badge/license-MIT-green?style=flat-square" alt="License MIT">
</p>

---

## Overview

**WMSU REO** replaces fragmented paper submissions and ad-hoc email communications with a centralized workflow engine that guarantees statutory compliance with Philippine Health Research Ethics Board (PHREB) standards.

The system connects researchers, reviewers, administrators, and super-admins in one platform for:

- Research proposal submission with supporting documents
- Document verification and completeness triage
- Reviewer assignment based on expertise and college affiliation
- Appointment scheduling and meeting management
- Structured feedback, revision tracking, and decision history
- Automated email notifications at every critical stage
- AI-assisted preliminary classification using machine learning

---

## Features

### Researcher Portal
- Submit research titles with descriptions and category selection
- Upload required supporting documents (protocols, consent forms, CVs)
- Track proposal progress through each review stage
- Receive and respond to reviewer feedback and revision requests

### Reviewer Portal
- Review assigned submissions with integrated document viewer
- Submit structured remarks and recommendations per file
- Track review history and monitor revision cycles
- Communicate through the built-in feedback system

### Admin Dashboard
- Conduct initial completeness triage on incoming submissions
- Assign qualified reviewers to protocols
- Manage review cycles, deadlines, and appointments
- Generate official clearance certificates and recommendation letters
- Publish announcements, guidelines, and FAQs via built-in CMS

### Super-Admin Controls
- Manage all users, roles, and reviewer pools
- Configure review fee structures
- View institutional analytics and submission metrics
- Oversee system-wide settings and access controls

### AI Decision Support
- Trained IRB classification model for preliminary risk assessment
- Accepts extracted document text for automated analysis
- Returns predicted review type and approval probability
- Assists reviewers with initial screening to reduce turnaround time

---

## Technology Stack

### Backend
<p>
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Eloquent-ORM-2F2F2F?style=flat-square" alt="Eloquent ORM">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
</p>

### Frontend
<p>
  <img src="https://img.shields.io/badge/Blade-Templates-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Blade">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4.x-38BDF8?style=flat-square&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Vite-7.x-646CFF?style=flat-square&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=flat-square&logo=alpine.js&logoColor=black" alt="Alpine.js">
  <img src="https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=flat-square&logo=javascript&logoColor=black" alt="JavaScript">
</p>

### AI / Machine Learning
<p>
  <img src="https://img.shields.io/badge/Python-3.x-3776AB?style=flat-square&logo=python&logoColor=white" alt="Python">
  <img src="https://img.shields.io/badge/Flask-API-000000?style=flat-square&logo=flask&logoColor=white" alt="Flask">
  <img src="https://img.shields.io/badge/scikit--learn-ML-F7931E?style=flat-square" alt="scikit-learn">
  <img src="https://img.shields.io/badge/pandas-Analysis-150458?style=flat-square&logo=pandas&logoColor=white" alt="pandas">
</p>

### Document Processing
<p>
  <img src="https://img.shields.io/badge/PHPOffice-PHPWord-777BB4?style=flat-square" alt="PHPWord">
  <img src="https://img.shields.io/badge/TCPDF-PDF%20Generation-777777?style=flat-square" alt="TCPDF">
  <img src="https://img.shields.io/badge/FPDI-PDF%20Templating-777777?style=flat-square" alt="FPDI">
  <img src="https://img.shields.io/badge/Spatie-PDF%20to%20Text-00AAFF?style=flat-square&logo=laravel&logoColor=white" alt="Spatie">
  <img src="https://img.shields.io/badge/Groq-AI%20Integration-000000?style=flat-square" alt="Groq">
</p>

---

## Architecture

The application uses a **dual-backend architecture**: a Laravel web application handles business logic and user-facing workflows, while a separate Python/Flask microservice provides AI-powered classification and document analysis.

```mermaid
flowchart LR
    U[Researchers / Reviewers / Admins] --> L[Laravel 12 Web App]
    L --> DB[(MySQL Database)]
    L --> FS[File Storage]
    L --> M[Email Notifications]
    L --> P[Python Flask API]

    P --> ML[Trained IRB Model]
    P --> DOC[Document Processing]
    P --> RES[Prediction Results]

    L -->|JSON REST API| P
    P -->|JSON Response| L
```

---

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL 8.x
- Python 3.x (for the ML microservice)

### Installation

```bash
# Clone the repository
git clone https://github.com/winston21587/REO.git
cd REO

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

### AI Microservice (Optional)

```bash
# Navigate to the Python service directory
cd python-ml

# Install dependencies
pip install -r requirements.txt

# Start the Flask API
python app.py
```

---

## License

This project is licensed under the [MIT License](LICENSE).
