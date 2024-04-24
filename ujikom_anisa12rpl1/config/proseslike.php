<?php
session_start();
include 'koneksi.php';
$fotoid = $_GET['fotoid'];
$userid = $_SESSION['userid'];

$ceksuka = mysqli_query($koneksi, "SELECT * FROM like_foto WHERE fotoid='$fotoid' AND userid='$userid'");

if (mysqli_num_rows($ceksuka) == 1) {
    while($row = mysqli_fetch_array($ceksuka)){
        $likeid = $row['likeid'];
        $query = mysqli_query($koneksi, "DELETE FROM like_foto WHERE likeid='$likeid'");
        echo "<script>
        location.href='../admin/index.php';
        </script>";
    }
}else{
    $tanggallike = date('Y-m-d');
    $query = mysqli_query($koneksi, "INSERT INTO like_foto VALUES('', '$fotoid','$userid','$tanggallike')");

echo "<script>
location.href='../admin/index.php';
</script>";
}



?>