delete [gestionGrupoCibeles].[dbo].[facturas2018]

INSERT INTO [gestionGrupoCibeles].[dbo].[facturas2018]
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
           ,[otBBDD])

  select numero, [nombre_emp], [orden de trabajo], CAST(expr25 as decimal(18,2))/100, fecha, pagada, CAST([total pesetas] as decimal(18,2))/100 as total, importe, CAST([total sin iva] as decimal(18,2))/100, CAST(iva as decimal(18,2))/100, [forma de pago],  [fecha pago], [ot_bbdd]
from [ACCESS_CB1_18]...[C_Facturas de Contabilidad] order by numero