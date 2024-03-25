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

class Contabilidad_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->lang->load('ion_auth');
		$this->load->helper('format');
	}


	public function get_saldos_cuentas($idcuenta = null,$tipo_cuenta = null){

		$cuentas_data = $this->db->select('pc.id, tcc.codigo as codigo_tipo, tcc.nombre as nombre_tipo, pc.codigo, pc.nombre, tcc.tipo, ifnull(ci.valor,0) as valor, pc.edita', false)
						  ->from('gc_plan_cuentas pc')
						  ->join('gc_tipo_cuenta_contable tcc','pc.idtipo = tcc.id')
						  ->join('gc_comunidad_cuenta_saldo ci','pc.id = ci.idcuentacontable and ci.idcomunidad = ' . $this->session->userdata('comunidadid'),'left')
		                  ->order_by('pc.codigo asc');

		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('pc.id',$idcuenta);
		$cuentas_data = is_null($tipo_cuenta) ? $cuentas_data : $cuentas_data->where('tcc.tipo',$tipo_cuenta);
		$query = $this->db->get();
		$datos = is_null($idcuenta) ? $query->result() : $query->row();
		return $datos;		


	}


	public function get_saldos_cuentas_inic($idcuenta = null,$tipo_cuenta = null){

		$cuentas_data = $this->db->select('pc.id, tcc.codigo as codigo_tipo, tcc.nombre as nombre_tipo, pc.codigo, pc.nombre, tcc.tipo, ifnull(ci.valor,0) as valor, pc.edita', false)
						  ->from('gc_plan_cuentas pc')
						  ->join('gc_tipo_cuenta_contable tcc','pc.idtipo = tcc.id')
						  ->join('gc_comunidad_cuenta_inic ci','pc.id = ci.idcuentacontable and ci.idcomunidad = ' . $this->session->userdata('comunidadid'),'left')
		                  ->order_by('pc.codigo asc');

		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('pc.id',$idcuenta);
		$cuentas_data = is_null($tipo_cuenta) ? $cuentas_data : $cuentas_data->where('tcc.tipo',$tipo_cuenta);
		$query = $this->db->get();
		$datos = is_null($idcuenta) ? $query->result() : $query->row();
		return $datos;		


	}	

	public function put_saldos_cuentas($array_datos_cuentas){

		$datos_balances = $this->get_balances(); 
		foreach ($array_datos_cuentas as $key => $valor) {
			$info_cuenta = $this->get_saldos_cuentas($key);
			if(count($datos_balances) == 0 || $info_cuenta->edita == 1){ // sólo se actualizan datos si no existen balances o si la cuenta permite edición posterior

				$this->db->select('id, idcomunidad, idcuentacontable, valor')
								  ->from('gc_comunidad_cuenta_saldo ')
								  ->where('idcomunidad',$this->session->userdata('comunidadid'))
								  ->where('idcuentacontable',$key);
				$query = $this->db->get();				  
				$dato_saldo_inic = $query->result();
				if(count($dato_saldo_inic) > 0){ // si ya existe, se actualiza, sino se inserta
					$datos = array(
							'valor' => $valor
							);

					#SE ACTUALIZA SALDO DE LA CUENTA Y SALDO INICIAL
					$this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
					$this->db->where('idcuentacontable', $key);
					$this->db->update('gc_comunidad_cuenta_saldo',$datos);


					$this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
					$this->db->where('idcuentacontable', $key);
					$this->db->update('gc_comunidad_cuenta_inic',$datos);


				}else{
					$datos = array(
							'idcomunidad' => $this->session->userdata('comunidadid'),
							'idcuentacontable' => $key,
							'valor' => $valor,
							'created_at' => date("Y-m-d H:i:s")
							);
					$this->db->insert('gc_comunidad_cuenta_saldo',$datos);


					$this->db->insert('gc_comunidad_cuenta_inic',$datos);
				}
			}
 
		}
		
		return true;
	}


	public function get_balances($idperiodo = null){

		$data_balance = $this->db->select("b.idperiodo, p.mes, p.anno, b.corte, b.calculo, b.aprueba, (select sum(valor) from gc_detalle_balance where idperiodo = b.idperiodo and idcomunidad = " . $this->session->userdata('comunidadid') . " and tipo = 'DEBE') as debe, (select sum(valor) from gc_detalle_balance where idperiodo = b.idperiodo and idcomunidad = " . $this->session->userdata('comunidadid') . " and tipo = 'HABER') as haber")
					  ->from('gc_periodo_balance b')
					  ->join('gc_periodo p','b.idperiodo = p.id')
					  ->where('b.idcomunidad',$this->session->userdata('comunidadid'))
		              ->order_by('b.corte desc');
		$data_balance = is_null($idperiodo) ? $data_balance : $data_balance->where('p.id',$idperiodo);		              
		$query = $this->db->get();	
		return is_null($idperiodo) ? $query->result() : $query->row();
	}			



	public function get_periodos_validos(){

		$this->db->select("max(corte) as feccorte",false)
						  ->from('gc_periodo_balance')
		                  ->where('idcomunidad', $this->session->userdata('comunidadid'));
		$query = $this->db->get();
		$ultimo_periodo = $query->row();

		$data_periodos = $this->db->select("p.id, p.mes, p.anno, pe.genera, date_format(genera,'%d/%m/%Y') as genera_format ")
						  ->from('gc_periodo as p')
						  ->join('gc_periodo_estado as pe','p.id = pe.idperiodo')
						  ->join('gc_periodo_balance as pb','pe.idperiodo = pb.idperiodo and pe.idcomunidad = pb.idcomunidad','left')
		                  ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('pe.genera is not null')
		                  ->where('p.inicial = 0')
		                  ->where('pb.idperiodo is null');
		$data_periodos = is_null($ultimo_periodo->feccorte) ? $data_periodos : $data_periodos->where('pe.genera > ',$ultimo_periodo->feccorte);		              		                  
		$query = $this->db->get();



		return $query->result();

	}	



	public function get_asientos_contables_by_periodo($idperiodo = null){

		$data_balance = $this->db->select("ac.id, ac.idcomunidad, ac.idperiodo, ac.glosa, ac.fecmovimiento")
					  ->from('gc_asiento_contable ac')
					  ->where('ac.idperiodo',$idperiodo)
					  ->where('ac.idcomunidad',$this->session->userdata('comunidadid'))
		              ->order_by('ac.fecmovimiento asc');
		$query = $this->db->get();	
		return $query->result();
	}	

	public function get_activo_fijo_by_periodo($idperiodo = null){

		$data_balance = $this->db->select("id, idcuenta, monto")
					  ->from('gc_activo_fijo_balance')
					  ->where('idperiodo',$idperiodo)
					  ->where('idcomunidad',$this->session->userdata('comunidadid'))
		              ->order_by('created_at asc');
		$query = $this->db->get();	
		return $query->result();
	}		


	public function get_balances_pendientes($idperiodo = null){

		$balance_data = $this->db->select("pb.idperiodo, p.mes, p.anno, (select sum(valor) from gc_detalle_balance where idperiodo = pb.idperiodo and idcomunidad = " . $this->session->userdata('comunidadid') . " and tipo = 'DEBE') as debe, (select sum(valor) from gc_detalle_balance where idperiodo = pb.idperiodo and idcomunidad = " . $this->session->userdata('comunidadid') . " and tipo = 'HABER') as haber, date_format(pb.corte,'%d/%m/%Y') as corte, date_format(pb.calculo,'%d/%m/%Y %H:%i:%s') as calculo",false)
						  ->from('gc_periodo_balance as pb')
						  ->join('gc_periodo as p','p.id = pb.idperiodo')
		                  ->where('pb.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('pb.calculo is not null')
		                  ->where('pb.aprueba is null');

		$balance_data = is_null($idperiodo) ? $balance_data : $balance_data->where('pb.idperiodo',$idperiodo);  		                  
		$query = $this->db->get();
		return is_null($idperiodo) ? $query->result() : $query->row();

	}	


	public function get_balances_aprobados($idperiodo = null){

		$balance_data = $this->db->select("pb.idperiodo, p.mes, p.anno, (select sum(valor) from gc_detalle_balance where idperiodo = pb.idperiodo and idcomunidad = " . $this->session->userdata('comunidadid') . " and tipo = 'DEBE') as debe, (select sum(valor) from gc_detalle_balance where idperiodo = pb.idperiodo and idcomunidad = " . $this->session->userdata('comunidadid') . " and tipo = 'HABER') as haber, date_format(pb.corte,'%d/%m/%Y') as corte, date_format(pb.calculo,'%d/%m/%Y %H:%i:%s') as calculo, date_format(pb.aprueba,'%d/%m/%Y %H:%i:%s') as aprueba",false)
						  ->from('gc_periodo_balance as pb')
						  ->join('gc_periodo as p','p.id = pb.idperiodo')
		                  ->where('pb.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('pb.calculo is not null')
		                  ->where('pb.aprueba is not null')
		                  ->order_by('pb.aprueba','desc');

		$balance_data = is_null($idperiodo) ? $balance_data : $balance_data->where('pb.idperiodo',$idperiodo);  		                  
		$query = $this->db->get();
		return is_null($idperiodo) ? $query->result() : $query->row();

	}	


	public function get_cuentas_balance($idperiodo, $idcuenta = null,$tipo_cuenta = null){

		$balance_data = $this->db->select('pc.id, tcc.codigo as codigo_tipo, tcc.nombre as nombre_tipo, pc.codigo, pc.nombre, tcc.tipo, db.tipo, ifnull(db.valor,0) as valor, pc.edita, pc.manual', false)
						  ->from('gc_plan_cuentas pc')
						  ->join('gc_tipo_cuenta_contable tcc','pc.idtipo = tcc.id')
						  ->join('gc_detalle_balance db','pc.id = db.idcuentacontable','left')
						  ->where('db.idcomunidad',$this->session->userdata('comunidadid')) 
						  ->where('db.idperiodo',$idperiodo) 
		                  ->order_by('pc.codigo asc');

		$balance_data = is_null($idcuenta) ? $balance_data : $balance_data->where('pc.id',$idcuenta);
		$balance_data = is_null($tipo_cuenta) ? $balance_data : $balance_data->where('tcc.tipo',$tipo_cuenta);
		$query = $this->db->get();
		$datos = is_null($idcuenta) ? $query->result() : $query->row();
		return $datos;		


	}


	public function asientos_contables($idperiodo,$fec_corte,$idcuentacontable){


		switch ($idcuentacontable) {
			case 7:  #DOCUMENTOS POR RENDIR
				$query = $this->db->query("
								select monto, left(created_at,10) as fec_movimiento from
								(select c.id, c.idggcc, left(c.fecautoriza,10) as fecautoriza, (select max(left(created_at,10)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id) as fec_ultimo_pago, monto, created_at
								from gc_cuenta c
								where idcomunidad = " . $this->session->userdata('comunidadid') . "
								and saldo = 0
								and formapago = 'gc') as tmp
								where fec_ultimo_pago <= '" . $fec_corte ."'
								and (left(fecautoriza,10) > '" . $fec_corte ."' or idggcc is null)
								");

				$data_doctos =  $query->result();
				foreach ($data_doctos as $docto) {
					$data_insert = array(
							'idcomunidad' => $this->session->userdata('comunidadid'),
							'idperiodo' => $idperiodo,				
							'glosa' => 'Ingreso de cuentas proveedor',
							'fecmovimiento' => $docto->fec_movimiento
							);

					$this->db->insert('gc_asiento_contable',$data_insert);	

					$idasiento = $this->db->insert_id();
					$data_insert = array(
							'idasiento' => $idasiento,
							'idcuentacontable' => 7,
							'tipo' => 'DEBE',
							'valor' => $docto->monto
							);

					$this->db->insert('gc_detalle_asiento_contable',$data_insert);						

					$data_insert = array(
							'idasiento' => $idasiento,
							'idcuentacontable' => 12,
							'tipo' => 'HABER',
							'valor' => $docto->monto
							);

					$this->db->insert('gc_detalle_asiento_contable',$data_insert);											
				}
				break;
			
			default:
				break;
		}

	}


	public function generar_balance($periodo,$fec_corte){

		$this->db->trans_start();
		$fec_corte = substr($fec_corte,6,4)."-".substr($fec_corte,3,2)."-".substr($fec_corte,0,2);

		$this->load->model('payment');
		$this->load->model('account');
		$this->load->model('admin');


		#COMIENZA A INGRESAR LOS VALORES POR CUENTA

		#1.- BANCO (SALDO CONTABLE A LA FECHA DE CORTE)
		# CALCULO: SALDO CONTABLE ACTUAL - MOVIMIENTOS GENERADOS DESDE FECHA DE CALCULO

		$saldo_contable = $this->payment->get_saldo_contable($fec_corte);

		$data_ing_no_contabilizados = $this->get_sum_ingresos_no_contabilizados($periodo);


		$data_insert = array(
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'idperiodo' => $periodo,				
				'idcuentacontable' => 1,
				//'tipo' => $saldo_contable > 0 ? 'DEBE' : 'HABER',
				'tipo' => 'DEBE',
				'valor' => $saldo_contable + $data_ing_no_contabilizados->monto,
				'descripcion' => '',
				'created_at' => date("Y-m-d H:i:s")
				);


		$this->db->insert('gc_detalle_balance',$data_insert);	


		#2.- DOCUMENTOS EN CARTERA (ABONOS NO CONCILIADOS)
			 #MOSTRAR LISTADO DE CHEQUES


			$this->db->select('COALESCE(SUM(a.monto),0) as monto',false)
							  ->from('gc_listado_abonos a')
							  ->join('gc_propiedad p','a.idpropiedad = p.id')
			                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where("left(created_at,10) <= '" . $fec_corte ."'")
			                  ->where("(a.fechaconciliacion is null or a.fechaconciliacion > '" . $fec_corte . "')")
			                  ->where('activo = 1');
			$query = $this->db->get();
			$monto_docs_cartera = $query->row()->monto;		

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 2,
					'tipo' => 'DEBE',
					'valor' => $monto_docs_cartera,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);		


		#3.- Gastos Comunes por Cobrar Mes (Gastos comunes del periodo seleccionado)

			/*$this->db->select('COALESCE(SUM(gp.monto) + SUM(gs.monto),0) as monto',false)
							  ->from('gc_propiedad p')
							  ->join('gc_ggcc_propiedad gp','gp.idpropiedad = p.id and gp.idperiodo = ' . $periodo)
							  ->join('gc_ggcc_saldo gs','gs.idggcc = gp.id','left')
							  ->where('idcomunidad', $this->session->userdata('comunidadid'));*/

			$this->db->select('COALESCE(SUM(i.monto),0) as monto',false)
							  ->from('gc_propiedad p')
							  ->join('gc_ggcc_propiedad gp','gp.idpropiedad = p.id and gp.idperiodo = ' . $periodo)
							  ->join('gc_ggcc_item i','gp.id = i.idggcc')
							  ->where('idcomunidad', $this->session->userdata('comunidadid'))
							  ->where('i.iditem in (1,2)');

			$query = $this->db->get();
			$gc_por_cobrar = $query->row()->monto;		


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 3,
					'tipo' => 'DEBE',
					'valor' => $gc_por_cobrar,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);		



		#4.- INTERESES POR COBRAR MES (TOTAL DE INTERESES - AJUSTES DEL PERIODO)
				# SE OBTIENEN TODAS LAS MULTAS APLICADAS A PROPIETARIOS EN EL PERIODO

			$this->db->select('COALESCE(SUM(dp.monto),0) as monto',false)
							  ->from('gc_deuda_propiedad dp')
							  ->join('gc_propiedad p','dp.idpropiedad = p.id')
			                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where('dp.idtipodeudadetalle',12)
			                  ->where('dp.idperiodo',$periodo);

			$query = $this->db->get();
			$monto_intereses_mes = $query->row()->monto;	

			$this->db->select('COALESCE(SUM(dp.monto),0) as monto',false)
							  ->from('gc_deuda_propiedad dp')
							  ->join('gc_propiedad p','dp.idpropiedad = p.id')
			                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where('dp.idtipodeudadetalle',8)
			                  ->where('dp.idperiodo',$periodo);

			$query = $this->db->get();
			$monto_ajustes_mes = $query->row()->monto;	
			$monto_intereses_por_cobrar = ($monto_intereses_mes + $monto_ajustes_mes);
			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 4,
					'tipo' => 'DEBE',
					'valor' => $monto_intereses_por_cobrar,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);		


		#5.- OTRAS MULTAS (TOTAL DEL MULTAS DEL PERIODO)

			$this->db->select('COALESCE(SUM(dp.monto),0) as monto',false)
							  ->from('gc_deuda_propiedad dp')
							  ->join('gc_propiedad p','dp.idpropiedad = p.id')
			                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where('dp.idtipodeudadetalle',7)
			                  ->where('dp.idperiodo',$periodo);

			$query = $this->db->get();
			$monto_multas_mes = $query->row()->monto;		

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 19,
					'tipo' => 'DEBE',
					'valor' => $monto_multas_mes,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	

		#6.- OTRAS COBROS (TOTAL DEL CUOTAS ESPECIALES DEL PERIODO)

			$this->db->select('COALESCE(SUM(dp.monto),0) as monto',false)
							  ->from('gc_deuda_propiedad dp')
							  ->join('gc_propiedad p','dp.idpropiedad = p.id')
			                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where('dp.idtipodeudadetalle',9)
			                  ->where('dp.idperiodo',$periodo);

			$query = $this->db->get();
			$monto_otros_cobros = $query->row()->monto;	

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 20,
					'tipo' => 'DEBE',
					'valor' => $monto_otros_cobros,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	


		#7.- DEPOSITOS A PLAZOS Y FONDOS MUTUOS (MANUAL)
			$saldo_dep_plazo = $this->get_saldos_cuentas(18);

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 18,
					'tipo' => 'DEBE',
					'valor' => isset($saldo_dep_plazo->valor) ? $saldo_dep_plazo->valor : 0,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);			

		#8.- GASTOS COMUNES POR COBRAR MOROSOS (TODO EL SALDO ANTERIOR)

			$this->db->select('COALESCE(SUM(gs.monto),0) as monto',false)
							  ->from('gc_propiedad p')
							  ->join('gc_ggcc_propiedad gp','gp.idpropiedad = p.id and gp.idperiodo = ' . $periodo)
							  ->join('gc_ggcc_saldo gs','gs.idggcc = gp.id','left')
							  ->where('idcomunidad', $this->session->userdata('comunidadid'))
							  ->where('gs.monto > 0');
			$query = $this->db->get();
			$gcm_por_cobrar = $query->row()->monto;		


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 5,
					'tipo' => 'DEBE',
					'valor' => $gcm_por_cobrar,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);		


		#9.- FONDO FIJO (********)
			$saldo_fondo_fijo = $this->get_saldos_cuentas(6);


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 6,
					'tipo' => 'DEBE',
					'valor' => isset($saldo_fondo_fijo->valor) ? $saldo_fondo_fijo->valor : 0,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	


		#10.- DOCUMENTOS POR RENDIR (CUENTAS GC PAGADAS Y NO AUTORIZADAS)
			/*$query = $this->db->query("
							select COALESCE(SUM(monto),0) as monto from
							(select c.id, c.idggcc, left(c.fecautoriza,10) as fecautoriza, (select max(left(created_at,10)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id) as fec_ultimo_pago, monto
							from gc_cuenta c
							where idcomunidad = " . $this->session->userdata('comunidadid') . "
							and saldo = 0
							and formapago = 'gc') as tmp
							where fec_ultimo_pago <= '" . $fec_corte ."'
							and (left(fecautoriza,10) > '" . $fec_corte ."' or idggcc is null)
							");*/
			/*$query = $this->db->query("
							select COALESCE(SUM(monto),0) as monto from
							(select c.id, c.idggcc, left(c.fecautoriza,10) as fecautoriza, (select max(left(created_at,10)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id) as fec_ultimo_pago, monto
							from gc_cuenta c
							where idcomunidad = " . $this->session->userdata('comunidadid') . "
							and formapago = 'gc') as tmp
							where fec_ultimo_pago <= '" . $fec_corte ."' and 
							(left(fecautoriza,10) > '" . $fec_corte ."' or idggcc is null)
							");*/

			$query = $this->db->query("
							select COALESCE(SUM(monto),0) as monto from
							(select c.id, c.idggcc, left(c.fecautoriza,10) as fecautoriza, (select max(left(created_at,10)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id) as fec_ultimo_pago, monto
							from gc_cuenta c
							where idcomunidad = " . $this->session->userdata('comunidadid') . "
							and formapago = 'gc') as tmp
							where (left(fecautoriza,10) > '" . $fec_corte ."' or idggcc is null)
							");

			$monto_docs_rendir =  $query->row()->monto;


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 7,
					'tipo' => 'DEBE',
					'valor' => $monto_docs_rendir,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	

			$this->asientos_contables($periodo,$fec_corte,7);	


		#11.- BIENES Y EQUIPOS

			$cuentas = $this->account->get_activo_fijo_impago_by_id();

			$monto_bienes_equipos = 0;

			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
			$saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;			
			$monto_dep_acum = 0;
			foreach ($cuentas as $cuenta) {
				if($cuenta->vidautil != 0){  // se ingresó vida util

					if($cuenta->baja == 0){
						$monto_bien	= (int)$cuenta->depreciacion;
					}else{
						$monto_bien = (int)($cuenta->depreciacion*$cuenta->vidautilresidual); // se calcula la depreciacion
					}


					#$nueva_depacum = $monto_bien + $cuenta->depacum;
					
					if(($monto_bien + $cuenta->depacum) > $cuenta->monto){ #QUIERE DECIR QUE ME PASÉ
						$monto_bien = $cuenta->monto - $cuenta->depacum; 
					}
					$monto_dep_acum += $cuenta->depacum + $monto_bien;

					//$monto_bien = $cuenta->baja == 0 ? (int)$cuenta->depreciacion : (int)($cuenta->depreciacion*$cuenta->vidautilresidual); // se calcula la depreciacion

					//se inserta el activo fijo
					$data_insert_af = array(
							'idcomunidad' => $this->session->userdata('comunidadid'),
							'idperiodo' => $periodo,				
							'idcuenta' => $cuenta->id,
							//'monto' => $monto_bien,
							'monto' => $cuenta->monto,
							'created_at' => date("Y-m-d H:i:s")
							);

					$this->db->insert('gc_activo_fijo_balance',$data_insert_af);	

					//rebaja fondo de reserva
					/*$this->db->query("update gc_comunidad set 
																fondoreserva = fondoreserva - " . $monto_bien . "
																where id = " . $this->session->userdata('comunidadid'));	


					$data = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idcuenta' => $cuenta->id,
				      	'glosa' =>  'Depreciación de Activo Fijo.  Cuenta de '. $cuenta->proveedor,
				        'monto' => (-1)*$monto_bien,				      	
				        'saldo' =>  ($saldo_fondo_reserva_actual - $monto_bien),
				        'created_at' => date("Y-m-d h:i:s")
					);
					
					$saldo_fondo_reserva_actual -= $monto_bien;

					$this->db->insert('gc_cartola_fondo_reserva', $data);	*/	

					//rebaja vida util residual
					$sql_vida_util_residual = $cuenta->baja == 0 ? "vidautilresidual - 1" : "0";
					$this->db->query("update gc_cuenta set 
																vidautilresidual = " . $sql_vida_util_residual . ",
																depacum = depacum + " . $monto_bien . ",
																valorresidual = valorresidual - " . $monto_bien . "
																where id = " . $cuenta->id);


					$monto_bienes_equipos += $cuenta->monto;
				}

			}

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 8,
					'tipo' => 'DEBE',
					'valor' => $monto_bienes_equipos,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	

		#12.- DEPRECIACION ACUMULADA (************)



			$saldo_dep_acum = $this->get_saldos_cuentas(9);


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 9,
					'tipo' => 'DEBE',
					'valor' => isset($saldo_dep_acum->valor) ? $saldo_dep_acum->valor + $monto_dep_acum*(-1) : $monto_dep_acum*(-1),
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	

		#13.- PAGOS ANTICIPADOS PROPIETARIOS (TODO EL SALDO ANTERIOR POSITIVO)

			$this->db->select('COALESCE(SUM(gs.monto),0) as monto',false)
							  ->from('gc_propiedad p')
							  ->join('gc_ggcc_propiedad gp','gp.idpropiedad = p.id and gp.idperiodo = ' . $periodo)
							  ->join('gc_ggcc_saldo gs','gs.idggcc = gp.id','left')
							  ->where('idcomunidad', $this->session->userdata('comunidadid'))
							  ->where('gs.monto < 0');
			$query = $this->db->get();
			$monto_pago_anticipado = $query->row()->monto;		


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 10,
					'tipo' => 'HABER',
					'valor' => abs($monto_pago_anticipado),
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);		


		#14.- PROVISIONES (*********)

			$saldo_provisiones = $this->get_saldos_cuentas(11);

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 11,
					'tipo' => 'HABER',
					'valor' => isset($saldo_provisiones->valor) ? $saldo_provisiones->valor : 0,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	


		#15.- CUENTAS POR PAGAR (CUENTAS NO PAGADAS Y ACTIVAS)
			#FALTAN LOS SALDOS POSITIVOS DE PROPIETARIOS (VER SI ES NECESARIO VALIDAR QUE SEAN DEL PERIODO)

			/*$this->db->select('COALESCE(SUM(saldo),0) as monto',false)
							  ->from('gc_cuenta')
							  ->where('idcomunidad', $this->session->userdata('comunidadid'))
							  ->where('idggcc is not null')
			                  ->where('saldo > 0')			                  
			                  ->where('formapago','gc');
			$query = $this->db->get();
			$saldos_ctas = $query->row()->monto;		*/

			$query = $this->db->query("
							select COALESCE(SUM(saldo+pago_fuera_corte),0) as monto from
							(select c.id, c.idggcc, left(c.fecautoriza,10) as fecautoriza, (select max(left(created_at,10)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id) as fec_ultimo_pago, monto, if(c.idtipodoctrib=4,saldo*(-1),saldo) as saldo,
								(select if(c.idtipodoctrib=4,COALESCE(SUM(monto),0)*(-1),COALESCE(SUM(monto),0)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id and left(created_at,10) > '" . $fec_corte ."') as pago_fuera_corte
							from gc_cuenta c
							where idcomunidad = " . $this->session->userdata('comunidadid') . " and 
							left(c.created_at,10) <= '" . $fec_corte ."'
							and formapago = 'gc' and
							(c.active = 1 or left(c.fecdesactiva,10) > '" . $fec_corte ."')
							) as tmp
							where (fec_ultimo_pago > '" . $fec_corte ."' or abs(saldo) > 0)
							");		


			$monto_cuentas_pagar =  $query->row()->monto;

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 12,
					'tipo' => 'HABER',
					'valor' => $monto_cuentas_pagar,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);		

		#16.- INGRESOS NO IDENTIFICADOS

			

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 24,
					'tipo' => 'HABER',
					'valor' => $data_ing_no_contabilizados->monto,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	


		#17.- FONDO OPERACIONAL (*******)

			$saldo_fondo_op = $this->get_saldos_cuentas(13);

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 13,
					'tipo' => 'HABER',
					'valor' => isset($saldo_fondo_op->valor) ? $saldo_fondo_op->valor : 0,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	


		#18.- FONDOS DE RESERVA

			$fondo_reserva = $this->payment->get_fondo_reserva($fec_corte);


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 14,
					'tipo' => 'HABER',
					'valor' => $fondo_reserva,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	


		#19.- FONDOS DE RESERVA - OTROS

			$saldo_fr_otros = $this->get_saldos_cuentas(15);


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 15,
					'tipo' => 'HABER',
					'valor' => isset($saldo_fr_otros->valor) ? $saldo_fr_otros->valor : 0,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	




		#20.- FONDOS DE RESERVA - MULTAS


			$saldo_fr_multas = $this->get_saldos_cuentas(16);


			//$ultimo_periodo = $this->get_ultimo_balance($periodo);
			/*if(count($ultimo_periodo) == 0){
				$fondoreserva_otros = 0;
			}else{
				$fondoreserva_otros = isset($this->get_cuentas_balance($periodo,16)->valor) ? $this->get_cuentas_balance($periodo,15)->valor : 0;
			}*/
			

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 16,
					'tipo' => 'HABER',
					//'valor' => $fondoreserva_otros + $monto_multas_mes,
					'valor' => isset($saldo_fr_multas->valor) ? $saldo_fr_multas->valor + $monto_multas_mes : $monto_multas_mes,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	

		#21.- FONDOS DE RESERVA - INTERESES

			$saldo_fr_intereses = $this->get_saldos_cuentas(21);

			/*if(count($ultimo_periodo) == 0){
				$fondoreserva_intereses = 0;
			}else{
				$fondoreserva_intereses = isset($this->get_cuentas_balance($periodo,21)->valor) ? $this->get_cuentas_balance($periodo,21)->valor : 0;
			}*/
			

			
			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 21,
					'tipo' => 'HABER',
					//'valor' => $fondoreserva_intereses + $monto_intereses_por_cobrar,
					'valor' => isset($saldo_fr_intereses->valor) ? $saldo_fr_intereses->valor + $monto_intereses_por_cobrar : $monto_intereses_por_cobrar,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	


		#22.- FONDOS DE RESERVA - OTROS COBROS

			$saldo_fr_otros_cobros = $this->get_saldos_cuentas(22);

			/*if(count($ultimo_periodo) == 0){
				$fondoreserva_otros_cobros = 0;
			}else{
				$fondoreserva_otros_cobros = isset($this->get_cuentas_balance($periodo,22)->valor) ? $this->get_cuentas_balance($periodo,22)->valor : 0;
			}*/

			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 22,
					'tipo' => 'HABER',
					//'valor' => $fondoreserva_otros_cobros + $monto_otros_cobros,
					'valor' => isset($saldo_fr_otros_cobros->valor) ? $saldo_fr_otros_cobros->valor + $monto_otros_cobros : $monto_otros_cobros,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	



		#23.- EXCEDENTES ACUMULADOS

			$saldo_ex_acum = $this->get_saldos_cuentas(17);


			$ggcc_total_deuda = $this->payment->get_ggcc_by_periodo($periodo,'D');
			$ggcc_total_fr = $this->payment->get_ggcc_by_periodo($periodo,'FR');
			$ggcc_total = $ggcc_total_deuda + $ggcc_total_fr;
			$monto_ex_acum = $gc_por_cobrar - $ggcc_total;


			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 17,
					'tipo' => 'HABER',
					//'valor' => $gc_por_cobrar - $ggcc_total,
					'valor' => isset($saldo_ex_acum->valor) ? $saldo_ex_acum->valor + $monto_ex_acum : $monto_ex_acum,
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	

		#23.- DEPRECIACION ACTIVO FIJO (************)
			$data_insert = array(
					'idcomunidad' => $this->session->userdata('comunidadid'),
					'idperiodo' => $periodo,				
					'idcuentacontable' => 23,
					'tipo' => 'HABER',
					'valor' => isset($saldo_dep_acum->valor) ? $saldo_dep_acum->valor + $monto_dep_acum*(-1) : $monto_dep_acum*(-1),
					//'valor' => $monto_dep_acum*(-1),
					'descripcion' => '',
					'created_at' => date("Y-m-d H:i:s")
					);

			$this->db->insert('gc_detalle_balance',$data_insert);	



		$datos = array(
				'idperiodo' => $periodo,
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'corte' => $fec_corte,
				'calculo' => date("Y-m-d H:i:s")
				);
		$this->db->insert('gc_periodo_balance',$datos);		


		$this->db->trans_complete();
		return true;
	}


	public function aceptar_balance($idperiodo){
		$this->db->trans_start();
			$cuentas = $this->contabilidad->get_cuentas_balance($idperiodo); 

			#ACTUALIZA SALDOS
			foreach ($cuentas as $datos_cuenta) {

					$this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
					$this->db->where('idcuentacontable', $datos_cuenta->id);
					$this->db->update('gc_comunidad_cuenta_saldo',array('valor' => $datos_cuenta->valor));				
			}


			$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
			$this->db->where('idperiodo',$idperiodo);
			$this->db->update('gc_periodo_balance',array('aprueba' => date("Y-m-d H:i:s")));	
		$this->db->trans_complete();
		return 1;		
	}


	public function rechazar_balance($idperiodo){


		$this->db->trans_start();
		#obtengo balance del periodo para la comunidad (me aseguro que sea un periodo ya calculado y no aprobado)
		$balance = $this->get_balances_pendientes($idperiodo);

		if(!is_null($balance)){ // SÓLO REALIZA REVERSA EN CASO DE QUE EL PERÍODO CORRESPONDA

			#BORRAMOS EL DETALLE POR CUENTA
			$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
			$this->db->where('idperiodo',$idperiodo);
			$this->db->delete('gc_detalle_balance');		

			$asientos = $this->get_asientos_contables_by_periodo($idperiodo);

			#BORRAMOS EL DETALLE DE LOS ASIENTOS CONTABLES GENERADOS
			foreach ($asientos as $asiento) {
				#elimino los bonos cargados a la remuneracion
				$this->db->delete('gc_detalle_asiento_contable', array('idasiento' => $asiento->id)); 


			}

			#BORRAMOS LOS ASIENTOS CONTABLES GENERADOS
			$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
			$this->db->where('idperiodo',$idperiodo);
			$this->db->delete('gc_asiento_contable');		

			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
			$saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;	


			$activos_fijos = $this->get_activo_fijo_by_periodo($idperiodo);

			$this->load->model('account');
			foreach ($activos_fijos as $activo_fijo) {
		
					/*$this->db->query("update gc_comunidad set 
																fondoreserva = fondoreserva + " . $activo_fijo->monto . "
																where id = " . $this->session->userdata('comunidadid'));	


					$cuenta_data = $this->account->get_proveedor_by_cuenta($activo_fijo->idcuenta);
					$data_fr = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idcuenta' => $activo_fijo->idcuenta,
				      	'glosa' => 'Reversa de depreciación de Activo Fijo.  Cuenta de ' . $cuenta_data->proveedor,
				        'monto' =>  $activo_fijo->monto,
				        'saldo' =>  ($saldo_fondo_reserva_actual + $activo_fijo->monto),
				        'created_at' => date("Y-m-d h:i:s")
					);
					
					$this->db->insert('gc_cartola_fondo_reserva', $data_fr);	
					$saldo_fondo_reserva_actual += $activo_fijo->monto;*/


					//devuelve util residual (REVISAR COMO HACER PARA DEVOLVER VIDA UTIL RESIDUAL EN CASO DE DAR DE BAJA PREVIAMENTE.  ES NECESARIO SABER CUAL ERA LA VIDA UTIL ANTERIOR)
					$this->db->query("update gc_cuenta set 
																vidautilresidual = vuresidualprevia,
																depacum = depreciacion*(vidautil-vuresidualprevia),
																valorresidual = monto - depreciacion*(vidautil-vuresidualprevia),
																baja = 0
																where id = " . $activo_fijo->idcuenta);


					#BORRAMOS ACTIVO FIJO INGRESADO
					$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
					$this->db->where('idperiodo',$idperiodo);
					$this->db->where('idcuenta',$activo_fijo->idcuenta);
					$this->db->delete('gc_activo_fijo_balance');
	
			}

			#BORRAMOS PERIODO DE TABLA DE PERIODOS DE BALANCES
			$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
			$this->db->where('idperiodo',$idperiodo);
			$this->db->delete('gc_periodo_balance');	
		


		}


		$this->db->trans_complete();

		return 1;
	}



	public function get_ultimo_balance($idperiodo){

		$this->db->select('corte')
						  ->from('gc_periodo_balance')
						  ->where('idcomunidad',$this->session->userdata('comunidadid'))
		                  ->where('idperiodo = ' . $idperiodo);
		$corte_query = $this->db->get();
		$datos_corte = $corte_query->row();		

 
		$ult_per_data = $this->db->select('idperiodo, corte, (corte + INTERVAL 1 DAY) as corte_sgte',false)
						  ->from('gc_periodo_balance')
						  ->where('idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('idperiodo <> ' . $idperiodo)
						  ->where('aprueba is not null')
		                  ->order_by('aprueba desc')
		                  ->limit(1);

		$ult_per_data = isset($datos_corte->corte) ? $ult_per_data->where("corte < '" . $datos_corte->corte . "'") : $ult_per_data;  				                 
				                  
		$query = $this->db->get();

		return $query->row();	
	}


	public function add_ingreso_no_contabilizado($parametros){

			$fechaingreso = substr($parametros['fecingreso'],6,4)."-".substr($parametros['fecingreso'],3,2)."-".substr($parametros['fecingreso'],0,2);

			$data = array(
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'fechaingreso' => $fechaingreso,
		      	'monto' => $parametros['monto'],
		      	'descripcion' =>  $parametros['descripcion'],
		        'created_at' => date("Y-m-d H:i:s")		        
			);

			$this->db->insert('gc_ingresos_no_contabilizados', $data);
			return true;
	}


	public function get_ingresos_no_contabilizados($idingreso = null){

		$ingresos_data = $this->db->select('id, date_format(fechaingreso,"%d/%m/%Y") as fechaingreso, monto, descripcion, if(activo=1,"Activo","Eliminado") as estado, date_format(fechaelimina,"%d/%m/%Y %H:%i:%s") as fechaelimina',false)
						  ->from('gc_ingresos_no_contabilizados')
		                  ->where('idcomunidad', $this->session->userdata('comunidadid'));
		//$query = $this->db->get();

		$ingresos_data = is_null($idingreso) ? $ingresos_data : $ingresos_data->where('id',$idingreso);  		                  
		$query = $this->db->get();
		//return $query->result();
		return is_null($idingreso) ? $query->result() : $query->row();
	}		


	public function get_sum_ingresos_no_contabilizados($idperiodo = null){

		if(!is_null($idperiodo)){
				$this->load->model('admin');
				$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);
				$mes = $datos_periodo->mes;
				$anno = $datos_periodo->anno;
		}		

		$ingresos_data = $this->db->select('COALESCE(SUM(monto),0) as monto',false)
						  ->from('gc_ingresos_no_contabilizados')
		                  ->where('idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('activo',1);

		if(!is_null($idperiodo)){
				//$ingresos_data = $ingresos_data->where('month(fechaingreso) = ' . $mes); 
				//$ingresos_data = $ingresos_data->where('year(fechaingreso) = ' . $anno); 
			$ingresos_data = $ingresos_data->where('fechaingreso <= "' . $anno.'-'.str_pad($mes,2,"0",STR_PAD_LEFT).'-'.ultimo_dia_mes($mes,$anno).'"'); 
		}
		$query = $this->db->get();
		return $query->row();
	}			


	public function get_ingresos_no_contabilizados_by_periodo($idperiodo = null,$eliminados = false){

		if(!is_null($idperiodo)){
				$this->load->model('admin');
				$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);
				$mes = $datos_periodo->mes;
				$anno = $datos_periodo->anno;
		}		

		$ingresos_data = $this->db->select('id, date_format(fechaingreso,"%d/%m/%Y") as fechaingreso, monto, descripcion, if(activo=1,"Activo","Eliminado") as estado, date_format(fechaelimina,"%d/%m/%Y %H:%i:%s") as fechaelimina',false)
						  ->from('gc_ingresos_no_contabilizados')
		                  ->where('idcomunidad', $this->session->userdata('comunidadid'));

		if(!is_null($idperiodo)){
			if($eliminados){
				$ingresos_data = $ingresos_data->where('month(fechaelimina) = ' . $mes); 
				$ingresos_data = $ingresos_data->where('year(fechaelimina) = ' . $anno); 
				//$ingresos_data =  $ingresos_data->where('activo',0); 				
			}else{
				$ingresos_data = $ingresos_data->where('month(fechaingreso) = ' . $mes); 
				$ingresos_data = $ingresos_data->where('year(fechaingreso) = ' . $anno); 				
				//$ingresos_data =  $ingresos_data->where('activo',1); 				
			}
		}else{
			$ingresos_data = $eliminados ? $ingresos_data->where('activo',0) : $ingresos_data; 
		}
		//$ingresos_data = $ingresos_data->order_by('activo','desc');

		$query = $this->db->get();
		return $query->result();
	}

	public function eliminar_ingreso($idingreso){

		$this->db->where('id', $idingreso);
		$this->db->update('gc_ingresos_no_contabilizados',array('activo' => 0, 'fechaelimina' => date("Y-m-d H:i:s"))); 		
	}


	public function set_cuenta_balance($idcuenta,$idperiodo,$monto){

				#SUMA SALDO ANTERIOR SI ES QUE EXISTE
				$saldo_anterior = $this->get_saldos_cuentas($idcuenta);
				$monto += isset($saldo_anterior->valor) ? $saldo_anterior->valor : 0;


				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('idperiodo',$idperiodo);
				$this->db->where('idcuentacontable',$idcuenta);
				$this->db->update('gc_detalle_balance', array('valor' => $monto));			
				return true;
	}			


	public function ver_cuentas_balance($idperiodo,$idcuenta,$balance){

		switch ($idcuenta) {
			case 1:  #BANCO

				$this->load->model('account');
				$this->load->model('payment');
				$ultimo_balance = $this->get_ultimo_balance($idperiodo);

				if(!is_null($ultimo_balance)){
					$fec_corte_anterior = $ultimo_balance->corte_sgte;
				}else{
					$fec_corte_anterior = '2010-01-01';
				}

				#var_dump($this->payment->get_saldo_contable('2016-06-16')); exit;

				$data[0] = $this->account->get_movimientos(null,null,$fec_corte_anterior,$balance->corte,'registro');
				$data[1] = array('saldo_contable' => $this->payment->get_saldo_contable($balance->corte));
				$data[2] = $this->get_sum_ingresos_no_contabilizados($idperiodo)->monto;

				break;		
			case 2:  #DOCUMENTOS EN CARTERA
				$this->db->select('p.numero, fp.nombre as formapago, a.cheque, b.nombre as banco, date_format(a.fechadeposito,"%d/%m/%Y") as fechadeposito, a.monto',false)
								  ->from('gc_listado_abonos a')
								  ->join('gc_propiedad p','a.idpropiedad = p.id')
								  ->join('gc_forma_pago fp','a.idformapago = fp.id')
								  ->join('gc_banco b','a.idbanco = b.id','left')
				                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
				                  ->where("left(created_at,10) <= '" . $balance->corte ."'")
				                  ->where("(a.fechaconciliacion is null or a.fechaconciliacion > '" . $balance->corte . "')")
				                  ->where('a.activo = 1');
				$query = $this->db->get();
				$data = $query->result();
				break;
			
			case 3: #GASTOS COMUNES POR COBRAR MES
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										(select i.monto
										FROM gc_ggcc_propiedad gp 
										left JOIN gc_ggcc_item i ON gp.id = i.idggcc
										WHERE gp.idpropiedad = p.id AND i.iditem = 1 and gp.idperiodo = ' . $idperiodo . ') as deuda,
										(select i.monto
										FROM gc_ggcc_propiedad gp 
										left JOIN gc_ggcc_item i ON gp.id = i.idggcc
										WHERE gp.idpropiedad = p.id AND i.iditem = 2 and gp.idperiodo = ' . $idperiodo . ') as fr
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));

				$data = $query->result();
				break;

			case 4: #INTERESES POR COBRAR DEL MES
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										(select COALESCE(SUM(dp.monto),0) as monto
										FROM gc_deuda_propiedad dp
										inner JOIN gc_propiedad prop ON dp.idpropiedad = prop.id
										WHERE prop.id = p.id AND dp.idtipodeudadetalle = 12 and dp.idperiodo = ' . $idperiodo . ') as intereses,
										(select COALESCE(SUM(dp.monto),0) as monto
										FROM gc_deuda_propiedad dp
										inner JOIN gc_propiedad prop ON dp.idpropiedad = prop.id
										WHERE prop.id = p.id AND dp.idtipodeudadetalle = 8 and dp.idperiodo = ' . $idperiodo . ') as ajustes
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));

				$data = $query->result();
				break;

			case 19: #OTRAS MULTAS
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										(select COALESCE(SUM(dp.monto),0) as monto
										FROM gc_deuda_propiedad dp
										inner JOIN gc_propiedad prop ON dp.idpropiedad = prop.id
										WHERE prop.id = p.id AND dp.idtipodeudadetalle = 7 and dp.idperiodo = ' . $idperiodo . ') as multas
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));

				$data = $query->result();
				break;
			case 20: #OTRAS COBROS
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										(select COALESCE(SUM(dp.monto),0) as monto
										FROM gc_deuda_propiedad dp
										inner JOIN gc_propiedad prop ON dp.idpropiedad = prop.id
										WHERE prop.id = p.id AND dp.idtipodeudadetalle = 9 and dp.idperiodo = ' . $idperiodo . ') as cuotas_especiales
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));

				$data = $query->result();
				break;

			case 5: #GASTOS COMUNES POR COBRAR MOROSOS
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										COALESCE((select gs.monto
										FROM gc_propiedad prop
										inner JOIN gc_ggcc_propiedad gp ON gp.idpropiedad = prop.id and gp.idperiodo = ' . $idperiodo . '
										left join gc_ggcc_saldo gs on gs.idggcc = gp.id
										WHERE prop.id = p.id and gs.monto > 0),0) as saldo
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));

				$data = $query->result();
				break;
			case 7: #DOCUMENTOS POR RENDIR
				/*$query = $this->db->query('select * from
							(select c.id,c.idggcc, if(p.nombre is null,c.nombreproveedor,p.nombre) as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, left(c.fecautoriza,10) as fecautoriza, (select max(left(created_at,10)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id) as fec_ultimo_pago, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.descripcion, c.monto
							from gc_cuenta c
							left join gc_proveedor p on c.idproveedor = p.id
							left join gc_tipo_documento_tributario tdt on c.idtipodoctrib = tdt.id
							left join gc_tipo_deuda_detalle d on c.idtipodeudadetalle = d.id
							where c.idcomunidad = ' . $this->session->userdata('comunidadid') . '
							and c.formapago = "gc") as tmp
							where fec_ultimo_pago <= "'. $balance->corte . '"
							and (left(fecautoriza,10) > "'. $balance->corte . '" or idggcc is null)');*/

				$query = $this->db->query('select * from
							(select c.id,c.idggcc, if(p.nombre is null,c.nombreproveedor,p.nombre) as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, left(c.fecautoriza,10) as fecautoriza, (select max(left(created_at,10)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id) as fec_ultimo_pago, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.descripcion, c.monto
							from gc_cuenta c
							left join gc_proveedor p on c.idproveedor = p.id
							left join gc_tipo_documento_tributario tdt on c.idtipodoctrib = tdt.id
							left join gc_tipo_deuda_detalle d on c.idtipodeudadetalle = d.id
							where c.idcomunidad = ' . $this->session->userdata('comunidadid') . '
							and c.formapago = "gc") as tmp
							where (left(fecautoriza,10) > "'. $balance->corte . '" or idggcc is null)');

				$data = $query->result();
				break;
			case 8: #BIENES Y EQUIPOS
				$this->load->model('account');
				$data = $this->account->get_activo_fijo_impago_by_id();
				break;
			case 9: #DEPRECIACIÓN ACUMULADA
				$this->load->model('account');
				$data[0] = $this->account->get_activo_fijo_impago_by_id();

				$ultimo_balance = $this->get_ultimo_balance($idperiodo);
				$data[1] = !is_null($ultimo_balance) ? $this->get_cuentas_balance($ultimo_balance->idperiodo,9) : $this->get_saldos_cuentas_inic(9);


				break;	
			case 10: #PAGOS ANTICIPADOS PROPIETARIOS
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										COALESCE((select gs.monto
										FROM gc_propiedad prop
										inner JOIN gc_ggcc_propiedad gp ON gp.idpropiedad = prop.id and gp.idperiodo = ' . $idperiodo . '
										left join gc_ggcc_saldo gs on gs.idggcc = gp.id
										WHERE prop.id = p.id and gs.monto < 0),0) as saldo
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid') . '
										having saldo <> 0');

				$data = $query->result();
				break;	
			case 12: #CUENTAS POR PAGAR
				$query = $this->db->query('select * from
										(select c.id, c.idggcc, if(p.nombre is null,c.nombreproveedor,p.nombre) as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, left(c.fecautoriza,10) as fecautoriza, (select max(left(created_at,10)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id) as fec_ultimo_pago,  date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.descripcion, c.monto, if(c.idtipodoctrib=4,saldo*(-1),saldo) as saldo,							(select if(c.idtipodoctrib=4,COALESCE(SUM(monto),0)*(-1),COALESCE(SUM(monto),0)) from gc_cartola_pagos where activo = 1 and idcuenta = c.id and left(created_at,10) > "'. $balance->corte . '") as pago_fuera_corte
										from gc_cuenta c
										left join gc_proveedor p on c.idproveedor = p.id
										left join gc_tipo_documento_tributario tdt on c.idtipodoctrib = tdt.id
										left join gc_tipo_deuda_detalle d on c.idtipodeudadetalle = d.id							
										where c.idcomunidad =  ' . $this->session->userdata('comunidadid') . ' and 
										left(c.created_at,10) <= "'. $balance->corte . '"
										and formapago = "gc" and
										(c.active = 1 or left(c.fecdesactiva,10) > "'. $balance->corte . '")
										) as tmp
										where (fec_ultimo_pago > "'. $balance->corte . '" or abs(saldo) > 0)');

				$data = $query->result();
				break;										
			case 24: #INGRESOS NO IDENTIFICADOS
				$ultimo_balance = $this->get_ultimo_balance($idperiodo);
				if(!is_null($ultimo_balance)){
					$data[0] = $this->get_ingresos_no_contabilizados_by_periodo($idperiodo); //INGRESADOS
					$data[1] = $this->get_ingresos_no_contabilizados_by_periodo($idperiodo,true); //ELIMINADOS
					$data[2] = !is_null($ultimo_balance) ? $this->get_cuentas_balance($ultimo_balance->idperiodo,24) : $this->get_saldos_cuentas_inic(24);				
				}else{
					$data[0] = $this->get_ingresos_no_contabilizados_by_periodo(); //INGRESADOS
					$data[1] = $this->get_ingresos_no_contabilizados_by_periodo(null,true); //ELIMINADOS
					//$data[1] = array();
					$data[2] = !is_null($ultimo_balance) ? $this->get_cuentas_balance($ultimo_balance->idperiodo,24) : $this->get_saldos_cuentas_inic(24);				
				}
				break;	
			case 14: #FONDO DE RESERVA

				$this->load->model('account');
				$this->load->model('payment');
				$ultimo_balance = $this->get_ultimo_balance($idperiodo);

				if(!is_null($ultimo_balance)){
					$fec_corte_anterior = $ultimo_balance->corte;
				}else{
					$fec_corte_anterior = '2010-01-01';
				}
				#var_dump($fec_corte_anterior);
				#var_dump($balance->corte); exit;
				$data[0] = $this->account->get_cartola_fondo_reserva(null,$fec_corte_anterior,$balance->corte);
				$data[1] = array('saldo_fr' => $this->payment->get_fondo_reserva($balance->corte));
				

				break;				
			case 16: #FONDO DE RESERVA - MULTAS
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										(select COALESCE(SUM(dp.monto),0) as monto
										FROM gc_deuda_propiedad dp
										inner JOIN gc_propiedad prop ON dp.idpropiedad = prop.id
										WHERE prop.id = p.id AND dp.idtipodeudadetalle = 7 and dp.idperiodo = ' . $idperiodo . ') as multas
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));

				$data[0] = $query->result();
				$ultimo_balance = $this->get_ultimo_balance($idperiodo);
				$data[1] = !is_null($ultimo_balance) ? $this->get_cuentas_balance($ultimo_balance->idperiodo,16) : $this->get_saldos_cuentas_inic(16);				
				break;					
			case 21: #FONDO DE RESERVA - INTERESES

				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										(select COALESCE(SUM(dp.monto),0) as monto
										FROM gc_deuda_propiedad dp
										inner JOIN gc_propiedad prop ON dp.idpropiedad = prop.id
										WHERE prop.id = p.id AND dp.idtipodeudadetalle = 12 and dp.idperiodo = ' . $idperiodo . ') as intereses,
										(select COALESCE(SUM(dp.monto),0) as monto
										FROM gc_deuda_propiedad dp
										inner JOIN gc_propiedad prop ON dp.idpropiedad = prop.id
										WHERE prop.id = p.id AND dp.idtipodeudadetalle = 8 and dp.idperiodo = ' . $idperiodo . ') as ajustes
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));
				$data[0] = $query->result();

				$ultimo_balance = $this->get_ultimo_balance($idperiodo);
				$data[1] = !is_null($ultimo_balance) ? $this->get_cuentas_balance($ultimo_balance->idperiodo,21) : $this->get_saldos_cuentas_inic(21);
				break;	
			case 22: #FONDO DE RESERVA - OTROS COBROS
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										(select COALESCE(SUM(dp.monto),0) as monto
										FROM gc_deuda_propiedad dp
										inner JOIN gc_propiedad prop ON dp.idpropiedad = prop.id
										WHERE prop.id = p.id AND dp.idtipodeudadetalle = 9 and dp.idperiodo = ' . $idperiodo . ') as cuotas_especiales
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));
				$data[0] = $query->result();
				$ultimo_balance = $this->get_ultimo_balance($idperiodo);
				$data[1] = !is_null($ultimo_balance) ? $this->get_cuentas_balance($ultimo_balance->idperiodo,22) : $this->get_saldos_cuentas_inic(22);				
				break;								
			case 17: #EXCEDENTES ACUMULADOS

				$this->load->model('payment');
				$query = $this->db->query('select p.numero, p.responsable, p.prorrateo,
										(select i.monto
										FROM gc_ggcc_propiedad gp 
										left JOIN gc_ggcc_item i ON gp.id = i.idggcc
										WHERE gp.idpropiedad = p.id AND i.iditem = 1 and gp.idperiodo = ' . $idperiodo . ') as deuda,
										(select i.monto
										FROM gc_ggcc_propiedad gp 
										left JOIN gc_ggcc_item i ON gp.id = i.idggcc
										WHERE gp.idpropiedad = p.id AND i.iditem = 2 and gp.idperiodo = ' . $idperiodo . ') as fr
										from gc_propiedad p
										where p.idcomunidad = ' . $this->session->userdata('comunidadid'));
				$data[0] = $query->result();
				$data[1] = array('deuda' => $this->payment->get_ggcc_by_periodo($idperiodo,'D'),
								 'fr' => $this->payment->get_ggcc_by_periodo($idperiodo,'FR'));
				$ultimo_balance = $this->get_ultimo_balance($idperiodo);
				$data[2] = !is_null($ultimo_balance) ? $this->get_cuentas_balance($ultimo_balance->idperiodo,17) : $this->get_saldos_cuentas_inic(17);								
				break;	
			case 23: #DEPRECIACIÓN ACTIVO FIJO
				$this->load->model('account');
				$data[0] = $this->account->get_activo_fijo_impago_by_id();

				$ultimo_balance = $this->get_ultimo_balance($idperiodo);
				#SE TOMA EL SALDO ANTERIOR DE LA DEPRECIACIÓN ACUMULADA, YA QUE TIENEN EL MISMO VALOR 
				$data[1] = !is_null($ultimo_balance) ? $this->get_cuentas_balance($ultimo_balance->idperiodo,9) : $this->get_saldos_cuentas_inic(9);				
				break;								
			default:
				break;
		}		

		return $data;

	}		

}