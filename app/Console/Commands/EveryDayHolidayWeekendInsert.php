<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Holiday;
use App\Models\User;
use App\Models\AttendanceTimesheet;

use App\Http\Controllers\Leave\LeaveController;

class EveryDayHolidayWeekendInsert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:insertHolidayWeekend  {dbname}?';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert holiday and weekend in attendance timesheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LeaveController $leaveController)
    {
        $this->leaveController = $leaveController;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Artisan::call("db:connect", ['database' => $this->argument('dbname')]);

        $date = new \DateTime(null, new \DateTimeZone('Asia/Dhaka'));
        $toDay = $date->format('Y-m-d');

        $getUsers = User::whereIn('status', [1,9,10])->get();

        if(count($getUsers) > 0){
            foreach($getUsers as $user){

                $chk = AttendanceTimesheet::where('user_id', $user->id)->where('date', $toDay)->first();
                $val = $this->leaveController->getWeekendHolidays($toDay, $toDay, $user->id);
                
                if(count($chk) < 1){
                    if($val['holidays'] > 0){
                        $sav = new AttendanceTimesheet;
                        $sav->user_id = $user->id;
                        $sav->date = $toDay;
                        $sav->observation = 3;
                        $sav->save();
                    }
                    elseif($val['weekend'] > 0){
                        $sav = new AttendanceTimesheet;
                        $sav->user_id = $user->id;
                        $sav->date = $toDay;
                        $sav->observation = 4;
                        $sav->save();
                    }
                }
                    
            }
        }
    }
}
