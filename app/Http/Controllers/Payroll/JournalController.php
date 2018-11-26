<?php

namespace App\Http\Controllers\Payroll;

use App\Models\User;
use App\Models\LoanDetails;
use App\Models\Setting;
use App\Models\PdfInfo;

use App\Services\CommonService;
use App\Services\PermissionService;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JournalController extends Controller
{
	use CommonService;  //PermissionService

	protected $auth;

    public function __construct(Auth $auth){
    	$this->middleware('auth:hrms');
        $this->middleware('CheckPermissions', ['except' => ['reportPdf', 'reportXl', 'saveEmpSign']]);
        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            if($request->isMethod('post')){
                return $this->generateJournalSheet($request, 'ajx');
            }
        }

        // $data['sidebar_hide'] = true;
        $data['title'] = 'Journal';
        $data['departments'] = $this->getDepartments();
        $data['branches'] = $this->getBranches();
        return view('payroll.journal')->with($data);
    }

    public function generateJournalSheet($request, $ajxORnot = null){

        if($ajxORnot == 'ajx'){
            $this->validate($request,[
                'salary_month' => 'required',
            ],[
                'salary_month' => 'month',
            ]);

            $branch_id = $request->branch_id;
            $department_id = $request->department_id;
            $unit_id = $request->unit_id;
            $salary_month = $request->salary_month;
        }
        else{
            $branch_id = $request['branch_id'];
            $department_id = $request['department_id'];
            $unit_id = $request['unit_id'];
            $salary_month = $request['salary_month'];
        }

        $user_id = 0;
            
        // $get_prev_month = date('Y-m', strtotime($salary_month ." -1 month"));
        $user_ids = $this->getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month)->toArray();

        $user_loans = LoanDetails::with('user', 'user.employeeTypeMapFirst')->whereIn('user_id', $user_ids)->where('salary_month', $salary_month)->get();

        $total_loan    = $user_loans->sum('amount');

        $final_journal = [];

        foreach($user_loans as $userLone){
            
            $final_journal[] = (object)[
                'pdf_branch_id' => $branch_id,
                'pdf_department_id' => $department_id,
                'pdf_unit_id' => $unit_id,
                'pdf_user_id' => $user_id,
                'pdf_salary_month' => $salary_month,
                'user_id'=> $userLone->user_id,
                'full_name' => $userLone->user->full_name,
                'amount' => $userLone->amount,
                'employee_no' => $userLone->user->employee_no,
                'salary_month' => "Salary of ".Carbon::parse($salary_month)->format('M Y'),
                'total_loan' => $total_loan,
            ];
        }
        
        return $final_journal;
    }

    protected function getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month)
    {
        if($user_id !=0){
            $user_ids = [$user_id];
        }elseif($branch_id !=0 || $department_id !=0 || $unit_id !=0){
            $users = $this->getEmployeeByDepartmentUnitBranch($branch_id, $department_id, $unit_id);
            $user_ids = $users->where('effective_date','<=',Carbon::parse($salary_month)->format('Y-m-t'))->pluck('id');
        }else{
            $users = User::where('status',1)->get();
            $user_ids = $users->where('effective_date','<=',Carbon::parse($salary_month)->format('Y-m-t'))->pluck('id');
        }

        return $user_ids;
    }

    public function reportPdf(Request $request){

        if($request->salary_month != 0){
            $reports['reports'] = $this->generateJournalSheet($request->all());
            $reports['salary_month'] = $request->salary_month;
            $reports['company_details'] = Setting::all();
            
            $signatures = PdfInfo::where('report_pdf_id', 3)->first();

            view()->share('reports',$reports['reports']);
            view()->share('salary_month',$reports['salary_month']);
            view()->share('company_details',$reports['company_details']);
            view()->share('inWord', $this->number_to_word($reports['reports'][0]->total_loan));
            view()->share('signatures',unserialize($signatures->signatures));

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('report.payroll.journalSheetPDF');
            return $pdf->stream();  

            // return view('report.payroll.journalSheetPDF', $reports);
        }
    }

    public function reportXl(Request $request){

        if($request->salary_month != 0){

            $reports['reports'] = $this->generateJournalSheet($request->all());
            $reports['salary_month'] = $request->salary_month;
            $reports['company_details'] = Setting::all();

            \Excel::create('excel_journal_sheet', function($excel) use ($reports){
                $excel->sheet('ExportFile', function($sheet) use ($reports){
                
                    $sheet->setColumnFormat(['C' => '0000']);
                    $sheet->loadView('report.payroll.journalSheetXl', $reports);
                });
            })->export('xls');
        }
    }

    public function getSignEmp(){

        $datas = PdfInfo::where('report_pdf_id', 3)->first();

        return unserialize($datas->signatures);
    }

    public function saveEmpSign(Request $request){

        $postData = [
            [
                'name' => $request->emp1,
                'desig' => $request->desig1
            ],
            [
                'name' => $request->emp2,
                'desig' => $request->desig2
            ],
            [
                'name' => $request->emp3,
                'desig' => $request->desig3
            ],
            [
                'name' => $request->emp4,
                'desig' => $request->desig4
            ],
            [
                'name' => $request->emp5,
                'desig' => $request->desig5
            ],
            [
                'name' => $request->emp6,
                'desig' => $request->desig6
            ],
            [
                'name' => $request->emp7,
                'desig' => $request->desig7
            ],
            [
                'name' => $request->emp8,
                'desig' => $request->desig8
            ],
            [
                'name' => $request->emp9,
                'desig' => $request->desig9
            ]
        ];

        $chk = PdfInfo::where('report_pdf_id', 3)->first();

        if(count($chk) > 0){

            PdfInfo::where('report_pdf_id', 3)->update([
                        'signatures' => serialize($postData),
                    ]);
        }
        else{
            $sav = new PdfInfo;
            $sav->report_pdf_id = 3;
            $sav->signatures = serialize($postData);
            $sav->save();
        }

        $data['title'] = 'success';
        $data['message'] = "Data updated successfully!";

        return $data;
    }

    //INword*****************
    function number_to_word( $num = '' )
    {
        $num    = ( string ) ( ( int ) $num );
       
        if( ( int ) ( $num ) && ctype_digit( $num ) )
        {
            $words  = array( );
           
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
           
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
           
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
           
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
           
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
           
            foreach( $num_levels as $num_part )
            {
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
               
                if( $tens < 20 )
                {
                    $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
                }
                else
                {
                    $tens   = ( int ) ( $tens / 10 );
                    $tens   = ' ' . $list2[$tens] . ' ';
                    $singles    = ( int ) ( $num_part % 10 );
                    $singles    = ' ' . $list1[$singles] . ' ';
                }
                $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
            }
           
            $commas = count( $words );
           
            if( $commas > 1 )
            {
                $commas = $commas - 1;
            }
           
            $words  = implode( ', ' , $words );
           
            //Some Finishing Touch
            //Replacing multiples of spaces with one space
            $words  = trim( str_replace( ' ,' , ',' , $this->trim_all( ucwords( $words ) ) ) , ', ' );
            if( $commas )
            {
                $words  = str_replace_last( ',' , ' and' , $words );
            }
           
            return $words;
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }

    function trim_all( $str , $what = NULL , $with = ' ' )
    {
        if( $what === NULL )
        {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space
           
            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }
       
        return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
    }

    function str_replace_last( $search , $replace , $str ) {
        if( ( $pos = strrpos( $str , $search ) ) !== false ) {
            $search_length  = strlen( $search );
            $str    = substr_replace( $str , $replace , $pos , $search_length );
        }
        return $str;
    }
}