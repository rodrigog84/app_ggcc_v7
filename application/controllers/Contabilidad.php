<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contabilidad extends CI_Controller {

	
	function __construct(){
	  parent::__construct();
	  $this->load->library('ion_auth');
      $this->load->library('form_validation');
      $this->load->helper('format');

      if (!$this->ion_auth->logged_in()){
      	 $this->session->set_userdata('uri_array',$this->uri->rsegment_array());
         redirect('auth/login', 'refresh');
      }else{
      		if(!$this->session->userdata('menu_list')){
      			$this->session->set_userdata('menu_list',json_decode($this->ion_auth_model->get_menu($this->session->userdata('user_id'))));
      		}
      		if($this->router->fetch_class()."/".$this->router->fetch_method() != "main/dashboard" && !$this->session->userdata('comunidadid') && ($this->session->userdata('level') == 1 || $this->session->userdata('level') == 3)){
      			redirect('main/dashboard');	      			
      		}
      }
      
   }


	public function index()
	{

		$this->load->model('ion_auth_model');
		redirect('main/dashboard');	
	}


	public function saldo_inicial(){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$resultid = $this->session->flashdata('saldo_inicial_result');
			if($resultid == 1){
				$vars['message'] = "Saldo inicial guardado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}


			$this->load->model('contabilidad_model','contabilidad');
			#$activos = $this->contabilidad->get_saldos_cuentas(NULL,'ACTIVO'); 
			#$pasivos = $this->contabilidad->get_saldos_cuentas(NULL,'PASIVO'); 
			#$patrimonio = $this->contabilidad->get_saldos_cuentas(NULL,'PATRIMONIO'); 
			$activos = $this->contabilidad->get_saldos_cuentas_inic(NULL,'ACTIVO'); 
			$pasivos = $this->contabilidad->get_saldos_cuentas_inic(NULL,'PASIVO'); 
			$patrimonio = $this->contabilidad->get_saldos_cuentas_inic(NULL,'PATRIMONIO'); 


			$balances = $this->contabilidad->get_balances();
			$tiene_balance = count($balances) > 0 ? true : false;
			//$tiene_balance = !is_null($balances) ? true : false;


			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Saldo Inicial');


			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/saldo_inicial';
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['activos'] = $activos;
			$vars['pasivos'] = $pasivos;
			$vars['patrimonio'] = $patrimonio;
			$vars['tiene_balance'] = $tiene_balance;
			$vars['maleta'] = true;
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}



	public function saldo_actual(){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$this->load->model('contabilidad_model','contabilidad');
			$activos = $this->contabilidad->get_saldos_cuentas(NULL,'ACTIVO'); 
			$pasivos = $this->contabilidad->get_saldos_cuentas(NULL,'PASIVO'); 
			$patrimonio = $this->contabilidad->get_saldos_cuentas(NULL,'PATRIMONIO'); 


			$balances = $this->contabilidad->get_balances();
			$tiene_balance = count($balances) > 0 ? true : false;
			//$tiene_balance = !is_null($balances) ? true : false;


			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Saldo Actual');


			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/saldo_actual';
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['activos'] = $activos;
			$vars['pasivos'] = $pasivos;
			$vars['patrimonio'] = $patrimonio;
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}



	public function submit_saldo_inicial(){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$array_elem = $this->input->post(NULL,true); 
			$array_datos_cuentas = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("_",$elem);
				if($arr_el[0] == 'cuenta'){
					$array_datos_cuentas[$arr_el[1]] = (int)str_replace(".","",$value_elem);
				}
			}
			$this->load->model('contabilidad_model','contabilidad');
			$this->contabilidad->put_saldos_cuentas($array_datos_cuentas);

			$this->session->set_flashdata('saldo_inicial_result', 1);
			redirect('contabilidad/saldo_inicial');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/saldo_inicial';
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['activos'] = $activos;
			$vars['pasivos'] = $pasivos;
			$vars['patrimonio'] = $patrimonio;
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}	



	public function generar_balance(){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('balance_result');
			if($resultid == 1){
				$vars['message'] = "Balance generado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}else if($resultid == 2){
				$vars['message'] = "Balance rechazado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}else if($resultid == 3){
				$vars['message'] = "No es posible generar m&aacute;s de un balance";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}else if($resultid == 4){
				$vars['message'] = "Balance aceptado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}else if($resultid == 5){
				$vars['message'] = "No existe balance indicado";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('contabilidad_model','contabilidad');
			$periodos = $this->contabilidad->get_periodos_validos(); 

			$balances = $this->contabilidad->get_balances_pendientes(); 


			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Generar Balance');


			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/generar_balance';
			$vars['formValidation'] = true;
			$vars['datetimepicker'] = true;
			$vars['periodos'] = $periodos;
			$vars['balances'] = $balances;			
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}




	public function submit_generar_balance(){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$periodo = $this->input->post('periodo');
			$feccorte = $this->input->post('feccorte');
			$this->load->model('contabilidad_model','contabilidad');

			
			$balances = $this->contabilidad->get_balances_pendientes(); 
			if(!is_null($balances)){
				$this->session->set_flashdata('balance_result', 3);
				redirect('contabilidad/generar_balance');				
			}

			
			$this->contabilidad->generar_balance($periodo,$feccorte);

			$this->session->set_flashdata('balance_result', 1);
			redirect('contabilidad/generar_balance');

			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}	



	public function ver_balance($idperiodo){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$resultid = $this->session->flashdata('ver_balance_result');
			if($resultid == 1){
				$vars['message'] = "Valor cuenta modificado correctamente.";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al modificar valor de cuenta.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "No se pueden editar balances ya generados";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('contabilidad_model','contabilidad');

			$balance = $this->contabilidad->get_balances($idperiodo);
			if(is_null($balance)){
				$this->session->set_flashdata('balance_result', 5);
				redirect('contabilidad/generar_balance/');							
			}

			$activos = $this->contabilidad->get_cuentas_balance($idperiodo,NULL,'ACTIVO'); 
			$pasivos = $this->contabilidad->get_cuentas_balance($idperiodo,NULL,'PASIVO'); 
			$patrimonio = $this->contabilidad->get_cuentas_balance($idperiodo,NULL,'PATRIMONIO'); 


			


			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Ver Balance');


			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/ver_balance';
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['activos'] = $activos;
			$vars['pasivos'] = $pasivos;
			$vars['patrimonio'] = $patrimonio;
			$vars['balance'] = $balance;
			$vars['idperiodo'] = $idperiodo;
			
			$template = "template";
			

			$this->load->view($template,$vars);	


		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}


	public function set_cuenta_balance($idperiodo = null,$idcuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if(is_null($idcuenta) || is_null($idperiodo)){

				$this->session->set_flashdata('ver_balance_result', 2);
				redirect('contabilidad/ver_balance/'.$idperiodo);				
			}

			$this->load->model('contabilidad_model','contabilidad');
			$balance = $this->contabilidad->get_balances($idperiodo);
			if($balance->aprueba != ''){
				$this->session->set_flashdata('ver_balance_result', 3);
				redirect('contabilidad/ver_balance/'.$idperiodo);					
			}


			
			$cuenta = $this->contabilidad->get_cuentas_balance($idperiodo,$idcuenta);

			$saldo_cuenta = $this->contabilidad->get_saldos_cuentas($idcuenta);

			$this->load->model('admin');
			$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);			
			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Editar Cuenta Balance');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/set_cuenta_balance';
			$vars['cuenta'] = $cuenta;
			$vars['saldo_cuenta'] = isset($saldo_cuenta->valor) ? $saldo_cuenta->valor : 0;
			$vars['datos_periodo'] = $datos_periodo;
			$vars['formValidation'] = true;

			$vars['mask'] = true;
			$vars['maleta'] = true;
			
			
			$template = "template";
			

			$this->load->view($template,$vars);	



		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}



	public function ver_cuenta_balance($idperiodo = null,$idcuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if(is_null($idcuenta) || is_null($idperiodo)){

				$this->session->set_flashdata('ver_balance_result', 2);
				redirect('contabilidad/ver_balance/'.$idperiodo);				
			}
			$this->load->model('contabilidad_model','contabilidad');
			$balance = $this->contabilidad->get_balances($idperiodo);
			$cuenta = $this->contabilidad->get_cuentas_balance($idperiodo,$idcuenta);
			$detalle_cuenta = $this->contabilidad->ver_cuentas_balance($idperiodo,$idcuenta,$balance);
			//var_dump($detalle_cuenta); exit;
			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Ver Cuenta Balance');

			//var_dump($detalle_cuenta); exit;
			$vars['content_menu'] = $content;		
			switch ($idcuenta){
				case 1:
					$vars['content_view'] = 'contabilidad/cuentas_balance/banco';
					$vars['dataTables'] = true;
					break;				
				case 2:
					$vars['content_view'] = 'contabilidad/cuentas_balance/docto_en_cartera';
					break;
				case 3:
					$vars['content_view'] = 'contabilidad/cuentas_balance/ggcc_por_cobrar';
					$vars['dataTables'] = true;
					break;
				case 4:
					$vars['content_view'] = 'contabilidad/cuentas_balance/int_por_cobrar';
					$vars['dataTables'] = true;
					break;		
				case 19:
					$vars['content_view'] = 'contabilidad/cuentas_balance/otras_multas';
					$vars['dataTables'] = true;
					break;	
				case 20:
					$vars['content_view'] = 'contabilidad/cuentas_balance/otros_cobros';
					$vars['dataTables'] = true;
					break;														
				case 5:
					$vars['content_view'] = 'contabilidad/cuentas_balance/ggcc_por_cobrar_morosos';
					$vars['dataTables'] = true;
					break;										
				case 7:
					$vars['content_view'] = 'contabilidad/cuentas_balance/doctos_por_rendir';
					$vars['dataTables'] = true;
					break;	
				case 8:
					$vars['content_view'] = 'contabilidad/cuentas_balance/bienes_y_equipos';
					$vars['dataTables'] = true;
					break;	
				case 9:
					$vars['content_view'] = 'contabilidad/cuentas_balance/dep_acum';
					$vars['dataTables'] = true;
					break;																								
				case 10:
					$vars['content_view'] = 'contabilidad/cuentas_balance/pagos_anticipados';
					$vars['dataTables'] = true;
					break;		
				case 12:
					$vars['content_view'] = 'contabilidad/cuentas_balance/cuentas_por_pagar';
					$vars['dataTables'] = true;
					break;			
				case 24:
					$vars['content_view'] = 'contabilidad/cuentas_balance/ing_no_identificados';
					$vars['dataTables'] = true;
					break;																		
				case 14:
					$vars['content_view'] = 'contabilidad/cuentas_balance/fondo_reserva';
					$vars['dataTables'] = true;
					break;						
				case 16:
					$vars['content_view'] = 'contabilidad/cuentas_balance/fr_multas';
					$vars['dataTables'] = true;
					break;						
				case 21:
					$vars['content_view'] = 'contabilidad/cuentas_balance/fr_intereses';
					$vars['dataTables'] = true;
					break;	
				case 22:
					$vars['content_view'] = 'contabilidad/cuentas_balance/fr_otros_cobros';
					$vars['dataTables'] = true;
					break;											
				case 17:
					$vars['content_view'] = 'contabilidad/cuentas_balance/excedentes_acumulados';
					$vars['dataTables'] = true;
					break;						
				case 23:
					$vars['content_view'] = 'contabilidad/cuentas_balance/dep_acum';
					$vars['dataTables'] = true;
					break;						
				default:
					//$vars['content_view'] = 'contabilidad/ver_cuenta_balance';
					break;
			}		
			
			$vars['detalle_cuenta'] = $detalle_cuenta;
			$vars['balance'] = $balance;
			$vars['cuenta'] = $cuenta;

			$template = "template";
			

			$this->load->view($template,$vars);	



		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}



	public function submit_cuenta_balance()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('contabilidad_model','contabilidad');
			$result = $this->contabilidad->set_cuenta_balance($this->input->post('cuentaid'),$this->input->post('idperiodo'),str_replace(".","",$this->input->post('monto')));


			if($result){

				$this->session->set_flashdata('ver_balance_result', 1);
				redirect('contabilidad/ver_balance/'.$this->input->post('idperiodo'));				
			}


		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}


	public function acepta_balance($idperiodo){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$this->load->model('contabilidad_model','contabilidad');
			$publicar = $this->contabilidad->aceptar_balance($idperiodo);

			$this->session->set_flashdata('balance_result', 4);
			redirect('contabilidad/generar_balance');	
		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}	



	public function rechaza_balance($idperiodo){
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$this->load->model('contabilidad_model','contabilidad');
			$publicar = $this->contabilidad->rechazar_balance($idperiodo);

			$this->session->set_flashdata('balance_result', 2);
			redirect('contabilidad/generar_balance');	
		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}	



	public function activo_fijo()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('activo_fijo_result');
			if($resultid == 1){
				$vars['message'] = "Vida &uacute;til agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al ingresar vida &uacute;til.  Debe indicar cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al editar vida &uacute;til.  Cuenta ya tiene depreciación";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('account');

			$cuentas = $this->account->get_activo_fijo_impago_by_id();


			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Activo Fijo');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/activo_fijo';
			$vars['cuentas'] = $cuentas;

			$vars['dataTables'] = true;
			$vars['icheck'] = true;
			//$vars['angular'] = true;			
			//$vars['angular_controller'] = 'accounts/add_cuenta.js';;
			
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}


	public function put_vida_util($idcuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if(is_null($idcuenta)){

				$this->session->set_flashdata('activo_fijo_result', 2);
				redirect('contabilidad/activo_fijo');				
			}
			$this->load->model('account');
			$cuenta = $this->account->get_activo_fijo_impago_by_id($idcuenta);

			if($cuenta->vidautil != $cuenta->vidautilresidual){

				$this->session->set_flashdata('activo_fijo_result', 3);
				redirect('contabilidad/activo_fijo');					
			}

			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Agregar Vida &Uacute;til');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/put_vida_util';
			$vars['cuenta'] = $cuenta;
			$vars['formValidation'] = true;
			$vars['datetimepicker'] = true;
			$vars['icheck'] = true;
			
			
			$template = "template";
			

			$this->load->view($template,$vars);	



		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}	



	public function submit_vida_util()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$result = $this->account->put_vida_util($this->input->post('cuentaid'),$this->input->post('vidautil'));


			if($result){

				$this->session->set_flashdata('activo_fijo_result', 1);
				redirect('contabilidad/activo_fijo');				
			}


		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}



	public function ingresos_no_contabilizados()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('ingresos_no_contabilizados_result');
			if($resultid == 1){
				$vars['message'] = "Ingreso Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al agregar Ingreso.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al eliminar Ingreso.  Debe seleccionar al menos uno";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar Ingreso.  Ingreso seleccionado no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$vars['message'] = "Ingreso eliminado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';
			}elseif($resultid == 6){
				$vars['message'] = "Error al eliminar Ingreso.  Ingreso ya se encuentra eliminado";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}

			$this->load->model('contabilidad_model','contabilidad');

			// no se consideran aun los cobros individuales
			$ingresos = $this->contabilidad->get_ingresos_no_contabilizados();


			

			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Ingresos No Identificados');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/ingresos_no_contabilizados';
			$vars['ingresos'] = $ingresos;


			$vars['dataTables'] = true;
			//$vars['angular'] = true;			
			//$vars['angular_controller'] = 'accounts/add_cuenta.js';;
			
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}


	public function add_ingreso_no_contabilizado()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Agregar Ingreso No Contabilizado');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/add_ingreso_no_contabilizado';
			$vars['formValidation'] = true;
			$vars['datetimepicker'] = true;
			$vars['mask'] = true;
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}	


	public function submit_ingreso_no_contabilizado()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			//$nuevo_proveedor = $this->input->post('proveedor');
			$fecingreso = $this->input->post('fecingreso');
			$monto = str_replace(".","",$this->input->post('monto'));
			$descripcion = $this->input->post('descripcion');



			$array_datos = array(
								'fecingreso' => $fecingreso,
								'monto' => $monto,
	       						'descripcion' => $descripcion,
	       						);

			$this->load->model('contabilidad_model','contabilidad');
			$result = $this->contabilidad->add_ingreso_no_contabilizado($array_datos);

			if($result){
				$this->session->set_flashdata('ingresos_no_contabilizados_result', 1);
				redirect('contabilidad/ingresos_no_contabilizados');		
			}else{
				$this->session->set_flashdata('ingresos_no_contabilizados_result', 2);
				redirect('contabilidad/ingresos_no_contabilizados');		
			}



		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}



	public function delete_ingreso_no_contabilizado($idingreso = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('contabilidad_model','contabilidad');
			if(is_null($idingreso)){
				$this->session->set_flashdata('ingresos_no_contabilizados_result', 3);
				redirect('contabilidad/ingresos_no_contabilizados');		

			}else{
				$ingreso = $this->contabilidad->get_ingresos_no_contabilizados($idingreso);
				if(is_null($ingreso)){
					$this->session->set_flashdata('ingresos_no_contabilizados_result', 4);
					redirect('contabilidad/ingresos_no_contabilizados');		
				}else{
					if($ingreso->estado == 'Activo'){
						$this->contabilidad->eliminar_ingreso($idingreso);
						$this->session->set_flashdata('ingresos_no_contabilizados_result', 5);
						redirect('contabilidad/ingresos_no_contabilizados');						
					}else{
						$this->session->set_flashdata('ingresos_no_contabilizados_result', 6);
						redirect('contabilidad/ingresos_no_contabilizados');
					}
		

				}

			}

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}



		


	public function balances_aprobados(){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('contabilidad_model','contabilidad');

			$balances = $this->contabilidad->get_balances_aprobados(); 


			$content = array(
						'menu' => 'Contabilidad',
						'title' => 'Contabilidad',
						'subtitle' => 'Balances Aprobados');


			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'contabilidad/balances_aprobados';
			$vars['balances'] = $balances;			
			
			$template = "template";
			

			$this->load->view($template,$vars);	

		}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}

	}


}

