 // Ambil preferensi dari localStorage atau default auto
 const storedTheme = localStorage.getItem('theme') || 'auto';

    // Fungsi untuk atur tema
    const setTheme = function (theme) {
      document.documentElement.setAttribute('data-bs-theme', theme);
      localStorage.setItem('theme', theme);
  
      // Update aktif class dan icon centang
      document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
        element.classList.remove('active');
        element.setAttribute('aria-pressed', 'false');
      });
  
      const activeButton = document.querySelector(`[data-bs-theme-value="${theme}"]`);
      if (activeButton) {
        activeButton.classList.add('active');
        activeButton.setAttribute('aria-pressed', 'true');
      }
    };
  
    // Set tema awal saat load halaman
    setTheme(storedTheme);
  
    // Event listener klik pada semua opsi tema
    document.querySelectorAll('[data-bs-theme-value]').forEach(button => {
      button.addEventListener('click', () => {
        const selectedTheme = button.getAttribute('data-bs-theme-value');
        setTheme(selectedTheme);
      });
    });