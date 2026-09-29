<?php
$statusMessage = "";
$statusType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitasi input form kontak
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    if (!empty($name) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Simulasi pengiriman pesan (bisa diintegrasikan dengan mail() atau database)
            $statusMessage = "Terima kasih, $name! Pesan Anda seputar penulisan dan plot telah berhasil dikirim ke Reflynda.";
            $statusType = "success";
        } else {
            $statusMessage = "Format alamat email tidak valid. Silakan coba lagi.";
            $statusType = "error";
        }
    } else {
        $statusMessage = "Mohon lengkapi seluruh kolom formulir terlebih dahulu.";
        $statusType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reflynda - Writer & Plot Developer Portfolio (PHP Integrated)</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pastel: {
                            purple: '#9b7ede',
                            lightpurple: '#e7e2f3',
                            pink: '#f8b4d9',
                            softpink: '#fce4ec',
                            lavender: '#f3e8ff',
                            dark: '#2d233d',
                            cardbg: 'rgba(255, 255, 255, 0.8)'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f9f5fc 0%, #fcecf3 100%);
            color: #2d233d;
            overflow-x: hidden;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(248, 180, 217, 0.3);
        }
        .glass-nav {
            background: rgba(249, 245, 252, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .floating-element {
            animation: float 4s ease-in-out infinite;
        }
        .blob {
            position: absolute;
            filter: blur(60px);
            z-index: -1;
            opacity: 0.5;
            transition: all 1s ease;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#faf7fc] to-[#fcf0f5] text-pastel-dark selection:bg-pastel-pink selection:text-pastel-dark min-h-screen relative">

    <!-- Background Ambient Blobs -->
    <div class="blob w-96 h-96 bg-pastel-lightpurple rounded-full top-10 left-[-100px]"></div>
    <div class="blob w-96 h-96 bg-pastel-pink rounded-full bottom-20 right-[-100px]"></div>

    <!-- Navigation Header -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-nav border-b border-pastel-pink/20 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="#hero" class="text-2xl font-serif font-bold text-pastel-purple tracking-wide flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-pastel-pink flex items-center justify-center text-pastel-dark text-sm font-sans font-bold shadow-sm">R</span>
                Reflynda
            </a>
            
            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-8 font-medium text-sm">
                <a href="#about" class="hover:text-pastel-purple transition-colors">Biografi</a>
                <a href="#skills" class="hover:text-pastel-purple transition-colors">Keahlian</a>
                <a href="#education" class="hover:text-pastel-purple transition-colors">Pendidikan</a>
                <a href="form_login.php" class="hover:text-pastel-purple transition-colors">login</a>
                <a href="#experience" class="hover:text-pastel-purple transition-colors">Pengalaman</a>
                <button onclick="openContactModal()" class="px-5 py-2.5 rounded-full bg-pastel-purple text-white shadow-md hover:bg-[#8869cc] transition-all transform hover:-translate-y-0.5">
                    Hubungi Saya
                </button>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-pastel-dark text-xl focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="hidden md:hidden absolute top-20 left-0 w-full glass-card border-b border-pastel-pink/30 py-6 px-6 flex flex-col gap-4 shadow-xl">
            <a href="#about" class="mobile-link text-lg font-medium hover:text-pastel-purple">Biografi</a>
            <a href="#skills" class="mobile-link text-lg font-medium hover:text-pastel-purple">Keahlian</a>
            <a href="#education" class="mobile-link text-lg font-medium hover:text-pastel-purple">Pendidikan</a>
              <a href="#education" class="mobile-link text-lg font-medium hover:text-pastel-purple">login</a>
            <a href="#experience" class="mobile-link text-lg font-medium hover:text-pastel-purple">Pengalaman</a>
            <button onclick="openContactModal()" class="w-full py-3 rounded-full bg-pastel-purple text-white font-medium shadow-md">
                Hubungi Saya
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="min-h-screen flex items-center justify-center pt-24 pb-12 px-6 relative">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            
            <div class="space-y-6 text-center md:text-left z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-pastel-lavender border border-pastel-pink/40 text-pastel-purple text-xs md:text-sm font-semibold tracking-wider uppercase shadow-sm">
                    <i class="fa-solid fa-feather animate-pulse"></i> Creative Writer & Plot Developer
                </div>
                <h1 class="text-4xl md:text-6xl font-serif font-bold text-pastel-dark leading-tight">
                    Halo, saya <span class="text-pastel-purple underline decoration-pastel-pink decoration-wavy decoration-2">Reflynda</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-lg mx-auto md:mx-0 font-normal leading-relaxed">
                    Seorang <span class="font-semibold text-pastel-dark">Writer</span> dan <span class="font-semibold text-pastel-dark">Plot Developer</span> yang berfokus pada penyusunan alur cerita mendalam, narasi imajinatif, dan pengembangan karakter yang berkesan.
                </p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-2">
                    <a href="#skills" class="px-8 py-3.5 rounded-full bg-pastel-purple text-white font-medium shadow-lg shadow-pastel-purple/30 hover:bg-[#8869cc] transition-all transform hover:-translate-y-1">
                        Jelajahi Keahlian
                    </a>
                    <a href="#about" class="px-8 py-3.5 rounded-full glass-card text-pastel-dark font-medium border border-pastel-purple/30 hover:bg-pastel-pink/20 transition-all">
                        Tentang Saya
                    </a>
                </div>
            </div>

            <!-- Hero Visual Graphic Card -->
            <div class="relative flex justify-center">
                <div class="absolute w-72 h-72 md:w-80 md:h-80 bg-gradient-to-tr from-pastel-pink to-pastel-lightpurple rounded-full filter blur-xl opacity-70 -z-10 animate-pulse"></div>
                <div class="glass-card p-8 rounded-3xl shadow-2xl max-w-sm w-full floating-element border border-white">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pastel-purple to-pastel-pink flex items-center justify-center text-white text-2xl font-serif font-bold shadow-md">
                            R
                        </div>
                        <div>
                            <h3 class="font-serif font-bold text-xl text-pastel-dark">Reflynda</h3>
                            <p class="text-xs text-pastel-purple font-semibold tracking-wide uppercase">Writer & Plot Specialist</p>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm text-gray-600 border-t border-pastel-pink/20 pt-4">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-graduation-cap text-pastel-purple"></i> Sekolah</span>
                            <span class="font-medium text-pastel-dark">SMK TI Airlangga</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-pen-nib text-pastel-purple"></i> Fokus Utama</span>
                            <span class="font-medium text-pastel-dark">Writing & Plotting</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-book-open text-pastel-purple"></i> Alumni SMP</span>
                            <span class="font-medium text-pastel-dark">SMPN 27</span>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-pastel-pink/20 flex justify-around text-pastel-purple">
                        <a href="#" class="w-10 h-10 rounded-full bg-pastel-lavender flex items-center justify-center hover:bg-pastel-pink hover:text-pastel-dark transition-colors"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-pastel-lavender flex items-center justify-center hover:bg-pastel-pink hover:text-pastel-dark transition-colors"><i class="fa-solid fa-book"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-pastel-lavender flex items-center justify-center hover:bg-pastel-pink hover:text-pastel-dark transition-colors"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Biografi Section -->
    <section id="about" class="py-20 px-6 relative">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-xs font-bold tracking-widest text-pastel-purple uppercase px-3 py-1 bg-pastel-lavender rounded-full">Mengenal Lebih Dekat</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold mt-3 text-pastel-dark">Biografi Singkat</h2>
                <div class="w-16 h-1 bg-pastel-pink mx-auto mt-3 rounded-full"></div>
            </div>
            
            <div class="glass-card p-8 md:p-12 rounded-3xl shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 text-pastel-pink/20 text-9xl font-serif pointer-events-none">
                    <i class="fa-solid fa-quote-right text-9xl"></i>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <div class="md:col-span-1 flex justify-center">
                        <div class="w-44 h-44 rounded-3xl bg-gradient-to-tr from-pastel-purple/30 to-pastel-pink/50 p-2 shadow-inner flex items-center justify-center">
                            <div class="w-full h-full rounded-2xl bg-white flex flex-col items-center justify-center text-pastel-purple shadow-sm">
                                <i class="fa-solid fa-feather-pointed text-4xl mb-2"></i>
                                <span class="font-serif font-bold text-sm text-pastel-dark">Creative Writer</span>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2 space-y-4 text-gray-700 leading-relaxed text-base md:text-lg">
                        <p>
                            Halo! Saya <span class="font-bold text-pastel-dark">Reflynda</span>, seorang penulis kreatif dan perancang plot yang memiliki kecintaan mendalam pada dunia sastra, penyusunan cerita, dan narasi imajinatif.
                        </p>
                        <p>
                            Perjalanan pendidikan saya dimulai di <span class="font-semibold text-pastel-dark">SMP Negeri 27</span>, dan saat ini saya tengah menempuh pendidikan di <span class="font-semibold text-pastel-purple">SMK TI Airlangga Samarinda</span>. Saya memadukan kreativitas penceritaan dengan ketelitian analitis untuk membangun dunia fiksi dan narasi yang hidup.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Keahlian Section -->
    <section id="skills" class="py-20 px-6 bg-white/40">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-xs font-bold tracking-widest text-pastel-purple uppercase px-3 py-1 bg-pastel-lavender rounded-full">Keahlian Profesional</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold mt-3 text-pastel-dark">Spesialisasi & Skill</h2>
                <div class="w-16 h-1 bg-pastel-pink mx-auto mt-3 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Skill Card 1: Writing -->
                <div class="glass-card p-8 rounded-3xl shadow-lg border border-pastel-pink/30 hover:shadow-xl transition-shadow flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-pastel-purple text-white flex items-center justify-center text-2xl shadow-md">
                                <i class="fa-solid fa-pen-nib"></i>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-xl text-pastel-dark">Writing (Menulis Kreatif)</h3>
                                <p class="text-xs text-pastel-purple font-medium">Diksi & Narasi Mendalam</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                            Kemampuan merangkai kata dengan gaya bahasa yang kaya, pemilihan diksi yang emosional, serta penyampaian pesan yang menyentuh hati pembaca melalui berbagai format tulisan.
                        </p>
                    </div>
                    <div class="space-y-4 pt-4 border-t border-pastel-pink/20">
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-pastel-dark mb-1">
                                <span>Creative Writing</span>
                                <span class="text-pastel-purple">96%</span>
                            </div>
                            <div class="w-full h-2.5 bg-pastel-lightpurple rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-pastel-purple to-pastel-pink rounded-full w-[96%]"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-pastel-dark mb-1">
                                <span>Character Voice & Dialogue</span>
                                <span class="text-pastel-purple">92%</span>
                            </div>
                            <div class="w-full h-2.5 bg-pastel-lightpurple rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-pastel-pink to-pastel-purple rounded-full w-[92%]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skill Card 2: Plot Development -->
                <div class="glass-card p-8 rounded-3xl shadow-lg border border-pastel-pink/30 hover:shadow-xl transition-shadow flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-pastel-pink text-pastel-dark flex items-center justify-center text-2xl shadow-md">
                                <i class="fa-solid fa-diagram-project"></i>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-xl text-pastel-dark">Plot Development</h3>
                                <p class="text-xs text-pastel-purple font-medium">Struktur & Alur Cerita</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                            Keahlian dalam merancang kerangka plot yang kuat, konflik cerita yang memikat, pacing yang pas, hingga penyelesaian yang memuaskan bagi sebuah karya fiksi atau naratif.
                        </p>
                    </div>
                    <div class="space-y-4 pt-4 border-t border-pastel-pink/20">
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-pastel-dark mb-1">
                                <span>Plot Architecture</span>
                                <span class="text-pastel-purple">95%</span>
                            </div>
                            <div class="w-full h-2.5 bg-pastel-lightpurple rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-pastel-purple to-pastel-pink rounded-full w-[95%]"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-pastel-dark mb-1">
                                <span>Conflict & Twist Design</span>
                                <span class="text-pastel-purple">94%</span>
                            </div>
                            <div class="w-full h-2.5 bg-pastel-lightpurple rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-pastel-pink to-pastel-purple rounded-full w-[94%]"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Pendidikan Section -->
    <section id="education" class="py-20 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-xs font-bold tracking-widest text-pastel-purple uppercase px-3 py-1 bg-pastel-lavender rounded-full">Jejak Akademik</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold mt-3 text-pastel-dark">Pendidikan</h2>
                <div class="w-16 h-1 bg-pastel-pink mx-auto mt-3 rounded-full"></div>
            </div>

            <!-- Modern Timeline Container -->
            <div class="relative border-l-2 border-pastel-purple/30 ml-4 md:ml-32 space-y-12">
                
                <!-- Education Item 1: SMK TI Airlangga -->
                <div class="relative pl-8 md:pl-12 group">
                    <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-pastel-purple border-4 border-white shadow-md group-hover:scale-125 transition-transform"></div>
                    
                    <div class="glass-card p-6 md:p-8 rounded-3xl shadow-lg border border-pastel-pink/30 relative">
                        <span class="inline-block px-3 py-1 rounded-full bg-pastel-purple text-white text-xs font-semibold mb-3 shadow-sm">
                            Sekarang (Aktif)
                        </span>
                        <h3 class="font-serif font-bold text-2xl text-pastel-dark">SMK TI Airlangga Samarinda</h3>
                        <p class="text-pastel-purple font-medium text-sm mt-1">Pendidikan Kejuruan Teknologi Informasi</p>
                        <p class="text-gray-600 text-sm mt-3 leading-relaxed">
                            Mengembangkan wawasan digital dan teknologi modern yang memperkaya cara pandang dalam menyusun struktur penulisan dan proyek kreatif berbasis media digital.
                        </p>
                    </div>
                </div>

                <!-- Education Item 2: SMP Negeri 27 -->
                <div class="relative pl-8 md:pl-12 group">
                    <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-pastel-pink border-4 border-white shadow-md group-hover:scale-125 transition-transform"></div>
                    
                    <div class="glass-card p-6 md:p-8 rounded-3xl shadow-lg border border-pastel-pink/30 relative">
                        <span class="inline-block px-3 py-1 rounded-full bg-pastel-pink text-pastel-dark text-xs font-semibold mb-3 shadow-sm">
                            Alumni
                        </span>
                        <h3 class="font-serif font-bold text-2xl text-pastel-dark">SMP Negeri 27</h3>
                        <p class="text-pastel-purple font-medium text-sm mt-1">Pendidikan Menengah Pertama</p>
                        <p class="text-gray-600 text-sm mt-3 leading-relaxed">
                            Masa awal penemuan bakat di bidang literasi, eksplorasi dunia membaca, dan kecintaan terhadap seni merangkai kata-kata indah.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Pengalaman Kerja Section -->
    <section id="experience" class="py-20 px-6 bg-white/40">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-xs font-bold tracking-widest text-pastel-purple uppercase px-3 py-1 bg-pastel-lavender rounded-full">Portofolio & Proyek Kreatif</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold mt-3 text-pastel-dark">Pengalaman & Proyek</h2>
                <div class="w-16 h-1 bg-pastel-pink mx-auto mt-3 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Experience Card 1 -->
                <div class="glass-card p-8 rounded-3xl shadow-lg border border-pastel-pink/30 flex flex-col justify-between hover:shadow-xl transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full bg-pastel-lavender text-pastel-purple text-xs font-bold">Plot Developer</span>
                            <i class="fa-solid fa-pen-to-square text-pastel-purple text-xl"></i>
                        </div>
                        <h3 class="font-serif font-bold text-xl text-pastel-dark mb-1">Freelance Plot & Story Architect</h3>
                        <p class="text-sm text-pastel-purple font-medium mb-4">Penyusunan Alur & Outline Cerita</p>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            Bekerja sama dalam merancang outline, konflik cerita, serta peta alur (plot) yang terstruktur rapi untuk berbagai proyek penulisan kreatif dan fiksi.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-pastel-dark pt-4 border-t border-pastel-pink/20">
                        <i class="fa-solid fa-circle-check text-pastel-purple"></i> 15+ Outline & Alur Cerita Tersusun
                    </div>
                </div>

                <!-- Experience Card 2 -->
                <div class="glass-card p-8 rounded-3xl shadow-lg border border-pastel-pink/30 flex flex-col justify-between hover:shadow-xl transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full bg-pastel-softpink text-pastel-dark text-xs font-bold">Creative Writer</span>
                            <i class="fa-solid fa-book-open-reader text-pastel-pink text-xl"></i>
                        </div>
                        <h3 class="font-serif font-bold text-xl text-pastel-dark mb-1">Independent Writer & Contributor</h3>
                        <p class="text-sm text-pastel-purple font-medium mb-4">Penulisan Cerita & Artikel Naratif</p>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            Menulis berbagai naskah cerita pendek, artikel naratif, dan karya sastra mandiri dengan fokus pada pengembangan karakter yang kuat dan emosional.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-pastel-dark pt-4 border-t border-pastel-pink/20">
                        <i class="fa-solid fa-circle-check text-pastel-purple"></i> Publikasi Karya & Esai Naratif
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA & Footer Section -->
    <footer class="py-16 px-6 bg-gradient-to-t from-pastel-lightpurple/50 to-transparent border-t border-pastel-pink/20">
        <div class="max-w-4xl mx-auto text-center space-y-6">
            <div class="w-16 h-16 rounded-3xl bg-pastel-purple text-white text-2xl flex items-center justify-center mx-auto shadow-lg">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
            <h2 class="text-3xl font-serif font-bold text-pastel-dark">Mari Berkolaborasi Bersama Reflynda!</h2>
            <p class="text-gray-600 max-w-lg mx-auto text-sm leading-relaxed">
                Punya proyek penulisan, ide cerita yang butuh plot development yang matang, atau sekadar ingin berdiskusi? Jangan ragu untuk menghubungi saya.
            </p>
            <button onclick="openContactModal()" class="px-8 py-4 rounded-full bg-pastel-purple text-white font-medium shadow-xl shadow-pastel-purple/30 hover:bg-[#8869cc] transition-all transform hover:-translate-y-1">
                Kirim Pesan Sekarang
            </button>
            <div class="pt-8 border-t border-pastel-pink/20 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                <p>&copy; 2026 Reflynda. All rights reserved. Designed with Pastel Purple & Pink theme.</p>
                <div class="flex gap-4">
                    <a href="#hero" class="hover:text-pastel-purple transition-colors">Kembali ke Atas <i class="fa-solid fa-arrow-up ml-1"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Contact Modal (Integrated with PHP Backend Handler) -->
    <div id="contact-modal" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/40 backdrop-blur-sm <?php echo empty($statusMessage) ? 'hidden' : ''; ?> transition-opacity duration-300">
        <div class="glass-card max-w-md w-full p-8 rounded-3xl shadow-2xl relative border border-pastel-pink transform transition-transform duration-300 scale-100" id="modal-content">
            <button onclick="closeContactModal()" class="absolute top-6 right-6 w-8 h-8 rounded-full bg-pastel-lavender flex items-center justify-center text-pastel-dark hover:bg-pastel-pink transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
            
            <div class="text-center mb-6">
                <h3 class="font-serif font-bold text-2xl text-pastel-dark">Hubungi Reflynda</h3>
                <p class="text-xs text-pastel-purple mt-1">Isi formulir di bawah untuk mengirim pesan langsung (PHP Backend)</p>
            </div>

            <?php if (!empty($statusMessage)): ?>
                <div class="mb-4 p-4 rounded-2xl text-xs font-medium text-center <?php echo $statusType === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200'; ?>">
                    <?php echo $statusMessage; ?>
                </div>
            <?php endif; ?>

            <form action="index.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-pastel-dark mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 rounded-2xl bg-white/80 border border-pastel-pink/50 focus:outline-none focus:border-pastel-purple text-sm text-pastel-dark" placeholder="Masukkan nama Anda...">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-pastel-dark mb-1">Email / Kontak</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-2xl bg-white/80 border border-pastel-pink/50 focus:outline-none focus:border-pastel-purple text-sm text-pastel-dark" placeholder="nama@email.com...">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-pastel-dark mb-1">Pesan / Proyek</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-3 rounded-2xl bg-white/80 border border-pastel-pink/50 focus:outline-none focus:border-pastel-purple text-sm text-pastel-dark resize-none" placeholder="Tuliskan pesan atau ide cerita Anda di sini..."></textarea>
                </div>
                <button type="submit" class="w-full py-3.5 rounded-2xl bg-pastel-purple text-white font-medium shadow-md hover:bg-[#8869cc] transition-all">
                    Kirim Pesan (via PHP)
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript for Interactivity -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });

        // Contact Modal Functions
        const contactModal = document.getElementById('contact-modal');
        const modalContent = document.getElementById('modal-content');

        function openContactModal() {
            contactModal.classList.remove('hidden');
        }

        function closeContactModal() {
            contactModal.classList.add('hidden');
        }

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === contactModal) {
                closeContactModal();
            }
        });
    </script>
</body>
</html>