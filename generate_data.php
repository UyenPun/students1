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

// Danh sách các quê quán
$addresses = array("Ha Noi", "Ho Chi Minh", "Da Nang", "Hue", "Can Tho", "Hai Phong", "Nha Trang", "Vung Tau", "Ha Noi", "Hai Phong", "Quang Ninh", "Lao Cai", "Lai Chau", "Yen Bai", "Dien Bien", "Son La", "Hoa Binh", "Ha Giang", "Cao Bang", "Bac Kan", "Lang Son", "Tuyen Quang", "Thai Nguyen", "Phu Tho", "Bac Giang", "Quang Ninh", "Bac Ninh", "Ha Nam", "Hai Duong", "Hung Yen", "Nam Dinh", "Thai Binh", "Vinh Phuc", "Nghe An", "Thanh Hoa", "Ha Tinh", "Quang Binh", "Quang Tri", "Thua Thien Hue", "Quang Nam", "Quang Ngai", "Binh Dinh", "Phu Yen", "Khanh Hoa", "Ninh Thuan", "Binh Thuan", "Kon Tum", "Gia Lai", "Dak Lak", "Dak Nong", "Lam Dong", " Da Nang", "Binh Duong", "Binh Phuoc", "Tay Ninh", "Ba Ria – Vung Tau", "Dong Nai", "Long An", "Tien Giang", "Ben Tre", "Vinh Long", "Tra Vinh", "Dong Thap", "Hau Giang", "An Giang", "Kien Giang", "Bac Lieu", "Soc Trang", "Ca Mau");

// Hàm để tạo tên ngẫu nhiên
function generateRandomName()
{
    $ten = array("Ngoc Ha", "Xuan Lan", "Huu Huy", "Manh Cuong", "Lan Huong", "Phuong Linh", "Ngoc Minh", "Kim Ngan", "Minh Tu", "Phuong Minh", "Phuong Anh", "Pham Hang", "Phuong Uyen", "My Linh", "Xuan Mai", "Thien Kim","Bao Chau","Phuong Oanh");
    return $ten[array_rand($ten)];
}


// Tạo thông tin khóa học, lớp, và sinh viên
for ($year = 2000; $year <= 2023; $year++) {
    // Thêm thông tin khóa học
    $sql_insert_khoa_hoc = "INSERT INTO khoahoc (namBatDau) VALUES ('$year')";
    $connection->query($sql_insert_khoa_hoc);

    // Lấy ID của khóa học vừa thêm
    $khoa_hoc_id = $connection->insert_id;

    // Số lớp trong mỗi khóa (từ 2 đến 5)
    $num_classes = rand(2, 5);

    for ($i = 1; $i <= $num_classes; $i++) {
        // Thêm thông tin lớp học
        $class_name = "Lớp " . chr(64 + $i);
        $sql_insert_lop_hoc = "INSERT INTO lophoc (idKhoaHoc, tenLop) VALUES ('$khoa_hoc_id', '$class_name')";
        $connection->query($sql_insert_lop_hoc);

        // Lấy ID của lớp học vừa thêm
        $lop_hoc_id = $connection->insert_id;

        // Số sinh viên trong mỗi lớp (từ 30 đến 50)
        $num_students = rand(30, 50);

        for ($j = 1; $j <= $num_students; $j++) {
            // Tạo thông tin sinh viên ngẫu nhiên
            $ten = generateRandomName();
            $idLopHoc = rand(1, 10);
            $ngay_sinh = date('Y-m-d', strtotime("-" . rand(18, 28) . " years", strtotime($year . "-01-01")));
            $gioi_tinh = rand(0, 1) ? 'Nam' : 'Nữ';
            $chieu_cao = rand(150, 190); // Chieu cao tu 1.5m den 1.9m
            $can_nang = rand(40, 90); // Can nang tu 40kg den 90kg
            $diem_thi = rand(0, 10); // Diem thi tu 0 den 10
            $diem_khu_vuc = rand(0, 2); // Diem khu vuc tu 0 den 2
            $diem_thi_dau_vao = $diem_thi + $diem_khu_vuc;

            // Chọn ngẫu nhiên một quê quán từ danh sách
            $que_quan = $addresses[array_rand($addresses)];

            // Thêm thông tin sinh viên
            $sql_insert_sinh_vien = "INSERT INTO sinhvien (idLopHoc, diemThi, diemKhuVuc, ten, ngaySinh, gioiTinh, chieuCao, canNang, queQuan, diemThiDauVao)
                                    VALUES ('$idLopHoc', '$diem_thi', '$diem_khu_vuc', '$ten', '$ngay_sinh', '$gioi_tinh', '$chieu_cao', '$can_nang', '$que_quan', '$diem_thi_dau_vao')";
            $connection->query($sql_insert_sinh_vien);
        }
    }
}

echo "Thêm thông tin khóa học, lớp, và sinh viên thành công!";
?>