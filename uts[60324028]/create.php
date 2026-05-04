<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
require_once 'config/database.php';

$errors = [];
$kode = '';
$nama = '';
$deskripsi = '';
$status = 'Aktif';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil & sanitasi
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $status = isset($_POST['status']) ? $_POST['status'] : 'Aktif';

    // ================= VALIDASI =================

    // Kode kategori
    if (empty($kode)) {
        $errors[] = "Kode kategori wajib diisi";
    } elseif (strlen($kode) < 4 || strlen($kode) > 10) {
        $errors[] = "Kode kategori harus 4-10 karakter";
    } elseif (strpos($kode, 'KAT-') !== 0) {
        $errors[] = "Kode harus diawali dengan 'KAT-'";
    }

    // Nama kategori
    if (empty($nama)) {
        $errors[] = "Nama kategori wajib diisi";
    } elseif (strlen($nama) < 3) {
        $errors[] = "Nama minimal 3 karakter";
    } elseif (strlen($nama) > 50) {
        $errors[] = "Nama maksimal 50 karakter";
    }

    // Deskripsi
    if (!empty($deskripsi) && strlen($deskripsi) > 200) {
        $errors[] = "Deskripsi maksimal 200 karakter";
    }

    // Status
    if (!in_array($status, ['Aktif', 'Nonaktif'])) {
        $errors[] = "Status tidak valid";
    }

    // Cek duplikat kode
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ?");
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Kode kategori sudah digunakan";
        }

        $stmt->close();
    }

    // ================= INSERT =================
    if (count($errors) == 0) {
        $stmt = $conn->prepare("INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES (?, ?, ?, ?)");

        $stmt->bind_param("ssss", 
            $kode,
            $nama,
            $deskripsi,
            $status
        );

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();

            header("Location: index.php?success=" . urlencode("Kategori berhasil ditambahkan"));
            exit();
        } else {
            $errors[] = "Gagal menyimpan data: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Kategori Baru</h4>
                </div>
                <div class="card-body">

                    <!-- ERROR -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $e): ?>
                                    <li><?= htmlspecialchars($e) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <!-- Kode -->
                        <div class="mb-3">
                            <label class="form-label">Kode Kategori *</label>
                            <input type="text" name="kode" class="form-control"
                                   value="<?= htmlspecialchars($kode) ?>"
                                   placeholder="KAT-001" required>
                        </div>

                        <!-- Nama -->
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori *</label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?= htmlspecialchars($nama) ?>"
                                   required>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($deskripsi) ?></textarea>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label">Status *</label><br>

                            <input type="radio" name="status" value="Aktif"
                                <?= ($status == 'Aktif') ? 'checked' : '' ?>> Aktif

                            <input type="radio" name="status" value="Nonaktif"
                                <?= ($status == 'Nonaktif') ? 'checked' : '' ?>> Nonaktif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>