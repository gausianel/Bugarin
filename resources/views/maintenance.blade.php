<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maintenance</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <style>
    /* 🔹 Spinner Custom (muter) */
    .spinner {
      border: 4px solid #e5e7eb; /* abu-abu */
      border-top: 4px solid #2563eb; /* biru */
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0%   { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* 🔹 Animasi Fade + Slide Up */
    @keyframes fadeSlideUp {
      0% { opacity: 0; transform: translateY(40px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .scroll-animate {
      opacity: 0;
      transform: translateY(40px);
    }
    .scroll-animate.active {
      animation: fadeSlideUp 0.8s forwards;
    }

    /* 🔹 Fade Out untuk Loading Screen */
    .fade-out {
      opacity: 0;
      transition: opacity 0.7s ease;
    }
  </style>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">

  <!-- 🔹 Loading Screen -->
  <div id="loading-screen" class="fixed inset-0 bg-white flex flex-col items-center justify-center z-50">
    <div class="spinner"></div>
    <p class="mt-4 text-blue-600 font-semibold">Loading...</p>
  </div>

  <!-- 🔹 Main Content -->
  <div id="app-content" class="opacity-0 transition-opacity duration-700">
    <div class="text-center p-8 bg-white shadow-lg rounded-2xl max-w-lg mx-auto">

      <!-- Icon -->
      <div class="mb-6 scroll-animate">
        <svg class="mx-auto w-20 h-20 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
      </div>

      <!-- Text -->
      <h1 class="text-2xl font-bold mb-2 scroll-animate">Mohon Maaf Sedang dalam pemeliharaan!</h1>
      <p class="text-gray-600 mb-6 scroll-animate">
        Kami sedang melakukan pemeliharaan sistem untuk meningkatkan layanan. Mohon coba lagi beberapa saat lagi.
      </p>
      <p class="text-sm text-gray-500 scroll-animate">Terima kasih atas pengertian Anda!</p>
    </div>
  </div>

  <!-- 🔹 Script Loading + Animasi -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const loading = document.getElementById('loading-screen');
      const content = document.getElementById('app-content');
      const elements = document.querySelectorAll('.scroll-animate');

      setTimeout(() => {
        loading.classList.add("fade-out");
        setTimeout(() => {
          loading.style.display = "none";
          content.classList.remove("opacity-0");

          // Animasi konten berurutan
          elements.forEach((el, i) => {
            setTimeout(() => {
              el.classList.add("active");
            }, i * 250);
          });

        }, 700);
      }, 1200);
    });
  </script>
</body>
</html>
