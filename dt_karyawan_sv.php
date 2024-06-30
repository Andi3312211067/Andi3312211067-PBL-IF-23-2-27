<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memeriksa apakah data gambar wajah ada
    if (!isset($_POST['face_image'])) {
        die('Face image data is missing');
    }

    // Mendapatkan data yang diperlukan dari formulir
    $id_karyawan = $_POST['id_karyawan'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];
    $tmp_tgl_lahir = $_POST['tmp_tgl_lahir'];
    $jenkel = $_POST['jenkel'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];
    $no_tel = $_POST['no_tel'];
    $jabatan = $_POST['jabatan'];
    $face_image = $_POST['face_image']; // Mendapatkan data gambar wajah dari formulir

    // Menyimpan data ke database
    include 'koneksi.php';
    $sql = "INSERT INTO tb_karyawan (id_karyawan, username, password, nama, tmp_tgl_lahir, jenkel, agama, alamat, no_tel, jabatan, face_image_path)
            VALUES ('$id_karyawan', '$username', '$password', '$nama', '$tmp_tgl_lahir', '$jenkel', '$agama', '$alamat', '$no_tel', '$jabatan', '')";

    if (mysqli_query($koneksi, $sql)) {
        // Simpan gambar wajah ke server
        $image_path = 'uploads/faces' . $id_karyawan . '.png'; // Path untuk menyimpan gambar wajah
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $face_image)); // Decode data gambar dari base64
        file_put_contents($image_path, $data); // Simpan gambar wajah ke server

        // Update path gambar wajah dalam database
        $update_sql = "UPDATE tb_karyawan SET face_image_path = '$image_path' WHERE id_karyawan = '$id_karyawan'";
        mysqli_query($koneksi, $update_sql);

        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
