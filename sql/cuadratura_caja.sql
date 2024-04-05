SELECT 	*
FROM 	gc_comunidad 
WHERE 	id = 239



select id, folio, idpropiedad, DATE_FORMAT(fecha, "%d/%m/%Y") AS fecha, DATE_FORMAT(fechapago, "%d/%m/%Y") AS fechapago_format, fechapago, DATE_FORMAT(fechaconciliacion, "%d/%m/%Y") AS fechaconciliacion, monto, glosa, tipo_movimiento, created_at,  protesto, null as idingreso, 0 as monto_listado, cheque, activo, idcaja from (
							select la.id, la.folio, la.idpropiedad, la.created_at AS fecha, la.fechapago AS fechapago, la.fechaconciliacion AS fechaconciliacion, la.monto, if(la.idprotesto is null,concat("Abono GC de Propiedad # ",p.numero),concat("Protesto de Documento en Movimiento A",lpad((select folio from gc_listado_abonos where id = la.idprotesto),5,"0"))) as glosa, "a" as tipo_movimiento, la.created_at, la.protesto, la.cheque, la.activo, c.id as idcaja from gc_listado_abonos la
							left join gc_cartola_propiedad cp on la.id = cp.idlistado
							left join gc_cartola_caja c on cp.id = c.idabono
							inner join gc_propiedad p on la.idpropiedad = p.id
							where la.idcomunidad = 239 and la.activo = 1 and la.fechaconciliacion is not null
							group by la.id
							union
							select lp.id, lp.folio, "" as idpropiedad, lp.created_at AS fecha, lp.fechapago AS fechapago, lp.fechaconciliacion AS fechaconciliacion, lp.monto*(-1) as monto, if(lp.idprotesto is null,if(lp.paguesea="",c.glosa,concat("Pago de Cuentas de Condominio. ",lp.paguesea)),concat("Protesto de Documento en Movimiento P",lpad((select folio from gc_listado_pagos where id = lp.idprotesto limit 1),5,"0")))  as glosa, "p" as tipo_movimiento, lp.created_at, lp.protesto, lp.cheque, lp.activo, c.id as idcaja from gc_listado_pagos lp
							left join gc_cartola_pagos cp on lp.id = cp.idlistado
							left join gc_cartola_caja c on cp.id = c.idpago
							where lp.idcomunidad = 239 and lp.activo = 1 and lp.fechaconciliacion is not null
							group by lp.id
							union 
							select cc.id, cc.id as folio, "" as idpropiedad, cc.created_at AS fecha, cc.fechapago, cc.fechaconciliacion, cc.monto, cc.glosa, "i" as tipo_movimiento, cc.created_at, cc.protesto, "" as cheque, cc.activo, cc.id as idcaja from gc_cartola_caja cc left join gc_ingresos i on cc.idingreso = i.id
							where cc.idcomunidad = 239 and (cc.idingreso is not null or cc.exingreso = 1) and (i.tipoingreso <> "na" OR i.tipoingreso IS NULL) and cc.activo = 1 and cc.fechaconciliacion is not null
							) as tmp
							order by fechapago desc, created_at desc, id desc