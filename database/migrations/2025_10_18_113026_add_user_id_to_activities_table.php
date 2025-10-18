<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('description')->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
