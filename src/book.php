<?php
require "config.php";
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['booking_csrf'])) {
    $_SESSION['booking_csrf'] = bin2hex(random_bytes(32));
}

$isLoggedIn = !empty($_SESSION['user']);
$username = $isLoggedIn ? (string) $_SESSION['user'] : 'Khách đặt sân';
$dayRate = 60000;
$eveningRate = 80000;
$weekOffset = filter_input(INPUT_GET, 'week', FILTER_VALIDATE_INT);
$weekOffset = $weekOffset === false || $weekOffset === null ? 0 : max(0, min(52, $weekOffset));
$weekStart = new DateTimeImmutable('monday this week');
if ($weekOffset > 0) {
    $weekStart = $weekStart->modify('+' . $weekOffset . ' weeks');
}
$days = [];
for ($i = 0; $i < 7; $i++) {
    $days[] = $weekStart->modify('+' . $i . ' days')->format('Y-m-d');
}

$requestedDate = $_GET['ngay'] ?? '';
$selectedDate = in_array($requestedDate, $days, true) ? $requestedDate : '';
if ($selectedDate === '') {
    foreach ($days as $day) {
        if ($day >= date('Y-m-d')) {
            $selectedDate = $day;
            break;
        }
    }
}
if ($selectedDate === '') {
    $selectedDate = $days[0];
}

$slots = [];
for ($hour = 7; $hour <= 21; $hour++) {
    $slots[] = sprintf('%02d:00', $hour);
}

$booked = [];
$bookings = $conn->prepare("SELECT giodat FROM booking WHERE ngaydat = ? AND COALESCE(STATUS, 'pending') <> 'cancelled'");
$bookings->bind_param('s', $selectedDate);
$bookings->execute();
$bookingRows = $bookings->get_result();
while ($row = $bookingRows->fetch_assoc()) {
    $existingStart = strtotime($row['giodat']);
    if ($existingStart !== false) {
        $booked[] = [$existingStart, $existingStart + 3600];
    }
}

$slotStates = [];
$now = time();
foreach ($slots as $slot) {
    $slotStart = strtotime($selectedDate . ' ' . $slot);
    $slotEnd = $slotStart + 3600;
    $past = $slotStart <= $now;
    $isBooked = false;
    foreach ($booked as [$existingStart, $existingEnd]) {
        if ($slotStart < $existingEnd && $slotEnd > $existingStart) {
            $isBooked = true;
            break;
        }
    }
    $slotStates[$slot] = ['past' => $past, 'booked' => $isBooked];
}

$errors = [];
$success = $_SESSION['booking_success'] ?? '';
unset($_SESSION['booking_success']);
$phoneValue = '';
$noteValue = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $phoneValue = trim((string) ($_POST['DT'] ?? ''));
    $noteValue = trim((string) ($_POST['note'] ?? ''));
    $postDate = (string) ($_POST['ngaydat'] ?? '');
    $postTime = (string) ($_POST['giodat'] ?? '');

    if (!hash_equals($_SESSION['booking_csrf'], $token)) {
        $errors[] = 'Phiên đặt sân hết hạn. Vui lòng tải lại trang và thử lại.';
    }
    if (!in_array($postDate, $days, true) || $postDate < date('Y-m-d')) {
        $errors[] = 'Vui lòng chọn một ngày hợp lệ trong tuần đang xem.';
    }
    if (!in_array($postTime, $slots, true)) {
        $errors[] = 'Vui lòng chọn một khung giờ hợp lệ.';
    }
    if (!preg_match('/^[0-9+().\s-]{9,20}$/', $phoneValue)) {
        $errors[] = 'Vui lòng nhập số điện thoại hợp lệ.';
    }
    if (strlen($noteValue) > 1000) {
        $errors[] = 'Ghi chú không được vượt quá 1.000 ký tự.';
    }

    if (!$errors) {
        $slotStart = strtotime($postDate . ' ' . $postTime);
        if ($postDate === date('Y-m-d') && $slotStart <= time()) {
            $errors[] = 'Khung giờ này đã qua. Vui lòng chọn giờ khác.';
        }
        $check = $conn->prepare("SELECT id FROM booking
            WHERE ngaydat = ?
              AND giodat < ADDTIME(?, '01:00:00')
              AND ADDTIME(giodat, '01:00:00') > ?
              AND COALESCE(STATUS, 'pending') <> 'cancelled'
            LIMIT 1");
        $check->bind_param('sss', $postDate, $postTime, $postTime);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $errors[] = 'Khung giờ vừa chọn đã có người đặt. Vui lòng chọn khung khác.';
        }
    }

    if (!$errors) {
        $totalAmount = ((int) substr($postTime, 0, 2) < 17) ? $dayRate : $eveningRate;
        $depositAmount = (int) ceil($totalAmount * 0.3);
        $depositStatus = 'pending';
        $stmt = $conn->prepare('INSERT INTO booking (name, DT, ngaydat, giodat, note, total_amount, deposit_amount, deposit_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssiis', $username, $phoneValue, $postDate, $postTime, $noteValue, $totalAmount, $depositAmount, $depositStatus);
        if ($stmt->execute()) {
            $bookingId = $stmt->insert_id;
            $_SESSION['booking_id'] = $bookingId;
            header('Location: payment.php?id=' . $bookingId);
            exit;
        }
        $errors[] = 'Chưa thể lưu lịch đặt. Vui lòng thử lại sau.';
    }
    $selectedDate = in_array($postDate, $days, true) ? $postDate : $selectedDate;
    $selectedTime = $postTime;
} else {
    $selectedTime = (string) ($_GET['gio'] ?? '');
}

// Refresh availability after selecting another day or after a failed form submission.
$booked = [];
$bookings = $conn->prepare("SELECT giodat FROM booking WHERE ngaydat = ? AND COALESCE(STATUS, 'pending') <> 'cancelled'");
$bookings->bind_param('s', $selectedDate);
$bookings->execute();
$bookingRows = $bookings->get_result();
while ($row = $bookingRows->fetch_assoc()) {
    $existingStart = strtotime($row['giodat']);
    if ($existingStart !== false) {
        $booked[] = [$existingStart, $existingStart + 3600];
    }
}
foreach ($slots as $slot) {
    $slotStart = strtotime($selectedDate . ' ' . $slot);
    $slotEnd = $slotStart + 3600;
    $isBooked = false;
    foreach ($booked as [$existingStart, $existingEnd]) {
        if ($slotStart < $existingEnd && $slotEnd > $existingStart) {
            $isBooked = true;
            break;
        }
    }
    $slotStates[$slot] = ['past' => $slotStart <= time(), 'booked' => $isBooked];
}
$canSubmit = isset($slotStates[$selectedTime]) && !$slotStates[$selectedTime]['past'] && !$slotStates[$selectedTime]['booked'];
$selectedRate = $selectedTime !== '' && (int) substr($selectedTime, 0, 2) < 17 ? $dayRate : $eveningRate;
$selectedDeposit = (int) ceil($selectedRate * 0.3);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f8f6">
    <title>Đặt sân | Pickleball Trung Ngọc</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        :root { --green: #168454; --ink: #193129; --muted: #687871; }
        body { background: #f5f8f6; color: var(--ink); }
        .topbar { background: #fff; border-bottom: 1px solid #e4ebe7; }
        .brand { color: var(--ink); font-weight: 750; text-decoration: none; }
        .brand-mark { display: inline-grid; width: 37px; height: 37px; margin-right: 8px; place-items: center; border-radius: 12px; background: #e5f4ec; }
        .page-wrap { max-width: 1100px; }
        .page-title { font-weight: 760; letter-spacing: -.04em; }
        .panel { background: #fff; border: 1px solid #e2eae5; border-radius: 17px; box-shadow: 0 8px 25px rgba(25,49,41,.055); }
        .panel-heading { font-size: 1.1rem; font-weight: 720; }
        .day-list { display: grid; grid-template-columns: repeat(7, minmax(105px, 1fr)); gap: 9px; }
        .day-choice { display: block; padding: 13px 8px; color: var(--ink); text-align: center; text-decoration: none; background: #f7faf8; border: 1px solid #e4ebe7; border-radius: 13px; transition: .18s ease; }
        .day-choice:hover { border-color: #8fc5a8; color: var(--green); }
        .day-choice.active { color: #fff; background: var(--green); border-color: var(--green); box-shadow: 0 5px 15px rgba(22,132,84,.2); }
        .day-weekday { display: block; font-size: .8rem; opacity: .78; }
        .day-number { display: block; margin-top: 3px; font-size: 1.15rem; font-weight: 720; }
        .slot-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px; }
        .slot { display: block; min-height: 58px; padding: 10px; color: var(--green); text-align: center; text-decoration: none; font-weight: 700; background: #eff8f2; border: 1px solid #d4eadc; border-radius: 12px; }
        .slot:hover, .slot.selected { color: #fff; background: var(--green); border-color: var(--green); }
        .slot.unavailable { color: #89938e; background: #f0f2f1; border-color: #e1e5e2; cursor: not-allowed; }
        .slot small { display: block; margin-top: 2px; font-size: .72rem; font-weight: 500; }
        .legend { color: var(--muted); font-size: .85rem; }
        .legend-dot { display: inline-block; width: 10px; height: 10px; margin-right: 5px; border-radius: 50%; background: #5fae7e; }
        .legend-dot.gray { background: #aab2ad; }
        .form-control { min-height: 46px; border-color: #dce5df; }
        .btn-success { background: var(--green); border-color: var(--green); }
        .btn-success:hover { background: #106944; border-color: #106944; }
        @media (max-width: 767.98px) { .day-list { display: flex; overflow-x: auto; padding: 2px 2px 8px; } .day-choice { flex: 0 0 102px; } .slot-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 420px) { .slot-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    </style>
</head>
<body>
<nav class="navbar topbar"><div class="container page-wrap">
    <a class="brand navbar-brand" href="index.php"><span class="brand-mark" aria-hidden="true">🏓</span>Pickleball Trung Ngọc</a>
    <div class="d-flex align-items-center gap-3"><span class="text-secondary small d-none d-sm-inline">Xin chào, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></span><a class="nav-link" href="logout.php">Đăng xuất</a></div>
</div></nav>

<main class="container page-wrap py-4 py-lg-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><p class="text-success fw-semibold mb-1">ĐẶT SÂN NHANH</p><h1 class="page-title h2 mb-1">Đặt sân Pickleball</h1><p class="text-secondary mb-0">Không cần đăng nhập. Chỉ cần số điện thoại để sân liên hệ xác nhận.</p></div>
        <div class="d-flex gap-2"><a class="btn btn-outline-secondary" href="about.php">Thông tin sân</a><?php if (!$isLoggedIn) { ?><a class="btn btn-outline-success" href="login.php">Đăng nhập</a><?php } ?></div>
    </div>

    <?php if ($success) { ?><div class="alert alert-success" role="status">✅ <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div><?php } ?>
    <?php if ($errors) { ?><div class="alert alert-danger" role="alert"><strong>Chưa thể đặt sân:</strong><ul class="mb-0 mt-1"><?php foreach (array_unique($errors) as $error) { ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li><?php } ?></ul></div><?php } ?>

    <section class="panel p-3 p-md-4 mb-4" aria-labelledby="day-title">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div><h2 class="panel-heading mb-1" id="day-title">1. Chọn ngày</h2><span class="text-secondary small"><?= date('d/m/Y', strtotime($days[0])) ?> – <?= date('d/m/Y', strtotime($days[6])) ?></span></div>
            <div class="d-flex gap-2">
                <?php if ($weekOffset > 0) { ?><a class="btn btn-sm btn-outline-secondary" href="book.php?week=<?= $weekOffset - 1 ?>">← Tuần trước</a><?php } ?>
                <?php if ($weekOffset < 52) { ?><a class="btn btn-sm btn-outline-secondary" href="book.php?week=<?= $weekOffset + 1 ?>">Tuần sau →</a><?php } ?>
            </div>
        </div>
        <div class="day-list">
            <?php foreach ($days as $day) {
                $dayLabel = date('D', strtotime($day));
                $weekdayNames = ['Mon' => 'Thứ 2', 'Tue' => 'Thứ 3', 'Wed' => 'Thứ 4', 'Thu' => 'Thứ 5', 'Fri' => 'Thứ 6', 'Sat' => 'Thứ 7', 'Sun' => 'Chủ nhật'];
                $isPast = $day < date('Y-m-d');
            ?>
                <?php if ($isPast) { ?><span class="day-choice opacity-50" aria-disabled="true"><span class="day-weekday"><?= $weekdayNames[$dayLabel] ?></span><span class="day-number"><?= date('d/m', strtotime($day)) ?></span></span>
                <?php } else { ?><a class="day-choice <?= $selectedDate === $day ? 'active' : '' ?>" href="book.php?week=<?= $weekOffset ?>&amp;ngay=<?= urlencode($day) ?>" aria-current="<?= $selectedDate === $day ? 'date' : 'false' ?>"><span class="day-weekday"><?= $weekdayNames[$dayLabel] ?></span><span class="day-number"><?= date('d/m', strtotime($day)) ?></span></a><?php } ?>
            <?php } ?>
        </div>
    </section>

    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <section class="panel p-3 p-md-4" aria-labelledby="time-title">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <?php $weekdayNames = [1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7', 7 => 'Chủ nhật']; ?>
                    <div><h2 class="panel-heading mb-1" id="time-title">2. Chọn giờ bắt đầu</h2><span class="text-secondary small"><?= $weekdayNames[(int) date('N', strtotime($selectedDate))] ?>, <?= date('d/m/Y', strtotime($selectedDate)) ?></span></div>
                    <div class="legend"><span class="legend-dot"></span>Còn trống <span class="legend-dot gray ms-2"></span>Đã qua / đã đặt</div>
                </div>
                <div class="slot-grid">
                    <?php foreach ($slots as $slot) {
                        $state = $slotStates[$slot];
                        $disabled = $state['past'] || $state['booked'];
                        $slotEndLabel = date('H:i', strtotime($slot . ' +1 hour'));
                        if ($disabled) {
                            $reason = $state['booked'] ? 'Đã có lịch' : 'Đã qua';
                    ?>
                        <span class="slot unavailable"><span><?= $slot ?></span><small><?= $reason ?></small></span>
                    <?php } else { ?>
                        <a class="slot <?= $selectedTime === $slot ? 'selected' : '' ?>" href="book.php?week=<?= $weekOffset ?>&amp;ngay=<?= urlencode($selectedDate) ?>&amp;gio=<?= urlencode($slot) ?>" aria-pressed="<?= $selectedTime === $slot ? 'true' : 'false' ?>"><span><?= $slot ?></span><small>đến <?= $slotEndLabel ?></small></a>
                    <?php } } ?>
                </div>
            </section>
        </div>

        <div class="col-lg-5">
            <section class="panel p-3 p-md-4" aria-labelledby="details-title">
                <h2 class="panel-heading mb-1" id="details-title">3. Thông tin đặt sân</h2>
                <p class="text-secondary small mb-3">Điền số điện thoại để sân liên hệ xác nhận lịch.</p>
                <form method="post" action="book.php?week=<?= $weekOffset ?>&amp;ngay=<?= urlencode($selectedDate) ?>" id="bookingForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['booking_csrf'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="ngaydat" value="<?= htmlspecialchars($selectedDate, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="giodat" value="<?= htmlspecialchars($selectedTime, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="mb-3"><label class="form-label" for="bookingPhone">Số điện thoại</label><input id="bookingPhone" type="tel" name="DT" class="form-control" value="<?= htmlspecialchars($phoneValue, ENT_QUOTES, 'UTF-8') ?>" placeholder="Ví dụ: 0901234567" autocomplete="tel" required></div>
                    <div class="mb-3"><label class="form-label" for="bookingNote">Ghi chú <span class="text-secondary">(không bắt buộc)</span></label><textarea id="bookingNote" name="note" class="form-control" rows="3" maxlength="1000" placeholder="Số người chơi hoặc yêu cầu thêm"><?= htmlspecialchars($noteValue, ENT_QUOTES, 'UTF-8') ?></textarea></div>
                    <div class="border rounded-3 p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between"><span class="text-secondary">Ngày</span><strong><?= date('d/m/Y', strtotime($selectedDate)) ?></strong></div>
                        <div class="d-flex justify-content-between mt-2"><span class="text-secondary">Khung giờ</span><strong id="selectedTimeLabel"><?= $selectedTime !== '' ? htmlspecialchars($selectedTime . ' – ' . date('H:i', strtotime($selectedTime . ' +1 hour')), ENT_QUOTES, 'UTF-8') : 'Chưa chọn giờ' ?></strong></div>
                        <div class="d-flex justify-content-between mt-2"><span class="text-secondary">Giá sân</span><strong><?= number_format($selectedRate, 0, ',', '.') ?>đ / giờ</strong></div>
                        <div class="d-flex justify-content-between mt-2 text-success"><span>Tiền cọc (30%)</span><strong><?= number_format($selectedDeposit, 0, ',', '.') ?>đ</strong></div>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100" <?= !$canSubmit ? 'disabled' : '' ?>>Gửi yêu cầu đặt sân</button>
                    <p class="small text-secondary text-center mt-3 mb-0">Sau khi gửi, sân sẽ liên hệ để xác nhận và hướng dẫn chuyển cọc 30%.</p>
                </form>
            </section>
        </div>
    </div>
</main>
</body>
</html>
