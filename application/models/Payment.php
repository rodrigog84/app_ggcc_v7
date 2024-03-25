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

class Payment extends CI_Model
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



	public function get_deuda_by_comunidad($comunidadid){

		$this->db->select('p.id, p.numero, p.responsable, p.saldo ')
						  ->from('gc_propiedad as p')
		                  ->where('p.idcomunidad', $comunidadid)
		                  ->order_by('p.saldo desc');
		$query = $this->db->get();
		$datos = $query->result();
		return $datos;

	}


	public function get_deuda_publicada_by_comunidad($comunidadid){

		$this->db->select('p.id, p.numero, p.responsable, p.direccion, p.saldo_publicado as saldo ')
						  ->from('gc_propiedad as p')
		                  ->where('p.idcomunidad', $comunidadid)
		                  ->where('p.active = 1')
		                  ->order_by('p.numero asc');
		$query = $this->db->get();
		$datos = $query->result();
		return $datos;

	}	


	public function get_deuda_by_comunidad_periodo($comunidadid,$idperiodo){

		$this->db->select('p.id, p.numero, p.responsable, gp.monto, gp.saldo  ')
						  ->from('gc_propiedad as p')
						  ->join('gc_ggcc_propiedad as gp','p.id = gp.idpropiedad and gp.idperiodo = '. $idperiodo)
		                  ->where('p.idcomunidad', $comunidadid)
		                  ->order_by('p.id asc');
		$query = $this->db->get();
		$datos = $query->result();
		return $datos;

	}	


	public function get_deuda_sin_publicar_by_propiedad(){

		$this->db->select('pr.id, pr.numero, pr.responsable, sum(gp.saldo) as saldo_sin_publicar ',false)
						  ->from('gc_ggcc_propiedad as gp')
						  ->join('gc_propiedad pr','gp.idpropiedad = pr.id')
						  ->join('gc_periodo p','gp.idperiodo = p.id')
						  ->join('gc_periodo_estado pe','p.id = pe.idperiodo and pe.idcomunidad = '.$this->session->userdata('comunidadid'))
		                  ->where('pr.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('pe.genera is not null')
		                  ->where('pe.publica is null')
		                  ->group_by('pr.id');
		$query = $this->db->get();
		$datos = $query->result();
		return $datos;

	}

	public function get_deuda_sin_cancelar_by_comunidad(){

		//$this->db->select('round((sum(gp.saldo)/sum(gp.monto))*100,2) as deuda ',false)
		$this->db->select('COALESCE(sum(gp.saldo),0) saldo , COALESCE(sum(gp.monto),0) as deuda ',false)
						  ->from('gc_ggcc_propiedad as gp')
						  ->join('gc_propiedad p','gp.idpropiedad = p.id')
		                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'));
		$query = $this->db->get();
		return $query->row();

	}



	public function get_deuda_by_propiedad($propiedadid){

		$this->db->select('p.id, p.numero, p.responsable, p.saldo, p.saldo_publicado ')
						  ->from('gc_propiedad as p')
		                  ->where('p.id', $propiedadid);
		$query = $this->db->get();
		return $query->row();

	}

	public function get_periodos_by_propiedad($propiedadid){

		$this->db->select('p.id, p.mes, p.anno, gp.saldo ')
						  ->from('gc_periodo as p')
						  ->join('gc_ggcc_propiedad as gp','p.id = gp.idperiodo')
		                  ->where('gp.idpropiedad', $propiedadid)
		                  ->where('gp.saldo >', 0);
		$query = $this->db->get();
		return $query->result();

	}

	public function get_saldo_disponible_by_comunidad($comunidadid){

		$this->db->select('caja')
						  ->from('gc_comunidad')
		                  ->where('id', $comunidadid);
		$query = $this->db->get();
		$datos_caja = $query->row();

		$saldo_contable = $datos_caja->caja;

		$this->db->select('COALESCE(SUM(monto),0) as monto',false)
						  ->from('gc_cartola_caja')
		                  ->where('idcomunidad', $comunidadid)
		                  ->where('fechaconciliacion is null')
		                  ->where('activo = 1');
		$query = $this->db->get();
		$datos_sin_conciliacion = $query->row();		

		$saldo_sin_conciliacion = $datos_sin_conciliacion->monto;

		$this->db->select('COALESCE(SUM(monto),0) as monto',false)
						  ->from('gc_cartola_caja')
		                  ->where('idcomunidad', $comunidadid)
		                  ->where("fechaconciliacion > '".date("Y-m-d")."'")
		                  ->where('activo = 1');
		$query = $this->db->get();
		$datos_sin_cobrar = $query->row();		

		$saldo_sin_cobrar = $datos_sin_cobrar->monto;


		$this->load->model('contabilidad_model','contabilidad');
		
		$ing_no_contabilizados = $this->contabilidad->get_sum_ingresos_no_contabilizados()->monto;

		return $saldo_contable + $ing_no_contabilizados - $saldo_sin_conciliacion - $saldo_sin_cobrar;

	}	



public function get_saldo_contable_by_comunidad($comunidadid){

		$this->db->select('caja')
						  ->from('gc_comunidad')
		                  ->where('id', $comunidadid);
		$query = $this->db->get();
		$datos_caja = $query->row();

		$saldo_contable = $datos_caja->caja;


		$this->load->model('contabilidad_model','contabilidad');

		$ing_no_contabilizados = $this->contabilidad->get_sum_ingresos_no_contabilizados()->monto;



		return $saldo_contable + $ing_no_contabilizados;

	}				



public function get_ingresos_totales_by_periodo($comunidadid,$idperiodo){


		$this->load->model('admin');

		$periodo = $this->admin->get_periodo_by_id($idperiodo);
		
		$mes = str_pad($periodo->mes,2,'0',STR_PAD_LEFT);
		$anno = $periodo->anno;

		$fecha_desde = $anno.'-'.$mes.'-01';
		$fecha_hasta = $anno.'-'.$mes.'-' . date("d",(mktime(0,0,0,$mes+1,1,$anno)-1));


		//echo date("d",(mktime(0,0,0,$month+1,1,$year)-1));

		$this->db->select('ifnull(SUM(la.monto),0) AS monto',false)
						  ->from('gc_listado_abonos la')
						  ->where('la.idcomunidad', $comunidadid)
		                  ->where('la.activo', 1)
		                  ->where('la.fechaconciliacion is not NULL')
		                  ->where("la.fechapago between '" . $fecha_desde . "' and '" . $fecha_hasta . "'");
		$query = $this->db->get();
		$datos_abonos = $query->row();

		$abonos = $datos_abonos->monto;




		$this->db->select('ifnull(SUM(cc.monto),0) AS monto',false)
						  ->from('gc_cartola_caja cc')
						  ->join('gc_ingresos i','cc.idingreso = i.id','LEFT')
						  ->where('cc.idcomunidad', $comunidadid)
		                  ->where('cc.activo', 1)
		                  ->where('cc.fechaconciliacion is not NULL')
		                  ->where("cc.fechapago between '" . $fecha_desde . "' and '" . $fecha_hasta . "'")
		                  ->where("(cc.idingreso is not null or cc.exingreso = 1)")
		                  ->where("(i.tipoingreso <> 'na' OR i.tipoingreso IS NULL)");

		$query = $this->db->get();
		$datos_ingresos = $query->row();

		$ingresos = $datos_ingresos->monto;


		return $abonos + $ingresos;

	}				



public function get_egresos_totales_by_periodo($comunidadid,$idperiodo){


		$this->load->model('admin');

		$periodo = $this->admin->get_periodo_by_id($idperiodo);
		
		$mes = str_pad($periodo->mes,2,'0',STR_PAD_LEFT);
		$anno = $periodo->anno;

		$fecha_desde = $anno.'-'.$mes.'-01';
		$fecha_hasta = $anno.'-'.$mes.'-' . date("d",(mktime(0,0,0,$mes+1,1,$anno)-1));


		//echo date("d",(mktime(0,0,0,$month+1,1,$year)-1));

		$this->db->select('ifnull(SUM(lp.monto),0) AS monto',false)
						  ->from('gc_listado_pagos lp')
						  ->where('lp.idcomunidad', $comunidadid)
		                  ->where('lp.activo', 1)
		                  ->where('lp.fechaconciliacion is not NULL')
		                  ->where("lp.fechapago between '" . $fecha_desde . "' and '" . $fecha_hasta . "'");
		$query = $this->db->get();
		$datos_egresos = $query->row();

		$egresos = $datos_egresos->monto;


		return $egresos;

	}		

	public function get_saldo_contable($fecha = null){

		$this->db->select('caja')
						  ->from('gc_comunidad')
		                  ->where('id', $this->session->userdata('comunidadid'));
		$query = $this->db->get();
		$datos_caja = $query->row();

		$saldo_contable = $datos_caja->caja;

		$monto_mov_fecha = 0;

		if(!is_null($fecha)){
			$this->db->select('COALESCE(SUM(monto),0) as monto',false)
							  ->from('gc_cartola_caja')
			                  ->where('idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where("left(created_at,10) > '" . $fecha . "'")
			                  ->where('activo = 1');
			$query = $this->db->get();
			$monto_mov_fecha = $query->row()->monto;		
		}


		return $saldo_contable - $monto_mov_fecha;

	}	


	public function get_fondo_reserva($fecha = null){

		$this->db->select('fondoreserva')
						  ->from('gc_comunidad')
		                  ->where('id', $this->session->userdata('comunidadid'));
		$query = $this->db->get();
		$datos_fondo_reserva = $query->row();

		$fondo_reserva = $datos_fondo_reserva->fondoreserva;

		$monto_mov_fecha = 0;

		if(!is_null($fecha)){
			$this->db->select('COALESCE(SUM(monto),0) as monto',false)
							  ->from('gc_cartola_fondo_reserva')
			                  ->where('idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where("left(created_at,10) > '" . $fecha . "'")
			                  ->where('activo = 1');
			$query = $this->db->get();
			$monto_mov_fecha = $query->row()->monto;		
		}


		return $fondo_reserva - $monto_mov_fecha;

	}		


	public function get_periodos_publicados_by_propiedad($propiedadid){

		$this->db->select('p.id, p.mes, p.anno, gp.saldo ')
						  ->from('gc_periodo as p')
						  ->join('gc_ggcc_propiedad as gp','p.id = gp.idperiodo')
						  ->join('gc_periodo_estado as pe','pe.idperiodo = p.id and pe.idcomunidad = '.$this->session->userdata('comunidadid'))
		                  ->where('gp.idpropiedad', $propiedadid)
		                  ->where('pe.publica is not null')
		                  ->where('gp.saldo >', 0)
		                  ->order_by('p.id');
		$query = $this->db->get();
		return $query->result();

	}


	public function get_periodos_activos($comunidadid){

		$this->db->select('p.id, p.mes, p.anno ')
						  ->from('gc_periodo as p')
						  ->join('gc_periodo_estado as pe','p.id = pe.idperiodo')
		                  ->where('pe.idcomunidad', $comunidadid)
		                  ->where('pe.genera is null');
		$query = $this->db->get();
		return $query->result();
	}	


	public function get_correlativo_abono(){
		$this->db->trans_start();
		$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));
		
		$folio_actual = $datos_comunidad->maxfolioabono;
		$folio_actual++;

		$this->db->where('id', $this->session->userdata('comunidadid'));
		$this->db->update('gc_comunidad',array('maxfolioabono' => $folio_actual)); 		

		$this->db->trans_complete();	
		return $folio_actual;
	}



	public function get_correlativo_pago(){
		$this->db->trans_start();
		$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));
		
		$folio_actual = $datos_comunidad->maxfoliopago;
		$folio_actual++;

		$this->db->where('id', $this->session->userdata('comunidadid'));
		$this->db->update('gc_comunidad',array('maxfoliopago' => $folio_actual)); 		

		$this->db->trans_complete();	
		return $folio_actual;
	}

	public function add_abono($parametros){
		
			$this->db->trans_start();

			$fechapago = substr($parametros['fechapago'],6,4)."-".substr($parametros['fechapago'],3,2)."-".substr($parametros['fechapago'],0,2);
			$fechadeposito = substr($parametros['fechadeposito'],6,4)."-".substr($parametros['fechadeposito'],3,2)."-".substr($parametros['fechadeposito'],0,2);

			$monto = $parametros['monto'];

			// guarda cartola de caja
			$this->load->model('admin');
			$propiedad_cartola = $this->admin->get_propiedad_by_id($parametros['idpropiedad']);
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
			$saldo_caja_actual = $datoscomunidad->caja;
			$id_cartola_pago = 0;


			$data = array(
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'folio' => $this->get_correlativo_abono(),
				'idpropiedad' => $parametros['idpropiedad'],
				'monto' => $monto,
		      	'fechapago' => $fechapago,
		        'idformapago' =>  $parametros['idformapago'],
		        'idbanco' =>  $parametros['idbanco'],
		        'cheque' =>  $parametros['cheque'],
		        'ruttitular' =>  $parametros['ruttitular'],
		        'dvtitular' =>  $parametros['dvtitular'],
		        'fechadeposito' => $fechadeposito,				      	
		        'nombrearchivo' =>  $parametros['nombrearchivo'],
		        'nombrerealarchivo' =>  $parametros['nombrerealarchivo'],
		        'created_at' => date("Y-m-d H:i:s")
			);

			$this->db->insert('gc_listado_abonos', $data);	
			$listado_abonos_id = $this->db->insert_id();		

			if($parametros['pagototal'] == 'on'){ // selecciona pago total
				$this->db->select('gp.id, gp.monto, gp.idpropiedad, gp.idperiodo, gp.abonado, gp.saldo, p.mes, p.anno ')
								  ->from('gc_ggcc_propiedad as gp')
								  ->join('gc_periodo as p','gp.idperiodo = p.id')
								  ->join('gc_periodo_estado as pe','pe.idperiodo = p.id and pe.idcomunidad = '.$this->session->userdata('comunidadid'))
				                  ->where('gp.idpropiedad', $parametros['idpropiedad'])
				                  ->where('gp.saldo >', 0)
				                  ->where('pe.publica is not null')
				                 // ->where('pe.genera is not null')
				                  ->order_by('gp.idperiodo asc')
				                  ->limit(1);
				$query = $this->db->get();
				$ggcc_propiedad = $query->row();

				$monto = is_null($ggcc_propiedad) ? 0 : $monto;

			}else{ // abona a periodo

				if($parametros['idperiodo'] != null){ // estoy pagando un período
					$this->db->select('gp.id, gp.monto, gp.idpropiedad, gp.idperiodo, gp.abonado, gp.saldo, p.mes, p.anno ')
									  ->from('gc_ggcc_propiedad as gp')
									  ->join('gc_periodo as p','gp.idperiodo = p.id')
					                  ->where('gp.idpropiedad', $parametros['idpropiedad'])
					                  ->where('gp.idperiodo', $parametros['idperiodo']);
					$query = $this->db->get();
					$ggcc_propiedad = $query->row();
				}
				
			}


			$array_envio_comprobante = array();

			/******* REBAJA DE DEUDAS DE PROPIEDAD *********/
			while($monto > 0){  // 

				if($parametros['idperiodo'] == null && $parametros['pagototal'] != 'on'){ // SI REALIZO UN ABONO SIN TENER DEUDA


						$data = array(
							'idlistado' => $listado_abonos_id,
					      	'idpropiedad' => $parametros['idpropiedad'],
					      	'idperiodo' =>  null,
					        'fechapago' => $fechapago,				      	
					        'monto' =>  $monto,
					        'idformapago' =>  $parametros['idformapago'],
					        'idbanco' =>  $parametros['idbanco'],
					        'cheque' =>  $parametros['cheque'],
					        'ruttitular' =>  $parametros['ruttitular'],
					        'dvtitular' =>  $parametros['dvtitular'],
					        'fechadeposito' => $fechadeposito,				      	
					        'nombrearchivo' =>  $parametros['nombrearchivo'],
					        'nombrerealarchivo' =>  $parametros['nombrerealarchivo']
						);
						// guarda cartola
						$this->db->insert('gc_cartola_propiedad', $data);
						// guarda cartola caja
						$cartola_propiedad_id = $this->db->insert_id();	
						$saldo_caja_actual += $monto;

						$data = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idabono' => $cartola_propiedad_id,
					      	'glosa' =>  'Abono GC de Propiedad # '.$propiedad_cartola->numero,
					        'monto' => $monto,				      	
					        'saldo' =>  $saldo_caja_actual,
					        'fechapago' => $fechapago,
					        'created_at' => date("Y-m-d h:i:s")
						);
						
						$this->db->insert('gc_cartola_caja', $data);
						//array_push($array_envio_comprobante,$cartola_propiedad_id);

						$monto = 0;


				}else{ //EN CASO DE TENER DEUDA

					if($monto <= $ggcc_propiedad->saldo){  // si monto no es mayor a deuda del mes
						$monto_abonado = $monto;
						//rebaja ggcc mensual
						$this->db->query("update gc_ggcc_propiedad set 
																	abonado = abonado + " . $monto_abonado . ", 
																	saldo = saldo - " . $monto_abonado . "
																	where id = " . $ggcc_propiedad->id);

						$data = array(
							'idlistado' => $listado_abonos_id,
					      	'idpropiedad' => $ggcc_propiedad->idpropiedad,
					      	'idperiodo' =>  $ggcc_propiedad->idperiodo,
					        'fechapago' => $fechapago,				      	
					        'monto' =>  $monto_abonado,
					        'idformapago' =>  $parametros['idformapago'],
					        'idbanco' =>  $parametros['idbanco'],
					        'cheque' =>  $parametros['cheque'],
					        'ruttitular' =>  $parametros['ruttitular'],
					        'dvtitular' =>  $parametros['dvtitular'],
					        'fechadeposito' => $fechadeposito,				      	
					        'nombrearchivo' =>  $parametros['nombrearchivo'],
					        'nombrerealarchivo' =>  $parametros['nombrerealarchivo']
						);
						// guarda cartola
						$this->db->insert('gc_cartola_propiedad', $data);
						$saldo_caja_actual += $monto_abonado;	

						// guarda cartola caja
						$cartola_propiedad_id = $this->db->insert_id();	

						$data = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idabono' => $cartola_propiedad_id,
					      	'glosa' =>  'Abono GC de Propiedad # '.$propiedad_cartola->numero . '.  '. date2string($ggcc_propiedad->mes,$ggcc_propiedad->anno),
					        'monto' => $monto_abonado,				      	
					        'saldo' =>  $saldo_caja_actual,
					        'fechapago' => $fechapago,
					        'created_at' => date("Y-m-d h:i:s")
						);
						
						$this->db->insert('gc_cartola_caja', $data);
						
						//array_push($array_envio_comprobante,$cartola_propiedad_id);
						//$this->generar_mail_abono($this->session->userdata('comunidadid'),$ggcc_propiedad->idpropiedad,$cartola_propiedad_id);

						$monto = 0;	

					}else{
						//rebaja ggcc mensual
						$monto = $monto - $ggcc_propiedad->saldo;
						$this->db->query("update gc_ggcc_propiedad set 
																	abonado = monto, 
																	saldo = 0
																	where id = " . $ggcc_propiedad->id);


						$data = array(
							'idlistado' => $listado_abonos_id,
					      	'idpropiedad' => $ggcc_propiedad->idpropiedad,
					      	'idperiodo' =>  $ggcc_propiedad->idperiodo,
					        'fechapago' => $fechapago,				      	
					        'monto' =>  $ggcc_propiedad->saldo,
					        'idformapago' =>  $parametros['idformapago'],
					        'idbanco' =>  $parametros['idbanco'],		
					        'cheque' =>  $parametros['cheque'],	
					        'ruttitular' =>  $parametros['ruttitular'],
					        'dvtitular' =>  $parametros['dvtitular'],	
					        'fechadeposito' => $fechadeposito,				      				        	        
					        'nombrearchivo' =>  $parametros['nombrearchivo'],
					        'nombrerealarchivo' =>  $parametros['nombrerealarchivo']
						);
						// guarda cartola
						$this->db->insert('gc_cartola_propiedad', $data);	

						$saldo_caja_actual += $ggcc_propiedad->saldo;

						// guarda cartola caja
						$cartola_propiedad_id = $this->db->insert_id();		
						$id_cartola_pago = $ggcc_propiedad->idperiodo == $parametros['idperiodo'] ? $cartola_propiedad_id : $id_cartola_pago;				

						$data = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idabono' => $cartola_propiedad_id,
					      	'glosa' =>  'Abono GC de Propiedad # '.$propiedad_cartola->numero . '.  '. date2string($ggcc_propiedad->mes,$ggcc_propiedad->anno),				      	
					        'monto' => $ggcc_propiedad->saldo,				      	
					        'saldo' =>  $saldo_caja_actual,
					        'fechapago' => $fechapago,
					        'created_at' => date("Y-m-d h:i:s")
						);
						
						$this->db->insert('gc_cartola_caja', $data);					

						//$this->generar_mail_abono($this->session->userdata('comunidadid'),$ggcc_propiedad->idpropiedad,$cartola_propiedad_id);
						//array_push($array_envio_comprobante,$cartola_propiedad_id);

						$this->db->select('gp.id, gp.monto, gp.idpropiedad, gp.idperiodo, gp.abonado, gp.saldo, p.mes, p.anno  ')
										  ->from('gc_ggcc_propiedad as gp')
										  ->join('gc_periodo as p','gp.idperiodo = p.id')
										  ->join('gc_periodo_estado as pe','pe.idperiodo = p.id and pe.idcomunidad = '.$this->session->userdata('comunidadid'))
						                  ->where('gp.idpropiedad', $parametros['idpropiedad'])
						                  ->where('gp.saldo >', 0)
						                  //->where('pe.publica is not null')
						                  ->where('pe.genera is not null')
						                  ->order_by('gp.idperiodo asc')
						                  ->limit(1);
						$query = $this->db->get();
						$ggcc_propiedad = $query->row();
						if(is_null($ggcc_propiedad) && $monto > 0){  //QUEDA UN SALDO POSITIVO (SE GENERA UN ABONO SIN PERIODO)


							$data = array(
								'idlistado' => $listado_abonos_id,
						      	'idpropiedad' => $parametros['idpropiedad'],
						      	'idperiodo' =>  null,
						        'fechapago' => $fechapago,				      	
						        'monto' =>  $monto,
						        'idformapago' =>  $parametros['idformapago'],
						        'idbanco' =>  $parametros['idbanco'],
						        'cheque' =>  $parametros['cheque'],
						        'ruttitular' =>  $parametros['ruttitular'],
						        'dvtitular' =>  $parametros['dvtitular'],
						        'fechadeposito' => $fechadeposito,				      	
						        'nombrearchivo' =>  $parametros['nombrearchivo'],
						        'nombrerealarchivo' =>  $parametros['nombrerealarchivo']
							);
							// guarda cartola
							$this->db->insert('gc_cartola_propiedad', $data);
							// guarda cartola caja
							$cartola_propiedad_id = $this->db->insert_id();	
							$saldo_caja_actual += $monto;

							$data = array(
						      	'idcomunidad' => $this->session->userdata('comunidadid'),
						      	'idabono' => $cartola_propiedad_id,
						      	'glosa' =>  'Abono GC de Propiedad # '.$propiedad_cartola->numero,
						        'monto' => $monto,				      	
						        'saldo' =>  $saldo_caja_actual,
						        'fechapago' => $fechapago,
						        'created_at' => date("Y-m-d h:i:s")
							);
							
							$this->db->insert('gc_cartola_caja', $data);							
							// rebaja deuda propiedad
							/*$this->db->query("update gc_cartola_propiedad set 
																		adicional =  " . $monto . "
																		where id = " . $id_cartola_pago);

							$this->db->query("update gc_cartola_caja set 
																		monto =  monto + " . $monto . ",
																		saldo =  saldo + " . $monto . "
																		where idabono = " . $id_cartola_pago);						
							*/


						}

						$monto = is_null($ggcc_propiedad) ? 0 : $monto;
					}


				}


			}

			// rebaja deuda propiedad
			$this->db->query("update gc_propiedad set 
														saldo = saldo - " . $parametros['monto'] . ",
														saldo_publicado = saldo_publicado - " . $parametros['monto'] . "
														where id = " . $parametros['idpropiedad']);

			//aumenta flujo de caja de comunidad			
			$this->db->query("update gc_comunidad set 
														caja = caja + " . $parametros['monto'] . "
														where id = " . $this->session->userdata('comunidadid'));


			/*foreach ($array_envio_comprobante as $cartola_propiedad_id) {
				$this->generar_mail_abono($this->session->userdata('comunidadid'),$parametros['idpropiedad'],$cartola_propiedad_id);
			}*/
			$this->generar_mail_abono($this->session->userdata('comunidadid'),$parametros['idpropiedad'],$listado_abonos_id);
			$this->generar_contenido_ingreso($parametros['idpropiedad'],$listado_abonos_id,true);

			$this->db->trans_complete();
	}	


	public function get_ggcc_by_ggcc($ggccid){
		$this->db->select('gp.id as ggccid, p.id as periodoid, p.mes, p.anno, gp.monto, gp.abonado, gp.saldo, gp.nombrearchivo, gp.nombrerealarchivo ')
						  ->from('gc_ggcc_propiedad as gp')
						  ->join('gc_periodo as p','gp.idperiodo = p.id')
						  ->join('gc_periodo_estado as pe','p.id = pe.idperiodo and pe.idcomunidad = '.$this->session->userdata('comunidadid'))
		                  ->where('gp.id', $ggccid)
		                  ->where('pe.genera is not null');
		$query = $this->db->get();
		return $query->row();

	}


	public function get_periodo_by_ggcc($ggccid){
		$this->db->select('gc.id as ggccid, p.id as periodoid, p.mes, p.anno ')
						  ->from('gc_ggcc_comunidad as gc')
						  ->join('gc_periodo as p','gc.idperiodo = p.id')
		                  ->where('gc.id', $ggccid);
		$query = $this->db->get();
		return $query->row();

	}	


	public function get_ggcc_by_propiedad($propiedadid,$idperiodo = null){
		$ggcc_data = $this->db->select('gp.id as ggccid, p.id as periodoid, p.mes, p.anno, gp.monto, gp.abonado, gp.saldo, gp.prorrateo, gp.nombrearchivo, gp.nombrerealarchivo ')
						  ->from('gc_ggcc_propiedad as gp')
						  ->join('gc_periodo as p','gp.idperiodo = p.id')
						  ->join('gc_periodo_estado as pe','p.id = pe.idperiodo and pe.idcomunidad = '.$this->session->userdata('comunidadid'))
		                  ->where('gp.idpropiedad', $propiedadid)
		                  ->where('pe.publica is not null')
		                  ->order_by('p.anno desc, p.mes desc');

		$ggcc_data = is_null($idperiodo) ? $ggcc_data : $ggcc_data->where('gp.idperiodo',$idperiodo);                 
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return is_null($idperiodo) ? $query->result() : $query->row();


		//$query = $this->db->get();
		//return $query->result();

	}

	public function get_cartola_by_propiedad($propiedadid,$periodoid){


		if($periodoid != 0){	
			$sql_cartola = $this->db->select('idpropiedad, monto, fechapago, nombrearchivo, nombrearchivo, nombrerealarchivo, created_at')
							  ->from('gc_cartola_propiedad')
			                  ->where('idpropiedad', $propiedadid)
			                  ->where('idperiodo', $periodoid)
			                  ->where('activo = 1')
			                  ->order_by('id desc');
		}else{

			/*$sql_cartola = $this->db->select('idpropiedad, monto, fechapago, nombrearchivo, nombrearchivo, nombrerealarchivo, created_at')
							  ->from('gc_cartola_propiedad')
			                  ->where('idpropiedad', $propiedadid)
			                  ->where('activo = 1')
			                  ->order_by('id desc');*/
			$sql_cartola = $this->db->select('id, idpropiedad, monto, fechapago, nombrearchivo, nombrearchivo, nombrerealarchivo, created_at')
							  ->from('gc_listado_abonos')
			                  ->where('idpropiedad', $propiedadid)
			                  ->where('activo = 1')
			                  ->order_by('id desc');			                  
		}

		/*if($tamanopagina != '' ){
			$offset = ($numpagina-1)*$tamanopagina;
			$sql_cartola->limit($tamanopagina,$offset);	
		}*/

		$query = $this->db->get();
		return $query->result();

	}	



	public function get_detalle_by_ggcc($ggccid){

		$this->db->select('i.nombre as item, gi.descripcion, gi.monto')
						  ->from('gc_ggcc_item as gi')
						  ->join('gc_item as i','gi.iditem = i.id')
		                  ->where('gi.idggcc', $ggccid)
		                  ->order_by('i.id asc');


		$query = $this->db->get();
		return $query->result();

	}	



	public function get_detalle_by_propiedadid_periodoid($propiedadid,$periodoid){

		$this->db->select('i.id as itemid, i.nombre as item, gi.descripcion, gi.monto')
						  ->from('gc_ggcc_propiedad as g')
						  ->join('gc_ggcc_item as gi','gi.idggcc = g.id')
						  ->join('gc_item as i','gi.iditem = i.id')
		                  ->where('g.idpropiedad', $propiedadid)
		                  ->where('g.idperiodo', $periodoid)
		                  ->order_by('i.id asc');


		$query = $this->db->get();
		return $query->result();

	}	

	public function get_detalle_by_ggcc_no_individual($ggccid){

		$this->db->select('i.id as itemid, i.nombre as item, gi.descripcion, gi.monto')
						  ->from('gc_ggcc_propiedad as g')
						  ->join('gc_ggcc_item as gi','gi.idggcc = g.id')
						  ->join('gc_item as i','gi.iditem = i.id')
		                  ->where('g.id', $ggccid)
		                  ->where('i.id <> 3')
		                  ->order_by('i.id asc');


		$query = $this->db->get();
		return $query->result();

	}



	public function get_ggcc_by_periodo($idperiodo,$tipo = null){

		$gc_deuda = 0;
		$gc_fr = 0;
		$gc_ci = 0;

		if(is_null($tipo) || $tipo == 'D'){
			$this->db->select('monto')
							  ->from('gc_ggcc_comunidad')
							  ->where('idcomunidad', $this->session->userdata('comunidadid'))
							  ->where('idperiodo', $idperiodo)
							  ->where('tipo','D');
			$query = $this->db->get();
			$gc_deuda = isset($query->row()->monto) ? $query->row()->monto : 0;

			if($tipo == 'D'){
				return $gc_deuda;
			}

		}

		if(is_null($tipo) || $tipo == 'FR'){
			$this->db->select('monto')
							  ->from('gc_ggcc_comunidad')
							  ->where('idcomunidad', $this->session->userdata('comunidadid'))
							  ->where('idperiodo', $idperiodo)
							  ->where('tipo','FR');
			$query = $this->db->get();
			$gc_fr = isset($query->row()->monto) ? $query->row()->monto : 0;

			if($tipo == 'FR'){
				return $gc_fr;
			}

		}

		if(is_null($tipo) || $tipo == 'CI'){
			$this->db->select('COALESCE(SUM(dp.monto),0) as monto',false)
							  ->from('gc_deuda_propiedad dp')
							  ->join('gc_propiedad p','dp.idpropiedad = p.id')
							  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
							  ->where('dp.idperiodo', $idperiodo);
			$query = $this->db->get();
			$gc_ci = isset($query->row()->monto) ? $query->row()->monto : 0;

			if($tipo == 'CI'){
				return $gc_ci;
			}

		}



		return $gc_deuda + $gc_fr + $gc_ci;

	}


	public function get_detalle_by_propiedadid_periodoid_no_individual($propiedadid,$periodoid){

		$this->db->select('i.id as itemid, i.nombre as item, gi.descripcion, gi.monto, gc.tipo_fr, gc.porcentaje')
						  ->from('gc_ggcc_propiedad as g')
						  ->join('gc_ggcc_item as gi','gi.idggcc = g.id')
						  ->join('gc_item as i','gi.iditem = i.id')
						  ->join('gc_propiedad as p','g.idpropiedad = p.id')
						  ->join('gc_ggcc_comunidad gc',"p.idcomunidad = gc.idcomunidad and tipo = 'FR' and gc.idperiodo = " . $periodoid . " and i.id = 2",'left')
		                  ->where('g.idpropiedad', $propiedadid)
		                  ->where('g.idperiodo', $periodoid)
		                  ->where('i.id <> 3')
		                  ->order_by('i.id asc');


		$query = $this->db->get();
		return $query->result();

	}

	public function get_detalle_by_ggcc_individual($ggccid){

		$this->db->select('td.id as itemid, td.nombre as item, dp.descripcion, dp.monto, dls.valor_ant, dls.valor,  format(dls.valor - dls.valor_ant,3) as consumo, format((select sum(valor) as consumo_total from gc_detalle_lectura_servicio where idlectura = ls.id) - (select sum(valor_ant) as consumo_total from gc_detalle_lectura_servicio where idlectura = ls.id),3) as consumo_total, c.unidadmedida, c.montounidad', false)
						  ->from('gc_ggcc_propiedad as g')
						  ->join('gc_deuda_propiedad as dp','g.idpropiedad = dp.idpropiedad and g.idperiodo = dp.idperiodo')
						  ->join('gc_tipo_deuda_detalle as td','dp.idtipodeudadetalle = td.id')
						  ->join('gc_lectura_servicio as ls','dp.idcuenta = ls.idcuenta','left')
						  ->join('gc_detalle_lectura_servicio as dls','ls.id = dls.idlectura and dls.idpropiedad = dp.idpropiedad','left')
						  ->join('gc_cuenta as c','dp.idcuenta = c.id','left')
		                  ->where('g.id', $ggccid)
		                  ->order_by('td.nombre asc');


		$query = $this->db->get();
		return $query->result();

	}	


	public function get_detalle_by_propiedadid_periodoid_individual($propiedadid,$periodoid){

		$this->db->select('td.id as itemid, td.nombre as item, dp.descripcion, dp.monto, dp.interes, dls.valor_ant, dls.valor, format(dls.valor - dls.valor_ant,3) as consumo, format((select sum(valor) as consumo_total from gc_detalle_lectura_servicio where idlectura = ls.id) - (select sum(valor_ant) as consumo_total from gc_detalle_lectura_servicio where idlectura = ls.id),3) as consumo_total, c.unidadmedida, c.montounidad', false)
						  ->from('gc_deuda_propiedad as dp')
						  ->join('gc_tipo_deuda_detalle as td','dp.idtipodeudadetalle = td.id')
						  ->join('gc_lectura_servicio as ls','dp.idcuenta = ls.idcuenta','left')
						  ->join('gc_detalle_lectura_servicio as dls','ls.id = dls.idlectura and dls.idpropiedad = dp.idpropiedad','left')
						  ->join('gc_cuenta c','dp.idcuenta = c.id','left')
		                  ->where('dp.idpropiedad', $propiedadid)
		                  ->where('dp.idperiodo', $periodoid)
		                  ->order_by('td.nombre asc');


		$query = $this->db->get();
		return $query->result();

	}	

	public function get_saldo_anterior_by_ggcc($ggccid){

		$this->db->select('gs.monto')
						  ->from('gc_ggcc_propiedad as g')
						  ->join('gc_ggcc_saldo as gs','gs.idggcc = g.id')
		                  ->where('g.id', $ggccid);


		$query = $this->db->get();
		$datos = $query->row();

		return is_null($query->row()) ? 0 : $datos->monto;

	}		


	public function get_saldo_anterior_by_propiedadid_periodoid($propiedadid,$periodoid){

		$this->db->select('gs.monto')
						  ->from('gc_ggcc_propiedad as g')
						  ->join('gc_ggcc_saldo as gs','gs.idggcc = g.id')
		                  ->where('g.idpropiedad', $propiedadid)
		                  ->where('g.idperiodo', $periodoid);


		$query = $this->db->get();
		$datos = $query->row();


		return is_null($query->row()) ? 0 : $datos->monto;

	}		


	public function get_ggcc_by_comunidad($comunidadid,$idperiodo = null){


		$ggcc_data = $this->db->select('gc.id as ggccid, p.id as periodoid, p.mes, p.anno, pe.autoriza, pe.genera, date_format(pe.fecha_vencimiento,"%d/%m/%Y") as fecha_vencimiento, pe.publica, sum(gc.monto) as monto, sum(gc.abonado) as abonado, sum(gc.saldo) as saldo ')
						  ->from('gc_ggcc_comunidad as gc')
						  ->join('gc_periodo as p','gc.idperiodo = p.id')
						  ->join('gc_periodo_estado as pe','pe.idperiodo = p.id and pe.idcomunidad = gc.idcomunidad','left')
		                  ->where('gc.idcomunidad', $comunidadid)
		                  ->where('gc.tipo', 'D')
		                  ->group_by('gc.idperiodo')
		                  ->order_by('p.id desc');

		$ggcc_data = is_null($idperiodo) ? $ggcc_data : $ggcc_data->where('gc.idperiodo',$idperiodo);                 
		$query = $this->db->get();

		return is_null($idperiodo) ? $query->result() : $query->row();

	}


	public function get_propiedad_by_periodo($idperiodo = null,$idpropiedad = null){


		$propiedad_data = $this->db->select('p.id, gp.idperiodo, p.numero, p.responsable, p.prorrateo, gp.monto, gs.monto as saldo_anterior, gp.abonado, gp.saldo, gp.id as ggccid ')
						  ->from('gc_propiedad p ')
						  ->join('gc_ggcc_propiedad as gp','gp.idpropiedad = p.id and gp.idperiodo = '. $idperiodo)
						  ->join('gc_ggcc_saldo as gs','gs.idggcc = gp.id','left')
		                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->order_by('p.numero asc');
		$propiedad_data = is_null($idpropiedad) ? $propiedad_data : $propiedad_data->where('p.id',$idpropiedad);                 
		$query = $this->db->get();

		return is_null($idpropiedad) ? $query->result() : $query->row();

	}	


	public function get_propiedad_by_periodo_full($idperiodo = null,$idpropiedad = null){


		$propiedad_data = $this->db->select(' p.id
											, gp.idperiodo
											, p.numero
											, p.responsable
											, p.prorrateo
											, (select sum(monto) as monto 
												from  gc_ggcc_item gi
												where idggcc = gp.id
												and    iditem = 1) as cobro_individual
											, (select sum(monto) as monto 
												from  gc_ggcc_item gi
												where idggcc = gp.id
												and    iditem = 2) as fondo_reserva
											, COALESCE((select sum(monto) as monto
												from 	gc_deuda_propiedad
												where 	idperiodo = ' . $idperiodo . '
												and 	idpropiedad = p.id
												and 	idtipodeudadetalle = 23),0) as agua
											, COALESCE((select sum(monto) as monto
												from 	gc_deuda_propiedad
												where 	idperiodo = ' . $idperiodo . '
												and 	idpropiedad = p.id
												and 	idtipodeudadetalle = 24),0) as gas								
											, COALESCE((select sum(monto) as monto
												from 	gc_deuda_propiedad
												where 	idperiodo = ' . $idperiodo . '
												and 	idpropiedad = p.id
												and 	idtipodeudadetalle = 7),0) as multas
											, COALESCE((select sum(monto) as monto
												from 	gc_deuda_propiedad
												where 	idperiodo = ' . $idperiodo . '
												and 	idpropiedad = p.id
												and 	idtipodeudadetalle = 8),0) as ajustes							
											, COALESCE((select sum(monto) as monto
												from 	gc_deuda_propiedad
												where 	idperiodo = ' . $idperiodo . '
												and 	idpropiedad = p.id
												and 	idtipodeudadetalle = 9),0) as cuotas_especiales
											, COALESCE((select sum(monto) as monto
												from 	gc_deuda_propiedad
												where 	idperiodo = ' . $idperiodo . '
												and 	idpropiedad = p.id
												and 	idtipodeudadetalle not in (7,8,9,23,24)),0) as otros_cobros	
											, gp.monto
											, gs.monto as saldo_anterior
											, gp.abonado
											, gp.saldo
											, gp.id as ggccid
											, (select count(gp.id) as cantidad
												from gc_ggcc_propiedad as gp
												inner join gc_periodo as per on gp.idperiodo = per.id
												inner join gc_periodo_estado as pe on per.id = pe.idperiodo and pe.idcomunidad = "' . $this->session->userdata('comunidadid') . '"
												where gp.idpropiedad = p.id
												and pe.publica is not null
												and gp.saldo > 0) as cuentas_impagas '
											, false)
						  ->from('gc_propiedad p ')
						  ->join('gc_ggcc_propiedad as gp','gp.idpropiedad = p.id and gp.idperiodo = '. $idperiodo)
						  ->join('gc_ggcc_saldo as gs','gs.idggcc = gp.id','left')
		                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->order_by('p.numero asc');
		$propiedad_data = is_null($idpropiedad) ? $propiedad_data : $propiedad_data->where('p.id',$idpropiedad);                 
		$query = $this->db->get();

		return is_null($idpropiedad) ? $query->result() : $query->row();

	}	

	public function get_ggcc_prorrateo_by_comunidad($comunidadid,$idperiodo = null){


		$ggcc_data = $this->db->select('gc.id as ggccid, p.id as periodoid, p.mes, p.anno, pe.autoriza, pe.genera, date_format(pe.fecha_vencimiento,"%d/%m/%Y") as fecha_vencimiento, pe.publica, sum(gc.monto) as monto, sum(gc.abonado) as abonado, sum(gc.saldo) as saldo ')
						  ->from('gc_ggcc_comunidad as gc')
						  ->join('gc_periodo as p','gc.idperiodo = p.id')
						  ->join('gc_periodo_estado as pe','pe.idperiodo = p.id and pe.idcomunidad = gc.idcomunidad','left')
		                  ->where('gc.idcomunidad', $comunidadid)
		                  ->where('gc.tipo', 'D')
		                  ->where('pe.autoriza is not null')
		                  ->where('pe.genera is null')		                  
		                  ->group_by('gc.idperiodo')
		                  ->order_by('p.id desc');

		$ggcc_data = is_null($idperiodo) ? $ggcc_data : $ggcc_data->where('gc.idperiodo',$idperiodo);                 
		$query = $this->db->get();

		return is_null($idperiodo) ? $query->result() : $query->row();

	}	


	public function get_ggcc_publicacion_by_comunidad($comunidadid,$idperiodo = null){


		/*$ggcc_data = $this->db->select('gc.id as ggccid, p.id as periodoid, p.mes, p.anno, pe.autoriza, pe.genera, date_format(pe.fecha_vencimiento,"%d/%m/%Y") as fecha_vencimiento, pe.publica, sum(gc.monto) as monto, sum(gc.abonado) as abonado, sum(gc.saldo) as saldo ')
						  ->from('gc_ggcc_comunidad as gc')
						  ->join('gc_periodo as p','gc.idperiodo = p.id')
						  ->join('gc_periodo_estado as pe','pe.idperiodo = p.id and pe.idcomunidad = gc.idcomunidad','left')
		                  ->where('gc.idcomunidad', $comunidadid)
		                  ->where('gc.tipo', 'D')
		                  ->where('pe.genera is not null')
		                  ->where('pe.publica is null')
		                  ->group_by('gc.idperiodo')
		                  ->order_by('p.id desc');*/


		$ggcc_data = $this->db->select('gc.id as ggccid, p.id as periodoid, p.mes, p.anno, pe.autoriza, pe.genera, date_format(pe.fecha_vencimiento,"%d/%m/%Y") as fecha_vencimiento, pe.publica, sum(gc.monto) as monto_deuda, sum(gc.abonado) as abonado_deuda, sum(gc.saldo) as saldo_deuda, sum(gc2.monto) as monto_fr, sum(gc2.abonado) as abonado_fr, sum(gc2.saldo) as saldo_fr ')
						  ->from('gc_periodo as p')
						  ->join('gc_periodo_estado as pe','pe.idperiodo = p.id and pe.idcomunidad = ' . $comunidadid)
						  ->join('gc_ggcc_comunidad as gc',"gc.idperiodo = pe.idperiodo and gc.tipo = 'D' and gc.idcomunidad = " . $comunidadid)
						  ->join('gc_ggcc_comunidad as gc2',"gc2.idperiodo = pe.idperiodo and gc2.tipo = 'FR'  and gc2.idcomunidad = " . $comunidadid,'left')
		                  ->where('pe.genera is not null')
		                  ->where('pe.publica is null')
		                  ->group_by('gc.idperiodo')
		                  ->order_by('p.id desc');


		$ggcc_data = is_null($idperiodo) ? $ggcc_data : $ggcc_data->where('gc.idperiodo',$idperiodo);                 
		$query = $this->db->get();

		return is_null($idperiodo) ? $query->result() : $query->row();

	}	


	public function publicar_ggcc($idperiodo){

		$this->db->trans_start();
		$this->db->select('pe.idperiodo')
						  ->from('gc_periodo_estado as pe')
		                  ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('pe.idperiodo', $idperiodo)
		                  ->where('pe.publica is not null');		
		$query = $this->db->get();
		$datos = $query->row();
		if(is_null($datos)){ //se valida de que gasto comun no se ha prorrateado


			$this->db->where('idcomunidad', $this->session->userdata('comunidadid'));	
			$this->db->where('idperiodo', $idperiodo);	
			$this->db->update('gc_periodo_estado', array('publica' => date('Y-m-d H:i:s'),
														 'delay_envio_min' => 10
														));	

			$datos_deuda_periodo = $this->get_deuda_by_comunidad_periodo($this->session->userdata('comunidadid'),$idperiodo);
			foreach ($datos_deuda_periodo as $deuda_periodo) { // actualiza saldo publicado de propiedad
				$this->db->query("update gc_propiedad set 
															saldo_publicado = saldo_publicado + " . $deuda_periodo->monto . "
															where id = " . $deuda_periodo->id);		
				
			}
			$this->db->trans_complete();
			return 1;
			/*if($this->db->affected_rows() > 0){ 
				return 1;
			}else{
				return -1;
			}*/
		}else{
			$this->db->trans_complete();
			return -1;
		}
	}





	public function reversar_ggcc($idperiodo){

		$this->db->trans_start();

		//VEMOS SI EL PERIODO QUE ESTAMOS ENVIANDO ESTÁ EN CONDICIONES DE SER REVERSADO
		$this->db->select('idperiodo')
							  ->from('gc_periodo_estado as pe')
			                  ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where('pe.idperiodo',$idperiodo)
			                  ->where('pe.genera is not null')
			                  ->where('pe.publica is null');		
		$query = $this->db->get();
		$datos_periodo = $query->result();

		if(count($datos_periodo) > 0){

			$this->load->model('admin');
			$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);

			$this->db->select('id , monto')
							  ->from('gc_ggcc_comunidad as c')
			                  ->where('c.idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where("c.tipo = 'FR'")
			                  ->where('c.idperiodo',$idperiodo);		
			$query = $this->db->get();
			$datos_fr = $query->row();


			if(!is_null($datos_fr)){ // SI TIENE FONDO DE RESERVA, DEBEN ELIMINARSE LOS DATOS
				$monto_fr = $datos_fr->monto;
				$idggcc_fr = $datos_fr->id;

				$datos_comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));
				$saldo_fondo_reserva_actual = $datos_comunidad->fondoreserva - $monto_fr;



				//restamos el saldo de fondo de reserva a la comunidad
				$this->db->query("update gc_comunidad set 
															fondoreserva = fondoreserva - " . $monto_fr . "
															where id = " . $this->session->userdata('comunidadid'));							


				// creamos una cartola negativa
				/*$data = array(
			      	'idcomunidad' => $this->session->userdata('comunidadid'),
			      	'idggcc' => $idggcc_fr,
			      	'glosa' =>  'Reversa de prorrateo de '. date2string($datos_periodo->mes,$datos_periodo->anno),
			        'monto' => (-1)*$monto_fr,				      	
			        'saldo' =>  $saldo_fondo_reserva_actual,
			        'created_at' => date("Y-m-d h:i:s")
				);
				
				$this->db->insert('gc_cartola_fondo_reserva', $data);	*/

				//eliminamos la cartola del fondo de reserva
				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('idggcc',$idggcc_fr);
				$this->db->delete('gc_cartola_fondo_reserva');


				//eliminamos el registro asociado a fondo de reserva
				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('id',$idggcc_fr);
				$this->db->delete('gc_ggcc_comunidad');

			}


			//COMENZAMOS A ELIMINAR EL PRORRATEO POR PROPIEDADES
			$this->db->select('gp.id, gp.idpropiedad, gp.monto ')
							  ->from('gc_ggcc_propiedad as gp')
							  ->join('gc_propiedad as p','gp.idpropiedad = p.id')
			                  ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where('gp.idperiodo',$idperiodo);		

			$query = $this->db->get();
			$propiedades = $query->result();
			foreach ($propiedades as $propiedad) {

				// ELIMINACION DE INTERESES
				$this->db->where('idpropiedad',$propiedad->idpropiedad);
				$this->db->where('idperiodo',$idperiodo);
				$this->db->where('idtipodeudadetalle = 12');
				$this->db->where('descripcion','Intereses por pago atrasado');
				$this->db->delete('gc_deuda_propiedad');

				

				// ELIMINACION DE ITEMS
				$this->db->where('idggcc',$propiedad->id);
				$this->db->delete('gc_ggcc_item');


				// ELIMINACION DE SALDO
				$this->db->where('idggcc',$propiedad->id);
				$this->db->delete('gc_ggcc_saldo');				

				//ELIMINACION GGCC
				$this->db->where('id',$propiedad->id);
				$this->db->delete('gc_ggcc_propiedad');		

				//restamos el saldo de deuda de la propiedad
				$this->db->query("update gc_propiedad set 
															saldo = saldo - " . $propiedad->monto . "
															where id = " . $propiedad->idpropiedad);		


				#OBTENEMOS DATOS DEL PERÍODO DIVIDIDO
				$this->db->select('c.id, c.monto, c.idlistado, c.fechapago, c.idformapago, c.idbanco, c.cheque, c.ruttitular, c.dvtitular, c.fechadeposito, c.observacion, c.created_at, p.numero')
				  ->from('gc_cartola_propiedad c ')
				  ->join('gc_propiedad p','c.idpropiedad = p.id')
				  ->join('gc_listado_abonos l','c.idlistado = l.id')
				  ->join('gc_listado_abonos l2','l2.idprotesto = l.id','left')
                  ->where('c.idpropiedad', $propiedad->idpropiedad)
                  ->where('c.idperiodo',$idperiodo)
                  ->where('c.activo = 1')
                  ->where('l2.id is null')
                  ->where('c.monto > 0')
                  ->order_by('c.created_at');	



                 $query_cartola = $this->db->get();
                 $data_cartola = $query_cartola->result();

                 //echo "<pre>";
                 //print_r($data_cartola); exit;

				foreach ($data_cartola as $cartola) {     

					// TOMO EL MONTO QUE TENGO EN NULO
					$this->db->select('COALESCE(SUM(monto),0) as monto ',false)
				             ->from('gc_cartola_propiedad')
                             ->where('idpropiedad', $propiedad->idpropiedad)
                             ->where('idlistado',$cartola->idlistado)
                             ->where('idperiodo is null')
			                 ->where('activo = 1');	




					$query = $this->db->get();
					$pago_sin_periodo = $query->row();

					$this->db->query("update gc_cartola_propiedad set 
																monto = monto + " . $pago_sin_periodo->monto . "
																where id = " . $cartola->id);	



					$this->db->query("update gc_cartola_caja set 
																monto = monto + " . $pago_sin_periodo->monto . "
																,glosa = 'Abono GC de Propiedad # " . $cartola->numero . "'
																where idabono = " . $cartola->id);

					// BORRA LOS MOVIMIENTOS DE CAJA ASOCIADOS A CARTOLA ELIMINADA
					$this->db->query("
										delete
										FROM 		gc_cartola_caja		
										where 	idabono in (select id
																   from  gc_cartola_propiedad
																	where idpropiedad = " . $propiedad->idpropiedad ."
																	and 	activo = 1
																	and 	idlistado = " . $cartola->idlistado . "
																	and 	idperiodo is null
																	)"
										);	

					//ELIMINACION CASOS SIN PERIODO
					$this->db->where('idpropiedad',$propiedad->idpropiedad);
					$this->db->where('idlistado',$cartola->idlistado);
					$this->db->where('idperiodo is null');
					//$this->db->where('idperiodo',$idperiodo);
					$this->db->delete('gc_cartola_propiedad');	







					#ELIMINAR DETALLE DE PAGOS POR PERIODO
					$this->db->where('id',$cartola->id);
					$this->db->update('gc_cartola_propiedad',array('idperiodo' => null));					

				}


			}


				$data_update = array(
			      	'genera' => null,
			      	'interes' => 0
				);

				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('idperiodo',$idperiodo);
				$this->db->update('gc_periodo_estado',$data_update);		

			$this->db->trans_complete();
			return true;
		}else{
			$this->db->trans_complete();
			return false;
		}



			
	}



	public function prorratear_ggcc($comunidadid,$ggcc,$array_fr){


		$this->db->trans_start();

		$fecha_calculo_prorrateo = date('Y-m-d');

		// obtiene monto deuda
		$this->db->select('gc.monto, gc.idperiodo')
						  ->from('gc_ggcc_comunidad gc')
		                  ->where('gc.id', $ggcc)
		                  ->where('gc.tipo = "D"');
		$query = $this->db->get();
		$ggcc_comunidad = $query->row();

		$this->db->select('pe.idperiodo')
						  ->from('gc_periodo_estado as pe')
		                  ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('pe.idperiodo', $ggcc_comunidad->idperiodo)
		                  ->where('pe.genera is not null');		
		$query = $this->db->get();
		$datos = $query->row();
		if(is_null($datos)){ //se valida de que gasto comun no se ha prorrateado

			$monto = $ggcc_comunidad->monto;


			$idperiodo = $ggcc_comunidad->idperiodo;

			// verifica si se solicitó fondo de reserva
			$monto_fr = 0;

			//INSERCION DE FONDO DE RESERVA
			if($array_fr['tipo_fr'] != 'sinfr'){ // si existe fondo de resenva
				$monto_fr = $array_fr['tipo_fr'] == 'pesos' ? $array_fr['monto_fr'] : (int)(($monto/100)*$array_fr['monto_fr']);
				//guarda fondo de reserva
				$data = array(
					      	'tipo' => 'FR',
					      	'idcomunidad' =>  $comunidadid,
					        'idperiodo' =>  $idperiodo,
					        'tipo_fr' => $array_fr['tipo_fr'],
					        'porcentaje' => $array_fr['tipo_fr'] == 'porcentaje' ? $array_fr['monto_fr'] : null,
					        'monto' =>  $monto_fr,
					        'abonado' =>  0,
					        'saldo' => $monto_fr
						);

				$this->db->insert('gc_ggcc_comunidad', $data);	
				$fondo_reserva_id = $this->db->insert_id();						

				// aumento el fondo de reserva de la comunidad
				$this->db->query("update gc_comunidad set 
															fondoreserva = fondoreserva + " . $monto_fr . "
															where id = " . $comunidadid);	



				$this->load->model('admin');
				$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);
				$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
				$saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;			

				$data = array(
			      	'idcomunidad' => $this->session->userdata('comunidadid'),
			      	'idggcc' => $fondo_reserva_id,
			      	'glosa' =>  'Abono a Fondo de Reserva por prorrateo de '. date2string($datos_periodo->mes,$datos_periodo->anno),
			        'monto' => $monto_fr,				      	
			        'saldo' =>  $saldo_fondo_reserva_actual,
			        'created_at' => date("Y-m-d h:i:s")
				);
				
				$this->db->insert('gc_cartola_fondo_reserva', $data);																					

			}


			$this->db->select('p.id, p.prorrateo, p.saldo_publicado as saldo ')
							  ->from('gc_propiedad as p')
			                  ->where('p.idcomunidad', $comunidadid)
			                  ->where('p.active = 1');		

			$query = $this->db->get();
			$propiedades = $query->result();

			foreach ($propiedades as $propiedad) {


				/***** CALCULAR PREVIAMENTE EL INTERÉS QUE DEBE PAGAR E INSERTARLO EN GC_DEUDA_PROPIEDAD ******/

					/**** OBTENEMOS EL ULTIMO PERIODO RECIBIDO PARA LA PROPIEDAD ******/
					$this->db->select('gp.id, gp.idperiodo, gp.monto, gp.saldo ')
									  ->from('gc_ggcc_propiedad gp')
									  ->join('gc_periodo_estado pe','gp.idperiodo = pe.idperiodo and pe.idcomunidad = ' . $comunidadid)
					                  ->where('gp.idpropiedad', $propiedad->id)
					                  ->where('pe.publica is not null')
					                 // ->where('pe.idperiodo <> 8')
					                  ->order_by('pe.publica desc')
					                  ->limit(1);	

					$query = $this->db->get();

				/*	if($propiedad->id == 1515){
								echo "<pre>";
								//var_dump($ultimos_pagos);
								echo $this->db->last_query();
								exit;

							}*/

					$ultimo_periodo = $query->row();	


					#LA IDEA ES CALCULAR EL INTERÉS SOBRE LA DEUDA EXISTENTE AL VENCIMIENTO DEL GASTO COMUN ANTERIOR

					if(!is_null($ultimo_periodo)){ // sólo si existen periodos previos se calcula
							$id_periodo_int = $ultimo_periodo->idperiodo;
							$monto_int = $ultimo_periodo->monto;
							$saldo_int = $ultimo_periodo->saldo;
							$idggcc_propiedad = $ultimo_periodo->id;
							//echo "idggcc anterior : ".$idggcc_propiedad."<br>";
							//echo "monto gc anterior : ".$monto_int . "<br>";

							/**** OBTENEMOS EL SALDO PREVIO E INTERESES DE ESE SALDO  AL MOMENTO DEL ULTIMO PERIODO ******/
							$this->db->select('id, monto, intereses ')
											  ->from('gc_ggcc_saldo')
							                  ->where('idggcc', $idggcc_propiedad)
							                  ->limit(1);					
							$query = $this->db->get();
							$ultimo_saldo = $query->row();

							$saldo_ant_int = $ultimo_saldo->monto;					                  			
							$saldo_intereses_int = $ultimo_saldo->intereses;

							#$saldo_efectivo_int = $saldo_ant_int - $saldo_intereses_int;
							#$saldo_efectivo_int = $saldo_efectivo_int < 0 ? 0 : $saldo_efectivo_int; // si es menor que cero, indica que sólo quedan intereses

							$saldo_efectivo_int = $saldo_ant_int - $saldo_intereses_int;
							if($saldo_ant_int >= 0){
								$saldo_efectivo_int = $saldo_efectivo_int < 0 ? 0 : $saldo_efectivo_int; // si es menor que cero, indica que sólo quedan intereses
							}							
							//echo "monto saldo anterior : ".$saldo_ant_int. "<br>";
							//echo "monto saldo intereses anterior : ".$saldo_intereses_int. "<br>";

							/****** OBTENEMOS LA FECHA DE VENCIMIENTO DEL ULTIMO PERIODO RECIBIDO PARA LA PROPIEDAD ******/
							$this->db->select('genera, publica, fecha_vencimiento, interes, tipo_interes ')
											  ->from('gc_periodo_estado')
							                  ->where('idperiodo', $id_periodo_int)
							                  ->where('idcomunidad', $comunidadid);	

							$query = $this->db->get();
							$ultimo_periodo = $query->row();	



							$fecha_vencimiento_int = $ultimo_periodo->fecha_vencimiento;
							$fecha_publica_int = $ultimo_periodo->publica;
							$fecha_genera_int = $ultimo_periodo->genera;
							$interes_int = $ultimo_periodo->interes;
							$tipo_interes_int = $ultimo_periodo->tipo_interes;
							//echo $fecha_vencimiento_int; exit;

							/******* CALCULAMOS TODOS LOS PAGOS REALIZADOS ENTRE LA PUBLICACION DEL ULTIMO GASTO COMUN, Y SU FECHA DE VENCIMIENTO *****/
							$sql_db_pagos = $this->db->select('COALESCE(SUM(monto),0) as monto ',false)
												  ->from('gc_cartola_propiedad')
								                  ->where('idpropiedad', $propiedad->id)
								                  //->where("created_at >= '" . $fecha_publica_int . "'")
								                  //->where("created_at >= '" . $fecha_genera_int . "'")
								                  ->where("fechapago <= '" . $fecha_vencimiento_int . "'")
								                  //->where("fechapago between '". substr($fecha_publica_int,0,10) ."' and '" . $fecha_vencimiento_int . "'")
								                  ->where('activo = 1');	
							
							//echo strtotime('2018-10-31')." --- ".strtotime($fecha_vencimiento_int); exit;

							/*if($tipo_interes_int == 'cm'){
								$sql_pagos = $sql_db_pagos->where("fechapago <= '" . $fecha_vencimiento_int . "'");

							}*/
							if($id_periodo_int != 8){
								$sql_pagos = $sql_db_pagos->where("created_at >= '" . $fecha_genera_int . "'");
							}

							$query = $this->db->get();
							//echo $this->db->last_query()."<br>";  exit;

							$ultimos_pagos = $query->row();	

							


							$pago_int = $ultimos_pagos->monto;
							//echo "pagos realizados : ".$pago_int. "<br>";


							//echo "id_periodo_int: ".$id_periodo_int."<br>";
							#echo "monto_int: ".$monto_int."<br>";
							//echo "saldo_int: ".$saldo_int."<br>";
							#echo "saldo_efectivo_int: ".$saldo_efectivo_int."<br>";
							#echo "pago_int: ".$pago_int."<br>";
							//echo "fecha_vencimiento_int: ".$fecha_vencimiento_int."<br>";
							//echo "interes_int: ".$interes_int."<br>";

							$monto_interes = 0;
							#OBTENEMOS AJUSTES DEL PERÍODO ACTUAL
							$this->db->select('COALESCE(SUM(ABS(monto)),0) as monto ',false)
												  ->from('gc_deuda_propiedad')
								                  ->where('idpropiedad', $propiedad->id)
								                  ->where('idtipodeudadetalle', 8)
								                  ->where('idperiodo',$id_periodo_int);
							$query = $this->db->get();
							$ajustes_periodo = $query->row();		
							$ajustes_int = $ajustes_periodo->monto;			


							#OBTENEMOS MULTAS DEL PERÍODO ACTUAL
							$this->db->select('COALESCE(SUM(ABS(monto)),0) as monto ',false)
												  ->from('gc_deuda_propiedad')
								                  ->where('idpropiedad', $propiedad->id)
								                  ->where('idtipodeudadetalle', 7)
								                  ->where('idperiodo',$id_periodo_int);
							$query = $this->db->get();
							$multas_periodo = $query->row();		
							$multas_int = $multas_periodo->monto;				


							#OBTENEMOS CUOTAS ESPECIALES DEL PERÍODO ACTUAL
							$this->db->select('COALESCE(SUM(ABS(monto)),0) as monto ',false)
												  ->from('gc_deuda_propiedad')
								                  ->where('idpropiedad', $propiedad->id)
								                  ->where('idtipodeudadetalle', 9)
								                  ->where('idperiodo',$id_periodo_int);
							$query = $this->db->get();
							$cuotas_especiales_periodo = $query->row();		
							$cuotas_especiales_int = $cuotas_especiales_periodo->monto;																													                  								
							#echo "ajustes_int: ".$ajustes_int."<br>";
							//ESTO ES POR UN ERROR EN EL SISTEMA.  EL PERIODO INICIAL NO DEBERÍA TENER SALDO ANTERIOR
							if($id_periodo_int == 8){
								$saldo_efectivo_int = 0;
							}

							### INTERES = GC ULTIMO PERIODO + SALDO ANTERIOR DE ULTIMO PERIODO - INTERESES ACTIVOS - PAGOS HASTA FECHA VENCIMIENTO - AJUSTES DEL PERÍODO ACTUAL
							//$base_calculo_interes = $monto_int + $saldo_efectivo_int - $pago_int - $ajustes_int;
							$base_calculo_interes = $monto_int + $saldo_efectivo_int - $pago_int - $ajustes_int - $multas_int - $cuotas_especiales_int;
							/*	echo "periodo_actual : " . $idperiodo . "<br>";
								echo "periodo_anterior : " . $id_periodo_int . "<br>";
								echo "idggcc_propiedad : ". $idggcc_propiedad . "<br>";
								echo "monto_int : ". $monto_int . "<br>";
								echo "saldo_efectivo_int : ". $saldo_efectivo_int . "<br>";
								echo "pago_int : ". $pago_int . "<br>";
								echo "ajustes_int : ". $ajustes_int . "<br>";
								echo "multas_int : ". $multas_int . "<br>";
								echo "cuotas_especiales_int : ". $cuotas_especiales_int . "<br>";
								echo "base_calculo_interes : ". $base_calculo_interes . "<br>";
								echo "tipo_interes_int : ". $tipo_interes_int . "<br>";*/


							/*if($propiedad->id == 1688){
								echo "<pre>";
								var_dump($ultimos_pagos);
								echo "periodo_actual : " . $idperiodo . "<br>";
								echo "periodo_anterior : " . $id_periodo_int . "<br>";
								echo "idggcc_propiedad : ". $idggcc_propiedad . "<br>";
								echo "monto_int : ". $monto_int . "<br>";
								echo "saldo_efectivo_int : ". $saldo_efectivo_int . "<br>";
								echo "pago_int : ". $pago_int . "<br>";
								echo "ajustes_int : ". $ajustes_int . "<br>";
								echo "multas_int : ". $multas_int . "<br>";
								echo "cuotas_especiales_int : ". $cuotas_especiales_int . "<br>";
								echo "base_calculo_interes : ". $base_calculo_interes . "<br>";
								echo "interes_int : " . $interes_int . "<br>";
								echo "tipo_interes_int : " . $tipo_interes_int . "<br>";
								exit;

							}*/

							//$saldo_anterior_efectivo = $base_calculo_interes - $monto_int;
							#echo "base calculo : ".$base_calculo_interes. "<br>";

							//echo "tasa interes : ".$interes_int. "<br>";

							if($base_calculo_interes > 0 && strtotime(date('Y-m-d')) > strtotime($fecha_vencimiento_int)){ // NO SE CALCULAN INTERESES SI GASTO COMUN ANTERIOR AÚN NO VENCE
								if($tipo_interes_int == 'cm'){  #CAPITALIZACIÓN MENSUAL
									$tasa_interes = 0;
									$monto_interes = (int)$base_calculo_interes*($interes_int/100);
								}else if($tipo_interes_int == 'cd'){ #CAPITALIZACIÓN DIARIA


									/******* CALCULAMOS TODOS LOS PAGOS REALIZADOS DESPUES DEL VENCIMIENTO HASTA FECHA CALCULO PRORRATEO *****/
									$this->db->select('COALESCE(SUM(monto),0) as monto ',false)
														  ->from('gc_cartola_propiedad')
										                  ->where('idpropiedad', $propiedad->id)
										                  ->where("fechapago > '" . $fecha_vencimiento_int . "'")
										                  ->where("fechapago <= '" . $fecha_calculo_prorrateo . "'")
										                  ->where('activo = 1');	
											
									$query = $this->db->get();
									//echo $this->db->last_query()."<br>"; 

									$ultimos_pagos_pos_venc = $query->row();	
									$pagos_pos_venc = $ultimos_pagos_pos_venc->monto;

									#SIGNIFICA QUE PAGÓ TODO FUERA DE PLAZO, POR ENDE SE CALCULARÁN INTERESES HASTA ESE DÍA
									$tmp_deuda = $base_calculo_interes;
									$fecha_dias_mora = '';
									if(($base_calculo_interes - $pagos_pos_venc) <= 0 ){

										$this->db->select('monto, fechapago ',false)
															  ->from('gc_cartola_propiedad')
											                  ->where('idpropiedad', $propiedad->id)
											                  ->where("fechapago > '" . $fecha_vencimiento_int . "'")
											                  ->where("fechapago <= '" . $fecha_calculo_prorrateo . "'")
											                  ->where('activo = 1');
										$query = $this->db->get();
										$lista_pagos = $query->result();		
										foreach ($lista_pagos as $dato_pago) {
											$tmp_deuda = $tmp_deuda - $dato_pago->monto;
											if($tmp_deuda <= 0){
												$fecha_dias_mora = $dato_pago->fechapago;
												break;
											}
										}
									}


									//echo $propiedad->id."<br>"; 
									$tasa_interes = round((pow((1+($interes_int/100)),(1/30)) - 1),6); // CONSIDERA HASTA 6 DECIMALES
									

									//17-08-2018
									$fecha_dias_mora = $fecha_dias_mora == '' ? $fecha_calculo_prorrateo : $fecha_dias_mora;
									$dias_mora = dias_transcurridos($fecha_vencimiento_int,$fecha_dias_mora);
									/*	if($propiedad->id == 1615){
											echo "<pre>";
											var_dump($ultimos_pagos);
											echo "periodo_actual : " . $idperiodo . "<br>";
											echo "periodo_anterior : " . $id_periodo_int . "<br>";
											echo "idggcc_propiedad : ". $idggcc_propiedad . "<br>";
											echo "monto_int : ". $monto_int . "<br>";
											echo "saldo_efectivo_int : ". $saldo_efectivo_int . "<br>";
											echo "pago_int : ". $pago_int . "<br>";
											echo "ajustes_int : ". $ajustes_int . "<br>";
											echo "multas_int : ". $multas_int . "<br>";
											echo "cuotas_especiales_int : ". $cuotas_especiales_int . "<br>";
											echo "base_calculo_interes : ". $base_calculo_interes . "<br>";
											echo "interes_int : " . $interes_int . "<br>";
											echo "tipo_interes_int : " . $tipo_interes_int . "<br>";
											echo "tasa_interes : " . $tasa_interes . "<br>";
											echo "dias_mora : " . $dias_mora . "<br>";
											echo "ultimos_pagos_pos_venc : " . $pagos_pos_venc . "<br>";
											echo "fecha_dias_mora : " . $fecha_dias_mora . "<br>";
											exit;

										}*/
									#DEUDA DESPUES DE FEC VENCIMIENTO ES MAYOR AL GASTO COMUN ANTERIOR
									#QUIERE DECIR QUE TIENE SALDOS ANTERIORES Y SALDO ACTUAL
									if($base_calculo_interes > $monto_int){ 

										#SE CALCULA EL INTERES DE LA DEUDA PASADA CON CAPITALIZACION MENSUAL
										$deuda_pasada = $base_calculo_interes - $monto_int;
										$monto_interes_mensual = (int)$deuda_pasada*($interes_int/100);

										#SE CALCULA EL INTERES DE LA DEUDA ACTUAL CON CAPITALIZACION DIARIA
										$deuda_actual = $monto_int;
										$monto_interes_diario = $deuda_actual * (pow((1+$tasa_interes),$dias_mora)-1);
										$monto_interes = $monto_interes_mensual + $monto_interes_diario;
									}else{

										$monto_interes = $base_calculo_interes * (pow((1+$tasa_interes),$dias_mora)-1);


									}
									

									/*if($propiedad->id == 1533){
										echo "monto ggcc anterior: ".$monto_int."<br>";
										echo "saldo anterior: ".$saldo_efectivo_int."<br>";
										echo "pagos: ".$pago_int."<br>";
										echo "base calculo: ".$base_calculo_interes."<br>";
										echo "tasa interes diario : ". ($tasa_interes*100) ."<br>";
										echo "tasa interes mensual : ". $interes_int."<br>";
										echo "deuda pasada : ". $deuda_pasada."<br>";
										echo "interes mensual : ". $monto_interes_mensual."<br>";
										echo "deuda actual : ". $monto_int."<br>";
										echo "interes diario : ". $monto_interes_diario."<br>";
										echo "interes total : ". $monto_interes."<br>";
										exit;
									}*/



									#ALMACENA EL INTERES DIARIO CALCULADO
									$this->db->where('idcomunidad',$comunidadid);
									$this->db->where('idperiodo',$id_periodo_int);
									$this->db->update('gc_periodo_estado', array('interes_diario' => $tasa_interes*100));	
								}

								//echo "monto_interes:".$monto_interes."<br>";
							}else{
								$monto_interes = 0;
							}
							

							

							//echo "monto interes : ".$monto_interes. "<br>";
							if($monto_interes > 0){
								$this->load->model('account');
					       		$parametros = array(
					       						'idpropiedad' => $propiedad->id,
					       						'idperiodo' => $idperiodo,
					       						'concepto' => 12,
					       						'fecuso' => date("d/m/Y"),
					       						'monto' => $monto_interes,
					       						'descripcion' => "Intereses por pago atrasado",
					       						'interes' => $interes_int,
					       						'nombrearchivo' => '',
					       						'nombrerealarchivo' => ''
								       			);       		

					       		
								$this->account->add_cuenta_individual($parametros);
							}

						}


				/************************************************************************************************/

				$monto_ggcc_individual = 0;
				$this->db->select(' COALESCE(SUM(dp.monto),0) as monto',false)
								  ->from('gc_deuda_propiedad as dp')
				                  ->where('dp.idpropiedad', $propiedad->id)
				                  ->where('dp.idperiodo', $idperiodo);	


				$query = $this->db->get();
				$cobro_individual = $query->row();			

				
		



				/**** suma ggcc segun prorrateo ****/
				$monto_ggcc_deuda = (int)(($monto/100)*$propiedad->prorrateo);
				$monto_ggcc_fr = (int)(($monto_fr/100)*$propiedad->prorrateo);
				$monto_ggcc_individual = $cobro_individual->monto;
				$monto_ggcc_total = $monto_ggcc_deuda + $monto_ggcc_fr + $monto_ggcc_individual;
				$saldo_propiedad = $propiedad->saldo;



				if($saldo_propiedad < 0){ //tiene saldo a favor

					if(($saldo_propiedad + $monto_ggcc_total) < 0){ #SALDO ES MAYOR AL GASTO COMUN GENERADO (SE DEBE DIVIDIR EL ÚLTIMO ABONO)
						$ocupa_saldo = $monto_ggcc_total;
						
						# OBTENEMOS DE LA CARTOLA DE PAGOS, EL CASO NO ASOCIADO (DEBERÍA COINCIDIR CON EL MONTO DEL SALDO A FAVOR)   DEBE SACAR EL ÚLTIMO IDLISTADO ACTIVO
						$this->db->select('c.id, c.monto, c.idlistado, c.fechapago, c.idformapago, c.idbanco, c.cheque, c.ruttitular, c.dvtitular, c.fechadeposito, c.observacion, c.created_at, l.idprotesto, l2.id as idlistadoprotesto')
						  ->from('gc_cartola_propiedad c')
						  ->join('gc_listado_abonos l','c.idlistado = l.id')
						  ->join('gc_listado_abonos l2','l2.idprotesto = l.id','left')
		                  ->where('c.idpropiedad', $propiedad->id)
		                  ->where('c.idperiodo is null')
		                  ->where('c.activo = 1')
		                  ->where('l2.id is null')
		                  ->where('c.monto > 0')
		                  ->order_by('c.created_at');	

		                 $query_cartola = $this->db->get();
		                 $data_cartola = $query_cartola->result();
		                 
		                 $compara_saldo = abs($monto_ggcc_total);
		                 foreach ($data_cartola as $cartola) {
		                 	$monto_cartola = $cartola->monto;
		                 	$dif_abono_cartola = $compara_saldo - $monto_cartola;
		                 	if($dif_abono_cartola < 0){ # CARTOLA CUBRE TOTALMENTE LA DEUDA, HAY QUE DIVIDIR

								$this->db->where('id',$cartola->id);
								$this->db->update('gc_cartola_propiedad', array('idperiodo' => $idperiodo,
																				'monto' => $compara_saldo));


								$data_ncartola = array(
									'idlistado' => $cartola->idlistado,
							      	'idpropiedad' => $propiedad->id,
							      	'idperiodo' =>  null,
							        'fechapago' => $cartola->fechapago,				      	
							        'monto' =>  abs($dif_abono_cartola),
							        'idformapago' =>  $cartola->idformapago,
							        'idbanco' =>  $cartola->idbanco,
							        'cheque' =>  $cartola->cheque,
							        'ruttitular' =>  $cartola->ruttitular,
							        'dvtitular' =>  $cartola->dvtitular,
							        'fechadeposito' => $cartola->fechadeposito,
							        'observacion' => $cartola->observacion
								);
								// guarda cartola
								$this->db->insert('gc_cartola_propiedad', $data_ncartola);

								break;

		                 	}else if($dif_abono_cartola > 0){ #MARCAMOS CARTOLA PARA EL PERIODO Y REBAJAMOS SALDO
								$this->db->where('id',$cartola->id);
								$this->db->update('gc_cartola_propiedad', array('idperiodo' => $idperiodo));
								$compara_saldo = $compara_saldo - $monto_cartola;
		                 	}else if($dif_abono_cartola == 0){  #SE REBAJA Y SE CIERRA EL CICLO
								$this->db->where('id',$cartola->id);
								$this->db->update('gc_cartola_propiedad', array('idperiodo' => $idperiodo));
								$compara_saldo = $compara_saldo - $monto_cartola;
								break;

		                 	}
		                 }


					}else{ #TODO EL SALDO A FAVOR PASA A PERTENECER AL PERIODO ACTUAL
						$ocupa_saldo = abs($saldo_propiedad);

						$this->db->where('idpropiedad',$propiedad->id);
						$this->db->where('idperiodo is null');
						$this->db->where('activo = 1');
						$this->db->update('gc_cartola_propiedad', array('idperiodo' => $idperiodo));									

					}
					#$ocupa_saldo = ($saldo_propiedad + $monto_ggcc_total) < 0 ? $monto_ggcc_total : abs($saldo_propiedad);

					



				}else{
					$ocupa_saldo = 0;
				}


				/**** genera ggcc para periodo ****/					
				$data = array(
					      	'idpropiedad' => $propiedad->id,
					      	'idperiodo' =>  $idperiodo,
					        'monto' =>  $monto_ggcc_total,
					        'abonado' =>  $ocupa_saldo,
					        'saldo' =>  $monto_ggcc_total - $ocupa_saldo,
					        'prorrateo' => $propiedad->prorrateo,
					        'created_at' => date("Y-m-d H:i:s")
						);
				// guarda ggcc
				$this->db->insert('gc_ggcc_propiedad', $data);				

				$ggcc_propiedad_id = $this->db->insert_id();

				// guarda detalle deuda
				$data = array(
					      	'idggcc' => $ggcc_propiedad_id,
					      	'iditem' =>  1,
					        'descripcion' =>  '',
					        'monto' =>  $monto_ggcc_deuda
						);
				// guarda ggcc
				$this->db->insert('gc_ggcc_item', $data);				


				if($array_fr['tipo_fr'] != 'sinfr'){ // si existe fondo de resenva
					// guarda detalle fondo de reserva
					$data = array(
						      	'idggcc' => $ggcc_propiedad_id,
						      	'iditem' =>  2,
						        'descripcion' =>  '',
						        'monto' =>  $monto_ggcc_fr
							);
					// guarda ggcc
					$this->db->insert('gc_ggcc_item', $data);				

				}

				if($monto_ggcc_individual > 0){
					// guarda detalle cobros individuales
					$data = array(
						      	'idggcc' => $ggcc_propiedad_id,
						      	'iditem' =>  3,
						        'descripcion' =>  '',
						        'monto' =>  $monto_ggcc_individual
							);
					// guarda ggcc
					$this->db->insert('gc_ggcc_item', $data);				


				}


				// CALCULA MONTO DE INTERESES ACTIVOS
				$this->db->select('COALESCE(SUM(dp.monto),0) as monto ',false)
								  ->from('gc_ggcc_propiedad gp')
								  ->join('gc_deuda_propiedad dp','gp.idpropiedad = dp.idpropiedad and gp.idperiodo = dp.idperiodo and dp.idtipodeudadetalle = 12')
								  ->join('gc_periodo_estado pe','dp.idperiodo = pe.idperiodo and pe.idcomunidad = ' . $this->session->userdata('comunidadid'))
								  ->where('pe.publica is not null')							  
								  ->where('gp.idpropiedad', $propiedad->id)
				                  ->where('gp.idperiodo <>', $idperiodo)
				                  ->where('gp.saldo > 0');	


				$query = $this->db->get();
				$calculo_intereses = $query->row();					                  			

				$saldo_intereses = $calculo_intereses->monto;



				/**** almacena saldo anterior ****/					
				$data = array(
					      	'idggcc' => $ggcc_propiedad_id,
					        'monto' =>  $saldo_propiedad,
					        'intereses' =>  $saldo_intereses,
						);
				// guarda ggcc
				$this->db->insert('gc_ggcc_saldo', $data);	



				//suma saldo a propiedad
				$this->db->query("update gc_propiedad set 
															saldo = saldo + " . $monto_ggcc_total . "
															where id = " . $propiedad->id);			

			}

			// Se quita guardado de fecha de vencimiento ya que se almacenó antes
			$this->db->query("update gc_periodo_estado set 
														genera = '" . date("Y-m-d H:i:s") . "',
														tipo_interes = '" . $array_fr['tipo_cap']  . "',
														interes = '" . $array_fr['interes'] . "'
														where idperiodo = " . $idperiodo . "
														and idcomunidad = " . $comunidadid);

		}

		//exit;
		$this->db->trans_complete();
	}


	/*public function generar_comprobante($comunidadid,$idperiodo,$idpropiedad){

			$this->load->model('admin');
			$datos_comunidad = $this->admin->datos_comunidad($comunidadid);

			$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);
			$datos_propiedad = $this->admin->get_propiedad_by_id($idpropiedad);
			$datos_ggcc = $this->get_ggcc_by_comunidad($comunidadid,$idperiodo);
			$datos_detalle_ggcc = $this->get_detalle_by_propiedadid_periodoid_no_individual($idpropiedad,$idperiodo);
			$datos_detalle_individual_ggcc = $this->get_detalle_by_propiedadid_periodoid_individual($idpropiedad,$idperiodo);
			
			$saldo_anterior = $this->get_saldo_anterior_by_propiedadid_periodoid($idpropiedad,$idperiodo);

			$estacionamientos = $this->admin->get_estacionamientos_by_propiedad($idpropiedad);
			$bodegas = $this->admin->get_bodegas_by_propiedad($idpropiedad);			
			

			//get_detalle_by_ggcc($ggccid);
			//var_dump($datos_comunidad); exit;
			$html = '<html>
					<head>
					<style type="text/css">
					.rounded {
					 border:0.1mm solid #220044;
					 background-color: #E4E3EA;
					 background-gradient: linear #E4E3EA #E4E3EA 0 1 0 0.5;
					 border-radius: 2mm;
					 background-clip: border-box;
					 padding: 1em;
						}

					.tableClass { 
						background-color: #e3ece4; 
						border-collapse: collapse;
						font-family: DejaVuSansCondensed;
						font-size: 9pt; 
						line-height: 1.2;
						margin-top: 2pt; 
						margin-bottom: 5pt; 
						width: 70%;
						topntail: 0.02cm solid #495b4a; 
					}

					.theadClass { 
						font-weight: bold; 
						vertical-align: bottom; 
					}

					.tdClass { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:left;
						padding-right: 4mm; 
						padding-top: 0.5mm; 
						padding-bottom: 0.5mm;
						border-top: 1px solid #FFFFFF; 
					}

					.tdClassCenter { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:center;
						padding-right: 4mm; 
						padding-top: 0.5mm; 
						padding-bottom: 0.5mm;
						border-top: 1px solid #FFFFFF; 
					}					

					.tdClassNumber { 
						text-align:right;
					}

					.headerRow td, .headerRow th { 
						background-gradient: linear #b7cebd #f5f8f5 0 1 0 0.2; 
						padding: 1mm; 
					}	

					.header4 { 
						font-weight: ; 
						font-size: 13pt; 
						color: #080636;
						font-family: DejaVuSansCondensed, sans-serif; 
						margin-top: 10pt; 
						margin-bottom: 7pt;
						text-align: center;
						margin-collapse:collapse; page-break-after:avoid; }										
					</style>
			</head>
					<body>';


			$html .= '
						<br>
						<p><h4 class="header4">Colilla de Cobro de Gastos Comunes de ' . date2string($datos_periodo->mes,$datos_periodo->anno) . ' </h4></p>
						<hr>
						<br>
						<div class="rounded">
							<h3><b>Copropietario:</b> ' . $datos_propiedad->responsable. '</h3>
							<table class="tableClass"  >
								<thead class="theadClass">
								<tr class="headerRow">
									<th><p>Unidad</p></th>
									<th><p>Nombre</p></th>
									<th>Prorrateo</th>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td class="tdClassCenter" >Propiedad</td>
										<td class="tdClassCenter" >' . $datos_propiedad->numero . '</td>
										<td class="tdClassCenter" >' . $datos_propiedad->prorrateo_propiedad . ' % </td>
									</tr>	
									';

								foreach ($estacionamientos as $estacionamiento) {
									$html .= '<tr>
										<td class="tdClassCenter" >Estacionamiento</td>
										<td class="tdClassCenter" >' . $estacionamiento->nombre . '</td>
										<td class="tdClassCenter" >' . $estacionamiento->prorrateo . ' % </td>
									</tr>';
								}

								foreach ($bodegas as $bodega) {
									$html .= '<tr>
										<td class="tdClassCenter" >Bodega</td>
										<td class="tdClassCenter" >' . $bodega->nombre . '</td>
										<td class="tdClassCenter" >' . $bodega->prorrateo . ' % </td>
									</tr>';
								}


								$html .= '</tbody>	
							</table>
						</div>
						<hr>
						<br>
						<div class="rounded">
						<table class="tableClass"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th><p>Item</p></th>
						<th>&nbsp;</th>
						<th>Valor</th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" colspan="2"><b>Total Gasto Com&uacute;n</b></td>
						<td class="tdClass tdClassNumber"><b>$ ' . number_format($datos_ggcc->monto,0,".",".") . '</b></td>
						</tr>';

				$subtotal = 0;
				foreach($datos_detalle_ggcc as $detalle){ // DEUDA Y FONDO DE RESERVA
						$texto_item = $detalle->itemid == 1 ? 'Cobro Individual (Prorrateo ' . $datos_propiedad->prorrateo .' %)' : $detalle->item;

						$html .= '<tr>
									<td class="tdClass" colspan="2">' .  $texto_item . '</td>
									<td class="tdClass tdClassNumber">$ ' .  number_format($detalle->monto,0,".",".") . '</td>
								  </tr>	';
						$subtotal += $detalle->monto;
				}

				foreach($datos_detalle_individual_ggcc as $detalle){ // COBROS INDIVIDUALES
						$texto_item = $detalle->item;

						$html .= '<tr>
									<td class="tdClass" colspan="2">' .  $texto_item . '</td>
									<td class="tdClass tdClassNumber">$ ' .  number_format($detalle->monto,0,".",".") . '</td>
								  </tr>	';
						$subtotal += $detalle->monto;
				}				

				$total = $subtotal + $saldo_anterior;

				$html .= '
						<tr>
						<td class="tdClass" colspan="2"><b>Subtotal Cobro del Mes</b></td>
						<td class="tdClass tdClassNumber"><b>$ ' .  number_format($subtotal,0,".",".") . '</b></td>
						</tr>
						<tr>
						<td class="tdClass" colspan="2">&nbsp;</td>
						<td class="tdClass">&nbsp;</td>
						</tr>																																																																												
						<tr>
						<td class="tdClass" colspan="2">Saldo Anterior</td>
						<td class="tdClass tdClassNumber">$ ' . number_format($saldo_anterior,0,".",".")  . '</td>
						</tr>
						<tr>
						<td class="tdClass" colspan="2"><b>Total a Pagar del Mes</b></td>
						<td class="tdClass tdClassNumber"><b>$ ' .  number_format($total,0,".",".") . '</b></td>
						</tr>
						</tbody>
						</table>
						<br><br>
</div>
						<br>
						<hr>
						<br>
						<br>
						<br>
						<br>
						<br>
						<p style="text-align:right"><b>Fecha Emisión de Pago: ' . date("d") . ' de ' . month2string(date("m")) . ' de ' . date("Y") . '</b></p>
						<p style="text-align:right"><b>Pagar Hasta: ' . substr($datos_ggcc->fecha_vencimiento,0,2) . ' de ' . month2string(substr($datos_ggcc->fecha_vencimiento,3,2)) . ' de ' . substr($datos_ggcc->fecha_vencimiento,6,4) . '</b></p>
		';

			$html .=	"</body>
						</html>";
			$this->load->library("mpdf");
			$mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				8,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				);  
			//echo $html; exit;
			$mpdf->SetHeader('Condominio '. $datos_comunidad->nombre . ' - ' .$datos_comunidad->comuna . ' - RUT: ' .number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);
			$mpdf->WriteHTML($html);
			$mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');


			// SI NO EXISTE EL DIRECTORIO, LO CREAMOS
			//if(!file_exists("uploads/ggcc/".$idpropiedad)){
			//	mkdir("uploads/ggcc/".$idpropiedad,0777,true);
			//}

			// SE ALMACENA EL ARCHIVO
			$nombre_archivo = date("Y")."_".date("m")."_".date("d")."_".$datos_propiedad->numero.".pdf";
			$mpdf->Output($nombre_archivo, "I");
			


	}	*/



	private function genera_grafico($ultimos_periodos,$comunidadid,$idpropiedad,$idperiodo){

		$this->load->library('JpGraph/Graph');
        require_once (APPPATH.'/libraries/JpGraph/jpgraph_bar.php');
        require_once (APPPATH.'/libraries/JpGraph/jpgraph_line.php');

        $this->load->model('admin');
        $datos_periodo = $this->admin->get_periodo_by_id($idperiodo);
		// We need some data
		$array_meses = array();
		$array_deuda_prop = array();
		$mes = "";

		$periodo_actual = (int)$datos_periodo->anno.str_pad($datos_periodo->mes,2,"0",STR_PAD_LEFT);
		foreach ($ultimos_periodos as $dato_mensual) {
			$periodo_consulta = (int)$dato_mensual['anno'].str_pad($dato_mensual['mes'],2,"0",STR_PAD_LEFT);
			if($periodo_consulta < $periodo_actual){
				$mes = date2string($dato_mensual['mes'],$dato_mensual['anno']) == 'Saldo Inicial' ? 'Saldo Inicial' : substr(month2string($dato_mensual['mes']),0,3);
				array_push($array_meses,$mes);
				array_push($array_deuda_prop,(int)$dato_mensual['monto']);

			}
		}
		
		//$datay=array(0.3031,0.3044,0.3049,0.3040,0.3024,0.3047);

		//if(!is_null($datos_periodo->publica)){  //si ya se publicó, entonces se elimina el ultimo elemento
		//	array_pop($array_deuda_prop);
		//	array_pop($array_meses);
		//}
		$datay = $array_deuda_prop;
		$datax = $array_meses;

		// Setup the graph. 
		$graph = new Graph(600,300);	
		$graph->img->SetMargin(60,30,30,40);
		$graph->SetScale("textlin");
		$graph->SetMarginColor("white");
		$graph->SetShadow();

		// Create the bar pot
		$bplot = new BarPlot($datay);
		$bplot->SetWidth(0.6);

		// This is how you make the bar graph start from something other than 0
		$bplot->SetYMin(0.302);

		// Setup color for gradient fill style 
		$tcol=array(196,201,187);
		$fcol=array(196,201,187);
		$bplot->SetFillGradient($fcol,$tcol,GRAD_HOR);
		$bplot->SetFillColor("white");
		$graph->Add($bplot);

		// Set up the title for the graph
		$graph->title->Set("Ultimos cobros");
		$graph->title->SetColor("black");
		$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);

		// Setup color for axis and labels
		$graph->xaxis->SetColor("black","black");
		$graph->yaxis->SetColor("black","black");

		// Setup font for axis
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
		$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,10);


		// Setup X-axis labels
		$graph->xaxis->SetTickLabels($datax);
		//$graph->xaxis->SetLabelAngle(50);

		// Setup X-axis title (color & font)
		$graph->xaxis->title->Set("Periodos");
		$graph->xaxis->title->SetColor("black");
		$graph->xaxis->title->SetFont(FF_VERDANA,FS_BOLD,10);

		// SI NO EXISTE EL DIRECTORIO, LO CREAMOS
		if(!file_exists("graph/ggcc/".$idpropiedad)){
			mkdir("graph/ggcc/".$idpropiedad,0777,true);
		}
		
		$graph->Stroke('graph/ggcc/'.$idpropiedad.'/graph_'.$idperiodo.'.png');
		

	}



public function generar_contenido_comprobante($comunidadid,$idperiodo,$idpropiedad){



			$this->load->model('admin');


			$comunidad = $this->admin->get_comunidades($comunidadid);



			$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);



			$ingresos = $this->get_ingresos_totales_by_periodo($comunidadid,$idperiodo);
			$egresos = $this->get_egresos_totales_by_periodo($comunidadid,$idperiodo);



			$datos_propiedad = $this->admin->get_propiedad_by_id($idpropiedad);
			$datos_ggcc = $this->get_ggcc_by_comunidad($comunidadid,$idperiodo);
			$datos_detalle_ggcc = $this->get_detalle_by_propiedadid_periodoid_no_individual($idpropiedad,$idperiodo);

			$datos_detalle_individual_ggcc = $this->get_detalle_by_propiedadid_periodoid_individual($idpropiedad,$idperiodo);

			$saldo_anterior = $this->get_saldo_anterior_by_propiedadid_periodoid($idpropiedad,$idperiodo);

			$estacionamientos = $this->admin->get_estacionamientos_by_propiedad($idpropiedad);
			$bodegas = $this->admin->get_bodegas_by_propiedad($idpropiedad);	


			$this->load->model('report');		
			$ultimos_periodos = $this->report->gc_mensual_prop($comunidadid,$idpropiedad);

			$reverse_ultimos_periodos = array_reverse($ultimos_periodos);

			if(count($ultimos_periodos) > 1){
				//if(!file_exists('graph/ggcc/'.$idpropiedad.'/graph_'.$idperiodo.'.png')){ // GENERA GRAFICO SOLO EN CASO QUE NO EXISTA IMAGEN

				if(file_exists('graph/ggcc/'.$idpropiedad.'/graph_'.$idperiodo.'.png')){ // GENERA GRAFICO SOLO EN CASO QUE NO EXISTA IMAGEN
					unlink('graph/ggcc/'.$idpropiedad.'/graph_'.$idperiodo.'.png');
				}

					$this->genera_grafico($ultimos_periodos,$comunidadid,$idpropiedad,$idperiodo);
				//}			
			}


			$html = '<html>
					<head>
					<style type="text/css">
					.rounded {
					 border:0.1mm solid #220044;
					 background-color: #FAFAFA;
					 background-clip: border-box;
					 padding: 1em;
						}

					.recto {
					 border:0.1mm solid #000000;
					 background-color: #FAFAFA;
					 background-clip: border-box;
					 padding: 1em;
						}


					.tableClass { 
						background-color: #e3ece4; 
						border-collapse: collapse;
						font-family: DejaVuSansCondensed;
						font-size: 8pt; 
						line-height: 1.2;
						margin-top: 2pt; 
						margin-bottom: 5pt; 
						width: 70%;
						topntail: 0.02cm solid #495b4a; 
					}

					.theadClass { 
						font-weight: bold; 
						vertical-align: bottom; 
					}

					.tdClass { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:left;
						padding-right: 4mm; 
						padding-top: 0.3mm; 
						padding-bottom: 0.3mm;
						border-top: 1px solid #FFFFFF; 
					}

					.tdClassCenter { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:center;
						padding-right: 4mm; 
						padding-top: 0.3mm; 
						padding-bottom: 0.3mm;
						border-top: 1px solid #FFFFFF; 
					}					

					.tdClassNumber { 
						text-align:right;
					}

					.headerRow td, .headerRow th { 
						background-gradient: linear #b7cebd #ffffff 0 1 0 0.2; 
						padding: 1mm; 
					}	

					.header4 { 
						font-weight: ; 
						font-size: 13pt; 
						color: #080636;
						font-family: DejaVuSansCondensed, sans-serif; 
						margin-top: 10pt; 
						margin-bottom: 7pt;
						text-align: center;
						margin-collapse:collapse; page-break-after:avoid; }										
					</style>
			</head>
					<body>';

			$logo = $comunidad->logo == '' || is_null($comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/'. $this->session->userdata('comunidadid') . '/' . $comunidad->logo;


			$firma = $comunidad->firma == '' || is_null($comunidad->firma) ? '&nbsp;' : '<img src="uploads/firmas/'. $this->session->userdata('comunidadid') . '/' . $comunidad->firma . '" width="150px"> ';		


			$html .= '
						<p><h4 class="header4"><br>Gastos Comunes Individuales de ' . date2string($datos_periodo->mes,$datos_periodo->anno) . '<br><br><img src="' .  $logo  .  '" width="100px"></h4></p>
						<hr>
						<br>
						<div class="recto">
							<h4><b>Nombre Copropietario:</b> ' . $datos_propiedad->responsable. '</h4>
							<table width="60%" >
								<thead class="theadClass">
								<tr class="headerRow">
									<th><p>Unidad</p></th>
									<th><p>Nombre</p></th>
									<th><p>Direcci&oacute;n</p></th>
									<th>Prorrateo</th>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td class="tdClassCenter" >Propiedad</td>
										<td class="tdClassCenter" >' . $datos_propiedad->numero . '</td>
										<td class="tdClassCenter" >' . $datos_propiedad->direccion . '</td>
										<td class="tdClassCenter" >' . $datos_propiedad->prorrateo_propiedad . ' % </td>
									</tr>	
									';

								foreach ($estacionamientos as $estacionamiento) {
									$html .= '<tr>
										<td class="tdClassCenter" >Estacionamiento</td>
										<td class="tdClassCenter" >' . $estacionamiento->nombre . '</td>
										<td class="tdClassCenter" >&nbsp;</td>
										<td class="tdClassCenter" >' . $estacionamiento->prorrateo . ' % </td>
									</tr>';
								}

								foreach ($bodegas as $bodega) {
									$html .= '<tr>
										<td class="tdClassCenter" >Bodega</td>
										<td class="tdClassCenter" >' . $bodega->nombre . '</td>
										<td class="tdClassCenter" >&nbsp;</td>
										<td class="tdClassCenter" >' . $bodega->prorrateo . ' % </td>
									</tr>';
								}


								$html .= '</tbody>	
							</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="80%"><p>Item</p></th>
						<th width="20%">Valor</th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" ><b>Total Gasto Com&uacute;n</b></td>
						<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_ggcc->monto,0,".",".") . '</b></td>
						</tr>';

				$subtotal = 0;

				foreach($datos_detalle_ggcc as $detalle){ // DEUDA Y FONDO DE RESERVA
						$texto_item = $detalle->itemid == 1 ? 'Cobro Individual (Prorrateo ' . $datos_propiedad->prorrateo .' %)' : $detalle->item;

						if(!is_null($detalle->tipo_fr)){
							if($detalle->tipo_fr == 'porcentaje'){
								$dato_extra = " ( " . $detalle->porcentaje . " % )";
							}else{
								$dato_extra = "";
							}

						}else{
							$dato_extra = "";

						}

						$html .= '<tr>
									<td class="tdClass" >' .  $texto_item . $dato_extra . ' </td>
									<td class="tdClass tdClassNumber">$ ' .  number_format($detalle->monto,0,".",".") . '</td>
								  </tr>	';
						$subtotal += $detalle->monto;
				}
				//print_r($datos_detalle_individual_ggcc); exit;
				$subtotal_otros_cobros = 0;
				$html_otros_cobros = "";
				foreach($datos_detalle_individual_ggcc as $detalle){ // COBROS INDIVIDUALES
						$texto_item = $detalle->item;
						$cobro_mes = true;
						if(!is_null($detalle->valor)){ # SI ES LECTURA INDIVIDUAL SE MUESTRA LA LECTURA
							$unidadmedida = is_null($detalle->unidadmedida) ? "unidad" : $detalle->unidadmedida;
							$dato_extra = ". Consumo: ".$detalle->consumo." " . $unidadmedida . " [". $detalle->valor." " . $unidadmedida . " - " . $detalle->valor_ant . " " . $unidadmedida . "]/ Consumo Comunidad: " . (int) $detalle->consumo_total . " " . $unidadmedida.". Valor " . $unidadmedida . " : $ " . number_format($detalle->montounidad,0,",",".") . "." ;
						}else if($detalle->itemid == 8 || $detalle->itemid == 9){ # SI ES CUOTA ESPECIAL SE MUESTRA LA DESCRIPCION
							$cobro_mes = false;
							$dato_extra = " ( ".$detalle->descripcion . " )";
						}else if($detalle->itemid == 7){ # SI ES CUOTA ESPECIAL SE MUESTRA LA DESCRIPCION
							$cobro_mes = false;					
							$dato_extra = " ( ".$detalle->descripcion . " )";						
						}else if($detalle->itemid == 12){ # SI ES INTERESES SE MUESTRA EL PORCENTAJE
							$dato_extra = is_null($detalle->interes) ? "" : " ( ".$detalle->interes . " % )";
						}else{
							$dato_extra = "";
						}
						//$dato_extra = is_null($detalle->valor) ? '' : ". Consumo: ".$detalle->consumo." m3 [". $detalle->valor." m3 - " . $detalle->valor_ant . " m3]/ Consumo edificio: " . $detalle->consumo_total . " m3";

						if($cobro_mes){
							$html .= '<tr>
										<td class="tdClass" >' .  $texto_item .  $dato_extra . ' </td>
										<td class="tdClass tdClassNumber">$ ' .  number_format($detalle->monto,0,".",".") . '</td>
									  </tr>	';
							$subtotal += $detalle->monto;
						}else{
							$html_otros_cobros .= '<tr>
										<td class="tdClass" >' .  $texto_item .  $dato_extra . ' </td>
										<td class="tdClass tdClassNumber">$ ' .  number_format($detalle->monto,0,".",".") . '</td>
									  </tr>	';

							$subtotal_otros_cobros += $detalle->monto;

						}
				}				

				$total = $subtotal + $subtotal_otros_cobros + $saldo_anterior;

				$html .= '
						<tr>
						<td class="tdClass" ><b>Subtotal Cobro del Mes</b></td>
						<td class="tdClass tdClassNumber"><b>$ ' .  number_format($subtotal,0,".",".") . '</b></td>
						</tr>
						
						<tr>
						<td class="tdClass">&nbsp;</td>
						<td class="tdClass">&nbsp;</td>
						</tr>';

				$html .= $html_otros_cobros . '
						<tr>
						<td class="tdClass" ><b>Subtotal Otros Cobros</b></td>
						<td class="tdClass tdClassNumber"><b>$ ' .  number_format($subtotal_otros_cobros,0,".",".") . '</b></td>
						</tr>
						<tr>
						<td class="tdClass" ><b>Subtotal del Mes</b></td>
						<td class="tdClass tdClassNumber"><b>$ ' .  number_format($subtotal + $subtotal_otros_cobros,0,".",".") . '</b></td>
						</tr>
						<tr>
						<td class="tdClass">&nbsp;</td>
						<td class="tdClass">&nbsp;</td>
						</tr>
						';																																																			
				$html .= '																					
						<tr>
						<td class="tdClass" >Saldo Anterior</td>
						<td class="tdClass tdClassNumber">$ ' . number_format($saldo_anterior,0,".",".")  . '</td>
						</tr>
						<tr >
						<td class="tdClass " ><b>Total a Pagar del Mes</b></td>
						<td class="tdClass tdClassNumber"><b>$ ' .  number_format($total > 0 ? $total : 0,0,".",".") . '</b></td>
						</tr>
						</tbody>
						</table>
						</div>
						<br>
						<hr>
						<div class="recto">
							<h4><b>Ultimos Cobros</b></h4>
							<table width="100%">
								<tr>
									<td width="30%">	
										<table>
											<tr><td><img src="graph/ggcc/'.$idpropiedad.'/graph_'.$idperiodo.'.png" width="60%"></td>/tr>
										</table>										
									</td>
									<td align="center" width="35%">	
										<table width="100%">
											<thead class="theadClass">
											<tr class="headerRow">
												<th><p>Periodo</p></th>
												<th><p>Monto</p></th>
											</tr>
											</thead>
											<tbody>';
											$min_periodo = is_null($datos_periodo->publica) ? 0 : 1;
											$periodo_actual = (int)$datos_periodo->anno.str_pad($datos_periodo->mes,2,"0",STR_PAD_LEFT);
											if(count($ultimos_periodos) > 1){
											$i = 1;
												foreach($reverse_ultimos_periodos as $periodo_revisa){
													$periodo_consulta = (int)$periodo_revisa['anno'].str_pad($periodo_revisa['mes'],2,"0",STR_PAD_LEFT);
													if($i > $min_periodo && $periodo_consulta < $periodo_actual ){

														$html .= '											
																<tr>
																	<td class="tdClassCenter" >' . date2string($periodo_revisa['mes'],$periodo_revisa['anno']) . '</td>
																	<td class="tdClass tdClassNumber" >$ ' . number_format($periodo_revisa['monto'],0,".",".") . '</td>
																</tr>';
													}
													if($i == 4){
														break;
													}
													$i++;


												}
											}else{
														$html .= '											
																<tr>
																	<td colspan="2" >No existen cobros anteriores</td>
																</tr>';
											}


									$html .='</tbody>										
										</table>										
									</td>

									<td align="center" width="35%">	
										<table width="100%">
											<thead class="theadClass">
											<tr class="headerRow">
												<th><p>Informaci&oacute;n</p></th>
												<th><p>Monto</p></th>
											</tr>
											</thead>
											<tbody>
																<tr>
																	<td class="tdClassCenter" >Total Ingresos</td>
																	<td class="tdClass tdClassNumber" >$ ' . number_format($ingresos,0,".",".") . '</td>
																</tr>
																<tr>
																	<td class="tdClassCenter" >Total Egresos</td>
																	<td class="tdClass tdClassNumber" >$ ' . number_format($egresos,0,".",".") . '</td>
																</tr>
																<tr>
																	<td class="tdClassCenter" >Saldo Mes</td>
																	<td class="tdClass tdClassNumber" >$ ' . number_format($ingresos - $egresos,0,".",".") . '</td>
																</tr>
											</tbody>										
										</table>										
									</td>





								</tr>															
							</table>
							

						</div>						
						';

						if($firma == '&nbsp;'){
							$html .= '<br><br>
						<br>
						<br>
						<br>';
						}
						
						$html .= '<table width="100%" border="0">
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="30%" style="border-bottom:1pt solid black;">' . $firma . '</td>
								<td width="10%">&nbsp;</td>
								<td width="10%" >&nbsp;</td>								
								<td width="40%" style="text-align:right"><b>Fecha Emisión de Pago: ' . date("d") . ' de ' . month2string(date("m")) . ' de ' . date("Y") . '</b><br>
								<b>Pagar Hasta: ' . substr($datos_ggcc->fecha_vencimiento,0,2) . ' de ' . month2string(substr($datos_ggcc->fecha_vencimiento,3,2)) . ' de ' . substr($datos_ggcc->fecha_vencimiento,6,4) . '</b></td>
								<td width="10%" >&nbsp;</td>
							</tr>
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="30%" style="text-align:center">Firma Administrador</td>
								<td width="10%">&nbsp;</td>
								<td width="10%" ></td>
								<td width="40%" style="text-align:right">&nbsp;</td>
								<td width="10%" >&nbsp;</td>
							</tr>							
						</table>						
		';

			$html .=	"</body>
						</html>";
					
				
				$this->db->where('idpropiedad',$idpropiedad);
				$this->db->where('idperiodo',$idperiodo);
				$this->db->update('gc_ggcc_propiedad', array('pdf_content' => $html));			

	}	

public function generar_contenido_detalle($comunidadid,$idperiodo){


			$this->load->model('account');

			


			$cuentas = $this->account->get_cuentas_by_periodo_format($idperiodo,$comunidadid);



			$ingresos = $this->account->get_ingresos_by_periodo_format($idperiodo,$comunidadid);


			$this->load->model('admin');
			$comunidad = $this->admin->get_comunidades($comunidadid);
    		$obscomunidad = $comunidad->obscomprobante != '' ? $comunidad->obscomprobante : '<CENTER>SIN OBSERVACIONES DE LA ADMINISTRACION</CENTER>';
			$datosperiodo = $this->admin->get_periodos($comunidadid,$idperiodo);

			/********* DAR FORMA A ARREGLO CUENTAS***********/
			$padres = array();
			$detalle = array();
			foreach ($cuentas as $cuenta) {
				if(!array_key_exists($cuenta->concepto_padre, $padres)){ // se almacenan los padres y el monto del detalle
					$padres[$cuenta->concepto_padre] = 0;
					$detalle[$cuenta->concepto_padre] = array();
				}
				$padres[$cuenta->concepto_padre] +=	$cuenta->monto;
				array_push($detalle[$cuenta->concepto_padre],$cuenta);
			}


			/********* DAR FORMA A ARREGLO INGRESOS***********/
			$padres_ingresos = array();
			$detalle_ingresos = array();
			foreach ($ingresos as $ingreso) {
				if(!array_key_exists($ingreso->concepto_padre, $padres_ingresos)){ // se almacenan los padres y el monto del detalle
					$padres_ingresos[$ingreso->concepto_padre] = 0;
					$detalle_ingresos[$ingreso->concepto_padre] = array();
				}
				$padres_ingresos[$ingreso->concepto_padre] +=	$ingreso->monto;
				array_push($detalle_ingresos[$ingreso->concepto_padre],$ingreso);
			}

			$html = '<html>
					<head>
					<style type="text/css">
					.rounded {
					 border:0.1mm solid #220044;
					 background-color: #FAFAFA;
					 background-clip: border-box;
					 padding: 1em;
						}

					.recto {
					 border:0.1mm solid #000000;
					 background-color: #FAFAFA;
					 background-clip: border-box;
					 padding: 1em;
						}


					.tableClass { 
						background-color: #e3ece4; 
						border-collapse: collapse;
						font-family: DejaVuSansCondensed;
						font-size: 9pt; 
						line-height: 1.2;
						margin-top: 2pt; 
						margin-bottom: 5pt; 
						width: 70%;
						topntail: 0.02cm solid #495b4a; 
					}

					.theadClass { 
						font-weight: bold; 
						vertical-align: bottom; 
					}

					.tdClass { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:left;
						padding-right: 4mm; 
						padding-top: 0.5mm; 
						padding-bottom: 0.5mm;
						border-top: 1px solid #FFFFFF; 
					}

					.tdClassCenter { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:center;
						padding-right: 4mm; 
						padding-top: 0.5mm; 
						padding-bottom: 0.5mm;
						border-top: 1px solid #FFFFFF; 
					}					

					.tdClassNumber { 
						text-align:right;
					}

					.headerRow td, .headerRow th { 
						background-gradient: linear #b7cebd #ffffff 0 1 0 0.2; 
						padding: 1mm; 
					}	

					.header4 { 
						font-weight: ; 
						font-size: 13pt; 
						color: #080636;
						font-family: DejaVuSansCondensed, sans-serif; 
						margin-top: 10pt; 
						margin-bottom: 7pt;
						text-align: center;
						margin-collapse:collapse; page-break-after:avoid; }										
					</style>
			</head>
					<body>';


			$logo = $comunidad->logo == '' || is_null($comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/'. $this->session->userdata('comunidadid') . '/' . $comunidad->logo;

			$html .= '
						<p><h4 class="header4"><br>Detalle Gasto Com&uacute;n<br><br><img src="' . $logo . '" width="100px"></h4></p>
						<hr>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="25%"><p>Concepto/Proveedor</p></th>
						<th width="30%"><p>Descripci&oacute;n</p></th>
						<th width="15%"><p>N. Doc</p></th>
						<th width="15%"><p>Fecha</p></th>
						<!--th width="40%"><p>Proveedor</p></th-->
						<th width="15%">Valor</th>
						</tr>
						</thead>
						<tbody>';
						$remuneraciones = 0; //NO SE PUEDE MOSTRAR EL DETALLE DE REMUNERACIONES
						$tiene_remuneracion = false;						
					    foreach ($padres as $key_padre => $value_padre) { 

			$html .= 	'<tr>
							<td class="tdClass"><b>' . $key_padre .'</b></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="tdClass tdClassNumber" style="font-size: 6pt;"><b>$ ' . number_format($value_padre,0,".",".") . '</b></td>
						</tr>';


							foreach ($detalle[$key_padre] as $cuenta) {
								if($cuenta->concepto != "Sueldos"){
									$html .= 	'<tr>
													<td class="tdClass" style="font-size: 6pt;">&nbsp;&nbsp;&nbsp;&nbsp;' . $cuenta->proveedor .'</td>
													<td class="tdClass" style="font-size: 6pt;">' . $cuenta->descripcion .'</td>
													<td class="tdClass" style="font-size: 6pt;">' . $cuenta->nrodocumento .'</td>
													<td class="tdClass" style="font-size: 6pt;">' . $cuenta->fecdocumento .'</td>
													<!--td class="tdClass" style="font-size: 6pt;>' . $cuenta->proveedor .'</td-->
													<td class="tdClass tdClassNumber" style="font-size: 6pt;">$ ' . number_format($cuenta->monto,0,".",".") . '</td>
												</tr>';

								}else{
									$tiene_remuneracion = true;
									$remuneraciones += $cuenta->monto;
								}

							}

							if($tiene_remuneracion && $remuneraciones > 0){ //AGRUPA TODOS LOS MONTOS DE REMUNERACIONES Y LOS MUESTRA COMO UN SÓLO ITEM
								$tiene_remuneracion = false;
								$html .= 	'<tr>
												<td class="tdClass" style="font-size: 6pt;">&nbsp;&nbsp;&nbsp;&nbsp;Sueldos</td>
												<td class="tdClass" style="font-size: 6pt;">Remuneraciones de ' . date2string($datosperiodo->mes,$datosperiodo->anno) . '</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td class="tdClass tdClassNumber" style="font-size: 6pt;">$ ' . number_format($remuneraciones,0,".",".") . '</td>
											</tr>';

							}							

						}



					    foreach ($padres_ingresos as $key_padre_ingreso => $value_padre_ingreso) { 

			$html .= 	'<tr>
							<td class="tdClass"><b>' . $key_padre_ingreso .'</b></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="tdClass tdClassNumber" style="font-size: 6pt;"><b>$ - ' . number_format($value_padre_ingreso,0,".",".") . '</b></td>
						</tr>';


							foreach ($detalle_ingresos[$key_padre_ingreso] as $ingreso) {
									$html .= 	'<tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;' . $ingreso->proveedor .'</td>
													<td class="tdClass">' . $ingreso->descripcion .'</td>
													<td class="tdClass">' . $ingreso->nrodocumento .'</td>
													<td class="tdClass">' . $ingreso->fecdocumento .'</td>
													<!--td class="tdClass">' . $ingreso->proveedor .'</td-->
													<td class="tdClass tdClassNumber">$ - ' . number_format($ingreso->monto,0,".",".") . '</td>
												</tr>';


							}
						}						
				$html .= '
						<tr>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
						</tr>
						<tr>
							<td class="tdClass"><b>Total Gasto Com&uacute;n</b></td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass tdClassNumber" style="font-size: 6pt;"><b>$ ' . number_format($datosperiodo->deuda,0,".",".") . '</b></td>
						</tr>
						<tr>
							<td class="tdClass"><b>Fondo de Reserva</b></td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass tdClassNumber" style="font-size: 6pt;"><b>$ ' . number_format($datosperiodo->fondo_reserva,0,".",".") . '</b></td>
						</tr>						
						<tr>
							<td class="tdClass"><b>Total</b></td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass tdClassNumber" style="font-size: 6pt;"><b>$ ' . number_format($datosperiodo->deuda+$datosperiodo->fondo_reserva,0,".",".") . '</b></td>
						</tr>												
						</tbody>
						</table>
						<br><br>
						</div>
						<br>
						<hr>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="100%"><p>Observaciones de la Administraci&oacute;n</p></th>
						</tr>
						</thead>
						<tbody><tr>
							<td class="tdClass">' . $obscomunidad . '</td>
						</tr>
						</tbody>
						</table>
							
							

						</div>
		';

			$html .=	"</body>
						</html>";
				
				$this->db->where('idcomunidad',$comunidadid);
				$this->db->where('idperiodo',$idperiodo);
				$this->db->update('gc_periodo_estado', array('pdf_content' => $html));			

	}			


	private function get_pdf_content($idpropiedad,$idperiodo){

		$this->db->select('pdf_content ')
						  ->from('gc_ggcc_propiedad ')
						  ->where('idpropiedad',$idpropiedad)
						  ->where('idperiodo',$idperiodo);
		$query = $this->db->get();
		return $query->row();
	}


	private function get_pdf_content_detalle($comunidadid,$idperiodo){

		$this->db->select('pdf_content ')
						  ->from('gc_periodo_estado ')
						  ->where('idcomunidad', $comunidadid)
						  ->where('idperiodo',$idperiodo);
		$query = $this->db->get();
		return $query->row();
	}		



	public function comprobantes($datos_propiedades,$idperiodo){

			$this->load->model('admin');
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

			/*$this->load->library("mpdf");
			$mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				8,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				); 
			*/
		
			$mpdf = new \Mpdf\Mpdf(['default_font_size' => 8,
									'margin-top' => 16,
									'margin-bottom' => 16,
									'margin-header' => 9,
									'margin-footer' => 9,
									'margin-left' => 10,
									'margin-right' => 5,
									]);


			$mpdf->SetTitle('Tu Gasto Común - Comprobantes');
			$mpdf->SetHeader('Condominio '. $datos_comunidad->nombre . ' - ' .$datos_comunidad->comuna . ' - RUT: ' .number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);
			$mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');
			$cantidad = count($datos_propiedades);
			$i = 0;
			foreach ($datos_propiedades as $propiedad) {
				$content = $this->get_pdf_content($propiedad->id,$idperiodo);

				if($content->pdf_content == ''){ // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
					$this->generar_contenido_comprobante($this->session->userdata('comunidadid'),$idperiodo,$propiedad->id);
					$content = $this->get_pdf_content($propiedad->id,$idperiodo);

				}
				$mpdf->WriteHTML($content->pdf_content);

				$i++;

				if($i < $cantidad){
					$mpdf->AddPage();
				}
			} 





			// SE ALMACENA EL ARCHIVO
			$nombre_archivo = date("Y")."_".date("m")."_".date("d")."_".$datos_propiedad->numero.".pdf";




			$mpdf->Output($nombre_archivo, "I");
			
	}		





public function comprobante_detalle_ggcc($idperiodo){

			$this->load->model('admin');
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));
			$datosperiodo = $this->admin->get_periodos($this->session->userdata('comunidadid'),$idperiodo);

			$mpdf = new \Mpdf\Mpdf(['default_font_size' => 8,
									'margin-top' => 16,
									'margin-bottom' => 16,
									'margin-header' => 9,
									'margin-footer' => 9,
									'margin-left' => 10,
									'margin-right' => 5,
									]);


			/*
			$this->load->library("mpdf");
			$this->mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				8,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				); */



			$mpdf->SetTitle('Tu Gasto Común - Detalle Cobros');
			$mpdf->SetHeader('Condominio '. $datos_comunidad->nombre . ' - ' .$datos_comunidad->comuna . ' - RUT: ' .number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);
			$mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');
			$content_detalle = $this->get_pdf_content_detalle($this->session->userdata('comunidadid'),$idperiodo);

			if($content_detalle->pdf_content == ''  || is_null($datosperiodo->publica)){ // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
				$this->generar_contenido_detalle($this->session->userdata('comunidadid'),$idperiodo);
				$content_detalle = $this->get_pdf_content_detalle($this->session->userdata('comunidadid'),$idperiodo);
			}		


			$mpdf->WriteHTML($content_detalle->pdf_content);			



			// SE ALMACENA EL ARCHIVO
			$nombre_archivo = date("Y")."_".date("m")."_".date("d")."_".$datos_propiedad->numero.".pdf";




			$mpdf->Output($nombre_archivo, "I");
			
	}		






	public function generar_comprobante($comunidadid,$idperiodo,$idpropiedad){


			$this->load->model('admin');
			$datos_comunidad = $this->admin->datos_comunidad($comunidadid);
			$datos_propiedad = $this->admin->get_propiedad_by_id($idpropiedad);
			$datosperiodo = $this->admin->get_periodos($comunidadid,$idperiodo);


			$content = $this->get_pdf_content($idpropiedad,$idperiodo);

			if($content->pdf_content == '' || is_null($datosperiodo->publica)){ // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
				$this->generar_contenido_comprobante($comunidadid,$idperiodo,$idpropiedad);
				$content = $this->get_pdf_content($idpropiedad,$idperiodo);
			}

			//$this->generar_contenido_detalle($comunidadid,$idperiodo);
			$content_detalle = $this->get_pdf_content_detalle($comunidadid,$idperiodo);

			if($content_detalle->pdf_content == '' || is_null($datosperiodo->publica)){ // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
				$this->generar_contenido_detalle($comunidadid,$idperiodo);
				$content_detalle = $this->get_pdf_content_detalle($comunidadid,$idperiodo);
			}		



			$mpdf = new \Mpdf\Mpdf(['default_font_size' => 7,
									'margin-top' => 16,
									'margin-bottom' => 16,
									'margin-header' => 9,
									'margin-footer' => 9,
									'margin-left' => 10,
									'margin-right' => 5,
									]);


			//$this->load->library("mpdf");
			/*$this->mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				7,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				);  */

				
			//echo $html; exit;
			$mpdf->SetTitle('Tu Gasto Común - Comprobante');
			$mpdf->SetHeader('Condominio '. $datos_comunidad->nombre . ' - ' .$datos_comunidad->comuna . ' - RUT: ' .number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);
			$mpdf->WriteHTML($content->pdf_content);
			$mpdf->AddPage();
			$mpdf->WriteHTML($content_detalle->pdf_content);			
			$mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');


			// SE ALMACENA EL ARCHIVO
			$nombre_archivo = date("Y")."_".date("m")."_".date("d")."_".$datos_propiedad->numero.".pdf";
			$mpdf->Output($nombre_archivo, "I");
			
	}	


	public function generar_mail($comunidadid,$idperiodo,$propiedad){

				$this->load->model('admin');
				$comunidad = $this->admin->get_comunidades($comunidadid);
				$periodo = $this->admin->get_periodo_by_id($idperiodo);
				
				$ggcc = $this->get_ggcc_by_propiedad($propiedad->id,$idperiodo);


				if(!is_null($ggcc)){
						$saldo_anterior = $this->get_saldo_anterior_by_propiedadid_periodoid($propiedad->id,$idperiodo);
						$monto_total = $ggcc->monto + $saldo_anterior;

						

						  $this->load->library('email');

		                  
		                  $messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
		  <head>
		    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		    <title>A Simple Responsive HTML Email</title>
		  </head>
		  <body yahoo="" bgcolor="#f6f8f1" style="min-width: 100% !important; margin: 0; padding: 0;">&#13;
		<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td>&#13;
		    <!--[if (gte mso 9)|(IE)]>
		      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
		        <tr>
		          <td>
		    <![endif]-->     &#13;
		    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;"><tbody><tr><td bgcolor="#605ca8" class="header" style="padding: 40px 30px 20px;">&#13;
		          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="70" style="padding: 0 20px 20px 0;">&#13;
		                <!--img class="fix" src="./mail_completo_files/logo4_1.png" width="70" height="70" border="0" alt=""-->&#13;
		              </td>&#13;
		            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
		            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
		              <tr>
		                <td>
		          <![endif]--><table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;"><tbody><tr><td height="70">&#13;
		                <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="subhead" style="font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px; padding: 0 0 0 3px;">&#13;
		                      Notificación Gasto Común&#13;
		                    </td>&#13;
		                  </tr><tr><td class="h1" style="color: #153643; font-family: sans-serif; font-size: 33px; line-height: 38px; font-weight: bold; padding: 5px 0 0;">&#13;
		                      <center><img class="fix" src="http://www.tugastocomun.cl/app/img/logo4_1.png" border="0" alt="" style="height: auto;" /></center>&#13;
		                    </td>&#13;
		                  </tr></tbody></table></td>&#13;
		            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
		                </td>
		              </tr>
		          </table>
		          <![endif]--></td>&#13;
		      </tr><tr><td class="innerpadding borderbottom" style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
		          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="h2" style="color: #153643; font-family: sans-serif; font-size: 24px; line-height: 28px; font-weight: bold; padding: 0 0 15px;">&#13;
		                Comunidad ' . $this->session->userdata('comunidadnombre') . '
		              </td>&#13;
		            </tr><tr><td class="bodycopy" style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">&#13;
		                Se ha generado el Gasto Común correspondiente al período <b>' .date2string($periodo->mes,$periodo->anno) . '</b>, el cual dejamos a su disposición.  &#13;
		              </td>&#13;
		            </tr></tbody></table></td>&#13;
		      </tr><tr><td class="innerpadding borderbottom" style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
		          <table width="115" align="left" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="115" style="padding: 0 20px 20px 0;">&#13;
		                <img class="fix" src="http://www.tugastocomun.cl/app/img/documentos-pdf.jpg" width="115" height="115" border="0" alt="" style="height: auto;" /></td>&#13;
		            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
		            <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">
		              <tr>
		                <td>
		          <![endif]--><table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 380px;"><tbody><tr><td>&#13;
		                <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="bodycopy" style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">&#13;
		                      <center><b>Monto Total a Pagar</b></center>
		                    </td>&#13;
		                  </tr>
						  <tr><td class="bodycopy" style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">&#13;
		                     <center><h2>$ ' . number_format($monto_total,0,".",".") .'</h2></center>
		                    </td>&#13;
		                  </tr>
		                  <tr><td style="padding: 20px 0 0;">&#13;
		                      <center><table class="buttonwrapper" bgcolor="#e05443" border="0" cellspacing="0" cellpadding="0" style="background-color: transparent !important;"><tbody><tr><td class="button" height="45" style="text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0px;" align="center">&#13;
		                            <a href="' . base_url() . 'guest/download_ggcc/' . $comunidadid. '/' . $propiedad->id . '/' . $idperiodo . '" target="_blank" style="color: #ffffff; text-decoration: none; background-color: #e05443; padding: 15px 15px 13px;">Ver Documento</a>&#13;
		                          </td>&#13;
		                        </tr></tbody></table></center></td>&#13;
		                  </tr></tbody></table></td>&#13;
		            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
		                </td>
		              </tr>
		          </table>
		          <![endif]--></td>&#13;
		      </tr><!--tr>
		        <td class="innerpadding borderbottom">
		          <img class="fix" src="./mail_completo_files/wide.png" width="100%" border="0" alt="">
		        </td>
		      </tr--><tr><td class="innerpadding bodycopy" style="color: #153643; font-family: sans-serif; font-size: 10px; line-height: 22px; padding: 30px;">&#13;
					El monto considerado como total, incluye tanto el monto de Gasto Com&uacute;n, como saldos impagos de per&iacute;odos anteriores.  Para m&aacute;s informaci&oacute;n vis&iacute;tenos en nuestro sitio web http://www.tugastocomun.cl
		        </td>&#13;
		      </tr><tr><td class="footer" bgcolor="#44525f" style="padding: 20px 30px 15px;">&#13;
		          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td align="center" class="footercopy" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">&#13;
		                Copyright © ' . date('Y') . '-' . (date('Y') + 1) . ' Tu Gasto Común.<br />
		                <span class="hide">Si no desea seguir recibiendo correos de Tu Gasto Comun, por favor </span>&#13;
		                <a href="' . base_url() . 'admins/unsubscribe/' . $propiedad->id . '" class="unsubscribe" ><font color="#ffffff">haz click aquí</font></a>                 &#13;
		              </td>&#13;
		            </tr><tr><td align="center" style="padding: 20px 0 0;">&#13;
		                <table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
		                      <a href="http://www.facebook.com/">&#13;
		                        <img src="http://www.tugastocomun.cl/app/img/facebook.png" width="37" height="37" alt="Facebook" border="0" style="height: auto;" /></a>&#13;
		                    </td>&#13;
		                    <td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
		                      <a href="http://www.twitter.com/">&#13;
		                        <img src="http://www.tugastocomun.cl/app/img/twitter.png" width="37" height="37" alt="Twitter" border="0" style="height: auto;" /></a>&#13;
		                    </td>&#13;
		                  </tr></tbody></table></td>&#13;
		            </tr></tbody></table></td>&#13;
		      </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
		          </td>
		        </tr>
		    </table>
		    <![endif]--></td>&#13;
		  </tr></tbody></table><!--analytics--><script type="text/javascript" async="" src="http://www.tugastocomun.cl/app/js/ga.js"></script><script src="http://www.tugastocomun.cl/app/js/jquery-1.10.1.min.js"></script><script src="http://www.tugastocomun.cl/app/js/ga-tracking.min.js"></script></body>
		</html>';


						$lista_email = $this->admin->get_propiedad_email_by_id($propiedad->id);
						
						//$array_email = array('rodrigog.84@gmail.com');

						$array_email = array($propiedad->mail);
						foreach ($lista_email as $lista) {
							array_push($array_email,$lista->email);
						}


						$this->admin->envia_mail('robot@tugastocomun.cl',$array_email,$comunidad->nombre." - " . $propiedad->numero . " - Gasto Común ".  date2string($periodo->mes,$periodo->anno),$messageBody,'html');
						//$this->admin->envia_mail('robot@tugastocomun.cl',$array_email,'Notificación de Generación de Gastos Comunes',$messageBody,'html');

							//$this->admin->envia_mail('robot@tugastocomun.cl','rodrigog.84@gmail.com','Notificación de Generación de Gastos Comunes',$messageBody,'html');

		                  /*$this->email->set_mailtype("html");
		                  $this->email->from('robot@tugastocomun.cl', 'Tu Gasto Común');
		                  $this->email->to($propiedad->mail);
					      //$this->email->bcc('rgonzalez@aurbana.cl'); 
					      //$this->email->bcc('adolfo@aurbana.cl'); 
					      //$this->email->bcc('rodrigog.84@gmail.com'); 
					      //$this->email->bcc(array('rgonzalez@aurbana.cl','adolfo@aurbana.cl','rodrigog.84@gmail.com'));

		                  $this->email->subject('Notificación de Generación de Gastos Comunes');
		                  $this->email->message($messageBody);
		                  try {
		                    $this->email->send();
		                  } catch (Exception $e) {
		                    echo $e->getMessage() . '<br />';
		                    echo $e->getCode() . '<br />';
		                    echo $e->getFile() . '<br />';
		                    echo $e->getTraceAsString() . '<br />';
		                    echo "no";
		                  }  */

                  }

	}



	public function generar_mail_adm_ggcc($comunidadid,$idperiodo){

				$this->load->model('admin');
				$comunidad = $this->admin->get_comunidades($comunidadid);
				$periodo = $this->admin->get_periodo_by_id($idperiodo);

					  $this->load->library('email');

	                  
	                  $messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
	  <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <title>A Simple Responsive HTML Email</title>
	  </head>
	  <body yahoo="" bgcolor="#f6f8f1" style="min-width: 100% !important; margin: 0; padding: 0;">&#13;
	<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td>&#13;
	    <!--[if (gte mso 9)|(IE)]>
	      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
	        <tr>
	          <td>
	    <![endif]-->     &#13;
	    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;"><tbody><tr><td bgcolor="#605ca8" class="header" style="padding: 40px 30px 20px;">&#13;
	          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="70" style="padding: 0 20px 20px 0;">&#13;
	                <!--img class="fix" src="./mail_completo_files/logo4_1.png" width="70" height="70" border="0" alt=""-->&#13;
	              </td>&#13;
	            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
	            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
	              <tr>
	                <td>
	          <![endif]--><table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;"><tbody><tr><td height="70">&#13;
	                <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="subhead" style="font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px; padding: 0 0 0 3px;">&#13;
	                      Notificación Gasto Común&#13;
	                    </td>&#13;
	                  </tr><tr><td class="h1" style="color: #153643; font-family: sans-serif; font-size: 33px; line-height: 38px; font-weight: bold; padding: 5px 0 0;">&#13;
	                      <center><img class="fix" src="http://www.tugastocomun.cl/app/img/logo4_1.png" border="0" alt="" style="height: auto;" /></center>&#13;
	                    </td>&#13;
	                  </tr></tbody></table></td>&#13;
	            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
	                </td>
	              </tr>
	          </table>
	          <![endif]--></td>&#13;
	      </tr><tr><td class="innerpadding borderbottom" style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
	          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="h2" style="color: #153643; font-family: sans-serif; font-size: 24px; line-height: 28px; font-weight: bold; padding: 0 0 15px;">&#13;
	                Comunidad ' . $this->session->userdata('comunidadnombre') . '
	              </td>&#13;
	            </tr><tr><td class="bodycopy" style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 22px;">&#13;
	                Se ha generado el Gasto Común correspondiente al período <b>' .date2string($periodo->mes,$periodo->anno) . '</b>, el cual fue enviado por correo electr&oacute;nico correctamente a sus propietarios.  &#13;
	              </td>&#13;
	            </tr></tbody></table></td>&#13;
	      </tr><!--tr>
	        <td class="innerpadding borderbottom">
	          <img class="fix" src="./mail_completo_files/wide.png" width="100%" border="0" alt="">
	        </td>
	      </tr--><tr><td class="innerpadding bodycopy" style="color: #153643; font-family: sans-serif; font-size: 10px; line-height: 22px; padding: 30px;">&#13;
					        </td>&#13;
	      </tr><tr><td class="footer" bgcolor="#44525f" style="padding: 20px 30px 15px;">&#13;
	          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td align="center" class="footercopy" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">&#13;
	                Copyright © ' . date('Y') . '-' . (date('Y') + 1) . ' Tu Gasto Común.<br />
	                <span class="hide">Si no desea seguir recibiendo correos de Tu Gasto Comun, por favor </span>&#13;
	                               &#13;
	              </td>&#13;
	            </tr><tr><td align="center" style="padding: 20px 0 0;">&#13;
	                <table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
	                      <a href="http://www.facebook.com/">&#13;
	                        <img src="http://www.tugastocomun.cl/app/img/facebook.png" width="37" height="37" alt="Facebook" border="0" style="height: auto;" /></a>&#13;
	                    </td>&#13;
	                    <td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
	                      <a href="http://www.twitter.com/">&#13;
	                        <img src="http://www.tugastocomun.cl/app/img/twitter.png" width="37" height="37" alt="Twitter" border="0" style="height: auto;" /></a>&#13;
	                    </td>&#13;
	                  </tr></tbody></table></td>&#13;
	            </tr></tbody></table></td>&#13;
	      </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
	          </td>
	        </tr>
	    </table>
	    <![endif]--></td>&#13;
	  </tr></tbody></table><!--analytics--><script type="text/javascript" async="" src="http://www.tugastocomun.cl/app/js/ga.js"></script><script src="http://www.tugastocomun.cl/app/js/jquery-1.10.1.min.js"></script><script src="http://www.tugastocomun.cl/app/js/ga-tracking.min.js"></script></body>
	</html>';

					$lista_email = $this->admin->get_comunidad_adm_email_by_id($comunidadid);
					
					$array_email = array();
					foreach ($lista_email as $lista) {
						array_push($array_email,$lista->email);
					}

					array_push($array_email,'rgonzalez@tugastocomun.cl');
					

					$this->admin->envia_mail('robot@tugastocomun.cl',$array_email,$comunidad->nombre." - Gasto Común ".  date2string($periodo->mes,$periodo->anno),$messageBody,'html');


	}	

	public function buscar_comunidades_sin_pago(){

			$comunidades_data = $this->db->select("c.id")
						  ->from('gc_comunidad c')
						  ->where("datediff(c.fecvencimiento,now())",DIAS_AVISO)
						  ->where("c.fecaviso <> '" . date('Y-m-d') . "'")
						  ->where("c.active",1);

			$query = $this->db->get();
			return $query->result();	
	}


	public function generar_mail_aviso_vencimiento($comunidadid = null){
			$this->db->trans_start();
				$this->load->model('admin');
				$comunidad = $this->admin->get_comunidades($comunidadid);


				

				  $this->load->library('email');
				   //por un monto de $55.600. 
				   //a la cuenta 88888888888-88 de Banco Santander
                  
                  $messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>A Simple Responsive HTML Email</title>
  </head>
  <body yahoo="" bgcolor="#f6f8f1" style="min-width: 100% !important; margin: 0; padding: 0;">&#13;
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td>&#13;
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->     &#13;
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;"><tbody><tr><td bgcolor="#605ca8" class="header" style="padding: 40px 30px 20px;">&#13;
          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="70" style="padding: 0 20px 20px 0;">&#13;
                <!--img class="fix" src="./mail_completo_files/logo4_1.png" width="70" height="70" border="0" alt=""-->&#13;
              </td>&#13;
            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]--><table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;"><tbody><tr><td height="70">&#13;
                <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="subhead" style="font-size: 14px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px; padding: 0 0 0 3px;">&#13;
                      <center>Aviso de Vencimiento Cuota</center>&#13;
                    </td>&#13;
                  </tr><tr><td class="h1" style="color: #153643; font-family: sans-serif; font-size: 33px; line-height: 38px; font-weight: bold; padding: 5px 0 0;">&#13;
                      <center><img class="fix" src="http://www.tugastocomun.cl/app/img/logo4_1.png" border="0" alt="" style="height: auto;" /></center>&#13;
                    </td>&#13;
                  </tr></tbody></table></td>&#13;
            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]--></td>&#13;
      </tr><tr><td class="innerpadding borderbottom" style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="h2" style="color: #153643; font-family: sans-serif; font-size: 18px; line-height: 28px; font-weight: bold; padding: 0 0 15px;">&#13;
                Comunidad ' . $comunidad->nombre . '
              </td>&#13;
            </tr><tr><td class="bodycopy" style="color: #153643; font-family: sans-serif; font-size: 13px; line-height: 22px;text-align: justify">&#13;
                El Equipo Tu Gasto Com&uacute;n le informa que su &uacute;ltima cuota se encuentra pr&oacute;xima a vencer, con fecha ' . $comunidad->fecvencimiento . '.  Le recordamos que para continuar con su servicio, &eacute;sta debe ser cancelada antes de la fecha en cuesti&oacute;n mediante dep&oacute;sito y/o transferencia, y ahora tambi&eacute;n por Webpay. &#13;<br><br>
                    En caso de transferencia, utilizar los siguientes datos: &#13;<br><br>
                    <ul style="list-style-type: disc">
  						<li>Banco: Santander</li>
 						<li>Cuenta Corriente: 7026007-7</li>
  						<li>Rut Empresa: 76.563.795-3</li>
  						<li>Raz&oacute;n Social: TECNOLOGIA VIRTUAL LTDA.</li>
  						<li>Correo Electr&oacute;nico: contacto@tugastocomun.cl</li>
  						<li>Monto: 1 UF Mensual</li>
					</ul>&#13;<br>&#13;<br>		
                	Si usted ya realiz&oacute; este pago, o no continuar&aacute; utilizando el software, favor omita este mensaje.<br><br>

                	Le saluda cordialmente,<br>
                	Equipo de Tu Gasto Común
              </td>&#13;
            </tr></tbody></table></td>&#13;
      			</tr>
          </table>
          <![endif]--></td>&#13;
      </tr><!--tr>
        <td class="innerpadding borderbottom">
          <img class="fix" src="./mail_completo_files/wide.png" width="100%" border="0" alt="">
        </td>
      </tr--><tr><td class="innerpadding bodycopy" style="color: #153643; font-family: sans-serif; font-size: 10px; line-height: 22px; padding: 30px;">&#13;
			  Para m&aacute;s informaci&oacute;n vis&iacute;tenos en nuestro sitio web http://www.tugastocomun.cl
        </td>&#13;
      </tr><tr><td class="footer" bgcolor="#44525f" style="padding: 20px 30px 15px;">&#13;
          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td align="center" class="footercopy" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">&#13;
                Copyright © ' . date('Y') . '-' . (date('Y') + 1) . ' Tu Gasto Común.<br />
                <span class="hide">Si no desea seguir recibiendo correos de Tu Gasto Comun, por favor </span>&#13;
                <a href="' . base_url() . 'admins/unsubscribe" class="unsubscribe" ><font color="#ffffff">haz click aquí</font></a>                 &#13;
              </td>&#13;
            </tr><tr><td align="center" style="padding: 20px 0 0;">&#13;
                <table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
                      <a href="http://www.facebook.com/">&#13;
                        <img src="http://www.tugastocomun.cl/app/img/facebook.png" width="37" height="37" alt="Facebook" border="0" style="height: auto;" /></a>&#13;
                    </td>&#13;
                    <td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
                      <a href="http://www.twitter.com/">&#13;
                        <img src="http://www.tugastocomun.cl/app/img/twitter.png" width="37" height="37" alt="Twitter" border="0" style="height: auto;" /></a>&#13;
                    </td>&#13;
                  </tr></tbody></table></td>&#13;
            </tr></tbody></table></td>&#13;
      </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
          </td>
        </tr>
    </table>
    <![endif]--></td>&#13;
  </tr></tbody></table><!--analytics--><script type="text/javascript" async="" src="http://www.tugastocomun.cl/app/js/ga.js"></script><script src="http://www.tugastocomun.cl/app/js/jquery-1.10.1.min.js"></script><script src="http://www.tugastocomun.cl/app/js/ga-tracking.min.js"></script></body>
</html>';


					$lista_email = $this->admin->get_comunidad_adm_email_by_id($comunidadid);
					
					$array_email = array();
					foreach ($lista_email as $lista) {
						array_push($array_email,$lista->email);
					}
					
					array_push($array_email,'csandoval@aurbana.cl');
					array_push($array_email,'adolfo@aurbana.cl');
					//array_push($array_email,'rgonzalez@tugastocomun.cl');

					//$array_email = array(); //Para pruebas y enviar sólo a mi

					$fecaviso = date('Y-m-d H:i:s');
					$this->admin->envia_mail_sb('robot@tugastocomun.cl',$array_email,$comunidad->nombre." - Aviso Vencimiento Cobro ",$messageBody,'html');



					$this->db->where('id',$comunidadid);
					$this->db->update('gc_comunidad',array('fecaviso' => $fecaviso));

					$array_insert_log = array(
										'idcomunidad' => $comunidadid,
										'fecaviso' => $fecaviso
										);
					$this->db->insert('gc_log_avisos',$array_insert_log);




			$this->db->trans_complete();
	}	







public function generar_mail_aviso_pago($idpago = null){
			$this->db->trans_start();
				$this->load->model('admin');
				$datos_pago = $this->admin->get_pagos_webpay($idpago);

				

				  $this->load->library('email');
				   //por un monto de $55.600. 
				   //a la cuenta 88888888888-88 de Banco Santander
                  
                  $messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>A Simple Responsive HTML Email</title>
  </head>
  <body yahoo="" bgcolor="#f6f8f1" style="min-width: 100% !important; margin: 0; padding: 0;">&#13;
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td>&#13;
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->     &#13;
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;"><tbody><tr><td bgcolor="#605ca8" class="header" style="padding: 40px 30px 20px;">&#13;
          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="70" style="padding: 0 20px 20px 0;">&#13;
                <!--img class="fix" src="./mail_completo_files/logo4_1.png" width="70" height="70" border="0" alt=""-->&#13;
              </td>&#13;
            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]--><table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;"><tbody><tr><td height="70">&#13;
                <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="subhead" style="font-size: 14px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px; padding: 0 0 0 3px;">&#13;
                      <center>Suscripci&oacute;n Extendida</center>&#13;
                    </td>&#13;
                  </tr><tr><td class="h1" style="color: #153643; font-family: sans-serif; font-size: 33px; line-height: 38px; font-weight: bold; padding: 5px 0 0;">&#13;
                      <center><img class="fix" src="http://www.tugastocomun.cl/app/img/logo4_1.png" border="0" alt="" style="height: auto;" /></center>&#13;
                    </td>&#13;
                  </tr></tbody></table></td>&#13;
            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]--></td>&#13;
      </tr><tr><td class="innerpadding borderbottom" style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="h2" style="color: #153643; font-family: sans-serif; font-size: 18px; line-height: 28px; font-weight: bold; padding: 0 0 15px;">&#13;
                Comunidad ' . $datos_pago->comunidad . '
              </td>&#13;
            </tr><tr><td class="bodycopy" style="color: #153643; font-family: sans-serif; font-size: 13px; line-height: 22px;text-align: justify">&#13;
                El Equipo Tu Gasto Com&uacute;n le informa que con fecha ' . $datos_pago->aceptacionpago.', hemos recibido correctamente su pago por un monto de $ ' . number_format($datos_pago->montopago,0,".",".") . '. De esta forma, su per&iacute;do de suscripci&oacute;n se ha extendido hasta el d&iacute;a ' . $datos_pago->fecvencimientonuevo . '. &#13;<br><br>
                	Le saluda cordialmente,<br>
                	Equipo de Tu Gasto Común
              </td>&#13;
            </tr></tbody></table></td>&#13;
      			</tr>
          </table>
          <![endif]--></td>&#13;
      </tr><!--tr>
        <td class="innerpadding borderbottom">
          <img class="fix" src="./mail_completo_files/wide.png" width="100%" border="0" alt="">
        </td>
      </tr--><tr><td class="innerpadding bodycopy" style="color: #153643; font-family: sans-serif; font-size: 10px; line-height: 22px; padding: 30px;">&#13;
			  Para m&aacute;s informaci&oacute;n vis&iacute;tenos en nuestro sitio web http://www.tugastocomun.cl
        </td>&#13;
      </tr><tr><td class="footer" bgcolor="#44525f" style="padding: 20px 30px 15px;">&#13;
          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td align="center" class="footercopy" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">&#13;
                Copyright © ' . date('Y') . '-' . (date('Y') + 1) . ' Tu Gasto Común.<br />
                <span class="hide">Si no desea seguir recibiendo correos de Tu Gasto Comun, por favor </span>&#13;
                <a href="' . base_url() . 'admins/unsubscribe" class="unsubscribe" ><font color="#ffffff">haz click aquí</font></a>                 &#13;
              </td>&#13;
            </tr><tr><td align="center" style="padding: 20px 0 0;">&#13;
                <table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
                      <a href="http://www.facebook.com/">&#13;
                        <img src="http://www.tugastocomun.cl/app/img/facebook.png" width="37" height="37" alt="Facebook" border="0" style="height: auto;" /></a>&#13;
                    </td>&#13;
                    <td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
                      <a href="http://www.twitter.com/">&#13;
                        <img src="http://www.tugastocomun.cl/app/img/twitter.png" width="37" height="37" alt="Twitter" border="0" style="height: auto;" /></a>&#13;
                    </td>&#13;
                  </tr></tbody></table></td>&#13;
            </tr></tbody></table></td>&#13;
      </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
          </td>
        </tr>
    </table>
    <![endif]--></td>&#13;
  </tr></tbody></table><!--analytics--><script type="text/javascript" async="" src="http://www.tugastocomun.cl/app/js/ga.js"></script><script src="http://www.tugastocomun.cl/app/js/jquery-1.10.1.min.js"></script><script src="http://www.tugastocomun.cl/app/js/ga-tracking.min.js"></script></body>
</html>';


					$lista_email = $this->admin->get_comunidad_adm_email_by_id($datos_pago->idcomunidad);
					
					$array_email = array();
					foreach ($lista_email as $lista) {
						array_push($array_email,$lista->email);
					}
					
					array_push($array_email,'csandoval@aurbana.cl');
					array_push($array_email,'adolfo@aurbana.cl');
					array_push($array_email,'rgonzalez@tugastocomun.cl');

					//$array_email = array(); //Para pruebas y enviar sólo a mi

					$fecaviso = date('Y-m-d H:i:s');
					$this->admin->envia_mail('robot@tugastocomun.cl',$array_email,$datos_pago->comunidad." - Recibo de Pago ",$messageBody,'html');



					$this->db->where('id',$idpago);
					$this->db->update('gc_log_pagos',array('envia_comprobante' => 1,
															'fec_envio' => $fecaviso));


			$this->db->trans_complete();
	}		






public function generar_mail_vencimiento_propiedad($comunid,$propiedad = null,$tipo = 'antes'){
				$this->db->trans_start();
				$this->load->model('admin');
				$comunidad = $this->admin->get_comunidades($comunid->idcomunidad);
				//var_dump($comunid); exit;

				

				  $this->load->library('email');
				   //por un monto de $55.600. 
				   //a la cuenta 88888888888-88 de Banco Santander
	$tipodeuda = $tipo == 'antes' ? 'pr&oacute;xima a vencer' : 'vencida';
	$txt_email = $tipo == 'antes' ? $comunidad->txt_mail_antes_vencimiento : $comunidad->txt_mail_despues_vencimiento;

	$txt_email = str_replace('{usuario}',$propiedad->responsable,$txt_email);
	$txt_email = str_replace('{comunidad}',$comunidad->nombre,$txt_email);
	$txt_email = str_replace('{propiedad}',$propiedad->numero,$txt_email);
	$txt_email = str_replace('{periodo}',date2string($comunid->mes,$comunid->anno),$txt_email);
	$txt_email = str_replace('{monto}','$ ' . number_format($propiedad->saldo,0,'.','.'),$txt_email);
	$txt_email = str_replace('{vencimiento}',$comunid->fecha_vencimiento,$txt_email);
	


				if($txt_email != ''){


						$messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
						<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
						  <head>
						    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
						    <title>A Simple Responsive HTML Email</title>
						  </head>
						  <body yahoo="" bgcolor="#f6f8f1" style="min-width: 100% !important; margin: 0; padding: 0;">&#13;
						<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td>&#13;
						    <!--[if (gte mso 9)|(IE)]>
						      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
						        <tr>
						          <td>
						    <![endif]-->     &#13;
						    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;"><tbody><tr><td bgcolor="#605ca8" class="header" style="padding: 40px 30px 20px;">&#13;
						          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td height="70" style="padding: 0 20px 20px 0;">&#13;
						                <!--img class="fix" src="./mail_completo_files/logo4_1.png" width="70" height="70" border="0" alt=""-->&#13;
						              </td>&#13;
						            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
						            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
						              <tr>
						                <td>
						          <![endif]--><table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;"><tbody><tr><td height="70">&#13;
						                <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="subhead" style="font-size: 14px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px; padding: 0 0 0 3px;">&#13;
						                      <center>Aviso de Vencimiento Pago</center>&#13;
						                    </td>&#13;
						                  </tr><tr><td class="h1" style="color: #153643; font-family: sans-serif; font-size: 33px; line-height: 38px; font-weight: bold; padding: 5px 0 0;">&#13;
						                      <center><img class="fix" src="http://www.tugastocomun.cl/app/img/logo4_1.png" border="0" alt="" style="height: auto;" /></center>&#13;
						                    </td>&#13;
						                  </tr></tbody></table></td>&#13;
						            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
						                </td>
						              </tr>
						          </table>
						          <![endif]--></td>&#13;
						      </tr><tr><td class="innerpadding borderbottom" style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
						          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="bodycopy" style="color: #153643; font-family: sans-serif; font-size: 13px; line-height: 22px;text-align: justify">&#13;
						               ' . $txt_email . '
						              </td>&#13;
						            </tr>
									<tr><td class="innerpadding bodycopy" style="color: #153643; font-family: sans-serif; font-size: 10px; line-height: 22px; padding: 30px;">&#13;
									  Para m&aacute;s informaci&oacute;n vis&iacute;tenos en nuestro sitio web http://www.tugastocomun.cl
						        </td>&#13;
						      </tr>
						            </tbody></table></td>&#13;
						      			</tr>
						          </table>
						          <![endif]--></td>&#13;
						      </tr><!--tr>
						        <td class="innerpadding borderbottom">
						          <img class="fix" src="./mail_completo_files/wide.png" width="100%" border="0" alt="">
						        </td>
						      </tr--><tr><td class="footer" bgcolor="#44525f" style="padding: 20px 30px 15px;">&#13;
						          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td align="center" class="footercopy" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">&#13;
						                Copyright © ' . date('Y') . '-' . (date('Y') + 1) . ' Tu Gasto Común.<br />
						                <span class="hide">Si no desea seguir recibiendo correos de Tu Gasto Comun, por favor </span>&#13;
						                <a href="' . base_url() . 'admins/unsubscribe" class="unsubscribe" ><font color="#ffffff">haz click aquí</font></a>                 &#13;
						              </td>&#13;
						            </tr><tr><td align="center" style="padding: 20px 0 0;">&#13;
						                <table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
						                      <a href="http://www.facebook.com/">&#13;
						                        <img src="http://www.tugastocomun.cl/app/img/facebook.png" width="37" height="37" alt="Facebook" border="0" style="height: auto;" /></a>&#13;
						                    </td>&#13;
						                    <td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
						                      <a href="http://www.twitter.com/">&#13;
						                        <img src="http://www.tugastocomun.cl/app/img/twitter.png" width="37" height="37" alt="Twitter" border="0" style="height: auto;" /></a>&#13;
						                    </td>&#13;
						                  </tr></tbody></table></td>&#13;
						            </tr></tbody></table></td>&#13;
						      </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
						          </td>
						        </tr>
						    </table>';


						    //var_dump($messageBody); exit;
											$lista_email = $this->admin->get_comunidad_adm_email_by_id($comunidadid);
											
											$array_email = array();
											$array_email = array('rodrigog.84@gmail.com');
											foreach ($lista_email as $lista) {
												array_push($array_email,$lista->email);
											}
											
											//array_push($array_email,'csandoval@aurbana.cl');
											/*array_push($array_email,'adolfo@aurbana.cl');
											array_push($array_email,'rgonzalez@tugastocomun.cl');
											*/
											//$array_email = array(); //Para pruebas y enviar sólo a mi

											$fecaviso = date('Y-m-d H:i:s');
											$this->admin->envia_mail('robot@tugastocomun.cl',$array_email,$comunidad->nombre." - Propiedad " . $propiedad->numero ." - Aviso Vencimiento Pago ",$messageBody,'html');


										/*
											$this->db->where('id',$comunidadid);
											$this->db->update('gc_comunidad',array('fecaviso' => $fecaviso));

											$array_insert_log = array(
																'idcomunidad' => $comunidadid,
																'fecaviso' => $fecaviso
																);
											$this->db->insert('gc_log_avisos',$array_insert_log);

										*/





				}




			$this->db->trans_complete();
	}	



public function generar_contenido_ingreso($idpropiedad,$idingreso,$saldo){

			$this->load->model('admin');
			$comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

			$datos_propiedad = $this->admin->get_propiedad_by_id($idpropiedad);

			$this->load->model('account');
			$datos_abono = $this->account->get_listado_abono_by_id($idingreso);

			$estacionamientos = $this->admin->get_estacionamientos_by_propiedad($idpropiedad);
			$bodegas = $this->admin->get_bodegas_by_propiedad($idpropiedad);


			$fechadeposito  = $datos_abono->fechadeposito != '00/00/0000' ? $datos_abono->fechadeposito : '';

			$logo = $comunidad->logo == '' || is_null($comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/'. $this->session->userdata('comunidadid') . '/' . $comunidad->logo;



			$firma = $comunidad->firma == '' || is_null($comunidad->firma) ? '&nbsp;' : '<img src="uploads/firmas/'. $this->session->userdata('comunidadid') . '/' . $comunidad->firma . '" width="150px"> ';				

			$html = '<html>
					<head>
					<style type="text/css">
					.rounded {
					 border:0.1mm solid #220044;
					 background-color: #FAFAFA;
					 background-clip: border-box;
					 padding: 1em;
						}

					.recto {
					 border:0.1mm solid #000000;
					 background-color: #FAFAFA;
					 background-clip: border-box;
					 padding: 1em;
						}


					.tableClass { 
						background-color: #e3ece4; 
						border-collapse: collapse;
						font-family: DejaVuSansCondensed;
						font-size: 9pt; 
						line-height: 1.2;
						margin-top: 2pt; 
						margin-bottom: 5pt; 
						width: 70%;
						topntail: 0.02cm solid #495b4a; 
					}

					.theadClass { 
						font-weight: bold; 
						vertical-align: bottom; 
					}

					.tdClass { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:left;
						padding-right: 4mm; 
						padding-top: 0.5mm; 
						padding-bottom: 0.5mm;
						border-top: 1px solid #FFFFFF; 
					}

					.tdClassCenter { 
						padding-left: 4mm; 
						vertical-align: top; 
						text-align:center;
						padding-right: 4mm; 
						padding-top: 0.5mm; 
						padding-bottom: 0.5mm;
						border-top: 1px solid #FFFFFF; 
					}					

					.tdClassNumber { 
						text-align:right;
					}

					.headerRow td, .headerRow th { 
						background-gradient: linear #b7cebd #ffffff 0 1 0 0.2; 
						padding: 1mm; 
						text-align: left;
					}	

					.header4 { 
						font-weight: ; 
						font-size: 13pt; 
						color: #080636;
						font-family: DejaVuSansCondensed, sans-serif; 
						margin-top: 10pt; 
						margin-bottom: 7pt;
						text-align: center;
						margin-collapse:collapse; page-break-after:avoid; }										
					</style>
			</head>
					<body>';


			$html .= '
						<p><h4 class="header4"><br>Comprobante de Pago N° ' .trackid($datos_abono->folio) . '<br><br><img src="' . $logo . '" width="100px"></h4></p>
						<hr>
						<br>
						<div class="recto">
							<h4><b>Nombre Copropietario:</b> ' . $datos_propiedad->responsable. '</h4><br>
							<table width="60%" >
								<thead class="theadClass">
								<tr class="headerRow">
									<th><p>Unidad</p></th>
									<th><p>Nombre</p></th>
									<th><p>Direcci&oacute;n</p></th>
									<th>Prorrateo</th>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td class="tdClassCenter" >Propiedad</td>
										<td class="tdClassCenter" >' . $datos_propiedad->numero . '</td>
										<td class="tdClassCenter" >' . $datos_propiedad->direccion . '</td>
										<td class="tdClassCenter" >' . $datos_propiedad->prorrateo_propiedad . ' % </td>
									</tr>';
								foreach ($estacionamientos as $estacionamiento) {
									$html .= '<tr>
										<td class="tdClassCenter" >Estacionamiento</td>
										<td class="tdClassCenter" >' . $estacionamiento->nombre . '</td>
										<td class="tdClassCenter" >&nbsp;</td>
										<td class="tdClassCenter" >' . $estacionamiento->prorrateo . ' % </td>
									</tr>';
								}

								foreach ($bodegas as $bodega) {
									$html .= '<tr>
										<td class="tdClassCenter" >Bodega</td>
										<td class="tdClassCenter" >' . $bodega->nombre . '</td>
										<td class="tdClassCenter" >&nbsp;</td>
										<td class="tdClassCenter" >' . $bodega->prorrateo . ' % </td>
									</tr>';
								}


								$html .= '</tbody>	
							</table>
						</div>
		';

				$html .= '
						<br>
						<hr>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="100%" colspan="2"><p>Datos Pago</p></th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" width="30%"><b><i>Forma de Pago:</i></b></td>
						<td class="tdClass" width="70%" >' . $datos_abono->forma_pago . '</td>
						</tr>
						<tr>
						<td class="tdClass" width="30%"><b><i>Fecha de Pago:</i></b></td>
						<td class="tdClass" width="70%">' . $datos_abono->fechapago . '</td>
						</tr>						
						<tr>
						<td class="tdClass" width="30%"><b><i>Banco:</i></b></td>
						<td class="tdClass" width="70%">' . $datos_abono->banco . '</td>
						</tr>
						<tr>
						<td class="tdClass" width="30%"><b><i>Monto:</i></b></td>
						<td class="tdClass" width="70%">$' . number_format($datos_abono->monto,0,".",".") . '  <b><i>( ' . valorEnLetras($datos_abono->monto) . ' )</i></b></td>
						</tr>';

						if($saldo){

							$html .= '
							<tr>
							<td class="tdClass" width="30%"><b><i>Saldo Adeudado:</i></b></td>
							<td class="tdClass" width="70%">$' . number_format($datos_propiedad->saldo_publicado,0,".",".") . '  <b><i>( ' . valorEnLetras($datos_propiedad->saldo_publicado) . ' )</i></b> </td>
							</tr>';

						}					


						$html .= '
						<tr>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" >&nbsp;</td>
						</tr>						
						<tr>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" >&nbsp;</td>
						</tr>						
						<tr>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" >&nbsp;</td>
						</tr>						
						</tbody>
						</table>
						</div>';

						if($firma == '&nbsp;'){
							$html .= '<br><hr><br>
						<br>
						<br>
						<br>';
						}else{
							$html .= '<br><hr>';
						}

				$html .='
						<table width="100%" border="0">
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%">&nbsp;</td>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">' . $firma . '</td>								
								<td width="10%">&nbsp;</td>
								<td width="20%" >&nbsp;</td>
								<td width="10%">&nbsp;</td>
							</tr>
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">&nbsp;</td>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Administrador</td>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">&nbsp;</td>
								<td width="10%">&nbsp;</td>
							</tr>							
						</table>';



			$html .=	"</body>
						</html>";

						//echo $html; exit;
				
				//$this->db->where('id',$idegreso);
				//$this->db->update('gc_listado_pagos', array('pdf_content' => $html));			
				//return $html;

				$this->db->where('id',$idingreso);
				$this->db->update('gc_listado_abonos', array('pdf_content' => $html));						

	}	

	private function get_pdf_ingreso_content($listado_abonos_id){

		$this->db->select('pdf_content, protesto ')
						  ->from('gc_listado_abonos ')
						  ->where('id',$listado_abonos_id);
		$query = $this->db->get();
		return $query->row();
	}


	public function generar_ingreso($idpropiedad,$listado_abonos_id){


			$this->load->model('admin');
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

			$content = $this->get_pdf_ingreso_content($listado_abonos_id);

			if($content->pdf_content == ''){ // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
				$this->generar_contenido_ingreso($idpropiedad,$listado_abonos_id,false);
				$content = $this->get_pdf_ingreso_content($listado_abonos_id);
			}

			//var_dump($content); exit;
			$mpdf = new \Mpdf\Mpdf(['default_font_size' => 8,
									'margin-top' => 16,
									'margin-bottom' => 16,
									'margin-header' => 9,
									'margin-footer' => 9,
									'margin-left' => 10,
									'margin-right' => 5,
									]);


			/*$this->load->library("mpdf");
			$this->mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				8,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				);  */
			//echo $content; exit;
			$mpdf->SetTitle('Tu Gasto Común - Comprobante de Ingreso');
			$mpdf->SetHeader('Condominio '. $datos_comunidad->nombre . ' - ' .$datos_comunidad->comuna . ' - RUT: ' .number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);
			$mpdf->WriteHTML($content->pdf_content);

			if($content->protesto == 1){
				$mpdf->SetWatermarkText('PROTESTO');
				$mpdf->watermark_font = 'DejaVuSansCondensed';
				$mpdf->showWatermarkText = true;
			}


			$mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');


			// SE ALMACENA EL ARCHIVO
			$nombre_archivo = date("Y")."_".date("m")."_".date("d")."_Ingreso_".$idegreso.".pdf";
			$mpdf->Output($nombre_archivo, "I");
			
	}



	public function generar_mail_abono($comunidadid,$idpropiedad,$listado_abonos_id){


					//hacer código de mail
					$this->load->model('admin');
					$datos_comunidad = $this->admin->datos_comunidad($comunidadid);
					$datos_propiedad = $this->admin->get_propiedad_by_id($idpropiedad);

					$logo = $datos_comunidad->logo == '' || is_null($datos_comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/'. $this->session->userdata('comunidadid') . '/' . $datos_comunidad->logo;



					$this->load->model('account');

					//$datos_abono = $this->account->get_abono_by_id($cartola_propiedad_id);
					$datos_abono = $this->account->get_listado_abono_by_id($listado_abonos_id);

					$fechadeposito  = $datos_abono->fechadeposito != '00/00/0000' ? $datos_abono->fechadeposito : '';
					//$nombre_periodo = $datos_abono->mes == '' ? '' : date2string($datos_abono->mes,$datos_abono->anno);

					$firma = $datos_comunidad->firma == '' || is_null($datos_comunidad->firma) ? '&nbsp;' : '<center><img src="http://www.tugastocomun.cl/app/uploads/firmas/'. $this->session->userdata('comunidadid') . '/' . $datos_comunidad->firma . '" width="150px"></center> ';							

					$this->load->library('email');

					$messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>A Simple Responsive HTML Email</title>

</head>
<body yahoo="" bgcolor="#f6f8f1">
<table border="1" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="10" cellspacing="0" width="100%" id="emailContainer">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="emailHeader">
                            <tr>
                                <td width="20%" align="center" valign="top" style="font-size: 8px;text-align:left;vertical-align:text-top">
								    <p><b>Condominio ' . $datos_comunidad->nombre.'</b></p>
								    <p><b>'.$datos_comunidad->direccion.'</b></p>
								    <p><b>'.$datos_comunidad->comuna.'</b></p>
								    <p><b>RUT:'.number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv.'</b></p>
                                </td>
                                <td width="60%" align="center" valign="middle">
                                    <img class="fix" src="http://www.tugastocomun.cl/app/' . $logo . '" border="0" alt="" style="height: auto;" />
                                </td>
                                <td width="20%" align="center" valign="top" style="font-size: 10px;vertical-align:middle">
                                    <b>Comprobante N°<br>' . trackid($datos_abono->folio) . '</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table border="1" cellpadding="0" cellspacing="0" width="100%" id="emailBody">
                            <tr>
                                <td align="center" valign="top">
                                    <h3>COMPROBANTE DE PAGO</h3>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>                
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailBody">
                            <tr>
                                <td width="20%" style="text-align:left;" valign="top">
                                    <b>Copropietario:</b>
                                </td>
                                <td width="30%" style="text-align:left;" valign="top">
                                    ' . $datos_propiedad->responsable . '
                                </td>                                
                                <td width="20%" style="text-align:left;" valign="top">
                                    <b>Fecha de Pago:</b>
                                </td>
                                <td width="30%" style="text-align:left;" valign="top">
                                    ' . $datos_abono->fechapago . '
                                </td>                                                                
                            </tr>
                            <tr>
                                <td width="20%" style="text-align:left;" valign="top">
                                    <b>Número Propiedad:</b>
                                </td>
                                <td width="30%" style="text-align:left;" valign="top">
                                    ' . $datos_propiedad->numero .'
                                </td>                                
                                <td width="20%" style="text-align:left;" valign="top">
                                   &nbsp;
                                </td>
                                <td width="30%" style="text-align:left;" valign="top">
                                    &nbsp;
                                </td>                                                                
                            </tr>                            
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailFooter">
                        	<tr>
	                        	<td width="70%">
	                        		<table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailFooter">
			 							<tr>
			                                <td width="20%" style="text-align:left;border-top:2pt solid black;border-left:2pt solid black;" valign="top">
			                                    <b>Forma de Pago:</b>
			                                </td>
			                                <td width="30%" style="text-align:left;border-top:2pt solid black;border-left:2pt solid black;border-right:2pt solid black;" valign="top">
			                                    ' . $datos_abono->forma_pago . '
			                                </td>                                
			                            </tr>
			                            <tr>
			                                <td width="20%" style="text-align:left;border-top:2pt solid black;border-left:2pt solid black;" valign="top">
			                                    <b>Banco:</b>
			                                </td>
			                                <td width="30%" style="text-align:left;border-top:2pt solid black;border-left:2pt solid black;border-right:2pt solid black;" valign="top">
			                                     ' . $datos_abono->banco . '
			                                </td>                                
			                            </tr>
			                            <tr>
			                                <td width="20%" style="text-align:left;border-top:2pt solid black;border-left:2pt solid black;" valign="top">
			                                    <b>Fecha Depósito:</b>
			                                </td>
			                                <td width="30%" style="text-align:left;border-top:2pt solid black;border-left:2pt solid black;border-right:2pt solid black;" valign="top">
			                                    ' . $fechadeposito . '
			                                </td>                                
			                                <td width="10%" style="text-align:left;" valign="top">
			                                    &nbsp;
			                                </td>
                            
			                            </tr>
			                            <tr>
			                                <td width="20%" style="text-align:left;border-top:2pt solid black;border-bottom:2pt solid black;border-left:2pt solid black;" valign="top">
			                                    <b>Monto:</b>
			                                </td>
			                                <td width="30%" style="text-align:left;border-top:2pt solid black;border-bottom:2pt solid black;border-left:2pt solid black;border-right:2pt solid black;" valign="top">
			                                    $' . number_format($datos_abono->monto,0,".",".") . '
			                                </td>                                
			                            </tr>  

	                        		</table>
	                        	</td>
	                        	<td width="30%">
	                        		<table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailFooter">
										<tr>
			                                <td width="50%" colspan="2" style="text-align:left;" valign="top">
			                                    &nbsp;
			                                </td>
			                            </tr>
			                            <tr>
			                                <td width="50%" colspan="2"  style="text-align:left;" valign="top">
			                                    &nbsp;
			                                </td>
			                            </tr>
			                            <tr>
			                                <td width="40%" style="border-bottom:1pt solid black;text-align:left;" valign="top">
			                                    ' . $firma . '
			                                </td>                                
			                            </tr>
			                            <tr>
			                                <td width="40%" style="text-align:center;font-size: 10px;" valign="top">
			                                    <b>FIRMA ADMINISTRADOR</b>
			                                </td>   
			                            </tr> 
	                        		</table>
	                        	</td>
                        	</tr>




                                                       
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';
//echo $messageBody; exit;
				if($datos_propiedad->mail != '' and $datos_propiedad->suscrito == 1){
					//$this->admin->envia_mail('robot@tugastocomun.cl','rodrigog.84@gmail.com','Comprobante de Pago',$messageBody,'html');

				$lista_email = $this->admin->get_propiedad_email_by_id($idpropiedad);
				$array_email = array();
				foreach ($lista_email as $lista) {
					array_push($array_email,$lista->email);
				}
				  //$this->admin->envia_mail('robot@tugastocomun.cl',$datos_propiedad->mail,'Comprobante de Pago',$messageBody,'html');
				//$this->admin->envia_mail('robot@tugastocomun.cl',$array_email,'Comprobante de Pago',$messageBody,'html');
				$this->admin->envia_mail('robot@tugastocomun.cl',$array_email,'Comprobante de Pago',$messageBody,'html');
				}
                  /*$this->email->set_mailtype("html");
                  $this->email->from('robot@tugastocomun.cl', 'Tu Gasto Común');
                  $this->email->to($datos_propiedad->mail);
				  $this->email->bcc(array('rgonzalez@aurbana.cl','adolfo@aurbana.cl')); 
 				  //$this->email->bcc('adolfo@aurbana.cl');                   
                  //$this->email->bcc('rodrigog.84@gmail.com'); 
                  $this->email->subject('Comprobante de Pago');
                  $this->email->message($messageBody);
                  try {
                    $this->email->send();
                  } catch (Exception $e) {
                    echo $e->getMessage() . '<br />';
                    echo $e->getCode() . '<br />';
                    echo $e->getFile() . '<br />';
                    echo $e->getTraceAsString() . '<br />';
                    echo "no";
                  } */

	}




	public function generar_mail_multa($comunidadid,$idpropiedad,$idcuenta){


					//hacer código de mail
					$this->load->model('admin');
					$datos_comunidad = $this->admin->datos_comunidad($comunidadid);
					$datos_propiedad = $this->admin->get_propiedad_by_id($idpropiedad);

					$logo = $datos_comunidad->logo == '' || is_null($datos_comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/'. $this->session->userdata('comunidadid') . '/' . $datos_comunidad->logo;



					//$this->load->model('account');

					//$datos_abono = $this->account->get_abono_by_id($cartola_propiedad_id);
					$datos_multa = $this->admin->get_multa_sin_envio($idcuenta);

					//print_r($datos_multa);
					// exit;

					//$fechadeposito  = $datos_abono->fechadeposito != '00/00/0000' ? $datos_abono->fechadeposito : '';
					//$nombre_periodo = $datos_abono->mes == '' ? '' : date2string($datos_abono->mes,$datos_abono->anno);

					$firma = $datos_comunidad->firma == '' || is_null($datos_comunidad->firma) ? '&nbsp;' : '<center><img src="http://www.tugastocomun.cl/app/uploads/firmas/'. $this->session->userdata('comunidadid') . '/' . $datos_comunidad->firma . '" width="150px"></center> ';							

					$this->load->library('email');

					$messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>A Simple Responsive HTML Email</title>

</head>
<body yahoo="" bgcolor="#f6f8f1">
<table border="1" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="10" cellspacing="0" width="100%" id="emailContainer">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="emailHeader">
                            <tr>
                                <td width="20%" align="center" valign="top" style="font-size: 8px;text-align:left;vertical-align:text-top">
								    <p><b>Condominio ' . $datos_comunidad->nombre.'</b></p>
								    <p><b>'.$datos_comunidad->direccion.'</b></p>
								    <p><b>'.$datos_comunidad->comuna.'</b></p>
								    <p><b>RUT:'.number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv.'</b></p>
                                </td>
                                <td width="60%" align="center" valign="middle">
                                    <img class="fix" src="http://www.tugastocomun.cl/app/' . $logo . '" border="0" alt="" style="height: auto;" />
                                </td>
                                <td width="20%" align="center" valign="top" style="font-size: 10px;vertical-align:middle">
                                    <b>Comprobante N°<br>' . trackid($datos_multa->id) . '</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table border="1" cellpadding="0" cellspacing="0" width="100%" id="emailBody">
                            <tr>
                                <td align="center" valign="top">
                                    <h3>COMPROBANTE DE MULTA</h3>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>                
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailBody">
                            <tr>
                                <td width="20%" style="text-align:left;" valign="top">
                                    <b>Copropietario:</b>
                                </td>
                                <td width="30%" style="text-align:left;" valign="top">
                                    ' . $datos_propiedad->responsable . '
                                </td>                                
                                <td width="20%" style="text-align:left;" valign="top">
                                    <b>Fecha de Multa:</b>
                                </td>
                                <td width="30%" style="text-align:left;" valign="top">
                                    ' . $datos_multa->fechadeuda . '
                                </td>                                                                
                            </tr>
                            <tr>
                                <td width="20%" style="text-align:left;" valign="top">
                                    <b>Número Propiedad:</b>
                                </td>
                                <td width="30%" style="text-align:left;" valign="top">
                                    ' . $datos_propiedad->numero .'
                                </td>                                
                                <td width="20%" style="text-align:left;" valign="top">
                                   &nbsp;
                                </td>
                                <td width="30%" style="text-align:left;" valign="top">
                                    &nbsp;
                                </td>                                                                
                            </tr>                            
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailFooter">
                        	<tr>
	                        	<td width="70%">
	                        		<table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailFooter">
			 							<tr>
			                                <td width="20%" style="text-align:left;border-top:2pt solid black;border-left:2pt solid black;" valign="top">
			                                    <b>Per&iacute;odo:</b>
			                                </td>
			                                <td width="30%" style="text-align:left;border-top:2pt solid black;border-left:2pt solid black;border-right:2pt solid black;" valign="top">
			                                    ' . date2string($datos_multa->mes,$datos_multa->anno)  . '
			                                </td>                                
			                            </tr>
			                           
			                            <tr>
			                                <td width="20%" style="text-align:left;border-top:2pt solid black;border-bottom:2pt solid black;border-left:2pt solid black;" valign="top">
			                                    <b>Monto:</b>
			                                </td>
			                                <td width="30%" style="text-align:left;border-top:2pt solid black;border-bottom:2pt solid black;border-left:2pt solid black;border-right:2pt solid black;" valign="top">
			                                    $' . number_format($datos_multa->monto,0,".",".") . '
			                                </td>                                
			                            </tr>  

	                        		</table>
	                        	</td>
	                        	<td width="30%">
	                        		<table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailFooter">
										<tr>
			                                <td width="50%" colspan="2" style="text-align:left;" valign="top">
			                                    &nbsp;
			                                </td>
			                            </tr>
			                            <tr>
			                                <td width="50%" colspan="2"  style="text-align:left;" valign="top">
			                                    &nbsp;
			                                </td>
			                            </tr>
			                            <tr>
			                                <td width="40%" style="border-bottom:1pt solid black;text-align:left;" valign="top">
			                                    ' . $firma . '
			                                </td>                                
			                            </tr>
			                            <tr>
			                                <td width="40%" style="text-align:center;font-size: 10px;" valign="top">
			                                    <b>FIRMA ADMINISTRADOR</b>
			                                </td>   
			                            </tr> 
	                        		</table>
	                        	</td>
                        	</tr>




                                                       
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';
//echo $messageBody; exit;
				if($datos_propiedad->mail != '' and $datos_propiedad->suscrito == 1){
					//$this->admin->envia_mail('robot@tugastocomun.cl','rodrigog.84@gmail.com','Comprobante de Pago',$messageBody,'html');

				//$lista_email = $this->admin->get_propiedad_email_by_id($idpropiedad);
				$array_email = array();
				array_push($array_email,'rgonzalez@tugastocomun.cl');
				/*foreach ($lista_email as $lista) {
					array_push($array_email,$lista->email);
				}*/
				$this->admin->envia_mail('robot@tugastocomun.cl',$array_email,$datos_comunidad->nombre." - Comprobante de Multa",$messageBody,'html');
				}
 
	}


	public function monto_cuota(){

		$this->db->select('montocuotaoferta ')
						  ->from('gc_comunidad')
		                  ->where('id', $this->session->userdata('comunidadid'));
		$query = $this->db->get();
		$montocuotaoferta = $query->row()->montocuotaoferta;

		if($montocuotaoferta > 0){

			return $montocuotaoferta;
		}else{

			$this->db->select('count(id) as cantidad',false)
							  ->from('gc_propiedad')
			                  ->where('idcomunidad', $this->session->userdata('comunidadid'))
			                  ->where('active',1);
			$query = $this->db->get();
			$cantidad_propiedades = $query->row()->cantidad;



			/*$this->db->select('valor',false)
							  ->from('gc_tabla_cobro')
			                  ->where($cantidad_propiedades . ' between desde and hasta');*/

			$this->db->select('uf as valor',false)
							  ->from('gc_parametros_generales');

			$query = $this->db->get();
			$monto_cobro = $query->row()->valor;

			return $monto_cobro;
		}



	}


	public function compara_pago_online($transaction){

		
		$this->load->model('admin');



		$orden = $this->admin->get_pay_for_token($transaction->order);
		if(is_null($orden)){
			//echo 'Orden no esta en sistema';
			$result = false;
		}else{
			if(isset($orden->aceptacionpago)){
				if(is_null($orden->aceptacionpago)){
						//echo 'Orden no completada en sistema';						
					$result = false;
				}else{
						//echo 'Orden Correcta';
					$result = true;
				}

			}else{

				//echo 'Orden no completada en sistema';	
				$result = false;
			}
		}

		if(!$result){
			var_dump($transaction);
		}
		return $result;
		//echo '<br>';
	}


	public function revisa_pagos_online($comunidad,$fecproceso){

		echo '<pre>';
		$this->load->model('payment');
		//$fecproceso = '2024-01-02'; //no hay registros
		//$fecproceso = '2024-01-10'; //solo 1 registro
		$fecprocesoinit = '2024-01-01'; //varios registros
		$fecprocesoend = '2024-01-29'; //varios registros
		$url_api = 'https://app.payku.cl/api/transaction?date_init=' . $fecprocesoinit . '&date_end=' . $fecprocesoend . '&success=true';

		var_dump($url_api);
		var_dump($comunidad->token_pagoonline);


		$curl_pasarela = curl_init();

		curl_setopt_array($curl_pasarela, array(
		  CURLOPT_URL => $url_api,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer ' . $comunidad->token_pagoonline,
		    'Cookie: PHPSESSID=k246v5dfg0p0nlmkdqcj9replu; __cflb=0H28vpEYUuS7CwnZUxZguAZir9YPp4rcaVJ1peWMmEL'
		  ),
		));

		$response_pasarela = curl_exec($curl_pasarela);



	    if ($response_pasarela === false) {
	    	var_dump(curl_error($curl_pasarela));
	    	var_dump(curl_errno($curl_pasarela));
	       // throw new Exception(curl_error($ch), curl_errno($ch));
	    }



		curl_close($curl_pasarela);
    	
    	$array_response_pasarela = json_decode($response_pasarela);	
    	var_dump($array_response_pasarela);

    	echo '############################################ INIT #########################################';
    	if(isset($array_response_pasarela->status)){

    		if($array_response_pasarela->status == 'failed'){

    			echo 'No hay registros';
    		}else{ // solo 1 registro

    			$transaction = $array_response_pasarela;
    			if($transaction->email != 'rodrigog.84@gmail.com'){
    				$this->payment->compara_pago_online($transaction);    				
    			}
    			

    		}

    	}else{ // varios registros

	    	foreach ($array_response_pasarela->transaction as $transaction) {
	    		if($transaction->email != 'rodrigog.84@gmail.com'){
    				$this->payment->compara_pago_online($transaction);    				
    			}
	    	}

    	}

    	echo '############################################# FIN ####################################';

		//exit;




	}	


}



