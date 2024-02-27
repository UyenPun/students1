<!DOCTYPE html>
<html>

<head>
  <title>Tính điểm thi đầu vào</title>
</head>

<body>
  <h2>Nhập điểm thi và điểm khu vực</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    Điểm thi: <input type="text" name="diem_thi"><br>
    Điểm khu vực: <input type="text" name="diem_khu_vuc"><br>
    <input type="submit" value="Tính điểm">
  </form>

  <?php
    // Kiểm tra xem có dữ liệu được gửi từ form chưa
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Lấy dữ liệu từ form
        $diem_thi = $_POST['diem_thi'];
        $diem_khu_vuc = $_POST['diem_khu_vuc'];

        // Kiểm tra xem dữ liệu đã được nhập đúng chưa
        if (!empty($diem_thi) && !empty($diem_khu_vuc)) {
            // Tính điểm thi đầu vào
            $diem_thi_dau_vao = $diem_thi + $diem_khu_vuc;
            echo "Điểm thi đầu vào của bạn là: " . $diem_thi_dau_vao;
        } else {
            echo "Vui lòng nhập đầy đủ thông tin";
        }
    }
    ?>
</body>

</html>