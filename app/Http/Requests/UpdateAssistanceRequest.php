<?php

namespace App\Http\Requests;

use App\Assistance;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateAssistanceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('assistance_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'assistance_name' => [
                'required',
            ],
            'insurance_id' => [
                'required',
            ],
        ];
    }
}