Vue.component("modal-carrousel", {
  props: ["modal-class", "footer-class"],
  methods: {
    habilitarTeclado: function (e) {
      if (e.which === 27) this.$emit("on-modal-close");

      if (e.which === 37) this.$emit("on-modal-flecha-izq");

      if (e.which === 39) this.$emit("on-modal-flecha-der");
    },
  },
  created: function () {
    window.addEventListener("keyup", this.habilitarTeclado);
  },
  template:
    `<div class="modal" v-on:after-enter="$emit('on-modal-show')" v-on:leave="$emit('on-modal-close')">
      <div class="modal-carrousel" v-on:click="$emit('on-modal-close')">
        <div class="modal-imagen">
          <slot name="body"></slot>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>`,
});
