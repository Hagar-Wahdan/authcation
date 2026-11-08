import { login } from "../stores/authStore.js";

const loginForm = document.getElementById("loginForm");

const message = document.getElementById("message");

const passwordInput = document.getElementById("password");

const togglePassword = document.getElementById("togglePassword");

// Show / Hide Password

togglePassword.addEventListener("click", function () {

    if (passwordInput.type === "password") {

        passwordInput.type = "text";
        togglePassword.textContent = "Hide";

    } else {

        passwordInput.type = "password";
        togglePassword.textContent = "Show";

    }

});

// Login

loginForm.addEventListener("submit", async function (event) {

    event.preventDefault();

    message.textContent = "";

    const email = document.getElementById("email").value.trim();

    const password = passwordInput.value;

    try {

        const result = await login(email, password);

        if (result.success) {

            message.textContent = result.message;
            message.className = "message success";

            window.location.href = "profile.html";

        } else {

            message.textContent = result.message;
            message.className = "message";

        }

    } catch (error) {

        message.textContent = "Unable to connect to server.";

        console.error(error);

    }

});