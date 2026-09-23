<?php
require "../config.php";
require "../include/auth_admin.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['admin_csrf'], $token)) {
        $_SESSION['admin_flash'] = ['type' => 'danger', 'text' => 'Phiên thao tác hết hạn. Vui lòng thử lại.'];
    } else {
        $id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $action = $_POST['action'] ?? '';
        if ($id && $action === 'role' && in_array($_POST['role'] ?? '', ['user', 'chusan', 'admin'], true)) {
            if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
                $_SESSION['admin_flash'] = ['type' => 'warning', 'text' => 'Không thể thay đổi quyền của tài khoản đang đăng nhập.'];
            } else {
                $role = $_POST['role'];
                $stmt = $conn->prepare('UPDATE users SET role = ? WHERE id = ?');
                $stmt->bind_param('si', $role, $id);
                $ok = $stmt->execute() && $stmt->affected_rows >= 0;
                $_SESSION['admin_flash'] = ['type' => $ok ? 'success' : 'danger', 'text' => $ok ? 'Đã cập nhật quyền người dùng.' : 'Không thể cập nhật quyền.'];
            }
        } elseif ($id && $action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role <> 'admin'");
            $stmt->bind_param('i', $id);
            $ok = $stmt->execute() && $stmt->affected_rows > 0;
            $_SESSION['admin_flash'] = ['type' => $ok ? 'success' : 'warning', 'text' => $ok ? 'Đã xóa người dùng.' : 'Không tìm thấy người dùng hoặc tài khoản quản trị không thể xóa.'];
        } else {
            $_SESSION['admin_flash'] = ['type' => 'warning', 'text' => 'Thao tác không hợp lệ.'];
        }
    }
    header('Location: users.php');
    exit;
}

$flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
$users = $conn->query('SELECT id, username, email, role, created_at FROM users ORDER BY id ASC');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý người dùng | Pickleball Trung Ngọc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f5; color: #193129; }
        .topbar { background: #fff; border-bottom: 1px solid #e4ebe7; }
        .topbar a { color: #193129; text-decoration: none; font-weight: 700; }
        .panel { overflow: hidden; background: #fff; border: 1px solid #e4ebe7; border-radius: 16px; }
        .table th, .table td { padding: 13px 14px; vertical-align: middle; }
    </style>
</head>
<body>
<nav class="navbar topbar"><div class="container"><a href="dashboar.php">🏓 Pickleball / Quản trị</a><a class="btn btn-outline-secondary btn-sm" href="../index.php">Về trang chủ</a></div></nav>
<main class="container py-4">
    <div class="mb-4"><a href="dashboar.php" class="text-success text-decoration-none">← Dashboard</a><h1 class="h2 fw-bold mt-2 mb-0">Quản lý người dùng</h1></div>
    <?php if ($flash) { ?><div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show" role="alert"><?= htmlspecialchars($flash['text'], ENT_QUOTES, 'UTF-8') ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button></div><?php } ?>
    <div class="panel table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-success"><tr><th>ID</th><th>Tên đăng nhập</th><th>Email</th><th>Ngày tạo</th><th>Quyền</th><th>Thao tác</th></tr></thead>
            <tbody>
            <?php if ($users && $users->num_rows > 0) { while ($user = $users->fetch_assoc()) { $isSelf = (int) $user['id'] === (int) ($_SESSION['user_id'] ?? 0); ?>
                <tr>
                    <td>#<?= (int) $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?><?= $isSelf ? ' <span class="badge text-bg-light">Bạn</span>' : '' ?></td>
                    <td><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= !empty($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : '—' ?></td>
                    <td>
                        <?php if ($isSelf) { ?><span class="badge text-bg-success">Quản trị viên</span><?php } else { ?>
                            <form method="post" class="d-flex gap-2">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                <input type="hidden" name="action" value="role">
                                <select name="role" class="form-select form-select-sm" aria-label="Quyền người dùng #<?= (int) $user['id'] ?>">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Người dùng</option>
                                    <option value="chusan" <?= $user['role'] === 'chusan' ? 'selected' : '' ?>>Chủ sân</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                                </select>
                                <button class="btn btn-sm btn-outline-success">Lưu</button>
                            </form>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($user['role'] === 'admin') { ?><span class="text-secondary">Tài khoản quản trị</span><?php } else { ?>
                            <form method="post" onsubmit="return confirm('Xóa người dùng <?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>?')">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button class="btn btn-sm btn-outline-danger">Xóa</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } } else { ?><tr><td colspan="6" class="text-center py-5 text-secondary">Chưa có người dùng.</td></tr><?php } ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
