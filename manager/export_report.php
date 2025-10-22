<?php
session_start();
require "connection.php";

if (!isset($_SESSION["manager"])) {
    header("Location: login.php");
    exit;
}

include "header.php";
?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Báo cáo tổng hợp hệ thống</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row" style="min-height:500px">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <!-- PHẦN 1: DANH SÁCH THIẾT BỊ -->
                    <div class="x_title">
                        <h2>I. Danh sách thiết bị</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-bordered">
                            <thead style="background-color:#2a3f54; color:white;">
                                <tr>
                                    <th>Tên thiết bị</th>
                                    <th>Hình ảnh</th>
                                    <th>Tình trạng</th>
                                    <th>Số lượng</th>
                                    <th>Có sẵn</th>
                                    <th>Vị trí</th>
                                    <th>Nhân viên quản lý</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $result = $link->query("SELECT * FROM add_device");
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['device_name']) ?></td>
                                    <td><img src="<?= htmlspecialchars('../public_image/' . basename($row['device_image'])) ?>" width="100"></td>
                                    <td><?= htmlspecialchars($row['device_status']) ?></td>
                                    <td><?= htmlspecialchars($row['device_qty']) ?></td>
                                    <td><?= htmlspecialchars($row['available_qty']) ?></td>
                                    <td><?= htmlspecialchars($row['location']) ?></td>
                                    <td><?= htmlspecialchars($row['admin_username']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- PHẦN 2: DANH SÁCH NHÂN VIÊN -->
                    <div class="x_title">
                        <h2>II. Danh sách nhân viên</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-bordered">
                            <thead style="background-color:#2a3f54; color:white;">
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Email</th>
                                    <th>Liên hệ</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $result = $link->query("SELECT firstname, lastname, username, email, contact FROM admin_registration");
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['contact']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- PHẦN 3: DANH SÁCH NGƯỜI DÙNG -->
                    <div class="x_title">
                        <h2>III. Danh sách người dùng</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-bordered">
                            <thead style="background-color:#2a3f54; color:white;">
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Email</th>
                                    <th>Liên hệ</th>
                                    <th>Mã nhân viên</th>
                                    <th>Thiết bị đang mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = "
                            SELECT 
    COALESCE(MAX(i.user_name), CONCAT(u.firstname, ' ', u.lastname)) AS fullname,
    u.username,
    u.email,
    u.contact,
    u.enrollment AS employee_id,
    COALESCE(
        GROUP_CONCAT(
            CASE 
                WHEN i.status IN ('Đã duyệt mượn', 'Từ chối cho trả', 'Yêu cầu trả') 
                THEN i.device_name 
            END SEPARATOR ', '
        ),
        'Chưa mượn thiết bị nào'
    ) AS borrowed_devices
FROM user_registration u
LEFT JOIN issue_device i ON u.username = i.user_username
GROUP BY u.username, u.email, u.contact, u.enrollment;
                            ";
                            $result = $link->query($query);
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['contact']) ?></td>
                                    <td><?= htmlspecialchars($row['employee_id']) ?></td>
                                    <td><?= htmlspecialchars($row['borrowed_devices'] ?: "Không có") ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
