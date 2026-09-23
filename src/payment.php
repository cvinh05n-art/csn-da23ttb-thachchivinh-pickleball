<?php
require "config.php";
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$bookingId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$sessionBookingId = (int) ($_SESSION['booking_id'] ?? 0);
if (!$bookingId || !$sessionBookingId || $bookingId !== $sessionBookingId) {
    header('Location: book.php');
    exit;
}

$stmt = $conn->prepare('SELECT id, name, DT, ngaydat, giodat, STATUS, total_amount, deposit_amount, deposit_status FROM booking WHERE id = ?');
$stmt->bind_param('i', $bookingId);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
if (!$booking) {
    http_response_code(404);
    exit('Không tìm thấy yêu cầu đặt sân.');
}

$isConfirmed = $booking['STATUS'] === 'confirmed';
$statusLabel = $isConfirmed ? 'Đặt sân thành công' : 'Đang chờ sân xác nhận';
$statusClass = $isConfirmed ? 'success' : 'warning';
$slotLabel = date('H:i', strtotime($booking['giodat'])) . ' - ' . date('H:i', strtotime($booking['giodat'] . ' +1 hour'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="20">
    <title>Thanh toán cọc | Pickleball Trung Ngọc</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        :root { --ink: #193129; --green: #168454; }
        body { min-height: 100vh; color: var(--ink); background: linear-gradient(135deg, #eaf7ef, #f8fbf9); }
        .page { max-width: 900px; }
        .panel { background: #fff; border: 1px solid #dfeae3; border-radius: 20px; box-shadow: 0 18px 45px rgba(25,49,41,.09); }
        .qr { width: min(100%, 330px); border-radius: 14px; }
        .status { border-left: 5px solid; }
        .status-warning { border-color: #e0a800; background: #fff9e8; }
        .status-success { border-color: var(--green); background: #eaf8ef; }
        .btn-success { background: var(--green); border-color: var(--green); }
    </style>
</head>
<body>
<main class="container page py-4 py-md-5">
    <a class="text-success fw-semibold text-decoration-none" href="index.php">🏓 Pickleball Trung Ngọc</a>
    <div class="panel p-4 p-md-5 mt-3">
        <div class="status status-<?= $statusClass ?> rounded-3 p-3 mb-4">
            <h1 class="h3 mb-1"><?= $isConfirmed ? '✅ ' : '⏳ ' ?><?= $statusLabel ?></h1>
            <p class="mb-0"><?= $isConfirmed ? 'Sân đã xác nhận lịch của bạn.' : 'Vui lòng chuyển khoản tiền cọc và chờ sân xác nhận lịch.' ?></p>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-md-6">
                <h2 class="h5 mb-3">Thông tin lịch đặt #<?= (int) $booking['id'] ?></h2>
                <dl class="row mb-4">
                    <dt class="col-5 text-secondary">Khách đặt</dt><dd class="col-7"><?= htmlspecialchars($booking['name'], ENT_QUOTES, 'UTF-8') ?></dd>
                    <dt class="col-5 text-secondary">Điện thoại</dt><dd class="col-7"><?= htmlspecialchars($booking['DT'], ENT_QUOTES, 'UTF-8') ?></dd>
                    <dt class="col-5 text-secondary">Ngày</dt><dd class="col-7"><?= date('d/m/Y', strtotime($booking['ngaydat'])) ?></dd>
                    <dt class="col-5 text-secondary">Khung giờ</dt><dd class="col-7"><?= htmlspecialchars($slotLabel, ENT_QUOTES, 'UTF-8') ?></dd>
                </dl>
                <div class="border rounded-3 p-3 bg-light">
                    <div class="d-flex justify-content-between"><span>Tổng tiền sân</span><strong><?= number_format((int) $booking['total_amount'], 0, ',', '.') ?>đ</strong></div>
                    <div class="d-flex justify-content-between mt-2 text-success"><span>Tiền cọc 30%</span><strong><?= number_format((int) $booking['deposit_amount'], 0, ',', '.') ?>đ</strong></div>
                    <div class="d-flex justify-content-between mt-2"><span>Trạng thái cọc</span><strong><?= htmlspecialchars($booking['deposit_status'], ENT_QUOTES, 'UTF-8') ?></strong></div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h2 class="h5 mb-3">Quét mã để chuyển khoản cọc</h2>
                <img class="qr img-fluid" src="images/vietcombank-qr.jpg" alt="Mã QR thanh toán Vietcombank">
                <p class="small text-secondary mt-3 mb-1">Nội dung chuyển khoản: <strong>DAT SAN #<?= (int) $booking['id'] ?></strong></p>
                <p class="small text-secondary mb-0">Vietcombank · THACH CHI VINH · 9383415367</p>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
            <a class="btn btn-success" href="book.php">Đặt lịch khác</a>
            <a class="btn btn-outline-secondary" href="index.php">Về trang chủ</a>
        </div>
    </div>
</main>
</body>
</html>
