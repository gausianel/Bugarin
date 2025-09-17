<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bugarin - Welcome</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/059de8f294.js" crossorigin="anonymous"></script>
  <style>
      /* 🔹 Animasi Fade + Slide Up */
      @keyframes fadeSlideUp {
          0% { opacity: 0; transform: translateY(40px); }
          100% { opacity: 1; transform: translateY(0); }
      }
      .scroll-animate {
          opacity: 0;
          transform: translateY(40px);
          transition: all 0.6s ease-out;
      }
      .scroll-animate.active {
          animation: fadeSlideUp 0.8s forwards;
      }
      html {
          scroll-behavior: smooth;
      }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- 🔹 Loading Screen -->
  <div id="loading-screen" class="fixed inset-0 bg-white flex flex-col items-center justify-center z-50 transition-opacity duration-700 opacity-100">
      <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
      <p class="mt-4 text-blue-600 font-medium">Loading...</p>
  </div>

  <!-- 🔹 Main Content -->
  <div id="app-content" class="opacity-0 transition-opacity duration-700">

    <!-- Navbar -->
   <nav id="navbar" class="flex justify-between items-center px-10 py-6 bg-transparent fixed w-full z-20 top-0 left-0 transition duration-300">
        <h1 id="brandText" class="text-3xl font-bold text-blue-600">Bugarin</h1>

         <!-- 🔴 Circle Merah (Notifikasi) -->
          <div id="logo-text" class="absolute -top-1 -right-1 w-3 h-3 bg-red-600 rounded-full"></div>

        <div class="flex items-center space-x-4">

                <!-- Language Dropdown -->
        <div class="relative inline-block">
            <button id="langButton" class="flex items-center px-3 py-2 border border-white rounded-lg hover:bg-gray-100 transition">
                <i class="fa-solid fa-language text-blue-600 mr-2"></i>
                <span id="langText" class="text-sm font-medium text-white">ID</span>
                <i class="fa-solid fa-chevron-down ml-1 text-gray-500 text-xs"></i>
            </button>
            <div id="langMenu" class="absolute hidden bg-white shadow-lg rounded-lg mt-2 w-32">
                <a href="#" class="flex items-center px-3 py-2 hover:bg-gray-100 text-sm">🇮🇩 <span class="ml-2">Indonesia</span></a>
                <a href="#" class="flex items-center px-3 py-2 hover:bg-gray-100 text-sm">🇺🇸 <span class="ml-2">English</span></a>
            </div>
        </div>
            <!-- Daftar (Dropdown) -->
            <div class="relative inline-block">
                <button id="registerButton" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 flex items-center">
                    Daftar
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="registerMenu" class="absolute hidden mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                    <a href="{{ route('register.member') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Daftar sebagai Member
                    </a>
                    <a href="{{ route('register.gym') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Daftar sebagai Gym
                    </a>
                </div>
            </div>

            <!-- Login -->
         <a id="loginLink" href="{{ route('login') }}" class="ml-2 px-3 py-1.5 text-blue-600 text-sm hover:underline">Login</a>
        </div>
    </nav>

    <!-- Hero Section with Video Background -->
    <section class="relative h-screen flex items-center justify-center text-center text-white">
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
            <source src="{{ asset('video/gymBackground.mp4') }}" type="video/mp4">
            Browser Anda tidak mendukung video.
        </video>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
       <div class="relative z-10 px-6 text-center">
    <h1 class="text-6xl font-extrabold mb-6 drop-shadow-lg">
        Selamat Datang di <span class="text-blue-500">Bugarin</span>
    </h1>
    <p class="text-lg text-gray-200 mb-8 max-w-2xl mx-auto">
        Kelola gym Anda dengan mudah, pantau jadwal, dan hadirkan pengalaman terbaik untuk member.
    </p>

        <!-- Tombol Member -->
        <a href="{{ route('register.member') }}" 
        class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white px-8 py-4 rounded-2xl text-lg font-semibold shadow-lg transform hover:scale-105 transition">
            <i class="fa-solid fa-user-plus mr-2"></i>
            Mulai Sebagai Member
        </a>

        <!-- Tombol Gym -->
        <a href="{{ route('register.gym') }}" 
        class="inline-flex items-center ml-4 bg-white/90 hover:bg-gray-100 text-gray-900 px-8 py-4 rounded-2xl text-lg font-semibold shadow-lg transform hover:scale-105 transition">
            <i class="fa-solid fa-dumbbell mr-2 text-blue-600"></i>
            Mulai Sebagai Gym
        </a>
    </div>

    </section>

    <!-- Features -->
    <section class="py-16">
        <h1 class="text-5xl text-center font-bold text-blue-600">Fitur Unggulan</h1>
        <p class="text-xl text-center text-gray-600 mt-4 max-w-2xl mx-auto">
            Fitur-fitur unggulan kami yang bikin manajemen gym lebih mudah.
        </p>
        <div class="py-12 px-8 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="bg-white shadow rounded-lg p-8 text-center hover:shadow-xl transition scroll-animate">
                <span class="text-5xl">📅</span>
                <h3 class="text-2xl font-bold mt-4">Manajemen Jadwal</h3>
                <p class="text-gray-600 mt-2">Atur jadwal kelas dan event gym dengan mudah.</p>
            </div>
            <div class="bg-white shadow rounded-lg p-8 text-center hover:shadow-xl transition scroll-animate">
                <span class="text-5xl">💳</span>
                <h3 class="text-2xl font-bold mt-4">Membership</h3>
                <p class="text-gray-600 mt-2">Pantau status dan perpanjangan member dengan cepat.</p>
            </div>
            <div class="bg-white shadow rounded-lg p-8 text-center hover:shadow-xl transition scroll-animate">
                <span class="text-5xl">📊</span>
                <h3 class="text-2xl font-bold mt-4">Laporan</h3>
                <p class="text-gray-600 mt-2">Analisis performa gym Anda setiap bulan.</p>
            </div>
        </div>
    </section>

    <!-- Packages -->
    <section class="py-16 bg-gray-100">
        <h1 class="text-5xl text-center font-bold text-blue-600">Paket Membership</h1>
        <p class="text-xl text-center text-gray-600 mt-4 max-w-2xl mx-auto">
            Pilih paket sesuai kebutuhan Anda untuk pengalaman gym terbaik.
        </p>
        <div class="py-12 px-8 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="bg-white shadow rounded-lg p-8 text-center hover:shadow-xl transition scroll-animate">
                <h3 class="text-2xl font-bold text-blue-600">Basic</h3>
                <p class="text-gray-600 mt-2">Akses gym reguler</p>
                <p class="text-3xl font-extrabold mt-4">Rp199K <span class="text-base font-medium">/bulan</span></p>
                <a href="#" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">Pilih Paket</a>
            </div>
            <div class="bg-white shadow-lg border-2 border-blue-600 rounded-lg p-8 text-center hover:shadow-xl transition scroll-animate">
                <h3 class="text-2xl font-bold text-blue-600">Premium</h3>
                <p class="text-gray-600 mt-2">Akses gym + kelas</p>
                <p class="text-3xl font-extrabold mt-4">Rp399K <span class="text-base font-medium">/bulan</span></p>
                <a href="#" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">Pilih Paket</a>
            </div>
            <div class="bg-white shadow rounded-lg p-8 text-center hover:shadow-xl transition scroll-animate">
                <h3 class="text-2xl font-bold text-blue-600">VIP</h3>
                <p class="text-gray-600 mt-2">All access + trainer pribadi</p>
                <p class="text-3xl font-extrabold mt-4">Rp699K <span class="text-base font-medium">/bulan</span></p>
                <a href="#" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">Pilih Paket</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-700 text-white pt-16 pb-8">
        <div class="max-w-6xl mx-auto px-8 grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <h1 class="text-2xl font-bold mb-4">Bugarin</h1>
                <p class="text-gray-300 mb-4">Kelola gym Anda dengan mudah, pantau jadwal, dan hadirkan pengalaman terbaik untuk member.</p>
                <div class="flex space-x-4 text-xl">
                    <a href="#" class="hover:text-blue-400">🌐</a>
                    <a href="#" class="hover:text-blue-400">🐦</a>
                    <a href="#" class="hover:text-blue-400">📘</a>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-semibold mb-4">Contact Info</h3>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-center space-x-3"><span>📍</span><span>Jl. Fitness No. 123, Jakarta</span></li>
                    <li class="flex items-center space-x-3"><span>📧</span><span>support@bugarin.com</span></li>
                    <li class="flex items-center space-x-3"><span>📞</span><span>+62 812-3456-7890</span></li>
                </ul>
            </div>
            <div>
                <h3 class="text-xl font-semibold mb-4">Stay Connected</h3>
                <p class="text-gray-300 mb-4">Dapatkan tips fitness, promo, dan info terbaru langsung di email Anda.</p>
                <form class="flex items-center">
                    <input type="email" placeholder="Masukkan email" class="w-full px-4 py-2 rounded-l-lg text-gray-800 focus:outline-none">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-r-lg font-semibold">Kirim</button>
                </form>
                <div class="flex space-x-6 text-2xl mt-6">
                    <a href="#" class="hover:text-pink-500 transition-colors duration-300"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-blue-400 transition-colors duration-300"><i class="fa-regular fa-envelope"></i></a>
                    <a href="#" class="hover:text-green-400 transition-colors duration-300"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="mt-10 border-t border-blue-700 pt-6 text-center text-white text-sm">
            © {{ date('Y') }} Bugarin. All rights reserved.
        </div>
    </footer>
  </div>

  <!-- 🔹 Script Loading + Scroll Animation + Dropdown Toggle -->
  <script>
      document.addEventListener("DOMContentLoaded", function() {
          const loading = document.getElementById('loading-screen');
          const content = document.getElementById('app-content');

          setTimeout(() => {
              loading.classList.add('opacity-0');
              setTimeout(() => {
                  loading.style.display = 'none';
                  content.classList.remove('opacity-0');
              }, 700); 
          }, 700);

          // Scroll animation
          const elements = document.querySelectorAll('.scroll-animate');
          const observer = new IntersectionObserver((entries) => {
              entries.forEach(entry => {
                  if (entry.isIntersecting) {
                      entry.target.classList.add('active');
                  } else {
                      entry.target.classList.remove('active');
                  }
              });
          }, { threshold: 0.2 });
          elements.forEach(el => observer.observe(el));

          // Dropdown toggles
          const langBtn = document.getElementById("langButton");
          const langMenu = document.getElementById("langMenu");
          const registerBtn = document.getElementById("registerButton");
          const registerMenu = document.getElementById("registerMenu");
          const navbar    = document.getElementById("navbar");
          const brandText = document.getElementById("brandText");
          const langButton= document.getElementById("langButton");
          const langText  = document.getElementById("langText");
          const loginLink = document.getElementById("loginLink");


          langBtn.addEventListener("click", function(e) {
              e.stopPropagation();
              langMenu.classList.toggle("hidden");
              registerMenu.classList.add("hidden"); // close other menu
          });

          registerBtn.addEventListener("click", function(e) {
              e.stopPropagation();
              registerMenu.classList.toggle("hidden");
              langMenu.classList.add("hidden"); // close other menu
          });

          // Click outside to close
          document.addEventListener("click", function(e) {
              if (!langMenu.contains(e.target) && !langBtn.contains(e.target)) {
                  langMenu.classList.add("hidden");
              }
              if (!registerMenu.contains(e.target) && !registerBtn.contains(e.target)) {
                  registerMenu.classList.add("hidden");
              }
          });

          window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
            navbar.classList.add("bg-white", "shadow-md");
            langButton.classList.remove("border-white", "hover:bg-white/20");
            langButton.classList.add("border-gray-300", "hover:bg-gray-100");
            langText.classList.remove("text-white");
            langText.classList.add("text-gray-700");
            brandText.classList.remove("text-white");
            brandText.classList.add("text-blue-600");
            loginLink.classList.remove("text-white");
            loginLink.classList.add("text-blue-600");
        } else {
            navbar.classList.remove("bg-white", "shadow-md");
            langButton.classList.remove("border-gray-300", "hover:bg-gray-100");
            langButton.classList.add("border-white", "hover:bg-white/20");
            langText.classList.remove("text-gray-700");
            langText.classList.add("text-white");
            brandText.classList.remove("text-blue-600");
            brandText.classList.add("text-white");
            loginLink.classList.remove("text-blue-600");
            loginLink.classList.add("text-white");
        }
    });


      });
  </script>
</body>
</html>
