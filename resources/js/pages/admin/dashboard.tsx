import { Head, Link } from '@inertiajs/react';
import {
    ArrowRight,
    BadgeDollarSign,
    Banknote,
    BookOpenCheck,
    GraduationCap,
    PiggyBank,
    School,
    Users,
    WalletCards,
} from 'lucide-react';
import type { AdminStats } from '@/types';

type DashboardProps = {
    stats: AdminStats;
};

const statCards = [
    {
        label: 'Total siswa',
        key: 'students',
        icon: GraduationCap,
        tone: 'bg-sky-600 text-white',
    },
    {
        label: 'Total guru',
        key: 'teachers',
        icon: Users,
        tone: 'bg-blue-50 text-blue-700',
    },
    {
        label: 'Tagihan pending',
        key: 'pendingBills',
        icon: BadgeDollarSign,
        tone: 'bg-amber-50 text-amber-700',
    },
    {
        label: 'Pembayaran lunas',
        key: 'paidPayments',
        icon: WalletCards,
        tone: 'bg-emerald-50 text-emerald-700',
    },
    {
        label: 'Total tabungan',
        key: 'savings',
        icon: PiggyBank,
        tone: 'bg-cyan-50 text-cyan-700',
    },
    {
        label: 'Total BOP',
        key: 'bopFunds',
        icon: Banknote,
        tone: 'bg-lime-50 text-lime-700',
    },
    {
        label: 'PPDB pending',
        key: 'pendingPpdb',
        icon: School,
        tone: 'bg-indigo-50 text-indigo-700',
    },
    {
        label: 'Total alumni',
        key: 'alumni',
        icon: BookOpenCheck,
        tone: 'bg-slate-100 text-slate-700',
    },
] as const;

export default function AdminDashboard({ stats }: DashboardProps) {
    return (
        <>
            <Head title="Dashboard Admin" />

            <div className="space-y-5">
                <section className="overflow-hidden rounded-[8px] bg-sky-700 text-white shadow-sm">
                    <div className="grid gap-4 p-5 md:grid-cols-[1fr_auto] md:items-center md:p-6">
                        <div>
                            <p className="text-sm font-semibold text-sky-100">
                                Selamat datang di panel sekolah
                            </p>
                            <h2 className="mt-2 max-w-2xl text-2xl font-bold tracking-normal sm:text-3xl">
                                Kelola data PAUD Asy-Syahidiyyah dengan rapi dan
                                cepat.
                            </h2>
                            <p className="mt-2 max-w-2xl text-sm leading-6 text-sky-100">
                                Mulai dari data siswa, tagihan, tabungan,
                                absensi, sampai arsip alumni dalam satu panel.
                            </p>
                        </div>
                        <Link
                            href="/admin/students/create"
                            className="inline-flex min-h-11 items-center justify-center gap-2 rounded-[8px] bg-white px-4 text-sm font-semibold text-sky-700 shadow-sm"
                        >
                            Tambah Siswa
                            <ArrowRight className="size-4" />
                        </Link>
                    </div>
                </section>

                <section className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    {statCards.map((card) => {
                        const Icon = card.icon;
                        const value = stats[card.key];

                        return (
                            <article
                                key={card.key}
                                className="rounded-[8px] border border-sky-100 bg-white p-4 shadow-sm"
                            >
                                <div className="flex items-center justify-between gap-3">
                                    <div>
                                        <p className="text-sm font-medium text-slate-500">
                                            {card.label}
                                        </p>
                                        <p className="mt-2 text-2xl font-bold text-slate-950">
                                            {value.toLocaleString('id-ID')}
                                        </p>
                                    </div>
                                    <span
                                        className={`grid size-11 place-items-center rounded-[8px] ${card.tone}`}
                                    >
                                        <Icon className="size-5" />
                                    </span>
                                </div>
                            </article>
                        );
                    })}
                </section>
            </div>
        </>
    );
}
