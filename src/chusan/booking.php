<?php
require("../config.php");
require("../include/auth_chusan.php");
?>
<?php include("chusan_header.php"); ?>
<div class="chusan-content">
    <h2 class="mb-3">📅 Lịch đặt sân</h2>
    <hr>
    <div class="row g-3">
        <?php
        $result = $conn->query("SELECT * FROM booking ORDER BY ngaydat DESC, giodat ASC");

        if ($result->num_rows === 0) {
            echo "<p class='text-center mt-3'>Chưa có lịch đặt nào</p>";
        }
        while ($row = $result->fetch_assoc()) {
        ?>
        <div class="col-md-6">
            <div class="card shadow-sm p-3">

                <h5 class="text-primary mb-3">📝 Lịch #<?php echo $row['id']; ?></h5>

                <p><strong>Họ tên:</strong> <?php echo $row['name']; ?></p>
                <p><strong>SĐT:</strong> <?php echo $row['DT']; ?></p>
                <p><strong>Ngày:</strong> <?php echo $row['ngaydat']; ?></p>
                <p><strong>Giờ:</strong> <?php echo $row['giodat']; ?></p>
                <p><strong>Ghi chú:</strong> <?php echo $row['note']; ?></p>
                <button class="btn btn-secondary w-100" disabled>
                    ✔ Chỉ xem (không có quyền xóa)
                </button>
            </div>
        </div>
        <?php } ?>
    </div>

</div>
