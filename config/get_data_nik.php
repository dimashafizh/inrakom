<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_inrakom");

$search = mysqli_real_escape_string($koneksi, $_POST['nomornik']);

$query = "SELECT * FROM manpower WHERE nik LIKE '%" . $search . "%'";
$result = mysqli_query($koneksi, $query);

$response = array();
while ($row = mysqli_fetch_array($result)) {
    
    $response[] = array(
        "value" => $row['nik'],
        "label" => $row['nik'],
        "nama_karyawan" => $row['nama_karyawan'],
        "departemen" => $row['departemen'],
        "id_manpower" => $row['id_manpower']
    );
}

echo json_encode($response);
?>