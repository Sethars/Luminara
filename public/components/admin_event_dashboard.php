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
                        <textarea class="form-control" id="event-message" rows="3" placeholder="Tambahkan pesan atau keterangan untuk event ini..."></textarea>
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
                                <th>Dibuat Oleh</th>
                                <th>Tanggal Berakhir</th>
                                <th>Khusus VIP</th>
                                <th>Status</th>
                                <th>Aksi</th>
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
    // Data dummy untuk users
    const users = [
        { id: 1, name: "Admin Utama" },
        { id: 2, name: "Admin Event" },
        { id: 3, name: "Super Admin" }
    ];

    // Data dummy untuk events
    let events = [
        {
            id: 1,
            nama: "Turnamen Poker Mingguan",
            pesan: "Turnamen poker mingguan dengan hadiah menarik. Daftar sekarang juga!",
            chips: 5000,
            created_at: "2023-05-15T10:30:00",
            end_at: "2023-05-22T23:59:59",
            created_by: 1,
            vip: false
        },
        {
            id: 2,
            nama: "Lucky Draw Bulanan",
            pesan: "Undian berhadiah chip setiap bulan. Semakin sering bermain, semakin besar kesempatan menang!",
            chips: 10000,
            created_at: "2023-05-10T14:20:00",
            end_at: "2023-06-10T23:59:59",
            created_by: 2,
            vip: true
        }
    ];

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

    function getUserName(userId) {
        const user = users.find(u => u.id === userId);
        return user ? user.name : "Unknown";
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

    function renderTable() {
        const tableBody = document.getElementById('events-table-body');
        tableBody.innerHTML = '';
        
        events.forEach(event => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${event.id}</td>
                <td>${event.nama}</td>
                <td>${event.pesan ? event.pesan.substring(0, 50) + (event.pesan.length > 50 ? '...' : '') : '-'}</td>
                <td>${event.chips.toLocaleString('id-ID')}</td>
                <td>${formatDate(event.created_at)}</td>
                <td>${getUserName(event.created_by)}</td>
                <td>${formatDate(event.end_at)}</td>
                <td>${event.vip ? '<span class="badge bg-info">VIP</span>' : '-'}</td>
                <td>${getEventStatus(event.end_at)}</td>
                <td>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${event.id}">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', handleDelete);
        });
    }

    // Form submit
    document.getElementById('event-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('event-id').value;
        const nama = document.getElementById('event-name').value;
        const pesan = document.getElementById('event-message').value;
        const chips = parseInt(document.getElementById('event-chips').value);
        const end_at = document.getElementById('event-end').value;
        const vip = document.getElementById('event-vip').checked;
        
        const currentUserId = 1;
        
        if (id) {
            const index = events.findIndex(event => event.id == id);
            if (index !== -1) {
                events[index] = { ...events[index], nama, pesan, chips, end_at, vip };
            }
        } else {
            const newId = events.length > 0 ? Math.max(...events.map(e => e.id)) + 1 : 1;
            events.push({
                id: newId,
                nama,
                pesan,
                chips,
                created_at: new Date().toISOString(),
                end_at,
                created_by: currentUserId,
                vip
            });
        }
        
        this.reset();
        document.getElementById('event-id').value = '';
        renderTable();
        showNotification(id ? 'Event berhasil diperbarui!' : 'Event berhasil ditambahkan!');
    });

    function handleDelete(e) {
        const id = e.currentTarget.getAttribute('data-id');
        if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
            events = events.filter(event => event.id != id);
            renderTable();
            showNotification('Event berhasil dihapus!');
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
