<?php
require_once __DIR__ . '/config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$teknologi = $_POST['teknologi'];
$tautan = $_POST['tautan'];
$dibuatPada = $_POST['dibuat_pada'];
$stmt = $pdo->prepare(
'INSERT INTO projects (judul, deskripsi, teknologi, tautan, dibuat_pada)
VALUES (:judul, :deskripsi, :teknologi, :tautan, :dibuat_pada)'
);
$stmt->execute([
'judul' => $judul,
'deskripsi' => $deskripsi,
'teknologi' => $teknologi,
'tautan' => $tautan,
'dibuat_pada' => $dibuatPada,
]);
header('Location: dashboard-project.php');
exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Project</title>
</head>
<body>
<h1>Tambah Project Baru</h1>
<form method="post">
<label>Judul Project</label><br>
<input type="text" name="judul" required><br><br>
<label>Deskripsi</label><br>
<textarea name="deskripsi" rows="4"></textarea><br><br>
<label>Teknologi</label><br>
<input type="text" name="teknologi" placeholder="Contoh: PHP, MySQL"><br><br>
<label>Tautan (Link)</label><br>
<input type="url" name="tautan" placeholder="https://..."><br><br>
<label>Tanggal Dibuat</label><br>
<input type="date" name="dibuat_pada" required><br><br>
<button type="submit">Simpan Project</button>
</form>
<p><a href="dashboard-project.php">Kembali ke Dashboard</a></p>
</body>
</html>