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
            header('Location: ../../Pengawas/Dashboard/dashboard.php');
            break;
        default:
            header('Location: ../login.php');
            break;
    }
    exit();
}
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Reset koneksi database
$db = getDB();
$db = null;

// Query untuk menampilkan data dengan memperhatikan constraint
$query = "SELECT * FROM tbl_user WHERE status != 'INACTIVE' ORDER BY id";
// Get user data from session
$userNama = $_SESSION['nama_user'];
$userRole = $_SESSION['role'];

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ../../login.php');
    exit();
}

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Search and filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$params = [];
$where_conditions = [];

// Build search conditions
if ($search) {
    $where_conditions[] = "(LOWER(NIP) LIKE LOWER(:search) OR LOWER(EMAIL) LIKE LOWER(:search) OR LOWER(NAMA_USER) LIKE LOWER(:search))";
    $params[':search'] = "%$search%";
}

if ($role_filter) {
    $where_conditions[] = "ROLE = :role";
    $params[':role'] = $role_filter;
}

if ($status_filter) {
    $where_conditions[] = "STATUS = :status";
    $params[':status'] = $status_filter;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total records for pagination
try {
    $total_records_query = "SELECT COUNT(*) AS TOTAL FROM TBL_USER $where_clause";
    $stmt = executeQuery($total_records_query, $params);
    $total_records = $stmt->fetch()['TOTAL'];
    $total_pages = ceil($total_records / $records_per_page);

    // Get users with pagination
    $query = "SELECT * FROM (
                SELECT a.*, ROWNUM rnum FROM (
                    SELECT ID, NAMA_USER, NIP, EMAIL, ROLE, STATUS, 
                           TO_CHAR(CREATED_AT, 'DD-MON-YYYY HH24:MI:SS') AS CREATED_AT,
                           TO_CHAR(LAST_LOGIN, 'DD-MON-YYYY HH24:MI:SS') AS LAST_LOGIN
                    FROM TBL_USER $where_clause 
                    ORDER BY ID
                ) a WHERE ROWNUM <= :upper_limit
              ) WHERE rnum > :lower_limit";

    $params[':upper_limit'] = $offset + $records_per_page;
    $params[':lower_limit'] = $offset;

    $stmt = executeQuery($query, $params);
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    $users = [];
    $total_pages = 1;
    echo "Database error: " . $e->getMessage();
}

// Handle delete all action
if (isset($_GET['delete_all']) && $userRole === 'ADMIN') {
    try {
        $delete_query = "DELETE FROM TBL_USER WHERE ROLE != 'ADMIN'";
        executeQuery($delete_query, []);
        header("Location: user.php");
        exit;
    } catch (Exception $e) {
        die("Error deleting users: " . $e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu User</title>
    <link rel="icon" type="image/x-icon" href="../images/LogoPNJ.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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
                        <h1 class="text-2xl font-bold text-gray-800">Menu User</h1>
                        <p class="text-sm text-gray-600">Manage the Details of Your Menu User,
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
                                    <div class="px-4 py-2 text-sm text-gray-600 border-b">
                                        <div><?php echo htmlspecialchars($userRole); ?></div>
                                    </div>

                                    <!-- User Info -->


                                    <hr class="my-1">

                                    <!-- Update Profile link -->
                                    <a href="../UpdateProfile.php?ref=<?php echo urlencode('/Sikompen/vendor/Admin/user/user.php'); ?>"
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
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Dashboard
                </a>
                <a href=/sikompen/vendor/Admin/Pekerjaan/Pekerjaan.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Pekerjaan
                </a>
                <a href=/sikompen/vendor/Admin/User/User.php
                    class="border-b-2 border-emerald-500 text-emerald-600 px-6 py-3 text-sm font-medium">
                    User
                </a>
                <a href=/sikompen/vendor/Admin/Mahasiswa/Mahasiswa.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Mahasiswa
                </a>
                <a href=/sikompen/vendor/Admin/Bertugas/Bertugas.php
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Bertugas
                </a>
                <a href="/sikompen/vendor/Admin/Pembayaran/Bertugas.php"
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    History Payment
                </a>
            </nav>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Search and Actions -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex-1 max-w-lg">
                <form action="" method="GET" class="flex space-x-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                            placeholder="Search by NIP or Email..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                    </div>
                    <select name="role"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="">All Roles</option>
                        <option value="ADMIN" <?php echo $role_filter === 'ADMIN' ? 'selected' : ''; ?>>Admin</option>
                        <option value="KALAB" <?php echo $role_filter === 'KALAB' ? 'selected' : ''; ?>>Kalab</option>
                        <option value="PLP" <?php echo $role_filter === 'PLP' ? 'selected' : ''; ?>>PLP</option>
                        <option value="PENGAWAS" <?php echo $role_filter === 'PENGAWAS' ? 'selected' : ''; ?>>Pengawas
                        </option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        Filter
                    </button>
                    <?php if (!empty($search) || !empty($role_filter)): ?>
                        <a href="user.php"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 flex items-center">
                            Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="flex space-x-3">
                <button onclick="window.location.href='TambahUser.php'"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Data
                </button>
                <button
                    onclick="if(confirm('Are you sure you want to delete all users?')) window.location.href='user.php?delete_all=true'"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i> Delete Semua
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                            User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $row): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($row['ID']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($row['NAMA_USER']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($row['NIP']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($row['EMAIL']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($row['ROLE']); ?></td>
                            <td class="px-6 py-4 text-sm text-center">
                                <a href="EditUser.php?id=<?php echo $row['ID']; ?>"
                                    class="inline-flex items-center px-3 py-1 border border-emerald-500 text-emerald-600 rounded-md hover:bg-emerald-50 transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                <a href="DeleteUser.php?id=<?php echo $row['ID']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this item?')"
                                    class="inline-flex items-center px-3 py-1 border border-red-500 text-red-600 rounded-md hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-trash-alt mr-1"></i>
                                    Delete
                                </a>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <a href="?page=1<?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $role_filter ? '&role=' . urlencode($role_filter) : ''; ?>"
                    class="px-3 py-2 rounded-l-md text-sm font-medium text-gray-500 hover:bg-gray-50">
                    First
                </a>
                <a href="?page=<?php echo max(1, $page - 1) . ($search ? '&search=' . urlencode($search) : '') . ($role_filter ? '&role=' . urlencode($role_filter) : ''); ?>"
                    class="px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50">
                    Previous
                </a>
                <a href="?page=<?php echo min($total_pages, $page + 1) . ($search ? '&search=' . urlencode($search) : '') . ($role_filter ? '&role=' . urlencode($role_filter) : ''); ?>"
                    class="px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50">
                    Next
                </a>
                <a href="?page=<?php echo $total_pages . ($search ? '&search=' . urlencode($search) : '') . ($role_filter ? '&role=' . urlencode($role_filter) : ''); ?>"
                    class="px-3 py-2 rounded-r-md text-sm font-medium text-gray-500 hover:bg-gray-50">
                    Last
                </a>
            </nav>
            <div class="text-sm text-gray-500">
                Page <?php echo $page; ?> of <?php echo $total_pages; ?>
            </div>
        </div>
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
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                document.getElementById('dropdownMenu').classList.add('hidden');
            }
        });
    </script>
</body>

</html>