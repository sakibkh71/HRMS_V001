<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

//class UsersExport implements FromCollection
//{
//    /**
//    * @return \Illuminate\Support\Collection
//    */
//    public function collection()
//    {
//        return User::all();
//    }
//}

class UsersExport implements FromView
{
    public function view(): View
    {
        return view('report.employee.empListXl', [
            'users' => User::all(),
            'company_details' => Setting::all()
        ]);
    }
}
