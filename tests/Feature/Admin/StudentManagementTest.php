<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;

function validStudentPayload(array $overrides = []): array
{
    return [
        'name' => 'Ahmad Fajar',
        'nipd' => 'NIPD-001',
        'nisn' => '1234567890',
        'student_nik' => '3201010101010001',
        'level' => 'PAUD',
        'class_name' => 'A',
        'gender' => 'laki-laki',
        'birth_place' => 'Bandung',
        'birth_date' => '2021-05-12',
        'religion' => 'Islam',
        'address' => 'Jl. Pendidikan No. 1',
        'child_order' => 1,
        'father_name' => 'Bapak Ahmad',
        'father_nik' => '3201010101010002',
        'father_phone' => '081234567890',
        'mother_name' => 'Ibu Aminah',
        'mother_nik' => '3201010101010003',
        'mother_phone' => '081234567891',
        'status' => 'aktif',
        ...$overrides,
    ];
}

test('admin student create page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('admin.students.create'));

    $response
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('admin/students/form')
        );
});

test('admin can store a student with uploaded files', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('admin.students.store'), validStudentPayload([
            'student_photo' => UploadedFile::fake()->image('student.jpg'),
            'family_card_photo' => UploadedFile::fake()->image('kk.jpg'),
            'parent_id_card_photo' => UploadedFile::fake()->image('ktp.jpg'),
        ]));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.students.index'));

    $student = Student::firstOrFail();

    expect($student->name)->toBe('Ahmad Fajar');
    expect($student->nipd)->toBe('NIPD-001');
    expect($student->student_photo_path)->not->toBeNull();
    expect($student->family_card_photo_path)->not->toBeNull();
    expect($student->parent_id_card_photo_path)->not->toBeNull();

    Storage::disk('public')->assertExists($student->student_photo_path);
    Storage::disk('public')->assertExists($student->family_card_photo_path);
    Storage::disk('public')->assertExists($student->parent_id_card_photo_path);
});

test('nipd must be unique', function () {
    $user = User::factory()->create();

    Student::factory()->create(['nipd' => 'NIPD-001']);

    $response = $this
        ->actingAs($user)
        ->post(route('admin.students.store'), validStudentPayload());

    $response->assertSessionHasErrors('nipd');
});

test('nullable nisn and student nik are unique only when filled', function () {
    $user = User::factory()->create();

    Student::factory()->create([
        'nisn' => '1234567890',
        'student_nik' => '3201010101010001',
    ]);

    $duplicateResponse = $this
        ->actingAs($user)
        ->post(route('admin.students.store'), validStudentPayload([
            'nipd' => 'NIPD-002',
        ]));

    $duplicateResponse->assertSessionHasErrors(['nisn', 'student_nik']);

    $nullableResponse = $this
        ->actingAs($user)
        ->post(route('admin.students.store'), validStudentPayload([
            'nipd' => 'NIPD-003',
            'nisn' => null,
            'student_nik' => null,
        ]));

    $nullableResponse
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.students.index'));
});

test('admin can update a student', function () {
    $user = User::factory()->create();
    $student = Student::factory()->create([
        'nipd' => 'NIPD-001',
        'nisn' => '1234567890',
        'student_nik' => '3201010101010001',
    ]);

    $response = $this
        ->actingAs($user)
        ->put(route('admin.students.update', $student), validStudentPayload([
            'name' => 'Fatimah Zahra',
            'nipd' => 'NIPD-001',
            'nisn' => '1234567890',
            'student_nik' => '3201010101010001',
            'status' => 'lulus',
        ]));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.students.edit', $student));

    expect($student->refresh()->name)->toBe('Fatimah Zahra');
    expect($student->status)->toBe('lulus');
});
