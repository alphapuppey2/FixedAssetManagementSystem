<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zxing\QrReader;

class QRUserController extends Controller
{
    public function uploadQRImage(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Store the uploaded image
        $imagePath = $request->file('qr_image')->store('qr_images', 'public');

        // Decode the QR code from the image
        $qrcode = new QrReader(storage_path('app/public/' . $imagePath));
        $text = $qrcode->text();

        // Return the decoded text
        return back()->with('success', 'QR Code detected: ' . $text);
    }
}
