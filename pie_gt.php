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

// Câu truy vấn để đếm số lượng sinh viên nam và nữ
$sql_male_count = "SELECT COUNT(*) AS male_count FROM sinhvien WHERE gioiTinh = 'Nam'";
$sql_female_count = "SELECT COUNT(*) AS female_count FROM sinhvien WHERE gioiTinh = 'Nữ'";

// Thực thi câu truy vấn và lấy kết quả
$result_male_count = $connection->query($sql_male_count);
$result_female_count = $connection->query($sql_female_count);

// Kiểm tra và lấy số lượng sinh viên nam
if ($result_male_count->num_rows > 0) {
    $row_male_count = $result_male_count->fetch_assoc();
    $maleCount = $row_male_count['male_count'];
} else {
    $maleCount = 0;
}

// Kiểm tra và lấy số lượng sinh viên nữ
if ($result_female_count->num_rows > 0) {
    $row_female_count = $result_female_count->fetch_assoc();
    $femaleCount = $row_female_count['female_count'];
} else {
    $femaleCount = 0;
}

// Đóng kết nối
$connection->close();
?>







<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Phân phối giới tính</title>
  <style>
  #genderChart {
    margin: 0 auto;
  }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <canvas id="genderChart" width="400" height="400"></canvas>

  <script>
  // Lấy dữ liệu từ PHP
  var maleCount = <?php echo $maleCount; ?>;
  var femaleCount = <?php echo $femaleCount; ?>;

  var ctx = document.getElementById('genderChart').getContext('2d');
  var genderChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Nam', 'Nữ'],
      datasets: [{
        label: 'Phân phối giới tính',
        data: [maleCount, femaleCount],
        backgroundColor: [
          'rgba(54, 162, 235, 0.5)', // Màu cho giới tính nam
          'rgba(255, 99, 132, 0.5)' // Màu cho giới tính nữ
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
        text: 'Phân phối giới tính của sinh viên'
      }
    }
  });
  </script>
</body>

</html>