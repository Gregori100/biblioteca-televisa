const mascaraTelefono = (value) => {
  const cleaned = value.replace(/\D/g, '').slice(0, 10);

  if (cleaned.length === 0) {
    return "";
  }

  const match = cleaned.match(/^(\d{0,3})(\d{0,3})(\d{0,4})$/);
  if (match) {
    return `${match[1] ? `${match[1]}` : ''}${match[2] ? ` ${match[2]}` : ''}${match[3] ? ` ${match[3]}` : ''}`;
  }
}

const numeroArticulos = (array) => {
  if (array.length > 1) {
    return `${array.length} Artículos`
  } else {
    return `${array.length} Artículo`
  }
}

const getHash = async (id) => {
  let hashn = "";
  const n = 5;

  const encoder = new TextEncoder();
  const data = encoder.encode(id.toString());
  const hashBuffer = await crypto.subtle.digest('SHA-256', data);

  const hashArray = Array.from(new Uint8Array(hashBuffer));
  const sha = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join('');

  hashn = sha.substring(sha.length - n);

  return hashn;
}

const armarHashKey = async (cadena) => {
  const timestamp = Math.floor(Date.now() / 600000).toString();
  const dataWithTimestamp = cadena + timestamp;

  // Convertir los datos a un array de bytes
  const encoder = new TextEncoder();
  const dataBuffer = encoder.encode(dataWithTimestamp);
  const hashBuffer = await crypto.subtle.digest('SHA-256', dataBuffer);
  const hashArray = Array.from(new Uint8Array(hashBuffer));
  let hashHex = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join('');
  return hashHex.slice(-10);
}

const validarEmail = (value) => {
  let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  return re.test(value);
}

const mayusculas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
  'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
];

const minusculas = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
  'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
];

const numeros = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

const especiales = ['!', '.'];

const generarPasswordSegura = (contraseniaLongitud) => {
  if (contraseniaLongitud < 4) {
    contraseniaLongitud = 4;
  }

  const allCharacters = [...mayusculas, ...minusculas, ...numeros, ...especiales];

  function getRandomInt(max) {
    return Math.floor(Math.random() * max);
  }

  // Generar una contraseña inicial con al menos un carácter de cada tipo
  let password = '';
  password += mayusculas[getRandomInt(mayusculas.length)];
  password += minusculas[getRandomInt(minusculas.length)];
  password += numeros[getRandomInt(numeros.length)];
  password += especiales[getRandomInt(especiales.length)];

  // Llenar el resto de la longitud de la contraseña con caracteres aleatorios
  for (let i = 4; i < contraseniaLongitud; i++) {
    const caracter = getRandomInt(allCharacters.length);
    password += allCharacters[caracter];
  }

  // Mezclar los caracteres para que no sigan un patrón predecible
  password = password.split('').sort(() => 0.5 - Math.random()).join('');

  return password;
}

const noSpaces = (e) => {
  if (e.key === " ") {
    e.preventDefault();
    return false;
  }
  return true;
};

const capitalizarTexto = (texto) => {
  if (texto == "" || texto == null || texto == undefined) return "";

  texto = texto.replace(/_/g, ' '); // Reemplaza guiones bajos por espacios
  return texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();

  // return texto
  //   .split(' ')
  //   .map(palabra => {
  //     return palabra.charAt(0).toUpperCase() + palabra.slice(1).toLowerCase();
  //   })
  //   .join(' ');
}

const agregarCero = (numero) => {
  return numero.toString().padStart(2, '0');
}

const validateInputNumber = (inputElement) => {
  let inputValue = inputElement.value;

  inputValue = inputValue.replace(/[^0-9.]/g, '');

  const parts = inputValue.split('.');

  if (parts.length > 2) {
    inputValue = parts[0] + '.' + parts.slice(1).join('');
  }

  if (parts[1] && parts[1].length > 2) {
    inputValue = parts[0] + '.' + parts[1].substring(0, 2);
  }

  inputElement.value = inputValue;
}

const generateNanoId = (length = 18) => {
  const chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  let nanoId = '';

  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * chars.length);
    nanoId += chars[randomIndex];
  }

  return nanoId;
}

formatearDecimales = (valor) => {
  //* Separar la cantidad entera y decimal
  let cantidades = valor.split('.');

  //* Limpiar digitos de la cantidad entera
  cantidades[0] = cantidades[0].replace(/\D/g, '');

  //* Limpiar cantidad decimal si
  if (cantidades[1]) {
    cantidades[1] = cantidades[1].replace(/\D/g, '');
  }

  // Formatear la parte entera con comas
  cantidades[0] = cantidades[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

  // Unir las partes nuevamente
  valor = cantidades.join('.');

  return valor;
}

numberFormat = (monto, decimales = 0, signo = 1, coma = 1) => {
  monto += '';
  monto = parseFloat(monto.replace(/[^0-9\.-]/g, ''));

  if ((isNaN(monto) || monto === 0) && signo == 1)
    return '$' + parseFloat(0).toFixed(decimales);

  if ((isNaN(monto) || monto === 0) && signo == 0)
    return parseFloat(0).toFixed(decimales);

  monto = '' + monto.toFixed(decimales);

  let parteMonto = monto.split('.'),
    regexp = /(\d+)(\d{3})/;

  if (coma == 1) {
    while (regexp.test(parteMonto[0]))
      parteMonto[0] = parteMonto[0].replace(regexp, '$1' + ',' + '$2');
  }

  if (signo == 0)
    return parteMonto.join('.');

  if (signo == 1)
    return '$' + parteMonto.join('.');
}


// Componentes syncfusion
cargarMultiSelectCheckBoxSyncfusion = (
  id,
  changeFn = () => { },
  dataSource = [],
  fields = {
    value: 'id',
    text: 'value',
  },
  value = [],
  enabled = true,
  placeholder = "Selecciona una opción",
  height = "300px",
  showSelectAll = false,
  showDropDownIcon = true,
  allowFiltering = false,
) => {
  selectObj = new ej.dropdowns.MultiSelect({
    enabled: enabled, // Habilitado
    dataSource: dataSource, // Data del multiselect
    fields: fields, // Campos para id y valor
    placeholder: placeholder, // Placeholder
    mode: 'CheckBox', // Modo checkbox
    showSelectAll: showSelectAll, // Mostrar select todos
    showDropDownIcon: showDropDownIcon, // Mostrar icono dropdown
    popupHeight: height, // Alto
    allowFiltering: allowFiltering, // Habilitar filtrado
    value: value, // Valores por defecto
    change: changeFn, // Función que se desea cuando se selecciona
  });

  // Se carga select a traves de su id
  selectObj.appendTo("#" + id);

  return selectObj;
};

cargarSelectSyncfusion = (
  id,
  changeFn = () => { },
  dataSource = [],
  fields = {
    value: 'id',
    text: 'value',
  },
  value = null,
  enabled = true,
  placeholder = "Selecciona una opción",
  height = "300px",
  allowFiltering = false,
) => {
  // Crear objeto Select de Syncfusion
  const selectObj = new ej.dropdowns.DropDownList({
    enabled: enabled, // Habilitado
    dataSource: dataSource, // Data del select
    fields: fields, // Campos para id y valor
    placeholder: placeholder, // Placeholder
    popupHeight: height, // Alto del dropdown
    allowFiltering: allowFiltering, // Habilitar filtrado
    value: value, // Valor por defecto
    change: changeFn, // Función para manejar cambio de selección

    // Añadir el evento "open" para personalizar el comportamiento en dispositivos móviles
    open: function (args) {
      if (ej.base.Browser.isDevice) { // Verificar si es un dispositivo móvil
        args.popup.element.classList.remove('e-ddl-device', 'e-ddl-device-filter');
        args.popup.element.style.width = args.popup.width;
        args.popup.element.style.maxHeight = parseInt(height) + 'px'; // Usar el valor de altura proporcionado
        args.popup.element.querySelector('.e-content.e-dropdownbase').style.maxHeight = (parseInt(height) - 25) + 'px';
        args.popup.element.querySelector('.e-content.e-dropdownbase').style.height = 'auto';
        args.popup.collision = { X: 'flip', Y: 'flip' }; // Ajustar la posición del popup
        args.popup.position = { X: 'left', Y: 'bottom' };
        args.popup.dataBind();
      }
    }
  });

  // Se carga el select a través de su id
  selectObj.appendTo("#" + id);

  return selectObj;
};

cargarDatePickerSyncfusion = (
  id,
  changeFn = () => { },
  value = null,
  enabled = true,
  min = null, // Fecha mínima permitida
  max = null, // Fecha máxima permitida
  placeholder = "Elige una fecha",
  start = 'Month', // El nivel inicial que se muestra (Mes, Año, Década)
  depth = 'Month', // La profundidad hasta la que se puede navegar
  format = 'yyyy/MM/dd', // Formato de la fecha
  allowMask = true, // Activar o desactivar la máscara
) => {
  // Crear objeto DatePicker de Syncfusion
  const datePickerObj = new ej.calendars.DatePicker({
    enabled: enabled, // Habilitado o no
    value: value, // Valor inicial de la fecha
    placeholder: placeholder, // Placeholder
    start: start, // Nivel inicial del calendario (meses, años, etc.)
    depth: depth, // Profundidad para seleccionar la fecha
    format: format, // Formato de la fecha
    mask: allowMask, // Activar máscara si está permitido
    min: min, // Fecha mínima permitida
    max: max, // Fecha máxima permitida
    change: changeFn, // Función para manejar cambio de fecha
  });

  // Se carga el DatePicker a través de su id
  datePickerObj.appendTo("#" + id);

  // Aplicar la máscara
  const inputElement = document.querySelector("#" + id);
  const im = new Inputmask("9999/99/99");
  im.mask(inputElement);

  return datePickerObj;
};


// Obtener status
const obtenerClaseStatus = (status) => {
  switch (status) {
    case 100:
      return "status-pendiente";
    case 200:
    case "DISPONIBLE":
      return "status-activo";
    case 300:
    case "RETIRADO":
      return "status-eliminado";
    case "OCUPADO":
      return "status-progreso";
    default:
      return "status-pendiente";
  }
};
