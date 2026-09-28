<?php
session_start();

$error_message = "";
$success_message = "";

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Basic validation
    if (empty($username) || empty($password)) {
        $error_message = "Username dan kata sandi tidak boleh kosong!";
    } else {
        // Simulated authentication (replace with database logic)
        if ($username === "pasteluser" && $password === "pastel123") {
            $_SESSION['username'] = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            $success_message = "Login berhasil! Selamat datang kembali, " . $_SESSION['username'] . ".";
        } else {
            $error_message = "Username atau kata sandi salah! Coba: pasteluser / pastel123";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Eksklusif - Pastel Ungu & Pink</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pastelPurple: '#b794f4',
                        pastelPink: '#fbb6ce',
                        softBg: '#fdf4f8'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(3deg); }
        }
        .blob-bg-1 {
            animation: floatSlow 7s ease-in-out infinite;
        }
        .blob-bg-2 {
            animation: floatSlow 9s ease-in-out infinite reverse;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.65);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-100 via-pink-50 to-purple-200 min-h-screen flex items-center justify-center p-4 relative overflow-hidden font-sans">

    <div class="absolute -top-32 -left-32 w-96 h-96 bg-pastelPurple rounded-full mix-blend-multiply filter blur-3xl opacity-50 blob-bg-1 pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-pastelPink rounded-full mix-blend-multiply filter blur-3xl opacity-50 blob-bg-2 pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="glass-panel rounded-3xl shadow-2xl p-8 sm:p-10 transition-all duration-300">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-purple-500 to-pink-400 rounded-2xl shadow-lg mb-4 text-white text-2xl transform transition hover:scale-105 duration-300">
                    <i class="fa-solid fa-gem"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 tracking-tight">Selamat Datang</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Masuk dengan akun pastel Anda</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm rounded-xl flex items-center space-x-3 shadow-sm animate-pulse">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-base"></i>
                    <span><?php echo $error_message; ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs sm:text-sm rounded-xl flex items-center space-x-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                    <span><?php echo $success_message; ?></span>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-5" onsubmit="setLoadingState(this)">
                
                <!-- Username Field -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-2">Username / Email</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-purple-400">
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <input type="text" name="username" required autocomplete="off"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            class="w-full pl-11 pr-4 py-3 bg-white/60 border border-purple-100 rounded-xl text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white transition-all duration-300 placeholder-gray-400 shadow-sm"
                            placeholder="cth: pasteluser">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-2">Kata Sandi</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-purple-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="passwordField" name="password" required
                            class="w-full pl-11 pr-12 py-3 bg-white/60 border border-purple-100 rounded-xl text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:bg-white transition-all duration-300 placeholder-gray-400 shadow-sm"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 text-gray-400 hover:text-purple-600 transition-colors focus:outline-none">
                            <i id="eyeIcon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-xs sm:text-sm pt-1">
                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-400 accent-purple-500">
                        <span class="ml-2 text-gray-600">Ingat saya</span>
                    </label>
                    <a href="#forgot" onclick="showNotification('Info', 'Fitur pemulihan sandi belum diaktifkan.')" class="font-medium text-purple-600 hover:text-pink-500 transition-colors">Lupa Sandi?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-purple-500 via-purple-600 to-pink-400 text-white font-semibold rounded-xl shadow-lg shadow-purple-300/50 hover:shadow-xl hover:shadow-purple-400/60 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 flex items-center justify-center space-x-2">
                    <span id="btnText">Masuk Sekarang</span>
                    <i id="btnIcon" class="fa-solid fa-arrow-right-long text-xs"></i>
                </button>

            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-purple-200/60"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white/70 px-3 text-gray-400 tracking-wider rounded-full backdrop-blur-sm">Atau hubungkan</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button onclick="showNotification('Google', 'Autentikasi Google berhasil disimulasikan.')" class="flex items-center justify-center py-2.5 px-4 bg-white/70 border border-purple-100 rounded-xl text-xs sm:text-sm font-medium text-gray-700 hover:bg-white hover:border-purple-300 transition-all shadow-sm">
                    <i class="fa-brands fa-google text-red-500 mr-2"></i> Google
                </button>
                <button onclick="showNotification('Apple', 'Autentikasi Apple berhasil disimulasikan.')" class="flex items-center justify-center py-2.5 px-4 bg-white/70 border border-purple-100 rounded-xl text-xs sm:text-sm font-medium text-gray-700 hover:bg-white hover:border-purple-300 transition-all shadow-sm">
                    <i class="fa-brands fa-apple text-gray-900 mr-2"></i> Apple
                </button>
            </div>

            <p class="text-center text-xs sm:text-sm text-gray-600 mt-8">
                Belum punya akun? 
                <a href="#register" onclick="showNotification('Pendaftaran', 'Silakan hubungi administrator untuk pendaftaran.')" class="font-semibold text-purple-600 hover:text-pink-500 transition-colors ml-1">Daftar akun</a>
            </p>

        </div>
    </div>

    <div id="customModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white/90 backdrop-blur-md rounded-2xl max-w-sm w-full p-6 shadow-2xl transform scale-95 transition-transform duration-300 border border-purple-100" id="modalContent">
            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-3 text-lg">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800 mb-1">Judul</h3>
                <p id="modalMessage" class="text-xs sm:text-sm text-gray-600 mb-5">Pesan informasi.</p>
                <button onclick="closeNotification()" class="w-full py-2.5 bg-gradient-to-r from-purple-500 to-pink-400 text-white font-medium rounded-xl text-sm shadow-md hover:opacity-95 transition-all">Mengerti</button>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('passwordField');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Show loading state on form submit
        function setLoadingState(form) {
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const submitBtn = document.getElementById('submitBtn');

            submitBtn.disabled = true;
            btnText.innerText = "Memproses...";
            btnIcon.className = "fa-solid fa-spinner fa-spin text-xs";
        }

        // Modal Notification system (replaces browser alerts)
        function showNotification(title, message) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalMessage').innerText = message;
            const modal = document.getElementById('customModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }

        function closeNotification() {
            const modal = document.getElementById('customModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.add('opacity-0', 'pointer-events-none');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('customModal');
            if (event.target === modal) {
                closeNotification();
            }
        }
    </script>
</body>
</html>