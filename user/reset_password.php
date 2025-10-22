<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION['reset_user'])) {
    header("Location: forgot_password.php");
    exit;
}

// ====== TẠO CSRF TOKEN ======
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$username = $_SESSION['reset_user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ====== KIỂM TRA CSRF TOKEN ======
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("Yêu cầu không hợp lệ (CSRF).");
    }

    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    // ====== KIỂM TRA ĐỘ MẠNH MẬT KHẨU ======
    if (strlen($new_pass) < 8 || 
        !preg_match('/[A-Z]/', $new_pass) ||
        !preg_match('/[a-z]/', $new_pass) ||
        !preg_match('/[0-9]/', $new_pass)) {
        $error = "Mật khẩu phải có ít nhất 8 ký tự, gồm chữ hoa, chữ thường và số.";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "Mật khẩu nhập lại không khớp.";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        $sql = "UPDATE user_registration SET password = ?, status = 'no' WHERE username = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ss", $hashed_pass, $username);
        $stmt->execute();

        unset($_SESSION['reset_user']);
        unset($_SESSION['csrf_token']);

        echo "<script>alert('Đổi mật khẩu thành công! Tài khoản sẽ được kích hoạt lại bởi quản lý.'); window.location='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo mật khẩu mới</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { width: 400px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #28a745; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        button:hover { background: #218838; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <h2>🔑 Tạo mật khẩu mới</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label>Mật khẩu mới</label>
        <input type="password" name="new_password" required>

        <label>Nhập lại mật khẩu</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Đổi mật khẩu</button>
    </form>
</div>
</body>
</html>
