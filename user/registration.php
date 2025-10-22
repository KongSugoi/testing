<?php
include "connection.php";
mysqli_set_charset($link, "utf8mb4");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit1"])) {

    // Làm sạch dữ liệu đầu vào
    $firstname = trim($_POST["firstname"]);
    $lastname  = trim($_POST["lastname"]);
    $username  = trim($_POST["username"]);
    $password  = $_POST["password"];
    $email     = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $contact   = trim($_POST["contact"]);

    // === Kiểm tra hợp lệ ===
    if (!preg_match("/^[A-Za-z0-9_]{1,15}$/", $username)) {
        $error = "Tên đăng nhập chỉ được chứa chữ, số, dấu gạch dưới (_) và tối đa 15 ký tự.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error = "Số điện thoại phải gồm 10 chữ số.";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{9,}$/", $password)) {
        $error = "Mật khẩu phải có ít nhất 9 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
    } else {
        // Kiểm tra username đã tồn tại chưa
        $stmt = $link->prepare("SELECT id FROM user_registration WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại.";
        } else {
            // ====== SINH MÃ NGƯỜI DÙNG ======
            $today = date("Ymd");
            $prefix = "USR" . $today;

            // Lấy số thứ tự trong ngày
            $query = $link->prepare("SELECT COUNT(*) FROM user_registration WHERE enrollment LIKE CONCAT(?, '%')");
            $query->bind_param("s", $prefix);
            $query->execute();
            $query->bind_result($count_today);
            $query->fetch();
            $query->close();

            $enrollment = $prefix . str_pad($count_today + 1, 3, "0", STR_PAD_LEFT);

            // Hash mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Thêm user vào CSDL
            $stmt = $link->prepare("
                INSERT INTO user_registration 
                (firstname, lastname, username, password, email, contact, enrollment, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'no')
            ");
            $stmt->bind_param("sssssss", 
                $firstname, $lastname, $username, $hashed_password, $email, $contact, $enrollment
            );

            if ($stmt->execute()) {
                $success = "🎉 Đăng ký thành công! Vui lòng đợi quản lý phê duyệt. 
                            Mã người dùng của bạn là: " . htmlspecialchars($enrollment);
            } else {
                $error = "Đã xảy ra lỗi khi tạo tài khoản. Vui lòng thử lại sau.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.min.css" rel="stylesheet">
</head>
<body class="login" style="margin-top: -20px;">

<div class="col-lg-12 text-center">
    <h1 style="font-family:Lucida Console">Hệ thống quản lý thiết bị</h1>
</div>

<div class="login_wrapper">
    <section class="login_content" style="margin-top: -40px;">
        <form method="post" autocomplete="off">
            <h2>Đăng ký tài khoản</h2><br>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger col-lg-8 col-lg-push-2"><?= htmlspecialchars($error) ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success col-lg-8 col-lg-push-2"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div><input type="text" class="form-control" placeholder="Họ" name="firstname" required></div>
            <div><input type="text" class="form-control" placeholder="Tên" name="lastname" required></div>
            <div><input type="text" class="form-control" placeholder="Tên đăng nhập (≤15 ký tự, a-z, A-Z, 0-9, _)" name="username" required></div>
            <div><input type="password" class="form-control" placeholder="Mật khẩu (≥9 ký tự, gồm chữ hoa, thường, số, ký tự đặc biệt)" name="password" required></div>
            <div><input type="email" class="form-control" placeholder="Email" name="email" required></div>
            <div><input type="text" class="form-control" placeholder="Số điện thoại" name="contact" required></div>

            <div class="col-lg-12 col-lg-push-3">
                <input class="btn btn-default submit" type="submit" name="submit1" value="Đăng ký">
            </div>

            <div class="separator">
                <p class="change_link">Đã có tài khoản?
                    <a href="login.php"> Đăng nhập </a>
                </p>
            </div>
        </form>
    </section>
</div>
</body>
</html>
