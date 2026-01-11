<?php
session_start();
?>

<!DOCTYPE html >
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pickleball</title>
<link rel="stylesheet" href="boostrap cdn/KT2/css/bootstrap.min.css">
<script src="bootstrap cdn/KT2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include("include/header.php"); ?>
     <div class="container-fluid p-5 my-5 bg-dark text-white text-center">
            <img src="images/LogoPickleball.png" alt="LogoPickleball" width="100">
            <h1>PICKLEBALL CENTER </h1>
            <p>UY TÍN - CHẤT LƯỢNG - TẬN TÂM</p>
        </div>
<!--NAVBAR-->
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark ">
    <div class="container justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Trang chủ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php"></a>
            </li>
            <?php if(isset($_SESSION['user'])) {?>
                <li class="nav-item">
                    <span class="nav-link text-warning">Xin chào, <?php echo $_SESSION['user']; ?>!</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">Đăng xuất</a>
                </li>
                <?php if(isset($_SESSION['user_role']) && 
                ($_SESSION['user_role'] === "admin" || $_SESSION['user_role'] === "chusan")) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin/booking.php">Quản lý đặt sân</a>
                </li>
            <?php } ?>
            <?php }
            else 
            { ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Đăng nhập</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Sigin.php">Đăng ký</a>
                </li>
                
            <?php } 
            ?>
            
        </ul>
    </div>
</nav>
    <!--end navs-->
        <div class="container my-2">
            <h2 class="text-center mb-4"></h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">

<!-- Nội Dung-->
 <div class="col">
    <div class="card shadow-sm">
      <img src="images/Pi_1.PNG" class="card-img-top" style="height: 180px; object-fit: cover; " alt="Hình ảnh sân">
         <div class="card-body text-center">
            <h5 class="card-title">Sân Pickleball Trung Ngọc</h5>
            <p class="card-text">Địa chỉ: Đường Nguyễn Chí Thanh, Phường Trà Vinh, Tỉnh Vĩnh Long.</p>
            <a href="about.php" class="btn btn-succes px-4">Tìm hiểu thêm</a>
        </div>
     </div>  
</div>
<!--end nội dung--> 
</div>
</body> 
<footer>
    <?php include("include/footer.php"); ?>
</footer>   
</html>