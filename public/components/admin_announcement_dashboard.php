<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --secondary: #6366f1;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #1f2937;
        --light: #f3f4f6;
        --white: #ffffff;
        --sidebar-width: 260px;
        --header-height: 70px;
    }

    body {
        background-color: var(--light);
        color: var(--dark);
    }

    /* Main Content */
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Header */
    .header {
        background-color: var(--white);
        height: var(--header-height);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    /* Dashboard Content */
    .content {
        background-color: #e3e3e3;
        padding: 30px;
        flex: 1;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--dark);
    }

    /* Data Table */
    .data-table-container {
        background-color: var(--white);
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .table-title {
        font-size: 20px;
        font-weight: 600;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 14px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .data-table th {
        font-weight: 600;
        color: #666;
    }

    .data-table tr:hover {
        background-color: #f9fafb;
    }

    .table-actions {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 5px;
        border: none;
        background-color: #f3f4f6;
        color: #666;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background-color: #e5e7eb;
    }

    .action-btn.edit:hover {
        background-color: rgba(79, 70, 229, 0.1);
        color: var(--primary);
    }

    .action-btn.delete:hover {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    /* Announcement Card Style */
    .announcement-card {
        background-color: var(--white);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--primary);
    }

    .announcement-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .announcement-content {
        margin-bottom: 10px;
    }

    .announcement-time {
        font-size: 14px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        overflow-y: auto;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: var(--white);
        border-radius: 10px;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        animation: modalFadeIn 0.3s ease;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 20px;
        font-weight: 600;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .empty-state i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 15px;
    }

    .empty-state h3 {
        margin-bottom: 10px;
        color: #444;
    }

    /* === Announcement Item === */
    .announcement-item {
        background: var(--white);
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-left: 4px solid var(--primary);
    }

    .announcement-text {
        flex: 1;
        font-size: 15px;
        color: var(--dark);
        line-height: 1.5;
    }

    .announcement-time {
        margin-top: 8px;
        font-size: 13px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .announcement-actions {
        display: flex;
        gap: 8px;
        margin-left: 20px;
    }

    .announcement-actions .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 5px;
        border: none;
        background: #f3f4f6;
        color: #555;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .announcement-actions .action-btn:hover {
        transform: scale(1.1);
    }

    .announcement-actions .action-btn.edit:hover {
        background: rgba(79,70,229,0.1);
        color: var(--primary);
    }

    .announcement-actions .action-btn.delete:hover {
        background: rgba(239,68,68,0.1);
        color: var(--danger);
    }


    /* === Announcement Card Enhancement === */
    .announcement-card {
        position: relative;
        border-left: 5px solid var(--primary);
        padding-left: 50px; /* ruang buat icon */
    }

    .announcement-card::before {
        content: "\f0a1"; /* fa-bullhorn */
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        left: 15px;
        top: 20px;
        font-size: 22px;
        color: var(--primary);
        opacity: 0.9;
    }

    /* Aksi Edit / Hapus di pojok kanan */
    .table-actions .action-btn {
        transition: transform 0.2s ease;
    }

    .table-actions .action-btn:hover {
        transform: scale(1.1);
    }

    /* Modal smooth overlay */
    .modal.active {
        animation: fadeInBg 0.3s ease;
    }

    @keyframes fadeInBg {
        from {background-color: rgba(0,0,0,0);}
        to   {background-color: rgba(0,0,0,0.5);}
    }

    /* Empty State lebih stand out */
    .empty-state {
        border: 2px dashed #ddd;
        border-radius: 10px;
        background: #fafafa;
    }


    /* Responsive Design */
    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .data-table {
            font-size: 14px;
        }

        .data-table th,
        .data-table td {
            padding: 10px;
        }

        .table-actions {
            flex-direction: column;
        }

        .modal-content {
            margin: 20px;
        }
    }
</style>
<body>
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1 class="page-title">Pengaturan Announcement</h1>
            <button class="btn btn-primary" id="addAnnouncementBtn">
                <i class="fas fa-plus"></i> Tambah Announcement
            </button>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="data-table-container">
                <div class="table-header">
                    <h2 class="table-title">Daftar Announcement</h2>
                </div>
                
                <div id="announcementList">
                    <!-- Announcement items will be inserted here by JavaScript -->
                </div>
                
                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="fas fa-bullhorn"></i>
                    <h3>Belum ada announcement</h3>
                    <p>Klik tombol "Tambah Announcement" untuk membuat pengumuman baru</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal" id="announcementModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Announcement</h3>
                <button class="close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="announcementForm">
                    <input type="hidden" id="announcementId">
                    <div class="form-group">
                        <label for="pesan" class="form-label">Pesan Announcement</label>
                        <textarea class="form-control" id="pesan" placeholder="Masukkan pesan announcement..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelBtn">Batal</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <button class="close" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus announcement ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>


</body>