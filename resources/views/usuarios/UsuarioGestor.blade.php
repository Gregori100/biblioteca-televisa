@extends("layout.app")

@section('title', 'Usuarios')

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
    <h2>Usuarios</h2>
    <div class="opciones">
      <usuario-activo :usuario-logueado="usuarioLogueado" :csrf-token="csrfToken" />
    </div>
  </div>

  <div class="contenedor-contenido">
    <!-- Filtros -->
    <form
      class="contenedor-filtros"
      action="{{ route('usuarios.gestor') }}"
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
            <span class="icono-input-derecha puntero-cursor" @@click="$refs.formFiltros.submit()" id="btnBuscarGestor">
              <i class="icon-buscar"></i>
            </span>
          </div>
          <button
            type="button"
            class="boton-expandir-filtro"
            @@click="window.location.href='{{ route('usuarios.gestor') }}'"
            id="btnLimpiarFiltros">
            <i class="icon-filtro"></i>
            Limpiar
          </button>
        </div>
        <div class="row-filtros-busqueda__opciones">
          <button
            type="button"
            class="boton-agregar-filtro"
            @@click="abrirModalAgregarUsuario()"
            id="btnNuevoRegistro"
            :disabled="!permisosVista.agregar">
            <i class="icon-agregar"></i>
            Nuevo usuario
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
            <th class="w10p">Usuario</th>
            <th class="w30p">Nombre completo</th>
            <th class="w20p">Perfil de acceso</th>
            <th class="w10p">Status</th>
            <th class="w15p">Fecha registro</th>
            <th class="w10p">Autor</th>
            <th class="w5p texto-centrado">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="usuarios.length > 0">
            <tr v-for="usuario in usuarios">
              <td>
                <a :href="'/usuarios/' + usuario.usuario_id + '/' + usuario.hash_id">
                  @{{ usuario.usuario }}
                </a>
              </td>
              <td>@{{ usuario.nombre_completo }}</td>
              <td>@{{ usuario.perfil_titulo }}</td>
              <td>
                <div class="status-global">
                  <div :class="'status-bullet '+ obtenerClaseStatus(usuario.status)"></div>
                  @{{ capitalizarTexto(usuario.status_nombre) }}
                </div>
              </td>
              <td>@{{ usuario.registro_fecha ? moment(usuario.registro_fecha).format("DD/MM/YYYY") : '' }}</td>
              <td>@{{ usuario.registro_autor }}</td>
              <td>
                <div class="celda-acciones-gestor center" v-if="usuario.status == 200">
                  <button
                    v-if="permisosVista.editar"
                    @@click="abrirModalEditarUsuario(usuario)"
                    class="boton-en-texto"
                    :id="'id-editar-' + usuario.usuario_id"
                    title="Editar usuario"
                    :disabled="usuario.status != 200">
                    <i class="icon-editar"></i>
                  </button>
                  <button
                    v-if="permisosVista.eliminar"
                    @@click="abrirModalEliminarUsuario(usuario)"
                    class="boton-en-texto"
                    :id="'id-eliminar-' + usuario.usuario_id"
                    title="Eliminar usuario"
                    :disabled="usuario.status != 200">
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
                  No se encuentra ningún usuario
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODALES -->
  <!-- COMIENZA MODAL AGREGAR USUARIO -->
  <template>
    <div v-if="modalAgregarUsuario" class="modal" id="modalAgregarUsuario">
      <div class="modal-card" id="modalCardAgregarUsuario">
        <div class="modal-header">
          <label>Nuevo usuario</label>
          <i @@click="cerrarModalAgregarUsuario" class="icon-cerrar" id="btnCerrarModalAgregarUsuario"></i>
        </div>
        <div class="modal-body">
          <form id="formAgregarUsuario" ref="formAgregarUsuario" @@submit.prevent="agregarUsuario()">
            @csrf
            <div class="input-row">
              <label class="requerido">Usuario</label>
              <input
                type="text"
                placeholder="Usuario"
                required
                maxlength="20"
                autocomplete="new-password"
                v-model.trim="usuarioObj.usuario"
                ref="usuarioInput"
                id="inputUsuario">
            </div>

            <div class="input-row">
              <label class="requerido">Nombre completo</label>
              <input
                type="text"
                placeholder="Nombre completo"
                required
                maxlength="100"
                autocomplete="new-password"
                v-model.trim="usuarioObj.nombreCompleto"
                id="inputNombreCompleto">
            </div>

            <div class="input-row">
              <label class="requerido">Email</label>
              <input
                type="email"
                placeholder="Email"
                maxlength="200"
                required
                autocomplete="new-password"
                v-model.trim="usuarioObj.email"
                id="inputEmail">
            </div>

            <div class="input-row">
              <label class="requerido">Perfil</label>
              <select v-model="usuarioObj.perfilId" required>
                <option :value="null" disabled>Selecciona una opción</option>
                <option :value="perfil.perfil_id" v-for="perfil in perfilesUsuarios">@{{ perfil.titulo }}</option>
              </select>
            </div>

            <div class="input-password">
              <div>
                <label class="requerido">Contraseña</label>
                <label class="generar-password" @@click="generarPassword" id="btnGenerarPasswordAgregar">
                  Generar y copiar
                </label>
              </div>
              <div class="input-con-icono derecha">
                <input
                  :type="verPassword ? 'text' : 'password'"
                  name="password"
                  placeholder="Contraseña"
                  required
                  maxlength="25"
                  @@input="validarPassword"
                  v-model.trim="usuarioObj.password"
                  onkeypress="noSpaces(event)"
                  id="inputPasswordAgregar"
                  autocomplete="new-password">
                <i
                  class="icon-visualizacion-cerrada copiar puntero-cursor"
                  @@click="verPassword = !verPassword"
                  v-if="!verPassword"
                  id="btnVerPassword"></i>
                <i
                  class="icon-visualizacion-abierta copiar puntero-cursor"
                  @@click="verPassword = !verPassword"
                  v-else id="btnOcultarPassword"></i>
              </div>
              <div class="validar-password">
                <div class="progreso">
                  <progress
                    :value="seguridad"
                    :class="colorSeguridad"
                    max="100"
                    id="progressSeguridadPasswordAgregar"></progress>
                  <label> Seguridad</label>
                </div>
                <div class="validaciones">
                  <div v-for="(rule, index) in reglas" :key="index">
                    <i
                      :class="{ 'icon-confirmar-filled verde': rule.valid, 'icon-cancelar-filled rojo': !rule.valid }"
                      :id="index"></i>
                    <label>@{{ rule.message }}</label>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer center">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalAgregarUsuario()"
            id="btnCancelarAgregar">
            Cancelar</button>
          <button
            type="submit"
            class="boton-aceptar"
            form="formAgregarUsuario"
            id="btnGuardarAgregar"
            :disabled="!(reglas.every(rule => rule.valid) && seguridad >= 50)">
            Guardar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL AGREGAR USUARIO -->

  <!-- COMIENZA MODAL EDITAR USUARIO -->
  <template>
    <div v-if="modalEditarUsuario" class="modal" id="modalEditarUsuario">
      <div class="modal-card" id="modalCardEditarUsuario">
        <div class="modal-header">
          <label>Editar usuario</label>
          <i @@click="cerrarModalEditarUsuario" class="icon-cerrar"></i>
        </div>
        <div class="modal-body">
          <form id="formEditarUsuario" ref="formEditarUsuario" @@submit.prevent="editarUsuario()">
            @csrf
            <div class="input-row">
              <label class="requerido">Usuario</label>
              <input
                type="text"
                placeholder="Usuario"
                required
                maxlength="20"
                autocomplete="new-password"
                v-model.trim="usuarioObj.usuario"
                ref="usuarioInput"
                id="inputUsuario">
            </div>

            <div class="input-row">
              <label class="requerido">Nombre completo</label>
              <input
                type="text"
                placeholder="Nombre completo"
                required
                maxlength="100"
                autocomplete="new-password"
                v-model.trim="usuarioObj.nombreCompleto"
                id="inputNombreCompleto">
            </div>

            <div class="input-row">
              <label class="requerido">Email</label>
              <input
                type="email"
                placeholder="Email"
                maxlength="200"
                required
                autocomplete="new-password"
                v-model.trim="usuarioObj.email"
                id="inputEmail">
            </div>

            <div class="input-row">
              <label class="requerido">Perfil</label>
              <select v-model="usuarioObj.perfilId" required>
                <option :value="null" disabled>Selecciona una opción</option>
                <option :value="perfil.perfil_id" v-for="perfil in perfilesUsuarios">@{{ perfil.titulo }}</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer center">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalEditarUsuario()"
            id="btnCancelarAgregar">
            Cancelar</button>
          <button
            type="submit"
            class="boton-aceptar"
            form="formEditarUsuario"
            id="btnGuardarAgregar">
            Guardar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL EDITAR USUARIO -->

  <!-- COMIENZA MODAL ELIMINAR USUARIO -->
  <template>
    <div v-if="modalEliminarUsuario" class="modal modal-eliminar">
      <div class="modal-card">
        <div class="modal-header solo-btn-cerrar">
          <i @@click="cerrarModalEliminarUsuario" class="icon-cerrar"></i>
        </div>
        <div class="modal-body">
          <img class="ilustracion-modal ilustracion-eliminar" src="{{ asset('imagenes/biblioteca-ilustracion-eliminar.svg') }}" alt="">

          <p class="titulo-modal">Eliminar registro</p>
          <p>¿Estás seguro que deseas eliminar el siguiente usuario?</p>
          <p class="mb-8">Esta acción no se puede deshacer.</p>

          <p class="subtitulo-modal">@{{ usuarioObj.nombreCompleto }}</p>
        </div>
        <div class="modal-footer center no-border">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalEliminarUsuario()"
            id="btnCancelar">
            Cancelar</button>
          <button
            type="button"
            class="boton-aceptar boton--eliminar"
            @@click="eliminarUsuario"
            id="btnEliminar">
            Eliminar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL EDITAR USUARIO -->
</div>

<!-- VARIABLES BASE -->
<script id="usuarioLogueado" type="application/json">
  @json($usuarioLogueado)
</script>
<script id="permisosVista" type="application/json">
  @json($permisosVista)
</script>
<script id="mensajeAccion" type="application/json">
  @json($mensajeAccion)
</script>

<!-- VARIABLES -->
<script id="usuarios" type="application/json">
  @json($usuarios)
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
      urlListarPerfilesUsuarios: "/usuarios/listar-perfiles",
      urlAgregarUsuario: "/usuarios/agregar",
      urlEditarUsuario: "/usuarios/editar",
      urlEliminarUsuario: "/usuarios/eliminar",
      urlUsuariosGestor: "/usuarios",

      usuarioLogueado: JSON.parse(document.getElementById('usuarioLogueado').textContent),
      permisosVista: JSON.parse(document.getElementById('permisosVista').textContent),
      mensajeAccion: JSON.parse(document.getElementById('mensajeAccion').textContent),
      usuarios: JSON.parse(document.getElementById('usuarios').textContent),

      filtros: {
        busqueda: ""
      },

      // Data
      perfilesUsuarios: [],
      usuarioObj: {
        usuarioId: null,
        usuario: null,
        nombreCompleto: null,
        email: null,
        password: null,
        perfilId: null,
      },

      // Modales
      modalAgregarUsuario: false,
      modalEditarUsuario: false,
      modalEliminarUsuario: false,

      // Password
      password: '',
      passwordStrength: {
        score: 0
      },
      reglas: [{
          valid: false,
          message: '8 Caracteres como mínimo'
        },
        {
          valid: false,
          message: 'Al menos una letra mayúscula'
        },
        {
          valid: false,
          message: 'Al menos una letra minúscula'
        },
        {
          valid: false,
          message: 'Al menos un número'
        },
        {
          valid: false,
          message: 'Al menos un caracter especial'
        }
      ],
      verPassword: '',
      seguridad: 0,
      colorSeguridad: '',

      // Mensaje accion gestor
      mensajeAccionGestor: "",
      mostrarMensajeAccionGestor: false,
    },
    async mounted() {
      this.cargaInicial();
      this.cargaGestor();
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
      cargaGestor() {
        this.$refs.inputFiltros.focus();
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

      // Listado
      async listarPerfilesUsuarios() {
        let data = {
          params: {
            filtros: {
              status: [200]
            }
          }
        }

        await axios.get(this.urlListarPerfilesUsuarios, data)
          .then((response) => {
            this.perfilesUsuarios = response.data.data
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error)
          });
      },

      limpiarUsuariObj() {
        this.usuarioObj = {
          usuarioId: null,
          usuario: null,
          nombreCompleto: null,
          email: null,
          password: null,
          perfilId: null,
        };

        this.perfilesUsuarios = [];
      },

      // Agregar usuario
      async abrirModalAgregarUsuario() {
        await this.reiniciarValidacionesPassword();
        await this.listarPerfilesUsuarios()
        this.modalAgregarUsuario = await true;

        this.$nextTick(() => {
          this.$refs.usuarioInput.focus();
        });
      },
      cerrarModalAgregarUsuario() {
        this.modalAgregarUsuario = false;

        this.limpiarUsuariObj();
      },
      async agregarUsuario() {
        if (this.loader) return;

        if (!validarEmail(this.usuarioObj.email)) {
          this.mostrarAlerta("alerta-error", "Formato de email incorrecto, favor de corregirlo");
          return;
        }

        this.loader = true;
        await axios.post(this.urlAgregarUsuario, this.usuarioObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            let id = data.data.usuarioId;
            let hashId = data.data.hashId;

            window.location.href = `${this.urlUsuariosGestor}/${id}/${hashId}`
            this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Editar usuario
      async abrirModalEditarUsuario(usuario) {
        this.usuarioObj = {
          usuarioEditadoId: usuario.usuario_id,
          usuario: usuario.usuario,
          nombreCompleto: usuario.nombre_completo,
          email: usuario.email,
          perfilId: usuario.perfil_id,
        }

        await this.listarPerfilesUsuarios()
        this.modalEditarUsuario = await true;

        this.$nextTick(() => {
          this.$refs.usuarioInput.focus();
        });
      },
      cerrarModalEditarUsuario() {
        this.modalEditarUsuario = false;

        this.limpiarUsuariObj();
      },
      async editarUsuario() {
        if (this.usuarioObj.perfilId == "" || this.usuarioObj.perfilId == null) {
          this.mostrarAlerta("alerta-error", "Selecciona un perfil para el usuario");
          return;
        }

        if (this.loader) return;

        if (!validarEmail(this.usuarioObj.email)) {
          this.mostrarAlerta("alerta-error", "Formato de email incorrecto, favor de corregirlo");
          return;
        }

        this.loader = true;
        await axios.post(this.urlEditarUsuario, this.usuarioObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            // Mensaje acción
            this.mostrarMensajeAccionGestor = true;
            this.mensajeAccionGestor = "editar";
            this.$nextTick(() => {
              this.$refs.inputMensajeAccion.name = "exito";
              this.$refs.formFiltros.submit()
            });

            // this.$refs.formFiltros.submit()
            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Eliminar usuario
      async abrirModalEliminarUsuario(usuario) {
        this.usuarioObj = {
          usuarioEditadoId: usuario.usuario_id,
          nombreCompleto: usuario.nombre_completo,
          usuario: usuario.usuario,
        }
        this.modalEliminarUsuario = await true;
      },
      cerrarModalEliminarUsuario() {
        this.modalEliminarUsuario = false;
        this.limpiarUsuariObj();
      },
      async eliminarUsuario() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlEliminarUsuario, this.usuarioObj)
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

            // this.$refs.formFiltros.submit()
            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Métodos del password
      validarPassword() {
        // Usar zxcvbn para evaluar la fortaleza de la contraseña
        this.passwordStrength = zxcvbn(this.usuarioObj.password);

        // Actualizar las reglas de validación
        this.reglas[0].valid = this.usuarioObj.password.length >= 8;
        this.reglas[1].valid = /[A-Z]/.test(this.usuarioObj.password);
        this.reglas[2].valid = /[a-z]/.test(this.usuarioObj.password);
        this.reglas[3].valid = /\d/.test(this.usuarioObj.password);
        this.reglas[4].valid = /[!@#$%^&*(),.?":{}|<>/]/.test(this.usuarioObj.password);

        if (this.passwordStrength.score == 0 || this.passwordStrength.score == 1) {
          this.seguridad = 33;
          this.colorSeguridad = 'rojo';
        } else if (this.passwordStrength.score == 2 || this.passwordStrength.score == 3) {
          this.seguridad = 66;
          this.colorSeguridad = 'amarillo';
        } else if (this.passwordStrength.score == 4) {
          this.seguridad = 100;
          this.colorSeguridad = 'verde';
        }
        if (this.usuarioObj.password == '') {
          this.seguridad = 0;
        }
      },
      generarPassword() {
        this.usuarioObj.password = generarPasswordSegura(12);
        this.validarPassword();
        navigator.clipboard.writeText(this.usuarioObj.password);
      },
      reiniciarValidacionesPassword() {
        this.usuarioObj.password = '';
        this.passwordStrength = {
          score: 0
        };
        this.verPassword = false;
        this.seguridad = 0;
        this.colorSeguridad = '';
        this.reglas = [{
            valid: false,
            message: '8 Caracteres como mínimo'
          },
          {
            valid: false,
            message: 'Al menos una letra mayúscula'
          },
          {
            valid: false,
            message: 'Al menos una letra minúscula'
          },
          {
            valid: false,
            message: 'Al menos un número'
          },
          {
            valid: false,
            message: 'Al menos un caracter especial'
          }
        ];
      }
    }
  })
</script>
@endsection