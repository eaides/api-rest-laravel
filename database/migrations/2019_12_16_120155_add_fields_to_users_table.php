<?php

use App\User;
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
                ->default(User::ROLE_READER);
            $table->text('bio')
                ->after('password')
                ->nullable();
            $table->string('image')
                ->after('bio')
                ->nullable();
            $table->string('verification_token', 200)
                ->after('remember_token')
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
            $table->dropColumn('bio');
            $table->dropColumn('image');
            $table->dropColumn('verification_token');
        });
    }
}
