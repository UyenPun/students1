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

// Lấy năm từ yêu cầu AJAX
$selectedYear = $_GET['year'];

// Truy vấn cơ sở dữ liệu để lấy các lớp học phù hợp với năm được chọn
$sql = "SELECT lophoc.id, lophoc.tenLop
        FROM lophoc
        JOIN khoahoc ON khoahoc.id = lophoc.idKhoaHoc
        WHERE khoahoc.namBatDau = $selectedYear";
$result = $connection->query($sql);

$classes = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($classes);

$connection->close();
?>