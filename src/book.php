<?php 
include("config.php");
session_start();
// Kiểm tra đăng nhập chưa?
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['user'];

$ngaydat = $_GET['ngay'] ?? '';
$giodat = $_GET['gio'] ?? '';

$weekOffset = isset($_GET['week']) ? intval($_GET['week']) : 0;
$startWeek = date('Y-m-d', strtotime("monday this week + $weekOffset week"));
$days = [];
for ($i = 0; $i < 7; $i++) {
    $days[] = date('Y-m-d', strtotime($startWeek . " +$i days"));
}
$timeSlots = [
    "Sáng(07:00 - 11:00)" => ["07:00", "11:00"],
    "Chiều(13:00 - 17:00)" => ["13:00", "17:00"],
    "Tối(17:00 - 22:00)" => ["17:00", "22:00"]
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Sân Pickleball</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body> 
    <?php include("include/header.php"); ?>
    <div class="container my-2">
        <h2 class="text-center mb-3">Đặt Sân Pickleball</h2>
        <h4 class="text-center mt-4 mb-3">Lịch đặt sân trong tuần</h4>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">
                Lịch đặt sân:
                <?= date('d/m/Y', strtotime($days[0])) ?> 
                -
                <?= date('d/m/Y', strtotime(end($days))) ?>
            </h4>
            <div>
                <?php if ($weekOffset > 0): ?>
                    <a href="book.php?week=<?=$weekOffset -1?>"
                    class="btn btn-outline-secondary btn-sm">
                    <-- Tuần trước 
                </a>
                <?php endif; ?>
                <a href="book.php?week=<?=$weekOffset +1?>"
                class="btn btn-outline-secondary btn-sm">
                    Tuần sau -->
                </a>
            </div>
        </div>
        <table class="table table-bordered text-center align-middle">
            <thead class="table-success">
                <tr>
                    <th>Khung giờ</th>
                    <?php foreach ($days as $day): ?>
                        <th><?= date('d/m', strtotime($day)) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timeSlots as $label => $time): ?>
                    <tr>
                        <td>
                            <strong>
                                <?= $label ?>
                            </strong>
                        </td>
                        <?php foreach ($days as $day): ?>
                            <?php
                            $check = $conn->prepare("
                                SELECT id from booking
                                WHERE ngaydat = ? And giodat between ? and ?");
                            $check->bind_param("sss", $day, $time[0], $time[1]);
                            $check->execute();
                            $result = $check->get_result();
                            ?>
                            <td>
                                <?php if ($result->num_rows > 0): ?>
                                    <span class="text-danger">Đã Đặt</span>
                                <?php else: ?>
                                    <a href="book.php?week=<?= $weekOffset ?>&ngay=<?= $day ?>&gio=<?= $time[0]?>"
                                    class="btn btn-success btn-sm">
                                    Trống - Đặt Ngay
                                </a>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form method="POST">
                <input type="text" name="name" class="form-control mb-3" value="<?=htmlspecialchars($username)?>" readonly>
                <input type="text" name="DT" class="form-control mb-3" placeholder="Số điện thoại" required>
                <label>Chọn ngày</label>
                <input type="date" name="ngaydat" class="form-control mb-3" value="<?=$ngaydat?>" required>
                <label>Chọn khung giờ</label>
                <input type="time" name="giodat" class="form-control mb-3" value="<?=$giodat?>"required>
                <textarea name="note" class="form-control mb-3" placeholder="Ghi chú (nếu có)="></textarea>
                <button type="submit" name="submit" class="btn btn-primary">Đặt Sân</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $name = $_POST['name'];
            $DT = $_POST['DT'];
            $ngaydat = $_POST['ngaydat'];
            $giodat = $_POST['giodat'];
            $note = $_POST['note'];

            // Giờ mở cửa và đóng cửa
            $open_time = "07:00";
            $close_time = "22:00";

            $today = date('Y-m-d');
            $now_time = date('H:i');

            // Chặn đặt sân trong quá khứ
            if ($ngaydat < $today)
            {
                echo "<div class='alert alert-danger mt-3'>Không thể đặt sân cho ngày đã qua!</div>";
                return;
            }

            // Chặn đặt sân trong ngày hiện tại nếu giờ đã qua
            if($ngaydat == $today && $giodat < $now_time)
            {
                echo "<div class='alert alert-danger mt-3'>Giờ đặt sân phải từ 07:00 đến 22:00!</div>";
                return;
            }

            // Kiểm tra giờ mở cửa
            if($giodat <$open_time || $giodat > $close_time)
            {
                echo "<div class='alert alert-danger mt-3'>Giờ đặt sân phải từ 07:00 đến 22:00!</div>";
                return;
            }

            // Kiểm tra trùng lịch đặt sân
            $check = $conn->prepare("
            SELECT id FROM booking 
            WHERE ngaydat = ? AND giodat = ?
            ");
            $check->bind_param("ss", $ngaydat, $giodat);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
            echo "<div class='alert alert-danger mt-3'>
            Khung giờ này đã có người đặt, vui lòng chọn giờ khác!
            </div>";
             return;
            }

            $stmt = $conn->prepare("
            INSERT INTO booking (name, DT, ngaydat, giodat, note)
            Values (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssss", $name, $DT, $ngaydat, $giodat, $note);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success mt-3'>
                Đặt sân thành công cho ngày 
                " . date('d/m/Y', strtotime($ngaydat)) . " vào lúc $giodat
                </div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>
                Đặt sân thất bại, vui lòng thử lại sau!
                </div>";
            }
        }    
        ?>
    </body>
</html>