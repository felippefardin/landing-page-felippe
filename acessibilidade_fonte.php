<!-- acessibilidade.php -->
<div class="acessibilidade-fontes">
  <button onclick="alterarFonte('aumentar')">A+</button>
  <button onclick="alterarFonte('diminuir')">A-</button>
  <button onclick="alterarFonte('resetar')">Resetar</button>
</div>

<style>
.acessibilidade-fontes {
  position: fixed;
  top: 20px;
  left: 20px;
  z-index: 999;
  background: #fff;
  padding: 8px;
  border-radius: 8px;
  box-shadow: 0 0 10px #ccc;
}
.acessibilidade-fontes button {
  margin: 0 5px;
  padding: 6px 10px;
  font-size: 16px;
  cursor: pointer;
  background-color: #222;
  color: #fff;
  border: none;
  border-radius: 4px;
}
.acessibilidade-fontes button:hover {
  background-color: #444;
}
</style>

<script>
let tamanhoFontePadrao = 100;
const body = document.body;

function alterarFonte(acao) {
  if (acao === 'aumentar') {
    tamanhoFontePadrao += 10;
  } else if (acao === 'diminuir') {
    tamanhoFontePadrao -= 10;
  } else if (acao === 'resetar') {
    tamanhoFontePadrao = 100;
  }

  body.style.fontSize = tamanhoFontePadrao + '%';
  localStorage.setItem('tamanhoFonte', tamanhoFontePadrao);
}

document.addEventListener("DOMContentLoaded", function () {
  const fonteSalva = localStorage.getItem('tamanhoFonte');
  if (fonteSalva) {
    tamanhoFontePadrao = parseInt(fonteSalva);
    body.style.fontSize = tamanhoFontePadrao + '%';
  }
});
</script>
