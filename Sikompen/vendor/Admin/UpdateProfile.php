<?php
require_once '../Config.php';

// Inisialisasi variabel pesan dan referrer
$message = '';
$messageType = '';
$referrer = isset($_GET['ref']) ? $_GET['ref'] : 'dashboard/dashboard.php';
$referrer = filter_var($referrer, FILTER_SANITIZE_URL);

// Validasi referrer yang diizinkan
// Validasi referrer yang diizinkan
$allowedPaths = [
    '/Sikompen/vendor/Admin/user/user.php',
    '/Sikompen/vendor/Admin/bertugas/bertugas.php',
    '/Sikompen/vendor/Admin/dashboard/dashboard.php',
    '/Sikompen/vendor/Admin/pembayaran/historypembayaran.php',
    '/Sikompen/vendor/Admin/mahasiswa/mahasiswa.php',
    '/Sikompen/vendor/Admin/pekerjaan/pekerjaan.php'
];

if (!in_array($referrer, $allowedPaths)) {
    $referrer = 'dashboard/dashboard.php';
}

// Fungsi untuk mendapatkan data pengguna
function getUserData($nip)
{
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM tbl_USER WHERE NIP = :nip");
        $stmt->execute(['nip' => $nip]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            error_log("Error: Data pengguna dengan NIP $nip tidak ditemukan.");
        }

        // Normalize TTD path if it exists
        if (!empty($data['TTD'])) {
            // Remove any potential double slashes and ensure correct relative path
            $data['TTD'] = str_replace('//', '/', $data['TTD']);

            // Handle both possible path formats
            if (strpos($data['TTD'], '../Uploads_ttd/') === 0) {
                $data['TTD'] = $data['TTD'];
            } else if (strpos($data['TTD'], '../Uploads_ttd/') !== 0) {
                $data['TTD'] = '../Uploads_ttd/' . basename($data['TTD']);
            }
        }

        return $data;
    } catch (PDOException $e) {
        error_log("Error fetching user data: " . $e->getMessage());
        return false;
    }
}

// Ambil NIP aktif dari session
$activeNip = $_SESSION['nip'] ?? '12345';
$userData = getUserData($activeNip);

if (!$userData) {
    die("Error: Data pengguna dengan NIP $activeNip tidak ditemukan.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validasi input
        if (empty($nama) || empty($email)) {
            throw new Exception("Nama dan Email harus diisi");
        }

        $pdo = getDB();
        $updateFields = [];
        $params = [
            'nip' => $userData['NIP'],
            'nama' => $nama,
            'email' => $email
        ];

        // Base query
        $updateFields[] = "NAMA_USER = :nama";
        $updateFields[] = "EMAIL = :email";

        // Handle TTD upload
        if (!empty($_FILES['ttd']['name'])) {
            $targetDir = "../Uploads_ttd/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = uniqid() . "_" . basename($_FILES["ttd"]["name"]);
            $targetFile = $targetDir . $fileName;
            $relativePath = "../Uploads_ttd/" . $fileName;

            // Check if image file is actual image
            $check = getimagesize($_FILES["ttd"]["tmp_name"]);
            if ($check === false) {
                throw new Exception("File is not an image.");
            }

            // Check file size (max 5MB)
            if ($_FILES["ttd"]["size"] > 5000000) {
                throw new Exception("File is too large. Max size is 5MB.");
            }

            // Allow certain file formats
            $allowedTypes = array('jpg', 'jpeg', 'png');
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (!in_array($imageFileType, $allowedTypes)) {
                throw new Exception("Only JPG, JPEG & PNG files are allowed.");
            }

            if (move_uploaded_file($_FILES["ttd"]["tmp_name"], $targetFile)) {
                $updateFields[] = "TTD = :ttd";
                $params[':ttd'] = $relativePath;

                // Hapus file TTD lama jika ada
                if (!empty($userData['TTD']) && file_exists($userData['TTD'])) {
                    unlink($userData['TTD']);
                }
            } else {
                throw new Exception("Failed to upload file.");
            }
        }

        // Jika user ingin mengubah password
        if (!empty($current_password)) {
            if (!password_verify($current_password, $userData['PASSWORD'])) {
                throw new Exception("Password saat ini tidak sesuai");
            }

            if (empty($new_password)) {
                throw new Exception("Password baru harus diisi");
            }

            if ($new_password !== $confirm_password) {
                throw new Exception("Konfirmasi password tidak sesuai");
            }

            $updateFields[] = "PASSWORD = :password";
            $params['password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        // Build and execute update query
        $query = "UPDATE tbl_USER SET " . implode(", ", $updateFields) . " WHERE NIP = :nip";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        $message = "Profile berhasil diperbarui!";
        $messageType = "success";

        // Refresh user data
        $userData = getUserData($userData['NIP']);

        // Redirect ke halaman sesuai referrer
        header("Location: " . $referrer);
        exit;

    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="icon" type="image/x-icon" href="../images/LogoPNJ.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <img src="images/LogoPNJ.png" alt="Logo" class="h-12 w-12">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Update Profile</h1>
                        <p class="text-sm text-gray-600">Update Your Profile Information</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <?php if (!empty($message)): ?>
                <div
                    class="mb-4 p-4 rounded <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6" enctype="multipart/form-data">
                <!-- NIP (Readonly) -->
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-id-card mr-2"></i>NIP
                    </label>
                    <input type="text" id="nip" value="<?php echo htmlspecialchars($userData['NIP'] ?? ''); ?>"
                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-4 py-2" readonly>
                </div>

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-user mr-2"></i>Nama
                    </label>
                    <input type="text" id="nama" name="nama"
                        value="<?php echo htmlspecialchars($userData['NAMA_USER'] ?? ''); ?>"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($userData['EMAIL'] ?? ''); ?>"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2">
                </div>

                <!-- TTD -->
                <div>
                    <label for="ttd" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-signature mr-2"></i>Tanda Tangan (TTD)
                    </label>
                    <?php if (!empty($userData['TTD'])): ?>
                        <div class="mb-2">
                            <div class="border p-2 rounded-lg mb-2">
                                <?php if (file_exists($userData['TTD'])): ?>
                                    <img src="<?php echo htmlspecialchars($userData['TTD']); ?>" alt="Current TTD"
                                        class="w-32 h-auto"
                                        onerror="this.onerror=null; this.parentElement.innerHTML='<p class=\'text-red-500\'>Gambar tidak dapat ditampilkan</p>'">
                                    <p class="text-sm text-gray-500 mt-1">TTD Saat Ini</p>
                                <?php else: ?>
                                    <p class="text-sm text-red-500">File TTD tidak ditemukan</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="ttd" name="ttd" accept="image/*" class="mt-1 block w-full px-4 py-2 text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-medium
                                  file:bg-emerald-50 file:text-emerald-700
                                  hover:file:bg-emerald-100">
                    <p class="mt-1 text-sm text-gray-500">Upload file gambar (JPG, PNG, max 5MB)</p>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-lock mr-2"></i>Password Saat Ini
                        </label>
                        <input type="password" id="current_password" name="current_password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2">
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="new_password" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-key mr-2"></i>Password Baru
                        </label>
                        <input type="password" id="new_password" name="new_password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2">
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-check-circle mr-2"></i>Konfirmasi Password Baru
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-4 py-2">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a href="<?php echo htmlspecialchars($referrer); ?>"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>