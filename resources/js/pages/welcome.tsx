import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    BookOpenCheck,
    CalendarCheck,
    GraduationCap,
    HeartHandshake,
    LogIn,
    School,
    ShieldCheck,
    Sparkles,
    WalletCards,
} from 'lucide-react';
import { dashboard, login, register } from '@/routes';
import type { User } from '@/types';

type WelcomePageProps = {
    auth: {
        user: User | null;
    };
};

const features = [
    {
        title: 'Data sekolah rapi',
        description: 'Siswa, guru, PPDB, alumni, dan galeri dikelola dalam satu panel.',
        icon: School,
    },
    {
        title: 'Keuangan terpantau',
        description: 'Tagihan, tabungan, BOP, dan pembayaran Midtrans siap dipakai admin.',
        icon: WalletCards,
    },
    {
        title: 'Kegiatan harian',
        description: 'Absensi siswa, absensi guru, pengumuman, dan jurnal mengajar tersusun jelas.',
        icon: CalendarCheck,
    },
];

const previewStats = [
    ['Siswa aktif', '128'],
    ['Guru', '12'],
    ['PPDB pending', '8'],
    ['Alumni', '74'],
];

export default function Welcome() {
    const { auth } = usePage<WelcomePageProps>().props;
    const primaryHref = auth.user ? dashboard() : login();
    const primaryLabel = auth.user ? 'Buka Dashboard Admin' : 'Masuk Admin';

    return (
        <>
            <Head title="PAUD Asy-Syahidiyyah" />

            <main className="min-h-screen bg-[#eef7ff] text-slate-950">
                <section className="relative isolate overflow-hidden bg-sky-800 text-white">
                    <div className="absolute inset-0 bg-[linear-gradient(135deg,rgba(3,105,161,0.96),rgba(14,116,144,0.9)_52%,rgba(16,185,129,0.82))]" />
                    <div className="absolute inset-x-0 bottom-0 h-24 bg-white [clip-path:polygon(0_62%,100%_18%,100%_100%,0_100%)]" />

                    <div className="relative mx-auto grid min-h-[92vh] max-w-7xl content-center gap-10 px-5 py-6 sm:px-6 lg:grid-cols-[1fr_0.92fr] lg:px-8">
                        <header className="absolute inset-x-5 top-4 flex items-center justify-between sm:inset-x-6 lg:inset-x-8">
                            <Link href="/" className="flex items-center gap-3">
                                <span className="grid size-11 place-items-center rounded-[8px] bg-white text-sky-700 shadow-sm">
                                    <ShieldCheck className="size-6" />
                                </span>
                                <span>
                                    <span className="block text-sm font-bold">
                                        PAUD Asy-Syahidiyyah
                                    </span>
                                    <span className="block text-xs font-medium text-sky-100">
                                        Sistem Administrasi Sekolah
                                    </span>
                                </span>
                            </Link>

                            <nav className="flex items-center gap-2">
                                {auth.user ? (
                                    <Link
                                        href={dashboard()}
                                        className="inline-flex min-h-10 items-center justify-center rounded-[8px] bg-white px-4 text-sm font-semibold text-sky-700 shadow-sm"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href={login()}
                                            className="inline-flex min-h-10 items-center justify-center rounded-[8px] px-4 text-sm font-semibold text-white hover:bg-white/10"
                                        >
                                            Login
                                        </Link>
                                        <Link
                                            href={register()}
                                            className="hidden min-h-10 items-center justify-center rounded-[8px] bg-white px-4 text-sm font-semibold text-sky-700 shadow-sm sm:inline-flex"
                                        >
                                            Daftar Admin
                                        </Link>
                                    </>
                                )}
                            </nav>
                        </header>

                        <div className="pt-20">
                            <div className="inline-flex items-center gap-2 rounded-full bg-white/14 px-3 py-1.5 text-sm font-semibold text-sky-50 ring-1 ring-white/20">
                                <Sparkles className="size-4" />
                                Panel sekolah ramah untuk PAUD
                            </div>

                            <h1 className="mt-5 max-w-3xl text-4xl font-bold tracking-normal sm:text-5xl lg:text-6xl">
                                PAUD Asy-Syahidiyyah
                            </h1>
                            <p className="mt-5 max-w-2xl text-base leading-7 text-sky-50 sm:text-lg">
                                Halaman awal sistem administrasi sekolah untuk mengelola data
                                siswa, guru, PPDB, keuangan, absensi, galeri, alumni, jurnal
                                mengajar, dan donatur dengan tampilan bersih.
                            </p>

                            <div className="mt-7 flex flex-col gap-3 sm:flex-row">
                                <Link
                                    href={primaryHref}
                                    className="inline-flex min-h-12 items-center justify-center gap-2 rounded-[8px] bg-white px-5 text-sm font-bold text-sky-700 shadow-sm transition hover:bg-sky-50"
                                >
                                    <LogIn className="size-5" />
                                    {primaryLabel}
                                </Link>
                                <a
                                    href="#fitur"
                                    className="inline-flex min-h-12 items-center justify-center gap-2 rounded-[8px] border border-white/30 px-5 text-sm font-bold text-white transition hover:bg-white/10"
                                >
                                    Lihat Ringkasan
                                    <ArrowRight className="size-5" />
                                </a>
                            </div>
                        </div>

                        <div className="relative pb-16 lg:pt-20">
                            <div className="rounded-[8px] border border-white/18 bg-white/95 p-4 text-slate-950 shadow-2xl shadow-sky-950/25">
                                <div className="flex items-center justify-between border-b border-sky-100 pb-3">
                                    <div>
                                        <p className="text-xs font-semibold uppercase text-sky-600">
                                            Admin Dashboard
                                        </p>
                                        <p className="text-lg font-bold">
                                            Hari ini di sekolah
                                        </p>
                                    </div>
                                    <span className="grid size-10 place-items-center rounded-[8px] bg-emerald-50 text-emerald-700">
                                        <BookOpenCheck className="size-5" />
                                    </span>
                                </div>

                                <div className="mt-4 grid grid-cols-2 gap-3">
                                    {previewStats.map(([label, value]) => (
                                        <div
                                            key={label}
                                            className="rounded-[8px] border border-sky-100 bg-sky-50 p-3"
                                        >
                                            <p className="text-xs font-medium text-slate-500">
                                                {label}
                                            </p>
                                            <p className="mt-1 text-2xl font-bold text-sky-800">
                                                {value}
                                            </p>
                                        </div>
                                    ))}
                                </div>

                                <div className="mt-4 space-y-3">
                                    <PreviewRow
                                        icon={GraduationCap}
                                        title="Data Siswa"
                                        meta="Form lengkap, upload berkas, status aktif/lulus"
                                    />
                                    <PreviewRow
                                        icon={WalletCards}
                                        title="Tagihan dan Tabungan"
                                        meta="Filter status, pembayaran manual, Midtrans"
                                    />
                                    <PreviewRow
                                        icon={HeartHandshake}
                                        title="Donatur dan BOP"
                                        meta="Catatan pemasukan sekolah dan bantuan"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="fitur" className="bg-white px-5 py-12 sm:px-6 lg:px-8">
                    <div className="mx-auto max-w-7xl">
                        <div className="max-w-2xl">
                            <p className="text-sm font-bold uppercase text-sky-600">
                                Fitur admin
                            </p>
                            <h2 className="mt-2 text-3xl font-bold tracking-normal text-slate-950">
                                Dibuat untuk pekerjaan sekolah sehari-hari.
                            </h2>
                        </div>

                        <div className="mt-7 grid gap-4 md:grid-cols-3">
                            {features.map((feature) => {
                                const Icon = feature.icon;

                                return (
                                    <article
                                        key={feature.title}
                                        className="rounded-[8px] border border-sky-100 bg-white p-5 shadow-sm"
                                    >
                                        <span className="grid size-11 place-items-center rounded-[8px] bg-sky-50 text-sky-700">
                                            <Icon className="size-5" />
                                        </span>
                                        <h3 className="mt-4 text-lg font-bold text-slate-950">
                                            {feature.title}
                                        </h3>
                                        <p className="mt-2 text-sm leading-6 text-slate-600">
                                            {feature.description}
                                        </p>
                                    </article>
                                );
                            })}
                        </div>
                    </div>
                </section>
            </main>
        </>
    );
}

function PreviewRow({
    icon: Icon,
    title,
    meta,
}: {
    icon: typeof School;
    title: string;
    meta: string;
}) {
    return (
        <div className="flex items-center gap-3 rounded-[8px] border border-slate-100 bg-white p-3">
            <span className="grid size-10 shrink-0 place-items-center rounded-[8px] bg-emerald-50 text-emerald-700">
                <Icon className="size-5" />
            </span>
            <div className="min-w-0">
                <p className="truncate text-sm font-bold text-slate-950">{title}</p>
                <p className="truncate text-xs text-slate-500">{meta}</p>
            </div>
        </div>
    );
}
