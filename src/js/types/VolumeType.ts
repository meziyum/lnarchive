
interface VolumeType {
    id: number;
    link: string;
    novel_link: string,
    title: {
        rendered: string;
    }
    excerpt: {
        rendered: string;
    }
    _embedded: {
        'wp:featuredmedia': Array<{
            source_url: string;
        }>;
        'wp:term': Array<{
            name: string;
            taxonomy: string;
        }>;
    };
    meta: object;
}
export default VolumeType;
