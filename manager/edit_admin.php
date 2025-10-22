<?php
session_start();
if (!isset($_SESSION["manager"])) {
    ?>
    <script type="text/javascript">
        window.location="login.php";
    </script>
    <?php
    exit;
}

require_once "connection.php";
require_once "header.php";

$success = "";
$error = "";

// ===== LẤY DỮ LIỆU NGƯỜI DÙNG CẦN SỬA =====
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("<div class='alert alert-danger'>Thiếu hoặc sai ID người dùng.</div>");
}
$id = intval($_GET["id"]);

$stmt = $link->prepare("SELECT * FROM admin_registration WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<div class='alert alert-warning'>Không tìm thấy người dùng có ID này.</div>");
}

$user = $result->fetch_assoc();
$stmt->close();

// ===== XỬ LÝ CẬP NHẬT =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $status = $_POST['status'] ?? 'yes';

    if ($firstname === '' || $lastname === '' || $username === '' || $email === '') {
        $error = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
    } else {
        // Nếu người dùng nhập mật khẩu mới → cập nhật
        if ($password !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $link->prepare("UPDATE admin_registration 
                                    SET firstname=?, lastname=?, username=?, password=?, email=?, contact=?, status=? 
                                    WHERE id=?");
            $stmt->bind_param("sssssssi", $firstname, $lastname, $username, $hashedPassword, $email, $contact, $status, $id);
        } else {
            // Không đổi mật khẩu
            $stmt = $link->prepare("UPDATE admin_registration 
                                    SET firstname=?, lastname=?, username=?, email=?, contact=?, status=? 
                                    WHERE id=?");
            $stmt->bind_param("ssssssi", $firstname, $lastname, $username, $email, $contact, $status, $id);
        }

        if ($stmt->execute()) {
            $success = "✅ Cập nhật thông tin người dùng thành công!";
            // Lấy lại dữ liệu sau khi cập nhật
            $stmt->close();
            $stmt = $link->prepare("SELECT * FROM admin_registration WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "Lỗi SQL: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }
}
?>

<!-- page content area main -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Chỉnh sửa người dùng quản trị</h3>
            </div>                
        </div>

        <div class="clearfix"></div>
        <div class="row" style="min-height:500px">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Thông tin người dùng</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php elseif ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="post" class="form-horizontal form-label-left" autocomplete="off">

                            <div class="form-group">
                                <label class="control-label col-md-3">Họ:</label>
                                <div class="col-md-6">
                                    <input type="text" name="firstname" class="form-control" 
                                           value="<?= htmlspecialchars($user['firstname']) ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Tên:</label>
                                <div class="col-md-6">
                                    <input type="text" name="lastname" class="form-control"
                                           value="<?= htmlspecialchars($user['lastname']) ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Tên đăng nhập:</label>
                                <div class="col-md-6">
                                    <input type="text" name="username" class="form-control"
                                           value="<?= htmlspecialchars($user['username']) ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Mật khẩu mới (nếu muốn đổi):</label>
                                <div class="col-md-6">
                                    <input type="password" name="password" class="form-control" placeholder="Để trống nếu không đổi">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Email:</label>
                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control"
                                           value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Số điện thoại:</label>
                                <div class="col-md-6">
                                    <input type="text" name="contact" class="form-control"
                                           value="<?= htmlspecialchars($user['contact']) ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Trạng thái:</label>
                                <div class="col-md-6">
                                    <select name="status" class="form-control">
                                        <option value="yes" <?= $user['status'] === 'yes' ? 'selected' : '' ?>>Hoạt động</option>
                                        <option value="no" <?= $user['status'] === 'no' ? 'selected' : '' ?>>Tạm khóa</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-success">Cập nhật</button>
                                    <a href="list_admin.php" class="btn btn-secondary">Quay lại</a>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<?php include "footer.php"; ?>
