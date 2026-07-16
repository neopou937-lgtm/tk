import type { ComponentProps } from 'react';
import { TextInput } from '@/components/admin/text-input';

type DateInputProps = Omit<ComponentProps<typeof TextInput>, 'type'>;

export function DateInput(props: DateInputProps) {
    return <TextInput type="date" {...props} />;
}
