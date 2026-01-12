<?php
require("../config.php");
require("../include/auth_admin.php");

// Cập nhật role
if (isset($_POST['update_role'])) {
    $id = intval($_POST['user_id']);
    $role = $_POST['role'];

    // Không cho đổi role của admin đang đăng nhập
    if ($id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
        $stmt->bind_param("si", $role, $id);
        $stmt->execute();
    }
}
// Xóa user (không xóa admin)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $check = $conn->query("SELECT role FROM users WHERE id=$id")->fetch_assoc();

    if ($check && $check['role'] !== 'admin') {
        $conn->query("DELETE FROM users WHERE id=$id");
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý người dùng</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
<a href="dashboar.php" class="btn btn-secondary mb-3">
⬅ Trở về Dashboard
</a>
<h2 class="mb-3">Quản lý người dùng</h2>

<table class="table table-bordered text-center align-middle">
<thead class="table-success">
<tr>
    <th>ID</th>
    <th>Tên đăng nhập</th>
    <th>Email</th>
    <th>Quyền</th>
    <th>Thao tác</th>
</tr>
</thead>
<tbody>

<?php
$result = $conn->query("SELECT * FROM users ORDER BY id ASC");
while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>

    <td>
        <form method="POST" class="d-flex justify-content-center gap-2">
            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">

            <select name="role" class="form-select form-select-sm w-auto"
                <?= $row['id'] == $_SESSION['user_id'] ? 'disabled' : '' ?>>
                <option value="user" <?= $row['role']=='user'?'selected':'' ?>>User</option>
                <option value="chusan" <?= $row['role']=='chusan'?'selected':'' ?>>Chủ sân</option>
                <option value="admin" <?= $row['role']=='admin'?'selected':'' ?>>Admin</option>
            </select>

            <?php if ($row['id'] != $_SESSION['user_id']): ?>
                <button name="update_role" class="btn btn-sm btn-primary">Lưu</button>
            <?php endif; ?>
        </form>
    </td>

    <td>
        <?php if ($row['role'] !== 'admin'): ?>
            <a href="users.php?delete=<?= $row['id'] ?>"
               onclick="return confirm('Xóa người dùng này?')"
               class="btn btn-danger btn-sm">
               Xóa
            </a>
        <?php else: ?>
            <span class="text-muted">Không thể xóa</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

</body>
</html>
