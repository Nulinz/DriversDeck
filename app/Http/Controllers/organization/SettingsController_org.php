<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Corporate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;



class SettingsController_org extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:corporate');
    // }

    public function settings()
    {
        $corporate = Auth::guard('corporate')->user();
        return view('organization.settings.settings', compact('corporate'));
    }

    public function update_basic_store(Request $request)
    {
        $activeTab = $request->input('activeTab')
            ?? $request->activeTab
            ?? '#Basic';

        // dd(Auth::guard('corporate')->id());
        $corporate = Corporate::find(Auth::guard('corporate')->id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:10|min:10',
            'mail' => 'required|email|max:255',
        ]);

        $corporate->update($validated);

        return redirect()->back()->with('activeTab', $request->input('activeTab'));
    }

    public function update_contact_store(Request $request)
    {
        $corporate = Corporate::find(Auth::guard('corporate')->id());


        $validator = Validator::make($request->all(), [
            'c_name' => 'required|string|max:255',
            'c_num' => 'required|string|max:10|min:10',
            'a_num' => 'nullable|digits_between:0,10',
            'c_mail' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            // 🛑 Print validation errors for debugging
            dd($validator->errors()->all());

            // Or redirect back normally:
            // return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $corporate->update([
                'c_name' => $request->input('c_name'),
                'c_num' => $request->input('c_num'),
                'a_num' => $request->input('a_num'),
                'c_mail' => $request->input('c_mail'),
            ]);
        } catch (\Exception $e) {
            Log::info($e);
            return redirect()->back()->withErrors(['Failed to update contact details']);
        }

        return redirect()->back()->with('activeTab', $request->input('activeTab'));
    }

    public function update_address_store(Request $request)
    {
        $corporate = Corporate::find(Auth::guard('corporate')->id());

        $validated = $request->validate([
            'ad_1' => 'required|string|max:255',
            // 'ad_2' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pin' => 'required|digits:6',
        ]);

        $corporate->update($validated);

        return back()->with('success', 'Address information updated successfully');
    }

    public function update_business_store(Request $request)
    {
        $corporate = Corporate::find(Auth::guard('corporate')->id());

        $validated = $request->validate([
            'pan' => 'required|string|size:10',
            'gst' => 'required|string|size:15',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $data = [
            'pan' => $validated['pan'],
            'gst' => $validated['gst'],
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($corporate->logo && File::exists(public_path($corporate->logo))) {
                File::delete(public_path($corporate->logo));
            }

            $image = $request->file('logo');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('image/corporate/logo'), $filename);
            $data['logo'] = 'public/image/corporate/logo/' . $filename;

            // $path = $request->file('logo')->store('corporate/logos', 'public');
            // $data['logo'] = $path;
        }

        $corporate->update($data);

        return back()->with('success', 'Business information updated successfully');
    }

    public function update_asset_store(Request $request)
    {
        $corporate = Corporate::find(Auth::guard('corporate')->id());

        $validated = $request->validate([
            'no_veh' => 'required|integer|min:1',
            'no_driver' => 'required|integer|min:1',
            'no_vac' => 'required|integer|min:0',
        ]);

        $corporate->update($validated);

        return back()->with('success', 'Asset information updated successfully');
    }
}
