<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Toastr;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AdminDivision;
use App\Models\Fee;
use Carbon\Carbon;
use App\Models\ReportCategory;
use App\Models\BookPurchase;
use App\Models\TopReporter;
use App\Models\UserOthersInfo;
use App\Models\Book;
use App\Models\MonthlyFeePayment;
use App\Models\Course;
use App\Models\Product;
use App\Models\Order;
use App\Models\TeamGenerationCommission;
use App\Models\UpgradeRequest;
use App\Models\PaymentRequest;
use App\Models\ReceivePayment;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	 public function datatables(Request $request)
    {
        $fees = Fee::first();
        $reporterRate = $fees ? $fees->reporter_view_rate : 0.1; 
    
        $q = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.report_type',
            DB::raw('COALESCE(SUM(CASE WHEN posts.status = "true" THEN posts.unique_count ELSE 0 END), 0) AS total_views'),
            DB::raw('COALESCE(SUM(CASE WHEN posts.status = "true" THEN posts.current_count ELSE 0 END), 0) AS current_views'),
            DB::raw('COALESCE(SUM(CASE WHEN posts.status = "true" THEN posts.unique_count ELSE 0 END) * ?, 0) AS total_commission'),
            DB::raw('COALESCE(SUM(CASE WHEN posts.status = "true" THEN posts.current_count ELSE 0 END) * ?, 0) AS current_commission')
        );

        $q->addBinding($reporterRate, 'select');
        $q->addBinding($reporterRate, 'select');
    
        $data = auth()->user();

        if (in_array($data->reporter_area, [1, 2, 3])) {
    
            if ($request->reporter_area == 1) {
                $q->where('users.division_id', $data->division_id);
    
            } elseif ($request->reporter_area == 2) {
                $q->whereIn('users.district_id', function ($sub) use ($data) {
                    $sub->select('id')->from('districts')
                        ->where('division_id', $data->division_id);
                });
    
            } elseif ($request->reporter_area == 3) {
                $q->whereIn('users.thana_id', function ($sub) use ($data) {
                    $sub->select('id')->from('upazilas')
                        ->where('district_id', $data->district_id);
                });
    
            } elseif ($request->reporter_area == 4) {
                $q->whereIn('users.union_id', function ($sub) use ($data) {
                    $sub->select('id')->from('unions')
                        ->where('upazilla_id', $data->thana_id);
                });
    
            } else {
                $q->where('users.division_id', $data->division_id);
            }
    
        } else {
            $q->where('users.division_id', $data->division_id);
        }

        $q->leftJoin('posts', 'users.id', '=', 'posts.user_id');
        $q->withCount('posts');
        $q->groupBy('users.id');
        $q->orderByDesc('total_views');
    
        $datas = $q->get();
    
        $reportcategories = DB::table('reportcategories')->pluck('title_en', 'id')->toArray();
    
        return DataTables::of($datas)
    
            ->editColumn('report_type', function ($data) use ($reportcategories) {
                $json_decode = json_decode($data->report_type, true);
                if (!empty($json_decode) && isset($json_decode[0])) {
                    return $reportcategories[$json_decode[0]] ?? '';
                }
                return '';
            })

            ->editColumn('total_commission', function ($data) use ($reporterRate) {
                return number_format($data->total_commission, 2) . 
                       ' <small class="text-muted">(' . $reporterRate . '/view)</small>';
            })
    
            ->editColumn('current_commission', function ($data) use ($reporterRate) {
                return number_format($data->current_commission, 2) . 
                       ' <small class="text-muted">(' . $reporterRate . '/view)</small>';
            })
    
            ->rawColumns(['report_type', 'total_commission', 'current_commission'])
            ->toJson();
    }

	
    public function paymentdatatables(Request $request){
			$datas = PaymentRequest::with('verifier', 'user')
            ->select(
                'payment_requests.id AS pid',
                'payment_requests.request_date',
                'payment_requests.verify_date',
                'payment_requests.status',
                'payment_requests.request_amount',
                'payment_requests.account_details',
                'payment_requests.approve_amount',
                'payment_requests.updated_at',
                'user_id',
                'admin_id'
            )
            ->where('payment_requests.user_id', auth()->id()) 
            ->get();
			
			
			return Datatables::of($datas)
                            ->addColumn('action', function(PaymentRequest $data) {
                               
								$edit = '<a data-href="' . route('user.profile.paymentedit', $data->pid) . '" class="edit" data-toggle="modal" data-target="#modal1">
								   <i class="fas fa-edit"></i>Edit
								</a>';
                                
                                //$details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'" target="_blank"> <i class="fa fa-info-circle" aria-hidden="true"></i> View on Frontend</a>';
								$details='';
								$delete = '<a href="javascript:;" data-href="'.route('user.profile.paymentdelete',$data->pid).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>';
								if($data->status>0){
									$edit=$delete="";
								}
                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list">'.$details.''.$edit.$delete.'</div></div>';
								
                               
                            })
							->addColumn('verify_date', function (PaymentRequest $data) {
								return $data->verify_date ? date('Y-m-d',strtotime($data->verify_date)) : '';
							})
                            ->editColumn('status',function(PaymentRequest $data){
								if($data->status==0){
									$status ='<span class="badge badge-warning">pending</span>';
								}else if($data->status==1){
									$status ='<span class="badge badge-success">approve</span>';
								}else{
									$status ='<span class="badge badge-info">reject</span>';
								}
                                return $status;
                            })
							 ->editColumn('name',function(PaymentRequest $data){
                                $name = $data->user->name ?? 'N/A';
                                return $name;
                            })
							 ->editColumn('email',function(PaymentRequest $data){
                                $email = $data->user->email ?? 'N/A';
                                return $email;
                            })
							 ->editColumn('phone',function(PaymentRequest $data){
                                $phone = $data->verifier->phone ?? 'N/A';
                                return $phone;
                            })

                            ->editColumn('admin_id',function(PaymentRequest $data){
                                $admin_id = $data->verifier->name ?? 'N/A';
                                return $admin_id;
                            })
                          
                            ->rawColumns(['status','name','email','phone','admin_id','action'])
                            ->toJson();
	
    }
	
	  public function reporter()
    {
        $data = auth()->user();
		return view('user.profile.index',compact('data'));

    }
	
	 public function paymentrequest()
    {
        $data = auth()->user();
    
        return view('user.profile.paymentrequest', compact('data'));
    }
    
    public function reader_paymentrequest()
    {
        $user = auth()->user();
    
        $payments = PaymentRequest::with('verifier')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    
        $balance = $user->balance;
    
        $fee = \App\Models\Fee::first(); 
    
        $withdrawMin = $fee->withdraw_min ?? 1000;
    
        $canWithdraw = $balance >= $withdrawMin;
    
        $maxWithdraw = $balance > 1 ? $balance - 1 : 0;
    
        return view('user.profile.reader_paymentrequest', compact(
            'user',
            'payments',
            'balance',
            'canWithdraw',
            'maxWithdraw',
            'withdrawMin',
            'fee'
        ));
    }
    
    public function paymentcerate()
    {
        $fees = Fee::first();
        $reporterRate = $fees ? $fees->reporter_view_rate : 0.1;
    
        $data['languages'] = [];
    
        $datas = Post::selectRaw('
            IFNULL(SUM(CASE WHEN posts.status = "true" THEN posts.unique_count * ? ELSE 0 END), 0) AS total_commission,
            COUNT(*) AS total_posts
        ')
        ->addBinding($reporterRate) 
        ->where('posts.user_id', auth()->id())
        ->groupBy('posts.user_id')
        ->first();
    
        $total_commission = $datas->total_commission ?? 0;
    
        $total_withdraw = PaymentRequest::whereUserId(auth()->id())
                            ->sum('approve_amount');
    
        $data['balance'] = $total_commission - $total_withdraw;
    
        return view('user.profile.paymentcerate', $data);
    }

    
    public function reader_paymentcerate()
    {
        return view('user.profile.reader_paymentcerate');
    }


    
	   public function paymentstore(Request $request)
    {
        $rules =
        [
            'payment_type' => 'required',
            'user_amount' => 'required|numeric|min:500',
            'request_amount' => 'required|numeric|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $user = auth()->user();
        $user_amount = (float) $request->user_amount;
        $request_amount = (float) $request->request_amount;

        if ($user->balance < $user_amount) {
            return response()->json(array('errors' => ['user_amount' => ['Insufficient balance.']]));
        }

        DB::beginTransaction();
        try {
            $user = User::where('id', $user->id)->lockForUpdate()->first();
            if ($user->balance < $user_amount) {
                throw new \Exception("Insufficient balance.");
            }

            $user->decrement('balance', $user_amount);

            $input = $request->all();
            $data  = new PaymentRequest();
            $input['user_id']= $user->id;
            $input['request_date']= date('Y-m-d');
            $input['user_amount'] = $user_amount;
            $input['request_amount'] = $request_amount;
            $data->fill($input)->save();

            DB::commit();
            $msg = 'Successfully submitted your payment request';
            return response()->json($msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(array('errors' => ['user_amount' => [$e->getMessage()]]));
        }
    }
    
    public function reader_paymentstore(Request $request)
    {
        $request->validate([
            'payment_type'    => 'required',
            'user_amount'     => 'required|numeric|min:500',
            'request_amount'  => 'required|numeric|min:1',
            'account_details' => 'required',
        ]);
    
        $user = User::find(Auth::id());
        if ($user->reader_type !== 'vip') {
            return redirect()->back()->with('error', 'পেমেন্ট রিকোয়েস্ট করার জন্য আপনার অ্যাকাউন্টটি অবশ্যই ভিআইপি (VIP) হতে হবে। অনুগ্রহ করে আপনার অ্যাকাউন্টটি ভিআইপি-তে আপগ্রেড করুন।');
        }
        $user_amount = (float) $request->user_amount;
        $request_amount = (float) $request->request_amount;
    
        if ($user->balance < $user_amount) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }
    
        DB::beginTransaction();
    
        try {
    
            $user = User::where('id', $user->id)->lockForUpdate()->first();
    
            if ($user->balance < $user_amount) {
                throw new \Exception("Balance changed. Try again.");
            }
    
            $user->balance -= $user_amount;
            $user->save();
    
            PaymentRequest::create([
                'user_id'         => $user->id,
                'payment_type'    => $request->payment_type,
                'user_amount'     => $user_amount,
                'request_amount'  => $request_amount,
                'account_details' => $request->account_details,
                'request_date'    => now()->toDateString(),
                'status'          => 0,
            ]);
    
            DB::commit();
    
            return redirect()->route('user.profile.reader_paymentrequest')
                ->with('success', 'Payment request submitted successfully');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function upgradeInstantly(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->reader_type === 'vip') {
            return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টটি ইতিমধ্যে ভিআইপি (VIP)।');
        }

        $fee = Fee::first();
        $vipPrice = $fee->vip_package_price ?? 0;

        if ($user->balance < $vipPrice) {
            return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালেন্স নেই। ভিআইপি আপগ্রেড ফি: ৳' . number_format($vipPrice, 2));
        }

        DB::beginTransaction();
        try {
            // Re-fetch and lock for update
            $user = User::where('id', $user->id)->lockForUpdate()->first();
            if ($user->balance < $vipPrice) {
                throw new \Exception("Insufficient balance.");
            }

            $user->balance -= $vipPrice;
            $user->reader_type = 'vip';
            $user->save();

            // Create Upgrade Request (marked as approved directly)
            $upgradeReq = UpgradeRequest::create([
                'user_id' => $user->id,
                'package' => 'vip',
                'amount'  => $vipPrice,
                'status'  => 'approved',
                'request_date' => now()->toDateString(),
            ]);

            // Create Package Upgrade transaction
            $category = \App\Models\TransactionCategory::firstOrCreate([
                'name' => 'Package Upgrade'
            ]);

            \App\Models\Transaction::create([
                'type'             => 'income',
                'title'            => 'Package Upgrade',
                'bearer'           => $user->name . ' (' . $user->phone . ')',
                'amount'           => $vipPrice,
                'transaction_date' => now()->toDateString(),
                'category_id'      => $category->id,
                'note'             => 'User upgraded to vip instantly using balance',
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
                    if ($fee && $fee->referral_commission > 0) {
                        $commissionAmount = ($vipPrice * $fee->referral_commission) / 100;
                    }
                }

                if ($commissionAmount > 0) {
                    $referrer->increment('referral_earning', $commissionAmount);
                    $referrer->increment('balance', $commissionAmount);
                }
            }

            // =========================
            // TEAM GENERATION COMMISSION
            // =========================
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

                    $rate = $genRates[$generation];
                    if ($rate <= 0) {
                        continue;
                    }

                    $commission = ($vipPrice * $rate) / 100;

                    TeamGenerationCommission::create([
                        'receiver_user_id'   => $upline->id,
                        'source_user_id'     => $user->id,
                        'upgrade_request_id' => $upgradeReq->id,
                        'generation'         => $generation,
                        'package'            => 'vip',
                        'upgrade_amount'     => $vipPrice,
                        'rate'               => $rate,
                        'commission'         => $commission,
                    ]);

                    $upline->increment('team_gen_' . $generation, $commission);
                    if ($upline->reader_type === 'vip') {
                        $upline->increment('balance', $commission);
                    }
                }
            }

            DB::commit();
            return redirect()->route('reader.paymentrequest')
                ->with('success', 'আপনার অ্যাকাউন্টটি সফলভাবে ভিআইপি (VIP) অ্যাকাউন্টে আপগ্রেড করা হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'আপগ্রেড ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }
    	
	  public function paymentedit($id){
        $data = PaymentRequest::find($id);
		
		$datas = Post::selectRaw('
			IFNULL(SUM(CASE WHEN posts.status = "true" THEN posts.unique_count * 0.01 ELSE 0 END), 0) AS total_commission,
			COUNT(*) AS total_posts
		')
		->where('posts.user_id', auth()->id())
		->groupBy('posts.user_id')
		->first();

		 $total_commission = $datas->total_commission ?? 0;
		 $total_withdraw = PaymentRequest::whereUserId(auth()->id())->sum('approve_amount');
		 $balance =$total_commission-$total_withdraw;
        return view('user.profile.paymentedit',compact('data','balance'));
    }
	
	public function paymentupdate(Request $request,$id)
    {
		 $data = PaymentRequest::find($id);
        $rules =
        [
            'payment_type' => 'required',
            'request_amount' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $input = $request->all();
		
			
        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
    }
	
	 public function paymentdelete($id){
        $data = PaymentRequest::findOrFail($id);
        if ($data->status == 0) {
            $user = User::find($data->user_id);
            if ($user) {
                $user->increment('balance', $data->user_amount ?? $data->request_amount);
            }
        }
        $data->delete();
        $msg = 'Data Successfully Deleted';
        return response()->json($msg);
    }
	
	 public function receivedatatables(Request $request){
			$datas = ReceivePayment::with('verifier', 'user') // load relationships
			->select(
				'payment_reporters.id AS pid',
				'payment_reporters.pdate',
				'payment_reporters.status',
				'payment_reporters.amount',
				'payment_reporters.txn_id',
				'payment_reporters.updated_at',
				'user_id', // keep the foreign key
				'admin_id'
			)
			->get();
			
			
			return Datatables::of($datas)
                            ->addColumn('action', function(ReceivePayment $data) {
                               
								$edit = '<a data-href="' . route('user.profile.receiveedit', $data->pid) . '" class="edit" data-toggle="modal" data-target="#modal1">
								   <i class="fas fa-edit"></i>Edit
								</a>';
                                
                                //$details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'" target="_blank"> <i class="fa fa-info-circle" aria-hidden="true"></i> View on Frontend</a>';
								$details='';
								$delete = '';
								if($data->status>0){
									$edit=$delete="";
								}
                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list">'.$details.''.$edit.$delete.'</div></div>';
								
                               
                            })
							->addColumn('verify_date', function (ReceivePayment $data) {
								return $data->verify_date ? date('Y-m-d',strtotime($data->verify_date)) : '';
							})
                            ->editColumn('status',function(ReceivePayment $data){
								if($data->status==0){
									$status ='<span class="badge badge-warning">unpaid</span>';
								}else if($data->status==1){
									$status ='<span class="badge badge-success">paid</span>';
								}else{
									$status ='<span class="badge badge-info">pending</span>';
								}
                                return $status;
                            })
							 ->editColumn('name',function(ReceivePayment $data){
                                $name = $data->user->name ?? 'N/A';
                                return $name;
                            })
							 ->editColumn('email',function(ReceivePayment $data){
                                $email = $data->user->email ?? 'N/A';
                                return $email;
                            })
							 ->editColumn('phone',function(ReceivePayment $data){
                                $phone = $data->verifier->phone ?? 'N/A';
                                return $phone;
                            })
                            ->rawColumns(['status','name','email','phone','action'])
                            ->toJson();
	
    }
	

    public function receiverequest()
    {
        $user = auth()->user();
    
        $payments = MonthlyFeePayment::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    
        $balance = 0;
    
        return view('user.profile.receiverequest', compact('payments', 'balance'));
    }
	public function receivecerate(){

		$data['users']=User::selectRaw('id,name,phone')->get();
		$data['balance'] = 0;
        return view('user.profile.receivecerate',$data);
    }
	   public function receivestore(Request $request)
    {
        $rules =
        [
            'user_id' => 'required',
			'payment_type' => 'required',
            'amount' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $input = $request->all();
		$data  = new ReceivePayment();
		
		$input['admin_id']= auth()->user()->id;
	
		$data->fill($input)->save();
     
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
    }
	
	  public function receiveedit($id){
        $data = ReceivePayment::find($id);
		$users=User::selectRaw('id,name,phone')->get();
		$balance=0;
		 
        return view('user.profile.receiveedit',compact('data','balance','users'));
    }
	
	public function receiveupdate(Request $request,$id)
    {
		 $data = ReceivePayment::find($id);
        $rules =
        [
            'payment_type' => 'required',
            'amount' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $input = $request->all();
		
		$input['admin_id']=auth()->user()->id;
        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
    }
	

    public function index(){
        
        $user = auth()->user();
        
        TopReporter::checkAndGenerateWeeklyBest();

        $latestWeekRange = TopReporter::whereNotNull('week')
            ->orderBy('id', 'desc')
            ->value('week');

        if ($latestWeekRange) {
            $topReporters = TopReporter::with('user')
                ->where('week', $latestWeekRange)
                ->orderBy('position')
                ->limit(3)
                ->get();

            $dates = explode(' - ', $latestWeekRange);
            $startOfLastMonth = isset($dates[0]) ? Carbon::parse($dates[0]) : Carbon::now()->subDays(7);
            $endOfLastMonth = isset($dates[1]) ? Carbon::parse($dates[1]) : Carbon::now();
        } else {
            $lastMonth = Carbon::now()->subMonth();
            $year = $lastMonth->year;
            $month = $lastMonth->month;
            
            $topReporters = TopReporter::with('user')
                ->where('year', $year)
                ->where('month', $month)
                ->orderBy('position')
                ->limit(3)
                ->get();

            $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
            $endOfLastMonth   = Carbon::now()->subMonth()->endOfMonth();
        }
    
        $topReporters = $topReporters->map(function ($item) {
            $user = $item->user;
            if ($user) {
                $item->id = $user->id;
                $item->name = $user->name;
                $item->phone = $user->phone;
                $item->report_type = $user->report_type;
                $item->reporter_area = $user->reporter_area;
                $item->district_id = $user->district_id;
                $item->thana_id = $user->thana_id;
                $item->union_id = $user->union_id;
                $item->photo = $user->photo;
            }
            return $item;
        })->filter(function($item) {
            return $item->user !== null;
        });

        $reportCategories = ReportCategory::pluck('title_bn', 'id')->toArray();
        $topReporters->transform(function($reporter) use ($reportCategories) {

            $reportTypeIds = json_decode($reporter->report_type, true); 
            $reportTypeId = $reportTypeIds[0] ?? null;
        
            $reporter->report_type_title = $reportTypeId 
                ? ($reportCategories[$reportTypeId] ?? '') 
                : '';

            $reporter->reporter_area_title = $reportTypeId 
                ? getReporterAreaName($reporter->language_id ?? 1, $reportTypeId, $reporter)
                : '';
        
            return $reporter;
        });


        
        $reporterDivisionIds = $user->division_id ? [$user->division_id] : [];

        $admins = \App\Models\Admin::where('role_id', 4)
        ->whereHas('divisions', function ($q) use ($reporterDivisionIds) {
            $q->whereIn('division_id', $reporterDivisionIds);
        })
        ->get(['id', 'name', 'phone']);

        $nextPayment = $user->next_payment_date;
        $isDeactivated = $nextPayment && now()->greaterThan($nextPayment);
    

        if ($isDeactivated) {
            return view('user.deactivated', [
                'admins' => $admins,
                'nextPayment' => $nextPayment,
            ]);
        }
        
        $data['products'] = Product::with(['firstImage', 'images'])->latest()
            ->get();

        $data['total_products'] = Product::count();
        $data['orders'] = Order::with(['items.product', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

    
        $data['admins'] = $admins;
    
        $data['posts'] = Post::whereUserId(auth()->id())->count();
        $data['articles'] = Post::wherePostType('article')->whereUserId(auth()->id())->count();
        $data['audios'] = Post::wherePostType('audio')->whereUserId(auth()->id())->count();
        $data['videos'] = Post::wherePostType('video')->whereUserId(auth()->id())->count();
        $data['personalites'] = Post::wherePostType('Personality Quiz')->whereUserId(auth()->id())->count();
        $data['trivias'] = Post::wherePostType('Trivia Quiz')->whereUserId(auth()->id())->count();
        $data['sorted'] = Post::wherePostType('Sorted List')->whereUserId(auth()->id())->count();
		
        $data['pending_posts'] = Post::whereUserId(auth()->id())->where('is_pending','=',1)->where('status','=','true')->count();
        $data['drafts'] = Post::whereUserId(auth()->id())->where('status','=','draft')->count();
		
        $data['schedules'] = Post::whereUserId(auth()->id())->where('status','=','true')->where('schedule_post_date', '>=', date('Y-m-d H:i:s'))->where('is_pending','=',0)->count();
		
		
	
        // $datas = Post::query()
        //     ->where('user_id', auth()->id())
        //     ->select('user_id')
        //     ->selectRaw('COALESCE(SUM(view_count), 0) as total_view_count')
        //     ->selectRaw('COUNT(*) as total_posts')
        //     ->groupBy('user_id')
        //     ->first();
        
//         $data['total_view_count'] = auth()->user()->views;
        		
	
// 		 $data['total_views'] = auth()->user()->views ?? 0;
// 		 $data['total_commission'] = $datas->total_commission ?? 0;
// 		 $data['total_withdraw'] = PaymentRequest::whereUserId(auth()->id())->sum('approve_amount');

        $totalViews = $user->views ?? 0;
        
        // Affiliate Link
        $data['affiliate_link'] = route('front.registration', [
            'ref' => $user->affilate_code
        ]);
        
        // Referral Commission
        $data['refferal_commission'] = $user->referral_earning ?? 0;
        
        // Team Income
        $data['team_income_total'] = TeamGenerationCommission::where(
            'receiver_user_id',
            $user->id
        )->sum('commission');
        
        $data['team_income_history'] = TeamGenerationCommission::with('source')
            ->where('receiver_user_id', $user->id)
            ->latest()
            ->take(50)
            ->get();
        
        // Referral Tree (5 Generation)
        $userId = $user->id;
        
        $reporterRate = DB::table('fees')
            ->value('reporter_view_rate');
        
        $data['total_views'] = $totalViews;
        
        $data['total_posts'] = Post::where('user_id', $user->id)->count();
        
        $data['total_commission'] = $totalViews * ($reporterRate ?? 0);
        $data['balance'] =  $user->balance ?? 0;
        
        $data['product_commission'] = \App\Models\ProductCommission::where('referrer_id', $user->id)->sum('commission_amount') ?? 0;
        
        $data['team_purchases'] = \App\Models\ProductCommission::with(['user', 'order.items.product'])
            ->where('referrer_id', $user->id)
            ->latest()
            ->get();
        
        $data['total_withdraw'] = PaymentRequest::whereUserId($user->id)
            ->sum('approve_amount');
		 
		$data['books'] = Book::oldest()->get();
    
        $data['purchasedBookIds'] = BookPurchase::where('user_id', $user->id)
            ->where('status', 'approved')
            ->pluck('book_id')
            ->toArray();
            
        // Prize Money Records
        $data['prize_money_records'] = \App\Models\ReporterPrizeMoney::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(50)
            ->get();
        
        $data['total_prize_money'] = \App\Models\ReporterPrizeMoney::where('user_id', $user->id)
            ->sum('amount');
            
        $data['courses'] = Course::with(['modules.exam.questions'])->get();
        
                $rows = \DB::select("
            WITH RECURSIVE referral_tree AS (
                SELECT 
                    id,
                    name,
                    phone,
                    reader_type,
                    referred_by,
                    photo,
                    district_id,
                    thana_id,
                    1 AS gen
                FROM users
                WHERE referred_by = ?
        
                UNION ALL
        
                SELECT 
                    u.id,
                    u.name,
                    u.phone,
                    u.reader_type,
                    u.referred_by,
                    u.photo,
                    u.district_id,
                    u.thana_id,
                    rt.gen + 1
                FROM users u
                INNER JOIN referral_tree rt ON u.referred_by = rt.id
                WHERE rt.gen < 5
            )
            SELECT rt.*, d.name AS district_name, up.name AS thana_name 
            FROM referral_tree rt
            LEFT JOIN districts d ON rt.district_id = d.id
            LEFT JOIN upazilas up ON rt.thana_id = up.id
            ORDER BY rt.gen, rt.id
        ", [$userId]);
        
        $genUsers = [1=>[],2=>[],3=>[],4=>[],5=>[]];
        
        foreach ($rows as $r) {
            $genUsers[$r->gen][] = $r;
        }
        
        $data['referralRows'] = $rows;
        
        $data['genUsers'] = $genUsers;
        
        // $data['purchasedCourseIds'] = BookPurchase::where('user_id', $user->id)
        //     ->where('status', 'approved')
        //     ->pluck('book_id')
        //     ->toArray();


        return view('user.dashboard', array_merge($data ?? [], [
            'topReporters' => $topReporters,
            'startOfLastMonth' => $startOfLastMonth,
            'endOfLastMonth' => $endOfLastMonth,
        ]));
    }
    
   public function reader_index()
    {
        $user = auth()->user();
        $fees = Fee::first();
        

        $genRates = [
            1 => $fees->team_gen_1_rate,
            2 => $fees->team_gen_2_rate,
            3 => $fees->team_gen_3_rate,
            4 => $fees->team_gen_4_rate,
            5 => $fees->team_gen_5_rate,
        ];
        
        $readerRates = [
            'free'      => $fees->free_reader_rate,
            'executive' => $fees->executive_reader_rate,
            'vip'       => $fees->vip_reader_rate,
        ];
    
        $views = $user->views ?? 0;
        $data['earning'] = $user->balance ?? 0;
        $data['view_income'] = $user->view_income ?? 0;
        $readerType = $user->reader_type ?? 'free';
    
        // $readerRates = [
        //     'free'      => $fees->free_reader_rate ?? 0,
        //     'executive' => $fees->executive_reader_rate ?? 0,
        //     'vip'       => $fees->vip_reader_rate ?? 0,
        // ];

        $viewRate = $readerRates[$readerType];
        $data['view_commission'] = $views * $viewRate;
    
        $data['total_views'] = $views;

        $data['refferal_commission'] = $user->referral_earning ?? 0;

        $team_bonus =
            ($user->team_gen_1 ?? 0) +
            ($user->team_gen_2 ?? 0) +
            ($user->team_gen_3 ?? 0) +
            ($user->team_gen_4 ?? 0) +
            ($user->team_gen_5 ?? 0);
    
        $data['team_bonus'] = $team_bonus;

        // $data['total_commission'] =
        //     $data['view_commission'] +
        //     $data['refferal_commission'] +
        //     $team_bonus;
            
        $data['total_commission'] =
            $data['earning'];

        $data['total_withdraw'] = PaymentRequest::whereUserId($user->id)
            ->sum('approve_amount');

        $data['total_balance'] =
            $data['total_commission'] + $data['total_withdraw'];

        $data['affiliate_link'] = route('register_reader', [
            'ref' => $user->affilate_code
        ]);

        $data['total_posts'] = Post::where('user_id', $user->id)
            ->where('status', true)
            ->count();
            
         $data['upgradeRequest'] = UpgradeRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();
    
        $data['offers'] = [
            'reader_type' => $readerType,
            'team_bonus' => [
                'gen_1' => $user->team_gen_1 ?? 0,
                'gen_2' => $user->team_gen_2 ?? 0,
                'gen_3' => $user->team_gen_3 ?? 0,
                'gen_4' => $user->team_gen_4 ?? 0,
                'gen_5' => $user->team_gen_5 ?? 0,
            ],
            'promotion_eligible' => $user->promotion_eligible,
        ];
        
        $userId = $user->id;
        
        $rows = \DB::select("
            WITH RECURSIVE referral_tree AS (
                SELECT 
                    id,
                    name,
                    phone,
                    reader_type,
                    referred_by,
                    photo,
                    district_id,
                    thana_id,
                    1 AS gen
                FROM users
                WHERE referred_by = ?
        
                UNION ALL
        
                SELECT 
                    u.id,
                    u.name,
                    u.phone,
                    u.reader_type,
                    u.referred_by,
                    u.photo,
                    u.district_id,
                    u.thana_id,
                    rt.gen + 1
                FROM users u
                INNER JOIN referral_tree rt ON u.referred_by = rt.id
                WHERE rt.gen < 5
            )
            SELECT rt.*, d.name AS district_name, up.name AS thana_name 
            FROM referral_tree rt
            LEFT JOIN districts d ON rt.district_id = d.id
            LEFT JOIN upazilas up ON rt.thana_id = up.id
            ORDER BY rt.gen, rt.id
        ", [$userId]);
        
        $genUsers = [1=>[],2=>[],3=>[],4=>[],5=>[]];
        
        foreach ($rows as $r) {
            $genUsers[$r->gen][] = $r;
        }
        
        $data['referralRows'] = $rows;
        
        $data['genUsers'] = $genUsers;
    
        $timezone = 'Asia/Dhaka';
        $todayDate = now($timezone)->toDateString();
        
        $data['todayQuizPosts'] = Post::with(['category:id,slug,title', 'quiz'])
            ->whereHas('quiz')
            ->whereDate(
                \DB::raw('COALESCE(schedule_post_date, created_at)'),
                $todayDate
            )
            ->where('status', 1)
            ->where('is_pending', 0)
            ->latest()
            ->get();
        
        $todayQuizIds = $data['todayQuizPosts']
            ->pluck('quiz.id')
            ->filter()
            ->values();
        
        $data['todayAnsweredQuizIds'] = \App\Models\PostQuizAnswer::whereIn('post_quiz_id', $todayQuizIds)
            ->where('user_id', $user->id)
            ->pluck('post_quiz_id')
            ->toArray();
        
        $data['myQuizWinningHistory'] = \App\Models\PostQuizWinner::with([
                'answer',
                'quiz.post.category'
            ])
            ->whereHas('answer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderByDesc('id')
            ->take(20)
            ->get();
            
        
        $quiz_prize = \App\Models\UserPrizeMoney::where('user_id', $user->id)
            ->sum('amount');
        
        $quiz_added_money = $user->daily_quiz_money ?? 0;
        
        $data['quiz_prize_total'] = $quiz_prize + $quiz_added_money;
        
        $data['quiz_winning_count'] = \App\Models\PostQuizWinner::whereHas('answer', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        
        $data['recentQuizWinners'] = \App\Models\PostQuizWinner::with([
                'answer',
                'quiz.post.category'
            ])
            ->orderByDesc('id')
            ->take(20)
            ->get();
            
        $data['team_income_total'] = TeamGenerationCommission::where('receiver_user_id', $user->id)
        ->sum('commission');
        
        $data['team_income_history'] = TeamGenerationCommission::with('source')
        ->where('receiver_user_id', $user->id)
        ->latest()
        ->take(50)
        ->get();
        
         
    
        return view('user.reader_dashboard', $data);
    }


    public function claim_offer(Request $request)
    {
        $user = auth()->user();
    
        $offer = $request->input('offer'); // e.g., 'upgrade_vip', 'promotion_bonus'
    
        switch($offer) {
            case 'upgrade_executive':
                if($user->reader_type == 'free') {
                    $user->reader_type = 'executive';
                    $user->save();
                    return back()->with('success', 'You have upgraded to Executive Reader!');
                }
                break;
    
            case 'upgrade_vip':
                if($user->reader_type != 'vip') {
                    $user->reader_type = 'vip';
                    // Example: instant bonus on upgrade
                    $user->referral_earning += 120; // adjust according to your logic
                    $user->save();
                    return back()->with('success', 'You have upgraded to VIP Reader and received bonus!');
                }
                break;
    
            case 'claim_promotion':
                if($user->promotion_eligible) {
                    $user->promotion_eligible = false;
                    // Add promo reward (cash/tour points)
                    $user->referral_earning += 5000; // example for 100 VIP team
                    $user->save();
                    return back()->with('success', 'Promotional incentive claimed!');
                }
                break;
    
            default:
                return back()->with('error', 'Invalid offer.');
        }
    
        return back()->with('error', 'You are not eligible for this offer.');
    }
    
    public function upgradeRequest(Request $request)
    {
        $user = auth()->user();
    
        $package = $request->package;
    
        $fee = Fee::first(); 
    
        if (!$fee) {
            return back()->with('error', 'Fee settings not found.');
        }
    
        $packagePrices = [
            'executive' => $fee->executive_package_price,
            'vip'       => $fee->vip_package_price,
        ];
    
        if (!isset($packagePrices[$package])) {
            return back()->with('error', 'Invalid package selected.');
        }
    
        $amount = $packagePrices[$package];
    
        UpgradeRequest::create([
            'user_id' => $user->id,
            'package' => $package,
            'amount' => $amount,
            'phone_number' => $request->phone_number,
            'operator' => $request->operator,
        ]);
    
        return back()->with('success', 'Upgrade request sent for admin approval.');
    }

	
    public function profile()
    {
        $data = auth()->user();
        return view('user.profile.edit',compact('data'));
    }
    
    public function reader_profile()
    {
        $data = auth()->user();
        return view('user.profile.reader_edit',compact('data'));
    }
	
    public function pincode(Request $request)
    {
        $rules =
        [
            'pincode' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $input = $request->all();
	    $count = User::where('affilate_code',trim($input['pincode']))->count();
		if($count>0){
			 $data = auth()->user();
			 Session::put('session_pin', $data->id);
			 $data=route('user.idcard');
			 return response()->json($data);
		}else{
			$msg[0] = 'Your pin code not valid.please contact with your admin';
			return response()->json(array('errors' => $msg));
		}
    }
	
	
    public function idcard($id = null)
    {
        $session_pin = Session::get('session_pin');
    
        if (auth()->user()->is_approve == 1 && auth()->user()->id == $session_pin) {
    
            $reportcategories = DB::table('reportcategories')->pluck('title_bn', 'id');
            $data = auth()->user();
            $type = 1;

            $areaName = '';
    
            if (!empty($data->report_type)) {
    
                $types = json_decode($data->report_type, true);
    
                if (is_array($types) && isset($types[0])) {
    
                    $typeId = (int)$types[0];
    
                    if (in_array($typeId, [29, 31, 37]) && !empty($data->division_id)) {
                        $areaName = DB::table('divisions')
                            ->where('id', $data->division_id)
                            ->value('bn_name');
                    }
                    elseif (in_array($typeId, [30, 36]) && !empty($data->district_id)) {
                        $areaName = DB::table('districts')
                            ->where('id', $data->district_id)
                            ->value('bn_name');
                    }
                    elseif (in_array($typeId, [32, 35]) && !empty($data->thana_id)) {
                        $areaName = DB::table('upazilas')
                            ->where('id', $data->thana_id)
                            ->value('bn_name');
                        }
                    elseif ($typeId == 34 && !empty($data->union_id)) {
                        $areaName = DB::table('unions')
                            ->where('id', $data->union_id)
                            ->value('bn_name');
                    }
                }
            }
    
            return view('user.profile.idcard', compact('data', 'reportcategories', 'type', 'areaName'));
    
        } else {
            $type = "";
            $data = [];
            return view('user.profile.pincard', compact('data', 'type'));
        }
    }

	
	 public function applicationform($id=null)
    {
		
		$reportcategories=DB::table('reportcategories')->pluck('title_en','id');
		if($id){
			$id=base64_decode($id);
			$data = User::select('users.name','users.father_name','users.mother_name','users.address','users.nid_no','users.created_at','users.photo','users.id','users.phone', 'users.report_type')->where('id',$id)->first();
		}else{
			$data = auth()->user();
		}
		$type="";
        return view('user.profile.applicationform',compact('data','reportcategories','type'));
    }

    public function profileupdate(Request $request)
    {
        //--- Validation Section
        $data = auth()->user();
        $rules =
        [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'required|unique:users,email,'.$data->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $input = $request->all();
		
        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            @unlink('assets/images/admin/'.$data->photo);
            $input['photo'] = $name;
        }
		
		if ($file = $request->file('nid'))
        {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            @unlink('assets/images/admin/'.$data->nid);
            $input['nid'] = $name;
        }
		if ($file = $request->file('signature'))
		{
			$insertedId = $input['id'];
			$customExtension = 'png';
			$name = $insertedId .  '.' . $customExtension;
			$file->move('assets/images/admin/',$name);
		}
			
        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
    }

    public function passwordreset()
    {
        $data = auth()->user();
        return view('user.profile.password',compact('data'));
    }
    
    public function reader_passwordreset()
    {
        $data = auth()->user();
        return view('user.profile.password_reader',compact('data'));
    }

    public function changepass(Request $request)
    {
        $admin = auth()->user();
    
        if (!Hash::check($request->cpass, $admin->password)) {
            return back()->withErrors(['Current password does not match.']);
        }
    
        if ($request->newpass != $request->renewpass) {
            return back()->withErrors(['Confirm password does not match.']);
        }
    
        $admin->password = Hash::make($request->newpass);
        $admin->save();
        
        UserOthersInfo::updateOrCreate(
            ['user_id' => $admin->id],
            ['password' => $request->newpass]
        );
            
        return back()->with('success', 'Successfully changed your password');
    }

    public function logout(){
        auth()->logout();
        return redirect('/');
    }
}
