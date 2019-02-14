<?php

namespace App\Http\Controllers;


use App\Models\Cache;
use App\Models\Prestashop;
use App\Models\TicketsDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Protechstudio\PrestashopWebService\PrestashopWebService;

class Products_Controller extends Controller
{
   private $prestashop;

    public function __construct(PrestashopWebService $prestashop)
    {
        $presta = DB::table('prestashop')->first();
        $this->prestashop = new $prestashop($presta->url, $presta->keycode, false);
        $this->url =  $presta->url;
        $this->keycode = $presta->keycode;

     }


    public function getSpecificPrices($product_id){
        $service = $this->prestashop;
        try{

            $resource = $service->get(array(
                'resource' => 'specific_prices',
                'filter[id_product]' =>$product_id,
                'display' => 'full'
            ));
            $result = array();
            if ($resource->specific_prices->specific_price->count() > 0){
                for ($i=0; $i < $resource->specific_prices->specific_price->count() ; $i++){
                    $specific_price = $resource->specific_prices->specific_price[$i];
                    if ((integer)$specific_price->id_product == $product_id){
                        $from = (string)$specific_price->from;
                        $to = (string)$specific_price->to;
                        $active = false;
                        if ($to == '0000-00-00 00:00:00' && $from == '0000-00-00 00:00:00'){
                            $active = true;
                        }
                        if ($active == false && $from == '0000-00-00 00:00:00' & $to != '0000-00-00 00:00:00'){
                            $to_stamp = $this->mktimeFromDate($to);
                            if ($to_stamp <= time()){
                                $active = true;
                            }
                        }
                        if ($active == false && $from != '0000-00-00 00:00:00' & $to == '0000-00-00 00:00:00'){
                            $from_stamp = $this->mktimeFromDate($from);
                            if (time() >= $from_stamp){
                                $active = true;
                            }
                        }
                        if ($active == false && $from != '0000-00-00 00:00:00' & $to != '0000-00-00 00:00:00'){
                            $from_stamp = $this->mktimeFromDate($from);
                            $to_stamp = $this->mktimeFromDate($to);
                            if (time() >= $from_stamp && time() <= $to_stamp){
                                $active = true;
                            }
                        }
                        if ($active == true){
                            if ((integer)$specific_price->id_group != 0){
                                $active = false;
                            }

                            if ($active == true){
                                $result[] = array(
                                    'reduction_type' => (string)$specific_price->reduction_type,
                                    'reduction' => (float)$specific_price->reduction,
                                    'from_quantity' => (integer) $specific_price->from_quantity,
                                    'price' => (float)$specific_price->price
                                );
                            }
                        }
                    }
                }
                return $result;
            } else {
                return null;
            }
        }catch(\PrestaShopWebserviceException $e){
            throw $e;
        }
    }

    public function mktimeFromDate($date){
        // 0000-00-00 00:00:00
        //split date from time
        $parts = explode(' ',$date);
        // split date into parts
        $date_parts = explode('-',$parts[0]);
        $time_parts = explode(':',$parts[1]);
        return mktime($time_parts[0],$time_parts[1],$time_parts[2],$date_parts[1],$date_parts[2],$date_parts[0]);
    }

    private function getProductStocks($product_id, $attribute_id){
        $service = $this->prestashop;
        try{
            $stocks = $service->get(array(
                'resource' => 'stock_availables',
                'filter[id_product]' => '['.$product_id.']',
                'filter[id_product_attribute]' => '['.$attribute_id.']',
                'display' => '[id, quantity]'
            ));
            if (count ($stocks->stock_availables->stock_available) > 0){
                $result['stock_id'] = (float)$stocks->stock_availables->stock_available->id;
                $result['in_stock'] = (float)$stocks->stock_availables->stock_available->quantity;
                return $result;
            } else {
                return null;
            }
        } catch (\PrestaShopWebserviceException $e){
            return 'error';
        }
    }

    private function getCategory($category_id){
        if ($category_id != null){
            $service = $this->prestashop;
            try{
                $resource = $service->get(array(
                    'resource' => 'categories',
                    'id' => $category_id
                ));
                return (string)$resource->category->name->language;
            }catch(\PrestaShopWebserviceException $e){
                return 'Error al obtener';
            }
        } else {
            return null;
        }
    }

    public function calcPriceAfterDiscount($price, $specificprice){
        if ($specificprice != null){
            switch($specificprice['reduction_type']){
                case 'amount':
                    return ($price + $specificprice['reduction']);
                    break;
                case 'percentage':
                    return $price - ($price * $specificprice['reduction']);
                    break;
            }
        } else {
            return $price;
        }
    }

    private function calcPriceWithoutDiscount($price, $specificprice){
        if ($specificprice != null){
            $specificprice = array_filter($specificprice,function($value){
                if ($value['from_quantity'] == 1){
                    return $value;
                } else {
                    return null;
                }
            });
        }
        if ($specificprice != null){
            $specificprice = array_pop($specificprice);
            if ($specificprice['from_quantity'] == 1){
                switch($specificprice['reduction_type']){
                    case 'amount':
                        return ($price + $specificprice['reduction']);
                        break;
                    case 'percentage':
                        return $price / (1 - $specificprice['reduction']);
                        break;
                }
            } else {
                return $price;
            }
        } else {
            return $price;
        }
    }

    public function getProductTax($tax_rule_group){
        if ($tax_rule_group != null){
            $service = $this->prestashop;
            try{
                $tax_rule = $service->get(array(
                    'resource' => 'tax_rules',
                    'filter[id_tax_rules_group]' => $tax_rule_group,
                    'display' => '[id_tax]'
                ));
                $tax_id = (integer)$tax_rule->tax_rules->tax_rule->id_tax;
                $tax = $service->get(array(
                    'resource' => 'taxes/'.$tax_id
                ));
                $result = array(
                    'name' => (string)$tax->tax->name->language,
                    'rate' => (float)$tax->tax->rate
                );
                return $result;
            } catch(\PrestaShopWebserviceException $e){
                return null;
            }
        } else {
            return null;
        }
    }


    protected  function setcache($entidad){
            $cache =  new Cache();
            $cache->id =  $entidad['id'];
            $cache->comb_id =  $entidad['comb_id'];
            $cache->codebar = $entidad['ean13'];
            $cache->upc =  $entidad['upc'];
            $cache->ean13 =  $entidad['ean13'];
            $cache->name =  $entidad['name'];
            $cache->description =  $entidad['description'];
            $cache->price =  $entidad['price'];
            $cache->reference =  $entidad['reference'];
            $cache->amount =  $entidad['amount'];
            $cache->discount=  $entidad['discount'];
            $cache->full_price =  $entidad['full_price'];
            $cache->unit_price =  $entidad['unit_price'];
            $cache->tax =  $entidad['tax']['rate'];
            $cache->specific_price =  0.0; // $entidad['specific_price'];
            //$cache->wholesale_price =  $entidad['wholesale_price'];
            $cache->category =  $entidad['category'];
            $cache->attribute_id =  $entidad['attribute_id'];
            $cache->min_stock =  $entidad['min_stock'];
            $cache->min_order =  $entidad['min_order'];
            $cache->order_amount = 1; //$entidad['order_amount'];
            // $cache->order_received =  $entidad['order_received'];
            $cache->stock_id =  $entidad['stock']['stock_id'];
            $cache->in_stock =  $entidad['stock']['in_stock'];
            $cache->save();
    }

    public function buildcache(){
        // productos
        $service = $this->prestashop;
        $display_fields = '[id, name, upc, ean13, description_short, price, wholesale_price, reference, id_tax_rules_group, id_default_combination, id_default_image, id_category_default]';
        $dupla = DB::table('prestashop')->first();
        $ss = substr($dupla->url, 4, 5);
        if ($ss == ':') {
            $ss = 'http://';
            $url = $ss.$dupla->keycode.'@'. substr($dupla->url, 0).'/api';
        } else {
            $ss='https://';
            $url = $ss.$dupla->keycode.'@'. substr($dupla->url, 0).'/api';
        }

         $response_products = Curl::to($url.'/products?output_format=JSON')->get();

           $product = [];
           $jArr = json_decode($response_products, true);


           foreach ($jArr['products'] as $p) {
               $exis = DB::table('cache')->where('id', $p['id'])->where('comb_id', null)->first();
                if ($exis == null ) {
                   $xml= $service->get(array(
                       'resource' => 'products',
                       'filter[id]' => '['.$p['id'].']',
                       'display'  => $display_fields
                   ));
                   $data = $this->buildProductFromXml($xml, '');
                   $this->setcache($data);
               } else
               {
                   $datecache = strtotime($exis->updated_at);
                   if (((Carbon::now()->getTimestamp() - $datecache) / 3600) > 3) {
                      DB::table('cache')->where('id', $p['id'])->where('comb_id', null)->delete();
                      $xml= $service->get(array(
                          'resource' => 'products',
                          'filter[id]' => '['.$p['id'].']',
                          'display'  => $display_fields
                      ));
                      $data = $this->buildProductFromXml($xml, '');
                      $this->setcache($data);
                  }
               }

           };

        $combinations = [];
        $response_combinations = Curl::to($url . '/combinations?output_format=JSON')->get();

        if  ($response_combinations == "[]") {
            return response()->json('Cache de productos actualizada con exito', 200);
        }
        $comi = json_decode($response_combinations, true);

        foreach ($comi['combinations'] as $p) {
            $exis = DB::table('cache')->where('comb_id', $p['id'])->first();
            if ($exis == null ) {
            $xml= $service->get(array(
                'resource' => 'combinations',
                'filter[id]' => '['.$p['id'].']',
                'display' => 'full'
            ));
            $data = $this->buildProductFromCombinationXml($xml, $xml->combinations->combination->ean13);
            $this->setcache($data);
            } else
            {
                $datecache = strtotime($exis->updated_at);
                if (((Carbon::now()->getTimestamp() - $datecache) / 3600) > 3) {
                    DB::table('cache')->where('comb_id', $p['id'])->delete();
                    $xml= $service->get(array(
                        'resource' => 'combinations',
                        'filter[id]' => '['.$p['id'].']',
                        'display' => 'full'
                    ));
                    $data = $this->buildProductFromCombinationXml($xml, $xml->combinations->combination->ean13);
                    $this->setcache($data);
                }
            }
        };
        return response()->json('Cache de productos actualizada con exito', 200);
    }

    private function buildProductFromXml($xml, $codebar, $index = 0){
        $results = array();
        foreach($xml->products->product as $product){
            $specific_price = $this->getSpecificPrices((integer)$product->id);
            $price = $this->calcPriceWithoutDiscount((float)$product->price, $specific_price);
            $category_id = (integer)$product->id_category_default;
            if ($codebar == ''){
                if ((string)$product->ean13 != ''){
                    $codebar = (string)$product->ean13;
                } else {
                    $codebar = (string)$product->upc;
                }
            }

            $result = array(
                'type' => 'product',
                'id' => (integer)$product->id,
                'comb_id' => null,
                'codebar' => $codebar,
                'upc' => (string)$product->upc,
                'ean13' => (string)$product->ean13,
                'name' => (string)$product->name->language,
                'description' => (string)$product->description_short->language,
                'price' => number_format($price, 2, '.', ''),
                'reference' => (string)$product->reference,
                'amount' => 1,
                'discount' => 0,
                'full_price' => number_format($price, 2, '.', ''),
                'unit_price' => number_format($price, 2, '.', ''),
                'tax' => $this->getProductTax((integer)$product->id_tax_rules_group),
                'specific_price' => $specific_price,
                'wholesale_price' => (float)$product->wholesale_price,
                'category' => $this->getCategory($category_id),
                'attribute_id' => (integer)$product->id_default_combination,
                'min_stock' => (integer)$product->stock_min,
                'min_order' => (integer)$product->order_min,
                'order_amount' => 1,
                'order_received' => 0
            );

            $codebar = '';
            $default_combination_id = (float)$product->id_default_combination;
            if ($default_combination_id != 0){
                $service = $this->prestashop;
                try{
                    $combination_resource = $service->get(array(
                        'resource' => 'combinations',
                        'id' => $default_combination_id
                    ));
                    if ($combination_resource->combination->associations->product_option_values->count() > 0){
                        foreach($combination_resource->combination->associations->product_option_values->product_option_value as $option){
                            $option_resource = $service->get(array(
                                'resource' => 'product_option_values',
                                'id' => (integer)$option->id
                            ));
                            $result['name'] .= ' '.(string)$option_resource->product_option_value->name->language.' ';
                        }
                    }
                }catch (\PrestaShopWebserviceException $e){
                    return array('status' => 'error', 'message' => 'Hubo un error en el servicio web');
                }
            }
            $stocks = $this->getProductStocks($result['id'], $default_combination_id);
            $result['stock'] = $stocks;
            $results[] = $result;
        }
        return $results[0];
    }

    function callproduct($codebar) {

        $result = $this->getproduct_simple($codebar);
      
        if ($result == null) $result = $this->getproduct_combination($codebar);

        $data = [
                   'codigo' => 201,
                   'result' => $result,
                    'msj' => $result == null ? 'No existe el producto!' : ''
               ];
       
       return response()->json($data) ;
    }

    
      function getcombinationfind($id_product) {

        $service = $this->prestashop;
        //-----------------------------------------------------------------------------------------------
        $combination_prestashop_get = array(       // Pedimos combinacion a prestashop           1 LLAMDA A LA API
            'resource' => 'combinations',
            'display' => 'full',
            'filter[id_product]' => '['.$id_product.']',
            'output_format' =>'JSON'
        );

       $result = $service->get($combination_prestashop_get); // Recatamos la combinaciones 

       $listcombi = []; 
       $opsaux = [];
     
       foreach ( $result->combinations->combination as $com ) {
        $ops = [];  
        foreach ( $com->associations->product_option_values->product_option_value as $op ) {
    
             array_push($ops, (integer) $op->id);
             array_push($opsaux, (integer) $op->id);
         }     

         array_push($listcombi, ['idcomb' => (integer) $com->id, 'option' => $ops, 'stock' => 0]);
       } 

       $listu = array_unique($opsaux);

       $name_id = [];
       
       foreach ($listu as $op ) {
          
         $option_resource = $service->get(array(
             'resource' => 'product_option_values',
             'id' => (integer) $op
         ));
         
         $talla = ' '.(string ) $option_resource->product_option_value->name->language.' ';
         
         array_push($name_id, ['id' => (integer) $op, 'name' => $talla ]);
         
       }  
     
       return response()->json(['comb' => $listcombi, 'option' => $name_id], 200);
       
    }

    public function getstock($idpro, $idcom) {

        $service = $this->prestashop;

        if ($idpro > 0 && $idcom > 0) {

            $stock_get = $service->get(array(
                'resource' => 'stock_availables',
                'filter[id_product]' => '['.  $idpro .']',
                'filter[id_product_attribute]' => '['.$idcom.']',
                'display' => '[id, quantity]'
            ));

        }  else {
                     
                $stock_get = $service->get(array(
                    'resource' => 'stock_availables',
                    'display' => '[id, quantity]',
                    'filter[id_product]' => '['. $idpro .']'
                ));
           }
     

        return  (integer) $stock_get->stock_availables->stock_available->quantity > 0 ?  (integer) $stock_get->stock_availables->stock_available->quantity : 'Ninguno';
    }

    public function get_combination_for_id($id) {
       
        $service = $this->prestashop;
        //-----------------------------------------------------------------------------------------------
        $combination_prestashop_get = array(       // Pedimos combinacion a prestashop           1 LLAMDA A LA API
            'resource' => 'combinations',
            'display' => 'full',
            'filter[id]' => '['. $id.']',
            'output_format' =>'JSON'
        );
        $combi =  $service->get($combination_prestashop_get); // Recatamos la combinacion 

        if (empty($combi->combinations->combination)) return null;  

        $product_prestashop_get = array(       // Pedimos el producto a prestashop           2 LLAMDA A LA API
            'resource' => 'products',
            //'display' => 'full',
            'display' => '[id, name, id_default_combination, ean13, price, id_tax_rules_group, id_default_image, reference, ean13, price, quantity_discount]',
            'filter[id]' => '['. $combi->combinations->combination->id_product .']',
            'output_format' =>'JSON'
        );

        $product_get =  $service->get($product_prestashop_get); // Recatamos el producto

        $product = $product_get->products->product;
         //-----------------------------------------------------------------------------------------------
        $tax_prestashop_get = array(   // Pedimos los tax del producto            2 LLAMDA A LA API
            'resource' => 'taxes',
            //'display' => 'full',
            'display' => '[rate]',
            'filter[id]' => '['. $product->id_tax_rules_group .']',
            'output_format' =>'JSON'
        );

        $tax_get = $service->get($tax_prestashop_get); // Recatamos el tax

        $tax = $tax_get->taxes->tax->rate == null  ? 21 : (float) $tax_get->taxes->tax->rate;  // Tax del producto

        

        $stock_get = $service->get(array(
            'resource' => 'stock_availables',
            'filter[id_product]' => '['.  $product->id .']',
            'filter[id_product_attribute]' => '['.$combi->combinations->combination->id .']',
            'display' => '[id, quantity]'
        ));

      $name = '';  
        foreach ( $combi->combinations->combination->associations->product_option_values->product_option_value as $op ) {
           // return   $op;
            $option_resource = $service->get(array(
                'resource' => 'product_option_values',
                'id' => (integer) $op->id
            ));
            
            $name .= ' '.(string ) $option_resource->product_option_value->name->language.' ';
        } 

        $resul = [
            'id' => (integer)$product->id,
            'name' =>(string)$product->name->language .  $name,
            'price' => number_format((double)$product->price, 2, '.', '') + number_format((double)$combi->combinations->combination->price, 2, '.', ''),            'ivarate' => (double)$tax,
            'discount' => (double)$product->quantity_discount,
            'ean13' => (string) $product->ean13,
            'amount' => 1,
            'stock' => [
              'in_stock' => (integer) $stock_get->stock_availables->stock_available->quantity,
              'stock_id' => (integer) $stock_get->stock_availables->stock_available->id 
              ],      
             'tax' => [
              //  'name' => (string) $tax_get->taxes->tax->name->language, ///  28-02-2018 Aqui. ultimo
                'rate' => $tax 
             ] 
        ];

        return  $resul;
       }

    public function getproduct_combination_forid($id) {
       
        $service = $this->prestashop;
        //-----------------------------------------------------------------------------------------------
        $combination_prestashop_get = array(       // Pedimos combinacion a prestashop           1 LLAMDA A LA API
            'resource' => 'combinations',
            'display' => 'full',
            'filter[id]' => '['. $id.']',
            'output_format' =>'JSON'
        );
        $combi =  $service->get($combination_prestashop_get); // Recatamos la combinacion 

        if (empty($combi->combinations->combination)) return null;  

        $product_prestashop_get = array(       // Pedimos el producto a prestashop           2 LLAMDA A LA API
            'resource' => 'products',
            //'display' => 'full',
            'display' => '[id, name, id_default_combination, ean13, price, id_tax_rules_group, id_default_image, reference, ean13, price, quantity_discount]',
            'filter[id]' => '['. $combi->combinations->combination->id_product .']',
            'output_format' =>'JSON'
        );

        $product_get =  $service->get($product_prestashop_get); // Recatamos el producto

        $product = $product_get->products->product;
        
       $stock_get = $service->get(array(
            'resource' => 'stock_availables',
            'filter[id_product]' => '['.  $product->id .']',
            'filter[id_product_attribute]' => '['.$combi->combinations->combination->id .']',
            'display' => '[id, quantity]'
        )); 

      $name = '';  
        foreach ( $combi->combinations->combination->associations->product_option_values->product_option_value as $op ) {
           // return   $op;
            $option_resource = $service->get(array(
                'resource' => 'product_option_values',
                'id' => (integer) $op->id
            ));
            
            $name .= ' '.(string ) $option_resource->product_option_value->name->language.' ';
        } 

        $resul = [
            'id' => (integer)$combi->combinations->combination->id,
            'name' =>(string)$product->name->language .  $name,
           'stock' => [
              'in_stock' => (integer) $stock_get->stock_availables->stock_available->quantity,
              'stock_id' => (integer) $stock_get->stock_availables->stock_available->id 
              ],    
           
        ];

        return  $resul;
       }

       public function getproduct_combination($ean13) {
       
        $service = $this->prestashop;
        //-----------------------------------------------------------------------------------------------
        $combination_prestashop_get = array(       // Pedimos combinacion a prestashop           1 LLAMDA A LA API
            'resource' => 'combinations',
            'display' => 'full',
            'filter[ean13]' => '['. $ean13.']',
            'output_format' =>'JSON'
        );
        $combi =  $service->get($combination_prestashop_get); // Recatamos la combinacion 

        if (empty($combi->combinations->combination)) return null;  

        $product_prestashop_get = array(       // Pedimos el producto a prestashop           2 LLAMDA A LA API
            'resource' => 'products',
            //'display' => 'full',
            'display' => '[id, name, id_default_combination, ean13, price, id_tax_rules_group, id_default_image, reference, ean13, price, quantity_discount]',
            'filter[id]' => '['. $combi->combinations->combination->id_product .']',
            'output_format' =>'JSON'
        );

        $product_get =  $service->get($product_prestashop_get); // Recatamos el producto

        $product = $product_get->products->product;
         //-----------------------------------------------------------------------------------------------
        $tax_prestashop_get = array(   // Pedimos los tax del producto            2 LLAMDA A LA API
            'resource' => 'taxes',
            //'display' => 'full',
            'display' => '[rate]',
            'filter[id]' => '['. $product->id_tax_rules_group .']',
            'output_format' =>'JSON'
        );

        $tax_get = $service->get($tax_prestashop_get); // Recatamos el tax

        $tax = $tax_get->taxes->tax->rate;  // Tax del producto

        $stock_get = $service->get(array(
            'resource' => 'stock_availables',
            'filter[id_product]' => '['.  $product->id .']',
            'filter[id_product_attribute]' => '['.$combi->combinations->combination->id .']',
            'display' => '[id, quantity]'
        ));

      $name = '';  
        foreach ( $combi->combinations->combination->associations->product_option_values->product_option_value as $op ) {
           // return   $op;
            $option_resource = $service->get(array(
                'resource' => 'product_option_values',
                'id' => (integer) $op->id
            ));
            
            $name .= ' '.(string ) $option_resource->product_option_value->name->language.' ';
        } 

        $resul = [
            'id' => (integer)$product->id,
            'name' =>(string)$product->name->language .  $name,
            'price' => number_format((double)$product->price, 2, '.', '') + number_format((double)$combi->combinations->combination->price, 2, '.', ''),            'ivarate' => (double)$tax,
            'discount' => (double)$product->quantity_discount,
            'ean13' => $ean13,
            'amount' => 1,
            'stock' => [
              'in_stock' => (integer) $stock_get->stock_availables->stock_available->quantity,
              'stock_id' => (integer) $stock_get->stock_availables->stock_available->id 
              ],      
             'tax' => [
                'name' => (string) $tax_get->taxes->tax->name->language,
                'rate' => (float) $tax 
             ] 
        ];

        return  $resul;// $combi->combinations->combination->associations->product_option_values->product_option_value;
       }



	   public function getproduct_simple($ean13) {  // producto por ean13

        $service = $this->prestashop;
        //-----------------------------------------------------------------------------------------------
        $product_prestashop_get = array(       // Pedimos el producto a prestashop           1 LLAMDA A LA API
            'resource' => 'products',
            //'display' => 'full',
            'display' => '[id, name, id_default_combination, ean13, price, id_tax_rules_group, id_default_image, reference, ean13, price, quantity_discount]',
            'filter[ean13]' => '['. $ean13.']',
            'output_format' =>'JSON'
        );


        $product_get =  $service->get($product_prestashop_get); // Recatamos el producto

        if (empty($product_get->products->product)) return null;  

        $product = $product_get->products->product; // producto acortado

        //-----------------------------------------------------------------------------------------------
        $tax_prestashop_get = array(   // Pedimos los tax del producto            2 LLAMDA A LA API
            'resource' => 'taxes',
            //'display' => 'full',
            'display' => '[rate]',
            'filter[id]' => '['. $product->id_tax_rules_group .']',
            'output_format' =>'JSON'
        );

        $tax_get = $service->get($tax_prestashop_get); // Recatamos el tax

        $tax = $tax_get->taxes->tax->rate;  // Tax del producto

        //-----------------------------------------------------------------------------------------------
        $stok_prestashop_get = array(   // Pedimos los tax del producto            3 LLAMDA A LA API
            'resource' => 'stock_availables',
            //'display' => 'full',
            'display' => 'full',
            'filter[id_product]' => '['. $product->id .']',
            'output_format' =>'JSON'
        );

        $stok_get = $service->get($stok_prestashop_get); // Recatamos el stok del producto

        $stok = $stok_get->stock_availables->stock_available->quantity;  // Stok del producto

        $resul = [
            'id' => (integer)$product->id,
            'name' => (string)$product->name->language,
            'price' => number_format((double)$product->price, 2, '.', ''),
            'ivarate' => (double)$tax,
            'discount' => (double)$product->quantity_discount,
            'ean13' => (double)$product->ean13,
            'amount' => 1,
            'stock' => [
              'in_stock' => (integer)$stok,
              'stock_id' => (integer) $stok_get->stock_availables->stock_available->id 
              ],      
             'tax' => [
                'name' => (string) $tax_get->taxes->tax->name->language,
                'rate' => (float) $tax_get->taxes->tax->rate 
             ] 
        ];

        return  $resul;
    }

    public function getproduct_simple_for_id($id) {  // producto por ean13

        $service = $this->prestashop;
        //-----------------------------------------------------------------------------------------------
        $product_prestashop_get = array(       // Pedimos el producto a prestashop           1 LLAMDA A LA API
            'resource' => 'products',
            //'display' => 'full',
            'display' => '[id, name, id_default_combination, ean13, price, id_tax_rules_group, id_default_image, reference, ean13, price, quantity_discount]',
            'filter[id]' => '['. $id.']',
            'output_format' =>'JSON'
        );


        $product_get =  $service->get($product_prestashop_get); // Recatamos el producto

        if (empty($product_get->products->product)) return null;  

        $product = $product_get->products->product; // producto acortado

        //-----------------------------------------------------------------------------------------------
        $tax_prestashop_get = array(   // Pedimos los tax del producto            2 LLAMDA A LA API
            'resource' => 'taxes',
            //'display' => 'full',
            'display' => '[rate]',
            'filter[id]' => '['. $product->id_tax_rules_group .']',
           // 'output_format' =>'JSON'
        );

        $tax_get = $service->get($tax_prestashop_get); // Recatamos el tax

      
        $tax = $tax_get->taxes->tax->rate;  // Tax del producto

        //-----------------------------------------------------------------------------------------------
        $stok_prestashop_get = array(   // Pedimos los tax del producto            3 LLAMDA A LA API
            'resource' => 'stock_availables',
            //'display' => 'full',
            'display' => 'full',
            'filter[id_product]' => '['. $product->id .']',
            'output_format' =>'JSON'
        );

        $stok_get = $service->get($stok_prestashop_get); // Recatamos el stok del producto

        $stok = $stok_get->stock_availables->stock_available->quantity;  // Stok del producto

     
        $resul = [
            'id' => (integer)$product->id,
            'name' => (string)$product->name->language,
            'price' => number_format((double)$product->price, 2, '.', ''),
            'ivarate' => (double)$tax,
            'discount' => (double)$product->quantity_discount,
            'ean13' => (double)$product->ean13,
            'amount' => 1,
            'stock' => [
              'in_stock' => (integer)$stok,
              'stock_id' => (integer) $stok_get->stock_availables->stock_available->id 
              ],      
             'tax' => [
               // 'name' =>  empty($tax_get->taxes) ? '21 %' : (string) $tax_get->taxes->tax->name->language,
                'rate' => $tax_get->taxes->tax->rate == null  ? 21 : (float) $tax_get->taxes->tax->rate 
             ] 
        ];

        return  $resul;
    }

    public function getproduct($codebar)
    {


               $result =  '';
              if ($result !== null ) {
                  $data = [
                      'codigo' => 200,
                      'result' => $result
                  ];
                  return  $this->callproduct($codebar);
              }else {
                return $this->callproduct($codebar);
              } 
              
    }
    public function searchCombination($codebar){
        try{
            $service = $this->prestashop;
            $results = $service->get(array(
                'resource' => 'combinations',
                'filter[ean13]' => "[$codebar]",
                'display' => 'full'
            ));

            if (count($results->combinations->combination) == 0){
                $results = $service->get(array(
                    'resource' => 'combinations',
                    'filter[upc]' => "[$codebar]",
                    'display' => 'full'
                ));
            }
            if (count($results->combinations->combination) > 0){
                $data = [
                    'codigo' => 201,
                    'result' => $this->buildProductFromCombinationXml($results, $codebar)
                ];
                return $data;

            } else {
                $data = [
                    'codigo' => 500,
                    'msj' => "No se encontraron resultados"
                ];
                return $data;
            }
        } catch(\PrestaShopWebserviceException $e){
            $data = [
                'codigo' => 500,
                'msj' => "Error en comunicación con tienda prestashop."
            ];
            return $data;
        }

    }

    private function buildProductFromCombinationXml($xml, $codebar){
        $product_id = $xml->combinations->combination[0]->id_product;
        $service = $this->prestashop;
        try{
            $parent = $service->get(array(
                'resource' => 'products/'.$product_id,
            ));

            $default_combination = $service->get(array(
                'resource' => 'combinations',
                'id' => (integer)$parent->product[0]->id_default_combination
            ));

            $combination = $service->get(array(
                'resource' => 'combinations',
                'id' => (integer)$xml->combinations->combination[0]->id
            ));
            $product_price = (float)$parent->product[0]->price;
            $category_id = (integer)$parent->product[0]->id_category_default;

            $specific_price = $this->getSpecificPrices((integer)$parent->product[0]->id);
            $tax = $this->getProductTax((integer)$parent->product[0]->id_tax_rules_group);

            $product_price_without_discount = $this->calcPriceWithoutDiscount($product_price, $specific_price);
            $discount = $product_price_without_discount - $product_price;

            //the impact of the default combination
            $default_combination_price = (float)$default_combination->combination[0]->price;

            $default_combination_price_with_tax = $default_combination_price + ($default_combination_price * $tax['rate'] / 100);
           // dd( $product_price + $default_combination_price,   $default_combination_price_with_tax);
            //the impact for the current combination
            $combination_price = (float)$xml->combinations->combination[0]->price;
            $combination_price_with_tax = $combination_price + ($combination_price * $tax['rate'] / 100);

            $retail_price = $product_price_without_discount - $default_combination_price_with_tax;
            $final_price_without_discount = $retail_price + $combination_price_with_tax;
            //need to calc the base price
            $tax = $this->getProductTax((integer)$parent->product[0]->id_tax_rules_group);
            $final_result = array(
                'type' => 'combination',
                'id' => (integer)$xml->combinations->combination[0]->id_product,
                'comb_id' => (integer)$xml->combinations->combination[0]->id,
                'codebar' => $codebar,
                'name' => (string)$parent->product[0]->name->language,
                'description' => (string)$parent->product[0]->description_short->language,
                'price' => number_format($product_price + (double)$combination->combination[0]->price[0], 2, '.', ''),
                'reference' => (string)$parent->product[0]->reference,
                'amount' => 1,
                'discount' => 0,
                'full_price' => number_format($product_price + (double)$combination->combination[0]->price[0], 2, '.', ''),
                'unit_price' => number_format($product_price + (double)$combination->combination[0]->price[0], 2, '.', ''),
                'tax' => $tax,
                'min_stock' => (integer)$xml->combinations->combination[0]->stock_min,
                'min_order' => (integer)$xml->combinations->combination[0]->order_min,
                'upc' => (string)$xml->combinations->combination[0]->upc,
                'ean13' => (string)$xml->combinations->combination[0]->ean13,
                'specific_price' => $specific_price,
                'category' => $this->getCategory($category_id),
                'attribute_id' => (integer)$xml->combinations->combination[0]->id
            );
            if ($combination->combination->associations->product_option_values->count() > 0){
                foreach($combination->combination->associations->product_option_values->product_option_value as $option){
                    $option_resource = $service->get(array(
                        'resource' => 'product_option_values',
                        'id' => (integer)$option->id
                    ));
                    $final_result['name'] .= ' '.(string)$option_resource->product_option_value->name->language.' ';
                }
            }
            $final_result['stock'] = $this->getProductStocks($final_result['id'] ,$final_result['attribute_id']);
            return array($final_result)[0];
        } catch(\PrestaShopWebserviceException $e){
            $data = [
                'codigo' => 500,
                'msj' => "A ocurrido un error 1"
            ];
            return response()->json($data);
        }
    }



    private function getCombinationDetails($id){
        $service = $this->prestashop;
        try{
            $resource = $service->get(array(
                'resource' => 'combinations',
                'id' => $id
            ));
            $product_id = (integer)$resource->combination->id_product;
            $product_resource = $service->get(array(
                'resource' => 'products',
                'id' => $product_id
            ));
            $result = array(
                'description' => (string)$product_resource->product->description_short->language,
                'upc' => 'upc: '.(string)$resource->combination->upc,
                'ean13' => 'ean13: '.(string)$resource->combination->ean13,
                'availability' => ((integer)$product_resource->product->available_for_order == 1) ? 'Disponible' : 'No disponible para venta',
                'image' => (count($resource->combination->associations->images->image) > 0) ? $this->url.'/api/images/products/'.$product_id.'/'.(integer)$resource->combination->associations->images->image[0]->id.'?ws_key='.$this->keycode : 'none',
            );
            return array('status' => 'success', 'result' =>$result);
        }catch(\PrestaShopWebserviceException $e){
            $data = [
                'codigo' => 500,
                'msj' => "Error en comunicación con tienda prestashop."
            ];
            return response()->json($data);
        }
    }



    public function getProductDetails($id, $type){
        if ($type == 'product') {
            return $this->getParentProductDetails($id);

        } else{
            return $this->getCombinationDetails($id);
        }
    }

    public function getParentProductDetails($id){
        $service = $this->prestashop;
        try{
            $products = $service->get(array('resource' => 'products/'.$id));
            if (count($products->product) > 0){
                $image = 'none';
                $result = array(
                    'description' => (string)$products->product[0]->description_short->language,
                    'upc' => 'upc: '.(string)$products->product[0]->upc,
                    'ean13' => 'ean13: '.(string)$products->product[0]->ean13,
                    'availability' => ((integer)$products->product[0]->available_for_order == 1) ? 'Disponible' : 'No disponible para venta',
                    'image' => (count($products->product[0]->associations->images->image) > 0) ? $this->url.'/api/images/products/'.$id.'/'.(integer)$products->product[0]->associations->images->image[0]->id.'?ws_key='.$this->keycode : 'none',
                );
                return array('status' => 'success', 'result' => $result);
            } else{
                $data = [
                    'codigo' => 500,
                    'msj' => "No existe el producto"
                ];
                return response()->json($data);
            }
        } catch (\PrestaShopWebserviceException $e){
            $data = [
                'codigo' => 500,
                'msj' => "Error en cominucación con tienda prestashop."
            ];
            return response()->json($data);
        }
    }

    public function updatestock(Request $request, $op)
    {

        $product = $request->input('sale');
        $product_id = $product['id'];
        $ean = $product['ean13'];
       
        $result = $this->prestashop->get(array(
            'resource' => 'stock_availables',
            'id' => $product['stock_id']
        ));

        if ($op == 'menos') {

            $result->stock_available->quantity = (float)$result->stock_available->quantity - $product['amount'];

        } else {

            $result->stock_available->quantity = (float)$result->stock_available->quantity + $product['amount'];

        }

        $this->prestashop->edit(array(
            'resource' => 'stock_availables',
            'id' => $product['stock_id'],
            'putXml' => $result->asXml()
        ));

    
        $data = [
            'codigo' => 500,
            'msj' =>  'Ok'
        ]; 

        return response()->json($data);

    }

    private function buildProductFromXmls($xml, $codebar, $index = 0){
        $product = $xml->products->product;

            $specific_price = $this->getSpecificPrices((integer)$product->id);
            $price = $this->calcPriceWithoutDiscount((float)$product->price, $specific_price);
            $category_id = (integer)$product->id_category_default;
            if ($codebar == ''){
                if ((string)$product->ean13 != ''){
                    $codebar = (string)$product->ean13;
                } else {
                    $codebar = (string)$product->upc;
                }
            }

            $result = array(
                'type' => 'product',
                'id' => (integer)$product->id,
                'comb_id' => null,
                'codebar' => $codebar,
                'upc' => (string)$product->upc,
                'ean13' => (string)$product->ean13,
                'name' => (string)$product->name->language,
                'description' => (string)$product->description_short->language,
                'price' => number_format($price, 2, '.', ''),
                'reference' => (string)$product->reference,
                'amount' => 1,
                'discount' => 0,
                'full_price' => number_format($price, 2, '.', ''),
                'unit_price' => number_format($price, 2, '.', ''),
                'tax' => $this->getProductTax((integer)$product->id_tax_rules_group),
                'specific_price' => $specific_price,
                'wholesale_price' => (float)$product->wholesale_price,
                'category' => $this->getCategory($category_id),
                'attribute_id' => (integer)$product->id_default_combination,
                'min_stock' => (integer)$product->stock_min,
                'min_order' => (integer)$product->order_min,
                'order_amount' => 1,
                'order_received' => 0
            );
            $default_combination_id = (float)$product->id_default_combination;
            $service = $this->prestashop;
            if ($default_combination_id != 0){
            try{
                $combination_resource = $service->get(array(
                    'resource' => 'combinations',
                    'id' => $default_combination_id
                ));
                if ($combination_resource->combination->associations->product_option_values->count() > 0){
                    foreach($combination_resource->combination->associations->product_option_values->product_option_value as $option){
                        $option_resource = $service->get(array(
                            'resource' => 'product_option_values',
                            'id' => (integer)$option->id
                        ));
                        $result['name'] .= ' '.(string)$option_resource->product_option_value->name->language.' ';
                    }
                }
            }catch (\PrestaShopWebserviceException $e){
                return array('status' => 'error', 'message' => 'Hubo un error en el servicio web');
            }
        }
            $stocks = $this->getProductStocks($result['id'],$default_combination_id);
            $result['stock'] = $stocks;
        return  $result;
    }


   public function findcombination ($term){

        $service = $this->prestashop;

         $resultsp = $service->get(array(
                'resource' => 'products',
                'id' => $term,
                'output_format' =>'JSON'    /// AKI ESOY TRABAJANDSO
          ));
         
         $combination = [];   

         foreach ($resultsp->product->associations->combinations->combination as $as) {
        
            $combi = $service->get(array(
                'resource' => 'combinations',
                'id' =>$as->id                 
           )); 

             array_push($combination, $combi);
          }  

         /* $resultsp = $service->get(array(
                'resource' => 'combinations',
                'id' => $term
          )); */
    
          return array('status'=> 'success', 'result' => $combination);

     }    

    public function searchByTerm($term, $findfor){
        $service = $this->prestashop;
         switch ( $findfor) {
            case 1:
               $filter = 'name';
                break;
            case 2:
                $filter = 'reference';
                break;
        }
          $resultsp = $service->get(array(

                'resource' => 'products/',
                'filter[' . $filter . ']' => '%['.$term.']%',
                'display' => '[id, name, ean13, reference, id_default_combination]',
                'output_format' =>'JSON'
          ));

         $result = array();
         foreach ($resultsp->products->product as $prod){               
          $prod->id_default_combination = (integer) $prod->id_default_combination;
          $prod->name = (string) $prod->name->language;
          array_push($result ,$prod);
         }

         if (count($result) > 0) { 

           return array('status'=> 'success', 'result' => $result);

         } else {
            return array('status' => 'fail', 'message' => 'No se encontraron resultados');    
         }
       

    }


    public function buildProductFromCombinationXmls($product_id, $idcombinations){
        $service = $this->prestashop;
        try{
            $parent = $service->get(array(
                'resource' => 'products/'.$product_id,
            ));

            $default_combination = $service->get(array(
                'resource' => 'combinations',
                'id' => (integer)$parent->product[0]->id_default_combination
            ));

            $combination = $service->get(array(
                'resource' => 'combinations',
                'id' => $idcombinations
            ));

           // dd( $combination);
            $product_price = (float)$parent->product[0]->price;
            $category_id = (integer)$parent->product[0]->id_category_default;

            $specific_price = $this->getSpecificPrices((integer)$parent->product[0]->id);
            $tax = $this->getProductTax((integer)$parent->product[0]->id_tax_rules_group);

            $product_price_without_discount = $this->calcPriceWithoutDiscount($product_price, $specific_price);
            $discount = $product_price_without_discount - $product_price;

            //the impact of the default combination
            $default_combination_price = (float)$default_combination->combination[0]->price;

            $default_combination_price_with_tax = $default_combination_price + ($default_combination_price * $tax['rate'] / 100);
            // dd( $product_price + $default_combination_price,   $default_combination_price_with_tax);
            //the impact for the current combination
            $combination_price = (float)$combination->combination[0]->price;
            $combination_price_with_tax = $combination_price + ($combination_price * $tax['rate'] / 100);

            $retail_price = $product_price_without_discount - $default_combination_price_with_tax;
            $final_price_without_discount = $retail_price + $combination_price_with_tax;
            //need to calc the base price
            $tax = $this->getProductTax((integer)$parent->product[0]->id_tax_rules_group);
            $final_result = array(
                'type' => 'combination',
                'id' => (integer)$combination->combination[0]->id_product,
                'comb_id' => (integer)$combination->combination[0]->id,
                'codebar' => $combination->combination[0]->ean13,
                'name' => (string)$parent->product[0]->name->language,
                'description' => (string)$parent->product[0]->description_short->language,
                'price' => number_format($product_price + (double)$combination->combination[0]->price[0], 2, '.', ''),
                'reference' => (string)$parent->product[0]->reference,
                'amount' => 1,
                'discount' => 0,
                'full_price' => number_format($product_price + (double)$combination->combination[0]->price[0], 2, '.', ''),
                'unit_price' => number_format($product_price + (double)$combination->combination[0]->price[0], 2, '.', ''),
                'tax' => $tax,
                'min_stock' => (integer)$combination->combination[0]->stock_min,
                'min_order' => (integer)$combination->combination[0]->order_min,
                'upc' => (string)$combination->combination[0]->upc,
                'ean13' => (string)$combination->combination[0]->ean13,
                'specific_price' => $specific_price,
                'category' => $this->getCategory($category_id),
                'attribute_id' => (integer)$combination->combination[0]->id
            );
            if ($combination->combination->associations->product_option_values->count() > 0){
                foreach($combination->combination->associations->product_option_values->product_option_value as $option){
                    $option_resource = $service->get(array(
                        'resource' => 'product_option_values',
                        'id' => (integer)$option->id
                    ));
                    $final_result['name'] .= ' '.(string)$option_resource->product_option_value->name->language.' ';
                }
            }
            $final_result['stock'] = $this->getProductStocks($final_result['id'],$final_result['attribute_id']);
            return array($final_result)[0];
        } catch(\PrestaShopWebserviceException $e){
            $data = [
                'codigo' => 500,
                'msj' => "A ocurrido un error 1"
            ];
            return response()->json($data);
        }
    }

    private function buildProductFromCombination($parent, $combination_id){
        $service = $this->prestashop;
        try{
            $combination = $service->get(array(
                'resource' => 'combinations',
                'id' => $combination_id
            ));

            $product_price = (float)$parent->product[0]->price;
            $category_id = (integer)$parent->product[0]->id_category_default;

            $specific_price = $this->getSpecificPrices($parent->product[0]->id);
            $tax = $this->getProductTax((integer)$parent->product[0]->id_tax_rules_group);

            $product_price_without_discount = $this->calcPriceWithoutDiscount($product_price,$specific_price);
            $discount = $product_price_without_discount - $product_price;

            //the impact of the default combination
            $default_combination_price = (float)$combination->combination[0]->price;
            $default_combination_price_with_tax = $default_combination_price + ($default_combination_price * $tax['rate'] / 100);

            //the impact for the current combination
            $combination_price = (float)$combination->combination[0]->price;
            $combination_price_with_tax = $combination_price + ($combination_price * $tax['rate'] / 100);

            $retail_price = $product_price_without_discount - $default_combination_price_with_tax;
//            $final_price_without_discount = $retail_price + $combination_price_with_tax;
            $final_price_without_discount = $product_price_without_discount + $combination_price_with_tax;
            //need to calc the base price
            $tax = $this->getProductTax((integer)$parent->product[0]->id_tax_rules_group);

            $final_result = array(
                'type' => 'combination',
                'id' => (integer)$combination->combination->id_product,
                'comb_id' => (integer)$combination->combination->id,
                'codebar' => (string)$combination->combination->ean13 == '' ? (string)$combination->combination->upc : (string)$combination->combination->ean13,
                'name' => (string)$parent->product[0]->name->language,
                'description' => (string)$parent->product[0]->description_short->language,
                'price' => number_format($final_price_without_discount, 2, '.', ''),
                'reference' => (string)$combination->combination->reference,
                'amount' => 1,
                'discount' => 0,
                'full_price' => number_format($final_price_without_discount, 2, '.', ''),
                'unit_price' => number_format($final_price_without_discount, 2, '.', ''),
                'tax' => $tax,
                'min_stock' => (integer)$combination->combination->stock_min,
                'min_order' => (integer)$combination->combination->order_min,
                'ean13' => (string)$combination->combination->ean13,
                'upc' => (string)$combination->combination->upc,
                'specific_price' => $specific_price,
                'category' => $this->getCategory($category_id),
                'attribute_id' => (integer)$combination->combination->id
            );
            if ($combination->combination->associations->product_option_values->count() > 0){
                foreach($combination->combination->associations->product_option_values->product_option_value as $option){
                    $option_resource = $service->get(array(
                        'resource' => 'product_option_values',
                        'id' => (integer)$option->id
                    ));
                    $final_result['name'] .= ' '.(string)$option_resource->product_option_value->name->language.' ';
                }
            }
            $final_result['stock'] = $this->getProductStocks($final_result['id'],$final_result['attribute_id']);
            return array($final_result);
        } catch(\PrestaShopWebserviceException $e){
            echo $e;
            return array('status' => 'error', 'message' => 'Ha ocurrido un error al buscar el producto');
        }
    }
}
