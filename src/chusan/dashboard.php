<?php
require("../config.php");
require("../include/auth_chusan.php");
?>
<?php include("chusan_header.php"); ?>
<div class="chusan-content">
    <h2>📊 Tổng quan sân Pickleball</h2>
    <hr>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-3 bg-primary text-white rounded">
                <h4>Lịch hôm nay</h4>
                <p>
                    <?php 
                        $today = date("Y-m-d");
                        $q = $conn->query("SELECT COUNT(*) AS total FROM booking WHERE ngaydat='$today'")->fetch_assoc();
                        echo $q["total"];
                    ?>
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-success text-white rounded">
                <h4>Tổng lịch đặt</h4>
                <p>
                    <?php 
                        $q = $conn->query("SELECT COUNT(*) AS total FROM booking")->fetch_assoc();
                        echo $q["total"];
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>
