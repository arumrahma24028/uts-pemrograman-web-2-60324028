<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php
require_once 'config/database.php';

// Query data kategori (prepared statement)
$query = "SELECT * FROM kategori ORDER BY id_kategori DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Kategori Buku</h2>
        <a href="create.php" class="btn btn-primary">Tambah Kategori</a>
    </div>

    <!-- Alert -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">

            <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="100">Kode</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th width="100">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($row = $result->fetch_assoc()):
                        $status = strtolower(trim($row['status']));
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><code><?= htmlspecialchars($row['kode_kategori']) ?></code></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>

                        <!-- Status -->
                        <td>
                            <span class="badge <?= $status == 'aktif' ? 'bg-success' : 'bg-danger' ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td>
                            <a href="edit.php?id=<?= $row['id_kategori'] ?>" 
                               class="btn btn-warning btn-sm">
                               Edit
                            </a>

                            <button onclick="confirmDelete(<?= $row['id_kategori'] ?>)" 
                                    class="btn btn-danger btn-sm">
                               Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php else: ?>
                <div class="alert alert-warning">
                    Belum ada data kategori
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>


<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus kategori ini?')) {
        window.location.href = 'delete.php?id=' + id;
    }
}
</script>

<!-- tombol close alert -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
