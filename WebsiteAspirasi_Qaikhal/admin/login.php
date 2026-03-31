<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/style.css"/>
</head>
<body class="login-page">
    <div class="login-center">
        <div class="login-box">
            <h2>LOGIN ADMIN</h2>
            <form action="" method="post">
                <input type="text" name="user" placeholder="👤 Username">
                <input type="password" name="pass" placeholder="🔑 Password">
                <button type="submit" name="submit" class="btn-login">Login</button>
            </form>
            <a href="../index.php" class="link-back">← Kembali Ke Halaman Utama</a>
        </div>
    </div>
    <?php 
    if(isset($_POST['submit'])){
        session_start();
        include '../db.php';
        $user = mysqli_real_escape_string($conn, $_POST['user']);
        $pass = mysqli_real_escape_string($conn, $_POST['pass']);

        $cek = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '".$user."' AND password = '".MD5($pass)."'");
        if(mysqli_num_rows($cek) > 0){
            $d = mysqli_fetch_object($cek);
            $_SESSION['status_login'] = true;
            $_SESSION['a_global'] = $d;
            $_SESSION['id'] = $d->admin_id;
            echo '<script>window.location="dashboard.php"</script>';
        } else {
            echo '<script>alert("Login Gagal: Cek username dan password anda!")</script>';
        }
    }
    ?>
</body>
</html>
