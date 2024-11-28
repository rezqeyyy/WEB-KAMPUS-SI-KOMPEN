<?php
require_once('../../../vendor/Config.php');
$message = '';
$messageType = '';

// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: user.php');
    exit;
}

$id = $_GET['id'];

// Define valid roles
$validRoles = ['Admin', 'Kalab', 'Pengawas', 'PLP'];

// Fetch user data
try {
    $query = "SELECT * FROM tbl_USER WHERE ID = :id";
    $stmt = executeQuery($query, [':id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("User not found.");
    }
    
    // Normalize TTD path if it exists
    if (!empty($user['TTD'])) {
        // Sesuaikan path TTD berdasarkan struktur folder
        $ttdPath = str_replace('../../Uploads_ttd/', '../../Uploads_ttd/', $user['TTD']);
        $user['TTD'] = $ttdPath;
        
        // Untuk debugging
        error_log("TTD Path: " . $user['TTD']);
    }
} catch (Exception $e) {
    die("Error fetching user: " . $e->getMessage());
}
// Handle form submission for updating user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $updateParts = [];
        $params = [];

        // NAMA_USER
        if (!empty($_POST['nama_user'])) {
            $updateParts[] = "NAMA_USER = :nama_user";
            $params[':nama_user'] = $_POST['nama_user'];
        }

        // NIP
        if (!empty($_POST['nip'])) {
            $updateParts[] = "NIP = :nip";
            $params[':nip'] = $_POST['nip'];
        }

        // EMAIL
        if (!empty($_POST['email'])) {
            $updateParts[] = "EMAIL = :email";
            $params[':email'] = $_POST['email'];
        }

        // ROLE - with validation
        if (!empty($_POST['role'])) {
            if (!in_array($_POST['role'], $validRoles)) {
                throw new Exception("Invalid role selected. Valid roles are: " . implode(", ", $validRoles));
            }
            $updateParts[] = "ROLE = :role";
            $params[':role'] = strtoupper($_POST['role']);
        }

        // PASSWORD - only if filled
        if (!empty($_POST['password'])) {
            $updateParts[] = "PASSWORD = :password";
            $params[':password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (!empty($_FILES['ttd']['name'])) {
            $targetDir = "../../Uploads_ttd/";

            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = uniqid() . "_" . basename($_FILES["ttd"]["name"]);
            $targetFile = $targetDir . $fileName;
            $relativePath = "../../Uploads_ttd/" . $fileName;

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
                $updateParts[] = "TTD = :ttd";
                $params[':ttd'] = $relativePath;
                
                // Hapus file TTD lama jika ada
                if (!empty($user['TTD']) && file_exists($user['TTD'])) {
                    unlink($user['TTD']);
                }
            } else {
                throw new Exception("Failed to upload file.");
            }
        }

        // Create query only if there are fields to update
        if (!empty($updateParts)) {
            $params[':id'] = $id;
            $update_query = "UPDATE tbl_USER SET " . implode(', ', $updateParts) . " WHERE ID = :id";
            executeQuery($update_query, $params);

            $message = "User berhasil diupdate.";
            $messageType = "success";
            header('Location: user.php');
            exit;
        }
    } catch (Exception $e) {
        $message = "Error updating user: " . $e->getMessage();
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="icon" type="image/x-icon" href="../images/LogoPNJ.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Edit User</h1>
        <?php if ($message): ?>
            <div class="mb-4 <?php echo $messageType === 'success' ? 'text-green-600' : 'text-red-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700">Nama User</label>
                <input type="text" name="nama_user" value="<?php echo htmlspecialchars($user['NAMA_USER']); ?>" required
                    class="border rounded-lg w-full p-2">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">NIP</label>
                <input type="text" name="nip" value="<?php echo htmlspecialchars($user['NIP']); ?>" required
                    class="border rounded-lg w-full p-2">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['EMAIL']); ?>" required
                    class="border rounded-lg w-full p-2">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Role</label>
                <select name="role" required class="border rounded-lg w-full p-2">
                    <option value="Admin" <?php echo $user['ROLE'] === 'ADMIN' ? 'selected' : ''; ?>>Admin</option>
                    <option value="Kalab" <?php echo $user['ROLE'] === 'KALAB' ? 'selected' : ''; ?>>Kalab</option>
                    <option value="PLP" <?php echo $user['ROLE'] === 'PLP' ? 'selected' : ''; ?>>PLP</option>
                    <option value="Pengawas" <?php echo $user['ROLE'] === 'PENGAWAS' ? 'selected' : ''; ?>>Pengawas
                    </option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" class="border rounded-lg w-full p-2"
                    placeholder="Masukkan password baru">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">TTD (Tanda Tangan)</label>
                <?php if (!empty($user['TTD'])): ?>
                    <div class="mb-2">
                        <div class="border p-2 rounded-lg mb-2">
                            <?php if (file_exists($user['TTD'])): ?>
                                <img src="<?php echo htmlspecialchars($user['TTD']); ?>" 
                                     alt="Current TTD" 
                                     class="w-32 h-auto"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<p class=\'text-red-500\'>Gambar tidak dapat ditampilkan</p>'">
                                <p class="text-sm text-gray-500 mt-1">TTD Saat Ini</p>
                            <?php else: ?>
                                <p class="text-sm text-red-500">File TTD tidak ditemukan</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <input type="file" name="ttd" accept="image/*" class="border rounded-lg w-full p-2">
                <p class="text-sm text-gray-500">Upload file gambar (JPG, PNG, max 5MB)</p>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Update
                    User</button>
                <button type="button" onclick="window.location.href='user.php'"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</button>
            </div>
        </form>
    </div>
</body>

</html>