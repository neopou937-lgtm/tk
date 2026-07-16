import type { ComponentProps } from 'react';
import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

type TextInputProps = ComponentProps<typeof Input> & {
    label: string;
    error?: string;
    wrapperClassName?: string;
};

export function TextInput({
    label,
    error,
    className,
    wrapperClassName,
    id,
    ...props
}: TextInputProps) {
    return (
        <div className={cn('grid gap-2', wrapperClassName)}>
            <Label
                htmlFor={id}
                className="text-sm font-semibold text-slate-700"
            >
                {label}
            </Label>
            <Input
                id={id}
                aria-invalid={Boolean(error)}
                className={cn(
                    'min-h-11 rounded-[8px] border-sky-100 bg-white px-3 text-slate-900 shadow-none focus-visible:border-sky-400 focus-visible:ring-sky-200',
                    className,
                )}
                {...props}
            />
            <InputError message={error} />
        </div>
    );
}
