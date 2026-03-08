<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Manager - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --bg: #f3f4f6;
            --white: #ffffff;
            --text: #111827;
            --text-secondary: #4b5563;
            --border: #e5e7eb;
            --success: #10b981;
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        nav {
            background-color: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-logout {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }

        .btn-logout:hover {
            color: var(--text);
        }

        main {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .content-card {
            background: var(--white);
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .filter-group {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .input-control {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            outline: none;
        }

        .input-control:focus {
            border-color: var(--primary);
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: opacity 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }

        .btn-success {
            background-color: var(--success);
            color: var(--white);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: #f9fafb;
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--text-secondary);
        }

        td {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            font-size: 0.875rem;
        }

        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .action-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            width: 100%;
            max-width: 400px;
        }

        .modal-header {
            margin-bottom: 1.5rem;
        }

        .modal-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <nav>
        <h1>Database Backup Manager</h1>
        <div class="nav-actions">
            <span>Admin Control Panel</span>
            <a href="<?= base_url('/logout') ?>" class="btn-logout">Sign Out</a>
        </div>
    </nav>

    <main>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Backups</h3>
                <p>
                    <?= count($backups) ?>
                </p>
            </div>
            <div class="stat-card">
                <h3>Retention</h3>
                <p>30 Days</p>
            </div>
            <div class="stat-card">
                <h3>Last Backup</h3>
                <p>
                    <?= !empty($backups) ? date('d M Y', strtotime($backups[0]['created_at'])) : '-' ?>
                </p>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header">
                <form action="<?= base_url('/backup') ?>" method="GET" class="filter-group">
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Cari Database..."
                        class="input-control">
                    <input type="date" name="date" value="<?= $date ?>" class="input-control">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?= base_url('/backup') ?>" class="btn"
                        style="background: #e5e7eb; color: #374151;">Reset</a>
                </form>
                <button onclick="openModal()" class="btn btn-success">Backup Sekarang</button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Database</th>
                            <th>Tanggal Backup</th>
                            <th>Ukuran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($backups)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                                    Belum ada data backup yang tercatat.
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($backups as $row): ?>
                            <tr>
                                <td><strong>
                                        <?= $row['database_name'] ?>
                                    </strong></td>
                                <td>
                                    <?= date('d M Y H:i', strtotime($row['backup_date'])) ?>
                                </td>
                                <td>
                                    <?= $row['file_size'] ?>
                                </td>
                                <td><span class="badge badge-success">Selesai</span></td>
                                <td>
                                    <a href="<?= base_url('/backup/download/' . $row['id']) ?>"
                                        class="action-link">Download</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="backupModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Konfirmasi Backup</h2>
                <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">
                    Silahkan masukkan password konfirmasi untuk menjalankan backup manual.
                </p>
            </div>
            <form action="<?= base_url('/backup/manual') ?>" method="POST">
                <?= csrf_field() ?>
                <div style="margin-bottom: 1.5rem;">
                    <label
                        style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem; font-weight: 500;">Password
                        Konfirmasi</label>
                    <input type="password" name="password" class="input-control" style="width: 100%;" required>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" class="btn"
                        style="background: #e5e7eb; color: #374151;">Batal</button>
                    <button type="submit" class="btn btn-primary">Jalankan Backup</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('backupModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('backupModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            let modal = document.getElementById('backupModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>