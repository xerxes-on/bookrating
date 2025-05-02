import client from '@/api/client.js'
const getReviews = (bookId, page) => {
    return client
        .post(`/all-reviews/${bookId}/?page=${page}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const reviewsAPi = {
    getReviews,
}
export default reviewsAPi
