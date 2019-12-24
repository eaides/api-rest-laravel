<?php

use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Transaction::all()->count()) {
            Transaction::flushEventListeners();
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            $quantity = 1000;
            factory(App\Transaction::class, $quantity)->create();

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
