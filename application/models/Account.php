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

class Account extends CI_Model
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


	public function add_cuenta_remuneracion($parametros){

			$data = array(
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'formapago' => $parametros['formapago'],
		      	'nombreproveedor' => $parametros['nombreproveedor'],
		      	'nrodocumento' =>  $parametros['documento'],
		      	'idtipodoctrib' =>  $parametros['tipodoc'],
		        'fecdocumento' =>  $parametros['fecdocumento'],
		        'idtipodeudadetalle' =>  $parametros['concepto'],
		        'fecvencimiento' =>  $parametros['fecdocumento'],
		        'descripcion' =>  $parametros['descripcion'],
		        'idperiodoremuneracion' =>  $parametros['idperiodo'],
		        'monto' => $parametros['monto'],
		        'abonado' => 0,
		        'saldo' => $parametros['monto'],
		        'created_at' => date("Y-m-d H:i:s")		        
			);

			$this->db->insert('gc_cuenta', $data);

	}


	public function add_cuenta_cuotas($parametros){
			
			$fecdocumento = substr($parametros['fecdocumento'],6,4)."-".substr($parametros['fecdocumento'],3,2)."-".substr($parametros['fecdocumento'],0,2);
			$fecvencimiento = substr($parametros['fecvencimiento'],6,4)."-".substr($parametros['fecvencimiento'],3,2)."-".substr($parametros['fecvencimiento'],0,2);

			$data = array(
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'formapago' => $parametros['formapago'],
		      	'idproveedor' => $parametros['idproveedor'],
		      	'numcuotas' => isset($parametros['numcuotas']) ? $parametros['numcuotas'] : 0,
		      	'nrodocumento' =>  $parametros['documento'],
		      	'idtipodoctrib' =>  $parametros['tipodoc'],
		        'fecdocumento' =>  $fecdocumento,
		        'idtipodeudadetalle' =>  $parametros['concepto'],
		        'fecvencimiento' =>  $fecvencimiento,
		        'monto' =>  $parametros['monto'],
		        'descripcion' =>  $parametros['descripcion'],
		        'nombrearchivo' =>  $parametros['nombrearchivo'],
		        'nombrerealarchivo' =>  $parametros['nombrerealarchivo'],
		        'created_at' => date("Y-m-d H:i:s")
			);			

	
			if($parametros['idcuenta'] == 0){
				// guarda cartola
				$this->db->insert('gc_cuenta_cuotas', $data);
				$idcuenta = $this->db->insert_id();
			}else{

				if($parametros['nombrearchivo'] == ''){

					$data = array(
						'idcomunidad' => $this->session->userdata('comunidadid'),
						'formapago' => $parametros['formapago'],
				      	'idproveedor' => $parametros['idproveedor'],
				      	'numcuotas' => $parametros['numcuotas'],
				      	'nrodocumento' =>  $parametros['documento'],
				      	'idtipodoctrib' =>  $parametros['tipodoc'],
				        'fecdocumento' =>  $fecdocumento,
				        'idtipodeudadetalle' =>  $parametros['concepto'],
				        'fecvencimiento' =>  $fecvencimiento,
				        'monto' =>  $parametros['monto'],
				        'descripcion' =>  $parametros['descripcion']
					);

				}

				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('id',$parametros['idcuenta']);
				$this->db->update('gc_cuenta_cuotas', $data);

				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('idcuentacuotas',$parametros['idcuenta']);
				$this->db->delete('gc_cuenta');


				$idcuenta = $parametros['idcuenta'];


			}

			return $idcuenta;


	}	



	public function add_cuenta($parametros){
			$this->db->trans_start();

			$this->load->model('admin');
			$fecdocumento = substr($parametros['fecdocumento'],6,4)."-".substr($parametros['fecdocumento'],3,2)."-".substr($parametros['fecdocumento'],0,2);
			$fecvencimiento = substr($parametros['fecvencimiento'],6,4)."-".substr($parametros['fecvencimiento'],3,2)."-".substr($parametros['fecvencimiento'],0,2);

			$array_forma_pago = explode('-',$parametros['formapago']);
			$idfondo = 0;
			if(isset($array_forma_pago[1])){
				$parametros['formapago'] = 'f'; // cuando la cuenta va asociada a un fondo
				$idfondo = $array_forma_pago[1];
			}


			$data = array(
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'formapago' => $parametros['formapago'],
				'idfondo' => $idfondo,
				'idperiodo' => isset($parametros['idperiodo']) ? $parametros['idperiodo'] : null,
		      	'idproveedor' => $parametros['idproveedor'],
		      	'unidadmedida' => isset($parametros['unidadmedida']) ? $parametros['unidadmedida'] : null,
		      	'cuotas' => isset($parametros['cuotas']) ? $parametros['cuotas'] : 'sc',
		      	'idcuentacuotas' => isset($parametros['idcuotas']) ? $parametros['idcuotas'] : null,
		      	'numcuotas' => isset($parametros['numcuotas']) ? $parametros['numcuotas'] : 0,
		      	'totalcuenta' => isset($parametros['totalcuenta']) ? $parametros['totalcuenta'] : 0,
		      	'nrodocumento' =>  $parametros['documento'],
		      	'idtipodoctrib' =>  $parametros['tipodoc'],
		        'fecdocumento' =>  $fecdocumento,
		        'idtipodeudadetalle' =>  $parametros['concepto'],
		        'fecvencimiento' =>  $fecvencimiento,
		        'descripcion' =>  $parametros['descripcion'],
		        'nombrearchivo' =>  $parametros['nombrearchivo'],
		        'nombrerealarchivo' =>  $parametros['nombrerealarchivo']		        
			);



			if($parametros['idcuenta'] == 0){

				#ESTOS 3 CAMPOS SOLO APLICAN PARA BOLETAS DE HONORARIOS
				if(isset($parametros['proveedor'])){
					$data['nombreproveedor'] = $parametros['proveedor'];
				}

				if(isset($parametros['retencion'])){
					$data['retencion'] = $parametros['retencion'];
				}

				
				if(isset($parametros['retencionidctaasoc'])){
					$data['retencionidctaasoc'] = $parametros['retencionidctaasoc'];
				}

				$data['monto'] = $parametros['monto'];
				$data['abonado'] = 0;
				$data['saldo'] = $parametros['monto'];
				$data['created_at'] = date("Y-m-d H:i:s");

				$this->db->insert('gc_cuenta', $data);
				$idcuenta = $this->db->insert_id();



				if($parametros['formapago'] == 'f'){

					$proveedor = $this->admin->get_proveedor_by_id($parametros['idproveedor']);


					$data_cartola_fondos = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idcuenta' =>  $idcuenta,
				      	'idfondo' => $idfondo,
				      	'glosa' =>  'Pagos de cuentas de Condominio. ' . $proveedor->nombre. '.',
				      	'monto' =>  $parametros['monto']*(-1),
					);

					$this->db->insert('gc_cartola_otros_fondos', $data_cartola_fondos);

				}




			}else{

				if($parametros['nombrearchivo'] == ''){

					$data = array(
						'idcomunidad' => $this->session->userdata('comunidadid'),
						'formapago' => $parametros['formapago'],
				      	'idproveedor' => $parametros['idproveedor'],
				      	'nrodocumento' =>  $parametros['documento'],
				      	'idtipodoctrib' =>  $parametros['tipodoc'],
				        'fecdocumento' =>  $fecdocumento,
				        'idtipodeudadetalle' =>  $parametros['concepto'],
				        'fecvencimiento' =>  $fecvencimiento,
				        'descripcion' =>  $parametros['descripcion']
					);

				}


				$this->db->select('c.formapago, c.abonado')
								  ->from('gc_cuenta c')
								  ->where('c.id',$parametros['idcuenta']);

				$query = $this->db->get();
				$cuenta_data = $query->row();
				if($cuenta_data->abonado > 0){
	
					$this->load->model('admin');
					if(($cuenta_data->formapago == 'gc' || $cuenta_data->formapago == 'sc' || $cuenta_data->formapago == 'af') && $parametros['formapago'] == 'fr'){ // rebajar pagos desde fondo de reserva
						$this->db->query("update gc_comunidad set 
																	fondoreserva = fondoreserva - " . $cuenta_data->abonado . "
																	where id = " . $this->session->userdata('comunidadid'));

						$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
						$saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;

						$data_fr = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idcuenta' => $parametros['idcuenta'],
					      	'glosa' =>  'Modificación de Cuenta. Se asocia a fondo de reserva',
					        'monto' => (-1)*$cuenta_data->abonado,				      	
					        'saldo' =>  $saldo_fondo_reserva_actual,
					        'created_at' => date("Y-m-d h:i:s")
						);
						
						$this->db->insert('gc_cartola_fondo_reserva', $data_fr);							

					}else if($cuenta_data->formapago == 'fr' && ($parametros['formapago'] == 'gc' || $parametros['formapago'] == 'sc' || $parametros['formapago'] == 'af')){ // reponer fondo de reserva
						$this->db->query("update gc_comunidad set 
																	fondoreserva = fondoreserva + " . $cuenta_data->abonado . "
																	where id = " . $this->session->userdata('comunidadid'));

						$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
						$saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;
					
						$data_fr = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idcuenta' => $parametros['idcuenta'],
					      	'glosa' =>  'Modificación de Cuenta. Se libera fondo de reserva',
					        'monto' => $cuenta_data->abonado,				      	
					        'saldo' =>  $saldo_fondo_reserva_actual,
					        'created_at' => date("Y-m-d h:i:s")
						);
						
						$this->db->insert('gc_cartola_fondo_reserva', $data_fr);	

					}
				}else{
					$data['monto'] = $parametros['monto'];
					$data['abonado'] = 0;
					$data['saldo'] = $parametros['monto'];					
				}

				#ESTOS 3 CAMPOS SOLO APLICAN PARA BOLETAS DE HONORARIOS
				if(isset($parametros['proveedor'])){
					$data['nombreproveedor'] = $parametros['proveedor'];
				}

				if(isset($parametros['retencion'])){
					$data['retencion'] = $parametros['retencion'];
				}

				
				if(isset($parametros['retencionidctaasoc'])){
					$data['retencionidctaasoc'] = $parametros['retencionidctaasoc'];
				}				

				$this->db->where('id',$parametros['idcuenta']);
				$this->db->update('gc_cuenta', $data);


				$idcuenta = $parametros['idcuenta'];



				// eliminamos todo el detalle de la lectura
				$this->db->where('idcuenta',$idcuenta);
				$this->db->delete('gc_cartola_otros_fondos');	


				if($parametros['formapago'] == 'f'){

					$proveedor = $this->admin->get_proveedor_by_id($parametros['idproveedor']);


					$data_cartola_fondos = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idcuenta' =>  $idcuenta,
				      	'idfondo' => $idfondo,
				      	'glosa' =>  'Pagos de cuentas de Condominio. ' . $proveedor->nombre. '.',
				      	'monto' =>  $parametros['monto']*(-1),
					);

					$this->db->insert('gc_cartola_otros_fondos', $data_cartola_fondos);

				}


			}

			$this->db->trans_complete();
			return $idcuenta;

	}	




	public function add_ingreso($parametros,$noautoriza = null){

			$this->db->trans_start();

			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
			$saldo_caja_actual = $datoscomunidad->caja;
			$saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;

			$fecdocumento = substr($parametros['fecdocumento'],6,4)."-".substr($parametros['fecdocumento'],3,2)."-".substr($parametros['fecdocumento'],0,2);
			$fecvencimiento = substr($parametros['fecvencimiento'],6,4)."-".substr($parametros['fecvencimiento'],3,2)."-".substr($parametros['fecvencimiento'],0,2);
			$data = array(
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'tipoingreso' => $parametros['tipoingreso'],
		      	'idproveedor' => $parametros['idproveedor'],
		      	'nrodocumento' =>  $parametros['documento'],
		      	'idtipodoctrib' =>  $parametros['tipodoc'],
		        'fecdocumento' =>  $fecdocumento,
		        'idtipodeudadetalle' =>  $parametros['concepto'],
		        'monto' => $parametros['monto'],
		        'fecvencimiento' =>  $fecvencimiento,
		        'descripcion' =>  $parametros['descripcion'],
		        'habilitagasto' =>  $parametros['habilitagasto'],
		        'nombrearchivo' =>  $parametros['nombrearchivo'],
		        'nombrerealarchivo' =>  $parametros['nombrerealarchivo']
		        
			);

			if($parametros['idingreso'] == 0){ # AGREGAR
				//$data['monto'] = $parametros['monto'];
				$data['created_at'] = date("Y-m-d H:i:s");

				$this->db->insert('gc_ingresos', $data);
				$id_ingreso = $this->db->insert_id();
				//agregar como saldo contable y en fondo de reserva si corresponde

				if($parametros['tipoingreso'] != 'na'){ #SI ES DISTINTO A "NO AFECTA BANCO"

					$saldo_caja_actual += $parametros['monto'];	
					$datos_proveedor = $this->admin->get_proveedor_by_id($parametros['idproveedor']);

					$data = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idingreso' => $id_ingreso,
				      	'glosa' =>  'Ingresos a comunidad. '.$datos_proveedor->nombre,
				        'monto' => $parametros['monto'], 	
				        'saldo' =>  $saldo_caja_actual,
				        'fechapago' => $fecdocumento,
				        'created_at' => date("Y-m-d h:i:s")
					);
					
					$this->db->insert('gc_cartola_caja', $data);

					//aumenta flujo de caja de comunidad			
					$this->db->query("update gc_comunidad set 
																caja = caja + " . $parametros['monto'] . "
																where id = " . $this->session->userdata('comunidadid'));

				}

				if($parametros['tipoingreso'] == 'fr'){ // ingreso hacia el fondo de reserva
					$this->db->query("update gc_comunidad set 
																fondoreserva = fondoreserva + " . $parametros['monto'] . "
																where id = " . $this->session->userdata('comunidadid'));	

					$data_fr = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idingreso' => $id_ingreso,
				      	'glosa' =>  'Abono a Fondo de Reserva por ingreso recibido de   '. $datos_proveedor->nombre,
				        'monto' => $parametros['monto'],				      	
				        'saldo' =>  ($saldo_fondo_reserva_actual + $parametros['monto']),
				        'created_at' => date("Y-m-d h:i:s")
					);
					
					$this->db->insert('gc_cartola_fondo_reserva', $data_fr);																					
				}


			}else{ # EDITAR

				$datos_ingreso_actual = $this->get_ingresos_by_id($parametros['idingreso'],null,$noautoriza);
				$datos_proveedor = $this->admin->get_proveedor_by_id($parametros['idproveedor']);

				#EVALUACION DE CAJA
				if($datos_ingreso_actual->monto != $parametros['monto'] || $datos_ingreso_actual->tipoingreso == 'na' || $parametros['tipoingreso'] == 'na'){ # SE ELIMINA FLUJO ANTERIOR Y SE CREA NUEVO

					if($datos_ingreso_actual->tipoingreso != 'na'){ #SI ES "NO AFECTA BANCO" NO SE REVERSA PORQUE NO HABÍA CARTOLA DE CAJA
						#ELIMINAMOS EL FLUJO DE CAJA ANTERIOR
						$this->db->query("update gc_comunidad set 
																	caja = caja - " . abs($datos_ingreso_actual->monto) . "
																	where id = " . $this->session->userdata('comunidadid'));


						$saldo_caja_actual -= $datos_ingreso_actual->monto;	
						$data_caja = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idingreso' => $parametros['idingreso'],
					      	'glosa' =>  'Reversa por corrección de monto ' . $datos_proveedor->nombre,
					        'monto' => $datos_ingreso_actual->monto*(-1),				      	
					        'saldo' =>  $saldo_caja_actual,
					        'fechapago' => $datos_ingreso_actual->fecdocumento_sformat,
					        'created_at' => date("Y-m-d h:i:s")
						);
						
						$this->db->insert('gc_cartola_caja', $data_caja);
					}

					if($parametros['tipoingreso'] != 'na'){ # SI ES "NO AFECTA BANCO" NO SE AGREGA CARTOLA DE CAJA

						#AGREGAMOS NUEVO FLUJO DE CAJA
						$saldo_caja_actual += $parametros['monto'];	
						$data_caja = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idingreso' => $parametros['idingreso'],
					      	'glosa' =>  'Ingresos a comunidad. '.$datos_proveedor->nombre,
					        'monto' => $parametros['monto'], 	
					        'saldo' =>  $saldo_caja_actual,
					        'fechapago' => $fecdocumento,
					        'created_at' => date("Y-m-d h:i:s")
						);
						
						$this->db->insert('gc_cartola_caja', $data_caja);

						//aumenta flujo de caja de comunidad			
						$this->db->query("update gc_comunidad set 
																	caja = caja + " . $parametros['monto'] . "
																	where id = " . $this->session->userdata('comunidadid'));					

					}
				}


				#EVAUACION DE FONDO DE RESERVA
				if($datos_ingreso_actual->tipoingreso != 'fr' && $parametros['tipoingreso'] == 'fr'){ ##NO ERA FONDO DE RESERVA Y AHORA FONDO DE RESERVA (SE AGREGA A FONDO DE RESERVA)


					$this->db->query("update gc_comunidad set 
																fondoreserva = fondoreserva + " . $parametros['monto'] . "
																where id = " . $this->session->userdata('comunidadid'));	

					$data_fr = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idingreso' => $parametros['idingreso'],
				      	'glosa' =>  'Abono a Fondo de Reserva por ingreso recibido de   '. $datos_proveedor->nombre,
				        'monto' => $parametros['monto'],				      	
				        'saldo' =>  ($saldo_fondo_reserva_actual + $parametros['monto']),
				        'created_at' => date("Y-m-d h:i:s")
					);
					
					$this->db->insert('gc_cartola_fondo_reserva', $data_fr);	

				}else if($datos_ingreso_actual->tipoingreso == 'fr' && $parametros['tipoingreso'] != 'fr'){ ##ERA FONDO DE RESERVA Y AHORA ES CUENTA CORRIENTE (SE ELIMINA DEL FONDO DE RESERVA)
						$this->db->query("update gc_comunidad set 
																	fondoreserva = fondoreserva - " . abs($datos_ingreso_actual->monto) . "
																	where id = " . $this->session->userdata('comunidadid'));	


						$saldo_fondo_reserva_actual = ($saldo_fondo_reserva_actual - abs($datos_ingreso_actual->monto));
						$data_fr = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idingreso' => $parametros['idingreso'],
					      	'glosa' => 'Reversa por corrección de monto ' . $datos_proveedor->nombre,
					        'monto' =>  $datos_ingreso_actual->monto*(-1),
					        'saldo' =>  $saldo_fondo_reserva_actual,
					        'created_at' => date("Y-m-d h:i:s")
						);
						
						$this->db->insert('gc_cartola_fondo_reserva', $data_fr);	

				}else if($datos_ingreso_actual->tipoingreso == 'fr' && $parametros['tipoingreso'] == 'fr'){  ## SE MANTIENE COMO FONDO DE RESERVA, SE EVALÚA SI CAMBIA MONTO
					if($datos_ingreso_actual->monto != $parametros['monto']){ ## SI CAMBIA MONTO, ENTONCES DEBE ELIMINARSE FONDO DE RESERVA Y CREAR UNO NUEVO

							# ELIMINACIÓN FONDO ANTIGUO
							$this->db->query("update gc_comunidad set 
																		fondoreserva = fondoreserva - " . abs($datos_ingreso_actual->monto) . "
																		where id = " . $this->session->userdata('comunidadid'));	


							$saldo_fondo_reserva_actual = ($saldo_fondo_reserva_actual - abs($datos_ingreso_actual->monto));
							$data_fr = array(
						      	'idcomunidad' => $this->session->userdata('comunidadid'),
						      	'idingreso' => $parametros['idingreso'],
						      	'glosa' => 'Reversa por corrección de monto ' . $datos_proveedor->nombre,
						        'monto' =>  $datos_ingreso_actual->monto*(-1),
						        'saldo' =>  $saldo_fondo_reserva_actual,
						        'created_at' => date("Y-m-d h:i:s")
							);
							
							$this->db->insert('gc_cartola_fondo_reserva', $data_fr);	

							#INGRESO DE FONDO NUEVO
							$this->db->query("update gc_comunidad set 
																		fondoreserva = fondoreserva + " . $parametros['monto'] . "
																		where id = " . $this->session->userdata('comunidadid'));	

							$data_fr = array(
						      	'idcomunidad' => $this->session->userdata('comunidadid'),
						      	'idingreso' => $parametros['idingreso'],
						      	'glosa' =>  'Abono a Fondo de Reserva por ingreso recibido de   '. $datos_proveedor->nombre,
						        'monto' => $parametros['monto'],				      	
						        'saldo' =>  ($saldo_fondo_reserva_actual + $parametros['monto']),
						        'created_at' => date("Y-m-d h:i:s")
							);
							
							$this->db->insert('gc_cartola_fondo_reserva', $data_fr);								


					}
				}


				## EDITAMOS EL INGRESO COMO TAL
				if($parametros['nombrearchivo'] == ''){
					unset($data['nombrearchivo']);
					unset($data['nombrerealarchivo']);
				}


				$this->db->where('id',$parametros['idingreso']);
				$this->db->update('gc_ingresos', $data);


				#EDITAMOS LA CARTOLA CAJA


			}

			$this->db->trans_complete();

	}	



	public function delete_ingreso($idingreso){

			$this->db->trans_start();
			$datos_ingreso = $this->get_ingresos_by_id($idingreso); 

			if(!is_null($datos_ingreso)){
				$this->load->model('admin');
				$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
				$saldo_caja_actual = $datoscomunidad->caja;
				$saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;
				$datos_proveedor = $this->admin->get_proveedor_by_id($datos_ingreso->idproveedor);

				if($datos_ingreso->tipoingreso != 'na'){
					#ELIMINAMOS EL FLUJO DEL INGRESO
					$this->db->query("update gc_comunidad set 
																caja = caja - " . abs($datos_ingreso->monto) . "
																where id = " . $this->session->userdata('comunidadid'));


					$saldo_caja_actual -= $datos_ingreso->monto;	
					$data_caja = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idingreso' => $idingreso,
				      	'glosa' =>  'Reversa por eliminación de ingreso ' . $datos_proveedor->nombre,
				        'monto' => $datos_ingreso->monto*(-1),				      	
				        'saldo' =>  $saldo_caja_actual,
				        'fechapago' => $datos_ingreso->fecdocumento_sformat,
				        'created_at' => date("Y-m-d h:i:s")
					);
					
					$this->db->insert('gc_cartola_caja', $data_caja);

				}

				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('idingreso',$idingreso);
				$this->db->update('gc_cartola_caja',array('idingreso' => null, 'exingreso' => 1));



				# ELIMINACIÓN DE FONDO DE RESERVA SI CORRESPONDE
				if($datos_ingreso->tipoingreso == 'fr'){ 

					$this->db->query("update gc_comunidad set 
																fondoreserva = fondoreserva - " . abs($datos_ingreso->monto) . "
																where id = " . $this->session->userdata('comunidadid'));	


					$saldo_fondo_reserva_actual = ($saldo_fondo_reserva_actual - abs($datos_ingreso->monto));
					$data_fr = array(
				      	'idcomunidad' => $this->session->userdata('comunidadid'),
				      	'idingreso' => $idingreso,
				      	'glosa' => 'Reversa por eliminación de ingreso ' . $datos_proveedor->nombre,
				        'monto' =>  $datos_ingreso->monto*(-1),
				        'saldo' =>  $saldo_fondo_reserva_actual,
				        'created_at' => date("Y-m-d h:i:s")
					);
					
					$this->db->insert('gc_cartola_fondo_reserva', $data_fr);	

					$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
					$this->db->where('idingreso',$idingreso);
					$this->db->update('gc_cartola_fondo_reserva',array('idingreso' => null));


				}				


				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('id',$idingreso);
				$this->db->delete('gc_ingresos');
				$this->db->trans_complete();	
				return true;
			}else{
				$this->db->trans_complete();	
				return false;
			}

			 			
	}


	public function delete_cuenta_retencion($idcuenta){

		$this->db->trans_start();

		$datos_cuenta = $this->get_cuentas_retencion_by_id($idcuenta); 
		if(isset($datos_cuenta->abonado)){
			if($datos_cuenta->abonado == 0){

				$this->db->select('id , idlistado')
						  ->from('gc_cartola_pagos')
						  ->where('idcuenta',$idcuenta);


				$query = $this->db->get();
				$data_cartola = $query->result();

				foreach ($data_cartola as $cartola) {
					$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
					$this->db->where('idpago',$cartola->id);
					$this->db->delete('gc_cartola_caja');


					#ELIMINAMOS DE CARTOLA PAGOS
					$this->db->where('id',$cartola->id);
					$this->db->delete('gc_cartola_pagos');


					#ELIMINAMOS DE CARTOLA PAGOS
					/*$this->db->where('id',$cartola->idlistado);
					$this->db->delete('gc_listado_pagos');*/


				}


				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('id',$idcuenta);
				$this->db->delete('gc_cuenta');

				$this->db->trans_complete();	
				return true;
			}else{
				$this->db->trans_complete();	
				return false;
			}
		}else{
				$this->db->trans_complete();	
				return false;

		}

			 			
	}	


	public function delete_cuenta($idcuenta,$tipocuenta = null,$honorarios = false){

		$this->db->trans_start();

		$datos_cuenta = $tipocuenta == 'c' ? $this->get_cargos_by_id($idcuenta) : $this->get_cuentas_by_id($idcuenta,$honorarios); 
		if(isset($datos_cuenta->abonado)){
			if($datos_cuenta->abonado == 0){

				$this->db->select('id , idlistado')
						  ->from('gc_cartola_pagos')
						  ->where('idcuenta',$idcuenta);


				$query = $this->db->get();
				$data_cartola = $query->result();

				foreach ($data_cartola as $cartola) {
					$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
					$this->db->where('idpago',$cartola->id);
					$this->db->delete('gc_cartola_caja');


					#ELIMINAMOS DE CARTOLA PAGOS
					$this->db->where('id',$cartola->id);
					$this->db->delete('gc_cartola_pagos');


					#ELIMINAMOS DE CARTOLA PAGOS
					/*$this->db->where('id',$cartola->idlistado);
					$this->db->delete('gc_listado_pagos');*/


				}

				// SI LA CUENTA TIENE ASOCIADO ALGO EN EL FONDO DE RESERVA, SE BORRA TAMBIÉN
				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('idcuenta',$idcuenta);
				$this->db->delete('gc_cartola_fondo_reserva');


				$this->db->where('idcuenta',$idcuenta);
				$this->db->delete('gc_cartola_otros_fondos');


				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('id',$idcuenta);
				$this->db->delete('gc_cuenta');

				$this->db->trans_complete();	
				return true;
			}else{
				$this->db->trans_complete();	
				return false;
			}
		}else{
				$this->db->trans_complete();	
				return false;

		}

			 			
	}


	public function delete_cuenta_remuneraciones($idcuenta){


		$this->db->trans_start();

			$this->db->select('id , idlistado')
					  ->from('gc_cartola_pagos')
					  ->where('idcuenta',$idcuenta);


			$query = $this->db->get();
			$data_cartola = $query->result();

			foreach ($data_cartola as $cartola) {
				$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
				$this->db->where('idpago',$cartola->id);
				$this->db->delete('gc_cartola_caja');


				#ELIMINAMOS DE CARTOLA PAGOS
				$this->db->where('id',$cartola->id);
				$this->db->delete('gc_cartola_pagos');


				#ELIMINAMOS DE CARTOLA PAGOS
				/*$this->db->where('id',$cartola->idlistado);
				$this->db->delete('gc_listado_pagos');*/


			}

			$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
			$this->db->where('id',$idcuenta);
			$this->db->delete('gc_cuenta');
		$this->db->trans_complete();			
			return true;
		
	}



	public function add_cobro_individual($data_form,$datos_cuenta){
		$this->db->trans_start();

			$monto_gc = str_replace(".","",$data_form['monto_gc']);
			$monto_ci = str_replace(".","",$data_form['monto_ci']);

			$nuevomedidor = isset($data_form['nuevomedidor']) ? $data_form['nuevomedidor'] : "";
			$nuevomedidor = $nuevomedidor == 'on' ? 'Y' : 'N';

			$array_elem = $data_form;
			$array_lecturas = array();
			$array_montos = array();
			$monto_total_pago = 0;
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'lectura_actual'){
						array_push($array_lecturas, array(
															'idpropiedad' => $arr_el[1],
															'valor_ant' => $data_form['lectura_anterior-'.$arr_el[1]],
															'valor' => $value_elem
														));
				}else if($arr_el[0] == 'monto_pagar'){
						array_push($array_montos, array(
															'idpropiedad' => $arr_el[1],
															'monto' => $value_elem
														));

						$monto_total_pago += $value_elem;

				}
			}

			if($monto_total_pago == 0){ // si no hay cambios, no deja guardar el cobro individual
	   			return false;					
			}				


			if($monto_gc > 0){
	       		$parametros_gc = array(
	       						'idcuenta' => 0,
	       						'idproveedor' => $datos_cuenta['idproveedor'],
	       						'tipodoc' => $datos_cuenta['tipodoc'],
	       						'documento' => $datos_cuenta['documento'],
	       						'fecdocumento' => $datos_cuenta['fecdocumento'],
	       						'concepto' => $datos_cuenta['concepto'],
	       						'monto' => $monto_gc,
	       						'fecvencimiento' => $datos_cuenta['fecvencimiento'],
	       						'descripcion' => $datos_cuenta['descripcion'],
	       						'formapago' => 'gc',
	       						'nombrearchivo' => $datos_cuenta['nombrearchivo'],
	       						'nombrerealarchivo' => $datos_cuenta['nombrerealarchivo']
				       			);

				$idcuenta = $this->add_cuenta($parametros_gc);
			}

			$idcuentaindividual = 0;
			// guardado de cuenta asociada a cobro individual
			if($monto_ci > 0){
	       		$parametros_ci = array(
	       						'idcuenta' => 0,
	       						'idproveedor' => $datos_cuenta['idproveedor'],
	       						'tipodoc' => $datos_cuenta['tipodoc'],
	       						'documento' => $datos_cuenta['documento'],
	       						'fecdocumento' => $datos_cuenta['fecdocumento'],
	       						'concepto' => $datos_cuenta['concepto'],
	       						'monto' => $monto_ci,
	       						'fecvencimiento' => $datos_cuenta['fecvencimiento'],
	       						'descripcion' => $datos_cuenta['descripcion'],
	       						'formapago' => 'ci',
	       						'idperiodo' => $data_form["periodo"],
	       						'unidadmedida' => $data_form["unidad_medida"],
	       						'nombrearchivo' => $datos_cuenta['nombrearchivo'],
	       						'nombrerealarchivo' => $datos_cuenta['nombrerealarchivo'],
				       			);

				$idcuentaindividual = $this->add_cuenta($parametros_ci);

			}			


			//guardado de cuentas individuales por propiedad
   			foreach ($array_montos as $dato_monto) {
   				if($dato_monto['monto'] != 0){
		       		$parametros_cuenta = array(
		       						'idpropiedad' => $dato_monto['idpropiedad'],
		       						'idperiodo' => $data_form["periodo"],
		       						'idcuenta' => $idcuentaindividual,
		       						'concepto' => $datos_cuenta['concepto'],
		       						'fecuso' => date("d/m/Y"),
		       						'monto' => $dato_monto['monto'],
		       						'descripcion' => "Cobro de cuenta individual",
		       						'nombrearchivo' => $datos_cuenta['nombrearchivo'],
		       						'nombrerealarchivo' => $datos_cuenta['nombrerealarchivo']
					       			);       		

		       		
					$this->add_cuenta_individual($parametros_cuenta);
				}
   			}


			//guardado de lectura
   			$idlectura = $this->add_lectura($datos_cuenta['concepto'],$idcuentaindividual,$nuevomedidor);
   			$total_consumo = 0;
   			foreach ($array_lecturas as $dato_lectura) {
   				$total_consumo += ($dato_lectura['valor'] - $dato_lectura['valor_ant']); 
	       		$parametros_lectura = array(
	       						'idlectura' => $idlectura,
	       						'idpropiedad' => $dato_lectura['idpropiedad'],
	       						'valor_ant' => $dato_lectura['valor_ant'],
	       						'valor' => $dato_lectura['valor']
				       			);       		

	       		
				$this->add_detalle_lectura($parametros_lectura);

   			}  

   			$monto_unidad = round(($monto_ci/$total_consumo),4);

   			
   			$this->db->where('id',$idcuentaindividual);
			$this->db->update('gc_cuenta',array('montounidad' => $monto_unidad));

		$this->db->trans_complete();
   		return true;			


		
	}


	public function delete_cobro_individual($idcuenta){

			$this->db->trans_start();

				$cuentas = $this->get_cuentas_by_id($idcuenta);
				if(!is_null($cuentas)){

					//BORRAMOS LAS CUENTAS INDIVIDUALES EXISTENTES
					$this->db->where('idcuenta',$idcuenta);
					$this->db->delete('gc_deuda_propiedad');

					//BORRAMOS TODAS LAS LECTURAS GENERADAS
					$this->delete_lectura_by_cuenta($idcuenta);

					//ELIMINAR CUENTA DE TABLA gc_cuenta
					$this->delete_cuenta($idcuenta);
					$this->db->trans_complete();
					return true;
				}else{
					$this->db->trans_complete();
					return false;
				}
	}



	public function delete_cuenta_cuotas($idcuentacuotas){

			$this->db->trans_start();

			$cuentacuotas = $this->get_cuentas_impagas_no_autorizada_by_id(null,false,true,$idcuentacuotas);
			foreach ($cuentacuotas as $cuenta) {
				$result = $this->delete_cuenta($cuenta->id);
			}

			//BORRAMOS LAS CUENTAS INDIVIDUALES EXISTENTES
			$this->db->where('id',$idcuentacuotas);
			$this->db->delete('gc_cuenta_cuotas');
			$this->db->trans_complete();
			return true;

	}




	public function delete_cuenta_individual($idcuenta){

			$datos_cuenta = $this->get_cuentas_individuales_by_id($idcuenta);
			if(!is_null($datos_cuenta)){

				$this->load->model('admin');
				$datos_propiedad = $this->admin->get_propiedad_by_id($datos_cuenta->idpropiedad);

				if($datos_propiedad->idcomunidad == $this->session->userdata('comunidadid')){


					// eliminamos todo el detalle de la lectura
					$this->db->where('idcuentaindividual',$idcuenta);
					$this->db->delete('gc_cartola_otros_fondos');	


					$this->db->where('id',$idcuenta);
					$this->db->delete('gc_deuda_propiedad');
					return true;

				}else{
					return false;
				}


			}else{

				$datos_cuenta_esp_comunes = $this->get_cuentas_espacios_comunes_by_id($idcuenta);

				if(!is_null($datos_cuenta_esp_comunes)){

					$this->load->model('admin');
					$datos_propiedad = $this->admin->get_propiedad_by_id($datos_cuenta_esp_comunes->idpropiedad);
					if($datos_propiedad->idcomunidad == $this->session->userdata('comunidadid')){

						// eliminamos todo el detalle de la lectura
						$this->db->where('idcuentaindividual',$idcuenta);
						$this->db->delete('gc_cartola_otros_fondos');	

												
						$this->db->where('id',$idcuenta);
						$this->db->delete('gc_deuda_propiedad');
						return true;

					}else{
						return false;
					}


				}else{
					return false;
				}


			}

			 			
	}



	public function add_otros_cargos($parametros){

			$fecpago = substr($parametros['fecpago'],6,4)."-".substr($parametros['fecpago'],3,2)."-".substr($parametros['fecpago'],0,2);
			$data = array(
				'idcomunidad' => $this->session->userdata('comunidadid'),
				'formapago' => 'gc',
		      	'nombreproveedor' => $parametros['nombreproveedor'],
		        'fecdocumento' =>  $fecpago,
		        'fecvencimiento' =>  $fecpago,
		        'descripcion' =>  $parametros['descripcion'],
		        'nombrearchivo' =>  $parametros['nombrearchivo'],
		        'nombrerealarchivo' =>  $parametros['nombrerealarchivo']
			);

			if($parametros['idcargo'] == 0){
				$data['monto'] = $parametros['monto'];
				$data['abonado'] = 0;
				$data['saldo'] = $parametros['monto'];
				$data['created_at'] = date("Y-m-d H:i:s");				
				$this->db->insert('gc_cuenta', $data);
			}else{

				if($parametros['nombrearchivo'] == ''){
					$data = array(
						'idcomunidad' => $this->session->userdata('comunidadid'),
						'formapago' => 'gc',
				      	'nombreproveedor' => $parametros['nombreproveedor'],
				        'fecdocumento' =>  $fecpago,
				        'fecvencimiento' =>  $fecpago,
				        'descripcion' =>  $parametros['descripcion']
					);

				}


				$this->db->select('c.formapago, c.abonado')
								  ->from('gc_cuenta c')
								  ->where('c.id',$parametros['idcargo']);

				$query = $this->db->get();
				$cuenta_data = $query->row();
				if($cuenta_data->abonado == 0){
					$data['monto'] = $parametros['monto'];
					$data['abonado'] = 0;
					$data['saldo'] = $parametros['monto'];					
				}


				$this->db->where('id',$parametros['idcargo']);
				$this->db->update('gc_cuenta', $data);			
			}
	}	


	public function add_cuenta_individual($parametros){

			//echo '<pre>';
			//var_dump($parametros); exit;
			$this->load->model('admin');
			$propiedad = $this->admin->get_propiedad_by_id($parametros['idpropiedad']);



			$monto = $parametros['concepto'] == 8 && $parametros['tipo_concepto'] == 'td' ? $parametros['monto']*(-1) : $parametros['monto'];  //SI SON AJUSTES, ES UN MONTO NEGATIVO

			$fecuso = substr($parametros['fecuso'],6,4)."-".substr($parametros['fecuso'],3,2)."-".substr($parametros['fecuso'],0,2);

			$parametros['tipo_concepto'] = isset($parametros['tipo_concepto']) ? $parametros['tipo_concepto'] : 'td';


			if($parametros['tipo_concepto'] == 'td'){
				$concepto = $parametros['concepto'];
				$fondo = 0;
			}else if($parametros['tipo_concepto'] == 'f'){
				$concepto = 0;
				$fondo = $parametros['concepto'];
			}

			$data = array(
		      	'idpropiedad' => $parametros['idpropiedad'],
		      	'idtipodeudadetalle' =>  $concepto,
		      	'idfondo' =>  $fondo,
		      	'fechadeuda' =>  $fecuso,
		      	'idperiodo' =>  $parametros['idperiodo'],
		      	'idcuenta' =>  isset($parametros['idcuenta']) ? $parametros['idcuenta'] : null,
		      	'idumespcomun' =>  isset($parametros['unidadmedida']) ? $parametros['unidadmedida'] : null,
		      	'montoum' =>  isset($parametros['ummonto']) ? $parametros['ummonto'] : 0,
		      	'cantidadum' =>  isset($parametros['cantidadum']) ? $parametros['cantidadum'] : 0,
		      	'monto' =>  $monto,
		        'descripcion' =>  $parametros['descripcion'],
		        'interes' => isset($parametros['interes']) ? $parametros['interes'] : null,
		        'nombrearchivo' =>  $parametros['nombrearchivo'],
		        'nombrerealarchivo' =>  $parametros['nombrerealarchivo']
			);

			$parametros['idcuentaindividual'] = isset($parametros['idcuentaindividual']) ? $parametros['idcuentaindividual'] : 0;

			if($parametros['idcuentaindividual'] == 0){
				// guarda cartola
				$this->db->insert('gc_deuda_propiedad', $data);
				$idcuenta =  $this->db->insert_id();
				if($parametros['concepto'] == 7 && $parametros['tipo_concepto'] == 'td'){
					$this->load->model('payment');
					$this->payment->generar_mail_multa($this->session->userdata('comunidadid'),$parametros['idpropiedad'],$idcuenta);					
				}


				if($parametros['tipo_concepto'] == 'f'){

						
						$data_cartola_fondos = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idcuentaindividual' =>  $idcuenta,
					      	'idfondo' => $parametros['concepto'],
					      	'glosa' =>  'Abono a Fondo por cobro individual. Propiedad ' . $propiedad->numero. '.',
					      	'monto' =>  $monto,
						);

						$this->db->insert('gc_cartola_otros_fondos', $data_cartola_fondos);

				}

			}else{
				// eliminamos todo el detalle de la lectura
				$this->db->where('idcuentaindividual',$parametros['idcuentaindividual']);
				$this->db->delete('gc_cartola_otros_fondos');	

				if($parametros['tipo_concepto'] == 'f'){

						
						$data_cartola_fondos = array(
					      	'idcomunidad' => $this->session->userdata('comunidadid'),
					      	'idcuentaindividual' =>  $parametros['idcuentaindividual'],
					      	'idfondo' => $parametros['concepto'],
					      	'glosa' =>  'Abono a Fondo por cobro individual. Propiedad ' . $propiedad->numero. '.',
					      	'monto' =>  $monto,
						);

						$this->db->insert('gc_cartola_otros_fondos', $data_cartola_fondos);

				}


				$this->db->where('id',$parametros['idcuentaindividual']);
				$this->db->update('gc_deuda_propiedad', $data);

			}

	}	



	public function get_cuentas_retencion_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.idperiodo, c.formapago, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, c.abonado, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.nombrerealarchivo, c.updated_at, c.descripcion, c.abonado, c.active, c.unidadmedida, c2.id as idretencion,  c2.monto as retencion, c2.abonado as abonado_retencion ')
						  ->from('gc_cuenta c')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_cuenta c2','c2.retencionidctaasoc = c.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idtipodoctrib',15)
						  ->where('c.retencionidctaasoc is not null')
						  ->where('c.idggcc is null')
		                  ->order_by('c.updated_at desc');

		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		#echo $this->db->last_query(); 
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());
	}


	public function get_cuentas_by_id($idcuenta = null,$honorarios = false){

		$cuentas_data = $this->db->select('c.id , c.idperiodo, c.formapago, c.idfondo, p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, c.abonado, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.nombrerealarchivo, c.updated_at, c.descripcion, c.abonado, c.active, c.unidadmedida, c.retencion as tiporetencion, c2.id as idretencion,  c2.monto as retencion, c2.abonado as abonado_retencion ')
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_cuenta c2','c2.retencionidctaasoc = c.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idggcc is null')
		                  ->order_by('c.updated_at desc');
		 #VERIFICA SI DEBO TRAER BOLETAS DE HONORARIOS
		$cuentas_data = $honorarios ? $cuentas_data->where('c.idtipodoctrib',15)->where('c.retencionidctaasoc is null') : $cuentas_data->where('c.idtipodoctrib <> 15');  

		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		#echo $this->db->last_query(); 
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());
	}



	public function get_cuentas_impagas_no_autorizada_by_id($idcuenta = null,$honorarios = false,$cuotas = true,$idcuentacuotas = null){

		$cuentas_data = $this->db->select('c.id , c.formapago, p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, c.abonado, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion, c2.monto as retencion, c2.abonado as abonado_retencion ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_cuenta c2','c2.retencionidctaasoc = c.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idggcc is null')
						  ->where("c.formapago in ('gc','fr','sc','af','f')")
						  ->where('c.saldo > 0')
						  ->where('c.abonado = 0')
						  ->where('c.active = 1')
		                  ->order_by('c.updated_at desc');

		 #VERIFICA SI DEBO TRAER BOLETAS DE HONORARIOS
		$cuentas_data = $honorarios ? $cuentas_data->where('c.idtipodoctrib',15)->where('c.retencionidctaasoc is null') : $cuentas_data->where('c.idtipodoctrib <> 15');  
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$cuentas_data = is_null($idcuentacuotas) ? $cuentas_data : $cuentas_data->where('c.idcuentacuotas',$idcuentacuotas);  		                  

		$cuentas_data = $cuotas ? $cuentas_data : $cuentas_data->where('c.idcuentacuotas is null');  

		$query = $this->db->get();



		#echo $this->db->last_query(); exit;
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());
	}



	public function get_lecturas_individuales_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.formapago, p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, c.abonado, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion, pes.genera, pe.mes, pe.anno ')
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_periodo pe','c.idperiodo = pe.id')
						  ->join('gc_periodo_estado pes','pe.id = pes.idperiodo and pes.idcomunidad = '.$this->session->userdata('comunidadid'))
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('pes.genera is null')
						  ->where("c.formapago = 'ci'")
						  ->where('c.saldo > 0')
						  ->where('c.active',1)
		                  ->order_by('c.updated_at desc');
		                  //aa
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());
	}


	public function get_cuentas_cuotas_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.formapago, p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.numcuotas, c.monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion ')
						  ->from('gc_cuenta_cuotas c')
						  ->join('gc_proveedor p','c.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('(select sum(saldo) from gc_cuenta where idcuentacuotas = c.id) = c.monto')
						  ->where('c.active',1)
		                  ->order_by('c.updated_at desc');
		                  //aa
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());
	}


	public function get_lecturas_individuales_by_periodo($idperiodo){

		$cuentas_data = $this->db->select('c.id , c.formapago, p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, c.abonado, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion ')
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_periodo pe','c.idperiodo = pe.id')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where("c.formapago = 'ci'")
						  ->where('c.idperiodo',$idperiodo)
		                  ->order_by('c.updated_at desc');
		$query = $this->db->get();
		return $query->result();
	}	



	public function get_detalle_lectura_by_cuenta($idcuenta){

		/*$cuentas_data = $this->db->select('dp.id, p.id as idpropiedad, p.numero, tdd.nombre as concepto, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto, c.unidadmedida, dls.valor_ant, dls.valor, (dls.valor - dls.valor_ant) as consumo, ', false)
						  ->from('gc_deuda_propiedad dp')
						  ->join('gc_propiedad p','dp.idpropiedad = p.id')
						  ->join('gc_cuenta c','dp.idcuenta = c.id')
						  ->join('gc_tipo_deuda_detalle tdd','dp.idtipodeudadetalle = tdd.id')
						  ->join('gc_periodo pe','dp.idperiodo = pe.id')
						  ->join('gc_lectura_servicio ls','dp.idcuenta = ls.idcuenta and dp.idtipodeudadetalle = ls.idtipodeudadetalle and ls.idcomunidad =' .$this->session->userdata('comunidadid'),'left')
						  ->join('gc_detalle_lectura_servicio dls','dls.idlectura = ls.id and dls.idpropiedad = dp.idpropiedad')
						  ->where('dp.idcuenta',$idcuenta)
						  ->where('p.idcomunidad',$this->session->userdata('comunidadid'))
		                  ->order_by('p.numero asc');*/

		$query = $this->db->query('select dp.id, p.id as idpropiedad, p.numero, tdd.nombre as concepto, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto, c.unidadmedida, dls.valor_ant, dls.valor, (dls.valor - dls.valor_ant) as consumo from
						  	gc_deuda_propiedad dp
						    inner join gc_propiedad p on dp.idpropiedad = p.id
						    inner join gc_cuenta c on dp.idcuenta = c.id
						    inner join gc_tipo_deuda_detalle tdd on dp.idtipodeudadetalle = tdd.id
						    inner join gc_periodo pe on dp.idperiodo = pe.id
						    left join gc_lectura_servicio ls on dp.idcuenta = ls.idcuenta and dp.idtipodeudadetalle = ls.idtipodeudadetalle and ls.idcomunidad =' .$this->session->userdata('comunidadid') . ' 
						    inner join gc_detalle_lectura_servicio dls on dls.idlectura = ls.id and dls.idpropiedad = dp.idpropiedad
						    where dp.idcuenta = ' . $idcuenta . ' and 
						    p.idcomunidad = ' . $this->session->userdata('comunidadid') . '
		                   	order by LPAD(lower(p.numero), 10,0) asc');


		//$query = $this->db->get();
		//return $query->result();
		return $query->result();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());
	}


	public function get_cuentas_remuneraciones_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.nombreproveedor as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion ')
						  ->from('gc_cuenta c')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_periodo_remuneracion pr','c.idperiodoremuneracion = pr.idperiodo and pr.idcomunidad = c.idcomunidad')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idggcc is null')
						  ->where("c.formapago = 'gc'")
						  ->where('c.idtipodeudadetalle in (52,53,54,55,80,123)')
						  ->where("c.idtipodoctrib <> 4") //NO ES NOTA DE CREDITO
						  ->where("c.idproveedor is null")
						  ->where("pr.aprueba is not null")
						  ->where("c.active = 1")
						  ->order_by('d.nombre asc')
						  ->order_by('c.nombreproveedor asc')
		                  ->order_by('c.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}


	public function get_cuentas_remuneraciones_anticipos_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.nombreproveedor as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion ')
						  ->from('gc_cuenta c')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_periodo_remuneracion pr','c.idperiodoremuneracion = pr.idperiodo and pr.idcomunidad = c.idcomunidad')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idggcc is null')
						  ->where("c.formapago = 'gc'")
						  ->where('c.idtipodeudadetalle in (53,54)')
						  ->where("c.idtipodoctrib <> 4") //NO ES NOTA DE CREDITO
						  ->where("c.idproveedor is null")
						  ->where("pr.anticipo is not null")
						  ->where("pr.aprueba is null")
						  ->where("c.active = 1")
						  ->order_by('d.nombre asc')
						  ->order_by('c.nombreproveedor asc')
		                  ->order_by('c.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	

	public function get_cuentas_gc_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion ')
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idggcc is null')
						  ->where("c.formapago = 'gc'")
		                  ->order_by('c.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}



	public function get_cuentas_no_autoriza_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , p.id as idproveedor, ifnull(p.nombre,c.nombreproveedor) as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idggcc is null')
						  ->where('(p.id is not null or c.retencionidctaasoc is not null)')
						  ->where('(c.idtipodoctrib <> 4 or c.idtipodoctrib is null)')
						  ->where("c.formapago = 'gc'")
						  ->where("c.active = 1")
		                  ->order_by('c.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		//return $query->result();
		//echo $this->db->last_query(); exit;
		return is_null($idcuenta) ? $query->result() : $query->row();

		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}


	public function get_nc_no_autoriza_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , p.id as idproveedor, if(p.nombre is null,c.nombreproveedor,p.nombre) as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, c.monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idggcc is null')
						  ->where('c.idtipodoctrib = 4')
						  ->where("c.formapago = 'gc'")
		                  ->order_by('c.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}


	public function get_ingresos_by_id($idingreso = null,$habilitagasto = null,$noautoriza = null,$noconcilia = false){

		$ingresos_data = $this->db->select('i.id , p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, i.nrodocumento, date_format(i.fecdocumento,"%d/%m/%Y") as fecdocumento, i.fecdocumento as fecdocumento_sformat, d.id as idconcepto, d.nombre as concepto, i.monto, date_format(i.fecvencimiento,"%d/%m/%Y") as fecvencimiento, i.nombrearchivo, i.updated_at, i.descripcion, i.tipoingreso, i.habilitagasto, date_format(c.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion ',false)
						  ->from('gc_ingresos i')
						  ->join('gc_proveedor p','i.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','i.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','i.idtipodoctrib = tdt.id','left')
						  ->join('gc_cartola_caja c','c.idingreso = i.id','left')
						  ->where('i.idcomunidad',$this->session->userdata('comunidadid'))
						  ->group_by('i.id')
		                  ->order_by('i.updated_at desc');
		$ingresos_data = is_null($idingreso) ? $ingresos_data : $ingresos_data->where('i.id',$idingreso);


		if(is_null($noautoriza)){
			$ingresos_data = $ingresos_data->where('i.idggcc is null');
		}


		if($noconcilia){
			$ingresos_data = $ingresos_data->where('c.fechaconciliacion is null');
		}

		if(!is_null($habilitagasto)){
			$hab_query = $habilitagasto ? 1 : 0;
			$ingresos_data = $ingresos_data->where('i.habilitagasto',$hab_query);			

		}

		
		$query = $this->db->get();
		return is_null($idingreso) ? $query->result() : $query->row();


	}


	public function get_saldo_cuentas_impagas_by_id(){

		$cuentas_data = $this->db->select('sum(saldo) as saldo ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_periodo_remuneracion pr','c.idperiodoremuneracion = pr.idperiodo and c.idcomunidad = pr.idcomunidad','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('(c.idperiodoremuneracion is null or (c.idperiodoremuneracion is not null and pr.aprueba is not null) or (c.idperiodoremuneracion is not null and pr.anticipo is not null and c.idtipodeudadetalle in (53,54)))')
						  ->where('(c.idtipodoctrib is null or c.idtipodoctrib <> 4)') #NO SE CONSIDERAN LAS NOTAS DE CRÉDITO

						  //->where('c.idggcc is not null and c.saldo > 0')
						  //->where("c.formapago <> 'ci'")
						  ->where('c.saldo > 0')
						  ->where('c.active = 1')
		                  ->order_by('c.created_at desc');
		$query = $this->db->get();
		return $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	


	public function get_cuentas_impagas_by_id($idcuenta = null,$tipo_cuentas = null,$active = false){

		$cuentas_data = $this->db->select('c.id , c.idproveedor, if(c.idproveedor is null,"cargo","cuenta") as tipocuenta, if(c.nombreproveedor is not null,c.nombreproveedor,p.nombre) as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, c.monto, c.abonado, c.saldo, 	date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.active, c.descripcion, c.idtipodeudadetalle, c.idtipodoctrib, c.formapago ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id','left')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_periodo_remuneracion pr','c.idperiodoremuneracion = pr.idperiodo and c.idcomunidad = pr.idcomunidad','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('(c.idperiodoremuneracion is null or (c.idperiodoremuneracion is not null and pr.aprueba is not null) or (c.idperiodoremuneracion is not null and pr.anticipo is not null and c.idtipodeudadetalle in (53,54)))')
						  ->where('(c.idtipodoctrib is null or c.idtipodoctrib <> 4)') #NO SE CONSIDERAN LAS NOTAS DE CRÉDITO
						  //->where('c.idggcc is not null and c.saldo > 0')
						  //->where("c.formapago <> 'ci'")
						  ->where('c.saldo > 0')
		                  ->order_by('c.created_at desc');


		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);

		$cuentas_data = $active ? $cuentas_data->where('c.active = 1') : $cuentas_data;

		if(!is_null($tipo_cuentas)){
			if($tipo_cuentas == 'activos'){
				$cuentas_data = $cuentas_data->where('c.active',1);
			}else if($tipo_cuentas == 'inactivos'){
				$cuentas_data = $cuentas_data->where('c.active',0);
			}
		}  		                  
		
		$query = $this->db->get();
//echo $this->db->last_query(); exit;
		return is_null($idcuenta) ? $query->result() : $query->row();
		
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	

public function get_notas_credito($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.idproveedor, if(c.idproveedor is null,"cargo","cuenta") as tipocuenta, if(c.nombreproveedor is not null,c.nombreproveedor,p.nombre) as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, c.monto, c.abonado, c.saldo, 	date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.descripcion, c.idtipodeudadetalle, c.idtipodoctrib, c.formapago ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id','left')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_periodo_remuneracion pr','c.idperiodoremuneracion = pr.idperiodo and c.idcomunidad = pr.idcomunidad','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('(c.idperiodoremuneracion is null or (c.idperiodoremuneracion is not null and pr.aprueba is not null))')
						  ->where('c.idtipodoctrib = 4') #SOLO NOTAS DE CRÉDITO
						  //->where('c.idggcc is not null and c.saldo > 0')
						  ->where('c.saldo > 0')
		                  ->order_by('c.created_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return $query->result();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}		



public function get_activo_fijo_impago_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , if(c.idproveedor is null,"cargo","cuenta") as tipocuenta, if(c.nombreproveedor is not null,c.nombreproveedor,p.nombre) as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, c.monto, c.abonado, c.saldo, 	date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.vidautil, c.vidautilresidual, c.baja, c.depreciacion, c.depacum, c.valorresidual ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id','left')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_periodo_remuneracion pr','c.idperiodoremuneracion = pr.idperiodo and c.idcomunidad = pr.idcomunidad','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('(c.idperiodoremuneracion is null or (c.idperiodoremuneracion is not null and pr.aprueba is not null))')
						  ->where("c.formapago = 'af'")
						  ->where('(c.vidautilresidual > 0 or c.vidautil = 0)')
		                  ->order_by('c.created_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() :  $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}

	public function get_cuentas_by_ggcc($ggccid,$idcuenta = null){

		$cuentas_data = $this->db->select('c.id , if(c.idproveedor is null,"cargo","cuenta") as tipocuenta, if(c.nombreproveedor is not null,c.nombreproveedor,p.nombre) as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, c.monto, c.abonado, c.saldo, 	date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.idtipodoctrib ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id','left')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idggcc',$ggccid)
		                  ->order_by('p.nombre asc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                                    
		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return $query->result();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	



	public function get_ingresos_by_ggcc($ggccid,$idingreso = null){

		$ingresos_data = $this->db->select('i.id ,p.nombre as proveedor,  tdt.nombre as tipodocumentotributario, i.nrodocumento, date_format(i.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, i.monto, date_format(i.fecvencimiento,"%d/%m/%Y") as fecvencimiento, i.nombrearchivo, i.updated_at, i.descripcion ',false)						
						  ->from('gc_ingresos i')
						  ->join('gc_proveedor p','i.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','i.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','i.idtipodoctrib = tdt.id','left')
						  ->where('i.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('i.idggcc',$ggccid)
		                  ->order_by('p.nombre asc');
		$ingresos_data = is_null($idingreso) ? $ingresos_data : $ingresos_data->where('i.id',$idingreso);  		                                    
		$query = $this->db->get();
		return is_null($idingreso) ? $query->result() : $query->row();

	}		


	public function get_proveedor_by_cuenta($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , if(c.idproveedor is null,c.nombreproveedor,p.nombre) as proveedor',false)
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
		                  ->order_by('p.nombre asc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();

	}	

	


	public function get_cuentas_by_periodo($idperiodo = null,$idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.idproveedor, c.idtipodeudadetalle, c.formapago, if(c.idproveedor is null,"cargo","cuenta") as tipocuenta, if(c.nombreproveedor is not null,c.nombreproveedor,p.nombre) as proveedor, c.idtipodoctrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, c.monto, c.abonado, c.saldo, 	date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.descripcion ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_ggcc_comunidad gc','c.idggcc = gc.id')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id','left')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('gc.idperiodo',$idperiodo)
		                  ->order_by('p.nombre asc');

		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                                    
		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();		                  
	}		


	public function get_ingresos_by_periodo($idperiodo = null,$idingreso = null){

		$ingresos_data = $this->db->select('i.id ,i.idproveedor, i.idtipodoctrib, i.idtipodeudadetalle, i.tipoingreso, i.habilitagasto, p.nombre as proveedor,  tdt.nombre as tipodocumentotributario, i.nrodocumento, date_format(i.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, i.monto, date_format(i.fecvencimiento,"%d/%m/%Y") as fecvencimiento, i.nombrearchivo, i.updated_at, i.descripcion ',false)
						  ->from('gc_ingresos i')
						  ->join('gc_ggcc_comunidad gc','i.idggcc = gc.id')
						  ->join('gc_proveedor p','i.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','i.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','i.idtipodoctrib = tdt.id','left')
						  ->where('i.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('gc.idperiodo',$idperiodo)
		                  ->order_by('p.nombre asc');
        $ingresos_data = is_null($idingreso) ? $ingresos_data : $ingresos_data->where('i.id',$idingreso);  		                                    
		$query = $this->db->get();
		return is_null($idingreso) ? $query->result() : $query->row();
		
	}		



	public function get_cuentas_by_periodo_format($idperiodo = null,$idcomunidad = null){

	    $comunidadid = is_null($idcomunidad) ? $this->session->userdata('comunidadid') : $idcomunidad;

		$this->db->select('c.id , if(c.idproveedor is null,"cargo","cuenta") as tipocuenta, if(d2.nombre is null,"Otras Cuentas",d2.nombre) as concepto_padre, if(d.nombre is null,"Otros Cargos",d.nombre) as concepto, if(c.nombreproveedor is not null,c.nombreproveedor,p.nombre) as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, c.descripcion, if(c.idtipodoctrib=4,c.monto*(-1),c.monto) as monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo  ',false)
						  ->from('gc_cuenta c')
						  ->join('gc_ggcc_comunidad gc','c.idggcc = gc.id')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id','left')
						  ->join('gc_tipo_deuda_detalle d2','d.idpadre = d2.id','left')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->where('c.idcomunidad',$comunidadid)
						  ->where('gc.idperiodo',$idperiodo)
		                  ->order_by('d2.nombre desc')
		                  ->order_by('d.nombre asc');
                  
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	


	public function get_ingresos_by_periodo_format($idperiodo = null,$idcomunidad = null){

		 $comunidadid = is_null($idcomunidad) ? $this->session->userdata('comunidadid') : $idcomunidad;
		$this->db->select('i.id , "Ingresos" as concepto_padre, if(d.nombre is null,"Otros Ingresos",d.nombre) as concepto, p.nombre as proveedor, tdt.nombre as tipodocumentotributario, i.nrodocumento, date_format(i.fecdocumento,"%d/%m/%Y") as fecdocumento, i.descripcion, i.monto, date_format(i.fecvencimiento,"%d/%m/%Y") as fecvencimiento, i.nombrearchivo  ',false)
						  ->from('gc_ingresos i')
						  ->join('gc_ggcc_comunidad gc','i.idggcc = gc.id')
						  ->join('gc_proveedor p','i.idproveedor = p.id','left')
						  ->join('gc_tipo_deuda_detalle d','i.idtipodeudadetalle = d.id','left')
						  ->join('gc_tipo_documento_tributario tdt','i.idtipodoctrib = tdt.id','left')
						  ->where('i.idcomunidad',$comunidadid)
						  ->where('gc.idperiodo',$idperiodo)
		                  ->order_by('d.nombre asc');
                  
		$query = $this->db->get();
		return $query->result();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	


	public function get_cargos_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.nombreproveedor, date_format(c.fecdocumento,"%d/%m/%Y") as fecpago, c.monto, c.abonado, c.nombrearchivo, c.descripcion ')
						  ->from('gc_cuenta c')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idproveedor is null')
						  ->where('c.idggcc is null')
						  ->where('(c.idtipodeudadetalle not in (52,53,54,55,80,123) or c.idtipodeudadetalle is null)')
		                  ->order_by('c.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	


	public function get_cargos_gc_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.nombreproveedor, date_format(c.fecdocumento,"%d/%m/%Y") as fecpago, c.monto, c.nombrearchivo, c.descripcion ')
						  ->from('gc_cuenta c')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idproveedor is null')
						  ->where('c.idggcc is null')
						  ->where('(c.idtipodeudadetalle not in (52,53,54,55,80,123) or c.idtipodeudadetalle is null)')
						  ->where("c.formapago = 'gc'")
		                  ->order_by('c.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	

	public function get_cuenta_cargos_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , c.nombreproveedor, date_format(c.fecdocumento,"%d/%m/%Y") as fecpago, c.monto, c.abonado, c.nombrearchivo ')
						  ->from('gc_cuenta c')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'));
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);  		                  
		$query = $this->db->get();
		$datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();
		return $datos;
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	


	public function get_cuentas_individuales_by_id($idcuenta = null){

		$cuentas_data = $this->db->select("dp.id, p.numero , case when dp.idfondo = 0 then 'td' else 'f' end as tipo_concepto, ifnull(tdd.id,f.id) as idconcepto, ifnull(tdd.nombre,f.nombre) as concepto, date_format(dp.fechadeuda,'%d/%m/%Y') as fechadeuda, pe.mes, pe.anno, dp.monto, dp.nombrearchivo, dp.descripcion, pes.idperiodo, dp.idpropiedad ", false )
						  ->from('gc_deuda_propiedad dp')
						  ->join('gc_propiedad p','dp.idpropiedad = p.id')
						  ->join('gc_periodo pe','dp.idperiodo = pe.id')
						  ->join('gc_tipo_deuda_detalle tdd','dp.idtipodeudadetalle = tdd.id AND tdd.idtipodeuda in (1,11)','LEFT')
						  ->join('gc_fondos f','dp.idfondo = f.id','LEFT')
						  ->join('gc_periodo_estado pes','pe.id = pes.idperiodo and pes.idcomunidad = '.$this->session->userdata('comunidadid'))
						  ->where('p.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('pes.genera is null')
						  //->where('tdd.idtipodeuda in (1,11)')
						  ->where('dp.idcuenta is null')
		                  ->order_by('dp.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('dp.id',$idcuenta);  

		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	



	public function get_lectura_individuales_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('dp.id, p.numero , tdd.nombre as concepto, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto, dp.nombrearchivo, dp.descripcion, pes.idperiodo, dp.idpropiedad ')
						  ->from('gc_deuda_propiedad dp')
						  ->join('gc_propiedad p','dp.idpropiedad = p.id')
						  ->join('gc_periodo pe','dp.idperiodo = pe.id')
						  ->join('gc_tipo_deuda_detalle tdd','dp.idtipodeudadetalle = tdd.id')
						  ->join('gc_periodo_estado pes','pe.id = pes.idperiodo and pes.idcomunidad = '.$this->session->userdata('comunidadid'))
						  ->where('p.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('pes.genera is null')
						  ->where('tdd.idtipodeuda = 1') // sólo servicios básicos
						  ->where('dp.idcuenta is not null')
		                  ->order_by('dp.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('dp.id',$idcuenta);  

		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}

	public function get_cuentas_espacios_comunes_by_id($idcuenta = null){

		$cuentas_data = $this->db->select('dp.id, p.numero , tdd.nombre as concepto, date_format(dp.fechadeuda,"%d/%m/%Y") as fechadeuda, pe.mes, pe.anno, dp.monto, dp.nombrearchivo, dp.descripcion, dp.idpropiedad  ')
						  ->from('gc_deuda_propiedad dp')
						  ->join('gc_propiedad p','dp.idpropiedad = p.id')
						  ->join('gc_periodo pe','dp.idperiodo = pe.id')
						  ->join('gc_tipo_deuda_detalle tdd','dp.idtipodeudadetalle = tdd.id')
						  ->join('gc_periodo_estado pes','pe.id = pes.idperiodo and pes.idcomunidad = '.$this->session->userdata('comunidadid'))
						  ->where('p.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('pes.genera is null')
						  ->where('tdd.idtipodeuda = 10')
		                  ->order_by('dp.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('dp.id',$idcuenta);  		                  
		$query = $this->db->get();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}	


	public function desautoriza_cuenta($idggcc,$idcuenta){


		$this->db->trans_start();


		$cuenta = $this->account->get_cuentas_by_ggcc($idggcc,$idcuenta);
		if(!is_null($cuenta)){
			$monto = $cuenta->idtipodoctrib == 4 ? $cuenta->monto*(-1) : $cuenta->monto;
			// ACTUALIZA MONTO DEUDA
			$this->db->query("update gc_ggcc_comunidad set 
														monto = monto - " . $monto . ",
														abonado = abonado - " . $monto . "
														where id = " . $idggcc);

			//SEPARÉ ESTE UPDATE, PORQUE DEPENDE DE LOS NUEVOS VALORES DE LOS CAMPOS ACTUALIZADOS EN UPDATE ANTERIOR
			$this->db->query("update gc_ggcc_comunidad set 
														saldo = monto - abonado
														where id = " . $idggcc);
			//ACTUALIZA DEUDA COMUNIDAD
			$this->db->query("update gc_comunidad set 
														saldo = saldo - " . $monto . "
														where id = " . $this->session->userdata('comunidadid'));	


			$this->db->query("update gc_cuenta set 
														idggcc = null,
														fecautoriza = null
														where id = " . $idcuenta);		

			$this->db->trans_complete();
			return true;


		}else{
			return false;

		}

	}




	public function desautoriza_ingreso($idggcc,$idingreso){


		$this->db->trans_start();


		$ingreso = $this->account->get_ingresos_by_ggcc($idggcc,$idingreso);
		if(!is_null($ingreso)){
			// ACTUALIZA MONTO DEUDA
			$this->db->query("update gc_ggcc_comunidad set 
														monto = monto + " . $ingreso->monto . ",
														abonado = abonado + " . $ingreso->monto . "
														where id = " . $idggcc);

			//SEPARÉ ESTE UPDATE, PORQUE DEPENDE DE LOS NUEVOS VALORES DE LOS CAMPOS ACTUALIZADOS EN UPDATE ANTERIOR
			$this->db->query("update gc_ggcc_comunidad set 
														saldo = monto - abonado
														where id = " . $idggcc);
			//ACTUALIZA DEUDA COMUNIDAD
			$this->db->query("update gc_comunidad set 
														saldo = saldo + " . $ingreso->monto . "
														where id = " . $this->session->userdata('comunidadid'));	


			$this->db->query("update gc_ingresos set 
														idggcc = null,
														fecautoriza = null
														where id = " . $idingreso);		

			$this->db->trans_complete();
			return true;


		}else{
			return false;

		}

	}


	public function autoriza_ggcc($idperiodo,$array_cuentas_autorizadas,$array_ingresos_autorizados,$array_nc_autorizados){

		$monto_ggcc = 0;
		$abonado_ggcc = 0;
		$this->db->trans_start();

		$this->load->model('admin');
		$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);


		if(is_null($datos_periodo->genera)){
			$this->db->select('id')
							  ->from('gc_ggcc_comunidad as gc')
			                  ->where('gc.tipo', 'D')
			                  ->where('gc.idcomunidad',$this->session->userdata('comunidadid'))
			                  ->where('gc.idperiodo', $idperiodo);		
			$query = $this->db->get();
			$datos = $query->row();

			if(is_null($datos)){ // no existe autorizacion
				$data = array(
			      	'tipo' => 'D',
			      	'idcomunidad' =>  $this->session->userdata('comunidadid'),
			        'idperiodo' => $idperiodo,				      	
			        'monto' =>  $monto_ggcc,
			        'abonado' =>  0,
			        'saldo' =>  $monto_ggcc
				);
				$this->db->insert('gc_ggcc_comunidad', $data);			

				$idggcc = $this->db->insert_id();

			}else{ // existe por tanto se actualiza

				$idggcc = $datos->id;

				$this->db->query("update gc_ggcc_comunidad set 
															monto = monto + " . $monto_ggcc . ", 
															saldo = saldo + " . $monto_ggcc . "
															where id = " . $idggcc);
			}			

				//MARCACIÓN DE CUENTAS
				foreach ($array_cuentas_autorizadas as $idcuenta) {
					$datos = $this->get_cuenta_cargos_by_id($idcuenta);
					$monto_ggcc += $datos->monto;
					$abonado_ggcc += $datos->abonado;

					$this->db->query("update gc_cuenta set 
																idggcc = " . $idggcc . ",
																fecautoriza = '" . date("Y-m-d H:i:s") . "'
																where id = " . $idcuenta);
				}



				//MARCACIÓN DE NOTAS DE CREDITO
				foreach ($array_nc_autorizados as $idnc) {
					$datos = $this->get_cuenta_cargos_by_id($idnc);
					$monto_ggcc -= $datos->monto;
					$abonado_ggcc -= $datos->abonado;

					$this->db->query("update gc_cuenta set 
																idggcc = " . $idggcc . ",
																fecautoriza = '" . date("Y-m-d H:i:s") . "'
																where id = " . $idnc);
				}


				//MARCACIÓN DE INGRESOS
				foreach ($array_ingresos_autorizados as $idingreso) {
					$datos = $this->get_ingresos_by_id($idingreso);
					$monto_ggcc -= $datos->monto;
					//analizar esto
					$abonado_ggcc -= $datos->monto;

					$this->db->query("update gc_ingresos set 
																idggcc = " . $idggcc . ",
																fecautoriza = '" . date("Y-m-d H:i:s") . "'
																where id = " . $idingreso);
				}				

				// ACTUALIZA MONTO DEUDA
				$this->db->query("update gc_ggcc_comunidad set 
															monto = monto + " . $monto_ggcc . ",
															abonado = abonado + " . $abonado_ggcc . "
															where id = " . $idggcc);

				//SEPARÉ ESTE UPDATE, PORQUE DEPENDE DE LOS NUEVOS VALORES DE LOS CAMPOS ACTUALIZADOS EN UPDATE ANTERIOR
				$this->db->query("update gc_ggcc_comunidad set 
															saldo = monto - abonado
															where id = " . $idggcc);

				//ACTUALIZA DEUDA COMUNIDAD
				$this->db->query("update gc_comunidad set 
															saldo = saldo + " . $monto_ggcc . "
															where id = " . $this->session->userdata('comunidadid'));				


				//ACTUALIZA ESTADO PERIODO
				$this->db->query("update gc_periodo_estado set 
															autoriza = '" . date("Y-m-d H:i:s") . "'
															where idperiodo = " . $idperiodo . "
															and idcomunidad = " . $this->session->userdata('comunidadid'));

				$this->db->trans_complete();
				return true;


		}else{
				$this->db->trans_complete();
				return false;

		}

		
	}	




	public function pago_cuenta($array_cuenta_pago,$parametros){

		$this->db->trans_start();
		$fechapago = substr($parametros['fechapago'],6,4)."-".substr($parametros['fechapago'],3,2)."-".substr($parametros['fechapago'],0,2);
		$monto_pago = 0;

		// guarda cartola de caja
		$this->load->model('admin');
		//$propiedad_cartola = $this->admin->get_propiedad_by_id($parametros['idpropiedad']);
		$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
		$saldo_caja_actual = $datoscomunidad->caja;
		$saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;

		$this->load->model('payment');

		$data = array(
			'idcomunidad' => $this->session->userdata('comunidadid'),
			'folio' => $this->payment->get_correlativo_pago(),
	      	'fechapago' => $fechapago,
	        'idformapago' =>  $parametros['idformapago'],
	        'cheque' =>  $parametros['cheque'],
	        'paguesea' =>  $parametros['paguesea']
		);

		$this->db->insert('gc_listado_pagos', $data);	
		$listado_pagos_id = $this->db->insert_id();			

		foreach ($array_cuenta_pago as $cuenta_pago) {

			$datos_cuenta = $this->get_proveedor_by_cuenta($cuenta_pago['id']);

			
			$ggcc = $this->db->select('idggcc, formapago, idtipodoctrib')
							  ->from('gc_cuenta')
							  ->where('id',$cuenta_pago['id']);
			$query = $this->db->get();
			$result = $query->row(); 
			$idggcc = $result->idggcc;
			$formapago = $result->formapago;
			$idtipodoctrib = $result->idtipodoctrib; #4 = NOTA DE CRÉDITO

			#SI ES NOTA DE CRÉDITO, LOS MONTOS SE RESTAN
			$monto_abono = $idtipodoctrib == 4 ? $cuenta_pago['monto_abono']*(-1) : $cuenta_pago['monto_abono'];

			$monto_pago += $monto_abono;

			$this->db->query("update gc_cuenta set 
														abonado = abonado + " . $cuenta_pago['monto_abono'] . ",
														saldo = saldo - " . $cuenta_pago['monto_abono'] . "
														where id = " . $cuenta_pago['id']);


			if(!is_null($idggcc)){ // rebaja deuda gasto comun en caso que cuenta ya esté asociada

				
				$this->db->query("update gc_ggcc_comunidad set 
															abonado = abonado + " . $monto_abono . ",
															saldo = saldo - " . $monto_abono . "
															where id = " . $idggcc);			

			}



			if($formapago == 'fr'){ // rebaja deuda desde fondo de reserva
				$this->db->query("update gc_comunidad set 
															fondoreserva = fondoreserva - " . $monto_abono . "
															where id = " . $this->session->userdata('comunidadid'));	

				$saldo_fondo_reserva_actual = ($saldo_fondo_reserva_actual - $monto_abono);
				$data = array(
			      	'idcomunidad' => $this->session->userdata('comunidadid'),
			      	'idcuenta' => $cuenta_pago['id'],
			      	'glosa' =>  'Pagos de cuentas de Condominio.  '. $datos_cuenta->proveedor,
			        'monto' => (-1)*$monto_abono,				      	
			        'saldo' =>  $saldo_fondo_reserva_actual,
			        'created_at' => date("Y-m-d h:i:s")
				);
				
				$this->db->insert('gc_cartola_fondo_reserva', $data);																					
			}


			$saldo_caja_actual	+= $monto_abono*(-1);

			$data = array(
		      	'idcuenta' => $cuenta_pago['id'],
		      	'idlistado' => $listado_pagos_id,
		      	'monto' =>  $monto_abono,
		      	'fechapago' => $fechapago,
		        'idformapago' =>  $parametros['idformapago'],
		        'cheque' =>  $parametros['cheque'],
			);

			$this->db->insert('gc_cartola_pagos', $data);	
			$cartola_pagos_id = $this->db->insert_id();	

			$data = array(
		      	'idcomunidad' => $this->session->userdata('comunidadid'),
		      	'idpago' => $cartola_pagos_id,
		      	'glosa' =>  'Pagos de cuentas de Condominio.  '. $datos_cuenta->proveedor,
		        'monto' => $monto_abono*(-1),				      	
		        'saldo' =>  $saldo_caja_actual,
		        'fechapago' => $fechapago,
				'created_at' => date("Y-m-d h:i:s")
			);
			
			$this->db->insert('gc_cartola_caja', $data);		



		}

		$this->db->query("update gc_listado_pagos set 
													monto = " . $monto_pago . "
													where id = " . $listado_pagos_id);	


		$this->db->query("update gc_comunidad set 
													saldo = saldo - " . $monto_pago . ",
													caja = caja - " . $monto_pago . "
													where id = " . $this->session->userdata('comunidadid'));			


		$this->db->trans_complete();

	}	



	public function get_cartola_caja($limit = null){

		$cartola_data = $this->db->select('date_format(c.updated_at,"%d/%m/%Y") as fecha , date_format(c.fechapago,"%d/%m/%Y") as fechapago , date_format(c.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion,  c.glosa, c.id, c.monto, c.saldo, lp.monto as monto_listado, if(c.idpago is null,"abono","pago") as tipo_movimiento, c.protesto, lp.id as idlistado, c.idingreso  ')
						  ->from('gc_cartola_caja c')
						  ->join('gc_cartola_pagos cp','c.idpago = cp.id','left')
						  ->join('gc_listado_pagos lp','cp.idlistado = lp.id','left')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.activo = 1')
		                  ->order_by('c.fechapago desc, c.created_at desc');

		$cartola_data = is_null($limit) ? $cartola_data : $cartola_data->limit($limit);  		                  
		$query = $this->db->get();
		return $query->result();
	}



	public function get_movimientos($limit = null,$tipo_concilia = null,$fechadesde = null, $fechahasta = null,$tipo_fecha = 'pago'){
		$sql_concilia_abonos = "";
		$sql_concilia_pagos = "";
		$sql_concilia_ingresos = "";
		if(!is_null($tipo_concilia)){
			if($tipo_concilia == 'noconcilia'){
				$sql_concilia_abonos = "and la.activo = 1 and la.fechaconciliacion is null";
				$sql_concilia_pagos = "and lp.activo = 1 and lp.fechaconciliacion is null";
				$sql_concilia_ingresos = "and cc.activo = 1  and cc.fechaconciliacion is null";
			}else if($tipo_concilia == 'conciliado'){
				$sql_concilia_abonos = "and la.activo = 1 and la.fechaconciliacion is not null";
				$sql_concilia_pagos = "and lp.activo = 1 and lp.fechaconciliacion is not null";
				$sql_concilia_ingresos = "and cc.activo = 1 and cc.fechaconciliacion is not null";
			}else if($tipo_concilia == 'anulado'){
				$sql_concilia_abonos = "and la.activo = 0 ";
				$sql_concilia_pagos = "and lp.activo = 0 ";
				$sql_concilia_ingresos = "and cc.activo = 0  ";				
			}
		}  		 


		if(!is_null($fechadesde) && !is_null($fechahasta)){
			if($tipo_fecha == 'registro'){
				$sql_filtro_fechas_abonos = "and left(c.created_at,10) between '" . $fechadesde . "' and '" . $fechahasta . "'";
				$sql_filtro_fechas_pagos = "and  left(c.created_at,10) between '" . $fechadesde . "' and '" . $fechahasta . "'";
				$sql_filtro_fechas_ingresos = "and  left(cc.created_at,10) between '" . $fechadesde . "' and '" . $fechahasta . "'";
			}else{
				$sql_filtro_fechas_abonos = "and la.fechapago between '" . $fechadesde . "' and '" . $fechahasta . "'";
				$sql_filtro_fechas_pagos = "and lp.fechapago between '" . $fechadesde . "' and '" . $fechahasta . "'";
				$sql_filtro_fechas_ingresos = "and fechapago between '" . $fechadesde . "' and '" . $fechahasta . "'";
			}

		}else{
			$sql_filtro_fechas_abonos = "";
			$sql_filtro_fechas_pagos = "";
			$sql_filtro_fechas_ingresos = "";

		}                


		$queryQuestion = $this->db->query('
						select id, folio, idpropiedad, DATE_FORMAT(fecha, "%d/%m/%Y") AS fecha, DATE_FORMAT(fechapago, "%d/%m/%Y") AS fechapago_format, fechapago, DATE_FORMAT(fechaconciliacion, "%d/%m/%Y") AS fechaconciliacion, monto, glosa, tipo_movimiento, created_at,  protesto, null as idingreso, 0 as monto_listado, cheque, activo, idcaja from (
							select la.id, la.folio, la.idpropiedad, la.created_at AS fecha, la.fechapago AS fechapago, la.fechaconciliacion AS fechaconciliacion, la.monto, if(la.idprotesto is null,concat("Abono GC de Propiedad # ",p.numero),concat("Protesto de Documento en Movimiento A",lpad((select folio from gc_listado_abonos where id = la.idprotesto),5,"0"))) as glosa, "a" as tipo_movimiento, la.created_at, la.protesto, la.cheque, la.activo, c.id as idcaja from gc_listado_abonos la
							left join gc_cartola_propiedad cp on la.id = cp.idlistado
							left join gc_cartola_caja c on cp.id = c.idabono
							inner join gc_propiedad p on la.idpropiedad = p.id
							where la.idcomunidad = ' . $this->session->userdata('comunidadid') .' ' . $sql_concilia_abonos . ' ' . $sql_filtro_fechas_abonos . '
							group by la.id
							union
							select lp.id, lp.folio, "" as idpropiedad, lp.created_at AS fecha, lp.fechapago AS fechapago, lp.fechaconciliacion AS fechaconciliacion, lp.monto*(-1) as monto, if(lp.idprotesto is null,if(lp.paguesea="",c.glosa,concat("Pago de Cuentas de Condominio. ",lp.paguesea)),concat("Protesto de Documento en Movimiento P",lpad((select folio from gc_listado_pagos where id = lp.idprotesto limit 1),5,"0")))  as glosa, "p" as tipo_movimiento, lp.created_at, lp.protesto, lp.cheque, lp.activo, c.id as idcaja from gc_listado_pagos lp
							left join gc_cartola_pagos cp on lp.id = cp.idlistado
							left join gc_cartola_caja c on cp.id = c.idpago
							where lp.idcomunidad = ' . $this->session->userdata('comunidadid') .' ' . $sql_concilia_pagos . ' ' . $sql_filtro_fechas_pagos . '
							group by lp.id
							union 
							select cc.id, cc.id as folio, "" as idpropiedad, cc.created_at AS fecha, cc.fechapago, cc.fechaconciliacion, cc.monto, cc.glosa, "i" as tipo_movimiento, cc.created_at, cc.protesto, "" as cheque, cc.activo, cc.id as idcaja from gc_cartola_caja cc left join gc_ingresos i on cc.idingreso = i.id
							where cc.idcomunidad = ' . $this->session->userdata('comunidadid') .' and (cc.idingreso is not null or cc.exingreso = 1) and (i.tipoingreso <> "na" OR i.tipoingreso IS NULL) ' . $sql_concilia_ingresos . ' ' . $sql_filtro_fechas_ingresos . '
							) as tmp
							order by fechapago desc, created_at desc, id desc');


		return $queryQuestion->result();

		/*$cartola_data = $this->db->select('lp.id, DATE_FORMAT(lp.created_at, "%d/%m/%Y") AS fecha, DATE_FORMAT(lp.fechapago, "%d/%m/%Y") AS fechapago, DATE_FORMAT(lp.fechaconciliacion, "%d/%m/%Y") AS fechaconciliacion, lp.monto*(-1) as monto, if(lp.paguesea="",c.glosa,concat("Pago de Cuentas de Condominio. ",lp.paguesea)) as glosa, "pago" as tipo_movimiento, 0 as protesto, "" as idingreso, 0 as monto_listado ',false)
						  ->from('gc_listado_pagos lp')
						  ->join('gc_cartola_pagos cp','lp.id = cp.idlistado')
						  ->join('gc_cartola_caja c','cp.id = c.idpago')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('lp.activo = 1')
						  ->group_by('lp.id')
		                  ->order_by('lp.fechapago desc, lp.created_at desc');

		$cartola_data = is_null($limit) ? $cartola_data : $cartola_data->limit($limit);  		                  
		$query = $this->db->get();
		return $query->result();*/
	}

	public function get_cartola_fondo_reserva($limit = null,$fechadesde = null, $fechahasta = null,$eliminados = false){

		$sql_limit = is_null($limit) ? '' : 'limit ' . $limit;
		$sql_fec_desde  = is_null($fechadesde) ? '' : "and left(c.created_at,10) > '" . $fechadesde ."'"; 
		$sql_fec_hasta  = is_null($fechahasta) ? '' : "and left(c.created_at,10) <=  '" . $fechahasta ."'"; 

		$sql_eliminados = $eliminados ? 'and 			(idcuenta in  (
																			select 		idcuenta
																			from 			gc_cartola_fondo_reserva
																			where 		idcomunidad =  ' . $this->session->userdata('comunidadid') . '
																			and 		idcuenta is not null
																			group by		idcuenta 
																			having		sum(monto) <> 0
																			)
														 or idcuenta is null)' : '';

		$queryQuestion = $this->db->query('select 		date_format(c.created_at,"%d/%m/%Y") as fecha , c.glosa, c.id, c.monto, c.saldo, cu.nombrearchivo
						  from 			gc_cartola_fondo_reserva c
						  left join 	gc_cuenta cu on c.idcuenta = cu.id
						  where 		c.idcomunidad = ' . $this->session->userdata('comunidadid') . '
						  and 			c.activo = 1 ' . $sql_fec_desde . ' ' . $sql_fec_hasta . ' ' . $sql_eliminados . '
						  order by 		c.created_at desc, c.id desc ' . $sql_limit);

	

		return $queryQuestion->result();
						 

		/*$cartola_data = $this->db->select('date_format(c.created_at,"%d/%m/%Y") as fecha , c.glosa, c.id, c.monto, c.saldo ')
						  ->from('gc_cartola_fondo_reserva c')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.activo = 1')
		                  ->order_by('c.created_at desc')
		                  ->order_by('c.id desc');

		$cartola_data = is_null($limit) ? $cartola_data : $cartola_data->limit($limit);  		                  
		$cartola_data = is_null($fechadesde) ? $cartola_data : $cartola_data->where("left(c.created_at,10) > '" . $fechadesde ."'");  
		$cartola_data = is_null($fechahasta) ? $cartola_data : $cartola_data->where("left(c.created_at,10) <= '" . $fechahasta ."'");  
		$query = $this->db->get();
		return $query->result();*/
		
	}



	public function get_cartola_otros_fondos($idfondo, $limit = null,$fechadesde = null, $fechahasta = null){

		$sql_limit = is_null($limit) ? '' : 'limit ' . $limit;
		$sql_fec_desde  = is_null($fechadesde) ? '' : "and left(c.created_at,10) > '" . $fechadesde ."'"; 
		$sql_fec_hasta  = is_null($fechahasta) ? '' : "and left(c.created_at,10) <=  '" . $fechahasta ."'"; 

		$queryQuestion = $this->db->query('select 		date_format(c.created_at,"%d/%m/%Y") as fecha , c.glosa, c.id, c.monto, 0 as saldo, "" as nombrearchivo
						  from 			gc_cartola_otros_fondos c
						  where 		c.idcomunidad = ' . $this->session->userdata('comunidadid') . '
						  and 			c.idfondo = ' . $idfondo . '
						  and 			c.activo = 1 ' . $sql_fec_desde . ' ' . $sql_fec_hasta . '						  order by 		c.created_at desc, c.id desc ' . $sql_limit);

		//echo $this->db->last_query(); exit;

		return $queryQuestion->result();
						 

		/*$cartola_data = $this->db->select('date_format(c.created_at,"%d/%m/%Y") as fecha , c.glosa, c.id, c.monto, c.saldo ')
						  ->from('gc_cartola_fondo_reserva c')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.activo = 1')
		                  ->order_by('c.created_at desc')
		                  ->order_by('c.id desc');

		$cartola_data = is_null($limit) ? $cartola_data : $cartola_data->limit($limit);  		                  
		$cartola_data = is_null($fechadesde) ? $cartola_data : $cartola_data->where("left(c.created_at,10) > '" . $fechadesde ."'");  
		$cartola_data = is_null($fechahasta) ? $cartola_data : $cartola_data->where("left(c.created_at,10) <= '" . $fechahasta ."'");  
		$query = $this->db->get();
		return $query->result();*/
		
	}



	public function get_movimiento_by_id($idmovimiento = null){

		$cartola_data = $this->db->select('date_format(c.updated_at,"%d/%m/%Y") as fecha , date_format(c.fechapago,"%d/%m/%Y") as fechapago , date_format(c.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion,  c.glosa, c.id, c.monto, c.saldo, c.idpago, c.idabono, c.idingreso, if(c.idpago is null,if(c.idabono is null,"i","a"),"p") as tipo_movimiento ',false)
						  ->from('gc_cartola_caja c')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.activo = 1')
		                  ->order_by('c.fechapago desc');

		$cartola_data = is_null($idmovimiento) ? $cartola_data : $cartola_data->where('c.id',$idmovimiento);

		$query = $this->db->get();
		return is_null($idmovimiento) ? $query->result() : $query->row();	
	}



	public function get_cartola_by_idmovimiento($tipo_movimiento,$idmovimiento = null){


		if($tipo_movimiento == 'a'){
			$cartola_data = $this->db->select('date_format(c.updated_at,"%d/%m/%Y") as fecha , date_format(c.fechapago,"%d/%m/%Y") as fechapago , date_format(c.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion,  c.glosa, c.id, c.monto, c.saldo, c.idpago, c.idabono, if(c.idpago is null,if(c.idabono is null,"i","a"),"p") as tipo_movimiento ',false)
							  ->from('gc_cartola_caja c')
							  ->join('gc_cartola_propiedad cp','c.idabono = cp.id')
							  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
							  ->where('c.activo = 1')
							  ->where('cp.idlistado',$idmovimiento)
			                  ->order_by('c.fechapago desc');
		}else if($tipo_movimiento == 'p'){
			$cartola_data = $this->db->select('date_format(c.updated_at,"%d/%m/%Y") as fecha , date_format(c.fechapago,"%d/%m/%Y") as fechapago , date_format(c.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion,  c.glosa, c.id, c.monto, c.saldo, c.idpago, c.idabono, if(c.idpago is null,if(c.idabono is null,"i","a"),"p") as tipo_movimiento ',false)
							  ->from('gc_cartola_caja c')
							  ->join('gc_cartola_pagos cp','c.idpago = cp.id')
							  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
							  ->where('c.activo = 1')
							  ->where('cp.idlistado',$idmovimiento)
			                  ->order_by('c.fechapago desc');
		}else if($tipo_movimiento == 'i'){
			$cartola_data = $this->db->select('date_format(c.updated_at,"%d/%m/%Y") as fecha , date_format(c.fechapago,"%d/%m/%Y") as fechapago , date_format(c.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion,  c.glosa, c.id, c.monto, c.saldo, c.idpago, c.idabono, if(c.idpago is null,if(c.idabono is null,"i","a"),"p") as tipo_movimiento ',false)
							  ->from('gc_cartola_caja c')
							  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
							  ->where('c.activo = 1')
							  ->where('c.id',$idmovimiento)
			                  ->order_by('c.fechapago desc');
		}
		$query = $this->db->get();
		return $query->result();	
	}

	public function get_mov_abono_by_id($idmovimiento = null){

		$cartola_data = $this->db->select('l.folio, date_format(l.created_at,"%d/%m/%Y") as fecha , date_format(l.fechapago,"%d/%m/%Y") as fechapago , date_format(l.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion,  concat("Abono GC de Propiedad # ",p.numero) as glosa, l.id, l.monto, "a" as tipo_movimiento, l.idpropiedad, l.idformapago, l.idbanco, l.cheque, l.ruttitular, l.dvtitular, l.fechadeposito',false)
						  ->from('gc_listado_abonos l')
						  ->join('gc_cartola_propiedad cp','l.id = cp.idlistado')
						  ->join('gc_cartola_caja c','cp.id = c.idabono')						  
						  ->join('gc_propiedad p','l.idpropiedad = p.id')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.activo = 1')
		                  ->order_by('c.fechapago desc');

		$cartola_data = is_null($idmovimiento) ? $cartola_data : $cartola_data->where('l.id',$idmovimiento);

		$query = $this->db->get();
		return is_null($idmovimiento) ? $query->result() : $query->row();	
	}


	public function get_mov_pago_by_id($idmovimiento = null){

		$cartola_data = $this->db->select('l.folio, date_format(l.created_at,"%d/%m/%Y") as fecha , date_format(l.fechapago,"%d/%m/%Y") as fechapago , date_format(l.fechaconciliacion,"%d/%m/%Y") as fechaconciliacion,  if(l.paguesea="",c.glosa,concat("Pago de Cuentas de Condominio. ",l.paguesea)) as glosa, l.id, l.monto, l.idformapago, l.cheque, "p" as tipo_movimiento ',false)
						  ->from('gc_listado_pagos l')
						  ->join('gc_cartola_pagos cp','l.id = cp.idlistado')
						  ->join('gc_cartola_caja c','cp.id = c.idpago')						  
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('l.activo = 1')
		                  ->order_by('l.fechapago desc');

		$cartola_data = is_null($idmovimiento) ? $cartola_data : $cartola_data->where('l.id',$idmovimiento);

		$query = $this->db->get();
		return is_null($idmovimiento) ? $query->result() : $query->row();	
	}	

	public function valida_existe_cuenta($proveedor,$tipodoc,$documento,$idcuenta){
		// no valida sobre cuentas individuales
		$cuenta_data = $this->db->select('c.id ')
						  ->from('gc_cuenta c')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idproveedor',$proveedor)
						  ->where('c.idtipodoctrib',$tipodoc)
						  ->where('c.nrodocumento',$documento)
						  ->where("c.formapago in ('gc','fr','sc')")
		                  ->order_by('c.updated_at desc');
		$cuentas_data = $idcuenta == 0 ? $cuenta_data : $cuenta_data->where('c.id <>',$idcuenta);  	
		$query = $this->db->get();
		return count($query->result()) > 0 ? true : false;


	}	



	public function valida_existe_cuenta_cuotas($proveedor,$tipodoc,$documento,$idcuenta){
		// no valida sobre cuentas individuales
		$cuenta_data = $this->db->select('c.id ')
						  ->from('gc_cuenta_cuotas c')
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('c.idproveedor',$proveedor)
						  ->where('c.idtipodoctrib',$tipodoc)
						  ->where('c.nrodocumento',$documento)
		                  ->order_by('c.updated_at desc');
		$cuentas_data = $idcuenta == 0 ? $cuenta_data : $cuenta_data->where('c.id <>',$idcuenta);  	
		$query = $this->db->get();
		return count($query->result()) > 0 ? true : false;


	}	


	public function valida_existe_ingreso($proveedor,$tipodoc,$documento,$idingreso){
		// no valida sobre cuentas individuales
		$ingreso_data = $this->db->select('i.id ')
						  ->from('gc_ingresos i')
						  ->where('i.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('i.idproveedor',$proveedor)
						  ->where('i.idtipodoctrib',$tipodoc)
						  ->where('i.nrodocumento',$documento)
		                  ->order_by('i.updated_at desc');
		$ingreso_data = $idingreso == 0 ? $ingreso_data : $ingreso_data->where('i.id <>',$idingreso);  	
		$query = $this->db->get();
		return count($query->result()) > 0 ? true : false;


	}	


	public function get_abono_by_id($idabono = null){

		$cartola_data = $this->db->select('cp.id, p.numero, cp.monto, cp.adicional, pe.mes, pe.anno, date_format(cp.fechapago,"%d/%m/%Y") as fechapago, cp.idformapago, fp.nombre as forma_pago, b.nombre as banco, cp.cheque, cp.ruttitular, cp.dvtitular, cp.nombrearchivo, cp.idpropiedad, date_format(cp.fechadeposito,"%d/%m/%Y") as fechadeposito, ')
						  ->from('gc_cartola_propiedad cp')
						  ->join('gc_propiedad p','cp.idpropiedad = p.id')
						  ->join('gc_periodo pe','cp.idperiodo = pe.id','left')
						  ->join('gc_forma_pago fp','cp.idformapago = fp.id','left')
						  ->join('gc_banco b','cp.idbanco = b.id','left')						  
						  ->where('p.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('cp.activo = 1')
		                  ->order_by('cp.created_at desc');

		$cartola_data = is_null($idabono) ? $cartola_data : $cartola_data->where('cp.id',$idabono);

		$query = $this->db->get();
		return is_null($idabono) ? $query->result() : $query->row();	
	}



	public function get_listado_abono_by_id($idlistadoabono = null){

		$cartola_data = $this->db->select('la.id, la.folio, p.numero, la.monto, date_format(la.fechapago,"%d/%m/%Y") as fechapago, la.idformapago, fp.nombre as forma_pago, b.nombre as banco, la.cheque, la.ruttitular, la.dvtitular, la.nombrearchivo, la.idpropiedad, date_format(la.fechadeposito,"%d/%m/%Y") as fechadeposito, ')
						  ->from('gc_listado_abonos la')
						  ->join('gc_propiedad p','la.idpropiedad = p.id')
						  ->join('gc_forma_pago fp','la.idformapago = fp.id','left')
						  ->join('gc_banco b','la.idbanco = b.id','left')						  
						  ->where('p.idcomunidad',$this->session->userdata('comunidadid'))
						  //->where('la.activo = 1')
		                  ->order_by('la.created_at desc');

		$cartola_data = is_null($idlistadoabono) ? $cartola_data : $cartola_data->where('la.id',$idlistadoabono);

		$query = $this->db->get();
		return is_null($idlistadoabono) ? $query->result() : $query->row();	
	}


	public function get_pago_by_id($idpago = null){

		$cartola_data = $this->db->select('cp.monto, if(c.idproveedor is null,"cargo","cuenta") as tipocuenta, if(c.idproveedor is null,c.nombreproveedor,p.nombre) as proveedor , date_format(cp.fechapago,"%d/%m/%Y") as fechapago, cp.idformapago, fp.nombre as forma_pago, b.nombre as banco, cp.cheque, cp.ruttitular, cp.dvtitular, c.id as idcuenta',false)
						  ->from('gc_cartola_pagos cp')
						  ->join('gc_cuenta c','cp.idcuenta = c.id')						  
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')	
						  ->join('gc_forma_pago fp','cp.idformapago = fp.id','left')
						  ->join('gc_banco b','cp.idbanco = b.id','left')						  
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('cp.activo = 1')
		                  ->order_by('cp.created_at desc');

		$cartola_data = is_null($idpago) ? $cartola_data : $cartola_data->where('cp.id',$idpago);

		$query = $this->db->get();
		return is_null($idpago) ? $query->result() : $query->row();	
	}



	public function get_lectura_by_cuenta($idcuenta){
 
		$this->db->select('l.id, l.idcomunidad, l.idtipodeudadetalle, l.idcuenta, l.nuevomedidor')
						  ->from('gc_lectura_servicio as l')
		                  ->where('l.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('l.idcuenta', $idcuenta)
		                  ->limit(1);		                  

		$query = $this->db->get();

		return count($query->result()) > 0 ? $query->row() : false;	
	}


	public function get_ultima_lectura($concepto,$idcuenta = null){
 
		$data_lectura = $this->db->select('ls.id')
						  ->from('gc_lectura_servicio ls')
						  ->where('ls.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('ls.idtipodeudadetalle',$concepto)
		                  ->order_by('ls.created_at desc')
		                  ->limit(1);
		$data_lectura = is_null($idcuenta) ? $data_lectura : $data_lectura->where('ls.idcuenta <> ' .$idcuenta);

		$query = $this->db->get();

		return count($query->result()) > 0 ? $query->row() : false;	
	}



	public function add_lectura($concepto,$idcuenta,$nuevomedidor = 'N'){

		$data = array(
	      	'idcomunidad' => $this->session->userdata('comunidadid'),
	      	'idtipodeudadetalle' => $concepto,
	      	'idcuenta' => $idcuenta,
	      	'nuevomedidor' => $nuevomedidor,
			'created_at' => date("Y-m-d H:i:s")
		);
		
		$this->db->insert('gc_lectura_servicio', $data);	
		return $this->db->insert_id();
	}


	public function delete_lectura_by_cuenta($idcuenta){


		$this->db->select('l.id, l.idcomunidad, l.idtipodeudadetalle, l.idcuenta')
						  ->from('gc_lectura_servicio as l')
		                  ->where('l.idcomunidad', $this->session->userdata('comunidadid'))
		                  ->where('l.idcuenta', $idcuenta)
		                  ->limit(1);

		$query = $this->db->get();
		$data_lectura = $query->row();

		// eliminamos todo el detalle de la lectura
		$this->db->where('idlectura',$data_lectura->id);
		$this->db->delete('gc_detalle_lectura_servicio');		


		//eliminamos el encabezado
		$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
		$this->db->where('id',$data_lectura->id);
		$this->db->delete('gc_lectura_servicio');			

	}



	public function add_detalle_lectura($parametros){

		$data = array(
	      	'idlectura' => $parametros['idlectura'],
	      	'idpropiedad' => $parametros['idpropiedad'],
	      	'valor_ant' => $parametros['valor_ant'],
	      	'valor' => $parametros['valor'],
			'created_at' => date("Y-m-d h:i:s")
		);
		
		$this->db->insert('gc_detalle_lectura_servicio', $data);	
		return 1;
	}	


	public function get_detalle_lectura($idlectura){
 
		$this->db->select('dls.id, dls.idpropiedad, dls.valor_ant, dls.valor')
						  ->from('gc_detalle_lectura_servicio dls')
						  ->where('dls.idlectura',$idlectura)
		                  ->order_by('dls.idpropiedad asc');

		$query = $this->db->get();

		return $query->result();	
	}


	private function get_pdf_egreso_content($idegreso){

		$this->db->select('pdf_content ')
						  ->from('gc_listado_pagos ')
						  ->where('id',$idegreso);
		$query = $this->db->get();
		return $query->row();
	}


	public function get_datos_egreso($idegreso){

		$this->db->select("if(lp.paguesea = '',if(c.nombreproveedor is not null,c.nombreproveedor,p.nombre),lp.paguesea) as proveedor, lp.folio, lp.monto, fp.nombre as forma_pago, lp.cheque, lp.id as idegreso, date_format(lp.fechapago,'%d/%m/%Y') as fechapago ",false)
						  ->from('gc_listado_pagos lp')
						  ->join('gc_cartola_pagos cp','cp.idlistado = lp.id')
						  ->join('gc_cuenta c','cp.idcuenta = c.id')
						  ->join('gc_proveedor p','c.idproveedor = p.id','left')
						  ->join('gc_forma_pago fp','lp.idformapago = fp.id')
						  ->where('lp.id',$idegreso)
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'));
		$query = $this->db->get();
		return $query->row();
	}



	private function get_detalle_egreso($idegreso){

		$this->db->select('cc.id as idmovimiento, lp.id, cc.glosa, cp.monto, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, c.descripcion',false)
						  ->from('gc_listado_pagos lp')
						  ->join('gc_cartola_pagos cp','cp.idlistado = lp.id')
						  ->join('gc_cartola_caja cc','cp.id = cc.idpago')
						  ->join('gc_cuenta c','cp.idcuenta = c.id')
						  ->where('lp.id',$idegreso)
						  //->where('cc.protesto = 0')
						  ->where("cc.glosa not like '%Protesto%'");
		$query = $this->db->get();
		return $query->result();
	}


	public function generar_contenido_egreso($idegreso){

			$datos_egreso = $this->get_datos_egreso($idegreso);
			$detalle_egreso = $this->get_detalle_egreso($idegreso);


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
						<p><h4 class="header4"><br>Comprobante Egreso ' .trackid($datos_egreso->folio) . '<br><br><img src="img/logo4_1_80p_color.png" width="100px"></h4></p>
						<hr>
						<br>
						<div class="recto">
						<h4><b>P&aacute;guese a:</b>  ' . $datos_egreso->proveedor . '</h4>
						<h4><b>Fecha Emisi&oacute;n:</b>  ' . date("d") . ' de ' . month2string(date("m")) . ' de ' . date("Y") . ' </h4><br>
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="5%"><p>#</p></th>
						<th width="30%"><p>Detalle</p></th>
						<th width="30%"><p>Descripci&oacute;n</p></th>
						<th width="10%"><p>Nro. Doc.</p></th>
						<th width="10%"><p>Fecha Doc.</p></th>
						<th width="15%">Valor</th>

						</tr>
						</thead>
						<tbody>';
						$i = 1;
						foreach ($detalle_egreso as $egreso) {

							$html .= 	'<tr>
											<td>' . $i . '</td>
											<td>' . $egreso->glosa . '</td>											
											<td>' . $egreso->descripcion . '</td>											
											<td>' . $egreso->nrodocumento . '</td>
											<td>' . $egreso->fecdocumento . '</td>
											<td class="tdClass tdClassNumber"><b>$ ' . number_format($egreso->monto,0,".",".") . '</b></td>
										</tr>';
							$i++;
						}



						

				$html .= '
						<tr>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
						</tr>
						<tr>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass"><b>Total a Pagar</b></td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ ' . number_format($datos_egreso->monto,0,".",".") . '</b></td>
						</tr>
						<tr>
							<td class="tdClass">&nbsp;</td>
							<td colspan="4" class="tdClass"><b><i>Son: '.valorEnLetras($datos_egreso->monto).'</i></b></td>
						</tr>																		
						</tbody>
						</table>
						<br><br>
						</div>
		';

				$html .= '
						<br>
						<hr>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="100%" colspan="4"><p>Datos Pago</p></th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" ><b><i>Forma de Pago:</i></b></td>
						<td class="tdClass" >' . $datos_egreso->forma_pago . '</td>
						<td class="tdClass" ><b><i>Nombre:</i></b></td>
						<td class="tdClass" ></td>						
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Nro:</i></b></td>
						<td class="tdClass" >' . $datos_egreso->cheque . '</td>
						<td class="tdClass" ><b><i>Rut:</i></b></td>
						<td class="tdClass" ></td>						
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Monto:</i></b></td>
						<td class="tdClass" >$' . number_format($datos_egreso->monto,0,".",".") . '</td>
						<td class="tdClass" ><b><i>Fecha:</i></b></td>
						<td class="tdClass" ></td>						
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Fecha Pago:</i></b></td>
						<td class="tdClass" >' . $datos_egreso->fechapago . '</td>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" >&nbsp;</td>						
						</tr>						
						<tr>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" >&nbsp;</td>						
						</tr>						
						<tr>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" >&nbsp;</td>
						<td class="tdClass" ><b><i>Firma:</i></b></td>
						<td class="tdClass" >______________________________</td>						
						</tr>						
						</tbody>
						</table>
						</div>';


				$html .='
						<br>
						<hr>
						<br>
						<br>
						<br>
						<br>						
						<table width="100%" border="0">
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">&nbsp;</td>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">&nbsp;</td>								
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">&nbsp;</td>
								<td width="10%">&nbsp;</td>
							</tr>
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Administrador</td>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Comit&eacute;</td>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Comit&eacute;</td>
								<td width="10%">&nbsp;</td>
							</tr>							
						</table>';



			$html .=	"</body>
						</html>";

						//echo $html; exit;
				
				//$this->db->where('id',$idegreso);
				//$this->db->update('gc_listado_pagos', array('pdf_content' => $html));			
				return $html;

	}			



	public function generar_egreso($idegreso){


			$this->load->model('admin');
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

			//$content = $this->get_pdf_egreso_content($idegreso);

			//if($content->pdf_content == ''){ // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
				$content = $this->generar_contenido_egreso($idegreso);
			//	echo $content; exit;
				//$content = $this->get_pdf_egreso_content($idegreso);
			//}


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
				);  
			//echo $html; exit;
			$this->mpdf->SetTitle('Tu Gasto Común - Comprobante de Egreso');
			$this->mpdf->SetHeader('Condominio '. $datos_comunidad->nombre . ' - ' .$datos_comunidad->comuna . ' - RUT: ' .number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);
			$this->mpdf->WriteHTML($content);
			$this->mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');


			// SE ALMACENA EL ARCHIVO
			$nombre_archivo = date("Y")."_".date("m")."_".date("d")."_Egreso_".$idegreso.".pdf";
			$this->mpdf->Output($nombre_archivo, "I");
			
	}



	public function es_servicio_basico($idconcepto){

		$cuentas_data = $this->db->select('id, nombre, idpadre')
						  ->from('gc_tipo_deuda_detalle')
						  ->where('id',$idconcepto);
		$query = $this->db->get();
		$datos = $query->row();
		return $datos->idpadre == 26 ? true : false; //retorna verdadero si es servicios básicos
	}


	public function put_baja_activo_fijo($idcuenta,$status){				
				$this->db->where('id',$idcuenta);
				$this->db->update('gc_cuenta', array('baja' => $status));			
				return true;
	}			


	public function put_vida_util($idcuenta,$vidautil){		

				$cuentas = $this->get_activo_fijo_impago_by_id($idcuenta);
				$monto = !is_null($cuentas) ? $cuentas->monto : 0;
				$depreciacion = (int)$monto/$vidautil;

				$this->db->where('id',$idcuenta);
				$this->db->update('gc_cuenta', array('vidautil' => $vidautil,
													 'vidautilresidual' => $vidautil,
													 'vuresidualprevia' => $vidautil,
													 'depreciacion' => $depreciacion,
													 'valorresidual' => $cuentas->monto));			
				return true;
	}			


	public function desactiva_cuenta($idcuenta){				

			$datos_cuenta = $this->get_cuentas_impagas_by_id($idcuenta);
			if(!is_null($datos_cuenta)){
				$active = $datos_cuenta->active == 1 ? 0 : 1;
				$fecdesactiva = $datos_cuenta->active == 1 ? date("Y-m-d H:i:s") : null;
				$this->db->where('id',$idcuenta);
				$this->db->update('gc_cuenta', array('active' => $active,
													 'fecdesactiva' => $fecdesactiva));			
				return true;
			}else{
				return false;
			}
	}	
}
