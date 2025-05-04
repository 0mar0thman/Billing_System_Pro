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
        Schema::create('invoices_sittings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id')->nullable(); // إضافة ارتباط بالبنوك
            $table->foreign('section_id')->references('id')->on('sections');
            $table->decimal('Amount_collection', 8, 2)->default(0); // قيمة التحصيل
            $table->decimal('Amount_Commission', 8, 2)->default(0); //نسبة العمولة
            $table->decimal('Discount_Commission', 8, 2)->default(0); // نسبة الخصم
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_sittings');
    }
};
