<?php
// File: /vendor/Kalab/DaftarPengajuan/DaftarPengajuan.php

require_once(__DIR__ . '/../../../vendor/Config.php');

// Check session and role
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['role']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../../../login.php');
    exit();
}

// Initialize variables for pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
$offset = ($page - 1) * $per_page;

// Initialize search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    $pdo = getDB();

    // Base query - adjusted for Oracle syntax
    $base_query = "FROM tbl_pengajuan p 
                   JOIN tbl_mahasiswa m ON p.kode_user = m.nim";

    // Where clause for search
    $where = "";
    $params = [];

    if (!empty($search)) {
        $where = " WHERE UPPER(m.nama) LIKE UPPER(:search) 
                   OR UPPER(p.kode_kegiatan) LIKE UPPER(:search) 
                   OR UPPER(p.status_approval1) LIKE UPPER(:search) 
                   OR UPPER(p.status_approval2) LIKE UPPER(:search) 
                   OR UPPER(p.status_approval3) LIKE UPPER(:search)";
        $params[':search'] = "%$search%";
    }

    // Count total records - adjusted for Oracle
    $count_query = "SELECT COUNT(*) as total " . $base_query . $where;
    $stmt = executeQuery($count_query, $params);
    $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['TOTAL'];
    $total_pages = ceil($total_rows / $per_page);

    // Main query for data - adjusted for Oracle pagination
    $query = "SELECT * FROM (
                SELECT a.*, ROWNUM rnum FROM (
                    SELECT p.id_pengajuan,
                           p.kode_kegiatan,
                           m.nama as nama_mahasiswa,
                           p.total as total_menit,
                           p.sisa as sisa_menit,
                           p.status_approval1,
                           p.status_approval2,
                           p.status_approval3
                    " . $base_query . $where . "
                    ORDER BY p.created_at DESC
                ) a WHERE ROWNUM <= :end_row
            ) WHERE rnum > :start_row";

    $params[':start_row'] = $offset;
    $params[':end_row'] = $offset + $per_page;

    $stmt = executeQuery($query, $params);
    $pengajuan_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error in daftarpengajuan.php: " . $e->getMessage());
    $error_message = "Terjadi kesalahan dalam mengambil data";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan Kepala Lab</title>
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
                        <h1 class="text-2xl font-bold text-gray-800">Daftar Pengajuan Kepala Lab</h1>
                        <p class="text-sm text-gray-600">Manage the Details of Your Menu Pengajuan Kepala Lab</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dropdown menu -->
        <div id="dropdownMenu"
            class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
            <div class="py-1">
                <!-- User Info -->
                <div class="px-4 py-2 text-sm text-gray-600 border-b">
                    <div><?php echo htmlspecialchars($userRole); ?></div>
                </div>

                <!-- Right Section: Profile -->
                <div class="flex items-center space-x-4">
                    <!-- You can dynamically echo user details if needed -->
                    <span class="text-gray-700"><?php echo htmlspecialchars($userRole); ?></span>
                    <img src="images/profile.jpg" alt="Profile" class="h-10 w-10 rounded-full">
                    <div class="flex space-x-2">
                        <a href="../UpdateProfile.php"
                            class="text-sm text-gray-700 hover:text-emerald-600 flex items-center">
                            <i class="fas fa-user-edit mr-2"></i>Update Profile
                        </a>
                        <a href="?action=logout" class="text-sm text-red-600 hover:text-red-800 flex items-center"
                            onclick="return confirm('Are you sure you want to logout?')">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
    </nav>

    <!-- Menu Tabs -->
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="border-b border-gray-200 bg-white rounded-t-lg">
            <nav class="flex">
                <a href="#" class="border-b-2 border-emerald-500 text-emerald-600 px-6 py-3 text-sm font-medium">
                    Pengajuan
                </a>
                <a href="#"
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Daftar Disetujui
                </a>
                <a href="#"
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Pengajuan Paksa
                </a>
                <a href="#"
                    class="border-b-2 border-transparent text-gray-500 hover:text-emerald-600 hover:border-emerald-300 px-6 py-3 text-sm font-medium">
                    Pekerjaan
                </a>
            </nav>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Search and Actions -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex-1 max-w-lg">
                <form method="GET" class="flex space-x-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                            placeholder="Search by nama, status approval..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        Search
                    </button>
                </form>
            </div>
            <div class="flex space-x-3">
                <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    Submit Semua Pengerjaan
                </button>
                <button class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Filter
                </button>
            </div>
        </div>

        <!-- Table -->
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Table header -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                                Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                                Kegiatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total(Menit)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sisa(Menit)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Approval 1</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Approval 2</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Approval 3</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($pengajuan_list)): ?>
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">No Data Available in Table
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pengajuan_list as $index => $pengajuan): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $offset + $index + 1; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pengajuan['ID_PENGAJUAN']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pengajuan['KODE_KEGIATAN']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pengajuan['NAMA_MAHASISWA']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pengajuan['TOTAL_MENIT']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pengajuan['SISA_MENIT']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pengajuan['STATUS_APPROVAL1']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pengajuan['STATUS_APPROVAL2']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($pengajuan['STATUS_APPROVAL3']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="viewPengajuan('<?php echo $pengajuan['ID_PENGAJUAN']; ?>')"
                                                class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="editPengajuan('<?php echo $pengajuan['ID_PENGAJUAN']; ?>')"
                                                class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deletePengajuan('<?php echo $pengajuan['ID_PENGAJUAN']; ?>')"
                                                class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <div class="flex justify-center mt-4">
                    <?php if ($total_pages > 1): ?>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo ($page - 1); ?>&per_page=<?php echo $per_page; ?>&search=<?php echo urlencode($search); ?>"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>&per_page=<?php echo $per_page; ?>&search=<?php echo urlencode($search); ?>"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?php echo ($page == $i) ? 'text-emerald-600 bg-emerald-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo ($page + 1); ?>&per_page=<?php echo $per_page; ?>&search=<?php echo urlencode($search); ?>"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    Next
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>

                <div class="flex items-center">
                    <select onchange="window.location.href=this.value"
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                        <?php foreach ([10, 25, 50, 100] as $value): ?>
                            <option value="?page=1&per_page=<?php echo $value; ?>&search=<?php echo urlencode($search); ?>"
                                <?php echo ($per_page == $value) ? 'selected' : ''; ?>>
                                <?php echo $value; ?> per page
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>


        // Your existing JavaScript functions remain the same
        function viewPengajuan(id) {
            window.location.href = `view_pengajuan.php?id=${id}`;
        }

        function editPengajuan(id) {
            window.location.href = `edit_pengajuan.php?id=${id}`;
        }

        function deletePengajuan(id) {
            if (confirm('Are you sure you want to delete this submission?')) {
                fetch('delete_pengajuan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Failed to delete submission');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting');
                    });
            }
        }
    </script>
</body>

</html>