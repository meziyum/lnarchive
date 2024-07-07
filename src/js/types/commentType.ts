
export type ResponseTypeType = 'like' | 'dislike' | 'none';

interface commentType{
    id: number,
    meta: {
        likes?: number,
        dislikes?: number,
        progress?: number,
    },
    user_comment_response: Array<{
        response_type: ResponseTypeType,
    }>,
    author: number,
    author_avatar_urls: {
        '96': string,
    },
    author_name: string,
    date: string,
    rating: string,
    content: {
        rendered: string,
    },
}
export default commentType;
