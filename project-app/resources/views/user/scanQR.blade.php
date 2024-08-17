<!-- resources/views/user/scanQR.blade.php -->
@extends('user.home')

@section('scanQR-content')
    <!-- QR CODE NOT YET WORKING IDK -->
    <h1 class="text-3xl font-semibold mb-4 mt-10 text-center">Scan QR</h1>
    <p class="text-center">Choose one of the options below:</p>

    <!-- Image -->
    <img id="placeholderImage" src="https://img.freepik.com/free-vector/smartphone-scanning-qr-code_23-2148624200.jpg?t=st=1723817740~exp=1723821340~hmac=9cf5113a1a0555e185320d1c6a533c3f41d94532e4706688499003f637de4c6e&w=826" alt="QR Code Scanning" class="mt-4 w-full max-w-lg mx-auto">

    <!-- Buttons -->
    <div class="flex justify-center mt-10 space-x-4">
        <!-- Scan QR Button -->
        <button onclick="startScanning()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Scan QR Code</button>

        <!-- Upload Image Button -->
        <button onclick="document.getElementById('uploadInput').click()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Upload Image</button>
    </div>

    <input type="file" id="uploadInput" accept="image/*" style="display: none;" onchange="handleImageChange(event)">

    <!-- Video Element for QR Scanning -->
    <div id="qr-scanner" class="mt-4 hidden">
        <video id="qr-video" class="w-full max-w-lg mx-auto" autoplay></video>
    </div>
    
    <!-- Include html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    
    <!-- Script to handle QR scanning -->
    <script>
        function startScanning() {
            const placeholderImage = document.getElementById('placeholderImage');
            const qrScanner = document.getElementById('qr-scanner');
            const qrVideo = document.getElementById('qr-video');

            // Hide the placeholder image and show the video element
            placeholderImage.style.display = 'none';
            qrScanner.style.display = 'block';

            // Initialize the QR code scanner
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    // Use the first available camera
                    const cameraId = devices[0].id;
                    const qrScannerInstance = new Html5Qrcode("qr-video");

                    qrScannerInstance.start(
                        { facingMode: "environment" }, // Use the rear camera
                        {
                            fps: 10, // Frame rate
                            qrbox: 250 // QR box size
                        },
                        (decodedText, decodedResult) => {
                            // Handle the decoded QR code text
                            alert(`QR Code detected: ${decodedText}`);
                            qrScannerInstance.stop().then(() => {
                                // Stop scanning and reset the view
                                qrScanner.style.display = 'none';
                                placeholderImage.style.display = 'block';
                            }).catch((error) => {
                                console.error(`Unable to stop QR code scanner: ${error}`);
                            });
                        },
                        (error) => {
                            console.error(`QR Code scanning error: ${error}`);
                        }
                    ).catch((error) => {
                        console.error(`Unable to start QR code scanner: ${error}`);
                    });
                } else {
                    console.error("No cameras found.");
                }
            }).catch(error => {
                console.error(`Unable to get cameras: ${error}`);
            });
        }

        function handleImageChange(event) {
            const file = event.target.files[0];
            if (file) {
                // Handle the file here (e.g., upload or process the image)
                console.log("File selected: ", file);
                // You might want to add further processing here
            }
        }
    </script>
@endsection
