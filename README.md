# OptiComp — Sistema de Gestión de Tickets y Componentes PC

> Proyecto académico — Arquitectura y Diseño de Software  
> Fundación Escuela Superior de Comercio — FESC  
> Docente: Robinson Damián Gómez Sánchez

---

## Integrantes

| Nombre | Rol |
|--------|-----|
| Diego Alejandro Mosquera Caicedo | Desarrollador Backend / Arquitectura |
| Julián Daniel Erazo Garzón | Desarrollador Full Stack / Documentación |

---

## Descripción

**OptiComp** es un MVP (Minimum Viable Product) para gestión de tickets de soporte técnico y catálogo de componentes de PC. Está construido sobre arquitectura **MVC pura en PHP 8.2**, sin frameworks externos, aplicando los principios de **PSR-12**, **Clean Code** y el **catálogo de refactoring de Martin Fowler**.

---

## Requisitos Previos

| Componente | Versión mínima |
|------------|---------------|
| PHP | 8.1+ |
| MySQL / MariaDB | 10.4+ |
| Servidor web | Apache (Laragon / XAMPP) con `mod_rewrite` activo |
| Extensiones PHP | `pdo`, `pdo_mysql`, `curl`, `simplexml` |

---

## Instalación paso a paso

### 1. Clonar / copiar el proyecto

```bash
# Clonar desde GitHub
git clone https://github.com/tu-usuario/OptiComp.git

# O copiar la carpeta OptiComp a:
# Laragon:  C:\laragon\www\OptiComp\
# XAMPP:    C:\xampp\htdocs\OptiComp\
```

### 2. Importar la base de datos

```bash
# Desde línea de comandos MySQL
mysql -u root -p < database/db_opticomp.sql

# O desde phpMyAdmin:
# 1. Crear base de datos: db_opticomp
# 2. Importar el archivo: database/db_opticomp.sql
```

### 3. Configurar la conexión

Editar el archivo `config/database.php`:

```php
return [
    'host'     => 'localhost',
    'dbname'   => 'db_opticomp',   // nombre de la BD
    'username' => 'root',           // usuario MySQL
    'password' => '',               // contraseña (vacía en Laragon/XAMPP por defecto)
];
```

### 4. Ajustar BASE_URL

Editar `public/index.php`:

```php
define('BASE_URL', 'http://localhost/OptiComp/public');
```

### 5. Verificar mod_rewrite (Apache)

El archivo `public/.htaccess` gestiona el enrutamiento. Asegúrese de que `mod_rewrite` esté activo en `httpd.conf` y que `AllowOverride All` esté configurado para el directorio.

### 6. Acceder al sistema

```
http://localhost/OptiComp/public/
```

---

## Estructura del Proyecto

```
OptiComp/
├── app/
│   ├── Controllers/        # Controladores MVC
│   ├── Models/             # Entidades de negocio
│   ├── Repositories/       # Acceso a datos (Repository Pattern)
│   ├── Services/           # Lógica de negocio / integración externa
│   ├── Helpers/            # Validación, utilidades
│   └── Views/              # Plantillas PHP (layouts, tickets, productos, clientes)
│       └── layouts/        # Header y footer reutilizables
├── config/                 # Configuración de BD y aplicación
├── database/               # Script SQL con estructura y seeders
├── logs/                   # Archivo audit.log (generado automáticamente)
├── public/                 # Punto de entrada público
│   ├── index.php           # Front Controller
│   ├── .htaccess           # Rewrite rules
│   └── css/app.css         # Estilos del sistema
└── routes/
    └── web.php             # Definición de rutas
```

---

## Credenciales de prueba (sustentación)

| Campo | Valor |
|-------|-------|
| BD | `db_opticomp` |
| Usuario MySQL | `root` |
| Contraseña | *(vacía)* |
| Cliente ID para tickets | `1` o `2` |
| URL base | `http://localhost/OptiComp/public/` |

---

## Módulos del sistema

| Módulo | URL | Descripción |
|--------|-----|-------------|
| Inicio | `/` | Panel de control |
| Tickets | `/tickets` | CRUD de tickets de soporte |
| Productos | `/productos` | Catálogo de componentes PC |
| Clientes | `/clientes` | Listado y sincronización de clientes |
| API XML | `/api/xml` | Endpoint REST-XML (POST) |
| Sync API | `/clientes/sincronizar` | Importa clientes desde JSONPlaceholder |

---

## API XML — Ejemplo de uso

**Endpoint:** `POST /api/xml`  
**Content-Type:** `text/xml`

### Registrar producto
```xml
<?xml version="1.0" encoding="UTF-8"?>
<request>
    <operacion>registrarProducto</operacion>
    <datos>
        <nombre>Corsair 16GB DDR5</nombre>
        <categoria_id>2</categoria_id>
    </datos>
</request>
```

### Consultar productos
```xml
<?xml version="1.0" encoding="UTF-8"?>
<request>
    <operacion>consultarProductos</operacion>
</request>
```

---

## Técnicas de Refactoring aplicadas (Guía 11)

| Técnica | Descripción | Ubicación |
|---------|-------------|-----------|
| **Extract Method** | `buildDsn()`, `fetchFromApi()`, `formatLine()`, `handleRegistrar()` | Conexion, ServiceConnector, Logger, ApiXmlService |
| **Rename Variable/Method** | `$db→$dbName`, `log()→logEvent()`, `actualizarEstadoYSalida()→updateEstado()` | Conexion, Logger, TicketRepository |
| **Move Method** | SQL movido de `Core/TicketDAO` a `Repositories/TicketRepository` | TicketRepository, ClienteRepository |
| **Eliminate Duplication** | Clase `Validador` duplicada en Core y Data → unificada en Helpers | Validador.php |
| **Fix Code Smell** | SQL con concatenación directa (SQL injection) → PDO preparado | ApiXmlService |

---

## Auditoría de calidad (análisis tipo SonarCloud)

| Métrica | Estado antes | Estado después |
|---------|-------------|----------------|
| Duplicación de código | Alta (2 Validador.php) | Eliminada |
| SQL injection | Presente en api_xml.php | Corregido con PDO |
| Conflictos Git en código | Presentes en 3 archivos | Resueltos |
| Responsabilidad de capas | Mezcladas (SQL en Services) | Separadas correctamente |
| PSR-12 | Incumplido | Aplicado en archivos refactorizados |

---

## Logs de auditoría

El sistema genera trazabilidad automática en `logs/audit.log`:

```
[2026-05-29 10:00:00] [OPERACION] Ticket #5 creado vía formulario web.
[2026-05-29 10:01:00] [OPERACION] Sincronización: 10 clientes importados desde API externa.
[2026-05-29 10:02:00] [ERROR_SISTEMA] Error crear ticket: ...
```

---

## Notas para sustentación

- El archivo `logs/audit.log` se crea automáticamente la primera vez que se genera un evento.
- Para probar la sincronización de clientes, hacer clic en **"Sincronizar API"** en la sección Clientes (requiere conexión a internet).
- La API XML puede probarse con Postman o cURL apuntando a `/api/xml`.
