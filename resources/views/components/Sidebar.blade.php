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
  </div>
</div>