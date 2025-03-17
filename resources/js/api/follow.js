import client from '@/api/client.js'

const follow = (user_id) => {
    return client
        .put('/follow/' + user_id)
        .then((response) => response)
        .catch((error) => error.response)
}
const followAuthor = (author_id) => {
    return client
        .put('/authors/' + author_id + '/follow')
        .then((response) => response)
        .catch((error) => error.response)
}

const followApi = {
    follow,
    followAuthor,
}
export default followApi
