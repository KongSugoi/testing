<?php
session_start();
if (!isset($_SESSION["manager"])) {
    ?>
    <script type="text/javascript">
        window.location="login.php";
    </script>
    <?php
}
require_once "connection.php";
require_once "header.php";

// ====== XỬ LÝ THÊM ADMIN ======
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $status = $_POST['status'] ?? 'yes';

    if ($firstname === '' || $lastname === '' || $username === '' || $password === '' || $email === '') {
        $error = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $link->prepare("INSERT INTO admin_registration (firstname, lastname, username, password, email, contact, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssss", $firstname, $lastname, $username, $hashedPassword, $email, $contact, $status);
            if ($stmt->execute()) {
                $success = "✅ Thêm người dùng quản trị thành công!";
            } else {
                if ($link->errno === 1062) {
                    $error = "❌ Tên đăng nhập đã tồn tại.";
                } else {
                    $error = "Lỗi SQL: " . htmlspecialchars($stmt->error);
                }
            }
            $stmt->close();
        } else {
            $error = "Không thể chuẩn bị câu lệnh SQL.";
        }
    }
}
?>


<!-- page content area main -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Thêm người dùng quản trị</h3>
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
                                    <input type="text" name="firstname" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Tên:</label>
                                <div class="col-md-6">
                                    <input type="text" name="lastname" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Tên đăng nhập:</label>
                                <div class="col-md-6">
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Mật khẩu:</label>
                                <div class="col-md-6">
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Email:</label>
                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Số điện thoại:</label>
                                <div class="col-md-6">
                                    <input type="text" name="contact" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Trạng thái:</label>
                                <div class="col-md-6">
                                    <select name="status" class="form-control">
                                        <option value="yes">Hoạt động</option>
                                        <option value="no">Tạm khóa</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-success">Thêm người dùng</button>
                                    <a href="index.php" class="btn btn-secondary">Hủy</a>
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

<?php
include "footer.php";
?>
