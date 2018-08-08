const axios = require('axios')
const instance = axios.create({
    baseURL: 'http://didrikfleischer.com/livedemo/codetube',
    headers: {
        'X-CSRF-TOKEN': window.Laravel.csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
    }
})

export default instance;