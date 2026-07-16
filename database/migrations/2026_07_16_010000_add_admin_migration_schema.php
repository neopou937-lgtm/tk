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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin')->after('email');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('source_firestore_id')->nullable()->unique()->after('id');
            $table->string('student_photo_url')->nullable()->after('student_photo_path');
            $table->string('family_card_photo_url')->nullable()->after('family_card_photo_path');
            $table->string('parent_id_card_photo_url')->nullable()->after('parent_id_card_photo_path');
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->string('name');
            $table->string('employee_number')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->string('class_name')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('ppdb_applications', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->string('registration_number')->nullable()->unique();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->string('child_name');
            $table->string('nipd')->nullable();
            $table->string('level')->nullable();
            $table->string('class_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('nipd')->nullable()->index();
            $table->string('nisn')->nullable();
            $table->string('class_name')->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->date('graduated_at')->nullable();
            $table->decimal('total_bills', 14, 2)->default(0);
            $table->decimal('total_savings', 14, 2)->default(0);
            $table->json('snapshot')->nullable();
            $table->timestamps();
        });

        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('alumni_id')->nullable()->constrained('alumni')->nullOnDelete();
            $table->string('nipd')->nullable()->index();
            $table->string('nisn')->nullable()->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 14, 2);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->string('status')->default('pending');
            $table->string('payment_status')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('snap_token')->nullable();
            $table->text('snap_redirect_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'due_date']);
        });

        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('method')->default('manual');
            $table->string('status')->default('paid');
            $table->string('reference')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('alumni_id')->nullable()->constrained('alumni')->nullOnDelete();
            $table->string('nipd')->nullable()->index();
            $table->string('student_name')->nullable();
            $table->string('type');
            $table->decimal('amount', 14, 2);
            $table->text('description')->nullable();
            $table->date('transaction_date')->nullable();
            $table->timestamps();
        });

        Schema::create('bop_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->string('type');
            $table->decimal('amount', 14, 2);
            $table->text('description')->nullable();
            $table->date('transaction_date')->nullable();
            $table->timestamps();
        });

        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nipd')->nullable()->index();
            $table->date('attendance_date');
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'attendance_date']);
        });

        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->date('attendance_date');
            $table->string('status');
            $table->time('check_in_at')->nullable();
            $table->time('check_out_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'attendance_date']);
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->string('title');
            $table->text('body');
            $table->string('audience')->default('all');
            $table->string('status')->default('published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('media_type')->default('image');
            $table->string('media_path')->nullable();
            $table->text('media_url')->nullable();
            $table->string('status')->default('published');
            $table->timestamps();
        });

        Schema::create('teaching_journals', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->date('journal_date')->nullable();
            $table->string('class_name')->nullable();
            $table->string('topic');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamp('donated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('source_firestore_id')->nullable()->unique();
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('audience')->default('all');
            $table->string('type')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('donors');
        Schema::dropIfExists('teaching_journals');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('teacher_attendances');
        Schema::dropIfExists('student_attendances');
        Schema::dropIfExists('bop_transactions');
        Schema::dropIfExists('savings_transactions');
        Schema::dropIfExists('bill_payments');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('alumni');
        Schema::dropIfExists('ppdb_applications');
        Schema::dropIfExists('teachers');

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'source_firestore_id',
                'student_photo_url',
                'family_card_photo_url',
                'parent_id_card_photo_url',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
