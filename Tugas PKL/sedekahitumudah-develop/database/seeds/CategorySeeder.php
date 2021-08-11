<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
        [
            'category_name' => 'Beasiswa Santri',
        ],
        [
            'category_name' => 'Kesehatan',
        ],
        [
            'category_name' => 'Pangan',
        ],
        [
            'category_name' => 'Sarana dan Prasarana',
        ],
        [
            'category_name' => 'Bencana',
        ],
        [
            'category_name' => 'Pendidikan',
        ],
        ]);
    }
}
