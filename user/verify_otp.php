<?php
require_once "connection.php";
session_start();

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];
    $stmt = $conn->prepare("SELECT reset_otp, reset_expire FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['reset_otp'] == $otp && strtotime($user['reset_expire']) > time()) {
        // Xóa cache OTP sau khi dùng 1 lần
        $conn->prepare("UPDATE users SET reset_otp=NULL, reset_expire=NULL WHERE email=?")->execute([$email]);
        $_SESSION['verified_email'] = $email;
        unset($_SESSION['reset_email']);
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "OTP không hợp lệ hoặc đã hết hạn.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Xác thực OTP</title></head>
<body>
<h3>Nhập mã OTP đã gửi đến email của bạn</h3>
<form method="POST">
    <input type="text" name="otp" placeholder="Nhập mã OTP" required>
    <button type="submit" name="verify">Xác nhận</button>
</form>
<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
</body>
</html>
