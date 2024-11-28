<?php
session_start();
require_once('../../../vendor/Config.php');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../../login.php');
    exit();
}

// Validate user role - Only allow ADMIN
if ($_SESSION['role'] !== 'ADMIN') {
    // Redirect based on role
    switch ($_SESSION['role']) {
        case 'KALAB':
            header('Location: ../../Kalab/Dashboard/dashboard.php');
            break;
        case 'PLP':
            header('Location: ../../PLP/Dashboard/dashboard.php');
            break;
        case 'PENGAWAS':
            header('Location: ../../Pengawas\Dashboard_PGW.php');
            break;
        default:
            header('Location: ../login.php');
            break;
    }
    exit();
}

// Get user data from session
$userNama = $_SESSION['nama_user'];
$userRole = $_SESSION['role'];

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Clear all session data
    $_SESSION = array();

    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header('Location: ../../login.php');
    exit();
}

// Function to safely get count from database
function getTableCount($query)
{
    try {
        $stmt = executeQuery($query);
        return intval($stmt->fetch(PDO::FETCH_NUM)[0]);
    } catch (PDOException $e) {
        error_log("Error in getTableCount: " . $e->getMessage());
        return 0;
    }
}

// Get total counts from database with error handling
// Get total counts from database
try {
    // Count total pekerjaan
    $pekerjaanQuery = "SELECT COUNT(1) FROM tbl_pekerjaan";
    $pekerjaanStmt = executeQuery($pekerjaanQuery);
    $totalPekerjaan = $pekerjaanStmt->fetch(PDO::FETCH_NUM)[0];

    // Count total users
    $userQuery = "SELECT COUNT(1) FROM tbl_user";
    $userStmt = executeQuery($userQuery);
    $totalUsers = $userStmt->fetch(PDO::FETCH_NUM)[0];

    // Convert to integers
    $totalPekerjaan = intval($totalPekerjaan);
    $totalUsers = intval($totalUsers);

    // Count total mahasiswa
    $mahasiswaQuery = "SELECT COUNT(1) FROM tbl_mahasiswa";
    $mahasiswaStmt = executeQuery($mahasiswaQuery);
    $totalMahasiswa = $mahasiswaStmt->fetch(PDO::FETCH_NUM)[0];

    // Count total bertugas
    $bertugasQuery = "SELECT COUNT(1) FROM setup_bertugas";
    $bertugasStmt = executeQuery($bertugasQuery);
    $totalBertugas = $bertugasStmt->fetch(PDO::FETCH_NUM)[0];

    // Convert to integer
    $totalBertugas = intval($totalBertugas);

    // Remove this line as it's overwriting the count to 0
    // $totalBertugas = 0;

} catch (PDOException $e) {
    // If there's an error, set defaults
    $totalPekerjaan = 0;
    $totalUsers = 0;
    $totalMahasiswa = 0;
    $totalBertugas = 0;
    error_log("Database error in Dashboard.php: " . $e->getMessage());
}

// Set last update time
$lastUpdate = date('Y-m-d H:i:s');

// Security Headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: same-origin");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../images/LogoPNJ.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* Additional styles for clickable cards */
    .clickable-card {
        position: relative;
        overflow: hidden;
    }

    .clickable-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .clickable-card:hover::after {
        opacity: 1;
    }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <img src="images/LogoPNJ.png" alt="Logo" class="h-12 w-12">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                        <p class="text-sm text-gray-600">Manage the Details of Your Dashboard Admin,
                            <?php echo htmlspecialchars($userNama); ?>
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="relative inline-block text-left">
                            <button type="button" onclick="toggleDropdown()"
                                class="flex items-center focus:outline-none">
                                <img src="images/profile.png" alt="Profile"
                                    class="h-10 w-10 rounded-full border-2 border-emerald-500 hover:border-emerald-600 transition-colors duration-200">
                                <span class="ml-2 text-gray-700"><?php echo htmlspecialchars($userNama); ?></span>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownMenu"
                                class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1">
                                    <!-- User Info -->
                                    <div class="px-4 py-2 text-sm text-gray-600 border-b">
                                        <div><?php echo htmlspecialchars($userRole); ?></div>
                                    </div>

                                    <!-- Menu Items -->

                                    <hr class="my-1">

                                    <!-- Update Profile link -->
                                    <a href="../UpdateProfile.php?ref=<?php echo urlencode('/Sikompen/vendor/Admin/dashboard/dashboard.php'); ?>"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                        <i class="fas fa-user-edit mr-2"></i>
                                        Update Profile
                                    </a>

                                    <!-- Logout link -->
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

    <!-- Menu Tabs -->
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="border-b border-gray-200 bg-white rounded-t-lg">
            <nav class="flex">
                <a href=/sikompen/vendor/Admin/Dashboard/Dashboard.php
                    class="border-b-2 border-emerald-500 text-emerald-600 px-6 py-3 text-sm font-medium">
                    Dashboard
                </a>
                <a href=/sikompen/vendor/Admin/Pekerjaan/Pekerjaan.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium transition-colors duration-200">
                    Pekerjaan
                </a>
                <a href=/sikompen/vendor/Admin/User/User.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium transition-colors duration-200"
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium transition-colors duration-200">
                    User
                </a>
                <a href=/sikompen/vendor/Admin/Mahasiswa/Mahasiswa.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium transition-colors duration-200">
                    Mahasiswa
                </a>
                <a href=/sikompen/vendor/Admin/Bertugas/Bertugas.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium transition-colors duration-200">
                    Bertugas
                </a>
                <a href="/sikompen/vendor/Admin/Pembayaran/Bertugas.php"
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    History Payment
                </a>

            </nav>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="max-w-7xl mx-auto px-4 mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 pb-8">
        <!-- Total Pekerjaan Card - Clickable -->
        <a href=/sikompen/vendor/Admin/Pekerjaan/Pekerjaan.php
            class="block transform transition-transform duration-300 hover:scale-105">
            <div
                class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg p-6 text-white shadow-lg card-hover cursor-pointer clickable-card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-5xl font-bold mb-2"><?php echo $totalPekerjaan; ?></h2>
                        <p class="text-2xl font-medium">Total Pekerjaan</p>
                    </div>
                    <div class="bg-emerald-400 bg-opacity-30 p-4 rounded-full">
                        <i class="fas fa-briefcase text-3xl"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total User Card - Clickable -->
        <a href=/sikompen/vendor/Admin/User/User.php
            class="block transform transition-transform duration-300 hover:scale-105">
            <div
                class="bg-gradient-to-br from-indigo-800 to-indigo-900 rounded-lg p-6 text-white shadow-lg card-hover cursor-pointer clickable-card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-5xl font-bold mb-2"><?php echo $totalUsers; ?></h2>
                        <p class="text-2xl font-medium">Total User</p>
                    </div>
                    <div class="bg-indigo-700 bg-opacity-30 p-4 rounded-full">
                        <i class="fas fa-user-circle text-3xl"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total Mahasiswa Card - Clickable -->
        <a href=/sikompen/vendor/Admin/Mahasiswa/Mahasiswa.php
            class="block transform transition-transform duration-300 hover:scale-105">
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white shadow-lg card-hover cursor-pointer clickable-card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-5xl font-bold mb-2"><?php echo $totalMahasiswa; ?></h2>
                        <p class="text-2xl font-medium">Total Mahasiswa</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 p-4 rounded-full">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total Setup Card - Clickable -->
        <a href=/sikompen/vendor/Admin/Bertugas/Bertugas.php
            class="block transform transition-transform duration-300 hover:scale-105">
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white shadow-lg card-hover cursor-pointer clickable-card">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-5xl font-bold mb-2"><?php echo $totalBertugas; ?></h2>
                        <p class="text-2xl font-medium">Total Setup</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 p-4 rounded-full">
                        <i class="fas fa-cog text-3xl"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>


    <script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');

        // Close dropdown when clicking outside
        const handleClickOutside = (event) => {
            const isClickInside = dropdown.contains(event.target) ||
                event.target.closest('button');
            if (!isClickInside) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', handleClickOutside);
            }
        };

        setTimeout(() => {
            document.addEventListener('click', handleClickOutside);
        }, 0);
    }

    // Close dropdown with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.getElementById('dropdownMenu').classList.add('hidden');
        }
    });
    </script>
</body>

</html>