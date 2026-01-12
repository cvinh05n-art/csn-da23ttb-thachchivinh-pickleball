<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - Pickleball Trung Ngọc</title>

    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap cdn/KT2/js/bootstrap.bundle.min.js"></script>

    <style>
    /*hero section */
        .hero {
            position: relative;
            background: url('images/Pi_1.PNG') no-repeat center center;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
        }
        .hero::before{
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 100,80, 0.55);
            backdrop-filter: brightness(75%);
        }
        .hero-content { 
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            top: 50%;
            transform: translateY(-50%);   
        }
        .hero h1 {
            font-size: 42px;
            font-weight: 700;
        }
        .hero p {
            font-size: 18px;
            margin-top: 10px;
        }
        .about-img {
            width: 100%;
            height: 230px;
            object-fit: cover;
            border-radius: 10px;
        }
    /*dropdown menu */
    .dropdown button {
        background-color: #198754;
        color: white;
        font-size: 18px;
        border-radius: 8px;
        padding: 10px 18px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.18);
        transition: 0.25s ease;
    }
    .dropdown button:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    .dropdown-menu {
        font-size: 12px;
        padding: 10px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        animation: fadeDropdown 0.25s ease;
    }
    .dropdown-item{
        padding: 12px 15px;
        font-size: 16px;
    }
    .dropdown-item:hover {
        background-color: #e4ffe4;
        border-radius: 6px;
        color: #0a7f31;
        font-weight: 600;
    }
    @keyframes fadeDropdown {
        from {opacity: 0; transform: translateY(-10px);}
        to {opacity: 1; transform: translateY(0);}
    }
    /*INFO BOX*/
    .info-box{
        background: #ffffff;
        padding:22px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        transition: 0.3s ease;
    }
    .info-box:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        transform: translateY(-5px);
    }
    .icon{
        font-size: 40px;
        margin-bottom: 10px;
    }
    /*IMAGE GRID*/
    .about-img{
        width: 100%;
        height:250px;
        object-fit: cover;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.18);
        transition: 0.3s ease;
    }
    .about-img:hover{
        transform: scale(1.03);
    }
    </style>
</head>

<body>

<?php include("include/header.php"); ?>

<!-- BANNER -->
<div class="container mt-4">
    <div class="hero">
        <div class="hero-content">
            <h1>Giới thiệu Pickleball Trung Ngọc</h1>
            <p>Uy tín – Chất lượng – Tận tâm vì cộng đồng Pickleball Trà Vinh</p>
        </div>
</div>

<!--Menu-->
<div class="container mt-4">
   <div class="dropdown">
    <button class="btn btn-success dropdown-toggle px-4 py-2"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
     >
     ☰ Menu
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#gioithieu">Về chúng tôi</a></li>
        <li><a class="dropdown-item" href="#tienich">Tiện ích</a></li>
        <li><a class="dropdown-item" href="#banggia">Bảng giá</a></li>
        <li><a class="dropdown-item" href="#Sukien">Sự kiện & Giải đấu</a></li>
        <li><a class="dropdown-item" href="#Hinhanh">Hình ảnh</a></li>
    </ul>
   </div>

</div>
<div class="container my-5">
<section id="gioithieu">
    <!-- Giới thiệu -->
    <div class="info-box mb-4">
        <h3>Về chúng tôi</h3>
        <p>
            Pickleball Trung Ngọc là một trong những sân đầu tiên tại Trà Vinh 
            được đầu tư bài bản theo đúng tiêu chuẩn Pickleball hiện đại. 
            Chúng tôi mang đến một không gian thể thao năng động, thân thiện và phù hợp 
            cho mọi độ tuổi – từ người chơi mới bắt đầu cho đến vận động viên chuyên nghiệp.
        </p>
        <p>
            Địa chỉ: Đường Nguyễn Chí Thanh, Phường 5, TP. Trà Vinh, Tỉnh Vĩnh Long.
        </p>
    </div>
</section>
    <!-- Sứ mệnh -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="info-box text-center">
                <div class="icon">🎯</div>
                <h4>Sứ mệnh</h4>
                <p>Xây dựng cộng đồng Pickleball lớn mạnh tại Trà Vinh và khu vực lân cận.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box text-center">
                <div class="icon">🤝</div>
                <h4>Giá trị</h4>
                <p>Trung thực – Chất lượng – Phục vụ tận tâm – Kết nối cộng đồng.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box text-center">
                <div class="icon">🌟</div>
                <h4>Tầm nhìn</h4>
                <p>Trở thành trung tâm Pickleball hàng đầu khu vực Đồng bằng Sông Cửu Long.</p>
            </div>
        </div>
    </div>

    <!-- Tiện ích -->
    <section id="tienich" class="mb-5">
    <h3 class="mb-3">Tiện ích nổi bật</h3>
    <div class="row g-4">

        <div class="col-md-4">
            <div class="info-box text-center">
                <div class="icon">🏓</div>
                <h5>Sân chuẩn quốc tế</h5>
                <p>3 sân pickleball chuẩn, mặt sân chất lượng cao, ánh sáng tốt.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box text-center">
                <div class="icon">🎒</div>
                <h5>Cho thuê vợt & dụng cụ</h5>
                <p>Cho thuê vợt, bóng và phụ kiện Pickleball cho người chơi mới.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box text-center">
                <div class="icon">📚</div>
                <h5>Huấn luyện viên</h5>
                <p>Đội ngũ HLV thân thiện, hướng dẫn cơ bản đến nâng cao.</p>
            </div>
        </div>
    </div>
    </section>

    <!--Bảng giá-->
 <section class="container my-5" id="banggia">
            <h2 class="mb-3">Bảng giá sân</h2>
        <table class="table table-bordered text-center">
            <thead class="table-success">
                <tr>
                    <th>Khung giờ</th>
                    <th>Thời gian</th>
                    <th>Giá (VNĐ/giờ)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sáng - Chiều</td>
                    <td>07:00 - 17:00</td>
                    <td>60.000đ/giờ</td>
                </tr>
                <tr>
                    <td>Buổi tối</td>
                    <td>17:00 - 22:00</td>
                    <td>80.000đ/giờ</td>
                </tr>
                <tr class="table-light">
                    <td> Khách vãng lai</td>
                    <td>Cả ngày</td>
                    <td>30.000đ/người/buổi</td>
                </tr>
                <tr class="table-light">
                    <td>Hội viên tháng</td>
                    <td>Cả tháng</td>
                    <td>500.000đ/tháng</td>
                </tr>
            </tbody>
        </table>

    <!-- Sự kiện -->
<section id="Sukien" class="container my-5">
    <h2 class="mb-3">Sự kiện & Giải đấu</h2>
    <p>
        Các sự kiện pickleball tại sân Trung Ngọc sẽ được cập nhật thường xuyên trên trang Facebook chính thức.
        Hãy truy cập để xem lịch thi đấu, buổi giao lưu và thông báo mới nhất nhé.
    </p>
    <a href="https://www.facebook.com/Pickleballtrungngoc"
        target="_blank"
        class="btn btn-primary w-100">
        📅 Xem sự kiện trên Facebook
    </a>
</section>
    <!-- Hình ảnh -->
<section id="Hinhanh">    
    <h3 class="mt-5 mb-3">Hình ảnh sân</h3>
    <div class="row g-3">
        <div class="col-md-6">
            <img src="images/Pi_1.PNG" class="about-img" alt="Sân Pickleball Trung Ngọc">
        </div>
        <div class="col-md-6">
            <img src="images/Pi_2.PNG" class="about-img" alt="Sân Pickleball Trung Ngọc">
        </div>
        <div class="col-md-6">
            <img src="images/Pi_3.PNG" class="about-img" alt="Sân Pickleball Trung Ngọc">
        </div>
        <div class="col-md-6">
            <img src="images/Pi_4.PNG" class="about-img" alt="Sân Pickleball Trung Ngọc">
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center mt-5">
        <div class="book-box" style="
        display: inline-block;
        padding: 25px 40px;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 6px 18px rgba(0,0,0,0.18);
        transition: 0.3s;">
        <a href="book.php" class="btn btn-success btn-lg">🏓Đặt sân ngay</a>
        </div>
    </div> 
</section>   
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<footer>
    <?php include("include/footer.php"); ?>
</footer>
</html>