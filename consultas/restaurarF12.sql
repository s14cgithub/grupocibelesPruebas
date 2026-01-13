delete [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES TRANSITORIO]
delete [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES MAESTRO]
insert into [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES MAESTRO]
select * from [ACCESS_CBPRODUCCION]...[INTRODUCIR FRANQUEO CLIENTES MAESTRO dia anterior]

update [gestionGrupoCibeles].[dbo].[franqueoTipos]
set comprobado = 0
where fecha =  convert(varchar(10),getDate(),105);

update [gestionGrupoCibeles].[dbo].[franqueo]
set comprobado = 0
where fecha =  convert(varchar(10),getDate(),105);