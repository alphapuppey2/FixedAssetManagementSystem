@extends('user.home')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ "Scan QR" }}
    </h2>
@endsection

@section('content')

    <p class="text-center">Choose one of the options below:</p>

    <div id="placeholderImage">
        <img src="{{ asset('images/scanQRImage.png') }}" alt="QR Code Scanning" class="w-full max-w-md mx-auto">
    </div>

    <!-- QR Scanner Container -->
    <div id="qr-scanner-wrapper" class="mt-4 flex justify-center">
        <div id="qr-scanner-container" class="hidden relative w-full max-w-md max-h-screen aspect-square bg-black">
            <video id="video" class="w-full h-full border border-black object-cover bg-black"></video>
            <div id="qr-bar"
                 class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1/3 h-1/3 border-2 border-gray-300"></div>
        </div>
    </div>

    <!-- Success Notification -->
    <div id="scan-success" aria-live="polite" class="hidden fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded-md shadow-md">
        Scan Successful: <span id="qr-result"></span>
    </div>

    <!-- Error Notification -->
    <div id="scan-error" class="hidden fixed bottom-20 right-20 bg-red-600 text-white px-4 py-2 rounded-md">
        No QR code found in the image.
    </div>

    <!-- Buttons for Scanning or Uploading -->
    <div class="flex justify-center mt-10 space-x-4">
        <button id="scanButton" onclick="toggleScan()" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700">
            Scan QR Code
        </button>
        <button onclick="document.getElementById('uploadInput').click()" class="bg-gray-500 text-white px-4 py-2 rounded hover:text-gray-400">
            Upload Image
        </button>
    </div>

    <!-- Hidden File Input for Image Upload -->
    <input type="file" id="uploadInput" name="qr_image" accept="image/*" class="hidden" onchange="handleImageChange(event)">

    <!-- ZXing Library -->
    <script src="https://unpkg.com/@zxing/library@0.18.6/umd/index.min.js"></script>

    <script>
        let codeReader = new ZXing.BrowserQRCodeReader();
        let isScanning = false;

        function toggleScan() {
            if (isScanning) {
                stopScanning();
            } else {
                startScanning();
            }
        }

        function startScanning() {
            if (isScanning) return;

            isScanning = true;
            document.getElementById('scanButton').textContent = 'Cancel Scan';
            document.getElementById('placeholderImage').style.display = 'none';
            document.getElementById('qr-scanner-container').style.display = 'block';

            codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
                if (result) {
                    showSuccessNotification(result.text);
                    stopScanning();
                } else if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error("Scanning Error:", err);
                }
            });
        }

        function stopScanning() {
            codeReader.reset();
            isScanning = false;

            document.getElementById('scanButton').textContent = 'Scan QR Code';
            document.getElementById('qr-scanner-container').style.display = 'none';
            document.getElementById('placeholderImage').style.display = 'block';
        }

        function handleImageChange(event) {
            stopScanning();

            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function () {
                    showLoading();
                    const img = new Image();
                    img.src = reader.result;
                    img.onload = function () {
                        codeReader.decodeFromImage(img).then(result => {
                            hideLoading();
                            showSuccessNotification(result.text);
                        }).catch(err => {
                            hideLoading();
                            console.error(err);
                            showErrorNotification("No QR code found in the image.");
                        });
                    };
                };
                reader.readAsDataURL(file);
            }
        }

        function showSuccessNotification(qrText) {
            const notification = document.getElementById('scan-success');
            const resultElement = document.getElementById('qr-result');

            resultElement.textContent = qrText;
            notification.style.display = 'block';

            setTimeout(() => {
                notification.style.display = 'none';
                window.location.href = `/assetdetails/${qrText}`;
            }, 3000);
        }

        function showErrorNotification(message) {
            const notification = document.getElementById('scan-error');
            notification.textContent = message;
            notification.style.display = 'block';

            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        function showLoading() {
            const loader = document.createElement('div');
            loader.id = 'loading';
            loader.textContent = 'Processing...';
            loader.className = 'fixed inset-0 flex items-center justify-center bg-opacity-50 bg-gray-700 text-white';
            document.body.appendChild(loader);
        }

        function hideLoading() {
            const loader = document.getElementById('loading');
            if (loader) loader.remove();
        }

        setTimeout(() => {
            const sessionError = document.getElementById('session-error');
            if (sessionError) {
                sessionError.style.display = 'none';
            }
        }, 3000);
    </script>
@endsection
