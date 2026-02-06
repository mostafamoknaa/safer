<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GlobalSettingController extends Controller
{
    public function edit(): View
    {
        $settings = GlobalSetting::first();
        if (!$settings) {
            $settings = GlobalSetting::create([
                'hotel_commission' => 0,
                'apartment_commission' => 0,
                'car_hour_commission' => 0,
                'car_day_commission' => 0,
                'bus_commission' => 0,
                'activity_commission' => 0,
            ]);
        }
        return view('admin.settings.global', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hotel_commission' => 'required|numeric|min:0|max:100',
            'apartment_commission' => 'required|numeric|min:0|max:100',
            'car_hour_commission' => 'required|numeric|min:0|max:100',
            'car_day_commission' => 'required|numeric|min:0|max:100',
            'bus_commission' => 'required|numeric|min:0|max:100',
            'activity_commission' => 'required|numeric|min:0|max:100',
        ]);

        $settings = GlobalSetting::first();
        $settings->update($validated);

        return redirect()->route('admin.settings.global')->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
