import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Save } from 'lucide-react';
import { DateInput } from '@/components/admin/date-input';
import { FileInput } from '@/components/admin/file-input';
import { FormSection } from '@/components/admin/form-section';
import { PrimaryButton } from '@/components/admin/primary-button';
import { SelectInput } from '@/components/admin/select-input';
import { TextInput } from '@/components/admin/text-input';
import { Button } from '@/components/ui/button';
import type { Student } from '@/types';

type StudentFormProps = {
    student?: Student;
};

type StudentFormData = {
    name: string;
    nipd: string;
    nisn: string;
    student_nik: string;
    level: string;
    class_name: string;
    gender: string;
    birth_place: string;
    birth_date: string;
    religion: string;
    address: string;
    child_order: string;
    father_name: string;
    father_nik: string;
    father_phone: string;
    mother_name: string;
    mother_nik: string;
    mother_phone: string;
    student_photo: File | null;
    family_card_photo: File | null;
    parent_id_card_photo: File | null;
    status: string;
};

const levelOptions = [
    { label: 'PAUD', value: 'PAUD' },
    { label: 'Kelompok Bermain', value: 'KB' },
    { label: 'TK A', value: 'TK A' },
    { label: 'TK B', value: 'TK B' },
];

const classOptions = [
    { label: 'A', value: 'A' },
    { label: 'B', value: 'B' },
    { label: 'C', value: 'C' },
];

const genderOptions = [
    { label: 'Laki-laki', value: 'laki-laki' },
    { label: 'Perempuan', value: 'perempuan' },
];

const religionOptions = [
    { label: 'Islam', value: 'Islam' },
    { label: 'Kristen', value: 'Kristen' },
    { label: 'Katolik', value: 'Katolik' },
    { label: 'Hindu', value: 'Hindu' },
    { label: 'Buddha', value: 'Buddha' },
    { label: 'Konghucu', value: 'Konghucu' },
];

const statusOptions = [
    { label: 'Aktif', value: 'aktif' },
    { label: 'Lulus', value: 'lulus' },
    { label: 'Nonaktif', value: 'nonaktif' },
];

export default function StudentForm({ student }: StudentFormProps) {
    const isEdit = Boolean(student);
    const form = useForm<StudentFormData>({
        name: student?.name ?? '',
        nipd: student?.nipd ?? '',
        nisn: student?.nisn ?? '',
        student_nik: student?.student_nik ?? '',
        level: student?.level ?? 'PAUD',
        class_name: student?.class_name ?? 'A',
        gender: student?.gender ?? '',
        birth_place: student?.birth_place ?? '',
        birth_date: student?.birth_date?.slice(0, 10) ?? '',
        religion: student?.religion ?? 'Islam',
        address: student?.address ?? '',
        child_order: student?.child_order ? String(student.child_order) : '',
        father_name: student?.father_name ?? '',
        father_nik: student?.father_nik ?? '',
        father_phone: student?.father_phone ?? '',
        mother_name: student?.mother_name ?? '',
        mother_nik: student?.mother_nik ?? '',
        mother_phone: student?.mother_phone ?? '',
        student_photo: null,
        family_card_photo: null,
        parent_id_card_photo: null,
        status: student?.status ?? 'aktif',
    });

    function updateField<K extends keyof StudentFormData>(
        key: K,
        value: StudentFormData[K],
    ) {
        form.setData((data) => ({ ...data, [key]: value }));
    }

    function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();

        if (student) {
            form.transform((data) => ({ ...data, _method: 'put' }));
            form.post(`/admin/students/${student.id}`, {
                forceFormData: true,
                preserveScroll: true,
            });

            return;
        }

        form.post('/admin/students', {
            forceFormData: true,
            preserveScroll: true,
        });
    }

    return (
        <>
            <Head title={isEdit ? 'Edit Siswa' : 'Tambah Siswa'} />

            <form onSubmit={handleSubmit} className="space-y-4">
                <div className="flex flex-col gap-3 rounded-[8px] border border-sky-100 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <Link
                            href="/admin/students"
                            className="inline-flex items-center gap-2 text-sm font-semibold text-sky-700"
                        >
                            <ArrowLeft className="size-4" />
                            Kembali ke Data Siswa
                        </Link>
                        <h2 className="mt-2 text-xl font-semibold text-slate-950">
                            {isEdit ? 'Edit Data Siswa' : 'Tambah Data Siswa'}
                        </h2>
                        <p className="mt-1 text-sm text-slate-500">
                            Isi data siswa, orang tua, dan berkas penting dengan
                            lengkap.
                        </p>
                    </div>
                    <PrimaryButton
                        type="submit"
                        disabled={form.processing}
                        className="hidden sm:inline-flex"
                    >
                        <Save className="size-4" />
                        {form.processing ? 'Menyimpan...' : 'Simpan'}
                    </PrimaryButton>
                </div>

                <FormSection
                    title="Data Utama"
                    description="Informasi akademik utama siswa."
                >
                    <TextInput
                        id="name"
                        label="Nama siswa"
                        value={form.data.name}
                        error={form.errors.name}
                        onChange={(event) =>
                            updateField('name', event.target.value)
                        }
                        required
                    />
                    <TextInput
                        id="nipd"
                        label="NIPD"
                        value={form.data.nipd}
                        error={form.errors.nipd}
                        onChange={(event) =>
                            updateField('nipd', event.target.value)
                        }
                        required
                    />
                    <TextInput
                        id="nisn"
                        label="NISN"
                        value={form.data.nisn}
                        error={form.errors.nisn}
                        onChange={(event) =>
                            updateField('nisn', event.target.value)
                        }
                    />
                    <TextInput
                        id="student_nik"
                        label="NIK siswa"
                        value={form.data.student_nik}
                        error={form.errors.student_nik}
                        onChange={(event) =>
                            updateField('student_nik', event.target.value)
                        }
                    />
                    <SelectInput
                        id="level"
                        label="Jenjang"
                        value={form.data.level}
                        error={form.errors.level}
                        options={levelOptions}
                        onChange={(event) =>
                            updateField('level', event.target.value)
                        }
                        required
                    />
                    <SelectInput
                        id="class_name"
                        label="Kelas"
                        value={form.data.class_name}
                        error={form.errors.class_name}
                        options={classOptions}
                        onChange={(event) =>
                            updateField('class_name', event.target.value)
                        }
                        required
                    />
                </FormSection>

                <FormSection
                    title="Identitas Pribadi"
                    description="Data lahir dan alamat siswa."
                >
                    <SelectInput
                        id="gender"
                        label="Jenis kelamin"
                        value={form.data.gender}
                        error={form.errors.gender}
                        options={genderOptions}
                        onChange={(event) =>
                            updateField('gender', event.target.value)
                        }
                        required
                    />
                    <TextInput
                        id="birth_place"
                        label="Tempat lahir"
                        value={form.data.birth_place}
                        error={form.errors.birth_place}
                        onChange={(event) =>
                            updateField('birth_place', event.target.value)
                        }
                    />
                    <DateInput
                        id="birth_date"
                        label="Tanggal lahir"
                        value={form.data.birth_date}
                        error={form.errors.birth_date}
                        onChange={(event) =>
                            updateField('birth_date', event.target.value)
                        }
                    />
                    <SelectInput
                        id="religion"
                        label="Agama"
                        value={form.data.religion}
                        error={form.errors.religion}
                        options={religionOptions}
                        onChange={(event) =>
                            updateField('religion', event.target.value)
                        }
                    />
                    <TextInput
                        id="child_order"
                        label="Anak ke"
                        type="number"
                        min="1"
                        value={form.data.child_order}
                        error={form.errors.child_order}
                        onChange={(event) =>
                            updateField('child_order', event.target.value)
                        }
                    />
                    <TextInput
                        id="address"
                        label="Alamat"
                        value={form.data.address}
                        error={form.errors.address}
                        onChange={(event) =>
                            updateField('address', event.target.value)
                        }
                        wrapperClassName="md:col-span-2"
                    />
                </FormSection>

                <FormSection
                    title="Data Orang Tua"
                    description="Kontak dan identitas ayah serta ibu."
                >
                    <TextInput
                        id="father_name"
                        label="Nama ayah"
                        value={form.data.father_name}
                        error={form.errors.father_name}
                        onChange={(event) =>
                            updateField('father_name', event.target.value)
                        }
                    />
                    <TextInput
                        id="father_nik"
                        label="NIK ayah"
                        value={form.data.father_nik}
                        error={form.errors.father_nik}
                        onChange={(event) =>
                            updateField('father_nik', event.target.value)
                        }
                    />
                    <TextInput
                        id="father_phone"
                        label="No HP ayah"
                        value={form.data.father_phone}
                        error={form.errors.father_phone}
                        onChange={(event) =>
                            updateField('father_phone', event.target.value)
                        }
                    />
                    <TextInput
                        id="mother_name"
                        label="Nama ibu"
                        value={form.data.mother_name}
                        error={form.errors.mother_name}
                        onChange={(event) =>
                            updateField('mother_name', event.target.value)
                        }
                    />
                    <TextInput
                        id="mother_nik"
                        label="NIK ibu"
                        value={form.data.mother_nik}
                        error={form.errors.mother_nik}
                        onChange={(event) =>
                            updateField('mother_nik', event.target.value)
                        }
                    />
                    <TextInput
                        id="mother_phone"
                        label="No HP ibu"
                        value={form.data.mother_phone}
                        error={form.errors.mother_phone}
                        onChange={(event) =>
                            updateField('mother_phone', event.target.value)
                        }
                    />
                </FormSection>

                <FormSection
                    title="Upload Berkas"
                    description="Unggah foto siswa, KK, dan KTP orang tua."
                >
                    <FileInput
                        id="student_photo"
                        label="Foto siswa"
                        existingUrl={student?.student_photo_url}
                        error={form.errors.student_photo}
                        onChange={(file) => updateField('student_photo', file)}
                    />
                    <FileInput
                        id="family_card_photo"
                        label="Foto KK"
                        existingUrl={student?.family_card_photo_url}
                        error={form.errors.family_card_photo}
                        onChange={(file) =>
                            updateField('family_card_photo', file)
                        }
                    />
                    <FileInput
                        id="parent_id_card_photo"
                        label="Foto KTP orang tua"
                        existingUrl={student?.parent_id_card_photo_url}
                        error={form.errors.parent_id_card_photo}
                        onChange={(file) =>
                            updateField('parent_id_card_photo', file)
                        }
                    />
                </FormSection>

                <FormSection
                    title="Status"
                    description="Tentukan status siswa di sekolah."
                >
                    <SelectInput
                        id="status"
                        label="Status siswa"
                        value={form.data.status}
                        error={form.errors.status}
                        options={statusOptions}
                        onChange={(event) =>
                            updateField('status', event.target.value)
                        }
                        required
                    />
                </FormSection>

                <div className="sticky bottom-16 z-10 rounded-[8px] border border-sky-100 bg-white/95 p-3 shadow-lg backdrop-blur sm:hidden">
                    <PrimaryButton
                        type="submit"
                        disabled={form.processing}
                        className="w-full"
                    >
                        <Save className="size-4" />
                        {form.processing ? 'Menyimpan...' : 'Simpan'}
                    </PrimaryButton>
                </div>

                <div className="hidden justify-end sm:flex">
                    <Button
                        type="button"
                        variant="outline"
                        asChild
                        className="min-h-11 rounded-[8px] border-sky-100 text-sky-700"
                    >
                        <Link href="/admin/students">Batal</Link>
                    </Button>
                </div>
            </form>
        </>
    );
}
