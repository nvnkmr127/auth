<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\App;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        App::updateOrCreate(['slug' => 'crm-dashboard'], [
            'name' => 'CRM Dashboard',
            'domain' => 'crm.saas.com',
            'status' => 'active'
        ]);

        App::updateOrCreate(['slug' => 'analytics-suite'], [
            'name' => 'Analytics Suite',
            'domain' => 'analytics.saas.com',
            'status' => 'active'
        ]);
    }
}
