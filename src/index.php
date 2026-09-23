<?php
session_start();

$userName = $_SESSION['user'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f6f8f7">
    <title>Sân Pickleball Trung Ngọc | Đặt sân tại Trà Vinh</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        :root { --green: #168454; --green-dark: #106944; --ink: #18302a; --muted: #65766f; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #f6f8f7; color: var(--ink); font-family: system-ui, -apple-system, "Segoe UI", sans-serif; }
        .site-nav { background: rgba(255,255,255,.96); border-bottom: 1px solid #e7eeea; }
        .brand { color: var(--ink); font-weight: 750; letter-spacing: -.03em; text-decoration: none; }
        .brand-mark { display: inline-grid; place-items: center; width: 37px; height: 37px; margin-right: 9px; border-radius: 12px; background: #e5f4ec; font-size: 20px; }
        .nav-link { color: #52635c; font-weight: 550; }
        .nav-link:hover { color: var(--green); }
        .welcome { color: var(--green-dark); font-size: .94rem; }
        .main-content { min-height: calc(100vh - 75px); padding: clamp(28px, 6vw, 76px) 16px; display: grid; place-items: center; }
        .venue-card { width: min(100%, 940px); overflow: hidden; background: #fff; border: 1px solid #e0e8e3; border-radius: 18px; box-shadow: 0 18px 55px rgba(27, 55, 43, .10); }
        .venue-photo { position: relative; height: clamp(240px, 43vw, 440px); background: #dce8e1; }
        .venue-photo img { width: 100%; height: 100%; object-fit: cover; object-position: center 55%; display: block; }
        .photo-label { position: absolute; left: 22px; bottom: 20px; padding: 8px 13px; color: #fff; background: rgba(18, 43, 33, .78); border: 1px solid rgba(255,255,255,.35); border-radius: 999px; font-size: .88rem; backdrop-filter: blur(8px); }
        .venue-body { padding: clamp(25px, 5vw, 46px) 22px; text-align: center; }
        .eyebrow { margin-bottom: 10px; color: var(--green); font-size: .78rem; font-weight: 750; letter-spacing: .14em; text-transform: uppercase; }
        h1 { margin: 0 0 12px; font-size: clamp(1.8rem, 4vw, 2.65rem); font-weight: 720; letter-spacing: -.045em; }
        .address { max-width: 660px; margin: 0 auto; color: var(--muted); font-size: clamp(1rem, 2.2vw, 1.2rem); line-height: 1.65; }
        .actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 12px; margin-top: 27px; }
        .actions .btn { min-width: 160px; padding: 12px 24px; border-radius: 10px; font-size: 1rem; font-weight: 650; }
        .btn-success { background: var(--green); border-color: var(--green); }
        .btn-success:hover { background: var(--green-dark); border-color: var(--green-dark); }
        .btn-outline-success { color: var(--green-dark); border-color: #acd4bf; }
        .btn-outline-success:hover { background: #edf7f1; color: var(--green-dark); border-color: #8fc5a8; }
        @media (max-width: 575.98px) {
            .main-content { padding: 24px 12px; }
            .venue-card { border-radius: 14px; }
            .venue-photo { height: 235px; }
            .photo-label { left: 13px; bottom: 13px; font-size: .8rem; }
            .actions { flex-direction: column; }
            .actions .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-sm site-nav">
        <div class="container">
            <a class="brand navbar-brand" href="index.php"><span class="brand-mark" aria-hidden="true">🏓</span>Pickleball Trung Ngọc</a>
            <div class="d-flex align-items-center gap-2 gap-md-3 ms-auto">
                <?php if ($userName !== null) { ?>
                    <span class="welcome d-none d-sm-inline">Xin chào, <?= htmlspecialchars((string) $userName, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php if ($userRole === 'admin') { ?>
                        <a class="nav-link" href="admin/dashboar.php">Quản lý</a>
                    <?php } elseif ($userRole === 'chusan') { ?>
                        <a class="nav-link" href="chusan/dashboard.php">Quản lý sân</a>
                    <?php } ?>
                    <a class="nav-link" href="logout.php">Đăng xuất</a>
                <?php } else { ?>
                    <a class="nav-link" href="login.php">Đăng nhập</a>
                    <a class="btn btn-sm btn-outline-success" href="Sigin.php">Đăng ký</a>
                <?php } ?>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <section class="venue-card" aria-labelledby="venue-title">
            <div class="venue-photo">
                <img src="images/Pi_1.png" alt="Không gian sân Pickleball Trung Ngọc">
                <span class="photo-label">Sân Pickleball tại Trà Vinh</span>
            </div>
            <div class="venue-body">
                <p class="eyebrow">Một điểm đến · Sẵn sàng cho trận đấu</p>
                <h1 id="venue-title">Sân Pickleball Trung Ngọc</h1>
                <p class="address">Đường Nguyễn Chí Thanh, Phường 5, TP. Trà Vinh, Tỉnh Vĩnh Long</p>
                <div class="actions">
                    <a href="about.php" class="btn btn-outline-success">Tìm hiểu thêm</a>
                    <a href="book.php" class="btn btn-success">Đặt sân</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
