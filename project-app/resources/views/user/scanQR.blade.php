@extends('user.home')

@section('scanQR-content')

    <h1 class="text-3xl font-semibold mb-4 mt-10 text-center">Scan QR</h1>
    <p class="text-center">Choose one of the options below:</p>

    <div id="placeholderImage">
        <img src="https://img.freepik.com/free-vector/smartphone-scanning-qr-code_23-2148624200.jpg" alt="QR Code Scanning" class="mt-4 w-full max-w-lg mx-auto">
    </div>

    <!-- QR Scanner Container -->
    <div id="qr-scanner-wrapper" class="mt-4 flex justify-center">
        <div id="qr-scanner-container" style="display: none; position: relative; width: 500px; height: 500px;">
            <video id="video" style="width: 100%; height: 100%; border: 1px solid black; object-fit: cover; background-color: #000;"></video>
            <!-- QR box -->
            <div id="qr-bar" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 30%; height: 30%; border: 2px solid rgb(236, 220, 220);"></div>
        </div>
    </div>

    <!-- Success Notification -->
    <div id="scan-success" style="display: none; position: fixed; bottom: 20px; right: 20px; background-color: #564caf; color: white; padding: 15px; border-radius: 5px;">
        Scan Successful: <span id="qr-result"></span>
    </div>

    <!-- Error Notification -->
    <div id="scan-error" style="display: none; position: fixed; bottom: 20px; right: 20px; background-color: #b61f14; color: white; padding: 15px; border-radius: 5px;">
        No QR code found in the image.
    </div>


    <!-- Buttons for Scanning or Uploading -->
    <div class="flex justify-center mt-10 space-x-4">
        <button onclick="startScanning()" class="bg-blue-900  text-white px-4 py-2 rounded hover:bg-blue-700">Scan QR Code</button>
        <button onclick="document.getElementById('uploadInput').click()" class="bg-white-500 text-blue-900 px-4 py-2 rounded hover:text-blue-400">Upload Image</button>
    </div>

    <!-- Hidden File Input for Image Upload -->
    <input type="file" id="uploadInput" name="qr_image" accept="image/*" style="display: none;" onchange="handleImageChange(event)">

    <!-- ZXing Library for QR Code Scanning -->
    <script src="https://unpkg.com/@zxing/library@0.18.6/umd/index.min.js"></script>
    <script>
        let codeReader = new ZXing.BrowserQRCodeReader();
        let isScanning = false;

        function startScanning() {
            // Ensure only one scanning process is active
            if (isScanning) return;
            isScanning = true;

            document.getElementById('placeholderImage').style.display = 'none';
            document.getElementById('qr-scanner-container').style.display = 'block';

            // Start scanning from the video stream
            codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
                if (result) {
                    showSuccessNotification(result.text);
                    stopScanning();  // Stop scanning on success
                } else if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                }
            });
        }

        function stopScanning() {
            // Reset and stop the camera
            codeReader.reset();
            isScanning = false;

            // Hide the video scanner and show placeholder
            document.getElementById('qr-scanner-container').style.display = 'none';
            document.getElementById('placeholderImage').style.display = 'block';
        }

        function handleImageChange(event) {
            stopScanning();  // Stop video scanning if already active

            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    const img = new Image();
                    img.src = reader.result;
                    img.onload = function() {
                        codeReader.decodeFromImage(img).then(result => {
                            showSuccessNotification(result.text);
                        }).catch(err => {
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
            }, 5000); // Set the timeout to 5 seconds for the error message to disappear
        }

        // Function to make the session error disappear after 5 seconds
        setTimeout(function() {
            const sessionError = document.getElementById('session-error');
            if (sessionError) {
                sessionError.style.display = 'none';
            }
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
@endsection
