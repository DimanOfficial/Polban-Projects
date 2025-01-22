
<?= $this->extend('template/templatePejabat'); ?>

<?= $this->section('content'); ?>
<div class="container bg-white shadow-sm p-3">
        <h2 class="text-center">Grafik Kegiatan</h2>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="total-tab" data-bs-toggle="tab" data-bs-target="#total" type="button" role="tab" aria-controls="total" aria-selected="true">Total Kegiatan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="peserta-tab" data-bs-toggle="tab" data-bs-target="#peserta" type="button" role="tab" aria-controls="peserta" aria-selected="false">Peserta</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="penyelenggara-tab" data-bs-toggle="tab" data-bs-target="#penyelenggara" type="button" role="tab" aria-controls="penyelenggara" aria-selected="false">Penyelenggara</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="kategoriTabContent">
            <!-- Total Kegiatan -->
            <div class="tab-pane fade show active" id="total" role="tabpanel" aria-labelledby="total-tab">
                <form onsubmit="fetchData('total'); return false;">
                    <label for="tahunTotal">Tahun:</label>
                    <select id="tahunTotal" class="form-select mb-3">
                        <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </form>
                <div class="bx">
                    <canvas id="totalChart"></canvas>
                </div>
            </div>

            <!-- Peserta -->
            <div class="tab-pane fade" id="peserta" role="tabpanel" aria-labelledby="peserta-tab">
                <form onsubmit="fetchData('peserta'); return false;">
                    <label for="filterPeserta">Peserta:</label>
                    <select id="filterPeserta" class="form-select mb-3">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="pejabat">Pejabat</option>
                        <option value="karyawan">Karyawan</option>
                        <option value="umum">Umum</option>
                    </select>
                    <label for="tahunPeserta">Tahun:</label>
                    <select id="tahunPeserta" class="form-select mb-3">
                        <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </form>
                <div class="bx">
                    <canvas id="pesertaChart"></canvas>
                </div>
            </div>

            <!-- Penyelenggara -->
            <div class="tab-pane fade" id="penyelenggara" role="tabpanel" aria-labelledby="penyelenggara-tab">
              <form onsubmit="fetchData('penyelenggara'); return false;">
    <label for="penyelenggaraType">Penyelenggara:</label>
    <select id="penyelenggaraType" class="form-select mb-3" onchange="updatePenyelenggaraOptions()">
        <option value="">-- Pilih Penyelenggara --</option>
        <option value="mahasiswa">Mahasiswa</option>
        <option value="karyawan">Karyawan</option>
    </select>
    <!-- Dropdown Dinamis akan dimasukkan di sini -->
    <div id="penyelenggaraDynamicDropdowns" class="mb-3"></div>

    <label for="tahunPenyelenggara">Tahun:</label>
    <select id="tahunPenyelenggara" class="form-select mb-3">
        <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>
                <div class="bx">
                    <canvas id="penyelenggaraChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    async function fetchData(type) {
        try {
            const year = document.getElementById(`tahun${capitalize(type)}`).value;
            let filter = '';

            if (type === 'penyelenggara') {
                const penyelenggaraType = document.getElementById('penyelenggaraType').value;

                if (penyelenggaraType === 'mahasiswa') {
                    const kategoriMahasiswa = document.getElementById('kategoriMahasiswa')?.value;
                    if (kategoriMahasiswa === 'jurusan') {
                        filter = document.getElementById('jurusan')?.value || 'showAllJurusan';
                    } else if (kategoriMahasiswa === 'prodi') {
                        filter = document.getElementById('prodi')?.value || 'showAllProdi';
                    } else {
                        filter = 'showAllMahasiswa';
                    }
                } else if (penyelenggaraType === 'karyawan') {
                    const jenisKaryawan = document.getElementById('jenisKaryawan')?.value;

                    if (jenisKaryawan === 'jurusan') {
                        const jurusanKaryawan = document.getElementById('jurusanKaryawan')?.value || 'showAllJurusan';
                        const prodiKaryawan = document.getElementById('prodiKaryawan')?.value || 'showAllProdi';
                        filter = `${jurusanKaryawan}-${prodiKaryawan}`;
                    } else if (jenisKaryawan === 'unit') {
                        filter = document.getElementById('unitKaryawan')?.value || 'showAllUnit';
                    } else {
                        filter = 'showAllKaryawan';
                    }
                }
            } else {
                filter = document.getElementById(`filter${capitalize(type)}`)?.value || '';
            }

            const response = await fetch(`<?= base_url('/dashboard/getChartData') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    chartType: type,
                    tahun: year,
                    filter
                }),
            });

            const data = await response.json();
            if (data.status !== 'success') {
                throw new Error(data.message || 'Gagal memuat data.');
            }

            updateChart(type, data.data);
        } catch (error) {
            console.error(`Error fetching data: ${error.message}`);
            alert('Terjadi kesalahan saat memuat data.');
        }
    }
    async function fetchDataPenyelenggara() {
    try {
        const year = document.getElementById('tahunPenyelenggara').value;
        const penyelenggaraType = document.getElementById('penyelenggaraType').value;
        let filter = '';

        if (penyelenggaraType === 'karyawan') {
            const jenisKaryawan = document.getElementById('jenisKaryawan')?.value;

            if (jenisKaryawan === 'jurusan') {
                const jurusanKaryawan = document.getElementById('jurusanKaryawan')?.value || 'showAllJurusan';
                const prodiKaryawan = document.getElementById('prodiKaryawan')?.value || 'showAllProdi';
                filter = `${jurusanKaryawan}-${prodiKaryawan}`;
            } else if (jenisKaryawan === 'unit') {
                filter = document.getElementById('unitKaryawan')?.value || 'showAllUnit';
            } else {
                filter = 'showAllKaryawan';
            }
        }

        const response = await fetch('<?= base_url('/dashboard/getChartDataPenyelenggara') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tahun: year,
                filter,
            }),
        });

        const result = await response.json();
        if (result.status !== 'success') {
            throw new Error(result.message || 'Gagal memuat data.');
        }

        updateChart('penyelenggara', result.data);
    } catch (error) {
        console.error(`Error fetching data penyelenggara: ${error.message}`);
        alert('Terjadi kesalahan saat memuat data penyelenggara.');
    }
}
    function updateChart(type, data) { 
        const chartId = `${type}Chart`;
        const ctx = document.getElementById(chartId)?.getContext('2d');
        if (!ctx) {
            console.error(`Canvas dengan ID ${chartId} tidak ditemukan.`);
            return;
        }

        // Hancurkan chart lama jika sudah ada
        if (Chart.getChart(chartId)) {
            Chart.getChart(chartId).destroy();
        }

        const labels = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const values = Array(12).fill(0);
        if (Array.isArray(data)) {
            data.forEach(item => {
                const month = parseInt(item.bulan) - 1;
                if (!isNaN(month) && month >= 0 && month < 12) {
                    values[month] = item.total;
                }
            });
        }

        new Chart(ctx, {
            type: type === 'total' ? 'bar' : 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: `Jumlah Kegiatan ${capitalize(type)}`,
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => `${tooltipItem.label}: ${tooltipItem.raw}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function loadChartData() {
    const tahun = document.getElementById('tahun').value;
    const filter = document.getElementById('filter').value; // Jurusan-Prodi

    fetch('/pejabat/getChartDataPenyelenggara', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ tahun, filter }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderChart(data.data);
            } else {
                console.error('Error: ', data.message);
            }
        })
        .catch(error => console.error('Error fetching chart data:', error));
}

function renderChart(data) {
    const ctx = document.getElementById('chartCanvas').getContext('2d');

    const labels = data.map(item => `Bulan ${item.bulan}`);
    const values = data.map(item => item.total);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Kegiatan',
                data: values,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}


    function updatePenyelenggaraOptions() {
        const penyelenggaraType = document.getElementById('penyelenggaraType').value;
        const dynamicDropdowns = document.getElementById('penyelenggaraDynamicDropdowns');
        dynamicDropdowns.innerHTML = ''; // Reset isi dropdown dinamis

        if (penyelenggaraType === 'mahasiswa') {
            dynamicDropdowns.innerHTML = `
                <label for="kategoriMahasiswa">Kategori Mahasiswa:</label>
                <select id="kategoriMahasiswa" class="form-select mb-3" onchange="updateMahasiswaOptions()">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="jurusan">Jurusan</option>
                    <option value="prodi">Prodi</option>
                    <option value="showAllMahasiswa">Tampilkan Semua Mahasiswa</option>
                </select>
                <div id="mahasiswaOptions"></div>
            `;
        } else if (penyelenggaraType === 'karyawan') {
            dynamicDropdowns.innerHTML = `
                <label for="jenisKaryawan">Jenis Karyawan:</label>
                <select id="jenisKaryawan" class="form-select mb-3" onchange="updateJenisKaryawanOptions()">
                    <option value="">-- Pilih Jenis Karyawan --</option>
                    <option value="jurusan">Jurusan</option>
                    <option value="unit">Unit</option>
                </select>
                <div id="karyawanOptions"></div>
            `;
        }
    }

    function updateJenisKaryawanOptions() {
        const jenisKaryawan = document.getElementById('jenisKaryawan')?.value;
        const karyawanOptions = document.getElementById('karyawanOptions');
        karyawanOptions.innerHTML = ''; // Reset isi dropdown karyawan

        if (jenisKaryawan === 'jurusan') {
            karyawanOptions.innerHTML = `
                <label for="jurusanKaryawan">Jurusan:</label>
                <select id="jurusanKaryawan" class="form-select mb-3" onchange="updateProdiKaryawanOptions()">
                    <option value="">-- Pilih Jurusan --</option>
                    <!-- Option untuk jurusan yang bisa dipilih -->
                    <?php foreach ($jurusans as $jurusan): ?>
                        <option value="<?= $jurusan['id_jurusan'] ?>"><?= $jurusan['nama_jurusan'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div id="prodiKaryawanOptions"></div>
            `;
        } else if (jenisKaryawan === 'unit') {
            karyawanOptions.innerHTML = `
                <label for="unitKaryawan">Unit:</label>
                <select id="unitKaryawan" class="form-select mb-3">
                    <option value="">-- Pilih Unit --</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?= $unit['id_unit'] ?>"><?= $unit['nama_unit'] ?></option>
                    <?php endforeach; ?>
                </select>
            `;
        }
    }

    function updateProdiKaryawanOptions() {
    const selectedJurusan = document.getElementById('jurusanKaryawan').value;
    const prodiOptions = document.getElementById('prodiKaryawanOptions');
    prodiOptions.innerHTML = '';

    if (selectedJurusan) {
        fetch(`<?= base_url('/get-prodi/') ?>/${selectedJurusan}`)
            .then(response => response.json())
            .then(data => {
                prodiOptions.innerHTML = `
                    <label for="prodiKaryawan">Prodi:</label>
                    <select id="prodiKaryawan" class="form-select mb-3">
                        <option value="">-- Pilih Prodi --</option>
                    </select>
                `;
                data.forEach(prodi => {
                    const option = document.createElement('option');
                    option.value = prodi.id_prodi;
                    option.textContent = prodi.nama_prodi;
                    document.getElementById('prodiKaryawan').appendChild(option);
                });
            });
    }
    }

    function updateMahasiswaOptions() {
        const kategoriMahasiswa = document.getElementById('kategoriMahasiswa')?.value;
        const mahasiswaOptions = document.getElementById('mahasiswaOptions');
        mahasiswaOptions.innerHTML = ''; // Reset isi dropdown mahasiswa

        if (kategoriMahasiswa === 'jurusan') {
            mahasiswaOptions.innerHTML = `
                <label for="jurusan">Jurusan:</label>
                <select id="jurusan" class="form-select mb-3">
                    <option value="">-- Pilih Jurusan --</option>
                    <?php foreach ($jurusans as $jurusan): ?>
                        <option value="<?= $jurusan['id_jurusan'] ?>"><?= $jurusan['nama_jurusan'] ?></option>
                    <?php endforeach; ?>
                </select>
            `;
        } else if (kategoriMahasiswa === 'prodi') {
            mahasiswaOptions.innerHTML = `
                <label for="prodi">Prodi:</label>
                <select id="prodi" class="form-select mb-3">
                    <option value="">-- Pilih Prodi --</option>
                    <?php foreach ($prodis as $prodi): ?>
                        <option value="<?= $prodi['id_prodi'] ?>"><?= $prodi['nama_prodi'] ?></option>
                    <?php endforeach; ?>
                </select>
            `;
        }
    }
</script>


<?= $this->endSection();?>