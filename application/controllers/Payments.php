<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {

	
	function __construct(){
	  parent::__construct();
	  $this->load->library('ion_auth');
      $this->load->library('form_validation');
      $this->load->helper('format');

      if (!$this->ion_auth->logged_in()){

	     $this->session->set_userdata('uri_array',$this->uri->rsegment_array());
         redirect('auth/login/');
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

	public function abonar_ggcc($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('abonar_ggcc_result');
			if($resultid == 1){
				$vars['message'] = "Abono realizado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al realizar abono.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 4){
				$vars['message'] = "Error al realizar abono.  No indica propiedad";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('payment');

			$datosdeuda = $this->payment->get_deuda_publicada_by_comunidad($this->session->userdata('comunidadid'));
			//$datosdeuda_sin_publicar = $this->payment->get_deuda_sin_publicar_by_propiedad();


			$menu = $this->session->userdata('level') == 1 ? 'Gasto Com&uacute;n' : 'Informaci&oacute;n';
			$subtitle = $this->session->userdata('level') == 1 ? 'Abonos' : 'Saldos por Propiedad';
			$abono = $this->session->userdata('level') == 1 ? true : false;
			$content = array(
						'menu' => $menu,
						'title' => $menu,
						'subtitle' => $subtitle);


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'payment/abonar_ggcc';
			$vars['datosdeuda'] = $datosdeuda;
			$vars['abono'] = $abono;
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



	public function add_abono($propiedadid = null)
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if(is_null($propiedadid)){
				$this->session->set_flashdata('abonar_ggcc_result',4);
				redirect('payments/abonar_ggcc');	
			}

			$this->load->model('payment');
			$datosdeuda = $this->payment->get_deuda_by_propiedad($propiedadid);
			$datosperiodo = $this->payment->get_periodos_publicados_by_propiedad($propiedadid);

			$this->load->model('admin');
			$formas_pago = $this->admin->get_forma_pago('abono');
			$bancos = $this->admin->get_banco();

			$vars['content_view'] = 'payment/add_abono';
			$vars['formValidation'] = true;
			$vars['datetimepicker'] = true;
			$vars['icheck'] = true;
			$vars['jqueryRut'] = true;
			$vars['mask'] = true;
			//$vars['moment'] = true;

			$vars['datosdeuda'] = $datosdeuda;
			$vars['datosperiodo'] = $datosperiodo;
			$vars['formas_pago'] = $formas_pago;
			$vars['bancos'] = $bancos;



			$content = array(
						'menu' => 'Gasto Com&uacute;n',
						'title' => 'Gasto Com&uacute;n',
						'subtitle' => 'Abonar');


			$vars['content_menu'] = $content;	


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



	public function submit_abono()
	{


		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$array_post = $this->input->post(NULL,true);
			if(!$array_post){
				$this->session->set_flashdata('abonar_ggcc_result',3);
				redirect('payments/abonar_ggcc');	
			}
	        $config['upload_path'] = "./uploads/abonos/".$this->input->post("idpropiedad")."/"	;

			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'],0777,true);
			}

	        $config['file_name'] = date("Ymd")."_".date("His")."_".randomstring(5)."_".$this->input->post("numero");
	        $config['allowed_types'] = "*";
	        $config['max_size'] = "10240";

	        $this->load->library('upload', $config);
	        $this->upload->do_upload("userfile");
       		$dataupload = $this->upload->data();

       		$ruttitular = str_replace(".","",$this->input->post("ruttitular"));
			$arrayRut = explode("-",$ruttitular);
				


       		$parametros = array(
       						'pagototal' => $this->input->post('pagototal'),
       						'idpropiedad' => $this->input->post("idpropiedad"),
       						'idperiodo' => $this->input->post("periodo") == null ? null : $this->input->post("periodo"),
       						'monto' => $this->input->post('pagototal') == 'on' ? $this->input->post('deudatotal') : str_replace(".","",$this->input->post("monto")),
       						'fechapago' => $this->input->post("fechapago"),
       						'idformapago' => $this->input->post("formas_pago"),
       						'idbanco' => $this->input->post("banco"),
       						'cheque' => $this->input->post("cheque"),
       						'ruttitular' => isset($arrayRut[0]) ? $arrayRut[0] : null,
       						'dvtitular' => isset($arrayRut[1]) ? $arrayRut[1] : null,
       						'fechadeposito' => $this->input->post("fechadeposito"),
       						'nombrearchivo' => $dataupload['orig_name'],
       						'nombrerealarchivo' => $dataupload['client_name']
			       			);


       		$this->load->model('payment');
			$this->payment->add_abono($parametros);




			$this->session->set_flashdata('abonar_ggcc_result',1);
			redirect('payments/abonar_ggcc');				

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



	public function ver_ggcc()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$resultid = $this->session->flashdata('ver_ggcc_result');
			if($resultid == 1 || $resultid == 2){
				$vars['message'] = "Error al realizar abono.  Favor Intente Nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';			
			}else if($resultid == 3){
				$vars['message'] = "Error al realizar abono.  Monto no puede ser mayor a deuda total";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';	

			}
		
			$this->load->model('admin');
			$datospropiedad = $this->admin->get_propiedad_by_id($this->session->userdata('propiedadid'));

			//echo "<pre>";
			//print_r($datospropiedad); exit;

			$periodo_inicial = $this->admin->get_periodo_inicial();

			$this->load->model('payment');
			$datos_inicial = $this->payment->get_propiedad_by_periodo($periodo_inicial->id,$this->session->userdata('propiedadid'));
			$datosggcc = $this->payment->get_ggcc_by_propiedad($this->session->userdata('propiedadid'));

			$content = array(
						'menu' => 'Gasto Com&uacute;n',
						'title' => 'Gasto Com&uacute;n',
						'subtitle' => 'Ver GGCC');
			
			$vars['classinfo'] = $datospropiedad->saldo_publicado > 0 ? 'bg-red' : 'bg-green';
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'payment/ver_ggcc';
			$vars['datospropiedad'] = $datospropiedad;
			$vars['datosggcc'] = $datosggcc;
			$vars['periodo_inicial'] = $periodo_inicial;
			$vars['datos_inicial'] = $datos_inicial;
			$vars['dataTables'] = true;
			$vars['volver'] = false;
			$vars['origen'] = 'prop';
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


	public function download_ingreso($idpropiedad,$idingreso)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$abono_data = $this->account->get_listado_abono_by_id($idingreso);

			if(!is_null($abono_data)){
				$this->load->model('payment');
				$datosdetalle = $this->payment->generar_ingreso($idpropiedad,$idingreso);						
			}else{

				redirect('main/dashboard');
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


	public function ver_cartola($propiedadid,$periodoid)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('payment');
			$datoscartola = $this->payment->get_cartola_by_propiedad($propiedadid,$periodoid);

			$this->load->model('admin');
			$datospropiedad = $this->admin->get_propiedad_by_id($propiedadid);

			if($periodoid != 0){
				$datosperiodo = $this->admin->get_periodo_by_id($periodoid);
			}

			$content = array(
						'menu' => 'Gasto Com&uacute;n',
						'title' => 'Gasto Com&uacute;n',
						'subtitle' => 'Ver Cartola');

			$vars['content_menu'] = $content;		
			$vars['content_view'] = 'payment/ver_cartola';
			$vars['datoscartola'] = $datoscartola;
			$vars['mail'] = $datospropiedad->mail;
			$vars['texto_propiedad'] = "Propiedad ".$datospropiedad->numero.".";
			$vars['texto_periodo'] = $periodoid == 0 ? "" : date2string($datosperiodo->mes,$datosperiodo->anno);
			$vars['periodoid'] = $periodoid;
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




	public function generar_mail_aviso_vencimiento()
	{

		//if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('payment');
			$comunidades_impagas = $this->payment->buscar_comunidades_sin_pago();

			foreach ($comunidades_impagas as $comunidad) {
					$this->payment->generar_mail_aviso_vencimiento($comunidad->id);
			}

		/*}else{
			$content = array(
						'menu' => 'Error 403',
						'title' => 'Error 403',
						'subtitle' => '403 error');


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'forbidden';
			$this->load->view('template',$vars);

		}*/

	}

	public function reenviar_comprobante($propiedadid,$idlistado)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('payment');
			$this->payment->generar_mail_abono($this->session->userdata('comunidadid'),$propiedadid,$idlistado);
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


	public function comprobantes($idperiodo = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			set_time_limit(0);

			$this->load->model('admin');
			$periodo = $this->admin->get_periodo_by_id($idperiodo);

			
			if(is_null($periodo->genera)){
				redirect('main/dashboard/');
			}else{

				$this->load->model('payment');
				$propiedades = $this->payment->get_propiedad_by_periodo($idperiodo);
				if(is_null($propiedades)){ // SI NO ENCUENTRO NINGUNA PROPIEDAD (CORRESPONDE A OTRA COMUNIDAD POR EJEMPLO)
					redirect('main/dashboard/');
				}else{
					$datosdetalle = $this->payment->comprobantes($propiedades,$idperiodo);
				}
			}


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



	public function comprobante_detalle_ggcc($idperiodo = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			set_time_limit(0);

			$this->load->model('admin');
			$periodo = $this->admin->get_periodo_by_id($idperiodo);

			
			if(is_null($periodo->genera)){
				redirect('main/dashboard/');
			}else{

				$this->load->model('payment');
				$detalle = $this->payment->comprobante_detalle_ggcc($idperiodo);
			}


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



	public function download_ggcc($idpropiedad,$idperiodo)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('admin');
			$datos_periodo = $this->admin->get_periodo_by_id($idperiodo);

			if(is_null($datos_periodo->genera)){
				redirect('main/dashboard/');				
			}

			if($this->session->userdata('level') == 3){ // PROPIETARIO
					if($this->session->userdata('propiedadid') != $idpropiedad){ // SI QUIERO VER UNA PROPIEDAD DISTINTA, ME REDIRECCIONA
						redirect('payments/download_ggcc/'.$this->session->userdata('propiedadid').'/'.$idperiodo);
					}else{
						$this->load->model('payment');
						$datosdetalle = $this->payment->generar_comprobante($this->session->userdata('comunidadid'),$idperiodo,$idpropiedad);						
					}

			}else{
				$this->load->model('admin');
				$datospropiedad = $this->admin->get_propiedad_by_id($idpropiedad);
				if($this->session->userdata('comunidadid') != $datospropiedad->idcomunidad){ // SI QUIERO VER UNA PROPIEDAD QUE NO CORRESPONDE A LA COMUNIDAD, ME REDIRECCIONA
					redirect('main/dashboard/');
				}else{
					$this->load->model('payment');
					$datosdetalle = $this->payment->generar_comprobante($this->session->userdata('comunidadid'),$idperiodo,$idpropiedad);
				}

			}
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



	public function ver_detalle($ggccid,$muestra_saldo = 0)
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('payment');
			$datosdetalle = $this->payment->get_detalle_by_ggcc_no_individual($ggccid);

			$datosindividual = $this->payment->get_detalle_by_ggcc_individual($ggccid);

			$saldoanterior = $muestra_saldo == 1 ? $this->payment->get_saldo_anterior_by_ggcc($ggccid) : 0;

			$totalggcc = $this->payment->get_ggcc_by_ggcc($ggccid);


			$content = array(
						'menu' => 'Gasto Com&uacute;n',
						'title' => 'Gasto Com&uacute;n',
						'subtitle' => 'Ver Detalle Gasto Com&uacute;n');

			$vars['content_menu'] = $content;		
			$vars['content_view'] = 'payment/ver_detalle';
			$vars['datosdetalle'] = $datosdetalle;
			$vars['datosindividual'] = $datosindividual;
			$vars['saldoanterior'] = $saldoanterior;
			$vars['totalggcc'] = $totalggcc;
			$vars['muestra_saldo'] = $muestra_saldo;
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



	public function ver_ggcc_adm($propiedadid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if($propiedadid == ''){
				redirect('main/dashboard');
			}
		
			$this->load->model('admin');
			$datospropiedad = $this->admin->get_propiedad_by_id($propiedadid);
			$periodo_inicial = $this->admin->get_periodo_inicial();

			$this->load->model('payment');
			$datos_inicial = $this->payment->get_propiedad_by_periodo($periodo_inicial->id,$propiedadid);			

			//SI ES ADMINISTRADOR, Y PROPIEDAD NO ES DE LA COMUNIDAD, NO TIENE PERMISO
			if($datospropiedad->idcomunidad != $this->session->userdata('comunidadid')){
				$content = array(
							'menu' => 'Error 403',
							'title' => 'Error 403',
							'subtitle' => '403 error');


				$vars['content_menu'] = $content;								
				$vars['content_view'] = 'forbidden';
				$this->load->view('template',$vars);	
			}else{

				$datosggcc = $this->payment->get_ggcc_by_propiedad($propiedadid);


				$vars['is_admin'] = true;
				/***** GRAFICO PROPIEDAD *******/
				$vars['highchartsGraph'] = true;
				$this->load->model('report');
				$datos_gc_propiedad = $this->report->gc_mensual_prop($this->session->userdata('comunidadid'),$propiedadid); 	
				$array_meses = array();
				$array_deuda_prop = array();
				$array_abonos = array();
				foreach ($datos_gc_propiedad as $dato_mensual) {
					array_push($array_meses,date2string($dato_mensual['mes'],$dato_mensual['anno']));
					array_push($array_deuda_prop,(int)$dato_mensual['monto']);
					array_push($array_abonos,(int)$dato_mensual['abonado']);
				}

				$listado_meses = implode("','",$array_meses);
				$listado_deuda_prop = implode(",",$array_deuda_prop);
				$listado_abonos = implode(",",$array_abonos);
				$vars['listado_meses'] = $listado_meses;
				$vars['listado_deuda_prop'] = $listado_deuda_prop;
				$vars['listado_abonos'] = $listado_abonos;



				/***********************************/



				$content = array(
							'menu' => 'Gasto Com&uacute;n',
							'title' => 'Gasto Com&uacute;n',
							'subtitle' => 'Ver GGCC');

				$vars['classinfo'] = $datospropiedad->saldo_publicado > 0 ? 'bg-red' : 'bg-green';
				$vars['content_menu'] = $content;				
				$vars['content_view'] = 'payment/ver_ggcc';
				$vars['datospropiedad'] = $datospropiedad;
				$vars['datosggcc'] = $datosggcc;
				$vars['periodo_inicial'] = $periodo_inicial;
				$vars['datos_inicial'] = $datos_inicial;
				$vars['volver'] = true;
				$vars['origen'] = 'adm';
				$vars['dataTables'] = true;
				$template = "template";
				

				$this->load->view($template,$vars);	

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



	public function conciliacion()
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
			}elseif($resultid == 5){
				$vars['message'] = "Error al eliminar movimiento.  Movimiento ya conciliado";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}			

			$this->load->model('account');

			//$consulta_conciliacion = $this->input->post('tipoconciliacion') == '' ? 'noconcilia' : $this->input->post('tipoconciliacion');

			//$this->session->set_flashdata('desautorizacion_result', 3);
			//echo $this->input->post('tipoconciliacion')."--<br>";
			if($this->input->post('tipoconciliacion') != ''){
				$consulta_conciliacion = $this->input->post('tipoconciliacion') == 'sc' ? 'noconcilia' : $this->input->post('tipoconciliacion');
				$this->session->set_flashdata('tipoconciliacion_conc',$consulta_conciliacion);
			}else{
				$consulta_conciliacion = $this->session->flashdata('tipoconciliacion_conc') == '' ? 'noconcilia' : $this->session->flashdata('tipoconciliacion_conc');				
			}



			if($this->input->post('fechadesde') != ''){
				$fechadesde = $this->input->post('fechadesde');
				$this->session->set_flashdata('fechadesde_conc',$fechadesde);
			}else{
				$fechadesde = $this->session->flashdata('fechadesde_conc') == '' ? date("Y")."-01-01" : $this->session->flashdata('fechadesde_conc');				
			}


			if($this->input->post('fechahasta') != ''){
				$fechahasta = $this->input->post('fechahasta');
				$this->session->set_flashdata('fechahasta_conc',$fechahasta);
			}else{
				$fechahasta = $this->session->flashdata('fechahasta_conc') == '' ? date("Y")."-12-31" : $this->session->flashdata('fechahasta_conc');			
			}	

			$this->session->keep_flashdata('tipoconciliacion_conc');
			$this->session->keep_flashdata('fechadesde_conc');
			$this->session->keep_flashdata('fechahasta_conc');

								
			//echo $consulta_conciliacion."--<br>";
			//exit;
			//$fechahasta = $this->input->post('fechahasta') == '' ? date("Y")."-12-31" : $this->input->post('fechahasta');			
			//$consulta_conciliacion = is_null($tipo_concilia) ? 'noconcilia' : $tipo_concilia;
			//$movimientos = $this->account->get_movimientos(null,$consulta_conciliacion);
			$movimientos = $this->account->get_movimientos(null,$consulta_conciliacion,$fechadesde,$fechahasta);

			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));

			//$this->load->model('payment');
			//$saldo_disponible = $this->payment->get_saldo_disponible_by_comunidad($this->session->userdata('comunidadid'));


			$content = array(
						'menu' => 'Banco',
						'title' => 'Banco',
						'subtitle' => 'Conciliaci&oacute;n Bancaria');

			/*if($tipo_concilia == 'todos'){
				$title_button = 'Mostrar Todos';
			}else if($tipo_concilia == 'conciliado'){
				$title_button = 'Mostrar Conciliados';
			}else{
				$title_button = 'Mostrar Sin Conciliaci&oacute;n';
			}*/


			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'payment/conciliacion';
			$vars['movimientos'] = $movimientos;
			$vars['datoscomunidad'] = $datoscomunidad;

			$vars['tipoconciliacion'] = $consulta_conciliacion;
			$vars['fechadesde'] = $fechadesde;
			$vars['fechahasta'] = $fechahasta;			
			//$vars['title_button'] = $title_button;
			//$vars['tipo_concilia'] = is_null($tipo_concilia) ? '' : $tipo_concilia;			
			//$vars['saldo_disponible'] = $saldo_disponible;
			//$vars['classinfo_disponible'] = $saldo_disponible > 0 ? 'bg-green' : 'bg-red';
			//$vars['classinfo_caja'] = $datoscomunidad->caja > 0 ? 'bg-green' : 'bg-red';			

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



	public function ver_conciliacion_movimiento($tipo_movimiento,$idmovimiento = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->session->keep_flashdata('tipoconciliacion_conc');
			$this->session->keep_flashdata('fechadesde_conc');
			$this->session->keep_flashdata('fechahasta_conc');

			$this->load->model('account');
			$movimientos = $this->account->get_cartola_by_idmovimiento($tipo_movimiento,$idmovimiento);

			if(count($movimientos) <= 1){
				if(isset($movimientos[0])){
					$movimiento = $movimientos[0];
					redirect('reports/ver_movimiento/'.$movimiento->id);
				}else{
					redirect('reports/ver_movimiento/');					
				}
			}
			
			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Detalle Movimiento');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = "payment/ver_conciliacion_movimiento";
			$vars['movimientos'] = $movimientos;
			$vars['datoscomunidad'] = $datoscomunidad;

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


	public function pagoonline()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$etapaproceso = $this->input->post('etapaproceso');
			$this->load->model('admin');

			if($etapaproceso == 1){

				$fecpago = $this->input->post('fecpago');
				$montocuota = $this->input->post('montocuota');
				$numpagos = $this->input->post('numpagos');
				$token = $this->input->post('token');
				$tokentgc = $this->input->post('tokentgc');
				

				$array_datos = array(
									'fecpago' => $fecpago,
									'numpagos' => $numpagos,
									'idcomunidad' => $this->session->userdata('comunidadid'),
									'montopago' => $montocuota*$numpagos,
									'tokentranskbank' => $token,
									'tokentgc' => $tokentgc
									);

				
				$result = $this->admin->add_pay($array_datos);	
				echo json_encode($result);


			}else if($etapaproceso == 2){

				$token = $this->input->post('token');
				$payment = $this->input->post('payment');
				$result = $this->admin->add_pay_info($token,$payment);
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



public function pagoonlineprop()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$etapaproceso = $this->input->post('etapaproceso');
			$this->load->model('admin');

			if($etapaproceso == 1){
				//var_dump($_POST); exit;
				//$fecpago = $this->input->post('fecpago');
				$monto = $this->input->post('monto');
				$comision = $this->input->post('comision');
				$total = $this->input->post('total');
				$token = $this->input->post('token');
				$tokentgc = $this->input->post('tokentgc');
				$deudatotal = $this->input->post('deudatotal');
				$idperiodo = $this->input->post('idperiodo');
				$pagototal = $this->input->post('pagototal');

				$array_datos = array(
									'fecpago' => date('Y-m-d'),
									'idcomunidad' => $this->session->userdata('comunidadid'),
									'idpropiedad' => $this->session->userdata('propiedadid'),
									'montopago' => $monto,
									'comision' => $comision,
									'total' => $total,
									'tokentranskbank' => $token,
									'tokentgc' => $tokentgc,
									'deudatotal' => $deudatotal,
									'idperiodo' => $idperiodo,
									'pagototal' => $pagototal,
									);

				
				$result = $this->admin->add_payprop($array_datos);	
				echo json_encode($result);


			}else if($etapaproceso == 2){

				$token = $this->input->post('token');
				$payment = $this->input->post('payment');
				$result = $this->admin->add_payprop_info($token,$payment);
				$this->admin->accept_payprop($token);
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



public function pagoreturn($tokentgc = null)
	{


		// no, no esta bien, porque si no hago nada, igual retorna para aca.  Tengo que ver si envia algo a pagonotify

		$this->load->model('admin');

		$tokentgc = $this->input->get('orderClient');
		//var_dump($tokentgc);
		$datatoken = $this->admin->get_pagos_webpay_by_tokentgc($tokentgc);
		//var_dump($datatoken); 
		if(isset($datatoken->tokentranskbank)){
			$token = $datatoken->tokentranskbank;

		}else{

			$token = '';
		}

		//var_dump($token); exit;
		/* validar si esta ok con conexion a api*/

		$datos_comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

		//$vars['classmessage'] = 'success';
		//$vars['icon'] = 'fa-check';


 		//$message = "Pago ACEPTADO. Para continuar servicio de comunidad: " . $datos_comunidad->nombre. ", hasta el d&iacute;a " . $datos_comunidad->fecvencimiento;


			$content = array(
						'menu' => 'Pago Online',
						'title' => 'Pago Online',
						'subtitle' => 'Resultado Pago');



		$vars['content_menu'] = $content;				
		$vars['content_view'] = "payment/pagoreturn";
		//$vars['message'] = $message;
		$vars['datoscomunidad'] = $datos_comunidad;
		$vars['token'] = $token;

		$template = "template";
		

		$this->load->view($template,$vars);	

	}



	public function pagoreturnprop($tokentgc = null)
	{


		// no, no esta bien, porque si no hago nada, igual retorna para aca.  Tengo que ver si envia algo a pagonotify

		$this->load->model('admin');



		$tokentgc = $this->input->get('orderClient');
		//var_dump($tokentgc);
		$datatoken = $this->admin->get_pagos_webpayprop_by_tokentgc($tokentgc);
		//var_dump($datatoken); 
		if(isset($datatoken->tokentranskbank)){
			$token = $datatoken->tokentranskbank;

		}else{

			$token = '';
		}

		//var_dump($token); exit;
		/* validar si esta ok con conexion a api*/

		$datos_comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

		//$vars['classmessage'] = 'success';
		//$vars['icon'] = 'fa-check';


 		//$message = "Pago ACEPTADO. Para continuar servicio de comunidad: " . $datos_comunidad->nombre. ", hasta el d&iacute;a " . $datos_comunidad->fecvencimiento;


			$content = array(
						'menu' => 'Pago Online',
						'title' => 'Pago Online',
						'subtitle' => 'Resultado Pago');



		$vars['content_menu'] = $content;				
		$vars['content_view'] = "payment/pagoreturnprop";
		//$vars['message'] = $message;
		$vars['datoscomunidad'] = $datos_comunidad;
		$vars['token'] = $token;

		$template = "template";
		

		$this->load->view($template,$vars);	

	}




	public function webpay()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			$path = $base_path . "/../libraries/webpay/sample/certificates/cert-normal.php";	
			$pathwp = $base_path . "/../libraries/webpay.php";	
			//print_r($_SERVER); EXIT;


			include_once $path;		
			include_once $pathwp;	


			//$sample_baseurl = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			//$sample_baseurl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			$sample_baseurl = RUTA_VUELTA_WEBPAY;
			$configuration = new Configuration();	
			
			$configuration->setEnvironment($certificate['environment']);
			$configuration->setCommerceCode($certificate['commerce_code']);
			$configuration->setPrivateKey($certificate['private_key']);
			$configuration->setPublicCert($certificate['public_cert']);
			$configuration->setWebpayCert($certificate['webpay_cert']);		
			

			$webpay = new Webpay($configuration);

			$action = isset($_GET["action"]) ? $_GET["action"] : 'Init';

			$post_array = false;						

			switch ($action) {

			    default:

			        $tx_step = "Init";


					$this->load->model('payment');
					$monto_cuota = $this->payment->monto_cuota();
					$num_pagos = $this->input->post('numpagos');

					if($num_pagos == ''){
						redirect('main/dashboard');		
					}
					


			        /** Monto de la transacción */
			        $amount = $monto_cuota*$num_pagos;

			        /** Orden de compra de la tienda */
			        $buyOrder = rand();

			        /** Código comercio de la tienda entregado por Transbank */
			        $sessionId = uniqid();
			        
			        /** URL de retorno */
			        $urlReturn = $sample_baseurl."?action=getResult";
			        
			        /** URL Final */
					$urlFinal  = $sample_baseurl."?action=end";

			        $request = array(
			            "amount"    => $amount,
			            "buyOrder"  => $buyOrder,
			            "sessionId" => $sessionId,
			            "urlReturn" => $urlReturn,
			            "urlFinal"  => $urlFinal,
			        );

			        /** Iniciamos Transaccion */
			        $result = $webpay->getNormalTransaction()->initTransaction($amount, $buyOrder, $sessionId, $urlReturn, $urlFinal);
			        
			        /** Verificamos respuesta de inicio en webpay */
			        if (!empty($result->token) && isset($result->token)) {
			            $message = "Sesion iniciada con exito en Webpay";
						$vars['classmessage'] = 'success';
						$vars['icon'] = 'fa-check';				            
			            $token = $result->token;
			            $next_page = $result->url;


						$fecpago = $this->input->post('fecpago');
						$idcomunidad = $this->input->post('idcomunidad');

						$array_datos = array(
											'fecpago' => $fecpago,
											'numpagos' => $num_pagos,
											'idcomunidad' => $this->session->userdata('comunidadid'),
											'montopago' => $monto_cuota*$num_pagos,
											'tokentranskbank' => $token
											);

						$this->load->model('admin');
						$result = $this->admin->add_pay($array_datos);			            



			        } else {
			            $message = "webpay no disponible";
			        }

			        $button_name = "Continuar &raquo;";
			        $vars['button_name'] = $button_name;
			        $vars['token'] = $token;
			        //$token
			        
			        break;

			    case "getResult":
			        
			        $tx_step = "Get Result";

			        if (!isset($_POST["token_ws"]))
			            break;

			        /** Token de la transacción */
			        $token = filter_input(INPUT_POST, 'token_ws');
			        
			        $request = array(
			            "token" => filter_input(INPUT_POST, 'token_ws')
			        );

			        /** Rescatamos resultado y datos de la transaccion */
			        $result = $webpay->getNormalTransaction()->getTransactionResult($token);
			        
			        /** Verificamos resultado  de transacción */
			        if ($result->detailOutput->responseCode === 0) {

			            /** propiedad de HTML5 (web storage), que permite almacenar datos en nuestro navegador web */
			            echo '<script>window.localStorage.clear();</script>';
			            echo '<script>localStorage.setItem("authorizationCode", '.$result->detailOutput->authorizationCode.')</script>';
			            echo '<script>localStorage.setItem("amount", '.$result->detailOutput->amount.')</script>';
			            echo '<script>localStorage.setItem("buyOrder", '.$result->buyOrder.')</script>';

			            $this->load->model('admin');
						$datos_result = $this->admin->accept_pay($token);		

						$datos_comunidad = $this->admin->get_comunidades($datos_result['idcomunidad']);

			            $message = "Pago ACEPTADO por webpay para continuar servicio de comunidad: " . $datos_comunidad->nombre. ", hasta el d&iacute;a " . $datos_comunidad->fecvencimiento. ". (Continuar para mostrar voucher)";
			            $next_page = $result->urlRedirection;


						

			            $vars['message'] = $message;
						$vars['classmessage'] = 'success';
						$vars['icon'] = 'fa-check';	
						
			            
			        } else {
			            $message = "Pago RECHAZADO por webpay - " . utf8_decode($result->detailOutput->responseDescription);
			            $next_page = '';
			            $vars['message'] = $message;
						$vars['classmessage'] = 'danger';
						$vars['icon'] = 'fa-ban';
			        }

			        $button_name = "Continuar &raquo;";
			        $vars['button_name'] = $button_name;
			        $vars['token'] = $token;

			        break;
			    
			    case "end":
			        
			        $post_array = true;
			        
			        $tx_step = "End";
			        $request = "";
			        $result = $_POST;
			        
			        $message = "Transacion Finalizada.  Ingresar nuevamente al sistema para aplicar cambios.";
			        $next_page = $sample_baseurl."?action=nullify";
			        $button_name = "Anular Transacci&oacute;n &raquo;";
			        $vars['message'] = $message;
					$vars['classmessage'] = 'success';
					$vars['icon'] = 'fa-check';	


			        $vars['button_name'] = $button_name;

			        break;   

			    
			    case "nullify":

			        $tx_step = "nullify";
			        
			        $request = $_POST;
			        
			        /** Codigo de Comercio */
			        $commercecode = null;

			        /** Código de autorización de la transacción que se requiere anular */
			        $authorizationCode = filter_input(INPUT_POST, 'authorizationCode');

			        /** Monto autorizado de la transacción que se requiere anular */
			        $amount =  filter_input(INPUT_POST, 'amount');

			        /** Orden de compra de la transacción que se requiere anular */
			        $buyOrder =  filter_input(INPUT_POST, 'buyOrder');
			        
			        /** Monto que se desea anular de la transacción */
			        $nullifyAmount = 200;

			        $request = array(
			            "authorizationCode" => $authorizationCode, // Código de autorización
			            "authorizedAmount" => $amount, // Monto autorizado
			            "buyOrder" => $buyOrder, // Orden de compra
			            "nullifyAmount" => $nullifyAmount, // idsession local
			            "commercecode" => $configuration->getCommerceCode(), // idsession local
			        );
			        
			        $result = $webpay->getNullifyTransaction()->nullify($authorizationCode, $amount, $buyOrder, $nullifyAmount, $commercecode);
			        
			        /** Verificamos resultado  de transacción */
			        if (!isset($result->authorizationCode)) {
			            $message = "webpay no disponible";
			        } else {
			            $message = "Transaci&oacute;n Finalizada";
			        }

			        $next_page = '';
			        
			        break;
			}

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Detalle Movimiento');

			$vars['content_menu'] = $content;				
			$vars['content_view'] = "payment/webpay";
			$vars['tx_step'] = $tx_step;
			$vars['request'] = $request;
			$vars['result'] = $result;
			$vars['message'] = $message;
			$vars['next_page'] = $next_page;
			$vars['post_array'] = $post_array;


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

	public function pay_webpay()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$content = array(
						'menu' => 'Administraci&oacute;n',
						'title' => 'Administraci&oacute;n',
						'subtitle' => 'Pago de Cuenta');

			$this->load->model('payment');
			$monto_cuota = $this->payment->monto_cuota();

			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'payment/pay_webpay';
			$vars['idcomunidad'] = $this->session->userdata('comunidadid');				
			$vars['monto_cuota'] = $monto_cuota;
			$vars['formValidation'] = true;
			//$vars['datetimepicker'] = true;
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
		

	public function add_abono_webpay($propiedadid = null)
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$propiedadid = $this->session->userdata('propiedadid');
			//echo "<pre>";
			//var_dump($propiedadid); exit;
			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));


			if(is_null($propiedadid)){
				$this->session->set_flashdata('abonar_ggcc_result',4);
				redirect('payments/ver_ggcc');	
			}

			$this->load->model('payment');
			$datosdeuda = $this->payment->get_deuda_by_propiedad($propiedadid);
			$datosperiodo = $this->payment->get_periodos_publicados_by_propiedad($propiedadid);


			$lista_email = $this->admin->get_propiedad_email_by_id($propiedadid);
			$array_email = array();
			foreach ($lista_email as $lista) {
				array_push($array_email,$lista->email);
			}


			$this->load->model('admin');
			$formas_pago = $this->admin->get_forma_pago('abono');
			$bancos = $this->admin->get_banco();


			$token_tgc = str_pad($propiedadid,5,"0",STR_PAD_LEFT).randomstring_mm(12).str_pad($this->session->userdata('comunidadid'),3,"0",STR_PAD_LEFT);



			$vars['content_view'] = 'payment/add_abono_webpay';
			$vars['formValidation'] = true;
			$vars['datetimepicker'] = true;
			$vars['icheck'] = true;
			$vars['jqueryRut'] = true;
			$vars['mask'] = true;
			$vars['maleta'] = true;
			//$vars['moment'] = true;
			$vars['token_pagoonline'] = $datoscomunidad->token_pagoonline;
			$vars['datosdeuda'] = $datosdeuda;
			$vars['datosperiodo'] = $datosperiodo;
			$vars['formas_pago'] = $formas_pago;
			$vars['bancos'] = $bancos;
			$vars['token_tgc'] = $token_tgc;



			$content = array(
						'menu' => 'Gasto Com&uacute;n',
						'title' => 'Gasto Com&uacute;n',
						'subtitle' => 'Abonar');


			$vars['content_menu'] = $content;	


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



	public function webpay_prop()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			//print_r($this->input->post(null,true)); exit;


			$propiedadid = $this->session->userdata('propiedadid');
			$idpropiedad = $this->input->post('idpropiedad');

			
			$this->load->model('admin');
			$datospropiedad = $this->admin->get_propiedad_by_id($this->session->userdata('propiedadid'));

			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			//$path = $base_path . "/../libraries/webpay/sample/certificates/cert-normal.php";	
			$pathwp = $base_path . "/../libraries/webpay.php";	
			//print_r($_SERVER); EXIT;

			//print_r($datospropiedad); exit;
			//include_once $path;		
			include_once $pathwp;	


			//$sample_baseurl = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			//$sample_baseurl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
			$sample_baseurl = RUTA_VUELTA_WEBPAY_PROP;
			$configuration = new Configuration();	
			//OBTENER DATOS DESDE LA TABLA
			$configuration->setEnvironment($datospropiedad->enviroment);
			$configuration->setCommerceCode($datospropiedad->codigo_comercio);
			$configuration->setPrivateKey($datospropiedad->private_key);
			$configuration->setPublicCert($datospropiedad->public_cert);
			$configuration->setWebpayCert($datospropiedad->webpay_cert);		
			

			$webpay = new Webpay($configuration);

			$action = isset($_GET["action"]) ? $_GET["action"] : 'Init';
			//var_dump($action); exit;
			$post_array = false;						

			switch ($action) {

			    default:

			        $tx_step = "Init";


					$this->load->model('payment');
					$monto = $this->input->post('pagototal') == 'on' ? $this->input->post('deudatotal') : str_replace(".","",$this->input->post("monto"));

					//$num_pagos = $this->input->post('numpagos');
					if($propiedadid != $idpropiedad){
						$this->session->set_flashdata('ver_ggcc_result',1);
						redirect('payments/ver_ggcc');					
					}

					if($monto == '' || $monto == 0){
						$this->session->set_flashdata('ver_ggcc_result',2);
						redirect('payments/ver_ggcc');		
					}else if($monto > $this->input->post('deudatotal')){
						$this->session->set_flashdata('ver_ggcc_result',3);
						redirect('payments/ver_ggcc');	

					}
					


			        /** Monto de la transacción */
			        //$amount = $monto_cuota*$num_pagos;
			        $amount = $monto;

			        /** Orden de compra de la tienda */
			        $buyOrder = rand();

			        /** Código comercio de la tienda entregado por Transbank */
			        $sessionId = uniqid();
			        
			        /** URL de retorno */
			        $urlReturn = $sample_baseurl."?action=getResult";
			        
			        /** URL Final */
					$urlFinal  = $sample_baseurl."?action=end";

			        $request = array(
			            "amount"    => $amount,
			            "buyOrder"  => $buyOrder,
			            "sessionId" => $sessionId,
			            "urlReturn" => $urlReturn,
			            "urlFinal"  => $urlFinal,
			        );

			        /** Iniciamos Transaccion */
			        $result = $webpay->getNormalTransaction()->initTransaction($amount, $buyOrder, $sessionId, $urlReturn, $urlFinal);
			        
			        /** Verificamos respuesta de inicio en webpay */
			        if (!empty($result->token) && isset($result->token)) {
			            $message = "Sesion iniciada con exito en Webpay";
						$vars['classmessage'] = 'success';
						$vars['icon'] = 'fa-check';				            
			            $token = $result->token;
			            $next_page = $result->url;


						$fecpago = $this->input->post('fecpago');
						$idcomunidad = $this->input->post('idcomunidad');

						$array_datos = array(
											'idpropiedad' => $propiedadid,
											'montopago' => $amount,
											'tokentranskbank' => $token,
											'periodo' => $this->input->post('periodo'),
											'pagototal' => $this->input->post('pagototal'),
											);

						$this->load->model('admin');
						$result = $this->admin->add_trans_abono($array_datos);			            
						


			        } else {
			            $message = "webpay no disponible";
			        }

			        $button_name = "Continuar &raquo;";
			        $vars['button_name'] = $button_name;
			        $vars['token'] = $token;
			        //$token
			        
			        break;

			    case "getResult":
			        
			        $tx_step = "Get Result";

			        if (!isset($_POST["token_ws"]))
			            break;

			        /** Token de la transacción */
			        $token = filter_input(INPUT_POST, 'token_ws');
			        
			        $request = array(
			            "token" => filter_input(INPUT_POST, 'token_ws')
			        );

			        /** Rescatamos resultado y datos de la transaccion */
			        $result = $webpay->getNormalTransaction()->getTransactionResult($token);
			        //var_dump($result); exit;
			        /** Verificamos resultado  de transacción */
			        if ($result->detailOutput->responseCode === 0) {

			            /** propiedad de HTML5 (web storage), que permite almacenar datos en nuestro navegador web */
			            echo '<script>window.localStorage.clear();</script>';
			            echo '<script>localStorage.setItem("authorizationCode", '.$result->detailOutput->authorizationCode.')</script>';
			            echo '<script>localStorage.setItem("amount", '.$result->detailOutput->amount.')</script>';
			            echo '<script>localStorage.setItem("buyOrder", '.$result->buyOrder.')</script>';


			            $message = "Pago ACEPTADO por webpay (se deben guardatos para mostrar voucher)";
			            $next_page = $result->urlRedirection;


						$this->load->model('admin');
						
						$result = $this->admin->accept_trans_abono($token);		


			            $vars['message'] = $message;
						$vars['classmessage'] = 'success';
						$vars['icon'] = 'fa-check';	
						
			            
			        } else {
			            $message = "Pago RECHAZADO por webpay - " . utf8_decode($result->detailOutput->responseDescription);
			            $next_page = '';
			            $vars['message'] = $message;
						$vars['classmessage'] = 'danger';
						$vars['icon'] = 'fa-ban';
			        }

			        $button_name = "Continuar &raquo;";
			        $vars['button_name'] = $button_name;
			        $vars['token'] = $token;

			        break;
			    
			    case "end":
			        
			        $post_array = true;
			        
			        $tx_step = "End";
			        $request = "";
			        $result = $_POST;
			        
			        $message = "Transacion Finalizada";
			        $next_page = $sample_baseurl."?action=nullify";
			        $button_name = "Anular Transacci&oacute;n &raquo;";
			        $vars['message'] = $message;
					$vars['classmessage'] = 'success';
					$vars['icon'] = 'fa-check';	


			        $vars['button_name'] = $button_name;

			        break;   

			    
			    case "nullify":

			        $tx_step = "nullify";
			        
			        $request = $_POST;
			        
			        /** Codigo de Comercio */
			        $commercecode = null;

			        /** Código de autorización de la transacción que se requiere anular */
			        $authorizationCode = filter_input(INPUT_POST, 'authorizationCode');

			        /** Monto autorizado de la transacción que se requiere anular */
			        $amount =  filter_input(INPUT_POST, 'amount');

			        /** Orden de compra de la transacción que se requiere anular */
			        $buyOrder =  filter_input(INPUT_POST, 'buyOrder');
			        
			        /** Monto que se desea anular de la transacción */
			        $nullifyAmount = 200;

			        $request = array(
			            "authorizationCode" => $authorizationCode, // Código de autorización
			            "authorizedAmount" => $amount, // Monto autorizado
			            "buyOrder" => $buyOrder, // Orden de compra
			            "nullifyAmount" => $nullifyAmount, // idsession local
			            "commercecode" => $configuration->getCommerceCode(), // idsession local
			        );
			        
			        $result = $webpay->getNullifyTransaction()->nullify($authorizationCode, $amount, $buyOrder, $nullifyAmount, $commercecode);
			        
			        /** Verificamos resultado  de transacción */
			        if (!isset($result->authorizationCode)) {
			            $message = "webpay no disponible";
			        } else {
			            $message = "Transaci&oacute;n Finalizada";
			        }

			        $next_page = '';
			        
			        break;
			}

			$content = array(
						'menu' => 'Informaci&oacute;n',
						'title' => 'Informaci&oacute;n',
						'subtitle' => 'Ver Detalle Movimiento');

			$vars['periodo'] = $this->input->post('periodo');				
			$vars['idpropiedad'] = $this->input->post('idpropiedad');				
			$vars['pagototal'] = $this->input->post('pagototal');				


			$vars['content_menu'] = $content;				
			$vars['content_view'] = "payment/webpay_prop";
			$vars['tx_step'] = $tx_step;
			$vars['request'] = $request;
			$vars['result'] = $result;
			$vars['message'] = $message;
			$vars['next_page'] = $next_page;
			$vars['post_array'] = $post_array;


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

