
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Livewire](https://img.shields.io/badge/Livewire-48B02C?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com/)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![Node.js](https://img.shields.io/badge/Node.js-43853D?style=for-the-badge&logo=node.js&logoColor=white)](https://nodejs.org/)

---

## 📖 Descripción

**SupportHub** es una plataforma web integral desarrollada para **CGR de México** como parte de un proyecto de transformación digital orientado a optimizar la gestión de incidencias y solicitudes de soporte técnico interno.

La aplicación centraliza el flujo de vida completo de las incidencias (registro, asignación, seguimiento y resolución), facilitando la interacción fluida entre los colaboradores y el departamento de Tecnologías de la Información (TI). Asimismo, integra un microservicio de notificaciones automáticas vía **WhatsApp**, garantizando actualizaciones en tiempo real sobre el estado de las solicitudes.

Su diseño web adaptativo (*Responsive Design*) asegura una **experiencia de usuario fluida e intuitiva desde cualquier dispositivo**, ya sean teléfonos inteligentes, tablets, laptops o computadoras de escritorio.

<img width="922" height="450" alt="Captura de pantalla 2026-07-29 152855" src="https://github.com/user-attachments/assets/4486024f-a8f8-4160-8ea4-b42e0ad245c7" />

---

## ✨ Funcionalidades Principales

- 📱 **Diseño Multiplataforma & Adaptativo:** Interfaz totalmente optimizada y fluida para celulares, tablets y desktops.
- 🎫 **Gestión Integral de Tickets:** Registro, clasificación, asignación y resolución de incidencias.
- 👥 **Control de Acceso (RBAC):** Administración centralizada de usuarios, roles y permisos específicos.
- 📌 **Seguimiento y Priorización:** Trazabilidad en tiempo real del progreso de cada solicitud.
- 💬 **Módulo de Comunicación Directa:** Hilos de interacción y respuestas dentro de cada ticket entre usuario y agente.
- 📲 **Notificaciones Automáticas vía WhatsApp:** Alertamiento inmediato sobre creación, cambios de estado y respuestas.
- 📊 **Dashboard Administrativo:** Indicadores técnicos y métricas para el monitoreo general del área de TI.
- 📂 **Historial & Log de Actividades:** Auditoría detallada del ciclo de vida de cada ticket.
- ⚡ **Reactividad e Interactividad:** Actualización dinámica de vistas sin recargar la página gracias a Livewire.

---

## 📱 Compatibilidad y Diseño Adaptativo

SupportHub fue diseñado bajo el paradigma **Mobile-First / Fully Responsive**, lo que permite a los técnicos y colaboradores interactuar con el sistema sin barreras de hardware:

| Dispositivo | Optimización |
| :--- | :--- |
| 📱 **Smartphones** | Interfaz táctil adaptada, navegación simplificada e integración fluida con notificaciones de WhatsApp. |
| 📱 **Tablets** | Vistas de paneles adaptables para revisiones rápidas y gestión sobre la marcha. |
| 💻 **Laptops / Desktops** | Panel de control multinivel completo, reportes extendidos y gestión avanzada de configuraciones. |
<img width="728" height="1600" alt="image" src="https://github.com/user-attachments/assets/aed7a5b3-29f6-42dd-8708-17e262775175" />

---

## 🛠️ Tecnologías Utilizadas

### Backend & Frameworks
- **PHP 8.2** / **Laravel 10+**
- **Laravel Livewire** (Reactividad en el servidor)

### Frontend & UI/UX
- **Blade Templating Engine**
- **Tailwind CSS** (Diseño responsivo y utilitario)
- **JavaScript (ES6+)** / HTML5 / CSS3

### Base de Datos
- **PostgreSQL**

### Microservicio de Notificaciones
- **Node.js** & **Express.js**
- **Baileys** (API Web de WhatsApp)

### Herramientas & Entorno de Desarrollo
- **Git** & **GitHub**
- **Composer** (PHP) / **NPM** (Node)
- **Visual Studio Code**

---

## 🏗️ Arquitectura del Sistema

El sistema utiliza una arquitectura desacoplada para la comunicación y la gestión de procesos:

1. **Aplicación Web Principal (Laravel + PostgreSQL):** Gestiona la lógica de negocio, autenticación, control de roles, almacenamiento persistente de datos e interfaz dinámica.
2. **Servicio de Alertamiento (Node.js + Baileys):** Escucha eventos desencadenados por el backend para procesar y enviar mensajes automáticos a través de la API Web de WhatsApp.

---

## 🏢 Entorno Empresarial

Desarrollado para **CGR de México**, empresa del sector automotriz especializada en la fabricación de componentes mecatrónicos de alta precisión, como parte de sus iniciativas de mejora continua, digitalización de procesos e innovación en infraestructura TI.

---

## 🎯 Objetivo

Optimizar los tiempos de atención y respuesta del departamento de TI mediante la centralización de requerimientos, automatización de canales de comunicación directos y la entrega de una herramienta accesible **desde cualquier lugar y dispositivo**.

---

## 📌 Características Destacadas

- 📱 **Multiplataforma y 100% Responsivo** (móviles, tablets y PC).
- ⚡ **Reactividad en tiempo real** mediante la integración de Livewire.
- 🗄️ **Base de datos relacional robusta** sobre PostgreSQL.
- 📲 **Integración transparente con WhatsApp Web API**.
- 🔐 **Seguridad y control de usuarios basado en roles**.
- 🚀 **Escalabilidad arquitectónica** lista para futuras integraciones.
