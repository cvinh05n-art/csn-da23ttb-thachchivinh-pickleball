<link rel="stylesheet" href="../bootstrap cdn/KT2/css/bootstrap.min.css">
<script src="../bootstrap cdn/KT2/js/bootstrap.bundle.min.js"></script>

<style>
    .admin-sidebar {
        width: 220px;
        height: 100vh;
        background: #198754;
        padding: 20px 10px;
        position: fixed;
        top: 0;
        left: 0;
        color: white;
    }
    .admin-sidebar a {
        display: block;
        padding: 10px;
        color: white;
        text-decoration: none;
        margin: 8px 0;
        border-radius: 6px;
    }
    .admin-sidebar a:hover {
        background: rgba(255,255,255,0.2);
    }
    .admin-content {
        margin-left: 240px;
        padding: 20px;
    }
</style>

<div class="admin-sidebar">
    <h3 class="text-center mb-3">ADMIN</h3>
    <a href="dashboar.php">📊 Dashboard</a>
    <a href="booking.php">📅 Quản lý đặt sân</a>
    <a href="users.php">👤 Quản lý người dùng</a>
    <a href="events.php">🎉 Sự kiện</a>
    <a href="courts.php">🏟 Quản lý sân</a>
    <a href="../index.php" class="text-warning">⬅ Quay lại trang chủ</a>
</div>
