import Cookies from 'js-cookie'

const TokenKey = 'TokenLocaMos'
const Auth = 'AuthLocaMos'
const Locale = 'Locale'
const Layout = 'Layout'

export function getAccessToken() {
    return Cookies.get(TokenKey)
}

export function setAccessToken(token) {
    return Cookies.set(TokenKey, token, { expires: 1 } )
}

export function removeToken() {
    return Cookies.remove(TokenKey)
}

/* Refresh Token */

export function getAuth() {
    try {
        return JSON.parse(Cookies.get(Auth))
    } catch (e) {
        return {}
    }
}

export function setAuth(data) {
    return Cookies.set(Auth, data)
}

export function removeAuth() {
    return Cookies.remove(Auth)
}

// set layout
export function setLayout(data) {
    return Cookies.set(Layout, data, { expires: 1 } )
}

// get layout
export function getLayout() {
    return Cookies.get(Layout) || false;
}

// set lang
export function getLocale() {
    return Cookies.get(Locale)
}

export function setLocale(lang) {
    return Cookies.set(Locale, lang, { expires: 365 } )
}