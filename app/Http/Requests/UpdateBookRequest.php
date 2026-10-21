<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
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
        $book = $this->route('book');
        $bookId = is_object($book) ? $book->id : $book;

        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:50', Rule::unique('books', 'isbn')->ignore($bookId)],
            'category_id' => ['required', 'exists:categories,id'],
            'publisher' => ['required', 'string', 'max:255'],
            'published_year' => ['required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
