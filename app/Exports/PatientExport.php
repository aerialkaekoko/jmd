<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Invoice;
use App\MedicalInformation;

class PatientExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
     public function __construct($request,$exchange){
        $this->request = $request;
        $this->user = auth()->user();
        $this->exchange = $exchange;

    }
    public function view(): View
    {
        // dd($this->request->search);
        if ($this->user->name == 'admin') {
            $patient_reports = MedicalInformation::where('deleted_at',NULL);
        }else{
            $patient_reports = MedicalInformation::where('deleted_at',NULL);
        }
        if ($this->request->has('start_date')&& isset($this->request->start_date)) {
            $patient_reports->whereDate('date_of_visit','>=',$this->request->start_date);
        }
        if ($this->request->has('to_date')&& isset($this->request->start_date)) {
            $patient_reports->whereDate('date_of_visit','<=',$this->request->to_date);
        }
        if ($this->request->has('country_id') && isset($this->request->country_id)) {
            $country = $this->request->country_id;
            $patient_reports->whereHas('hospital',function($query) use ($country){
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
            $patient_reports->whereHas('hospital',function($query) use ($code){
                $query->where('country_code',$code);
            });
        }
        if($this->request->has('search') && isset($this->request->search)){
            $search = $this->request->search;
            $search_id = json_decode($search);
            $patient_reports = $patient_reports->whereIn('id',$search_id );
        }
        $patient_reports = $patient_reports->get();
        return view('admin.reports.patient_reports_excel', [
            'patient' => $patient_reports,'country' => $this->request->country_id,'desk' => $this->request->desk_id,'exchange' => $this->exchange
        ]);
    
  }
}
