<?php
require("../config.php");
require("../include/auth_admin.php");

// Tổng số người dùng
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Tổng số lịch đặt
$totalBooking = $conn->query("SELECT COUNT(*) AS total FROM booking")->fetch_assoc()['total'];

// Lịch chờ duyệt
$pendingBooking = $conn->query("SELECT COUNT(*) AS total FROM booking WHERE STATUS='pending'")->fetch_assoc()['total'];

// Tổng số sân
$checkSan = $conn->query("SHOW TABLES LIKE 'san'");
if ($checkSan->num_rows > 0) {
    $totalSan = $conn->query("SELECT COUNT(*) AS total FROM san")->fetch_assoc()['total'];
} else {
    $totalSan = 0;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card-box {
    border-radius: 15px;
    padding: 25px;
    color: white;
    text-align: center;
}
.bg1 { background: linear-gradient(135deg, #4e73df, #224abe); }
.bg2 { background: linear-gradient(135deg, #1cc88a, #13855c); }
.bg3 { background: linear-gradient(135deg, #f6c23e, #dda20a); }
.bg4 { background: linear-gradient(135deg, #e74a3b, #be2617); }
.number {
    font-size: 40px;
    font-weight: bold;
}
</style>
</head>

<body>
<div class="container mt-5">

<h2 class="mb-4">📊 Dashboard Quản Trị</h2>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card-box bg1">
            <div class="number"><?= $totalUsers ?></div>
            <div>Người dùng</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box bg2">
            <div class="number"><?= $totalSan ?></div>
            <div>Sân Pickleball</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box bg3">
            <div class="number"><?= $totalBooking ?></div>
            <div>Lịch đặt</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box bg4">
            <div class="number"><?= $pendingBooking ?></div>
            <div>Chờ duyệt</div>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="d-flex gap-3">
    <a href="users.php" class="btn btn-primary">👤 Quản lý người dùng</a>
    <a href="../index.php" class="btn btn-secondary">🏠 Về trang chủ</a>
</div>

</div>
</body>
</html>
