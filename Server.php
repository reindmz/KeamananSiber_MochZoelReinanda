<?php

require('Inventaris.php'); // Memanggil file Inventaris.php yang sudah dibuat sebelumnya

// Menambahkan fungsi validasi token autentikasi
function validateAuthToken($token) {
    $validTokens = ["your_secure_token1", "your_secure_token2"]; // Daftar token valid
    return in_array($token, $validTokens);
}

// Menggunakan SoapServer untuk membuat web service
$server = new SoapServer('inventaris.wsdl'); // Ganti dengan file WSDL yang sesuai
$server->setClass('Inventaris'); // Menentukan kelas yang akan menangani request

// Middleware untuk memvalidasi setiap request
class SecureMiddleware {
    public function handleRequest() {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            throw new SoapFault("Unauthorized", "Token tidak ditemukan.");
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $token = str_replace("Bearer ", "", $authHeader);

        if (!validateAuthToken($token)) {
            throw new SoapFault("Unauthorized", "Token tidak valid.");
        }
    }
}

// Menangani request dengan validasi keamanan
try {
    $middleware = new SecureMiddleware();
    $middleware->handleRequest();
    $server->handle(); // Menangani request yang masuk
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
