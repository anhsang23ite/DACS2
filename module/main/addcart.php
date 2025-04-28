<?php 
	session_start();
	include('../../Admin/config/config.php');

	// Tăng số lượng sản phẩm trong giỏ hàng
	if(isset($_GET['cong']))
	{
		$id=$_GET['cong'];
		foreach ($_SESSION['cart'] as $cart_item)
		{
			// Nếu sản phẩm không phải là sản phẩm cần tăng số lượng
			if($cart_item['id']!=$id)
			{
				$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $cart_item['soluong'],'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
				$_SESSION['cart'] = $sp;
			}
			else
			{
				// Tăng số lượng sản phẩm lên 1, tối đa là 20
				$tangsoluong = $cart_item['soluong'] + 1;
				if ($cart_item['soluong'] <= 19) 
				{
					$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $tangsoluong,'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
				}
				else
				{
					// Nếu số lượng đã đạt tối đa thì giữ nguyên số lượng
					$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $cart_item['soluong'],'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
				}
				$_SESSION['cart'] = $sp;
			}
		}
		// Chuyển hướng về trang giỏ hàng sau khi cập nhật
		header('location:../../product.php?quanly=cart');
	}

	// Giảm số lượng sản phẩm trong giỏ hàng
	if(isset($_GET['tru']))
	{
		$id=$_GET['tru'];
		foreach ($_SESSION['cart'] as $cart_item)
		{
			// Nếu sản phẩm không phải là sản phẩm cần giảm số lượng
			if($cart_item['id']!=$id)
			{
				$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $cart_item['soluong'],'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
				$_SESSION['cart'] = $sp;
			}
			else
			{
				// Giảm số lượng sản phẩm xuống 1, nhưng không giảm dưới 1
				$tangsoluong = $cart_item['soluong'] - 1;
				if ($cart_item['soluong'] > 1) 
				{
					$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $tangsoluong,'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
				}
				else
				{
					// Nếu số lượng đã là 1 thì không giảm nữa
					$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $cart_item['soluong'],'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
				}
				$_SESSION['cart'] = $sp;
			}
		}
		// Chuyển hướng về trang giỏ hàng sau khi cập nhật
		header('location:../../product.php?quanly=cart');
	}	

	// Xóa sản phẩm khỏi giỏ hàng
	if(isset($_SESSION['cart']) && isset($_GET['xoa']))
	{
		$id = $_GET['xoa'];
		foreach ($_SESSION['cart'] as $cart_item)
		{
			// Nếu sản phẩm không phải là sản phẩm cần xóa
			if($cart_item['id']!=$id)
			{
				$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $cart_item['soluong'],'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
			}
			$_SESSION['cart'] = $sp;
			// Chuyển hướng về trang giỏ hàng sau khi xóa
			header('location:../../product.php?quanly=cart');
		}
	}

	// Xóa tất cả sản phẩm trong giỏ hàng
	if(isset($_GET['xoatatca']) && $_GET['xoatatca'] == 1)
	{
		// Xóa tất cả sản phẩm trong session
		unset($_SESSION['cart']);
		// Chuyển hướng về trang giỏ hàng sau khi xóa
		header('location:../../product.php?quanly=cart');
	}

	// Thêm sản phẩm vào giỏ hàng
	if(isset($_POST['themgiohang']))
	{
		$id = $_GET['idsanpham'];
		$soluong = 1;
		$sql = "SELECT * FROM sanpham where id_sanpham='".$id."' LIMIT 1";
		$query = mysqli_query($mysqli,$sql);
		$row = mysqli_fetch_array($query);
		if($row)
		{
			$new_sp = array(array('tensanpham'=>$row['title'],'id'=>$id,'giasanpham'=> $row['gia'],'soluong'=>$soluong,'anhsanpham'=>$row['img'],'masanpham'=>$row['masanpham']));
			if(isset($_SESSION['cart']))
			{
				$found = false;
				foreach ($_SESSION['cart'] as $cart_item) 
				{
					// Nếu sản phẩm đã có trong giỏ hàng thì tăng số lượng
					if($cart_item['id'] == $id)
					{
						$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $soluong+1,'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
						$found = true;
					}
					else
					{
						// Nếu sản phẩm chưa có trong giỏ hàng thì giữ nguyên
						$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $cart_item['soluong'],'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
					}
				}
				// Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới
				if($found == false)
				{
					$_SESSION['cart'] = array_merge($sp, $new_sp);
				}
				else
				{
					$_SESSION['cart'] = $sp;
				}
			}
			else
			{
				// Nếu giỏ hàng chưa có gì, tạo mới giỏ hàng
				$_SESSION['cart'] = $new_sp;
			}
		}

		// Chuyển hướng về trang sản phẩm sau khi thêm vào giỏ hàng
		echo header('location:../../product.php?quanly=danhmuc&id=all');
	}

	// Mua ngay
	if(isset($_POST['muangay']))
	{
		$id = $_GET['idsanpham'];
		$soluong = 1;
		$sql = "SELECT * FROM sanpham where id_sanpham='".$id."' LIMIT 1";
		$query = mysqli_query($mysqli,$sql);
		$row = mysqli_fetch_array($query);
		if($row)
		{
			$new_sp = array(array('tensanpham'=>$row['title'],'id'=>$id,'giasanpham'=> $row['gia'],'soluong'=>$soluong,'anhsanpham'=>$row['img'],'masanpham'=>$row['masanpham']));
			if(isset($_SESSION['cart']))
			{
				$found = false;
				foreach ($_SESSION['cart'] as $cart_item) 
				{
					// Nếu sản phẩm đã có trong giỏ hàng thì tăng số lượng
					if($cart_item['id'] == $id)
					{
						$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $soluong+1,'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
						$found = true;
					}
					else
					{
						// Nếu sản phẩm chưa có trong giỏ hàng thì giữ nguyên
						$sp[] = array('tensanpham' => $cart_item['tensanpham'],'id' => $cart_item['id'], 'giasanpham' => $cart_item['giasanpham'], 'soluong' => $cart_item['soluong'],'anhsanpham' => $cart_item['anhsanpham'], 'masanpham' => $cart_item['masanpham']);
					}
				}
				// Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới
				if($found == false)
				{
					$_SESSION['cart'] = array_merge($sp, $new_sp);
				}
				else
				{
					$_SESSION['cart'] = $sp;
				}
			}
			else
			{
				// Nếu giỏ hàng chưa có gì, tạo mới giỏ hàng
				$_SESSION['cart'] = $new_sp; 
			}
		}
		// Chuyển hướng về trang giỏ hàng sau khi mua ngay
		header('location:../../product.php?quanly=cart');
	}	
?>
