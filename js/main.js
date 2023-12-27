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
