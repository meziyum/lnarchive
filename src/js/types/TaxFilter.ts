
export interface TaxFilter {
    list: {
        term_id: number;
        term_name: string;
        }[];
    taxLabel: string;
    taxQueryName: string;
}
