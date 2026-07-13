<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Management;
use App\Models\Fee;
use Validator;

class GeneralSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    protected $rules =
    [
        'logo'              => 'mimes:jpeg,jpg,png,svg,webp',
        'favicon'           => 'mimes:jpeg,jpg,png,svg,webp',
        'loader'            => 'mimes:gif',
        'admin_loader'      => 'mimes:gif',
        'error_photo'       => 'mimes:jpeg,jpg,png,svg,webp',
        'footer_logo'       => 'mimes:jpeg,jpg,png,svg,webp',
    ];

    private function setEnv($key, $value,$prev)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . $prev,
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }


    public function update(Request $request){

        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
	
		

        $data   = GeneralSettings::find(1);
        $input  = $request->all();

        $input['recent_news_limit'] = $request->recent_news_limit ?? $data->recent_news_limit;
        $input['popular_news_limit'] = $request->popular_news_limit ?? $data->popular_news_limit;

        if($request->hasFile('logo')){
            $logo = $request->file('logo');
            $name = time().$logo->getClientOriginalName();
            $logo->move('assets/images/logo/',$name);
            @unlink('assets/images/logo/'.$data->logo);
            $input['logo'] = $name;
        }
        if($request->hasFile('footer_logo')){
            $footer_logo = $request->file('footer_logo');
            $name = time().$footer_logo->getClientOriginalName();
            $footer_logo->move('assets/images/logo/',$name);
            @unlink('assets/images/logo/'.$data->footer_logo);
            $input['footer_logo'] = $name;
        }
        if($request->hasFile('favicon')){
            $favicon = $request->file('favicon');
            $name = time().$favicon->getClientOriginalName();
            $favicon->move('assets/images/',$name);
            @unlink('assets/images/'.$data->favicon);
            $input['favicon'] = $name;
        }
        if($request->hasFile('loader')){
            $loader = $request->file('loader');
            $name = time().$loader->getClientOriginalName();
            $loader->move('assets/images/',$name);
            @unlink('assets/images/'.$data->loader);
            $input['loader'] = $name;
        }
        if($request->hasFile('admin_loader')){
            $admin_loader = $request->file('admin_loader');
            $name = time().$admin_loader->getClientOriginalName();
            $admin_loader->move('assets/images/',$name);
            @unlink('assets/images/'.$data->admin_loader);
            $input['admin_loader'] = $name;
        }
        if($request->hasFile('error_photo')){
            $error_photo = $request->file('error_photo');
            $name = time().$error_photo->getClientOriginalName();
            $error_photo->move('assets/images/',$name);
            @unlink('assets/images/'.$data->error_photo);
            $input['error_photo'] = $name;
        }
		
		 if(isset($request->category_section)){
            $input['home_category_section'] = json_encode($request->categories);
        }
		
		 if(isset($request->category_section_en)){
            $input['home_category_section_en'] = json_encode($request->categories);
        }
	
      
        if($request->captcha_secret_key){

            $this->setEnv('NOCAPTCHA_SECRET',$request->captcha_secret_key,env('NOCAPTCHA_SECRET'));
        }
        if($request->captcha_site_key){
            $this->setEnv('NOCAPTCHA_SITEKEY',$request->captcha_site_key,env('NOCAPTCHA_SITEKEY'));
        }
        $data->update($input);
        
        if ($request->has('address') || $request->has('phone')) {
            Contact::updateOrCreate(
                ['id' => 1],
                [
                    'address' => $request->address,
                    'address_bn' => $request->address_bn,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'website' => $request->website,
                ]
            );
        }

    
        if ($request->has('management_id')) {
        
            $existing_ids = array_filter($request->management_id);
        
            Management::whereNotIn('id', $existing_ids)->delete();
        
            if ($request->designation) {
                foreach ($request->designation as $key => $value) {
                    Management::updateOrCreate(
                        ['id' => $request->management_id[$key] ?? null],
                        [
                            'designation' => $request->designation[$key],
                            'designation_bn' => $request->designation_bn[$key],
                            'name' => $request->name[$key],
                            'name_bn' => $request->name_bn[$key],
                        ]
                    );
                }
            }
        }


        $msg = 'Data Updated Successfully';
        return response()->json($msg);

    }
    public function logo(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.logo',compact('data'));
    }
	
	 public function homecategorysection(){
		$sections=array(array('id'=>1,'title'=>'Section 1 (Checked 2 Category )'),array('id'=>2,'title'=>'Section 2 (Checked 3 Category )'),array('id'=>3,'title'=>'Section 3 (Checked 2 Category )'),array('id'=>4,'title'=>'Section 4 (Checked 2 Category )'),array('id'=>5,'title'=>'Section 5 (Checked 3 Category )'),array('id'=>6,'title'=>'Section 6 (Crime Section )'));
		
		 $categorlists  = Category::orderBy('category_order','asc')
                                        ->where('language_id','=',1)
                                        ->where('parent_id','=',null)
                                        ->get();
						
        $data = GeneralSettings::select('home_category_section')->first(1);
        return view('admin.generalsettings.homecategorysection',compact('data','sections','categorlists'));
    }
	public function homecategorysectionen(){
		$sections=array(array('id'=>1,'title'=>'Section 1 (Checked 2 Category )'),array('id'=>2,'title'=>'Section 2 (Checked 3 Category )'),array('id'=>3,'title'=>'Section 3 (Checked 2 Category )'),array('id'=>4,'title'=>'Section 4 (Checked 2 Category )'),array('id'=>5,'title'=>'Section 5 (Checked 3 Category )'));
		
		 $categorlists  = Category::orderBy('category_order','asc')
                                        ->where('language_id','=',2)
                                        ->where('parent_id','=',null)
                                        ->get();
						
        $data = GeneralSettings::select('home_category_section_en AS home_category_section')->first(1);
        return view('admin.generalsettings.homecategorysectionen',compact('data','sections','categorlists'));
    }
	
    public function favicon(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.favicon',compact('data'));
    }
    public function loader(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.loader',compact('data'));
    }
    public function websiteContent(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.websiteContent',compact('data'));
    }
    
    public function fees()
    {
        $data = Fee::first();
    
        if (!$data) {
            $data = Fee::create([
                'free_reader_rate' => 0.00,
                'executive_reader_rate' => 0.00,
                'vip_reader_rate' => 0.00,
    
                'reader_view_rate' => 0.00,
                'reporter_view_rate' => 0.00,
                'rep_monthly_fee' => 0.00,
    
                'referral_commission' => 0.00,
                'referral_view_commission' => 0.00,
    
                'team_gen_1_rate' => 0.00,
                'team_gen_2_rate' => 0.00,
                'team_gen_3_rate' => 0.00,
                'team_gen_4_rate' => 0.00,
                'team_gen_5_rate' => 0.00,
    
                'executive_package_price' => 0.00,
                'vip_package_price' => 0.00,
                'withdraw_min' => 0.00,

                'reporter_1st_prize' => 0.00,
                'reporter_2nd_prize' => 0.00,
                'reporter_3rd_prize' => 0.00,
                'quiz_1st_prize' => 0.00,
                'quiz_2nd_prize' => 0.00,
                'quiz_3rd_prize' => 0.00,
            ]);
        }
    
        return view('admin.generalsettings.fees', compact('data'));
    }


    
    public function feesUpdate(Request $request)
    {
       
        $rules = [
            'free_reader_rate' => 'required|numeric|min:0',
            'executive_reader_rate' => 'required|numeric|min:0',
            'vip_reader_rate' => 'required|numeric|min:0',
    
            'rep_monthly_fee' => 'required|numeric|min:0',
            'reader_view_rate' => 'required|numeric|min:0',
            'reporter_view_rate' => 'required|numeric|min:0',
    
            'referral_commission' => 'required|numeric|min:0',
            'common_reffer_commission' => 'required|numeric|min:0',
            'referral_view_commission' => 'required|numeric|min:0',
    
            'executive_package_price' => 'required|numeric|min:0',
            'vip_package_price' => 'required|numeric|min:0',
            'withdraw_min' => 'required|numeric|min:0',

            'reporter_1st_prize' => 'required|numeric|min:0',
            'reporter_2nd_prize' => 'required|numeric|min:0',
            'reporter_3rd_prize' => 'required|numeric|min:0',
            'quiz_1st_prize' => 'required|numeric|min:0',
            'quiz_2nd_prize' => 'required|numeric|min:0',
            'quiz_3rd_prize' => 'required|numeric|min:0',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
    
        try {
    
            $fee = Fee::firstOrCreate([]);
    
            $fee->update($request->only([
                'free_reader_rate',
                'executive_reader_rate',
                'vip_reader_rate',
    
                'rep_monthly_fee',
                'reader_view_rate',
                'reporter_view_rate',
    
                'referral_commission',
                'common_reffer_commission',
                'referral_view_commission',
                
                'executive_package_price',
                'vip_package_price',
                'withdraw_min',

                'reporter_1st_prize',
                'reporter_2nd_prize',
                'reporter_3rd_prize',
                'quiz_1st_prize',
                'quiz_2nd_prize',
                'quiz_3rd_prize',
            ]));
    
            return response()->json([
                'success' => __('Fees settings updated successfully!'),
                'data' => $fee
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => __('Failed to update fees settings. Please try again.')
            ]);
        }
    }
    
    public function popularTags(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.popularTags',compact('data'));
    }
    public function footer(){
        $data = GeneralSettings::find(1);
        $contact = Contact::first();
        $management = Management::all();
        return view('admin.generalsettings.footer',compact('data', 'contact', 'management'));
    }
    public function errorPage(){
        $data = GeneralSettings::find(1);
        return view('admin.generalsettings.errorPage',compact('data'));
    }

    public function tawkto($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_talkto =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_talkto =0;
            $data->update();
            return response()->json($id);
        }
    }

    public function isLoader($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_loader =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_loader =0;
            $data->update();
            return response()->json($id);
        }
    }

    public function isAdminLoader($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_adminloader =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_adminloader =0;
            $data->update();
            return response()->json($id);
        }
    }

    public function disqus($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_disqus =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_disqus =0;
            $data->update();
            return response()->json($id);
        }

    }
    public function capcha($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_capcha =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_capcha =0;
            $data->update();
            return response()->json($id);
        }
    }

    public function emailVerfication($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_verification_email =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_verification_email =0;
            $data->update();
            return response()->json($id);
        }
    }

    public function smtp($id){
        $data  = GeneralSettings::findOrFail(1);
        if($id ==1){
            $data->is_smtp =1;
            $data->update();
            return response()->json($id);
        }else{
            $data->is_smtp =0;
            $data->update();
            return response()->json($id);
        }

    }


}
