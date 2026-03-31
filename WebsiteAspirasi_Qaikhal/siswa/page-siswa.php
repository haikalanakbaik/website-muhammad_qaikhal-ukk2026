<?php 
session_start();
if(!isset($_SESSION['status_login'])){
    echo '<script>window.location="index.php"</script>';
}
include '../db.php';
$nis_session = mysqli_real_escape_string($conn, $_SESSION['nis']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aspirasi Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/style.css"/>
</head>
<body>
    <header>
        <h1>Aspirasi Siswa</h1>
        <a href="../keluar.php" class="btn-logout">Logout</a>
    </header>

    <div class="siswa-layout">

        <!-- FORM PENGADUAN -->
        <div class="form-pengaduan">
            <form action="" method="POST">
                <h3>BUAT PENGADUAN</h3>

                <!-- NIS -->
                <div class="field">
                    <input type="text" name="nis" value="<?php echo $nis_session ?>" placeholder="Masukkan NIS" required readonly>
                </div>

                <!-- Kategori -->
                <div class="field">
                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <?php
                        $kategori = mysqli_query($conn, "SELECT * FROM tb_kategori ORDER BY id_kategori DESC");
                        while($r = mysqli_fetch_array($kategori)){ ?>
                        <option value="<?php echo $r['id_kategori'] ?>"><?php echo $r['kat_kategori'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Lokasi -->
                <div class="field">
                    <input type="text" name="lokasi" placeholder="Masukkan Lokasi" required>
                </div>

                <!-- Keterangan -->
                <div class="field">
                    <textarea name="ket" rows="5" placeholder="Masukkan Keterangan" required></textarea>
                </div>

                <button type="submit" name="kirim" class="btn-kirim">Kirim Pengaduan</button>
            </form>

            <?php
            if(isset($_POST['kirim'])){
                $nis      = $_POST['nis'];
                $kategori = $_POST['kategori'];
                $lokasi   = $_POST['lokasi'];
                $ket      = $_POST['ket'];

                $insert = mysqli_query($conn, "INSERT INTO tb_input_aspirasi VALUES (
                    null, '".$nis."', '".$kategori."', '".$lokasi."', '".$ket."', null
                )");
                    
                $id_pelaporan = mysqli_insert_id($conn);
                mysqli_query($conn, "INSERT INTO tb_aspirasi (id_aspirasi, id_pelaporan, status, feedback)
                    VALUES (null, '".$id_pelaporan."', 'menunggu', '')");

                if($insert){
                    echo '<script>alert("Tambah data berhasil")</script>';
                    echo '<script>window.location="page-siswa.php"</script>';
                } else {
                    echo 'Gagal: '.mysqli_error($conn);
                }
            }
            ?>
        </div>

        <!-- MAIN AREA -->
        <div class="siswa-main">

            <div class="stat-cards">
                <?php 
                $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_input_aspirasi WHERE nis='$nis_session'");
                $datacount = mysqli_fetch_assoc($count);
                ?>
                <div class="stat-card blue">
                    <h3>Total Aspirasi</h3>
                    <p class="stat-number"><?php echo $datacount['total'] ?></p>
                </div>

                <?php 
                $menunggu = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_aspirasi a JOIN tb_input_aspirasi i ON a.id_pelaporan = i.id_pelaporan WHERE a.status='menunggu' AND i.nis='$nis_session'");
                $dataMenunggu = mysqli_fetch_assoc($menunggu);
                ?>
                <div class="stat-card red">
                    <h3>Menunggu</h3>
                    <p class="stat-number"><?php echo $dataMenunggu['total'] ?></p>
                </div>

                <?php 
                $proses = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_aspirasi a JOIN tb_input_aspirasi i ON a.id_pelaporan = i.id_pelaporan WHERE a.status='proses' AND i.nis='$nis_session'");
                $dataMenunggu = mysqli_fetch_assoc($proses);
                ?>
                <div class="stat-card yellow">
                    <h3>Proses</h3>
                    <p class="stat-number"><?php echo $dataMenunggu['total'] ?></p>
                </div>

                <?php 
                $selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_aspirasi a JOIN tb_input_aspirasi i ON a.id_pelaporan = i.id_pelaporan WHERE a.status='selesai' AND i.nis='$nis_session'");
                $dataMenunggu = mysqli_fetch_assoc($selesai);
                ?>
                <div class="stat-card green">
                    <h3>Selesai</h3>
                    <p class="stat-number"><?php echo $dataMenunggu['total'] ?></p>
                </div>
            </div>

            <?php
            $query = mysqli_query($conn, "
                SELECT i.*, s.kelas
                FROM tb_input_aspirasi i
                LEFT JOIN tb_siswa s ON i.nis = s.nis
                WHERE i.nis = '$nis_session'
                ORDER BY i.id_pelaporan DESC
            ");
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
                    <span class="badge-kategori"><?= $datakategori['kat_kategori']; ?></span>
                    <span class="badge-status <?= $statusClass ?>"><?= $dataaspirasi['status']; ?></span>
                </div>
                <p class="card-nis">NIS : <?= $data["nis"] ?></p>
                <p class="card-kelas">KELAS : <?= $data["kelas"] ?></p>
                <p class="card-lokasi">Lokasi : <?= $data["lokasi"] ?></p>
                <div class="card-ket"><p><?= $data["ket"] ?></p></div>
                <div class="card-feedback">
                    <span>Umpan Balik</span> : <?= $dataaspirasi['feedback'] ?: 'Belum ada Umpan Balik'; ?>
                </div>
            </div>

            <?php } ?>
        </div>

    </div>
</body>
</html>
