<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
require_once 'config/database.php';

// ================= AMBIL ID =================
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=ID tidak valid");
    exit();
}

$id = (int) $_GET['id'];
$errors = [];

// ================= AMBIL DATA =================
$stmt = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

$data = $result->fetch_assoc();
$stmt->close();

// set default value (pre-fill)
$kode = $data['kode_kategori'];
$nama = $data['nama_kategori'];
$deskripsi = $data['deskripsi'];
$status = $data['status'];


// ================= PROSES UPDATE =================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil & sanitasi
    $kode = htmlspecialchars(trim($_POST['kode']));
    $nama = htmlspecialchars(trim($_POST['nama']));
    $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));
    $status = htmlspecialchars($_POST['status'] ?? 'Aktif');

    // ===== VALIDASI =====

    // Kode kategori
    if (empty($kode)) {
        $errors[] = "Kode kategori wajib diisi";
    } elseif (strlen($kode) < 4 || strlen($kode) > 10) {
        $errors[] = "Kode kategori harus 4-10 karakter";
    } elseif (strpos($kode, 'KAT-') !== 0) {
        $errors[] = "Kode harus diawali dengan 'KAT-'";
    }

    // Nama Kategori
    if (empty($nama)) {
        $errors[] = "Nama wajib diisi";
    } else {
        if (strlen($nama) < 3) {
            $errors[] = "Nama minimal 3 karakter";
        }
        if (strlen($nama) > 50) {
            $errors[] = "Nama maksimal 50 karakter";
        }
    }

    // Deskripsi
    if (!empty($deskripsi) && strlen($deskripsi) > 200) {
        $errors[] = "Deskripsi maksimal 200 karakter";
    }

    // Status
    if (!in_array($status, ['Aktif', 'Nonaktif'])) {
        $errors[] = "Status tidak valid";
    }

    // ===== CEK DUPLIKAT (exclude diri sendiri) =====
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id_kategori FROM kategori WHERE kode_kategori = ? AND id_kategori != ?");
        $stmt->bind_param("si", $kode, $id);
        $stmt->execute();
        $cek = $stmt->get_result();

        if ($cek->num_rows > 0) {
            $errors[] = "Kode sudah digunakan kategori lain";
        }

        $stmt->close();
    }

    // ===== UPDATE =====
    if (count($errors) == 0) {
        $stmt = $conn->prepare("UPDATE kategori SET kode_kategori=?, nama_kategori=?, deskripsi=?, status=? WHERE id_kategori=?");
        $stmt->bind_param("ssssi", $kode, $nama, $deskripsi, $status, $id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();

            header("Location: index.php?success=" . urlencode("Kategori berhasil diupdate"));
            exit();
        } else {
            $errors[] = "Gagal update: " . $stmt->error;
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
                    <h4>Edit Kategori</h4>
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

                        <!-- KODE -->
                        <div class="mb-3">
                            <label class="form-label">Kode *</label>
                            <input type="text" name="kode" class="form-control"
                                   value="<?= htmlspecialchars($kode) ?>" required>
                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">
                            <label class="form-label">Nama *</label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?= htmlspecialchars($nama) ?>" required>
                        </div>

                        <!-- DESKRIPSI -->
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($deskripsi) ?></textarea>
                        </div>

                        <!-- STATUS -->
                        <div class="mb-3">
                            <label class="form-label">Status *</label><br>

                            <input type="radio" name="status" value="Aktif"
                                <?= ($status == 'Aktif') ? 'checked' : '' ?>> Aktif

                            <input type="radio" name="status" value="Nonaktif"
                                <?= ($status == 'Nonaktif') ? 'checked' : '' ?>> Nonaktif
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-warning">Update</button>
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
