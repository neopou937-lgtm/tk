export default function AppLogo() {
    return (
        <>
            <div className="flex aspect-square size-9 items-center justify-center rounded-[8px] border border-sky-100 bg-white p-1 shadow-sm">
                <img
                    src="/images/logo-tk-asy-syahidiyyah.png"
                    alt="Logo TK Asy-Syahidiyyah"
                    className="size-full object-contain"
                />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold">
                    PAUD Asy-Syahidiyyah
                </span>
            </div>
        </>
    );
}
