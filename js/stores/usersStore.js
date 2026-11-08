const API_URL = "backend/api";

async function registerUser(userData) {

    const response = await fetch(
        `${API_URL}/register.php`,
        {
            method: "POST",
            body: userData,
            credentials: "same-origin"
        }
    );

    return await response.json();
}

async function getCurrentUser() {

    const response = await fetch(
        `${API_URL}/current_user.php`,
        {
            credentials: "same-origin"
        }
    );

    return await response.json();
}

async function updateUser(userData) {

    const response = await fetch(
        `${API_URL}/update_profile.php`,
        {
            method: "POST",
            body: userData,
            credentials: "same-origin"
        }
    );

    return await response.json();
}

export {
    registerUser,
    getCurrentUser,
    updateUser
};