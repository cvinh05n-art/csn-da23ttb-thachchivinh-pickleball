<?php
require "../config.php";
require "../include/auth_admin.php";

function adminCount(mysqli $conn, string $sql): int
{
    $result = $conn->query($sql);
    return $result ? (int) $result->fetch_assoc()['total'] : 0;
}

$totalUsers = adminCount($conn, "SELECT COUNT(*) AS total FROM users");
$totalBooking = adminCount($conn, "SELECT COUNT(*) AS total FROM booking WHERE STATUS <> 'cancelled'");
$pendingBooking = adminCount($conn, "SELECT COUNT(*) AS total FROM booking WHERE STATUS = 'pending'");
$confirmedBooking = adminCount($conn, "SELECT COUNT(*) AS total FROM booking WHERE STATUS = 'confirmed'");
$recentBookings = $conn->query("SELECT id, name, ngaydat, giodat, STATUS FROM booking ORDER BY ngaydat DESC, giodat DESC LIMIT 6");
$statusNames = ['pending' => 'Chờ duyệt', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard quản trị | Pickleball Trung Ngọc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --green: #168454; --ink: #193129; --muted: #65766f; }
        body { background: #f4f7f5; color: var(--ink); }
        .admin-topbar { background: #fff; border-bottom: 1px solid #e4ebe7; }
        .admin-brand { color: var(--ink); font-weight: 750; text-decoration: none; }
        .dashboard-wrap { max-width: 1180px; }
        .metric { display: block; height: 100%; padding: 23px; color: inherit; text-decoration: none; background: #fff; border: 1px solid #e4ebe7; border-radius: 16px; box-shadow: 0 5px 18px rgba(25,49,41,.045); transition: transform .18s, box-shadow .18s; }
        .metric:hover { color: inherit; transform: translateY(-3px); box-shadow: 0 11px 26px rgba(25,49,41,.09); }
        .metric-label { color: var(--muted); font-weight: 600; }
        .metric-value { margin-top: 10px; font-size: 2.35rem; line-height: 1; font-weight: 750; }
        .metric-icon { float: right; display: grid; width: 43px; height: 43px; place-items: center; border-radius: 13px; background: #eaf6ef; font-size: 21px; }
        .panel { overflow: hidden; background: #fff; border: 1px solid #e4ebe7; border-radius: 16px; box-shadow: 0 5px 18px rgba(25,49,41,.045); }
        .panel-head { padding: 19px 22px; border-bottom: 1px solid #e8eeea; }
        .panel-head h2 { margin: 0; font-size: 1.1rem; font-weight: 700; }
        .table { margin: 0; }
        .table th, .table td { padding: 13px 18px; vertical-align: middle; }
        @media (max-width: 575.98px) { .metric { padding: 18px; } .table th, .table td { padding: 11px; } }
    </style>
</head>
<body>
<nav class="navbar admin-topbar">
    <div class="container dashboard-wrap">
        <a class="admin-brand navbar-brand" href="dashboar.php">🏓 Pickleball <span class="text-success">/ Quản trị</span></a>
        <div class="d-flex align-items-center gap-3">
            <a class="nav-link d-none d-sm-inline" href="users.php">Người dùng</a>
            <a class="nav-link" href="../index.php">Về trang chủ</a>
        </div>
    </div>
</nav>
<main class="container dashboard-wrap py-4 py-lg-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
        <div><p class="text-success fw-semibold mb-1">TỔNG QUAN</p><h1 class="h2 fw-bold mb-0">Dashboard quản trị</h1></div>
        <a href="booking.php" class="btn btn-success">Quản lý lịch đặt</a>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><a class="metric" href="users.php"><span class="metric-icon">👥</span><span class="metric-label">Người dùng</span><div class="metric-value"><?= $totalUsers ?></div></a></div>
        <div class="col-6 col-lg-3"><a class="metric" href="../about.php#bando"><span class="metric-icon">🏟️</span><span class="metric-label">Cơ sở đang quản lý</span><div class="metric-value">1</div></a></div>
        <div class="col-6 col-lg-3"><a class="metric" href="booking.php"><span class="metric-icon">📅</span><span class="metric-label">Lịch chưa hủy</span><div class="metric-value"><?= $totalBooking ?></div></a></div>
        <div class="col-6 col-lg-3"><a class="metric" href="booking.php?status=pending"><span class="metric-icon">⏳</span><span class="metric-label">Chờ duyệt</span><div class="metric-value"><?= $pendingBooking ?></div></a></div>
    </div>
    <div class="panel">
        <div class="panel-head d-flex justify-content-between align-items-center gap-3">
            <div><h2>Lịch đặt gần đây</h2><small class="text-secondary">Đã xác nhận: <?= $confirmedBooking ?> lịch</small></div>
            <a href="booking.php" class="btn btn-outline-success btn-sm">Xem tất cả</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Mã</th><th>Khách đặt</th><th>Ngày</th><th>Giờ</th><th>Trạng thái</th></tr></thead>
                <tbody>
                <?php if ($recentBookings && $recentBookings->num_rows > 0) { while ($booking = $recentBookings->fetch_assoc()) {
                    $status = $booking['STATUS'] ?? 'pending';
                    $badge = $status === 'confirmed' ? 'success' : ($status === 'cancelled' ? 'secondary' : 'warning text-dark');
                ?>
                    <tr><td>#<?= (int) $booking['id'] ?></td><td><?= htmlspecialchars($booking['name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= date('d/m/Y', strtotime($booking['ngaydat'])) ?></td><td><?= htmlspecialchars(substr($booking['giodat'], 0, 5), ENT_QUOTES, 'UTF-8') ?></td><td><span class="badge text-bg-<?= $badge ?>"><?= htmlspecialchars($statusNames[$status] ?? $status, ENT_QUOTES, 'UTF-8') ?></span></td></tr>
                <?php } } else { ?>
                    <tr><td colspan="5" class="text-center text-secondary py-4">Chưa có lịch đặt sân.</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>
