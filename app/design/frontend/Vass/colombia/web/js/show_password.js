window.onload = () => {
    function showPassword() {
        // Clase para el call to action
        const btnShowPassword = document.querySelectorAll(".js-password-btn");

        if (btnShowPassword.length > 0) {
            // Recorrer array de call to actions
            btnShowPassword.forEach((currentBtn) => {
                // Click para cada botón
                currentBtn.addEventListener("click", () => {
                    // Clase padre del campo password
                    const parentBtn = currentBtn.closest(".js-password-target");
                    // Input
                    const inputPassword = parentBtn.querySelector("input");

                    // Agregar o Remover clase para cambiar icono
                    parentBtn.classList.toggle("is-password-show");

                    // Cambiar attr type del input. password|text
                    if (inputPassword.type == "password") {
                        inputPassword.type = "text";
                    } else {
                        inputPassword.type = "password";
                    }
                });
            });

            return 1;
        } else {
            return 0;
        }
    }

    const intervalActiveShowPassword = setInterval(showPassword, 1000);

    const intervalClearIntervalShowPassword = setInterval(() => {
        showPassword();
        if (showPassword() > 0) {
            clearInterval(intervalActiveShowPassword);
            clearInterval(this);
            clearInterval(intervalClearIntervalShowPassword);
        }
    }, 1000);
};
