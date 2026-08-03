# Project Structure - Multimedia Club Platform

The project uses CodeIgniter 4's Modular Architecture feature to organize codebase by domain modules under `app/Modules/`.

```
Multimedia-club-platform/
├── ARCHITECTURE.md
├── PROJECT_STRUCTURE.md
├── DATABASE_ERD.md
├── HOW-TO-RUNNING.md
├── spark
├── composer.json
├── .env
│
├── app/
│   ├── Config/
│   │   ├── Autoload.php       # Registers App\Modules namespace
│   │   ├── Filters.php        # AuthFilter & RoleFilter aliases
│   │   ├── Routes.php         # Master route loader
│   │   └── Database.php
│   │
│   ├── Database/
│   │   ├── Migrations/        # 27 Relational migrations with DB indexes
│   │   └── Seeds/             # Role, TaskMaster, User, Setting, CmsSeeder, SampleData seeders
│   │
│   ├── Filters/
│   │   ├── AuthFilter.php     # Route authentication check
│   │   └── RoleFilter.php     # RBAC role permissions filter
│   │
│   ├── Helpers/
│   │   ├── response_helper.php # Standardized API JSON response helper
│   │   └── qr_helper.php       # QR Code utility functions
│   │
│   ├── Services/
│   │   └── BaseService.php    # Base service with DB transaction wrappers
│   │
│   └── Modules/               # Self-contained domain modules
│       │
│       ├── Auth/              # Authentication & Member Registration
│       │   ├── Controllers/
│       │   │   └── AuthController.php
│       │   ├── Services/
│       │   │   └── AuthService.php
│       │   ├── Views/
│       │   │   ├── login.php
│       │   │   └── register.php
│       │   └── Config/
│       │       └── Routes.php
│       │
│       ├── User/              # User & Member Management
│       │   ├── Controllers/
│       │   │   └── UserController.php
│       │   ├── Services/
│       │   │   └── UserService.php
│       │   ├── Models/
│       │   │   ├── UserModel.php
│       │   │   └── RoleModel.php
│       │   ├── Views/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   ├── qr_card.php
│       │   │   └── profile.php
│       │   └── Config/
│       │       └── Routes.php
│       │
│       ├── Meeting/           # Meetings & Workshops Management
│       │   ├── Controllers/
│       │   │   └── MeetingController.php
│       │   ├── Services/
│       │   │   └── MeetingService.php
│       │   ├── Models/
│       │   │   └── MeetingModel.php
│       │   ├── Views/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   └── qr_poster.php
│       │   └── Config/
│       │       └── Routes.php
│       │
│       ├── Attendance/        # Attendance Engine & Scanners
│       │   ├── Controllers/
│       │   │   └── AttendanceController.php
│       │   ├── Services/
│       │   │   └── AttendanceService.php
│       │   ├── Models/
│       │   │   └── AttendanceModel.php
│       │   ├── Views/
│       │   │   ├── index.php
│       │   │   ├── scan_meeting_qr.php
│       │   │   ├── scan_member_qr.php
│       │   │   └── history.php
│       │   └── Config/
│       │       └── Routes.php
│       │
│       ├── Task/              # Multi-Assignee Tasks & ClickUp Timeline
│       │   ├── Controllers/
│       │   │   └── TaskController.php
│       │   ├── Services/
│       │   │   └── TaskService.php
│       │   ├── Models/
│       │   │   ├── TaskModel.php
│       │   │   ├── TaskAssigneeModel.php
│       │   │   ├── TaskSubmissionModel.php
│       │   │   ├── TaskActivityModel.php
│       │   │   ├── TaskStatusModel.php
│       │   │   ├── TaskPriorityModel.php
│       │   │   └── LabelModel.php
│       │   ├── Views/
│       │   │   ├── index.php
│       │   │   ├── create.php
│       │   │   ├── edit.php
│       │   │   ├── detail.php
│       │   │   └── member_tasks.php
│       │   └── Config/
│       │       └── Routes.php
│       │
│       ├── Cms/               # Dynamic Website Content Management System (WCMS)
│       │   ├── Config/
│       │   │   └── Routes.php
│       │   ├── Controllers/
│       │   │   ├── CmsController.php
│       │   │   ├── MediaLibraryController.php
│       │   │   ├── DivisionCmsController.php
│       │   │   ├── PortfolioCmsController.php
│       │   │   ├── AchievementCmsController.php
│       │   │   ├── HistoryCmsController.php
│       │   │   └── OrgCmsController.php
│       │   ├── Services/
│       │   │   ├── CmsService.php
│       │   │   └── MediaLibraryService.php
│       │   └── Views/
│       │       ├── homepage_builder.php
│       │       ├── contact_messages.php
│       │       ├── media_library/
│       │       ├── divisions/
│       │       ├── portfolios/
│       │       ├── achievements/
│       │       ├── history/
│       │       └── structure/
│       │
│       ├── Dashboard/         # Role-Based Dashboard View
│       │   ├── Controllers/
│       │   │   └── DashboardController.php
│       │   ├── Views/
│       │   │   ├── admin.php
│       │   │   └── member.php
│       │   └── Config/
│       │       └── Routes.php
│       │
│       └── System/            # Audit Logs & Settings Manager
│           ├── Controllers/
│           │   └── SystemController.php
│           ├── Services/
│           │   └── SystemService.php
│           ├── Models/
│           │   ├── AuditLogModel.php
│           │   ├── SettingModel.php
│           │   └── NotificationModel.php
│           ├── Views/
│           │   ├── audit_logs.php
│           │   └── settings.php
│           └── Config/
│               └── Routes.php
│
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css      # Dark SaaS Design System CSS & Breakpoints
│   │   ├── vendor/            # Offline vendor JS/CSS/Fonts (Bootstrap, FontAwesome, DataTables, SweetAlert2)
│   │   └── logo-mm-2023.png
│   ├── uploads/
│   │   └── cms/               # Centralized Media Library Storage
│   └── index.php
```
