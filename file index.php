<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Logistic Corner - Politeknik Negeri Lampung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .notification-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .modal {
            display: none;
        }
        .modal.active {
            display: flex;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-box-open text-3xl"></i>
                    <div>
                        <h1 class="text-xl font-bold">Logistic Corner</h1>
                        <p class="text-sm text-blue-100">Politeknik Negeri Lampung</p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <button id="notifBtn" class="relative">
                        <i class="fas fa-bell text-2xl"></i>
                        <span id="notifBadge" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs notification-badge">0</span>
                    </button>
                    <div class="text-right">
                        <p class="font-semibold">Admin Logistik</p>
                        <p class="text-sm text-blue-100">Petugas</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-6">
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Paket Masuk</p>
                        <p id="totalPaketMasuk" class="text-3xl font-bold text-blue-600">0</p>
                    </div>
                    <i class="fas fa-inbox text-4xl text-blue-200"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Belum Diambil</p>
                        <p id="totalBelumDiambil" class="text-3xl font-bold text-orange-600">0</p>
                    </div>
                    <i class="fas fa-clock text-4xl text-orange-200"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Sudah Diambil</p>
                        <p id="totalSudahDiambil" class="text-3xl font-bold text-green-600">0</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl text-green-200"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Denda</p>
                        <p id="totalDenda" class="text-3xl font-bold text-red-600">Rp 0</p>
                    </div>
                    <i class="fas fa-money-bill text-4xl text-red-200"></i>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b">
                <div class="flex">
                    <button class="tab-btn px-6 py-3 font-semibold text-blue-600 border-b-2 border-blue-600" data-tab="inputPaket">
                        <i class="fas fa-plus-circle mr-2"></i>Input Paket Baru
                    </button>
                    <button class="tab-btn px-6 py-3 font-semibold text-gray-600 hover:text-blue-600" data-tab="daftarPaket">
                        <i class="fas fa-list mr-2"></i>Daftar Paket
                    </button>
                    <button class="tab-btn px-6 py-3 font-semibold text-gray-600 hover:text-blue-600" data-tab="trackingPaket">
                        <i class="fas fa-search mr-2"></i>Tracking Paket
                    </button>
                </div>
            </div>

            <!-- Input Paket Tab -->
            <div id="inputPaket" class="tab-content active p-6">
                <h2 class="text-2xl font-bold mb-4">Input Paket Baru</h2>
                <form id="formInputPaket" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                        <input type="text" id="namaPenerima" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">NIM/NIP <span class="text-red-500">*</span></label>
                        <input type="text" id="nim" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nomor Resi <span class="text-red-500">*</span></label>
                        <input type="text" id="nomorResi" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Kurir <span class="text-red-500">*</span></label>
                        <select id="kurir" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Kurir</option>
                            <option value="JNE">JNE</option>
                            <option value="J&T">J&T</option>
                            <option value="SiCepat">SiCepat</option>
                            <option value="Shopee Express">Shopee Express</option>
                            <option value="Anteraja">Anteraja</option>
                            <option value="Ninja Express">Ninja Express</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Berat Paket (kg)</label>
                        <input type="number" id="beratPaket" step="0.1" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nomor WhatsApp</label>
                        <input type="tel" id="noWhatsapp" placeholder="08xxxxxxxxxx" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Masuk <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="tanggalMasuk" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Catatan</label>
                        <input type="text" id="catatan" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Paket & Kirim Notifikasi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Paket Tab -->
            <div id="daftarPaket" class="tab-content p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">Daftar Paket</h2>
                    <div class="flex space-x-2">
                        <input type="text" id="searchPaket" placeholder="Cari nama/resi..." class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <select id="filterStatus" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="belum">Belum Diambil</option>
                            <option value="sudah">Sudah Diambil</option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Resi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Penerima</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">NIM/NIP</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kurir</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Masuk</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Lama (Hari)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Denda</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tablePaket" class="divide-y divide-gray-200">
                            <tr>
                                <td colspan="10" class="px-4 py-8 text-center text-gray-500">Belum ada data paket</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tracking Paket Tab -->
            <div id="trackingPaket" class="tab-content p-6">
                <h2 class="text-2xl font-bold mb-4">Tracking Paket Mahasiswa</h2>
                <div class="max-w-2xl mx-auto">
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Masukkan Nomor Resi atau NIM</label>
                        <div class="flex space-x-2">
                            <input type="text" id="trackingInput" placeholder="Contoh: JNE123456 atau 1234567890" class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button id="btnTracking" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                        </div>
                    </div>
                    <div id="trackingResult" class="hidden">
                        <!-- Results will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-xl font-bold">Notifikasi Paket</h3>
                <button id="closeNotifModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="notificationList" class="p-6">
                <!-- Notifications will be displayed here -->
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-xl font-bold">Detail Paket</h3>
                <button id="closeDetailModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div id="detailContent" class="p-6">
                <!-- Detail will be displayed here -->
            </div>
        </div>
    </div>

    <script>
        // Check if user is logged in
        const currentUser = JSON.parse(sessionStorage.getItem('user'));
        if (!currentUser) {
            window.location.href = 'login.html';
        }

        // Add logout functionality
        function logout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                sessionStorage.removeItem('user');
                localStorage.removeItem('remember_user');
                window.location.href = 'login.html';
            }
        }

        // Initialize data from localStorage
        let packages = JSON.parse(localStorage.getItem('packages')) || [];
        let notifications = JSON.parse(localStorage.getItem('notifications')) || [];

        // Set default date to now
        document.getElementById('tanggalMasuk').value = new Date().toISOString().slice(0, 16);

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                
                // Update buttons
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    b.classList.add('text-gray-600');
                });
                this.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                this.classList.remove('text-gray-600');
                
                // Update content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabName).classList.add('active');
            });
        });

        // Calculate working days (excluding holidays)
        function calculateWorkingDays(startDate, endDate) {
            let count = 0;
            let current = new Date(startDate);
            const end = new Date(endDate);
            
            // Indonesian public holidays 2024/2025 (simplified)
            const holidays = [
                '2024-01-01', '2024-02-08', '2024-03-11', '2024-03-12',
                '2024-03-29', '2024-04-10', '2024-05-01', '2024-05-09',
                '2024-05-23', '2024-06-01', '2024-06-17', '2024-06-18',
                '2024-08-17', '2024-09-16', '2024-12-25', '2024-12-26',
                '2025-01-01'
            ];
            
            while (current <= end) {
                const dateStr = current.toISOString().split('T')[0];
                const dayOfWeek = current.getDay();
                
                // Skip Sundays (0) and holidays
                if (dayOfWeek !== 0 && !holidays.includes(dateStr)) {
                    count++;
                }
                
                current.setDate(current.getDate() + 1);
            }
            
            return count;
        }

        // Calculate fine
        function calculateFine(tanggalMasuk) {
            const now = new Date();
            const masuk = new Date(tanggalMasuk);
            const workingDays = calculateWorkingDays(masuk, now);
            
            // Free for first day
            const dendaDays = Math.max(0, workingDays - 1);
            return dendaDays * 1000; // Rp 1,000 per day
        }

        // Format currency
        function formatRupiah(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        // Update dashboard stats
        function updateDashboard() {
            const totalMasuk = packages.length;
            const belumDiambil = packages.filter(p => !p.diambil).length;
            const sudahDiambil = packages.filter(p => p.diambil).length;
            const totalDenda = packages
                .filter(p => !p.diambil)
                .reduce((sum, p) => sum + calculateFine(p.tanggalMasuk), 0);
            
            document.getElementById('totalPaketMasuk').textContent = totalMasuk;
            document.getElementById('totalBelumDiambil').textContent = belumDiambil;
            document.getElementById('totalSudahDiambil').textContent = sudahDiambil;
            document.getElementById('totalDenda').textContent = formatRupiah(totalDenda);
        }

        // Add notification
        function addNotification(type, message, packageData) {
            const notification = {
                id: Date.now(),
                type: type,
                message: message,
                packageData: packageData,
                timestamp: new Date().toISOString(),
                read: false
            };
            
            notifications.unshift(notification);
            localStorage.setItem('notifications', JSON.stringify(notifications));
            updateNotificationBadge();
        }

        // Update notification badge
        function updateNotificationBadge() {
            const unreadCount = notifications.filter(n => !n.read).length;
            document.getElementById('notifBadge').textContent = unreadCount;
        }

        // Send WhatsApp notification (simulation)
        function sendWhatsAppNotification(phone, message) {
            if (phone) {
                console.log(`Sending WhatsApp to ${phone}: ${message}`);
                // In real implementation, this would call WhatsApp API
                return true;
            }
            return false;
        }

        // Form submit
        document.getElementById('formInputPaket').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const packageData = {
                id: Date.now(),
                namaPenerima: document.getElementById('namaPenerima').value,
                nim: document.getElementById('nim').value,
                nomorResi: document.getElementById('nomorResi').value,
                kurir: document.getElementById('kurir').value,
                beratPaket: document.getElementById('beratPaket').value,
                noWhatsapp: document.getElementById('noWhatsapp').value,
                tanggalMasuk: document.getElementById('tanggalMasuk').value,
                catatan: document.getElementById('catatan').value,
                diambil: false,
                tanggalDiambil: null
            };
            
            packages.push(packageData);
            localStorage.setItem('packages', JSON.stringify(packages));
            
            // Send notification
            const message = `Halo ${packageData.namaPenerima}, paket Anda dengan nomor resi ${packageData.nomorResi} dari ${packageData.kurir} telah tiba di Logistic Corner Politeknik Negeri Lampung. Mohon segera diambil. Terima kasih!`;
            
            addNotification('arrival', `Paket baru tiba untuk ${packageData.namaPenerima}`, packageData);
            sendWhatsAppNotification(packageData.noWhatsapp, message);
            
            // Reset form
            this.reset();
            document.getElementById('tanggalMasuk').value = new Date().toISOString().slice(0, 16);
            
            // Show success message
            alert('Paket berhasil disimpan dan notifikasi telah dikirim!');
            
            // Update display
            updateDashboard();
            displayPackages();
        });

        // Display packages in table
        function displayPackages(filter = {}) {
            const tbody = document.getElementById('tablePaket');
            let filteredPackages = packages;
            
            // Apply search filter
            if (filter.search) {
                filteredPackages = filteredPackages.filter(p => 
                    p.namaPenerima.toLowerCase().includes(filter.search.toLowerCase()) ||
                    p.nomorResi.toLowerCase().includes(filter.search.toLowerCase()) ||
                    p.nim.toLowerCase().includes(filter.search.toLowerCase())
                );
            }
            
            // Apply status filter
            if (filter.status === 'belum') {
                filteredPackages = filteredPackages.filter(p => !p.diambil);
            } else if (filter.status === 'sudah') {
                filteredPackages = filteredPackages.filter(p => p.diambil);
            }
            
            if (filteredPackages.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" class="px-4 py-8 text-center text-gray-500">Tidak ada data paket</td></tr>';
                return;
            }
            
            tbody.innerHTML = filteredPackages.map((pkg, index) => {
                const now = new Date();
                const masuk = new Date(pkg.tanggalMasuk);
                const lamaDays = Math.floor((now - masuk) / (1000 * 60 * 60 * 24));
                const denda = pkg.diambil ? 0 : calculateFine(pkg.tanggalMasuk);
                const statusClass = pkg.diambil ? 'bg-green-100 text-green-800' : (denda > 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                const statusText = pkg.diambil ? 'Sudah Diambil' : 'Belum Diambil';
                
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">${index + 1}</td>
                        <td class="px-4 py-3 font-mono text-sm">${pkg.nomorResi}</td>
                        <td class="px-4 py-3">${pkg.namaPenerima}</td>
                        <td class="px-4 py-3">${pkg.nim}</td>
                        <td class="px-4 py-3">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">${pkg.kurir}</span>
                        </td>
                        <td class="px-4 py-3 text-sm">${new Date(pkg.tanggalMasuk).toLocaleString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</td>
                        <td class="px-4 py-3">${lamaDays} hari</td>
                        <td class="px-4 py-3 font-semibold ${denda > 0 ? 'text-red-600' : ''}">${formatRupiah(denda)}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold ${statusClass}">${statusText}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <button onclick="viewDetail(${pkg.id})" class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${!pkg.diambil ? `
                                    <button onclick="markAsTaken(${pkg.id})" class="text-green-600 hover:text-green-800" title="Tandai Diambil">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="sendReminder(${pkg.id})" class="text-orange-600 hover:text-orange-800" title="Kirim Pengingat">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                ` : ''}
                                <button onclick="deletePackage(${pkg.id})" class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // View package detail
        function viewDetail(id) {
            const pkg = packages.find(p => p.id === id);
            if (!pkg) return;
            
            const denda = pkg.diambil ? pkg.dendaDibayar || 0 : calculateFine(pkg.tanggalMasuk);
            
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Nomor Resi</p>
                            <p class="font-semibold">${pkg.nomorResi}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Kurir</p>
                            <p class="font-semibold">${pkg.kurir}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Nama Penerima</p>
                            <p class="font-semibold">${pkg.namaPenerima}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">NIM/NIP</p>
                            <p class="font-semibold">${pkg.nim}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Berat Paket</p>
                            <p class="font-semibold">${pkg.beratPaket || '-'} kg</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Nomor WhatsApp</p>
                            <p class="font-semibold">${pkg.noWhatsapp || '-'}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Masuk</p>
                            <p class="font-semibold">${new Date(pkg.tanggalMasuk).toLocaleString('id-ID')}</p>
                        </div>
                        ${pkg.diambil ? `
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Diambil</p>
                            <p class="font-semibold">${new Date(pkg.tanggalDiambil).toLocaleString('id-ID')}</p>
                        </div>
                        ` : ''}
                        <div class="col-span-2">
                            <p class="text-gray-600 text-sm">Denda</p>
                            <p class="font-semibold text-lg ${denda > 0 ? 'text-red-600' : 'text-green-600'}">${formatRupiah(denda)}</p>
                        </div>
                        ${pkg.catatan ? `
                        <div class="col-span-2">
                            <p class="text-gray-600 text-sm">Catatan</p>
                            <p class="font-semibold">${pkg.catatan}</p>
                        </div>
                        ` : ''}
                        <div class="col-span-2">
                            <p class="text-gray-600 text-sm">Status</p>
                            <p class="font-semibold">
                                <span class="px-3 py-1 rounded ${pkg.diambil ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                    ${pkg.diambil ? 'Sudah Diambil' : 'Belum Diambil'}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('detailModal').classList.add('active');
        }

        // Mark package as taken
        function markAsTaken(id) {
            const pkg = packages.find(p => p.id === id);
            if (!pkg) return;
            
            const denda = calculateFine(pkg.tanggalMasuk);
            
            if (denda > 0) {
                const confirm = window.confirm(`Paket ini memiliki denda sebesar ${formatRupiah(denda)}. Apakah denda sudah dibayar?`);
                if (!confirm) return;
            }
            
            pkg.diambil = true;
            pkg.tanggalDiambil = new Date().toISOString();
            pkg.dendaDibayar = denda;
            
            localStorage.setItem('packages', JSON.stringify(packages));
            
            addNotification('taken', `Paket ${pkg.nomorResi} telah diambil oleh ${pkg.namaPenerima}`, pkg);
            
            updateDashboard();
            displayPackages();
            
            alert('Paket berhasil ditandai sebagai sudah diambil!');
        }

        // Send reminder
        function sendReminder(id) {
            const pkg = packages.find(p => p.id === id);
            if (!pkg) return;
            
            const denda = calculateFine(pkg.tanggalMasuk);
            const message = `Pengingat: Paket Anda dengan nomor resi ${pkg.nomorResi} dari ${pkg.kurir} masih berada di Logistic Corner. ${denda > 0 ? `Denda saat ini: ${formatRupiah(denda)}.` : ''} Mohon segera diambil!`;
            
            addNotification('reminder', `Pengingat dikirim ke ${pkg.namaPenerima}`, pkg);
            sendWhatsAppNotification(pkg.noWhatsapp, message);
            
            alert('Pengingat berhasil dikirim!');
        }

        // Delete package
        function deletePackage(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data paket ini?')) return;
            
            packages = packages.filter(p => p.id !== id);
            localStorage.setItem('packages', JSON.stringify(packages));
            
            updateDashboard();
            displayPackages();
            
            alert('Paket berhasil dihapus!');
        }

        // Search and filter
        document.getElementById('searchPaket').addEventListener('input', function() {
            const search = this.value;
            const status = document.getElementById('filterStatus').value;
            displayPackages({ search, status });
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            const search = document.getElementById('searchPaket').value;
            const status = this.value;
            displayPackages({ search, status });
        });

        // Tracking
        document.getElementById('btnTracking').addEventListener('click', function() {
            const query = document.getElementById('trackingInput').value.trim();
            if (!query) {
                alert('Mohon masukkan nomor resi atau NIM!');
                return;
            }
            
            const results = packages.filter(p => 
                p.nomorResi.toLowerCase().includes(query.toLowerCase()) ||
                p.nim.toLowerCase().includes(query.toLowerCase())
            );
            
            const resultDiv = document.getElementById('trackingResult');
            
            if (results.length === 0) {
                resultDiv.innerHTML = `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                        <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-3"></i>
                        <p class="text-gray-700">Paket tidak ditemukan</p>
                    </div>
                `;
                resultDiv.classList.remove('hidden');
                return;
            }
            
            resultDiv.innerHTML = results.map(pkg => {
                const denda = pkg.diambil ? pkg.dendaDibayar || 0 : calculateFine(pkg.tanggalMasuk);
                return `
                    <div class="bg-white border rounded-lg p-6 mb-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">${pkg.nomorResi}</h3>
                                <p class="text-gray-600">${pkg.kurir}</p>
                            </div>
                            <span class="px-3 py-1 rounded font-semibold ${pkg.diambil ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                ${pkg.diambil ? 'Sudah Diambil' : 'Belum Diambil'}
                            </span>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-user w-6 text-gray-400"></i>
                                <span class="text-gray-700">${pkg.namaPenerima} (${pkg.nim})</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar w-6 text-gray-400"></i>
                                <span class="text-gray-700">Masuk: ${new Date(pkg.tanggalMasuk).toLocaleString('id-ID', {day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</span>
                            </div>
                            ${pkg.diambil ? `
                            <div class="flex items-center">
                                <i class="fas fa-check-circle w-6 text-green-500"></i>
                                <span class="text-gray-700">Diambil: ${new Date(pkg.tanggalDiambil).toLocaleString('id-ID', {day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</span>
                            </div>
                            ` : ''}
                            <div class="flex items-center">
                                <i class="fas fa-money-bill w-6 text-gray-400"></i>
                                <span class="text-gray-700">Denda: <strong class="${denda > 0 ? 'text-red-600' : 'text-green-600'}">${formatRupiah(denda)}</strong></span>
                            </div>
                            ${pkg.catatan ? `
                            <div class="flex items-start">
                                <i class="fas fa-sticky-note w-6 text-gray-400 mt-1"></i>
                                <span class="text-gray-700">${pkg.catatan}</span>
                            </div>
                            ` : ''}
                        </div>
                        
                        ${!pkg.diambil ? `
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                Paket Anda tersedia di Logistic Corner. Silakan ambil di jam operasional (Senin-Jumat, 08:00-16:00).
                            </p>
                        </div>
                        ` : ''}
                    </div>
                `;
            }).join('');
            
            resultDiv.classList.remove('hidden');
        });

        // Notification modal
        document.getElementById('notifBtn').addEventListener('click', function() {
            displayNotifications();
            document.getElementById('notificationModal').classList.add('active');
        });

        document.getElementById('closeNotifModal').addEventListener('click', function() {
            document.getElementById('notificationModal').classList.remove('active');
        });

        document.getElementById('closeDetailModal').addEventListener('click', function() {
            document.getElementById('detailModal').classList.remove('active');
        });

        function displayNotifications() {
            const notifList = document.getElementById('notificationList');
            
            if (notifications.length === 0) {
                notifList.innerHTML = '<p class="text-center text-gray-500">Tidak ada notifikasi</p>';
                return;
            }
            
            // Mark all as read
            notifications.forEach(n => n.read = true);
            localStorage.setItem('notifications', JSON.stringify(notifications));
            updateNotificationBadge();
            
            notifList.innerHTML = notifications.map(notif => {
                const icon = notif.type === 'arrival' ? 'fa-box' : 
                           notif.type === 'reminder' ? 'fa-bell' : 'fa-check-circle';
                const color = notif.type === 'arrival' ? 'blue' : 
                            notif.type === 'reminder' ? 'orange' : 'green';
                
                return `
                    <div class="mb-4 p-4 border rounded-lg hover:bg-gray-50">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-${color}-100 rounded-full flex items-center justify-center">
                                    <i class="fas ${icon} text-${color}-600"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800">${notif.message}</p>
                                <p class="text-sm text-gray-500 mt-1">${new Date(notif.timestamp).toLocaleString('id-ID')}</p>
                                ${notif.packageData ? `
                                <div class="mt-2 text-sm text-gray-600">
                                    <p>Resi: ${notif.packageData.nomorResi} | ${notif.packageData.kurir}</p>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Auto-check for packages that need reminders (simulation)
        function checkReminders() {
            const today = new Date();
            packages.filter(p => !p.diambil).forEach(pkg => {
                const masuk = new Date(pkg.tanggalMasuk);
                const daysSince = Math.floor((today - masuk) / (1000 * 60 * 60 * 24));
                
                // Send reminder every 3 days
                if (daysSince > 0 && daysSince % 3 === 0) {
                    const lastReminder = notifications.find(n => 
                        n.type === 'reminder' && 
                        n.packageData?.id === pkg.id &&
                        new Date(n.timestamp).toDateString() === today.toDateString()
                    );
                    
                    if (!lastReminder) {
                        sendReminder(pkg.id);
                    }
                }
            });
        }

        // Initialize
        updateDashboard();
        displayPackages();
        updateNotificationBadge();
        
        // Check reminders every hour
        setInterval(checkReminders, 3600000);
        
        // Add some sample data for demonstration (comment out in production)
        if (packages.length === 0) {
            const samplePackages = [
                {
                    id: Date.now() - 1000000,
                    namaPenerima: "Budi Santoso",
                    nim: "2141001",
                    nomorResi: "JNE1234567890",
                    kurir: "JNE",
                    beratPaket: "2.5",
                    noWhatsapp: "081234567890",
                    tanggalMasuk: new Date(Date.now() - 5 * 24 * 60 * 60 * 1000).toISOString(),
                    catatan: "Paket besar",
                    diambil: false,
                    tanggalDiambil: null
                },
                {
                    id: Date.now() - 2000000,
                    namaPenerima: "Siti Aminah",
                    nim: "2141002",
                    nomorResi: "JNTX987654321",
                    kurir: "J&T",
                    beratPaket: "1.0",
                    noWhatsapp: "082345678901",
                    tanggalMasuk: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString(),
                    catatan: "",
                    diambil: false,
                    tanggalDiambil: null
                },
                {
                    id: Date.now() - 3000000,
                    namaPenerima: "Ahmad Fauzi",
                    nim: "2141003",
                    nomorResi: "SPX555666777",
                    kurir: "Shopee Express",
                    beratPaket: "0.5",
                    noWhatsapp: "083456789012",
                    tanggalMasuk: new Date(Date.now() - 10 * 24 * 60 * 60 * 1000).toISOString(),
                    catatan: "",
                    diambil: true,
                    tanggalDiambil: new Date(Date.now() - 8 * 24 * 60 * 60 * 1000).toISOString(),
                    dendaDibayar: 2000
                }
            ];
            
            packages = samplePackages;
            localStorage.setItem('packages', JSON.stringify(packages));
            
            // Add sample notifications
            samplePackages.forEach(pkg => {
                addNotification('arrival', `Paket baru tiba untuk ${pkg.namaPenerima}`, pkg);
            });
            
            updateDashboard();
            displayPackages();
        }
    </script>
</body>
</html>
