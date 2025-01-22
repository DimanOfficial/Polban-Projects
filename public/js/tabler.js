document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.nav-link');
    const dataContainer = document.getElementById('data-container');
    const yearSelect = document.getElementById('tahun');

    // Fungsi untuk memuat data
    function loadData(kategori, tahun) {
        fetch(`/kegiatan/loadData?kategori=${kategori}&tahun=${tahun}`)
            .then(response => response.text())
            .then(data => {
                dataContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                dataContainer.innerHTML = '<p class="text-danger">Terjadi kesalahan saat memuat data.</p>';
            });
    }

    // Event listener untuk tab klik
    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const kategori = this.getAttribute('data-kategori');
            const selectedYear = yearSelect.value;

            // Ganti tab aktif
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Load data
            loadData(kategori, selectedYear);
        });
    });

    // Muat data awal saat halaman pertama kali dimuat
    const defaultKategori = 'total';
    const defaultYear = yearSelect.value;
    loadData(defaultKategori, defaultYear);
});
