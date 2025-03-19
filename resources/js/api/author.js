import client from '@/api/client.js'

/**
 * Get paginated list of authors
 * @param {number} page - Page number
 * @returns {Promise} - API response
 */
const getAuthors = (page = 1) => {
    return client
        .get(`/authors?page=${page}`)
        .then((response) => response)
        .catch((error) => error.response)
}

/**
 * Search for authors by name
 * @param {string} query - Search query
 * @returns {Promise} - API response
 */
const searchAuthors = (query) => {
    return client
        .get(`/authors/search?query=${encodeURIComponent(query)}`)
        .then((response) => response)
        .catch((error) => error.response)
}

/**
 * Get author details by ID
 * @param {number|string} id - Author ID
 * @returns {Promise} - API response
 */
const getAuthorDetails = (id) => {
    return client
        .get(`/authors/${id}`)
        .then((response) => response)
        .catch((error) => error.response)
}

const authorApi = {
    getAuthors,
    searchAuthors,
    getAuthorDetails
}

export default authorApi
