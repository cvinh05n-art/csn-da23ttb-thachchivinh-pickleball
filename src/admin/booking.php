<?php
require "../config.php";
require "../include/auth_admin.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['admin_csrf'], $token)) {
        $_SESSION['admin_flash'] = ['type' => 'danger', 'text' => 'Phiên thao tác hết hạn. Vui lòng thử lại.'];
    } else {
        $id = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
        $action = $_POST['action'] ?? '';
        if ($id && $action === 'status' && in_array($_POST['status'] ?? '', ['pending', 'confirmed', 'cancelled'], true)) {
            $status = $_POST['status'];
            $stmt = $conn->prepare('UPDATE booking SET STATUS = ?, deposit_status = CASE WHEN ? = \'confirmed\' THEN \'submitted\' ELSE deposit_status END WHERE id = ?');
            $stmt->bind_param('ssi', $status, $status, $id);
            $ok = $stmt->execute() && $stmt->affected_rows >= 0;
            $_SESSION['admin_flash'] = ['type' => $ok ? 'success' : 'danger', 'text' => $ok ? 'Đã cập nhật trạng thái lịch đặt.' : 'Không thể cập nhật lịch đặt.'];
        } elseif ($id && $action === 'delete') {
            $stmt = $conn->prepare('DELETE FROM booking WHERE id = ?');
            $stmt->bind_param('i', $id);
            $ok = $stmt->execute() && $stmt->affected_rows > 0;
            $_SESSION['admin_flash'] = ['type' => $ok ? 'success' : 'warning', 'text' => $ok ? 'Đã xóa lịch đặt.' : 'Không tìm thấy lịch đặt để xóa.'];
        } else {
            $_SESSION['admin_flash'] = ['type' => 'warning', 'text' => 'Thao tác không hợp lệ.'];
        }
    }
    header('Location: booking.php' . (isset($_GET['status']) ? '?status=' . rawurlencode($_GET['status']) : ''));
    exit;
}

$allowedStatuses = ['pending', 'confirmed', 'cancelled'];
$filter = $_GET['status'] ?? 'all';
if (!in_array($filter, array_merge(['all'], $allowedStatuses), true)) {
    $filter = 'all';
}
$flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
if ($filter === 'all') {
    $result = $conn->query('SELECT id, name, DT, ngaydat, giodat, note, STATUS, total_amount, deposit_amount, deposit_status FROM booking ORDER BY ngaydat DESC, giodat ASC');
} else {
    $stmt = $conn->prepare('SELECT id, name, DT, ngaydat, giodat, note, STATUS, total_amount, deposit_amount, deposit_status FROM booking WHERE STATUS = ? ORDER BY ngaydat DESC, giodat ASC');
    $stmt->bind_param('s', $filter);
    $stmt->execute();
    $result = $stmt->get_result();
}
$statusNames = ['pending' => 'Chờ duyệt', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý lịch đặt | Pickleball Trung Ngọc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f5; color: #193129; }
        .topbar { background: #fff; border-bottom: 1px solid #e4ebe7; }
        .topbar a { color: #193129; text-decoration: none; font-weight: 700; }
        .panel { overflow: hidden; background: #fff; border: 1px solid #e4ebe7; border-radius: 16px; }
        .table th, .table td { padding: 13px 14px; vertical-align: middle; }
        @media (max-width: 767.98px) { .table th, .table td { padding: 10px; } }
    </style>
</head>
<body>
<nav class="navbar topbar"><div class="container"><a href="dashboar.php">🏓 Pickleball / Quản trị</a><a class="btn btn-outline-secondary btn-sm" href="../index.php">Về trang chủ</a></div></nav>
<main class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4"><div><a href="dashboar.php" class="text-success text-decoration-none">← Dashboard</a><h1 class="h2 fw-bold mt-2 mb-0">Quản lý lịch đặt</h1></div></div>
    <?php if ($flash) { ?><div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show" role="alert"><?= htmlspecialchars($flash['text'], ENT_QUOTES, 'UTF-8') ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button></div><?php } ?>
    <div class="d-flex flex-wrap gap-2 mb-3">
        <?php foreach (['all' => 'Tất cả', 'pending' => 'Chờ duyệt', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy'] as $key => $label) { ?>
            <a class="btn btn-sm <?= $filter === $key ? 'btn-success' : 'btn-outline-success' ?>" href="booking.php<?= $key === 'all' ? '' : '?status=' . $key ?>"><?= $label ?></a>
        <?php } ?>
    </div>
    <div class="panel table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-success"><tr><th>Mã</th><th>Khách hàng</th><th>Điện thoại</th><th>Ngày</th><th>Giờ</th><th>Ghi chú</th><th>Thanh toán</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0) { while ($row = $result->fetch_assoc()) {
                $status = $row['STATUS'] ?: 'pending';
                $badge = $status === 'confirmed' ? 'success' : ($status === 'cancelled' ? 'secondary' : 'warning text-dark');
            ?>
                <tr>
                    <td>#<?= (int) $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><a href="tel:<?= htmlspecialchars($row['DT'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($row['DT'], ENT_QUOTES, 'UTF-8') ?></a></td>
                    <td><?= date('d/m/Y', strtotime($row['ngaydat'])) ?></td>
                    <td><?= htmlspecialchars(substr($row['giodat'], 0, 5), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= nl2br(htmlspecialchars($row['note'] ?? '', ENT_QUOTES, 'UTF-8')) ?: '<span class="text-secondary">—</span>' ?></td>
                    <td><small>Tổng: <?= number_format((int) $row['total_amount'], 0, ',', '.') ?>đ<br>Cọc: <?= number_format((int) $row['deposit_amount'], 0, ',', '.') ?>đ<br><?= htmlspecialchars($row['deposit_status'], ENT_QUOTES, 'UTF-8') ?></small></td>
                    <td><span class="badge text-bg-<?= $badge ?>"><?= htmlspecialchars($statusNames[$status] ?? $status, ENT_QUOTES, 'UTF-8') ?></span></td>
                    <td><div class="d-flex flex-wrap gap-2">
                        <form method="post" class="d-flex gap-1">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="booking_id" value="<?= (int) $row['id'] ?>">
                            <input type="hidden" name="action" value="status">
                            <select name="status" class="form-select form-select-sm" aria-label="Trạng thái lịch #<?= (int) $row['id'] ?>">
                                <?php foreach ($statusNames as $value => $label) { ?><option value="<?= $value ?>" <?= $status === $value ? 'selected' : '' ?>><?= $label ?></option><?php } ?>
                            </select>
                            <button class="btn btn-sm btn-outline-success">Lưu</button>
                        </form>
                        <form method="post" onsubmit="return confirm('Xóa lịch đặt #<?= (int) $row['id'] ?>?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="booking_id" value="<?= (int) $row['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn btn-sm btn-outline-danger">Xóa</button>
                        </form>
                    </div></td>
                </tr>
            <?php } } else { ?><tr><td colspan="9" class="text-center py-5 text-secondary">Không có lịch đặt trong mục này.</td></tr><?php } ?>
            </tbody>
        </table>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
