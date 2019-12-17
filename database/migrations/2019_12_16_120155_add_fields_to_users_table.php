<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 50)->change();
            $table->string('surname', 100)->after('name');
            $table->string('role', 20)
                ->after('surname')
                ->nullable();
            $table->text('description')->after('password');
            $table->string('image')
                ->after('description')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->dropColumn('surname');
            $table->dropColumn('role');
            $table->dropColumn('description');
            $table->dropColumn('image');
        });
    }
}
