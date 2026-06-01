<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new actuator records
        DB::table('actuator_states')->insert([
            [
                'actuator_name' => 'yellow_led',
                'state' => 1,
                'controlled_by' => 'system',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'actuator_name' => 'red_led',
                'state' => 1,
                'controlled_by' => 'system',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        DB::table('actuator_states')->where('actuator_name', 'yellow_led')->delete();
        DB::table('actuator_states')->where('actuator_name', 'red_led')->delete();
    }
};