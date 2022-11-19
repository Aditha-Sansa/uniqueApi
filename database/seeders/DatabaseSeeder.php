<?php

namespace Database\Seeders;

use App\Actions\RandomAlphaNumeric;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new RandomAlphaNumeric())->create(50000, 7);
    }
}
