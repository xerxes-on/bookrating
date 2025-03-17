import client from '@/api/client.js'

const like_review = (review_id) => {
    return client
        .put('/reviews/' + review_id + '/like')
        .then((response) => response)
        .catch((error) => error.response)
}
const like_quote = (quote_id) => {
    return client
        .put('/quotes/' + quote_id + '/like')
        .then((response) => response)
        .catch((error) => error.response)
}
const likeApi = {
    like_review,
    like_quote
}
export default likeApi
