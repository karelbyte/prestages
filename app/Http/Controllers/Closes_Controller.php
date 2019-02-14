<?php

namespace App\Http\Controllers;

use App\Models\Closes;
use App\Models\MoneyBox;
use App\Models\Settings;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Closes_Controller extends Controller
{


    public function lists(Request $request)
    {
        $skip =$request['start'] * $request['take'];
        $tickets = DB::table('closes')->join('users', 'users.id', '=', 'closes.iduser');
        $filtros = json_decode($request['fillter'], true);
        $order = json_decode($request['order'], true);
        if ( $filtros['name'] !== "" ) $tickets->where('name', 'LIKE',  "%".$filtros['name']."%");
        $tickets->orderby($order['field'], $order['type'] );
        $total =  $tickets->select('closes.*', 'users.nick')->count();
        $list =  $tickets->skip($skip)->take($request['take'])->get();
        $result = [
            'total' => $total,
            'data' =>  $list
        ];
        return response()->json($result);
    }

    public function index()
    {
       return view('closes.index');
    }

    public function openbox(Request $request)
    {
       DB::table('moneybox')->delete();
       $mb = new MoneyBox();
       $mb->money = $request->saldo;
       $mb->save();
       return response()->json('ok',200);
    }

    public function geymonybox(Request $request)
    {
      $mb = MoneyBox::first();
      return response()->json($mb);
    }


    public function getclose(Request $request)
    {
      // $mb = Ticket::where('created_at', '>=', $request->finicio)->details;

      //  $mb = Ticket::whereBetween('created_at', [$request->inicio, $request->final])->details;
      
        $mb= DB::table('tickets')
              ->select('tickets.typepayment','tickets.fullimport', 'tickets.status')
              ->whereBetween('created_at', [$request->get('finicio'),$request->get('ffinal')])
              ->wherein('tickets.status',[1, 4, 9, 10, 5]) 
             //->wherein('tickets.idclose',[0])  // mod. M
              ->get();

             
         $cash = 0;
         $caja = 0;
         $credito = 0;
         $cancel_credit = 0;
         $cancel_cash = 0;
         foreach ($mb as $tic ){
            if (($tic->status != 5) && $tic->typepayment == 'cash') {$cash += abs($tic->fullimport);}
             if ($tic->status != 5 && $tic->typepayment == 'credit') {$credito += abs($tic->fullimport);}
              $caja += abs($tic->fullimport);
             if ( ($tic->status == 10) && ($tic->typepayment == 'credit')) {  $cancel_credit += abs($tic->fullimport); }
              if (($tic->status == 10) && ($tic->typepayment == 'cash')) {  $cancel_cash += abs($tic->fullimport); }
         }
        $data = [
            'cash' => $cash,
            'money_caja' => $cash + $credito,
            'cancel_money' => $cancel_credit +  $cancel_cash,
            'caja_real' => ( $cash + $credito) - ($cancel_credit +  $cancel_cash),
            /*'caja_total' => $saldo + (( $cash + $credito) - ($cancel_credit +  $cancel_cash)),*/
            'credito' => $credito,
            'cant_ticket' => count($mb),
            'cancel_credit' => $cancel_credit,
            'cancel_cash' => $cancel_cash
        ];
        return response()->json($data, 200);

    }

    public function setclose(Request $request)
    {
       
      $close =  new Closes();
      $close->iduser = $request->cierre['iduser'];
      $close->creado = $request->cierre['creado'];
      $close->fecha_inicio = $request->cierre['finicio'];
      $close->fecha_final = $request->cierre['ffinal'];
      $close->inicio_caja = $request->cierre['inicio_caja'];
      $close->total_caja = $request->cierre['caja_real'];
      $close->efectivo = $request->cierre['efectivo'];
      $close->tarjeta = $request->cierre['tarjeta'];
      $close->devolucion_credito = $request->cierre['cancel_credit'];
      $close->devolucion_efectivo = $request->cierre['cancel_cash'];
      $close->cantticket =  $request->cierre['cantticket'];
      $close->save();
        DB::table('tickets')
            ->select('tickets.id')
            ->whereBetween('created_at', [ $request->cierre['finicio'], $request->cierre['ffinal']])
            ->where('tickets.idclose', 0)
            ->update(['idclose' =>  $close->id ]);

            DB::table('tickets')
            ->select('tickets.id')
            ->whereBetween('created_at', [ $request->cierre['finicio'], $request->cierre['ffinal']])
            ->where('tickets.status', 1)
            ->update([ 'status' => 2 ]);
           // return response()->json(['idclose' =>$close->id], 200); (modif miguel 20-03-18)
       /* DB::table('tickets')
            ->select('tickets.id')
            ->whereBetween('created_at', [ $request->cierre['finicio'], $request->cierre['ffinal']])
            ->wherein('tickets.status',[1])
            ->update(['idclose' =>  $close->id, 'status' => 2 ]);*/
                return response()->json(['idclose' =>$close->id], 200);
    }

    public function printcloses($id)
    {
        $configs = array(
            'page_height' => Settings::where('params','ticket_height')->first() !== null ? Settings::where('params','ticket_height')->first()->value : 4,
            'description_length' => Settings::where('params','ticket_description_height')->first() !== null ? Settings::where('params','ticket_description_height')->first()->value : 14,
            'headings_length' =>  Settings::where('params','ticket_headings_height')->first() !== null ? Settings::where('params','ticket_headings_height')->first()->value : 14,
            'text_length' => Settings::where('params','ticket_text_height')->first() !== null ? Settings::where('params','ticket_text_height')->first()->value : 12,
            'copy' => ''
        );

       $close = Closes::find($id);

       return view('closes.close')->with(['close'=>$close, 'config' => $configs])->render();
    }

    public function printcloses_view($id)
    {
        $configs = array(
            'page_height' => Settings::where('params','ticket_height')->first() !== null ? Settings::where('params','ticket_height')->first()->value : 4,
            'description_length' => Settings::where('params','ticket_description_height')->first() !== null ? Settings::where('params','ticket_description_height')->first()->value : 14,
            'headings_length' =>  Settings::where('params','ticket_headings_height')->first() !== null ? Settings::where('params','ticket_headings_height')->first()->value : 14,
            'text_length' => Settings::where('params','ticket_text_height')->first() !== null ? Settings::where('params','ticket_text_height')->first()->value : 12,
            'copy' => ''
        );

        $close = Closes::find($id);

        $view = view('closes.close_view')->with(['close'=>$close, 'config' => $configs])->render();
        return \PDF::loadHTML($view)->stream('CloseVista.pdf');
    }


    public function lastclose()
    {
       $actual = DB::table('closes')
            ->select(DB::raw('MAX(creado) as actual'))->first();
        $ini =  DB::table('tickets')
            ->select(DB::raw('MIN(created_at) as ini'))->wherein('tickets.status',[1, 4, 9, 10, 5])->first();

        return response()->json([$actual, $ini], 200);
    }

    public function pdf(){
        $ini =  DB::table('tickets')
            ->select(DB::raw('MIN(created_at) as ini'))->wherein('tickets.status',[1, 4, 9])->first();

        $mb= DB::table('tickets')
            ->select('tickets.typepayment','tickets.fullimport')
            ->whereBetween('created_at', [$ini->ini , Carbon::now()->toDateTimeString()])
            ->wherein('tickets.status',[1, 4, 9])
            ->get();

        $cash = 0;
        $caja = 0;
        $credito = 0;
        foreach ($mb as $tic ){
            if ($tic->typepayment == 'cash') {$cash += $tic->fullimport;}
            if ($tic->typepayment == 'credit') {$credito += $tic->fullimport;}
            $caja += $tic->fullimport;

        }

        //----------------------
        $close =  new Closes();
        $close->iduser = -1;
        $close->creado =  Carbon::now();
        $close->fecha_inicio = $ini->ini;
        $close->fecha_final =  Carbon::now()->toDateTimeString();
        $close->inicio_caja = number_format(MoneyBox::first()->money, 2, '.', '');
        $close->total_caja = $caja;
        $close->efectivo = $cash;
        $close->tarjeta =  $credito;
        $close->cantticket = count($mb);

        $configs = array(
            'page_height' => Settings::where('params','ticket_height')->first() !== null ? Settings::where('params','ticket_height')->first()->value : 4,
            'description_length' => Settings::where('params','ticket_description_height')->first() !== null ? Settings::where('params','ticket_description_height')->first()->value : 14,
            'headings_length' =>  Settings::where('params','ticket_headings_height')->first() !== null ? Settings::where('params','ticket_headings_height')->first()->value : 14,
            'text_length' => Settings::where('params','ticket_text_height')->first() !== null ? Settings::where('params','ticket_text_height')->first()->value : 12,
            'copy' => ''
        );
       // dd( $configs);
        $pdf = \App::make('dompdf.wrapper');
        $view = view('closes.preview')->with(['close'=>$close, 'config' => $configs])->render();
        $pdf->loadHTML($view);
        return $pdf->stream('Previa de cierre');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {

        $sql = DB::table('tickets')
        ->where('idclose', $id)
        ->whereNotNull('idowner')
        ->update(['idclose' => null, 'status' => 9 ]);

        $sql = DB::table('tickets')
            ->where('idclose', $id)
            ->whereNull('idowner')
            ->update(['idclose' => null, 'status' => 1 ]);

         DB::table('closes')->where('id', $id)->delete();
         $data = [
             'codigo' => 200,
             'msj' => "Cierre cancelado correctamente."
         ];
         return response()->json($data);
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
