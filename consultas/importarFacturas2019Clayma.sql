delete [gestionGrupoCibeles].[dbo].[facturasClayma2019]

INSERT INTO [gestionGrupoCibeles].[dbo].[facturasClayma2019]
           ([numero]
           ,[cliente]
           ,[ot]
           ,[aPagar]
           ,[fecha]
           ,[Pagada]
           ,[total]
           ,[importe]
           ,[sinIva]
           ,[iva]
           ,[formaPago]
           ,[fechaPago]
          )

  select numero, [nombre_emp], [orden de trabajo], CAST(expr25 as decimal(18,2))/100, fecha, pagada, CAST([total pesetas] as decimal(18,2))/100 as total, importe, CAST([total sin iva] as decimal(18,2))/100, CAST(iva as decimal(18,2))/100, [forma de pago],  [fecha pago]
from [ACCESS_CLAYMA_19]...[C_Facturas de Contabilidad] order by numero