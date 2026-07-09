<x-mail::message>
# Notificación de Sistema de Tickets

{{ $messageText }}

**Detalles del Ticket:**
- **Folio:** #{{ $ticket->id }}
- **Título:** {{ $ticket->titulo }}
- **Planta:** Planta {{ $ticket->planta }}
- **Estado:** {{ $ticket->estado->nombre ?? 'N/A' }}
- **Prioridad:** {{ $ticket->prioridad->nombre ?? 'N/A' }}

**Descripción:**
{{ $ticket->descripcion }}

<x-mail::button :url="route('supporthub')">
Ver en la Plataforma
</x-mail::button>

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
