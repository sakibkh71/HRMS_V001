<?php

namespace App\Http\Controllers\Pim;

use App\Models\Institute;
use App\Models\Degree;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstituteDegreeController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:hrms');
        $this->middleware('CheckPermissions');

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function index(){

        $data['title'] = "Institutes and Degrees";
        $data['institutes'] = Institute::where('education_level_id', 2)->orderBy('id', 'DESC')->get();
        $data['degrees'] = Degree::where('education_level_id', 2)->orderBy('id', 'DESC')->get();

        return view('pim.institute_n_degree', $data);
    }

    public function create(Request $request){

        if($request->institute_or_degree == 'institute'){
            $this->validate($request, [
                'institute_name' => 'required',
            ]);
        }
        else{
            $this->validate($request, [
                'degree_name' => 'required',
            ]);
        }

        DB::beginTransaction();

        try{
            if($request->institute_or_degree == 'institute'){

                Institute::create([
                    'institute_name' => $request->institute_name,
                    'education_level_id' => 2,
                    'status' => 1,
                ]);
            }
            else{
                Degree::create([
                    'degree_name' => $request->degree_name,
                    'education_level_id' => 2,
                    'status' => 1,
                ]);
            }
            
            DB::commit();
            $data['title'] = 'success';
            $data['message'] = 'data successfully added!';

        }catch (\Exception $e) {
            
            DB::rollback();
            $data['title'] = 'error';
            $data['message'] = 'data not added!';
        }

        return response()->json($data);
    }

    public function edit($id, $typee){

        $dataAry = [];

        if($typee == 'Institute'){
            $data = Institute::find($id);
            $dataAry['id'] = $data->id;
            $dataAry['name'] = $data->institute_name;
            $dataAry['institute_or_degree'] = 'institute';
        }
        elseif($typee == 'Degree'){
            $data = Degree::find($id);
            $dataAry['id'] = $data->id;
            $dataAry['name'] = $data->degree_name;
            $dataAry['institute_or_degree'] = 'degree';
        }

        return $dataAry;
    }

    public function update(Request $request){

        $this->validate($request, [
            'ins_degree_name' => 'required',
        ],
        [
            'ins_degree_name.required' => 'Name field is required.',
        ]);
        

        DB::beginTransaction();

        try{
            if($request->institute_or_degree == 'institute'){

                Institute::find($request->hdn_id)->update([
                    'institute_name' => $request->ins_degree_name,
                ]);
            }
            else{
                Degree::find($request->hdn_id)->update([
                    'degree_name' => $request->ins_degree_name,
                ]);
            }
            
            DB::commit();
            $data['title'] = 'success';
            $data['message'] = 'data successfully updated!';

        }catch (\Exception $e) {
            
            DB::rollback();
            $data['title'] = 'error';
            $data['message'] = 'data not updated!';
        }

        return response()->json($data);
    }

    public function delete($id, $typee){

        try{
            if($typee == 'institute'){
                Institute::find($id)->delete();
            }
            else{
                Degree::find($id)->delete();
            }
        
            $data['title'] = 'success';
            $data['message'] = 'data successfully removed!';

        }catch(\Exception $e){
            
            $data['title'] = 'error';
            $data['message'] = 'Delete not possible. Some one already using this data!';
        }

        return response()->json($data);
    }
}