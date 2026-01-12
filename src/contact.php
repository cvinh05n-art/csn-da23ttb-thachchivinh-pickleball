<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pickleball Center</title>
        <link rel="stylesheet" href="boostrap cdn/KT2/css/bootstrap.min.css">
        <script src="bootstrap cdn/KT2/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
    <?php include("include/header.php"); ?>
<!-- Contact Form -->
    <div class ="container my-5">
        <h2 class="text-center mb-4">Liên hệ với chúng tôi</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="process_contact.php" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Tin nhắn:</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi</button>
                </form>
            </div>
        </div>
<!-- Thông Tin-->
     <div class="text-center mt-4">
       <h4>Thông tin liên hệ</h4>
       <p>Địa chỉ: Phường Trà Vinh, Tỉnh Vĩnh Long.</p>
       <p>Điện thoại: 0383415367</p>
       <p>Email: cvinh05n@gmail.com
     </div>
 </body>
</html>