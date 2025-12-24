<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    // public function request(){
    //     return view('admin.wallet.request');
    // }

   public function request()
{
    $withdrawals = DB::table('bank_withdraw')
        ->where('status', 'pending')
        ->get();

    return view('admin.wallet.request', compact('withdrawals'));
}


public function handleApproval(Request $request)
{
    $request->validate([
        'id' => 'required|integer',
        'action' => 'required|in:approve,reject',
    ]);

    $status = $request->action;

    DB::table('bank_withdraw')
        ->where('id', $request->id)
        ->update([
            'status' => $status,
            'updated_at' => now()
        ]);

    return redirect()->back()->with('success', 'Withdrawal request ' . $status . ' successfully!');
}

}
