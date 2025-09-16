<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crew_vessel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crew_id')->constrained('crews')->cascadeOnDelete();
            $table->foreignId('vessel_id')->constrained('vessels')->cascadeOnDelete();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->timestamps();
            $table->unique(['crew_id','vessel_id','started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crew_vessel');
    }
};

