<?php

use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       for ($i = 10; $i <= 200; $i++){
           $cli = new \App\Models\Clients();
           $cli->code = "CL/0" . $i;
           $cli->name = strtoupper(str_random(5));
           $cli->email = str_random()."@gmail.com";
           $cli->note = str_random(10);
           $cli->dni_cif = strtoupper(str_random(5));
           $cli->phone = "57735435";
           $cli->address = strtoupper(str_random(15));
           $cli->province = strtoupper(str_random(15));
           $cli->location = strtoupper(str_random(15));
           $cli->zip = "80890";
           $cli ->save();
       }
    }
}
