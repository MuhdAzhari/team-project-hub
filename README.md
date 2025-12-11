# 🗂️ Team Project Hub
A simple and clean **Laravel-based Project & Task Management System** featuring a Kanban board, activity logging, and role-based access.  
Built as an MVP to demonstrate software architecture, backend development, and UI behaviour.

## 🚀 Key Features

### 🧩 Projects
- Create, edit, and manage client projects  
- Track project status (Planned, Active, On Hold, Completed)  
- Project overview page with metadata  

### 📝 Tasks
- Add tasks under each project  
- Edit, assign users, set priority & due date  
- Delete tasks  
- **Kanban drag-and-drop** (To Do → In Progress → Done)

### 🎯 Kanban Board
- Drag-and-drop task cards  
- Smooth UI transitions  
- **Real-time status update** with Laravel backend  
- Automatic UI counter updates  
- Auto-refresh to show real-time Activity Log updates

### 🛡 Role-Based Access (RBAC)
- Admin and Member roles  
- Admin can manage projects & tasks  
- Members can manage tasks within assigned projects

### 📜 Activity Log (Audit Trail)
Tracks:
- Project updates  
- Task creation  
- Task updates  
- Task deletion  
- **Task status changes from Kanban board**  
Each entry records:
- User who performed the action  
- Timestamp  
- Field-level changes (old → new)

## 🏗 Tech Stack

| Layer | Technology |
|------|------------|
| Backend | **Laravel 10** |
| Frontend | Blade + TailwindCSS + Vanilla JS |
| Authentication | Laravel Breeze |
| Database | MySQL / MariaDB |
| UI Components | Blade components |
| Version Control | GitHub |

## 🔧 Installation

```bash
git clone https://github.com/USERNAME/team-project-hub.git
cd team-project-hub

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
```

Configure `.env`:

```
DB_DATABASE=teamhub
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
php artisan serve
```

## ▶️ Usage

1. Register an account  
2. (Optional) Set your user as admin  
3. Create a project  
4. Add tasks  
5. Drag cards between Kanban lanes  
6. Activity Log updates automatically  

## 📜 Activity Log Details

| Action | Example Entry |
|--------|----------------|
| project_updated | Status changed from On Hold → Active |
| task_created | Task created |
| task_updated | Priority or assignee updated |
| task_deleted | Task removed |
| task_status_changed | To Do → In Progress |

Each log records: user, timestamp, description, changes.

## 🧭 Roadmap

- [ ] Global Activity Log page  
- [ ] Dashboard widgets  
- [ ] File attachments  
- [ ] Task comments  
- [ ] Advanced search & filters  
- [ ] API endpoints  
- [ ] WebSocket real-time updates  

## 🧑‍💻 Author
**Muhammad Azhari Ahmad Kamil**  
Programmer • System Architect • Researcher  
Email: muhd.azhari.ayie@gmail.com  
GitHub: https://github.com/MuhdAzhari

## 📄 License
MIT License

## ⭐ Support
If you like this project, please star the repository!
