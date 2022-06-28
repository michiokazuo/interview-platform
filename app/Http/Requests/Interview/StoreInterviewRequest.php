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
            'record' => 'nullable',
            'result' => 'nullable|array',
            'address' => 'nullable|string',
            'form' => 'nullable|in:Online,Offline',
            'time' => 'nullable|date',
            'room' => 'nullable|string',
            'interview_meeting' => 'nullable|boolean',
            'interview_test' => 'nullable|boolean',
            
            // for create test
            'interview_questions' => 'nullable|array',
            'interview_questions_tags' => 'nullable|array',
            'number_of_questions' => 'nullable|numeric',
            'title' => 'nullable|string',
            'topics' => 'nullable|string',
            'edit_questions' => 'nullable|array',
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
