import { Head, Link, router } from '@inertiajs/react';
import { Edit, Plus, Trophy } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import type { Student } from '@/types';

type StudentIndexProps = {
    students: Student[];
};

const statusLabels: Record<string, string> = {
    aktif: 'Aktif',
    lulus: 'Lulus',
    nonaktif: 'Nonaktif',
};

export default function StudentIndex({ students }: StudentIndexProps) {
    function graduate(student: Student) {
        if (!confirm(`Luluskan ${student.name} dan arsipkan ke alumni?`)) {
            return;
        }

        router.post(`/admin/students/${student.id}/graduate`, {}, {
            preserveScroll: true,
        });
    }

    return (
        <>
            <Head title="Data Siswa" />

            <div className="space-y-4">
                <div className="flex flex-col gap-3 rounded-[8px] border border-sky-100 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 className="text-xl font-semibold text-slate-950">
                            Data Siswa
                        </h2>
                        <p className="mt-1 text-sm text-slate-500">
                            Kelola data siswa PAUD aktif, alumni, dan nonaktif.
                        </p>
                    </div>
                    <Link
                        href="/admin/students/create"
                        className="inline-flex min-h-11 items-center justify-center gap-2 rounded-[8px] bg-sky-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-sky-700"
                    >
                        <Plus className="size-4" />
                        Tambah Siswa
                    </Link>
                </div>

                <div className="overflow-hidden rounded-[8px] border border-sky-100 bg-white shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-sky-50 text-sm">
                            <thead className="bg-sky-50 text-left text-xs font-semibold tracking-normal text-sky-800 uppercase">
                                <tr>
                                    <th className="px-4 py-3">Nama</th>
                                    <th className="px-4 py-3">NIPD</th>
                                    <th className="px-4 py-3">NISN</th>
                                    <th className="px-4 py-3">Kelas</th>
                                    <th className="px-4 py-3">Status</th>
                                    <th className="px-4 py-3 text-right">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-sky-50">
                                {students.length === 0 ? (
                                    <tr>
                                        <td
                                            colSpan={6}
                                            className="px-4 py-10 text-center text-slate-500"
                                        >
                                            Belum ada data siswa. Tambahkan data
                                            pertama untuk mulai mengelola kelas.
                                        </td>
                                    </tr>
                                ) : (
                                    students.map((student) => (
                                        <tr
                                            key={student.id}
                                            className="hover:bg-sky-50/50"
                                        >
                                            <td className="px-4 py-3 font-semibold text-slate-950">
                                                {student.name}
                                            </td>
                                            <td className="px-4 py-3 text-slate-600">
                                                {student.nipd}
                                            </td>
                                            <td className="px-4 py-3 text-slate-600">
                                                {student.nisn ?? '-'}
                                            </td>
                                            <td className="px-4 py-3 text-slate-600">
                                                {student.level}{' '}
                                                {student.class_name}
                                            </td>
                                            <td className="px-4 py-3">
                                                <Badge
                                                    className={
                                                        student.status ===
                                                        'aktif'
                                                            ? 'border-emerald-100 bg-emerald-50 text-emerald-700'
                                                            : 'border-slate-100 bg-slate-100 text-slate-700'
                                                    }
                                                    variant="outline"
                                                >
                                                    {statusLabels[
                                                        student.status
                                                    ] ?? student.status}
                                                </Badge>
                                            </td>
                                            <td className="px-4 py-3 text-right">
                                                <div className="flex justify-end gap-2">
                                                    {student.status ===
                                                        'aktif' && (
                                                        <button
                                                            type="button"
                                                            onClick={() =>
                                                                graduate(
                                                                    student,
                                                                )
                                                            }
                                                            className="inline-flex min-h-10 items-center justify-center gap-2 rounded-[8px] border border-emerald-100 px-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-50"
                                                        >
                                                            <Trophy className="size-4" />
                                                            Luluskan
                                                        </button>
                                                    )}
                                                    <Link
                                                        href={`/admin/students/${student.id}/edit`}
                                                        className="inline-flex min-h-10 items-center justify-center gap-2 rounded-[8px] border border-sky-100 px-3 text-sm font-semibold text-sky-700 hover:bg-sky-50"
                                                    >
                                                        <Edit className="size-4" />
                                                        Edit
                                                    </Link>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </>
    );
}
