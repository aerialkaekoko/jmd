@extends('layouts.admin')
@section('styles')
    <style>
        .member-header{
            border-left: 3px solid #27AE60;
            padding-left: 10px;
        }
        h5{
            border-bottom: 1px solid #e3e3e3;
            padding-bottom: 10px;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.medical_informations.title_singular') }}
    </div>
    <div class="card-body">
        <form action="{{ route("admin.medical_informations.update", ['id'=>$medical_information->id,'user_id'=>$user_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="treatment-info">
                <h5 class="mt-4"><span class="member-header">Treatment Info</span></h5>
                <div class="row">
                    <div class="col-md-3">
                        <label for="Last Reference No">{{isset($medical_information)?'Last':''}} Reference No :</label>
                        <input type="text" value="{{isset($medical_information)?$medical_information->ba_ref_no : ''}}" class="form-control" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="User">Patient Name. 患者氏名 :</label>
                            <p class="text-danger">
                                @if($user->gender == "male")
                                    Mr.
                                @else
                                    Ms.
                                @endif
                                {{$user->family_name}} {{$user->name}} 
                            </p>
                            <input type="hidden" name="patient_id" value="{{$user->id}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('date_of_visit') ? 'has-error' : '' }}">
                            <label for="title">Date Of Visit*:</label>
                            <input type="text" id="date_of_visit" name="date_of_visit" class="form-control" value="{{ old('date_of_visit', isset($medical_information->date_of_visit) ? date('Y-m-d',strtotime($medical_information->date_of_visit)) : '') }}" placeholder="Treatment Date">
                            @if($errors->has('date_of_visit'))
                                <p class="help-block">
                                    {{ $errors->first('date_of_visit') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Hospital">Hospital 病院名 *:</label>
                            <select class="form-control select2" id="hospital" name="hospital_id">
                                <option value="" data-patient_no="-">Please Select</option>
                                @foreach ($hospitals as $id=>$item)
                                    <option value="{{$item->id}}" {{(isset($medical_information) && $medical_information->hospital_id == $item->id)?'selected':''}} data-patient_no="{{$user->personalInformation->hospital_patient_no}}" data-country="{{$item->country}}">{{$item->name}}</option>
                                @endforeach
                                {{-- @empty(!$user->personalInformation->hospital_id)
                                    <option value="{{$user->personalInformation->hospital_id}}" data-patient_no="{{$user->personalInformation->hospital_patient_no}}" data-country="{{$user->personalInformation->hospital->country}}" {{  $medical_information->hospital_id == $user->personalInformation->hospital_id ? 'selected':''}}>{{$user->personalInformation->hospital->name}}</option>
                                @endempty
                                @empty(!$user->personalInformation->hospital2_id)
                                    <option value="{{$user->personalInformation->hospital2_id}}" data-patient_no="{{$user->personalInformation->hospital_patient_no}}" data-country="{{$user->personalInformation->hospital2->country}}"  {{  $medical_information->hospital_id == $user->personalInformation->hospital2_id ? 'selected':''}}>{{$user->personalInformation->hospital2->name}}</option>
                                @endempty
                                @empty(!$user->personalInformation->hospital3_id)
                                    <option value="{{$user->personalInformation->hospital3_id}}" data-patient_no="{{$user->personalInformation->hospital_patient_no}}" data-country="{{$user->personalInformation->hospital3->country}}" {{  $medical_information->hospital_id == $user->personalInformation->hospital3_id ? 'selected':''}}>{{$user->personalInformation->hospital3->name}}</option>
                                @endempty --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Hospital">Patient No. 患者番号 :</label>
                            <input type="hidden" name="patient_no" value="{{ old('patient_no', isset($medical_information) ? $medical_information->patient_no : '') }}" id="patient_no">
                            <p id="patient_number">-</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 ipd_opd_div">
                        <div class="form-group">
                            <label for="IPd/Opd">OPD/IPD 外来／入院:</label>
                            <select class="form-control" id="opd_ipd" name="opd_ipd" >
                                <option value="">Please Select</option>
                                <option value="1" {{ (isset($medical_information) ? $medical_information->opd_ipd : old('opd_ipd')) == 1 ? 'selected' : '' }}>IPD</option>
                                <option value="2" {{ (isset($medical_information)? $medical_information->opd_ipd : old('opd_ipd')) == 2 ? 'selected' : '' }}>OPD</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 ipd_div">
                        <div class="form-group">
                            <label for="Hospital">IPD Start Date:</label>
                            <input type="text" class="form-control" name="ipd_start_date" id="ipd_start_date"  value="{{ old('ipd_start_date', isset($medical_information) ? date('Y-m-d',strtotime($medical_information->ipd_start_date)) : '') }}">
                            <span class="error_ipd_start_date text-danger"></span>
                        </div>
                    </div>
                    <div class="col-md-3 ipd_div">
                        <div class="form-group">
                            <label for="Hospital">IPD Finish Date:</label>
                            <input type="text" class="form-control" name="ipd_finish_date" id="ipd_finish_date" value="{{ old('ipd_finish_date', isset($medical_information) ? date('Y-m-d',strtotime($medical_information->ipd_finish_date)) : '') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Hospital">Re-exam 再診:</label>
                            <select class="form-control select2" id="re_exam" name="re_exam" >
                                <option value="1" {{ (isset($medical_information) && $medical_information->re_exam ? $medical_information->re_exam : old('re_exam')) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="2" {{ (isset($medical_information) && $medical_information->re_exam ? $medical_information->re_exam : old('re_exam')) == 2 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Department">Department 診察科:</label>
                            <select class="form-control select2" id="department" name="department_id" >
                                @foreach ($departments as $id=>$department)
                                    <option value="{{$id}}" {{ (isset($medical_information) && $medical_information->department ? $medical_information->department->id : old('department_id')) == $id ? 'selected' : '' }}>{{$department}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Doctor">Doctor 医師:</label>
                            <input type="text" class="form-control" name="doctor_name" id="doctor_name" placeholder="Dr.Jon david"
                                value="{{ old('doctor_name', isset($medical_information) ? $medical_information->doctor_name : '') }}" >
                            {{-- <select class="form-control select2" id="doctor" name="doctor_id">
                                @foreach ($doctors as $id=>$doctor)
                                    <option value="{{$id}}" {{ (isset($medical_information) && $medical_information->doctor ? $medical_information->doctor->id : old('doctor_id')) == $id ? 'selected' : '' }}>{{$doctor}}</option>
                                @endforeach
                            </select> --}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Symptons">Symptoms 症状 :</label>
                            <input type="text" class="form-control" name="symptons" id="symptons" value="{{ old('symptons', isset($medical_information) ? $medical_information->symptons : '') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Disease">Diagnosis 診断名:</label>
                            <select class="form-control select2" id="disease" name="disease_id" >
                                @foreach ($medicals as $id=>$medical)
                                    <option value="{{$id}}" {{ (isset($medical_information) && $medical_information->medical ? $medical_information->medical->id : old('medical_id')) == $id ? 'selected' : '' }}>{{$medical}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="First Visit ">The 1st visit 初診日:</label>
                            <input type="text" class="form-control" name="the_first_visit_date" id="the_first_visit_date"  value="{{ old('the_first_visit_date', isset($medical_information) ? date('Y-m-d',strtotime($medical_information->the_first_visit_date)) : date('Y-m-d')) }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Treatment Status">Treatment States 治療状況</label>
                            <select class="form-control" id="treatment_status" name="treatment_status">
                                <option value="1" {{ (isset($medical_information) && $medical_information->treatment_status ? $medical_information->treatment_status : old('treatment_status')) == 1 ? 'selected' : '' }}>Ongoing</option>
                                <option value="2" {{ (isset($medical_information) && $medical_information->treatment_status ? $medical_information->treatment_status : old('treatment_status')) == 2 ? 'selected' : '' }}>Finished</option>
                            </select>
                        </div>
                    </div>  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Appointment Date ">Appointment Date 予約日:</label>
                            <input type="text" class="form-control" name="appointment_date" id="appointment_date" value="{{ old('appointment_date', isset($medical_information->appointment_date) ? $medical_information->appointment_date : '') }}" >
                        </div>
                    </div>                  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Translator Name ">Translator Name 担当者:</label>
                            <input type="text" class="form-control" name="translator_name" id="translator_name"  value="{{ old('translator_name', isset($medical_information) ? $medical_information->translator_name : '') }}" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('appointment_status') ? 'has-error' : '' }}">
                            <label for="appointment_status">Appointment Status</label>
                            <select id="appointment_status" name="appointment_status" class="form-control" >
                                <option value="">{{ trans('global.pleaseSelect') }}</option>
                                @foreach(trans('cruds.appointment_status') as $key => $label)
                                <option value="{{ $key }}" {{ old( 'appointment_status', $medical_information->appointment_status)===( string)$key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="Sympton">Comments 自由記入欄 :</label>
                        <textarea class="form-control" id="treatment_info_comments" name="treatment_info_comments" rows="2">{{ old('treatment_info_comments', isset($medical_information) ? $medical_information->treatment_info_comments : '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="receipt-info">
                <h5 class="mt-4"><span class="member-header">Receipt & GCL Info</span></h5>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label for="Payment Type">Payment System 支払いシステム :</label>
                        <select name="payment_type" id="payment_type" class="form-control">
                            @foreach(App\UserInsurance::TYPE as $key => $label)
                                <option value="{{$key}}" {{ (isset($medical_information) ? $medical_information->payment_type : old('payment_type')) == $key ? 'selected' : '' }}>{{$label}}</option>
                            @endforeach
                            {{-- @foreach (unserialize($user->userInsurance->type) as $key=>$value)
                                <option value="{{$value}}" {{ (isset($medical_information) ? $medical_information->payment_type : old('payment_type')) == $value ? 'selected' : '' }}>{{App\UserInsurance::TYPE[$value]}}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="Cash Or Credit">Payment Type 支払方法 :</label>
                        <select name="cash_credit" id="cash_credit" class="form-control">
                            <option value="">Choose</option>
                            @foreach (App\MedicalInformation::CASH_CREDIT as $key=>$item)
                            <option value="{{$key}}" {{ (isset($medical_information) ? $medical_information->cash_credit : old('cash_credit')) == $key ? 'selected' : '' }}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 box type-div2">
                        <label for="Membership company">Membership Company Name/会社名 :</label>
                        <select name="membership_id" id="membership" class="form-control">
                            @foreach($memberships as $id => $membership)
                                <option value="{{ $id }}" {{ (isset($user->userInsurance)? $user->userInsurance->membership_id : old('membership_id')) == $id ? 'selected' : '' }}>{{ $membership }}</option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        $type = $medical_information->payment_type;
                    @endphp
                    <div class="col-md-3 box type-div1 type-div3" style="{{($type== 2 || $type == 4)?'display:none' : ''}}">
                        <div class="form-group">
                            <label for="Insurance Company">Insurance company 保険会社 :</label>
                            <select class="form-control" id="insurance_id" name="insurance_id">
                                <option value="">Select </option>
                                @foreach ($insurances as $id=>$item)
                                    <option value="{{$item->id}}" {{(isset($medical_information) && $medical_information->insurance_id == $item->id)?'selected':''}} data-type="1">{{$item->company_name}}</option>
                                @endforeach
                                {{-- @empty(!$user->userInsurance->insurance_id1)
                                    <option value="{{$user->userInsurance->insurance_id1}}" {{  $medical_information->insurance_id == $user->userInsurance->insurance_id1 ? 'selected':''}} data-type="1">{{$user->userInsurance->insurance1->company_name??''}}</option>
                                @endempty
                                @empty(!$user->userInsurance->insurance_id2)
                                    <option value="{{$user->userInsurance->insurance_id2}}" {{  $medical_information->insurance_id == $user->userInsurance->insurance_id2 ? 'selected':''}} data-type="1">{{$user->userInsurance->insurance2->company_name??''}}</option>
                                @endempty
                                @empty(!$user->userInsurance->credit_insurance_company)
                                    <option value="{{$user->userInsurance->credit_insurance_company}}" {{  $medical_information->insurance_id == $user->userInsurance->credit_insurance_company ? 'selected':''}} data-type="3">{{$user->userInsurance->insurance3->company_name??''}}</option>
                                @endempty --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 box type-div1 type-div3" style="{{($type== 2 || $type == 4)?'display:none' : ''}}">
                        <div class="form-group">
                            <label for="Assistance Company">Assistance アシスタンス会社名:</label>
                            <select class="form-control" id="assistance_id" name="assistance_id">
                                <option value="">Select</option>
                                @foreach ($assistances as $id=>$item)
                                    <option value="{{$item->id}}" {{(isset($medical_information) && $medical_information->assistance_id == $item->id)?'selected':''}} data-type="1">{{$item->assistance_name}}</option>
                                @endforeach
                                {{-- @empty(!$user->userInsurance->assistance_id1)
                                    <option value="{{$user->userInsurance->assistance_id1}}" {{  $medical_information->assistance_id == $user->userInsurance->assistance_id1 ? 'selected':''}} data-type="1">{{$user->userInsurance->assistance1->assistance_name??''}}</option>
                                @endempty
                                @empty(!$user->userInsurance->assistance_id2)
                                    <option value="{{$user->userInsurance->assistance_id2}}" {{  $medical_information->assistance_id == $user->userInsurance->assistance_id2 ? 'selected':''}} data-type="1">{{$user->userInsurance->assistance2->assistance_name??''}}</option>
                                @endempty
                                @empty(!$user->userInsurance->credit_assistance_company)
                                    <option value="{{$user->userInsurance->credit_assistance_company}}" {{  $medical_information->assistance_id == $user->userInsurance->credit_assistance_company ? 'selected':''}} data-type="3">{{$user->userInsurance->assistance3->assistance_name??''}}</option>
                                @endempty --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 box type-div4">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="Other Types">Other Types そのほか :</label>
                                <select name="other_type" id="other_type" class="form-control" >
                                    @foreach(App\UserInsurance::OTHER_TYPE as $key => $label)
                                        <option value="{{ $key }}" {{ (isset($user->userInsurance) ? $user->userInsurance->other_type : old('other_type')) == (string)$key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6  box2 otherdiv1">
                                <div class="form-group {{ $errors->has('cash_comment') ? 'has-error' : '' }}">
                                    <label for="Cash Comment">Cash Comments</label>
                                    <textarea id="cash_comments" name="cash_comments" rows="3" class="form-control" placeholder="Enter Comments">{{ old('cash_comments', isset($user->userInsurance) ? $user->userInsurance->cash_comments : '') }}</textarea>
                                    @if($errors->has('cash_comments'))
                                        <p class="help-block">
                                            {{ $errors->first('cash_comments') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6  box2 otherdiv2">
                                <div class="form-group {{ $errors->has('cash_comment') ? 'has-error' : '' }}">
                                    <label for="Corporate Company">Corporate Company</label>
                                    <input type="text" id="corporate_company" name="corporate_company" class="form-control" value="{{ old('corporate_company', isset($user->userInsurance) ? $user->userInsurance->corporate_company : '') }}" placeholder="Enter Company">
                                    @if($errors->has('corporate_company'))
                                        <p class="help-block">
                                            {{ $errors->first('corporate_company') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 box2 otherdiv3">
                                <div class="form-group {{ $errors->has('local_insurance_id') ? 'has-error' : '' }}">
                                    <label for="Membership">Local Insurance Company</label>
                                    <select name="local_insurance_id" id="local_insurance_id" class="form-control select2">
                                        @foreach($localInsurances as $id => $localInsurance)
                                            <option value="{{ $id }}" {{ (isset($user->userInsurance)? $user->userInsurance->local_insurance_id : old('local_insurance_id')) == $id ? 'selected' : '' }}>{{ $localInsurance }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('local_insurance_id'))
                                        <p class="help-block">
                                            {{ $errors->first('local_insurance_id') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 box2 otherdiv5">
                                <div class="form-group {{ $errors->has('other_comment') ? 'has-error' : '' }}">
                                    <label for="Other Comment">Other Comments</label>
                                    <textarea id="other_comments" name="other_comments" rows="3" class="form-control" placeholder="Enter Comments">{{ old('other_comments', isset($user->userInsurance) ? $user->userInsurance->other_comments : '') }}</textarea>
                                    @if($errors->has('other_comments'))
                                        <p class="help-block">
                                            {{ $errors->first('other_comments') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Status Of GCL">Status of GCL 支払保証申請状況</label>
                            <select class="form-control" id="status_of_gcl" name="status_of_gcl">
                                @foreach (App\MedicalInformation::GCL_STATUS as $key=>$item)
                                    <option value="{{$key}}" {{ (isset($medical_information) ? $medical_information->status_of_gcl : old('status_of_gcl')) == $key ? 'selected' : '' }}>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="GCL Case No">GCL Case No 支払保証番号</label>
                            <input type="text" name="gcl_case_no" id="gcl_case_no" class="form-control" value="{{ old('gcl_case_no', isset($medical_information) ? $medical_information->gcl_case_no : '') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="180 days Periods">180 days Periods 支払保証カバー期間:</label>
                            <input type="text" name="period_case" id="period_case" class="form-control" value="{{ old('period_case', isset($medical_information) ? $medical_information->period_case : '') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="Medical Amount">Medical Amount-1 治療金額:</label>
                        <input class="form-control text-right" id="medical_amount" name="medical_amount" value="{{ old('medical_amount', isset($medical_information) ? $medical_information->medical_amount : 0) }}" onkeypress="return isNumberKey(event)"/>
                    </div>
                    <div class="col-md-3">
                        <label for="Medical Amount">Currency Type 通貨の種類:</label>
                        <select name="currency" id="currency" class="form-control">
                            @foreach (App\MedicalInformation::CURRENCY as $key=>$item)
                            <option value="{{$key}}" {{ (isset($medical_information) ? $medical_information->currency : old('currency')) == $key ? 'selected' : '' }}>{{$item}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="Medical Amount">Medical Amount-2 治療金額:</label>
                        <input class="form-control text-right" id="medical_amount2" name="medical_amount2" value="{{ old('medical_amount', isset($medical_information) ? $medical_information->medical_amount2: 0) }}" onkeypress="return isNumberKey(event)"/>
                    </div>
                    <div class="col-md-3">
                        <label for="Medical Amount">Currency Type 通貨の種類:</label>
                        <select  id="currency" class="form-control">
                            @foreach (App\MedicalInformation::CURRENCY as $key=>$item)
                            <option value="{{$key}}" {{ (isset($medical_information) ? $medical_information->currency : old('currency')) == $key ? 'selected' : '' }}>{{$item}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="row kb">
                    <div class="col-md-3">
                        <label for="Medical Amount">KB :</label>
                        <input class="form-control text-right" id="kb" name="kb" value="{{ old('kb', isset($medical_information) ? $medical_information->kb : 0) }}" onkeypress="return isNumberKey(event)"/>
                    </div>
                    <div class="col-md-3">
                        <label for="Medical Amount">Currency Type 通貨の種類:</label>
                        <select  id="currency" class="form-control">
                            @foreach (App\MedicalInformation::CURRENCY as $key=>$item)
                            <option value="{{$key}}" {{ (isset($medical_information) ? $medical_information->currency : old('currency')) == $key ? 'selected' : '' }}>{{$item}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="Sympton">Comments 自由記入欄 :</label>
                        <textarea class="form-control" id="gcl_info_comments" name="gcl_info_comments" rows="2">{{ old('gcl_info_comments', isset($medical_information) ? $medical_information->gcl_info_comments : '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="gad-use">
                <h5 class="mt-4"><span class="member-header">GAD Use</span></h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Translator Name">Translator Name 担当者</label>
                            <input type="text"  id="translator_name_2" value="{{ old('translator_name', isset($medical_information) ? $medical_information->translator_name : '') }}" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Weekday or Weekendday">Weekday/Weekend平日又は週末祝日対応</label>
                            <select class="form-control" id="weekday_end" name="weekday_end">
                                <option value="">-</option>
                                <option value="1" {{ (isset($medical_information) ? $medical_information->weekday_end : old('weekday_end')) == 1 ? 'selected' : '' }}>WD</option>
                                <option value="2" {{ (isset($medical_information) ? $medical_information->weekday_end : old('weekday_end')) == 2 ? 'selected' : '' }}>WE</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Side Response">Side Response 対応形式</label>
                            <select class="form-control" id="side_response" name="side_response">
                                <option value="">-</option>
                                <option value="1" {{ (isset($medical_information) ? $medical_information->side_response : old('side_response')) == 1 ? 'selected' : '' }}>Onsite</option>
                                <option value="2" {{ (isset($medical_information) ? $medical_information->side_response : old('side_response')) == 2 ? 'selected' : '' }}>Phone</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="service col-md-8 my-2" style="">
                        <label>Service Time
                            @if ($medical_information->opd_ipd == 1)
                                <i class="fas text-success fa-plus-circle create-service" style="cursor: pointer;" data-toggle="modal" data-target="#exampleModal"></i>
                            @endif
                        </label>
                        <table class="table">
                          <thead>
                            <tr>
                              <th scope="col">Date</th>
                              <th scope="col">In Time</th>
                              <th scope="col">Out Time</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                           @forelse ($medical_information->services as $service)
                                <tr>
                                  <td>{{$service->service_date??'-'}}</td>
                                  <td>{{$service->intime ?date('h:i A',strtotime($service->intime)):'-'}}</td>
                                  <td>{{$service->outtime ?date('h:i A',strtotime($service->outtime)):'-'}}</td>
                                  <td>
                                        <a class="btn btn-xs btn-warning edit-service" style="cursor: pointer;" data-toggle="modal" data-target="#exampleModal"  data-id="{{$service->id}}" data-service_date="{{$service->service_date??'-'}}" data-intime="{{$service->intime}}" data-outtime="{{$service->outtime}}"><i class="fas fa-edit text-white"></i></a>
                                        @if ($medical_information->opd_ipd == 1)
                                          <a href="/admin/{{$medical_information->patient_id}}/{{$medical_information->id}}/services/{{$service->id}}" class="btn btn-xs btn-danger">
                                                    <i class='fas fa-trash'></i>
                                         </a>
                                        @endif
                                  </td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No Service Time Yet !....</td></tr>
                            @endforelse
                          </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Document to BA Office Date">Document to BA Office Date:</label>
                            <input type="text"  id="document_date" name="document_date" class="form-control" value="{{ old('document_date', isset($medical_information) ? $medical_information->document_date : '') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="Sympton">Comments 自由記入欄:</label>
                        <textarea class="form-control" id="gad_use_comments" name="gad_use_comments" rows="2">{{ old('gad_use_comments', isset($medical_information) ? $medical_information->gad_use_comments : '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="receipt-info">
                <h5 class="mt-4"><span class="member-header">Attached Files</span></h5>
                <div class="row">
                    <div class="col-md-6">
                        <!-- passport info -->
                        <div class="form-group {{ $errors->has('medicalinvoice') ? 'has-error' : '' }}">
                            <label for="medicalinvoice">Medical Attach</label>
                            <div class="needsclick dropzone" id="medicalinvoice-dropzone">
                            </div>
                            @if($errors->has('medicalinvoice'))
                                <p class="help-block">
                                    {{ $errors->first('medicalinvoice') }}
                                </p>
                            @endif
                                <p class="helper-block">
                                    jpg,jpeg
                                </p>
                         </div>
                        <!-- end passport info -->
                    </div>
                    <!--
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('gruntee') ? 'has-error' : '' }}">
                            <label for="gruntee">Medical Certificate 診断書</label>
                            <div class="needsclick dropzone" id="gruntee-dropzone">
                            </div>
                            @if($errors->has('gruntee'))
                                <p class="help-block">
                                    {{ $errors->first('gruntee') }}
                                </p>
                            @endif
                                <p class="helper-block">
                                    jpg,jpeg
                                </p>
                         </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('medicalinfoform') ? 'has-error' : '' }}">
                            <label for="medicalinfoform">Claim Form</label>
                            <div class="needsclick dropzone" id="medicalinfoform-dropzone">
                            </div>
                            @if($errors->has('medicalinfoform'))
                                <p class="help-block">
                                    {{ $errors->first('medicalinfoform') }}
                                </p>
                            @endif
                                <p class="helper-block">
                                    jpg,jpeg
                                </p>
                         </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('gcl') ? 'has-error' : '' }}">
                            <label for="gcl">GCL</label>
                            <div class="needsclick dropzone" id="gcl-dropzone">
                            </div>
                            @if($errors->has('gcl'))
                                <p class="help-block">
                                    {{ $errors->first('gcl') }}
                                </p>
                            @endif
                                <p class="helper-block">
                                    jpg,jpeg
                                </p>
                         </div>
                    </div>
                -->
                </div>
            </div>
            <div class="row">                
                <div class="col-md-6">
                        <a href="{{ URL::previous() }}" class="btn btn-md btn-danger">Cancel</a>
                </div>
                <div class="col-md-6 text-right">
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.update') }}">
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Service Model -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Service Time</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form  method="post" class="service-form">
          @csrf
              <span class="update_method"></span>
            <input type="hidden" class="medical_id" name="id" value="{{$medical_information->id}}">
            <input type="hidden" name="user_id" value="{{$medical_information->patient_id}}">
            <div class="modal-body">
              <label for="From"> Date</label>
              <input type="text" id="service_date" name="service_date" class="form-control" value="">
              <label for="From"> In Time </label>
              <input type="time" id="service_time" name="service_time" class="form-control">
              <label for="To">Out Time</label>
              <input type="time" id="service_outtime" name="service_outtime" class="form-control">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary service-submit" >Service Time</button>
            </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('.create-service').on('click',function(){
                var formAction = "{{route('admin.storeService')}}";
                $('.service-form').attr('action', formAction);
                $('.service-submit').html('Add Service Time');
            });
            $('.edit-service').on('click',function(){
                var id = $(this).data('id');
                var service_date = $(this).data('service_date');
                if (service_date == '-') {
                    $('#service_date').attr('readonly',true)
                }
                var intime = $(this).data('intime');
                var outtime = $(this).data('outtime');
                console.log('intime',intime);
                var formAction = "/admin/services/"+id;
                $('#service_date').val(service_date);
                $('#service_time').val(intime);
                $('#service_outtime').val(outtime);
                $(".update_method").html("<input type='hidden' name='_method' value='PUT'>")
                $('.service-form').attr('action', formAction);
                $('.service-submit').html('Update Service Time');
            });
            $('.checkExchange').on('submit',function () {
            var country_code = $('#hospital').find(':selected').data('country');
            if (country_code) {
                var exchange_amount = 0;
                if (country_code == 1) {
                    exchange_amount = $('#mmk').val();
                }else if(country_code == 2){
                    exchange_amount = $('#thb').val();
                }else{
                    exchange_amount = $('#lak').val();
                }
                if (exchange_amount == 0) {
                    alert('Your Exchange Amount is 0.Please Update Currency');
                    return false;
                }
            }else{ alert('Please Select Hospital');return false;}
            
        });
            $('#date_of_visit').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#the_first_visit_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#treatment_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#appointment_date').datetimepicker({
                format:	'Y-m-d H:i'
            });
            $('#ipd_start_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#ipd_finish_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#document_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#service_date').datepicker({
                dateFormat : 'yy-mm-dd',
            });
            $('#period_case').datepicker({
                dateFormat: 'yy-mm-dd'
            });
            //calculate period day
            // var the_first_visit_date = $('#the_first_visit_date').val();
            // var period_case = moment(the_first_visit_date).add(180, 'days');
            // var day = period_case.format('DD');
            // var month = period_case.format('MM');
            // var year = period_case.format('YYYY');
            // $('#period_case').val(year + '-' + month + '-' + day);
            //end peroid day
            $('#hospital').change(function(){
                $(this).find("option:selected").each(function(){
                    var optionValue = $(this).attr("data-patient_no");
                    $('#patient_number').html(optionValue)
                    $('#patient_no').val(optionValue)
                    var country = $('#hospital').find(':selected').data('country');
                    if (country == 1) {
                        $('.kb').show();
                        $('.ipd_opd_div').hide();
                        $(".ipd_div").hide();
                        $('#opd_ipd').val('');
                        $('#opd_ipd').select2().change();
                    } else {
                        $('.kb').hide();
                        $('.ipd_opd_div').show();
                    }
                    
                });
            }).change();
            $("#opd_ipd").change(function(){
                $(this).find("option:selected").each(function(){
                    var optionValue = $(this).attr("value");
                    if(optionValue == 1){
                        $(".ipd_div").show();
                    } else{
                        $(".ipd_div").hide();
                    }
                });
            }).change();
            $('#translator_name').on('keyup',function(){
                $('#translator_name_2').val($(this).val());
            })
        });
        // $("#insurance_id").children('option:gt(0)').hide();
        // $("#assistance_id").children('option:gt(0)').hide();
        $("#payment_type").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){

                $(".box").not(".type-div" + optionValue).hide();

                $(".type-div" + optionValue).show();

                } else{

                $(".box").hide();

                }
                // if (optionValue == 1) {
                //     var otai_insurance = $(this).data('otai_insurance');
                //     $("#insurance_id").val(otai_insurance).change();
                //     var otai_assistance = $(this).data('otai_assistance');
                //     $("#assistance_id").val(otai_assistance).change();
                // }
                // if (optionValue == 3) {
                //     var credit_insurance = $(this).data('credit_insurance');
                //     $("#insurance_id").val(credit_insurance).change();
                //     var credit_assistance = $(this).data('credit_assistance');
                //     $("#assistance_id").val(credit_assistance).change();
                // }
                if(optionValue == 1 || optionValue == 3){
                    $("#insurance_id").attr('required',true);
                    $("#assistance_id").attr('required',true);
                } else{
                    $("#insurance_id").attr('required',false);
                    $("#assistance_id").attr('required',false);
                }
            });
        }).change();
        $("#other_type").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){

                $(".box2").not(".otherdiv" + optionValue).hide();

                $(".otherdiv" + optionValue).show();

                } else{

                $(".box2").hide();

                }
            });
        }).change();
        function isNumberKey(evt){
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                    return true;
            }
    </script>
    <script>
        var uploadedmedicalinvoiceMap = {}
    Dropzone.options.medicalinvoiceDropzone = {
        url: '{{ route('admin.medical_informations.storeMedia') }}',
        maxFilesize: 5, // MB
        addRemoveLinks: true,
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
          size: 5
        },
        success: function (file, response) {
          $('form').append('<input type="hidden" name="medicalinvoice[]" value="' + response.name + '">')
          uploadedmedicalinvoiceMap[file.name] = response.name
        },
        removedfile: function (file) {
          file.previewElement.remove()
          var name = ''
          if (typeof file.file_name !== 'undefined') {
            name = file.file_name
          } else {
            name = uploadedmedicalinvoiceMap[file.name]
          }
          $('form').find('input[name="medicalinvoice[]"][value="' + name + '"]').remove()
        },
        init: function () {
    @if(isset($medical_information) && $medical_information->medicalinvoice)
              var files =
                {!! json_encode($medical_information->medicalinvoice) !!}
                  for (var i in files) {
                  var file = files[i]
                  this.options.addedfile.call(this, file)
                  file.previewElement.classList.add('dz-complete')
                  $('form').append('<input type="hidden" name="medicalinvoice[]" value="' + file.file_name + '">')
                }
    @endif
        },
         error: function (file, response) {
             if ($.type(response) === 'string') {
                 var message = response //dropzone sends it's own error messages in string
             } else {
                 var message = response.errors.file
             }
             file.previewElement.classList.add('dz-error')
             _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
             _results = []
             for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                 node = _ref[_i]
                 _results.push(node.textContent = message)
             }
    
             return _results
         }
    }
    </script>
    <script>
        var uploadedgrunteeMap = {}
    Dropzone.options.grunteeDropzone = {
        url: '{{ route('admin.medical_informations.storeMedia') }}',
        maxFilesize: 5, // MB
        addRemoveLinks: true,
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
          size: 5
        },
        success: function (file, response) {
          $('form').append('<input type="hidden" name="gruntee[]" value="' + response.name + '">')
          uploadedgrunteeMap[file.name] = response.name
        },
        removedfile: function (file) {
          file.previewElement.remove()
          var name = ''
          if (typeof file.file_name !== 'undefined') {
            name = file.file_name
          } else {
            name = uploadedgrunteeMap[file.name]
          }
          $('form').find('input[name="gruntee[]"][value="' + name + '"]').remove()
        },
        init: function () {
    @if(isset($medical_information) && $medical_information->gruntee)
              var files =
                {!! json_encode($medical_information->gruntee) !!}
                  for (var i in files) {
                  var file = files[i]
                  this.options.addedfile.call(this, file)
                  file.previewElement.classList.add('dz-complete')
                  $('form').append('<input type="hidden" name="gruntee[]" value="' + file.file_name + '">')
                }
    @endif
        },
         error: function (file, response) {
             if ($.type(response) === 'string') {
                 var message = response //dropzone sends it's own error messages in string
             } else {
                 var message = response.errors.file
             }
             file.previewElement.classList.add('dz-error')
             _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
             _results = []
             for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                 node = _ref[_i]
                 _results.push(node.textContent = message)
             }
    
             return _results
         }
    }
    </script>
    <script>
        var uploadedmedicalinfoformMap = {}
        Dropzone.options.medicalinfoformDropzone = {
            url: '{{ route('admin.medical_informations.storeMedia') }}',
            maxFilesize: 6, // MB
            addRemoveLinks: true,
            headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
              size: 6
            },
            success: function (file, response) {
              $('form').append('<input type="hidden" name="medicalinfoform[]" value="' + response.name + '">')
              uploadedmedicalinfoformMap[file.name] = response.name
            },
            removedfile: function (file) {
              file.previewElement.remove()
              var name = ''
              if (typeof file.file_name !== 'undefined') {
                name = file.file_name
              } else {
                name = uploadedmedicalinfoformMap[file.name]
              }
              $('form').find('input[name="medicalinfoform[]"][value="' + name + '"]').remove()
            },
            init: function () {
        @if(isset($medical_information) && $medical_information->medicalinfoform)
                  var files =
                    {!! json_encode($medical_information->medicalinfoform) !!}
                      for (var i in files) {
                      var file = files[i]
                      this.options.addedfile.call(this, file)
                      file.previewElement.classList.add('dz-complete')
                      $('form').append('<input type="hidden" name="medicalinfoform[]" value="' + file.file_name + '">')
                    }
        @endif
            },
             error: function (file, response) {
                 if ($.type(response) === 'string') {
                     var message = response //dropzone sends it's own error messages in string
                 } else {
                     var message = response.errors.file
                 }
                 file.previewElement.classList.add('dz-error')
                 _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                 _results = []
                 for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                     node = _ref[_i]
                     _results.push(node.textContent = message)
                 }
    
                 return _results
             }
        }
        </script>
        <script>
            var uploadedgclMap = {}
            Dropzone.options.gclDropzone = {
                url: '{{ route('admin.medical_informations.storeMedia') }}',
                maxFilesize: 6, // MB
                addRemoveLinks: true,
                headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                params: {
                  size: 6
                },
                success: function (file, response) {
                  $('form').append('<input type="hidden" name="gcl[]" value="' + response.name + '">')
                  uploadedgclMap[file.name] = response.name
                },
                removedfile: function (file) {
                  file.previewElement.remove()
                  var name = ''
                  if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                  } else {
                    name = uploadedgclMap[file.name]
                  }
                  $('form').find('input[name="gcl[]"][value="' + name + '"]').remove()
                },
                init: function () {
            @if(isset($medical_information) && $medical_information->gcl)
                      var files =
                        {!! json_encode($medical_information->gcl) !!}
                          for (var i in files) {
                          var file = files[i]
                          this.options.addedfile.call(this, file)
                          file.previewElement.classList.add('dz-complete')
                          $('form').append('<input type="hidden" name="gcl[]" value="' + file.file_name + '">')
                        }
            @endif
                },
                 error: function (file, response) {
                     if ($.type(response) === 'string') {
                         var message = response //dropzone sends it's own error messages in string
                     } else {
                         var message = response.errors.file
                     }
                     file.previewElement.classList.add('dz-error')
                     _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                     _results = []
                     for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                         node = _ref[_i]
                         _results.push(node.textContent = message)
                     }
        
                     return _results
                 }
            }
            </script>
@endsection