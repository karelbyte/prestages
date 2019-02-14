<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class Clients_Update_Request extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required',
            'name' => 'required',
            'email'=> 'required|email',
            'dni_cif'=> 'required',
            'address'=> 'required',
        ];
    }
    public function messages()
    {
        return [
            'code.required' => 'El codigo es requerido.',
            'name.required' => 'El nombre es requerido.',
            'email.required' => 'El correo electronico es requerido.',
            'email.email' => 'Formato de correo electronico no válido.',
            'email.unique' => 'El  correo electronico tiene que ser único.',
            'dni_cif.required' => 'El DNI / CFI es requerido.',
            'address.required' => 'La direccion es requerida.'
        ];
    }
}
