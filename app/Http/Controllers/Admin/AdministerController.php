<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Post;
use App\Models\User;
use App\Models\Division;
use App\Models\ReceivePayment;
use App\Models\UpgradeRequest;
use App\Models\PaymentRequest;
use App\Models\Transaction;
use App\Models\MonthlyFeePayment;
use App\Models\TransactionCategory;
use App\Models\PackageUpgradePayment;
use App\Models\Product;
use App\Models\ProductPayment;
use App\Models\Book;
use App\Models\BookPurchase;
use App\Models\Order;
use Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AdministerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
	

    public function fetch()
    {
        $admin = Auth::guard('admin')->user();
    
        $divisionIds = [];
    
        $isDivisionScoped = $admin
            && $admin->role
            && in_array($admin->role->name, ['Divisional Admin', 'Hed Of Admin', 'Dex Report']);

        if ($isDivisionScoped) {
            $divisionIds = $admin->divisions->pluck('id')->toArray();
        }

        $newsQuery = Post::where('is_pending', 1)
            ->where('status', 'true');
    
        if ($isDivisionScoped && !empty($divisionIds)) {
            $newsQuery->whereHas('user', function ($q) use ($divisionIds) {
                $q->whereIn('division_id', $divisionIds);
            });
        }
    
        $news = (clone $newsQuery)
            ->latest()
            ->take(5)
            ->get();
    
        $news_count = (clone $newsQuery)->count();
    
        $applicantQuery = User::where('is_approve', 0)
            ->where('is_reader', 0);
    
        if ($isDivisionScoped && !empty($divisionIds)) {
            $applicantQuery->whereIn('division_id', $divisionIds);
        }
    
        $applicants = (clone $applicantQuery)
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'created_at']);
    
        $applicant_count = (clone $applicantQuery)->count();

        $upgrades = UpgradeRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
    
        $upgrade_count = $upgrades->count();

        $isAdmin = $admin->IsSuper() || ($admin->role && strtolower($admin->role->name) === 'admin');

        if ($isAdmin) {
            $payments = PaymentRequest::with('user')
                ->where('status', 0)
                ->latest()
                ->take(5)
                ->get();
        
            $payment_count = $payments->count();

            $packagePayments = PackageUpgradePayment::with('user')
                ->where('seen', 0)
                ->latest()
                ->take(5)
                ->get();

            $monthlyPayments = MonthlyFeePayment::with('user')
                ->where('seen', 0)
                ->latest()
                ->take(5)
                ->get();

            $productPayments = ProductPayment::with(['user', 'order.items.product'])
                ->where('seen', 0)
                ->latest()
                ->take(5)
                ->get();
        } else {
            $payments = collect();
            $payment_count = 0;
            $packagePayments = collect();
            $monthlyPayments = collect();
            $productPayments = collect();
        }

        if ($isAdmin) {
            $bookPayments = BookPurchase::with(['user', 'book'])
                ->where('seen', 0)
                ->latest()
                ->take(5)
                ->get();
        } else {
            $bookPayments = collect();
        }

        $hasProductOrdersPermission = $isAdmin || ($admin->role && $admin->sectionCheck('product_orders'));

        if ($hasProductOrdersPermission) {
            $pendingOrders = Order::with('user')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            $pending_order_count = Order::where('status', 'pending')->count();
        } else {
            $pendingOrders = collect();
            $pending_order_count = 0;
        }
    
        $responseData = [
     
            'news_count' => $news_count,
     
            'news' => $news->map(fn ($n) => [
                'title' => $n->title,
                'url'   => route(
                    'frontend.postBySubcategory.details',
                    [$n->category->slug, $n->slug]
                ),
                'time'  => date('Y-m-d h:i:s A', strtotime($n->created_at)),
            ]),
     
            'applicant_count' => $applicant_count,
     
            'applicants' => $applicants->map(fn ($a) => [
                'name' => $a->name,
                'url'  => route('admin.applicationform', [base64_encode($a->id), 1]),
                'time' => date('Y-m-d h:i:s A', strtotime($a->created_at)),
            ]),
     
            'upgrade_count' => $upgrade_count,
     
            'upgrades' => $upgrades->map(fn ($u) => [
                'name' => $u->user->name ?? 'Unknown',
                'package' => ucfirst($u->package),
                'amount' => $u->amount,
                'url' => route('admin.upgrade.index'),
                'time' => $u->created_at->diffForHumans(),
            ]),
     
            'payment_count' => $payment_count,
     
            'payments' => $payments->map(fn ($p) => [
                'name' => $p->user->name ?? 'Unknown',
                'amount' => $p->amount,
                'url' => route('admin.administator.paymentrequest'),
                'time' => $p->created_at->diffForHumans(),
            ]),
 
            'package_payment_count' => $isAdmin ? PackageUpgradePayment::where('seen', 0)->count() : 0,
 
            'package_payments' => $packagePayments->map(fn ($p) => [
                'name' => $p->user->name ?? 'Unknown',
                'package' => ucfirst($p->package),
                'amount' => $p->amount,
                'url' => route('admin.administator.packageUpgradePayments', ['seen' => 0]),
                'time' => $p->created_at->diffForHumans(),
            ]),
 
            'monthly_payment_count' => $isAdmin ? MonthlyFeePayment::where('seen', 0)->count() : 0,
 
            'monthly_payments' => $monthlyPayments->map(fn ($p) => [
                'name' => $p->user->name ?? 'Unknown',
                'amount' => $p->amount,
                'url' => route('admin.administator.monthlypayments', ['seen' => 0]),
                'time' => $p->created_at->diffForHumans(),
            ]),
 
            'product_payment_count' => $isAdmin ? ProductPayment::where('seen', 0)->count() : 0,
 
            'product_payments' => $productPayments->map(fn ($p) => [
                'name' => $p->user->name ?? 'Unknown',
                'items' => $p->order && $p->order->items->count()
                    ? $p->order->items->map(fn ($item) => ($item->product->name ?? 'Deleted product') . ' x ' . $item->quantity)->implode(', ')
                    : $p->product_name,
                'amount' => $p->amount,
                'url' => route('admin.administator.productPayments', ['seen' => 0]),
                'time' => $p->created_at->diffForHumans(),
            ]),
 
            'book_payment_count' => $isAdmin ? BookPurchase::where('seen', 0)->count() : 0,
 
            'book_payments' => $bookPayments->map(fn ($p) => [
                'name' => $p->user->name ?? 'Unknown',
                'book' => $p->book->title ?? 'Deleted book',
                'amount' => $p->amount ?? ($p->book->price ?? 0),
                'url' => route('admin.administator.bookPurchasePayments', ['seen' => 0]),
                'time' => $p->created_at->diffForHumans(),
            ]),
 
            'pending_order_count' => $pending_order_count,
 
            'pending_orders' => $pendingOrders->map(fn ($o) => [
                'name' => $o->user->name ?? 'Guest',
                'amount' => $o->total_amount,
                'url' => route('admin.orders.index', ['status' => 'pending']),
                'time' => $o->created_at->diffForHumans(),
            ]),
        ];
        \Log::info("Fetch notifications for Admin ID " . ($admin ? $admin->id : 'null') . ": " . json_encode([
            'pending_order_count' => $responseData['pending_order_count'],
            'pending_orders_count' => count($responseData['pending_orders'])
        ]));
        return response()->json($responseData);
    }



    public function datatables(){
        $datas = Admin::with('divisions','role')
                    ->orderBy('id','desc')
                    ->get();
        return Datatables::of($datas)
                            ->addColumn('action', function(Admin $data) {
                                if($data->id != 1){
                                    $delete = $data->id == 1 ? '':'<a href="javascript:;" data-href="'.route('admin.administator.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                                }else{
                                    $delete = '';
                                }
                                return '<div class="action-list"><a target="_blank" href="'.route('admin.idcard',[base64_encode($data->id),2]).'" ><i class="fas fa-credit-card"></i> ID Card</a><a data-href="'.route('admin.administator.edit',$data->id) .'" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>'.$delete.'</div>';
                            })
                            ->editColumn('role_id',function(Admin $data){
                                return $role = $data->role_id ? $data->role->name:'';
                            })
                             ->addColumn('divisions', function (Admin $data) {
                                return $data->divisions->pluck('name')->implode(', ');
                            })
                            ->rawColumns(['action','role_id'])
                            ->toJson();
    }
	
	public function paymentdatatables(Request $request){
			$q = PaymentRequest::with('verifier', 'user') // load relationships
			->select(
				'payment_requests.id AS pid',
				'payment_requests.request_date',
				'payment_requests.verify_date',
				'payment_requests.status',
				'payment_requests.request_amount',
				'payment_requests.account_details',
				
				'payment_requests.approve_amount',
				'payment_requests.updated_at',
				'user_id', // keep the foreign key
				'admin_id'
			)->orderBy('payment_requests.id', 'desc');
			
			if($request->user_id){
				$q->where('payment_requests.user_id', $request->user_id);
			}
			
			$datas=$q->get();
			

			
			
			return Datatables::of($datas)
                            ->addColumn('action', function(PaymentRequest $data) {
                               
								$edit = '<a data-href="' . route('admin.administator.paymentedit', $data->pid) . '" class="edit" data-toggle="modal" data-target="#modal1">
								   <i class="fas fa-edit"></i>Edit
								</a>';
                                
                                //$details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'" target="_blank"> <i class="fa fa-info-circle" aria-hidden="true"></i> View on Frontend</a>';
								$details='';
								$delete = '<a href="javascript:;" data-href="'.route('admin.administator.paymentdelete',$data->pid).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>';
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
                                 $roleBadge = '';
                                 if ($data->user) {
                                     if ($data->user->is_reader == 1) {
                                         $roleBadge = ' <span class="badge badge-info" style="font-size: 10px; padding: 3px 6px; font-weight: 600;">Reader</span>';
                                     } else {
                                         $roleBadge = ' <span class="badge badge-success" style="font-size: 10px; padding: 3px 6px; font-weight: 600;">Reporter</span>';
                                     }
                                 }
                                 return $name . $roleBadge;
                             })
							 ->editColumn('email',function(PaymentRequest $data){
                                $email = $data->user->email ?? 'N/A';
                                return $email;
                            })
							 ->editColumn('phone',function(PaymentRequest $data){
                                $phone =  $data->user->phone ?? 'N/A';
                                return $phone;
                            })

                            ->editColumn('admin_id',function(PaymentRequest $data){
                                $admin_id = $data->verifier->name ?? 'N/A';
                                return $admin_id;
                            })
                          
                            ->rawColumns(['status','name','email','phone','admin_id','action'])
                            ->toJson();
	
    }
	

	  public function paymentrequest()
    {
        $data = auth()->user();
		$balance=0;
		
		$users =User::selectRaw('id,name,phone')->get();
		return view('admin.administrator.paymentrequest',compact('data','balance','users'));

    }
	public function paymentcerate(){
      
		
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
		 
		$data['languages'] = [];
		$data['balance'] = $balance;
		
        return view('admin.administrator.paymentcerate',$data);
    }
	   public function paymentstore(Request $request)
    {
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
		$data  = new PaymentRequest();
		
		$input['user_id']= auth()->user()->id;
		$input['request_date']= date('Y-m-d');
		$data->fill($input)->save();
     
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
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
		 
        return view('admin.administrator.paymentedit',compact('data','balance'));
    }
	
    public function paymentupdate(Request $request, $id)
    {
        $data = \App\Models\PaymentRequest::with('user')->findOrFail($id);
    
        $rules = [
            'status' => 'required|in:1,2',
        ];
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    
        if ($data->status != 0) {
            return response()->json(['errors' => ['This request is already processed.']]);
        }
    
        $user = $data->user;
    
        if ($request->status == 2) {
            if ($user) {
                $user->increment('balance', $data->user_amount ?? $data->request_amount);
            }
            $data->approve_amount = 0;
        }
    
        if ($request->status == 1) {

            $data->approve_amount = $data->request_amount;
        
            $category = TransactionCategory::firstOrCreate([
                'name' => 'User Withdrawal'
            ]);
        
            Transaction::create([
                'type'             => 'expense',
                'title'            => 'User Withdrawal',
                'bearer'           => $user->name . ' (' . $user->phone . ')',
                'amount'           => $data->request_amount,
                'transaction_date' => now()->toDateString(),
                'category_id'      => $category->id,
                'note'             => 'Withdrawal approved. Request ID: ' . $data->id,
            ]);
        }
    
        $data->status      = $request->status;
        $data->verify_date = now();
        $data->admin_id    = auth()->id();
    
        $data->save();
    
        return response()->json('Payment request processed successfully');
    }
	
    public function paymentdelete($id)
    {
        $data = \App\Models\PaymentRequest::with('user')->findOrFail($id);
    
        // Refund only if still pending
        if ($data->status == 0) {
            $user = $data->user;
            if ($user) {
                $user->balance += ($data->user_amount ?? $data->request_amount);
                $user->save();
            }
        }
    
        $data->delete();
    
        return response()->json('Payment request deleted and amount refunded (if pending).');
    }
        
    public function receivedatatables(Request $request){
			$q = ReceivePayment::with('verifier', 'user') // load relationships
			->select(
				'payment_reporters.id AS pid',
				'payment_reporters.pdate',
				'payment_reporters.status',
				'payment_reporters.txn_id',
				'payment_reporters.amount',
				'payment_reporters.updated_at',
				'user_id', // keep the foreign key
				'admin_id'
			);
			
			if($request->user_id){
				$q->where('payment_reporters.user_id', $request->user_id);
			}
			
			$datas=$q->get();
			
			
			
			
			return Datatables::of($datas)
                            ->addColumn('action', function(ReceivePayment $data) {
                               
								$edit = '<a data-href="' . route('admin.administator.receiveedit', $data->pid) . '" class="edit" data-toggle="modal" data-target="#modal1">
								   <i class="fas fa-edit"></i>Edit
								</a>';
                                
                                //$details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'" target="_blank"> <i class="fa fa-info-circle" aria-hidden="true"></i> View on Frontend</a>';
								$details='';
								$delete = '<a href="javascript:;" data-href="'.route('admin.administator.receivedelete',$data->pid).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>';
								if($data->status>0){
									$delete="";
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

                            ->editColumn('admin_id',function(ReceivePayment $data){
                                $admin_id = $data->verifier->name ?? 'N/A';
                                return $admin_id;
                            })
                          
                            ->rawColumns(['status','name','email','phone','admin_id','action'])
                            ->toJson();
	
    }
	

	  public function receiverequest()
    {
	
        $data = auth()->user();
		$balance=0;
		
		$users =User::selectRaw('id,name,phone')->get();
		return view('admin.administrator.receiverequest',compact('data','balance','users'));

    }
    
    public function monthlypayments(Request $request)
    {
        $users = User::select('id', 'name')->get();
    
        $query = MonthlyFeePayment::with('user');

        $query->when($request->seen !== null && $request->seen !== '', function ($q) use ($request) {
            $q->where('seen', $request->seen);
        });

        $query->when($request->user_id, function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });

        $query->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->month, function ($q) use ($request) {
            $q->whereMonth('paid_at', $request->month);
        });

        $query->when($request->year, function ($q) use ($request) {
            $q->whereYear('paid_at', $request->year);
        });

        $query->when($request->search && $request->search != 'all', function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('transaction_id', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($u) use ($request) {
                        $u->where('name', 'like', "%{$request->search}%")
                          ->orWhere('email', 'like', "%{$request->search}%");
                    });
            });
        });
    
        $payments = $query->orderBy('paid_at', 'desc')->paginate(20);

        MonthlyFeePayment::where('seen', 0)->update(['seen' => 1]);

        $thisMonthTotal = MonthlyFeePayment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');
    
        return view('admin.administrator.monthlypayments', compact(
            'payments',
            'users',
            'thisMonthTotal'
        ));
    }
    
    public function packageUpgradePayments(Request $request)
    {
        $users = User::select('id', 'name')->get();
    
        $query = PackageUpgradePayment::with('user');

        $query->when($request->seen !== null && $request->seen !== '', function ($q) use ($request) {
            $q->where('seen', $request->seen);
        });
    
        $query->when($request->user_id, function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });

        $query->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        });
    
        $query->when($request->package, function ($q) use ($request) {
            $q->where('package', $request->package);
        });
    
        $query->when($request->month, function ($q) use ($request) {
            $q->whereMonth('paid_at', $request->month);
        });
    
        $query->when($request->year, function ($q) use ($request) {
            $q->whereYear('paid_at', $request->year);
        });
    
        $query->when($request->search && $request->search != 'all', function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('transaction_id', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($u) use ($request) {
                        $u->where('name', 'like', "%{$request->search}%")
                          ->orWhere('email', 'like', "%{$request->search}%");
                    });
            });
        });
    
        $payments = $query->orderBy('paid_at', 'desc')->paginate(20);

        PackageUpgradePayment::where('seen', 0)->update(['seen' => 1]);
    
        $thisMonthTotal = PackageUpgradePayment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');
    
        return view('admin.administrator.package-upgrade-payments', compact(
            'payments',
            'users',
            'thisMonthTotal'
        ));
    }

    public function productPayments(Request $request)
    {
        $users = User::select('id', 'name')->get();
        $products = Product::select('id', 'name')->orderBy('name')->get();

        $query = ProductPayment::with(['user', 'product', 'order.items.product']);

        $query->when($request->seen !== null && $request->seen !== '', function ($q) use ($request) {
            $q->where('seen', $request->seen);
        });

        $query->when($request->user_id, function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });

        $query->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->product_id, function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('product_id', $request->product_id)
                    ->orWhereHas('order.items', function ($item) use ($request) {
                        $item->where('product_id', $request->product_id);
                    });
            });
        });

        $query->when($request->month, function ($q) use ($request) {
            $q->whereMonth('paid_at', $request->month);
        });

        $query->when($request->year, function ($q) use ($request) {
            $q->whereYear('paid_at', $request->year);
        });

        $query->when($request->search && $request->search != 'all', function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('transaction_id', 'like', "%{$request->search}%")
                    ->orWhere('product_name', 'like', "%{$request->search}%")
                    ->orWhereHas('order.items.product', function ($product) use ($request) {
                        $product->where('name', 'like', "%{$request->search}%");
                    })
                    ->orWhereHas('user', function ($u) use ($request) {
                        $u->where('name', 'like', "%{$request->search}%")
                          ->orWhere('email', 'like', "%{$request->search}%");
                    });
            });
        });

        $payments = $query->orderBy('paid_at', 'desc')->paginate(20);

        ProductPayment::where('seen', 0)->update(['seen' => 1]);

        $thisMonthTotal = ProductPayment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        return view('admin.administrator.product-payments', compact(
            'payments',
            'users',
            'products',
            'thisMonthTotal'
        ));
    }

    public function bookPurchasePayments(Request $request)
    {
        $users = User::select('id', 'name')->get();
        $books = Book::select('id', 'title', 'price')->orderBy('title')->get();

        $query = BookPurchase::with(['user', 'book']);

        $query->when($request->seen !== null && $request->seen !== '', function ($q) use ($request) {
            $q->where('seen', $request->seen);
        });

        $query->when($request->user_id, function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        });

        $query->when($request->book_id, function ($q) use ($request) {
            $q->where('book_id', $request->book_id);
        });

        $query->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->month, function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->whereMonth('paid_at', $request->month)
                    ->orWhereMonth('created_at', $request->month);
            });
        });

        $query->when($request->year, function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->whereYear('paid_at', $request->year)
                    ->orWhereYear('created_at', $request->year);
            });
        });

        $query->when($request->search && $request->search != 'all', function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('transaction_id', 'like', "%{$request->search}%")
                    ->orWhere('phone_number', 'like', "%{$request->search}%")
                    ->orWhere('operator', 'like', "%{$request->search}%")
                    ->orWhereHas('book', function ($b) use ($request) {
                        $b->where('title', 'like', "%{$request->search}%");
                    })
                    ->orWhereHas('user', function ($u) use ($request) {
                        $u->where('name', 'like', "%{$request->search}%")
                          ->orWhere('email', 'like', "%{$request->search}%");
                    });
            });
        });

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        BookPurchase::where('seen', 0)->update(['seen' => 1]);

        $thisMonthApproved = BookPurchase::with('book')
            ->where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $thisMonthTotal = $thisMonthApproved->sum(function ($p) {
            return $p->amount > 0 ? $p->amount : ($p->book->price ?? 0);
        });

        return view('admin.administrator.book-purchase-payments', compact(
            'payments',
            'users',
            'books',
            'thisMonthTotal'
        ));
    }
    
	public function receivecerate(){

		$data['users']=User::selectRaw('id,name,phone')->get();
		$data['balance'] = 0;
        return view('admin.administrator.receivecerate',$data);
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
		 
        return view('admin.administrator.receiveedit',compact('data','balance','users'));
    }
	
	public function receiveupdate(Request $request,$id)
    {
		$data = ReceivePayment::find($id);
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
		
		if(isset($input['status']) && $input['payment_type']==9){
			
			$paymentRequest = PaymentRequest::where('check_id', $id)
				->first();
			if (isset($paymentRequest->id)) {
				
				$inputs['payment_type']= $input['payment_type'];
				$inputs['request_amount']= $input['amount'];
				$inputs['approve_amount']= $input['amount'];
				$inputs['request_date']= $input['pdate'];
				$inputs['verify_date']= $input['pdate'];
				$paymentRequest->update($inputs);
			}else{
				$data  = new PaymentRequest();
				$inputs['payment_type']= $input['payment_type'];
				$inputs['request_amount']= $input['amount'];
				$inputs['approve_amount']= $input['amount'];
				$inputs['request_date']= $input['pdate'];
				$inputs['verify_date']= $input['pdate'];
				$inputs['status']= 1;
				$inputs['user_id']= $input['user_id'];
				$inputs['admin_id']= auth()->user()->id;
				$inputs['account_details']= "Wallet";
				$inputs['check_id']= $id;
				$data->fill($inputs)->save();
			}
		}
		
		$input['admin_id']=auth()->user()->id;
        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
    }
	
	 public function receivedelete($id){
        $data = ReceivePayment::find($id);
        $data->delete();
        $msg = 'Data Successfully Deleted';
        return response()->json($msg);
    }
	
    public function index(){
        return view('admin.administrator.index');
    }

    public function create()
    {
        $roles = Role::orderBy('id','desc')->get();
        $divisions = Division::orderBy('name')->get();
    
        return view('admin.administrator.create', compact('roles','divisions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name'       => 'required',
            'email'      => 'required|unique:admins',
            'phone'      => 'required',
            'role_id'    => 'required',
            'password'   => 'required',
            'divisions' => 'nullable|array',
            'divisions.*' => 'integer',
            'photo'      => 'image|mimes:jpeg,png,jpg,gif,svg',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->getMessageBag()->toArray()]);
        }
    
        $admin = new Admin();
        $admin->name     = $request->name;
        $admin->email    = $request->email;
        $admin->phone    = $request->phone;
        $admin->role_id  = $request->role_id;
        $admin->details  = $request->details;
        $admin->password = bcrypt($request->password);
        $admin->display_password = $request->password;
        $admin->verify   = 1;
    
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/', $name);
            $admin->photo = $name;
        }
    
        $admin->save();
    
        $admin->divisions()->sync($request->divisions);
    
        return response()->json('Data Added Successfully');
    }


    public function edit($id)
    {
        $data = Admin::with('divisions')->findOrFail($id);
        $roles = Role::orderBy('id','desc')->get();
        $divisions = Division::orderBy('name')->get();
    
        return view('admin.administrator.edit', compact('data','roles','divisions'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'      => 'required',
            'email'     => 'required|unique:admins,email,'.$id,
            'phone'     => 'required',
            'role_id'   => 'required',
            'divisions' => 'nullable|array',
            'status'    => 'required|in:0,1',
            'photo'     => 'image|mimes:jpeg,png,jpg,gif,svg',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->getMessageBag()->toArray()]);
        }
    
        $data = Admin::findOrFail($id);
        $input = $request->all();
    
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/', $name);
            @unlink('assets/images/admin/'.$data->photo);
            $input['photo'] = $name;
        }
    
        $input['password'] = bcrypt($request->password);
        $input['display_password'] = $request->password;
    
        $data->update($input);
    
        $data->divisions()->sync($request->divisions);
    
        return response()->json('Data Updated Successfully');
    }

    public function delete($id){
        $data  = Admin::find($id);
        if($data->photo != null){
            @unlink('assets/images/admin/'.$data->photo);
        }
        $data->delete();
        $msg = 'Data Updated Sucessfully';
        return response()->json($msg);
    }
}
