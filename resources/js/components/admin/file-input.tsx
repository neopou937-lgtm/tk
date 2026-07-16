import { ImagePlus } from 'lucide-react';
import type { ChangeEvent } from 'react';
import { useEffect, useRef, useState } from 'react';
import InputError from '@/components/input-error';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';

type FileInputProps = {
    id: string;
    label: string;
    error?: string;
    existingUrl?: string | null;
    onChange: (file: File | null) => void;
};

export function FileInput({
    id,
    label,
    error,
    existingUrl,
    onChange,
}: FileInputProps) {
    const previousObjectUrl = useRef<string | null>(null);
    const [objectUrl, setObjectUrl] = useState<string | null>(null);
    const preview = objectUrl ?? existingUrl ?? null;

    useEffect(() => {
        return () => {
            if (previousObjectUrl.current) {
                URL.revokeObjectURL(previousObjectUrl.current);
            }
        };
    }, []);

    function handleChange(event: ChangeEvent<HTMLInputElement>) {
        const file = event.target.files?.[0] ?? null;

        if (previousObjectUrl.current) {
            URL.revokeObjectURL(previousObjectUrl.current);
            previousObjectUrl.current = null;
        }

        if (file) {
            const nextObjectUrl = URL.createObjectURL(file);

            previousObjectUrl.current = nextObjectUrl;
            setObjectUrl(nextObjectUrl);
        } else {
            setObjectUrl(null);
        }

        onChange(file);
    }

    return (
        <div className="grid gap-2">
            <Label
                htmlFor={id}
                className="text-sm font-semibold text-slate-700"
            >
                {label}
            </Label>
            <label
                htmlFor={id}
                className={cn(
                    'flex min-h-36 cursor-pointer flex-col items-center justify-center gap-3 rounded-[8px] border border-dashed border-sky-200 bg-sky-50/70 p-3 text-center transition-colors hover:border-sky-400 hover:bg-sky-50',
                    error && 'border-red-300 bg-red-50',
                )}
            >
                {preview ? (
                    <img
                        src={preview}
                        alt={label}
                        className="h-28 w-full rounded-[8px] object-cover"
                    />
                ) : (
                    <div className="grid place-items-center gap-2 text-sky-700">
                        <span className="grid size-11 place-items-center rounded-full bg-white">
                            <ImagePlus className="size-5" />
                        </span>
                        <span className="text-sm font-medium">
                            Pilih atau ganti gambar
                        </span>
                    </div>
                )}
            </label>
            <input
                id={id}
                type="file"
                accept="image/*"
                className="sr-only"
                onChange={handleChange}
            />
            <InputError message={error} />
        </div>
    );
}
