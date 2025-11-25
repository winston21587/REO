# REO Admin Portal System Overview

## Project Context
The Research Ethics Office (REO) Admin Portal is a Laravel 12 application designed to manage research protocols, ethics reviews, and committee memberships. It serves as the central hub for the REO staff to oversee the entire research lifecycle.

## Key Modules

### 1. Active Protocols
- **Purpose:** Manage ongoing research applications.
- **Features:** List view with status indicators, search/filter, and access to detailed protocol views.
- **Tech:** Blade templates, TailwindCSS, Laravel Controllers.

### 2. Meeting & Agenda
- **Purpose:** Schedule and manage ethics committee meetings.
- **Features:** Upcoming meeting cards, agenda preparation, and meeting minutes generation.
- **Tech:** Custom Blade view (`meetings.blade.php`), dedicated route.

### 3. Analytics
- **Purpose:** Provide insights into submission trends and performance metrics.
- **Features:** Key metrics cards, submission trend charts, and compliance status.
- **Tech:** Chart.js (or similar), TailwindCSS grid layout.

### 4. Initial Intake
- **Purpose:** Process new submissions and assign review types.
- **Features:** Triage modal for classifying protocols (Exempt, Expedited, Full Review), submission list.
- **Tech:** Modal logic (Alpine.js/Vanilla JS), Form handling.

### 5. REO Members
- **Purpose:** Manage the ethics committee composition.
- **Features:** Member directory, role classification (Scientist/Lay Member), quorum status.
- **Tech:** Grid/List layout, User management.

### 6. Researchers
- **Purpose:** Directory of all registered researchers (Faculty, Students, Staff).
- **Features:** Searchable user list, contact details, and status management.
- **Tech:** Search/Filter UI, User model integration.

## Technical Stack
- **Framework:** Laravel 12
- **Frontend:** Blade Templates, TailwindCSS, Vite
- **Database:** MySQL (implied)
- **Authentication:** Laravel Auth (Internal/External users)

## Design Philosophy
- **Premium & Clean:** Uses a white/slate color scheme with the primary brand color (`#8B0000`) for accents.
- **Responsive:** Fully adaptive layouts for desktop and tablet use.
- **Accessible:** Semantic HTML and clear visual hierarchy.
