<?php 
	// Kết nối cơ sở dữ liệu
	include('Admin/config/config.php');
	
	// Kiểm tra giỏ hàng trong phiên
	if(isset($_SESSION['cart']))
	{
		// Xử lý khi giỏ hàng có dữ liệu
	}

	// Kiểm tra nếu người dùng đã đăng nhập
	if($_SESSION['dangnhapuser'])
	{
		$account_user = $_SESSION['dangnhapuser']; // Lấy tài khoản người dùng
		$id_user = $_SESSION['id_user']; // Lấy ID người dùng
	}

	// Lấy thông tin hóa đơn mới nhất của người dùng
	$sql_hoadon = "SELECT * FROM hoadon where hoadon.id_user = '".$id_user."' ORDER BY id_hoadon DESC LIMIT 1";
	$query_hoadon = mysqli_query($mysqli,$sql_hoadon);
	$row_hoadon = mysqli_fetch_array($query_hoadon);

	// Lấy thông tin địa chỉ giao hàng của người dùng
	$sql_user_shipping = mysqli_query($mysqli,"SELECT * from shipping where id_user='$id_user' limit 1");
	$row_user_shipping = mysqli_fetch_array($sql_user_shipping);

	// Xử lý thông tin thanh toán qua MoMo
	if(isset($_GET['partnerCode']))
	{
		$partnerCode = $_GET['partnerCode'];
		$orderId = $_GET['orderId'];
		$amount = $_GET['amount'];
		$orderInfo = $_GET['orderInfo'];
		$orderType = $_GET['orderType'];
		$transId = $_GET['transId'];
		$payType = $_GET['payType'];
		$code_hoadon = rand(0,9999); // Tạo mã hóa đơn ngẫu nhiên

		// Chèn thông tin thanh toán vào cơ sở dữ liệu
		$insert_momo = "INSERT INTO momo(partnerCode,orderId,amount,orderInfo,orderType,orderType,payType,code_cart) VALUES ('".$partnerCode."','".$orderId."','".$amount."','".$orderInfo."','".$orderType."','".$transId."','".$payType."','".$code_hoadon."')";
		$cart_query = mysqli_query($mysqli,$insert_momo);

		// Lưu thông tin chi tiết hóa đơn
		if($cart_query)
		{
			foreach($_SESSION['cart'] as $key => $value)
			{
				$id_sanpham = $value['id'];
				$soluong = $value['soluong'];
				// Chèn chi tiết từng sản phẩm vào hóa đơn
				$insert_chitiethoadon = "INSERT into chitiethoadon(code_hoadon,id_sanpham,soluong_sp) values('".$code_hoadon."','".$id_sanpham."','".$soluong."')";
				mysqli_query($mysqli,$insert_chitiethoadon);
			}
		}
	}
	// Xử lý thông tin thanh toán qua VNPAY
	elseif(isset($_GET['vnp_Amount']))
	{
		$vnp_Amount = $_GET['vnp_Amount'];
		$vnp_BankCode = $_GET['vnp_BankCode'];
		$vnp_BankTranNo = $_GET['vnp_BankTranNo'];
		$vnp_CardType = $_GET['vnp_CardType'];
		$vnp_OrderInfo = $_GET['vnp_OrderInfo'];
		$vnp_PayDate = $_GET['vnp_PayDate'];
		$vnp_TmnCode = $_GET['vnp_TmnCode'];
		$vnp_TransactionNo = $_GET['vnp_TransactionNo'];

		$code_cart = $_SESSION['code_hoadon'];

		// Chèn thông tin thanh toán VNPAY vào cơ sở dữ liệu
		$insert_vnpay = "INSERT INTO vnpay(vnp_amount,vnp_bankcode,vnp_banktranno,vnp_cardtype,vnp_orderinfo,vnp_paydate,vnp_tmncode,vnp_transactionno,id_hoadon) values ('".$vnp_Amount."','".$vnp_BankCode."','".$vnp_BankTranNo."','".$vnp_CardType."','".$vnp_OrderInfo."','".$vnp_PayDate."','".$vnp_TmnCode."','".$vnp_TransactionNo."','".$code_cart."')";
		$cart_query = mysqli_query($mysqli,$insert_vnpay);

		// Thông báo kết quả giao dịch
		if($cart_query)
		{
			echo'<h3 style="margin:0 auto;"><marquee style="color:green;border:1px solid green;padding: 5px 10px;border-radius: 10px; min-width:200px; margin-top:10px">Giao dịch thanh toán qua VNPAY thành công!</marquee></h3>';
		}
		else
		{
			echo'<h3 style="margin:0 auto;"><marquee style="color:red;border:1px solid red;padding: 5px 10px;border-radius: 10px; min-width:200px; margin-top:10px">Giao dịch thất bại!</marquee></h3>';
		}
	}

	// Xử lý khi người dùng hoàn tất giỏ hàng
	if(isset($_POST['done_cart']))
	{
		unset($_SESSION['cart']); // Xóa giỏ hàng trong phiên
		header('Location:product.php?quanly=thanks'); // Chuyển hướng đến trang cảm ơn
	}

?>

<!-- Giao diện hiển thị chi tiết giỏ hàng và thanh toán -->
<div class="row">
	<div class="col l-12 c-12">
		<div class="card__cart">
			<div class="card-cart-box">
				<div class="cart-cart-status">
					<!-- Hiển thị trạng thái bước trong quy trình mua hàng -->
					<div class="arrow-steps clearfix">
						<div class="step done"> <span> <a href="product.php?quanly=cart" >Cart</a></span> </div>
						<div class="step done"> <span><a href="product.php?quanly=vanchuyen" >Transport</a></span> </div>
						<div class="step done"> <span><a href="product.php?quanly=thanhtoan" >Payment</a><span> </div>
						<div class="step current"> <span><a href="product.php?quanly=chitietcart" >Detail</a><span> </div>
					</div>
				</div>

				<div class="card__cart__content">
					<div class="card-cart-box-content-item">
						<h2>Chi tiết đặt hàng hóa đơn: <?php echo $row_hoadon['id_hoadon'] ?></h2>
					</div>
					<div class="card-cart-box-content min-height-payment">
						<?php 
						if(isset($_SESSION['cart']))
						{
							$i = 0;
							$tongtien = 0;
							foreach ($_SESSION['cart'] as $cart_item)
							{
								$thanhtien = $cart_item['soluong']*$cart_item['giasanpham'];
								$tongtien+= $thanhtien; // Tính tổng tiền của giỏ hàng
								$i++;
						?>
							<div class="card-cart-box-content-item">
								<h1 class="card-cart-box-content-item-numberle"><?php echo $i ?></h1>
								<div class="card__cart__content__item__img">
									<img src="Admin/module/sanpham/uploads/<?php echo $cart_item['anhsanpham'] ?>" alt="" width="100px" height="100px" style="border-radius: 10px;">
								</div>
								<div class="card__cart__content__item__info">
									<h1><?php echo $cart_item['tensanpham'] ?></h1>
									<ul>
										<li>Giá: <?php echo number_format($cart_item['giasanpham'],0,',',',') ?> đ</li>
										<li>Số lượng: 
											<?php echo $cart_item['soluong'] ?>	
										</li>
										<li>Mã sản phẩm: <?php echo $cart_item['masanpham'] ?></li>
									</ul>
								</div>
							</div>	
						<?php 
							}	
						}	
						?>		
					</div>

					<div class="card-cart-box-content-item">
						<h2>Thanh toán: 
						<?php 
						// Hiển thị phương thức thanh toán
						switch ($row_hoadon['phuongthucthanhtoan']) {
							case 'tienmat':
								echo "Tiền mặt";
								break;
							case 'chuyenkhoan':
								echo "Chuyển khoản";
								break;
							case 'momo':
								echo "MoMo";
								break;
							case 'paypal':
								echo "Paypal";
								break;
							case 'vnpay':
								echo "VNPAY";
								break;
							
							default:
								break;
						}

						?>
						</h2>
					</div>

					<div class="card-cart-box-content-item">
						<h2>Giao đến:</h2>
					</div>

					<!-- Hiển thị thông tin giao hàng -->
					<div class="card-cart-box-content-item" style="display:flex; flex-direction: column;">
						<div class="card__dangky-box-form-group">
							<h3>Họ tên</h3>
							<input type="text" placeholder="Ngan Vo" class="input-form" name="name_shipping" required="" disabled value="<?php echo $row_user_shipping['name_shipping'] ?>">
							<span class="message-error"></span>
						</div>
						<div class="card__dangky-box-form-group">
							<h3>Số điện thoại</h3>
							<input type="text" placeholder="Ngan Vo" class="input-form" name="phone_shipping" required="" disabled value="<?php echo $row_user_shipping['phone_shipping'] ?>">
							<span class="message-error"></span>
						</div>
						<div class="card__dangky-box-form-group">
							<h3>Địa chỉ</h3>
							<input type="text" placeholder="Ngan Vo" class="input-form" name="address_shipping" required="" disabled value="<?php echo $row_user_shipping['address_shipping'] ?>">
							<span class="message-error"></span>
						</div>
						<div class="card__dangky-box-form-group">
							<h3>Ghi chú</h3>
							<?php 
							$sql_user_shipping = mysqli_query($mysqli,"SELECT * from shipping where id_user='$id_user' limit 1");
							$row_user_shipping = mysqli_fetch_array($sql_user_shipping);
							$count_row_user_shipping = mysqli_num_rows($sql_user_shipping);
							if($count_row_user_shipping>0)
							{
								?>
								<textarea rows="5" name="node_shipping" disabled class="input-form"><?php echo $row_user_shipping['node_shipping'] ?></textarea>
								<script>
								CKEDITOR.replace( 'noidung' );
								</script>
							<?php 
							}else{
								?>
								<textarea rows="5" name="node_shipping" class="input-form"></textarea>
								<script>
								CKEDITOR.replace( 'noidung' );
								</script>
							<?php 
							}
							?>
							<span class="message-error"></span>
						</div>
					</div>	

					<!-- Nút hoàn tất đơn hàng -->
					<div class="card-cart-box-thanhtoan">
						<div class="card-cart-box-thanhtoan-item">
							<div class="card-cart-box-thanhtoan-item-btn">
								<form action="" method="POST">
									<a href="#"><input type="submit" name="done_cart" value="Hoàn tất" class="btn-buy btn-success"></a>
								</form>
							</div>
						</div>	
					</div>

					<!-- Hiển thị logo -->
					<div class="card-cart-box-content-item">
						<img src="img\logo2.png" alt="" style="width: 250px;height: 200px;object-fit: cover; margin: 0 auto;">
					</div>

				</div>
			</div>
		<div>
	</div>
</div>
