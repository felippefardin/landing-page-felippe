function validarFormulario() {
    const nome = document.querySelector('[name="nome"]').value.trim();
    const email = document.querySelector('[name="email"]').value.trim();
    const mensagem = document.querySelector('[name="mensagem"]').value.trim();

    if (!nome || !email || !mensagem) {
        alert("Por favor, preencha todos os campos.");
        return false;
    }
    return true;
}


