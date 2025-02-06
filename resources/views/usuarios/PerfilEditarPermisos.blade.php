@extends("layout.app")

@section('title', 'Editar perfil de acceso')

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
    <h2>
      <i class="icon-flecha-izquierda mr-8 puntero-cursor" @@click="irDetallePerfil()"></i>
      Editar permisos perfil de acceso
    </h2>
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
                <td>@{{ moment(perfilObj.registroFecha).format("YYYY/MM/DD HH:mm") }}</td>
              </tr>
              <tr>
                <td>Fecha actualización</td>
                <td>@{{ perfilObj.actualizacionFecha ? moment(perfilObj.actualizacionFecha).format("YYYY/MM/DD HH:mm") : "" }}</td>
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
          <div class="row-titulo-detalle mb-20">
            <h3>Lista de permisos</h3>
          </div>

          <div class="contenedor-lista-expandible">
            <template v-for="(seccion, key) in seccionesPermisos">
              <div class="row-padre-lista-expandible mb-8">
                <label class="checkbox-container">
                  <input type="checkbox" v-model="seccion.checkSeccion" @@change="seleccionarTodosPermisos(seccion)">
                  <span class="checkmark color-verde" :id="'check-' + key"></span>
                </label>
                <h4>@{{ key }}</h4>
                <i
                  class="icon-angulo-arriba"
                  @@click="seccion.checkExpandir = !seccion.checkExpandir"
                  v-if="seccion.checkExpandir"
                  :id="'expandir-' + key"></i>
                <i
                  class="icon-angulo-abajo"
                  @@click="seccion.checkExpandir = !seccion.checkExpandir"
                  v-else
                  :id="'expandir-'+ key"></i>
              </div>
              <transition name="lista-expandible" mode="out-in">
                <table class="tabla-detalle" v-if="seccion.checkExpandir">
                  <tbody>
                    <tr v-for="(permiso, key) in seccion.permisos">
                      <td class="w4p">
                        <label class="checkbox-container">
                          <input type="checkbox" v-model="permiso.checkPermiso" @@change="seleccionPermiso(seccion)">
                          <span class="checkmark color-verde" :id="'check-permiso-' + key"></span>
                        </label>
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

      <div class="contenedor-datos-detalle">
        <div class="detalle-datos">
          <div class="row-titulo-detalle mb-0 solo-boton-opcion">
            <!-- :disabled="!permisosVista.editarPermisos"   -->
            <button
              class="boton-aceptar"
              @@click="editarPermisos()"
              id="btnEditarPermisos">
              Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODALES -->
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

      // Lista expandible

      // URLs
      urlPerfilesEditarPermisosPerfil: "/usuarios/editar-permisos-perfil",
      urlPerfilGestor: "/usuarios/perfiles",

      // Variables vista
      usuarioLogueado: JSON.parse(document.getElementById('usuarioLogueado').textContent),
      permisosVista: JSON.parse(document.getElementById('permisosVista').textContent),
      mensajeAccion: JSON.parse(document.getElementById('mensajeAccion').textContent),
      hashId: JSON.parse(document.getElementById('hashId').textContent),
      perfilObj: JSON.parse(document.getElementById('perfilObj').textContent),
      seccionesPermisos: JSON.parse(document.getElementById('seccionesPermisos').textContent),

      // Data

      // Modales

      // SyncFusion
    },
    beforeDestroy() {},
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

      // Permisos
      seleccionarTodosPermisos(seccion) {
        seccion.permisos.forEach(permiso => {
          permiso.checkPermiso = seccion.checkSeccion
        });
      },
      seleccionPermiso(seccion) {
        if (seccion.checkSeccion) {
          seccion.checkSeccion = false
        }
      },
      async editarPermisos() {
        if (this.loader) return;

        let data = {
          "perfilId": this.perfilObj.perfilId,
          "permisosSeccion": this.seccionesPermisos,
        }

        this.loader = true;
        await axios.post(this.urlPerfilesEditarPermisosPerfil, data)
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

      // Redirección
      irDetallePerfil() {
        window.location.href = `${this.urlPerfilGestor}/${this.perfilObj.perfilId}/${this.hashId}`
      },
    }
  })
</script>
@endsection