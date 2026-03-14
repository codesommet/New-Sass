<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('account_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Company info
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_country')->nullable();
            $table->string('sector')->nullable();
            $table->string('employees_count')->nullable();

            // Contact person
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();

            // Request details
            $table->text('message')->nullable();
            $table->string('ip_address')->nullable();

            // Status management
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->uuid('handled_by')->nullable();
            $table->dateTime('handled_at')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            $table->foreign('handled_by')->references('id')->on('users')->nullOnDelete();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_requests');
    }
};
