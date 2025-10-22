<?php
session_start();
require_once "connection.php";

// ====== TẠO CSRF TOKEN ======
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ====== GIỚI HẠN SỐ LẦN THỬ ======
if (!isset($_SESSION['fail_count'])) $_SESSION['fail_count'] = 0;
if ($_SESSION['fail_count'] >= 5) {
    die("Bạn đã nhập sai quá 5 lần. Vui lòng thử lại sau 10 phút.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ====== KIỂM TRA CSRF TOKEN ======
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die(" Yêu cầu không hợp lệ (CSRF).");
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);

    $sql = "SELECT * FROM user_registration WHERE username = ? AND email = ? AND contact = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $contact);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Xác minh thành công
        $_SESSION['reset_user'] = $username;
        $_SESSION['fail_count'] = 0; // reset đếm
        header("Location: reset_password.php");
        exit;
    } else {
        $_SESSION['fail_count']++;
        $error = "Thông tin không khớp. Vui lòng kiểm tra lại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .container { width: 400px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        button:hover { background: #0056b3; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <h2>🔐 Quên mật khẩu</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label>Tên đăng nhập</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Liên hệ</label>
        <input type="text" name="contact" required>

        <button type="submit">Xác nhận</button>
    </form>
</div>
</body>
</html>
