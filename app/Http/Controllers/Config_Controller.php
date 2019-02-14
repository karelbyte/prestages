<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Prestashop;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Protechstudio\PrestashopWebService\PrestashopWebService;

class Config_Controller extends Controller
{
     public function index()
    {
        return view('configuration.index');
    }

    public function getprint()
    {
        $set = Settings::all();
        return response()->json($set);
    }

    public function setprint(Request $request)
    {
       foreach ($request->all() as $item){
         $set = Settings::find($item['id']);
         $set->value = $item['value'];
         $set->save();
       }
        $data = [
            'codigo' => 200,
            'msj' => "Datos actualizados correctamente."
        ];
        return response()->json($data);
    }

    public function getprestashop()
    {
        $presta = DB::table('prestashop')->first();
        return response()->json($presta);
    }


    public function setprestashop(Request $request)
    {
        DB::table('prestashop')->delete();
        $datas = json_decode($request['presta'], true);
        $presta = new Prestashop();
        $presta->url = $datas['url'];
        $presta->keycode = $datas['keycode'];
        $presta->save();
        $data = [
            'codigo' => 200,
            'msj' => "Datos actualizados correctamente."
        ];
        return response()->json($data);
    }

    public function testprestashop()
    {
        $dupla = DB::table('prestashop')->first();
        $call = new \PrestaShopWebservice($dupla->url, $dupla->keycode, false);
            try{
                $result = $call->get(array(
                    'resource' => 'categories'
                ));
                $data = [
                    'codigo' => 200,
                    'msj' => "Conexión establecida con éxito."
                ];
                return response()->json($data);
            } catch (\PrestaShopWebserviceException $e){
                $data = [
                    'codigo' => 500,
                    'msj' => $e->getMessage()
                ];
                return response()->json($data);
            }
    }
    
    public function getcurrencypresta()
    {
        $dupla = DB::table('prestashop')->first();
        $service = new \PrestaShopWebservice($dupla->url, $dupla->keycode, false);
        try{

            $results = $service->get(array(
                'resource' => 'configurations',
                'filter[name]' => '['.'PS_CURRENCY_DEFAULT'.']',
                'display' => "[value]"
            ));
            $id_currency = (int)$results->configurations->configuration->value;
            $currency = $service->get(array(
                'resource' => 'currencies',
                'filter[id]' => $id_currency,
                'display' => "full"
            ));
        
            $current  = array(
                'name' => (string)$currency->currencies->currency->name,
                'iso_code' => (string)$currency->currencies->currency->iso_code,
                'sign' => (string)$currency->currencies->currency->sign
            );
            $data = [
                'codigo' => 200,
                'data' => $current
            ];
            return response()->json($data);
        } catch (\PrestaShopWebserviceException $e){
            $data = [
                'codigo' => 500,
                'msj' => $e->getMessage()
            ];
            return response()->json($data);
        }
    }

    public function setcurrency(Request $request)
    {
        DB::table('currency')->delete();
        $datas = json_decode($request['currency'], true);
        $currency = new Currency();
        $currency->name = $datas['name'];
        $currency->iso_code = $datas['iso_code'];
        $currency->sign = $datas['sign'];
        $currency->save();
        $data = [
            'codigo' => 200,
            'msj' => "Datos actualizados correctamente."
        ];
        return response()->json($data);
    }


    public function getcurrency()
    {
        $currency = DB::table('currency')->first();
        return response()->json($currency);
    }


    public function setcompany(Request $request)
    {

        DB::table('company')->delete();
        $datas = json_decode($request['company'], true);
        $company = new Company();
        $company->name = $datas['name'];
        $company->address = $datas['address'];
        $company->cif = $datas['cif'];
        $company->phone = $datas['phone'];
        $company->email = $datas['email'];
        $company->logo = $datas['logo'];
        $company->save();
        $data = [
            'codigo' => 200,
            'msj' => "Datos actualizados correctamente."
        ];
        return response()->json($data);
    }


    public function getcompany()
    {

        $company = DB::table('company')->first();
        $data = [
            'codigo' => 200,
            'msj' => "Datos actualizados correctamente."
        ];
        return response()->json($company);
    }

    public function saveimg(Request $request)
    {
        $file = $request->file('file');
        $nombre = $file->getClientOriginalName();
        \Storage::disk('local')->put( $nombre, \File::get($file));
        return response()->json($nombre);
    }

    public function getpricetap()
    {
      return response()->json( Settings::where('params','tap_price')->first() !== null ? Settings::where('params','tap_price')->select('value')->first()->value : 1);
    }

    public function setpricetap($tap)
    {
        DB::table('settings')
            ->where('params', 'tap_price')
            ->update(['value' => $tap]);
        return response()->json('ok', 200);
     }


}
