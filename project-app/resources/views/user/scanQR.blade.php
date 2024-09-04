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
            <div id="qr-bar" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 30%; height: 30%; border: 2px solid red;"></div>
        </div>
    </div>

    <!-- Success Notification -->
    <div id="scan-success" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background-color: #4CAF50; color: white; padding: 15px; border-radius: 5px;">
        Scan Successful: <span id="qr-result"></span>
    </div>

    <!-- Error Notification -->
    <div id="scan-error" style="display: none; position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background-color: #f44336; color: white; padding: 15px; border-radius: 5px;">
        No QR code found in the image.
    </div>

    <!-- Buttons for Scanning or Uploading -->
    <div class="flex justify-center mt-10 space-x-4">
        <button onclick="startScanning()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Scan QR Code</button>
        <button onclick="document.getElementById('uploadInput').click()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Upload Image</button>
    </div>

    <!-- Hidden File Input for Image Upload -->
    <input type="file" id="uploadInput" name="qr_image" accept="image/*" style="display: none;" onchange="handleImageChange(event)">

    <!-- ZXing Library for QR Code Scanning -->
    <script src="https://unpkg.com/@zxing/library@0.18.6/umd/index.min.js"></script>
    <script>
        let codeReader = new ZXing.BrowserQRCodeReader();

        function startScanning() {
            document.getElementById('placeholderImage').style.display = 'none';
            document.getElementById('qr-scanner-container').style.display = 'block';

            codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
                if (result) {
                    showSuccessNotification(result.text);
                    stopScanning();
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                }
            });
        }

        function stopScanning() {
            codeReader.reset();
            document.getElementById('qr-scanner-container').style.display = 'none';
            document.getElementById('placeholderImage').style.display = 'block';
        }

        function handleImageChange(event) {
            stopScanning();

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
                            showErrorNotification();
                        });
                    };
                };
                reader.readAsDataURL(file);
            }
        }

        function showSuccessNotification(qrText) {
            const notification = document.getElementById('scan-success');
            const resultElement = document.getElementById('qr-result');

            // Display the scanned QR code text (which is the asset code)
            resultElement.textContent = qrText;
            notification.style.display = 'block';

            // After 3 seconds (3000 milliseconds), hide the notification and redirect to the asset details page
            setTimeout(() => {
                notification.style.display = 'none';
                
                // This line redirects the user to the route that handles showing the asset details
                window.location.href = `/assetdetails/${qrText}`;
            }, 3000); // 3-second delay before redirect
        }



        function showErrorNotification() {
            const notification = document.getElementById('scan-error');
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
    </script>
@endsection
