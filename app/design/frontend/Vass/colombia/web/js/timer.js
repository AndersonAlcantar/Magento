class Timer {
    constructor() {
        //- El maximo de segundos para el contador
        this.maxSecond = 59;
        this.initSecond = 59;

        // Elementos del DOM
        this.timerText = document.querySelector(".js-timer-txt");
        this.timerMessage = document.querySelector(".js-timer-message");
        this.btnResetTimer = document.querySelector(".js-timer-btn");
        this.seconds = document.querySelector(".js-timer-seconds");

        // Clase para estados del temporizados
        this.classToHideContent = "u-hidden";

        this.init();
    }

    init() {
        if (this.btnResetTimer) {
            this.updateTimer();
            this.clickToResetTimer();
        }
    }

    updateTimer() {
        let counterTime = setInterval(() => {
            // Restar uno a la cantidad maxima de segundos cada segundo
            this.maxSecond -= 1;

            if (this.maxSecond === 0) {
                // Eliminar temporizador cuando llegue a 0
                clearInterval(counterTime);

                this.changeStateOfElements(false);
            }

            this.renderSecondsInDom();
        }, 1000);
    }

    // Resetear el temporizador para nuevo código
    clickToResetTimer() {
        this.btnResetTimer.addEventListener("click", () => {
            this.maxSecond = this.initSecond + 1;

            this.changeStateOfElements(true);

            this.updateTimer();
        });
    }

    changeStateOfElements(reset) {
        if (reset) {
            this.timerText.classList.remove(this.classToHideContent);
            this.timerMessage.classList.add(this.classToHideContent);

            this.btnResetTimer.setAttribute("disabled", "true");
        } else {
            this.timerText.classList.add(this.classToHideContent);
            this.timerMessage.classList.remove(this.classToHideContent);

            // Habilitar botón para resetear temporizador
            this.btnResetTimer.removeAttribute("disabled");
        }
    }

    renderSecondsInDom() {
        if (this.maxSecond < 10) {
            this.seconds.innerHTML = `0${this.maxSecond}`;
        } else {
            this.seconds.innerHTML = this.maxSecond;
        }
    }
}

new Timer();
