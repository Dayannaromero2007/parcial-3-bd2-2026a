# 🅿️ ParkControl — Sistema de Gestión de Parqueadero

Aplicativo web diseñado para el control de ingresos y salidas de vehículos en un parqueadero con capacidad para 100 espacios, gestión de clientes mensuales y cálculo automático de tarifas.

## Características
- **Control de Activos:** Registro en tiempo real de ingresos y salidas.
- **Gestión Mensual:** Asignación de cupos con fechas de vigencia.
- **Tarifas Dinámicas:** Configuración independiente por tipo de vehículo (automovil, automovil electrico, moto, etc.).
- **Reportes:** Visualización de ingresos y ocupación actual.
- **Facturación e Impresión:** Generación automática de comprobantes de servicio (tickets) detallando la hora de entrada, salida, duración exacta y el total a pagar, adaptado para impresión
- **Validaciones:** Control estricto de capacidad máxima (100 espacios).

## 🛠 Tecnologías Utilizadas
- **Backend:** PHP 8.x con PDO para interacción segura con la base de datos.
- **Base de Datos:** MySQL.
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla) con diseño responsive.

##  Instrucciones de Instalación
1. **Clonar/Descargar:** Descarga el proyecto en tu carpeta de servidor local (`htdocs` si usas XAMPP).
2. **Base de Datos:**
   - Abre `phpMyAdmin`.
   - Crea una base de datos llamada `control_parqueadero_opt`.
   - Importa el archivo `01-crear-control-parqueadero-bd.sql` ubicado en la raíz.
3. **Conexión:**
   - Verifica el archivo `conexion.php`. Asegúrate de que las credenciales (`root` / `dayanna08`) coincidan con las de tu servidor MySQL local.
4. **Ejecución:**
   - Abre tu navegador y dirígete a `http://localhost/nombre_carpeta_proyecto/index.php`.

##  Requisitos Funcionales Cumplidos
- **RF1 - RF2:** Registro de entradas y salidas con cálculo automático.
- **RF3:** Configuración de tarifas por tipo.
- **RF4:** Gestión de mensualidades.
- **RF5:** Validación de disponibilidad de espacios.
- **RF6:** Reportes financieros.
- **RF7:** Monitoreo de vehículos activos.

## Desarrollado por
Dayanna Romero Parra — Estudiante de Ingeniería de Sistemas e Informática, Unipaz.