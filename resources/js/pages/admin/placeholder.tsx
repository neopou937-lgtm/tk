import { Head } from '@inertiajs/react';
import { Construction } from 'lucide-react';

type PlaceholderProps = {
    title: string;
};

export default function AdminPlaceholder({ title }: PlaceholderProps) {
    return (
        <>
            <Head title={title} />

            <div className="rounded-[8px] border border-sky-100 bg-white p-6 shadow-sm">
                <div className="grid max-w-xl gap-3">
                    <span className="grid size-12 place-items-center rounded-[8px] bg-emerald-50 text-emerald-700">
                        <Construction className="size-6" />
                    </span>
                    <h2 className="text-xl font-semibold text-slate-950">
                        {title}
                    </h2>
                    <p className="text-sm leading-6 text-slate-600">
                        Halaman ini sudah disiapkan dalam menu admin dan akan
                        diisi pada tahap berikutnya.
                    </p>
                </div>
            </div>
        </>
    );
}
