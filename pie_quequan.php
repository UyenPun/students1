<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "student1";

// Tạo kết nối
$connection = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Lọc dữ liệu theo miền Bắc
$sql_bac = "SELECT COUNT(*) AS bac_count FROM sinhvien WHERE queQuan IN ('Ha Noi', 'Hai Phong', 'Quang Ninh', ...)";
$sql_trung = "SELECT COUNT(*) AS bac_count FROM sinhvien WHERE queQuan IN ('Hoi An', 'Hải Phòng', 'Quảng Ninh', ...)";
$sql_nam = "SELECT COUNT(*) AS bac_count FROM sinhvien WHERE queQuan IN ('Hà Nội', 'Hải Phòng', 'Quảng Ninh', ...)";


// Câu truy vấn để đếm số lượng sinh viên theo các khu vực: bắc, trung và nam
$sql_bac = "SELECT COUNT(*) AS bac_count FROM sinhvien WHERE queQuan IN ('Ha Noi', 'Hai Phong', 'Quang Ninh')";
$sql_trung = "SELECT COUNT(*) AS trung_count FROM sinhvien WHERE queQuan IN ('Da Nang', 'Hoi An', 'Hue')";
$sql_nam = "SELECT COUNT(*) AS nam_count FROM sinhvien WHERE queQuan IN ('Can Tho', 'Da Lat', 'Nha Trang', 'Vung Tau', 'Phu Quoc')";


// $sql_bac = "SELECT COUNT(*) AS bac_count FROM sinhvien WHERE queQuan IN ('Ha Noi', 'Hai Phong', 'Quang Ninh','Lao Cai', 'Lai Chau', 'Yen Bai', 'Dien Bien','Son La','Hoa Binh','Ha Giang','Cao Bang','Bac Kan','Lang Son','Tuyen Quang','Thai Nguyen','Phu Tho','Bac Giang','Quang Ninh','Bac Ninh','Ha Nam','Hai Duong','Hung Yen','Nam Dinh', 'Thai Binh','Vinh Phuc')";

// $sql_trung = "SELECT COUNT(*) AS trung_count FROM sinhvien WHERE queQuan IN ('Nghe An', 'Thanh Hoa', 'Ha Tinh', 'Quang Binh', 'Quang Tri', 'Thua Thien Hue', 'Quang Nam', 'Quang Ngai', 'Binh Dinh', 'Phu Yen', 'Khanh Hoa', 'Ninh Thuan', 'Binh Thuan', 'Kon Tum', 'Gia Lai', 'Dak Lak', 'Dak Nong', 'Lam Dong', ' Da Nang')";

// $sql_nam = "SELECT COUNT(*) AS nam_count FROM sinhvien WHERE queQuan IN ('Binh Duong', 'Binh Phuoc', 'Tay Ninh', 'Ba Ria – Vung Tau', 'Dong Nai', 'Long An', 'Tien Giang', 'Ben Tre', 'Vinh Long', 'Tra Vinh', 'Dong Thap', 'Hau Giang', 'An Giang', 'Kien Giang', 'Bac Lieu', 'Soc Trang', 'Ca Mau')";

// Thực thi các câu truy vấn và lấy kết quả
$result_bac = $connection->query($sql_bac);
$result_trung = $connection->query($sql_trung);
$result_nam = $connection->query($sql_nam);

// Kiểm tra và lấy số lượng sinh viên theo từng khu vực
if ($result_bac->num_rows > 0) {
    $row_bac = $result_bac->fetch_assoc();
    $bacCount = $row_bac['bac_count'];
} else {
    $bacCount = 0;
}

if ($result_trung->num_rows > 0) {
    $row_trung = $result_trung->fetch_assoc();
    $trungCount = $row_trung['trung_count'];
} else {
    $trungCount = 0;
}

if ($result_nam->num_rows > 0) {
    $row_nam = $result_nam->fetch_assoc();
    $namCount = $row_nam['nam_count'];
} else {
    $namCount = 0;
}

// Tính tổng số sinh viên
$totalCount = $bacCount + $trungCount + $namCount;

// Đóng kết nối
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Phân phối quê quán</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
  }

  canvas {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  </style>
</head>

<body>
  <canvas id="quocGiaChart" width="400" height="400"></canvas>

  <script>
  // Lấy dữ liệu từ PHP
  var bacCount = <?php echo $bacCount; ?>;
  var trungCount = <?php echo $trungCount; ?>;
  var namCount = <?php echo $namCount; ?>;

  var ctx = document.getElementById('quocGiaChart').getContext('2d');
  var quocGiaChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Bắc', 'Trung', 'Nam'],
      datasets: [{
        label: 'Phân phối quê quán',
        data: [bacCount, trungCount, namCount],
        backgroundColor: [
          'rgba(255, 99, 132, 0.5)', // Màu cho khu vực bắc
          'rgba(54, 162, 235, 0.5)', // Màu cho khu vực trung
          'rgba(255, 206, 86, 0.5)' // Màu cho khu vực nam
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: false,
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Phân phối quê quán của sinh viên'
      }
    }
  });
  </script>
</body>

</html>