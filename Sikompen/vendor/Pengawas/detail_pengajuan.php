<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['nama_user']) || !isset($_SESSION['role'])) {
    header('Location: ../login.php');
    exit();
}

require_once __DIR__ . '/../../vendor/Config.php';

$userNama = $_SESSION['nama_user']; 
$userRole = $_SESSION['role'];

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Handle approval action
if (isset($_POST['approve'])) {
    try {
        $conn = getDB();
        
        // Begin transaction
        $conn->beginTransaction();
        
        // Update STATUS_APPROVAL1 to 'Disetujui'
        $updateQuery = "UPDATE tbl_pengajuan 
                       SET status_approval1 = 'Disetujui',
                           approval1_by = :approval_by,
                           updated_at = CURRENT_TIMESTAMP
                       WHERE id_pengajuan = :id";
                       
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([
            ':approval_by' => $userNama,
            ':id' => $id
        ]);
        
        // Commit transaction
        $conn->commit();
        
        // Redirect with success message
        header('Location: Dashboard_PGW.php?success=true');
        exit();
        
    } catch(PDOException $e) {
        // Rollback transaction on error
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        $error_message = "Error updating approval status: " . $e->getMessage();
    }
}

try {
    $conn = getDB();
    
    $query = "SELECT 
                p.id_pengajuan,
                pd.before_pekerjaan, 
                pd.after_pekerjaan, 
                COALESCE(p.status_approval1, 'Pending') as status_approval1,
                COALESCE(p.approval1_by, '') as approval1_by,
                p.kode_kegiatan
              FROM tbl_pengajuan p
              LEFT JOIN tbl_pengajuan_detail pd 
                ON pd.kode_kegiatan = p.kode_kegiatan 
              WHERE p.id_pengajuan = :id";
              
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id]);
    $pengajuan = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pengajuan) {
        throw new Exception("Pengajuan tidak ditemukan.");
    }

    // Set default values if needed
    $pengajuan['status_approval1'] = $pengajuan['status_approval1'] ?? 'Pending';
    $pengajuan['approval1_by'] = $pengajuan['approval1_by'] ?? '';

} catch(Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan</title>
    <link rel="icon" type="image/x-icon" href="../images/LogoPNJ.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        z-index: 1000;
        margin-top: 0.5rem;
        transform-origin: top right;
    }

    .relative {
        position: relative !important;
    }

    .image-container {
        width: 100%;
        height: 300px;
        position: relative;
        overflow: hidden;
        background-color: #f3f4f6;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .no-image {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #9ca3af;
    }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <img src="../images/LogoPNJ.png" alt="Logo" class="h-12 w-12">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Daftar Pengajuan</h1>
                        <p class="text-sm text-gray-600">Manage the Details of Your Menu Disetujui,
                            <?php echo htmlspecialchars($userNama); ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="relative inline-block text-left">
                            <button type="button" id="profileButton" class="flex items-center focus:outline-none">
                                <img src="../images/Profile.png" alt="Profile"
                                    class="h-10 w-10 rounded-full border-2 border-emerald-500 hover:border-emerald-600 transition-colors duration-200">
                                <span class="ml-2 text-gray-700"><?php echo htmlspecialchars($userNama); ?></span>
                            </button>

                            <div id="dropdownMenu"
                                class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-sm text-gray-600 border-b">
                                        <div><?php echo htmlspecialchars($userRole); ?></div>
                                    </div>
                                    <hr class="my-1">
                                    <a href="UpdateProfile.php"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                        <i class="fas fa-user-edit mr-2"></i>
                                        Update Profile
                                    </a>
                                    <a href="?action=logout"
                                        class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                        onclick="return confirm('Are you sure you want to logout?')">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($pengajuan): ?>
        <!-- Before Image -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Foto Sebelum Pengerjaan</h3>
            <div class="image-container">
                <?php if (!empty($pengajuan['before_pekerjaan'])): ?>
                <img src="/Sikompen/uploads/<?php echo htmlspecialchars($pengajuan['before_pekerjaan']); ?>"
                    alt="Foto Sebelum Pengerjaan" class="rounded-lg shadow-md"
                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-image\'><i class=\'fas fa-image text-4xl\'></i><p class=\'mt-2 text-gray-500\'>Gambar tidak ditemukan</p></div>'">
                <?php else: ?>
                <div class="no-image">
                    <i class="fas fa-image text-4xl"></i>
                    <p class="mt-2 text-gray-500">Tidak ada foto sebelum pengerjaan</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- After Image -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Foto Setelah Pengerjaan</h3>
            <div class="image-container">
                <?php if (!empty($pengajuan['after_pekerjaan'])): ?>
                <img src="/Sikompen/uploads/<?php echo htmlspecialchars($pengajuan['after_pekerjaan']); ?>"
                    alt="Foto Setelah Pengerjaan" class="rounded-lg shadow-md"
                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-image\'><i class=\'fas fa-image text-4xl\'></i><p class=\'mt-2 text-gray-500\'>Gambar tidak ditemukan</p></div>'">
                <?php else: ?>
                <div class="no-image">
                    <i class="fas fa-image text-4xl"></i>
                    <p class="mt-2 text-gray-500">Tidak ada foto setelah pengerjaan</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center space-x-4 mt-8">
            <a href="Dashboard_PGW.php"
                class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors text-center">
                Kembali
            </a>
            <?php if (isset($pengajuan['status_approval1']) && $pengajuan['status_approval1'] !== 'Disetujui'): ?>
            <form method="POST" style="display: inline;">
                <button type="submit" name="approve"
                    onclick="return confirm('Are you sure you want to approve this submission?')"
                    class="px-6 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600 transition-colors text-center">
                    Approve
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="text-center text-gray-500 my-8">
            <p>Tidak ada data pengajuan yang ditemukan.</p>
        </div>
        <?php endif; ?>

        <!-- Status messages -->
        <?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
            Pengajuan berhasil disetujui!
        </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-md">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileButton = document.getElementById('profileButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        profileButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!profileButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    });
    </script>
</body>

</html>