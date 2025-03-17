import client from '@/api/client.js'

const edit = (data) => {
    return client
        .post('/profile_edit?_method=PUT', data)
        .then((response) => response)
        .catch((error) => error.response)
}

const editProfileApi = {
    edit,
}
export default editProfileApi
