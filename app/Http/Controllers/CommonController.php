<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\CommonService;

use App\Models\Language;
use App\Models\Designation;
use App\Http\Requests\DesignationRequest;

class CommonController extends Controller
{
    use CommonService;

    public function __construct(){
    	$this->middleware('auth:hrms');

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }


    public function addDesignation(DesignationRequest $request){
        try{
            $request->offsetSet('created_by', $this->auth->id);
//            $data['data'] = Designation::create($request->all());
            Designation::create($request->all());
            $data['data'] = $this->getDesignations();

            if($request->ajax()){
                $data['status'] = 'success';
                $data['statusType'] = 'OK';
                $data['code'] = 200;
                $data['title'] = 'Success!';
                $data['message'] = 'Designation Successfully Added.';
                return response()->json($data,200);
            }

            $request->session()->flash('success','Designation Successfully Added.');
            return redirect()->back();

        }catch(\Exception $e){
            if($request->ajax()){
                $data['status'] = 'danger';
                $data['statusType'] = 'NotOk';
                $data['code'] = 500;
                $data['title'] = 'Error!';
                $data['message'] = 'Designation Not Saved.';
                $data['data'] = '';
                return response()->json($data,500);
            }

            $request->session()->flash('danger','Designation Not Added.');
            return redirect()->back()->withInput();
        }
    }


    public function addLanguage(Request $request){
        $this->validate($request,['language_name'=>'required']);

        try{
            $request->offsetSet('created_by', $this->auth->id);
            $data['data'] = Language::create($request->all());

            if($request->ajax()){
                $data['status'] = 'success';
                $data['statusType'] = 'OK';
                $data['code'] = 200;
                $data['title'] = 'Success!';
                $data['message'] = 'Language Successfully Added.';
                return response()->json($data,200);
            }

            $request->session()->flash('success','Language Successfully Added.');
            return redirect()->back();

        }catch(\Exception $e){
            if($request->ajax()){
                $data['status'] = 'danger';
                $data['statusType'] = 'NotOk';
                $data['code'] = 500;
                $data['title'] = 'Error!';
                $data['message'] = 'Language Not Saved.';
                $data['data'] = '';
                return response()->json($data,500);
            }

            $request->session()->flash('danger','Language Not Added.');
            return redirect()->back()->withInput();
        }
    }


}
