import client from '@/api/client.js'

const getTrendingBooks = () => {
    return client
        .post('/trending_books')
        .then((response) => response)
        .catch((error) => error.response)
}

const getSuggestedBooks = () => {
    return client
        .post('/suggestions')
        .then((response) => response)
        .catch((error) => error.response)
}
const homeApi = {
    getTrendingBooks,
    getSuggestedBooks,
}
export default homeApi
