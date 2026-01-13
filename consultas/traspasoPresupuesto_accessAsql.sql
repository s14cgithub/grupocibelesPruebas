begin try

delete from [gestionGrupoCibeles].[dbo].[presupuestos]
INSERT INTO [gestionGrupoCibeles].[dbo].[presupuestos] ( PRESUPUESTO, CLIENTE, PERSONA, DIRECCION, Poblacion, CP, PAGO, notaCibeles, CANTIDAD, Fecha, PEDCLI, fechaAceptacion, fechaCompromiso, fechaTerminado, FACTURA, detallada ) select presupuesto, cliente, persona, direccion, poblacion, cp, pago, [nota cibeles], cantidad, fecha, pedcli, [fe-aceptado], [fe-compromiso], [fe-terminado], factura, detallada  from [ACCESS_PRESUPUESTOS]...[MAESTRO PRESUPUESTOS]

delete from [gestionGrupoCibeles].[dbo].[presupuestos detalle]

INSERT INTO [gestionGrupoCibeles].[dbo].[presupuestos detalle] (id, PRESUPUESTO, CONCEPTO, GRUPO, UNIDADES, PRECIO, DESCRIPCION, notaCibeles) select id, presupuesto, concepto, grupo, unidades, precio, descripcion, [nota cibeles] from [ACCESS_PRESUPUESTOS]...[MAESTRO PRESUPUESTOS DETALLE]


END TRY

begin CATCH

  SELECT  
        ERROR_NUMBER() AS ErrorNumber  
        ,ERROR_SEVERITY() AS ErrorSeverity  
        ,ERROR_STATE() AS ErrorState  
        ,ERROR_PROCEDURE() AS ErrorProcedure  
        ,ERROR_LINE() AS ErrorLine  
        ,ERROR_MESSAGE() AS ErrorMessage 
END CATCH 
GO