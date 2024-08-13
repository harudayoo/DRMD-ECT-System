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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreignId('adminID')->nullable()->constrained('admins')->onDelete('cascade');
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id('provinceID');
            $table->string('provinceName');
            $table->integer('totalBeneficiaries')->nullable();
            $table->decimal('totalAmountReleased', 15, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('municipalities', function (Blueprint $table) {
            $table->id('municipalityID');
            $table->foreignId('provinceID')->constrained('provinces')->onDelete('cascade');
            $table->string('municipalityName');
            $table->integer('totalBeneficiaries')->nullable();
            $table->decimal('totalAmountReleased', 15, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('barangays', function (Blueprint $table) {
            $table->id('barangayID');
            $table->foreignId('municipalityID')->constrained('municipalities')->onDelete('cascade');
            $table->string('barangayName');
            $table->integer('totalBeneficiaries')->nullable();
            $table->decimal('totalAmountReleased', 15, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id('beneficiaryID');
            $table->foreignId('barangayID')->constrained('barangays')->onDelete('cascade');
            $table->string('beneficiaryNumber')->unique();
            $table->string('lastName');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('extensionName')->nullable();
            $table->date('dateOfBirth');
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('payrolls', function (Blueprint $table) {
            $table->id('payrollID');
            $table->string('payrollNumber')->unique();
            $table->string('projectName');
            $table->date('date');
            $table->decimal('subTotal', 15, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id('payrollDetailID');
            $table->foreignId('payrollID')->constrained('payrolls')->onDelete('cascade');
            $table->foreignId('beneficiaryID')->constrained('beneficiaries')->onDelete('cascade');
            $table->string('beneficiaryPayrollNumber')->unique();
            $table->decimal('amount', 15, 2)->nullable();
            $table->date('signatureDate')->nullable();
            $table->date('datePaid')->nullable();
            $table->string('claimStub')->nullable();
            $table->timestamps();
        });

        Schema::create('report_of_cash_disbursements', function (Blueprint $table) {
            $table->id('ReportOfCashDisbursementID');
            $table->string('fundCluster');
            $table->string('reportNumber');
            $table->date('dateCovered');
            $table->timestamps();
        });

        Schema::create('report_of_cash_disbursement_details', function (Blueprint $table) {
            $table->id('ReportOfCashDisbursementDetailID');
            $table->foreignId('ReportOfCashDisbursementID')->constrained('report_of_cash_disbursements')->onDelete('cascade');
            $table->date('date');
            $table->string('orDVNumber')->unique();
            $table->string('responsibilityCenterCode');
            $table->string('payee');
            $table->string('useObjectCode');
            $table->string('natureOfPayment');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('cash_disbursement_records', function (Blueprint $table) {
            $table->id('cashDisbursementRecordID');
            $table->string('row1');
            $table->string('row2');
            $table->string('row3');
            $table->string('entityName');
            $table->string('fundCluster');
            $table->string('sheetNumber');
            $table->string('accountableOfficer');
            $table->string('officialDesignation');
            $table->string('station');
            $table->string('monthOfForwarding');
            $table->decimal('initialCashBalance', 15, 2);
            $table->timestamps();
        });

        Schema::create('cash_dis_rec_details', function (Blueprint $table) {
            $table->id('cashDisRecDetailID');
            $table->foreignId('cashDisbursementRecordID')->constrained('cash_disbursement_records')->onDelete('cascade');
            $table->date('date');
            $table->string('referenceNumber')->unique();
            $table->string('payee');
            $table->string('useObjectCode');
            $table->string('natureOfPayment');
            $table->decimal('cashAdvanceReceived', 15, 2);
            $table->decimal('disbursements', 15, 2);
            $table->decimal('cashAdvanceBalance', 15, 2);
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

       Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('cash_dis_rec_details');
        Schema::dropIfExists('cash_disbursement_records');
        Schema::dropIfExists('report_of_cash_disbursement_details');
        Schema::dropIfExists('report_of_cash_disbursements');
        Schema::dropIfExists('payroll_details');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('beneficiaries');
        Schema::dropIfExists('barangays');
        Schema::dropIfExists('municipalities');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('users');
        Schema::dropIfExists('admins');
    }
};
