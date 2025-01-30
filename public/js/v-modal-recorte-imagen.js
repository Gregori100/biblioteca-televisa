Vue.component("modal-recorte-imagen", {
  props: {
    tituloModal: {
      type: String,
      default: 'Subir imagen'
    },
    validacionDimenciones: {
      type: Boolean,
      default: false
    },
    anchoMinimo: {
      type: Number,
      default: 550
    },
    altoMinimo: {
      type: Number,
      default: 350
    },
  },
  data() {
    return {
      archivoCargado: false,
      base64Image: null,
      nombreOriginal: null,
      tamanioKB: null,
      imageSrc: null,
    };
  },
  beforeDestroy() {
    this.limpiarModal();
  },
  methods: {
    async cerrarModal() {
      await this.limpiarModal();
      await this.$emit('on-modal-close');
    },
    async recortarImagen() {
      if (this.cropper) {
        // Obtener el recorte como un canvas
        const croppedCanvas = this.cropper.getCroppedCanvas({
          width: 1080, // Aumenta la resolución del recorte
          height: 1080,
        });

        if (croppedCanvas) {
          // Convertir el canvas a Base64
          this.base64Image = croppedCanvas.toDataURL('image/png');

          this.$emit(
            'recorte-imagen',
            this.base64Image,
            this.nombreOriginal,
            this.tamanioKB
          );
        }
      }
    },
    dragOver(event) {
      event.preventDefault();
      event.target.classList.add('drag-over');
    },
    dragLeave(event) {
      event.target.classList.remove('drag-over');
    },
    drop(event) {
      event.preventDefault();
      event.target.classList.remove('drag-over');
      const files = event.dataTransfer.files;
      this.onFileChange(files);
    },
    handleFileSelect(event) {
      const files = event.target.files;
      this.onFileChange(files);
    },
    onFileChange(files) {
      const file = files[0];

      this.archivoCargado = true;

      this.$nextTick(() => {
        if (file) {
          this.tamanioKB = (file.size / 1024).toFixed(2);

          const reader = new FileReader();
          reader.onload = (e) => {
            this.imageSrc = e.target.result;

            const img = new Image();
            img.src = e.target.result;

            img.onload = () => {
              this.$nextTick(() => {
                const width = img.width;
                const height = img.height;

                // Validación de dimensiones
                if (this.validacionDimenciones && (width < anchoMinimo || height < altoMinimo)) {
                  this.mostrarAlerta("alerta-error", `La imagen debe tener dimensiones de más de 550x350 píxeles. Actualmente tiene ${width}x${height}`);
                  this.archivoCargado = false;
                  return;
                }

                const imageElement = this.$refs.image;
                this.cropper = new Cropper(imageElement, {
                  aspectRatio: NaN,
                  viewMode: 1,
                  movable: true,
                  zoomable: true,
                  scalable: true,
                  rotatable: true,
                  responsive: true,

                  autoCropArea: 1,
                  cropBoxResizable: true,
                  cropBoxMovable: true,
                });
              });
            };
          };
          reader.readAsDataURL(file);
          this.nombreOriginal = file.name;
        }
      });
    },
    async limpiarModal() {
      if (this.cropper) {
        this.cropper.destroy();
        this.cropper = null;
      }

      this.archivoCargado = false;
      this.base64Image = null;
      this.tamanioKB = null;
      this.nombreOriginal = null;
      this.imageSrc = null;
    },
  },
  template:
    `<div class="modal" v-on:after-enter="$emit('on-modal-show')" v-on:leave="$emit('on-modal-close')">
      <div class="modal-card w600">
        <div class="modal-header">
          <label>{{ tituloModal }}</label>
          <i @click="cerrarModal()" class="icon-cerrar" id="btnCerrarModalRecorte"></i>
        </div>
        <div class="modal-body">
          <div class="contenedor-subida-img">
            <div v-if="archivoCargado">
              <div class="crop-container">
                <img ref="image" :src="imageSrc" style="max-width: 100%; display: block;">
              </div>
            </div>
            <div
              v-else
              class="recuadro-subida-img"
              @dragover.prevent="dragOver"
              @drop.prevent="drop"
              @dragleave="dragLeave">
              <i class="icon-subir mb-24"></i>
              <p>Arrastra la imagen</p>
              <p class="mb-8">o</p>
              <button
                id="btnClickAquiSubidaArchivo"
                class="boton-outline boton-subida-imagen"
                @click="$refs.fileInput.click()"
                id="btnClickAqui">Da click aquí</button>
              <input
                style="display: none;"
                type="file"
                id="fileInput"
                accept="image/jpeg, image/png"
                @change="handleFileSelect"
                ref="fileInput">
            </div>
          </div>
        </div>
        <div class="modal-footer center">
          <button
            class="boton-aceptar boton--plata"
            @click="cerrarModal()"
            id="btnCancelarAgregarImagen">
            Cancelar
          </button>
          <button
            type="button"
            class="boton-aceptar"
            @click="recortarImagen()"
            id="btnAgregarImagen"
            :disabled="!archivoCargado">
            Guardar
          </button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>`,
});
