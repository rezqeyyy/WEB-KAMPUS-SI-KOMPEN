<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['nama_user']) || !isset($_SESSION['role'])) {
    header('Location: ../login.php');
    exit();
}
include_once 'C:\xampp\htdocs\Sikompen\vendor\Config.php';

// Get user data from session
$userNama = $_SESSION['nama_user'];
$userRole = $_SESSION['role'];

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ../login.php'); 
    exit();
}

// Initialize search term
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Initialize variables for pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
$offset = ($page - 1) * $per_page;

try {
    $pdo = getDB();

    // Base query - menghilangkan WHERE clause untuk STATUS_APPROVAL1
    $base_query = "FROM tbl_pengajuan p 
                   JOIN tbl_mahasiswa m ON p.kode_user = m.nim";

    // Where clause for search
    $params = [];
    if (!empty($searchTerm)) {
        $base_query .= " WHERE (
            UPPER(m.nama) LIKE UPPER(:search) 
            OR UPPER(p.kode_kegiatan) LIKE UPPER(:search)
            OR UPPER(p.id_pengajuan) LIKE UPPER(:search)
            OR UPPER(p.status_approval1) LIKE UPPER(:search)
            OR UPPER(p.status_approval2) LIKE UPPER(:search)
            OR UPPER(p.status_approval3) LIKE UPPER(:search)
        )";
        $params[':search'] = "%$searchTerm%";
    }

    // Count total records
    $count_query = "SELECT COUNT(*) as TOTAL " . $base_query;
    $stmt = $pdo->prepare($count_query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_rows = $result['TOTAL'];
    $total_pages = ceil($total_rows / $per_page);

    // Main query with pagination
    $query = "SELECT p.id_pengajuan,
                     p.kode_kegiatan,
                     m.nama as nama_mahasiswa,
                     m.kelas,
                     m.semester,
                     p.total,
                     p.sisa,
                     p.status_approval1,
                     p.status_approval2,
                     p.status_approval3
              " . $base_query . "
              ORDER BY p.created_at ASC
              OFFSET :offset ROWS FETCH NEXT :per_page ROWS ONLY";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $disetujui_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error in Dashboard_PGW.php: " . $e->getMessage());
    $error_message = "Terjadi kesalahan dalam mengambil data";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/LogoPNJ.png">
    <title>Daftar Pengajuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: calc(100% + 0.5rem);
        width: 12rem;
        background-color: white;
        border-radius: 0.375rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 50;
    }

    .dropdown-menu.active {
        display: block;
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
                        <h1 class="text-2xl font-bold text-gray-800">Daftar Pengajuan</h1>
                        <p class="text-sm text-gray-600">Manage the Details of Your Menu Disetujui,
                            <?php echo htmlspecialchars($userNama); ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="relative inline-block text-left">
                            <div class="relative">
                                <button type="button" id="profileButton" class="flex items-center focus:outline-none"
                                    aria-expanded="false">
                                    <img src="images/Profile.png" alt="Profile"
                                        class="h-10 w-10 rounded-full border-2 border-emerald-500 hover:border-emerald-600 transition-colors duration-200">
                                    <span class="ml-2 text-gray-700"><?php echo htmlspecialchars($userNama); ?></span>
                                </button>

                                <div id="dropdownMenu" class="dropdown-menu">
                                    <div class="py-1">
                                        <div class="px-4 py-2 text-sm text-gray-600 border-b">
                                            <div><?php echo htmlspecialchars($userRole); ?></div>
                                        </div>
                                        <hr class="my-1">
                                        <a href="\Sikompen\vendor\Pengawas\UpdateProfile.php"
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
        </div>
        </div>
    </nav>

    <!-- Menu Tabs -->
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="border-b border-gray-200 bg-white rounded-t-lg">
            <nav class="flex">
                <a href=#
                    class="border-b-2 border-emerald-500 text-emerald-600 font-bold hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Pengajuan
                </a>
                <a href=\Sikompen\vendor\Pengawas\Setuju_PGW.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Daftar Disetujui
                </a>
                <a href=\Sikompen\vendor\Pengawas\Pekerjaan.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Pekerjaan
                </a>
            </nav>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Search and Actions -->
        <!-- Search Form -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex-1 max-w-lg">
                <form action="" method="GET" class="flex space-x-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>"
                            placeholder="Search by ID, kode kegiatan, nama mahasiswa, atau kelas..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors duration-200">
                        Filter
                    </button>
                    <?php if (!empty($searchTerm)): ?>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 flex items-center">
                        Reset
                    </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Table -->
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                        Pengajuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                        Kegiatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                        Mahasiswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        (menit)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa
                        (Menit)</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Approval 1 Pengawas</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Approval 2 PLP</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Approval 3 KaLab</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($disetujui_list)): ?>
                <tr>
                    <td colspan="13" class="px-6 py-4 text-center text-sm text-gray-500">No Data Available in Table</td>
                </tr>
                <?php else: ?>
                <?php foreach ($disetujui_list as $index => $item): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo htmlspecialchars($offset + $index + 1); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['ID_PENGAJUAN'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['KODE_KEGIATAN'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['NAMA_MAHASISWA'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['KELAS'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['SEMESTER'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['TOTAL'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['SISA'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['STATUS_APPROVAL1'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['STATUS_APPROVAL2'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo htmlspecialchars($item['STATUS_APPROVAL3'] ?? ''); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <a href="detail_pengajuan.php?id=<?php echo htmlspecialchars($item['ID_PENGAJUAN']); ?>"
                            class="inline-flex items-center px-3 py-1 border border-emerald-500 text-emerald-600 rounded-md hover:bg-emerald-50">
                            Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center">
            <div>
                <select onchange="window.location.href=this.value"
                    class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                    <?php foreach ([10, 25, 50, 100] as $value): ?>
                    <option value="?page=1&per_page=<?php echo $value; ?>&search=<?php echo urlencode($searchTerm); ?>"
                        <?php echo ($per_page == $value) ? 'selected' : ''; ?>>
                        <?php echo $value; ?> per page
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&per_page=<?php echo $per_page; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    Previous
                </a>
                <?php endif; ?>

                <span class="px-4 py-2">
                    Page <?php echo $page; ?> of <?php echo $total_pages; ?>
                </span>

                <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&per_page=<?php echo $per_page; ?>&search=<?php echo htmlspecialchars($searchTerm); ?>"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    Next
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tambahkan script JavaScript untuk handle checkboxes -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileButton = document.getElementById('profileButton');
            const dropdownMenu = document.getElementById('dropdownMenu');

            function toggleDropdown() {
                dropdownMenu.classList.toggle('active');
                profileButton.setAttribute('aria-expanded',
                    dropdownMenu.classList.contains('active'));
            }

            // Toggle dropdown on button click
            profileButton.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown();
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('active');
                    profileButton.setAttribute('aria-expanded', 'false');
                }
            });

            // Prevent dropdown from closing when clicking inside it
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
        </script>
</body>

</html>