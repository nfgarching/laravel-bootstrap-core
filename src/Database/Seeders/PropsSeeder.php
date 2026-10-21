<?php

namespace Nfgarching\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Nfgarching\Core\Models\Prop;

class PropsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Prop::factory(10)
            ->create();
    }
}
