<style>
    .page-header {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    }

    .page-header h2 {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0;
    }

    .form-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    max-width: 600px;
    }

    .form-group {
    margin-bottom: 18px;
    }

    .form-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 6px;
    color: #444;
    }

    .form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    }

    .form-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    }

</style>
<body>
  <div class="admin-container">
    <!-- Main Content -->
    <main class="main-content">
      <!-- Page Content -->
      <div class="content">
        <div class="page-header">
          <h2><i class="fas fa-dice"></i> Buat Lottery Event</h2>
        </div>

        <!-- Form Buat Event -->
        <div class="card form-card">
          <form id="lottery-form">
            <div class="form-group">
              <label for="eventName">Nama Event</label>
              <input
                type="text"
                id="eventName"
                name="eventName"
                class="form-control"
                placeholder="Contoh: Luminara Lottery"
                required
              />
            </div>

            <div class="form-group">
              <label for="ticketPrice">Harga Tiket</label>
              <input
                type="number"
                id="ticketPrice"
                name="ticketPrice"
                class="form-control"
                min="500"
                placeholder="-"
                disabled
                required
              />
            </div>

            <div class="form-group">
              <label for="prizes">Hadiah Awal</label>
              <input
                type="number"
                id="prizes"
                name="prizes"
                class="form-control"
                min="5000"
                placeholder="Minimal: 5000"
                required
              />
            </div>

            <div class="form-group">
              <label for="startDate">Tanggal Mulai</label>
              <input
                type="datetime-local"
                id="startDate"
                name="startDate"
                class="form-control"
                required
              />
            </div>

            <div class="form-group">
              <label for="endDate">Tanggal Berakhir</label>
              <input
                type="datetime-local"
                id="endDate"
                name="endDate"
                class="form-control"
                required
              />
            </div>

            <div class="form-actions">
              <button id="lottery-submit" type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Event
              </button>
              <button type="reset" class="btn btn-secondary">
                <i class="fas fa-times"></i> Reset
              </button>
            </div>
          </form>
        </div>

        <!-- Daftar Event -->
        <div class="data-table-container" style="margin-top: 30px;">
        <div class="table-header">
            <div class="table-title">Daftar Lottery Event</div>
        </div>
        <table class="data-table">
            <thead>
            <tr>
                <th>Nama Event</th>
                <th>Harga Tiket</th>
                <th>Total Hadiah</th>
                <th>Mulai</th>
                <th>Berakhir</th>
                <th>Status</th>
                <th>Total Tiket Terjual</th>
            </tr>
            </thead>
            <tbody id="recentLotteryTableBody"></tbody>
        </table>
        </div>

      </div>
    </main>
  </div>
</body>

