<?php
// Mengimpor file Inventaris.php yang berisi class Inventaris
require_once 'Inventaris.php';

// Inisialisasi objek Inventaris
$inventaris = new Inventaris();

// Pesan umpan balik untuk user
$pesan = "";

// Fungsi untuk membuat token CSRF
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Fungsi untuk memvalidasi token CSRF
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Memulai sesi untuk token CSRF
session_start();
$csrfToken = generateCsrfToken();

// Memeriksa jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi token CSRF
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $pesan = "Akses tidak sah. Token CSRF tidak valid.";
    } else {
        // Mengambil data dari input form dengan validasi
        $productId = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
        $newStock = filter_var($_POST['new_stock'], FILTER_VALIDATE_INT);

        if ($productId === false || $newStock === false) {
            $pesan = "Input tidak valid. Harap masukkan data yang benar.";
        } else {
            // Memanggil fungsi notifikasiRestock untuk mengirim pemberitahuan ke sistem
            $hasil = $inventaris->notifikasiRestock($productId, $newStock);
            $pesan = $hasil ? "Stok berhasil diperbarui." : "Gagal memperbarui stok.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stok Inventaris</title>
</head>
<body>
    <h1>Form Update Stok</h1>

    <?php if (!empty($pesan)): ?>
        <p><?php echo htmlspecialchars($pesan); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="product_id">ID Produk:</label>
        <input type="number" name="product_id" id="product_id" required>
        <br>

        <label for="new_stock">Stok Baru:</label>
        <input type="number" name="new_stock" id="new_stock" required>
        <br>

        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
        <button type="submit">Update Stok</button>
    </form>
</body>
</html>
