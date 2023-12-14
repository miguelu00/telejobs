# MANUAL DE API - TELEJOBS

Se usará la siguiente URL:
    http://telejobs.net/Repositories/API.php

Método por defecto de Return:
<b>JSON</b>

Lenguaje usado:
PHP + mySQL DB (con PHPMyAdmin)

    Según el método de HTTP que usemos, habrá diferencias tanto en las respuestas que recibamos de la API, como de los datos que debemos darle.

# PUT
    Habrá que pasarle

"tabla" --> representa la TABLA sobre la que queremos <b>actualizar</b> datos.

"CAMPOS_SET" --> representará el nombre del campo, y el VALOR en sí que queremos updatear.

"WHERE" --> será la especificación de los campos que queremos cambiar


# POST
    Le pasaremos

"tabla" --> la tabla sobre la que queremos INSERTAR datos/resources

"CAMPOS" [opcional] --> si lo especificamos, limitará los valores que vamos a insertar sobre los campos especificados


# GET
    Tendrá como parámetros

"tabla" --> La TABLA sobre la que vamos a realizar una consulta de datos.

"CAMPOS" [opcional] --> los campos específicos que queremos consultar. Si no lo especificamos, recibiremos TODOS los datos de la tabla indicada

"WHERE" [opcional] --> Si queremos limitar la consulta para que cumpla una o varias condiciones lógicas/mySQL


# PATCH
    Como parámetros

"tabla" --> TABLA sobre la que se actualizará un dato concreto

"CAMPOS_SET" --> los campos que queremos actualizar (mediante formato clave-valor [CAMPO1=valor, CAMPO2=valor2] ...)

"WHERE" [opcional] --> Limitar la actualización para que cumpla una o varias condiciones lógicas/mySQL


# DELETE
    Como parámetros tendrá

"tabla" --> La TABLA sobre la que BORRAREMOS los datos.

"WHERE" [opcional] --> Limitar la consulta para que cumpla una o varias condiciones lógicas/mySQL. Si no se especifica, se devolverá un error 400 - BAD REQUEST

*Para poder usar el método DELETE en tablas importantes ['DEMANDANTES', 'EMPRESAS', 'CANDIDATURAS', 'OFERTAS_TRAB'], deberemos emplear una clave de API de ADMIN. En caso contrario, recibiremos un error 403 - FORBIDDEN*