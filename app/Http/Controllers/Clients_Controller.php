<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Clients_Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('clients.index');
    }

    public function lists(Request $request)
    {
        $skip =$request['start'] * $request['take'];
        $clients = DB::table('clients');
        $filtros = json_decode($request['fillter'], true);
        $order = json_decode($request['order'], true);
        if ( $filtros['name'] !== "" ) $clients->where('name', 'LIKE',  "%".$filtros['name']."%");
        $clients->orderby($order['field'], $order['type'] );
        $total =  $clients->select('id', 'code', 'name', 'email', 'dni_cif', 'default', 'created_at')->count();
        $list =  $clients->skip($skip)->take($request['take'])->get();
        $result = [
            'total' => $total,
            'data' =>  $list
        ];
        return response()->json($result);
    }

    public function get()
    {
        $clients = DB::table('clients');
        $total =  $clients->select('id', 'code', 'name', 'email')->get();
        $result = [
            'data' =>  $total
        ];
        return response()->json($result);
    }


    public function store(Requests\Clients_Request $request)
    {
        $cli = new Clients();
        $cli->code = $request->input('code');
        $cli->name = $request->input('name');
        $cli->email = $request->input('email');
        $cli->note = $request->input('note');
        $cli->dni_cif = $request->input('dni_cif');
        $cli->phone = $request->input('phone');
        $cli->address = $request->input('address');
        $cli->province = $request->input('province');
        $cli->location = $request->input('location');
        $cli->zip = $request->input('zip');
        $cli ->save();
        $data = [
            'codigo' => 200,
            'msj' => "Usuario creado correctamente."
        ];
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Clients::select('*')->where('id', $id)->first();
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\Clients_Update_Request $request, $id)
    {
        if ( is_null( DB::table('clients')->where('name',  $request['name'])->where('id', '<>', $id)->first())){
            $cli = Clients::find($id);
            $cli->code = $request->input('code');
            $cli->name = $request->input('name');
            $cli->email = $request->input('email');
            $cli->note = $request->input('note');
            $cli->dni_cif = $request->input('dni_cif');
            $cli->phone = $request->input('phone');
            $cli->address = $request->input('address');
            $cli->province = $request->input('province');
            $cli->location = $request->input('location');
            $cli->zip = $request->input('zip');
            $cli->save();
            $data = [
                'codigo' => 200,
                'msj' => "Datos actualizados correctamente."
            ];
        }
        else {
            $data = [
                'codigo' => 500,
                'msj' => "El cliente ya existe."
            ];
        }

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Clients::destroy($id);
        $data = [
            'codigo' => 200,
            'msj' => "Cliente eliminado."
        ];
        return response()->json($data);
    }

    public function setdef($id)
    {
        if (!is_null(DB::table('clients')->where('default', '1')->first())){
           $ids =  DB::table('clients')->where('default', '1')->first()->id;
            $cli = Clients::find($ids);
            $cli->default = 0;
            $cli->save();
        }
        $cli = Clients::find($id);
        $cli->default = 1;
        $cli->save();
        $data = [
            'codigo' => 200,
            'msj' => "Cliente actualizado."
        ];
        return response()->json($data);
    }

    public function getdef(Request $request)
    {


        if ($request->input('code') == '') {
            if (is_null(DB::table('clients')->where('default', 1)->first())) {
                $data = [
                    'codigo' => 300,
                    'msj' => "Crea un cliente por defecto!"
                ];
            } else {
                $cli= DB::table('clients')->where('default', 1)->first();

                $data = [
                    'codigo' => 200,
                    'client' => $cli
                ];
            }
        } else {
            $cli = DB::table('clients')->where('code', $request->input('code'))->first();
            if (!is_null($cli)) {
            $data = [
                'codigo' => 200,
                'client' => $cli
            ];} else {
                $data = [
                    'codigo' => 500,
                    'msj' => "No existe un cliente con ese código!"
                ];
            }
        }
        return response()->json($data);

    }
}
