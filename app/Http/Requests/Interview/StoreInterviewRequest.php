<?php

namespace App\Http\Requests\Interview;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreInterviewRequest extends FormRequest
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
        $urlRule = 'nullable|numeric';
        if (request('news_id')) {
            $urlRule = 'required|numeric';
        }
        
        return [
            'candidate_id' => 'required|numeric',
            'company_id' => $urlRule,
            'news_id' => $urlRule,
            'record' => 'nullable|string',
            'result' => 'nullable|array',
            'address' => 'nullable|string',
            'form' => 'nullable|string',
            'time' => 'nullable|date'
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
