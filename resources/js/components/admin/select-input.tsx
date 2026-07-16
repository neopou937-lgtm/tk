import type { ComponentProps } from 'react';
import InputError from '@/components/input-error';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

type SelectOption = {
    label: string;
    value: string;
};

type SelectInputProps = ComponentProps<'select'> & {
    label: string;
    error?: string;
    options: SelectOption[];
    placeholder?: string;
    wrapperClassName?: string;
};

export function SelectInput({
    label,
    error,
    options,
    placeholder = 'Pilih data',
    wrapperClassName,
    className,
    id,
    ...props
}: SelectInputProps) {
    return (
        <div className={cn('grid gap-2', wrapperClassName)}>
            <Label
                htmlFor={id}
                className="text-sm font-semibold text-slate-700"
            >
                {label}
            </Label>
            <select
                id={id}
                aria-invalid={Boolean(error)}
                className={cn(
                    'min-h-11 w-full rounded-[8px] border border-sky-100 bg-white px-3 text-sm text-slate-900 shadow-none transition-colors outline-none focus:border-sky-400 focus:ring-3 focus:ring-sky-200 disabled:opacity-50',
                    className,
                )}
                {...props}
            >
                <option value="">{placeholder}</option>
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
            <InputError message={error} />
        </div>
    );
}
