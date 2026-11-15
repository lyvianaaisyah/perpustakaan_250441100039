<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    public function authorize(): bool
    {
        return true;
    }

    //aturan dari store
    public function rules(): array
    {
        $rules = parent::rules();

        foreach ($rules as $key => $rule) {
            $rules[$key] = 'sometimes|' . $rule;
        }

        return $rules;
    }
}