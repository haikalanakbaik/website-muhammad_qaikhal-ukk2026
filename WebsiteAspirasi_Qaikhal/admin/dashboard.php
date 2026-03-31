<?php 
session_start();
if(!isset($_SESSION['status_login'])){
    echo '<script>window.location="index.php"</script>';
}
include '../db.php';
$query = mysqli_query($conn, "SELECT * FROM tb_siswa, tb_admin");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
    <header>
        <h1>Aspirasi Siswa</h1>
        <a href="keluar.php" class="btn-logout">Logout</a>
    </header>

    <div class="layout">
        <div class="sidebar">
            <ul>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="umpan-balik.php">Umpan Balik</a></li>
                <li><a href="histori.php">Histori</a></li>
            </ul>
        </div>

        <div class="main-content">
            <p class="page-title">Dashboard</p>
            <p class="page-subtitle">Selamat Datang <?php echo $_SESSION["username"]?>, Anda login sebagai admin</p>

            <div class="stat-cards">
                <?php 
                $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_input_aspirasi");
                $datacount = mysqli_fetch_assoc($count);
                ?>
                <div class="stat-card blue">
                    <h3>Total Aspirasi</h3>
                    <p class="stat-number"><?php echo $datacount['total'] ?></p>
                </div>

                <?php 
                $menunggu = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_aspirasi WHERE status='menunggu'");
                $dataMenunggu = mysqli_fetch_assoc($menunggu);
                ?>
                <div class="stat-card red">
                    <h3>Menunggu</h3>
                    <p class="stat-number"><?php echo $dataMenunggu['total'] ?></p>
                </div>

                <?php 
                $proses = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_aspirasi WHERE status='proses'");
                $dataMenunggu = mysqli_fetch_assoc($proses);
                ?>
                <div class="stat-card yellow">
                    <h3>Proses</h3>
                    <p class="stat-number"><?php echo $dataMenunggu['total'] ?></p>
                </div>

                <?php 
                $selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_aspirasi WHERE status='selesai'");
                $dataMenunggu = mysqli_fetch_assoc($selesai);
                ?>
                <div class="stat-card green">
                    <h3>Selesai</h3>
                    <p class="stat-number"><?php echo $dataMenunggu['total'] ?></p>
                </div>
            </div>

            <?php
            $search = $_GET['search'] ?? '';
            $status = $_GET['status'] ?? '';
            $tgl_awal = $_GET['tgl_awal'] ?? '';
            $tgl_akhir = $_GET['tgl_akhir'] ?? '';
            ?>

            <form method="GET" class="filter-form">
                <input type="text" name="search" placeholder="Cari NIS / lokasi..." value="<?php echo $search ?>">
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="menunggu" <?php if($status == 'menunggu') echo 'selected'; ?>>Menunggu</option>
                    <option value="proses"   <?php if($status == 'proses')   echo 'selected'; ?>>Proses</option>
                    <option value="selesai"  <?php if($status == 'selesai')  echo 'selected'; ?>>Selesai</option>
                </select>
                <input type="date" name="tgl_awal"  value="<?php echo $tgl_awal ?>">
                <input type="date" name="tgl_akhir" value="<?php echo $tgl_akhir ?>">
                <button type="submit" class="btn-filter">Filter</button>
            </form>

            <?php
            $sql = "
            SELECT 
                i.*, 
                s.kelas,
                k.kat_kategori,
                a.status,
                a.feedback,
                a.tgl_aspirasi
            FROM tb_input_aspirasi i
            LEFT JOIN tb_siswa s ON i.nis = s.nis
            LEFT JOIN tb_kategori k ON i.id_kategori = k.id_kategori
            LEFT JOIN tb_aspirasi a ON i.id_pelaporan = a.id_pelaporan
            WHERE 1=1
            ";

            if($search != ''){
                $sql .= " AND (i.nis LIKE '%$search%' OR i.lokasi LIKE '%$search%' OR i.ket LIKE '%$search%')";
            }
            if($status != ''){
                $sql .= " AND a.status = '$status'";
            }
            if($tgl_awal != '' && $tgl_akhir != ''){
                $sql .= " AND DATE(i.tgl_input) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
            }
            $sql .= " ORDER BY i.id_pelaporan DESC";

            $query = mysqli_query($conn, $sql);
            while($data = mysqli_fetch_assoc($query)) {

                $kategori   = mysqli_query($conn, "SELECT * FROM tb_kategori WHERE id_kategori = '".$data['id_kategori']."'");
                $datakategori = mysqli_fetch_assoc($kategori);

                $aspirasi   = mysqli_query($conn, "SELECT * FROM tb_aspirasi WHERE id_pelaporan = '".$data['id_pelaporan']."'");
                $dataaspirasi = mysqli_fetch_assoc($aspirasi);

                $statusVal = strtolower($dataaspirasi['status'] ?? '');
                $statusClass = in_array($statusVal, ['menunggu','proses','selesai']) ? $statusVal : 'default';
            ?>

            <div class="aspirasi-card">
                <div class="card-header">
                    <div class="card-header-left">
                        <span class="badge-kategori"><?= $datakategori['kat_kategori']; ?></span>
                        <span class="badge-tanggal"><?= date('d-m-Y H:i:s', strtotime($data['tgl_input'])); ?></span>
                    </div>
                    <span class="badge-status <?= $statusClass ?>"><?= $dataaspirasi['status']; ?></span>
                </div>
                <p class="card-nis">NIS : <?= $data["nis"] ?></p>
                <p class="card-kelas">KELAS : <?= $data["kelas"] ?></p>
                <p class="card-lokasi">Lokasi : <?= $data["lokasi"] ?></p>
                <div class="card-ket"><p><?= $data["ket"] ?></p></div>
                <div class="card-feedback">
                    <span>Umpan Balik</span> : <?= $dataaspirasi['feedback'] ?: 'Belum ada feedback'; ?>
                </div>
            </div>

            <?php } ?>
        </div>
    </div>
</body>
</html>
