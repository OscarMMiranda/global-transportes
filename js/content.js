console.log('📦 variable_a_parsear:', variable_a_parsear);

let data;
try {
  data = typeof variable_a_parsear === 'string'
    ? JSON.parse(variable_a_parsear)
    : variable_a_parsear;
} catch (e) {
  console.warn('❌ Error al parsear variable_a_parsear:', variable_a_parsear, e);
  data = null;
}