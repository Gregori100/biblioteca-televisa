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
    <h2>Detalle de usuario</h2>
    <div class="opciones">
      <usuario-activo :usuario-logueado="usuarioLogueado" :csrf-token="csrfToken" />
    </div>
  </div>

  <div class="contenedor-contenido">
    <div class="contenedor-detalle ancho-maximo-1156">
      <div class="contenedor-datos-detalle">
        <div class="detalle-datos">
          <div class="row-titulo-detalle">
            <h3>@{{ usuarioObj.nombreCompleto }}</h3>
            <button
              class="boton-opciones-puntos"
              :class="showDropdown ? 'boton-opciones-puntos--activo' : ''"
              @@click="toggleDropdown()"
              ref="iconoPuntos"
              :disabled="usuarioObj.status == 300"
              id="btnOpciones">
              <i class="icon-puntos"></i>
            </button>
            <div v-if="showDropdown" class="dropdown-menu" :style="dropdownStyle" id="dropdownOpciones" ref="dropdownOpciones">
              <ul>
                <li>
                  <button
                    class="boton-en-texto"
                    @@click="abrirModalEditarUsuario()"
                    id="opcEditar"
                    :disabled="!permisosVista.editar">
                    <i class="icon-editar"></i>Editar
                  </button>
                </li>
                <li>
                  <button
                    class="boton-en-texto"
                    @@click="abrirModalEliminarUsuario()"
                    id="opcEliminar"
                    :disabled="!permisosVista.eliminar">
                    <i class="icon-eliminar"></i>Eliminar
                  </button>
                </li>
              </ul>
            </div>
          </div>

          <table class="tabla-detalle">
            <tbody>
              <tr>
                <td class="w15p">Usuario</td>
                <td class="w35p">@{{ usuarioObj.usuario }}</td>
                <td class="w15p">Actualización</td>
                <td class="w35p">@{{ usuarioObj.actualizacionFecha ? moment(usuarioObj.actualizacionFecha).format("DD/MM/YYYY") : '' }}</td>
              </tr>
              <tr>
                <td>Nombre completo</td>
                <td>@{{ usuarioObj.nombreCompleto }}</td>
                <td>Autor</td>
                <td>@{{ usuarioObj.actualizacionAutor ?? "" }}</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>@{{ usuarioObj.email }}</td>
                <td>Último acceso</td>
                <td>@{{ usuarioObj.fechaUltimoAcceso ? moment(usuarioObj.fechaUltimoAcceso).format("DD/MM/YYYY") : '' }}</td>
              </tr>
              <tr>
                <td>Perfil de acceso</td>
                <td>@{{ usuarioObj.perfilTitulo }}</td>
                <td>Status</td>
                <td>
                  <div class="status-global">
                    <div class="status-bullet" :class="obtenerClaseStatus(usuarioObj.status)"></div>
                    @{{ usuarioObj.statusNombre }}
                  </div>
                </td>
              </tr>
              <tr>
                <td>Fecha registro</td>
                <td>@{{ usuarioObj.registroFecha }}</td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>

          <div class="row-titulo-detalle">
            <button
              class="boton-outline"
              id="btnCambiarContrasena"
              @@click="abrirModalEditarPassword()"
              :disabled="!permisosVista.editarPassword || usuarioObj.status == 300">
              <i class="icon-contrasena"></i>
              Cambiar contraseña
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODALES -->
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
                v-model.trim="usuarioEditarObj.usuario"
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
                v-model.trim="usuarioEditarObj.nombreCompleto"
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
                v-model.trim="usuarioEditarObj.email"
                id="inputEmail">
            </div>

            <div class="input-row">
              <label class="requerido">Perfil</label>
              <select v-model="usuarioEditarObj.perfilId" required>
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

  <!-- COMIENZA MODAL CAMBIAR CONTRASEÑA -->
  <template>
    <div v-if="modalEditarPassword" class="modal">
      <div class="modal-card">
        <div class="modal-header">
          <label>Cambiar contraseña</label>
          <i @@click="cerrarModalEditarPassword" class="icon-cerrar"></i>
        </div>
        <div class="modal-body">
          <form id="formEditarPassword" ref="formEditarPassword" @@submit.prevent="editarPassword()">
            @csrf
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
                  v-model.trim="usuarioEditarObj.password"
                  onkeypress="noSpaces(event)"
                  id="inputPasswordAgregar"
                  autocomplete="new-password"
                  ref="passwordInput">
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
            @@click="cerrarModalEditarPassword()"
            id="btnCancelarAgregar">
            Cancelar</button>
          <button
            type="submit"
            class="boton-aceptar"
            form="formEditarPassword"
            id="btnGuardar"
            :disabled="!(reglas.every(rule => rule.valid) && seguridad >= 50)">
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

          <p class="subtitulo-modal">@{{ usuarioEditarObj.nombreCompleto }}</p>
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
<script id="hashId" type="application/json">
  @json($hashId)
</script>

<!-- VARIABLES -->
<script id="usuarioObj" type="application/json">
  @json($usuarioObj)
</script>
<script id="sucursales" type="application/json">
  @json($sucursales)
</script>
<script id="sucursalesRelacionadas" type="application/json">
  @json($sucursalesRelacionadas)
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
      urlEditarUsuario: "/usuarios/editar",
      urlEditarPasswordUsuario: "/usuarios/editar-password",
      urlEliminarUsuario: "/usuarios/eliminar",
      urlUsuariosGestor: "/usuarios",
      urlListarPerfilesUsuarios: "/usuarios/listar-perfiles",
      urlEditarRelacionSucursales: "/usuarios/editar-relacion-sucursales",

      usuarioLogueado: JSON.parse(document.getElementById('usuarioLogueado').textContent),
      permisosVista: JSON.parse(document.getElementById('permisosVista').textContent),
      mensajeAccion: JSON.parse(document.getElementById('mensajeAccion').textContent),
      usuarioObj: JSON.parse(document.getElementById('usuarioObj').textContent),
      sucursales: JSON.parse(document.getElementById('sucursales').textContent),
      sucursalesRelacionadas: JSON.parse(document.getElementById('sucursalesRelacionadas').textContent),
      hashId: JSON.parse(document.getElementById('hashId').textContent),

      usuarioEditarObj: {
        usuarioId: null,
        usuario: null,
        nombreCompleto: null,
        email: null,
        password: null,
        perfilId: null,
      },

      // Data
      perfilesUsuarios: [],
      relacionarSucursales: false,

      // Modales
      modalEditarUsuario: false,
      modalEditarPassword: false,
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
    },
    beforeDestroy() {
      // Evento para cerrar opcion 3 puntos
      window.removeEventListener('click', this.handleClickOutside);
    },
    computed: {},
    async mounted() {
      this.cargaInicial()
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
        this.usuarioEditarObj = {
          usuarioId: null,
          usuario: null,
          nombreCompleto: null,
          email: null,
          password: null,
          perfilId: null,
        };

        this.perfilesUsuarios = [];
      },

      // Editar usuario
      async abrirModalEditarUsuario() {
        this.usuarioEditarObj = {
          usuarioEditadoId: this.usuarioObj.usuarioId,
          usuario: this.usuarioObj.usuario,
          nombreCompleto: this.usuarioObj.nombreCompleto,
          email: this.usuarioObj.email,
          perfilId: this.usuarioObj.perfilId,
        }

        await this.listarPerfilesUsuarios()
        this.modalEditarUsuario = await true;

        this.toggleDropdown();

        this.$nextTick(() => {
          this.$refs.usuarioInput.focus();
        });
      },
      cerrarModalEditarUsuario() {
        this.modalEditarUsuario = false;
        this.limpiarUsuariObj();
      },
      async editarUsuario() {
        if (this.usuarioEditarObj.perfilId == "" || this.usuarioEditarObj.perfilId == null) {
          this.mostrarAlerta("alerta-error", "Selecciona un perfil para el usuario");
          return;
        }

        if (this.loader) return;

        if (!validarEmail(this.usuarioEditarObj.email)) {
          this.mostrarAlerta("alerta-error", "Formato de email incorrecto, favor de corregirlo");
          return;
        }

        this.loader = true;
        await axios.post(this.urlEditarUsuario, this.usuarioEditarObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            window.location.href = `${this.urlUsuariosGestor}/${this.usuarioObj.usuarioId}/${this.hashId}?exito=editar`
            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Editar contraseña usuario
      async abrirModalEditarPassword() {
        this.usuarioEditarObj = {
          usuarioEditadoId: this.usuarioObj.usuarioId,
          password: null,
        }

        this.modalEditarPassword = true;

        this.$nextTick(() => {
          this.$refs.passwordInput.focus();
        });
      },
      cerrarModalEditarPassword() {
        this.modalEditarPassword = false;

        this.limpiarUsuariObj();
      },
      async editarPassword() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlEditarPasswordUsuario, this.usuarioEditarObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            window.location.href = `${this.urlUsuariosGestor}/${this.usuarioObj.usuarioId}/${this.hashId}?exito=editarContrena`
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Eliminar usuario
      async abrirModalEliminarUsuario() {
        this.usuarioEditarObj = {
          usuarioEditadoId: this.usuarioObj.usuarioId,
          nombreCompleto: this.usuarioObj.nombreCompleto,
          usuario: this.usuarioObj.usuario,
        }
        this.modalEliminarUsuario = await true;

        this.toggleDropdown();
      },
      cerrarModalEliminarUsuario() {
        this.modalEliminarUsuario = false;
        this.limpiarUsuariObj();
      },
      async eliminarUsuario() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlEliminarUsuario, this.usuarioEditarObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            window.location.href = `${this.urlUsuariosGestor}/${this.usuarioObj.usuarioId}/${this.hashId}?exito=eliminar`
            this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Relacción sucursales
      async editarRelacionSucursales() {
        if (this.loader) return;

        let form = new FormData();
        form.append('usuarioEditadoId', this.usuarioObj.usuarioId);
        form.append('sucursales', JSON.stringify(this.sucursales));

        this.loader = true;
        await axios.post(this.urlEditarRelacionSucursales, form)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            window.location.href = `${this.urlUsuariosGestor}/${this.usuarioObj.usuarioId}/${this.hashId}?exito=eliminarRelSucursal`

            this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      // Métodos del password
      validarPassword() {
        // Usar zxcvbn para evaluar la fortaleza de la contraseña
        this.passwordStrength = zxcvbn(this.usuarioEditarObj.password);

        // Actualizar las reglas de validación
        this.reglas[0].valid = this.usuarioEditarObj.password.length >= 8;
        this.reglas[1].valid = /[A-Z]/.test(this.usuarioEditarObj.password);
        this.reglas[2].valid = /[a-z]/.test(this.usuarioEditarObj.password);
        this.reglas[3].valid = /\d/.test(this.usuarioEditarObj.password);
        this.reglas[4].valid = /[!@#$%^&*(),.?":{}|<>/]/.test(this.usuarioEditarObj.password);

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
        if (this.usuarioEditarObj.password == '') {
          this.seguridad = 0;
        }
      },
      generarPassword() {
        this.usuarioEditarObj.password = generarPasswordSegura(12);
        this.validarPassword();
        navigator.clipboard.writeText(this.usuarioEditarObj.password);
      },
      reiniciarValidacionesPassword() {
        this.usuarioEditarObj.password = '';
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
      },
    }
  })
</script>
@endsection