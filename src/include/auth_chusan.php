<?php
session_start();

if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "chusan") {
    echo "
    <div class='container mt-5'>
        <div class='alert alert-danger text-center'>
            <h4>Bạn không có quyền truy cập trang này!</h4>
        </div>
        <script>
            setTimeout(() => { window.location.href = '../index.php'; }, 3000);
        </script>
    </div>";
    exit;
}
?>
