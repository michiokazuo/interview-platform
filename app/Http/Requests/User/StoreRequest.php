<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $urlRule = 'string';
        if(request('role_name') === 'ROLE_COMPANY') {
            $urlRule = 'required|url';
        }
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:10|unique:users',
            'address' => 'required|string',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'introduction' => 'nullable|string',
            'social_networks' => 'nullable|array',
            'major' => 'required|string',
            'role_id' => 'required|numeric',
            'role_name' => 'required|in:ROLE_CANDIDATE,ROLE_COMPANY',
            'url' => $urlRule,
        ];
    }

    public function messages(): array
    {
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
