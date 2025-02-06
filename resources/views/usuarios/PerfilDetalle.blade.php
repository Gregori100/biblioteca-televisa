@extends("layout.app")

@section('title', 'Detalle perfil de acceso')

@section('content')
<div id='app' class="vista-web">
  <!-- Loader -->
  <div v-if="loader" class="loader-overlay">
    <div class="loader"></div>
  </div>
  <!-- Fin loader -->

  <!-- Alerta -->
  <alerta
    v-if="showAlerta"
    :alerta-mensaje="alertaMensaje"
    :alerta-class="alertaClass"
    :reset-tiempo="resetTiempoAlerta"
    @on-alerta-close="ocultarAlerta()"></alerta>
  <!-- Fin alerta -->

  <div class="encabezado">
    <h2>Detalle de perfil de acceso</h2>
    <div class="opciones">
      <usuario-activo :usuario-logueado="usuarioLogueado" :csrf-token="csrfToken" />
    </div>
  </div>

  <div class="contenedor-contenido contenido-detalle-row">
    <div class="contenedor-detalle ancho-maximo-554 border-right">
      <div class="contenedor-datos-detalle">
        <div class="detalle-datos">
          <div class="row-titulo-detalle">
            <h3>@{{ `${perfilObj.titulo}` }}</h3>
            <button
              class="boton-opciones-puntos"
              :class="showDropdown ? 'boton-opciones-puntos--activo' : ''"
              @@click="toggleDropdown()"
              ref="iconoPuntos"
              :disabled="perfilObj.status == 300"
              id="btnOpciones">
              <i class="icon-puntos"></i>
            </button>
            <div v-if="showDropdown" class="dropdown-menu" :style="dropdownStyle" id="dropdownOpciones" ref="dropdownOpciones">
              <ul>
                <li>
                  <!-- :disabled="!permisosVista.editar" -->
                  <button
                    class="boton-en-texto"
                    @@click="abrirModalEditarPerfil()"
                    id="opcEditar">
                    <i class="icon-editar"></i>Editar
                  </button>
                </li>
                <li>
                  <!-- :disabled="!permisosVista.eliminar" -->
                  <button
                    class="boton-en-texto"
                    @@click="abrirModalEliminarPerfil()"
                    id="opcEliminar">
                    <i class="icon-eliminar"></i>Eliminar
                  </button>
                </li>
              </ul>
            </div>
          </div>

          <table class="tabla-detalle">
            <tbody>
              <tr>
                <td class="w30p">Clave</td>
                <td>@{{ perfilObj.clave }}</td>
              </tr>
              <tr>
                <td>Nombre</td>
                <td>@{{ perfilObj.titulo }}</td>
              </tr>
              <tr>
                <td>Descripción</td>
                <td>@{{ perfilObj.descripcion }}</td>
              </tr>
              <tr>
                <td>Fecha registro</td>
                <td>@{{ moment(perfilObj.registroFecha).format("YYYY/MM/DD") }}</td>
              </tr>
              <tr>
                <td>Fecha actualización</td>
                <td>@{{ perfilObj.actualizacionFecha ? moment(perfilObj.actualizacionFecha).format("YYYY/MM/DD") : "" }}</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>
                  <div class="status-global">
                    <div class="status-bullet" :class="obtenerClaseStatus(perfilObj.status)"></div>
                    @{{ capitalizarTexto(perfilObj.statusNombre) }}
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="contenedor-detalle ancho-maximo-602 espacio-entre-contenedores-detalle">
      <div class="contenedor-datos-detalle ocultar-overflow">
        <div class="detalle-datos">
          <div class="row-titulo-detalle mb-34">
            <h3>Lista de permisos</h3>
            <!-- :disabled="!permisosVista.editarPermisos || perfilObj.status == 300" -->
            <button
              id="btnEditarPermisos"
              class="boton-en-texto mr-8"
              @@click="irEditarPermisos()"
              :disabled="perfilObj.status == 300">
              <i class="icon-editar"></i>
            </button>
          </div>

          <div class="contenedor-lista-expandible">
            <template v-for="(seccion, key) in seccionesPermisos">
              <div class="row-padre-lista-expandible mb-8">
                <h4>@{{ key }}</h4>
                <i
                  class="icon-angulo-arriba"
                  @@click="seccion.checkExpandir = !seccion.checkExpandir"
                  v-if="seccion.checkExpandir"></i>
                <i
                  class="icon-angulo-abajo"
                  @@click="seccion.checkExpandir = !seccion.checkExpandir"
                  v-else></i>
              </div>
              <transition name="lista-expandible" mode="out-in">
                <table class="tabla-detalle" v-if="seccion.checkExpandir">
                  <tbody>
                    <tr v-for="(permiso, key) in seccion.permisos">
                      <td class="w4p">
                        <i class="icon-realizar verde" v-if="permiso.checkPermiso"></i>
                      </td>
                      <td class="w35p">@{{ permiso.titulo }}</td>
                      <td>@{{ permiso.descripcion }}</td>
                    </tr>
                  </tbody>
                </table>
              </transition>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODALES -->
  <!-- COMIENZA MODAL EDITAR PERFIL -->
  <template>
    <div v-if="modalEditarPerfil" class="modal" id="modalEditarPerfil">
      <div class="modal-card">
        <div class="modal-header">
          <label>Editar datos del perfil de acceso</label>
          <i @@click="cerrarModalEditarPerfil" class="icon-cerrar" id="btnCerrarModalEditarPerfil"></i>
        </div>
        <div class="modal-body">
          <form
            id="formEditarPerfil"
            ref="formEditarPerfil"
            @@submit.prevent="editarPerfil()">
            <div class="input-row">
              <label class="requerido">Clave</label>
              <input
                type="text"
                placeholder="Clave"
                required
                maxlength="20"
                v-model.trim="perfilEditarObj.clave"
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
                v-model.trim="perfilEditarObj.titulo"
                id="tituloInput">
            </div>
            <div class="input-row">
              <label class="requerido">Descripción</label>
              <input
                type="text"
                placeholder="Descripción"
                required
                maxlength="250"
                v-model.trim="perfilEditarObj.descripcion"
                id="descripcionInput">
            </div>
          </form>
        </div>
        <div class="modal-footer footer-end">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalEditarPerfil()"
            id="btnCancelarEditar">
            Cancelar</button>
          <button
            type="submit"
            class="boton-aceptar"
            form="formEditarPerfil"
            id="btnEditar">
            Guardar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL EDITAR CONTRATO -->

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
<script id="hashId" type="application/json">
  @json($hashId)
</script>
<script id="perfilObj" type="application/json">
  @json($perfilObj)
</script>
<script id="seccionesPermisos" type="application/json">
  @json($seccionesPermisos)
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

      // Dropdown opciones detalle
      showDropdown: false,
      dropdownStyle: {},

      // URLs
      urlPerfilesEditar: "/usuarios/editar-perfil",
      urlPerfilesEliminar: "/usuarios/eliminar-perfil",
      urlPerfilGestor: "/usuarios/perfiles",

      // Variables vista
      usuarioLogueado: JSON.parse(document.getElementById('usuarioLogueado').textContent),
      permisosVista: JSON.parse(document.getElementById('permisosVista').textContent),
      mensajeAccion: JSON.parse(document.getElementById('mensajeAccion').textContent),
      hashId: JSON.parse(document.getElementById('hashId').textContent),
      perfilObj: JSON.parse(document.getElementById('perfilObj').textContent),
      seccionesPermisos: JSON.parse(document.getElementById('seccionesPermisos').textContent),

      // Data
      perfilEditarObj: {},

      // Modales
      modalEditarPerfil: false,
      modalEliminarPerfil: false,

      // SyncFusion
      selectEstado: null,
    },
    beforeDestroy() {
      // Evento para cerrar opcion 3 puntos
      window.removeEventListener('click', this.handleClickOutside);
    },
    async mounted() {
      this.cargaInicial();
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

        // Listener para cerrar opcion 3 puntos
        window.addEventListener('click', this.handleClickOutside);
      },
      handleClickOutside(event) {
        if (this.showDropdown && !this.$refs.iconoPuntos.contains(event.target) && !this.$refs.dropdownOpciones.contains(event.target)) {
          this.toggleDropdown();
        }
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

      // Toggle 3 puntos
      toggleDropdown() {
        this.showDropdown = !this.showDropdown;
        if (this.showDropdown) {
          this.setPosition();
        }
      },
      setPosition() {
        const icono = this.$refs.iconoPuntos;
        const rect = icono.getBoundingClientRect();
        this.dropdownStyle = {
          top: `${36}px`,
          right: `${0}px`,
          position: 'absolute'
        };
      },

      // Editar perfil
      abrirModalEditarPerfil() {
        this.modalEditarPerfil = true;

        this.perfilEditarObj = {
          ...this.perfilObj
        }

        this.toggleDropdown();
      },
      cerrarModalEditarPerfil() {
        this.modalEditarPerfil = false;

        this.perfilEditarObj = {}
      },
      async editarPerfil() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlPerfilesEditar, this.perfilEditarObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            window.location.href =
              `${this.urlPerfilGestor}/${this.perfilObj.perfilId}/${this.hashId}?exito=editar`
            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Eliminar
      abrirModalEliminarPerfil(perfil) {
        this.modalEliminarPerfil = true;
      },
      cerrarModalEliminarPerfil() {
        this.modalEliminarPerfil = false;
      },
      async eliminarPerfil() {
        if (this.detallePerfil) return;

        this.loader = true;
        await axios.post(this.urlPerfilesEliminar, this.perfilObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            window.location.href =
              `${this.urlPerfilGestor}/${this.perfilObj.perfilId}/${this.hashId}?exito=eliminar`

            this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Permisos
      irEditarPermisos() {
        window.location.href = `${this.urlPerfilGestor}/editar/${this.perfilObj.perfilId}/${this.hashId}`
      },
    }
  })
</script>
@endsection