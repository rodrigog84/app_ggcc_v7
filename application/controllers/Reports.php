<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reports extends CI_Controller {

	
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

	public function cuentas_sin_autorizar($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if($resultid == 1){
				$vars['message'] = "Cuenta Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('account');

			$cuentas = $this->account->get_cuentas_gc_by_id();


			$cargos = $this->account->get_cargos_gc_by_id();

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Cuentas sin Autorizar');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/cuentas_sin_autorizar';
			$vars['cuentas'] = $cuentas;
			$vars['cargos'] = $cargos;

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


	public function cuentas_individuales($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');

			$cuentas_individuales = $this->account->get_cuentas_individuales_by_id();
			$cuentas_espacios_comunes = $this->account->get_cuentas_espacios_comunes_by_id();



			$cargos = $this->account->get_cargos_by_id();

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Cuentas sin Autorizar');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/cuentas_individuales';
			$vars['cuentas_individuales'] = $cuentas_individuales;
			$vars['cuentas_espacios_comunes'] = $cuentas_espacios_comunes;
			$vars['cargos'] = $cargos;

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



	public function movimiento_caja()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			
			$resultid = $this->session->flashdata('movcaja_result');
			if($resultid == 1){
				$vars['message'] = "Movimiento eliminado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al eliminar movimiento";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al eliminar movimiento.  Movimiento corresponde a Protesto";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 4){
				$vars['message'] = "Conciliaci&oacute;n realizada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}				

			$this->load->model('account');

			$movimientos = $this->account->get_cartola_caja(500);

			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));

			//$this->load->model('payment');
			//$saldo_disponible = $this->payment->get_saldo_disponible_by_comunidad($this->session->userdata('comunidadid'));


			$content = array(
						'menu' => 'Banco',
						'title' => 'Banco',
						'subtitle' => 'Conciliaci&oacute;n Bancaria');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/movimiento_caja';
			$vars['movimientos'] = $movimientos;
			$vars['datoscomunidad'] = $datoscomunidad;
			//$vars['saldo_disponible'] = $saldo_disponible;
			//$vars['classinfo_disponible'] = $saldo_disponible > 0 ? 'bg-green' : 'bg-red';
			//$vars['classinfo_caja'] = $datoscomunidad->caja > 0 ? 'bg-green' : 'bg-red';			

			$vars['dataTables'] = true;
			$vars['datetimepicker'] = true;			
			

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



	public function flujo_caja()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			//var_dump($this->input->post(NULL,true)); exit;
			$this->load->model('account');


			//$consulta_conciliacion = is_null($tipo_concilia) ? 'noconcilia' : $tipo_concilia;
			$consulta_conciliacion = $this->input->post('tipoconciliacion') == '' ? 'noconcilia' : $this->input->post('tipoconciliacion');


			$fechadesde = $this->input->post('fechadesde') == '' ? date("Y")."-01-01" : $this->input->post('fechadesde');
			$fechahasta = $this->input->post('fechahasta') == '' ? date("Y")."-12-31" : $this->input->post('fechahasta');
			//$movimientos = $this->account->get_cartola_caja(500);
			$movimientos = $this->account->get_movimientos(null,$consulta_conciliacion,$fechadesde,$fechahasta);

			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));

			//$this->load->model('payment');
			//$saldo_disponible = $this->payment->get_saldo_disponible_by_comunidad($this->session->userdata('comunidadid'));


			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Saldos y Movimientos de Caja');


			/*if($tipo_concilia == 'todos'){
				$title_button = 'Mostrar Todos';
			}else if($tipo_concilia == 'conciliado'){
				$title_button = 'Mostrar Conciliados';
			}else{
				$title_button = 'Mostrar Sin Conciliaci&oacute;n';
			}*/

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/flujo_caja';
			$vars['movimientos'] = $movimientos;
			$vars['datoscomunidad'] = $datoscomunidad;
			//$vars['title_button'] = $title_button;
			$vars['tipoconciliacion'] = $consulta_conciliacion;
			$vars['fechadesde'] = $fechadesde;
			$vars['fechahasta'] = $fechahasta;
			$vars['dataTables'] = true;
			$vars['datetimepicker'] = true;	
			$vars['daterangepicker'] = true;	
			$vars['moment'] = false;		
			$vars['inputmask'] = true;		
			
			

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


	public function cuentas_impagas()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$cuentas = $this->account->get_cuentas_impagas_by_id(null,null,true);

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Cuentas Impagas');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/cuentas_impagas';
			$vars['cuentas'] = $cuentas;

			$vars['dataTables'] = true;
			
			
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


	public function ver_cuenta_individual($idcuenta = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$cuenta = $this->account->get_cuentas_individuales_by_id($idcuenta);

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Cuenta Individual');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_cuenta_individual';
			$vars['existe'] = count($cuenta) > 0 ? true : false;

			if(!$vars['existe']){
				$vars['message'] = "Cuenta Individual no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}

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




	public function ver_lectura_individual($idcuenta = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('report');
			$cuenta = $this->report->get_lectura_individuales_by_id($idcuenta);


			$this->session->keep_flashdata('tiporeporte_mensualdata');
			$this->session->keep_flashdata('mes_mensualdata');
			$this->session->keep_flashdata('anno_mensualdata');




			if(count($cuenta) <= 0){
				$this->session->set_flashdata('ver_detalle_lectura_result', 1);
				redirect('reports/ver_detalle_lectura/'.$idcuenta);

			}			


			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Cuenta Lectura Individual');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_cuenta_individual';



			$vars['existe'] = true;

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



	public function ver_cuenta_esp_comunes($idcuenta = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$cuenta = $this->account->get_cuentas_espacios_comunes_by_id($idcuenta);


			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Cuenta Espacios Comunes');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_cuenta_esp_comunes';
			$vars['existe'] = count($cuenta) > 0 ? true : false;

			if(!$vars['existe']){
				$vars['message'] = "Cuenta de Espacios Comunes no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}

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



	public function ver_cuenta($idcuenta = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('admin');
			$cuenta = $this->admin->get_cuentas_by_id($idcuenta);


			$this->session->keep_flashdata('tiporeporte_mensualdata');
			$this->session->keep_flashdata('mes_mensualdata');
			$this->session->keep_flashdata('anno_mensualdata');

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Cuenta');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_cuenta';
			$vars['existe'] = count($cuenta) > 0 ? true : false;
			$vars['mensual_data'] = $this->session->flashdata('mes_mensualdata') != '' ? true : false;

			if(!$vars['existe']){
				$vars['message'] = "Cuenta no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}

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

	

	public function ver_detalle_lectura($idcuenta = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('ver_detalle_lectura_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta Lectura Individual no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}

			$this->session->keep_flashdata('tiporeporte_mensualdata');
			$this->session->keep_flashdata('mes_mensualdata');
			$this->session->keep_flashdata('anno_mensualdata');

			$this->load->model('account');
			$detalle_lectura = $this->account->get_detalle_lectura_by_cuenta($idcuenta);

			if(count($detalle_lectura) <= 0){
				$this->session->set_flashdata('editar_cuenta_result', 11);
				redirect('accounts/editar_cuenta');

			}


			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Cuentas Lectura Individual');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_detalle_lectura';
			$vars['mensual_data'] = $this->session->flashdata('mes_mensualdata') != '' ? true : false;
			$vars['idcuenta'] = $idcuenta;


			$vars['detalle_lectura'] = $detalle_lectura;
			$vars['dataTables'] = true;

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



	public function ver_ingreso($idingreso = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('admin');
			$ingreso = $this->admin->get_ingresos_by_id($idingreso);

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Ingreso');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_ingreso';
			$vars['existe'] = count($ingreso) > 0 ? true : false;

			if(!$vars['existe']){
				$vars['message'] = "Ingreso no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}

			$vars['ingreso'] = $ingreso;

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

	public function ver_cargo($idcuenta = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('admin');
			$cuenta = $this->admin->get_cargos_by_id($idcuenta);
			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Cargo');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_cargo';
			$vars['existe'] = count($cuenta) > 0 ? true : false;

			if(!$vars['existe']){
				$vars['message'] = "Cargo no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}

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


	public function ver_detalle_periodo($ggccid = '',$idperiodo = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$resultid = $this->session->flashdata('desautorizacion_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta desautorizada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al desautorizar cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al subir comprobante.  Cuenta no corresponde al per&iacute;odo seleccionado";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 4){
				$vars['message'] = "Comprobante cargado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 5){
				$ctas_desautorizadas = $this->session->flashdata('desautorizacion_cantidad_result');
				if($ctas_desautorizadas > 0){
					$vars['message'] = "Se han desautorizado " . $ctas_desautorizadas . " cuentas correctamente";
					$vars['classmessage'] = 'success';
					$vars['icon'] = 'fa-check';				
				}else{
					$vars['message'] = "No se han desautorizado cuentas.  Favor intentar nuevamente";
					$vars['classmessage'] = 'danger';
					$vars['icon'] = 'fa-ban';					
				}
			}


			$desautoriza = false;
			$cuentas = array();
			$ingresos = array();
			$periodo = "";
			$back = "";
			if($idperiodo != ''){
				$this->load->model('account');
				$cuentas = $this->account->get_cuentas_by_periodo($idperiodo);
				$ingresos = $this->account->get_ingresos_by_periodo($idperiodo);

				


				$this->load->model('admin');
				$periodo = $this->admin->get_periodo_by_id($idperiodo);
				$upload = $this->session->userdata('level') == 1 ? true : false;
				$back = "reports/periodos";

			}else if($ggccid != ''){
				$this->load->model('account');
				$cuentas = $this->account->get_cuentas_by_ggcc($ggccid);
				$ingresos = $this->account->get_ingresos_by_ggcc($ggccid);



				$this->load->model('payment');

				$periodo = $this->payment->get_periodo_by_ggcc($ggccid);
				$datosdeuda = $this->payment->get_ggcc_prorrateo_by_comunidad($this->session->userdata('comunidadid'),$periodo->periodoid);

				
				$vars['datosdeuda'] = $datosdeuda;
				$vars['ggccid'] = $ggccid;
				$desautoriza = true;
				$upload = false;
			}
			$content = array(
						'menu' => 'Ver',
						'title' => 'Ver',
						'subtitle' => 'Cuentas/Ingresos asociadas a Gasto Com&uacute;n');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_detalle_periodo';
			$vars['cuentas'] = $cuentas;
			$vars['ingresos'] = $ingresos;
			$vars['periodo'] = $periodo;
			$vars['desautoriza'] = $desautoriza;
			$vars['upload'] = $upload;
			$vars['back'] = $back;
			$vars['origen'] = 'ver_detalle_periodo';
			$vars['icheck'] = true;
			$vars['maleta'] = true;	
			$vars['dataTables'] = true;
			
			
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



	public function ver_detalle_ggcc($idperiodo = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if($idperiodo != ''){
				$this->load->model('account');
				$cuentas = $this->account->get_cuentas_by_periodo_format($idperiodo);


				$ingresos = $this->account->get_ingresos_by_periodo_format($idperiodo);

				/********* DAR FORMA A ARREGLO ***********/
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

				//echo "<pre>";
				//var_dump($detalle); exit;


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


				$this->load->model('admin');
				$periodo = $this->admin->get_periodo_by_id($idperiodo);

			}

			$datosperiodo = $this->admin->get_periodos($this->session->userdata('comunidadid'),$idperiodo);


			$content = array(
						'menu' => 'Ver',
						'title' => 'Ver',
						'subtitle' => 'Cuentas asociadas a Gasto Com&uacute;n');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_detalle_ggcc';
			$vars['padres_ingresos'] = $padres_ingresos;
			$vars['padres'] = $padres;
			$vars['detalle'] = $detalle;
			$vars['detalle_ingresos'] = $detalle_ingresos;
			$vars['periodo'] = $periodo;
			$vars['datosperiodo'] = $datosperiodo;


			if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
				$this->load->model('payment');

				// lectura de prorrateo desde gasto comun
				$ggcc = $this->payment->get_ggcc_by_propiedad($this->session->userdata('propiedadid'),$idperiodo);
				
				if(is_null($ggcc)){
					$this->load->model('admin');
					$propiedad = $this->admin->get_propiedad_by_id($this->session->userdata('propiedadid'));
					$vars['prorrateo'] = $propiedad->prorrateo;
				}else if(is_null($ggcc->prorrateo)){ //si no está almacenado, lee el actual
					$this->load->model('admin');
					$propiedad = $this->admin->get_propiedad_by_id($this->session->userdata('propiedadid'));
					$vars['prorrateo'] = $propiedad->prorrateo;
				}else{
					$vars['prorrateo'] = $ggcc->prorrateo;
				}
			}

			$vars['dataTables'] = true;
			
			
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



	public function export_mensual_data($tiporeporte = null,$mes = null,$anno = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			set_time_limit(0);

			$this->session->keep_flashdata('tiporeporte_mensualdata');
			$this->session->keep_flashdata('mes_mensualdata');
			$this->session->keep_flashdata('anno_mensualdata');


			if(is_null($mes) || is_null($anno)){
				$this->session->set_flashdata('mensualdata_result',1);
				redirect('reports/mensual_data/');			
			}

			$this->load->model('report');
			if($tiporeporte == 'ri'){
				$title_libro = 'detalle_interes';
				$title_report = 'Detalle Intereses';
				$detalle_libro = $this->report->get_intereses_mensuales($mes,$anno);
			}else if($tiporeporte == 'ra'){
				$title_libro = 'detalle_ajustes';
				$title_report = 'Detalle Ajustes';
				$detalle_libro = $this->report->get_ajustes_mensuales($mes,$anno);				
			}else if($tiporeporte == 'rm'){
				$title_libro = 'detalle_multas';
				$title_report = 'Detalle Multas';
				$detalle_libro = $this->report->get_multas_mensuales($mes,$anno);				
			}else if($tiporeporte == 'rce'){
				$title_libro = 'detalle_cuotas_especiales';
				$title_report = 'Detalle Cuotas Especiales';
				$detalle_libro = $this->report->get_cuotas_especiales_mensuales($mes,$anno);								
			}else if($tiporeporte == 'ric'){
				$title_libro = 'detalle_ingresos';
				$title_report = 'Detalle Ingresos';
				$detalle_libro = $this->report->get_ingresos_mensuales($mes,$anno);												
			}else if($tiporeporte == 'rsc'){
				$title_libro = 'detalle_cuentas_sin_cobro';
				$title_report = 'Detalle Cuentas sin Cobro';
				$detalle_libro = $this->report->get_cuentas_sin_cobro($mes,$anno);												
			}else if($tiporeporte == 'cgc'){
				$title_libro = 'detalle_cobro_gasto_comun';
				$title_report = 'Detalle Cobro Gasto Común';
				$detalle_libro = $this->report->get_cobro_gasto_comun($mes,$anno);												
			}else if($tiporeporte == 'rec'){
				$title_libro = 'detalle_espacios_comunes';
				$title_report = 'Detalle Cuentas Espacios Comunes';
				$detalle_libro = $this->report->get_cuentas_espacios_comunes($mes,$anno);												
			}

			/*else{
				$this->session->set_flashdata('mensualdata_result',1);
				redirect('reports/mensual_data/');
			}*/


	        /*$this->load->library('PHPExcel');
	  	    $this->phpexcel->setActiveSheetIndex(0);
	        $sheet = $this->phpexcel->getActiveSheet();*/

        	$spreadsheet = new Spreadsheet();
        	$sheet = $spreadsheet->getActiveSheet();	        
	        $sheet->setTitle($title_libro);

			
			

			$this->load->model('admin');
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));
			

			/********* COMIENZA A CREAR EXCEL *******/
	        // DATOS INICIALES
			$sheet->getColumnDimension('A')->setWidth(5);

	        $sheet->mergeCells('B2:D2');
	        $sheet->setCellValue('B2', $title_report);
	        $sheet->getColumnDimension('B')->setWidth(20);
	        $sheet->setCellValue('B3', 'Nombre Comunidad');
	        $sheet->setCellValue('C3',html_entity_decode($this->session->userdata('comunidadnombre')));
	        $sheet->mergeCells('C3:D3');
	        $sheet->setCellValue('B4', 'Rut Comunidad');
	        $sheet->setCellValue('C4',number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);	        
	        $sheet->mergeCells('C4:D4');
	        $sheet->setCellValue('B5', 'Direccion Comunidad');
	        $sheet->setCellValue('C5',$datos_comunidad->direccion.", ".$datos_comunidad->comuna);	        	        
	        $sheet->mergeCells('C5:D5');
	        $sheet->setCellValue('B6', 'Fecha emision Reporte');
	        $sheet->setCellValue('C6',date('d/m/Y') );
	        $sheet->mergeCells('C6:D6');
	        
			$sheet->getStyle("B2:B6")->getFont()->setBold(true);
			$sheet->getStyle("B2:D6")->getFont()->setSize(10);    	

			//D7E4BC


			/****************** TABLA INICIAL ****************/

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B2:D6")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
			$sheet->getStyle("B2:D2")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:D2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B6:D6")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("D2:D6")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		
			/**********************************************************************************************************/			        
				
			/***** COLOR TABLA ****************/
			$sheet->getStyle("B2:D2")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:D2")->getFill()->getStartColor()->setRGB('D7E4BC');

			$sheet->getStyle("B2:B6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:B6")->getFill()->getStartColor()->setRGB('D7E4BC');			


			$i = 8;



			//ENCABEZADO REPORTE

			if($tiporeporte == 'ri'){

			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(20);
			 $sheet->setCellValue('c'.$i, 'Nro. Propiedad');
			 $sheet->getColumnDimension('D')->setWidth(25);
			 $sheet->setCellValue('D'.$i, 'Descripción');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('E'.$i, 'Fecha Cobro');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Período Cobro');
			 $sheet->getColumnDimension('G')->setWidth(17);
			 $sheet->setCellValue('G'.$i, 'Monto');

			 $columnaFinal = 6;
			 $mergeTotal = 5;
			 $columnaTotales = 6;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
				$filaInicio = $i-1; 
				
				//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
				$linea = 1;
	            foreach ($detalle_libro as $libro) {
	            	$sheet->setCellValue("B".$i,$linea);
	            	$sheet->setCellValue("C".$i,$libro->numero);
	            	$sheet->setCellValue("D".$i,$libro->descripcion);
	            	$sheet->setCellValue("E".$i,$libro->fechadeuda);
	            	$sheet->setCellValue("F".$i,date2string($libro->mes,$libro->anno));
	            	$sheet->setCellValue("G".$i,$libro->monto);
	            	$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');

		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
		 			}	            	
	            	$i++;
	            	$linea++;
	              }

	             $i--;

			}else if($tiporeporte == 'ra'){
				
			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(20);
			 $sheet->setCellValue('c'.$i, 'Nro. Propiedad');
			 $sheet->getColumnDimension('D')->setWidth(25);
			 $sheet->setCellValue('D'.$i, 'Descripción');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('E'.$i, 'Fecha Cobro');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Período Cobro');
			 $sheet->getColumnDimension('G')->setWidth(17);
			 $sheet->setCellValue('G'.$i, 'Monto');

			 $columnaFinal = 6;
			 $mergeTotal = 5;
			 $columnaTotales = 6;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
				$filaInicio = $i-1; 
				
				//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
				$linea = 1;
	            foreach ($detalle_libro as $libro) {
	            	$sheet->setCellValue("B".$i,$linea);
	            	$sheet->setCellValue("C".$i,$libro->numero);
	            	$sheet->setCellValue("D".$i,$libro->descripcion);
	            	$sheet->setCellValue("E".$i,$libro->fechadeuda);
	            	$sheet->setCellValue("F".$i,date2string($libro->mes,$libro->anno));
	            	$sheet->setCellValue("G".$i,$libro->monto);
	            	$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');

		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
		 			}	            	
	            	$i++;
	            	$linea++;
	              }

	             $i--;



			}else if($tiporeporte == 'rm'){
				
			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(20);
			 $sheet->setCellValue('c'.$i, 'Nro. Propiedad');
			 $sheet->getColumnDimension('D')->setWidth(25);
			 $sheet->setCellValue('D'.$i, 'Descripción');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('E'.$i, 'Fecha Cobro');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Período Cobro');
			 $sheet->getColumnDimension('G')->setWidth(17);
			 $sheet->setCellValue('G'.$i, 'Monto');

			 $columnaFinal = 6;
			 $mergeTotal = 5;
			 $columnaTotales = 6;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
				$filaInicio = $i-1; 
				
				//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
				$linea = 1;
	            foreach ($detalle_libro as $libro) {
	            	$sheet->setCellValue("B".$i,$linea);
	            	$sheet->setCellValue("C".$i,$libro->numero);
	            	$sheet->setCellValue("D".$i,$libro->descripcion);
	            	$sheet->setCellValue("E".$i,$libro->fechadeuda);
	            	$sheet->setCellValue("F".$i,date2string($libro->mes,$libro->anno));
	            	$sheet->setCellValue("G".$i,$libro->monto);
	            	$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');

		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
		 			}	            	
	            	$i++;
	            	$linea++;
	              }

	             $i--;



			}else if($tiporeporte == 'rce'){
				
			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(20);
			 $sheet->setCellValue('c'.$i, 'Nro. Propiedad');
			 $sheet->getColumnDimension('D')->setWidth(25);
			 $sheet->setCellValue('D'.$i, 'Descripción');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('E'.$i, 'Fecha Cobro');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Período Cobro');
			 $sheet->getColumnDimension('G')->setWidth(17);
			 $sheet->setCellValue('G'.$i, 'Monto');

			 $columnaFinal = 6;
			 $mergeTotal = 5;
			 $columnaTotales = 6;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
				$filaInicio = $i-1; 
				
				//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
				$linea = 1;
	            foreach ($detalle_libro as $libro) {
	            	$sheet->setCellValue("B".$i,$linea);
	            	$sheet->setCellValue("C".$i,$libro->numero);
	            	$sheet->setCellValue("D".$i,$libro->descripcion);
	            	$sheet->setCellValue("E".$i,$libro->fechadeuda);
	            	$sheet->setCellValue("F".$i,date2string($libro->mes,$libro->anno));
	            	$sheet->setCellValue("G".$i,$libro->monto);
	            	$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');

		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
		 			}	            	
	            	$i++;
	            	$linea++;
	              }

	             $i--;



			}else if($tiporeporte == 'ric'){
				
				 $sheet->getColumnDimension('B')->setWidth(10);
				 $sheet->setCellValue('B'.$i, '#');
				 $sheet->getColumnDimension('C')->setWidth(25);
				 $sheet->setCellValue('c'.$i, 'Proveedor');
				 $sheet->getColumnDimension('D')->setWidth(25);
				 $sheet->setCellValue('D'.$i, 'Concepto');
				 $sheet->getColumnDimension('E')->setWidth(30);
				 $sheet->setCellValue('E'.$i, 'Descripción');
				 $sheet->getColumnDimension('F')->setWidth(15);
				 $sheet->setCellValue('F'.$i, 'Tipo Ingreso');
				 $sheet->getColumnDimension('G')->setWidth(15);
				 $sheet->setCellValue('G'.$i, 'Fecha Documento');	
				 $sheet->getColumnDimension('H')->setWidth(15);
				 $sheet->setCellValue('H'.$i, 'Nro Documento');					 			 
				 $sheet->getColumnDimension('I')->setWidth(15);
				 $sheet->setCellValue('I'.$i, 'Fecha Vencimiento');				 				 
				 $sheet->getColumnDimension('J')->setWidth(17);
				 $sheet->setCellValue('J'.$i, 'Monto');

				 $columnaFinal = 9;
				 $mergeTotal = 8;
				 $columnaTotales = 9;
				 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
				 $i++;
				$filaInicio = $i-1; 
				
				//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
				$linea = 1;
	            foreach ($detalle_libro as $libro) {
	            	$sheet->setCellValue("B".$i,$linea);
	            	$sheet->setCellValue("C".$i,$libro->proveedor);
	            	$sheet->setCellValue("D".$i,$libro->concepto);
	            	$sheet->setCellValue("E".$i,$libro->descripcion);
	            	$sheet->setCellValue("F".$i,tipo_ingreso($libro->tipoingreso));
	            	$sheet->setCellValue("G".$i,$libro->fecdocumento);
	            	$sheet->setCellValue("H".$i,$libro->nrodocumento);
	            	$sheet->setCellValue("I".$i,$libro->fecvencimiento);
	            	$sheet->setCellValue("J".$i,$libro->monto);
	            	$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');

		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
		 			}	            	
	            	$i++;
	            	$linea++;
	              }

	             $i--;



			}else if($tiporeporte == 'rsc'){
				
				 $sheet->getColumnDimension('B')->setWidth(10);
				 $sheet->setCellValue('B'.$i, '#');
				 $sheet->getColumnDimension('C')->setWidth(25);
				 $sheet->setCellValue('c'.$i, 'Proveedor');
				 $sheet->getColumnDimension('D')->setWidth(25);
				 $sheet->setCellValue('D'.$i, 'Concepto');
				 $sheet->getColumnDimension('E')->setWidth(30);
				 $sheet->setCellValue('E'.$i, 'Descripción');
				 $sheet->getColumnDimension('F')->setWidth(15);
				 $sheet->setCellValue('F'.$i, 'Fecha Documento');	
				 $sheet->getColumnDimension('G')->setWidth(15);
				 $sheet->setCellValue('G'.$i, 'Nro Documento');					 			 
				 $sheet->getColumnDimension('H')->setWidth(15);
				 $sheet->setCellValue('H'.$i, 'Fecha Vencimiento');				 				 
				 $sheet->getColumnDimension('I')->setWidth(17);
				 $sheet->setCellValue('I'.$i, 'Monto');

				 $columnaFinal = 8;
				 $mergeTotal = 7;
				 $columnaTotales = 8;
				 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
				 $i++;
				$filaInicio = $i-1; 
				
				//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
				$linea = 1;
	            foreach ($detalle_libro as $libro) {
	            	$sheet->setCellValue("B".$i,$linea);
	            	$sheet->setCellValue("C".$i,$libro->proveedor);
	            	$sheet->setCellValue("D".$i,$libro->concepto);
	            	$sheet->setCellValue("E".$i,$libro->descripcion);
	            	$sheet->setCellValue("F".$i,$libro->fecdocumento);
	            	$sheet->setCellValue("G".$i,$libro->nrodocumento);
	            	$sheet->setCellValue("H".$i,$libro->fecvencimiento);
	            	$sheet->setCellValue("I".$i,$libro->monto);
	            	$sheet->getStyle('I'.$i)->getNumberFormat()->setFormatCode('#,##0');

		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
		 			}	            	
	            	$i++;
	            	$linea++;
	              }

	             $i--;



			}else if($tiporeporte == 'cgc'){
				 $sheet->mergeCells('B'.$i . ':B' . ($i+1));
				 $sheet->getColumnDimension('B')->setWidth(10);
				 $sheet->setCellValue('B'.$i, '#');
				 $sheet->mergeCells('C'.$i . ':C' . ($i+1));
				 $sheet->getColumnDimension('C')->setWidth(15);
				 $sheet->setCellValue('c'.$i, 'Nro');
				 $sheet->mergeCells('D'.$i . ':D' . ($i+1));
				 $sheet->getColumnDimension('D')->setWidth(35);
				 $sheet->setCellValue('D'.$i, 'Responsable');
				 $sheet->mergeCells('E'.$i . ':E' . ($i+1));
				 $sheet->getColumnDimension('E')->setWidth(15);
				 $sheet->setCellValue('E'.$i, 'Prorrateo');
				 $sheet->mergeCells('F'.$i . ':F' . ($i+1));
				 $sheet->getColumnDimension('F')->setWidth(17);
				 $sheet->setCellValue('F'.$i, 'Cobro Individual');	
				 $sheet->mergeCells('G'.$i . ':G' . ($i+1));
				 $sheet->getColumnDimension('G')->setWidth(17);
				 $sheet->setCellValue('G'.$i, 'Fondo de Reserva');	

				 $sheet->mergeCells('H'.$i . ':I' . $i);
				 $sheet->getColumnDimension('H')->setWidth(34);
				 $sheet->setCellValue('H'.$i, 'Cobro Lectura Individual');	


					

				 $sheet->mergeCells('J'.$i . ':J' . ($i+1)); 
				 $sheet->getColumnDimension('J')->setWidth(17);
				 $sheet->setCellValue('J'.$i, 'Multas');					 
				 $sheet->mergeCells('K'.$i . ':K' . ($i+1));
				 $sheet->getColumnDimension('K')->setWidth(17);
				 $sheet->setCellValue('K'.$i, 'Ajustes');					 
				 $sheet->mergeCells('L'.$i . ':L' . ($i+1));
				 $sheet->getColumnDimension('L')->setWidth(17);
				 $sheet->setCellValue('L'.$i, 'Cuotas Especiales');					 
				 $sheet->mergeCells('M'.$i . ':M' . ($i+1));
				 $sheet->getColumnDimension('M')->setWidth(17);
				 $sheet->setCellValue('M'.$i, 'Intereses');					 

				 $sheet->mergeCells('N'.$i . ':N' . ($i+1));
				 $sheet->getColumnDimension('N')->setWidth(17);
				 $sheet->setCellValue('N'.$i, 'Cobro del Mes');	


				 $sheet->mergeCells('O'.$i . ':O' . ($i+1));
				 $sheet->getColumnDimension('O')->setWidth(2);
				 $sheet->setCellValue('O'.$i, '');	

				 $sheet->mergeCells('P'.$i . ':P' . ($i+1));
				 $sheet->getColumnDimension('P')->setWidth(17);
				 $sheet->setCellValue('P'.$i, 'Saldo Anterior');	
				 $sheet->mergeCells('Q'.$i . ':Q' . ($i+1));				 			 
				 $sheet->getColumnDimension('Q')->setWidth(17);
				 $sheet->setCellValue('Q'.$i, 'Cobro Total');	

				 $sheet->mergeCells('R'.$i . ':R' . ($i+1));
				 $sheet->getColumnDimension('R')->setWidth(2);
				 $sheet->setCellValue('R'.$i, '');	


				 $sheet->mergeCells('S'.$i . ':S' . ($i+1));			 				 
				 $sheet->getColumnDimension('S')->setWidth(17);
				 $sheet->setCellValue('S'.$i, 'Cuotas Impagas');	
				 $sheet->mergeCells('T'.$i . ':T' . ($i+1));
				 $sheet->getColumnDimension('T')->setWidth(17);
				 $sheet->setCellValue('T'.$i, 'Observación');

				 $sheet->getColumnDimension('H')->setWidth(17);
				 $sheet->setCellValue('H'.($i+1), 'Agua');	
				 $sheet->getColumnDimension('I')->setWidth(17);
				 $sheet->setCellValue('I'.($i+1), 'Gas');	
				 
				 $columnaFinal = 19;
				 $mergeTotal = 6;
				 $columnaTotales = 19;
				 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).($i+1))->getFont()->setBold(true);
				 $i=$i+2;
				$filaInicio = $i-2; 


				$comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

				$mes_aldia = $comunidad->mes_aldia;
				$mes_moroso = $comunidad->mes_moroso;
				$mes_corteluz = $comunidad->mes_corteluz;
				$mes_prejudicial = $comunidad->mes_prejudicial;
				$mes_judicial = $comunidad->mes_judicial;

				
				//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
				$linea = 1;
	            foreach ($detalle_libro as $libro) {
	            	$sheet->setCellValue("B".$i,$linea);
	            	$sheet->setCellValue("C".$i,$libro->numero);
	            	$sheet->setCellValue("D".$i,$libro->responsable);
	            	$sheet->setCellValue("E".$i,$libro->prorrateo."%");
	            	$sheet->setCellValue("F".$i,$libro->cobro_individual);
	            	$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	$sheet->setCellValue("G".$i,$libro->fondo_reserva);
	            	$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');

	            	$sheet->setCellValue("H".$i,$libro->agua);
	            	$sheet->getStyle('H'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	$sheet->setCellValue("I".$i,$libro->gas);
	            	$sheet->getStyle('I'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	$sheet->setCellValue("J".$i,$libro->multas);
	            	$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	$sheet->setCellValue("K".$i,$libro->ajustes);
	            	$sheet->getStyle('K'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	$sheet->setCellValue("L".$i,$libro->cuotas_especiales);
	            	$sheet->getStyle('L'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	$sheet->setCellValue("M".$i,$libro->otros_cobros);
	            	$sheet->getStyle('M'.$i)->getNumberFormat()->setFormatCode('#,##0');	            		            		            		            	

	            	$sheet->setCellValue("N".$i,$libro->monto);
	            	$sheet->getStyle('N'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	$sheet->setCellValue("O".$i,'');

	            	$sheet->setCellValue("P".$i,$libro->saldo_anterior);
	            	$sheet->getStyle('P'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	$cobro_total = $libro->monto+$libro->saldo_anterior > 0 ? $libro->monto+$libro->saldo_anterior : 0;

	            	$sheet->setCellValue("Q".$i,$cobro_total);
	            	$sheet->getStyle('Q'.$i)->getNumberFormat()->setFormatCode('#,##0');

	            	$sheet->setCellValue("R".$i,'');

	            	$sheet->setCellValue("S".$i,$libro->cuentas_impagas);
	            	//var_dump($mes_judicial); exit; 
	            	$txt_obs = '';
	            	$txt_obs = $libro->cuentas_impagas >= $mes_aldia && $mes_aldia != '-1' ? 'Al día' : $txt_obs;
	            	$txt_obs = $libro->cuentas_impagas >= $mes_moroso  && $mes_moroso != '-1' ? 'Moroso' : $txt_obs;
	            	$txt_obs = $libro->cuentas_impagas >= $mes_corteluz  && $mes_corteluz != '-1' ? 'Corte de Luz' : $txt_obs;
	            	$txt_obs = $libro->cuentas_impagas >= $mes_prejudicial  && $mes_prejudicial != '-1' ? 'Cobranza Prejudicial' : $txt_obs;
	            	$txt_obs = $libro->cuentas_impagas >= $mes_judicial  && $mes_judicial != '-1' ? 'Cobranza Judicial' : $txt_obs;
	            	


	            	$sheet->setCellValue("T".$i,$txt_obs);
		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
		 			}	            	
	            	$i++;
	            	$linea++;
	              }

	             $i--;


			/*********************** Color Cobro Individual********************************************************/
						$sheet->getStyle("F".$filaInicio.":F".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("F".$filaInicio.":F".$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
						$sheet->getStyle("N".$filaInicio.":N".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("N".$filaInicio.":N".$i)->getFill()->getStartColor()->setRGB('E8EDFF');

						$sheet->getStyle("Q".$filaInicio.":Q".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("Q".$filaInicio.":Q".$i)->getFill()->getStartColor()->setRGB('E8EDFF');

						$sheet->getStyle("B".($filaInicio+1).":". ordenLetrasExcel($columnaFinal) .($filaInicio+1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".($filaInicio+1).":". ordenLetrasExcel($columnaFinal) .($filaInicio+1))->getFill()->getStartColor()->setRGB('E8EDFF');




					
					
					
			/******************************************************************************************************/



			}else if($tiporeporte == 'rec'){
				
			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(20);
			 $sheet->setCellValue('c'.$i, 'Nro. Propiedad');
			 $sheet->getColumnDimension('D')->setWidth(25);
			 $sheet->setCellValue('D'.$i, 'Concepto');
			 $sheet->getColumnDimension('E')->setWidth(15);			 
			 $sheet->setCellValue('E'.$i, 'Descripción');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Fecha Cobro');
			 $sheet->getColumnDimension('G')->setWidth(15);
			 $sheet->setCellValue('G'.$i, 'Período Cobro');
			 $sheet->getColumnDimension('H')->setWidth(17);
			 $sheet->setCellValue('H'.$i, 'Monto');

			 $columnaFinal = 7;
			 $mergeTotal = 6;
			 $columnaTotales = 7;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
				$filaInicio = $i-1; 
				
				//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
				$linea = 1;
	            foreach ($detalle_libro as $libro) {
	            	$sheet->setCellValue("B".$i,$linea);
	            	$sheet->setCellValue("C".$i,$libro->numero);
	            	$sheet->setCellValue("D".$i,$libro->concepto);
	            	$sheet->setCellValue("E".$i,$libro->descripcion);
	            	$sheet->setCellValue("F".$i,$libro->fechadeuda);
	            	$sheet->setCellValue("G".$i,date2string($libro->mes,$libro->anno));
	            	$sheet->setCellValue("H".$i,$libro->monto);
	            	$sheet->getStyle('H'.$i)->getNumberFormat()->setFormatCode('#,##0');

		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
		 			}	            	
	            	$i++;
	            	$linea++;
	              }

	             $i--;



			}


			         	
			$sheet->getStyle("B" . $filaInicio . ":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setSize(10);

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B".$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
					for($j=1;$j<=$columnaFinal;$j++){ //borde superior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde izquierdo
						$sheet->getStyle("B".$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/**********************************************************************************************************/			        
				

			/***************************** Segundo borde superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/******************************************************************************************************/
			

		/***************************** Penultimo borde izquierdo ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle("B".$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			



		/***************************** Penultimo borde derecho ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			

			/***************************** Color fila superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->getStartColor()->setRGB('E8EDFF');
					}
			
			/******************************************************************************************************/


		/***************************** Color primera columna ********************************************************/
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


		/***************************** Color montos ********************************************************/
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


			if($tiporeporte == 'cgc'){

				$sheet->getStyle("O".$filaInicio.":O".$i)->getFill()->getStartColor()->setRGB('FFFFFF');

				$sheet->getStyle("O".$filaInicio.":O".$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);


				$sheet->getStyle("O".$filaInicio.":O".$i)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

				$sheet->getStyle("O".$filaInicio.":O".$i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);



				$sheet->getStyle("R".$filaInicio.":R".$i)->getFill()->getStartColor()->setRGB('FFFFFF');

				$sheet->getStyle("R".$filaInicio.":R".$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);


				$sheet->getStyle("R".$filaInicio.":R".$i)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

				$sheet->getStyle("R".$filaInicio.":R".$i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

			}


	



			$sheet->setSelectedCells('E1'); //celda seleccionada

			ob_end_clean();
	        header("Content-Type: application/vnd.ms-excel");
			
	        $nombreArchivo = $title_libro;
	        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
	        header("Cache-Control: max-age=0");
	        // Genera Excel
	        $writer = new Xlsx($spreadsheet); //objeto de PHPExcel, para escribir en el excel
	        //$writer = new PHPExcel_Writer_Excel5($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
	        //$writer = new PHPExcel_Writer_Excel2007($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
	        // Escribir
	        //$writer->setIncludeCharts(TRUE);			
	        $writer->save('php://output');
	        exit;			

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


	public function export_detalle_lectura($idcuenta = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$this->session->keep_flashdata('tiporeporte_mensualdata');
			$this->session->keep_flashdata('mes_mensualdata');
			$this->session->keep_flashdata('anno_mensualdata');



	        /*$this->load->library('PHPExcel');
	  	    $this->phpexcel->setActiveSheetIndex(0);
	        $sheet = $this->phpexcel->getActiveSheet();*/
        	
        	$spreadsheet = new Spreadsheet();
        	$sheet = $spreadsheet->getActiveSheet();	        
	        $sheet->setTitle("detalle_lectura");


			$this->load->model('account');
			$detalle_lectura = $this->account->get_detalle_lectura_by_cuenta($idcuenta);

			$this->load->model('admin');
			$cuenta = $this->admin->get_cuentas_by_id($idcuenta);
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

			$unidadmedida = is_null($cuenta->unidadmedida) ? 'unidad' : $cuenta->unidadmedida;
			

			/********* COMIENZA A CREAR EXCEL *******/
	        // DATOS INICIALES
			$sheet->getColumnDimension('A')->setWidth(5);

	        $sheet->mergeCells('B2:D2');
	        $sheet->setCellValue('B2', 'Detalle Lectura');
	        $sheet->getColumnDimension('B')->setWidth(20);
	        $sheet->setCellValue('B3', 'Nombre Comunidad');
	        $sheet->setCellValue('C3',html_entity_decode($this->session->userdata('comunidadnombre')));
	        $sheet->mergeCells('C3:D3');
	        $sheet->setCellValue('B4', 'Rut Comunidad');
	        $sheet->setCellValue('C4',number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);	        
	        $sheet->mergeCells('C4:D4');
	        $sheet->setCellValue('B5', 'Direccion Comunidad');
	        $sheet->setCellValue('C5',$datos_comunidad->direccion.", ".$datos_comunidad->comuna);	        	        
	        $sheet->mergeCells('C5:D5');
	        $sheet->setCellValue('B6', "Proveedor");
	        $sheet->setCellValue('C6', $cuenta->proveedor);        
	        $sheet->mergeCells('C6:D6');
	        $sheet->setCellValue('B7', "Unidad de Medida");
	        $sheet->setCellValue('C7', $unidadmedida);        
	        $sheet->mergeCells('C7:D7');	
	        $sheet->setCellValue('B8', "Valor ".$unidadmedida);
	        $sheet->setCellValue('C8', "$ ".number_format($cuenta->montounidad,2,",","."));        
	        $sheet->mergeCells('C8:D8');		                
	        $sheet->setCellValue('B9', 'Fecha emision Reporte');
	        $sheet->setCellValue('C9',date('d/m/Y') );
	        $sheet->mergeCells('C9:D9');
	        
			$sheet->getStyle("B2:B9")->getFont()->setBold(true);
			$sheet->getStyle("B2:D9")->getFont()->setSize(10);    	

			//D7E4BC


			/****************** TABLA INICIAL ****************/

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B2:D9")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
			$sheet->getStyle("B2:D2")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:D2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B9:D9")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B9")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B9")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("D2:D9")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		
			/**********************************************************************************************************/			        
				
			/***** COLOR TABLA ****************/
			$sheet->getStyle("B2:D2")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:D2")->getFill()->getStartColor()->setRGB('D7E4BC');

			$sheet->getStyle("B2:B9")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:B9")->getFill()->getStartColor()->setRGB('D7E4BC');			


			$i = 11;

			//ENCABEZADO REPORTE
			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(20);
			 $sheet->setCellValue('c'.$i, 'Nro. Propiedad');
			 $sheet->getColumnDimension('D')->setWidth(25);
			 $sheet->setCellValue('D'.$i, 'Concepto');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('E'.$i, 'Fecha Vencimiento');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Período Cobro');
			 $sheet->getColumnDimension('G')->setWidth(17);
			 $sheet->setCellValue('G'.$i, 'Lectura Actual');
			 $sheet->getColumnDimension('H')->setWidth(17);
			 $sheet->setCellValue('H'.$i, 'Lectura Anterior');
			 $sheet->getColumnDimension('I')->setWidth(17);
			 $sheet->setCellValue('I'.$i, 'Consumo');
			 $sheet->getColumnDimension('J')->setWidth(25);
			 $sheet->setCellValue('J'.$i, 'Monto');

			 $columnaFinal = 9;

			 $mergeTotal = 8;
			 $columnaTotales = 9;

			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
			$filaInicio = $i-1; 
			
			//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
			$linea = 1;
            foreach ($detalle_lectura as $lectura) {
            	$sheet->setCellValue("B".$i,$linea);
            	$sheet->setCellValue("C".$i,$lectura->numero);
            	$sheet->setCellValue("D".$i,$lectura->concepto);
            	$sheet->setCellValue("E".$i,$lectura->fechadeuda);
            	$sheet->setCellValue("F".$i,date2string($lectura->mes,$lectura->anno));
            	$sheet->setCellValue("G".$i,$lectura->valor);
            	$sheet->setCellValue("H".$i,$lectura->valor_ant);
            	$sheet->setCellValue("I".$i,$lectura->consumo);
            	$sheet->setCellValue("J".$i,$lectura->monto);
            	$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');

	 			if($i % 2 != 0){
	 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
	 			}	            	
            	$i++;
            	$linea++;
              }

             $i--;
	        /*$sheet->mergeCells("B".$i.":".ordenLetrasExcel($mergeTotal).$i);
         	$sheet->setCellValue("B".$i,"Total Lectura");

         	$sheet->setCellValue("H".$i,$cuenta->monto);
         	$sheet->getStyle('H'.$i)->getNumberFormat()->setFormatCode('#,##0');	    */
			         	
			$sheet->getStyle("B7:".ordenLetrasExcel($columnaFinal).$i)->getFont()->setSize(10);

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B".$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
					for($j=1;$j<=$columnaFinal;$j++){ //borde superior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde izquierdo
						$sheet->getStyle("B".$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/**********************************************************************************************************/			        
				

			/***************************** Segundo borde superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/******************************************************************************************************/
			

		/***************************** Penultimo borde izquierdo ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle("B".$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			



		/***************************** Penultimo borde derecho ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			


			/***************************** Penultimo borde inferior********************************************************
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).($i-1))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			******************************************************************************************************/
			

				/*** negritas para titulo y totales ******/
				//$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
				//$sheet->getStyle("B".($i-2).":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
						


			/***************************** Color fila superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->getStartColor()->setRGB('E8EDFF');
					}
			
			/******************************************************************************************************/


		/***************************** Color fila inferior********************************************************
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('E8EDFF');


					}
			
			******************************************************************************************************/


		/***************************** Color primera columna ********************************************************/
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


		/***************************** Color montos ********************************************************/
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


			$sheet->setSelectedCells('E1'); //celda seleccionada

			/*
			foreach(range('B',ordenLetrasExcel($columnaFinal)) as $columnID) {
			    $sheet->getColumnDimension($columnID)
			        ->setAutoSize(true);
			}*/


			// Auto size columns for each worksheet

	/*		    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
			    $cellIterator->setIterateOnlyExistingCells(true);
			    foreach ($cellIterator as $cell) {
			        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
			    }*/
			ob_end_clean();
	        header("Content-Type: application/vnd.ms-excel");
	        $nombreArchivo = 'detalle_lectura';
	        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
	        header("Cache-Control: max-age=0");
	        // Genera Excel
	        $writer = new Xlsx($spreadsheet); //objeto de PHPExcel, para escribir en el excel
	        //$writer = new PHPExcel_Writer_Excel2007($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
	        // Escribir
	        //$writer->setIncludeCharts(TRUE);			
	        $writer->save('php://output');
	        exit;			

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



	public function export_saldos_propiedad()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


	        /*$this->load->library('PHPExcel');
	  	    $this->phpexcel->setActiveSheetIndex(0);
	        $sheet = $this->phpexcel->getActiveSheet();*/

        	$spreadsheet = new Spreadsheet();
        	$sheet = $spreadsheet->getActiveSheet();	        
	        $sheet->setTitle("detalle_saldos");


			$this->load->model('payment');
			$this->load->model('admin');
			$detalle_libro = $this->payment->get_deuda_publicada_by_comunidad($this->session->userdata('comunidadid'));
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

			/********* COMIENZA A CREAR EXCEL *******/
	        // DATOS INICIALES
			$sheet->getColumnDimension('A')->setWidth(5);


	        $sheet->mergeCells('B2:D2');
	        $sheet->setCellValue('B2', 'Saldos Propiedades');
	        $sheet->getColumnDimension('B')->setWidth(20);
	        $sheet->setCellValue('B3', 'Nombre Comunidad');
	        $sheet->setCellValue('C3',html_entity_decode($this->session->userdata('comunidadnombre')));
	        $sheet->mergeCells('C3:D3');
	        $sheet->setCellValue('B4', 'Rut Comunidad');
	        $sheet->setCellValue('C4',number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);	        
	        $sheet->mergeCells('C4:D4');
	        $sheet->setCellValue('B5', 'Direccion Comunidad');
	        $sheet->setCellValue('C5',$datos_comunidad->direccion.", ".$datos_comunidad->comuna);	        	        
	        $sheet->mergeCells('C5:D5');
	        $sheet->setCellValue('B6', 'Fecha emision Reporte');
	        $sheet->setCellValue('C6',date('d/m/Y') );
	        $sheet->mergeCells('C6:D6');
	        
 
			$sheet->getStyle("B2:B6")->getFont()->setBold(true);
			$sheet->getStyle("B2:D6")->getFont()->setSize(10);    	

			//D7E4BC


			/****************** TABLA INICIAL ****************/

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B2:D6")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
			$sheet->getStyle("B2:D2")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:D2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B6:D6")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("D2:D6")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		
			/**********************************************************************************************************/			        
				
			/***** COLOR TABLA ****************/
			$sheet->getStyle("B2:D2")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:D2")->getFill()->getStartColor()->setRGB('D7E4BC');

			$sheet->getStyle("B2:B6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:B6")->getFill()->getStartColor()->setRGB('D7E4BC');			


			$i = 8;



			//ENCABEZADO REPORTE

			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(20);
			 $sheet->setCellValue('c'.$i, 'Nro. Propiedad');
			 $sheet->getColumnDimension('D')->setWidth(25);
			 $sheet->setCellValue('D'.$i, 'Responsable');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('E'.$i, 'Saldo Deuda');


			 $columnaFinal = 4;
			 $mergeTotal = 5;
			 $columnaTotales = 4;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
			$filaInicio = $i-1; 
			
			//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
			$linea = 1;
            foreach ($detalle_libro as $libro) {
            	$sheet->setCellValue("B".$i,$linea);
            	$sheet->setCellValue("C".$i,$libro->numero);
            	$sheet->setCellValue("D".$i,$libro->responsable);
            	$sheet->setCellValue("E".$i,$libro->saldo);
            	$sheet->getStyle('E'.$i)->getNumberFormat()->setFormatCode('#,##0');

	 			if($i % 2 != 0){
	 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
	 			}	            	
            	$i++;
            	$linea++;
              }

             $i--;



			         	
			$sheet->getStyle("B" . $filaInicio . ":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setSize(10);

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B".$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
					for($j=1;$j<=$columnaFinal;$j++){ //borde superior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde izquierdo
						$sheet->getStyle("B".$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/**********************************************************************************************************/			        
				

			/***************************** Segundo borde superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/******************************************************************************************************/
			

		/***************************** Penultimo borde izquierdo ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle("B".$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			



		/***************************** Penultimo borde derecho ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			

			/***************************** Color fila superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->getStartColor()->setRGB('E8EDFF');
					}
			
			/******************************************************************************************************/


		/***************************** Color primera columna ********************************************************/
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


		/***************************** Color montos ********************************************************/
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


			$sheet->setSelectedCells('E1'); //celda seleccionada


			ob_end_clean();
	        header("Content-Type: application/vnd.ms-excel");
	        $nombreArchivo = 'saldos_propiedades';
	        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
	        header("Cache-Control: max-age=0");
	        // Genera Excel
	        $writer = new Xlsx($spreadsheet); //objeto de PHPExcel, para escribir en el excel
	        //$writer = new PHPExcel_Writer_Excel2007($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
	        // Escribir
	        //$writer->setIncludeCharts(TRUE);			
	        $writer->save('php://output');
	        exit;			

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


	public function export_detalle_ggcc($idperiodo = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

	        /*$this->load->library('PHPExcel');
	  	    $this->phpexcel->setActiveSheetIndex(0);
	        $sheet = $this->phpexcel->getActiveSheet();*/

        	$spreadsheet = new Spreadsheet();
        	$sheet = $spreadsheet->getActiveSheet();	        
	        $sheet->setTitle("detalle_gc");

			if($idperiodo != ''){
				$this->load->model('account');
				$cuentas = $this->account->get_cuentas_by_periodo_format($idperiodo);

				$ingresos = $this->account->get_ingresos_by_periodo_format($idperiodo);

				/********* DAR FORMA A ARREGLO ***********/
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

				$this->load->model('admin');
				$periodo = $this->admin->get_periodo_by_id($idperiodo);
				


			}

			$datosperiodo = $this->admin->get_periodos($this->session->userdata('comunidadid'),$idperiodo);
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

			/********* COMIENZA A CREAR EXCEL *******/
	        // DATOS INICIALES
			$sheet->getColumnDimension('A')->setWidth(5);

	        $sheet->mergeCells('B2:D2');
	        $sheet->setCellValue('B2', 'Detalle Gasto Comun');
	        $sheet->getColumnDimension('B')->setWidth(20);
	        $sheet->setCellValue('B3', 'Nombre Comunidad');
	        $sheet->setCellValue('C3',html_entity_decode($this->session->userdata('comunidadnombre')));
	        $sheet->mergeCells('C3:D3');
	        $sheet->setCellValue('B4', 'Rut Comunidad');
	        $sheet->setCellValue('C4',number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);	        
	        $sheet->mergeCells('C4:D4');
	        $sheet->setCellValue('B5', 'Direccion Comunidad');
	        $sheet->setCellValue('C5',$datos_comunidad->direccion.", ".$datos_comunidad->comuna);	        	        
	        $sheet->mergeCells('C5:D5');
	        $sheet->setCellValue('B6', "Periodo");
	        $sheet->setCellValue('C6', date2string($datosperiodo->mes,$datosperiodo->anno));        
	        $sheet->mergeCells('C6:D6');
	        $sheet->setCellValue('B7', "Fecha Vencimiento");
	        $sheet->setCellValue('C7', $datosperiodo->fecha_vencimiento);        	        
	        $sheet->mergeCells('C7:D7');
	        $sheet->setCellValue('B8', 'Fecha emision Reporte');
	        $sheet->setCellValue('C8',date('d/m/Y') );
	        $sheet->mergeCells('C8:D8');
	        
			$sheet->getStyle("B2:B8")->getFont()->setBold(true);
			$sheet->getStyle("B2:D8")->getFont()->setSize(10);    	

			//D7E4BC


			/****************** TABLA INICIAL ****************/

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B2:D8")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
			$sheet->getStyle("B2:D2")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:D2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B8:D8")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B8")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B8")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("D2:D8")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		
			/**********************************************************************************************************/			        
				
			/***** COLOR TABLA ****************/
			$sheet->getStyle("B2:D2")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:D2")->getFill()->getStartColor()->setRGB('D7E4BC');

			$sheet->getStyle("B2:B8")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:B8")->getFill()->getStartColor()->setRGB('D7E4BC');			


			$i = 10;

			//ENCABEZADO REPORTE
			 $sheet->setCellValue('B'.$i, 'Concepto');
			 $sheet->getColumnDimension('B')->setWidth(20);
			 $sheet->getColumnDimension('C')->setWidth(25);
			 $sheet->mergeCells('B'.$i.':C'.$i);
			 $sheet->setCellValue('D'.$i, 'Proveedor');
			 $sheet->getColumnDimension('D')->setWidth(25);
			 $sheet->setCellValue('E'.$i, 'Tipo Documento');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Nro. Documento');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('G'.$i, 'Fecha Documento');
			 $sheet->getColumnDimension('G')->setWidth(17);
			 $sheet->setCellValue('H'.$i, 'Descripcion');
			 $sheet->getColumnDimension('H')->setWidth(25);
			 $sheet->setCellValue('I'.$i, 'Deuda Total');
			 $sheet->getColumnDimension('I')->setWidth(13);
			 $sheet->getColumnDimension('J')->setWidth(13);
			 $sheet->mergeCells('I'.$i.':J'.$i);

			 if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
				 $sheet->setCellValue('K'.$i, 'Deuda Propiedad');
				 $sheet->getColumnDimension('K')->setWidth(13);
				 $sheet->getColumnDimension('L')->setWidth(13);
				 $sheet->mergeCells('K'.$i.':L'.$i);
				 $columnaFinal = 11;			 



				$this->load->model('payment');
				// lectura de prorrateo desde gasto comun
				$ggcc = $this->payment->get_ggcc_by_propiedad($this->session->userdata('propiedadid'),$idperiodo);
				
				if(is_null($ggcc)){
					$this->load->model('admin');
					$propiedad = $this->admin->get_propiedad_by_id($this->session->userdata('propiedadid'));
					$vars['prorrateo'] = $propiedad->prorrateo;
				}else if(is_null($ggcc->prorrateo)){ //si no está almacenado, lee el actual
					$this->load->model('admin');
					$propiedad = $this->admin->get_propiedad_by_id($this->session->userdata('propiedadid'));
					$prorrateo = $propiedad->prorrateo;
				}else{
					$prorrateo = $ggcc->prorrateo;
				}

			 }else{

				$columnaFinal = 9;			  	
			 }
			 $mergeTotal = 7;
			 $columnaTotales = 8;

			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
			$filaInicio = $i-1; 
			
			//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  




	         foreach ($padres as $key_padre => $value_padre) {
	         	$sheet->setCellValue("B".$i,$key_padre);
	         	$sheet->setCellValue("J".$i,$value_padre);
				$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');	 
				if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
		         	$sheet->setCellValue("L".$i,$value_padre*($prorrateo/100));
					$sheet->getStyle('L'.$i)->getNumberFormat()->setFormatCode('#,##0');	 
				}
	         	//echo "consulta 1: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
	 			if($i % 2 != 0){
	 				//echo "consulta 2: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
  					//F7F9FD
	 			}					        	
	         	$i++;
         	
	            foreach ($detalle[$key_padre] as $cuenta) {
	            	$sheet->setCellValue("C".$i,html_entity_decode($cuenta->concepto));
	            	$sheet->setCellValue("D".$i,html_entity_decode($cuenta->proveedor));
	            	$sheet->setCellValue("E".$i,html_entity_decode($cuenta->tipodocumentotributario));
	            	$sheet->setCellValue("F".$i,$cuenta->nrodocumento);
	            	$sheet->setCellValue("G".$i,$cuenta->fecdocumento);
	            	$sheet->setCellValue("H".$i,html_entity_decode($cuenta->descripcion));
	            	$sheet->setCellValue("I".$i,$cuenta->monto);
	            	$sheet->getStyle('I'.$i)->getNumberFormat()->setFormatCode('#,##0');

	            	if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
		            	$sheet->setCellValue("K".$i,$cuenta->monto*($prorrateo/100));
		            	$sheet->getStyle('K'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	}
	            	//echo "consulta 3: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
	  					//F7F9FD
		 			}	            	
	            	$i++;
	               }
	          }


	         foreach ($padres_ingresos as $key_padre_ingreso => $value_padre_ingreso) {
	         	$sheet->setCellValue("B".$i,$key_padre_ingreso);
	         	$sheet->setCellValue("J".$i,$value_padre_ingreso*(-1));
				$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');	 
				if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
		         	$sheet->setCellValue("L".$i,$value_padre_ingreso*($prorrateo/100)*(-1));
					$sheet->getStyle('L'.$i)->getNumberFormat()->setFormatCode('#,##0');	 
				}
	         	//echo "consulta 1: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
	 			if($i % 2 != 0){
	 				//echo "consulta 2: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
  					//F7F9FD
	 			}					        	
	         	$i++;
         	
	            foreach ($detalle_ingresos[$key_padre_ingreso] as $ingreso) {
	            	$sheet->setCellValue("C".$i,html_entity_decode($ingreso->concepto));
	            	$sheet->setCellValue("D".$i,html_entity_decode($ingreso->proveedor));
	            	$sheet->setCellValue("E".$i,html_entity_decode($ingreso->tipodocumentotributario));
	            	$sheet->setCellValue("F".$i,$ingreso->nrodocumento);
	            	$sheet->setCellValue("G".$i,$ingreso->fecdocumento);
	            	$sheet->setCellValue("H".$i,html_entity_decode($ingreso->descripcion));
	            	$sheet->setCellValue("I".$i,$ingreso->monto*(-1));
	            	$sheet->getStyle('I'.$i)->getNumberFormat()->setFormatCode('#,##0');

	            	if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
		            	$sheet->setCellValue("K".$i,$ingreso->monto*($prorrateo/100));
		            	$sheet->getStyle('K'.$i)->getNumberFormat()->setFormatCode('#,##0');
	            	}
	            	//echo "consulta 3: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
		 			if($i % 2 != 0){
		 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
	  					//F7F9FD
		 			}	            	
	            	$i++;
	               }
	          }	          
	        // exit;

	        $sheet->mergeCells("B".$i.":".ordenLetrasExcel($mergeTotal).$i);
         	$sheet->setCellValue("B".$i,"Total Gasto Comun");

         	$sheet->setCellValue("J".$i,$datosperiodo->deuda);
         	$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');	    
         	if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
	         	$sheet->setCellValue("L".$i,$datosperiodo->deuda*($prorrateo/100));
	         	$sheet->getStyle('L'.$i)->getNumberFormat()->setFormatCode('#,##0');	    
         	}	          
         	$i++;

         	$sheet->mergeCells("B".$i.":".ordenLetrasExcel($mergeTotal).$i);
         	$sheet->setCellValue("B".$i,"Fondo de Reserva");
         	$sheet->setCellValue("J".$i,$datosperiodo->fondo_reserva);	          
         	$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');	   
         	if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
	         	$sheet->setCellValue("L".$i,$datosperiodo->fondo_reserva*($prorrateo/100));	          
	         	$sheet->getStyle('L'.$i)->getNumberFormat()->setFormatCode('#,##0');	   
         	} 
         	$i++;

         	$sheet->mergeCells("B".$i.":".ordenLetrasExcel($mergeTotal).$i);
         	$sheet->setCellValue("B".$i,"Total");
         	$sheet->setCellValue("J".$i,$datosperiodo->deuda+$datosperiodo->fondo_reserva);	          
         	$sheet->getStyle('J'.$i)->getNumberFormat()->setFormatCode('#,##0');	    
         	if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
	         	$sheet->setCellValue("L".$i,($datosperiodo->deuda+$datosperiodo->fondo_reserva)*($prorrateo/100));	          
	         	$sheet->getStyle('L'.$i)->getNumberFormat()->setFormatCode('#,##0');	    
         	}
         	
			$sheet->getStyle("B7:".ordenLetrasExcel($columnaFinal).$i)->getFont()->setSize(10);

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B".$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
					for($j=1;$j<=$columnaFinal;$j++){ //borde superior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde izquierdo
						$sheet->getStyle("B".$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/**********************************************************************************************************/			        
				

			/***************************** Segundo borde superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/******************************************************************************************************/
			

		/***************************** Penultimo borde derecho ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal-1).$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}

					if($this->session->userdata('level') == 2 || $this->session->userdata('level') == 3){
						for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
							$sheet->getStyle(ordenLetrasExcel($columnaFinal-3).$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
						}
					}
			
			
		/******************************************************************************************************/			


			/***************************** Penultimo borde inferior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).($i-1))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
						$sheet->getStyle(ordenLetrasExcel($j).($i-2))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
						$sheet->getStyle(ordenLetrasExcel($j).($i-3))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/******************************************************************************************************/
			

				/*** negritas para titulo y totales ******/
				$sheet->getStyle("B".($i-2).":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
				$sheet->getStyle("B".($i-2).":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
						


			/***************************** Color fila superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->getStartColor()->setRGB('E8EDFF');
					}
			
			/******************************************************************************************************/


		/***************************** Color fila inferior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle("B".($i-2).":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".($i-2).":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('E8EDFF');


					}
			
			/******************************************************************************************************/


		/***************************** Color montos ********************************************************/
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


			$sheet->setSelectedCells('E1'); //celda seleccionada

			/*
			foreach(range('B',ordenLetrasExcel($columnaFinal)) as $columnID) {
			    $sheet->getColumnDimension($columnID)
			        ->setAutoSize(true);
			}*/


			// Auto size columns for each worksheet

	/*		    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
			    $cellIterator->setIterateOnlyExistingCells(true);
			    foreach ($cellIterator as $cell) {
			        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
			    }*/
	
			ob_end_clean();
	        header("Content-Type: application/vnd.ms-excel");
	        $nombreArchivo = 'detalle_GC';
	        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
	        header("Cache-Control: max-age=0");
	        // Genera Excel
	        $writer = new Xlsx($spreadsheet); //objeto de PHPExcel, para escribir en el excel
	        //$writer = new PHPExcel_Writer_Excel2007($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
	        // Escribir
	        //$writer->setIncludeCharts(TRUE);			
	        $writer->save('php://output');
	        exit;			

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

	public function periodos($idperiodo = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('admin');

			$datosperiodo = $this->admin->get_periodos($this->session->userdata('comunidadid'));
			$periodo_inicial = $this->admin->get_periodo_inicial();

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Per&iacute;odos');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/periodos';
			$vars['datosperiodo'] = $datosperiodo;
			$vars['periodo_inicial'] = $periodo_inicial;
			$vars['idperiodo'] = $idperiodo;


			$vars['dataTables'] = true;
			
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


	public function ver_propiedades_periodo($idperiodo = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('payment');
			$propiedades = $this->payment->get_propiedad_by_periodo($idperiodo);

			$this->load->model('admin');
			$datosperiodo = $this->admin->get_periodo_by_id($idperiodo);
			$periodo_inicial = $this->admin->get_periodo_inicial();


			$content = array(
						'menu' => 'Ver',
						'title' => 'Ver',
						'subtitle' => 'Propiedades');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/ver_propiedades_periodo';
			$vars['propiedades'] = $propiedades;
			$vars['datosperiodo'] = $datosperiodo;
			$vars['idperiodo'] = $idperiodo;
			$vars['periodo_inicial'] = $periodo_inicial;


			$vars['gritter'] = true;
			$vars['loadingOverlay'] = true;


			$vars['dataTables'] = true;
			
			
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



	public function reenviar_comprobante($idperiodo,$idpropiedad = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			set_time_limit(0);

			$this->load->model('payment');
			$this->load->model('admin');

			$periodo = $this->admin->get_periodo_by_id($idperiodo);

			if(!is_null($periodo->publica)){

				$propiedades = $this->admin->get_propiedades_comunidad();

				foreach ($propiedades as $propiedad) { // ENVIO DE MAIL

					if($propiedad->mail != '' and $propiedad->suscrito == 1){
						if(is_null($idpropiedad)){
								$this->payment->generar_mail($this->session->userdata('comunidadid'),$idperiodo,$propiedad);
							
						}else{
							if($propiedad->id == $idpropiedad){
								$this->payment->generar_mail($this->session->userdata('comunidadid'),$idperiodo,$propiedad);
							}

						}


					}


				}

			}

			

			$data = 1;
			echo json_encode($data);

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



	public function ver_movimiento($idmovimiento = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$this->session->keep_flashdata('tipoconciliacion_conc');
			$this->session->keep_flashdata('fechadesde_conc');
			$this->session->keep_flashdata('fechahasta_conc');

			
			$this->load->model('account');
			$movimiento = $this->account->get_movimiento_by_id($idmovimiento);
			$op = "Movimiento";
			if(count($movimiento) > 0){

				if(is_null($movimiento->idpago)){
					if(is_null($movimiento->idabono)){
						redirect('reports/ver_ingreso/'.$movimiento->idingreso);
					}else{
						$datos_movimiento = $this->account->get_abono_by_id($movimiento->idabono);
					}
				}else{
						$datos_movimiento = $this->account->get_pago_by_id($movimiento->idpago);	
				}
				//$datos_movimiento = is_null($movimiento->idpago) ? $this->account->get_abono_by_id($movimiento->idabono) : $this->account->get_pago_by_id($movimiento->idpago);
				$existe = true;
				$view = is_null($movimiento->idpago) ?  'reports/ver_abono' : 'reports/ver_pago';
				$op = is_null($movimiento->idpago) ?  'Abono Gasto Com&uacute;n' : 'Pago Cuenta';
			}else{
				$datos_movimiento = array();
				$existe = false;
				$view = 'reports/ver_abono';
			}
			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver ' . $op);

			$vars['content_menu'] = $content;				
			$vars['content_view'] = $view;
			$vars['existe'] = $existe;
			$vars['datos_movimiento'] = $datos_movimiento;

			if(!$vars['existe']){
				$vars['message'] = "Abono Deuda Gasto Com&uacute;n no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}

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


	public function fondo_reserva()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$movimientos = $this->account->get_cartola_fondo_reserva(500,null,null,true);

			//$this->load->model('admin');
			//$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));

			//$this->load->model('payment');
			//$saldo_disponible = $this->payment->get_saldo_disponible_by_comunidad($this->session->userdata('comunidadid'));


			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Saldos y Movimientos de Fondo de Reserva');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/fondo_reserva';
			$vars['movimientos'] = $movimientos;
			//$vars['datoscomunidad'] = $datoscomunidad;
			//$vars['saldo_disponible'] = $saldo_disponible;
			//$vars['classinfo_disponible'] = $saldo_disponible > 0 ? 'bg-green' : 'bg-red';
			//$vars['classinfo_caja'] = $datoscomunidad->caja > 0 ? 'bg-green' : 'bg-red';			

			$vars['dataTables'] = true;
			$vars['datetimepicker'] = true;			
			

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



public function mensual_data($resultid = '')
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$resultid = $this->session->flashdata('mensualdata_result');
			if($resultid == 1){
				$vars['message'] = "Error al exportar informaci&oacute;n";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';			
			}

			//print_r($this->input->post(NULL,true)); exit;

			if($this->input->post('tiporeporte') != ''){
				$tiporeporte = $this->input->post('tiporeporte');
				$this->session->set_flashdata('tiporeporte_mensualdata',$tiporeporte);
			}else{
				$tiporeporte = $this->session->flashdata('tiporeporte_mensualdata') == '' ? '' : $this->session->flashdata('tiporeporte_mensualdata');				
			}


			if($this->input->post('mes') != ''){
				$mes = $this->input->post('mes');
				$this->session->set_flashdata('mes_mensualdata',$mes);
			}else{
				$mes = $this->session->flashdata('mes_mensualdata') == '' ? date('m') : $this->session->flashdata('mes_mensualdata');				
			}


			if($this->input->post('anno') != ''){
				$anno = $this->input->post('anno');
				$this->session->set_flashdata('anno_mensualdata',$anno);
			}else{
				$anno = $this->session->flashdata('anno_mensualdata') == '' ? date('Y') : $this->session->flashdata('anno_mensualdata');				
			}

			$this->session->keep_flashdata('tiporeporte_mensualdata');
			$this->session->keep_flashdata('mes_mensualdata');
			$this->session->keep_flashdata('anno_mensualdata');

 
			$mensual_data = array();
			$this->load->model('report');
			if($tiporeporte == 'li'){				
				$mensual_data = $this->report->get_lecturas_individuales(null,$mes,$anno);
			}else if($tiporeporte == 'ri'){
				$mensual_data = $this->report->get_intereses_mensuales($mes,$anno);
			}else if($tiporeporte == 'ra'){
				$mensual_data = $this->report->get_ajustes_mensuales($mes,$anno);				
			}else if($tiporeporte == 'rm'){
				$mensual_data = $this->report->get_multas_mensuales($mes,$anno);				
			}else if($tiporeporte == 'rce'){
				$mensual_data = $this->report->get_cuotas_especiales_mensuales($mes,$anno);				
			}else if($tiporeporte == 'ric'){
				$mensual_data = $this->report->get_ingresos_mensuales($mes,$anno);				
			}else if($tiporeporte == 'rsc'){
				$mensual_data = $this->report->get_cuentas_sin_cobro($mes,$anno);				
			}else if($tiporeporte == 'cgc'){
				$mensual_data = $this->report->get_cobro_gasto_comun($mes,$anno);				
			}else if($tiporeporte == 'rec'){
				$mensual_data = $this->report->get_cuentas_espacios_comunes($mes,$anno);				
			}

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Reportes Mensuales');

			$vars['content_menu'] = $content;				
			$vars['mensual_data'] = $mensual_data;	
			$vars['tiporeporte'] = $tiporeporte;	
			$vars['mes'] = $mes;	
			$vars['anno'] = $anno;	
			$vars['content_view'] = 'reports/mensual_data';
			$vars['formValidation'] = true;
			$vars['dataTables'] = true;

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


	public function reporte_egresos()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			
			$this->load->model('report');

			$fechadesde = $this->input->post('fechadesde') != '' ? $this->input->post('fechadesde') : date('Y-m-d', strtotime('-29 day', strtotime(date("Y-m-d"))));
			$fechahasta = $this->input->post('fechahasta') != '' ? $this->input->post('fechahasta') : date("Y-m-d");

			$movimientos = $this->report->get_egresos_by_periodo($fechadesde,$fechahasta);
			//var_dump($movimientos); exit;

			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));



			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Reportes Egresos');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'reports/reporte_egresos';
			$vars['movimientos'] = $movimientos;
			$vars['datoscomunidad'] = $datoscomunidad;

			$vars['fechadesde'] = $fechadesde;
			$vars['fechahasta'] = $fechahasta;			

			$vars['dataTables'] = true;
			$vars['daterangepicker2'] = true;		
			//$vars['moment'] = false;	
			
			$vars['inputmask'] = true;		

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



	public function export_egresos($fechadesde,$fechahasta)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


	        /*$this->load->library('PHPExcel');
	  	    $this->phpexcel->setActiveSheetIndex(0);
	        $sheet = $this->phpexcel->getActiveSheet();*/

        	$spreadsheet = new Spreadsheet();
        	$sheet = $spreadsheet->getActiveSheet();	        
	        $sheet->setTitle("egresos");


			$this->load->model('report');
			$this->load->model('admin');
			$detalle_libro = $this->report->get_egresos_by_periodo($fechadesde,$fechahasta);
			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

			/********* COMIENZA A CREAR EXCEL *******/
	        // DATOS INICIALES
			$sheet->getColumnDimension('A')->setWidth(5);


	        $sheet->mergeCells('B2:D2');
	        $sheet->setCellValue('B2', 'Reporte Egresos');
	        $sheet->getColumnDimension('B')->setWidth(20);
	        $sheet->setCellValue('B3', 'Nombre Comunidad');
	        $sheet->setCellValue('C3',html_entity_decode($this->session->userdata('comunidadnombre')));
	        $sheet->mergeCells('C3:D3');
	        $sheet->setCellValue('B4', 'Rut Comunidad');
	        $sheet->setCellValue('C4',number_format($datos_comunidad->rut,0,".",".") . '-' .$datos_comunidad->dv);	        
	        $sheet->mergeCells('C4:D4');
	        $sheet->setCellValue('B5', 'Direccion Comunidad');
	        $sheet->setCellValue('C5',$datos_comunidad->direccion.", ".$datos_comunidad->comuna);	        	        
	        $sheet->mergeCells('C5:D5');
	        $sheet->setCellValue('B6', 'Fecha emision Reporte');
	        $sheet->setCellValue('C6',date('d/m/Y') );
	        $sheet->mergeCells('C6:D6');
	        
 
			$sheet->getStyle("B2:B6")->getFont()->setBold(true);
			$sheet->getStyle("B2:D6")->getFont()->setSize(10);    	

			//D7E4BC


			/****************** TABLA INICIAL ****************/

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B2:D6")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
			$sheet->getStyle("B2:D2")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:D2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B6:D6")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("D2:D6")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		
			/**********************************************************************************************************/			        
				
			/***** COLOR TABLA ****************/
			$sheet->getStyle("B2:D2")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:D2")->getFill()->getStartColor()->setRGB('D7E4BC');

			$sheet->getStyle("B2:B6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:B6")->getFill()->getStartColor()->setRGB('D7E4BC');			


			$i = 8;



			//ENCABEZADO REPORTE

			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, '#');
			 $sheet->getColumnDimension('C')->setWidth(15);
			 $sheet->setCellValue('C'.$i, 'Fecha');
			 $sheet->getColumnDimension('D')->setWidth(35);
			 $sheet->setCellValue('D'.$i, 'Glosa');
			 $sheet->getColumnDimension('E')->setWidth(15);
			 $sheet->setCellValue('E'.$i, 'Cheque');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Nro. Transacción');
			 $sheet->getColumnDimension('G')->setWidth(15);
			 $sheet->setCellValue('G'.$i, 'Monto');

			 $columnaFinal = 6;
			 $mergeTotal = 7;
			 $columnaTotales = 6;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
			$filaInicio = $i-1; 
			
			//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
			$linea = 1;
            foreach ($detalle_libro as $libro) {
            	//$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            	$sheet->setCellValue("B".$i,$linea);
            	$sheet->setCellValue("C".$i,$libro->fechapago_format);
            	$sheet->setCellValue("D".$i,$libro->proveedor);
            	$sheet->setCellValue("E".$i,$libro->cheque);
            	$sheet->setCellValueExplicit("F".$i,trackid($libro->folio),PHPExcel_Cell_DataType::TYPE_STRING);
            	$sheet->setCellValue("G".$i,$libro->monto);
            	$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	

	 			if($i % 2 != 0){
	 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
	 			}	            	
            	$i++;
            	$linea++;
              }

             $i--;



			         	
			$sheet->getStyle("B" . $filaInicio . ":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setSize(10);

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B".$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
					for($j=1;$j<=$columnaFinal;$j++){ //borde superior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde izquierdo
						$sheet->getStyle("B".$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/**********************************************************************************************************/			        
				

			/***************************** Segundo borde superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/******************************************************************************************************/
			

		/***************************** Penultimo borde izquierdo ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle("B".$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			



		/***************************** Penultimo borde derecho ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			

			/***************************** Color fila superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->getStartColor()->setRGB('E8EDFF');
					}
			
			/******************************************************************************************************/


		/***************************** Color primera columna ********************************************************/
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


		/***************************** Color montos ********************************************************/
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


			$sheet->setSelectedCells('E1'); //celda seleccionada


			ob_end_clean();
	        header("Content-Type: application/vnd.ms-excel");
	        $nombreArchivo = 'reporte_egresos';
	        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
	        header("Cache-Control: max-age=0");
	        // Genera Excel
	        $writer = new Xlsx($spreadsheet); //objeto de PHPExcel, para escribir en el excel
	        //$writer = new PHPExcel_Writer_Excel2007($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
	        // Escribir
	        //$writer->setIncludeCharts(TRUE);			
	        $writer->save('php://output');
	        exit;			

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




public function export_cartola_propiedad($idpropiedad = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


       // $this->load->library('PHPExcel');
	  	   // $this->phpexcel->setActiveSheetIndex(0);
	       // $sheet = $this->phpexcel->getActiveSheet();

			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
	        $sheet->setTitle("cartola_propiedad");
	       	$this->load->model('report');
	       	$this->load->model('admin');
			$detalle_cartola = $this->report->get_cartola_propietario($this->session->userdata('comunidadid'),$idpropiedad);

			//var_dump($detalle_cartola);

			$detalle_cartola_inv = array_reverse($detalle_cartola, true);

			$saldo = 0;
			foreach ($detalle_cartola_inv as $key => $cartola_inv) {
				$saldo = $saldo + $cartola_inv['Cargo'];
				$saldo = $saldo - $cartola_inv['Abono'];

				$detalle_cartola[$key]['Saldo'] = $saldo;
			}


			$datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));
			$datos_propiedad = $this->admin->get_propiedad_by_id($idpropiedad);
			

			/********* COMIENZA A CREAR EXCEL *******/
	        // DATOS INICIALES
			$sheet->getColumnDimension('A')->setWidth(5);


	        $sheet->mergeCells('B2:D2');
	        $sheet->setCellValue('B2', 'Cartola Propiedad');
	        $sheet->getColumnDimension('B')->setWidth(20);
	        $sheet->setCellValue('B3', 'Nombre Comunidad');
	        $sheet->setCellValue('C3',html_entity_decode($this->session->userdata('comunidadnombre')));
	        $sheet->mergeCells('C3:D3');
	        $sheet->setCellValue('B4', 'Numero Propiedad');
	        $sheet->setCellValue('C4',$datos_propiedad->numero);	        
	        $sheet->mergeCells('C4:D4');
	        $sheet->setCellValue('B5', 'Responsable');
	        $sheet->setCellValue('C5',$datos_propiedad->responsable);	        	        
	        $sheet->mergeCells('C5:D5');
	        $sheet->setCellValue('B6', 'Fecha emision Reporte');
	        $sheet->setCellValue('C6',date('d/m/Y') );
	        $sheet->mergeCells('C6:D6');
	        
 
			$sheet->getStyle("B2:B6")->getFont()->setBold(true);
			$sheet->getStyle("B2:D6")->getFont()->setSize(10);    	

			//D7E4BC


			/****************** TABLA INICIAL ****************/

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B2:D6")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
			$sheet->getStyle("B2:D2")->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:D2")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B6:D6")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("B2:B6")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
			$sheet->getStyle("D2:D6")->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		
			/**********************************************************************************************************/			        
				
			/***** COLOR TABLA ****************/
			$sheet->getStyle("B2:D2")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:D2")->getFill()->getStartColor()->setRGB('D7E4BC');

			$sheet->getStyle("B2:B6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle("B2:B6")->getFill()->getStartColor()->setRGB('D7E4BC');			


			$i = 8;



			//ENCABEZADO REPORTE

			 $sheet->getColumnDimension('B')->setWidth(10);
			 $sheet->setCellValue('B'.$i, 'Fecha Ingreso');
			 $sheet->getColumnDimension('C')->setWidth(15);
			 $sheet->setCellValue('C'.$i, 'Gastos Comunes');
			 $sheet->getColumnDimension('D')->setWidth(15);
			 $sheet->setCellValue('D'.$i, 'Abonos');
			 $sheet->getColumnDimension('E')->setWidth(55);
			 $sheet->setCellValue('E'.$i, 'Descripción');
			 $sheet->getColumnDimension('F')->setWidth(15);
			 $sheet->setCellValue('F'.$i, 'Saldo Acumulado');


			 $columnaFinal = 5;
			 $mergeTotal = 6;
			 $columnaTotales = 5;
			 $sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setBold(true);
			 $i++;
			$filaInicio = $i-1; 
			
			//$sheet->getStyle("B7:I7")->getFont()->setSize(11);  
			$linea = 1;
            foreach ($detalle_cartola as $cartola) {

            	//var_dump($cartola); exit;
            	//$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            	$sheet->setCellValue("B".$i,$cartola['fec_ingreso']);
            	$sheet->setCellValue("C".$i,$cartola['Cargo']);
            	$sheet->getStyle('C'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("D".$i,$cartola['Abono']);
            	$sheet->getStyle('D'.$i)->getNumberFormat()->setFormatCode('#,##0');
            	$sheet->setCellValue("E".$i,$cartola['Descripcion']);
            	$sheet->setCellValue("F".$i,$cartola['Saldo']);
            	$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');

            	

	 			if($i % 2 != 0){
	 				//echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
					$sheet->getStyle("B".$i.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('F7F9FD');	  				
	 			}	            	
            	$i++;
            	$linea++;
              }

             $i--;



			         	
			$sheet->getStyle("B" . $filaInicio . ":".ordenLetrasExcel($columnaFinal).$i)->getFont()->setSize(10);

			/*************************todos los bordes internos *************************************/
			$sheet->getStyle("B".$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


			/*************************bordes cuadro principal (externo) *************************************/
					for($j=1;$j<=$columnaFinal;$j++){ //borde superior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde izquierdo
						$sheet->getStyle("B".$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/**********************************************************************************************************/			        
				

			/***************************** Segundo borde superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //borde inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
			/******************************************************************************************************/
			

		/***************************** Penultimo borde izquierdo ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle("B".$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			



		/***************************** Penultimo borde derecho ********************************************************/
			
					for($n=$filaInicio;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle(ordenLetrasExcel($columnaFinal).$n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}
			
		/******************************************************************************************************/			

			/***************************** Color fila superior********************************************************/
			
					for($j=1;$j<=$columnaFinal;$j++){ //color fondo inferior
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($j).$filaInicio)->getFill()->getStartColor()->setRGB('E8EDFF');
					}
			
			/******************************************************************************************************/


		/***************************** Color primera columna ********************************************************/
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle("B".$filaInicio.":B".$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


		/***************************** Color montos ********************************************************/
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
						$sheet->getStyle(ordenLetrasExcel($columnaTotales).$filaInicio.":".ordenLetrasExcel($columnaFinal).$i)->getFill()->getStartColor()->setRGB('E8EDFF');
			
			/******************************************************************************************************/


			$sheet->setSelectedCells('E1'); //celda seleccionada


			if (ob_get_level() > 0) {
			    ob_end_clean();
			}
	        header("Content-Type: application/vnd.ms-excel");
	        $nombreArchivo = 'Cartola_Propiedad';
	        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
	        header("Cache-Control: max-age=0");
	        // Genera Excel
	        $writer = new Xlsx($spreadsheet); //objeto de PHPExcel, para escribir en el excel
	        //$writer = new PHPExcel_Writer_Excel2007($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
	        // Escribir
	        //$writer->setIncludeCharts(TRUE);			
	        $writer->save('php://output');
	        exit;			

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
