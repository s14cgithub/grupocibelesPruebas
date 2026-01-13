if (exists (select * from [gestionGrupoCibeles].[dbo].[franqueoTipos] where fecha = convert(varchar(10),getDate(),23) and comprobado=0))
begin
	
	delete [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES MAESTRO dia anterior]
	insert into [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES MAESTRO dia anterior]
	select * from [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES MAESTRO]


	UPDATE [gestionGrupoCibeles].[dbo].[franqueoTipos] SET ot = ' ' WHERE ot = '';

	INSERT INTO [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES TRANSITORIO] ( codigo_cliente, extension, fecha, tipos, unidades, empleado, [precio extranjero], gramos )
	SELECT idCliente, ot, convert(varchar(10),getDate(),105), tipo, unidades, 1, 0.00, gramos
	FROM [gestionGrupoCibeles].[dbo].[franqueoTipos]
	WHERE tipo <> '1' and
	fecha =  convert(varchar(10),getDate(),105);

	INSERT INTO [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES TRANSITORIO] ( codigo_cliente, extension, fecha, tipos, unidades, empleado, [precio extranjero], gramos )
	SELECT idCliente, ot, convert(varchar(10),getDate(),105), tipo, unidades, 1, importe*100/unidades, gramos
	FROM [gestionGrupoCibeles].[dbo].[franqueoTipos]
	WHERE tipo = '1' and
	fecha =  convert(varchar(10),getDate(),105);

	update [gestionGrupoCibeles].[dbo].[franqueoTipos]
	set comprobado = 1
	where fecha =  convert(varchar(10),getDate(),105);

	update [gestionGrupoCibeles].[dbo].[franqueo]
	set comprobado = 1
	where fecha =  convert(varchar(10),getDate(),105);



end
else
begin 
	select 'Error: no hay registros para pasar'
end