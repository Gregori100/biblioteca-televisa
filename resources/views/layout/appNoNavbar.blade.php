<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>

  {{-- AXIOS --}}
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  {{-- VUE --}}
  <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>

  {{-- INPUTMASK --}}
  <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>

  {{-- VERIFICADOR DE CONTRASEÑAS --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>

  {{-- MOMENT --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

  {{-- METODOS --}}
  <script src="{{ asset('js/global.js') }}"></script>

  {{-- COMPONENTES --}}
  <script src="{{ asset('js/v-alerta.js') }}"></script>
  <script src="{{ asset('js/v-usuario-activo.js') }}"></script>

  <script>
    window.Laravel = {
      appUrl: "{{ env('APP_URL') }}"
    };
  </script>

  {{-- ESTILOS --}}
  <link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
  <link rel="stylesheet" href="{{ asset('ico/outline.css') }}" />

  {{-- ICON --}}
</head>

<body>
  <main>
    @yield('content')
  </main>
</body>

</html>
