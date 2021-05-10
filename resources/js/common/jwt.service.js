const ID_API_TOKEN = 'id_api_token';
const ROLE = 'role';
const CURRENT_COMPETITION = 'current_competition';
const CURRENT_CATEGORY = 'current_category';
const CURRENT_MODALITY = 'current_modality';
const CURRENT_ROUND = 'current_round';
const CURRENT_HEAT = 'current_heat';

const JwtService = {
    getToken() {
        return window.localStorage.getItem(ID_API_TOKEN)
    },
    setToken(token) {
        window.localStorage.setItem(ID_API_TOKEN, token);
    },
    unsetToken() {
        window.localStorage.removeItem(ID_API_TOKEN);
    },

    getRole() {
        return window.localStorage.getItem(ROLE)
    },
    setRole(role) {
        window.localStorage.setItem(ROLE, role);
    },
    unsetRole() {
        window.localStorage.removeItem(ROLE);
    },
}

export default JwtService