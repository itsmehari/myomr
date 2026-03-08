# MyOMR.in - Community Hub for OMR Corridor

**Version:** 2.0.0 | **Status:** Production Active | **Last Updated:** December 2024

Welcome to the MyOMR.in project! This is the digital community hub for the Old Mahabalipuram Road (OMR) corridor in Chennai, India. The platform connects residents, businesses, and visitors with news, events, jobs, and local services.

---

## 🚀 Quick Start

### **New to the Project?**

👉 **Read the [PROJECT_MASTER.md](PROJECT_MASTER.md) file first!**

This master file contains:

- ✅ Complete project rules and guidelines
- ✅ Development workflow instructions
- ✅ Database management guides
- ✅ Security best practices
- ✅ Deployment checklists
- ✅ AI assistant guidelines

### **Developer Setup (5 Minutes)**

```powershell
# 1. Clone repository
git clone https://github.com/yourusername/myomr-root.git
cd myomr-root

# 2. Start SSH tunnel to live database
cd dev-tools
.\start-ssh-tunnel.ps1

# 3. Start local server
php -S localhost:80

# 4. Access dev dashboard
# Visit: http://localhost/dev-tools/
```

---

## ✨ Key Features

- 📰 Local news, events, and community updates
- 🏢 Business and services directory (15+ categories)
- 💼 Job listings and opportunities
- 📸 Photo galleries and media
- 🤝 Community engagement and contributions
- 📍 Location-based services with maps
- 🔐 Secure admin panel for content management

---

## 📚 Documentation Hub

| Document                                         | Purpose                 | Read When               |
| ------------------------------------------------ | ----------------------- | ----------------------- |
| **[PROJECT_MASTER.md](PROJECT_MASTER.md)**       | **🎯 Master reference** | **Always start here**   |
| [CHANGELOG.md](CHANGELOG.md)                     | Version history         | Before updating         |
| [docs/DATABASE_INDEX.md](docs/DATABASE_INDEX.md) | Database documentation  | Working with data       |
| [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)     | System architecture     | Understanding structure |
| [docs/USER_GUIDE_V2.md](docs/USER_GUIDE_V2.md)   | End user guide          | For users/testers       |
| [dev-tools/README.md](dev-tools/README.md)       | Dev tools guide         | Using dev tools         |

## ⚠️ Critical Rules

**Before making ANY changes:**

1. ❌ **NEVER deploy `dev-tools/` folder to production**
2. ❌ **NEVER commit database credentials to Git**
3. ❌ **NEVER skip input validation in database queries**
4. ✅ **ALWAYS read [PROJECT_MASTER.md](PROJECT_MASTER.md) first**
5. ✅ **ALWAYS use prepared statements for SQL queries**
6. ✅ **ALWAYS test locally before deploying**
7. ✅ **ALWAYS backup before schema changes**

See [PROJECT_MASTER.md](PROJECT_MASTER.md) for complete rules.

---

## 🛠️ Project Structure

```
Root/
├── PROJECT_MASTER.md           ← 🎯 START HERE - Master reference
├── index.php                   ← Homepage
├── admin/                      ← Admin panel
├── omr-listings/               ← Public directory pages
├── local-news/                 ← News articles
├── events/                     ← Events & happenings
├── components/                 ← Reusable UI components
├── core/                       ← Core logic & DB connection
├── assets/                     ← CSS, JS, images
├── dev-tools/                  ← 🚫 Development tools (NEVER deploy)
└── docs/                       ← Documentation files
```

**Important:** `dev-tools/` folder is for **local development only** and should **NEVER** be deployed to production.

---

## 🤝 Contributing

### **Before Making Changes:**

1. Read [PROJECT_MASTER.md](PROJECT_MASTER.md)
2. Check [docs/TODO.md](docs/TODO.md) for current tasks
3. Review [CHANGELOG.md](CHANGELOG.md) for recent changes

### **Development Workflow:**

1. Create feature branch: `git checkout -b feature/your-feature`
2. Make changes and test locally
3. Update documentation if needed
4. Commit: `git commit -m "Description"`
5. Push: `git push origin feature/your-feature`
6. Create pull request

### **Documentation Updates:**

- Update `CHANGELOG.md` for version changes
- Update relevant `docs/*.md` files for features
- Update `PROJECT_MASTER.md` for major changes

---

## 📞 Support

**Questions or Issues?**

- 📧 Email: myomrnews@gmail.com
- 📖 Documentation: Start with [PROJECT_MASTER.md](PROJECT_MASTER.md)
- 🐛 Bug Reports: Create an issue with detailed description

**For Developers:**

- 🔧 Dev Tools: [dev-tools/README.md](dev-tools/README.md)
- 🗄️ Database: [docs/DATABASE_INDEX.md](docs/DATABASE_INDEX.md)
- 🏗️ Architecture: [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)

---

## 🎯 Tech Stack

| Layer       | Technology                                   |
| ----------- | -------------------------------------------- |
| Frontend    | HTML5, CSS3, Bootstrap 5, Vanilla JavaScript |
| Backend     | PHP 7.4+ (Procedural)                        |
| Database    | MySQL 5.7+                                   |
| Hosting     | cPanel Shared Hosting                        |
| Development | Windows + SSH Tunnel to Live DB              |

---

## 📋 Version History

**Current:** 2.0.0 (December 2024)

- Complete database documentation
- Development tools infrastructure
- Enhanced security features
- Comprehensive documentation

See [CHANGELOG.md](CHANGELOG.md) for full history.

---

## ✅ Quick Checklist

**I'm a new developer, what do I do?**

- [ ] Read [PROJECT_MASTER.md](PROJECT_MASTER.md)
- [ ] Setup local environment
- [ ] Start SSH tunnel: `.\dev-tools\start-ssh-tunnel.ps1`
- [ ] Access dev tools: http://localhost/dev-tools/
- [ ] Verify connection and explore

**I'm deploying to production, what do I check?**

- [ ] Read deployment section in [PROJECT_MASTER.md](PROJECT_MASTER.md)
- [ ] Backup database and files
- [ ] Test all changes locally
- [ ] Exclude `dev-tools/` folder
- [ ] Verify live site after deployment

---

**This project is a collaborative effort. Let's make OMR a better place together! 🚀**

For complete guidelines, always refer to **[PROJECT_MASTER.md](PROJECT_MASTER.md)**
