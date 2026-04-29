<?php

namespace App\Http\Requests\Chat;

use App\Traits\CustomValidationResponseTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StartCallRequest extends FormRequest
{
    use CustomValidationResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules() : array
    {
        return [
            'recipient_id' => 'required|integer|exists:users,id',
        ];
    }
}
