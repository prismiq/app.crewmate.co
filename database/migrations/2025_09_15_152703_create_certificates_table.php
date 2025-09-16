<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crew_id')->constrained('crews')->cascadeOnDelete();
            $table->foreignId('vessel_id')->nullable()->constrained('vessels')->nullOnDelete();
            $table->foreignId('certificate_type_id')->constrained('certificate_types');
            $table->string('reference')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('valid');
            $table->boolean('flagged')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['crew_id','certificate_type_id','reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};

