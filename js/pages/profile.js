import {
    getCurrentUser,
    updateUser
} from "../stores/usersStore.js";

import { logout } from "../stores/authStore.js";

const profileForm = document.getElementById("profileForm");

const message = document.getElementById("message");

const logoutButton = document.getElementById("logoutButton");

// Load current user

async function loadProfile() {

    try {

        const result = await getCurrentUser();

        if (!result.success) {

            window.location.href = "login.html";
            return;

        }

        const user = result.data;

        document.getElementById("fullName").value =
            user.full_name;

        document.getElementById("email").value =
            user.email;

        document.getElementById("phone").value =
            user.phone;

        document.getElementById("createdAt").textContent =
            user.created_at;

    } catch (error) {

        message.textContent = "Unable to load profile.";

        console.error(error);

    }

}

// Update profile

profileForm.addEventListener("submit", async function (event) {

    event.preventDefault();

    message.textContent = "";

    const formData = new FormData(profileForm);

    try {

        const result = await updateUser(formData);

        message.textContent = result.message;

        message.className = result.success
            ? "message success"
            : "message";

        if (result.success) {
            await loadProfile();
        }

    } catch (error) {

        message.textContent = "Unable to update profile.";

        console.error(error);

    }

});

// Logout

logoutButton.addEventListener("click", async function () {

    try {

        const result = await logout();

        if (result.success) {
            window.location.href = "login.html";
        } else {
            message.textContent = result.message;
        }

    } catch (error) {

        message.textContent = "Unable to logout.";

        console.error(error);

    }

});

// Start

loadProfile();