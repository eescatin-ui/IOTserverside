<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actuator_states', function (Blueprint $table) {
            $table->id();
            $table->string('actuator_name');  // led, buzzer
            $table->boolean('state')->default(false);  // on/off
            $table->string('controlled_by')->default('mobile'); // mobile, web, auto
            $table->timestamps();
        });

        // Insert default actuators
        DB::table('actuator_states')->insert([
            ['actuator_name' => 'led', 'state' => false, 'controlled_by' => 'system'],
            ['actuator_name' => 'buzzer', 'state' => false, 'controlled_by' => 'system'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('actuator_states');
    }
};