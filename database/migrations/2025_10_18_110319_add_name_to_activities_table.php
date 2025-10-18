<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('name')->after('id');
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}