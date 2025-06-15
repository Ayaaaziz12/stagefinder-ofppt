<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobTypesAndStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert job types
        $jobTypes = [
            ['Libelle' => 'Full-time'],
            ['Libelle' => 'Part-time'],
            ['Libelle' => 'Contract'],
            ['Libelle' => 'Internship'],
            ['Libelle' => 'Freelance'],
            ['Libelle' => 'Temporary'],
            ['Libelle' => 'Remote'],
            ['Libelle' => 'On-site'],
            ['Libelle' => 'Hybrid']
        ];

        DB::table('jobtypes')->insert($jobTypes);

        // Insert offer statuses
        $offerStatuses = [
            ['Libelle' => 'Active'],
            ['Libelle' => 'Closed'],
            ['Libelle' => 'Pending'],
            ['Libelle' => 'Draft'],
            ['Libelle' => 'Expired']
        ];

        DB::table('offrestatuses')->insert($offerStatuses);
    }
}
