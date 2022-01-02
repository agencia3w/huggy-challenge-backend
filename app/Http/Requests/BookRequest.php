<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        return [
            'title' => isset($this->book->id) ? "required|unique:books,title,{$this->book->id}" : 'required|unique:books,title',
            'genre' => 'required',
            'author' => 'required',
            'year' => 'required|date_format:Y',
            'pages' => 'required|numeric',
            'language' => 'required',
            'edition' => 'required',
            'publisher_name' => 'required',
            'publisher_code' => 'required',
            'publisher_phone' => 'required',
            'isbn' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Título',
            'genre' => 'Gênero',
            'author' => 'Autor',
            'year' => 'Ano da publicação',
            'pages' => 'Páginas',
            'language' => 'Idioma',
            'edition' => 'Edição',
            'publisher_name' => 'Editora',
            'publisher_code' => 'Código da Editora',
            'publisher_phone' => 'Telefone da Editora',
            'isbn' => 'ISBN',
        ];
    }
}
