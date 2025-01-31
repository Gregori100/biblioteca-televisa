Vue.component("usuario-activo", {
  props: {
    usuarioLogueado: {
      type: Object,
      default: {}
    },
    csrfToken: {
      type: String,
      default: ""
    },
  },
  data() {
    return {
      showDropdownUsuario: false,
      dropdownStyle: {},
      urlLogout: "/logout",
    };
  },
  mounted() {
    window.addEventListener('click', this.handleClickOutside);
  },
  beforeDestroy() {
    window.removeEventListener('click', this.handleClickOutside);
  },
  methods: {
    handleClickOutside(event) {
      if (this.showDropdownUsuario && !this.$refs.rowUsuario.contains(event.target)) {
        this.toggleDropdownUsuario();
      }
    },
    toggleDropdownUsuario() {
      this.showDropdownUsuario = !this.showDropdownUsuario;
      if (this.showDropdownUsuario) {
        this.setPositionUsuario();
      }
    },
    setPositionUsuario() {
      this.dropdownStyle = {
        top: `${28}px`,
        right: `${0}px`,
        position: 'absolute'
      };
    },
    async cerrarSesion() {
      this.loader = true;
      await axios.post(this.urlLogout)
        .then((response) => {
          let data = response.data;

          if (data.codigo != 200) {
            throw data.mensaje
          }

          // window.location.href = `${this.urlUsuariosGestor}/${this.usuarioObj.usuarioId}/${this.hashId}`
          this.loader = false;
        })
        .catch((error) => {
          this.mostrarAlerta("alerta-error", error);
          this.loader = false;
        });
    },
  },
  computed: {
  },
  template: `
    <transition name="usuario-activo">
      <div class="row-usuario-activo puntero-cursor" ref="rowUsuario">
        <h4 class="letra-semi-bold" @click="toggleDropdownUsuario()">{{ usuarioLogueado.usuario }}</h4>
        <button id="dropdownBtn" class="boton-dropdown" ref="dropdownBtn" id="btnOpcionesUsuario" @click="toggleDropdownUsuario()">
          <i :class="showDropdownUsuario ? 'icon-angulo-arriba' : 'icon-angulo-abajo'"></i>
        </button >
        <div
          v-if="showDropdownUsuario"
          class="dropdown-menu"
          :style="dropdownStyle"
          id="dropdownUsuario">
          <ul>
            <form id="logout-form" action="/logout" method="POST" style="display: none;" ref="formLogout">
              <input type="hidden" name="_token" :value="csrfToken">
            </form>
            <li id="opcEditar" @click="$refs.formLogout.submit()" id="btnCerrarSesion"><i class="icon-salir"></i>Cerrar sesión</li>
          </ul >
        </div >
      </div>
    </transition>
  `
});