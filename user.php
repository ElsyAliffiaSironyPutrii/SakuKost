<?php

session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

$pesan_bayar = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kirim_bayar'])) {
    $bulan   = $_POST['bulan'];
    $jumlah  = $_POST['jumlah'];
    $catatan = $_POST['catatan'];

    mysqli_query($koneksi, "
        INSERT INTO pembayaran (user_id, bulan, jumlah, catatan, status)
        VALUES ($user_id, '$bulan', '$jumlah', '$catatan', 'menunggu')
    ");

    $pesan_bayar = "✅ Bukti pembayaran berhasil dikirim! Tunggu konfirmasi admin.";
    header("Location: user.php?hal=bayar&pesan=sukses");
    exit();
}

$pesan_laporan = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kirim_laporan'])) {
    $fasilitas = $_POST['fasilitas'];
    $lokasi    = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    mysqli_query($koneksi, "
        INSERT INTO laporan (user_id, fasilitas, lokasi, deskripsi)
        VALUES ($user_id, '$fasilitas', '$lokasi', '$deskripsi')
    ");

    header("Location: user.php?hal=lapor&pesan=sukses");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan_profil'])) {
    $nama  = $_POST['nama'];
    $email = $_POST['email'];
    $hp    = $_POST['hp'];

    mysqli_query($koneksi, "UPDATE users SET nama='$nama', email='$email', hp='$hp' WHERE id=$user_id");

    $_SESSION['nama']  = $nama;
    $_SESSION['email'] = $email;
    $_SESSION['hp']    = $hp;

    header("Location: user.php?hal=profil&pesan=sukses");
    exit();
}

$info_kamar = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT u.*, k.tipe, k.harga
    FROM users u
    LEFT JOIN kamar k ON u.kamar = k.no_kamar
    WHERE u.id = $user_id
"));

$riwayat_bayar = mysqli_query($koneksi, "
    SELECT * FROM pembayaran
    WHERE user_id = $user_id
    ORDER BY tanggal DESC
");

$hal_aktif = isset($_GET['hal']) ? $_GET['hal'] : 'beranda';
$pesan_sukses = isset($_GET['pesan']) && $_GET['pesan'] == 'sukses';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SakuKost - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-layout">

    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">🏠</div>
            <h3>SakuKost</h3>
            <p>Panel Penghuni</p>
        </div>

        <div class="sidebar-user">
            <div class="avatar">U</div>
            <div class="info">
                <div class="nama"><?= $_SESSION['nama'] ?></div>
                <div class="role">Kamar <?= $_SESSION['kamar'] ?></div>
            </div>
        </div>
