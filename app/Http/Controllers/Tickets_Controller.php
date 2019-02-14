<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Company;
use App\Models\Settings;
use App\Models\Ticket;
use App\Models\TicketsDetails;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Protechstudio\PrestashopWebService\PrestashopWebService;

class Tickets_Controller extends Controller
{
    private $prestashop;

    public function __construct(PrestashopWebService $prestashop)
    {
        $presta = DB::table('prestashop')->first();
        $this->prestashop = new $prestashop($presta->url, $presta->keycode, false);
        $this->url =  $presta->url;
        $this->keycode = $presta->keycode;

    }

    public function index()
    {
        return view('tickets.index');
    }
    
     public function lists(Request $request)
    {
        $skip =$request['start'] * $request['take'];
        $tickets = DB::table('tickets')->join('clients', 'clients.code', '=','tickets.codeclient')
            ->join('status', 'status.id', '=','tickets.status');
        $filtros = json_decode($request['fillter'], true);
        $order = json_decode($request['order'], true);
        if ( $filtros['name'] !== "" ) $tickets->where('name', 'LIKE',  "%".$filtros['name']."%");
        if ( $filtros['code'] !== "" ) $tickets->where('tickets.code', 'LIKE',  "%".$filtros['code']."%");
       // if ( $filtros['created_at'] !== "" ) $tickets->where('tickets.created_at', 'LIKE',  "%".$filtros['created_at']."%");
        //$tickets->where('tickets.created_at', Carbon::now());
        if ( $filtros['status'] !== "" ) $tickets->where('tickets.status',$filtros['status']);
        if ( $filtros['idclose'] !== "" ) {
            if ($filtros['idclose'] == 0) $tickets->where('tickets.idclose', 0); else  $tickets->where('tickets.idclose', '<>', 0);
        } 
        $f =  Carbon::now();
        $dia =  $f->day;
        $mes =  $f->month;
        $anos =  $f->year;
        if ( $filtros['hoy'] !== "" &&  $filtros['hoy'] == true ) $tickets->whereBetween('tickets.created_at',[Carbon::create($anos, $mes, $dia, 0, 0, 0), Carbon::create($anos, $mes, $dia, 23, 59, 59)]);
        $tickets->orderby($order['field'], $order['type'] );
        $total =  $tickets->select('tickets.id', 'tickets.code', 'tickets.codeclient', 'clients.name', 'tickets.fullimport', 'tickets.created_at', 'status.descrip',  'tickets.typepayment', 'tickets.owner', 'tickets.status', 'tickets.idclose' )->count();
        $list =  $tickets->skip($skip)->take($request['take'])->get();
        $statuslist =  DB::table('status')->get();
    
        $result = [
            'total' => $total,
            'data' =>  $list,
            'statuslist' =>  $statuslist
        ];
        return response()->json($result);
    }

 

    public function get(Request $request)
    {
        $tickets = DB::table('tickets');
        $total =  $tickets->select('tickets.id', 'tickets.code', 'tickets.fullimport', 'tickets.created_at' )->get();
        $result = [
            'data' =>   $total
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
        if ( $filtros['name'] !== "" ) $tickets->where('name', 'LIKE',  "%".$filtros['name']."%");
        if ( $filtros['code'] !== "" ) $tickets->where('tickets.code', 'LIKE',  "%".$filtros['code']."%");
        if ( $filtros['status'] !== "" ) $tickets->where('tickets.status',$filtros['status']);
        //$tickets->wherenotin('tickets.status', [1, 4, 9]);
        if ( $filtros['idate'] !== "" &&  $filtros['fdate'] !== ""  ) {

            $tickets->whereBetween('tickets.created_at',[$filtros['idate'], $filtros['fdate']]);
       
        } 
        $tickets->orderby($order['field'], $order['type'] );
        $total =  $tickets->select('tickets.id', 'tickets.code', 'tickets.codeclient', 'clients.name', 'tickets.fullimport', 'tickets.created_at', 'status.descrip',  'tickets.typepayment', 'tickets.owner', 'tickets.idclose', 'tickets.status'  )->count();
        $list =  $tickets->skip($skip)->take($request['take'])->get();

        $statuslist =  DB::table('status')->get();
                  
        $result = [
            'total' => $total,
            'data' =>  $list,
            'statuslist' =>  $statuslist
        ];
        return response()->json($result);
    }

    public function printTicketAction($id, $mode, $tgift, $copy = ''){
        $ticket = Ticket::find($id);
        $articles = TicketsDetails::where('idticket', $id)->where('status', 1)->get();
        $client = Clients::where('code', $ticket->codeclient)->first();
        $articles_array = array();
        $subtotal = 0;
        $discountotal = 0;
        $total = 0;
        $total_amount = 0;
        $total_tax = 0;
        $taxes = array();
        foreach($articles as $article){
            $subtotal += $article->price  * $article->cant;
            $total += $article->fullimport;
            $total_tax += $article->ivaimport;
            $iva = $article->iva;
            $total_amount +=  $article->cant;
            $discountotal += ((($article->price  * $article->cant) +$article->ivaimport)  * $article->discount) / 100 ;
        array_push( $articles_array, array(
                'units' => $article->cant,
                'name' => $article->name,
                'codebar' => $article->ean13,
                'tax' => $article->iva,
                'tax_import' => number_format($article->ivaimport,2,'.',' '),
                'price' => number_format($article->price + ($article->ivaimport / $article->cant),2,'.',' '),
                'discount' => $article->discount,
                'fullimport' => number_format($article->fullimport,2,'.',' ')
            ));
        }
       $totals = array(
            'subtotal' => number_format($subtotal,2,'.',' '),
            'total_full' => number_format($ticket->fullimport,2,'.',' '),
            'total' => number_format($total,2,'.',' '),
            'tax' => number_format($iva, 2,'.',' '),
            'total_tax' => number_format($total_tax,2,'.',' '),
            'total_amount' => number_format($total_amount,2,'.',' ')
        );
        $company = Company::first();
        $site = array(
            'name' => $company->name,
            'address' => $company->address,
            'contact' => $company->contact,
            'phone' => $company->phone,
            'logo' => $company->logo, // traer el logo
            'cif' => $company->cif,
            'client' => $client,
            'atendido' => auth()->user()->name
        );

        $configs = array(
            'ticket_with' => Settings::where('params','ticket_with')->first() !== null ? Settings::where('params','ticket_with')->first()->value : 4,
            'description_length' => Settings::where('params','ticket_description_height')->first() !== null ? Settings::where('params','ticket_description_height')->first()->value : 14,
            'headings_length' =>  Settings::where('params','ticket_headings_height')->first() !== null ? Settings::where('params','ticket_headings_height')->first()->value : 14,
            'text_length' => Settings::where('params','ticket_text_height')->first() !== null ? Settings::where('params','ticket_text_height')->first()->value : 12,
            'copy' => $copy == 'copy' ? '(COPIA)' : '',
            'mode' => $mode
        );

        return view('tickets.ticket')->with(array('ticket' => $ticket,'articles'=>$articles_array, 'taxes' => $taxes,  'config' => $configs, 'site' => $site, 'totals' => $totals, 'gift' => $tgift))->render();
    }

    public function printTicketActionPDF($id, $mode, $copy = ''){
        $ticket = Ticket::find($id);
        $articles = TicketsDetails::where('idticket', $id)->where('status', 1)->get();
        $client = Clients::where('code', $ticket->codeclient)->first();
        $articles_array = array();
        $subtotal = 0;
        $total = 0;
        $total_amount = 0;
        $total_tax = 0;
        $discountotal = 0;
        $taxes = array();
        foreach($articles as $article){
            $subtotal += $article->price  * $article->cant;
            $total += $article->fullimport;
            $total_tax += $article->ivaimport;
            $iva = $article->iva;
            $total_amount +=  $article->cant;
            $discountotal += ((($article->price  * $article->cant) +$article->ivaimport)  * $article->discount) / 100 ;
            array_push( $articles_array, array(
                'units' => $article->cant,
                'name' => $article->name,
                'codebar' => $article->ean13,
                'tax' => $article->iva,
                'tax_import' => number_format($article->ivaimport,2,'.',' '),
                'price' => number_format($article->price + ($article->ivaimport / $article->cant) ,2,'.',' '),
                'discount' => $article->discount,
                'fullimport' => number_format($article->fullimport,2,'.',' ')
            ));
        }
        $totals = array(
            'subtotal' => number_format($subtotal,2,'.',' '),
            'total_full' => number_format($ticket->fullimport,2,'.',' '),
            'total' => number_format($total,2,'.',' '),
            'tax' => number_format($iva, 2,'.',' '),
            'total_tax' => number_format($total_tax,2,'.',' '),
            'total_amount' => number_format($total_amount,2,'.',' ')
        );
        $company = Company::first();
        $site = array(
            'name' => $company->name,
            'address' => $company->address,
            'contact' => $company->contact,
            'phone' => $company->phone,
            'logo' => $company->logo, // traer el logo
            'cif' => $company->cif,
            'client' => $client,
            'atendido' => auth()->user()->name
        );

        $configs = array(
            'ticket_with' => Settings::where('params','ticket_with')->first() !== null ? Settings::where('params','ticket_with')->first()->value : 4,
            'description_length' => Settings::where('params','ticket_description_height')->first() !== null ? Settings::where('params','ticket_description_height')->first()->value : 14,
            'headings_length' =>  Settings::where('params','ticket_headings_height')->first() !== null ? Settings::where('params','ticket_headings_height')->first()->value : 14,
            'text_length' => Settings::where('params','ticket_text_height')->first() !== null ? Settings::where('params','ticket_text_height')->first()->value : 12,
            'copy' => $copy == 'copy' ? '(COPIA)' : '',
            'mode' => $mode
        );
        $tgift = $ticket->gift;
        $view = view('tickets.ticket_view')->with(array('ticket' => $ticket,'articles'=>$articles_array, 'taxes' => $taxes,  'config' => $configs, 'site' => $site, 'totals' => $totals, 'gift' => $tgift))->render();
        return  \PDF::loadHTML($view)->stream('TicketVista.pdf');

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showdetais($id)
    {
        $td = TicketsDetails::select('id','ean13', 'name', 'cant', 'fullimport', 'stock_id', 'status')->where('idticket', $id)->get();

        return response()->json($td);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chengestatus(Request $request, $id, $status)
    {
        DB::table('tickets')
            ->where('id', $id)
            ->update(['status' => $status, 'fullimport' => $request->import]);
        return response()->json('ok', 200);
    }

    public function canceldetail($id, $iddet){

    }

    function getsurrogate($surro){
        $data = DB::table('surrogate')->where('descrip', $surro)->first();
        $id = $data->ids;
        $up = $id + 1;
        DB::table('surrogate')->where('descrip', $surro)->update(['ids' => $up]);
        return $id;
      }

    public function cancel($id, $status, $type)
    {
        $tick =  DB::table('tickets')
            ->where('id', $id)
            ->select('idowner', 'owner', 'code')->first();

       $ne =  Ticket::
             where('id', $id)
            ->select('code', 'codeclient', 'fullimport', 'received', 'change',
             'typepayment', 'status', 'idclose', 'owner', 'idowner', 'gift')->first();

        $ne->fullimport = $ne->fullimport * -1;
        
        $ne->code = Carbon::now()->year . $this->getsurrogate('ticket');

        $ne->owner = $tick->code;

        $ne->typepayment = $type;

        $ne->status = $status;

        $negativo = Ticket::create($ne->toArray());
  
        TicketsDetails::where('idticket', $id)->update(['idticket' =>  $negativo->id]);

        if ($tick->idowner == null) {
            $details = DB::table('ticketdetails')->where('idticket', $id)
                ->select('ticketdetails.*')->get();
            foreach ($details as $det) {
                if ($det->ean13 != '100' && $det->ean13 = 'VARIOS') {
                    $result = $this->prestashop->get(array(
                        'resource' => 'stock_availables',
                        'id' => $det->stock_id
                    ));

                    $result->stock_available->quantity = (float)$result->stock_available->quantity + $det->cant;
                    DB::table('ticketdetails')->where('id', $det->id)->update(['status' => 0]);
                    $this->prestashop->edit(array(
                        'resource' => 'stock_availables',
                        'id' => $det->stock_id,
                        'putXml' => $result->asXml()
                    ));
                } else {
                    DB::table('ticketdetails')->where('id', $det->id)->update(['status' => 0]);
                }
            }

            DB::table('tickets')
                ->where('id', $id)
                ->update(['status' => 5, 'owner' => $ne->code]);


            return response()->json($negativo->id, 200);

        } else {
            return response()->json('El ticket tiene referencia de factura '.$tick->owner. ' no se puede cancelar. '  , 500);
        }
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
