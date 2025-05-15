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
        Schema::table('ttus', function (Blueprint $table) {
            $table->string('manufacturer')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->year('year')->nullable();
            $table->string('county')->nullable();
            $table->string('imei')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('disposition')->nullable();
            $table->string('transport_agency')->nullable();
            $table->string('recipient_type')->nullable();
            $table->string('agency')->nullable();
            $table->string('donation_category')->nullable();
            $table->text('remarks')->nullable();
            $table->text('comments')->nullable();
            $table->string('fema_id')->nullable();
            $table->string('name')->nullable();
            $table->string('lo')->nullable();
            $table->date('lo_date')->nullable();
            $table->date('est_lo_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ttus', function (Blueprint $table) {
            $table->dropColumn([
                'manufacturer',
                'brand',
                'model',
                'year',
                'county',
                'imei',
                'purchase_price',
                'disposition',
                'transport_agency',
                'recipient_type',
                'agency',
                'donation_category',
                'remarks',
                'comments',
                'fema_id',
                'name',
                'lo',
                'lo_date',
                'est_lo_date',
            ]);
        });
    }
};
