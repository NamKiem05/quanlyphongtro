<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseSetting;
use Illuminate\Http\Request;

class HouseSettingController extends Controller
{
    /**
     * Hiển thị thông tin nhà trọ
     */
    public function index()
    {
        $houseSetting = HouseSetting::first();

        return view('admin.house_settings.index', compact('houseSetting'));
    }

    /**
     * Lưu hoặc cập nhật thông tin nhà trọ
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'house_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
        ]);

        $houseSetting = HouseSetting::first();

        if ($houseSetting) {
            $houseSetting->update($validated);
        } else {
            $houseSetting = HouseSetting::create($validated);
        }

        return redirect()
            ->route('house-settings.index')
            ->with('success', 'Cập nhật thông tin nhà trọ thành công.');
    }
}