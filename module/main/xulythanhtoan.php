<?php 
    session_start();
    include('../../Admin/config/config.php'); // Kết nối cơ sở dữ liệu
    include('config_vnpay.php'); // Cấu hình VNPAY

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $now = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại

    $code_hoadon = rand(0,9999); // Tạo mã hóa đơn ngẫu nhiên
    $id_user = $_SESSION['id_user']; // ID người dùng từ session
    // Lấy thông tin người dùng
    $sql_user = "SELECT * FROM users where users.id_user = '".$id_user."' LIMIT 1";
    $query_user = mysqli_query($mysqli,$sql_user);
    $row_user = mysqli_fetch_array($query_user);
    $name_user = $row_user['name_user'];
    $phone_user = $row_user['phone_user'];
    $address_user = $row_user['address_user'];
    $payment = $_POST['payment']; // Phương thức thanh toán từ form

    // Lấy ID vận chuyển của người dùng
    $sql_get_iduser_shipping = mysqli_query($mysqli,"SELECT * from shipping where id_user='$id_user' limit 1");
    $row_get_iduser_shipping = mysqli_fetch_array($sql_get_iduser_shipping);
    $id_shipping = $row_get_iduser_shipping['id_shipping'];

    if(isset($_SESSION['cart'])){
        $tongtien = 0; // Tính tổng tiền giỏ hàng
        foreach ($_SESSION['cart'] as $cart_item) {
            $thanhtien = $cart_item['soluong'] * $cart_item['giasanpham'];
            $tongtien += $thanhtien;
        }
    }

    if(isset($_POST['redirect'])) {
        if($payment == 'tienmat') {
            // Thêm hóa đơn mới vào cơ sở dữ liệu
            $insert_hoadon = "INSERT into hoadon(id_user,name_user,phone_user,address_user,date_hoadon,status_hoadon,status_transport_hoadon,phuongthucthanhtoan,id_shipping) 
                              values ('".$id_user."','".$name_user."','".$phone_user."','".$address_user."','".$now."',1,4,'".$payment."','".$id_shipping."')";
            $hoadon_query = mysqli_query($mysqli,$insert_hoadon);

            if($hoadon_query) {
                // Thêm chi tiết hóa đơn
                foreach($_SESSION['cart'] as $key => $value) {
                    $id_sanpham = $value['id'];
                    $soluong = $value['soluong'];
                    // Lấy ID hóa đơn vừa tạo
                    $id_hoadon_select_id = "SELECT id_hoadon FROM hoadon ORDER BY id_hoadon DESC LIMIT 1";
                    $id_hoadon_query_id = mysqli_query($mysqli,$id_hoadon_select_id);
                    $id_hoadon_row = mysqli_fetch_array($id_hoadon_query_id);
                    $id_hoadon = $id_hoadon_row['id_hoadon'];

                    $insert_chitiethoadon = "INSERT into chitiethoadon(id_sanpham,soluong_sp,id_hoadon) 
                                             values ('".$id_sanpham."','".$soluong."','".$id_hoadon."')";
                    mysqli_query($mysqli,$insert_chitiethoadon);
                }
            }
            header('Location:../../product.php?quanly=chitietcart'); // Quay lại trang chi tiết giỏ hàng
        } elseif($payment == 'vnpay') {
            // Cấu hình thông tin giao dịch VNPAY
            $vnp_TxnRef = $code_hoadon;
            $vnp_OrderInfo = 'Thanh toán qua VNPAY cho đơn hàng';
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $tongtien * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            $vnp_ExpireDate = $expire;

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $vnp_ExpireDate 
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            ksort($inputData); // Sắp xếp tham số
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            $returnData = array('code' => '00', 'message' => 'success', 'data' => $vnp_Url);
            if (isset($_POST['redirect'])) {
                // Lưu hóa đơn và chi tiết vào cơ sở dữ liệu trước khi chuyển hướng
                $insert_hoadon = "INSERT into hoadon(id_user,name_user,phone_user,address_user,date_hoadon,status_hoadon,status_transport_hoadon,phuongthucthanhtoan,id_shipping) 
                                  values ('".$id_user."','".$name_user."','".$phone_user."','".$address_user."','".$now."',1,4,'".$payment."','".$id_shipping."')";
                $hoadon_query = mysqli_query($mysqli,$insert_hoadon);

                $id_hoadon_select_id = "SELECT id_hoadon FROM hoadon ORDER BY id_hoadon DESC LIMIT 1";
                $id_hoadon_query_id = mysqli_query($mysqli,$id_hoadon_select_id);
                $id_hoadon_row = mysqli_fetch_array($id_hoadon_query_id);
                $id_hoadon = $id_hoadon_row['id_hoadon'];

                $_SESSION['code_hoadon'] = $id_hoadon;

                if($hoadon_query) {
                    foreach($_SESSION['cart'] as $key => $value) {
                        $id_sanpham = $value['id'];
                        $soluong = $value['soluong'];
                        $insert_chitiethoadon = "INSERT into chitiethoadon(id_sanpham,soluong_sp,id_hoadon) 
                                                 values ('".$id_sanpham."','".$soluong."','".$id_hoadon."')";
                        mysqli_query($mysqli,$insert_chitiethoadon);
                    }
                }
                header('Location: ' . $vnp_Url); // Điều hướng tới URL thanh toán
                die();
            } else {
                echo json_encode($returnData);
            }
        }
    }
?>
