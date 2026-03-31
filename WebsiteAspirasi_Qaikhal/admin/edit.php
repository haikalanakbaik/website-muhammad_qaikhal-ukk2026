<?php
include '../db.php';

if(!isset($_GET['id'])){
    die("ID tidak ditemukan");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$query = mysqli_query($conn, "
SELECT 
    i.*, 
    s.kelas,
    k.kat_kategori,
    a.status,
    a.feedback
FROM tb_input_aspirasi i
JOIN tb_siswa s ON i.nis = s.nis
JOIN tb_kategori k ON i.id_kategori = k.id_kategori
LEFT JOIN tb_aspirasi a ON i.id_pelaporan = a.id_pelaporan
WHERE i.id_pelaporan = '$id'
");

$data = mysqli_fetch_assoc($query);

if(!$data){
    die("Data tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Umpan Balik</title>
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="umpan-balik.php" class="active">Umpan Balik</a></li>
                <li><a href="histori.php">Histori</a></li>
            </ul>
        </div>

        <div class="edit-wrap">
            <p class="page-title" style="margin-bottom:1.25rem">Edit Umpan Balik</p>

            <form action="" method="POST">
                <input type="hidden" name="id_pelaporan" value="<?php echo $data['id_pelaporan']; ?>">

                <div class="form-row">
                    <label>NIS Siswa :</label>
                    <input type="text" name="nis" value="<?php echo $data['nis']; ?>" readonly>
                </div>

                <div class="form-row">
                    <label>Kelas :</label>
                    <input type="text" name="kelas" value="<?php echo $data['kelas']; ?>" readonly>
                </div>

                <div class="form-row">
                    <label>Lokasi :</label>
                    <input type="text" name="lokasi" value="<?php echo $data['lokasi']; ?>" readonly>
                </div>

                <div class="form-row">
                    <label>Keterangan :</label>
                    <textarea cols="30" rows="5" readonly><?php echo $data['ket']; ?></textarea>
                </div>

                <div class="form-row">
                    <label>Status :</label>
                    <select name="status">
                        <option value="Menunggu" <?php if($data['status'] == 'Menunggu') echo 'selected'; ?>>Menunggu</option>
                        <option value="Proses"   <?php if($data['status'] == 'Proses')   echo 'selected'; ?>>Proses</option>
                        <option value="Selesai"  <?php if($data['status'] == 'Selesai')  echo 'selected'; ?>>Selesai</option>
                    </select>
                </div>

                <div class="form-col">
                    <label>Umpan Balik :</label>
                    <textarea name="feedback" cols="20" rows="8"><?php echo $data['feedback']; ?></textarea>
                </div>

                <input type="submit" name="submit" value="Kirim" class="btn-submit">
            </form>

            <?php
            if(isset($_POST['submit'])){
                $status      = $_POST['status'];
                $feedback    = $_POST['feedback'];
                $id_pelaporan = $_POST['id_pelaporan'];
                
                $update = mysqli_query($conn,"UPDATE tb_aspirasi SET
                    status='$status',
                    feedback='$feedback',
                    tgl_aspirasi = NOW()
                    WHERE id_pelaporan='$id_pelaporan'");
                
                if($update){
                    echo "<script>alert('Data berhasil di update')</script>";
                    echo "<script>window.location='umpan-balik.php'</script>";
                } else {
                    echo mysqli_error($conn);
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
