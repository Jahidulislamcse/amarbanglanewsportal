<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\PollQuestion;
use App\Models\Post;
use App\Models\Role;
use App\Models\Rss;
use App\Models\User;
use App\Models\AdminDivision;
use App\Models\Admin;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index(){
        $default_language = Language::where('is_default',1)->first();
        
        $adminId = Auth::guard('admin')->id();
        
        $hasDivision = AdminDivision::where('admin_id', $adminId)->exists();
        $data['hasDivision'] = $hasDivision;

        if ($hasDivision) {
            $approvedPosts = Post::where('approve_by', $adminId)
                                 ->where('status', 'true');
        
            $data['approved_total'] = (clone $approvedPosts)->count();
        
            $data['approved_today'] = (clone $approvedPosts)
                ->whereDate('created_at', Carbon::today())
                ->count();
        
            $data['approved_last_week'] = (clone $approvedPosts)
                ->whereBetween('created_at', [
                    Carbon::now()->subWeek(),
                    Carbon::now()
                ])
                ->count();
        
            $data['approved_last_month'] = (clone $approvedPosts)
                ->whereBetween('created_at', [
                    Carbon::now()->subMonth(),
                    Carbon::now()
                ])
                ->count();
        }
            
        $data['total_post'] = Post::count();
        $data['author_post'] = Auth::guard('admin')->user()->posts()->count();
		
        $data['pending_posts'] = Post::where('is_pending', 1)
            ->where('status', 'true')
            ->count();
		
        $data['author_pending'] = Auth::guard('admin')->user()->posts()->where('is_pending', 1)->where('status', 'true')->count();
        $data['drafts'] = Auth::guard('admin')->user()->posts()->where('status', 'draft')->count();
        $data['schedules'] = Auth::guard('admin')
            ->user()
            ->posts()
            ->where('status', 'true')
            ->where('schedule_post', 1)
            ->where('is_pending', 0)
            ->count();
        $data['rss'] = Rss::count();
        $data['polls'] = PollQuestion::count();
        $data['userRole'] = Role::where('id', '!=', 1)->get();
        $data['subscribers'] = Subscriber::orderBy('id', 'desc')->take(10)->get();
        $data['categories'] = Category::where('language_id', '=', $default_language->id);

        return view('admin.dashboard',$data);
    }
	
    public function idcard($id = null, $type = null)
    {
        $reportcategories = DB::table('reportcategories')->pluck('title_bn', 'id');
    
        if ($id) {
            $id = base64_decode($id);
    
            if ($type == 1) {
                
                $data = User::select(
                    'users.id',
                    'users.name',
                    'users.photo',
                    'users.blood',
                    'users.phone',
                    'users.report_type',
                    'users.division_id',
                    'users.district_id',
                    'users.thana_id',
                    'users.union_id'
                )->where('id', $id)->first();
    
            } else {
                
                $data = Admin::join('roles', 'admins.role_id', '=', 'roles.id')
                    ->where('admins.id', $id)
                    ->select(
                        'admins.id',
                        'admins.name',
                        'admins.photo',
                        'admins.phone',
                        'admins.blood',
                        'roles.name_bn AS report_type'
                    )
                    ->first();
            }
    
        } else {
            $data = auth()->user();
            $type = 1;
        }
    
        // dd($data);
        $areaName = '';
    
        if ($type == 1 && !empty($data->report_type)) {
    
            $types = json_decode($data->report_type, true);
    
            if (is_array($types) && isset($types[0])) {
    
                $typeId = (int) $types[0];
    
                if (in_array($typeId, [29, 31, 37]) && !empty($data->division_id)) {
                    $areaName = DB::table('divisions')->where('id', $data->division_id)->value('bn_name');
    
                } elseif (in_array($typeId, [30, 36]) && !empty($data->district_id)) {
                    $areaName = DB::table('districts')->where('id', $data->district_id)->value('bn_name');
    
                }  elseif (in_array($typeId, [32, 35]) && !empty($data->thana_id)) {
                    $areaName = DB::table('upazilas')->where('id', $data->thana_id)->value('bn_name');
    
                } elseif ($typeId == 34 && !empty($data->union_id)) {
                    $areaName = DB::table('unions')->where('id', $data->union_id)->value('bn_name');
                }
            }
        }
       
    
        return view('user.profile.idcard', compact('data', 'reportcategories', 'type', 'areaName'));
    }

	
	
	  public function applicationform($id=null,$type=null)
    {
		$reportcategories=DB::table('reportcategories')->pluck('title_en','id');
		if($id){
		
			if($type==1){
				$id=base64_decode($id);
				$data = User::select('users.name','users.father_name','users.mother_name','users.address','users.nid_no','users.created_at','users.photo','users.id','users.phone', 'users.report_type')->where('id',$id)->first();
			}else{
				$data = Admin::join('roles', 'admins.role_id', '=', 'roles.id')
				->where('admins.id', $id)
				->select('admins.name','admins.photo','admins.phone', 'roles.name AS report_type')
				->first();
			}
			
		}else{
			$data = auth()->user();
		}
        
        return view('user.profile.applicationform',compact('data','reportcategories','type'));
    }

    public function profile()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile.edit',compact('data'));
    }

    public function profileupdate(Request $request)
    {
        //--- Validation Section
        $data = Auth::guard('admin')->user();
        $rules =
        [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'required|unique:admins,email,'.$data->id,
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
            if($data->photo != null)
            {
                @unlink('assets/images/post/'.$data->image_big);
            }
            $input['photo'] = $name;
        }
        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
    }

    public function passwordreset()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile.password',compact('data'));
    }

    public function changepass(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($request->cpass){
            if (Hash::check($request->cpass, $admin->password)){
                if ($request->newpass == $request->renewpass){
                    $input['password'] = Hash::make($request->newpass);
                }else{
                    return response()->json(array('errors' => [ 0 => 'Confirm password does not match.' ]));
                }
            }else{
                return response()->json(array('errors' => [ 0 => 'Current password Does not match.' ]));
            }
        }
        $admin->update($input);
        $msg = 'Successfully change your passwprd';
        return response()->json($msg);
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('/');
    }


    public function generate_bkup()
    {
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            $bkuplink = url($chk);
        }
        return view('admin.movetoserver',compact('bkuplink','chk'));
    }


    public function clear_bkup()
    {
        $destination  = public_path().'/install';
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        $handle = fopen('backup.txt','w+');
        fwrite($handle,"");
        fclose($handle);
        //return "No Backup File Generated.";
        return redirect()->back()->with('success','Backup file Deleted Successfully!');
    }


    public function activation()
    {
        $activation_data = "";
        if (file_exists(public_path().'/project/license.txt')){
            $license = file_get_contents(public_path().'/project/license.txt');
            if ($license != ""){
                $activation_data = "<i style='color:darkgreen;' class='icofont-check-circled icofont-4x'></i><br><h3 style='color:darkgreen;'>Your System is Activated!</h3><br> Your License Key:  <b>".$license."</b>";
            }
        }
        return view('admin.activation',compact('activation_data'));
    }


    public function activation_submit(Request $request)
    {
        //return config('services.genius.ocean');
        $purchase_code =  $request->pcode;
        $my_script =  'Newspaper';
        $my_domain = url('/');

        $varUrl = str_replace (' ', '%20', config('services.genius.ocean').'purchase112662activate.php?code='.$purchase_code.'&domain='.$my_domain.'&script='.$my_script);

        if( ini_get('allow_url_fopen') ) {
            $contents = file_get_contents($varUrl);
        }else{
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $varUrl);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            $contents = curl_exec($ch);
            curl_close($ch);
        }

        $chk = json_decode($contents,true);

        if($chk['status'] != "success")
        {

            $msg = $chk['message'];
            return response()->json($msg);
            //return redirect()->back()->with('unsuccess',$chk['message']);

        }else{
            $this->setUp($chk['p2'],$chk['lData']);

            if (file_exists(public_path().'/rooted.txt')){
                unlink(public_path().'/rooted.txt');
            }

            $fpbt = fopen(public_path().'/project/license.txt', 'w');
            fwrite($fpbt, $purchase_code);
            fclose($fpbt);

            $msg = 'Congratulation!! Your System is successfully Activated.';
            return response()->json($msg);
            //return redirect('admin/dashboard')->with('success','Congratulation!! Your System is successfully Activated.');
        }
        //return config('services.genius.ocean');
    }

    function setUp($mtFile,$goFileData){
        $fpa = fopen(public_path().$mtFile, 'w');
        fwrite($fpa, $goFileData);
        fclose($fpa);
    }



    public function movescript(){
        ini_set('max_execution_time', 3000);

        $destination  = public_path().'/install';
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }

        $src = base_path().'/vendor/update';
        $this->recurse_copy($src,$destination);
        $files = public_path();
        $bkupname = 'GeniusCart-By-GeniusOcean-'.date('Y-m-d').'.zip';

        $zipper = new \Chumper\Zipper\Zipper;

        $zipper->make($bkupname)->add($files);

        $zipper->remove($bkupname);

        $zipper->close();

        $handle = fopen('backup.txt','w+');
        fwrite($handle,$bkupname);
        fclose($handle);

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        return response()->json(['status' => 'success','backupfile' => url($bkupname),'filename' => $bkupname],200);
    }

    public function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

}
