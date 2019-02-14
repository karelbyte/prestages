<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class User_Request extends Request
{
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
            'name' => 'required',
            'email'=> 'required|email|unique:users',
            'nick'=>array('required', 'unique:users','regex:/^[0-9a-zA-Z]+$/', 'min:6'),
            'password' => 'required|min:6|confirmed',
            'password_confirmation'=>'required|min:6',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'email.required' => 'El correo electronico es requerido.',
            'email.email' => 'Formato de correo electronico no válido.',
            'email.unique' => 'El  correo electronico tiene que ser único.',
            'nick.required' => 'Nombre de usuario requerido.',
            'nick.unique' => 'El nombre de usuario tiene que ser único.',
            'nick.regex' => 'Formato de Alias no válido.',
            'nick.min' => 'El Alias debe contener al menos 6 caracteres',
            'password.required' => 'La contraseña es requerida.',
            'password.confirmed' => 'La confirmación de contraseña no coincide',
            'password.min' => 'La contraseña no tiene los caracteres minimos',
            'password_confirmation.min' => 'La confirmación no tiene los caracteres minimos',
            'password_confirmation.required' => 'La confirmación de contraseña es requerida.',
        ];
    }
}
