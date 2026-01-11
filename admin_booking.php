<?php 
include("config.php");
session_start();
if (!isset($_SESSION["user"])) {
    // Kiểm tra đã Login hay chưa
    header("Location: login.php");
    exit;
}
    if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
       //Đã đăng nhập nhưng không phải admin
        echo"
        <div class='container mt-5'>
            <div class='alert alert-danger text-center'>
                <h4>Bạn không có quyền truy cập trang này!</h4>
            </div>

            <script>
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 5000);
        ";
        exit;
    }
    if (isset(($_GET['delete']))){
        $id= intval($_GET['delete']);
        $conn->query("DELETE FROM booking WHERE id=$id");
        header("Location: admin_booking.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản Lý Đặt Sân</title>
        <link rel="stylesheet" href="boostrap cdn/KT2/css/bootstrap.min.css">
        <script src="boostrap cdn/KT2/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <?php include("include/header.php"); ?>
        <div class="container my-2">
            <h2 class="text-center mb-4">Quản Lý Đặt Sân</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và Tên</th>
                        <th>Số Điện Thoại</th>
                        <th>Ngày Đặt</th>
                        <th>Giờ Đặt</th>
                        <th>Ghi Chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM booking ORDER BY id DESC");
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['DT']}</td>
                            <td>{$row['ngaydat']}</td>
                            <td>{$row['giodat']}</td>
                            <td>{$row['note']}</td>
                            <td>
                                <a href='admin_booking.php?delete={$row['id']}' 
                                    class='btn btn-danger btn-sm' 
                                     onclick=\"return confirm('Chắc chắn muốn xóa lịch đặt sân này?');\">
                                     Xóa
                                 </a>
                            </td>
                        </tr>";
                    }
                    ?>
                </body>
            </table>
        </div>
</html>