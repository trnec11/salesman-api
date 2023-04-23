<?php

namespace Database\Seeders;

use App\Models\Salesman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SalesmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Salesman::query()->truncate();

        $salesman = [
            [
                'uuid' => Str::uuid(),
                'first_name' => 'John',
                'last_name' => 'Rambo',
                'titles_before' => null,
                'titles_after' => null,
                'prosight_id' => 11100,
                'email' => 'johnrambo@prosight.sk',
                'phone' => '421999125666',
                'gender' => 'm',
                'marital_status' => 'single'
            ],
            [
                'uuid' => Str::uuid(),
                'first_name' => 'Tester',
                'last_name' => 'Titulový',
                'titles_before' => 'Ing.',
                'titles_after' => 'PhD.',
                'prosight_id' => 11101,
                'email' => 'tester@prosight.sk',
                'phone' => '421999125667',
                'gender' => 'm',
                'marital_status' => 'single'
            ],
            [
                'uuid' => Str::uuid(),
                'first_name' => 'Tester',
                'last_name' => 'Tester',
                'titles_before' => 'Bc.',
                'titles_after' => null,
                'prosight_id' => 11002,
                'email' => 'tester1@prosight.sk',
                'phone' => '421999125668',
                'gender' => 'm',
                'marital_status' => 'married'
            ],
            [
                'uuid' => Str::uuid(),
                'first_name' => 'Janko',
                'last_name' => 'Hraško',
                'titles_before' => 'Bc.',
                'titles_after' => null,
                'prosight_id' => 11003,
                'email' => 'jankohrasko@prosight.sk',
                'phone' => '421999125669',
                'gender' => 'm',
            ],
            [
                'uuid' => Str::uuid(),
                'first_name' => 'Test',
                'last_name' => 'Testov',
                'titles_before' => null,
                'titles_after' => null,
                'prosight_id' => 11004,
                'email' => 'tester2@prosight.sk',
                'phone' => '421999125670',
                'gender' => 'm',
            ],
            [
                'uuid' => Str::uuid(),
                'first_name' => 'Janka',
                'last_name' => 'Testová',
                'titles_before' => null,
                'titles_after' => null,
                'prosight_id' => 11005,
                'email' => 'tester3@prosight.sk',
                'phone' => '421999125671',
                'gender' => 'f',
            ],
            [
                'uuid' => Str::uuid(),
                'first_name' => 'Jožko',
                'last_name' => 'Mrkvička',
                'titles_before' => 'PharmDr.',
                'titles_after' => null,
                'prosight_id' => 12103,
                'email' => 'tester4@prosight.sk',
                'phone' => '421999125672',
                'gender' => 'm',
                'marital_status' => 'divorced'
            ],
            [
                'uuid' => Str::uuid(),
                'first_name' => 'Julka',
                'last_name' => 'Testová',
                'titles_before' => null,
                'titles_after' => null,
                'prosight_id' => 14755,
                'email' => 'tester5@prosight.sk',
                'phone' => '421999125673',
                'gender' => 'f',
            ],
            [
                'uuid' => Str::uuid(),
                'first_name' => 'Testerka',
                'last_name' => 'Testová',
                'titles_before' => null,
                'titles_after' => null,
                'prosight_id' => 77755,
                'email' => 'tester6@prosight.sk',
                'phone' => '421999125674',
                'gender' => 'f',
            ],
        ];

        foreach ($salesman as $item) {
            Salesman::query()->create($item);
        }
    }
}
