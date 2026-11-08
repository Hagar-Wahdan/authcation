import { registerUser } from "../stores/usersStore.js";

const registerForm = document.getElementById("registerForm");
const message = document.getElementById("message");

registerForm.addEventListener("submit", async function (event) {

    event.preventDefault();

    message.textContent = "";
    message.className = "message";

    const formData = new FormData(registerForm);

    const password = formData.get("password");
    const confirmPassword = formData.get("confirm_password");

    if (password !== confirmPassword) {
        message.textContent = "Passwords do not match.";
        return;
    }

    try {

        const result = await registerUser(formData);

        if (result.success) {

            message.textContent = result.message;
            message.className = "message success";

            registerForm.reset();

            setTimeout(function () {
                window.location.href = "login.html";
            }, 1200);

        } else {

            message.textContent = result.message;

        }

    } catch (error) {

        message.textContent = "Unable to connect to server.";

        console.error(error);

    }

});