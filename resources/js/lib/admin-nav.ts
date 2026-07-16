import {
    BadgeDollarSign,
    Banknote,
    BookOpenCheck,
    CalendarCheck,
    GraduationCap,
    Image,
    LayoutDashboard,
    Megaphone,
    PiggyBank,
    School,
    UserRoundCheck,
    Users,
} from 'lucide-react';
import type { NavItem } from '@/types';

export const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/admin/dashboard',
        icon: LayoutDashboard,
    },
    {
        title: 'Data Siswa',
        href: '/admin/students',
        icon: GraduationCap,
    },
    {
        title: 'Data Guru',
        href: '/admin/teachers',
        icon: Users,
    },
    {
        title: 'PPDB',
        href: '/admin/ppdb',
        icon: School,
    },
    {
        title: 'Tagihan',
        href: '/admin/bills',
        icon: BadgeDollarSign,
    },
    {
        title: 'Tabungan',
        href: '/admin/savings',
        icon: PiggyBank,
    },
    {
        title: 'Keuangan BOP',
        href: '/admin/bop-finance',
        icon: Banknote,
    },
    {
        title: 'Absensi Siswa',
        href: '/admin/student-attendance',
        icon: CalendarCheck,
    },
    {
        title: 'Absensi Guru',
        href: '/admin/teacher-attendance',
        icon: UserRoundCheck,
    },
    {
        title: 'Pengumuman',
        href: '/admin/announcements',
        icon: Megaphone,
    },
    {
        title: 'Galeri',
        href: '/admin/gallery',
        icon: Image,
    },
    {
        title: 'Alumni',
        href: '/admin/alumni',
        icon: BookOpenCheck,
    },
];

export function adminPageTitle(url: string): string {
    if (url.startsWith('/admin/students/create')) {
        return 'Tambah Siswa';
    }

    if (url.includes('/admin/students/') && url.endsWith('/edit')) {
        return 'Edit Siswa';
    }

    const activeItem = adminNavItems.find((item) =>
        url.startsWith(String(item.href)),
    );

    return activeItem?.title ?? 'Admin PAUD';
}
