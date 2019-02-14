<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class User_Update_Request extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email'=> 'required|email',
            'nick'=>array('required','regex:/^[0-9a-zA-Z]+$/', 'min:6'),
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'email.required' => 'El correo electronico es requerido.',
            'email.email' => 'Formato de correo electronico no válido.',
            'nick.required' => 'Nombre de usuario requerido.',
            'nick.regex' => 'Formato de Alias no válido.',
            'nick.min' => 'El Alias debe contener al menos 6 caracteres',
        ];
    }
}
