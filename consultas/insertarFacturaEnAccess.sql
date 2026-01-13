
DECLARE @var1 int;  

select @var1 = temporal1 from [gestionGrupoCibeles].[dbo].[temporal_traspaso]

insert into [ACCESS_cb1_21]...[R_MAESTRO DE FACTURAS DE CLIENTES] ([NOMBRE_EMP], [Orden de Trabajo], [Otros Conceptos Fijos], expr20, expr21, expr25, Fecha, [A_pagar], numFacturaWeb)
select cliente, presupuesto, descripcion, precioNeto*100, iva*100, precioTotal*100, fecha, aPagar*100, @var1  FROM [gestionGrupoCibeles].[dbo].[facturas] 
where numeroTemporal =  @var1

 
DECLARE @var2 int;  


select @var2 = numero from [ACCESS_cb1_21]...[R_MAESTRO DE FACTURAS DE CLIENTES] where numFacturaWeb=@var1

update [gestionGrupoCibeles].[dbo].[facturas] set numero = @var2 where numeroTemporal = @var1

update [gestionGrupoCibeles].[dbo].[temporal_traspaso] set numero = @var2