import type { ImgHTMLAttributes } from 'react';

export default function AppLogoIcon(props: ImgHTMLAttributes<HTMLImageElement>) {
    const { className, ...imageProps } = props;

    return (
        <img
            {...imageProps}
            src="/images/logo-tk-asy-syahidiyyah.png"
            alt={props.alt ?? 'Logo TK Asy-Syahidiyyah'}
            className={`object-contain ${className ?? ''}`}
        />
    );
}
