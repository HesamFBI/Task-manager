# HESAM - Smart Task Management System

A sleek, modern, and highly secure web-based Task Management application built with **PHP (OOP/PDO)** and a vanilla front-end styled using the **Glassmorphism** design paradigm. 

---

## 🚀 Features

- **Glassmorphism UI/UX**: Dark mode aesthetic with premium blur effects, glowing accents, and fluid animations.
- **Dynamic Calendar Widget**: Interactive weekly and monthly views to track deadlines seamlessly.
- **Task Analytics & Progress**: Real-time progress tracking bar that calculates daily completion rates.
- **Advanced Filtering**: Instant categorization (Work, Study, Personal, Sports) powered by optimized logic.
- **Admin Supervision Panel**: Centralized control dashboard for administrators to monitor all user tasks.
- **Robust Security**: Multi-tier security framework including BCrypt password hashing, session validation, and full protection against SQL Injection.

---

## 📁 Project Structure

Based on the official directory tree shown in `image_28fd6c.png`:

TASK-MANAGER/
│
├── assets/
│   ├── css/
│   │   └── style.css       # Core Glassmorphism design and animations
│   ├── images/             # Visual graphic assets
│   └── js/
│       └── main.js        # Calendar rendering and client-side interactions
│
├── auth/
│   ├── change_profile.php # Secure profile & credential updater
│   ├── login.php          # User authentication entry
│   ├── logout.php         # Clean session termination
│   └── register.php       # New user onboarding with strict validation
│
├── config/
│   └── db.php             # Secure Database connection instance (PDO)
│
├── includes/
│   ├── footer.php         # Reusable application footer
│   └── header.php         # Global navigation bar & session checking
│
├── admin.php              # Global admin monitoring and filtering panel
├── Hesam Azizzadeh.pdf    # Academic project documentation
├── index.php              # Core User Dashboard and logic hub
├── process.php            # Main backend request router and form handler
└── ReadMe.php             # Application about page


---

## 🛠️ Tech Stack

- **Backend:** PHP 8.x (PDO Object-Oriented Database Driver)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3 (Modern Flexbox/Grid), Vanilla JavaScript (ES6+)

---

## 💾 Installation & Setup

1. **Clone the Repository:**
   
   git clone [https://github.com/HesamFBI/task-manager.git](https://github.com/HesamFBI/task-manager.git)
Database Setup:

Open phpMyAdmin.

Create a database named hesam_db.

Import the provided schema or configuration file (tasks.sql).

Environment Configuration:

Configure your local server (XAMPP/WampServer).

Verify server credentials inside config/db.php.

Run Application:

Move the directory to your local web root (e.g., htdocs).

Navigate to http://localhost/task-manager/ in your browser.

📄 سامانه هوشمند مدیریت وظایف (HESAM)
یک پلتفرم تحت وب پیشرفته، مدرن و بسیار ایمن جهت مدیریت زمان و وظایف روزانه که با هسته قدرتمند PHP (PDO) و ظاهر اختصاصی شیشه‌ای (Glassmorphism) توسعه یافته است.

✨ ویژگی‌های کلیدی سامانه
رابط کاربری مدرن (Glassmorphism): زبان طراحی تاریک همراه با افکت‌های بلور، سایه‌های درخشان و انیمیشن‌های روان.

ابزارک تقویم داینامیک: قابلیت سوییچ بین نمای هفتگی و ماهانه جهت پایش هوشمند ددلاین‌ها.

نمودار پیشرفت زنده: محاسبه خودکار درصد موفقیت روزانه و پر شدن نوار وضعیت گرافیکی.

سیستم فیلترینگ پیشرفته: تفکیک آنی وظایف بر اساس دسته‌بندی‌ها (کاری، درسی، شخصی، ورزشی).

پنل نظارت کلان ادمین (admin.php): میز کار اختصاصی مدیر سیستم جهت فیلتر و مانیتورینگ تسک‌های تمامی کاربران.

امنیت چند لایه: رمزنگاری پسوردها با الگوریتم‌های استاندارد وب، مهار هوشمند خطاهای پایگاه داده و مصونیت کامل در برابر حملات SQL Injection.

📂 ساختار درختی و مهندسی فایل‌ها
مطابق با نمای رسمی ساختار پروژه در تصویر image_28fd6c.png:

TASK-MANAGER/
│
├── assets/
│   ├── css/
│   │   └── style.css       # کدهای استایل‌دهی شیشه‌ای و انیمیشن‌ها
│   ├── images/             # المان‌ها و دارایی‌های گرافیکی
│   └── js/
│       └── main.js        # منطق رندر تقویم و تعاملات فرانت‌اند
│
├── auth/
│   ├── change_profile.php # فرم ایمن ویرایش مشخصات و رمز عبور
│   ├── login.php          # صفحه ورود کاربران
│   ├── logout.php         # سیستم خروج و پاکسازی سشن‌ها
│   └── register.php       # فرم ثبت‌نام همراه با ولیدیشن فیلدها
│
├── config/
│   └── db.php             # فایل اتصال به پایگاه داده با شیوه امن PDO
│
├── includes/
│   ├── footer.php         # کامپوننت فوتر مشترک صفحات
│   └── header.php         # هدر اصلی و سیستم اعتبارسنجی نشست‌ها
│
├── admin.php              # پنل سرپرستی ادمین و فیلترینگ پیشرفته وظایف
├── Hesam Azizzadeh.pdf    # مستندات جامع پروژه
├── index.php              # داشبورد اصلی و هسته پردازش تسک‌های کاربر
├── process.php            # مدیریت مرکزی درخواست‌ها و عملیات‌های فرم
└── ReadMe.php             # صفحه درباره برنامه
🛠️ تکنولوژی‌های مورد استفاده
بک‌اند: PHP 8.x (درایور شیءگرای PDO)

پایگاه داده: MySQL / MariaDB

فرانت‌اند: HTML5, CSS3, Vanilla JavaScript (ES6+)

🚀 راهنمای راه‌اندازی و اجرا
۱. دریافت سورس کد:


git clone [https://github.com/HesamFBI/task-manager.git](https://github.com/HesamFBI/task-manager.git)
۲. تنظیمات پایگاه داده:

وارد محیط phpMyAdmin شوید.

یک دیتابیس با نام hesam_db بسازید.

فایل خروجی دیتابیس (tasks.sql) را داخل آن ایمپورت کنید.
۳. پیکربندی سرور محلی:

مشخصات اتصال به دیتابیس را در صورت نیاز در فایل config/db.php بررسی کنید.
۴. اجرای پروژه:

پوشه پروژه را به هاست محلی خود (مانند htdocs در XAMPP) منتقل کنید.

آدرس http://localhost/task-manager/ را در مرورگر باز کنید.