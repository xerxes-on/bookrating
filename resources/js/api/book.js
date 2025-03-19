import client from '@/api/client.js'

const getBookDetails = (id) => {
    return client
        .get('/books/' + id)
        .then((response) => response)
        .catch((error) => error.response)
}
const getBooks = (page = 1) => {
    return client
        .get(`/books?page=${page}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const getBookReviews = (id) => {
    return client
        .post('/books/reviews/' + id)
        .then((response) => response)
        .catch((error) => error.response)
}
const rateBook = (data) => {
    return client
        .post('/reviews/', data)
        .then((response) => response)
        .catch((error) => error.response)
}
const getReviews = (page) => {
    return client
        .post('/all-reviews?page=' + page)
        .then((response) => response)
        .catch((error) => error.response)
}
const searchBooks = (query) => {
    return client
        .get(`/searchBooks?query=${encodeURIComponent(query)}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const searchQuotes = (query) => {
    return client
        .get(`/searchQuotes?query=${encodeURIComponent(query)}`)
        .then((response) => response)
        .catch((error) => error.response)
}
const bookApi = {
    getBookDetails,
    getBookReviews,
    searchBooks,
    getBooks,
    searchQuotes,
    rateBook,
    getReviews,
}
export default bookApi
