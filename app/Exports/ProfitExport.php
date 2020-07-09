<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Invoice;
use App\MedicalInformation;
use DB;

class ProfitExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($request,$exchange_usd,$exchange_thb,$exchange_mmk,$exchange_lak){   
        $this->request = $request;
        $this->user = auth()->user();
        $this->exchange_usd = $exchange_usd;
        $this->exchange_thb = $exchange_thb;
        $this->exchange_mmk = $exchange_mmk;
        $this->exchange_lak = $exchange_lak;
    }

    public function view(): View
    {
        if ($this->user->name == 'admin') {
            $invoices = MedicalInformation::where('deleted_at',NULL);
        }else{
            $invoices = MedicalInformation::where('deleted_at',NULL);
        }
        if ($this->request->has('start_date')&& isset($this->request->start_date)) {
            $invoices->whereDate('date_of_visit','>=',$this->request->start_date);
        }
        if ($this->request->has('to_date')&& isset($this->request->start_date)) {
            $invoices->whereDate('date_of_visit','<=',$this->request->to_date);
        }
        if ($this->request->has('country_id') && isset($this->request->country_id)) {
            $country = $this->request->country_id;
            $invoices->whereHas('hospital',function($query) use ($country){
                $query->where('country',$country);
            });
        }
        if ($this->request->has('desk_id') && isset($this->request->desk_id)) {
            $desk = $this->request->desk_id;
            switch ($desk) {
                case 1:
                    $code = "A";
                    break;
                case 2:
                    $code = "P";
                    break;
                case 3:
                    $code = "L";
                    break;
                
                default:
                    $code = "M";
                    break;
            }
            $invoices->whereHas('hospital',function($query) use ($code){
                $query->where('country_code',$code);
            });
        }
        if($this->request->has('search') && isset($this->request->search)){
            $search = $this->request->search;
            $search_id = json_decode($search);
            $invoices = $invoices->whereIn('id',$search_id );
        }
        $invoices = $invoices->get();

        return view('admin.reports.profit_reports_excel', [
            'profits' => $invoices,'country' => $this->request->country_id,'desk' => $this->request->desk_id,'exchange_usd' => $this->exchange_usd,'exchange_thb' => $this->exchange_thb,'exchange_mmk' => $this->exchange_mmk,'exchange_lak' => $this->exchange_lak
        ]);

    }
}
