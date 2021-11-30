# Musguillo Loquillo: Proyecto para TC1004B
## Objetivo
Implementar herramientas del Internet de las Cosas para el monitoreo, cuidado y conservación del musgo.   

## Contenido
Consta de dos archivos *.ino* estos sirven para obtener las lecturas del sensor DTH11 usando la placa de desarrollo NodeMCU, mandarlas a sus respectivas bases de datos alojadas en Fibase, el archivo *prototipo* se usa para la parte del monitoreo en tiempo real y el archivo *SQLFireBase* tiene el proposito de exportar un *json* e importarse a un MySQL para generar el archivo *sqlfirebase.csv*. Mediante el documento *.js* que extrae la información contenida en la base de datos de Firebase orientada al tiempo real y para hacer el analisis de los datos historicos se hace por medio de un *index.php*, por ultimo el documento *html*  se encarga de mandar a llamar estos datos para su visualización en un entorno web.

## Autores:
* Ana Karen López Baltazar A01707750.
* Cristian Leilael Rico Espinosa A01707023.
* Ricardo Nunez Alanis A01703259.
* Olivia Araceli Morales Quezada A01707173.
