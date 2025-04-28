<?php
// Kết nối cơ sở dữ liệu
include('../../config/config.php');

// Kiểm tra xem có truyền iduser qua GET không
if (isset($_GET['iduser'])) {
    $id_user = $_GET['iduser'];

    // Thực hiện câu lệnh DELETE để xóa người dùng
    $sql_xoa = "DELETE FROM users WHERE id_user = '$id_user' LIMIT 1";
    $result = mysqli_query($mysqli, $sql_xoa);

    // Kiểm tra kết quả và hiển thị thông báo
    if ($result) {
        // Xóa thành công, chuyển hướng về trang danh sách người dùng
        header('Location: ../../index.php?action=quanlyuser&query=them');
        exit();
    } else {
        // Hiển thị lỗi nếu không thể xóa
        echo "Lỗi: Không thể xóa người dùng! " . mysqli_error($mysqli);
    }
} else {
    echo "ID người dùng không hợp lệ!";
}
?>
