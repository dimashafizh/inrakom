<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_inrakom");

$search = mysqli_real_escape_string($koneksi, $_POST['nama']);

$query = "SELECT * FROM manpower WHERE nama_karyawan LIKE '%" . $search . "%'";
$result = mysqli_query($koneksi, $query);

$response = array();
while ($row = mysqli_fetch_array($result)) {
    
    $response[] = array(
        "value" => $row['nama_karyawan'],
        "label" => $row['nama_karyawan'],
        "nik" => $row['nik'],
        "departemen" => $row['departemen'],
        "id_manpower" => $row['id_manpower']
    );
}

echo json_encode($response);
?>