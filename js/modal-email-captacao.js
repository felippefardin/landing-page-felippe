
function fecharModal() {
  document.getElementById('emailModal').style.display = 'none';
}

window.addEventListener("load", function () {
  setTimeout(() => {
    const modal = document.getElementById("emailModal");
    if (modal) {
      modal.style.display = "flex";
    }
  }, 2000);
});