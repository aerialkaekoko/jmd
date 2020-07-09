<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Invoice;
use App\MedicalInformation;
use App\InvoiceDescription;
use DB;

class SummaryExport implements FromView
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
            $invoice = Invoice::where('deleted_at',NULL);
        }else{
            $country_id = $this->user->country;
            $invoice = Invoice::where('deleted_at',NULL)->whereHas('medical_info',function($query) use ($country_id){
                $query->whereHas('hospital',function($q) use ($country_id){
                    $q->where('country',$country_id);
                });
            });
        }

        if ($this->request->has('start_date')&& isset($this->request->start_date)) {
            $invoice->whereDate('created_at','>=',$this->request->start_date);
        }
        if ($this->request->has('to_date')&& isset($this->request->to_date)) {
            $invoice->whereDate('created_at','<=',$this->request->to_date);
        }
        if ($this->request->has('country_id')&& isset($this->request->country_id)) {
            $country = $this->request->country_id;
            $invoice->whereHas('medical_info',function($query) use ($country){
                $query->whereHas('hospital',function($q) use ($country){
                    $q->where('country',$country);
                });
            });
        }
        if ($this->request->has('search')&& isset($this->request->search)) {
            $search = $this->request->search;
            $search_id = json_decode($search);
            $invoice = $invoice->whereIn('id',$search_id );
        }
        $invoice = $invoice->get();
        return view('admin.reports.summary_detail_excel', [
            'invoice' => $invoice,'country' => $this->request->country_id,'exchange_usd' => $this->exchange_usd,'exchange_thb' => $this->exchange_thb,'exchange_mmk' => $this->exchange_mmk,'exchange_lak' => $this->exchange_lak
        ]);
    }
}
