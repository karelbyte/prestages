<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Company;
use App\Models\Invoice;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Invoice_Controller extends Controller
{


    public function prints($id)
    {
        $invoice = Invoice::find($id);
        $client = Clients::find($invoice->idclient);
        $invoice_details = DB::table('tickets')->join('ticketdetails', 'tickets.id', '=', 'ticketdetails.idticket')->where('tickets.idowner',$id)->where('ticketdetails.status',1)
            ->select('tickets.code','ticketdetails.*')->get();
        $company = Company::first();
        $subtotal = 0;
        $iva = 0;
        foreach (  $invoice_details as $det){
            $subtotal +=  ($det->cant * $det->price);
            $iva+= $det->ivaimport;
        }

        $view = view('invoice.print')->with(['invoice' =>  $invoice, 'details' =>  $invoice_details, 'company' => $company, 'client' =>  $client, 'subtotal' => $subtotal, 'iva' => $iva])->render();

        return \PDF::loadHTML($view)->stream('Factura'. str_replace('/', '_', $invoice->numero).'.pdf');
    }


    public function index()
    {
       return view('invoice.index');
    }


    public function lists(Request $request)
    {
        $skip =$request['start'] * $request['take'];
        $clients = DB::table('invoice')->join('clients', 'invoice.idclient', '=', 'clients.id')
            ->join('status', 'invoice.status', '=', 'status.id');
        $filtros = json_decode($request['fillter'], true);
        $order = json_decode($request['order'], true);
        if ( $filtros['name'] !== "" ) $clients->where('name', 'LIKE',  "%".$filtros['name']."%");
        $clients->orderby($order['field'], $order['type']);
        $total =  $clients->select('invoice.id','invoice.numero', 'invoice.referencia', 'invoice.formapago',  'invoice.importe', 'clients.name', 'invoice.created_at', 'status.descrip')->count();
        $list =  $clients->skip($skip)->take($request['take'])->get();
        $result = [
            'total' => $total,
            'data' =>  $list
        ];
        return response()->json($result);
    }

    public function listsh(Request $request)
    {
        $skip =$request['start'] * $request['take'];
        $tickets = DB::table('tickets')->join('clients', 'clients.code', '=','tickets.codeclient')
            ->join('status', 'status.id', '=','tickets.status');
        $filtros = json_decode($request['fillter'], true);
        $order = json_decode($request['order'], true);
        if ( $filtros['name'] !== "" ) $tickets->where('tickets.code', 'LIKE',  "%".$filtros['name']."%");
        $tickets->wherein('tickets.status',  [1, 2, 4]);
        $tickets->orderby($order['field'], $order['type'] );
        $total =  $tickets->selectraw(DB::raw('false as \'check\', tickets.id, tickets.code, tickets.fullimport, tickets.created_at'))->count();
        $list =  $tickets->skip($skip)->take($request['take'])->get();

        $result = [
            'total' => $total,
            'data' =>  $list
        ];
        return response()->json($result);
    }


    public function getsurrogate($surro){
        $data = DB::table('surrogate')->where('descrip', $surro)->first();
        $id = $data->ids;
        $up = $id + 1;
        DB::table('surrogate')->where('descrip', $surro)->update(['ids' => $up]);
        return $id;
    }


    public function surrogate()
    {
        $data = DB::table('surrogate')->where('descrip', 'invoice')->first();
        return response()->json(Carbon::now()->year . '/'. $data->ids);
    }


    public function saveinvoice(Request $request)
    {
        $invo =  new Invoice();
        $invo->numero =Carbon::now()->year . '/'. $this->getsurrogate('invoice');
        $invo->referencia = $request->fac['referencia'];
        $invo->idclient = $request->fac['idclient'];
        $invo->formapago = 'segun ticket';
        $invo->importe = $request->fac['fullimport'];
        $invo->femision = $request->fac['femision'];
        $invo->status = 6;
        $invo->save();

        foreach ($request->fac['tick'] as $dt ){
            DB::table('tickets')
                ->where('id',$dt)
                ->update(['idowner' => $invo->id, 'owner' => 'I'.$request->fac['numero'], 'status' => 9]);
        }
        return response()->json($invo->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        DB::table('invoice')
            ->where('id', $id)
            ->update(['status' => 8]);
        $data = [
            'codigo' => 200,
            'msj' => "Factura cancelada correctamente."
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
