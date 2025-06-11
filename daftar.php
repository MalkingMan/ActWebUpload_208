<?php
// Bagian ini menangani permintaan untuk menghapus file
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['file'])) {
    // Membuat path yang aman ke file yang akan dihapus
    $file_to_delete = 'uploads/' . basename($_GET['file']);
    
    // Memeriksa apakah file benar-benar ada untuk keamanan, lalu menghapusnya
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
    }
    
    // Mengarahkan kembali ke halaman ini untuk me-refresh daftar
    // Ini mencegah file terhapus lagi jika halaman di-refresh
    header('Location: daftar.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar File</title>
    <!-- Memuat pustaka CSS dan Font -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
    </style>
</head>
<body class="bg-gray-50">

    <div class="container mx-auto max-w-4xl p-4 sm:p-6 lg:p-8">
        <!-- Header Halaman -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">File yang Telah Diunggah</h1>
            </div>
            <!-- Tombol untuk kembali ke halaman unggah -->
            <a href="unggah.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                + Unggah Lagi
            </a>
        </div>

        <!-- Kontainer Daftar File -->
        <div class="bg-white rounded-lg shadow-md">
            <ul id="file-list" class="divide-y divide-gray-200">
                <?php
                // Mulai logika PHP untuk menampilkan daftar
                $upload_dir = 'uploads/';
                
                // Cek apakah folder 'uploads' ada
                if (is_dir($upload_dir)) {
                    // Memindai isi direktori dan mengurutkan dari yang terbaru ke yang terlama
                    $files = array_diff(scandir($upload_dir, SCANDIR_SORT_DESCENDING), array('..', '.'));
                    
                    // Jika tidak ada file, tampilkan pesan
                    if (empty($files)) {
                        echo '<li class="p-8 text-center text-gray-500">Belum ada file yang diunggah.</li>';
                    } else {
                        // Looping untuk setiap file yang ditemukan
                        foreach ($files as $file) {
                            $file_path = $upload_dir . $file;
                            $file_size = filesize($file_path);

                            // Mengonversi ukuran file ke format yang mudah dibaca (KB atau MB)
                            $file_size_formatted = round($file_size / 1024, 2) . ' KB';
                            if ($file_size > 1024 * 1024) {
                                $file_size_formatted = round($file_size / (1024 * 1024), 2) . ' MB';
                            }
                ?>
                <!-- Template HTML yang akan di-generate untuk setiap file -->
                <li class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center min-w-0">
                        <!-- Tampilkan gambar mini -->
                        <img src="<?php echo $file_path; ?>" alt="Thumbnail" class="w-16 h-16 sm:w-20 sm:h-20 rounded-md object-cover bg-gray-200 flex-shrink-0">
                        <div class="ml-4 min-w-0">
                            <!-- Tampilkan nama file (dengan htmlspecialchars untuk keamanan) -->
                            <p class="font-semibold text-gray-900 truncate" title="<?php echo htmlspecialchars($file); ?>"><?php echo htmlspecialchars($file); ?></p>
                            <!-- Tampilkan ukuran file -->
                            <p class="text-sm text-gray-500"><?php echo $file_size_formatted; ?></p>
                        </div>
                    </div>
                    <!-- Tombol Aksi -->
                    <div class="flex items-center space-x-2 sm:space-x-3 ml-4">
                        <!-- Tombol Download -->
                        <a href="<?php echo $file_path; ?>" download class="p-2 text-gray-500 hover:text-blue-600 hover:bg-gray-100 rounded-full" title="Download">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        </a>
                        <!-- Tombol Hapus (dengan konfirmasi JavaScript) -->
                        <a href="?action=delete&file=<?php echo urlencode($file); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus file ini?');" class="delete-btn p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-full" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </a>
                    </div>
                </li>
                <?php
                        } // Akhir dari loop
                    }
                } else {
                    // Pesan error jika folder 'uploads' tidak ada
                    echo '<li class="p-8 text-center text-red-500 font-bold">Error: Folder "uploads" tidak ditemukan!</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
