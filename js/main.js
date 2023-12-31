const OpenBoxpdv = document.getElementById('open-boxpdv');
const CloseBoxpdv = document.getElementById('close-boxpdv');
const CloseModalBoxPdv = document.getElementById('close-boxpdv-modal');
const overlay = document.getElementById("overlay");

OpenBoxpdv.addEventListener("click", async (e) => {
  if ((CloseBoxpdv.style.display = "none")) {
    CloseBoxpdv.style.display = "block";
    overlay.style.display = "block";
    CloseBoxpdv.style.transition = "transform 0.9s";
  }
});

CloseModalBoxPdv.addEventListener("click", async (e) => {
  if ((CloseBoxpdv.style.display = "block")) {
    CloseBoxpdv.style.display = "none";
    overlay.style.display = "none";
    CloseBoxpdv.style.transition = "transform 0.9s";
  }
});

document.getElementById("cnpj").addEventListener("input", function (e) {
  var input = e.target;
  var value = input.value.replace(/\D/g, "");

  if (value.length > 14) {
    value = value.slice(0, 14);
  }

  input.value = formmatecnpj(value);
});

document.getElementById("cpf").addEventListener("input", function (e) {
  var input = e.target;
  var value = input.value.replace(/\D/g, "");

  if (value.length > 11) {
    value = value.slice(0, 11);
  }

  input.value = formmatecpf(value);
});

function formmatecnpj(value) {
  return value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
}

function formmatecpf(value) {
  return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
}
