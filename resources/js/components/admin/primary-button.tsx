import type { ComponentProps } from 'react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

export function PrimaryButton({
    className,
    ...props
}: ComponentProps<typeof Button>) {
    return (
        <Button
            className={cn(
                'min-h-11 rounded-[8px] bg-sky-600 px-5 text-white shadow-sm hover:bg-sky-700',
                className,
            )}
            {...props}
        />
    );
}
