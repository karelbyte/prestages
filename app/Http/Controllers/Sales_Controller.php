<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketsDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Sales_Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('sales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function getsurrogate($surro){
      $data = DB::table('surrogate')->where('descrip', $surro)->first();
      $id = $data->ids;
      $up = $id + 1;
      DB::table('surrogate')->where('descrip', $surro)->update(['ids' => $up]);
      return $id;
    }

    public function setsale(Request $request)
    {

      $datas = json_decode($request['sale'], true);
      $sale = new Ticket();
      $sale->code =  Carbon::now()->year . $this->getsurrogate('ticket');
      $sale->codeclient = $datas['codeclient'];
      $sale->fullimport = $datas['fullimport'];
      $sale->received = $datas['received'];
      $sale->change = $datas['change'];
      $sale->typepayment = $datas['typepayment'];
      $sale->status = 1;
      $sale->save();
        $data = [
           // 'codigo' => 200,
            'idticket' => $sale->id,
          //  'msj' => "Datos actualizados correctamente."
        ];
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setsaledetails(Request $request, $id)
    {
     // $datas = json_decode($request['array'], true);
      foreach ($request->array as $det){
         $sale = new TicketsDetails();
           $sale->idticket = $id;
           $sale->iduser = $det['iduser'];
           $sale->ean13 = $det['ean13'];
           $sale->name = $det['name'];
           $sale->cant = $det['amount'];
           $sale->iva = $det['IVArate'];
           $sale->discount = $det['discount'];
           $IVArateporcien = 100 + $det['IVArate'];
           $sale->price = number_format($det['price_base'], 2, '.', ' '); //number_format(($det['price'] / $IVArateporcien) * 100, 2, '.', ' ');
           $sale->ivaimport =  number_format($det['IVAvalue'], 2, '.', ' ');  //number_format($det['price']-($det['price'] / $IVArateporcien) * 100, 2, '.', ' ');
           $sale->stock_id = $det['stock_id'];
           $sale->status = 1;

           /**$menos = ((($det['price'] / $IVArateporcien) * 100) + ($det['price']-($det['price'] / $IVArateporcien) * 100) / 100); quitamos esto porque no hace bien el descuento y pruebo con la linea anterior**/
           $menos =  ((($det['amount']*  $det['price']) + (($det['IVArate'] * ($det['amount'] * $det['price'])) / 100)) * $det['discount']) /100;
           $sale->fullimport =  number_format( $det['fullprice'],2,'.',' '); //number_format(($det['amount']*  $det['price']) - $menos,2,'.',' ') ;
           $sale->save();
       }
        $data = [
            'codigo' => 200,
            'msj' => "Datos actualizados correctamente."
        ];
        return response()->json($data);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
