import { Link, router, usePage } from '@inertiajs/react';
import {
    Bell,
    ChevronRight,
    Home,
    LogOut,
    Menu,
    Search,
    ShieldCheck,
    X,
} from 'lucide-react';
import type { ReactNode } from 'react';
import { useMemo, useState } from 'react';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { adminNavItems, adminPageTitle } from '@/lib/admin-nav';
import { cn } from '@/lib/utils';
import { logout } from '@/routes';
import type { Auth } from '@/types';

type AdminLayoutProps = {
    children: ReactNode;
};

type PageProps = {
    auth: Auth;
};

export default function AdminLayout({ children }: AdminLayoutProps) {
    const { props, url } = usePage<PageProps>();
    const { auth } = props;
    const [drawerOpen, setDrawerOpen] = useState(false);
    const title = adminPageTitle(url);
    const handleLogout = () => {
        router.flushAll();
    };

    const initials = useMemo(
        () =>
            auth.user.name
                .split(' ')
                .map((part) => part[0])
                .join('')
                .slice(0, 2)
                .toUpperCase(),
        [auth.user.name],
    );

    return (
        <div className="min-h-screen bg-[#eef7ff] text-slate-900">
            <aside className="fixed inset-y-0 left-0 z-30 hidden w-72 border-r border-sky-100 bg-white lg:flex lg:flex-col">
                <AdminBrand />
                <AdminNavigation className="flex-1 px-3 py-4" />
                <div className="border-t border-sky-100 p-4">
                    <div className="rounded-[8px] bg-emerald-50 p-3 text-sm text-emerald-900">
                        <p className="font-semibold">PAUD Asy-Syahidiyyah</p>
                        <p className="mt-1 text-xs leading-5 text-emerald-700">
                            Panel sekolah untuk mengelola data harian dengan
                            rapi.
                        </p>
                    </div>
                </div>
            </aside>

            <div className="lg:pl-72">
                <header className="sticky top-0 z-20 border-b border-sky-100 bg-white/95 backdrop-blur">
                    <div className="flex min-h-16 items-center gap-3 px-4 sm:px-6">
                        <Button
                            type="button"
                            variant="ghost"
                            size="icon"
                            className="lg:hidden"
                            onClick={() => setDrawerOpen(true)}
                            aria-label="Buka menu admin"
                        >
                            <Menu className="size-5" />
                        </Button>

                        <div className="min-w-0 flex-1">
                            <div className="flex items-center gap-1 text-xs font-medium text-sky-600">
                                <Home className="size-3.5" />
                                <ChevronRight className="size-3.5" />
                                <span>Admin</span>
                            </div>
                            <h1 className="truncate text-lg font-semibold text-slate-950 sm:text-xl">
                                {title}
                            </h1>
                        </div>

                        <button
                            type="button"
                            className="hidden h-10 items-center gap-2 rounded-[8px] border border-sky-100 bg-sky-50 px-3 text-sm text-sky-800 md:flex"
                        >
                            <Search className="size-4" />
                            Cari data
                        </button>

                        <button
                            type="button"
                            className="grid size-10 place-items-center rounded-[8px] border border-sky-100 bg-white text-sky-700 shadow-sm"
                            aria-label="Notifikasi"
                        >
                            <Bell className="size-5" />
                        </button>

                        <div className="flex items-center gap-2 rounded-[8px] bg-sky-50 p-1.5">
                            <Avatar className="size-9 border border-sky-200 bg-sky-600 text-white">
                                <AvatarFallback className="bg-sky-600 text-xs font-semibold text-white">
                                    {initials}
                                </AvatarFallback>
                            </Avatar>
                            <div className="hidden leading-tight sm:block">
                                <p className="text-sm font-semibold">
                                    {auth.user.name}
                                </p>
                                <p className="text-xs text-sky-700">
                                    Admin Sekolah
                                </p>
                            </div>
                            <Link
                                href={logout()}
                                method="post"
                                as="button"
                                onClick={handleLogout}
                                className="grid size-9 place-items-center rounded-[8px] bg-white text-sky-700 shadow-sm transition hover:bg-red-50 hover:text-red-600"
                                aria-label="Logout admin"
                                data-test="admin-logout-button"
                            >
                                <LogOut className="size-4" />
                            </Link>
                        </div>
                    </div>
                </header>

                <main className="min-h-[calc(100vh-4rem)] px-4 py-5 pb-24 sm:px-6 lg:pb-8">
                    {children}
                </main>
            </div>

            <nav className="fixed inset-x-0 bottom-0 z-20 border-t border-sky-100 bg-white px-2 py-2 shadow-[0_-8px_24px_rgba(14,116,144,0.08)] lg:hidden">
                <div className="mx-auto grid max-w-md grid-cols-4 gap-1">
                    {adminNavItems.slice(0, 3).map((item) => (
                        <MobileNavLink key={item.title} item={item} />
                    ))}
                    <button
                        type="button"
                        onClick={() => setDrawerOpen(true)}
                        className="flex min-h-12 flex-col items-center justify-center gap-1 rounded-[8px] text-xs font-medium text-sky-700"
                    >
                        <Menu className="size-5" />
                        Menu
                    </button>
                </div>
            </nav>

            {drawerOpen && (
                <div className="fixed inset-0 z-40 lg:hidden">
                    <button
                        type="button"
                        aria-label="Tutup menu admin"
                        className="absolute inset-0 bg-slate-900/40"
                        onClick={() => setDrawerOpen(false)}
                    />
                    <div className="absolute inset-y-0 left-0 flex w-[min(88vw,21rem)] flex-col bg-white shadow-xl">
                        <div className="flex items-center justify-between border-b border-sky-100 pr-3">
                            <AdminBrand compact />
                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                onClick={() => setDrawerOpen(false)}
                                aria-label="Tutup menu"
                            >
                                <X className="size-5" />
                            </Button>
                        </div>
                        <AdminNavigation
                            className="flex-1 px-3 py-4"
                            onNavigate={() => setDrawerOpen(false)}
                        />
                    </div>
                </div>
            )}
        </div>
    );
}

function AdminBrand({ compact = false }: { compact?: boolean }) {
    return (
        <div className={cn('flex items-center gap-3 p-4', compact && 'py-3')}>
            <div className="grid size-11 place-items-center rounded-[8px] bg-sky-600 text-white shadow-sm">
                <ShieldCheck className="size-6" />
            </div>
            <div className="min-w-0">
                <p className="truncate text-sm font-bold text-slate-950">
                    PAUD Asy-Syahidiyyah
                </p>
                <p className="text-xs font-medium text-sky-700">
                    Admin Sekolah
                </p>
            </div>
        </div>
    );
}

function AdminNavigation({
    className,
    onNavigate,
}: {
    className?: string;
    onNavigate?: () => void;
}) {
    const { url } = usePage();

    return (
        <div className={cn('overflow-y-auto', className)}>
            <div className="space-y-1">
                {adminNavItems.map((item) => {
                    const Icon = item.icon;
                    const active = url.startsWith(String(item.href));

                    return (
                        <Link
                            key={item.title}
                            href={item.href}
                            onClick={onNavigate}
                            className={cn(
                                'flex min-h-11 items-center gap-3 rounded-[8px] px-3 text-sm font-medium transition-colors',
                                active
                                    ? 'bg-sky-600 text-white shadow-sm'
                                    : 'text-slate-700 hover:bg-sky-50 hover:text-sky-800',
                            )}
                        >
                            {Icon && <Icon className="size-5 shrink-0" />}
                            <span className="truncate">{item.title}</span>
                        </Link>
                    );
                })}
            </div>
        </div>
    );
}

function MobileNavLink({ item }: { item: (typeof adminNavItems)[number] }) {
    const { url } = usePage();
    const Icon = item.icon;
    const active = url.startsWith(String(item.href));

    return (
        <Link
            href={item.href}
            className={cn(
                'flex min-h-12 flex-col items-center justify-center gap-1 rounded-[8px] text-xs font-medium',
                active ? 'bg-sky-100 text-sky-800' : 'text-slate-600',
            )}
        >
            {Icon && <Icon className="size-5" />}
            <span>{item.title.replace('Data ', '')}</span>
        </Link>
    );
}
