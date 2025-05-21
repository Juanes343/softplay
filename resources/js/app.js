import '@fullcalendar/daygrid/main.css'; // ✅ importa el CSS correctamente
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction'; // <-- ya disponible

const calendarEl = document.getElementById('calendar');
if (calendarEl) {
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        selectable: true, // puedes permitir selección
        height: 500,
        events: [
            {
                title: 'Reservado',
                start: '2025-05-03',
                end: '2025-05-04',
                color: '#dc3545'
            },
            {
                title: 'Disponible',
                start: '2025-05-06',
                color: '#198754'
            }
        ]
    });
    calendar.render();
}
