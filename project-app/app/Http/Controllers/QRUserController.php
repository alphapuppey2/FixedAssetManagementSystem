<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Zxing\QrReader;
use App\Models\assetModel;
use Illuminate\Support\Facades\Auth;

class QRUserController extends Controller
{
    public function uploadQRImage(Request $request)
    {
        // Validate the uploaded image
        $request->validate([
            'qr_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Store the uploaded image
        $imagePath = $request->file('qr_image')->store('qr_images', 'public');

        // Decode the QR code from the image
        $qrcode = new QrReader(storage_path('app/public/' . $imagePath));
        $text = $qrcode->text();

        // Redirect to asset details view
        return redirect()->route('qr.asset.details', ['code' => $text]);
    }

    public function showDetails($code)
    {
        $userDept = Auth::user()->dept_id;
        $userType = Auth::user()->usertype;

        // Query the asset based on the scanned code and user's department
        $retrieveDataQuery = assetModel::where('asset.code', $code)
            ->join('department', 'asset.dept_ID', '=', 'department.id')
            ->join('category', 'asset.ctg_ID', '=', 'category.id')
            ->join('model', 'asset.model_key', '=', 'model.id')
            ->join('manufacturer', 'asset.manufacturer_key', '=', 'manufacturer.id')
            ->join('location', 'asset.loc_key', '=', 'location.id')
            ->select(
                'asset.id',
                'asset.code',
                'asset.name',
                'asset.image',
                'asset.cost',
                'asset.depreciation',
                'asset.salvageVal',
                'asset.usage_Lifespan',
                'asset.status',
                'asset.ctg_ID',
                'asset.dept_ID',
                'asset.manufacturer_key',
                'asset.model_key',
                'asset.loc_key',
                'asset.custom_fields',
                'asset.qr',  // Retrieve QR code
                'asset.created_at',
                'asset.updated_at',
                'category.name as category',
                'model.name as model',
                'location.name as location',
                'manufacturer.name as manufacturer',
                'department.name as department'
            );

        // If the user is not an admin, restrict by department
        if ($userType != 'admin') {
            $retrieveDataQuery->where('asset.dept_ID', '=', $userDept);
        }

        // Retrieve asset data
        $retrieveData = $retrieveDataQuery->first();

        // If asset not found, redirect back with error message
        if (!$retrieveData) {
            return redirect()->back()->with('status', 'No asset found with the scanned QR code.');
        }

        // Decode custom fields
        $fields = json_decode($retrieveData->custom_fields, true);

        // Pass the asset data, custom fields, and QR code to the view
        return view('user.assetDetail', compact('retrieveData', 'fields'));
    }
}
