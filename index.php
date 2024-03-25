<?php
session_start();
include 'connect.php';

// Số dòng dữ liệu trên mỗi trang
$rowsPerPage = 5;

// Xác định trang hiện tại
if (isset($_GET['page'])) {
  $currentPage = $_GET['page'];
} else {
  $currentPage = 1;
}

// Tính toán chỉ số hàng bắt đầu và kết thúc của trang hiện tại
$startRow = ($currentPage - 1) * $rowsPerPage;
$endRow = $startRow + $rowsPerPage - 1;

// Truy vấn dữ liệu với giới hạn số lượng dòng
$sql = "SELECT MaNV, TenNV, Phai, NoiSinh, MaPhong, Luong FROM nhanvien LIMIT $startRow, $rowsPerPage";
$result = mysqli_query($conn, $sql);

// Tính toán số trang
$sqlCount = "SELECT COUNT(*) AS total FROM nhanvien";
$resultCount = mysqli_query($conn, $sqlCount);
$rowCount = mysqli_fetch_assoc($resultCount);
$totalRows = $rowCount['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Thông tin nhân viên</title>
  <h1> Danh sách nhân viên </h1>
  
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }

    img {
      width: 50px;
    }

    .pagination {
      margin-top: 20px;
    }

    .pagination a {
      display: inline-block;
      padding: 8px 16px;
      text-decoration: none;
      color: #000;
      background-color: #f2f2f2;
      border: 1px solid #ddd;
    }

    .pagination a.active {
      background-color: #4CAF50;
      color: white;
    }
   
    .action-buttons {
    
    justify-content: space-between;
    
    }
    .action-buttons button.edit {
    background-color: #4CAF50;
    color: white;
    }

    .action-buttons button.delete {
    background-color: #f44336;
    color: white;
    }  
  </style>
</head>
<body>
    <?php
       if(isset($_SESSION["role"]) && $_SESSION["role"] == "ADMIN"){
        echo "<button class='Add'><a href='add.php'>Thêm nhân viên</a></button>";
    }
    ?>

  <table>
    <tr>
      <th>Mã nhân viên</th>
      <th>Tên nhân viên</th>
      <th>Phái</th>
      <th>Nơi sinh</th>
      <th>Mã phòng</th>
      <th>Lương</th>
      <th>Action</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['MaNV'] . "</td>";
        echo "<td>" . $row['TenNV'] . "</td>";
        if ($row['Phai'] == 'NAM') {
          echo '<td><img src="images/nam.jpg" alt="Nam"></td>';
        } elseif ($row['Phai'] == 'NU') {
          echo '<td><img src="images/nu.jpg" alt="Nữ"></td>';
        } else {
          echo "<td>" . $row['Phai'] . "</td>";
        }
        echo "<td>" . $row['NoiSinh'] . "</td>";
        echo "<td>" . $row['MaPhong'] . "</td>";
        echo "<td>" . $row['Luong'] . "</td>";
        if(isset($_SESSION["role"]) && $_SESSION["role"] == "ADMIN"){
            echo '<td class="action-buttons">';
            echo '<button class="edit" onclick="window.location.href=\'edit.php?MaNV=' . $row['MaNV'] . '\'">Edit</button>';
            
            echo '<button class="delete" onclick="window.location.href=\'delete.php?MaNV=' . $row['MaNV'] . '\'">Delete</button>';
            echo '</td>';
          }
        echo "</tr>";
      }
    }
    
    ?>
  </table>

  <div class="pagination">
    <?php
    // Hiển thị các liên kết phân trang
    for ($i = 1; $i <= $totalPages; $i++) {
      if ($i == $currentPage) {
        echo '<a class="active" href="?page=' . $i . '">' . $i . '</a>';
      } else {
        echo '<a href="?page=' . $i . '">' . $i . '</a>';
      }
    }
    ?>
  </div>

</body>
</html>

<?php
mysqli_close($conn);
?>