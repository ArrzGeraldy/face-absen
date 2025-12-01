<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OfficeSetting;
use Illuminate\Http\Request;

class OfficeSettingController extends Controller
{
    public function index()
    {
        // Ambil record pertama (karena cuma 1 setting)
        $setting = OfficeSetting::first();

        return view('admin.office-setting.index', compact('setting'));
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'radius' => ['required', 'integer'],
            'jam_masuk' => ['required'],
            'jam_pulang' => ['required'],
            'toleransi_terlambat' => ['required', 'integer'],
        ]);

        // Cek apakah sudah ada data
        $setting = OfficeSetting::first();

        if (!$setting) {
            // CREATE
            OfficeSetting::create($validated);

            return redirect()->back()->with('success', 'Office setting created successfully.');
        }

        // UPDATE
        $setting->update($validated);

        return redirect()->back()->with('success', 'Office setting updated successfully.');
    }
}
