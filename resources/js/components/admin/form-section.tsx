import type { ReactNode } from 'react';

type FormSectionProps = {
    title: string;
    description?: string;
    children: ReactNode;
};

export function FormSection({
    title,
    description,
    children,
}: FormSectionProps) {
    return (
        <section className="rounded-[8px] border border-sky-100 bg-white p-4 shadow-sm sm:p-5">
            <div className="mb-4 border-b border-sky-50 pb-3">
                <h2 className="text-base font-semibold text-slate-950">
                    {title}
                </h2>
                {description && (
                    <p className="mt-1 text-sm text-slate-500">{description}</p>
                )}
            </div>
            <div className="grid gap-4 md:grid-cols-2">{children}</div>
        </section>
    );
}
