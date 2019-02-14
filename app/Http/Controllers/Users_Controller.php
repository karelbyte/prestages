<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Users_Controller extends Controller
{
    /**
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::select('id', 'name', 'nick', 'code', 'email', 'lastlogin', 'active')->where('id', $id)->first();
        return response()->json($user);
    }

    public function codes()
    {
        $user = User::select('id', 'code')->get();
        return response()->json($user);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\User_Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->nick = $request->input('nick');
        $user->code = $request->input('code');
        $user->email = $request->input('email');
        $user->password = bcrypt($request['password']);
        $user->active =  $request->input('active');
        $fecha = new Carbon();
        $user->lastlogin =  $fecha->now();
        $user ->save();
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
    public function lists(Request $request)
    {
        $skip =$request['start'] * $request['take'];
        $users = DB::table('users');
        $filtros = json_decode($request['fillter'], true);
        $order = json_decode($request['order'], true);
        if ( $filtros['name'] !== "" ) $users->where('name', 'LIKE',  "%".$filtros['name']."%");
        $users->orderby($order['field'], $order['type'] );
        $total =  $users->select('id', 'name', 'email', 'lastlogin', 'nick')->count();
        $list =  $users->skip($skip)->take($request['take'])->get();
        $result = [
            'total' => $total,
            'data' =>  $list
        ];
        return response()->json($result);
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
    public function update(Requests\User_Update_Request $request, $id)
    {
       if ( is_null( DB::table('users')->where('nick',  $request['nick'])->where('id', '<>', $id)->first())){
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->nick = $request->input('nick');
            $user->code = $request->input('code');
            $user->email = $request->input('email');
            $user->active =  $request->input('active');
            if ($request['password'] != "") $user->password = bcrypt($request['password']);
            $user->save();
            $data = [
                'codigo' => 200,
                'msj' => "Datos actualizados correctamente."
            ];
        }
        else {
            $data = [
                'codigo' => 500,
                'msj' => "El usuario ya existe."
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
        User::destroy($id);
        $data = [
            'codigo' => 200,
            'msj' => "Usuario eliminado."
        ];
        return response()->json($data);
    }
}
