@extends("layout.app")

@section('title', 'Libros gestor')

@section('content')
<div id='app' class="vista-web">
  <!-- LOADER -->
  <div v-if="loader" class="loader-overlay">
    <div class="loader"></div>
  </div>
  <!-- FIN LOADER -->

  <!-- ALERTA -->
  <alerta
    v-if="showAlerta"
    :alerta-mensaje="alertaMensaje"
    :alerta-class="alertaClass"
    :reset-tiempo="resetTiempoAlerta"
    @on-alerta-close="ocultarAlerta()"></alerta>
  <!-- FIN ALERTA -->

  <div class="encabezado">
    <h2>Biblioteca</h2>
    <div class="opciones">
      <usuario-activo :usuario-logueado="usuarioLogueado" :csrf-token="csrfToken" />
    </div>
  </div>

  <div class="contenedor-contenido">
    <!-- FILTROS -->
    <form
      class="contenedor-filtros"
      action="{{ route('libros.gestor') }}"
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
            <span class="icono-input-derecha puntero-cursor" @@click="$refs.formFiltros.submit()">
              <i class="icon-buscar"></i>
            </span>
          </div>
          <button
            type="button"
            class="boton-expandir-filtro"
            :class="cardFiltros ? 'activo' : ''"
            @@click="toggleFiltros()">
            <i class="icon-filtro"></i>
            Filtrar
            <i class="icon-angulo-abajo" v-if="!cardFiltros"></i>
            <i class="icon-angulo-arriba" v-else></i>
          </button>
        </div>
        <div class="row-filtros-busqueda__opciones">
          <button
            type="button"
            class="boton-agregar-filtro"
            @@click="abrirModalAgregarLibro()"
            id="btnNuevoLibro"
            :disabled="!permisosVista.agregar">
            <i class="icon-agregar"></i>
            Nuevo libro
          </button>
        </div>
      </div>

      <input ref="inputMensajeAccion" type="hidden" :value="mensajeAccionGestor" v-if="mostrarMensajeAccionGestor">

      <div class="row-filtros-varios" v-if="cardFiltros">
        <div class="input-row">
          <label>Autor</label>
          <input
            type="text"
            placeholder="Autor"
            value="{{ $filtros['busquedaAutor'] ?? '' }}"
            name="busquedaAutor"
            id="inputFiltrosBusquedaAutor"
            ref="inputFiltrosBusquedaAutor">
        </div>

        <div class="input-row">
          <label>Editorial</label>
          <input
            type="text"
            placeholder="Editorial"
            value="{{ $filtros['busquedaEditorial'] ?? '' }}"
            name="busquedaEditorial"
            id="inputFiltrosBusquedaEditorial"
            ref="inputFiltrosBusquedaEditorial">
        </div>

        <div class="input-row">
          <label>Genero</label>
          <input
            type="text"
            placeholder="Genero"
            value="{{ $filtros['busquedaGenero'] ?? '' }}"
            name="busquedaGenero"
            id="inputFiltrosBusquedaGenero"
            ref="inputFiltrosBusquedaGenero">
        </div>

        <div class="input-row">
          <label>Idioma</label>
          <input
            type="text"
            placeholder="Idioma"
            value="{{ $filtros['busquedaIdioma'] ?? '' }}"
            name="busquedaIdioma"
            id="inputFiltrosBusquedaIdioma"
            ref="inputFiltrosBusquedaIdioma">
        </div>

        <div class="input-row">
          <label>ISBN</label>
          <input
            type="text"
            placeholder="ISBN"
            value="{{ $filtros['busquedaIsbn'] ?? '' }}"
            name="busquedaIsbn"
            id="inputFiltrosBusquedaIsbn"
            ref="inputFiltrosBusquedaIsbn">
        </div>

        <div class="input-row">
          <label>Status</label>
          <select name="statusDisponibilidad[]" id="selectStatusDisponibilidad" v-model="filtros.statusDisponibilidad">
            <option :value="undefined" disabled>Selecciona una opción</option>
            <option value="DISPONIBLE">Disponible</option>
            <option value="OCUPADO">Ocupado</option>
            <option value="RETIRADO">Retirado</option>
          </select>
        </div>

        <div class="input-row botones-filtros">
          <button
            type="submit"
            class="boton-aceptar">
            Buscar
          </button>
          <button
            type="button"
            class="boton-expandir-filtro"
            @@click="window.location.href='{{ route('libros.gestor') }}'">
            <i class="icon-filtro"></i>
            Limpiar
          </button>
        </div>
      </div>
    </form>
    <!-- FIN FILTROS -->

    <div class="contenedor-grid-gestor">
      <!-- <div id="Grid"></div> -->
      <table class="styled-table">
        <thead>
          <tr>
            <th class="w5p texto-centrado">Acciones</th>
            <th class="w5p texto-centrado">Folio</th>
            <th class="w10p">Nombre</th>
            <th class="w10p">Autor</th>
            <th class="w10p">Editorial</th>
            <th class="w6p texto-centrado">No. páginas</th>
            <th class="w8p">Género</th>
            <th class="w8p">Idioma</th>
            <th class="w9p">ISBN</th>
            <th class="w7p">Disponibilidad</th>
            <th class="w6p texto-centrado">Ocupación</th>
            <th class="w6p texto-centrado">Retorno</th>
            <th class="w10p">Observaciones</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="libros.length > 0">
            <tr v-for="libro in libros">
              <td>
                <div class="celda-acciones-gestor center" v-if="libro.status == 200">
                  <button
                    v-if="permisosVista.editar"
                    @@click="abrirModalEditarLibro(libro)"
                    class="boton-en-texto"
                    :id="'id-editar-' + libro.libroId"
                    title="Editar"
                    :disabled="libro.statusDisponibilidad != 'DISPONIBLE'">
                    <i class="icon-editar accionEditar"></i>
                  </button>
                  <button
                    class="boton-en-texto"
                    @@click="abrirModalOcuparLibro(libro)"
                    :id="'id-ocupar-' + libro.libroId"
                    title="Ocupar"
                    :disabled="libro.statusDisponibilidad != 'DISPONIBLE'">
                    <i class="icon-calendario accionOcupar"></i>
                  </button>
                  <button
                    v-if="permisosVista.eliminar"
                    @@click="abrirModalEliminarLibro(libro)"
                    class="boton-en-texto"
                    :id="'id-eliminar-' + libro.libroId"
                    title="Eliminar"
                    :disabled="libro.statusDisponibilidad != 'DISPONIBLE'">
                    <i class="icon-eliminar accionEliminar"></i>
                  </button>
                </div>
              </td>
              <td class="texto-centrado">
                <a :href="'/libros/' + libro.libro_id + '/' + libro.hash_id">
                  @{{ libro.folio }}
                </a>
              </td>
              <td>
                <div class="texto-elipsis">
                  @{{ libro.nombre }}
                </div>
              </td>
              <td>
                <div class="texto-elipsis">
                  @{{ libro.autorNombre }}
                </div>
              </td>
              <td>
                <div class="texto-elipsis">
                  @{{ libro.editorialNombre }}
                </div>
              </td>
              <td class="texto-centrado">@{{ libro.numeroPaginas }}</td>
              <td>@{{ capitalizarTexto(libro.genero) }}</td>
              <td>@{{ capitalizarTexto(libro.idioma) }}</td>
              <td>@{{ libro.isbn }}</td>
              <td>
                <div class="status-global">
                  <div :class="'status-bullet '+ obtenerClaseStatus(libro.statusDisponibilidad)"></div>
                  @{{ capitalizarTexto(libro.statusDisponibilidad) }}
                </div>
              </td>
              <td class="texto-centrado">
                @{{ libro.salidaFecha ? moment(libro.salidaFecha).format("DD/MM/YYYY") : '' }}
              </td>
              <td class="texto-centrado">
                @{{ libro.regresoFecha ? moment(libro.regresoFecha).format("DD/MM/YYYY") : '' }}
              </td>
              <td>
                <div class="texto-elipsis">
                  @{{ libro.observaciones }}
                </div>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="13">
                <div class="texto-centrado">
                  No se encuentra ninguna libro relacionado
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODALES -->
  <!-- COMIENZA MODAL AGREGAR LIBRO -->
  <template>
    <div v-if="modalAgregarLibro" class="modal">
      <div class="modal-card w700">
        <div class="modal-header">
          <label>Nuevo libro</label>
          <i @@click="cerrarModalAgregarLibro" class="icon-cerrar" id="btnCerrarModalAgregarLibro"></i>
        </div>
        <div class="modal-body">
          <form
            id="formAgregarLibro"
            ref="formAgregarLibro"
            @@submit.prevent="agregarLibro()"
            class="formulario-multi-columna">
            <div class="columna-inputs">
              <div class="input-row">
                <label class="requerido">Nombre</label>
                <input
                  type="text"
                  placeholder="Nombre"
                  required
                  maxlength="240"
                  v-model.trim="libroObj.nombre"
                  ref="nombreInput"
                  id="nombreInput">
              </div>

              <div class="input-multiple">
                <div class="input-row">
                  <label>Autor</label>
                  <input
                    type="text"
                    maxlength="240"
                    placeholder="Autor"
                    v-model="libroObj.autorNombre"
                    id="inputAutor"
                    ref="autorNombre"
                    @@keydown.enter.prevent="abrirModalBusquedaAutor()">
                </div>
                <div class="input-row input-sin-label ancho-contenido">
                  <button
                    type="button"
                    class="boton-busqueda"
                    id="btnAbrirModalBusquedaAutor"
                    @@click="abrirModalBusquedaAutor()">
                    <i class="icon-buscar"></i>
                  </button>
                </div>
              </div>

              <div class="input-multiple">
                <div class="input-row">
                  <label>Editorial</label>
                  <input
                    type="text"
                    maxlength="240"
                    placeholder="Editorial"
                    v-model="libroObj.editorialNombre"
                    id="inputEditorial"
                    ref="editorialNombre"
                    @@keydown.enter.prevent="abrirModalBusquedaEditorial()">
                </div>
                <div class="input-row input-sin-label ancho-contenido">
                  <button
                    type="button"
                    class="boton-busqueda"
                    id="btnAbrirModalBusquedaEditorial"
                    @@click="abrirModalBusquedaEditorial()">
                    <i class="icon-buscar"></i>
                  </button>
                </div>
              </div>

              <div class="input-multiple">
                <div class="input-row">
                  <label>Genero</label>
                  <input
                    type="text"
                    maxlength="100"
                    placeholder="Genero"
                    v-model="libroObj.generoNombre"
                    id="inputGenero"
                    ref="generoNombre"
                    @@keydown.enter.prevent="abrirModalBusquedaGenero()">
                </div>
                <div class="input-row input-sin-label ancho-contenido">
                  <button
                    type="button"
                    class="boton-busqueda"
                    id="btnAbrirModalBusquedaGenero"
                    @@click="abrirModalBusquedaGenero()">
                    <i class="icon-buscar"></i>
                  </button>
                </div>
              </div>

              <div class="input-multiple">
                <div class="input-row">
                  <label>Idioma</label>
                  <input
                    type="text"
                    maxlength="100"
                    placeholder="Idioma"
                    v-model="libroObj.idiomaNombre"
                    id="inputIdioma"
                    ref="idiomaNombre"
                    @@keydown.enter.prevent="abrirModalBusquedaIdioma()">
                </div>
                <div class="input-row input-sin-label ancho-contenido">
                  <button
                    type="button"
                    class="boton-busqueda"
                    id="btnAbrirModalBusquedaIdioma"
                    @@click="abrirModalBusquedaIdioma()">
                    <i class="icon-buscar"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="columna-inputs">
              <div class="input-row">
                <label class="requerido">Número de páginas</label>
                <input
                  type="text"
                  placeholder="Número de páginas"
                  required
                  maxlength="4"
                  v-model.trim="libroObj.numeroPaginas"
                  oninput="validateInputNumber(this)"
                  id="numeroPaginasInput">
              </div>

              <div class="input-row">
                <label>ISBN</label>
                <input
                  type="text"
                  placeholder="ISBN"
                  maxlength="13"
                  v-model.trim="libroObj.isbn"
                  id="isbnInput">
              </div>

              <div class="input-row">
                <label>Observaciones</label>
                <textarea
                  class="text-116h"
                  placeholder="Descripción"
                  maxlength="250"
                  v-model.trim="libroObj.observaciones"
                  id="observacionInput"></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer center">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalAgregarLibro()"
            id="btnCancelarAgregarLibro">
            Cancelar</button>
          <button
            type="submit"
            class="boton-aceptar"
            form="formAgregarLibro"
            id="btnAgregarLibro">
            Guardar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL AGREGAR LIBRO -->

  <!-- COMIENZA MODAL EDITAR LIBRO -->
  <template>
    <div v-if="modalEditarLibro" class="modal">
      <div class="modal-card w700">
        <div class="modal-header">
          <label>Editar libro</label>
          <i @@click="cerrarModalEditarLibro" class="icon-cerrar" id="btnCerrarModalEditarLibro"></i>
        </div>
        <div class="modal-body">
          <form
            id="formEditarLibro"
            ref="formEditarLibro"
            @@submit.prevent="editarLibro()"
            class="formulario-multi-columna">
            <div class="columna-inputs">
              <div class="input-row">
                <label class="requerido">Nombre</label>
                <input
                  type="text"
                  placeholder="Nombre"
                  required
                  maxlength="240"
                  v-model.trim="libroObj.nombre"
                  ref="nombreInput"
                  id="nombreInput">
              </div>

              <div class="input-multiple">
                <div class="input-row">
                  <label>Autor</label>
                  <input
                    type="text"
                    maxlength="240"
                    placeholder="Autor"
                    v-model="libroObj.autorNombre"
                    id="inputAutor"
                    ref="autorNombre"
                    @@keydown.enter.prevent="abrirModalBusquedaAutor()">
                </div>
                <div class="input-row input-sin-label ancho-contenido">
                  <button
                    type="button"
                    class="boton-busqueda"
                    id="btnAbrirModalBusquedaAutor"
                    @@click="abrirModalBusquedaAutor()">
                    <i class="icon-buscar"></i>
                  </button>
                </div>
              </div>

              <div class="input-multiple">
                <div class="input-row">
                  <label>Editorial</label>
                  <input
                    type="text"
                    maxlength="240"
                    placeholder="Editorial"
                    v-model="libroObj.editorialNombre"
                    id="inputEditorial"
                    ref="editorialNombre"
                    @@keydown.enter.prevent="abrirModalBusquedaEditorial()">
                </div>
                <div class="input-row input-sin-label ancho-contenido">
                  <button
                    type="button"
                    class="boton-busqueda"
                    id="btnAbrirModalBusquedaEditorial"
                    @@click="abrirModalBusquedaEditorial()">
                    <i class="icon-buscar"></i>
                  </button>
                </div>
              </div>

              <div class="input-multiple">
                <div class="input-row">
                  <label>Genero</label>
                  <input
                    type="text"
                    maxlength="100"
                    placeholder="Genero"
                    v-model="libroObj.generoNombre"
                    id="inputGenero"
                    ref="generoNombre"
                    @@keydown.enter.prevent="abrirModalBusquedaGenero()">
                </div>
                <div class="input-row input-sin-label ancho-contenido">
                  <button
                    type="button"
                    class="boton-busqueda"
                    id="btnAbrirModalBusquedaGenero"
                    @@click="abrirModalBusquedaGenero()">
                    <i class="icon-buscar"></i>
                  </button>
                </div>
              </div>

              <div class="input-multiple">
                <div class="input-row">
                  <label>Idioma</label>
                  <input
                    type="text"
                    maxlength="100"
                    placeholder="Idioma"
                    v-model="libroObj.idiomaNombre"
                    id="inputIdioma"
                    ref="idiomaNombre"
                    @@keydown.enter.prevent="abrirModalBusquedaIdioma()">
                </div>
                <div class="input-row input-sin-label ancho-contenido">
                  <button
                    type="button"
                    class="boton-busqueda"
                    id="btnAbrirModalBusquedaIdioma"
                    @@click="abrirModalBusquedaIdioma()">
                    <i class="icon-buscar"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="columna-inputs">
              <div class="input-row">
                <label class="requerido">Número de páginas</label>
                <input
                  type="text"
                  placeholder="Número de páginas"
                  required
                  maxlength="4"
                  v-model.trim="libroObj.numeroPaginas"
                  oninput="validateInputNumber(this)"
                  id="numeroPaginasInput">
              </div>

              <div class="input-row">
                <label>ISBN</label>
                <input
                  type="text"
                  placeholder="ISBN"
                  maxlength="13"
                  v-model.trim="libroObj.isbn"
                  id="isbnInput">
              </div>

              <div class="input-row">
                <label>Observaciones</label>
                <textarea
                  class="text-116h"
                  placeholder="Descripción"
                  maxlength="250"
                  v-model.trim="libroObj.observaciones"
                  id="observacionInput"></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer center">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalEditarLibro()"
            id="btnCancelarEditarLibro">
            Cancelar</button>
          <button
            type="submit"
            class="boton-aceptar"
            form="formEditarLibro"
            id="btnEditarLibro">
            Guardar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL EDITAR LIBRO -->

  <!-- COMIENZA MODAL BUSQUEDA GENEROS -->
  <template>
    <div v-if="modalBusquedaGenero" class="modal modal-busqueda">
      <div class="modal-card w600">
        <div class="modal-header">
          <label>Búsqueda de categorías</label>
          <i @@click="cerrarModalBusquedaGenero" class="icon-cerrar" id="btncerrarModalBusquedaGenero"></i>
        </div>
        <div class="modal-body modal-busqueda">
          <form
            id="formBuscarGenero"
            ref="formBuscarGenero"
            @@submit.prevent="listarGeneros()">
            <div class="row-filtros-busqueda">
              <div class="row-filtros-busqueda__input">
                <div class="input-con-icono-contenedor">
                  <input
                    type="text"
                    placeholder="Buscar"
                    class="input-con-icono-derecha"
                    v-model="libroObj.generoNombre"
                    name="busqueda"
                    id="inputFiltroBusquedaGenero"
                    ref="inputFiltroBusquedaGenero">
                  <span class="icono-input-derecha puntero-cursor" @@click="listarGeneros()">
                    <i class="icon-buscar"></i>
                  </span>
                </div>
                <button
                  type="submit"
                  class="boton-aceptar">
                  Buscar
                </button>
                <button
                  type="button"
                  class="boton-expandir-filtro"
                  @@click="limpiarInputBusquedaGenero()">
                  <i class="icon-filtro"></i>
                  Limpiar
                </button>
              </div>
            </div>
          </form>

          <div class="div-overflow">
            <table class="tabla-base">
              <thead class="cabecera-sticky">
                <tr>
                  <th class="texto-izquierda">Genero</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="generos.length > 0">
                  <tr v-for="(genero, index) in generos" @dblclick="seleccionarGenero(genero)" :id="'id-' + index">
                    <td>@{{ capitalizarTexto(genero.genero) }}</td>
                  </tr>
                </template>
                <template v-else>
                  <tr>
                    <td class="texto-centrado">No se encontró ningún registro</td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA BUSQUEDA GENEROS -->

  <!-- COMIENZA MODAL BUSQUEDA IDIOMAS -->
  <template>
    <div v-if="modalBusquedaIdioma" class="modal modal-busqueda">
      <div class="modal-card w600">
        <div class="modal-header">
          <label>Búsqueda de categorías</label>
          <i @@click="cerrarModalBusquedaIdioma" class="icon-cerrar" id="btncerrarModalBusquedaIdioma"></i>
        </div>
        <div class="modal-body modal-busqueda">
          <form
            id="formBuscarIdioma"
            ref="formBuscarIdioma"
            @@submit.prevent="listarIdiomas()">
            <div class="row-filtros-busqueda">
              <div class="row-filtros-busqueda__input">
                <div class="input-con-icono-contenedor">
                  <input
                    type="text"
                    placeholder="Buscar"
                    class="input-con-icono-derecha"
                    v-model="libroObj.idiomaNombre"
                    name="busqueda"
                    id="inputFiltroBusquedaIdioma"
                    ref="inputFiltroBusquedaIdioma">
                  <span class="icono-input-derecha puntero-cursor" @@click="listarIdiomas()">
                    <i class="icon-buscar"></i>
                  </span>
                </div>
                <button
                  type="submit"
                  class="boton-aceptar">
                  Buscar
                </button>
                <button
                  type="button"
                  class="boton-expandir-filtro"
                  @@click="limpiarInputBusquedaIdioma()">
                  <i class="icon-filtro"></i>
                  Limpiar
                </button>
              </div>
            </div>
          </form>

          <div class="div-overflow">
            <table class="tabla-base">
              <thead class="cabecera-sticky">
                <tr>
                  <th class="texto-izquierda">Idioma</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="idiomas.length > 0">
                  <tr v-for="(idioma, index) in idiomas" @dblclick="seleccionarIdioma(idioma)" :id="'id-' + index">
                    <td>@{{ capitalizarTexto(idioma.idioma) }}</td>
                  </tr>
                </template>
                <template v-else>
                  <tr>
                    <td class="texto-centrado">No se encontró ningún registro</td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA BUSQUEDA IDIOMAS -->

  <!-- COMIENZA MODAL BUSQUEDA AUTORES -->
  <template>
    <div v-if="modalBusquedaAutor" class="modal modal-busqueda">
      <div class="modal-card w600">
        <div class="modal-header">
          <label>Búsqueda de categorías</label>
          <i @@click="cerrarModalBusquedaAutor" class="icon-cerrar" id="btncerrarModalBusquedaAutor"></i>
        </div>
        <div class="modal-body modal-busqueda">
          <form
            id="formBuscarAutor"
            ref="formBuscarAutor"
            @@submit.prevent="listarAutores()">
            <div class="row-filtros-busqueda">
              <div class="row-filtros-busqueda__input">
                <div class="input-con-icono-contenedor">
                  <input
                    type="text"
                    placeholder="Buscar"
                    class="input-con-icono-derecha"
                    v-model="libroObj.autorNombre"
                    name="busqueda"
                    id="inputFiltroBusquedaAutor"
                    ref="inputFiltroBusquedaAutor">
                  <span class="icono-input-derecha puntero-cursor" @@click="listarAutores()">
                    <i class="icon-buscar"></i>
                  </span>
                </div>
                <button
                  type="submit"
                  class="boton-aceptar">
                  Buscar
                </button>
                <button
                  type="button"
                  class="boton-expandir-filtro"
                  @@click="limpiarInputBusquedaAutor()">
                  <i class="icon-filtro"></i>
                  Limpiar
                </button>
              </div>
            </div>
          </form>

          <div class="div-overflow">
            <table class="tabla-base">
              <thead class="cabecera-sticky">
                <tr>
                  <th class="texto-izquierda">Autor</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="autores.length > 0">
                  <tr v-for="(autor, index) in autores" @dblclick="seleccionarAutor(autor)" :id="'id-' + index">
                    <td>@{{ autor.autor_nombre }}</td>
                  </tr>
                </template>
                <template v-else>
                  <tr>
                    <td class="texto-centrado">No se encontró ningún registro</td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA BUSQUEDA AUTORES -->

  <!-- COMIENZA MODAL BUSQUEDA EDITORIALES -->
  <template>
    <div v-if="modalBusquedaEditorial" class="modal modal-busqueda">
      <div class="modal-card w600">
        <div class="modal-header">
          <label>Búsqueda de categorías</label>
          <i @@click="cerrarModalBusquedaEditorial" class="icon-cerrar" id="btncerrarModalBusquedaEditorial"></i>
        </div>
        <div class="modal-body modal-busqueda">
          <form
            id="formBuscarEditorial"
            ref="formBuscarEditorial"
            @@submit.prevent="listarEditoriales()">
            <div class="row-filtros-busqueda">
              <div class="row-filtros-busqueda__input">
                <div class="input-con-icono-contenedor">
                  <input
                    type="text"
                    placeholder="Buscar"
                    class="input-con-icono-derecha"
                    v-model="libroObj.editorialNombre"
                    name="busqueda"
                    id="inputFiltroBusquedaEditorial"
                    ref="inputFiltroBusquedaEditorial">
                  <span class="icono-input-derecha puntero-cursor" @@click="listarEditoriales()">
                    <i class="icon-buscar"></i>
                  </span>
                </div>
                <button
                  type="submit"
                  class="boton-aceptar">
                  Buscar
                </button>
                <button
                  type="button"
                  class="boton-expandir-filtro"
                  @@click="limpiarInputBusquedaEditorial()">
                  <i class="icon-filtro"></i>
                  Limpiar
                </button>
              </div>
            </div>
          </form>

          <div class="div-overflow">
            <table class="tabla-base">
              <thead class="cabecera-sticky">
                <tr>
                  <th class="texto-izquierda">Editorial</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="editoriales.length > 0">
                  <tr v-for="(editorial, index) in editoriales" @dblclick="seleccionarEditorial(editorial)" :id="'id-' + index">
                    <td>@{{ editorial.editorial_nombre }}</td>
                  </tr>
                </template>
                <template v-else>
                  <tr>
                    <td class="texto-centrado">No se encontró ningún registro</td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA BUSQUEDA EDITORIALES -->

  <!-- COMIENZA MODAL ELIMINAR LIBRO -->
  <template>
    <div v-if="modalEliminarLibro" class="modal modal-eliminar">
      <div class="modal-card">
        <div class="modal-header solo-btn-cerrar">
          <i @@click="cerrarModalEliminarLibro" class="icon-cerrar" id="btnCerrarModalEliminarLibro"></i>
        </div>
        <div class="modal-body">
          <img class="ilustracion-modal ilustracion-eliminar" src="{{ asset('imagenes/biblioteca-ilustracion-eliminar.svg') }}" alt="">

          <p class="titulo-modal">Eliminar registro</p>
          <p>¿Estás seguro que deseas eliminar el siguiente libro?</p>
          <p class="mb-8">Esta acción no se puede deshacer.</p>

          <p class="subtitulo-modal">@{{ `${libroObj.folio} - ${libroObj.nombre}` }}</p>
        </div>
        <div class="modal-footer center no-border">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalEliminarLibro()"
            id="btnCancelarEliminarLibro">
            Cancelar</button>
          <button
            type="button"
            class="boton-aceptar boton--eliminar"
            @@click="eliminarLibro"
            id="btnEliminarLibro">
            Eliminar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL ELIMINAR LIBRO -->

  <!-- COMIENZA MODAL OCUPAR LIBRO -->
  <template>
    <div v-if="modalOcuparLibro" class="modal modal-eliminar">
      <div class="modal-card">
        <div class="modal-header solo-btn-cerrar">
          <i @@click="cerrarModalOcuparLibro" class="icon-cerrar" id="btnCerrarModalOcuparLibro"></i>
        </div>
        <div class="modal-body">
          <img class="ilustracion-modal ilustracion-eliminar" src="{{ asset('imagenes/calendar.svg') }}" alt="">

          <p class="titulo-modal">Ocupar libro</p>
          <p>¿Estás seguro que deseas ocupar el siguiente libro?</p>
          <p class="mb-8">Tienes máximo el día <span class="letra-bold">@{{ calcularFechaEntrega }}</span> para realizar la devolución.</p>

          <p class="subtitulo-modal">@{{ `${libroObj.folio} - ${libroObj.nombre}` }}</p>
        </div>
        <div class="modal-footer center no-border">
          <button
            class="boton-aceptar boton--plata"
            @@click="cerrarModalOcuparLibro()"
            id="btnCancelarOcuparLibro">
            Cancelar</button>
          <button
            type="button"
            class="boton-aceptar"
            @@click="ocuparLibro"
            id="btnOcuparLibro">
            Aceptar</button>
        </div>
      </div>
      <div class="modal-background"></div>
    </div>
  </template>
  <!-- TERMINA MODAL OCUPAR LIBRO -->
</div>

<!-- VARIABLES -->
<script id="usuarioLogueado" type="application/json">
  @json($usuarioLogueado)
</script>
<script id="mensajeAccion" type="application/json">
  @json($mensajeAccion)
</script>
<script id="permisosVista" type="application/json">
  @json($permisosVista)
</script>
<script id="datosGestor" type="application/json">
  @json($datosGestor)
</script>
<script id="filtros" type="application/json">
  @json($filtros)
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
      urlLibroGestor: "/libros",
      urlLibroLeerExcel: "/libros/leer-excel",
      urlListarGeneros: "/libros/listar-generos",
      urlListarIdiomas: "/libros/listar-idiomas",
      urlListarAutores: "/libros/listar-autores",
      urlListarEditoriales: "/libros/listar-editoriales",
      urlLibrosAgregar: "/libros/agregar",
      urlLibroEditar: "/libros/editar",
      urlLibrosEliminar: "/libros/eliminar",
      urlLibrosOcupar: "/libros/ocupar",

      // Variables vista
      usuarioLogueado: JSON.parse(document.getElementById('usuarioLogueado').textContent),
      mensajeAccion: JSON.parse(document.getElementById('mensajeAccion').textContent),
      permisosVista: JSON.parse(document.getElementById('permisosVista').textContent),
      datosGestor: JSON.parse(document.getElementById('datosGestor').textContent),
      filtrosURL: JSON.parse(document.getElementById('filtros').textContent),

      // Filtros
      filtros: {
        status: null,
        statusDisponibilidad: undefined,
      },
      statusDisponibilidadOpc: [{
          value: "DISPONIBLE",
          text: "Disponible",
        },
        {
          value: "OCUPADO",
          text: "Ocupado",
        },
        {
          value: "RETIRADO",
          text: "Retirado",
        },
      ],

      // Data
      libros: [],
      generos: [],
      idiomas: [],
      autores: [],
      editoriales: [],
      libroObj: {
        nombre: null,
        autorNombre: null,
        editorialNombre: null,
        numeroPaginas: null,
        generoNombre: null,
        idiomaNombre: null,
        isbn: null,
        observaciones: null,
      },

      // Modales
      modalAgregarLibro: false,
      modalEditarLibro: false,
      modalEliminarLibro: false,
      modalBusquedaGenero: false,
      modalBusquedaIdioma: false,
      modalBusquedaAutor: false,
      modalBusquedaEditorial: false,
      modalOcuparLibro: false,

      // Mensaje accion gestor
      mensajeAccionGestor: "",
      mostrarMensajeAccionGestor: false,

      // SyncFusion
      selectStatusDisponibilidad: null,
    },
    beforeDestroy() {},
    computed: {
      calcularFechaEntrega() {
        const hoy = new Date();
        hoy.setDate(hoy.getDate() + 14);

        return moment(hoy).format("DD/MM/YYYY")
      },
    },
    async mounted() {
      this.cargaInicial();
      this.cargarFiltros();
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
        // Setear registros
        // this.libros = this.datosGestor.registros;
        this.libros = this.datosGestor;
        console.log(this.libros);

        this.$refs.inputFiltros.focus();
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

      /************************************************************/
      /************************* Filtros **************************/
      /************************************************************/
      cargarFiltros() {
        if (this.filtrosURL.statusDisponibilidad.length > 0) {
          this.filtros.statusDisponibilidad = this.filtrosURL.statusDisponibilidad[0];
        } else {
          this.filtros.statusDisponibilidad = undefined
        }
      },
      async toggleFiltros() {
        this.cardFiltros = !this.cardFiltros;

        this.$nextTick(() => {
          if (this.cardFiltros) {}
        });
      },

      /************************************************************/
      /********************** Agregar libro ***********************/
      /************************************************************/
      abrirModalAgregarLibro() {
        this.modalAgregarLibro = true;

        this.$nextTick(() => {
          this.$refs.nombreInput.focus();
        });
      },
      cerrarModalAgregarLibro() {
        this.modalAgregarLibro = false;
        this.libroObj = {
          nombre: null,
          autorNombre: null,
          editorialNombre: null,
          numeroPaginas: null,
          generoNombre: null,
          idiomaNombre: null,
          isbn: null,
          observaciones: null,
        };
      },
      async agregarLibro() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlLibrosAgregar, this.libroObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            // let id = data.data.libroId;
            // let hashId = data.data.hashId;

            // window.location.href = `${this.urlLibroGestor}/${id}/${hashId}?exito=agregar`
            window.location.href = `${this.urlLibroGestor}/?exito=agregar`
            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      /************************************************************/
      /*********************** Editar libro ***********************/
      /************************************************************/
      abrirModalEditarLibro(libroObj) {
        this.libroObj = {
          ...libroObj,
          generoNombre: libroObj.genero,
          idiomaNombre: libroObj.idioma
        };

        this.modalEditarLibro = true;

        this.$nextTick(() => {
          this.$refs.nombreInput.focus();
        });
      },
      cerrarModalEditarLibro() {
        this.modalEditarLibro = false;
        this.libroObj = {
          nombre: null,
          autorNombre: null,
          editorialNombre: null,
          numeroPaginas: null,
          generoNombre: null,
          idiomaNombre: null,
          isbn: null,
          observaciones: null,
        };
      },
      async editarLibro() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlLibroEditar, this.libroObj)
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

            // this.$refs.formFiltros.submit();

            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      /************************************************************/
      /********************** Eliminar libro **********************/
      /************************************************************/
      abrirModalEliminarLibro(libroObj) {
        this.libroObj = {
          ...libroObj,
        };

        this.modalEliminarLibro = true;
      },
      cerrarModalEliminarLibro() {
        this.modalEliminarLibro = false;
        this.libroObj = {
          nombre: null,
          autorNombre: null,
          editorialNombre: null,
          numeroPaginas: null,
          generoNombre: null,
          idiomaNombre: null,
          isbn: null,
          observaciones: null,
        };
      },
      async eliminarLibro() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlLibrosEliminar, this.libroObj)
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

            // this.$refs.formFiltros.submit();
            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      /************************************************************/
      /*********************** Ocupar libro ***********************/
      /************************************************************/
      abrirModalOcuparLibro(libroObj) {
        this.libroObj = {
          ...libroObj,
        };

        this.modalOcuparLibro = true;
      },
      cerrarModalOcuparLibro() {
        this.modalOcuparLibro = false;
        this.libroObj = {
          nombre: null,
          autorNombre: null,
          editorialNombre: null,
          numeroPaginas: null,
          generoNombre: null,
          idiomaNombre: null,
          isbn: null,
          observaciones: null,
        };
      },
      async ocuparLibro() {
        if (this.loader) return;

        this.loader = true;
        await axios.post(this.urlLibrosOcupar, this.libroObj)
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            // Mensaje acción
            this.mostrarMensajeAccionGestor = true;
            this.mensajeAccionGestor = "ocupar";
            this.$nextTick(() => {
              this.$refs.inputMensajeAccion.name = "exito";
              this.$refs.formFiltros.submit()
            });

            // this.$refs.formFiltros.submit();
            // this.loader = false;
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },

      /************************************************************/
      /********************* Busqueda genero **********************/
      /************************************************************/
      async listarGeneros() {
        let data = {
          params: {
            filtros: {
              busqueda: this.libroObj.generoNombre
            },
            order: ["nombre_asc"]
          }
        };

        await axios.get(this.urlListarGeneros, data)
          .then((response) => {
            this.generos = response.data.data ?? [];
            console.log(this.generos);
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error)
          });
      },
      async limpiarInputBusquedaGenero() {
        this.libroObj.generoNombre = "";
        await this.listarGeneros();
      },
      async abrirModalBusquedaGenero() {
        await this.listarGeneros();
        this.modalBusquedaGenero = await true;

        this.$nextTick(() => {
          this.$refs.inputFiltroBusquedaGenero.focus();
        });
      },
      cerrarModalBusquedaGenero() {
        this.modalBusquedaGenero = false;

        this.$nextTick(() => {
          this.$refs.generoNombre.focus();
        });
      },
      async seleccionarGenero(genero) {
        this.libroObj.generoNombre = genero.genero
        this.cerrarModalBusquedaGenero();
      },

      /************************************************************/
      /********************* Busqueda idioma **********************/
      /************************************************************/
      async listarIdiomas() {
        let data = {
          params: {
            filtros: {
              busqueda: this.libroObj.idiomaNombre
            },
            order: ["nombre_asc"]
          }
        };

        await axios.get(this.urlListarIdiomas, data)
          .then((response) => {
            this.idiomas = response.data.data ?? [];
            console.log(this.idiomas);
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error)
          });
      },
      async limpiarInputBusquedaIdioma() {
        this.libroObj.idiomaNombre = "";
        await this.listarIdiomas();
      },
      async abrirModalBusquedaIdioma() {
        await this.listarIdiomas();
        this.modalBusquedaIdioma = await true;

        this.$nextTick(() => {
          this.$refs.inputFiltroBusquedaIdioma.focus();
        });
      },
      cerrarModalBusquedaIdioma() {
        this.modalBusquedaIdioma = false;

        this.$nextTick(() => {
          this.$refs.idiomaNombre.focus();
        });
      },
      async seleccionarIdioma(idioma) {
        this.libroObj.idiomaNombre = idioma.idioma
        this.cerrarModalBusquedaIdioma();
      },

      /************************************************************/
      /********************* Busqueda autores *********************/
      /************************************************************/
      async listarAutores() {
        let data = {
          params: {
            filtros: {
              busqueda: this.libroObj.autorNombre
            },
            order: ["nombre_asc"]
          }
        };

        await axios.get(this.urlListarAutores, data)
          .then((response) => {
            this.autores = response.data.data ?? [];
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error)
          });
      },
      async limpiarInputBusquedaAutor() {
        this.libroObj.autorNombre = "";
        await this.listarAutores();
      },
      async abrirModalBusquedaAutor() {
        await this.listarAutores();
        this.modalBusquedaAutor = await true;

        this.$nextTick(() => {
          this.$refs.inputFiltroBusquedaAutor.focus();
        });
      },
      cerrarModalBusquedaAutor() {
        this.modalBusquedaAutor = false;

        this.$nextTick(() => {
          this.$refs.autorNombre.focus();
        });
      },
      async seleccionarAutor(autor) {
        this.libroObj.autorNombre = autor.autor_nombre
        this.cerrarModalBusquedaAutor();
      },

      /************************************************************/
      /******************* Busqueda editoriales *******************/
      /************************************************************/
      async listarEditoriales() {
        let data = {
          params: {
            filtros: {
              busqueda: this.libroObj.editorialNombre
            },
            order: ["nombre_asc"]
          }
        };

        await axios.get(this.urlListarEditoriales, data)
          .then((response) => {
            this.editoriales = response.data.data ?? [];
          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error)
          });
      },
      async limpiarInputBusquedaEditorial() {
        this.libroObj.editorialNombre = "";
        await this.listarEditoriales();
      },
      async abrirModalBusquedaEditorial() {
        await this.listarEditoriales();
        this.modalBusquedaEditorial = await true;

        this.$nextTick(() => {
          this.$refs.inputFiltroBusquedaEditorial.focus();
        });
      },
      cerrarModalBusquedaEditorial() {
        this.modalBusquedaEditorial = false;

        this.$nextTick(() => {
          this.$refs.editorialNombre.focus();
        });
      },
      async seleccionarEditorial(editorial) {
        this.libroObj.editorialNombre = editorial.editorial_nombre
        this.cerrarModalBusquedaEditorial();
      },

      /************************************************************/
      /************************ Leer excel ************************/
      /************************************************************/
      leerExcel() {
        const fileInput = this.$refs.archivoExcel;
        const file = fileInput.files[0];
        if (!file) {
          return;
        }

        const formData = new FormData();
        formData.append('file', file);

        axios.post(this.urlLibroLeerExcel, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then((response) => {
            let data = response.data;

            if (data.codigo != 200) {
              throw data.mensaje
            }

            console.log(response);

          })
          .catch((error) => {
            this.mostrarAlerta("alerta-error", error);
            this.loader = false;
          });
      },
    }
  })
</script>
@endsection