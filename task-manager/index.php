<?php
// index.php
// اتصال هدر از پوشه شاملات (includes) طبق ساختار جدید پروژه‌ی HESAM
require_once __DIR__ . '/includes/header.php'; 
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد هوشمند HESAM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { height: 100vh; background: #18191b; display: flex; justify-content: center; align-items: center; overflow: hidden; position: relative; padding: 20px; }
        
        .liquid-bg { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; overflow: hidden; }
        .blob { position: absolute; border-radius: 50%; filter: blur(100px); mix-blend-mode: screen; animation: moveBlobs 14s infinite alternate ease-in-out; opacity: 0.6; }
        .blob-1 { width: 500px; height: 500px; background: radial-gradient(circle, #2563eb, transparent); top: -10%; left: -10%; }
        .blob-2 { width: 500px; height: 500px; background: radial-gradient(circle, #3b82f6, transparent); bottom: -15%; right: -10%; }
        
        .app-container { width: 100%; max-width: 1200px; height: 85vh; max-height: 800px; min-height: 620px; display: grid; grid-template-columns: 260px 1fr 380px; gap: 20px; z-index: 1; overflow: hidden; }

        /* Sidebar */
        .sidebar { background: rgba(30, 31, 33, 0.4); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 24px; padding: 35px 18px; display: flex; flex-direction: column; justify-content: space-between; height: 100%; }
        .sidebar-top { text-align: center; }
        .avatar-glow { width: 65px; height: 65px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 20px; margin: 0 auto 12px; display: flex; justify-content: center; align-items: center; font-size: 1.4rem; font-weight: bold; color: white; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3); }
        .tehran-clock { background: rgba(255, 255, 255, 0.04); padding: 10px; border-radius: 12px; font-size: 0.85rem; color: #94a3b8; text-align: center; width: 100%; margin-top: 10px; border: 1px solid rgba(255, 255, 255, 0.02); }
        
        .sidebar-menu { display: flex; flex-direction: column; gap: 6px; margin-top: 25px; flex: 1; }
        .menu-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 14px; color: #a1a1aa; text-decoration: none; font-size: 0.85rem; transition: all 0.3s; cursor: pointer; }
        .menu-item:hover { background: rgba(255, 255, 255, 0.03); color: #fff; }
        .menu-item.active { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.15); font-weight: bold; }

        .progress-widget { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.04); padding: 14px; border-radius: 16px; margin-bottom: 15px; }
        .progress-info { display: flex; justify-content: space-between; font-size: 0.75rem; color: #94a3b8; margin-bottom: 8px; }
        .progress-bar-bg { background: #18191b; height: 6px; border-radius: 4px; overflow: hidden; }
        .progress-bar-fill { background: linear-gradient(90deg, #3b82f6, #60a5fa); height: 100%; width: 0%; transition: width 0.4s ease; }
        
        .logout-btn { background: rgba(248, 113, 113, 0.08); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.15); width: 100%; padding: 12px; border-radius: 14px; text-decoration: none; text-align: center; font-size: 0.85rem; font-weight: bold; display: block; transition: 0.3s; }
        .logout-btn:hover { background: #f87171; color: white; }
        .admin-link { background: rgba(103, 232, 249, 0.08); color: #67e8f9; border: 1px solid rgba(103, 232, 249, 0.15); width: 100%; padding: 12px; border-radius: 14px; text-decoration: none; text-align: center; font-size: 0.85rem; font-weight: bold; display: block; transition: 0.3s; margin-bottom: 10px; }
        .admin-link:hover { background: #67e8f9; color: #000; }
        .profile-link { background: rgba(168, 85, 247, 0.08); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.15); width: 100%; padding: 12px; border-radius: 14px; text-decoration: none; text-align: center; font-size: 0.85rem; font-weight: bold; display: block; transition: 0.3s; margin-bottom: 10px; }
        .profile-link:hover { background: #c084fc; color: #000; }

        /* Calendar Middle Card */
        .main-content { display: flex; justify-content: center; align-items: center; height: 100%; }
        .widget-card { background: #222325; border: 1px solid rgba(255, 255, 255, 0.04); border-radius: 32px; width: 100%; max-width: 440px; padding: 30px; box-shadow: 0 40px 80px rgba(0, 0, 0, 0.8); display: flex; flex-direction: column; gap: 28px; direction: ltr; }
        .widget-header { display: flex; justify-content: space-between; align-items: center; }
        .toggle-capsule { background: #18191b; padding: 6px; border-radius: 14px; display: flex; gap: 4px; }
        .toggle-btn { padding: 8px 18px; border-radius: 10px; font-size: 0.8rem; font-weight: bold; cursor: pointer; border: none; color: #71717a; background: transparent; transition: 0.3s; }
        .toggle-btn.active { background: #e4e4e7; color: #18191b; }

        .big-date-display { display: flex; justify-content: space-between; align-items: center; color: #fff; }
        .big-month { font-size: 2.4rem; font-weight: 500; }
        .big-day-num { font-size: 2.8rem; font-weight: 400; }

        .week-strip-container { display: flex; flex-direction: column; gap: 10px; width: 100%; }
        .strip-labels { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; color: #52525b; font-size: 0.75rem; font-weight: bold; }
        .strip-days { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; gap: 5px; }
        .strip-day-cell { color: #a1a1aa; padding: 10px 0; font-size: 0.85rem; cursor: pointer; border-radius: 10px; transition: 0.2s; }
        .strip-day-cell.active { background: #3b82f6 !important; color: #fff !important; font-weight: bold; box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4); }

        .monthly-grid-container { display: none; flex-direction: column; gap: 8px; width: 100%; }
        .month-grid-header { display: flex; justify-content: space-between; align-items: center; color: #fff; font-size: 0.85rem; direction: rtl; }
        .month-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; }
        .month-cell { color: #a1a1aa; padding: 8px 0; font-size: 0.8rem; cursor: pointer; border-radius: 8px; }
        .month-cell.selected { background: #fff !important; color: #000 !important; font-weight: bold; }

        .widget-footer { display: flex; align-items: center; justify-content: space-between; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.03); border-radius: 18px; padding: 6px 6px 6px 14px; direction: rtl; }
        .reminder-input { background: transparent; border: none; outline: none; color: #fff; font-size: 0.85rem; width: 60%; text-align: right; }
        .reminder-input::placeholder { color: #52525b; }
        .new-event-btn { background: #000; color: #fff; border: none; padding: 10px 20px; border-radius: 14px; font-size: 0.8rem; font-weight: bold; cursor: pointer; border: 1px solid rgba(255, 255, 255, 0.05); }

        /* Left Panel: Tasks */
        .task-panel { background: rgba(30, 31, 33, 0.4); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 24px; padding: 30px 20px; display: flex; flex-direction: column; height: 100%; overflow: hidden; }
        .panel-title-area { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.03); padding-bottom: 12px; }
        .panel-title-area h3 { color: #f4f4f5; font-size: 1rem; font-weight: 500; }
        .selected-badge { background: rgba(59, 130, 246, 0.1); color: #3b82f6; font-size: 0.75rem; padding: 4px 10px; border-radius: 8px; font-weight: bold; }
        
        .category-filter-bar { display: none; gap: 8px; margin-bottom: 15px; overflow-x: auto; padding-bottom: 5px; direction: rtl; }
        .cat-filter-btn { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); color: #a1a1aa; padding: 6px 14px; border-radius: 10px; font-size: 0.75rem; cursor: pointer; transition: 0.2s; }
        .cat-filter-btn.active { background: #3b82f6; color: #fff; border-color: #3b82f6; }

        .task-list { flex: 1; overflow-y: auto; padding-left: 5px; }
        .task-list::-webkit-scrollbar { width: 5px; }
        .task-list::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        
        .task-item { background: #222325; border: 1px solid rgba(255, 255, 255, 0.02); padding: 14px 16px; border-radius: 18px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; transition: 0.25s; cursor: pointer; }
        .task-item:hover { background: #27282a; border-color: rgba(59, 130, 246, 0.3); }
        .task-item.completed-task h4 { text-decoration: line-through; color: #71717a; }

        .task-info { flex: 1; padding-left: 15px; text-align: right; }
        .task-info h4 { font-size: 0.9rem; color: #f4f4f5; font-weight: 500; }
        .task-desc { font-size: 0.8rem; color: #a1a1aa; margin-top: 4px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }
        .task-cat-tag { font-size: 0.65rem; background: rgba(255, 255, 255, 0.04); color: #94a3b8; padding: 2px 8px; border-radius: 6px; margin-top: 6px; display: inline-block; }

        .task-status-actions { display: flex; gap: 8px; align-items: center; direction: ltr; }
        .status-circle-btn { width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(255, 255, 255, 0.05); background: rgba(255, 255, 255, 0.02); color: #71717a; font-size: 0.8rem; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: 0.2s; z-index: 10; }
        
        .status-circle-btn.active-completed { background: #10b981 !important; color: #000 !important; font-weight: bold; box-shadow: 0 0 12px rgba(16, 185, 129, 0.4); border-color: #10b981; }
        .status-circle-btn.active-failed { background: #ef4444 !important; color: #fff !important; font-weight: bold; box-shadow: 0 0 12px rgba(239, 68, 68, 0.4); border-color: #ef4444; }
        
        .btn-tick:hover { background: #10b981; color: #000; box-shadow: 0 0 12px rgba(16, 185, 129, 0.4); }
        .btn-cross:hover { background: #ef4444; color: #fff; box-shadow: 0 0 12px rgba(239, 68, 68, 0.4); }
        
        .btn-trash { background: transparent; border: none; color: #52525b; cursor: pointer; font-size: 0.85rem; opacity: 0; transition: 0.2s; margin-right: 5px; z-index: 10; }
        .task-item:hover .btn-trash { opacity: 1; }
        .btn-trash:hover { color: #f87171; }

        /* Modern Popup Modal */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 9999; display: flex; justify-content: center; align-items: center; opacity: 0; pointer-events: none; transition: 0.3s; }
        .modal-overlay.active { opacity: 1; pointer-events: auto; }
        .modal-box { background: #222325; border: 1px solid rgba(255, 255, 255, 0.08); width: 90%; max-width: 440px; border-radius: 24px; padding: 25px; transform: translateY(-20px); transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); direction: rtl; }
        .modal-overlay.active .modal-box { transform: translateY(0); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding-bottom: 10px; }
        .modal-header h3 { color: #fff; font-size: 1rem; font-weight: 500; }
        .modal-close { background: none; border: none; color: #71717a; font-size: 1.2rem; cursor: pointer; }
        .modal-body { display: flex; flex-direction: column; gap: 15px; }
        .modal-label { color: #a1a1aa; font-size: 0.8rem; margin-bottom: -5px; text-align: right; }
        .modal-input, .modal-textarea, .modal-select { background: #18191b; border: 1px solid rgba(255, 255, 255, 0.05); padding: 12px; border-radius: 12px; color: #fff; font-size: 0.9rem; outline: none; width: 100%; }
        .modal-textarea { min-height: 120px; resize: none; line-height: 1.6; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px; }
        .modal-btn { padding: 10px 20px; border-radius: 12px; font-size: 0.85rem; font-weight: bold; cursor: pointer; border: none; }
        .btn-cancel { background: rgba(255,255,255,0.04); color: #a1a1aa; }
        .btn-submit { background: #3b82f6; color: #fff; }
    </style>
</head>
<body>

    <div class="liquid-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="modal-overlay" id="taskModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 id="modalMainHeading">ایجاد وظیفه جدید</h3>
                <button class="modal-close" onclick="closeModal()">✕</button>
            </div>
            <form id="modalTaskForm">
                <input type="hidden" id="modalTaskId" value="">
                <div class="modal-body">
                    <div class="modal-label">عنوان کار (Title)</div>
                    <input type="text" id="modalTitle" class="modal-input" placeholder="عنوان تسک..." required>
                    
                    <div class="modal-label">توضیحات کامل (Description)</div>
                    <textarea id="modalDescription" class="modal-textarea" placeholder="متن کامل یا توضیحات تکمیلی را اینجا وارد کنید..."></textarea>
                    
                    <div class="modal-label">انتخاب دسته‌بندی (Category)</div>
                    <select id="modalCategory" class="modal-select">
                        <option value="کاری">💼 کاری</option>
                        <option value="شخصی">🏠 شخصی</option>
                        <option value="درسی">📚 درسی</option>
                        <option value="ورزشی">🏃 ورزشی</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="modal-btn btn-cancel" onclick="closeModal()">انصراف</button>
                    <button type="submit" id="modalSubmitBtn" class="modal-btn btn-submit">ثبت نهایی</button>
                </div>
            </form>
        </div>
    </div>

    <div class="app-container">
        <div class="sidebar">
            <div class="sidebar-top">
                <div class="avatar-glow"><?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?></div>
                <h2 style="color:#fff; font-size:1.1rem; font-weight:500;"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></h2>
                <div class="tehran-clock" id="liveClock">--:--:--</div>
            </div>
            <div class="sidebar-menu">
                <div id="menuDaily" class="menu-item active" onclick="changePanelMode('daily')">📅 <span>برنامه روزانه</span></div>
                <div id="menuReport" class="menu-item" onclick="changePanelMode('report')">📊 <span>گزارش عملکرد</span></div>
                <div id="menuCategories" class="menu-item" onclick="changePanelMode('categories')">📁 <span>دسته‌بندی‌ها</span></div>
                <div id="menuArchive" class="menu-item" onclick="changePanelMode('archive')">🗄️ <span>آرشیو کل وظایف</span></div>
            </div>
            <div class="progress-widget">
                <div class="progress-info"><span>پیشرفت روز</span><span id="progressPercent">0%</span></div>
                <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressBarFill"></div></div>
            </div>
            
            <div>
                <a href="auth/change_profile.php" class="profile-link">⚙️ تغییر نام و رمز عبور</a>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin.php" class="admin-link">⚙️ پنل مدیریت کل وظایف</a>
                <?php endif; ?>
                <a href="./auth/logout.php" class="logout-btn">خروج از حساب</a>
            </div>
        </div>

        <div class="main-content">
            <div class="widget-card">
                <div class="widget-header">
                    <div class="toggle-capsule">
                        <button class="toggle-btn active" id="viewWeekly" onclick="switchView('weekly')">Weekly</button>
                        <button class="toggle-btn" id="viewMonthly" onclick="switchView('monthly')">Monthly</button>
                    </div>
                    <span style="color:#71717a;">⚙</span>
                </div>
                <div class="big-date-display">
                    <div class="big-month" id="bigMonthLabel">June</div>
                    <div class="big-day-num" id="bigDayLabel">30</div>
                </div>
                <div class="week-strip-container" id="weeklyStripWrapper">
                    <div class="strip-labels"><div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div></div>
                    <div class="strip-days" id="weeklyDaysContainer"></div>
                </div>
                <div class="monthly-grid-container" id="monthlyGridWrapper">
                    <div class="month-grid-header">
                        <span style="cursor:pointer;" id="prevMonthBtn">&lt; قبل</span>
                        <span id="fullMonthYearLabel" style="font-weight:bold;"></span>
                        <span style="cursor:pointer;" id="nextMonthBtn">بعد &gt;</span>
                    </div>
                    <div class="month-grid" id="monthlyGridDays"></div>
                </div>
                <form id="quickTaskTriggerForm">
                    <div class="widget-footer">
                        <input type="text" id="quickTitleTrigger" class="reminder-input" placeholder="Add a reminder..." required>
                        <button type="submit" class="new-event-btn">New Event</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="task-panel">
            <div class="panel-title-area">
                <h3 id="panelMainTitle">لیست کارهای روز</h3>
                <div class="selected-badge" id="taskTargetBadge">--/--/--</div>
            </div>
            
            <div class="category-filter-bar" id="catFilterBar">
                <button class="cat-filter-btn active" onclick="filterBySelectedCategory('همه')">همه</button>
                <button class="cat-filter-btn" onclick="filterBySelectedCategory('کاری')">💼 کاری</button>
                <button class="cat-filter-btn" onclick="filterBySelectedCategory('شخصی')">🏠 شخصی</button>
                <button class="cat-filter-btn" onclick="filterBySelectedCategory('درسی')">📚 درسی</button>
                <button class="cat-filter-btn" onclick="filterBySelectedCategory('ورزشی')">🏃 ورزشی</button>
            </div>

            <div class="task-list" id="taskList"></div>
        </div>
    </div>

    <script>
        let globalTasksArray = [];
        let activeViewMode = 'weekly';
        let currentPanelMode = 'daily'; 
        let selectedCategoryFilter = 'همه';
        let selectedDate = new Date();
        const monthsLong = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        
        function initClock() {
            const options = { timeZone: 'Asia/Tehran', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            setInterval(() => {
                document.getElementById('liveClock').innerText = 'تهران ' + new Intl.DateTimeFormat('fa-IR', options).format(new Date());
            }, 1000);
        }
        initClock();

        function formatToDatabaseString(dateObj) {
            const y = dateObj.getFullYear();
            const m = String(dateObj.getMonth() + 1).padStart(2, '0');
            const d = String(dateObj.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function updateDateUI() {
            document.getElementById('bigMonthLabel').innerText = monthsLong[selectedDate.getMonth()];
            document.getElementById('bigDayLabel').innerText = selectedDate.getDate();
            document.getElementById('taskTargetBadge').innerText = formatToDatabaseString(selectedDate);
            renderWeeklyStrip();
            renderMonthlyGrid();
            filterAndDisplayTasks();
        }

        function changePanelMode(mode) {
            currentPanelMode = mode;
            document.getElementById('menuDaily').classList.toggle('active', mode === 'daily');
            document.getElementById('menuReport').classList.toggle('active', mode === 'report');
            document.getElementById('menuCategories').classList.toggle('active', mode === 'categories');
            document.getElementById('menuArchive').classList.toggle('active', mode === 'archive');
            document.getElementById('catFilterBar').style.display = mode === 'categories' ? 'flex' : 'none';

            if(mode === 'daily') document.getElementById('panelMainTitle').innerText = 'لیست کارهای روز';
            if(mode === 'report') document.getElementById('panelMainTitle').innerText = '📊 گزارش عملکرد';
            if(mode === 'categories') document.getElementById('panelMainTitle').innerText = '📁 تفکیک دسته‌بندی';
            if(mode === 'archive') document.getElementById('panelMainTitle').innerText = '🗄️ آرشیو کل وظایف';
            
            filterAndDisplayTasks();
        }

        function filterBySelectedCategory(catName) {
            selectedCategoryFilter = catName;
            document.querySelectorAll('.cat-filter-btn').forEach(btn => {
                btn.classList.toggle('active', btn.innerText.includes(catName) || (catName === 'همه' && btn.innerText === 'همه'));
            });
            filterAndDisplayTasks();
        }

        function switchView(mode) {
            activeViewMode = mode;
            document.getElementById('viewWeekly').classList.toggle('active', mode === 'weekly');
            document.getElementById('viewMonthly').classList.toggle('active', mode === 'monthly');
            document.getElementById('weeklyStripWrapper').style.display = mode === 'weekly' ? 'flex' : 'none';
            document.getElementById('monthlyGridWrapper').style.display = mode === 'monthly' ? 'flex' : 'none';
        }

        function renderWeeklyStrip() {
            const container = document.getElementById('weeklyDaysContainer');
            container.innerHTML = '';
            const startOfWeek = new Date(selectedDate);
            startOfWeek.setDate(selectedDate.getDate() - selectedDate.getDay());

            for (let i = 0; i < 7; i++) {
                const walkDate = new Date(startOfWeek);
                walkDate.setDate(startOfWeek.getDate() + i);
                const cell = document.createElement('div');
                cell.className = 'strip-day-cell';
                cell.innerText = walkDate.getDate();
                if(formatToDatabaseString(walkDate) === formatToDatabaseString(selectedDate)) cell.classList.add('active');
                cell.onclick = () => { selectedDate = walkDate; updateDateUI(); };
                container.appendChild(cell);
            }
        }

        function renderMonthlyGrid() {
            const year = selectedDate.getFullYear();
            const month = selectedDate.getMonth();
            document.getElementById('fullMonthYearLabel').innerText = `${monthsLong[month]} ${year}`;
            const grid = document.getElementById('monthlyGridDays');
            grid.innerHTML = '';
            const firstDayIndex = new Date(year, month, 1).getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDayIndex; i++) {
                grid.appendChild(Object.assign(document.createElement('div'), {className: 'month-cell empty'}));
            }
            for (let day = 1; day <= totalDays; day++) {
                const cellDate = new Date(year, month, day);
                const cell = document.createElement('div');
                cell.className = 'month-cell';
                cell.innerText = day;
                if(formatToDatabaseString(cellDate) === formatToDatabaseString(selectedDate)) cell.classList.add('selected');
                cell.onclick = () => { selectedDate = cellDate; updateDateUI(); };
                grid.appendChild(cell);
            }
        }

        document.getElementById('prevMonthBtn').onclick = () => { selectedDate.setMonth(selectedDate.getMonth() - 1); updateDateUI(); };
        document.getElementById('nextMonthBtn').onclick = () => { selectedDate.setMonth(selectedDate.getMonth() + 1); updateDateUI(); };

        function syncBackendTasks() {
            fetch('process.php?action=fetch_tasks')
            .then(res => res.json())
            .then(data => {
                globalTasksArray = Array.isArray(data) ? data : [];
                filterAndDisplayTasks();
            }).catch(() => {});
        }

        function filterAndDisplayTasks() {
            const listContainer = document.getElementById('taskList');
            listContainer.innerHTML = '';
            const targetDateStr = formatToDatabaseString(selectedDate);
            
            const allDateTasks = globalTasksArray.filter(task => (task.due_date || '').substring(0,10) === targetDateStr);
            const total = allDateTasks.length;
            const completedCount = allDateTasks.filter(t => t.status === 'completed').length;
            const percent = total > 0 ? Math.round((completedCount / total) * 100) : 0;
            document.getElementById('progressPercent').innerText = percent + '%';
            document.getElementById('progressBarFill').style.width = percent + '%';

            let filtered = [];
            // قابلیت جدید: در صورت انتخاب آرشیو، تمام تسک‌ها بدون فیلتر تاریخ نمایش داده می‌شوند
            if (currentPanelMode === 'archive') {
                filtered = globalTasksArray;
            } else {
                filtered = allDateTasks.filter(task => {
                    if (currentPanelMode === 'daily') return task.status === 'in_progress';
                    if (currentPanelMode === 'report') return task.status === 'completed' || task.status === 'failed';
                    if (currentPanelMode === 'categories') return selectedCategoryFilter === 'همه' || task.category === selectedCategoryFilter;
                    return true;
                });
            }

            if(filtered.length === 0) {
                listContainer.innerHTML = `<p style="text-align:center;color:#52525b;font-size:0.8rem;padding-top:20px;">هیچ تسکی یافت نشد.</p>`;
                return;
            }

            filtered.forEach(task => {
                const item = document.createElement('div');
                item.className = `task-item ${task.status === 'completed' ? 'completed-task' : ''}`;
                let previewDesc = task.description ? task.description : 'بدون توضیحات';

                item.onclick = (e) => {
                    if(e.target.tagName === 'BUTTON' || e.target.classList.contains('status-circle-btn')) return;
                    openModalForEdit(task);
                };

                let tickClass = task.status === 'completed' ? 'active-completed' : '';
                let crossClass = task.status === 'failed' ? 'active-failed' : '';
                
                // نمایش تاریخ فقط در حالت آرشیو کل وظایف
                let dateInfo = currentPanelMode === 'archive' ? `<span style="font-size:0.75rem; color:#64748b; margin-right:8px; direction:ltr; display:inline-block;">(${task.due_date ? task.due_date.substring(0,10) : ''})</span>` : '';

                item.innerHTML = `
                    <div class="task-info">
                        <h4>${task.title} ${dateInfo}</h4>
                        <span class="task-desc">${previewDesc}</span>
                        <span class="task-cat-tag"># ${task.category}</span>
                    </div>
                    <div class="task-status-actions">
                        <button class="btn-trash" onclick="removeTask(${task.id})">🗑</button>
                        <button class="status-circle-btn btn-cross ${crossClass}" onclick="changeStatus(${task.id}, 'failed')">✕</button>
                        <button class="status-circle-btn btn-tick ${tickClass}" onclick="changeStatus(${task.id}, 'completed')">✓</button>
                    </div>
                `;
                listContainer.appendChild(item);
            });
        }

        function openModalForCreate(initialTitle) {
            document.getElementById('modalTaskId').value = '';
            document.getElementById('modalMainHeading').innerText = 'ایجاد وظیفه جدید';
            document.getElementById('modalSubmitBtn').innerText = 'ثبت نهایی';
            document.getElementById('modalTitle').value = initialTitle;
            document.getElementById('modalDescription').value = ''; 
            document.getElementById('taskModal').classList.add('active');
        }

        function openModalForEdit(task) {
            document.getElementById('modalTaskId').value = task.id;
            document.getElementById('modalMainHeading').innerText = '🔍 مشاهده و ویرایش وظیفه';
            document.getElementById('modalSubmitBtn').innerText = 'ذخیره تغییرات';
            document.getElementById('modalTitle').value = task.title;
            document.getElementById('modalDescription').value = task.description || '';
            document.getElementById('modalCategory').value = task.category || 'کاری';
            document.getElementById('taskModal').classList.add('active');
        }

        function closeModal() { document.getElementById('taskModal').classList.remove('active'); }

        document.getElementById('quickTaskTriggerForm').onsubmit = function(e) {
            e.preventDefault();
            const input = document.getElementById('quickTitleTrigger');
            openModalForCreate(input.value);
            input.value = '';
        };

        document.getElementById('modalTaskForm').onsubmit = function(e) {
            e.preventDefault();
            const taskId = document.getElementById('modalTaskId').value;
            const fd = new FormData();
            
            if(taskId) {
                fd.append('action', 'update_task');
                fd.append('id', taskId);
            } else {
                fd.append('action', 'create_task');
                fd.append('due_date', formatToDatabaseString(selectedDate));
            }
            
            fd.append('title', document.getElementById('modalTitle').value);
            fd.append('description', document.getElementById('modalDescription').value);
            fd.append('category', document.getElementById('modalCategory').value);

            fetch('process.php', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success' || data.status === undefined) {
                    closeModal();
                    syncBackendTasks();
                } else { alert('خطا: ' + data.message); }
            }).catch(() => syncBackendTasks());
        };

        function changeStatus(id, status) {
            const fd = new FormData();
            fd.append('action', 'update_status');
            fd.append('id', id);
            fd.append('status', status);
            fetch('process.php', { method: 'POST', body: fd }).then(() => syncBackendTasks());
        }

        function removeTask(id) {
            if(confirm('آیا از حذف این وظیفه اطمینان دارید؟')) {
                const fd = new FormData();
                fd.append('action', 'delete_task');
                fd.append('id', id);
                fetch('process.php', { method: 'POST', body: fd }).then(() => syncBackendTasks());
            }
        }

        updateDateUI();
        syncBackendTasks();
    </script>
</body>
</html>
<?php 
// اتصال فوتر از پوشه شاملات (includes) طبق ساختار جدید پروژه
require_once __DIR__ . '/includes/footer.php'; 
?>