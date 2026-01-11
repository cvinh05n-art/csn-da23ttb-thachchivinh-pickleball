<?
    require_once("../include/config.php");
    require("../include/auth_admin.php");
?>
<?php include("include/admin_header.php"); ?>
<div class="admin-content">
    <h2>Dashboard Admin</h2>
    <p>Chào mừng đến với trang quản trị!</p>
    <hr>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-3 bg-sucess text-white rounded">
                <h4>Tổng booking</h4>
                <p>
                    <?php
                    $result = $conn->query("SELECT COUNT(*) AS total FROM booking");
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 bg-success text-white rounded">
                <h4>Tổng người dùng</h4>
                <p>
                    <?php
                    $result = $conn->query("SELECT COUNT(*) AS total FROM users");
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </p>
            </div>

        <div class="col-md-4">
            <div class="p-3 bg-success text-white rounded">
                <h4>Booking hôm nay</h4>
                <p>
                    <?php
                    $today = date('Y-m-d');
                    $result = $conn->query("SELECT COUNT(*) AS total FROM booking WHERE ngaydat = '$today'");
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </p>
            </div>
    </div>
</div>