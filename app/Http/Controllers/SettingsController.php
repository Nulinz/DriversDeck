<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User_active;
use App\Models\District; 

class SettingsController extends Controller
{
public function settings()
{
    $users = User_active::where('status', 'active')->get();
    // Fetch locations with district names
$locations = DB::table('location_active')
    ->leftJoin('district', 'location_active.district', '=', 'district.id')
    ->select('location_active.*', 'district.district as district_name')
    ->get();

    $districts = District::where('status', 'active')->get(); // Fetch active districts

    return view('admin.settings.settings', compact('users', 'locations', 'districts'));
}

public function storeLocation(Request $request)
{
    $request->validate([
        'location' => 'required|string',
        'cord' => 'required|string',
        'district_id' => 'required|exists:district,id', // validate district id
    ]);

    DB::table('location_active')->insert([
        'location'   => $request->location,
        'cord'       => $request->cord,
        'district'   => $request->district_id, // store district id
        'status'     => 'active',
        'c_by'       => auth('web')->user()->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.settings.location')
                     ->with('success', 'Location added successfully');
}

public function deactivateLocation($location)
{
    DB::table('location_active')
        ->where('location', $location)
        ->update([
            'status' => 'inactive',
            'updated_at' => now()
        ]);

    return redirect()->back()->with('success', 'Location deactivated successfully.');
}

public function activateLocation($location)
{
    DB::table('location_active')
        ->where('location', $location)
        ->update([
            'status' => 'active',
            'updated_at' => now()
        ]);

    return redirect()->back()->with('success', 'Location activated successfully.');
}

  
  public function toggleStatus(Request $request, $id)
  {
    $user = User_active::findOrFail($id);
    $user->status = $request->status;
    $user->save();

    return response()->json(['success' => true, 'status' => $user->status]);
  }

  public function addUser()
  {
    $users = User_active::where('status', 'active')->get();
    return view('admin.settings.add_user', compact('users'));
  }

  public function storeUser(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'designation' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:user_active,mail',
      'contact_number' => 'required|string|max:15|unique:user_active,contact',
      'password' => 'required|string|min:6',
      'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $imagePath = null;
    if ($request->hasFile('profile_image')) {
      $image = $request->file('profile_image');
      $filename = time() . '_' . $image->getClientOriginalName();
      $image->move(public_path('image/user/profileimage'), $filename);
      $imagePath = 'image/user/profileimage/' . $filename;
    }

    User_active::create([
      'name' => $request->name,
      'designation' => $request->designation,
      'mail' => $request->email,
      'contact' => $request->contact_number,
      'password' => bcrypt($request->password),
      'img' => $imagePath,
      'status' => 'active',
      'c_by' => auth('web')->user()->id ?? 1,
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    // dd(User_active::all());


    $users = User_active::where('status', 'active')->get();

    // return redirect()->route('admin.settings.add_user')->with('success', 'User added successfully');
    return redirect()->route('admin.settings.settings')->with('success', 'User added successfully')->with(compact('users'));
  }


  public function addPermission()
  {
    return view('admin.settings.add_permission');
  }
  public function editPermission()
  {
    return view('admin.settings.edit_permission');
  }


  public function showLocationTab()
  {
    $locations = DB::table('location_active')->get();
    //  dd($locations); 
    return view('admin.settings.settings', compact('locations'));
  }





}
