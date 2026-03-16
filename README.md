# 🎓 Student Feedback Analysis System

A web-based **Student Feedback System** developed using **PHP, MySQL, Bootstrap, and Chart.js**.  
This system allows administrators and HODs to analyze student feedback on teachers and courses using graphical dashboards.

---

## 📌 Project Overview

The **Student Feedback Analysis System** helps colleges collect and analyze feedback submitted by students through **Google Forms**.  
The feedback data is exported as CSV and uploaded into the system for analysis.

The system generates:

- Teacher performance reports
- Course feedback analysis
- Submission statistics
- Visual charts and dashboards

---

## 🚀 Features

### 👨‍💼 Admin Panel
- Upload student data using CSV
- Upload teacher data using CSV
- Upload Google Form feedback CSV
- View feedback reports
- Filter data by department, year, and semester
- Dashboard with performance charts

### 👨‍🏫 HOD Panel
- Department-restricted dashboard
- View teacher performance
- Monitor student feedback submissions
- Performance comparison charts

### 📊 Analytics
- Teacher rating analysis
- Course content feedback analysis
- Best teacher identification
- Lowest rated teacher identification
- Submission percentage tracking
- Chart visualization using Chart.js

---

## 🛠️ Technologies Used

| Technology | Purpose |
|-----------|--------|
| PHP | Backend Development |
| MySQL | Database |
| Bootstrap 5 | UI Design |
| Chart.js | Data Visualization |
| HTML/CSS | Frontend |
| XAMPP | Local Server |

---

## 🗂️ Project Structure

---

# ⚙️ Installation & Setup

### 1️⃣ Install XAMPP
Download and install XAMPP.

### 2️⃣ Move Project
Copy the project folder into:


### 3️⃣ Start Server
Open XAMPP Control Panel and start:

- Apache
- MySQL

### 4️⃣ Setup Database

Open **phpMyAdmin**

Create database: feedback_system

Import the SQL file.

### 5️⃣ Run Project

Open in browser:
http://localhost/college-feedback-system

---

# 📄 CSV Upload Format

## Students CSV
id,name,department,year,semester
## Teachers CSV
id,teacher_name,department


## Feedback CSV

Export responses from **Google Forms** and upload them through the **Upload Feedback** page.

---

# 📊 Dashboard Preview

The dashboard shows:

- Total feedback responses
- Submission percentage
- Teacher performance charts
- Best performing teacher
- Lowest rated teacher

---

# 👨‍💻 Author

**Rachamreddy Manivardhanreddy**

Second Year Project  
Student Feedback Analysis System

---

# ⭐ Future Improvements

- Direct integration with Google Forms API
- Student login system
- Real-time feedback dashboard
- Export reports to PDF

---

# 📜 License

This project is created for **educational purposes**.
