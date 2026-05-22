<?php

session_start();
include 'koneksi.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = $_POST['nama'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $hp       = $_POST['hp'];

    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM users WHERE username='$username'"));

    if ($cek) {
        $error = "Username sudah digunakan! Coba yang lain.";
    } else {
        mysqli_query($koneksi, "
            INSERT INTO users (nama, username, email, password, hp, role)
            VALUES ('$nama', '$username', '$email', '$password', '$hp', 'user')
        ");

        header("Location: index.php?daftar=sukses");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SakuKost - Daftar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-logo">📝</div>
        <h2>Daftar Akun</h2>
        <p class="subtitle">Registrasi sebagai penyewa baru</p>

        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <input type="text"     name="nama"     placeholder="Nama Lengkap" required>
            <input type="text"     name="username" placeholder="Username" required>
            <input type="email"    name="email"    placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text"     name="hp"       placeholder="Nomor HP" required>
            <button type="submit">✅ Daftar Sekarang</button>
        </form>

        <p class="auth-footer">Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </div>
</div>

</body>
</html>
