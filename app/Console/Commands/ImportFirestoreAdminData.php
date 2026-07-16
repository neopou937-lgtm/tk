<?php

namespace App\Console\Commands;

use App\Models\Alumni;
use App\Models\Announcement;
use App\Models\Bill;
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
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class ImportFirestoreAdminData extends Command
{
    protected $signature = 'admin:import-firestore {--path= : JSON export path containing Firestore collections}';

    protected $description = 'Import admin data exported from the Flutter Firestore project into Laravel SQL tables.';

    public function handle(): int
    {
        $path = (string) $this->option('path');
        if ($path === '' || ! File::exists($path)) {
            $this->error('Gunakan --path=export.json. Import tidak menghapus data Firestore lama.');

            return self::FAILURE;
        }

        $payload = json_decode(File::get($path), true);
        if (! is_array($payload)) {
            $this->error('File JSON tidak valid.');

            return self::FAILURE;
        }

        $this->importStudents($payload['students'] ?? []);
        $this->importTeachers($payload['teachers'] ?? []);
        $this->importPpdb($payload['ppdb_tk_kober'] ?? []);
        $this->importBills($payload['bills'] ?? []);
        $this->importSavings($payload['tabungan'] ?? []);
        $this->importBop($payload['bop'] ?? []);
        $this->importStudentAttendances($payload['attendances'] ?? []);
        $this->importTeacherAttendances($payload['teacher_attendances'] ?? []);
        $this->importAnnouncements($payload['notifications'] ?? []);
        $this->importGallery($payload['galeri'] ?? []);
        $this->importAlumni($payload['alumni'] ?? []);
        $this->importTeachingJournals($payload['teaching_journals'] ?? []);
        $this->importDonors($payload['donatur'] ?? []);

        $this->info('Import selesai. Jalankan pengecekan data di admin panel.');

        return self::SUCCESS;
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importStudents(iterable $rows): void
    {
        foreach ($rows as $row) {
            $id = $this->docId($row);
            $nipd = $this->text($row, ['nipd', 'NIPD', 'nisn'], $id);

            Student::updateOrCreate(['nipd' => $nipd], [
                'source_firestore_id' => $id,
                'name' => $this->text($row, ['nama_siswa', 'nama', 'name'], 'Tanpa Nama'),
                'nisn' => $this->text($row, ['nisn'], null),
                'student_nik' => $this->text($row, ['nik_siswa', 'student_nik'], null),
                'level' => $this->text($row, ['jenjang', 'level'], 'PAUD'),
                'class_name' => $this->text($row, ['kelas', 'class_name'], '-'),
                'gender' => $this->text($row, ['jenis_kelamin', 'gender'], 'laki-laki'),
                'birth_place' => $this->text($row, ['tempat_lahir', 'birth_place'], null),
                'birth_date' => $this->date($row, ['tanggal_lahir', 'birth_date']),
                'religion' => $this->text($row, ['agama', 'religion'], null),
                'address' => $this->text($row, ['alamat', 'address'], null),
                'child_order' => (int) ($row['anak_ke'] ?? 0) ?: null,
                'father_name' => Arr::get($row, 'ayah.nama'),
                'father_nik' => Arr::get($row, 'ayah.nik'),
                'father_phone' => Arr::get($row, 'ayah.hp'),
                'mother_name' => Arr::get($row, 'ibu.nama'),
                'mother_nik' => Arr::get($row, 'ibu.nik'),
                'mother_phone' => Arr::get($row, 'ibu.hp'),
                'student_photo_url' => $this->text($row, ['url_foto_siswa', 'foto_url'], null),
                'family_card_photo_url' => $this->text($row, ['url_foto_kk'], null),
                'parent_id_card_photo_url' => $this->text($row, ['url_foto_ktp_orangtua'], null),
                'status' => $this->text($row, ['status_siswa', 'status'], 'aktif'),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importTeachers(iterable $rows): void
    {
        foreach ($rows as $row) {
            Teacher::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'name' => $this->text($row, ['nama', 'name'], 'Tanpa Nama'),
                'employee_number' => $this->text($row, ['nip', 'employee_number'], null),
                'email' => $this->text($row, ['email'], null),
                'phone' => $this->text($row, ['hp', 'phone'], null),
                'subject' => $this->text($row, ['mapel', 'subject'], null),
                'class_name' => $this->text($row, ['kelas', 'class_name'], null),
                'photo_url' => $this->text($row, ['foto_url', 'photo_url'], null),
                'status' => $this->text($row, ['status'], 'aktif'),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importPpdb(iterable $rows): void
    {
        foreach ($rows as $row) {
            PpdbApplication::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'registration_number' => $this->text($row, ['no_pendaftaran', 'registration_number'], null),
                'child_name' => $this->text($row, ['nama_siswa', 'nama_anak', 'child_name'], 'Tanpa Nama'),
                'nipd' => $this->text($row, ['nipd', 'NIPD'], null),
                'level' => $this->text($row, ['jenjang', 'level'], null),
                'class_name' => $this->text($row, ['kelas', 'class_name'], null),
                'gender' => $this->text($row, ['jenis_kelamin', 'gender'], null),
                'birth_date' => $this->date($row, ['tanggal_lahir', 'birth_date']),
                'address' => $this->text($row, ['alamat', 'address'], null),
                'parent_name' => $this->text($row, ['nama_orangtua', 'parent_name'], null),
                'parent_phone' => $this->text($row, ['no_hp', 'parent_phone'], null),
                'status' => $this->text($row, ['status'], 'pending'),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importBills(iterable $rows): void
    {
        foreach ($rows as $row) {
            $student = $this->studentByIdentity($row);
            Bill::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'student_id' => $student?->id,
                'nipd' => $this->text($row, ['nipd', 'NIPD'], $student?->nipd),
                'nisn' => $this->text($row, ['nisn'], $student?->nisn),
                'title' => $this->text($row, ['title', 'judul'], 'Tagihan Sekolah'),
                'description' => $this->text($row, ['description', 'keterangan'], null),
                'amount' => (float) ($row['amount'] ?? $row['jumlah'] ?? 0),
                'paid_amount' => (float) ($row['terbayar'] ?? $row['dibayar'] ?? 0),
                'status' => $this->normalizeBillStatus($row['status'] ?? 'pending'),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importSavings(iterable $rows): void
    {
        foreach ($rows as $row) {
            $student = $this->studentByIdentity($row);
            SavingsTransaction::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'student_id' => $student?->id,
                'nipd' => $this->text($row, ['nipd', 'NIPD'], $student?->nipd),
                'student_name' => $this->text($row, ['nama_siswa'], $student?->name),
                'type' => $this->text($row, ['jenis_transaksi', 'type'], 'setor'),
                'amount' => (float) ($row['jumlah'] ?? $row['amount'] ?? 0),
                'description' => $this->text($row, ['keterangan', 'description'], null),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importBop(iterable $rows): void
    {
        foreach ($rows as $row) {
            BopTransaction::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'type' => str((string) ($row['jenis'] ?? $row['type'] ?? 'debit'))->lower()->toString(),
                'amount' => (float) ($row['amount'] ?? $row['jumlah'] ?? 0),
                'description' => $this->text($row, ['keterangan', 'description'], null),
                'transaction_date' => $this->date($row, ['tanggal', 'transaction_date']),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importStudentAttendances(iterable $rows): void
    {
        foreach ($rows as $row) {
            $student = $this->studentByIdentity($row);
            StudentAttendance::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'student_id' => $student?->id,
                'nipd' => $this->text($row, ['nipd', 'NIPD'], $student?->nipd),
                'attendance_date' => $this->date($row, ['tanggal', 'date', 'attendance_date']) ?? now()->toDateString(),
                'status' => $this->text($row, ['status'], 'hadir'),
                'note' => $this->text($row, ['catatan', 'note'], null),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importTeacherAttendances(iterable $rows): void
    {
        foreach ($rows as $row) {
            TeacherAttendance::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'attendance_date' => $this->date($row, ['tanggal', 'date', 'attendance_date']) ?? now()->toDateString(),
                'status' => $this->text($row, ['status'], 'hadir'),
                'note' => $this->text($row, ['catatan', 'note'], null),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importAnnouncements(iterable $rows): void
    {
        foreach ($rows as $row) {
            Announcement::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'title' => $this->text($row, ['title', 'judul'], 'Pengumuman'),
                'body' => $this->text($row, ['body', 'pesan', 'message'], ''),
                'audience' => $this->text($row, ['audience', 'target'], 'all'),
                'status' => 'published',
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importGallery(iterable $rows): void
    {
        foreach ($rows as $row) {
            GalleryItem::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'title' => $this->text($row, ['title', 'judul'], 'Galeri'),
                'description' => $this->text($row, ['description', 'keterangan'], null),
                'media_type' => $this->text($row, ['media_type', 'type'], 'image'),
                'media_url' => $this->text($row, ['url', 'image_url', 'media_url'], null),
                'status' => 'published',
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importAlumni(iterable $rows): void
    {
        foreach ($rows as $row) {
            Alumni::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'name' => $this->text($row, ['nama_siswa', 'name'], 'Tanpa Nama'),
                'nipd' => $this->text($row, ['nipd', 'NIPD'], null),
                'nisn' => $this->text($row, ['nisn'], null),
                'class_name' => $this->text($row, ['kelas', 'class_name'], null),
                'graduation_year' => (int) ($row['tahun_lulus'] ?? now()->year),
                'snapshot' => $row,
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importTeachingJournals(iterable $rows): void
    {
        foreach ($rows as $row) {
            TeachingJournal::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'journal_date' => $this->date($row, ['tanggal', 'journal_date']),
                'class_name' => $this->text($row, ['kelas', 'class_name'], null),
                'topic' => $this->text($row, ['materi', 'topic'], 'Jurnal Mengajar'),
                'notes' => $this->text($row, ['catatan', 'notes'], null),
            ]);
        }
    }

    /**
     * @param  iterable<array<string, mixed>>  $rows
     */
    private function importDonors(iterable $rows): void
    {
        foreach ($rows as $row) {
            Donor::updateOrCreate(['source_firestore_id' => $this->docId($row)], [
                'name' => $this->text($row, ['nama', 'name'], 'Donatur'),
                'phone' => $this->text($row, ['phone', 'hp'], null),
                'amount' => (float) ($row['amount'] ?? $row['jumlah'] ?? 0),
                'status' => $this->text($row, ['status'], 'pending'),
                'note' => $this->text($row, ['note', 'catatan'], null),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $keys
     */
    private function text(array $row, array $keys, ?string $fallback): ?string
    {
        foreach ($keys as $key) {
            $value = Arr::get($row, $key);
            $text = $value === null ? '' : trim((string) $value);
            if ($text !== '') {
                return $text;
            }
        }

        return $fallback;
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $keys
     */
    private function date(array $row, array $keys): ?string
    {
        $value = $this->text($row, $keys, null);
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function docId(array $row): string
    {
        return $this->text($row, ['id', '__docId', 'docId'], null) ?? md5(json_encode($row) ?: uniqid());
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function studentByIdentity(array $row): ?Student
    {
        $identity = $this->text($row, ['student_id', 'nipd', 'NIPD', 'nisn'], null);
        if (! $identity) {
            return null;
        }

        return Student::where('nipd', $identity)
            ->orWhere('nisn', $identity)
            ->first();
    }

    private function normalizeBillStatus(mixed $status): string
    {
        $text = str((string) $status)->lower()->trim()->toString();

        return match ($text) {
            'lunas', 'paid', 'sukses', 'selesai' => 'paid',
            'batal', 'cancelled' => 'cancelled',
            default => 'pending',
        };
    }
}
