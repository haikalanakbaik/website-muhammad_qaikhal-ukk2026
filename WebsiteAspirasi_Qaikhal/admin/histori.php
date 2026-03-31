<?php 
session_start();
if(!isset($_SESSION['status_login'])){
    echo '<script>window.location="index.php"</script>';
}
include '../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Aspirasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
    <header>
        <h1>Aspirasi Siswa</h1>
        <a href="../keluar.php" class="btn-logout">Logout</a>
    </header>

    <div class="layout">
        <div class="sidebar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="umpan-balik.php">Umpan Balik</a></li>
                <li><a href="histori.php" class="active">Histori</a></li>
            </ul>
        </div>

        <div class="main-content">
            <p class="page-title">Histori Aspirasi</p>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Lokasi</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Feedback</th>
                        <th>Waktu Update</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query = mysqli_query($conn,"
                    SELECT 
                        i.nis, s.kelas, i.lokasi, k.kat_kategori,
                        i.ket, a.status, a.feedback, a.tgl_aspirasi
                    FROM tb_aspirasi a
                    JOIN tb_input_aspirasi i ON a.id_pelaporan = i.id_pelaporan
                    JOIN tb_siswa s ON i.nis = s.nis
                    JOIN tb_kategori k ON i.id_kategori = k.id_kategori
                    ORDER BY a.tgl_aspirasi DESC
                ");

                $no = 1;
                while($data = mysqli_fetch_assoc($query)){
                ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $data['nis'] ?></td>
                    <td><?php echo $data['kelas'] ?></td>
                    <td><?php echo $data['lokasi'] ?></td>
                    <td><?php echo $data['kat_kategori'] ?></td>
                    <td><?php echo $data['ket'] ?></td>
                    <td><?php echo $data['status'] ?></td>
                    <td><?php echo $data['feedback'] ?: 'Belum Ada Balasan' ?></td>
                    <td><?php echo date('d-m-Y H:i:s', strtotime($data['tgl_aspirasi'])); ?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
