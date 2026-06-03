<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename the `red_led` actuator to `green_led` to match
     * the new ESP32 dual-LED firmware:
     *   - green_led  (D12) : ON when LIGHT
     *   - yellow_led (D14) : ON when DARK
     */
    public function up(): void
    {
        // 1. Make sure the new `green_led` row exists.
        $greenExists = DB::table('actuator_states')
            ->where('actuator_name', 'green_led')
            ->exists();

        if (! $greenExists) {
            // Carry over the previous state/controlled_by from the old red_led
            // row if it exists, otherwise insert a sensible default.
            $red = DB::table('actuator_states')
                ->where('actuator_name', 'red_led')
                ->first();

            DB::table('actuator_states')->insert([
                'actuator_name'  => 'green_led',
                'state'          => $red ? $red->state : 0,
                'controlled_by'  => $red ? $red->controlled_by : 'system',
                'created_at'     => $red && $red->created_at ? $red->created_at : now(),
                'updated_at'     => now(),
            ]);
        }

        // 2. Remove the old `red_led` row (no longer used by ESP32).
        DB::table('actuator_states')
            ->where('actuator_name', 'red_led')
            ->delete();
    }

    public function down(): void
    {
        // Re-create the `red_led` row from the current `green_led` row.
        $green = DB::table('actuator_states')
            ->where('actuator_name', 'green_led')
            ->first();

        if ($green) {
            DB::table('actuator_states')->insert([
                'actuator_name'  => 'red_led',
                'state'          => $green->state,
                'controlled_by'  => $green->controlled_by,
                'created_at'     => $green->created_at,
                'updated_at'     => $green->updated_at,
            ]);

            DB::table('actuator_states')
                ->where('actuator_name', 'green_led')
                ->delete();
        }
    }
};
