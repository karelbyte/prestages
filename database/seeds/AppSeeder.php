<?php

use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // crear usuario maestro
        $user = new \App\Models\User();
        $user->name = 'administador';
        $user->email = 'admin@mitienda.com';
        $user->nick = 'admin';
        $user->active = 1;
        $user->code = 'ADMINSYS';
        $user->password = bcrypt('1234');
        $user->save();
/*
        // crear estados
        \Illuminate\Support\Facades\DB::table('status')->insert([
            ['id' => 1, 'descrip' => 'ENCAJA'],
            ['id' => 2, 'descrip' => 'CERRADO'],
            ['id' => 3, 'descrip' => 'CERRADO/FACT.'],
            ['id' => 4, 'descrip' => 'ENCAJA/MODIF.'],
            ['id' => 5, 'descrip' => 'CANCELADO'],
            ['id' => 6, 'descrip' => 'ABIERTA'],
            ['id' => 7, 'descrip' => 'CERRADA'],
            ['id' => 8, 'descrip' => 'CANCELADA'],
            ['id' => 9, 'descrip' => 'ENCAJA/FACT.'],
            ['id' => 10, 'descrip' => 'IMPORTE DEVUELTO'],
            ['id' => 11, 'descrip' => 'IMPORTE PENDIENTE'],
        ]);

        // crear setting impresoras
        \Illuminate\Support\Facades\DB::table('settings')->insert([
            ['id' => 1, 'params' => 'items_per_page', 'value' => 15 ],
            ['id' => 2, 'params' => 'pagination_position', 'value' => 'left' ],
            ['id' => 3, 'params' => 'ticket_with', 'value' => 4.3 ],
            ['id' => 4, 'params' => 'ticket_description_height', 'value' => 100 ],
            ['id' => 5, 'params' => 'ticket_headings_height', 'value' => 9 ],
            ['id' => 6, 'params' => 'ticket_text_height', 'value' => 8.8 ],
            // crear tabulacion de precios
            ['id' => 7, 'params' => 'tap_price', 'value' => 2 ],

        ]);

        // crear primeros surrogates
        \Illuminate\Support\Facades\DB::table('surrogate')->insert([
            ['id' => 1, 'descrip' => 'ticket', 'ids' => 1],
            ['id' => 2, 'descrip' => 'invoice', 'ids' => 1],
            ['id' => 3, 'descrip' => 'client', 'ids' => 2]
        ]);

        // cread un cliente por defecto
        $client = new \App\Models\Clients();
        $client->code = 'PG/1';
        $client->name = 'PUBLICO EN GENERAL';
        $client->default = 1;
        $client->save();

        $c = new \App\Models\Company();
        $c->name = 'administrador';
        $c->contact = 'Don Prospero';
        $c->address = 'Avenida S/N ';
        $c->phone = '4674222222';
        $c->logo = 'company.png';
        $c->save(); */
    }
}
