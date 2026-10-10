<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DirectIssueLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isLibrarian();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'book_copy_id' => ['required', 'exists:book_copies,id'],
        ];
    }
}
