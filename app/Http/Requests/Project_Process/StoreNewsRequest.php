<?php

namespace App\Http\Requests\Project_Process;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class StoreNewsRequest extends FormRequest
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
        return [
            'title' => 'required|string',
            'benefits' => 'required|string',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date',
            'salary' => 'numeric|min:0',
            'job_position' => 'required|string',
            'working_form' => 'required|string',
            'gender' => 'required|string',
            'experience' => 'required|string',
            'workplace' => 'required|string',
            'number_of_recruits'=> 'required|string',
            'requirements' => 'nullable|string',
            'project_id' => 'required|numeric'
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
