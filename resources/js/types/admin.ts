export type AdminStats = {
    students: number;
    teachers: number;
    pendingBills: number;
    paidPayments: number;
    savings: number;
    bopFunds: number;
    pendingPpdb: number;
    alumni: number;
};

export type StudentStatus = 'aktif' | 'lulus' | 'nonaktif';

export type Student = {
    id: number;
    name: string;
    nipd: string;
    nisn: string | null;
    student_nik: string | null;
    level: string;
    class_name: string;
    gender: string;
    birth_place: string | null;
    birth_date: string | null;
    religion: string | null;
    address: string | null;
    child_order: number | null;
    father_name: string | null;
    father_nik: string | null;
    father_phone: string | null;
    mother_name: string | null;
    mother_nik: string | null;
    mother_phone: string | null;
    student_photo_path: string | null;
    family_card_photo_path: string | null;
    parent_id_card_photo_path: string | null;
    student_photo_url?: string | null;
    family_card_photo_url?: string | null;
    parent_id_card_photo_url?: string | null;
    status: StudentStatus;
    created_at: string;
    updated_at?: string;
};
