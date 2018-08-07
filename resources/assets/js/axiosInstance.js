const axios = require('axios')
const instance = axios.create({
    baseURL: 'http://didrikfleischer.com/livedemo/codetube',
    headers: {
        'X-CSRF-TOKEN': window.Laravel.csrfToken,
    }
})

export default instance;