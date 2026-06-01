<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove the red_led actuator record
        DB::table('actuator_states')->where('actuator_name', 'red_led')->delete();
    }

    public function down(): void
    {
        // Re-insert the red_led record if rolled back
        DB::table('actuator_states')->insert([
            'actuator_name' => 'red_led',
            'state' => 1,
            'controlled_by' => 'system',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
};