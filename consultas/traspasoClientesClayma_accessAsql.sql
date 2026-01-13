begin try

delete from [gestionGrupoCibeles].[dbo].clientesClayma


INSERT INTO [gestionGrupoCibeles].[dbo].clientesClayma (codigo_saldo, codigo, subcliente, nombre_empresa, nombre_franqueo, DIRECCION, LOCALIDAD, PROVINCIA, codigo_postal, nif_subcliente, NIF, comercial, FORMA_PAGO, tipo_listado, tipo_factura, FECHA_ALTA, provision_inicial, conductor, pais, activo, dias_de_pago)
SELECT t1.[codigo saldo], t1.codigo, t1.subcliente, t1.NOMBRE_EMP, t1.NOMBRE_FRANQ, t1.DIRECCION, t1.LOCALIDAD, t1.PROVINCIA, t1.CODPOSTAL, t1.[nif subcliente], t1.NIF, t1.CATEGORIA, t1.FORMA_PAGO, t1.TIpoListado, t1.TipoFactura, t1.FECHA_ALTA, t1.PROVISION_ACTUAL, t1.conductor, t1.pais, t1.activo, t1.[dias de pago]
FROM [ACCESS_CLAYMA_21]...[CLIENTES MAESTRO] as t1 


UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idComercial = 12 WHERE comercial = 'ALEJANDRA';
UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idComercial = 10 WHERE comercial = 'ALEJANDRO' or comercial = 'JANO';
UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idComercial = 3 WHERE comercial = 'ALFONSO';
UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idComercial = 6 WHERE comercial = 'ANGEL';
UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idComercial = 4 WHERE comercial = 'CARLOS';
UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idComercial = 1 WHERE comercial = 'RAUL';
UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idComercial = 11 WHERE comercial = 'THIERRY';
UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idFormaPago = 2
WHERE forma_pago = '30 D F/F' 
or  forma_pago = '30 DFF'
or  forma_pago = '30 DIAS'
or  forma_pago = '30 DIAS F FACT'
or  forma_pago = '30 DIAS F.F.'
or  forma_pago = '30 DIAS F.FAC'
or  forma_pago = '30 DIAS F/F'
or  forma_pago = '30 DIAS F/FACT'
or  forma_pago = '30 dias fec fact'
or  forma_pago = '30 DIAS FECHA'
or  forma_pago = '30 DIAS FECHA FAC'
or  forma_pago = '30 DIAS FECHA FACT'
or  forma_pago = '30 DIAS FECHA FACTURA'
or  forma_pago = '30 dias fecha factura RBO DOMICILIADO'
or  forma_pago = '30 DIAS FECHA FRA'
or  forma_pago = '30 DIAS FECHA RECOGIDA'
or  forma_pago = '30 DIAS FEHCA FACTURA'
or  forma_pago = '30 DIAS FF'
or  forma_pago = '30 F F'
or  forma_pago = '30DIAS FECHA FACT'
or  forma_pago = 'A 90 DIAS LOS DIAS 30'
or  forma_pago = 'TRANS. 30 F.F.'
or  forma_pago = 'TRANSF 30 D F FACT'
or  forma_pago = 'TRANSF 30 DIAS'
or  forma_pago = 'TRANSF 30 DIAS FACT'
or  forma_pago = 'TRANSF. 30 DIAS F.F.'
or  forma_pago = 'TRANSFERENCIA 30 DIAS F.F.'
or  forma_pago = 'TRANSFERENCIA 30 DIAS FECHA FACTURA'
or  forma_pago = 'TRASF. 30 DIAS F.F.';

UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idFormaPago = 3
WHERE forma_pago = '60' 
or   forma_pago = '60 DIAS' 
or   forma_pago = '60 DIAS F.F.' 
or   forma_pago = '60 DIAS F/FACT' 
or   forma_pago = '60 DIAS FECHA FACT' 
or   forma_pago = '60 DIAS FECHA FACT PAGARE' 
or   forma_pago = '60 DIAS FECHA FACTURA' 
or   forma_pago = 'TRANSF 60 DIS FECHA FACT' 
or   forma_pago = 'TRANSF. 60 DIAS F.F.' 
or   forma_pago = 'TRNSF 60 DIAS F/FACT';

UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idFormaPago = 4
WHERE forma_pago = 'ANTICIPADO' 
or   forma_pago = 'ANTICIPADO SIEMPRE MAL PAGADOR' 
or   forma_pago = 'no activar OJO TIENE DEUDA ANTICIPADO TODO' 
or   forma_pago = 'OJO POR ANTICIPADO' 
or   forma_pago = 'OJO POR ANTICIPADO TODO' 
or   forma_pago = '60 DIAS FECHA FACT PAGARE' 
or   forma_pago = '60 DIAS FECHA FACTURA' 
or   forma_pago = 'POR ANTICIPADO SIEMPRE';

UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idFormaPago = 1 WHERE idFormaPago is null;
UPDATE [gestionGrupoCibeles].[dbo].clientesClayma SET idFormaPagoFranqueo = 1;

UPDATE t1
SET t1.fac_idPeriodo = 1
FROM [gestionGrupoCibeles].[dbo].[clientesClayma] t1
INNER JOIN [ACCESS_CLAYMA_21]...[datos generales para facturacion] t2
ON t2.cod_cliente = t1.codigo 
WHERE t2.[periodo de facturacion]='Mensual' or 
t2.[periodo de facturacion]='Quincenal';

UPDATE t1
SET t1.fac_idPeriodo = 2
FROM [gestionGrupoCibeles].[dbo].[clientesClayma] t1
INNER JOIN [ACCESS_CLAYMA_21]...[datos generales para facturacion] t2
ON t2.cod_cliente = t1.codigo 
WHERE t2.[periodo de facturacion]='Especial'

UPDATE t1
SET t1.fac_idProvisionFondos = 1
FROM [gestionGrupoCibeles].[dbo].[clientesClayma] t1
INNER JOIN [ACCESS_CLAYMA_21]...[datos generales para facturacion] t2
ON t2.cod_cliente = t1.codigo 
WHERE t2.[Provision de Fondos Fija]='Fija'

UPDATE t1
SET t1.fac_idProvisionFondos = 2
FROM [gestionGrupoCibeles].[dbo].[clientesClayma] t1
INNER JOIN [ACCESS_CLAYMA_21]...[datos generales para facturacion] t2
ON t2.cod_cliente = t1.codigo 
WHERE t2.[Provision de Fondos Fija]='Variable' or t2.[Provision de Fondos Fija]='Descontar' 

UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 1 WHERE dias_de_pago = 0;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 14 WHERE dias_de_pago = 5;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 4 WHERE dias_de_pago = 10;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 5 WHERE dias_de_pago = 15;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 6 WHERE dias_de_pago = 20;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 15 WHERE dias_de_pago = 25;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 2 WHERE dias_de_pago = 30;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 8 WHERE dias_de_pago = 35;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 16 WHERE dias_de_pago = 40;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 9 WHERE dias_de_pago = 45;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 10 WHERE dias_de_pago = 55;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 3 WHERE dias_de_pago = 60;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 11 WHERE dias_de_pago = 70;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 12 WHERE dias_de_pago = 75;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 13 WHERE dias_de_pago = 90;
UPDATE [gestionGrupoCibeles].[dbo].[clientesClayma] SET idDiasDePago = 17 WHERE dias_de_pago = 180;



update t1
set t1.fac_cuotaRecogida = t2.[Cuota de Recogida]
, t1.fac_porCientoNoBonificable = t2.[cobro 15% de No Bonificable]
, t1.fac_otrosConceptosFijos = t2.[Otros Conceptos Fijos]
, t1.fac_importeFijoOtrosConcepto = t2.[Importe Fijo Otros Conceptos]
, t1.fac_cobroUnitarioEnvio = t2.[c_unitario]
FROM [gestionGrupoCibeles].[dbo].[clientesClayma] t1
INNER JOIN [ACCESS_CLAYMA_21]...[datos generales para facturacion] t2
on t2.cod_cliente = t1.codigo


update [gestionGrupoCibeles].[dbo].[clientesClayma]
set fac_cuotaRecogida = fac_cuotaRecogida/100,
fac_importeFijoOtrosConcepto = fac_importeFijoOtrosConcepto/100,
fac_cobroUnitarioEnvio = fac_cobroUnitarioEnvio/100


update [gestionGrupoCibeles].[dbo].[clientesClayma]
set correoDiario = 1
where fac_idPeriodo = 1


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


/*delete [gestionGrupoCibeles].[dbo].[clientesContactos]


insert into [gestionGrupoCibeles].[dbo].[clientesContactos]
		([idCliente]
      ,[idSexo]
      ,[nombre]
      ,[apellidos]
      ,[departamento]
      ,[cargo]
      ,[telefono]
      ,[movil]
      ,[email]
      ,[comentario]

	  )
	  SELECT
		[COD-CLI]
      ,  case when [Sexo]='H' or sexo='Hombre' then 1 
			when sexo = 'M' or sexo = 'Mujer' then 2
			else 3 end AS sexo
      ,[Nombre]
      ,[Apellidos]
      ,[Departamento]
      ,[Cargo]
      ,[tel]
      ,[movil]
      ,[email]
      ,[comentario]
      
  FROM [ACCESS_BBDD_COMP_CIBELES]...[CLIENTES Contactos]
GO*/



update [gestionGrupoCibeles].[dbo].[clientesClayma]
set nuestraCuenta = 'ES42 2100 1945 2202 0006 7880 CAIXESBBXXX
ES38 0081 5626 8100 0120 9927 BSABESBBXXX'

GO

delete [gestionGrupoCibeles].[dbo].[clientesContactosClayma]
delete [gestionGrupoCibeles].[dbo].[clientesObservacionesClayma]
GO


