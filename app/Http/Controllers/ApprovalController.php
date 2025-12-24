<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Corporate;
use App\Models\ApprovalReason;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function approval()
    {
        // Get pending drivers + approved acting drivers with subscription == 'no'
        $drivers = Driver::where(function($query) {
            $query->where('status', 'pending')
                  ->orWhere(function($q) {
                      $q->where('type', 'acting')
                        ->where('status', 'approved')
                        ->where('subscription', 'progress');
                  });
        })->get()->map(function ($list) {
            $list->loc = DB::table('location_active')->where('id', $list->location)->value('location');
            
            // Add subscription status for drivers
            if ($list->type === 'acting' && $list->subscription === 'progress') {
                $list->subscription_status = 'Paid';
            } else {
                $list->subscription_status = ($list->subscription === 'yes') ? 'Paid' : 'Pending';
            }
            
            // Get subscription details for drivers
            $subscription = Subscription::where('f_id', $list->id)
                ->where('type', $list->type)
                ->latest()
                ->first();
            
            $list->transaction_id = $subscription->t_id ?? null;
            $list->payment_screenshot = $subscription->payment_screenshot ?? null;
            
            return $list;
        });

        $corporates = Corporate::where('status', 'pending')->get()->map(function ($lt) {
            $lt->loc = DB::table('location_active')->where('id', $lt->location)->value('location');
            
            // Add subscription status for corporates
            $lt->subscription_status = ($lt->subscription === 'yes' || $lt->subscription === 'pending') ? 'Paid' : 'Pending';
            
            // Get subscription details for corporates
            $subscription = Subscription::where('f_id', $lt->id)
                ->where('type', 'corporate')
                ->latest()
                ->first();
            
            $lt->transaction_id = $subscription->t_id ?? null;
            $lt->payment_screenshot = $subscription->payment_screenshot ?? null;
            
            return $lt;
        });

        $concat = $drivers->concat($corporates)
            ->sortByDesc('created_at')
            ->values();

        return view('admin.approval.approval_list', compact('drivers', 'corporates', 'concat'));
    }

    public function handleApproval($type, $id, $action)
    {
        if (($type === 'acting') or ($type === 'permanent')) {
            $model = \App\Models\Driver::class;
        } else {
            $model = \App\Models\Corporate::class;
        }

        $record = $model::findOrFail($id);

        if ($action === 'approve') {
            $record->status = 'approved';
            $message = ucfirst($type) . ' approved successfully!';
        } elseif ($action === 'reject') {
            // For acting/permanent drivers, reject with reason is handled by handleApprovalWithReason
            // For corporate/owner, reject without reason
            if (in_array($type, ['acting', 'permanent'])) {
                return redirect()->back()->with('error', 'Please use the reject button with reason for drivers.');
            }
            $record->status = 'rejected';
            $message = ucfirst($type) . ' rejected successfully!';
        } else {
            return redirect()->back()->with('error', 'Invalid action.');
        }

        $record->save();

        return redirect()->back()->with('success', $message);
    }

    public function handleApprovalWithReason(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'action' => 'required|in:reject,pending',
            'reason' => 'required|string|min:10|max:500'
        ]);

        $type = $request->type;
        $id = $request->id;
        $action = $request->action;

        if (($type === 'acting') or ($type === 'permanent')) {
            $model = \App\Models\Driver::class;
        } else {
            $model = \App\Models\Corporate::class;
        }

        $record = $model::findOrFail($id);

        // Update status
        if ($action === 'reject') {
            $record->status = 'rejected';
        } else {
            $record->status = 'pending';
        }

        $record->save();

        // Store reason in approval_reasons table
        ApprovalReason::create([
            'user_id' => $id,
            'user_type' => $type,
            'action' => $action,
            'reason' => $request->reason,
            'admin_id' => Auth::id() // Store admin who performed the action
        ]);

        $message = ucfirst($type) . ' ' . $action . ' successfully with reason!';

        return redirect()->back()->with('success', $message);
    }

    public function handleSubscriptionApproval($type, $id, $action)
    {
        if (($type === 'acting') or ($type === 'permanent')) {
            $model = \App\Models\Driver::class;
        } else {
            $model = \App\Models\Corporate::class;
        }

        $record = $model::findOrFail($id);

        if ($action === 'approve') {
            $record->subscription = 'yes';
            
            // Update the subscription table with expiration date
            $subscription = Subscription::where('f_id', $id)
                ->where('type', $type)
                ->latest()
                ->first();
            
            if ($subscription) {
                // Get plan months from subscription and calculate dynamic expiration date
                $planMonths = (int) $subscription->plan;
                $subscription->exp_date = now()->addMonths($planMonths);
                $subscription->status = 'active';
                $subscription->paid_sts = 'approved';
                $subscription->save();
            }
            
            $message = 'Subscription approved successfully!';
        } else {
            $record->subscription = 'no';
            
            // Optional: Update subscription status to rejected
            $subscription = Subscription::where('f_id', $id)
                ->where('type', $type)
                ->latest()
                ->first();
            
            if ($subscription) {
                $subscription->status = 'rejected';
                $subscription->paid_sts = 'rejected';
                $subscription->save();
            }
            
            $message = 'Subscription rejected successfully!';
        }

        $record->save();

        return redirect()->back()->with('success', $message);
    }
}