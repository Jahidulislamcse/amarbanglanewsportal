<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Post;
use Datatables;

use Auth;

class PendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables(Request $request)
    {
        $input = $request->all();
        $admin = Auth::guard('admin')->user();
        $role  = $admin->role;
        $default_language = Language::where('is_default', 1)->first();
    
        $divisionIds = [];
        if ($admin && (
            $admin->role->name === 'Divisional Admin' ||
            $admin->role->name === 'Hed Of Admin' ||
            $admin->role->name === 'Dex Report' ||
            $admin->role->name === 'Admin'
        )) {
        $divisionIds = $admin->divisions->pluck('id')->toArray();
        }

        if ($role->code != 'admin' && $role->code != 'NA') {
    
            $query = $admin->posts()
                ->where('is_pending', 1)
                ->where('status', 'true');
    
        } else {
    
            $query = Post::where('is_pending', 1)
                ->where('status', 'true');
        }
    
        if (!empty($input['lang'])) {
            $query->where('language_id', $input['lang']);
        } elseif (!empty($input['category'])) {
            $query->where('category_id', $input['category']);
        } else {
            $query->where('language_id', $default_language->id);
        }

        if (!empty($divisionIds)) {
            $query->whereHas('user', function ($q) use ($divisionIds) {
                $q->whereIn('division_id', $divisionIds);
            });
        }
    
        $datas = $query->orderBy('id', 'desc')->get();

        return Datatables::of($datas)
    
            ->addColumn('action', function (Post $data) {
    
                $slider = $data->is_trending == 0
                    ? '<a href="'.route('post.trendingChange',$data->id).'"><i class="fa fa-plus"></i> Add Into Breaking</a>'
                    : '<a href="'.route('post.trendingChange',$data->id).'"><i class="fa fa-minus"></i> Remove Breaking</a>';
    
                $is_approve = $data->is_pending == 0
                    ? '<a href="'.route('post.pendingChange',$data->id).'"><i class="fa fa-file"></i> Make Post Pending</a>'
                    : '<a href="'.route('post.pendingChange',$data->id).'"><i class="fa fa-file"></i> Make Post Approve</a>';
    
                $is_feature = $data->is_feature == 0
                    ? '<a href="'.route('post.feature',$data->id).'"><i class="fa fa-plus"></i> Add into Feature</a>'
                    : '<a href="'.route('post.feature',$data->id).'"><i class="fa fa-minus"></i> Remove Feature</a>';
    
                $edit = $data->post_type == 'rss'
                    ? ''
                    : '<a href="'.route('post.edit',$data->id).'"><i class="fas fa-edit"></i> Edit</a>';
    
                $details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'" target="_blank">
                                <i class="fa fa-info-circle"></i> View on Frontend
                            </a>';
    
                return '
                    <div class="godropdown">
                        <button class="go-dropdown-toggle">
                            Actions <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="action-list">
                            '.$details.'
                            '.$edit.'
                            '.$slider.'
                            '.$is_feature.'
                            '.$is_approve.'
                            <a href="javascript:;" data-href="'.route('post.delete',$data->id).'" 
                               data-toggle="modal" data-target="#confirm-delete" class="delete">
                               <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </div>
                    </div>
                ';
            })
    
            ->editColumn('category_id', function (Post $data) {
                return $data->category ? $data->category->title : '';
            })
    
            ->addColumn('checkbox', function (Post $data) {
                return '<input type="checkbox" class="form-check-input postCheck" value="'.$data->id.'">';
            })
            
                
            ->editColumn('image_big', function (Post $data) {
                if ($data->post_type == 'rss') {
                    $img = $data->rss_image ?: asset('assets/images/nopic.png');
                } else {
                    $img = $data->image_big
                        ? asset('assets/images/post/'.$data->image_big)
                        : asset('assets/images/noimage.png');
                }
                return '<img src="'.$img.'" alt="Image">';
            })
    
            ->editColumn('phone', function (Post $data) {

                $phone_a = $data->reporter_id && $data->admin ? $data->admin->phone : '';
            
                $phone_u = $data->user_id && $data->user ? $data->user->phone : '';
            
                if($phone_a){
                    $phone = $phone_a;
                }else{
                    $phone = $phone_u;
                }
            
                return $phone ? '<span class="badge badge-success">'.$phone.'</span>' : '';
            })
            
            ->addColumn('reporter_area', function (Post $data) {

                $area = '';
                $reportType = json_decode($data->user->report_type ?? '[]', true);
                $reportTypeId = $reportType[0] ?? null;
            
                if ($reportTypeId) {
                    $area = getReporterAreaName(
                        $data->language_id,
                        $reportTypeId,
                        $data->user
                    );
                }
            
                return $area;
            })

    
            ->editColumn('admin_id', function (Post $data) {
                return $data->admin
                    ? $data->admin->name
                    : ($data->user ? $data->user->name : '');
            })
            ->editColumn('reporter_id',function(Post $data){
                $name_a = $data->reporter_id ? $data->admin->name: '';
				$name_u = $data->user_id ? $data->user->name: '';
				if($name_a){
					$name = $name_a;
				}else{
					$name = $name_u;
				}
                return $name;
            })
            ->addColumn('orders', function (Post $data) {
                if ($data->user_id && $data->user) {
                    return '<button class="btn btn-outline-primary btn-sm rounded-pill view-orders" data-id="' . $data->user_id . '" data-name="' . e($data->user->name) . '">
                                <i class="fas fa-shopping-basket"></i> Orders
                            </button>';
                }
                return '-';
            })
    
            ->rawColumns([
                'image_big',
                'category_id',
                'checkbox',
                'phone',
                'reporter_area',
                'reporter_id',
                'orders',
                'action'
            ])
    
            ->toJson();
    }

    public function index(){
        return view('admin.pending.index');
    }

    public function categoryFilter($id){
        $datas = Language::find($id)->categories()->where('parent_id',NULL)->get();
        $output = '<option data-href="'.route('pending.datatables').'?category=&lang='.$id.'">All</option>';
        foreach($datas as $data){
            $output .= '<option data-href="'.route('pending.datatables').'?category='.$data->id.'">'.$data->title.'</option>';
        }
        return $output;
    }
}
