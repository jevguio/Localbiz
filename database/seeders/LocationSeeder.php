<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['name' => 'Tagbilaran City'],
            ['name' => 'Alburquerque'],
            ['name' => 'Alicia'],
            ['name' => 'Anda'],
            ['name' => 'Antequera'],
            ['name' => 'Baclayon'],
            ['name' => 'Balilihan'],
            ['name' => 'Batuan'],
            ['name' => 'Bien Unido'],
            ['name' => 'Bilar'],
            ['name' => 'Buenavista'],
            ['name' => 'Calape'],
            ['name' => 'Candijay'],
            ['name' => 'Carmen'],
            ['name' => 'Catigbian'],
            ['name' => 'Clarin'],
            ['name' => 'Corella'],
            ['name' => 'Cortes'],
            ['name' => 'Dagohoy'],
            ['name' => 'Danao'],
            ['name' => 'Dauis'],
            ['name' => 'Dimiao'],
            ['name' => 'Duero'],
            ['name' => 'Garcia Hernandez'],
            ['name' => 'Guindulman'],
            ['name' => 'Inabanga'],
            ['name' => 'Jagna'],
            ['name' => 'Lila'],
            ['name' => 'Loay'],
            ['name' => 'Loboc'],
            ['name' => 'Loon'],
            ['name' => 'Mabini'],
            ['name' => 'Maribojoc'],
            ['name' => 'Panglao'],
            ['name' => 'Pilar'],
            ['name' => 'Pres. Carlos P. Garcia'],
            ['name' => 'Sagbayan'],
            ['name' => 'San Isidro'],
            ['name' => 'San Miguel'],
            ['name' => 'Sevilla'],
            ['name' => 'Sierra Bullones'],
            ['name' => 'Sikatuna'],
            ['name' => 'Talibon'],
            ['name' => 'Trinidad'],
            ['name' => 'Tubigon'],
            ['name' => 'Ubay'],
            ['name' => 'Valencia']
        ];
 
        Location::insert($locations);
    }
}
