<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use App\Models\Post;
use App\Models\ReportCategory;
use Datatables;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\SmsService;
use App\Models\Fee;
use App\Models\TopReporter;
use App\Models\UpgradeRequest;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables(Request $request)
    {
        $fees = Fee::first();
        $reporterRate = $fees ? $fees->reporter_view_rate : 0.1; 
        
        $startOfWeek = Carbon::now()->subDays(7)->format('Y-m-d');
        $endOfWeek = Carbon::now()->format('Y-m-d');
        $weekRange = "{$startOfWeek} - {$endOfWeek}";
        $currentWeeklyWinnerIds = TopReporter::where('week', $weekRange)
            ->pluck('user_id')
            ->toArray();

        $selectColumns = [
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.next_payment_date',
            'users.is_approve',
            'users.created_at',
            'users.division_id',
            'users.district_id',
            'users.thana_id',
            'users.report_type',
            'users.photo',
            'divisions.name as division_name',
            'districts.name as district_name',
            
            'users.has_experience',
            'users.experience_organization',
            'users.experience_designation',
            'users.experience',
            'users.views as total_views',
            'users.balance as total_commission',
        ];

        $selectColumns[] = DB::raw('(SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id) AS total_posts_count');
        $selectColumns[] = DB::raw('(SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id AND posts.created_at >= "' . Carbon::now()->subDays(7)->startOfDay()->toDateTimeString() . '") AS last_7_days_posts_count');
        $selectColumns[] = DB::raw('(SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id AND posts.is_pending = 1) AS pending_posts_count');
        $selectColumns[] = DB::raw('(SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id AND posts.is_pending = 2) AS rejected_posts_count');

        $q = User::select($selectColumns);
        
        $q->where('users.is_reader', 0);
        
        $admin = Auth::guard('admin')->user();

        if ($admin && (
            $admin->role->name === 'Divisional Admin' ||
            $admin->role->name === 'Hed Of Admin'
        )) {
            $divisionIds = $admin->divisions->pluck('id')->toArray();
            $q->whereIn('users.division_id', $divisionIds);
        }
                
        if ($request->status_filter === 'rejected') {
            $q->where('users.is_approve', 2);
        } elseif ($request->status_filter === 'no_purchase') {
            $q->where('users.is_approve', '!=', 2);
            $q->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('orders')
                    ->whereColumn('orders.user_id', 'users.id');
            });
        } elseif ($request->status_filter === 'no_purchase_with_posts') {
            $q->where('users.is_approve', '!=', 2);
            $q->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('orders')
                    ->whereColumn('orders.user_id', 'users.id');
            });
            $q->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('posts')
                    ->whereColumn('posts.user_id', 'users.id');
            });
            $q->where(function ($query) {
                $query->whereNull('users.next_payment_date')
                      ->orWhere('users.next_payment_date', '>=', \Carbon\Carbon::now()->subMonths(2));
            });
        } elseif ($request->status_filter === 'no_posts') {
            $q->where('users.is_approve', '!=', 2);
            $q->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('posts')
                    ->whereColumn('posts.user_id', 'users.id')
                    ->where('posts.is_pending', 0);
            });
        } elseif ($request->user_status === 'pending') {
            $q->where('users.is_approve', 0);
        } elseif ($request->user_status === 'approved') {
            $q->where('users.is_approve', 1);
        } elseif($request->pending_status){
            $q->where('users.is_approve', 0);
        } else {
            $q->where(function ($query) {
                $query->whereNull('users.is_approve')
                    ->orWhere('users.is_approve', '!=', 2);
            });
        }
        
        if($request->user_id){
            $q->where('users.id', $request->user_id);
        }
        if($request->reporter_area){
            $q->where('users.reporter_area', $request->reporter_area);
        }
        if($request->report_type){
            $q->whereJsonContains('users.report_type', $request->report_type);
        }
        if ($request->division_id) {
            $q->where('users.division_id', $request->division_id);
        }
        if ($request->district_id) {
            $q->where('users.district_id', $request->district_id);
        }
        if ($request->thana_id) {
            $q->where('users.thana_id', $request->thana_id);
        }

        if ($request->date_filter === 'last_3_days') {
            $q->where('users.created_at', '>=', Carbon::now()->subDays(3)->startOfDay());
        } elseif ($request->date_filter === 'last_7_days') {
            $q->where('users.created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
        } elseif ($request->date_filter === 'last_month') {
            $q->where('users.created_at', '>=', Carbon::now()->subMonth()->startOfDay());
        }
        
        $q->leftJoin('divisions', 'users.division_id', '=', 'divisions.id');
        $q->leftJoin('districts', 'users.district_id', '=', 'districts.id');
        $q->leftJoin('upazilas', 'users.thana_id', '=', 'upazilas.id');



        if ($request->status_filter === 'no_purchase') {
            $q->orderBy('users.created_at', 'asc');
        } elseif ($request->sort_by === 'location') {
            $q->orderBy('divisions.name')
                ->orderBy('districts.name')
                ->orderBy('upazilas.name')
                ->orderByDesc('users.created_at');
        } else {
            $q->orderByDesc('users.created_at')
                ->orderBy('divisions.name')
                ->orderBy('districts.name')
                ->orderBy('upazilas.name');
        }
        $reportcategories = DB::table('reportcategories')->pluck('title_en', 'id')->toArray();
        
        return DataTables::of($q)
        ->editColumn('name', function($data) use ($currentWeeklyWinnerIds) {
            $nameHtml = e($data->name);
            if (in_array($data->id, $currentWeeklyWinnerIds)) {
                $nameHtml .= ' <span class="badge badge-success rounded-pill" style="font-size:10px;padding:3px 6px;"><i class="fas fa-star text-warning"></i> Best</span>';
            }
            return $nameHtml;
        })
        ->editColumn('created_at', function($data) {
            return $data->created_at ? Carbon::parse($data->created_at)->format('d M Y') : '';
        })
        ->editColumn('next_payment_date', function($data) {
            if($data->next_payment_date){
                $date = \Carbon\Carbon::parse($data->next_payment_date);
                $formatted = $date->format('d M Y');
        
                // Check if date is past today
                if($date->isPast()){
                    return '<span style="background-color:#f8d7da;color:#721c24;padding:3px 8px;border-radius:4px;">' . $formatted . '</span>';
                } else {
                    return '<span style="background-color:#d4edda;color:#155724;padding:3px 8px;border-radius:4px;">' . $formatted . '</span>';
                }
            }
            return '<span class="text-muted">-</span>';
        })



        ->addColumn('photo', function ($data) {
            
                if ($data->photo) {
                    $url = asset('assets/images/admin/' . $data->photo);
                } else {
                    $url = asset('assets/images/default-user.png'); // fallback image
                }
            
                return '<img src="'.$url.'" 
                            style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:1px solid #ddd;">';
            })
            ->addColumn('action', function ($data) {
                $delete = '<a href="javascript:;" data-href="' . route('admin.staff.delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                              <i class="fas fa-trash-alt"></i>
                           </a>';
    
                $count_detail = '<a target="_blank" href="' . route('admin.idcard', [base64_encode($data->id), 1]) . '">
                                    <i class="fas fa-credit-card"></i> ID Card
                                 </a>
                                 <a target="_blank" href="' . route('admin.applicationform', [base64_encode($data->id), 1]) . '">
                                    <i class="fas fa-credit-card"></i> Form
                                 <a target="_blank" href="' . route('admin.staff.user_income_detail', $data->id) . '">
                                    <i class="fas fa-search"></i> View Income
                                 </a>';
                 $nextPaymentBtn = '<button class="btn btn-success btn-sm rounded-pill update-next-payment" data-id="' . $data->id . '">
                             Update Next Payment
                        </button>';
     
                return '<div class="action-list">' . $count_detail . 
                       '<a data-href="' . route('admin.staff.edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1">
                           <i class="fas fa-edit"></i>Edit
                        </a>' . $delete . $nextPaymentBtn . '</div>';
            })
            ->editColumn('report_type', function ($data) use ($reportcategories) {
                $json_decode = json_decode($data->report_type, true);
                $name = '';
    
                if (!empty($json_decode) && isset($json_decode[0])) {
                    $name = $reportcategories[$json_decode[0]] ?? '';
                }
    
                return $name;
            })
            ->editColumn('total_commission', function ($data) {
                return number_format($data->total_commission, 2);
            })
            ->addColumn('orders', function ($data) {
                return '<button class="btn btn-outline-primary btn-sm rounded-pill view-orders" data-id="' . $data->id . '" data-name="' . e($data->name) . '">
                            <i class="fas fa-shopping-basket"></i> Orders
                        </button>';
            })
            ->rawColumns(['name', 'photo','report_type', 'action', 'total_commission', 'orders', 'next_payment_date'])
            ->toJson();
    }

    
    public function reader_datatables(Request $request)
    {
        // Get the dynamic fee rate for readers
        $fees = Fee::first();
        $readerRate = (float) ($fees->reader_view_rate ?? 0.01);
        
        $q = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.reader_type',
            'users.photo',
            'users.is_ban',
            'users.referral_earning',
            'users.daily_quiz_money',
            'users.created_at',
            DB::raw('users.views AS total_views'),
            'users.view_income AS view_commission',
            DB::raw('users.balance AS total_commission')
        );
        
        $q->where('users.is_reader', 1);
        
        if($request->pending_status){
            $q->where('users.is_approve', 0);
        }
        
        if($request->user_id){
            $q->where('users.id', $request->user_id);
        }
        if($request->report_type){
            $q->whereJsonContains('users.report_type', $request->report_type);
        }

        // Apply Manual Status Filter
        if ($request->status_filter === 'active') {
            $q->where('users.is_ban', 0);
        } elseif ($request->status_filter === 'disabled') {
            $q->where('users.is_ban', 1);
        }
        

        $q->withCount('posts');
        $q->withSum('prizeMoneys', 'amount');

        // Apply Manual Sorting
        if ($request->sort_filter === 'views_desc') {
            $q->orderByDesc('total_views');
        } elseif ($request->sort_filter === 'balance_desc') {
            $q->orderByDesc('total_commission');
        } elseif ($request->sort_filter === 'banned_first') {
            $q->orderByDesc('users.is_ban');
        } else {
            $q->orderByDesc('total_commission'); // Default sort
        }
        $reportcategories = DB::table('reportcategories')->pluck('title_en', 'id')->toArray();
        
        return DataTables::of($q)
            ->addColumn('photo', function ($data) {
            
                if ($data->photo) {
                    $url = asset('assets/images/admin/' . $data->photo);
                } else {
                    $url = asset('assets/images/default-user.png'); // fallback image
                }
            
                return '<img src="'.$url.'" 
                            style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:1px solid #ddd;">';
            })

            ->editColumn('name', function ($data) {
                return $data->name;
            })
            ->editColumn('is_ban', function ($data) {
                if ($data->is_ban == 1) {
                    return '<span class="badge badge-danger">Disabled</span>';
                }
                return '<span class="badge badge-success">Active</span>';
            })
            ->addColumn('action', function ($data) {
                $delete = '<a href="javascript:;" data-href="' . route('admin.staff.delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                              <i class="fas fa-trash-alt"></i>
                           </a>';
                                 
                $banText = $data->is_ban == 1 ? 'Enable' : 'Disable';
                $banIcon = $data->is_ban == 1 ? 'fa-unlock' : 'fa-ban';
                $banColor = $data->is_ban == 1 ? 'color: #28a745;' : 'color: #dc3545;';
                
                $confirmMsg = $data->is_ban == 1 
                    ? 'Are you sure you want to enable this user?' 
                    : 'Are you sure you want to disable this user?';

                $banBtn = '<a href="' . route('admin.staff.ban', $data->id) . '" class="ml-2" style="' . $banColor . '" title="' . $banText . '" onclick="return confirm(\'' . e($confirmMsg) . '\')">
                              <i class="fas ' . $banIcon . '"></i> ' . $banText . '
                           </a>';
                 
                // Direct query is 100% robust and matches dashboard exactly
                $quizWinnerMoney = \App\Models\UserPrizeMoney::where('user_id', $data->id)->sum('amount');
                            
                $totalWithdraw = \App\Models\PaymentRequest::where('user_id', $data->id)->sum('approve_amount');

                $detailsBtn = '<a href="javascript:;" class="view-details ml-2" style="color: #007bff;" ' .
                              'data-name="' . e($data->name) . '" ' .
                              'data-created="' . ($data->created_at ? $data->created_at->format('d M Y, h:i A') : 'N/A') . '" ' .
                              'data-referral="' . number_format($data->referral_earning, 2) . '" ' .
                              'data-views-income="' . number_format($data->view_commission, 2) . '" ' .
                              'data-quiz-money="' . number_format($data->daily_quiz_money, 2) . '" ' .
                              'data-quiz-winner-money="' . number_format($quizWinnerMoney, 2) . '" ' .
                              'data-withdraw="' . number_format($totalWithdraw, 2) . '" ' .
                              'data-balance="' . number_format($data->total_commission, 2) . '" ' .
                              'data-ban="' . $data->is_ban . '" title="Details">' .
                              '<i class="fas fa-eye"></i> Details' .
                              '</a>';
    
                return '<div class="action-list">' . $detailsBtn . $banBtn . $delete . '</div>';
            })
            ->editColumn('report_type', function ($data) use ($reportcategories) {
                $json_decode = json_decode($data->report_type, true);
                $name = '';
    
                if (!empty($json_decode) && isset($json_decode[0])) {
                    $name = $reportcategories[$json_decode[0]] ?? '';
                }
    
                return $name;
            })
            ->editColumn('reader_type', function ($data) {
                $type = strtolower($data->reader_type ?? 'free');
                if ($type === 'vip') {
                    return '<span class="badge px-2 py-1 text-white font-weight-bold" style="background-color: #ff9900; box-shadow: 0 2px 4px rgba(255,153,0,0.2);">VIP</span>';
                } elseif ($type === 'executive') {
                    return '<span class="badge badge-primary px-2 py-1 font-weight-bold" style="box-shadow: 0 2px 4px rgba(0,123,255,0.2);">Executive</span>';
                } else {
                    return '<span class="badge px-2 py-1" style="background-color: #e4e6eb; color: #4b4f56; font-weight: 500;">Free</span>';
                }
            })
            ->editColumn('total_views', function ($data) {
                return number_format($data->total_views);
            })
            ->editColumn('view_commission', function ($data) use ($readerRate) {
                return number_format($data->view_commission, 2) . 
                       ' <small class="text-muted">(' . $readerRate . '/view)</small>';
            })
            ->editColumn('referral_earning', function ($data) {
                return number_format($data->referral_earning, 2);
            })
            ->editColumn('total_commission', function ($data) {
                // This already includes referral_earning + view_commission
                return '<span class="font-weight-bold text-success">' . 
                       number_format($data->total_commission, 2) . '</span>';
            })
            ->addColumn('breakdown', function ($data) use ($readerRate) {
                $viewCommission = $data->view_commission;
                return '<small class="text-muted">' . 
                       number_format($data->referral_earning, 2) . ' (referral) + ' . 
                       number_format($viewCommission, 2) . ' (views) = ' . 
                       number_format($data->total_commission, 2) . '</small>';
            })
            ->rawColumns(['name', 'photo', 'report_type', 'reader_type', 'action', 'total_commission', 'breakdown', 'is_ban'])
            ->toJson();
    }
    
	
	public function userPostdatatables(Request $request){
            /*
            $reporterRate = DB::table('fees')->value('reporter_view_rate') ?? 0.01;
			$datas = Post::where('user_id', $request->user_id)
			->selectRaw('title, description, created_at, view_count, (view_count * ?) AS total_commission', [$reporterRate])
			->orderByDesc('created_at')
			->get();
			return Datatables::of($datas)
			->editColumn('created_at', function($row) {
				return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '';
			})
			->editColumn('description', function($row) {
				return strip_tags($row->description);
			})
			->editColumn('total_commission', function($row) {
				return '৳' . number_format($row->total_commission, 2);
			})
			->toJson();
            */

			$datas = Post::where('user_id', $request->user_id)
			->selectRaw('title,description,created_at,view_count,COALESCE(SUM(CASE WHEN view_count > 0 THEN view_count ELSE 0 END) * 0.01, 0) AS total_commission')
			->orderByDesc('created_at')
			->get();
			return Datatables::of($datas)
			->editColumn('created_at', function($row) {
				return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '';
			})
			->editColumn('description', function($row) {
				return strip_tags($row->description);
			})
			->toJson();
    }
	
	
    public function index(Request $request)
    {
        $pending_status = $request->has('pending_status') ? 1 : 0;
    
        $divisions = \App\Models\Division::orderBy('name')->get(['id', 'name']);
        $districts = \App\Models\District::orderBy('name')->get(['id', 'name', 'division_id']);
        $thanas = \App\Models\Thana::orderBy('name')->get(['id', 'name', 'district_id']);
    
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth   = Carbon::now()->subMonth()->endOfMonth();
    
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;
    
        $topReporters = TopReporter::with('user')
            ->where('year', $year)
            ->where('month', $month)
            ->orderBy('position')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                $user = $item->user;
    
                $item->id = $user->id;
                $item->name = $user->name;
                $item->phone = $user->phone;
                $item->report_type = $user->report_type;
                $item->reporter_area = $user->reporter_area;
                $item->district_id = $user->district_id;
                $item->thana_id = $user->thana_id;
                $item->union_id = $user->union_id;
                $item->photo = $user->photo;
                $item->total_views = $item->total_views;
    
                return $item;
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


        return view('admin.staff.index', compact(
            'pending_status', 
            'topReporters', 
            'startOfLastMonth', 
            'endOfLastMonth',
            'divisions',
            'districts',
            'thanas'
        ));
    }

    public function userOrders($id)
    {
        $orders = \App\Models\Order::with('items.product')
            ->where('user_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($orders);
    }
    
    public function generateTopReporters(Request $request)
    {
        $month = $request->month;
    
        if (!$month) {
            return response()->json([
                'success' => false,
                'message' => 'Month is required'
            ]);
        }
    
        [$year, $monthNumber] = explode('-', $month);
    
        $startDate = Carbon::create($year, $monthNumber, 1)->startOfMonth();
        $endDate = Carbon::create($year, $monthNumber, 1)->endOfMonth();
    
        $topReporters = User::select(
                'users.id',
                DB::raw('SUM(CASE WHEN posts.status = "true" THEN posts.view_count ELSE 0 END) as total_views')
            )
            ->join('posts', 'posts.user_id', '=', 'users.id')
            ->where('users.is_reader', 0)
            ->whereBetween('posts.created_at', [$startDate, $endDate])
            ->groupBy('users.id')
            ->orderByDesc('total_views')
            ->limit(3)
            ->get();
    
        // Remove previous generated records
        TopReporter::where('year', $year)
            ->where('month', $monthNumber)
            ->delete();
    
        foreach ($topReporters as $index => $reporter) {
    
            TopReporter::create([
                'user_id' => $reporter->id,
                'position' => $index + 1,
                'total_views' => $reporter->total_views,
                'year' => $year,
                'month' => $monthNumber
            ]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Top reporters generated successfully.'
        ]);
    }

    
    public function topReportersMonth(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
    
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
    
       $topReporters = User::select(
            'users.id',
            'users.name',
            'users.phone',
            'users.report_type',
            'users.reporter_area',
            'users.district_id',
            'users.thana_id',
            'users.union_id',
            'users.photo',
            DB::raw('SUM(CASE WHEN posts.status = "true" THEN posts.view_count ELSE 0 END) as total_views')
        )
        ->join('posts', 'posts.user_id', '=', 'users.id')
        ->where('users.is_reader', 0)
        ->whereBetween('posts.created_at', [$startOfMonth, $endOfMonth])
        ->groupBy('users.id', 'users.name', 'users.phone', 'users.report_type', 'users.reporter_area', 'users.district_id', 'users.thana_id', 'users.union_id', 'users.photo')
        ->orderByDesc('total_views')
        ->limit(3)
        ->get();

    
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



    
        $tbody = '';
        foreach ($topReporters as $key => $reporter) {
            $position = $key == 0 ? '1st' : ($key == 1 ? '2nd' : '3rd');
            $tbody .= "<tr>
                <td>{$position}</td>
                <td><img src='".($reporter->photo ? asset('assets/images/admin/' . $reporter->photo) : asset('assets/images/default_user.png'))."' width='50' height='50' style='border-radius:50%;object-fit:cover'></td>
                <td>{$reporter->name}</td>
                <td>{$reporter->phone}</td>
                <td>{$reporter->report_type_title}</td>
                <td>{$reporter->reporter_area_title}</td>
                <td>{$reporter->total_views}</td>
            </tr>";
        }
    
        return response()->json([
            'tbody' => $tbody,
            'date_range' => $startOfMonth->format('d M Y') . ' - ' . $endOfMonth->format('d M Y')
        ]);
    }




    
    public function reader_index(Request $request)
    {
        $pending_status = $request->has('pending_status') ? 1 : 0;
    
        $users = User::select('id', 'name', 'phone')
            ->where('is_reader', 1)
            ->orderByDesc('views')
            ->get();
    
        // Upgrade Requests
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
    
        return view('admin.staff.reader_index', compact(
            'users',
            'pending_status',
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests'
        ));
    }


	
	  public function user_income_detail($user_id=null){
           /*
		   $user_informations = User::where('id',$user_id)->first();
           if (!$user_informations) {
               abort(404);
           }
		
           $reporterRate = DB::table('fees')->value('reporter_view_rate') ?? 0.01;
           $view_income = ($user_informations->views ?? 0) * $reporterRate;

           // Team Purchases Commission (from product purchases)
           $product_commission = \App\Models\ProductCommission::where('referrer_id', $user_id)->sum('commission_amount') ?? 0;
           $team_purchases = \App\Models\ProductCommission::with(['user', 'order.items.product'])
               ->where('referrer_id', $user_id)
               ->latest()
               ->get();

           // Referral Tree (5 Generations)
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
           ", [$user_id]);
        
           $genUsers = [1=>[],2=>[],3=>[],4=>[],5=>[]];
           foreach ($rows as $r) {
               $genUsers[$r->gen][] = $r;
           }

           return view('admin.staff.user_income_detail', compact(
               'user_informations',
               'view_income',
               'reporterRate',
               'product_commission',
               'team_purchases',
               'genUsers'
           ));
           */

		   $user_informations = User::select(
				'users.id',
				'users.name',
				'users.email',
				'users.phone'
			)
			->where('id',$user_id)
			->first();
		
           return view('admin.staff.user_income_detail',compact('user_informations'));
    }
    public function create(){
        $divisions = \App\Models\Division::orderBy('name')->get();
        return view('admin.staff.create', compact('divisions'));
    }
    public function store(Request $request){
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:4',
            'father_name' => 'required',
            'mother_name' => 'required',
            'dob' => 'required',
            'blood' => 'required',
            'address' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'thana_id' => 'required',
            'union_id' => 'required',
            'eduaction' => 'required',
            'education_year' => 'required',
            'nid_no' => 'required',
            'report_type' => 'required',
            'reporter_area' => 'required',
            'has_experience' => 'required|in:0,1',
            'experience_organization' => 'required_if:has_experience,1|max:255',
            'experience_designation' => 'required_if:has_experience,1|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nid' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nid_back' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->getMessageBag()->toArray()]);
        }
        $data  = new User();
        $input = $request->all();

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            $input['photo'] = $name;
        }

        if($request->hasFile('nid')){
            $file = $request->file('nid');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            $input['nid'] = $name;
        }

        if($request->hasFile('nid_back')){
            $file = $request->file('nid_back');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            $input['nid_back'] = $name;
        }

        $input['password'] = bcrypt($request->password);
        $input['token'] = md5(time().$request->name.$request->email);
        $input['report_type'] = json_encode($request->report_type);
        
        $input['verified'] = 1;
        $input['email_verified'] = 'Yes';
        $input['is_approve'] = 1; // auto approve since created by admin
        $input['approved_date'] = now();
        $input['payment_status'] = 'upcoming';
        $input['next_payment_date'] = now()->addDays(30);

        // Generate affiliate code
        do {
            $code = mt_rand(10000000, 99999999);
        } while(User::where('affilate_code', $code)->exists());
        $input['affilate_code'] = $code;

        $data->fill($input)->save();

        if ($file = $request->file('signature')) {
            $insertedId = $data->id;
            $customExtension = 'png';
            $name = $insertedId .  '.' . $customExtension;
            $file->move('assets/images/admin/',$name);
        }

        \App\Models\UserOthersInfo::create([
            'user_id' => $data->id,
            'password' => $request->password
        ]);

        // Send welcome SMS
        $userPhone = preg_replace('/[^0-9]/', '', $request->phone); 
        if (substr($userPhone, 0, 1) == "0") {
            $userPhone = "88" . $userPhone;
        }

        $message = "Welcome, {$request->name}! Your registration on আমার বাংলা 24 has been successfully completed by Admin. Use Pin {$code} for ID Card.";
        $smsService = new \App\Services\SmsService();
        $smsService->send($userPhone, $message);

        $msg = 'Data Added Sucessfully';
        return response()->json($msg);
    }
    
    public function edit($id)
    {
        $data = \App\Models\User::findOrFail($id);
        
        if (empty($data->permanent_division_id)) {
            $data->permanent_division_id = $data->division_id;
            $data->permanent_district_id = $data->district_id;
            $data->permanent_thana_id = $data->thana_id;
            $data->permanent_union_id = $data->union_id;
        }
    
        $divisions = \App\Models\Division::all();
    
        $districts = \App\Models\District::where('division_id', $data->division_id)->get();
    
        $thanas = \App\Models\Thana::where('district_id', $data->district_id)->get();
    
        $unions = \App\Models\Unions::where('upazilla_id', $data->thana_id)->get();
    
        $permanentDistricts = $data->permanent_division_id ? \App\Models\District::where('division_id', $data->permanent_division_id)->get() : collect();
    
        $permanentThanas = $data->permanent_district_id ? \App\Models\Thana::where('district_id', $data->permanent_district_id)->get() : collect();
    
        $permanentUnions = $data->permanent_thana_id ? \App\Models\Unions::where('upazilla_id', $data->permanent_thana_id)->get() : collect();
    
        $allDistricts = \App\Models\District::select('id','name','division_id')->get();

        $reporterQuery = \App\Models\Post::where('user_id', $id);

        $reporterPostCount = (clone $reporterQuery)->count();
        $reporterViews = $data->views;

        $approvedCount = (clone $reporterQuery)->where('is_pending', 0)->count();
        $pendingCount = (clone $reporterQuery)->where('is_pending', 1)->count();
        $rejectedCount = (clone $reporterQuery)->where('is_pending', 2)->count();
        
        $todayStart = \Carbon\Carbon::today();
        $threeDaysAgo = \Carbon\Carbon::now()->subDays(3);
        $sevenDaysAgo = \Carbon\Carbon::now()->subDays(7);

        $todayCount = (clone $reporterQuery)->where('created_at', '>=', $todayStart)->count();
        $threeDaysCount = (clone $reporterQuery)->where('created_at', '>=', $threeDaysAgo)->count();
        $sevenDaysCount = (clone $reporterQuery)->where('created_at', '>=', $sevenDaysAgo)->count();

        return view('admin.staff.edit', compact(
            'data',
            'divisions',
            'districts',
            'thanas',
            'unions',
            'permanentDistricts',
            'permanentThanas',
            'permanentUnions',
            'allDistricts',
            'approvedCount',
            'pendingCount',
            'rejectedCount',
            'todayCount',
            'threeDaysCount',
            'sevenDaysCount',
            'reporterPostCount',
            'reporterViews'
        ));
    }
        
        public function update(Request $request,$id,  SmsService $sms){
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'phone' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'nid' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'nid_back' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'signature' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'permanent_division_id' => 'required',
            'permanent_district_id' => 'required',
            'permanent_thana_id' => 'required',
            'permanent_union_id' => 'nullable',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['errors'=>$validator->getMessageBag()->toArray()]);
        }
        $data  = User::find($id);
        $previousApprove = $data->is_approve;
         
        $input = $request->all();
        if (empty($input['permanent_division_id'])) {
            $input['permanent_division_id'] = $data->division_id;
            $input['permanent_district_id'] = $data->district_id;
            $input['permanent_thana_id'] = $data->thana_id;
            $input['permanent_union_id'] = $data->union_id;
        }
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            @unlink('assets/images/admin/'.$data->photo);
            $input['photo'] = $name;
        }
		
		 if($request->hasFile('nid')){
            $file = $request->file('nid');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            @unlink('assets/images/admin/'.$data->nid);
            $input['nid'] = $name;
        }

		 if($request->hasFile('nid_back')){
            $file = $request->file('nid_back');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/admin/',$name);
            @unlink('assets/images/admin/'.$data->nid_back);
            $input['nid_back'] = $name;
        }
		
		if ($file = $request->file('signature'))
		{
			$insertedId = $id;
			$customExtension = 'png';
			$name = $insertedId .  '.' . $customExtension;
			$file->move('assets/images/admin/',$name);
		}
		
		$input['report_type'] =json_encode($request->report_type);
		
        if ($request->filled('bypass_duration')) {
            $hours = (int) $request->bypass_duration;
            $input['package_bypass_until'] = now()->addHours($hours);
        } else {
            if ($request->has('bypass_duration') || $request->package1_purchased == 1) {
                $input['package_bypass_until'] = null;
            }
        }

        $data->update($input);
        
        if ($previousApprove == 0 && isset($request->is_approve) && $request->is_approve == 1) {
            $data->approved_date = now();
            $data->payment_status = 'upcoming';
            if (!$data->next_payment_date) {
                $data->next_payment_date = now()->addDays(30);
            }$data->next_payment_date = now()->addDays(30);
            
            $data->save(); 
                    
            $userPhone = preg_replace('/[^0-9]/', '', $request->phone); 
            if (substr($userPhone, 0, 1) == "0") {
                $userPhone = "88" . $userPhone;
            }
    
            $message = "Congratulations {$request->name}, your account on আমার বাংলা-24 has been verified successfully! Use Pin " . implode(',', (array)$request->affilate_code) . " for ID Card";
    
            $smsService = new \App\Services\SmsService();
            $smsService->send($userPhone, $message);
        }
        
        

        $msg = 'Data Updated Sucessfully';
        return response()->json($msg);
    }
    
    public function updateNextPaymentDate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);
    
        $user = User::find($request->id);

        $currentDate = $user->next_payment_date ?? now();
        $user->next_payment_date = Carbon::parse($currentDate)->addMonth();
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Next Payment Date updated successfully',
            'next_payment_date' => $user->next_payment_date->format('Y-m-d'),
        ]);
    }

    public function getWeeklyBestCandidates(Request $request)
    {
        TopReporter::checkAndGenerateWeeklyBest();
        
        $latestWeekRange = TopReporter::whereNotNull('week')
            ->orderBy('id', 'desc')
            ->value('week');
        
        if ($latestWeekRange) {
            $topReporters = TopReporter::with('user')
                ->where('week', $latestWeekRange)
                ->orderBy('position')
                ->get();
        } else {
            $topReporters = collect();
        }

        $reportCategories = ReportCategory::pluck('title_bn', 'id')->toArray();
        
        $tbody = '';
        foreach ($topReporters as $key => $reporter) {
            $user = $reporter->user;
            if (!$user) continue;

            $positionLabel = $reporter->position == 1 ? '1st' : ($reporter->position == 2 ? '2nd' : '3rd');
            $reportTypeIds = json_decode($user->report_type, true); 
            $reportTypeId = $reportTypeIds[0] ?? null;
            $reportTypeTitle = $reportTypeId ? ($reportCategories[$reportTypeId] ?? '') : '';
            $reporterAreaTitle = $reportTypeId ? getReporterAreaName($user->language_id ?? 1, $reportTypeId, $user) : '';

            $photoUrl = $user->photo ? asset('assets/images/admin/' . $user->photo) : asset('assets/images/default-user.png');
            
            $tbody .= "<tr>
                <td><span class='badge badge-success px-2 py-1'>{$positionLabel}</span></td>
                <td><img src='{$photoUrl}' width='40' height='40' style='border-radius:50%;object-fit:cover;border:1px solid #ddd;'></td>
                <td>{$user->name}</td>
                <td>{$user->phone}</td>
                <td>{$reportTypeTitle}</td>
                <td>{$reporterAreaTitle}</td>
                <td><strong>{$reporter->total_views}</strong></td>
                <td><span class='badge badge-info px-2 py-1'><i class='fas fa-trophy text-warning'></i> Winner</span></td>
            </tr>";
        }

        return response()->json([
            'tbody' => $tbody,
            'week_range' => $latestWeekRange ?? 'None'
        ]);
    }

    public function setWeeklyBest(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Handled automatically.'
        ]);
    }

    public function delete($id){
        $data  = User::find($id);
        @unlink('assets/images/admin/'.$data->photo);
        $data->delete();
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->is_ban = $user->is_ban == 1 ? 0 : 1;
        $user->save();
        
        $status = $user->is_ban == 1 ? 'disabled' : 'enabled';
        return redirect()->back()->with('success', "User {$user->name} has been {$status} successfully.");
    }
}
