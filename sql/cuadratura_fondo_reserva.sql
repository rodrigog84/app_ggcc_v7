SELECT 	*
FROM 	gc_listado_abonos
ORDER BY id DESC
LIMIT 10



SELECT 	*
FROM 	gc_comunidad 
WHERE 	id = 239


select 		date_format(c.created_at,"%d/%m/%Y") as fecha , c.glosa, c.id, c.monto, c.saldo, cu.nombrearchivo
						  from 			gc_cartola_fondo_reserva c
						  left join 	gc_cuenta cu on c.idcuenta = cu.id
						  where 		c.idcomunidad = 239
						  and 			c.activo = 1 and 			(idcuenta in  (
																			select 		idcuenta
																			from 			gc_cartola_fondo_reserva
																			where 		idcomunidad =  239
																			and 		idcuenta is not null
																			group by		idcuenta 
																			having		sum(monto) <> 0
																			)
														 or idcuenta is null)
						  order by 		c.created_at desc, c.id desc 