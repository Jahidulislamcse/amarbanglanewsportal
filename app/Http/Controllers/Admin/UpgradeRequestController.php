<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UpgradeRequest;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\TeamGenerationCommission;
use Illuminate\Support\Facades\DB;

class UpgradeRequestController extends Controller
{

    public function index()
    {
        $pendingRequests = UpgradeRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $approvedRequests = UpgradeRequest::with('user')
            ->where('status', 'approved')
            ->latest()
            ->get();

        $rejectedRequests = UpgradeRequest::with('user')
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('admin.packages.requests', compact(
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests'
        ));
    }


    // public function approve($id)
    // {
    //     $req = UpgradeRequest::with('user')->findOrFail($id);

    //     if ($req->status !== 'pending') {
    //         return back()->with('error', 'Already processed.');
    //     }

    //     $req->status = 'approved';
    //     $req->save();

    //     // Upgrade user package
    //     if ($req->user) {
    //         $req->user->update([
    //             'reader_type' => $req->package
    //         ]);
    //     }

    //     return back()->with('success', 'Upgrade request approved successfully.');
    // }
    
    public function approve($id)
    {
        try {
    
            DB::transaction(function () use ($id) {
    
                $req = UpgradeRequest::with(['user.referrer'])
                    ->lockForUpdate()
                    ->findOrFail($id);
    
                if ($req->status !== 'pending') {
                    throw new \Exception('Already processed.');
                }
    
                $user = $req->user;
    
                if (!$user) {
                    throw new \Exception('User not found.');
                }
    
                $req->update([
                    'status' => 'approved'
                ]);
    
                $user->update([
                    'reader_type' => $req->package
                ]);
    
                $category = TransactionCategory::firstOrCreate([
                    'name' => 'Package Upgrade'
                ]);
    
                Transaction::create([
                    'type'             => 'income',
                    'title'            => 'Package Upgrade',
                    'bearer'           => $user->name . ' (' . $user->phone . ')',
                    'amount'           => $req->amount,
                    'transaction_date' => now()->toDateString(),
                    'category_id'      => $category->id,
                    'note'             => 'User upgraded to ' . $req->package,
                ]);
    
                // =========================
                // DIRECT REFERRAL COMMISSION
                // =========================
                $referrer = $user->referrer;
    
                if ($referrer) {
    
                    $commissionAmount = 0;
    
                    if ($referrer->reader_type === 'free') {
                        $commissionAmount = 5;
                    } elseif ($referrer->reader_type === 'executive') {
                        $commissionAmount = 10;
                    } elseif ($referrer->reader_type === 'vip') {
    
                        $fee = Fee::first();
    
                        if ($fee && $fee->referral_commission > 0) {
                            $commissionAmount = ($req->amount * $fee->referral_commission) / 100;
                        }
                    }
    
                    if ($commissionAmount > 0) {
    
                        $referrer->increment('referral_earning', $commissionAmount);
                        $referrer->increment('balance', $commissionAmount);
                    }
                }
    
                if ($req->package === 'vip') {
    
                    $fee = Fee::first();
    
                    if ($fee) {
    
                        $genRates = [
                            1 => $fee->team_gen_1_rate ?? 0,
                            2 => $fee->team_gen_2_rate ?? 0,
                            3 => $fee->team_gen_3_rate ?? 0,
                            4 => $fee->team_gen_4_rate ?? 0,
                            5 => $fee->team_gen_5_rate ?? 0,
                        ];
    
                        $currentUser = $user;
    
                        for ($generation = 1; $generation <= 5; $generation++) {

                            $upline = $currentUser->referrer;
                        
                            if (!$upline) {
                                break;
                            }
                        
                            $currentUser = $upline;
                        
                            // if ($upline->reader_type !== 'vip') {
                            //     continue;
                            // }
                        
                            $rate = $genRates[$generation];
                        
                            if ($rate <= 0) {
                                continue;
                            }
                        
                            $commission = ($req->amount * $rate) / 100;
                        
                            $created = TeamGenerationCommission::firstOrCreate(
                                [
                                    'receiver_user_id'   => $upline->id,
                                    'source_user_id'     => $user->id,
                                    'upgrade_request_id' => $req->id,
                                    'generation'         => $generation,
                                ],
                                [
                                    'package'          => $req->package,
                                    'upgrade_amount'   => $req->amount,
                                    'rate'             => $rate,
                                    'commission'       => $commission,
                                ]
                            );
                        
                            $upline->increment('team_gen_' . $generation, $commission);

                            if ($upline->reader_type === 'vip') {
                                $upline->increment('balance', $commission);
                            }
                        }
                                            }
                }
    
            });
    
            return back()->with('success', 'Upgrade approved successfully. Referral and team commissions processed.');
    
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject($id)
    {
        $req = UpgradeRequest::findOrFail($id);

        if ($req->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        $req->status = 'rejected';
        $req->save();

        return back()->with('success', 'Upgrade request rejected.');
    }


    public function destroy($id)
    {
        $request = UpgradeRequest::findOrFail($id);
        $request->delete();
    
        return back()->with('success', 'Request deleted successfully.');
    }
}