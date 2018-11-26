<?php

namespace App\Http\Controllers\Setup;

use App\Models\Setup\User as SetupUser;
use App\Models\Setup\Config;
use App\Models\Setup\Package;
use App\Models\Setup\Payment;
use App\Models\Setup\Module as SetupModule;
use App\Models\Setup\Menu as SetupMenu;
use App\Models\Module as HrmsModule;
use App\Models\Menu as HrmsMenu;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class UserDetailsController extends Controller
{
	public function __construct(){
        $this->middleware('auth:setup');
    }


    public function index($id=null){

        $id=($id)?$id:Auth::user('setup')->id;

    	$data['title'] = "User Details-HRMS";
    	$data['config_info']    = Config::where('user_id',$id)->first();
    	$data['sister_concern'] = Config::where('parent_id',$data['config_info']->id)->get();
    	$data['user_info']      = SetupUser::find($id);
    	$data['payment_info']   = Payment::with('package')->where('user_id',$id)->get();
        $data['all_modules']    = SetupModule::where('module_status', 1)->get();

        Artisan::call("db:connect", ['database'=> $data['config_info']->database_name]);

        $data['hrms_modules'] = HrmsModule::all();
        //convert obj to array
        $moduleAry = json_decode(json_encode($data['hrms_modules']),TRUE); 
        $data['hrms_modules_id'] = array_column($moduleAry, 'id');
           
    	return view('setup.userDetails',$data);
    }

    public function addHrmsModule(Request $request){

        $this->validate($request, [
            'new_module' => 'required',
            'user_db' => 'required'
        ]);

        $fetchData = SetupModule::with('menus')->where('id', $request->new_module)->first();

        $moduleData[] = [
                    'id' => $fetchData->id,
                    'module_icon_class' => $fetchData->module_icon_class,
                    'module_name' => $fetchData->module_name,
                    'module_details' => $fetchData->module_details,
                    'module_status' => $fetchData->module_status,
                ];

        if($fetchData->menus){
            foreach($fetchData->menus as $info){
                $menuData[] = [
                        'id' => $info->id,
                        'menu_parent_id' => $info->menu_parent_id,
                        'module_id' => $info->module_id,
                        'menu_name' => $info->menu_name,
                        'menu_url'  => $info->menu_url,
                        'menu_section_name' => $info->menu_section_name,
                        'menu_icon_class' => $info->menu_icon_class,
                        'menu_status' => $info->menu_status,
                    ];
            }
        }

        Artisan::call("db:connect", ['database'=> $request->user_db]);
        
        DB::beginTransaction();

        try {
            
                HrmsModule::insert($moduleData);
                HrmsMenu::insert($menuData);
        
            DB::commit();
            $request->session()->flash('success','Data successfully updated!');

        } catch (\Exception $e) {
            DB::rollback();
            $request->session()->flash('danger','Data not updated!');
        }

        Artisan::call('db:connect'); //Connect with default DB

        return redirect()->back();
    }

}
