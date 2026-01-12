<?php
require("../config.php");
require("../include/auth_admin.php");
?>

<?php include("admin_header.php"); ?>

<div class="admin-content">

    <h2 class="mb-3"> Quản lý đặt sân</h2>
    <hr>

    <?php
    // Xóa booking
    if (isset($_GET["delete"])) 
    {
        $id = intval($_GET["delete"]);
        $conn->query("DELETE FROM booking WHERE id = $id");
        echo "<div class='alert alert-success'>Đã xoá lịch đặt sân!</div>";
    }
    ?>

    <table class="table table-bordered table-hover mt-3 text-center">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Điện thoại</th>
                <th>Ngày đặt</th>
                <th>Giờ</th>
                <th>Ghi chú</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM booking ORDER BY ngaydat DESC, giodat ASC");
            if ($result->num_rows === 0) 
                {
                echo 
                "<tr>
                    <td colspan='7'>Không có lịch đặt sân nào!</td>
                </tr>";
            }
            while ($row = $result->fetch_assoc())
            {
                echo "
                <tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['DT']}</td>
                    <td>{$row['ngaydat']}</td>
                    <td>{$row['giodat']}</td>
                    <td>{$row['note']}</td>
                    <td>
                        <a href='booking.php?delete={$row['id']}'
                           class='btn btn-danger btn-sm'
                           onclick=\"return confirm('Có chắc chắn muốn xoá lịch đặt này?');\">
                           Xoá
                        </a>
                    </td>
                </tr>
                ";
            }
            ?>
        </tbody>
    </table>
</div>
