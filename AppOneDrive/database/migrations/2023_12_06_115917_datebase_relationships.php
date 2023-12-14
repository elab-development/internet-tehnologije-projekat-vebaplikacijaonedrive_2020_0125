<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members',function(Blueprint $table){
            $table->string('firm_pib')->after('id');
            $table->foreignId('user_id')->after('id');
            $table->dropColumn('id');
            $table->foreign('firm_pib')->references('PIB')->on('firms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(array('firm_pib','user_id'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("zaposleni",function(Blueprint $table){
            $table->id();
            $table->dropColumn("user_id");
            $table->dropColumn("firm_pib");
        });
    }
};
