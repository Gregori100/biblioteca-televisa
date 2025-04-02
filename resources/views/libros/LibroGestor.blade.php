@extends("layout.app")

@section('title', 'Libros gestor')

@section('content')
<div id='app' class="vista-web">
  <!-- LOADER -->
  <div v-if="loader" class="loader-overlay">
    <div class="loader"></div>
  </div>
  <!-- FIN LOADER -->

  <!-- ALERTA -->
  <alerta
    v-if="showAlerta"
    :alerta-mensaje="alertaMensaje"
    :alerta-class="alertaClass"
    :reset-tiempo="resetTiempoAlerta"
    @on-alerta-close="ocultarAlerta()"></alerta>
  <!-- FIN ALERTA -->

  <div class="encabezado">
    <h2>Biblioteca</h2>
    <div class="opciones">
    </div>
  </div>

  <div class="contenedor-contenido">
    <!-- FILTROS -->
    <form
      class="contenedor-filtros"
      action="{{ route('libros.gestor') }}"
      method="GET"
      ref="formFiltros">
      <div class="row-filtros-busqueda">
        <div class="row-filtros-busqueda__input">
          <div class="input-con-icono-contenedor">
            <input
              type="text"
              placeholder="Buscar"
              class="input-con-icono-derecha"
              value="{{ $filtros['busqueda'] ?? '' }}"
              name="busqueda"
              id="inputFiltros"
              ref="inputFiltros">
            <span class="icono-input-derecha puntero-cursor" @@click="$refs.formFiltros.submit()">
              <i class="icon-buscar"></i>
            </span>
          </div>
          <button
            type="button"
            class="boton-expandir-filtro"
            :class="cardFiltros ? 'activo' : ''"
            @@click="toggleFiltros()">
            <i class="icon-filtro"></i>
            Filtrar
            <i class="icon-angulo-abajo" v-if="!cardFiltros"></i>
            <i class="icon-angulo-arriba" v-else></i>
          </button>
        </div>
        <div class="row-filtros-busqueda__opciones">
        </div>
      </div>

      <input ref="inputMensajeAccion" type="hidden" :value="mensajeAccionGestor" v-if="mostrarMensajeAccionGestor">

      <div class="row-filtros-varios" v-if="cardFiltros">
        <div class="input-row">
          <label>Autor</label>
          <input
            type="text"
            placeholder="Autor"
            value="{{ $filtros['busquedaAutor'] ?? '' }}"
            name="busquedaAutor"
            id="inputFiltrosBusquedaAutor"
            ref="inputFiltrosBusquedaAutor">
        </div>

        <div class="input-row">
          <label>Editorial</label>
          <input
            type="text"
            placeholder="Editorial"
            value="{{ $filtros['busquedaEditorial'] ?? '' }}"
            name="busquedaEditorial"
            id="inputFiltrosBusquedaEditorial"
            ref="inputFiltrosBusquedaEditorial">
        </div>

        <div class="input-row">
          <label>Genero</label>
          <input
            type="text"
            placeholder="Genero"
            value="{{ $filtros['busquedaGenero'] ?? '' }}"
            name="busquedaGenero"
            id="inputFiltrosBusquedaGenero"
            ref="inputFiltrosBusquedaGenero">
        </div>

        <div class="input-row">
          <label>Idioma</label>
          <input
            type="text"
            placeholder="Idioma"
            value="{{ $filtros['busquedaIdioma'] ?? '' }}"
            name="busquedaIdioma"
            id="inputFiltrosBusquedaIdioma"
            ref="inputFiltrosBusquedaIdioma">
        </div>

        <div class="input-row">
          <label>ISBN</label>
          <input
            type="text"
            placeholder="ISBN"
            value="{{ $filtros['busquedaIsbn'] ?? '' }}"
            name="busquedaIsbn"
            id="inputFiltrosBusquedaIsbn"
            ref="inputFiltrosBusquedaIsbn">
        </div>

        <div class="input-row">
          <label>Status</label>
          <select name="statusDisponibilidad[]" id="selectStatusDisponibilidad" v-model="filtros.statusDisponibilidad">
            <option :value="undefined" disabled>Selecciona una opción</option>
            <option value="DISPONIBLE">Disponible</option>
            <option value="OCUPADO">Ocupado</option>
            <option value="RETIRADO">Retirado</option>
          </select>
        </div>

        <div class="input-row botones-filtros">
          <button
            type="submit"
            class="boton-aceptar">
            Buscar
          </button>
          <button
            type="button"
            class="boton-expandir-filtro"
            @@click="window.location.href='{{ route('libros.gestor') }}'">
            <i class="icon-filtro"></i>
            Limpiar
          </button>
        </div>
      </div>
    </form>
    <!-- FIN FILTROS -->

    <div class="contenedor-grid-gestor">
      <!-- <div id="Grid"></div> -->
      <table class="styled-table">
        <thead>
          <tr>
            <th class="w5p texto-centrado">Acciones</th>
            <th class="w5p texto-centrado">Folio</th>
            <th class="w10p">Nombre</th>
            <th class="w10p">Autor</th>
            <th class="w10p">Editorial</th>
            <th class="w6p texto-centrado">No. páginas</th>
            <th class="w8p">Género</th>
            <th class="w8p">Idioma</th>
            <th class="w9p">ISBN</th>
            <th class="w7p">Disponibilidad</th>
            <th class="w6p texto-centrado">Ocupación</th>
            <th class="w6p texto-centrado">Retorno</th>
            <th class="w10p">Observaciones</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="libros.length > 0">
            <tr v-for="libro in libros">
              <td>
                <div class="celda-acciones-gestor center" v-if="libro.status == 200">
                  <button
                    @@click="descargarCodigoQR(libro)"
                    class="boton-en-texto"
                    :id="'id-qr-' + libro.libroId"
                    title="Descargar QR">
                    <i class="icon-descargar"></i>
                  </button>
                  <button
                    class="boton-en-texto"
                    @@click="abrirModalOcuparLibro(libro)"
                    :id="'id-ocupar-' + libro.libroId"
                    title="Ocupar"
                    :disabled="libro.statusDisponibilidad != 'DISPONIBLE'">
                    <i class="icon-calendario"></i>
                  </button>
                </div>
              </td>
              <td class="texto-centrado">
                <a href="#">
                  @{{ libro.folio }}
                </a>
              </td>
              <td>
                <div class="texto-elipsis">
                  @{{ libro.nombre }}
                </div>
              </td>
              <td>
                <div class="texto-elipsis">
                  @{{ libro.autorNombre }}
                </div>
              </td>
              <td>
                <div class="texto-elipsis">
                  @{{ libro.editorialNombre }}
                </div>
              </td>
              <td class="texto-centrado">@{{ libro.numeroPaginas }}</td>
              <td>@{{ capitalizarTexto(libro.genero) }}</td>
              <td>@{{ capitalizarTexto(libro.idioma) }}</td>
              <td>@{{ libro.isbn }}</td>
              <td>
                <div class="status-global">
                  <div :class="'status-bullet '+ obtenerClaseStatus(libro.statusDisponibilidad)"></div>
                  @{{ capitalizarTexto(libro.statusDisponibilidad) }}
                </div>
              </td>
              <td class="texto-centrado">
                @{{ libro.salidaFecha ? moment(libro.salidaFecha).format("DD/MM/YYYY") : '' }}
              </td>
              <td class="texto-centrado">
                @{{ libro.regresoFecha ? moment(libro.regresoFecha).format("DD/MM/YYYY") : '' }}
              </td>
              <td>
                <div class="texto-elipsis">
                  @{{ libro.observaciones }}
                </div>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="13">
                <div class="texto-centrado">
                  No se encuentra ninguna libro relacionado
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODALES -->

  <!-- COMIENZA MODAL OCUPAR LIBRO -->
  <template>
    <div v-if="modalOcuparLibro" class="modal modal-eliminar">
      <div class="modal-card">
        <div class="modal-header solo-btn-cerrar">
          <i @@click="cerrarModalOcuparLibro" class="icon-cerrar" id="btnCerrarModalOcuparLibro"></i>
        </div>
        <div class="modal-body">
          <img class="ilustracion-modal ilustracion-eliminar" src="{{ asset('imagenes/calendar.svg') }}" alt="">

          <p class="titulo-modal">Ocupar libro</p>
          <p>¿Estás seguro que deseas ocupar el siguiente libro?</p>
          <p class="mb-8">Tienes máximo el día <span class="letra-bold">@{{ calcularFechaEntrega }}</span> para realizar la devolución.</p>

          <p class="subtitulo-modal">@{{ `${libroObj.folio} - ${libroObj.nombre}` }}</p>
        </div>
        <div class="modal-footer center no-border">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalOcuparLibro()"
            id="btnCancelarOcuparLibro">
            Cancelar</button>
          <button
            type="button"
            class="boton-aceptar"
            @@click="ocuparLibro"
            id="btnOcuparLibro">
            Aceptar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL OCUPAR LIBRO -->
</div>

<!-- VARIABLES -->
<script id="mensajeAccion" type="application/json">
  @json($mensajeAccion)
</script>
<script id="datosGestor" type="application/json">
  @json($datosGestor)
</script>
<script id="filtros" type="application/json">
  @json($filtros)
</script>

<script>
  var app = new Vue({
    el: '#app',
    data: {
      csrfToken: "{{ csrf_token() }}",
      showAlerta: false,
      alertaMensaje: null,
      alertaClass: false,
      resetTiempoAlerta: false,
      loader: false,
      cardFiltros: false,

      // URLs
      urlLibroGestor: "/libros",
      urlLibrosOcupar: "/libros/ocupar",
      urlDescargarCodigoQR: "/libros/descargar-codigo-qr",

      // Variables vista
      mensajeAccion: JSON.parse(document.getElementById('mensajeAccion').textContent),
      datosGestor: JSON.parse(document.getElementById('datosGestor').textContent),
      filtrosURL: JSON.parse(document.getElementById('filtros').textContent),

      // Filtros
      filtros: {
        status: null,
        statusDisponibilidad: undefined,
      },
      statusDisponibilidadOpc: [{
          value: "DISPONIBLE",
          text: "Disponible",
        },
        {
          value: "OCUPADO",
          text: "Ocupado",
        },
        {
          value: "RETIRADO",
          text: "Retirado",
        },
      ],

      // Data
      libros: [],
      libroObj: {
        nombre: null,
        autorNombre: null,
        editorialNombre: null,
        numeroPaginas: null,
        generoNombre: null,
        idiomaNombre: null,
        isbn: null,
        observaciones: null,
      },

      // Modales
      modalOcuparLibro: false,

      // Mensaje accion gestor
      mensajeAccionGestor: "",
      mostrarMensajeAccionGestor: false,

      // SyncFusion
      selectStatusDisponibilidad: null,
    },
    beforeDestroy() {},
    computed: {
      calcularFechaEntrega() {
        const hoy = new Date();
        hoy.setDate(hoy.getDate() + 14);

        return moment(hoy).format("DD/MM/YYYY")
      },
    },
    async mounted() {
      this.cargaInicial();
      this.cargaGestor();
      this.cargarFiltros();
    },
    methods: {
      cargaInicial() {
        // Carga de axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = this.csrfToken;
        axios.defaults.headers.common['Accept'] = 'application/json';

        // Mensaje en variable de vista
        if (this.mensajeAccion) {
          const tiposAlertas = {
            exito: "alerta-exito",
            advertencia: "alerta-advertencia",
            error: "alerta-error"
          };

          const claseAlerta = tiposAlertas[this.mensajeAccion.tipo];

          if (claseAlerta) {
            this.mostrarAlerta(claseAlerta, this.mensajeAccion.mensaje);
          }
        }

        // Mensaje en variable de sesion
        if ("{{ Session::get('error') }}" != "") {
          this.mostrarAlerta("alerta-error", "{{ Session::get('error') }}");
        }

        // Quitar parametro de URL
        quitarMensajeExitoURL();
      },
      cargaGestor() {
        // Setear registros
        // this.libros = this.datosGestor.registros;
        this.libros = this.datosGestor;

        this.$refs.inputFiltros.focus();
      },
      // Alerta
      mostrarAlerta(tipoAlerta, mensajeAlerta) {
        this.alertaMensaje = mensajeAlerta
        this.alertaClass = tipoAlerta;
        this.showAlerta = true;
        this.resetTiempoAlerta = !this.resetTiempoAlerta;
      },
      ocultarAlerta() {
        this.showAlerta = false;
      },

      /************************************************************/
      /************************* Filtros **************************/
      /************************************************************/
      cargarFiltros() {
        if (this.filtrosURL.statusDisponibilidad.length > 0) {
          this.filtros.statusDisponibilidad = this.filtrosURL.statusDisponibilidad[0];
        } else {
          this.filtros.statusDisponibilidad = undefined
        }

        // Abrir modal de ocupar libro
        if (this.filtrosURL.ocupar === "1" && this.libros.length == 1 && this.libros[0].statusDisponibilidad == "DISPONIBLE") {
          this.abrirModalOcuparLibro(this.libros[0]);
        }
      },
      async toggleFiltros() {
        this.cardFiltros = !this.cardFiltros;

        this.$nextTick(() => {
          if (this.cardFiltros) {}
        });
      },

      /************************************************************/
      /*********************** Ocupar libro ***********************/
      /************************************************************/
      abrirModalOcuparLibro(libroObj) {
        this.libroObj = {
          ...libroObj,
        };

        this.modalOcuparLibro = true;
      },
      cerrarModalOcuparLibro() {
        this.modalOcuparLibro = false;
        this.libroObj = {
          nombre: null,
          autorNombre: null,
          editorialNombre: null,
          numeroPaginas: null,
          generoNombre: null,
          idiomaNombre: null,
          isbn: null,
          observaciones: null,
        };
      },
      async ocuparLibro() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlLibrosOcupar, this.libroObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            // Mensaje acción
            this.mostrarMensajeAccionGestor = true;
            this.mensajeAccionGestor = "ocupar";
            this.$nextTick(() => {
              this.$refs.inputMensajeAccion.name = "exito";
              this.$refs.formFiltros.submit()
            });

            // this.$refs.formFiltros.submit();
            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      /************************************************************/
      /******************* Descargar codigo QR ********************/
      /************************************************************/
      async descargarCodigoQR(libro) {
        if (this.loader) return;

        let data = {
          params: {
            libroId: libro.libroId
          }
        };

        this.loader = true;
        await axios.get(this.urlDescargarCodigoQR, data)
          .then((resp) => {
            let data = resp.data;
            if (data.codigo != 200) {
              throw data.mensaje;
            }

            let archivo = data.data;

            let extension = archivo.extension;
            let base64 = archivo.base64;
            let nombre = archivo.nombre;
            let linkSource = `data:application/${extension};base64,${base64}`;

            let downloadLink = document.createElement("a");
            let fileName = nombre;
            downloadLink.href = linkSource;
            downloadLink.download = fileName;
            downloadLink.click();

            this.mostrarAlerta("alerta-exito", "Código descargado correctamente");
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
          })
          .then(() => {
            this.loader = false;
          });
      },
    }
  })
</script>
@endsection