let tamanhoFonte = localStorage.getItem("tamanhoFonte")
    ? parseInt(localStorage.getItem("tamanhoFonte"))
    : 100;

document.documentElement.style.fontSize = tamanhoFonte + "%";

function atualizarFonte() {
    document.documentElement.style.fontSize = tamanhoFonte + "%";
    localStorage.setItem("tamanhoFonte", tamanhoFonte);
}

document
    .getElementById("aumentar-fonte")
    .addEventListener("click", () => {
        if (tamanhoFonte < 150) {
            tamanhoFonte += 10;
            atualizarFonte();
        }
    });

document
    .getElementById("diminuir-fonte")
    .addEventListener("click", () => {
        if (tamanhoFonte > 70) {
            tamanhoFonte -= 10;
            atualizarFonte();
        }
    });

document
    .getElementById("resetar-fonte")
    .addEventListener("click", () => {
        tamanhoFonte = 100;
        atualizarFonte();
    });