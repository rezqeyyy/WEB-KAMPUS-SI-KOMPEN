<?php
require_once('../../../vendor/Config.php');require_once dirname(__FILE__) . '/../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Inisialisasi variabel pesan
$message = '';
$messageType = '';

// Fungsi untuk membersihkan input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Handle template download
if (isset($_GET['download_template'])) {
    try {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers sesuai struktur database
        $headers = [
            'NIM',
            'Nama',
            'Email',
            'Prodi',
            'Kelas',
            'Semester',
            'No. Telp',
            'Password',
            'Jumlah Terlambat',
            'Jumlah Alfa',
            'Total'
        ];

        foreach ($headers as $key => $header) {
            $column = chr(65 + $key);
            $sheet->setCellValue($column . '1', $header);
        }

        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_mahasiswa.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    } catch (Exception $e) {
        $message = "Error saat mengunduh template: " . $e->getMessage();
        $messageType = "error";
    }
}

// Handle file import
if (isset($_POST["submit"])) {
    try {
        if (!isset($_FILES["file"]) || empty($_FILES["file"]["name"])) {
            throw new Exception("Silakan pilih file untuk diimport");
        }

        $allowed_ext = ['xls', 'csv', 'xlsx'];
        $fileName = $_FILES["file"]["name"];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowed_ext)) {
            throw new Exception("Format file tidak valid. Hanya file XLS, XLSX, dan CSV yang diizinkan");
        }

        $inputFileName = $_FILES["file"]["tmp_name"];

        if (!is_uploaded_file($inputFileName)) {
            throw new Exception("File tidak berhasil diunggah");
        }

        $spreadsheet = IOFactory::load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        if ($highestRow <= 1) {
            throw new Exception("File Excel kosong atau tidak memiliki data");
        }

        $pdo = getDB();
        $pdo->beginTransaction();

        // Prepare statement untuk cek duplikasi
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_mahasiswa WHERE nim = ?");

        // Prepare statement untuk insert dengan semua kolom yang diperlukan
        $insertStmt = $pdo->prepare("INSERT INTO tbl_mahasiswa (
            id_mhs, nim, nama, email, prodi, kelas, semester, notelp, password,
            edit_password, user_role, jumlah_terlambat, jumlah_alfa, total,
            user_create, created_at
        ) VALUES (
            seq_id_mhs.NEXTVAL, :nim, :nama, :email, :prodi, :kelas, :semester, :notelp, :password,
            '0', 'Mahasiswa', :jumlah_terlambat, :jumlah_alfa, :total,
            :user_create, CURRENT_TIMESTAMP
        )");

        $insertedRows = 0;
        $errors = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            try {
                $nim = sanitizeInput($worksheet->getCell('A' . $row)->getValue());
                $nama = sanitizeInput($worksheet->getCell('B' . $row)->getValue());
                $email = sanitizeInput($worksheet->getCell('C' . $row)->getValue());
                $prodi = sanitizeInput($worksheet->getCell('D' . $row)->getValue());
                $kelas = sanitizeInput($worksheet->getCell('E' . $row)->getValue());
                $semester = sanitizeInput($worksheet->getCell('F' . $row)->getValue());
                $notelp = sanitizeInput($worksheet->getCell('G' . $row)->getValue());
                $password = sanitizeInput($worksheet->getCell('H' . $row)->getValue());
                $jumlah_terlambat = sanitizeInput($worksheet->getCell('I' . $row)->getValue());
                $jumlah_alfa = sanitizeInput($worksheet->getCell('J' . $row)->getValue());
                $total = sanitizeInput($worksheet->getCell('K' . $row)->getValue());

                // Skip empty rows
                if (empty($nim) && empty($nama)) {
                    continue;
                }

                // Validasi data wajib
                if (empty($nim) || empty($nama)) {
                    throw new Exception("Baris $row: NIM dan Nama Mahasiswa tidak boleh kosong");
                }

                // Cek duplikasi
                $checkStmt->execute([$nim]);
                if ($checkStmt->fetchColumn() > 0) {
                    throw new Exception("Baris $row: NIM '$nim' sudah ada dalam database");
                }

                // Hash password jika ada
                $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : password_hash($nim, PASSWORD_DEFAULT); // Default to NIM if no password

                // Insert data
                $insertStmt->execute([
                    ':nim' => $nim,
                    ':nama' => $nama,
                    ':email' => $email,
                    ':prodi' => $prodi,
                    ':kelas' => $kelas,
                    ':semester' => $semester,
                    ':notelp' => $notelp,
                    ':password' => $hashedPassword,
                    ':jumlah_terlambat' => $jumlah_terlambat,
                    ':jumlah_alfa' => $jumlah_alfa,
                    ':total' => $total,
                    ':user_create' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'system'
                ]);

                $insertedRows++;
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            throw new Exception("Terjadi error:<br>" . implode("<br>", $errors));
        }

        $pdo->commit();

        // Redirect ke mahasiswa.php setelah berhasil
        header('Location: mahasiswa.php?message=success&count=' . $insertedRows);
        exit;

    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data Mahasiswa</title>
    <link rel="icon" type="image/x-icon" href="../images/LogoPNJ.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Import Data Mahasiswa</h2>
            <a href="mahasiswa.php" class="text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-times text-xl hover:scale-110"></i>
            </a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-4 p-4 rounded <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih File Excel</label>
                <input type="file" name="file" accept=".xls,.xlsx,.csv" class="w-full p-2 border rounded">
                <p class="text-sm text-gray-600 mt-1">Format yang diterima: XLS, XLSX, CSV</p>
            </div>

            <div class="flex gap-2">
                <button type="submit" name="submit" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition flex items-center">
                    <i class="fas fa-file-import mr-2"></i>
                    Import Data
                </button>
                <a href="?download_template" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition inline-flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Download Template
                </a>
            </div>
        </form>
    </div>
</body>
</html>