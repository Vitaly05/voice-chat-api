<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait CustomValidationResponseTrait
{
    protected function failedValidation( Validator $validator )
    {
        throw new HttpResponseException( response()->json(
            [
                'success' => false,
                'messages' => $validator->errors(),
            ],
            422
        ) );
    }
}
