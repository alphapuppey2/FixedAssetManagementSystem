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
        </div>
    </div>

    <!-- Buttons for Scanning or Uploading -->
    <div class="flex justify-center mt-10 space-x-4">
        <button id="scanButton" onclick="toggleScan()" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700">
            Scan QR Code
        </button>
        <button onclick="document.getElementById('uploadInput').click()"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:text-gray-400">
            Upload Image
        </button>
    </div>

    <!-- Hidden File Input for Image Upload -->
    <input type="file" id="uploadInput" name="qr_image" accept="image/*" class="hidden" onchange="handleImageChange(event)">

    <!-- Loading Screen -->
    <div id="loadingScreen" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="text-center">
            <div class="loader mb-4"></div>
            <p class="text-white text-lg">Processing, please wait...</p>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ZXing Library -->
    <script src="https://unpkg.com/@zxing/library@0.18.6/umd/index.min.js"></script>

    <style>
        .loader {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        let codeReader = new ZXing.BrowserQRCodeReader();
        let isScanning = false;
        const SCAN_DELAY_MS = 2000; // Delay duration (2 seconds)

        function toggleScan() {
            isScanning ? stopScanning() : startScanning();
        }

        async function startScanning() {
            if (isScanning) return;
            isScanning = true;
            updateUIForScanning(true);

            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
                document.getElementById('video').srcObject = stream;

                codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
                    if (result) {
                        console.log('QR Code Found:', result.text);
                        showLoadingScreen(); // Show loading screen
                        setTimeout(() => {
                            hideLoadingScreen();
                            checkQRCode(result.text);
                        }, SCAN_DELAY_MS); // Delay for smooth UI experience
                        stopScanning();
                    } else if (err && !(err instanceof ZXing.NotFoundException)) {
                        console.error("Scanning Error:", err);
                        showErrorToast('Scanning failed. Please try again.');
                    }
                });
            } catch (error) {
                console.error('Camera Access Error:', error);
                showErrorToast('Unable to access the camera. Please check your permissions.');
                stopScanning();
            }
        }

        function stopScanning() {
            codeReader.reset();
            isScanning = false;
            stopVideoStream();
            updateUIForScanning(false);
        }

        function stopVideoStream() {
            const videoElement = document.getElementById('video');
            const stream = videoElement.srcObject;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        }

        function handleImageChange(event) {
            stopScanning();
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function () {
                    const img = new Image();
                    img.src = reader.result;
                    img.onload = function () {
                        showLoadingScreen(); // Show loading screen during image processing
                        setTimeout(() => {
                            codeReader.decodeFromImage(img).then(result => {
                                console.log('QR Code Found:', result.text);
                                hideLoadingScreen();
                                checkQRCode(result.text);
                            }).catch(err => {
                                console.error("Error:", err);
                                hideLoadingScreen();
                                showErrorToast("No QR code found in the image.");
                            });
                        }, SCAN_DELAY_MS);
                    };
                };
                reader.readAsDataURL(file);
            } else {
                showErrorToast('Please upload a valid image file.');
            }
        }

        function checkQRCode(qrText) {
            fetch('/validate-qr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ qr_code: qrText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.found) {
                    window.location.href = `/assetdetails/${data.code}`;
                } else {
                    showErrorToast('No asset found with the scanned QR code.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('An error occurred. Please try again.');
            });
        }

        function showLoadingScreen() {
            document.getElementById('loadingScreen').classList.remove('hidden');
        }

        function hideLoadingScreen() {
            document.getElementById('loadingScreen').classList.add('hidden');
        }

        function showErrorToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function updateUIForScanning(isScanning) {
            document.getElementById('scanButton').textContent = isScanning ? 'Cancel Scan' : 'Scan QR Code';
            document.getElementById('placeholderImage').style.display = isScanning ? 'none' : 'block';
            document.getElementById('qr-scanner-container').style.display = isScanning ? 'block' : 'none';
        }

        // Auto-hide session-based toast after 3 seconds
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) toast.remove();
        }, 3000);
    </script>
@endsection
