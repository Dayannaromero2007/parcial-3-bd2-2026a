# Notas de Normalización — Proyecto ParkControl

Este documento detalla el proceso de diseño y normalización de la base de datos `control_parqueadero_opt` aplicado hasta la Tercera Forma Normal (3FN), garantizando la integridad de los datos y evitando redundancias.

---

## 1. Primera Forma Normal (1FN)

**Regla teórica:** 
Para cumplir con la 1FN, una tabla debe tener una clave primaria definida, no debe contener grupos repetitivos y todos sus atributos deben ser atómicos (es decir, cada celda debe contener un único valor indivisible).

**Aplicación en ParkControl:**
* Todas las entidades del modelo cuentan con una clave primaria única que identifica cada registro, como `idVehiculo` en la tabla `vehiculo` o `idIngresos` en la tabla `ingresos`[cite: 11].
* Los atributos son completamente atómicos[cite: 11]. Por ejemplo, en la tabla `clientes_mensuales`, los campos `nombre`, `telefono` y `correo` almacenan un solo dato por celda[cite: 11]. No existen campos separados por comas que contengan múltiples números de teléfono para un mismo cliente.

---

## 2. Segunda Forma Normal (2FN)

**Regla teórica:** 
Para cumplir con la 2FN, la tabla debe estar en 1FN y todos los atributos que no forman parte de la clave primaria deben depender funcionalmente y de forma completa de dicha clave primaria. Esto elimina las dependencias parciales.

**Aplicación en ParkControl:**
* El diseño utiliza claves primarias simples (identificadores autoincrementales de un solo campo) en lugar de claves compuestas[cite: 11]. Al tener claves simples, se elimina automáticamente el riesgo de dependencias parciales.
* En la tabla `vehiculo`, atributos descriptivos como `placa`, `marca` y `color` dependen en su totalidad de la clave primaria `idVehiculo`[cite: 11]. Ninguno de estos datos depende solo de una parte del identificador.
* En la tabla `ingresos`, atributos como `fecha_hora_ingreso` y `total_pago` dependen completamente del identificador único del movimiento (`idIngresos`)[cite: 11].

---

## 3. Tercera Forma Normal (3FN)

**Regla teórica:** 
Para cumplir con la 3FN, la tabla debe estar en 2FN y no deben existir dependencias transitivas. Esto significa que ningún atributo no clave debe depender de otro atributo que tampoco es clave. Todos los atributos deben depender únicamente de la clave primaria.

**Aplicación en ParkControl:**
* **Separación de Tarifas y Tipos de Vehículo:** Esta es la principal aplicación de la 3FN en el modelo. Se crearon las tablas `tipo_vehiculo` y `tarifa` por separado[cite: 11]. Si los valores monetarios se hubieran guardado en `tipo_vehiculo`, existiría una dependencia transitiva: el precio dependería del nombre del tipo de vehículo, y no de la clave primaria.
* Al aislar la tabla `tarifa`, atributos como `precio_hora` y `precio_mensual` dependen única y exclusivamente de `idTarifa`, y se relacionan con el tipo de vehículo mediante la llave foránea `Tipo_vehiculo_idTipo_vehiculo`[cite: 11]. Esto permite actualizar los precios sin alterar la definición del vehículo.
* **Cálculo del Total:** En la tabla `ingresos`, se almacena el campo `total_pago` como resultado final del servicio[cite: 11]. No se almacenan en esta tabla los precios base por hora utilizados para calcular ese total, ya que hacerlo generaría redundancia y una dependencia transitiva hacia la tabla de tarifas.