function formmaterReal(input) {
  var value = input.value.replace(/\D/g, '');
  value = (value / 100).toFixed(2);
  value = value.replace('.', ',');
  value = "R$ " + value;
  input.value = value;
}