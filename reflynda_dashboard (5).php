<?php
// ==========================================
// REFLYNDA'S COZY AUTHOR DASHBOARD & PORTFOLIO
// Tema: Ungu & Pink Pastel (Aesthetic, Lucu, & Cozy)
// PHP Single-File dengan Session & AJAX-like Seamless Tab Switching
// Memastikan Pemutar Musik Spotify TETAP MENYALA (Tidak Ter-reload)
// ==========================================

session_start();

// Inisialisasi data session secara aman jika belum ada
if (!isset($_SESSION['projects'])) {
    $_SESSION['projects'] = [
        ["id" => 1, "title" => "Bulan di Ujung Jari", "genre" => "Fantasy Romance", "target" => 50000, "current" => 24500, "status" => "Drafting ✨", "year" => "2026"],
        ["id" => 2, "title" => "Kronik Kota Kertas", "genre" => "Slice of Life / Melodrama", "target" => 30000, "current" => 29800, "status" => "Editing ☕", "year" => "2026"],
        ["id" => 3, "title" => "Misteri Shinbi's House", "genre" => "Sci-Fi / Time Loop", "target" => 25000, "current" => 15200, "status" => "Plotting 📝", "year" => "2025"]
    ];
}

if (!isset($_SESSION['chapters'])) {
    $_SESSION['chapters'] = [
        ["id" => 1, "proj_id" => 1, "title" => "Bab 1: Surat Lilin", "word_count" => 2100, "content" => "Angin malam berembus pelan menembus celah jendela loteng kayu tua. Di atas meja berlapis debu tipis, sebuah kotak musik tua berdenting..."],
        ["id" => 2, "proj_id" => 1, "title" => "Bab 2: Kunci Emas", "word_count" => 1800, "content" => "Cahaya bulan purnama jatuh tepat di atas halaman buku harian bersampul kulit merah muda milik Reflynda..."],
        ["id" => 3, "proj_id" => 1, "title" => "Bab 3: Lorong Waktu", "word_count" => 2400, "content" => "Detik jam dinding berdetak lebih lambat dari biasanya. Aroma teh melati hangat menemani malam panjang..."],
        ["id" => 4, "proj_id" => 1, "title" => "Bab 4: Rahasia Loteng", "word_count" => 2150, "content" => "Tidak ada yang perlu ditakutkan dari halaman kosong. Jari-jarinya mulai menari di atas keyboard..."]
    ];
}

if (!isset($_SESSION['characters'])) {
    $_SESSION['characters'] = [
        ["name" => "Reflynda Starla", "role" => "Main Protagonist / Author Avatar", "desc" => "Gadis pemimpi yang tinggal di loteng penuh buku dan pena sihir.", "color" => "pink"],
        ["name" => "Aiden Sterling", "role" => "Mysterious Clockmaker", "desc" => "Pemuda berambut perak yang memegang rahasia waktu di kota kertas.", "color" => "purple"],
        ["name" => "Luna the Cat", "role" => "Cozy Companion", "desc" => "Kucing berbulu putih lembut yang selalu menemani sesi menulis malam.", "color" => "pink"]
    ];
}

if (!isset($_SESSION['works'])) {
    $_SESSION['works'] = [
        ["title" => "Melodi Hujan di Stasiun Senja", "genre" => "Romance / Novella", "year" => "2025", "desc" => "Kisah pertemuan dua insan di stasiun kereta saat senja berbalut rintik hujan."],
        ["title" => "Jurnal Penjaga Bintang", "genre" => "Cozy Fantasy / Cerpen", "year" => "2024", "desc" => "Koleksi cerpen tentang penjaga mercusuar yang merajut mimpi di angkasa luar."],
        ["title" => "Mischievous Kiss: AU Edition", "genre" => "AU Fiction", "year" => "2024", "desc" => "Adaptasi kisah cinta klasik dengan sentuhan latar kampus seni pastel yang manis."]
    ];
}

if (!isset($_SESSION['experiences'])) {
    $_SESSION['experiences'] = [
        ["year" => "2025 - Sekarang", "role" => "Penulis Cerpen & Novel AU Aktif", "desc" => "Aktif mempublikasikan serial fiksi web dan AU interaktif yang dibaca ribuan penikmat fiksi romansa & fantasi cozy."],
        ["year" => "2023 - 2024", "role" => "Pembuat Plot Cerita Fiksi & Worldbuilder", "desc" => "Merancang alur cerita kompleks, peta dunia fiksi, dan pengembangan karakter mendalam untuk berbagai proyek kolaborasi pembaca."],
        ["year" => "2022 - 2023", "role" => "Kolaborator Roleplay & Novel Series", "desc" => "Berpartisipasi aktif dalam komunitas kepenulisan kreatif, menyusun antologi cerpen remaja dan puisi."]
    ];
}

if (!isset($_SESSION['skills'])) {
    $_SESSION['skills'] = [
        ["name" => "Creative Writing & Storytelling", "level" => "96%"],
        ["name" => "Character Development & OC Design", "level" => "94%"],
        ["name" => "Worldbuilding & Lore Construction", "level" => "90%"],
        ["name" => "Alternate Universe (AU) & Roleplay Plotting", "level" => "95%"],
        ["name" => "Cerpen, Pantun, & Puisi Pendek", "level" => "92%"],
        ["name" => "Editing, Proofreading, & Dialogue Polish", "level" => "88%"]
    ];
}

$message = "";
$active_tab = isset($_POST['current_tab']) ? $_POST['current_tab'] : 'dashboard';
$active_chapter_id = isset($_POST['active_chapter_id']) ? intval($_POST['active_chapter_id']) : 1;

// Tangani form POST secara aman di file yang sama
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'add_project') {
        $newProj = [
            "id" => count($_SESSION['projects']) + 1,
            "title" => isset($_POST['proj_title']) ? htmlspecialchars($_POST['proj_title']) : 'Proyek Baru',
            "genre" => isset($_POST['proj_genre']) ? htmlspecialchars($_POST['proj_genre']) : 'Fiksi',
            "target" => isset($_POST['proj_target']) ? intval($_POST['proj_target']) : 30000,
            "current" => 0,
            "status" => "Plotting 📝",
            "year" => date("Y")
        ];
        array_unshift($_SESSION['projects'], $newProj);
        $message = "Yay! Proyek novel baru berhasil ditambahkan ke laman project! 🚀✨";
        $active_tab = 'projects';
    } elseif ($action === 'add_chapter') {
        $newChap = [
            "id" => count($_SESSION['chapters']) + 1,
            "proj_id" => 1,
            "title" => isset($_POST['chap_title']) ? htmlspecialchars($_POST['chap_title']) : 'Bab Baru',
            "word_count" => 0,
            "content" => "Mulai menulis cerita mukjizatmu di sini..."
        ];
        $_SESSION['chapters'][] = $newChap;
        $active_chapter_id = $newChap['id'];
        $message = "Bab baru berhasil dibuat di ruang tulis! ✨";
        $active_tab = 'workspace';
    } elseif ($action === 'save_chapter') {
        $chap_id = isset($_POST['chap_id']) ? intval($_POST['chap_id']) : 1;
        $content = isset($_POST['chap_content']) ? $_POST['chap_content'] : '';
        $active_chapter_id = $chap_id;
        
        foreach ($_SESSION['chapters'] as &$c) {
            if ($c['id'] === $chap_id) {
                $c['content'] = $content;
                $c['word_count'] = str_word_count(strip_tags($content));
                break;
            }
        }
        unset($c);
        $message = "Draf bab berhasil disimpan dengan aman! 💾💖";
        $active_tab = 'workspace';
    } elseif ($action === 'add_character') {
        $newChar = [
            "name" => isset($_POST['char_name']) ? htmlspecialchars($_POST['char_name']) : 'OC Baru',
            "role" => isset($_POST['char_role']) ? htmlspecialchars($_POST['char_role']) : 'Supporting Role',
            "desc" => isset($_POST['char_desc']) ? htmlspecialchars($_POST['char_desc']) : '',
            "color" => "pink"
        ];
        array_unshift($_SESSION['characters'], $newChar);
        $message = "Karakter OC baru berhasil ditambahkan ke daftar! 🌸";
        $active_tab = 'workspace';
    } elseif ($action === 'add_work') {
        $newWork = [
            "title" => isset($_POST['work_title']) ? htmlspecialchars($_POST['work_title']) : 'Karya Baru',
            "genre" => isset($_POST['work_genre']) ? htmlspecialchars($_POST['work_genre']) : 'Cerpen',
            "year" => isset($_POST['work_year']) ? htmlspecialchars($_POST['work_year']) : date("Y"),
            "desc" => isset($_POST['work_desc']) ? htmlspecialchars($_POST['work_desc']) : ''
        ];
        array_unshift($_SESSION['works'], $newWork);
        $message = "Hore! Cerita / karya baru berhasil ditambahkan ke portofolio! 📚💖";
        $active_tab = 'works';
    } elseif ($action === 'add_experience') {
        $newExp = [
            "year" => isset($_POST['exp_year']) ? htmlspecialchars($_POST['exp_year']) : '2026',
            "role" => isset($_POST['exp_role']) ? htmlspecialchars($_POST['exp_role']) : 'Pengalaman Baru',
            "desc" => isset($_POST['exp_desc']) ? htmlspecialchars($_POST['exp_desc']) : ''
        ];
        array_unshift($_SESSION['experiences'], $newExp);
        $message = "Yeay! Pengalaman baru berhasil ditambahkan ke rekam jejak penulis! ✨";
        $active_tab = 'experience';
    } elseif ($action === 'add_skill') {
        $newSkill = [
            "name" => isset($_POST['skill_name']) ? htmlspecialchars($_POST['skill_name']) : 'Keahlian Baru',
            "level" => (isset($_POST['skill_level']) ? intval($_POST['skill_level']) : 90) . '%'
        ];
        array_unshift($_SESSION['skills'], $newSkill);
        $message = "Keahlian baru berhasil ditambahkan ke daftar skill Reflynda! 🎨";
        $active_tab = 'profile';
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reflynda's Cozy Author Dashboard & Portfolio 🌸</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pastel: {
                            pinkBg: '#fff0f5',
                            lavenderBg: '#f3e8ff',
                            pinkCard: '#ffe4e6',
                            purpleCard: '#ede9fe',
                            softPink: '#f472b6',
                            softPurple: '#a855f7',
                            deepDark: '#3b2f4a'
                        }
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #fce7f3 0%, #fae8ff 50%, #ede9fe 100%);
            min-height: 100vh;
        }
        .serif-font {
            font-family: 'Playfair Display', serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            border: 2px solid rgba(244, 114, 182, 0.25);
            box-shadow: 0 10px 30px -5px rgba(236, 72, 153, 0.1);
        }
        .cute-badge {
            background: linear-gradient(135deg, #f472b6 0%, #c084fc 100%);
            color: white;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #f472b6;
            border-radius: 9999px;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-6px) rotate(2deg); }
        }
        .floating-anim {
            animation: float 4s ease-in-out infinite;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body class="text-pastel-deepDark flex flex-col min-h-full pb-24">

    <!-- Hidden form to maintain state & tab navigation without reload interruption -->
    <form id="navigation-form" method="POST" class="hidden">
        <input type="hidden" name="current_tab" id="form-current-tab" value="<?php echo $active_tab; ?>">
        <input type="hidden" name="active_chapter_id" id="form-active-chapter" value="<?php echo $active_chapter_id; ?>">
    </form>

    <!-- Top Navigation Header -->
    <header class="w-full glass-card sticky top-0 z-40 px-4 sm:px-8 py-3.5 flex items-center justify-between border-b border-pink-200">
        <div class="flex items-center space-x-3">
            <div class="w-11 h-11 rounded-2xl cute-badge flex items-center justify-center text-white shadow-md font-bold text-xl serif-font floating-anim">
                R✨
            </div>
            <div>
                <h1 class="font-bold text-base sm:text-lg text-purple-950 tracking-wide">Reflynda's Cozy Attic 🌸</h1>
                <p class="text-[11px] sm:text-xs text-pink-600 font-medium">Personal Author Dashboard & Portfolio</p>
            </div>
        </div>

        <!-- Navigation Tabs (Seamless JS tab switcher - Keeps Spotify music playing uninterrupted) -->
        <nav class="hidden lg:flex items-center space-x-1.5 bg-pink-100/70 p-1.5 rounded-full border border-pink-200 shadow-inner">
            <button type="button" onclick="switchTab('dashboard')" class="tab-btn px-4 py-2 rounded-full text-xs font-bold transition cursor-pointer <?php echo ($active_tab === 'dashboard') ? 'bg-white text-pink-600 shadow-sm' : 'text-purple-900 hover:bg-white/50'; ?>" data-tab="dashboard">
                <i class="fa-solid fa-house-heart mr-1.5"></i> Dashboard
            </button>
            <button type="button" onclick="switchTab('workspace')" class="tab-btn px-4 py-2 rounded-full text-xs font-semibold transition cursor-pointer <?php echo ($active_tab === 'workspace') ? 'bg-white text-pink-600 shadow-sm' : 'text-purple-900 hover:bg-white/50'; ?>" data-tab="workspace">
                <i class="fa-solid fa-feather mr-1.5"></i> Ruang Tulis & OC
            </button>
            <button type="button" onclick="switchTab('profile')" class="tab-btn px-4 py-2 rounded-full text-xs font-semibold transition cursor-pointer <?php echo ($active_tab === 'profile') ? 'bg-white text-pink-600 shadow-sm' : 'text-purple-900 hover:bg-white/50'; ?>" data-tab="profile">
                <i class="fa-solid fa-user-pen mr-1.5"></i> Profil & Keahlian
            </button>
            <button type="button" onclick="switchTab('experience')" class="tab-btn px-4 py-2 rounded-full text-xs font-semibold transition cursor-pointer <?php echo ($active_tab === 'experience') ? 'bg-white text-pink-600 shadow-sm' : 'text-purple-900 hover:bg-white/50'; ?>" data-tab="experience">
                <i class="fa-solid fa-timeline mr-1.5"></i> Pengalaman
            </button>
            <button type="button" onclick="switchTab('works')" class="tab-btn px-4 py-2 rounded-full text-xs font-semibold transition cursor-pointer <?php echo ($active_tab === 'works') ? 'bg-white text-pink-600 shadow-sm' : 'text-purple-900 hover:bg-white/50'; ?>" data-tab="works">
                <i class="fa-solid fa-book-bookmark mr-1.5"></i> Karya / Cerpen
            </button>
            <button type="button" onclick="switchTab('projects')" class="tab-btn px-4 py-2 rounded-full text-xs font-semibold transition cursor-pointer <?php echo ($active_tab === 'projects') ? 'bg-white text-pink-600 shadow-sm' : 'text-purple-900 hover:bg-white/50'; ?>" data-tab="projects">
                <i class="fa-solid fa-diagram-project mr-1.5"></i> Laman Project
            </button>
        </nav>

        <!-- Right Header info & Clock -->
        <div class="flex items-center space-x-2">
            <div class="flex items-center space-x-1.5 text-xs bg-white/80 px-3.5 py-2 rounded-full border border-pink-200 text-purple-900 font-semibold shadow-xs">
                <i class="fa-solid fa-clock text-pink-500"></i>
                <span id="live-clock">00:00</span>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Drawer Bar -->
    <div class="lg:hidden bg-pink-100/90 border-b border-pink-200 px-3 py-2.5 flex items-center justify-around text-xs font-bold text-purple-950 overflow-x-auto space-x-2">
        <button type="button" onclick="switchTab('dashboard')" class="tab-btn whitespace-nowrap px-3 py-1.5 rounded-xl <?php echo ($active_tab === 'dashboard') ? 'bg-white shadow-xs' : ''; ?>" data-tab="dashboard">Dashboard</button>
        <button type="button" onclick="switchTab('workspace')" class="tab-btn whitespace-nowrap px-3 py-1.5 rounded-xl <?php echo ($active_tab === 'workspace') ? 'bg-white shadow-xs' : ''; ?>" data-tab="workspace">Ruang Tulis</button>
        <button type="button" onclick="switchTab('profile')" class="tab-btn whitespace-nowrap px-3 py-1.5 rounded-xl <?php echo ($active_tab === 'profile') ? 'bg-white shadow-xs' : ''; ?>" data-tab="profile">Profil</button>
        <button type="button" onclick="switchTab('experience')" class="tab-btn whitespace-nowrap px-3 py-1.5 rounded-xl <?php echo ($active_tab === 'experience') ? 'bg-white shadow-xs' : ''; ?>" data-tab="experience">Pengalaman</button>
        <button type="button" onclick="switchTab('works')" class="tab-btn whitespace-nowrap px-3 py-1.5 rounded-xl <?php echo ($active_tab === 'works') ? 'bg-white shadow-xs' : ''; ?>" data-tab="works">Karya</button>
        <button type="button" onclick="switchTab('projects')" class="tab-btn whitespace-nowrap px-3 py-1.5 rounded-xl <?php echo ($active_tab === 'projects') ? 'bg-white shadow-xs' : ''; ?>" data-tab="projects">Project</button>
    </div>

    <!-- Main Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 py-6 space-y-6">

        <!-- PHP Alert Banner -->
        <?php if (!empty($message)): ?>
            <div class="glass-card bg-pink-100/90 border border-pink-300 text-purple-950 px-5 py-3.5 rounded-2xl shadow-md flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-xl">🎉</span>
                    <span class="text-sm font-bold"><?php echo $message; ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-pink-600 hover:text-pink-900 font-bold text-lg">&times;</button>
            </div>
        <?php endif; ?>

        <!-- ================= TAB 1: DASHBOARD UTAMA ================= -->
        <div id="tab-dashboard" class="tab-content <?php echo ($active_tab === 'dashboard') ? 'active' : ''; ?> space-y-6">
            
            <!-- Hero Banner: Welcome & Rotating Cute Quotes -->
            <section class="glass-card rounded-3xl p-6 sm:p-10 shadow-lg relative overflow-hidden bg-gradient-to-r from-pink-100/90 via-purple-100/90 to-pink-200/90 border-2 border-pink-300">
                <div class="absolute -right-8 -bottom-8 opacity-15 text-9xl text-pink-600 pointer-events-none">
                    <i class="fa-solid fa-feather-pointed"></i>
                </div>
                <div class="max-w-3xl relative z-10 space-y-3">
                    <span class="inline-block px-3.5 py-1 bg-white text-pink-600 text-xs font-bold rounded-full shadow-xs border border-pink-200">
                        ✨ Halo, Reflynda! Selamat datang di loteng menulismu ☕
                    </span>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-purple-950 tracking-tight serif-font">
                        Merajut Imajinasi, Mewarnai Dunia Cerita 🌸
                    </h2>
                    <p class="text-purple-900 text-sm sm:text-base leading-relaxed font-medium">
                        "Semangkuk teh hangat, alunan musik lo-fi dari pemutar Spotify di bawah, dan lembar cerita baru menantimu. Mari ciptakan keajaiban hari ini!"
                    </p>
                    
                    <!-- Random Rotating Quote Box -->
                    <div class="bg-white/80 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-pink-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <div class="flex items-start space-x-3">
                            <div class="p-2.5 bg-pink-200 text-pink-700 rounded-2xl text-base mt-0.5 shadow-xs">
                                <i class="fa-solid fa-quote-left"></i>
                            </div>
                            <div>
                                <p id="quote-text" class="italic text-sm sm:text-base text-purple-950 font-semibold">"Menulis adalah cara terbaik memeluk dunia dalam genggaman kata."</p>
                                <span id="quote-author" class="text-xs text-pink-600 font-bold">— Reflynda's Daily Muse</span>
                            </div>
                        </div>
                        <button type="button" onclick="rotateQuote()" class="px-4 py-2 cute-badge hover:opacity-90 text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap flex items-center space-x-1.5 cursor-pointer">
                            <i class="fa-solid fa-rotate"></i> <span>Ganti Quote</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Grid Quick Shortcuts (Clickable to jump directly) -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <button type="button" onclick="switchTab('workspace')" class="text-left glass-card p-5 rounded-3xl shadow-sm hover:shadow-md transition cursor-pointer group border-2 border-pink-200 hover:border-pink-400 bg-white/90">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-2xl bg-pink-200 text-pink-700 flex items-center justify-center text-lg group-hover:scale-110 transition shadow-inner">
                            <i class="fa-solid fa-feather"></i>
                        </div>
                        <span class="text-[11px] font-bold text-pink-600 bg-pink-50 px-3 py-1 rounded-full border border-pink-100">Fokus Nulis</span>
                    </div>
                    <h3 class="font-bold text-purple-950 text-base mb-1">Ruang Tulis & OC</h3>
                    <p class="text-xs text-purple-800">Tekan untuk buka editor bab novel dan daftar karakter OC.</p>
                </button>

                <button type="button" onclick="switchTab('profile')" class="text-left glass-card p-5 rounded-3xl shadow-sm hover:shadow-md transition cursor-pointer group border-2 border-purple-200 hover:border-purple-400 bg-white/90">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-200 text-purple-700 flex items-center justify-center text-lg group-hover:scale-110 transition shadow-inner">
                            <i class="fa-solid fa-user-sparkles"></i>
                        </div>
                        <span class="text-[11px] font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full border border-purple-100">Profil & Skill</span>
                    </div>
                    <h3 class="font-bold text-purple-950 text-base mb-1">Keahlian Penulis</h3>
                    <p class="text-xs text-purple-800">Tekan untuk cek skill storytelling, worldbuilding & profil author.</p>
                </button>

                <button type="button" onclick="switchTab('works')" class="text-left glass-card p-5 rounded-3xl shadow-sm hover:shadow-md transition cursor-pointer group border-2 border-pink-200 hover:border-pink-400 bg-white/90">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-2xl bg-pink-200 text-pink-700 flex items-center justify-center text-lg group-hover:scale-110 transition shadow-inner">
                            <i class="fa-solid fa-book-bookmark"></i>
                        </div>
                        <span class="text-[11px] font-bold text-pink-600 bg-pink-50 px-3 py-1 rounded-full border border-pink-100"><?php echo count($_SESSION['works']); ?> Karya</span>
                    </div>
                    <h3 class="font-bold text-purple-950 text-base mb-1">Karya & Cerpen</h3>
                    <p class="text-xs text-purple-800">Tekan untuk lihat koleksi cerpen, novel, dan portofolio karya.</p>
                </button>

                <button type="button" onclick="switchTab('projects')" class="text-left glass-card p-5 rounded-3xl shadow-sm hover:shadow-md transition cursor-pointer group border-2 border-purple-200 hover:border-purple-400 bg-white/90">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-200 text-purple-700 flex items-center justify-center text-lg group-hover:scale-110 transition shadow-inner">
                            <i class="fa-solid fa-diagram-project"></i>
                        </div>
                        <span class="text-[11px] font-bold text-purple-600 bg-purple-50 px-3 py-1 rounded-full border border-purple-100">Laman Project</span>
                    </div>
                    <h3 class="font-bold text-purple-950 text-base mb-1">Status Proyek</h3>
                    <p class="text-xs text-purple-800">Tekan untuk pantau target kata dan progress novel aktif.</p>
                </button>

            </section>

            <!-- Main Dashboard Content Grid: Projects & Active Calendar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left 2 Cols: Active Projects Preview -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-sm border-2 border-pink-200 bg-white/90">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="font-bold text-purple-950 text-lg flex items-center space-x-2 serif-font">
                                <i class="fa-solid fa-book-open text-pink-500"></i>
                                <span>Proyek Novel & AU Aktif Reflynda</span>
                            </h3>
                            <button type="button" onclick="switchTab('projects')" class="text-xs text-pink-600 hover:underline font-bold">Buka Laman Project &rarr;</button>
                        </div>
                        
                        <div class="space-y-4">
                            <?php foreach ($_SESSION['projects'] as $proj): 
                                $target = isset($proj['target']) && $proj['target'] > 0 ? $proj['target'] : 1;
                                $current = isset($proj['current']) ? $proj['current'] : 0;
                                $percent = round(($current / $target) * 100);
                            ?>
                                <div class="bg-pink-50/70 p-4.5 rounded-2xl border border-pink-200 shadow-xs space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <span class="text-[11px] px-2.5 py-0.5 bg-pink-200 text-pink-800 rounded-md font-bold"><?php echo isset($proj['genre']) ? $proj['genre'] : ''; ?></span>
                                            <h4 class="font-bold text-purple-950 text-base mt-1"><?php echo isset($proj['title']) ? $proj['title'] : ''; ?></h4>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-bold text-purple-800 bg-purple-200 px-3 py-1 rounded-full"><?php echo isset($proj['status']) ? $proj['status'] : ''; ?></span>
                                            <button type="button" onclick="switchTab('workspace')" class="px-3 py-1 bg-pink-500 hover:bg-pink-600 text-white rounded-xl text-xs font-bold shadow-xs transition cursor-pointer">
                                                Tulis ✍️
                                            </button>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-xs text-purple-900 font-semibold">
                                            <span>Progress: <?php echo number_format($current); ?> kata</span>
                                            <span>Target: <?php echo number_format($target); ?> kata (<?php echo $percent; ?>%)</span>
                                        </div>
                                        <div class="w-full bg-pink-100 h-3 rounded-full overflow-hidden p-0.5 border border-pink-200">
                                            <div class="bg-gradient-to-r from-pink-400 to-purple-500 h-full rounded-full transition-all duration-500" style="width: <?php echo $percent; ?>%;"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Col: Active Cute Calendar -->
                <div class="space-y-6">
                    <div class="glass-card rounded-3xl p-6 shadow-sm border-2 border-pink-200 bg-white/90">
                        <div class="flex items-center justify-between mb-4">
                            <h3 id="cal-month-year" class="font-bold text-purple-950 text-base serif-font">September 2026</h3>
                            <div class="flex space-x-1">
                                <button type="button" onclick="changeMonth(-1)" class="w-7 h-7 rounded-xl bg-pink-100 hover:bg-pink-200 text-pink-700 flex items-center justify-center text-xs transition cursor-pointer font-bold">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                                <button type="button" onclick="changeMonth(1)" class="w-7 h-7 rounded-xl bg-pink-100 hover:bg-pink-200 text-pink-700 flex items-center justify-center text-xs transition cursor-pointer font-bold">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Days Header -->
                        <div class="grid grid-cols-7 gap-1 text-center text-xs font-bold text-pink-500 mb-2">
                            <span>Sn</span><span>Sl</span><span>Rb</span><span>Km</span><span>Jm</span><span>Sb</span><span>Mg</span>
                        </div>

                        <!-- Calendar Grid Days -->
                        <div id="calendar-days" class="grid grid-cols-7 gap-1 text-center text-xs font-semibold">
                            <!-- Populated via JavaScript -->
                        </div>

                        <div class="mt-4 pt-4 border-t border-pink-200 text-center">
                            <div class="text-xs font-bold text-purple-950 mb-1">🔥 Streak Menulis Aktif: <span class="text-pink-600">18 Hari</span></div>
                            <p class="text-[11px] text-pink-600 italic">"Setiap hari menulis walau 100 kata adalah kemenangan kecil."</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- ================= TAB 2: RUANG TULIS & KARAKTER OC ================= -->
        <div id="tab-workspace" class="tab-content <?php echo ($active_tab === 'workspace') ? 'active' : ''; ?> space-y-6">
            <?php 
            $current_chap = null;
            foreach ($_SESSION['chapters'] as $c) {
                if ($c['id'] === $active_chapter_id) {
                    $current_chap = $c;
                    break;
                }
            }
            if (!$current_chap && count($_SESSION['chapters']) > 0) {
                $current_chap = $_SESSION['chapters'][0];
                $active_chapter_id = $current_chap['id'];
            }
            ?>
            <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-sm border-2 border-pink-200 bg-white/90">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <span class="text-xs px-3 py-1 bg-pink-200 text-pink-800 rounded-full font-bold">Editor Bab & Plotting</span>
                        <h2 class="text-2xl font-bold text-purple-950 mt-1 serif-font">Ruang Tulis Reflynda ✨</h2>
                    </div>
                    <form method="POST" class="inline">
                        <input type="hidden" name="action" value="add_chapter">
                        <input type="hidden" name="current_tab" value="workspace">
                        <button type="submit" class="px-4 py-2 cute-badge text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-1.5 cursor-pointer">
                            <i class="fa-solid fa-plus"></i> <span>Buat Bab Baru</span>
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Sidebar Chapters -->
                    <div class="lg:col-span-1 bg-pink-50/80 p-4 rounded-2xl border border-pink-200 space-y-3">
                        <h4 class="font-bold text-purple-950 text-sm flex items-center justify-between">
                            <span>Daftar Bab Novel</span>
                            <span class="text-xs text-pink-600"><?php echo count($_SESSION['chapters']); ?> Bab</span>
                        </h4>
                        <div class="space-y-2 max-h-96 overflow-y-auto custom-scrollbar pr-1">
                            <?php foreach ($_SESSION['chapters'] as $chap): 
                                $is_active = ($chap['id'] === $active_chapter_id);
                            ?>
                                <form method="POST" class="block">
                                    <input type="hidden" name="current_tab" value="workspace">
                                    <input type="hidden" name="active_chapter_id" value="<?php echo $chap['id']; ?>">
                                    <button type="submit" class="w-full text-left p-3 rounded-xl text-xs font-semibold border transition shadow-xs <?php echo $is_active ? 'cute-badge text-white border-pink-400' : 'bg-white text-purple-900 border-pink-100 hover:bg-pink-100'; ?>">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold truncate"><?php echo isset($chap['title']) ? $chap['title'] : ''; ?></span>
                                            <?php if ($is_active): ?><i class="fa-solid fa-check ml-1"></i><?php endif; ?>
                                        </div>
                                        <div class="text-[10px] opacity-80 mt-1"><?php echo isset($chap['word_count']) ? number_format($chap['word_count']) : 0; ?> kata</div>
                                    </button>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Main Textarea Editor with PHP Form Save -->
                    <div class="lg:col-span-3 space-y-3">
                        <form method="POST" class="space-y-3">
                            <input type="hidden" name="action" value="save_chapter">
                            <input type="hidden" name="current_tab" value="workspace">
                            <input type="hidden" name="chap_id" value="<?php echo $active_chapter_id; ?>">
                            <input type="hidden" name="active_chapter_id" value="<?php echo $active_chapter_id; ?>">
                            
                            <div class="flex items-center justify-between bg-pink-100/70 p-3 rounded-xl border border-pink-200">
                                <span class="font-bold text-purple-950 text-sm">Sedang Menulis: <?php echo isset($current_chap['title']) ? $current_chap['title'] : ''; ?></span>
                                <button type="submit" class="px-4 py-1.5 cute-badge text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer hover:opacity-95">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Draf ke Database
                                </button>
                            </div>

                            <div class="bg-white p-2 rounded-2xl border-2 border-pink-200 shadow-inner">
                                <textarea name="chap_content" id="writer-textarea" rows="14" class="w-full p-4 bg-transparent focus:outline-none text-purple-950 text-sm leading-relaxed font-medium resize-none custom-scrollbar" placeholder="Mulai menulis cerita mukjizatmu di sini, Reflynda..."><?php echo isset($current_chap['content']) ? htmlspecialchars($current_chap['content']) : ''; ?></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Section Karakter OC (Original Characters) -->
                <div class="mt-10 pt-8 border-t-2 border-pink-200 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs px-3 py-1 bg-purple-200 text-purple-800 rounded-full font-bold">Worldbuilding & Plotting</span>
                            <h3 class="text-xl font-bold text-purple-950 mt-1 serif-font">Karakter OC (Original Characters) 🌸</h3>
                        </div>
                        <button type="button" onclick="toggleOCModal()" class="px-4 py-2 cute-badge text-white rounded-xl text-xs font-bold shadow-xs hover:opacity-95 transition cursor-pointer">
                            <i class="fa-solid fa-user-plus mr-1"></i> Tambah Karakter OC
                        </button>
                    </div>

                    <!-- Form Tambah OC (Hidden by default) -->
                    <div id="oc-form-box" class="hidden bg-pink-50 p-5 rounded-2xl border-2 border-pink-200 shadow-xs">
                        <h4 class="font-bold text-purple-950 text-sm mb-3">Tambah Karakter OC Baru</h4>
                        <form method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="hidden" name="action" value="add_character">
                            <input type="hidden" name="current_tab" value="workspace">
                            <div>
                                <label class="block text-[11px] font-bold text-purple-900 mb-1">Nama Karakter</label>
                                <input type="text" name="char_name" required placeholder="Contoh: Selene Moonlight" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-purple-900 mb-1">Peran / Role</label>
                                <input type="text" name="char_role" required placeholder="Contoh: Second Lead / Mage" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold text-purple-900 mb-1">Deskripsi & Latar Belakang</label>
                                <textarea name="char_desc" rows="2" required placeholder="Ceritakan kepribadian dan latar belakang karakter..." class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400"></textarea>
                            </div>
                            <div class="sm:col-span-2 flex justify-end space-x-2">
                                <button type="button" onclick="toggleOCModal()" class="px-4 py-2 bg-pink-200 text-pink-800 rounded-xl text-xs font-bold cursor-pointer">Batal</button>
                                <button type="submit" class="px-5 py-2 cute-badge text-white rounded-xl text-xs font-bold cursor-pointer shadow-xs">Simpan OC</button>
                            </div>
                        </form>
                    </div>

                    <!-- Grid Karakter -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <?php foreach ($_SESSION['characters'] as $char): ?>
                            <div class="bg-pink-50/70 p-5 rounded-2xl border border-pink-200 shadow-xs space-y-2.5 flex flex-col justify-between">
                                <div>
                                    <span class="text-xs px-2.5 py-0.5 bg-pink-200 text-pink-800 rounded-full font-bold"><?php echo isset($char['role']) ? $char['role'] : ''; ?></span>
                                    <h4 class="font-bold text-purple-950 text-base mt-2 serif-font"><?php echo isset($char['name']) ? $char['name'] : ''; ?></h4>
                                    <p class="text-xs text-purple-900 mt-1 leading-relaxed font-medium"><?php echo isset($char['desc']) ? $char['desc'] : ''; ?></p>
                                </div>
                                <div class="pt-3 border-t border-pink-200 text-[11px] text-pink-600 font-bold flex items-center">
                                    <i class="fa-solid fa-heart mr-1.5"></i> Karakter Aktif Story
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- ================= TAB 3: PROFIL & KEAHLIAN (PROFILE & SKILLS) ================= -->
        <div id="tab-profile" class="tab-content <?php echo ($active_tab === 'profile') ? 'active' : ''; ?> space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-10 shadow-sm border-2 border-pink-200 bg-white/90 space-y-8">
                
                <!-- Author Bio Profile Header -->
                <div class="flex flex-col md:flex-row items-center gap-6 pb-8 border-b border-pink-200">
                    <div class="w-28 h-28 rounded-full cute-badge flex items-center justify-center text-white text-4xl font-extrabold shadow-lg serif-font floating-anim">
                        R🌸
                    </div>
                    <div class="space-y-2 text-center md:text-left">
                        <span class="px-3 py-1 bg-pink-100 text-pink-700 text-xs font-bold rounded-full">Lead Author & Storyteller</span>
                        <h2 class="text-3xl font-bold text-purple-950 serif-font">Reflynda</h2>
                        <p class="text-purple-900 text-sm max-w-2xl leading-relaxed font-medium">
                            Seorang penulis fiksi romansa, fantasi cozy, dan kreator Alternate Universe (AU) yang jatuh cinta pada aroma buku tua, teh hangat, dan rajutan kata yang menghangatkan hati pembaca.
                        </p>
                    </div>
                </div>

                <!-- Keahlian (Skills) Section + Form Tambah Skill -->
                <div class="space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <h3 class="text-xl font-bold text-purple-950 serif-font flex items-center space-x-2">
                            <i class="fa-solid fa-wand-magic-sparkles text-pink-500"></i>
                            <span>Keahlian Kepenulisan (Skills)</span>
                        </h3>
                        <button type="button" onclick="toggleSkillModal()" class="px-4 py-2 cute-badge text-white rounded-xl text-xs font-bold shadow-xs hover:opacity-95 transition cursor-pointer">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Keahlian Baru
                        </button>
                    </div>

                    <!-- Form Tambah Skill (Hidden by default) -->
                    <div id="skill-form-box" class="hidden bg-pink-50 p-5 rounded-2xl border-2 border-pink-200 shadow-xs">
                        <h4 class="font-bold text-purple-950 text-sm mb-3">Tambah Skill / Keahlian Penulis</h4>
                        <form method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <input type="hidden" name="action" value="add_skill">
                            <input type="hidden" name="current_tab" value="profile">
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold text-purple-900 mb-1">Nama Keahlian</label>
                                <input type="text" name="skill_name" required placeholder="Contoh: Dialog Writing & Romance Trope" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-purple-900 mb-1">Level (%)</label>
                                <input type="number" name="skill_level" required min="50" max="100" value="95" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                            </div>
                            <div class="sm:col-span-3 flex justify-end space-x-2">
                                <button type="button" onclick="toggleSkillModal()" class="px-4 py-2 bg-pink-200 text-pink-800 rounded-xl text-xs font-bold cursor-pointer">Batal</button>
                                <button type="submit" class="px-5 py-2 cute-badge text-white rounded-xl text-xs font-bold cursor-pointer shadow-xs">Simpan Skill</button>
                            </div>
                        </form>
                    </div>

                    <!-- Grid Skills -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <?php foreach ($_SESSION['skills'] as $skill): ?>
                            <div class="bg-pink-50/70 p-5 rounded-2xl border border-pink-200 shadow-xs space-y-2.5">
                                <div class="flex justify-between items-center text-sm font-bold text-purple-950">
                                    <span class="flex items-center space-x-2">
                                        <i class="fa-solid fa-heart text-pink-500 text-xs"></i>
                                        <span><?php echo isset($skill['name']) ? $skill['name'] : ''; ?></span>
                                    </span>
                                    <span class="text-pink-600 bg-pink-100 px-3 py-0.5 rounded-full text-xs font-bold"><?php echo isset($skill['level']) ? $skill['level'] : ''; ?></span>
                                </div>
                                <div class="w-full bg-pink-100 h-2.5 rounded-full overflow-hidden border border-pink-200">
                                    <div class="bg-gradient-to-r from-pink-400 to-purple-400 h-full rounded-full transition-all duration-500" style="width: <?php echo isset($skill['level']) ? $skill['level'] : '90%'; ?>;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

        <!-- ================= TAB 4: PENGALAMAN (EXPERIENCE) ================= -->
        <div id="tab-experience" class="tab-content <?php echo ($active_tab === 'experience') ? 'active' : ''; ?> space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-10 shadow-sm border-2 border-pink-200 bg-white/90 space-y-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs px-3 py-1 bg-purple-200 text-purple-800 rounded-full font-bold">Rekam Jejak Penulis</span>
                        <h2 class="text-2xl font-bold text-purple-950 mt-1 serif-font">Pengalaman Kepenulisan (Experience) ✨</h2>
                    </div>
                    <button type="button" onclick="toggleExpModal()" class="px-4 py-2 cute-badge text-white rounded-xl text-xs font-bold shadow-xs hover:opacity-95 transition cursor-pointer">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Pengalaman Baru
                    </button>
                </div>

                <!-- Form Tambah Pengalaman (Hidden by default) -->
                <div id="exp-form-box" class="hidden bg-pink-50 p-5 rounded-2xl border-2 border-pink-200 shadow-xs">
                    <h4 class="font-bold text-purple-950 text-sm mb-3">Tambah Pengalaman Kepenulisan Baru</h4>
                    <form method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="hidden" name="action" value="add_experience">
                        <input type="hidden" name="current_tab" value="experience">
                        <div>
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Tahun / Periode</label>
                            <input type="text" name="exp_year" required placeholder="Contoh: 2026 - Sekarang" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Peran / Posisi</label>
                            <input type="text" name="exp_role" required placeholder="Contoh: Penulis Novel Tamu" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Deskripsi Pengalaman</label>
                            <textarea name="exp_desc" rows="2" required placeholder="Ceritakan detail pengalamanmu di sini..." class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400"></textarea>
                        </div>
                        <div class="sm:col-span-2 flex justify-end space-x-2">
                            <button type="button" onclick="toggleExpModal()" class="px-4 py-2 bg-pink-200 text-pink-800 rounded-xl text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2 cute-badge text-white rounded-xl text-xs font-bold cursor-pointer shadow-xs">Simpan Pengalaman</button>
                        </div>
                    </form>
                </div>

                <!-- Timeline List -->
                <div class="space-y-6 relative before:absolute before:inset-0 before:left-4 before:w-0.5 before:bg-pink-300">
                    <?php foreach ($_SESSION['experiences'] as $exp): ?>
                        <div class="relative pl-10 space-y-2">
                            <div class="absolute left-2 top-1.5 w-4 h-4 rounded-full bg-pink-500 ring-4 ring-pink-100 shadow-sm"></div>
                            <span class="text-xs font-bold text-pink-700 bg-pink-100 px-3 py-1 rounded-full"><?php echo isset($exp['year']) ? $exp['year'] : ''; ?></span>
                            <h3 class="font-bold text-purple-950 text-lg serif-font"><?php echo isset($exp['role']) ? $exp['role'] : ''; ?></h3>
                            <p class="text-xs sm:text-sm text-purple-900 leading-relaxed bg-pink-50/70 p-4 rounded-2xl border border-pink-200 shadow-xs font-medium">
                                <?php echo isset($exp['desc']) ? $exp['desc'] : ''; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <!-- ================= TAB 5: KARYA / CERPEN (WORKS) ================= -->
        <div id="tab-works" class="tab-content <?php echo ($active_tab === 'works') ? 'active' : ''; ?> space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-10 shadow-sm border-2 border-pink-200 bg-white/90">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <span class="text-xs px-3 py-1 bg-pink-200 text-pink-800 rounded-full font-bold">Portofolio Buku & Cerpen</span>
                        <h2 class="text-2xl font-bold text-purple-950 mt-1 serif-font">Daftar Karya & Publikasi Reflynda 📚</h2>
                    </div>
                    <button type="button" onclick="toggleWorkModal()" class="px-4 py-2 cute-badge text-white rounded-xl text-xs font-bold shadow-xs hover:opacity-95 transition cursor-pointer">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Karya / Cerita Baru
                    </button>
                </div>

                <!-- Form Tambah Karya (Hidden by default) -->
                <div id="work-form-box" class="hidden mb-6 bg-pink-50 p-5 rounded-2xl border-2 border-pink-200 shadow-xs">
                    <h4 class="font-bold text-purple-950 text-sm mb-3">Publikasikan Cerpen / Karya Baru</h4>
                    <form method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <input type="hidden" name="action" value="add_work">
                        <input type="hidden" name="current_tab" value="works">
                        <div>
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Judul Karya</label>
                            <input type="text" name="work_title" required placeholder="Contoh: Rembulan di Kota Tua" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Genre / Tema</label>
                            <input type="text" name="work_genre" required placeholder="Contoh: Romance / Cerpen" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Tahun Terbit</label>
                            <input type="text" name="work_year" required value="2026" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Sinopsis / Deskripsi Singkat</label>
                            <textarea name="work_desc" rows="2" required placeholder="Deskripsi singkat cerita..." class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400"></textarea>
                        </div>
                        <div class="sm:col-span-3 flex justify-end space-x-2">
                            <button type="button" onclick="toggleWorkModal()" class="px-4 py-2 bg-pink-200 text-pink-800 rounded-xl text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2 cute-badge text-white rounded-xl text-xs font-bold cursor-pointer shadow-xs">Simpan Karya</button>
                        </div>
                    </form>
                </div>

                <!-- Grid Works -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($_SESSION['works'] as $work): ?>
                        <div class="bg-pink-50/70 rounded-2xl p-5 border border-pink-200 shadow-xs space-y-3 hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs px-2.5 py-0.5 bg-pink-200 text-pink-800 rounded-full font-bold"><?php echo isset($work['genre']) ? $work['genre'] : ''; ?></span>
                                    <span class="text-xs text-pink-600 font-bold"><?php echo isset($work['year']) ? $work['year'] : ''; ?></span>
                                </div>
                                <h3 class="font-bold text-purple-950 text-base mb-1 serif-font"><?php echo isset($work['title']) ? $work['title'] : ''; ?></h3>
                                <p class="text-xs text-purple-900 leading-relaxed font-medium"><?php echo isset($work['desc']) ? $work['desc'] : ''; ?></p>
                            </div>
                            <div class="pt-3 border-t border-pink-200 flex items-center justify-between text-xs">
                                <span class="text-pink-600 font-bold"><i class="fa-solid fa-circle-check mr-1"></i> Publikasi</span>
                                <button type="button" onclick="switchTab('workspace')" class="text-purple-900 hover:underline font-bold cursor-pointer">Buka Editor &rarr;</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <!-- ================= TAB 6: LAMAN PROJECT ================= -->
        <div id="tab-projects" class="tab-content <?php echo ($active_tab === 'projects') ? 'active' : ''; ?> space-y-6">
            <div class="glass-card rounded-3xl p-6 sm:p-10 shadow-sm border-2 border-pink-200 bg-white/90">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <span class="text-xs px-3 py-1 bg-purple-200 text-purple-800 rounded-full font-bold">Manajemen Proyek Novel</span>
                        <h2 class="text-2xl font-bold text-purple-950 mt-1 serif-font">Laman Project Pribadi Reflynda 🚀</h2>
                    </div>
                    <button type="button" onclick="toggleProjectModal()" class="px-4 py-2 cute-badge text-white rounded-xl text-xs font-bold shadow-xs hover:opacity-95 transition cursor-pointer">
                        <i class="fa-solid fa-plus mr-1"></i> Buat Proyek Novel Baru
                    </button>
                </div>

                <!-- Form Tambah Proyek Baru (Hidden by default) -->
                <div id="project-form-box" class="hidden mb-6 bg-pink-50 p-5 rounded-2xl border-2 border-pink-200 shadow-xs">
                    <h4 class="font-bold text-purple-950 text-sm mb-3">Tambah Proyek Novel / AU Baru</h4>
                    <form method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <input type="hidden" name="action" value="add_project">
                        <input type="hidden" name="current_tab" value="projects">
                        <div>
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Judul Proyek</label>
                            <input type="text" name="proj_title" required placeholder="Contoh: Senja di Ujung Dunia" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Genre / Tema</label>
                            <input type="text" name="proj_genre" required placeholder="Contoh: Fantasy Romance" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-purple-900 mb-1">Target Kata</label>
                            <input type="number" name="proj_target" required value="40000" class="w-full px-3.5 py-2 bg-white border border-pink-200 rounded-xl text-xs text-purple-950 focus:outline-none focus:ring-2 focus:ring-pink-400">
                        </div>
                        <div class="sm:col-span-3 flex justify-end space-x-2">
                            <button type="button" onclick="toggleProjectModal()" class="px-4 py-2 bg-pink-200 text-pink-800 rounded-xl text-xs font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2 cute-badge text-white rounded-xl text-xs font-bold cursor-pointer shadow-xs">Simpan Proyek</button>
                        </div>
                    </form>
                </div>

                <div class="space-y-6">
                    <?php foreach ($_SESSION['projects'] as $proj): 
                        $target = isset($proj['target']) && $proj['target'] > 0 ? $proj['target'] : 1;
                        $current = isset($proj['current']) ? $proj['current'] : 0;
                        $percent = round(($current / $target) * 100);
                    ?>
                        <div class="bg-pink-50/80 p-6 rounded-3xl border border-pink-200 shadow-xs space-y-4">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-xs px-2.5 py-0.5 bg-pink-200 text-pink-800 rounded-md font-bold"><?php echo isset($proj['genre']) ? $proj['genre'] : ''; ?></span>
                                        <span class="text-xs font-bold text-purple-900 bg-purple-200 px-3 py-0.5 rounded-full"><?php echo isset($proj['status']) ? $proj['status'] : ''; ?></span>
                                    </div>
                                    <h3 class="font-bold text-purple-950 text-lg serif-font"><?php echo isset($proj['title']) ? $proj['title'] : ''; ?></h3>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <div class="text-xs text-purple-800 font-semibold">Pencapaian Kata</div>
                                        <div class="text-sm font-bold text-purple-950"><?php echo number_format($current); ?> / <?php echo number_format($target); ?></div>
                                    </div>
                                    <button type="button" onclick="switchTab('workspace')" class="px-4 py-2 cute-badge text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer">
                                        Tulis ✍️
                                    </button>
                                </div>
                            </div>
                            
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs font-bold text-purple-900">
                                    <span>Progress Pengerjaan</span>
                                    <span><?php echo $percent; ?>% Selesai</span>
                                </div>
                                <div class="w-full bg-pink-100 h-3 rounded-full overflow-hidden p-0.5 border border-pink-200">
                                    <div class="bg-gradient-to-r from-pink-400 to-purple-500 h-full rounded-full transition-all duration-500" style="width: <?php echo $percent; ?>%;"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </main>

    <!-- ================= STICKY PERSISTENT SPOTIFY MUSIC PLAYER DOCK (BOTTOM CORNER) ================= -->
    <div id="sticky-spotify-dock" class="fixed bottom-3 right-3 z-50 glass-card bg-white/95 rounded-3xl p-3 shadow-2xl border-2 border-pink-300 flex items-center space-x-3 transition-all duration-300">
        <div id="spotify-minimized" class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-green-500 text-white flex items-center justify-center text-lg shadow-md animate-pulse">
                <i class="fa-brands fa-spotify"></i>
            </div>
            <div class="hidden sm:block">
                <div class="text-xs font-bold text-purple-950">Spotify Writer's Playlist 🎧</div>
                <div class="text-[10px] text-pink-600 font-medium">Musik tetap menyala saat pindah tab</div>
            </div>
            <div class="flex items-center space-x-1.5">
                <button type="button" onclick="toggleSpotifyPlayer()" class="px-3 py-1.5 cute-badge rounded-xl text-xs font-bold shadow-xs cursor-pointer hover:opacity-90">
                    <span id="spotify-toggle-btn-text">Buka Player</span>
                </button>
            </div>
        </div>

        <!-- Expanded Spotify Iframe Container -->
        <div id="spotify-expanded" class="hidden flex-col space-y-2 w-72 sm:w-80">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-xl bg-green-500 text-white flex items-center justify-center text-sm">
                        <i class="fa-brands fa-spotify"></i>
                    </div>
                    <span class="text-xs font-bold text-purple-950">Reflynda's Cozy Playlist</span>
                </div>
                <button type="button" onclick="toggleSpotifyPlayer()" class="text-pink-600 hover:text-pink-900 font-bold text-base px-2">&times;</button>
            </div>
            <div class="rounded-2xl overflow-hidden border border-pink-200">
                <iframe style="border-radius:12px" src="https://open.spotify.com/embed/playlist/47b48Xdzza41qDxakrObYx?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full text-center py-6 text-xs text-pink-700 font-bold border-t border-pink-200/60 mt-auto bg-white/40">
        <p>Reflynda's Cozy Author Dashboard &copy; 2026 ✨ Made with lots of pink, lavender & love.</p>
    </footer>

    <!-- JavaScript Interactive Code -->
    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const clockEl = document.getElementById('live-clock');
            if (clockEl) clockEl.innerText = hours + ':' + minutes;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Seamless Tab Switching (Tanpa reload halaman, menjaga musik Spotify tetap menyala tanpa putus)
        function switchTab(tabName) {
            // Sembunyikan semua konten tab
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(el => el.classList.remove('active'));

            // Tampilkan tab yang dipilih
            const targetTab = document.getElementById('tab-' + tabName);
            if (targetTab) targetTab.classList.add('active');

            // Update status aktif pada tombol navigasi desktop & mobile
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => {
                if (btn.getAttribute('data-tab') === tabName) {
                    btn.classList.add('bg-white', 'text-pink-600', 'shadow-sm');
                    btn.classList.remove('text-purple-900', 'hover:bg-white/50');
                } else if (btn.tagName === 'BUTTON' && btn.getAttribute('data-tab')) {
                    btn.classList.remove('bg-white', 'text-pink-600', 'shadow-sm');
                    btn.classList.add('text-purple-900', 'hover:bg-white/50');
                }
            });

            // Update hidden form field
            const hiddenInput = document.getElementById('form-current-tab');
            if (hiddenInput) hiddenInput.value = tabName;

            // Scroll ke atas dengan mulus
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Toggle Spotify Player Expanded/Minimized
        let isSpotifyExpanded = false;
        function toggleSpotifyPlayer() {
            isSpotifyExpanded = !isSpotifyExpanded;
            const expandedEl = document.getElementById('spotify-expanded');
            const minimizedEl = document.getElementById('spotify-minimized');
            const btnText = document.getElementById('spotify-toggle-btn-text');
            const dock = document.getElementById('sticky-spotify-dock');

            if (isSpotifyExpanded) {
                expandedEl.classList.remove('hidden');
                expandedEl.classList.add('flex');
                minimizedEl.classList.add('hidden');
                if (btnText) btnText.innerText = 'Tutup';
            } else {
                expandedEl.classList.add('hidden');
                expandedEl.classList.remove('flex');
                minimizedEl.classList.remove('hidden');
                if (btnText) btnText.innerText = 'Buka Player';
            }
        }

        // Random Rotating Quotes (Otomatis & Manual)
        const quotes = [
            { text: "Menulis adalah cara terbaik memeluk dunia dalam genggaman kata.", author: "Reflynda's Daily Muse" },
            { text: "Draf pertama tidak harus sempurna, ia hanya harus ditulis dengan jujur.", author: "Stephen King" },
            { text: "Di dalam setiap karakter dan AU yang kau ciptakan, ada secercah jiwa yang kauhidupkan.", author: "Cozy Attic Journal" },
            { text: "Secangkir teh hangat, alunan melodi Spotify, dan lembar cerita yang menunggu diselesaikan.", author: "Author Diary" },
            { text: "Kata-kata memiliki kekuatan untuk menyembuhkan ruang sunyi di dalam hati pembaca.", author: "Virginia Woolf" }
        ];
        let currentQuoteIndex = 0;

        function rotateQuote() {
            currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
            const qText = document.getElementById('quote-text');
            const qAuthor = document.getElementById('quote-author');
            if (qText && qAuthor) {
                qText.style.opacity = 0;
                qAuthor.style.opacity = 0;
                setTimeout(() => {
                    qText.innerText = `"${quotes[currentQuoteIndex].text}"`;
                    qAuthor.innerText = `— ${quotes[currentQuoteIndex].author}`;
                    qText.style.opacity = 1;
                    qAuthor.style.opacity = 1;
                }, 200);
            }
        }
        setInterval(rotateQuote, 10000);

        // Interactive Calendar Generator (September 2026)
        let currentDate = new Date(2026, 8, 1);
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            const monthYearEl = document.getElementById('cal-month-year');
            if (monthYearEl) monthYearEl.innerText = `${monthNames[month]} ${year}`;

            const firstDayIndex = new Date(year, month, 1).getDay();
            const adjustedFirstDay = (firstDayIndex === 0) ? 6 : firstDayIndex - 1;
            const totalDays = new Date(year, month + 1, 0).getDate();

            const daysContainer = document.getElementById('calendar-days');
            if (!daysContainer) return;
            daysContainer.innerHTML = '';

            for (let i = 0; i < adjustedFirstDay; i++) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'py-1.5 text-pink-200';
                daysContainer.appendChild(emptyDiv);
            }

            for (let day = 1; day <= totalDays; day++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'py-1.5 rounded-xl cursor-pointer transition font-bold text-purple-900 hover:bg-pink-200';
                
                if (year === 2026 && month === 8 && day === 28) {
                    dayDiv.className += ' cute-badge text-white shadow-xs';
                } else {
                    dayDiv.className += ' bg-pink-50/70';
                }

                dayDiv.innerText = day;
                daysContainer.appendChild(dayDiv);
            }
        }

        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            renderCalendar();
        }

        renderCalendar();

        // Toggle Modals / Forms
        function toggleOCModal() {
            const box = document.getElementById('oc-form-box');
            if (box) box.classList.toggle('hidden');
        }

        function toggleSkillModal() {
            const box = document.getElementById('skill-form-box');
            if (box) box.classList.toggle('hidden');
        }

        function toggleExpModal() {
            const box = document.getElementById('exp-form-box');
            if (box) box.classList.toggle('hidden');
        }

        function toggleWorkModal() {
            const box = document.getElementById('work-form-box');
            if (box) box.classList.toggle('hidden');
        }

        function toggleProjectModal() {
            const box = document.getElementById('project-form-box');
            if (box) box.classList.toggle('hidden');
        }
    </script>
</body>
</html>