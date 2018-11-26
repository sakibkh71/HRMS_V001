<?php

namespace App\Http\Controllers\Pim;

use Auth;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    public function __construct(){
        $this->middleware('auth:hrms');
    	$this->middleware('CheckPermissions');

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }


    public function index(Request $request){
    	if($request->ajax()){
    		return Bank::get();
    	}else{
    		return view('pim.bank');
    	}
    }

    public function create(Request $request){
    	$this->validate($request,[
    		'bank_code' => 'required',
    		'bank_name' => 'required'
    		]);

    	try{
    		$bank = Bank::create($request->all());

    		if($request->ajax()){
                $data['data'] = $bank;
                $data['status'] = 'success';
                $data['statusType'] = 'OK';
                $data['code'] = 200;
                $data['title'] = 'Success!';
                $data['message'] = 'Bank Successfully Added!';
                return response()->json($data,200);
            }

            $request->session()->flash('success','Bank Successfully Added!');
            return redirect()->back();

    	}catch(\Exception $e){
    		if($request->ajax()){
                $data['status'] = 'danger';
                $data['statusType'] = 'NotOk';
                $data['code'] = 500;
                $data['title'] = 'Error!';
                $data['message'] = 'Bank Not Added.';
                return response()->json($data,500);
            }

            $request->session()->flash('danger','Bank Not Added!');
            return redirect()->back()->withInput();
    	}
    }


    public function edit(Request $request){

    	try{
    		$bank = Bank::find($request->id);
    		if(!$bank){
    			if($request->ajax()){
	                $data['status'] = 'danger';
	                $data['statusType'] = 'NotOk';
	                $data['code'] = 500;
	                $data['title'] = 'Error!';
	                $data['message'] = 'Bank Not found.';
	                return response()->json($data,500);
            	}
    		}

    		if($request->ajax()){
                $data['data'] = $bank;
                $data['status'] = 'success';
                $data['statusType'] = 'OK';
                $data['code'] = 200;
                $data['title'] = 'Success!';
                $data['message'] = 'Bank Successfully Find!';
                return response()->json($data,200);
            }

            $request->session()->flash('success','Bank Not found.');
            return redirect()->back();

    	}catch(\Exception $e){
    		if($request->ajax()){
                $data['status'] = 'danger';
                $data['statusType'] = 'NotOk';
                $data['code'] = 500;
                $data['title'] = 'Error!';
                $data['message'] = 'Bank Not Find.';
                return response()->json($data,500);
            }

            $request->session()->flash('danger','Bank Not found.');
            return redirect()->back()->withInput();
    	}
    }


    public function update(Request $request){
    	if(!$request->has('status')){
	    	$this->validate($request,[
	    		'bank_code' => 'required',
	    		'bank_name' => 'required'
	    		]);
    	}

    	try{
    		$bank = Bank::find($request->id)->update($request->all());

    		if($request->ajax()){
                $data['data'] = $bank;
                $data['status'] = 'success';
                $data['statusType'] = 'OK';
                $data['code'] = 200;
                $data['title'] = 'Success!';
                $data['message'] = 'Bank Successfully updated!';
                return response()->json($data,200);
            }

            $request->session()->flash('success','Bank Successfully updated!');
            return redirect()->back();

    	}catch(\Exception $e){
    		if($request->ajax()){
                $data['status'] = 'danger';
                $data['statusType'] = 'NotOk';
                $data['code'] = 500;
                $data['title'] = 'Error!';
                $data['message'] = 'Bank not updated.';
                return response()->json($data,500);
            }

            $request->session()->flash('danger','Bank not updated!');
            return redirect()->back()->withInput();
    	}
    }



    public function delete(Request $request){
    	try{
    		Bank::where('id',$request->id)->delete();

    		if($request->ajax()){
                $data['status'] = 'success';
                $data['statusType'] = 'OK';
                $data['code'] = 200;
                $data['title'] = 'Success!';
                $data['message'] = 'Bank Successfully Deleted!';
                return response()->json($data,200);
            }
    	}catch(\Exception $e){
    		if($request->ajax()){
                $data['status'] = 'danger';
                $data['statusType'] = 'NotOk';
                $data['code'] = 500;
                $data['title'] = 'Error!';
                $data['message'] = 'Bank Not Delete.';
                return response()->json($data,500);
            }
    	}
    }




}
