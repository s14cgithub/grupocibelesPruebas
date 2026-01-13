delete [gestionGrupoCibeles].[dbo].[facturasCorreos2020]

INSERT INTO [gestionGrupoCibeles].[dbo].[facturasCorreos2020]
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

   select [numero oficial], [nombre_emp], especial, CAST(apagar as decimal(18,2))/100, fecha, pagada, CAST([SumaDeSumaDeExpr8] as decimal(18,2))/100 as total, 0, CAST([neto] as decimal(18,2))/100, CAST(iva as decimal(18,2))/100, [forma de pago],  '', [ot_bbdd]
from [ACCESS_CB1_20]...[CO_MAESTRO FACTURAS CORREOS] order by numero