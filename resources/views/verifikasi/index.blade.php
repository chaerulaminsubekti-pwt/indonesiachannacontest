@extends('layouts.public')

@section('title', 'Verifikasi Sertifikat')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-[#FF1A1A]/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-[#FF1A1A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-[#0A0A0A] mb-2">Verifikasi Sertifikat</h1>
        <p class="text-[#64748B]">Scan QR Code pada sertifikat untuk memverifikasi keasliannya</p>
    </div>

    <!-- Camera Scanner -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-[#0A0A0A] mb-2">Scan QR Code Sertifikat</h2>
            <p class="text-[#64748B]">Arahkan kamera ke QR Code pada sertifikat</p>
        </div>

        <div class="relative max-w-md mx-auto">
            <div id="scanner-container" class="w-full rounded-xl overflow-hidden border-2 border-[#FF1A1A]/20" style="min-height:300px"></div>

            <div class="mt-4 flex gap-3 justify-center">
                <button id="start-scan" class="flex-1 bg-[#FF1A1A] text-white py-3 px-6 rounded-xl font-semibold hover:bg-[#CC1515] transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    <span>Mulai Scan</span>
                </button>
                <button id="stop-scan" class="hidden flex-1 bg-gray-100 text-[#0A0A0A] py-3 px-6 rounded-xl font-semibold hover:bg-gray-200 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>Berhenti</span>
                </button>
                <label id="upload-btn" class="flex-1 bg-gray-100 text-[#0A0A0A] py-3 px-6 rounded-xl font-semibold hover:bg-gray-200 transition flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Upload Gambar</span>
                    <input id="upload-input" type="file" accept="image/*" class="hidden">
                </label>
            </div>

            <p id="scan-status" class="text-center mt-4 text-sm text-[#64748B] hidden">Menunggu scan...</p>
        </div>
    </div>

    <!-- Manual Input Fallback -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-[#0A0A0A] mb-2">Atau Masukkan Kode Manual</h2>
            <p class="text-[#64748B]">Jika kamera tidak tersedia, masukkan <strong>Kode Verifikasi</strong> atau <strong>Nomor Sertifikat</strong></p>
        </div>

        <form id="manual-form" class="max-w-md mx-auto">
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#0A0A0A] mb-2">Kode Verifikasi / Nomor Sertifikat</label>
                <input type="text" id="manual-code" name="kode_verifikasi" 
                    placeholder="Masukkan Kode Verifikasi (ABC123XYZ) atau Nomor Sertifikat (ICC/7/1/20260709)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#FF1A1A] focus:border-[#FF1A1A] transition"
                    autocomplete="off">
            </div>
            <button type="submit" class="w-full bg-[#FF1A1A] text-white py-3 px-6 rounded-xl font-semibold hover:bg-[#CC1515] transition">
                Verifikasi
            </button>
        </form>
    </div>

    <!-- Result Modal -->
    <div id="result-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden animate-scale-in">
            <div id="modal-header" class="px-6 py-2 flex items-center justify-end">
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="modal-content"></div>
                <div class="mt-4 flex gap-3">
                    <button id="modal-download" class="hidden flex-1 bg-[#FF1A1A] text-white py-3 px-6 rounded-xl font-semibold hover:bg-[#CC1515] transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Download Sertifikat</span>
                    </button>
                    <button id="modal-close" class="flex-1 bg-gray-100 text-[#0A0A0A] py-3 px-6 rounded-xl font-semibold hover:bg-gray-200 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>
    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function() {
        var html5QrCode = null;
        var isScanning = false;
        var retryCount = 0;

        function handleScanResult(code) {
            if (!isScanning) return;
            stopScanner();
            code = extractCodeFromUrl(code);
            verifyCode(code);
        }

        function verifyCode(code) {
            var statusEl = document.getElementById('scan-status');
            statusEl.textContent = 'Memverifikasi kode...';
            statusEl.classList.remove('hidden');

            fetch('/verifikasi/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ kode_verifikasi: code })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) { showResult(data); })
            .catch(function(err) {
                console.error(err);
                showResult({ success: false, message: 'Terjadi kesalahan saat verifikasi' });
            });
        }

        function showResult(data) {
            var modal = document.getElementById('result-modal');
            var content = document.getElementById('modal-content');
            var downloadBtn = document.getElementById('modal-download');

            if (data.success) {
                var d = data.data;
                content.innerHTML = [
                    '<div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">',
                    '  <div class="flex items-center justify-center mb-3">',
                    '    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">',
                    '      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                    '    </svg>',
                    '  </div>',
                    '  <h4 class="text-lg font-bold text-green-800 text-center mb-2">Sertifikat Valid</h4>',
                    '  <p class="text-green-700 text-center mb-4">Resmi Dari Indonesia Channa Contest</p>',
                    '</div>',
                    '<div class="bg-white border border-gray-200 rounded-xl p-4 space-y-3 text-sm">',
                    '  <div class="grid grid-cols-2 gap-2">',
                    '    <span class="text-gray-500">Nama Pemenang:</span>',
                    '    <span class="font-medium">' + escapeHtml(d.nama_pemenang) + '</span>',
                    '    <span class="text-gray-500">Kelas:</span>',
                    '    <span class="font-medium">' + escapeHtml(d.kelas) + '</span>',
                    '    <span class="text-gray-500">Event:</span>',
                    '    <span class="font-medium">' + escapeHtml(d.event) + '</span>',
                    '    <span class="text-gray-500">Tanggal Event:</span>',
                    '    <span class="font-medium">' + escapeHtml(d.tanggal_event) + '</span>',
                    '    <span class="text-gray-500">Venue:</span>',
                    '    <span class="font-medium">' + escapeHtml(d.venue) + ', ' + escapeHtml(d.wilayah_kota) + '</span>',
                    '    <span class="text-gray-500">No. Sertifikat:</span>',
                    '    <span class="font-medium">' + escapeHtml(d.nomor_sertifikat) + '</span>',
                    '    <span class="text-gray-500">Tanggal Terbit:</span>',
                    '    <span class="font-medium">' + escapeHtml(d.tanggal_terbit) + '</span>',
                    '  </div>',
                    '</div>'
                ].join('\n');
                downloadBtn.href = data.data.download_url;
                downloadBtn.classList.remove('hidden');
            } else {
                content.innerHTML = [
                    '<div class="bg-red-50 border border-red-200 rounded-xl p-4">',
                    '  <div class="flex items-center justify-center mb-3">',
                    '    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">',
                    '      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
                    '    </svg>',
                    '  </div>',
                    '  <h4 class="text-lg font-bold text-red-800 text-center mb-2">Sertifikat Tidak Ditemukan</h4>',
                    '  <p class="text-red-700 text-center">' + escapeHtml(data.message) + '</p>',
                    '</div>'
                ].join('\n');
                downloadBtn.classList.add('hidden');
            }

            modal.classList.remove('hidden');
        }

        function tryStartCamera(facingMode) {
            var statusEl = document.getElementById('scan-status');

            if (typeof Html5Qrcode === 'undefined') {
                statusEl.textContent = 'Library scanner gagal dimuat. Coba refresh halaman.';
                statusEl.classList.add('text-red-500');
                stopScanner();
                return;
            }

            html5QrCode = new Html5Qrcode("scanner-container");
            html5QrCode.start(
                { facingMode: facingMode },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                function(decodedText) { handleScanResult(decodedText); },
                function() {}
            ).then(function() {
                isScanning = true;
                retryCount = 0;
                statusEl.textContent = 'Scanning... Arahkan kamera ke QR Code';
                statusEl.classList.add('text-[#FF1A1A]');
            }).catch(function(err) {
                console.error('Scanner error with ' + facingMode + ':', err);

                // If environment fails, try user (front camera)
                if (facingMode === 'environment') {
                    statusEl.textContent = 'Kamera belakang tidak bisa diakses, mencoba kamera depan...';
                    tryStartCamera('user');
                    return;
                }

                // If user also fails, show helpful error
                statusEl.textContent = 'Tidak bisa mengakses kamera. Pastikan tidak ada aplikasi lain yg memakai kamera, lalu coba lagi.';
                statusEl.classList.add('text-red-500');
                stopScanner();
            });
        }

        function startScanner() {
            var statusEl = document.getElementById('scan-status');
            statusEl.classList.remove('hidden', 'text-red-500');
            statusEl.textContent = 'Mengakses kamera...';

            stopScanner().then(function() {
                document.getElementById('start-scan').classList.add('hidden');
                document.getElementById('stop-scan').classList.remove('hidden');
                retryCount = 0;
                tryStartCamera('environment');
            });
        }

        function handleUploadScan(file) {
            var statusEl = document.getElementById('scan-status');
            statusEl.classList.remove('hidden', 'text-red-500');
            statusEl.textContent = 'Memindai gambar...';

            if (typeof Html5Qrcode === 'undefined') {
                statusEl.textContent = 'Library scanner gagal dimuat. Coba refresh halaman.';
                statusEl.classList.add('text-red-500');
                return;
            }

            var scanner = new Html5Qrcode("scanner-container");
            scanner.scanFile(file, true).then(function(decodedText) {
                statusEl.textContent = 'Memverifikasi kode...';
                decodedText = extractCodeFromUrl(decodedText);
                verifyCode(decodedText);
            }).catch(function(err) {
                console.error('Upload scan error:', err);
                statusEl.textContent = 'Tidak dapat membaca QR Code dari gambar. Coba upload gambar yang lebih jelas.';
                statusEl.classList.add('text-red-500');
            });
        }

        function extractCodeFromUrl(code) {
            try {
                if (code.startsWith('http://') || code.startsWith('https://')) {
                    var url = new URL(code);
                    var pathParts = url.pathname.split('/').filter(Boolean);
                    var lastSegment = pathParts[pathParts.length - 1];
                    if (lastSegment) return lastSegment;
                }
            } catch (e) {
                console.warn('QR scan result is not a valid URL, using raw value');
            }
            return code;
        }

        function stopScanner() {
            isScanning = false;

            var promise = Promise.resolve();

            if (html5QrCode) {
                promise = html5QrCode.stop().catch(function() {});
                html5QrCode = null;
            }

            return promise.then(function() {
                document.getElementById('start-scan').classList.remove('hidden');
                document.getElementById('stop-scan').classList.add('hidden');
                document.getElementById('scan-status').classList.add('hidden');
            });
        }

        document.getElementById('start-scan').addEventListener('click', startScanner);
        document.getElementById('stop-scan').addEventListener('click', stopScanner);

        document.getElementById('upload-input').addEventListener('change', function(e) {
            if (e.target.files && e.target.files.length > 0) {
                handleUploadScan(e.target.files[0]);
            }
            e.target.value = '';
        });

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('result-modal').classList.add('hidden');
        });
        document.getElementById('modal-close').addEventListener('click', function() {
            document.getElementById('result-modal').classList.add('hidden');
        });
        document.getElementById('result-modal').addEventListener('click', function(e) {
            if (e.target === e.currentTarget) {
                document.getElementById('result-modal').classList.add('hidden');
            }
        });

        document.getElementById('manual-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var code = document.getElementById('manual-code').value.trim();
            if (code) verifyCode(code);
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('result-modal').classList.add('hidden');
                if (isScanning) stopScanner();
            }
        });
    });
</script>
@endpush