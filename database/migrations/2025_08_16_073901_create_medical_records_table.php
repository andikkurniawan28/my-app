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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->date('date');

            // Pengukuran dasar
            $table->float('weight')->nullable(); // kg
            $table->float('height')->nullable(); // cm
            $table->integer('blood_pressure_systolic')->nullable(); // Tekanan darah atas
            $table->integer('blood_pressure_diastolic')->nullable(); // Tekanan darah bawah
            $table->float('temperature')->nullable(); // °C
            $table->integer('pulse')->nullable(); // denyut per menit

            // Gula darah (indikator diabetes)
            $table->float('blood_sugar_fasting')->nullable();
            $table->float('blood_sugar_random')->nullable();
            $table->float('hba1c')->nullable(); // rata-rata gula darah 3 bulan

            // Profil lipid (indikator penyakit jantung & pembuluh darah)
            $table->float('cholesterol_total')->nullable();
            $table->float('cholesterol_hdl')->nullable();
            $table->float('cholesterol_ldl')->nullable();
            $table->float('triglycerides')->nullable();

            // Fungsi ginjal
            $table->float('creatinine')->nullable(); // mg/dL
            $table->float('bun')->nullable(); // Blood Urea Nitrogen
            $table->float('egfr')->nullable(); // Estimasi fungsi ginjal

            // Catatan tambahan
            $table->text('notes')->nullable();

            // Timestamp
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
