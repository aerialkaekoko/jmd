<?php
namespace App\Http\Requests;

use App\Expense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('expenses_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
        ];
    }
}

namespace App\Http\Requests;

use Symfony\Component\HttpFoundation\Response;
