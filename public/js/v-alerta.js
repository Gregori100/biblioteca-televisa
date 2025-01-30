Vue.component("alerta", {
  props: ["alerta-class", "alerta-image", "alerta-mensaje"],
  props: {
    alertaClass: {
      type: String,
      default: 'alerta-exito'
    },
    alertaMensaje: {
      type: String,
      default: 'Mensaje de alerta'
    },
    resetTiempo: Boolean,
  },
  data() {
    return {
      appURL: window.Laravel.appUrl,
      timeoutId: null
    };
  },
  beforeDestroy() {
    if (this.timeoutId) {
      clearTimeout(this.timeoutId);
    }
  },
  mounted() {
    this.startTimeout();
  },
  watch: {
    resetTiempo() {
      this.startTimeout();
    },
  },
  computed: {
    alertaImage() {
      return `${this.appURL}/imagenes/dataxtractor-iconos-notificacion-${this.alertaClass}.svg`;
    }
  },
  methods: {
    ocultarAlerta() {
      this.$emit('on-alerta-close');
    },
    startTimeout() {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.$emit('on-alerta-close');
      }, 5000);
    }
  },
  template: `
    <transition name="alerta">
      <div class="alerta-contenedor" @click="ocultarAlerta">
        <div :class="alertaClass + ' alerta'">
          <img class="imagen-link" :src="alertaImage">
          <p>{{ alertaMensaje }}</p>
          <i @click="ocultarAlerta" class="icon-ol-cerrar"></i>
        </div>
      </div>
    </transition>
  `
});