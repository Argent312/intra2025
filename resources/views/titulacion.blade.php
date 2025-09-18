<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reserva de Sala de Titulacion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' /> 
    
    <style>
        body { font-family: Arial, sans-serif; }
        .container { margin-top: 30px; }
        #calendar { max-width: 900px; margin: 0 auto; }
        .modal-body label { font-weight: bold; margin-top: 10px; display: block; }
        .modal-body input, .modal-body textarea { width: 100%; padding: 8px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Calendario Sala de Titulacion</h2>
        <div id="calendar"></div>
    </div>

    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">Reservar Sala de Titulacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Horario seleccionado: <strong id="selectedTime"></strong></p>
                   
                    <input type="hidden" id="nombreReservante" name="nombreReservante" class="form-control" required value="{{ Auth::user()->name }}" readonly>

                    <label for="motivoReunion">Motivo de la Reunión:</label>
                    <textarea id="motivoReunion" class="form-control" rows="3" required></textarea>

                    <label for="participantes">Participantes:</label>
                    <textarea id="participantes" class="form-control" rows="3" required></textarea>

                    <input type="hidden" id="startDate" name="startDate">
                    <input type="hidden" id="endDate" name="endDate">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveReservationBtn">Guardar Reservación</button>
                </div>
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.js'></script> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
            var selectedTimeSpan = document.getElementById('selectedTime');
            var startDateInput = document.getElementById('startDate');
            var endDateInput = document.getElementById('endDate');
            var saveReservationBtn = document.getElementById('saveReservationBtn');
            var nombreReservanteInput = document.getElementById('nombreReservante');
            var participantesInput = document.getElementById('participantes');
            var motivoReunionInput = document.getElementById('motivoReunion');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek', // Vista semanal con horas
                locale: 'es', // Establece el idioma a español
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // Opciones de vista
                },
                slotMinTime: '08:00:00', // Horario de inicio del día (8 AM)
                slotMaxTime: '20:00:00', // Horario de fin del día (6 PM)
                businessHours: { // Define horas de trabajo si quieres restringir la selección
                    daysOfWeek: [1, 2, 3, 4, 5], // Lunes a Viernes
                    
                    timeZone: 'local',
                },
                selectable: true, // Permite al usuario seleccionar franjas de tiempo
                selectMirror: true, // Muestra un "fantasma" de la selección
                selectOverlap: false, // Evita la selección sobre eventos existentes
                nowIndicator: true, // Muestra la hora actual
                events: '/meetingsTitulacion', // Carga eventos desde tu API PHP
                
                select: function(info) {
                    // Abre el modal cuando el usuario selecciona un rango de tiempo
                    const start = info.startStr.substring(0, 16); // Formato YYYY-MM-DDTHH:MM
                    const end = info.endStr.substring(0, 16);

                    selectedTimeSpan.textContent = `${start} a ${end}`;
                    startDateInput.value = info.startStr;
                    endDateInput.value = info.endStr;

                    nombreReservanteInput.value = '{{ Auth::user()->name }}'; // Limpiar campos
                    motivoReunionInput.value = '';
                    participantesInput.value = '';

                    reservationModal.show();
                },
                eventClick: function(info) {
                    // Puedes añadir lógica para ver detalles del evento o borrarlo
                    // Por ahora, solo muestra una alerta básica
                    alert('Reunión: ' + info.event.title + '\nDesde: ' + info.event.start.toLocaleString() + '\nHasta: ' + info.event.end.toLocaleString());
                },
                eventDidMount: function(info) {
                    // Puedes personalizar la apariencia de los eventos aquí si lo deseas
                }
            });

            calendar.render();

            // Lógica para guardar la reservación cuando se hace clic en el botón del modal
            saveReservationBtn.addEventListener('click', function() {
                const nombre = nombreReservanteInput.value.trim();
                const motivo = motivoReunionInput.value.trim();
                const participantes = participantesInput.value.trim();
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                if (!nombre || !motivo) {
                    alert('Por favor, ingresa tu nombre y el motivo de la reunión.');
                    return;
                }

                fetch('/meetingsTitulacion', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nombre_reservante: nombre,
                        motivo_reunion: motivo,
                        participantes: participantes,
                        fecha_inicio: startDate,
                        fecha_fin: endDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        reservationModal.hide();
                        calendar.refetchEvents(); // Recargar los eventos del calendario
                    } else {
                        alert('Error al reservar: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al intentar reservar.');
                });
            });
        });
    </script>
</body>
</html>