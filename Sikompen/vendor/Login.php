<?php
session_start();
require_once('../vendor/Config.php');

// Jika sudah login, redirect berdasarkan role
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    redirectBasedOnRole($_SESSION['role']);
    exit();
}

// Function untuk redirect berdasarkan role
function redirectBasedOnRole($role) {
    switch(strtoupper($role)) {
        case 'ADMIN':
            header('Location: Admin/dashboard/dashboard.php'); 

            break;
        case 'KALAB':
            header('Location: kalab/daftarpengajuan/daftarpengajuan.php'); 

            break;
        case 'PLP':
            header('Location: PLP/daftarpengajuan/daftarpengajuanplp.php'); 

            break;
        case 'PENGAWAS':
            header('Location: Pengawas\Dashboard_PGW.php'); 

            break;
        default:
            header("Location: login.php");
            break;
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input dari form login
    $nip = trim($_POST['nip'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input
    if (!empty($nip) && !empty($password)) {
        try {
            // Query untuk mengambil data user berdasarkan NIP
            $query = "SELECT * FROM tbl_user WHERE NIP = :nip AND STATUS = 'ACTIVE'";
            $stmt = executeQuery($query, [':nip' => $nip]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['PASSWORD'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['nama_user'] = $user['NAMA_USER'];
                $_SESSION['email'] = $user['EMAIL'];
                $_SESSION['role'] = $user['ROLE'];
                $_SESSION['nip'] = $user['NIP'];
                $_SESSION['logged_in'] = true;

                // Update last login
                $updateQuery = "UPDATE tbl_user SET LAST_LOGIN = CURRENT_TIMESTAMP WHERE ID = :id";
                executeQuery($updateQuery, [':id' => $user['ID']]);

                // Pastikan session tersimpan
                session_write_close();

                // Redirect berdasarkan role
                redirectBasedOnRole($user['ROLE']);
            } else {
                $error = "NIP atau Password salah";
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $error = "Harap isi NIP dan Password";
    }
}
?>


<!-- Rest of your HTML form remains the same -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .animated-gradient {
        background: linear-gradient(-45deg, #064e3b, #10b981);
        background-size: 200% 200%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0% {
            transform: translate(0, 0px);
        }

        50% {
            transform: translate(0, 15px);
        }

        100% {
            transform: translate(0, -0px);
        }
    }

    .side-image {
        background-size: cover;
        background-position: center;
    }
    </style>
</head>

<body class="animated-gradient min-h-screen flex items-center justify-center p-4">
    <!-- Animated Background Shapes -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-1/2 -left-1/2 w-full h-full floating opacity-30">
            <div class="absolute w-96 h-96 bg-emerald-600 rounded-full filter blur-3xl"></div>
        </div>
        <div class="absolute -bottom-1/2 -right-1/2 w-full h-full floating opacity-30" style="animation-delay: -2s;">
            <div class="absolute w-96 h-96 bg-green-800 rounded-full filter blur-3xl"></div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-5xl flex rounded-2xl shadow-2xl overflow-hidden">
        <!-- Left Side - Image -->
        <div class="hidden lg:block w-1/2 side-image glass-effect">
            <div class="h-full w-full flex items-center justify-center bg-black bg-opacity-20">
                <img src="images/Admin.jpg" alt="Login Illustration" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 glass-effect p-8">
            <!-- Logo and Title Section -->
            <div class="text-center mb-8">
                <div class="mx-auto mb-4">
                    <img src="images/LogoPNJ.png" alt="Logo" class="w-32 h-32 object-contain mx-auto">
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Login User</h1>
                <p class="text-gray-200">Please enter your credentials</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-6"
                autocomplete="off">
                <!-- NIP Input -->
                <div class="space-y-2">
                    <label for="nip" class="block text-sm font-medium text-white">
                        <i class="fas fa-id-card mr-2"></i>NIP
                    </label>
                    <div class="relative">
                        <input type="text" id="nip" name="nip" required pattern="[0-9]{18}"
                            class="w-full px-4 py-3 bg-white bg-opacity-20 border border-emerald-300 text-white placeholder-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition duration-200"
                            placeholder="Masukkan NIP"
                            value="<?php echo isset($_POST['nip']) ? htmlspecialchars($_POST['nip']) : ''; ?>">
                        <div class="mt-1 text-xs text-gray-200">
                            <i class="fas fa-info-circle mr-1"></i>Masukkan NIP 18 digit
                        </div>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-white">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 bg-white bg-opacity-20 border border-emerald-300 text-white placeholder-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition duration-200"
                            placeholder="Masukkan password">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 hover:text-white transition duration-200">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </button>
                    </div>
                </div>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                <div class="bg-red-500 bg-opacity-20 border border-red-500 text-white px-4 py-3 rounded relative"
                    role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" data-bs-dismiss="alert">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php endif; ?>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-white text-emerald-700 py-3 rounded-lg font-semibold shadow-lg hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>
        </div>
    </div>

    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    window.onload = function() {
        document.getElementById('password').value = '';
    };

    function togglePassword() {
        const password = document.getElementById('password');
        const toggle = document.getElementById('togglePassword');
        if (password.type === 'password') {
            password.type = 'text';
            toggle.classList.remove('fa-eye');
            toggle.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            toggle.classList.remove('fa-eye-slash');
            toggle.classList.add('fa-eye');
        }
    }
    </script>
</body>

</html>