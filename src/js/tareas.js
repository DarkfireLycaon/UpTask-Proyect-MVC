(function(){
  obtenerTareas();
  let tareas = [];
  let filtradas = [];
// boton para mostrar el modal de agregar tarea
const nuevaTareaBtn = document.querySelector('#agregar-tarea');
nuevaTareaBtn.addEventListener('click', function(){
  mostrarFormulario();
});
//filtros de busqueda
const filtros = document.querySelectorAll('#filtros input[type="radio');
filtros.forEach(radio=>{
  radio.addEventListener('input', filtrarTareas);
})
function filtrarTareas(e){
const filtro = e.target.value;
if( filtro !== ""){
 filtradas = tareas.filter(tarea => tarea.estado === filtro);
} else{
filtradas = [];
}
mostrarTareas();
}
async function obtenerTareas(){
  try {
    const id = obtenerProyecto();
    const url = `/api/tareas?id=${id}`;
    const respuesta = await fetch(url);
    const resultado = await respuesta.json();
    
tareas = resultado.tareas;
    mostrarTareas();
  } catch (error) {
    console.log(error);
  }
}
function mostrarTareas(){
  limpiarTareas();
  totalPendientes();
  totalCompletas();

  const arrayTareas = filtradas.length ? filtradas : tareas;

 if(arrayTareas.length === 0 ){
 const contenedorTareas = document.querySelector('#listado-tareas');

 const textoNoTareas = document.createElement('LI');
 textoNoTareas.textContent = 'No Tasks';
 textoNoTareas.classList.add('no-tareas');

 contenedorTareas.appendChild(textoNoTareas);
 return;
 }
 const estados = {
   0: 'Pending',
   1: 'Complete'
 }
  arrayTareas.forEach(tarea=>{
    const contenedorTarea = document.createElement('LI');

    contenedorTarea.dataset.tareaId = tarea.id;
    contenedorTarea.classList.add('tarea');

    const nombreTarea = document.createElement('P');
    nombreTarea.textContent = tarea.nombre;
    nombreTarea.ondblclick = function(){
      mostrarFormulario(editar = true,{...tarea});
    }

    const opcionesDiv = document.createElement('DIV');
    opcionesDiv.classList.add('opciones');

    //botones
    const btnEstadoTarea = document.createElement('BUTTON');
    btnEstadoTarea.classList.add('estado-tarea');
    btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
    btnEstadoTarea.textContent = estados[tarea.estado];
    btnEstadoTarea.dataset.estadoTarea = tarea.estado;
    btnEstadoTarea.ondblclick = function() {
      cambiarEstadoTarea({... tarea});

    }

    const btnEliminarTarea = document.createElement('BUTTON');
    btnEliminarTarea.classList.add('eliminar-tarea');
    btnEliminarTarea.dataset.idTarea = tarea.id;
    btnEliminarTarea.textContent = 'Delete';
    btnEliminarTarea.ondblclick = function(){
      confirmarEliminarTarea({... tarea});
    }

    opcionesDiv.appendChild(btnEstadoTarea);
    opcionesDiv.appendChild(btnEliminarTarea);

    contenedorTarea.appendChild(nombreTarea);
    contenedorTarea.appendChild(opcionesDiv);

    const listadoTareas = document.querySelector('#listado-tareas');
    listadoTareas.appendChild(contenedorTarea);
  });
 
}
function totalCompletas(){
  const totalCompletas = tareas.filter(tarea=> tarea.estado === "1");
  const completasRadio = document.querySelector('#completadas');
  if(totalCompletas.length === 0){
    completasRadio.disabled = true;
  } else {
    completasRadio.disabled = false;
  }
}
function totalPendientes(){
  const totalPendienes = tareas.filter(tarea=> tarea.estado === "0");
  const pendientesRadio = document.querySelector('#pendientes');
  if(totalPendienes.length === 0){
    pendientesRadio.disabled = true;
  } else {
    pendientesRadio.disabled = false;
  }
}
function mostrarFormulario(editar = false, tarea = {}){
const modal = document.createElement('DIV');
modal.classList.add('modal');
modal.innerHTML = `
<form class= "formulario nueva-tarea">
<legend>${editar ? 'Edit Task' :  'Add a new task'} </legend>
<div class="campo"> 
    <label>Task</label>
    <input
    type="text" 
    name="tarea" 
    placeholder="${tarea.nombre ? 'Edit the Task' : 'Add task to the current proyect'}"
    id="tarea"
    value= "${tarea.nombre ? tarea.nombre : ''}" 
    />
    </div>
    <div class="opciones">
  <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Save changes' : "Add task"}"/>
  <button type="button" class="cerrar-modal">Cancel</button>
    </div>
    
</form>
`;
setTimeout(() => {
 
    const formulario = document.querySelector('.formulario');
    formulario.classList.add('animar');
}, 0);

modal.addEventListener('click', function(e){
  e.preventDefault();
  if(e.target.classList.contains('cerrar-modal')){
    const formulario = modal.querySelector('.formulario');
    formulario.classList.add('cerrar');
    setTimeout(() => {
      modal.remove();
    }, 500);
    
  }
  if(e.target.classList.contains('submit-nueva-tarea')){
    const nombreTarea = document.querySelector('#tarea').value.trim();
    if(nombreTarea === ''){
  
     mostrarAlerta('You must write a name for the task', 'error', 
      document.querySelector('.formulario legend')
      );
      return;
    } 
    if(editar){
      tarea.nombre = nombreTarea;
     actualizarTarea(tarea);
    } else{
      agregarTarea(nombreTarea);
    }
     
  }
})

document.querySelector('.dashboard').appendChild(modal);
}

//consultar el server para añadir nueva tarea al proyecto actual
async function agregarTarea(tarea){
// construir la peticion
const datos = new FormData();
datos.append('nombre', tarea);
datos.append('proyectoId', obtenerProyecto());


try{
 const url = 'http://localhost:3000/api/tarea';
 const respuesta = await fetch(url,  {
  method: 'POST',
  body: datos
 });
 const resultado = await respuesta.json();
 
 mostrarAlerta(
  resultado.mensaje,
   resultado.tipo, 
  document.querySelector('.formulario legend')
);

  if(resultado.tipo === 'exito'){
    const modal = document.querySelector('.modal');
    setTimeout(() => {
      modal.remove();
      
    }, 3000);
    // agregar el objeto de tareas al global
    const tareaObj = {
      id: String(resultado.id) ,
      nombre: tarea,
      estado: "0",
      proyectoId: resultado.proyectoId
    }
    tareas = [... tareas, tareaObj];
    mostrarTareas();
  }

} catch(error){
  console.log(error);
}
}
function cambiarEstadoTarea(tarea){
const nuevoEstado = tarea.estado === "1" ? "0" : "1";
tarea.estado = nuevoEstado;
actualizarTarea(tarea);
}
function confirmarEliminarTarea(tarea){
  Swal.fire({
    title: "Delete task?",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: 'No',
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
       eliminarTarea(tarea);
    } 
  });
}
async function eliminarTarea(tarea){
  const {estado, id, nombre,} = tarea;

  const datos = new FormData();
  datos.append('id', id);
  datos.append('nombre', nombre);
  datos.append('estado', estado);
  datos.append('proyectoId', obtenerProyecto());
try {
  const url = 'http://localhost:3000/api/tarea/eliminar';
  const respuesta = await fetch(url, {
    method: 'Post',
    body: datos
  }); 
  const resultado = await respuesta.json();

  if(resultado.resultado ){
    // mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.contenedor-nueva-tarea'));
   Swal.fire('Deleted!', resultado.mensaje, 'success');
    tareas = tareas.filter(tareaMemoria =>
      tareaMemoria.id !== tarea.id
    );
    mostrarTareas();
  }
} catch (error) {
  
}
}
async function actualizarTarea(tarea){
  const {estado, id, nombre, proyectoId} = tarea;

  const datos = new FormData();
  datos.append('id', id);
  datos.append('nombre', nombre);
  datos.append('estado', estado);
  datos.append('proyectoId', obtenerProyecto());

  try {
    const url = 'http://localhost:3000/api/tarea/actualizar';
    
    const respuesta = await fetch(url, {
      method: 'POST',
      body: datos


    });
    const resultado = await respuesta.json();

    if(resultado.respuesta.tipo === 'exito'){
      Swal.fire(resultado.respuesta.mensaje, resultado.respuesta.mensaje, 'success');

      const modal = document.querySelector('.modal');
      if(modal){
        modal.remove();
      }
      
        tareas = tareas.map(tareaMemoria => {
          if(tareaMemoria.id === id){
            tareaMemoria.estado = estado;
            tareaMemoria.nombre = nombre;


          } 
          return tareaMemoria;
        });
        mostrarTareas();
    }
  } catch (error) {
    console.log(error);
  }

}

function obtenerProyecto(){
  
const proyectoParams = new URLSearchParams(window.location.search);

const proyecto = Object.fromEntries(proyectoParams.entries());
return proyecto.id;
}
function mostrarAlerta(mensaje, tipo, referencia){
  //previene la creacion de multiples alertas
  const alertaPrevia = document.querySelector('.alerta');
  if(alertaPrevia){
    alertaPrevia.remove();
  }

const alerta = document.createElement('DIV');

alerta.classList.add('alerta', tipo);

alerta.textContent = mensaje;
//inserta la alerta antes del legend
referencia.parentElement.insertBefore(alerta, referencia. nextElementSibling);
//eliminar alerta despues de 5 segundos
setTimeout(() => {
alerta.remove()
}, 5000);

}
function limpiarTareas(){
  const listadoTareas = document.querySelector('#listado-tareas');
  while (listadoTareas.firstChild){
    listadoTareas.removeChild(listadoTareas.firstChild);
  }
}
})();