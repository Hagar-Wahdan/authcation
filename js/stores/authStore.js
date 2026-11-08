const API_URL = "backend/api";

async function login(email, password) {

    const formData = new FormData();

    formData.append("email", email);
    formData.append("password", password);

    const response = await fetch(
        `${API_URL}/login.php`,
        {
            method: "POST",
            body: formData,
            credentials: "same-origin"
        }
    );

    return await response.json();
}

async function logout() {

    const response = await fetch(
        `${API_URL}/logout.php`,
        {
            method: "POST",
            credentials: "same-origin"
        }
    );

    return await response.json();
}

async function isLoggedIn() {

    const result = await fetch(
        `${API_URL}/current_user.php`,
        {
            credentials: "same-origin"
        }
    );

    return result.ok;
}

export {
    login,
    logout,
    isLoggedIn
};