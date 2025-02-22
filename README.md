# 📚 Quick Start Documentation

## 🎯 Prerequisites
- 🐳 Docker (required to avoid system configuration conflicts)
- 🛠️ DDev (required)
- 🌿 Git CLI

## 🚀 Installation Steps

### 1. Clone the Repository 📥
```bash
git clone https://github.com/xerxes-on/bookrating.git
cd bookrating
```

### 2. Build and Run Docker Container 🏗️
The project uses a Docker network called **bookrating** which includes:
- 🗄️ PostgreSQL database
- 🌐 Nginx
- 🐘 PHP
- ⚡ Redis
- 📧 Mailhook
- 🔄 Queue worker

Run the following command to build and start all containers:
```bash
ddev start
```
Note: Build time may vary depending on your internet connection and system resources.

### 3. Setup Application ⚙️
Run the following commands to set up the database and application key:
```bash
ddev php artisan migrate
ddev php artisan key:generate
```

### 4. Access the Application 🌍
Visit [https://bookrating.ddev.site](http://bookrating.ddev.site/) in your browser to see the running application.

### 5. Configure Queue Worker 📮
To process email jobs in the queue:
```bash
ddev php artisan queue:work --queue=email
```

## 👑 Setting Up Admin Access

1. Register a new user through the browser interface
2. Run the database seeder to create a super admin:
```bash
ddev php artisan db:seed
```
3. Access the admin panel at [http://bookrating.ddev.site/admin](http://bookrating.ddev.site/admin)

## ℹ️ Additional Information
- 👤 The registered user will be granted super admin privileges after running the seeder
- 🔍 You can explore the admin panel and sample data that was generated
- 🎨 Figma designs are available for reference (link to be added)

## 🏛️ Container Architecture
The project runs within a Docker network containing:
1. 🗄️ PostgreSQL database
2. 🌐 Nginx web server
3. 🐘 PHP runtime
4. ⚡ Redis cache
5. 📧 Mailhook service
6. 🔄 Queue worker for background tasks
