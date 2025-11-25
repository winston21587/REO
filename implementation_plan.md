# Admin UI Enhancements Implementation Plan

## Goal Description
Enhance the REO Admin Portal by making "Active Protocols" and "Meeting & Agenda" accessible and visually appealing, and beautifying the existing "Analytics", "Initial Intake", "REO Members", and "Researchers" pages. The design will be consistent, premium, and responsive, leveraging the `admin_layout` and TailwindCSS.

## User Review Required
> [!IMPORTANT]
> "Meeting & Agenda" currently has no route or view. I will create a new route `/admin/meetings` and a corresponding view.

## Proposed Changes

### Routes
#### [MODIFY] [web.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/routes/web.php)
- Add route for `admin.meetings`.

### Admin Controller
#### [MODIFY] [admin.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/app/Http/Controllers/admin.php)
- Add `meetings()` method to handle the Meeting & Agenda view.

### Views (New & Modified)

#### [MODIFY] [admin_layout.blade.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/resources/views/components/admin_layout.blade.php)
- Update navigation links to point to the correct routes (specifically Meeting & Agenda).

#### [MODIFY] [applications.blade.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/resources/views/admin/applications.blade.php)
- **Active Protocols**: Redesign table/grid to show protocol details (ID, Title, PI, Status, Date) with status badges and action buttons.

#### [NEW] [meetings.blade.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/resources/views/admin/meetings.blade.php)
- **Meeting & Agenda**: Create a calendar or list view for upcoming meetings and agenda items.

#### [MODIFY] [Analytics.blade.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/resources/views/admin/Analytics.blade.php)
- **Analytics**: Enhance charts (using CSS/SVG or a library if available, currently SVG) and stat cards.

#### [MODIFY] [NewSubmissions.blade.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/resources/views/admin/NewSubmissions.blade.php)
- **Initial Intake**: Improve the list of pending/incomplete submissions with clear actions and status indicators.

#### [MODIFY] [manage_staff.blade.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/resources/views/admin/manage_staff.blade.php)
- **REO Members**: Card or list layout for staff members with roles and contact info.

#### [MODIFY] [manage_users.blade.php](file:///c:/xampp/htdocs/YEAR%203/SE/REO/resources/views/admin/manage_users.blade.php)
- **Researchers**: Searchable list of registered researchers with verification status.

## Verification Plan

### Manual Verification
- **Active Protocols**: Visit `/admin/applications`, check data display and responsiveness.
- **Meeting & Agenda**: Visit `/admin/meetings`, check layout.
- **Analytics**: Visit `/admin`, check visual polish.
- **Initial Intake**: Visit `/admin/new`, check list styling.
- **REO Members**: Visit `/admin/staff`, check staff list.
- **Researchers**: Visit `/admin/users`, check user list.
