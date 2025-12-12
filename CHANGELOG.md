# 📜 CHANGELOG

All notable changes to this project will be documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

---

## [1.0.3] – 2025-12-12

### Added
- Demo seeders for Users, Clients, Projects, and Tasks.
- Realistic sample data for testing Kanban workflows.
- Seeder improvements for better ordering and stability.

### Fixed
- Project seeder status values now use valid enum options.
- php artisan migrate:fresh --seed now executes without errors.

### Improved
- Centralized seeding logic in DatabaseSeeder.
- Sample project/task data structured to reflect real use cases.

---

## [1.0.2] – 2025-12-11
### Fixed
- Enabled full project CRUD routes for MVP.
- Resolved /projects/create returning 404.
- Corrected route grouping and missing admin-only routes.

### Added
- GitHub release notes starter template.
- Laravel workflow file (laravel.yml) for future CI/CD.

---

## [1.0.1] – 2025-12-11
### Added
- Auto-refresh after Kanban task status update (activity log updates immediately).
- Updated task counters after drag-and-drop.
- GitHub-ready README with badges and project description.
- MIT License file.
- Initial CHANGELOG.md created.

### Improved
- Kanban interactions now smoother and more responsive.
- Better structure for versioning and documentation.

---

## [1.0.0] – Initial Release
### Added
- Project creation, editing, deletion, and details page.
- Task management with full CRUD operations.
- Kanban board with drag-and-drop task movement.
- Activity Log for task and project events.
- Role-based access control (Admin / Member).
- Authentication scaffolding (Laravel Breeze).
