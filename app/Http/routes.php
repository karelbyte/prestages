<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*'middleware' => 'auth'    <label>{{ auth()->user()->nick}}</label> {{auth()->user()->id}}*/
Route::group(['middleware' => ['auth', 'user.active']], function () {

    get('/home', function () {
        return view('app.home');
    });

    get('/', function () {
        return view('app.home');
    });

    get('/users/lists', 'Users_Controller@lists');
    get('/users/codes', 'Users_Controller@codes');
    resource('users', 'Users_Controller');

    get('/clients/get', 'Clients_Controller@get');
    get('/clients/lists', 'Clients_Controller@lists');
    post('/clients/setdef/{id}', 'Clients_Controller@setdef');
    get('/clients/getdef', 'Clients_Controller@getdef');
    resource('clients', 'Clients_Controller');

    get('/sales/lists', 'Sales_Controller@lists');
    post('/sales/setsales', 'Sales_Controller@setsale');
    post('/sales/setsalesdetails/{idticket}', 'Sales_Controller@setsaledetails');
    resource('sales', 'Sales_Controller');

    get('/products/getstock/{idpro}/{idcom}', 'Products_Controller@getstock');
    get('/products/getproduct/{code}', 'Products_Controller@getproduct');
    get('/products/find/{term}/{findfor}', 'Products_Controller@searchByTerm');
    get('/products/getcombinationfind/{id_product}', 'Products_Controller@getcombinationfind'); 
    get('/products/findcombination/{term}', 'Products_Controller@findcombination');
    get('/products/getproductdetails/{code}/{type}', 'Products_Controller@getProductDetails');
    post('/products/updatestock/{op}', 'Products_Controller@updatestock');
    get('/products/getcombinationselect/{id}', 'Products_Controller@get_combination_for_id');
    get('/products/getproductselect/{id}', 'Products_Controller@getproduct_simple_for_id');
    resource('products', 'Products_Controller');

    get('/config/getprestashop', 'Config_Controller@getprestashop');
    get('/config/getpricetap', 'Config_Controller@getpricetap');
    post('/config/setpricetap/{tap}', 'Config_Controller@setpricetap');
    get('/config/testprestashop', 'Config_Controller@testprestashop');
    post('/config/setprestashop', 'Config_Controller@setprestashop');
    get('/config/getcurrencypresta', 'Config_Controller@getcurrencypresta');
    get('/config/getcurrency', 'Config_Controller@getcurrency');
    post('/config/setcurrency', 'Config_Controller@setcurrency');
    get('/config/getcompany', 'Config_Controller@getcompany');
    post('/config/setcompany', 'Config_Controller@setcompany');
    get('/config/getprint', 'Config_Controller@getprint');
    post('/config/setprint', 'Config_Controller@setprint');
    post('config/imgstore', 'Config_Controller@saveimg');
    resource('config', 'Config_Controller');

    get('/tickets/get', 'Tickets_Controller@get');
    get('/tickets/lists', 'Tickets_Controller@lists');
 //   get('/tickets/listsh', 'Tickets_Controller@listsh');
    get('/tickets/showdetais/{id}', 'Tickets_Controller@showdetais');
    get('/ticket/print/{id}/{standard}/{tgift}','Tickets_Controller@printTicketAction');
    get('/ticket/pdf/{id}/{standard}','Tickets_Controller@printTicketActionPDF');
   // get('/ticketsh','Tickets_Controller@ticketsh');
    post('/tickets/chengestatus/{id}/{status}', 'Tickets_Controller@chengestatus');
    post('/tickets/cancel/{id}/{status}/{type}', 'Tickets_Controller@cancel');
    resource('tickets', 'Tickets_Controller');

    post('/closes/openbox', 'Closes_Controller@openbox');
    get('/closes/geymonybox', 'Closes_Controller@geymonybox');
    post('/closes/getclose', 'Closes_Controller@getclose');
    post('/closes/setclose', 'Closes_Controller@setclose');
    get('/closes/lists', 'Closes_Controller@lists');
    get('/closes/print/{id}','Closes_Controller@printcloses');
    get('/closes/lastclose','Closes_Controller@lastclose');
    get('/closes/pdf','Closes_Controller@pdf');
    get('/closes/view/{id}','Closes_Controller@printcloses_view');
    post('/closes/cancel/{id}','Closes_Controller@cancel');
    resource('/closes', 'Closes_Controller');

    get('/invoices/ticket/lists', 'Invoice_Controller@listsh');
    get('/invoices/surrogate', 'Invoice_Controller@surrogate');
    get('/invoices/lists', 'Invoice_Controller@lists');
    post('/invoices/save', 'Invoice_Controller@saveinvoice');
    get('/invoices/pdf/{id}', 'Invoice_Controller@prints');
    post('/invoices/cancel/{id}', 'Invoice_Controller@cancel');
    resource('/invoices', 'Invoice_Controller');



});

//get('/cache', 'Products_Controller@buildcache');

get('auth/login', 'Auth\AuthController@getLogin')->name('auth.login')->middleware('guest');
post('auth/login', 'Auth\AuthController@postLogin');
get('auth/logout', 'Auth\AuthController@getLogout');
