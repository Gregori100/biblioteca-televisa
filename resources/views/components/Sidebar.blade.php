<div class="sidebar">
  <div class="logo-2">
  </div>
  <div class="menus">
    <a
      class="{{ request()->routeIs('libros') ? 'boton active' : 'boton' }}"
      href="{{ route('libros.gestor') }}"
      id="linkHomeSidebar"
      title="Inicio">
      <i class="icon-inicio" id="iconHomeSidebar"></i>
      <p>Inicio</p>
    </a>

    <a
      class="
        {{ request()->routeIs('usuarios.*')
        && !request()->routeIs('usuarios.perfiles*')
        && !request()->routeIs('usuarios.editarPermisos*')
        && !request()->routeIs('usuarios.detallePerfil*') ? 'boton active' : 'boton' }}"
      href="{{ route('usuarios.gestor') }}"
      id="linkUsuarios"
      title="Usuarios">
      <i class="icon-usuario" id="iconHomeSidebar"></i>
      <p>Usuarios</p>
    </a>

    <a
      class="{{ request()->routeIs(
      'usuarios.perfilesGestor',
      'usuarios.detallePerfil',
      'usuarios.editarPermisos'
      ) ? 'boton active' : 'boton' }}"
      href="{{ route('usuarios.perfilesGestor') }}"
      id="linkPerfiles"
      title="Perfiles">
      <i class="icon-usuarios" id="iconHomeSidebar"></i>
      <p>Perfiles</p>
    </a>
  </div>
</div>