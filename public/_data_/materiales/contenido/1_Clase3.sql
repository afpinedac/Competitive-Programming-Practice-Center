
------------Clase Numero 3 Consultas avanzadas -------------------


--Creación de Tabla Departamento
CREATE TABLE departamento 
(
	codigo INTEGER PRIMARY KEY,
	nombre VARCHAR2(30) NOT NULL
);

--Insertar Datos en Departamentos
INSERT INTO departamento VALUES(1,'Ventas');
INSERT INTO departamento VALUES(2,'Diseño');
INSERT INTO departamento VALUES(3,'Publicidad');
INSERT INTO departamento VALUES(4,'Recursos Humanos');
INSERT INTO departamento VALUES(5,'Desarrollo');

--Creación de Tabla Empleado
CREATE TABLE empleado 
(
	codigo INTEGER PRIMARY KEY,
	nombre VARCHAR2(30) NOT NULL, 
	salario FLOAT NOT NULL, 
	departamento REFERENCES departamento NOT NULL,
	jefe  REFERENCES empleado
);

--Insertar Empleados.
INSERT INTO empleado VALUES(1,'Juan',1000,1,NULL);
INSERT INTO empleado VALUES(2,'Daniel',2000,1,1);
INSERT INTO empleado VALUES(3,'Miguel',3000,1,1);
INSERT INTO empleado VALUES(4,'Cata',800,1,2);
INSERT INTO empleado VALUES(5,'Andres',500,2,NULL);
INSERT INTO empleado VALUES(6,'James',600,2,5);
INSERT INTO empleado VALUES(7,'Camilo',700,3,NULL);
INSERT INTO empleado VALUES(8,'Fernando',1500,3,7);
INSERT INTO empleado VALUES(9,'Alex',2000,3,8);
INSERT INTO empleado VALUES(10,'Jose',300,4,NULL);
INSERT INTO empleado VALUES(11,'Pedro',200,4,10);
INSERT INTO empleado VALUES(12,'Liliana',1500,4,11);
INSERT INTO empleado VALUES(13,'Samuel',200,4,12);

---Contar Empleados por departamentos-----
SELECT d.nombre,COUNT(emp.departamento) Nempleados
FROM departamento d LEFT OUTER JOIN empleado emp 
ON d.codigo = emp.departamento 
GROUP BY d.nombre;

---Sumar Salario por departamentos-----
SELECT d.nombre,NVL(SUM(emp.salario),0) Salario_empleados
FROM departamento d LEFT OUTER JOIN empleado emp 
ON d.codigo = emp.departamento 
GROUP BY d.nombre;

--Mostrar los departamentos con más de dos empleados
SELECT d.nombre,COUNT(emp.departamento) 
FROM departamento d LEFT OUTER JOIN empleado emp 
ON d.codigo = emp.departamento 
GROUP BY d.nombre
HAVING COUNT(emp.departamento) > 2;

--Empleado que más gane
SELECT emp.nombre 
FROM empleado emp 
WHERE emp.salario = (SELECT Max(SALARIO) FROM empleado);

--Departamento que gaste menos de 2300
SELECT d.nombre
FROM departamento d LEFT OUTER JOIN empleado emp 
ON d.codigo = emp.departamento 
GROUP BY d.nombre 
HAVING NVL(SUM(salario),0) <=2200;

--Seleccionar Los empleados que ganan más que Fernando
SELECT * FROM empleado 
WHERE salario> ALL(SELECT salario FROM empleado WHERE nombre = 'Fernando' ) AND salario < ALL(SELECT salario FROM empleado WHERE nombre = 'Fernando' );

--Seleccionar los 5 empleados que más ganan
SELECT * FROM (SELECT nombre FROM empleado ORDER BY salario DESC) 
where ROWNUM<6;

--Seleccionar los empleados que ganan mas que Fernando, menos Miguel y trabajan en el departamento 1
SELECT * FROM empleado 
WHERE salario> ALL(
	SELECT salario FROM empleado 
	WHERE nombre = 'Fernando' ) 
AND salario <ALL(
	SELECT salario FROM empleado 
	WHERE nombre = 'Miguel'
	) 
and departamento = 1;

--Seleccionar empleados que ganen mas que el empleado que el empleado que gana menos del departamento 1
SELECT nombre FROM empleado
WHERE salario>ALL(
	SELECT MIN(salario) FROM empleado 
	WHERE departamento = 1
);

--Exist
SELECT d.nombre FROM departamento d 
WHERE EXISTS(
	SELECT e.departamento FROM empleado e 
	WHERE e.departamento = d.codigo 
	group by e.departamento 
	having count(e.departamento)>2
);