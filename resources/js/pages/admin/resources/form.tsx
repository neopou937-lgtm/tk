import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, Save } from 'lucide-react';
import { DateInput } from '@/components/admin/date-input';
import { FileInput } from '@/components/admin/file-input';
import { FormSection } from '@/components/admin/form-section';
import { PrimaryButton } from '@/components/admin/primary-button';
import { SelectInput } from '@/components/admin/select-input';
import { TextInput } from '@/components/admin/text-input';
import type { AdminField, AdminRecord } from '@/types';

type ResourceFormProps = {
    title: string;
    singularTitle: string;
    baseUrl: string;
    fields: AdminField[];
    record: AdminRecord | null;
};

type FormData = Record<string, string | File | null>;

export default function ResourceForm({
    title,
    singularTitle,
    baseUrl,
    fields,
    record,
}: ResourceFormProps) {
    const form = useForm<FormData>(initialData(fields, record));
    const isEdit = Boolean(record);

    function setField(name: string, value: string | File | null) {
        form.setData((data) => ({ ...data, [name]: value }));
    }

    function submit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();

        if (record) {
            form.transform((data) => ({ ...data, _method: 'put' }));
            form.post(`${baseUrl}/${record.id}`, {
                forceFormData: true,
                preserveScroll: true,
            });

            return;
        }

        form.post(baseUrl, {
            forceFormData: true,
            preserveScroll: true,
        });
    }

    return (
        <>
            <Head title={title} />

            <form onSubmit={submit} className="space-y-4">
                <div className="flex flex-col gap-3 rounded-[8px] border border-sky-100 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <Link
                            href={baseUrl}
                            className="inline-flex items-center gap-2 text-sm font-semibold text-sky-700"
                        >
                            <ArrowLeft className="size-4" />
                            Kembali
                        </Link>
                        <h2 className="mt-2 text-xl font-semibold text-slate-950">
                            {title}
                        </h2>
                        <p className="mt-1 text-sm text-slate-500">
                            {isEdit ? 'Perbarui' : 'Tambahkan'} data{' '}
                            {singularTitle.toLowerCase()}.
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

                <FormSection title="Data Utama">
                    {fields.map((field) => (
                        <FieldControl
                            key={field.name}
                            field={field}
                            value={form.data[field.name]}
                            error={form.errors[field.name]}
                            record={record}
                            onChange={(value) => setField(field.name, value)}
                        />
                    ))}
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
            </form>
        </>
    );
}

function FieldControl({
    field,
    value,
    error,
    record,
    onChange,
}: {
    field: AdminField;
    value: string | File | null;
    error?: string;
    record: AdminRecord | null;
    onChange: (value: string | File | null) => void;
}) {
    const common = {
        id: field.name,
        label: field.label,
        error,
        required: field.required,
        wrapperClassName: field.colSpan === 2 ? 'md:col-span-2' : undefined,
    };

    if (field.type === 'select') {
        return (
            <SelectInput
                {...common}
                value={String(value ?? '')}
                options={field.options ?? []}
                onChange={(event) => onChange(event.target.value)}
            />
        );
    }

    if (field.type === 'textarea') {
        return (
            <div className="grid gap-2 md:col-span-2">
                <label
                    htmlFor={field.name}
                    className="text-sm font-semibold text-slate-700"
                >
                    {field.label}
                </label>
                <textarea
                    id={field.name}
                    value={String(value ?? '')}
                    required={field.required}
                    onChange={(event) => onChange(event.target.value)}
                    className="min-h-28 rounded-[8px] border border-sky-100 bg-white px-3 py-2 text-sm outline-none focus:border-sky-400 focus:ring-3 focus:ring-sky-200"
                />
                {error && <p className="text-sm text-red-600">{error}</p>}
            </div>
        );
    }

    if (field.type === 'file') {
        return (
            <FileInput
                id={field.name}
                label={field.label}
                error={error}
                existingUrl={
                    field.urlColumn
                        ? String(record?.[field.urlColumn] ?? '')
                        : undefined
                }
                onChange={onChange}
            />
        );
    }

    if (field.type === 'date') {
        return (
            <DateInput
                {...common}
                value={String(value ?? '')}
                onChange={(event) => onChange(event.target.value)}
            />
        );
    }

    return (
        <TextInput
            {...common}
            type={field.type ?? 'text'}
            value={String(value ?? '')}
            onChange={(event) => onChange(event.target.value)}
        />
    );
}

function initialData(fields: AdminField[], record: AdminRecord | null): FormData {
    return fields.reduce<FormData>((data, field) => {
        if (field.type === 'file') {
            data[field.name] = null;

            return data;
        }

        const value = record?.[field.name];
        data[field.name] = value == null ? '' : String(value).slice(0, field.type === 'date' ? 10 : undefined);

        return data;
    }, {});
}
