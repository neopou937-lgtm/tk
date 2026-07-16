<?php

use App\Models\Alumni;
use App\Models\Announcement;
use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\BopTransaction;
use App\Models\Donor;
use App\Models\GalleryItem;
use App\Models\PpdbApplication;
use App\Models\SavingsTransaction;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeachingJournal;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia;

test('admin dashboard uses real sql aggregate stats', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create(['status' => 'aktif']);

    Teacher::create(['name' => 'Bu Guru', 'status' => 'aktif']);
    Bill::create(['student_id' => $student->id, 'nipd' => $student->nipd, 'title' => 'SPP', 'amount' => 150000, 'paid_amount' => 0, 'status' => 'pending']);
    Bill::create(['student_id' => $student->id, 'nipd' => $student->nipd, 'title' => 'Seragam', 'amount' => 100000, 'paid_amount' => 100000, 'status' => 'paid']);
    SavingsTransaction::create(['student_id' => $student->id, 'nipd' => $student->nipd, 'student_name' => $student->name, 'type' => 'setor', 'amount' => 50000]);
    SavingsTransaction::create(['student_id' => $student->id, 'nipd' => $student->nipd, 'student_name' => $student->name, 'type' => 'tarik', 'amount' => 10000]);
    BopTransaction::create(['type' => 'debit', 'amount' => 300000]);
    BopTransaction::create(['type' => 'kredit', 'amount' => 75000]);
    PpdbApplication::create(['child_name' => 'Calon Siswa', 'status' => 'pending']);
    Alumni::create(['student_id' => $student->id, 'name' => 'Alumni Satu', 'graduation_year' => 2026]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertOk()->assertInertia(fn (AssertableInertia $page) => $page
        ->component('admin/dashboard')
        ->where('stats.students', 1)
        ->where('stats.teachers', 1)
        ->where('stats.pendingBills', 1)
        ->where('stats.paidPayments', 1)
        ->where('stats.savings', 40000)
        ->where('stats.bopFunds', 225000)
        ->where('stats.pendingPpdb', 1)
        ->where('stats.alumni', 1)
    );
});

test('admin can store migrated module records', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create(['name' => 'Ahmad Fajar', 'nipd' => 'NIPD-100']);

    $this->actingAs($user)->post(route('admin.teachers.store'), [
        'name' => 'Bu Siti',
        'employee_number' => 'GTK-001',
        'email' => 'siti@example.test',
        'status' => 'aktif',
    ])->assertSessionHasNoErrors();

    $teacher = Teacher::firstOrFail();

    $this->actingAs($user)->post(route('admin.ppdb.store'), [
        'registration_number' => 'PPDB-001',
        'child_name' => 'Calon Murid',
        'parent_name' => 'Bapak Calon',
        'status' => 'pending',
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.bills.store'), [
        'student_id' => $student->id,
        'nipd' => '',
        'nisn' => '',
        'title' => 'SPP Juli',
        'amount' => 125000,
        'paid_amount' => 0,
        'status' => 'pending',
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.savings.store'), [
        'student_id' => $student->id,
        'nipd' => '',
        'student_name' => '',
        'type' => 'setor',
        'amount' => 25000,
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.bop-finance.store'), [
        'type' => 'debit',
        'amount' => 500000,
        'description' => 'Dana BOP awal',
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.student-attendance.store'), [
        'student_id' => $student->id,
        'nipd' => '',
        'attendance_date' => '2026-07-16',
        'status' => 'hadir',
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.teacher-attendance.store'), [
        'teacher_id' => $teacher->id,
        'attendance_date' => '2026-07-16',
        'status' => 'hadir',
        'check_in_at' => '07:00',
        'check_out_at' => '11:00',
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.announcements.store'), [
        'title' => 'Libur Sekolah',
        'body' => 'Besok kegiatan belajar diliburkan.',
        'audience' => 'all',
        'status' => 'published',
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.gallery.store'), [
        'title' => 'Kegiatan Bermain',
        'media_type' => 'image',
        'media_url' => 'https://example.test/foto.jpg',
        'status' => 'published',
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.teaching-journals.store'), [
        'teacher_id' => $teacher->id,
        'journal_date' => '2026-07-16',
        'class_name' => 'A',
        'topic' => 'Mengenal warna',
    ])->assertSessionHasNoErrors();

    $this->actingAs($user)->post(route('admin.donors.store'), [
        'name' => 'Hamba Allah',
        'amount' => 100000,
        'status' => 'verified',
    ])->assertSessionHasNoErrors();

    expect(Teacher::where('employee_number', 'GTK-001')->exists())->toBeTrue();
    expect(PpdbApplication::where('registration_number', 'PPDB-001')->exists())->toBeTrue();
    expect(Bill::where('title', 'SPP Juli')->first()?->nipd)->toBe('NIPD-100');
    expect(SavingsTransaction::where('type', 'setor')->first()?->student_name)->toBe('Ahmad Fajar');
    expect(BopTransaction::where('description', 'Dana BOP awal')->exists())->toBeTrue();
    expect(StudentAttendance::where('nipd', 'NIPD-100')->exists())->toBeTrue();
    expect(TeacherAttendance::where('teacher_id', $teacher->id)->exists())->toBeTrue();
    expect(Announcement::where('title', 'Libur Sekolah')->exists())->toBeTrue();
    expect(GalleryItem::where('title', 'Kegiatan Bermain')->exists())->toBeTrue();
    expect(TeachingJournal::where('topic', 'Mengenal warna')->exists())->toBeTrue();
    expect(Donor::where('name', 'Hamba Allah')->exists())->toBeTrue();
});

test('graduating a student creates alumni and archives finance records', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create(['status' => 'aktif', 'nipd' => 'NIPD-200']);

    $bill = Bill::create(['student_id' => $student->id, 'nipd' => $student->nipd, 'title' => 'SPP', 'amount' => 150000, 'paid_amount' => 0, 'status' => 'pending']);
    $saving = SavingsTransaction::create(['student_id' => $student->id, 'nipd' => $student->nipd, 'student_name' => $student->name, 'type' => 'setor', 'amount' => 40000]);

    $this->actingAs($user)
        ->post(route('admin.students.graduate', $student))
        ->assertRedirect(route('admin.students.index'));

    $alumni = Alumni::where('student_id', $student->id)->firstOrFail();

    expect($student->refresh()->status)->toBe('lulus');
    expect($alumni->total_savings)->toBe('40000.00');
    expect($bill->refresh()->alumni_id)->toBe($alumni->id);
    expect($saving->refresh()->alumni_id)->toBe($alumni->id);
});

test('midtrans transaction and webhook update bill status', function () {
    config()->set('services.midtrans.server_key', 'server-key');

    Http::fake([
        'app.sandbox.midtrans.com/*' => Http::response([
            'token' => 'snap-token',
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/snap-token',
        ]),
    ]);

    $user = User::factory()->create(['email' => 'admin@example.test']);
    $student = Student::factory()->create();
    $bill = Bill::create([
        'student_id' => $student->id,
        'nipd' => $student->nipd,
        'title' => 'SPP Juli',
        'amount' => 125000,
        'paid_amount' => 0,
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->postJson(route('admin.bills.midtrans', $bill))
        ->assertOk()
        ->assertJsonPath('snapToken', 'snap-token');

    $bill->refresh();

    expect($bill->midtrans_order_id)->not->toBeNull();
    expect($bill->payment_status)->toBe('pending');

    $this->postJson(route('midtrans.notification'), [
        'order_id' => $bill->midtrans_order_id,
        'transaction_status' => 'settlement',
        'gross_amount' => '125000.00',
    ])->assertOk();

    expect($bill->refresh()->status)->toBe('paid');
    expect($bill->paid_amount)->toBe('125000.00');
    expect(BillPayment::where('bill_id', $bill->id)->where('method', 'midtrans')->exists())->toBeTrue();
});

test('midtrans refuses paid bills', function () {
    config()->set('services.midtrans.server_key', 'server-key');

    $user = User::factory()->create();
    $bill = Bill::create([
        'title' => 'SPP Lunas',
        'amount' => 125000,
        'paid_amount' => 125000,
        'status' => 'paid',
    ]);

    $this->actingAs($user)
        ->postJson(route('admin.bills.midtrans', $bill))
        ->assertStatus(422)
        ->assertJsonPath('error', 'Tagihan sudah lunas.');
});
