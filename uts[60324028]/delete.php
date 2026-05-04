<?php
require_once 'config/database.php';

// ================= VALIDASI ID =================
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=" . urlencode("ID tidak valid"));
    exit();
}

$id = (int)$_GET['id'];

// ================= CEK DATA =================
$stmt = $conn->prepare("SELECT nama_kategori FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt->close();
    header("Location: index.php?error=" . urlencode("Data tidak ditemukan"));
    exit();
}

$data = $result->fetch_assoc();
$nama = $data['nama_kategori'];
$stmt->close();

// ================= DELETE =================
$stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $stmt->close();
        header("Location: index.php?success=" . urlencode("Kategori '$nama' berhasil dihapus"));
        exit();
    } else {
        $stmt->close();
        header("Location: index.php?error=" . urlencode("Gagal menghapus data"));
        exit();
    }
} else {
    $error = $stmt->error;
    $stmt->close();
    header("Location: index.php?error=" . urlencode("Error database: $error"));
    exit();
}
?>