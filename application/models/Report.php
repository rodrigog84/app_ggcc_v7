<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Model
*
* Version: 2.5.2
*
* Author:  Ben Edmunds
* 		   ben.edmunds@gmail.com
*	  	   @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Last Change: 3.22.13
*
* Changelog:
* * 3-22-13 - Additional entropy added - 52aa456eef8b60ad6754b31fbdcc77bb
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Report extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
	}



	public function consolidado_mensual_adm($idcomunidad){

		// se muestran periodos publicados
		$queryQuestion = $this->db->query("
						select * from (select
						p.id, p.mes, p.anno, 
						(select monto from gc_ggcc_comunidad where idcomunidad = " . $idcomunidad ." and idperiodo = p.id and tipo = 'D' limit 1) as deuda,
						(select monto from gc_ggcc_comunidad where idcomunidad = " . $idcomunidad ." and idperiodo = p.id and tipo = 'FR' limit 1) as reserva,
						(select sum(dp.monto) as monto from gc_deuda_propiedad dp
						inner join gc_propiedad pr on dp.idpropiedad = pr.id
						where pr.idcomunidad = " . $idcomunidad ." and dp.idperiodo = p.id) as individual,
						pe.publica
						from gc_periodo as p
						inner join gc_periodo_estado as pe on p.id = pe.idperiodo
						where pe.idcomunidad = " . $idcomunidad ."
						and pe.publica is not null
						order by p.anno desc, p.mes desc
						limit 12) as consolidado order by anno, mes asc");
		return $queryQuestion->result_array();
	}


	public function gc_mensual_prop($idcomunidad,$idpropiedad){

		$queryQuestion = $this->db->query("
						select * from (select
						p.id, p.mes, p.anno, gp.monto, gp.abonado, pe.publica
						from gc_periodo as p
						inner join gc_periodo_estado as pe on p.id = pe.idperiodo
						left join gc_ggcc_propiedad gp on p.id = gp.idperiodo and gp.idpropiedad = " . $idpropiedad . "
						where pe.idcomunidad = " . $idcomunidad ."
						and pe.publica is not null
						order by p.anno desc, p.mes desc
						limit 12) as deuda order by anno, mes asc");
		return $queryQuestion->result_array();
	}	




	public function gc_comunidades_activas(){

		$queryQuestion = $this->db->query("
						select * from (
						select p.mes, p.anno, count(pe.idcomunidad) as numcomunidad  from gc_periodo_estado pe	
						inner join gc_periodo p on pe.idperiodo = p.id 
						where p.inicial = 0 
						group by pe.idperiodo
						order by p.anno desc, p.mes desc
						limit 12) as comunidades order by anno, mes asc");
		return $queryQuestion->result_array();
	}	


	public function get_lecturas_individuales($idcuenta=null,$mes=null,$anno=null){

		$cuentas_data = $this->db->select('c.id , c.formapago, p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, c.abonado, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion, pe.mes, pe.anno ')
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_periodo pe','c.idperiodo = pe.id')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where("c.formapago = 'ci'")
						  ->where('pe.mes',$mes)
						  ->where('pe.anno',$anno)
		                  ->order_by('c.updated_at desc');

		$cuentas_data = is_null($mes) ? $cuentas_data : $cuentas_data->where('pe.mes',$mes);
		$cuentas_data = is_null($anno) ? $cuentas_data : $cuentas_data->where('pe.anno',$anno);

		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);
		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();

	}

	public function get_lectura_individuales_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('dp.id, p.numero , tdd.nombre as concepto, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto, dp.nombrearchivo, dp.descripcion, pes.idperiodo, dp.idpropiedad ')
						  ->from('gc_deuda_propiedad dp')
						  ->join('gc_propiedad p','dp.idpropiedad = p.id')
						  ->join('gc_periodo pe','dp.idperiodo = pe.id')
						  ->join('gc_tipo_deuda_detalle tdd','dp.idtipodeudadetalle = tdd.id')
						  ->join('gc_periodo_estado pes','pe.id = pes.idperiodo and pes.idcomunidad = '.$this->session->userdata('comunidadid'))
						  ->where('p.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('tdd.idtipodeuda = 1') // sólo servicios básicos
						  ->where('dp.idcuenta is not null')
		                  ->order_by('dp.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('dp.id',$idcuenta);  

		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}



	public function get_intereses_mensuales($mes=null,$anno=null){

		$query = $this->db->query('select p.numero, dp.descripcion, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto from gc_deuda_propiedad dp 
								inner join gc_propiedad p on dp.idpropiedad = p.id
								inner join gc_periodo pe on dp.idperiodo = pe.id
								where p.idcomunidad = ' . $this->session->userdata('comunidadid') . '
								and dp.idtipodeudadetalle = 12 
								and pe.mes = ' . $mes . ' and pe.anno = ' . $anno . '
								order by LPAD(lower(p.numero), 10,0) asc');

		return $query->result();		
	}

	public function get_ajustes_mensuales($mes=null,$anno=null){

		$query = $this->db->query('select p.numero, dp.descripcion, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto from gc_deuda_propiedad dp 
								inner join gc_propiedad p on dp.idpropiedad = p.id
								inner join gc_periodo pe on dp.idperiodo = pe.id
								where p.idcomunidad = ' . $this->session->userdata('comunidadid') . '
								and dp.idtipodeudadetalle = 8 
								and pe.mes = ' . $mes . ' and pe.anno = ' . $anno . '
								order by LPAD(lower(p.numero), 10,0) asc');

		return $query->result();		
	}


	public function get_multas_mensuales($mes=null,$anno=null){

		$query = $this->db->query('select p.numero, dp.descripcion, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto from gc_deuda_propiedad dp 
								inner join gc_propiedad p on dp.idpropiedad = p.id
								inner join gc_periodo pe on dp.idperiodo = pe.id
								where p.idcomunidad = ' . $this->session->userdata('comunidadid') . '
								and dp.idtipodeudadetalle = 7
								and pe.mes = ' . $mes . ' and pe.anno = ' . $anno . '
								order by LPAD(lower(p.numero), 10,0) asc');

		return $query->result();		
	}



	public function get_cuotas_especiales_mensuales($mes=null,$anno=null){

		$query = $this->db->query('select p.numero, dp.descripcion, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto from gc_deuda_propiedad dp 
								inner join gc_propiedad p on dp.idpropiedad = p.id
								inner join gc_periodo pe on dp.idperiodo = pe.id
								where p.idcomunidad = ' . $this->session->userdata('comunidadid') . '
								and dp.idtipodeudadetalle = 9
								and pe.mes = ' . $mes . ' and pe.anno = ' . $anno . '
								order by LPAD(lower(p.numero), 10,0) asc');

		return $query->result();		
	}




	public function get_ingresos_mensuales($mes=null,$anno=null){

		$this->db->select('i.id , p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, i.nrodocumento, date_format(i.fecdocumento,"%d/%m/%Y") as fecdocumento, i.fecdocumento as fecdocumento_sformat, d.id as idconcepto, d.nombre as concepto, i.monto, date_format(i.fecvencimiento,"%d/%m/%Y") as fecvencimiento, i.nombrearchivo, i.updated_at, i.descripcion, i.tipoingreso, i.habilitagasto, date_format(c.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion, tipoingreso ',false)
						  ->from('gc_ingresos i')
						  ->join('gc_proveedor p','i.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','i.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','i.idtipodoctrib = tdt.id','left')
						  ->join('gc_cartola_caja c','c.idingreso = i.id','left')
						  ->where('i.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('month(i.fecdocumento)',$mes)
						  ->where('year(i.fecdocumento)',$anno)
						  ->group_by('i.id')
		                  ->order_by('i.fecdocumento asc');
		$query = $this->db->get();

		return $query->result();		
	}


	public function get_cuentas_sin_cobro($mes=null,$anno=null){

		$cuentas_data = $this->db->select('c.id , c.idproveedor, c.idtipodeudadetalle, c.formapago, if(c.idproveedor is null,"cargo","cuenta") as tipocuenta, if(c.nombreproveedor is not null,c.nombreproveedor,p.nombre) as proveedor, c.idtipodoctrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, c.monto, c.abonado, c.saldo, 	date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.descripcion ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id','left')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.formapago','sc')
						  ->where('month(c.fecdocumento)',$mes)
						  ->where('year(c.fecdocumento)',$anno)
		                  ->order_by('p.nombre asc');

		$query = $this->db->get();
		return $query->result();		                  
	}


	public function get_cuentas_espacios_comunes($mes=null,$anno=null){

		$cuentas_data = $this->db->select('dp.id, p.numero , tdd.nombre as concepto, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto, dp.nombrearchivo, dp.descripcion, dp.idpropiedad  ')
						  ->from('gc_deuda_propiedad dp')
						  ->join('gc_propiedad p','dp.idpropiedad = p.id')
						  ->join('gc_periodo pe','dp.idperiodo = pe.id')
						  ->join('gc_tipo_deuda_detalle tdd','dp.idtipodeudadetalle = tdd.id')
						  ->where('p.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('tdd.idtipodeuda = 10')
						  ->where('pe.mes',$mes)
						  ->where('pe.anno',$anno)						  
		                  ->order_by('dp.updated_at desc');

		$query = $this->db->get();
		return $query->result();		                  
	}


	public function get_cobro_gasto_comun($mes=null,$anno=null){

		$this->load->model('admin');
		$periodo = $this->admin->get_periodo_by_mes($mes,$anno);

		if(isset($periodo->id)){
			$this->load->model('payment');
			$propiedades = $this->payment->get_propiedad_by_periodo_full($periodo->id);
			return $propiedades;
		}else{

			return array();
		}
                  
	}			



	public function get_egresos_by_periodo($fechadesde,$fechahasta){

		$this->db->select('lp.id, lp.folio, "" as idpropiedad, if(lp.idprotesto is null,if(lp.paguesea="",c.glosa,concat("Pago de Cuentas de Condominio. ",lp.paguesea)),concat("Protesto de Documento en Movimiento P",lpad((select folio from gc_listado_pagos where id = lp.idprotesto limit 1),5,"0")))  as proveedor, lp.created_at AS fecha,  DATE_FORMAT(lp.fechapago, "%d/%m/%Y") AS fechapago_format, lp.fechapago AS fechapago, lp.fechaconciliacion AS fechaconciliacion, lp.monto, lp.created_at, lp.protesto, lp.cheque, lp.activo, c.id as idcaja ',false)
						  ->from('gc_listado_pagos lp')
						  ->join('gc_cartola_pagos cp','lp.id = cp.idlistado')
						  ->join('gc_cuenta cu','cp.idcuenta = cu.id')
						  ->join('gc_proveedor p','cu.idproveedor = p.id','left')						  
						  ->join('gc_cartola_caja c','cp.id = c.idpago')
						  ->where('lp.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where("lp.fechapago between '" . $fechadesde . "' and '". $fechahasta . "'")
						  ->group_by('lp.id')
		                  ->order_by('lp.fechapago asc');
		$query = $this->db->get();
		#echo $this->db->last_query(); exit;
		return $query->result();		
	}


	public function get_ranking_morosos($top = 8){

		$query = $this->db->query("select p.id, p.numero, p.responsable, p.saldo_publicado as saldo,
										(select count(gp.id) as cantidad
										from gc_ggcc_propiedad as gp
										inner join gc_periodo as per on gp.idperiodo = per.id
										inner join gc_periodo_estado as pe on per.id = pe.idperiodo and pe.idcomunidad = '" . $this->session->userdata('comunidadid') . "'
										where gp.idpropiedad = p.id
										and pe.publica is not null
										and gp.saldo > 0) as cuentas_impagas
 										from
										gc_propiedad as p
										where p.idcomunidad = '" . $this->session->userdata('comunidadid') . "'
										and p.active = 1
										order by p.saldo 
										desc
										limit ". $top);

		return $query->result();		
	}	



	public function get_resumen_medios_pago(){

		$this->db->select('fp.nombre, sum(la.monto) as monto',false)
						  ->from('gc_listado_abonos la')
						  ->join('gc_forma_pago fp','la.idformapago = fp.id')
						  ->where('la.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('la.activo',1)
						  ->group_by('la.idformapago')
		                  ->order_by('fp.nombre asc');
		$query = $this->db->get();
		#echo $this->db->last_query(); exit;
		return $query->result();		
	}




	public function get_cartola_propietario($idcomunidad,$idpropiedad){

		// se muestran periodos publicados
		$queryQuestion = $this->db->query("SELECT 	fec_ingreso
								,Cargo
								,Abono
								,Descripcion
					FROM 		(
								SELECT 	CAST(e.publica AS DATE)  AS fec_ingreso
											,g.monto AS Cargo
											,0 AS Abono
											,CASE WHEN p.mes = 1 AND p.anno = 2010 THEN 'Gasto Común Saldo Inicial'
													ELSE CONCAT('Gasto Común ',LPAD(CAST(p.mes AS char(30)),2,'0'),'/',CAST(p.anno AS char(30))) 
											END AS Descripcion
								FROM 		gc_ggcc_propiedad g
								INNER JOIN 	gc_periodo p ON g.idperiodo = p.id
								INNER JOIN 	gc_periodo_estado e ON p.id = e.idperiodo AND e.idcomunidad = '" . $idcomunidad . "'   AND e.publica IS NOT null
								WHERE 	g.idpropiedad = '" . $idpropiedad . "'
								AND 		g.monto > 0
								union
								SELECT 	CAST(l.created_at AS DATE) AS fec_ingreso
											,0 AS Cargo
											,l.monto AS Abono
											,CONCAT('Pago por ',f.nombre,', Folio: ',CAST(l.folio AS CHAR(10)),', Pagado el día: ',CAST(l.fechapago AS CHAR(10))) AS Descripcion
								FROM 		gc_listado_abonos l
								INNER JOIN gc_forma_pago f ON l.idformapago = f.id
								WHERE 		l.idpropiedad = '" . $idpropiedad . "'
								AND 		l.activo = 1
								)b
					ORDER BY fec_ingreso desc");	
		return $queryQuestion->result_array();
	}

}



