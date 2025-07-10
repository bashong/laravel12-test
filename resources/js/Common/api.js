export default function api(method, url, data = {}) {

    return window.axios({
        method,
        url,
        ...(method.toLowerCase() === 'get' ? { params: data } : { data }),
        withCredentials: true
    })
}