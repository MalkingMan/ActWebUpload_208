<?php
// Pesan status, awalnya kosong
$message = '';

// Cek jika form telah di-submit (metode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Cek jika ada file yang dipilih di dalam array $_FILES dan tidak ada error
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        
        $target_directory = "uploads/"; // Folder tujuan untuk menyimpan file
        
        // Pastikan nama file aman untuk digunakan sebagai nama file di server
        $target_file = $target_directory . basename($_FILES["fileToUpload"]["name"]);

        // Memeriksa apakah file adalah gambar asli menggunakan getimagesize
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            // Memindahkan file yang diunggah dari lokasi sementara ke folder tujuan ('uploads/')
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                // Jika berhasil, langsung arahkan (redirect) pengguna ke halaman daftar.php
                header("Location: daftar.php");
                exit(); // Penting: Hentikan eksekusi skrip setelah redirect
            } else {
                $message = "Maaf, terjadi kesalahan saat memindahkan file Anda.";
            }
        } else {
            $message = "File yang Anda pilih bukan gambar.";
        }
    } else {
        // Jika tidak ada file yang dipilih atau terjadi error lain
        $message = "Anda belum memilih file atau terjadi kesalahan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Unggah File</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-xl m-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-6">
            Unggah File
        </h1>

        <!-- Bagian ini akan menampilkan pesan error jika proses unggah gagal -->
        <?php if (!empty($message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <!-- Form HTML untuk unggah file. Perhatikan atribut action, method, dan enctype -->
        <form action="unggah.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="text-center">
                <label for="file-input" class="cursor-pointer inline-block text-center px-6 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                    Pilih File...
                </label>
                <!-- Atribut 'name' sangat penting agar bisa dibaca oleh PHP -->
                <input id="file-input" name="fileToUpload" type="file" class="hidden" accept="image/*">
            </div>

            <!-- JavaScript akan menampilkan nama file & pratinjau di sini -->
            <p id="file-name-display" class="text-center text-sm text-gray-500 h-5">Belum ada file yang dipilih</p>
            <div id="image-preview-container" class="w-full h-auto flex justify-center hidden">
                <img id="image-preview" src="#" alt="Pratinjau Gambar" class="max-h-60 rounded-lg shadow-md object-contain" />
            </div>

            <button type="submit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg border border-gray-300">
                Unggah
            </button>
        </form>
    </div>

    <script>
        // JavaScript ini hanya untuk menampilkan pratinjau di sisi klien, tidak ada perubahan
        const fileInput = document.getElementById('file-input');
        const fileNameDisplay = document.getElementById('file-name-display');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const imagePreview = document.getElementById('image-preview');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                fileNameDisplay.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
```

