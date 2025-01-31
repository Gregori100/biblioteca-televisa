@extends("layout.appNoNavbar")

@section('title', 'Login')

@section('content')
<div id='app' class="vista-web">
  <!-- Alerta -->
  <alerta
    v-if="showAlerta"
    :alerta-mensaje="alertaMensaje"
    :alerta-class="alertaClass"
    :reset-tiempo="resetTiempoAlerta"
    @on-alerta-close="ocultarAlerta()"></alerta>
  <!-- Fin alerta -->

  <div class="login">
    <div class="login__card">
      <div class="credenciales-card">
        <!-- <img src="{{ asset('imagenes/artegrafico-logotipo-oro.svg') }}" alt="" class="mb-32"> -->

        <h1 class="mb-40 texto-color-blanco letra-bold">Login</h1>

        <form action="{{ route('auth.login') }}" method="POST" class="gap-16 mb-32" id="form-login">
          @csrf
          <div class="input-row">
            <input
              type="text"
              id="usuario"
              name="usuario"
              placeholder="Usuario"
              required
              maxlength="20"
              value="{{ old('usuario') }}"
              id="inputUsuario">
          </div>
          <div class="input-row">
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Password"
              required
              maxlength="128"
              value="{{ old('password') }}"
              id="inputContrasena">
          </div>
        </form>

        <button class="boton-aceptar" type="submit" form="form-login" id="btnIniciarSesion">
          Iniciar sesión
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  var app = new Vue({
    el: '#app',
    data: {
      showAlerta: false,
      alertaMensaje: null,
      alertaClass: false,
      resetTiempoAlerta: false,
      appURL: "{{ env('APP_URL') }}",
    },
    mounted() {
      if ("{{ Session::get('error') }}" != "") {
        this.mostrarAlerta("alerta-error", "{{ Session::get('error') }}");
      }
    },
    computed: {},
    methods: {
      mostrarAlerta(tipoAlerta, mensajeAlerta) {
        this.alertaMensaje = mensajeAlerta
        this.alertaClass = tipoAlerta;
        this.showAlerta = true;
        this.resetTiempoAlerta = !this.resetTiempoAlerta;
      },
      ocultarAlerta() {
        this.showAlerta = false;
      },
    }
  })
</script>
@endsection
