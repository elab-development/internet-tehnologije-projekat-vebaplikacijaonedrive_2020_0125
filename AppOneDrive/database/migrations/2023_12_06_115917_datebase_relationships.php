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
        Schema::table('zaposlenis',function(Blueprint $table){
            $table->string('firma_pib')->after('id');
            $table->foreignId('user_id')->after('id');
            $table->dropColumn('id');
            $table->foreign('firma_pib')->references('PIB')->on('firmas');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(array('firma_pib','user_id'));
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
            $table->dropColumn("firma_pib");
        });
    }
};
