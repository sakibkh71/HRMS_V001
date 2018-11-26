<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Increment;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SalaryIncrementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $toDate = Carbon::now()->format('Y-m-d');

        $increments = \DB::table('increments')
            ->select(\DB::raw('(SUM(increments.increment_amount)+users.gross_salary) as total_increments'), 'increments.user_id')
            ->where('approved_by','!=',0)
            ->where('increment_status',0)
            ->where('increment_effective_date','<=', $toDate)
            ->groupBy('increments.user_id')
            ->join('users','users.id','=','increments.user_id')
            ->get();

        foreach($increments as $info){

            $userInfo =  User::find($info->user_id);
            $increment_amount = $info->total_increments - $userInfo->gross_salary;

            if($userInfo->basic_salary > 0){

                $final_basic = $userInfo->basic_salary+$increment_amount;
                   
                $userInfo->gross_salary = $info->total_increments;
                $userInfo->basic_salary = $final_basic;
                $userInfo->save();
            }
            else{

                $final_in_cash = $userInfo->salary_in_cache+$increment_amount;
                 
                $userInfo->gross_salary = $info->total_increments;
                $userInfo->salary_in_cache = $final_in_cash;
                $userInfo->save();
            }
        }

        Increment::where('increment_status',0)->where('increment_effective_date','<=', $toDate)
        ->update(['increment_status'=>1]);
    }


}
