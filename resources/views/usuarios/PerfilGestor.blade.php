@extends("layout.app")

@section('title', 'Perfiles')

@section('content')
<div id='app' class="vista-web">
  <!-- LOADER -->
  <div v-if="loader" class="loader-overlay">
    <div class="loader"></div>
  </div>
  <!-- FIN LOADER -->

  <!-- Alerta -->
  <alerta
    v-if="showAlerta"
    :alerta-mensaje="alertaMensaje"
    :alerta-class="alertaClass"
    :reset-tiempo="resetTiempoAlerta"
    @on-alerta-close="ocultarAlerta()"></alerta>
  <!-- Fin alerta -->

  <div class="encabezado">
    <h2>Perfiles de acceso</h2>
    <div class="opciones">
      <usuario-activo :usuario-logueado="usuarioLogueado" :csrf-token="csrfToken" />
    </div>
  </div>

  <div class="contenedor-contenido">
    <!-- Filtros -->
    <form
      class="contenedor-filtros"
      action="{{ route('usuarios.perfilesGestor') }}"
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
              ref="inputFiltros"
              id="inputFiltros">
            <span class="icono-input-derecha puntero-cursor" @@click="$refs.formFiltros.submit()" id="btnBusquedaGestor">
              <i class="icon-buscar"></i>
            </span>
          </div>
          <button
            type="button"
            class="boton-expandir-filtro"
            @@click="window.location.href='{{ route('usuarios.perfilesGestor') }}'"
            id="btnLimpiarGestor">
            <i class="icon-filtro"></i>
            Limpiar
          </button>
        </div>
        <div class="row-filtros-busqueda__opciones">
          <!-- :disabled="!permisosVista.agregar" -->
          <button
            type="button"
            class="boton-agregar-filtro"
            @@click="abrirModalAgregarPerfil()"
            id="btnAgregarPerfil">
            <i class="icon-agregar"></i>
            Nuevo perfil
          </button>
        </div>
      </div>

      <input ref="inputMensajeAccion" type="hidden" :value="mensajeAccionGestor" v-if="mostrarMensajeAccionGestor">

      <div class="row-filtros-varios" v-if="cardFiltros">
      </div>
    </form>
    <!-- Fin filtros -->

    <div class="contenedor-grid-gestor">
      <table class="styled-table">
        <thead>
          <tr>
            <th class="w15p">Clave</th>
            <th class="w20p">Título</th>
            <th class="w30p">Descripción</th>
            <th class="w10p">Status</th>
            <th class="w10p">Fecha registro</th>
            <th class="w10p">Autor</th>
            <th class="w5p texto-centrado">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="perfiles.length > 0">
            <tr v-for="perfil in perfiles">
              <td>
                <a :href="'perfiles/' + perfil.perfil_id + '/' + perfil.hash_id" :id="'btn-detalle-' + perfil.perfil_id">
                  @{{ perfil.clave }}
                </a>
              </td>
              <td>@{{ perfil.titulo }}</td>
              <td>
                <div class="texto-elipsis">
                  @{{ perfil.descripcion }}
                </div>
              </td>
              <td>
                <div class="status-global">
                  <div :class="'status-bullet '+ obtenerClaseStatus(perfil.status)"></div>
                  @{{ capitalizarTexto(perfil.status_nombre) }}
                </div>
              </td>
              <td>@{{ perfil.registro_fecha ? moment(perfil.registro_fecha).format("DD/MM/YYYY") : '' }}</td>
              <td>@{{ perfil.registro_autor }}</td>
              <td>
                <div class="celda-acciones-gestor center" v-if="perfil.status == 200">
                  <!-- v-if="permisosVista.eliminar" -->
                  <button
                    @@click="abrirModalEliminarPerfil(perfil)"
                    class="boton-en-texto"
                    :id="'id-eliminar-' + perfil.perfil_id"
                    title="Eliminar perfil"
                    :disabled="perfil.status != 200">
                    <i class="icon-eliminar"></i>
                  </button>
                </div>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="7">
                <div class="texto-centrado">
                  No se encuentra ningún perfil
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODALES -->
  <!-- COMIENZA MODAL AGREGAR PERFIL -->
  <template>
    <div v-if="modalAgregarPerfil" class="modal" id="modalAgregarPerfil">
      <div class="modal-card" id="modalCardAgregarPerfil">
        <div class="modal-header">
          <label>Nuevo perfil de acceso</label>
          <i @@click="cerrarModalAgregarPerfil" class="icon-cerrar" id="btnCerrarModalAgregar"></i>
        </div>
        <div class="modal-body">
          <form id="formAgregarPerfil" ref="formAgregarPerfil" @@submit.prevent="agregarPerfil()">
            <div class="input-row">
              <label class="requerido">Clave</label>
              <input
                type="text"
                placeholder="Clave"
                required
                maxlength="20"
                v-model.trim="perfilObj.clave"
                ref="claveInput"
                id="claveInput">
            </div>
            <div class="input-row">
              <label class="requerido">Título</label>
              <input
                type="text"
                placeholder="Clave"
                required
                maxlength="45"
                v-model.trim="perfilObj.titulo"
                id="tituloInput">
            </div>
            <div class="input-row">
              <label class="requerido">Descripción</label>
              <input
                type="text"
                placeholder="Descripción"
                required
                maxlength="250"
                v-model.trim="perfilObj.descripcion"
                id="descripcionInput">
            </div>
          </form>
        </div>
        <div class="modal-footer center">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalAgregarPerfil()"
            id="btnCancelarAgregar">
            Cancelar</button>
          <button
            type="submit"
            class="boton-aceptar"
            form="formAgregarPerfil"
            id="btnGuardarAgregar">
            Guardar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL AGREGAR PERFIL -->

  <!-- COMIENZA MODAL ELIMINAR PERFIL -->
  <template>
    <div v-if="modalEliminarPerfil" class="modal modal-eliminar">
      <div class="modal-card">
        <div class="modal-header solo-btn-cerrar">
          <i @@click="cerrarModalEliminarPerfil" class="icon-cerrar" id="btnCerrarModalEliminarPerfil"></i>
        </div>
        <div class="modal-body">
          <img class="ilustracion-modal ilustracion-eliminar" src="{{ asset('imagenes/biblioteca-ilustracion-eliminar.svg') }}" alt="">

          <p class="titulo-modal">Eliminar registro</p>
          <p>¿Estás seguro que deseas eliminar la siguiente perfil?</p>
          <p class="mb-8">Esta acción no se puede deshacer.</p>

          <p class="subtitulo-modal">@{{ `${perfilObj.clave} - ${perfilObj.titulo}` }}</p>
        </div>
        <div class="modal-footer center no-border">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalEliminarPerfil()"
            id="btnCancelarEliminarPerfil">
            Cancelar</button>
          <button
            type="button"
            class="boton-aceptar boton--eliminar"
            @@click="eliminarPerfil"
            id="btnEliminarPerfil">
            Eliminar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL ELIMINAR PERFIL -->
</div>

<script id="usuarioLogueado" type="application/json">
  @json($usuarioLogueado)
</script>
<script id="permisosVista" type="application/json">
  @json($permisosVista)
</script>
<script id="mensajeAccion" type="application/json">
  @json($mensajeAccion)
</script>
<script id="perfiles" type="application/json">
  @json($perfiles)
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
      urlPerfilesGestor: "/usuarios/perfiles",
      urlPerfilesAgregar: "/usuarios/agregar-perfil",
      urlPerfilesEliminar: "/usuarios/eliminar-perfil",

      usuarioLogueado: JSON.parse(document.getElementById('usuarioLogueado').textContent),
      permisosVista: JSON.parse(document.getElementById('permisosVista').textContent),
      mensajeAccion: JSON.parse(document.getElementById('mensajeAccion').textContent),
      perfiles: JSON.parse(document.getElementById('perfiles').textContent),

      filtros: {
        busqueda: ""
      },

      // Data
      perfilObj: {},

      // Modales
      modalAgregarPerfil: false,
      modalEliminarPerfil: false,

      // Mensaje accion gestor
      mensajeAccionGestor: "",
      mostrarMensajeAccionGestor: false,
    },
    async mounted() {
      this.cargaInicial();
      // this.cargaGestor();
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
      },
      mostrarAlerta(tipoAlerta, mensajeAlerta) {
        this.alertaMensaje = mensajeAlerta
        this.alertaClass = tipoAlerta;
        this.showAlerta = true;
        this.resetTiempoAlerta = !this.resetTiempoAlerta;
      },
      ocultarAlerta() {
        this.showAlerta = false;
      },

      abrirModalAgregarPerfil() {
        this.modalAgregarPerfil = true;

        this.$nextTick(() => {
          this.$refs.claveInput.focus();
        });
      },
      cerrarModalAgregarPerfil() {
        this.modalAgregarPerfil = false;
        this.perfilObj = {}
      },
      async agregarPerfil() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlPerfilesAgregar, this.perfilObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            let id = data.data.perfilId;
            let hashId = data.data.hashId;

            window.location.href = `${this.urlPerfilesGestor}/${id}/${hashId}?exito=agregar`
            this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Eliminar
      abrirModalEliminarPerfil(perfil) {
        this.modalEliminarPerfil = true;

        this.perfilObj = {
          ...perfil,
          "perfilId": perfil.perfil_id,
        }
      },
      cerrarModalEliminarPerfil() {
        this.modalEliminarPerfil = false;
        this.perfilObj = {}
      },
      async eliminarPerfil() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlPerfilesEliminar, this.perfilObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            // Mensaje acción
            this.mostrarMensajeAccionGestor = true;
            this.mensajeAccionGestor = "eliminar";
            this.$nextTick(() => {
              this.$refs.inputMensajeAccion.name = "exito";
              this.$refs.formFiltros.submit()
            });

            // window.location.href = `${this.urlPerfilesGestor}`

            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },
    }
  })
</script>
@endsection