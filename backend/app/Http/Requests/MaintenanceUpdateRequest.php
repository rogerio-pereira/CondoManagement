<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class MaintenanceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(Auth::user()->role == 'Admin')
            return [
                // 'id' => 'required',
                // 'problem' => 'exclude_if:id',
                // 'tenant_id' => 'exclude_if:id',
                // 'apartment_id' => 'exclude_if:id',

                'maintenance_user_id' => 'nullable|required_if:solved,true|numeric|exists:users,id',
                'solution' => 'nullable|required_if:solved,true',
                'solved' => 'nullable|boolean',
            ];
        else if(Auth::user()->role == 'Maintenance')
            return [
                // 'id' => 'required',
                // 'problem' => 'exclude_if:id',
                // 'tenant_id' => 'exclude_if:id',
                // 'apartment_id' => 'exclude_if:id',
                //'maintenance_user_id' => 'nullable|required_if:solved,true|numeric|exists:users,id',
                
                'solution' => 'nullable|required_if:solved,true',
                'solved' => 'nullable|boolean',
            ];
        else
            return [
                'solution' => 'nullable|required_if:solved,true',
                'solved' => 'nullable|boolean',
            ];
    }
    
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors], 422));
    }
}
