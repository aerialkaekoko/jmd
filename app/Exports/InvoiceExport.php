<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Invoice;
use App\Insurance;
use App\Assistance;

class InvoiceExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($request){
        $this->request = $request;
        $this->user = auth()->user();
    }
    public function view(): View
    {
        if ($this->user->name == 'admin') {
            $invoice_reports = Invoice::where('deleted_at',NULL);
        }else{
            $country_id = $this->user->country;
            $invoice_reports = Invoice::where('deleted_at',NULL)->whereHas('medical_info',function($query) use ($country_id){
                $query->whereHas('hospital',function($q) use ($country_id){
                    $q->where('country',$country_id);
                });
            });
        }

        if ($this->request->has('start_date')&& isset($this->request->start_date)) {
            $invoice_reports->whereDate('created_at','>=',$this->request->start_date);
        }
        if ($this->request->has('to_date')&& isset($this->request->to_date)) {
            $invoice_reports->whereDate('created_at','<=',$this->request->to_date);
        }
        if ($this->request->has('country_id')&& isset($this->request->country_id)) {
            $country = $this->request->country_id;
            $invoice_reports->whereHas('medical_info',function($query) use ($country){
                $query->whereHas('hospital',function($q) use ($country){
                    $q->where('country',$country);
                });
            });
        }
        if ($this->request->has('search')&& isset($this->request->search)) {
            $search = $this->request->search;
            $search_id = json_decode($search);
            $invoice_reports = $invoice_reports->whereIn('id',$search_id );
        }
        $invoice_reports = $invoice_reports->get();
        return view('admin.reports.invoice_reports_excel', [
            'invoices' => $invoice_reports,'country' => $this->request->country_id
        ]);
    }
}
