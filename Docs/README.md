--------------------------- RAÍCES ------------------------------------

Raíces – Plataforma KM 0 para cooperativa agrícola

Aplicación web desarrollada como proyecto final del ciclo Desarrollo de Aplicaciones Web (DAW).

Raíces es una plataforma online para la gestión y venta de productos agrícolas de kilómetro 0, orientada a cooperativas locales. Permite a los clientes realizar pedidos y a los administradores gestionar productos, pedidos, usuarios y finanzas.

------- Objetivo del proyecto------

Conectar productores locales con consumidores, facilitando:

-Gestión de productos

-Control de stock

-Realización de pedidos

-Gestión de estados de pedidos

-Panel de administración

-Control básico de finanzas

-El proyecto está enfocado a una posible implantación en Fuerteventura.

 -----Arquitectura del proyecto----------

La aplicación sigue una arquitectura separada en:

public/ → Frontend accesible (HTML, JS, Tailwind, Flowbite)

backend/ → Lógica de negocio (PHP 8.4, PDO)

database/ → Script SQL de creación de base de datos

entities/ → Clases del dominio

repositories/ → Acceso a datos

utils/ → Gestión de sesión y utilidades

Se utiliza arquitectura tipo:

Patrón Repository

Separación frontend/backend

Control de acceso por sesión (rol admin / cliente)

------ Tecnologías utilizadas--------

PHP 8.4

MySQL / MariaDB

PDO

JavaScript (Fetch API)

TailwindCSS

Flowbite

HTML5

Git / GitHub

-------- Seguridad-----------------

Autenticación con sesiones PHP

Control de acceso por rol

Validación y sanitización de datos

Protección de endpoints administrativos

------Estado del proyecto-----

Proyecto en desarrollo activo.

Pendiente de mejora:

Modularización completa de componentes

Separación mediante variables de entorno (.env)

Optimización de panel de finanzas

Trazabilidad avanzada

Cambiar estados de tabla pedidos

crear perfil de cliente

------Contexto académico-------

Proyecto desarrollado para el módulo de:

Ciclo Formativo de Grado Superior en Desarrollo de Aplicaciones Web.

Autor

Javi Hipólito
Proyecto: Raíces – 2026