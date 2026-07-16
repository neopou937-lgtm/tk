import { Head, Link, router } from '@inertiajs/react';
import { CreditCard, Edit, Plus, Trash2 } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { AdminColumn, AdminRecord } from '@/types';

type ResourceIndexProps = {
    title: string;
    singularTitle: string;
    baseUrl: string;
    columns: AdminColumn[];
    records: AdminRecord[];
};

export default function ResourceIndex({
    title,
    singularTitle,
    baseUrl,
    columns,
    records,
}: ResourceIndexProps) {
    function destroy(record: AdminRecord) {
        if (!confirm(`Hapus ${singularTitle} ini?`)) {
            return;
        }

        router.delete(`${baseUrl}/${record.id}`, {
            preserveScroll: true,
        });
    }

    function markPaid(record: AdminRecord) {
        router.post(`${baseUrl}/${record.id}/mark-paid`, {}, {
            preserveScroll: true,
        });
    }

    async function createMidtrans(record: AdminRecord) {
        const response = await fetch(`${baseUrl}/${record.id}/midtrans`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content') ?? '',
                Accept: 'application/json',
            },
        });
        const data = (await response.json()) as {
            redirectUrl?: string;
            error?: string;
        };

        if (data.redirectUrl) {
            window.open(data.redirectUrl, '_blank', 'noopener,noreferrer');

            return;
        }

        alert(data.error ?? 'Gagal membuat transaksi Midtrans.');
    }

    return (
        <>
            <Head title={title} />

            <div className="space-y-4">
                <div className="flex flex-col gap-3 rounded-[8px] border border-sky-100 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 className="text-xl font-semibold text-slate-950">
                            {title}
                        </h2>
                        <p className="mt-1 text-sm text-slate-500">
                            Kelola data {singularTitle.toLowerCase()} dari
                            panel admin PAUD.
                        </p>
                    </div>
                    <Link
                        href={`${baseUrl}/create`}
                        className="inline-flex min-h-11 items-center justify-center gap-2 rounded-[8px] bg-sky-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-sky-700"
                    >
                        <Plus className="size-4" />
                        Tambah
                    </Link>
                </div>

                <div className="overflow-hidden rounded-[8px] border border-sky-100 bg-white shadow-sm">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-sky-50 text-sm">
                            <thead className="bg-sky-50 text-left text-xs font-semibold uppercase tracking-normal text-sky-800">
                                <tr>
                                    {columns.map((column) => (
                                        <th
                                            key={column.key}
                                            className="px-4 py-3"
                                        >
                                            {column.label}
                                        </th>
                                    ))}
                                    <th className="px-4 py-3 text-right">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-sky-50">
                                {records.length === 0 ? (
                                    <tr>
                                        <td
                                            colSpan={columns.length + 1}
                                            className="px-4 py-10 text-center text-slate-500"
                                        >
                                            Belum ada data.
                                        </td>
                                    </tr>
                                ) : (
                                    records.map((record) => (
                                        <tr
                                            key={record.id}
                                            className="hover:bg-sky-50/50"
                                        >
                                            {columns.map((column) => (
                                                <td
                                                    key={column.key}
                                                    className="px-4 py-3 text-slate-700"
                                                >
                                                    <Cell
                                                        column={column}
                                                        value={
                                                            record[column.key]
                                                        }
                                                    />
                                                </td>
                                            ))}
                                            <td className="px-4 py-3">
                                                <div className="flex justify-end gap-2">
                                                    {baseUrl ===
                                                        '/admin/bills' && (
                                                        <>
                                                            <Button
                                                                type="button"
                                                                variant="outline"
                                                                size="sm"
                                                                className="rounded-[8px]"
                                                                onClick={() =>
                                                                    markPaid(
                                                                        record,
                                                                    )
                                                                }
                                                            >
                                                                Lunas
                                                            </Button>
                                                            <Button
                                                                type="button"
                                                                variant="outline"
                                                                size="icon"
                                                                className="rounded-[8px]"
                                                                onClick={() =>
                                                                    createMidtrans(
                                                                        record,
                                                                    )
                                                                }
                                                                title="Buat transaksi Midtrans"
                                                            >
                                                                <CreditCard className="size-4" />
                                                            </Button>
                                                        </>
                                                    )}
                                                    <Link
                                                        href={`${baseUrl}/${record.id}/edit`}
                                                        className="inline-flex min-h-9 items-center justify-center gap-2 rounded-[8px] border border-sky-100 px-3 text-sm font-semibold text-sky-700 hover:bg-sky-50"
                                                    >
                                                        <Edit className="size-4" />
                                                        Edit
                                                    </Link>
                                                    <Button
                                                        type="button"
                                                        variant="outline"
                                                        size="icon"
                                                        className="rounded-[8px] border-red-100 text-red-600 hover:bg-red-50"
                                                        onClick={() =>
                                                            destroy(record)
                                                        }
                                                    >
                                                        <Trash2 className="size-4" />
                                                    </Button>
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

function Cell({ column, value }: { column: AdminColumn; value: unknown }) {
    if (column.type === 'badge') {
        return (
            <Badge
                variant="outline"
                className="border-sky-100 bg-sky-50 text-sky-700"
            >
                {String(value ?? '-')}
            </Badge>
        );
    }

    if (column.type === 'money') {
        return formatMoney(value);
    }

    return String(value ?? '-');
}

function formatMoney(value: unknown): string {
    const number = Number(value ?? 0);

    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(number);
}
