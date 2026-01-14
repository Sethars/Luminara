    <style>
        /* Custom styles untuk event management */
        .content-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #e3e3e3;
            border-radius: 10px;
        }
        
        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--dark);
        }
        
        .card {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            border: none;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 10px 10px 0 0 !important;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #666;
        }
        
        .badge {
            font-size: 0.75em;
        }

        .table td:nth-child(3) {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .table td, .table th {
            white-space: nowrap;
            width: auto;
        }

        .table td.pesan-col, .table th.pesan-col {
            max-width: 300px;
            width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }       

        
        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .content-section {
                max-width: calc(100% - 320px) !important;
                margin-left: 280px !important;
                margin-right: 20px !important;
            }
        }
        
        @media (max-width: 992px) {
            .content-section {
                max-width: calc(100% - 40px) !important;
                margin-left: 20px !important;
                margin-right: 20px !important;
            }
        }
        
        @media (max-width: 768px) {
            .content-section {
                max-width: calc(100% - 20px) !important;
                margin-left: 10px !important;
                margin-right: 10px !important;
                padding: 15px;
            }
            
            .card {
                max-width: 100% !important;
            }
        }
    </style>
<body>
    <!-- Event Management Section -->
    <div class="content-section" style="margin-left: 280px; margin-right: 20px; max-width: calc(100% - 300px);">
        <h2 class="section-title">Kelola Event</h2>
        
        <!-- Event Form -->
        <div class="card mb-4" style="max-width: 800px;">
            <div class="card-header bg-primary text-white">
                <h5 id="form-title" class="mb-0">Tambah Event Baru</h5>
            </div>
            <div class="card-body">
                <form id="event-form">
                    <input type="hidden" id="event-id">
                    
                    <div class="mb-3">
                        <label for="event-name" class="form-label">Nama Event</label>
                        <input type="text" class="form-control" id="event-name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="event-message" class="form-label">Pesan/Keterangan</label>
                        <textarea maxlength="50" class="form-control" id="event-message" rows="3" placeholder="Tambahkan pesan atau keterangan untuk event ini..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="event-chips" class="form-label">Chips</label>
                        <input type="number" class="form-control" id="event-chips" min="0" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="event-end" class="form-label">Tanggal Berakhir</label>
                        <input type="datetime-local" class="form-control" id="event-end" required>
                    </div>

                    <!-- Checkbox khusus VIP -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="event-vip">
                        <label class="form-check-label" for="event-vip">Khusus VIP</label>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submit-btn">Simpan</button>
                        <button type="button" class="btn btn-secondary d-none" id="cancel-btn">Batal</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Events Table -->
        <div class="card" style="max-width: 1200px;">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Daftar Event</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama Event</th>
                                <th class="pesan-col">Pesan</th> <!-- Fixed width -->
                                <th>Chips</th>
                                <th>Tanggal Dibuat</th>
                                <th>Tanggal Berakhir</th>
                                <th>Khusus VIP</th>
                                <th>Status</th>
                                <th>Aksi</th> <!-- cukup hapus tanpa edit -->
                            </tr>
                        </thead>
                        <tbody id="events-table-body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
   
    // Format tanggal GMT+7
    function formatDate(dateString) {
        const options = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit',
            timeZone: 'Asia/Jakarta'
        };
        return new Date(dateString).toLocaleString('id-ID', options);
    }

    // Format tanggal untuk input datetime-local
    function formatDateTimeLocal(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }


    function getEventStatus(endDate) {
        const now = new Date();
        const end = new Date(endDate);
        
        if (end < now) {
            return '<span class="badge bg-danger">Berakhir</span>';
        } else {
            const diffTime = end - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays <= 3) {
                return `<span class="badge bg-warning">Berakhir dalam ${diffDays} hari</span>`;
            } else {
                return '<span class="badge bg-success">Aktif</span>';
            }
        }
    }




    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '300px';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => { notification.remove(); }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderTable();
    });
</script>
</body>
