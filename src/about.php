<?php
session_start();
$userName = $_SESSION['user'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;
$photos = [
    ['file' => 'Pi_1.png', 'alt' => 'Toàn cảnh sân Pickleball Trung Ngọc'],
    ['file' => 'Pi_2.png', 'alt' => 'Các sân Pickleball trong nhà'],
    ['file' => 'Pi_3.png', 'alt' => 'Không gian sân và khu vực thi đấu'],
    ['file' => 'Pi_4.png', 'alt' => 'Hoạt động tại sân Pickleball Trung Ngọc'],
];
$mapQuery = 'Khu Liên Hợp Thể Thao Trung Ngọc, Đường Nguyễn Chí Thanh, Khóm 1, Thành phố Trà Vinh, Việt Nam';
$mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapQuery);
$mapEmbedUrl = 'https://maps.google.com/maps?q=' . rawurlencode($mapQuery) . '&output=embed';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f8f6">
    <title>Giới thiệu | Sân Pickleball Trung Ngọc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --green: #168454; --green-dark: #106944; --ink: #18302a; --muted: #65766f; }
        body { margin: 0; background: #f5f8f6; color: var(--ink); font-family: system-ui, -apple-system, "Segoe UI", sans-serif; }
        .site-nav { background: #fff; border-bottom: 1px solid #e6ede9; }
        .brand { color: var(--ink); font-weight: 750; letter-spacing: -.03em; text-decoration: none; }
        .brand-mark { display: inline-grid; place-items: center; width: 37px; height: 37px; margin-right: 8px; border-radius: 12px; background: #e5f4ec; }
        .nav-link { color: #52635c; font-weight: 550; }
        .nav-link:hover { color: var(--green); }
        .welcome { color: var(--green-dark); font-size: .9rem; }
        .hero { position: relative; display: grid; min-height: clamp(270px, 38vw, 430px); place-items: center; overflow: hidden; border-radius: 20px; background: #15372c; }
        .hero::before { position: absolute; inset: 0; content: ""; background: linear-gradient(90deg, rgba(9,35,27,.78), rgba(9,35,27,.25)), url("images/Pi_1.png") center 53%/cover; }
        .hero-content { position: relative; width: min(780px, 100%); padding: 38px 22px; color: white; text-align: center; }
        .hero-kicker { color: #bde8d0; font-size: .8rem; font-weight: 750; letter-spacing: .16em; text-transform: uppercase; }
        .hero h1 { margin: 10px 0 12px; font-size: clamp(2rem, 5vw, 3.7rem); font-weight: 750; letter-spacing: -.045em; }
        .hero p { margin: 0 auto; max-width: 620px; color: rgba(255,255,255,.9); font-size: clamp(1rem, 2vw, 1.2rem); }
        .section { scroll-margin-top: 82px; margin-top: 54px; }
        .section-heading { margin-bottom: 20px; }
        .section-heading h2 { margin: 0 0 7px; font-size: clamp(1.55rem, 3vw, 2rem); font-weight: 720; letter-spacing: -.035em; }
        .section-heading p { margin: 0; color: var(--muted); }
        .info-card, .feature-card, .price-card { height: 100%; padding: 24px; background: #fff; border: 1px solid #e3ebe6; border-radius: 16px; box-shadow: 0 8px 24px rgba(27,55,43,.045); }
        .info-card p { color: #53655d; line-height: 1.75; }
        .address-line { display: flex; gap: 10px; align-items: flex-start; padding-top: 10px; color: var(--ink); font-weight: 600; }
        .map-card { overflow: hidden; background: #fff; border: 1px solid #e3ebe6; border-radius: 16px; box-shadow: 0 8px 24px rgba(27,55,43,.06); }
        .map-frame { display: block; width: 100%; height: clamp(300px, 42vw, 440px); border: 0; background: #e7eee9; }
        .map-caption { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 18px 20px; }
        .map-caption p { margin: 4px 0 0; color: var(--muted); }
        .feature-card { transition: transform .2s ease, box-shadow .2s ease; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 13px 28px rgba(27,55,43,.1); }
        .feature-icon { display: grid; width: 48px; height: 48px; margin-bottom: 15px; place-items: center; border-radius: 14px; background: #eaf6ef; font-size: 24px; }
        .feature-card h3 { font-size: 1.1rem; font-weight: 700; }
        .feature-card p { margin: 0; color: var(--muted); line-height: 1.65; }
        .price-card { overflow: hidden; padding: 0; }
        .price-card .table { margin: 0; }
        .price-card th, .price-card td { padding: 15px 18px; vertical-align: middle; }
        .event-card { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 24px; background: #edf7f1; border: 1px solid #d7ebdf; border-radius: 16px; }
        .event-card p { margin: 5px 0 0; color: var(--muted); }
        .gallery-item { display: block; width: 100%; padding: 0; overflow: hidden; border: 0; border-radius: 15px; background: #e2e9e5; box-shadow: 0 7px 20px rgba(20,45,34,.1); cursor: zoom-in; }
        .gallery-item img { display: block; width: 100%; height: clamp(190px, 24vw, 280px); object-fit: cover; transition: transform .35s ease; }
        .gallery-item:hover img, .gallery-item:focus-visible img { transform: scale(1.045); }
        .gallery-item:focus-visible { outline: 3px solid #73bd93; outline-offset: 3px; }
        .cta { padding: clamp(25px, 5vw, 42px); color: white; text-align: center; background: linear-gradient(125deg, #106944, #198a58); border-radius: 18px; }
        .cta h2 { font-weight: 720; }
        .cta p { margin: 8px 0 20px; color: rgba(255,255,255,.85); }
        .btn-success { background: var(--green); border-color: var(--green); }
        .btn-success:hover { background: var(--green-dark); border-color: var(--green-dark); }
        .page-footer { margin-top: 60px; padding: 25px 0; color: #d5e0da; background: #18302a; }
        .page-footer a { color: #d5e0da; }
        .modal-photo { display: block; max-width: 100%; max-height: 82vh; margin: auto; object-fit: contain; }
        @media (max-width: 575.98px) {
            .hero { border-radius: 14px; }
            .section { margin-top: 38px; }
            .event-card { align-items: flex-start; flex-direction: column; }
            .event-card .btn { width: 100%; }
            .gallery-item img { height: 220px; }
            .map-caption { align-items: stretch; flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; } }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm site-nav">
    <div class="container">
        <a class="brand navbar-brand" href="index.php"><span class="brand-mark" aria-hidden="true">🏓</span>Pickleball Trung Ngọc</a>
        <div class="d-flex align-items-center gap-2 gap-md-3 ms-auto">
            <a class="nav-link d-none d-md-inline" href="index.php">Trang chủ</a>
            <?php if ($userName !== null) { ?>
                <span class="welcome d-none d-lg-inline">Xin chào, <?= htmlspecialchars((string) $userName, ENT_QUOTES, 'UTF-8') ?></span>
                <?php if ($userRole === 'admin') { ?><a class="nav-link" href="admin/dashboar.php">Quản lý</a>
                <?php } elseif ($userRole === 'chusan') { ?><a class="nav-link" href="chusan/dashboard.php">Quản lý sân</a><?php } ?>
                <a class="nav-link" href="logout.php">Đăng xuất</a>
            <?php } else { ?>
                <a class="nav-link" href="login.php">Đăng nhập</a>
                <a class="btn btn-sm btn-outline-success" href="Sigin.php">Đăng ký</a>
            <?php } ?>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">
    <header class="hero">
        <div class="hero-content">
            <div class="hero-kicker">Điểm hẹn Pickleball tại Trà Vinh</div>
            <h1>Sân Pickleball Trung Ngọc</h1>
            <p>Không gian thể thao thân thiện, phù hợp cho người mới bắt đầu, gia đình và những trận đấu cùng bạn bè.</p>
            <a href="book.php" class="btn btn-success btn-lg mt-4">🏓 Đặt sân ngay</a>
        </div>
    </header>

    <section class="section" id="gioithieu">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-7">
                <div class="info-card">
                    <div class="section-heading"><h2>Về chúng tôi</h2><p>Một địa điểm để vận động, học chơi và kết nối cộng đồng.</p></div>
                    <p>Pickleball Trung Ngọc mang đến không gian chơi thể thao năng động, thân thiện cho nhiều độ tuổi và trình độ. Bạn có thể đến trải nghiệm cùng bạn bè, rèn luyện kỹ năng hoặc tham gia các hoạt động giao lưu tại sân.</p>
                    <div class="address-line"><span aria-hidden="true">📍</span><span>Đường Nguyễn Chí Thanh, Khóm 1, TP. Trà Vinh, Tỉnh Vĩnh Long</span></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3 h-100">
                    <div class="col-12"><article class="feature-card"><div class="feature-icon">🎯</div><h3>Vận động mỗi ngày</h3><p>Không gian chơi phù hợp để bắt đầu làm quen và duy trì thói quen vận động.</p></article></div>
                    <div class="col-12"><article class="feature-card"><div class="feature-icon">🤝</div><h3>Kết nối cộng đồng</h3><p>Cùng bạn bè giao lưu, học hỏi và tham gia các hoạt động Pickleball.</p></article></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="bando">
        <div class="section-heading"><h2>Vị trí &amp; chỉ đường</h2><p>Tìm Khu Liên Hợp Thể Thao Trung Ngọc trên bản đồ để mở chỉ đường đến sân.</p></div>
        <div class="map-card">
            <iframe class="map-frame" src="<?= htmlspecialchars($mapEmbedUrl, ENT_QUOTES, 'UTF-8') ?>" title="Bản đồ vị trí Sân Pickleball Trung Ngọc tại Khóm 1, Trà Vinh" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            <div class="map-caption">
                <div><strong>📍 Khu Liên Hợp Thể Thao Trung Ngọc</strong><p>Đường Nguyễn Chí Thanh, Khóm 1, Thành phố Trà Vinh</p></div>
                <a class="btn btn-success" href="<?= htmlspecialchars($mapUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Mở Google Maps &amp; chỉ đường ↗</a>
            </div>
        </div>
    </section>

    <section class="section" id="tienich">
        <div class="section-heading"><h2>Tiện ích nổi bật</h2><p>Mọi thứ cần thiết cho một buổi chơi thoải mái.</p></div>
        <div class="row g-3">
            <div class="col-md-4"><article class="feature-card"><div class="feature-icon">🏓</div><h3>Sân chơi trong nhà</h3><p>Khu vực sân có mái che, hỗ trợ chơi và tập luyện trong nhiều điều kiện thời tiết.</p></article></div>
            <div class="col-md-4"><article class="feature-card"><div class="feature-icon">🎒</div><h3>Dụng cụ chơi</h3><p>Liên hệ sân để hỏi tình trạng thuê vợt, bóng và phụ kiện trước khi đến.</p></article></div>
            <div class="col-md-4"><article class="feature-card"><div class="feature-icon">🌟</div><h3>Phù hợp nhiều trình độ</h3><p>Chào đón người mới chơi, nhóm bạn và người chơi muốn rèn luyện thường xuyên.</p></article></div>
        </div>
    </section>

    <section class="section" id="banggia">
        <div class="section-heading"><h2>Bảng giá tham khảo</h2><p>Vui lòng liên hệ sân để xác nhận giá và khung giờ còn trống.</p></div>
        <div class="price-card table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-success"><tr><th scope="col">Dịch vụ</th><th scope="col">Thời gian</th><th scope="col" class="text-end">Giá tham khảo</th></tr></thead>
                <tbody>
                    <tr><td>Thuê sân</td><td>07:00 – 17:00</td><td class="text-end">60.000đ / giờ</td></tr>
                    <tr><td>Thuê sân</td><td>17:00 – 22:00</td><td class="text-end">80.000đ / giờ</td></tr>
                    <tr><td>Khách vãng lai</td><td>Cả ngày</td><td class="text-end">30.000đ / người / buổi</td></tr>
                    <tr><td>Hội viên tháng</td><td>Theo tháng</td><td class="text-end">500.000đ / tháng</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="section" id="sukien">
        <div class="section-heading"><h2>Sự kiện &amp; giao lưu</h2><p>Theo dõi thông tin mới nhất từ sân.</p></div>
        <div class="event-card"><div><h3 class="h5 mb-0">Hoạt động Pickleball Trung Ngọc</h3><p>Lịch giao lưu và sự kiện được cập nhật trên Facebook.</p></div><a href="https://www.facebook.com/Pickleballtrungngoc" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Xem Facebook ↗</a></div>
    </section>

    <section class="section" id="hinhanh">
        <div class="section-heading"><h2>Hình ảnh sân</h2><p>Chọn một ảnh để xem ở kích thước lớn.</p></div>
        <div class="row g-3 g-lg-4">
            <?php foreach ($photos as $index => $photo) { ?>
                <div class="col-12 col-md-6">
                    <button type="button" class="gallery-item" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo="images/<?= htmlspecialchars($photo['file'], ENT_QUOTES, 'UTF-8') ?>" data-alt="<?= htmlspecialchars($photo['alt'], ENT_QUOTES, 'UTF-8') ?>" aria-label="Phóng to ảnh <?= $index + 1 ?>: <?= htmlspecialchars($photo['alt'], ENT_QUOTES, 'UTF-8') ?>">
                        <img src="images/<?= htmlspecialchars($photo['file'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($photo['alt'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                    </button>
                </div>
            <?php } ?>
        </div>
    </section>

    <section class="section" aria-label="Đặt sân">
        <div class="cta"><h2>Sẵn sàng ra sân?</h2><p>Xem lịch và chọn khung giờ phù hợp cho buổi chơi tiếp theo.</p><a href="book.php" class="btn btn-light btn-lg text-success fw-semibold">Xem lịch &amp; đặt sân</a></div>
    </section>
</main>

<footer class="page-footer"><div class="container d-flex flex-column flex-sm-row justify-content-between gap-2"><span>© <?= date('Y') ?> Pickleball Trung Ngọc</span><a href="index.php">Trang chủ</a></div></footer>

<div class="modal fade" id="photoModal" tabindex="-1" aria-label="Xem ảnh sân" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"><div class="modal-content bg-dark border-0">
        <div class="modal-header border-0"><p class="modal-title text-white mb-0" id="photoCaption"></p><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button></div>
        <div class="modal-body pt-0"><img class="modal-photo" id="largePhoto" src="" alt=""></div>
    </div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('photoModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const image = document.getElementById('largePhoto');
        const caption = document.getElementById('photoCaption');
        image.src = button.dataset.photo;
        image.alt = button.dataset.alt;
        caption.textContent = button.dataset.alt;
    });
    document.getElementById('photoModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('largePhoto').src = '';
    });
</script>
</body>
</html>
