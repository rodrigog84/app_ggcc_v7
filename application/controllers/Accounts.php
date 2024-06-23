<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

	
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

	public function add_cuenta($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('cuenta_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al crear cuenta individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 4){
				$vars['message'] = "Error al crear cuenta individual.  Medici&oacute;n sin cambios. Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('admin');

			$proveedores = $this->admin->get_proveedor_comunidad_by_id();
			$tipodoc = $this->admin->get_tipodoc_tributario_by_id();
			$conceptos = $this->admin->get_tipos_cuentas_comunidad_by_id();

			$this->load->model('account');
			$this->load->model('admin');
			$data_fondos = $this->admin->get_fondos($this->session->userdata('comunidadid'));

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar Cuenta');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/add_cuenta';
			$vars['proveedores'] = $proveedores;
			$vars['tipodoc'] = $tipodoc;
			$vars['conceptos'] = $conceptos;
			$vars['fondos'] = $data_fondos;

			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['icheck'] = true;
			$vars['maleta'] = true;	
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



	public function upfile($idcuenta = null,$tipocuenta = null,$origen = null,$idperiodo = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if((is_null($idperiodo) && $origen == 'ver_detalle_periodo') || is_null($idcuenta) || is_null($origen) || is_null($tipocuenta)){
				redirect('main/dashboard');
			}else{
				if($origen != 'ver_detalle_periodo' && $origen != 'pagos_cuentas'){
					redirect('main/dashboard');	
				}

				if($tipocuenta != 'cuenta' && $tipocuenta != 'cargo' && $tipocuenta != 'ingreso'){
					redirect('main/dashboard');	
				}
			}


			$this->load->model('account');

			if($origen == 'ver_detalle_periodo'){
				if($tipocuenta == 'ingreso'){
					$cuenta = $this->account->get_ingresos_by_periodo($idperiodo,$idcuenta);
				}else{
					$cuenta = $this->account->get_cuentas_by_periodo($idperiodo,$idcuenta);
				}
			}else if($origen == 'pagos_cuentas'){
				$cuenta = $this->account->get_cuentas_impagas_by_id($idcuenta);		
				if(is_null($cuenta)){
					$cuenta = $this->account->get_notas_credito($idcuenta);		
				}		
			}

			//$cuenta = $origen == 'ver_detalle_periodo' ? $this->account->get_cuentas_by_periodo($idperiodo,$idcuenta) : $this->account->get_cuentas_impagas_by_id($idcuenta);
			//var_dump($cuentas); exit;
			//$vars['existe'] = count($cuenta) > 0 ? true : false;

			$vars['existe'] = !is_null($cuenta) ? true : false;

			if(!$vars['existe']){ //  no se consideran aun los cobros individuales
				if($origen == 'ver_detalle_periodo'){
					$this->session->set_flashdata('desautorizacion_result', 3);
					redirect('reports/ver_detalle_periodo/0/'.$idperiodo);
				}else if($origen == 'pagos_cuentas'){
						$this->session->set_flashdata('pagos_cuentas_result', 6);
						redirect('accounts/pagos_cuentas');	
				}

			}else{
				#AUNQUE TENGA ARCHIVO, PERMITE MODIFICARLO
				/*if(!is_null($cuenta->nombrearchivo)){
					$this->session->set_flashdata('desautorizacion_result', 3);
					redirect('reports/ver_detalle_periodo/0/'.$idperiodo);					
				}*/
			}

		

	
			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Adjuntar Comprobante');

			


			$vars['content_menu'] = $content;				
			if($tipocuenta == 'cuenta'){
				$vars['content_view'] = 'accounts/upfilecuenta';
			}else if($tipocuenta == 'cargo'){
				$vars['content_view'] = 'accounts/upfilecargo';
			}else if($tipocuenta == 'ingreso'){
				$vars['content_view'] = 'accounts/upfileingreso';
			}
			#$vars['content_view'] = $tipocuenta == 'cuenta' ? 'accounts/upfilecuenta' : $tipocuenta == 'cargo' ? 'accounts/upfilecargo' : 'accounts/upfileingreso';
			$vars['cuenta'] = $cuenta;
			$vars['idperiodo'] = $idperiodo;
			$vars['tipoguarda'] = $origen;
			
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




	public function edit_cuenta($idcuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			if(is_null($idcuenta)){
				$this->session->set_flashdata('editar_cuenta_result', 17);
				redirect('accounts/editar_cuenta');
			}


			$this->load->model('account');
			$cuentas = $this->account->get_cuentas_by_id($idcuenta);

			//$vars['existe'] = count($cuentas) > 0 ? true : false;
			$vars['existe'] = !is_null($cuentas) ? true : false;

			if(!$vars['existe'] || $cuentas->formapago == 'ci'){ //  no se consideran aun los cobros individuales
				$this->session->set_flashdata('editar_cuenta_result', 4);
				redirect('accounts/editar_cuenta');
				/*$cuentas = array();
				$vars['message'] = "Cuenta no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				*/
			}

			if($cuentas->abonado > 0){
				$this->session->set_flashdata('editar_cuenta_result', 5);
				redirect('accounts/editar_cuenta');
			}



			if($cuentas->active == 0){
				$this->session->set_flashdata('editar_cuenta_result', 6);
				redirect('accounts/editar_cuenta');
			}


			$datos_form = array(
							'formapago' => is_null($cuentas) ? 0 : $cuentas->formapago,
							'idfondo' => is_null($cuentas) ? 0 : $cuentas->idfondo,
							'proveedor' => is_null($cuentas) ? 0 : $cuentas->idproveedor,
							'tipodoc' => is_null($cuentas) ? '' : $cuentas->idtipodoctotrib,
							'documento' => is_null($cuentas) ? '' : $cuentas->nrodocumento,
							'fecdocumento' => is_null($cuentas) ? '' : $cuentas->fecdocumento,
							'concepto' => is_null($cuentas) ? '' : $cuentas->idconcepto,
							'monto' => is_null($cuentas) ? '' : $cuentas->monto,
							'abonado' => is_null($cuentas) ? '' : $cuentas->abonado,
							'fecvencimiento' => is_null($cuentas) ? '' : $cuentas->fecvencimiento,
							'descripcion' => is_null($cuentas) ? '' : $cuentas->descripcion,
							'nombrearchivo' => is_null($cuentas) ? '' : $cuentas->nombrearchivo,
							'idcuenta' => is_null($cuentas) ? '' : $cuentas->id,
							);

			$this->load->model('admin');

			$proveedores = $this->admin->get_proveedor_comunidad_by_id();
			$tipodoc = $this->admin->get_tipodoc_tributario_by_id();
			$conceptos = $this->admin->get_tipos_cuentas_comunidad_by_id();
			$data_fondos = $this->admin->get_fondos();

			$this->load->model('account');

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Editar Cuenta');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/edit_cuenta';
			$vars['proveedores'] = $proveedores;
			$vars['tipodoc'] = $tipodoc;
			$vars['conceptos'] = $conceptos;
			$vars['fondos'] = $data_fondos;
			$vars['datos_form'] = $datos_form;


			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['mask'] = true;
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



	public function validate_cuenta($data = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$proveedor = $this->input->post('proveedor');
			$tipodoc = $this->input->post('tipodoc');
			$documento = $this->input->post('documento');
			$idcuenta = $this->input->post('idcuenta');

			$this->load->model('account');

			$existe = $tipodoc == 13 ? false : $this->account->valida_existe_cuenta($proveedor,$tipodoc,$documento,$idcuenta);

			$data = array();

			if($existe){
				$data['result'] = "error";
				$data['fields']['documento'] = "Ya existe el documento indicado para el proveedor";	
			}else{
				$data['result'] = "ok";
			}

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


	public function validate_cuenta_cuotas($data = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$proveedor = $this->input->post('proveedor');
			$tipodoc = $this->input->post('tipodoc');
			$documento = $this->input->post('documento');
			$idcuenta = $this->input->post('idcuenta');

			$this->load->model('account');

			$existe = $tipodoc == 13 ? false : $this->account->valida_existe_cuenta_cuotas($proveedor,$tipodoc,$documento,$idcuenta);
			$data = array();
			if($existe){
				$data['result'] = "error";
				$data['fields']['documento'] = "Ya existe el documento indicado para el proveedor";	
			}else{
				$data['result'] = "ok";
			}

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


	public function validate_ingreso($data = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$proveedor = $this->input->post('proveedor');
			$tipodoc = $this->input->post('tipodoc');
			$documento = $this->input->post('documento');
			$idingreso = $this->input->post('idingreso');

			$this->load->model('account');
			$existe = $this->account->valida_existe_ingreso($proveedor,$tipodoc,$documento,$idingreso);
			$data = array();
			if($existe){
				$data['result'] = "error";
				$data['fields']['documento'] = "Ya existe el documento indicado para el proveedor";	
			}else{
				$data['result'] = "ok";
			}

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

	public function submit_cuenta()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$array_post = $this->input->post(NULL,true);
			if(count($array_post) == 0){
				redirect('main/dashboard');
			}

	        $config['upload_path'] = "./uploads/cuentas/".$this->session->userdata('comunidadid')."/"	;

			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'],0777,true);
			}

	        $config['file_name'] = date("Ymd")."_".date("His")."_".randomstring(5)."_".$this->input->post("documento");
	        $config['allowed_types'] = "*";
	        $config['max_size'] = "10240";
	        //$config['max_width'] = "2000";
	        //$config['max_height'] = "2000";

	        $this->load->library('upload', $config);
	        $this->upload->do_upload("userfile");


	        /*if (!$this->upload->do_upload("userfile")) {
	            //*** ocurrio un error
	            print_r($this->upload->data()); exit;
	            $data['uploadError'] = $this->upload->display_errors();
	            redirect('accounts/add_cuenta/2');
	            //return;
	        }*/
       		$dataupload = $this->upload->data();
       		$formapago = $this->input->post("cuotas") == 'cc' ? 'gc' : $this->input->post("formapago");

       		$parametros = array(
       						'idcuenta' => $this->input->post("idcuenta"),
       						'idproveedor' => $this->input->post("proveedor"),
       						'tipodoc' => $this->input->post("tipodoc"),
       						'documento' => $this->input->post("documento"),
       						'fecdocumento' => $this->input->post("fecdocumento"),
       						'concepto' => $this->input->post("concepto"),
       						'monto' => str_replace(".","",$this->input->post("monto")),
       						'totalcuenta' => $this->input->post("cuotas") == 'cc' ? str_replace(".","",$this->input->post("monto")) : 0,
       						'cuotas' => $this->input->post("cuotas"),
       						'numcuotas' => $this->input->post("numcuotas"),
       						'montocuota' => str_replace(".","",$this->input->post("montocuota")),
       						'fecvencimiento' => $this->input->post("fecvencimiento"),
       						'descripcion' => $this->input->post("descripcion"),
       						'formapago' => $formapago,
       						'nombrearchivo' => $dataupload['orig_name'],
       						'nombrerealarchivo' => $dataupload['client_name']
			       			);


       		if($formapago == 'ci'){ // cobro individual
       			$this->session->set_flashdata('cuenta', $parametros);
       			redirect('accounts/add_cobro_individual');	

       		}

       		$descripcion = $parametros['descripcion'];

       		$this->load->model('account');
       		if($this->input->post("cuotas") == 'cc'){

       			$parametros_cuenta_cuotas = array(
       						'idcuenta' => 0,
       						'idproveedor' => $this->input->post("proveedor"),
       						'tipodoc' => $this->input->post("tipodoc"),
       						'documento' => $this->input->post("documento"),
       						'fecdocumento' => $this->input->post("fecdocumento"),
       						'concepto' => $this->input->post("concepto"),
       						'monto' => str_replace(".","",$this->input->post("monto")),
       						'numcuotas' => $this->input->post("numcuotas"),
       						'fecvencimiento' => $this->input->post("fecvencimiento"),
       						'descripcion' => $descripcion,
       						'formapago' => $formapago,
       						'nombrearchivo' => $dataupload['orig_name'],
       						'nombrerealarchivo' => $dataupload['client_name']
			       			);

       			$idcuotas = $this->account->add_cuenta_cuotas($parametros_cuenta_cuotas);


       			$monto_total = $parametros['monto'];
       			$fecvencimiento = formato_fecha($parametros['fecvencimiento'],'d/m/Y','Y-m-d');
       			$parametros['idcuotas'] = $idcuotas;

       			for($i=1;$i<=$parametros['numcuotas'];$i++){

       				$parametros['descripcion'] = $descripcion . " . Cuota " . $i . " de " . $parametros['numcuotas'];
       				if( ($monto_total - $parametros['montocuota']) < $parametros['montocuota']){
       					$parametros['monto'] = $parametros['montocuota'] + ($monto_total - $parametros['montocuota']);
       				}else{
       					$parametros['monto'] = $parametros['montocuota'];
       				}
       				
       				$parametros['fecvencimiento'] = date("d-m-Y", strtotime("+ " . ($i - 1 ) . " month", strtotime($fecvencimiento)));
       				$this->account->add_cuenta($parametros);

       				$monto_total = $monto_total - $parametros['montocuota'];

       			}
       		}else{ //SIN CUOTAS SE GRABA DE MANERA TRADICIONAL
	       		
				$this->account->add_cuenta($parametros);

       		}





			if($this->input->post("idcuenta") == 0){
				$this->session->set_flashdata('editar_cuenta_result', 7);
				redirect('accounts/editar_cuenta');				
			}else{
				if($this->input->post('tipoguarda') == ''){
					$this->session->set_flashdata('editar_cuenta_result', 1);
					redirect('accounts/editar_cuenta');				
				}else{

					if($this->input->post('tipoguarda') == 'ver_detalle_periodo'){
						$this->session->set_flashdata('desautorizacion_result', 4);
						redirect('reports/ver_detalle_periodo/0/'.$this->input->post('idperiodo'));	
					}else if($this->input->post('tipoguarda') == 'pagos_cuentas'){
						$this->session->set_flashdata('pagos_cuentas_result', 5);
						redirect('accounts/pagos_cuentas');	
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



	public function submit_cuenta_cuotas()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
	        $config['upload_path'] = "./uploads/cuentas/".$this->session->userdata('comunidadid')."/"	;

			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'],0777,true);
			}

	        $config['file_name'] = date("Ymd")."_".date("His")."_".randomstring(5)."_".$this->input->post("documento");
	        $config['allowed_types'] = "*";
	        $config['max_size'] = "10240";
	        //$config['max_width'] = "2000";
	        //$config['max_height'] = "2000";

	        $this->load->library('upload', $config);
	        $this->upload->do_upload("userfile");

	        /*if (!$this->upload->do_upload("userfile")) {
	            //*** ocurrio un error
	            print_r($this->upload->data()); exit;
	            $data['uploadError'] = $this->upload->display_errors();
	            redirect('accounts/add_cuenta/2');
	            //return;
	        }*/
       		$dataupload = $this->upload->data();
       		$formapago = $this->input->post("cuotas") == 'cc' ? 'gc' : $this->input->post("formapago");

       		$parametros = array(
       						'idcuenta' => 0,
       						'idproveedor' => $this->input->post("proveedor"),
       						'tipodoc' => $this->input->post("tipodoc"),
       						'documento' => $this->input->post("documento"),
       						'fecdocumento' => $this->input->post("fecdocumento"),
       						'concepto' => $this->input->post("concepto"),
       						'monto' => str_replace(".","",$this->input->post("monto")),
       						'totalcuenta' => $this->input->post("cuotas") == 'cc' ? str_replace(".","",$this->input->post("monto")) : 0,
       						'cuotas' => $this->input->post("cuotas"),
       						'numcuotas' => $this->input->post("numcuotas"),
       						'montocuota' => str_replace(".","",$this->input->post("montocuota")),
       						'fecvencimiento' => $this->input->post("fecvencimiento"),
       						'descripcion' => $this->input->post("descripcion"),
       						'formapago' => $formapago,
       						'nombrearchivo' => $dataupload['orig_name'],
       						'nombrerealarchivo' => $dataupload['client_name']
			       			);

       		$descripcion = $parametros['descripcion'];

       		$this->load->model('account');
   			$parametros_cuenta_cuotas = array(
   						'idcuenta' => $this->input->post("idcuenta"),
   						'idproveedor' => $this->input->post("proveedor"),
   						'tipodoc' => $this->input->post("tipodoc"),
   						'documento' => $this->input->post("documento"),
   						'fecdocumento' => $this->input->post("fecdocumento"),
   						'concepto' => $this->input->post("concepto"),
   						'monto' => str_replace(".","",$this->input->post("monto")),
   						'numcuotas' => $this->input->post("numcuotas"),
   						'fecvencimiento' => $this->input->post("fecvencimiento"),
   						'descripcion' => $descripcion,
   						'formapago' => $formapago,
   						'nombrearchivo' => $dataupload['orig_name'],
   						'nombrerealarchivo' => $dataupload['client_name']
		       			);

   			$this->db->trans_start();

   			$idcuotas = $this->account->add_cuenta_cuotas($parametros_cuenta_cuotas);


   			$monto_total = $parametros['monto'];
   			$fecvencimiento = formato_fecha($parametros['fecvencimiento'],'d/m/Y','Y-m-d');
   			$parametros['idcuotas'] = $idcuotas;

   			for($i=1;$i<=$parametros['numcuotas'];$i++){

   				$parametros['descripcion'] = $descripcion . " . Cuota " . $i . " de " . $parametros['numcuotas'];
   				if( ($monto_total - $parametros['montocuota']) < $parametros['montocuota']){
   					$parametros['monto'] = $parametros['montocuota'] + ($monto_total - $parametros['montocuota']);
   				}else{
   					$parametros['monto'] = $parametros['montocuota'];
   				}
   				
   				$parametros['fecvencimiento'] = date("d-m-Y", strtotime("+ " . ($i - 1 ) . " month", strtotime($fecvencimiento)));
   				$this->account->add_cuenta($parametros);

   				$monto_total = $monto_total - $parametros['montocuota'];

   			}

   			$this->db->trans_complete();
			$this->session->set_flashdata('editar_cuenta_result', 1);
			redirect('accounts/editar_cuenta');				

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


	public function submit_honorarios_condominio()
		{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$array_post = $this->input->post(NULL,true);
			if(count($array_post) == 0){
				redirect('main/dashboard');
			}



			//print_r($this->input->post(NULL,true)); exit;
	        $config['upload_path'] = "./uploads/cuentas/".$this->session->userdata('comunidadid')."/"	;

			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'],0777,true);
			}

	        $config['file_name'] = date("Ymd")."_".date("His")."_".randomstring(5)."_".$this->input->post("documento");
	        $config['allowed_types'] = "*";
	        $config['max_size'] = "10240";
	        //$config['max_width'] = "2000";
	        //$config['max_height'] = "2000";

	        $this->load->library('upload', $config);
	        $this->upload->do_upload("userfile");


	        /*if (!$this->upload->do_upload("userfile")) {
	            //*** ocurrio un error
	            print_r($this->upload->data()); exit;
	            $data['uploadError'] = $this->upload->display_errors();
	            redirect('accounts/add_cuenta/2');
	            //return;
	        }*/
       		$dataupload = $this->upload->data();
       		$this->load->model('account');

       		$parametros = array(
       						'idcuenta' => $this->input->post("idcuenta"),
       						'idproveedor' => $this->input->post("proveedor"),
       						'tipodoc' => $this->input->post("tipodoc"),
       						'retencion' => $this->input->post("tiporetencion"),       						
       						'documento' => $this->input->post("documento"),
       						'fecdocumento' => $this->input->post("fecdocumento"),
       						'concepto' => $this->input->post("concepto"),
       						'monto' => str_replace(".","",$this->input->post("montoliquido")),
       						'fecvencimiento' => $this->input->post("fecvencimiento"),
       						'descripcion' => $this->input->post("descripcion"),
       						'formapago' => $this->input->post("formapago"),
       						'nombrearchivo' => $dataupload['orig_name'],
       						'nombrerealarchivo' => $dataupload['client_name']
			       			);


       		$retencion = str_replace(".","",$this->input->post("montoretencion"));
       		$idcuenta = $this->account->add_cuenta($parametros);


       		if($retencion > 0){
	       		$parametros_retencion = array(
	       						'idcuenta' => $this->input->post("idretencion"),
	       						'idproveedor' => NULL,
	       						'proveedor' => 'TESORERIA GENERAL DE LA REPUBLICA',
	       						'retencionidctaasoc' => $idcuenta,
	       						'tipodoc' => $this->input->post("tipodoc"),
	       						'retencion' => $this->input->post("tiporetencion"),
	       						'documento' => $this->input->post("documento"),
	       						'fecdocumento' => $this->input->post("fecdocumento"),
	       						'concepto' => $this->input->post("concepto"),
	       						'monto' => str_replace(".","",$this->input->post("montoretencion")),
	       						'fecvencimiento' => $this->input->post("fecvencimiento"),
	       						'descripcion' => $this->input->post("descripcion"),
	       						'formapago' => $this->input->post("formapago"),
	       						'nombrearchivo' => $dataupload['orig_name'],
	       						'nombrerealarchivo' => $dataupload['client_name']
				       			);
	       		$this->account->add_cuenta($parametros_retencion);
       		}else{ #EN CASO DE EDICION Y QUITAR RETENCION
       			if($this->input->post("idretencion") != 0){
       				$this->account->delete_cuenta_retencion($this->input->post("idretencion"));	
       			}
       			

       		}
	

			if($this->input->post("idcuenta") == 0){
				$this->session->set_flashdata('editar_cuenta_honorarios_result', 7);
				redirect('accounts/honorarios_condominio');				
			}else{
				if($this->input->post('tipoguarda') == ''){
					$this->session->set_flashdata('editar_cuenta_honorarios_result', 1);
					redirect('accounts/honorarios_condominio');				
				}else{

					if($this->input->post('tipoguarda') == 'ver_detalle_periodo'){
						$this->session->set_flashdata('desautorizacion_result', 4);
						redirect('reports/ver_detalle_periodo/0/'.$this->input->post('idperiodo'));	
					}else if($this->input->post('tipoguarda') == 'pagos_cuentas'){
						$this->session->set_flashdata('pagos_cuentas_result', 5);
						redirect('accounts/pagos_cuentas');	
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

	public function add_ingreso_comunidad($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('add_ingreso_result');
			if($resultid == 1){
				$vars['message'] = "Ingreso Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al crear cuenta individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('admin');

			$proveedores = $this->admin->get_proveedor_comunidad_by_id();
			$tipodoc = $this->admin->get_tipodoc_tributario_by_id();
			$conceptos = $this->admin->get_ingresos_comunidad_by_id();


			$this->load->model('account');

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Ingresos Comunidad');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/add_ingreso_comunidad';
			$vars['proveedores'] = $proveedores;
			$vars['tipodoc'] = $tipodoc;
			$vars['conceptos'] = $conceptos;

			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['mask'] = true;
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




	public function submit_ingreso()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
	        $config['upload_path'] = "./uploads/ingresos/".$this->session->userdata('comunidadid')."/"	;

			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'],0777,true);
			}

	        $config['file_name'] = date("Ymd")."_".date("His")."_".randomstring(5)."_".$this->input->post("documento");
	        $config['allowed_types'] = "*";
	        $config['max_size'] = "10240";
	        //$config['max_width'] = "2000";
	        //$config['max_height'] = "2000";
	        $this->load->library('upload', $config);
	        $this->upload->do_upload("userfile");


	        /*if (!$this->upload->do_upload("userfile")) {
	            //*** ocurrio un error
	            print_r($this->upload->data()); exit;
	            $data['uploadError'] = $this->upload->display_errors();
	            redirect('accounts/add_cuenta/2');
	            //return;
	        }*/
       		$dataupload = $this->upload->data();

       		$parametros = array(
       						'idingreso' => $this->input->post("idingreso"),
       						'idproveedor' => $this->input->post("proveedor"),
       						'tipodoc' => $this->input->post("tipodoc"),
       						'documento' => $this->input->post("documento"),
       						'fecdocumento' => $this->input->post("fecdocumento"),
       						'concepto' => $this->input->post("concepto"),
       						'monto' => str_replace(".","",$this->input->post("monto")),
       						'fecvencimiento' => $this->input->post("fecvencimiento"),
       						'descripcion' => $this->input->post("descripcion"),
       						'tipoingreso' => $this->input->post("tipoingreso"),
       						'habilitagasto' => $this->input->post('habilitagasto') == 'on' ? 1 : 0,
       						'nombrearchivo' => $dataupload['orig_name'],
       						'nombrerealarchivo' => $dataupload['client_name'],
			       			);


       		$noautoriza = $this->input->post('tipoguarda') == '' ? null : true;
      		$this->load->model('account');
			$this->account->add_ingreso($parametros,$noautoriza);



			if($this->input->post("idingreso") == 0){
				$this->session->set_flashdata('editar_ingreso_result', 6);
				redirect('accounts/editar_ingresos');				
			}else{
				if($this->input->post('tipoguarda') == ''){
					$this->session->set_flashdata('editar_ingreso_result', 1);
					redirect('accounts/editar_ingresos');				
				}else{

					if($this->input->post('tipoguarda') == 'ver_detalle_periodo'){
						$this->session->set_flashdata('desautorizacion_result', 4);
						redirect('reports/ver_detalle_periodo/0/'.$this->input->post('idperiodo'));	
					}else if($this->input->post('tipoguarda') == 'pagos_cuentas'){
						$this->session->set_flashdata('pagos_cuentas_result', 5);
						redirect('accounts/pagos_cuentas');	
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


	public function add_cobro_individual(){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
				$datos_cuenta = $this->session->flashdata('cuenta');

				$this->session->keep_flashdata('cuenta');

				if(count($datos_cuenta) == 0){
						$this->session->set_flashdata('editar_cuenta_result', 9);
						redirect('accounts/editar_cuenta');	
				}

				$this->load->model('admin');
				$proveedor = $this->admin->get_proveedor_by_id($datos_cuenta['idproveedor']);
				$tipodoc = $this->admin->get_tipodoc_tributario_by_id($datos_cuenta['tipodoc']);
				$propiedades = $this->admin->get_propiedades_comunidad();
				$concepto =	$this->admin->get_concepto_by_id($datos_cuenta['concepto']);

				$this->load->model('account');
				$ultima_lectura = $this->account->get_ultima_lectura($datos_cuenta['concepto']);
				$array_datos_lectura = array();

				// LLENA ARREGLO DE VALORES ANTERIORES CON CEROS
				foreach ($propiedades as $propiedad) {
					if(!array_key_exists($propiedad->id, $array_datos_lectura)){
						$array_datos_lectura[$propiedad->id] = 0;
					}
				}	

				// SIGNIFICA QUE EXISTE ULTIMA LECTURA.  ES NECESARIO BUSCAR EL DETALLE
				$readonly = '';
				$readonly_jquery = 'false';
				if($ultima_lectura !== false){ 
					$datos_lectura = $this->account->get_detalle_lectura($ultima_lectura->id);
					foreach ($datos_lectura as $dato) {
						$array_datos_lectura[$dato->idpropiedad] = $dato->valor;
					}
					$readonly = 'readonly';
					$readonly_jquery = 'true';
				}

				$this->load->model('payment');
				$datosperiodo = $this->payment->get_periodos_activos($this->session->userdata('comunidadid'));				

				$info_cuenta = array('proveedor' => $proveedor->nombre,
									  'tipodoc' => $tipodoc->nombre,
									  'nrodocumento' => $datos_cuenta['documento'],
									  'concepto' => $concepto->nombre,
									  'unidadmedida' => '');

				$content = array(
							'menu' => 'Cuentas',
							'title' => 'Cuentas',
							'subtitle' => 'Agregar Cobro Individual');

				
				$vars['content_menu'] = $content;				
				$vars['content_view'] = 'accounts/add_cobro_individual';				
				$vars['propiedades'] = $propiedades;
				$vars['datos_cuenta'] = $datos_cuenta;
				$vars['datosperiodo'] = $datosperiodo;
				$vars['info_cuenta'] = $info_cuenta;
				$vars['datos_lectura'] = $array_datos_lectura;
				$vars['readonly'] = $readonly;
				$vars['readonly_jquery'] = $readonly_jquery;
				$vars['dataTables'] = true;
				$vars['formValidation'] = true;				
				$vars['maleta'] = true;				
				$vars['mask'] = true;				
				$vars['icheck'] = true;
				$vars['nuevomedidor'] = 'N';

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



	public function edit_cobro_individual($idcuenta = null){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

				if(is_null($idcuenta)){
						$this->session->set_flashdata('editar_cuenta_result', 15);
						redirect('accounts/editar_cuenta');						
				}


				$this->load->model('account');
				$datos_cuenta = $this->account->get_cuentas_by_id($idcuenta);

				//$datos_cuenta = $this->session->flashdata('cuenta');
				if(is_null($datos_cuenta)){
						$this->session->set_flashdata('editar_cuenta_result', 11);
						redirect('accounts/editar_cuenta');	
				}

				$this->load->model('admin');
				$proveedor = $this->admin->get_proveedor_by_id($datos_cuenta->idproveedor);
				$tipodoc = $this->admin->get_tipodoc_tributario_by_id($datos_cuenta->idtipodoctotrib);
				$propiedades = $this->admin->get_propiedades_comunidad();
				$concepto =	$this->admin->get_concepto_by_id($datos_cuenta->idconcepto);

				$this->load->model('account');

				$ultima_lectura = $this->account->get_lectura_by_cuenta($idcuenta);
				$lecturas_previas = $this->account->get_ultima_lectura($datos_cuenta->idconcepto,$idcuenta);




				$array_datos_lectura = array();

				// LLENA ARREGLO DE VALORES ANTERIORES CON CEROS
				foreach ($propiedades as $propiedad) {
					if(!array_key_exists($propiedad->id, $array_datos_lectura)){
						$array_datos_lectura[$propiedad->id] = 0;
					}
				}	

				// SIGNIFICA QUE EXISTE ULTIMA LECTURA.  ES NECESARIO BUSCAR EL DETALLE
				$readonly = '';
				$readonly_jquery = 'false';
				if($ultima_lectura !== false){ 
					$datos_lectura = $this->account->get_detalle_lectura($ultima_lectura->id);
					foreach ($datos_lectura as $dato) {
						$array_datos_lectura[$dato->idpropiedad] = $dato->valor;
						$array_datos_lectura_ant[$dato->idpropiedad] = $dato->valor_ant;
					}
					$readonly = !$lecturas_previas || $ultima_lectura->nuevomedidor == 'Y' ? '' : 'readonly';
					$readonly_jquery = !$lecturas_previas || $ultima_lectura->nuevomedidor == 'Y' ? 'false' : 'true';
				}


				$this->load->model('payment');
				$datosperiodo = $this->payment->get_periodos_activos($this->session->userdata('comunidadid'));				

				$info_cuenta = array('proveedor' => $proveedor->nombre,
									  'tipodoc' => $tipodoc->nombre,
									  'nrodocumento' => $datos_cuenta->nrodocumento,
									  'concepto' => $concepto->nombre,
									  'unidadmedida' => $datos_cuenta->unidadmedida);

				$content = array(
							'menu' => 'Cuentas',
							'title' => 'Cuentas',
							'subtitle' => 'Editar Cobro Individual');

				
				$vars['content_menu'] = $content;				
				$vars['content_view'] = 'accounts/add_cobro_individual';				
				$vars['propiedades'] = $propiedades;
				$vars['datos_cuenta'] = (array)$datos_cuenta;
				$vars['datosperiodo'] = $datosperiodo;
				$vars['info_cuenta'] = $info_cuenta;
				$vars['datos_lectura'] = $array_datos_lectura;
				$vars['datos_lectura_ant'] = $array_datos_lectura_ant;
				$vars['nuevomedidor'] = isset($ultima_lectura->nuevomedidor) ? $ultima_lectura->nuevomedidor : 'N';
				$vars['readonly'] = $readonly;
				$vars['readonly_jquery'] = $readonly_jquery;
				$vars['formValidation'] = true;				
				$vars['maleta'] = true;				
				$vars['mask'] = true;
				$vars['idcuenta'] = $idcuenta;	
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



	public function submit_cobro_individual()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$datos_cuenta = $this->session->flashdata('cuenta');
			$array_elem = $this->input->post(NULL,true);
			$this->load->model('account');
   			$result = $this->account->add_cobro_individual($array_elem,$datos_cuenta);
   			if($result){
   				$this->session->set_flashdata('editar_cuenta_result', 7);
   			}else{
   				$this->session->set_flashdata('editar_cuenta_result', 10);
   			}

			redirect('accounts/editar_cuenta');				

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


	public function submit_edit_cobro_individual()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			// agregar guardado de cuenta asociado al monto
			$array_elem = $this->input->post(NULL,true);

			$this->load->model('account');

			if($this->input->post("idcuenta") == ""){
				$this->session->set_flashdata('editar_cuenta_result', 15);
				redirect('accounts/editar_cuenta');					
			}
			
			$datos_cuenta = $this->account->get_cuentas_by_id($this->input->post("idcuenta"));
			if(is_null($datos_cuenta)){
				$this->session->set_flashdata('editar_cuenta_result', 9);
				redirect('accounts/editar_cuenta');	
			}

			if(is_null($datos_cuenta)){
				$this->session->set_flashdata('editar_cuenta_result', 15);
				redirect('accounts/editar_cuenta');		
			}

			$datos_cuenta = (array)$datos_cuenta;
			$datos_cuenta['tipodoc'] = $datos_cuenta['idtipodoctotrib'];
			$datos_cuenta['documento'] = $datos_cuenta['nrodocumento'];
			$datos_cuenta['concepto'] = $datos_cuenta['idconcepto'];
			$datos_cuenta['periodo'] = $datos_cuenta['idperiodo'];

			$this->account->delete_cobro_individual($this->input->post("idcuenta"));
			$result = $this->account->add_cobro_individual($array_elem,$datos_cuenta);
   			if($result){
   				$this->session->set_flashdata('editar_cuenta_result', 16);
   			}else{
   				$this->session->set_flashdata('editar_cuenta_result', 15);
   			}			
   			redirect('accounts/editar_cuenta');				

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

	public function add_otros_cargos($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('add_otros_cargos_result');
			if($resultid == 1){
				$vars['message'] = "Cargo Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('admin');

			$conceptos = $this->admin->get_concepto_by_id();

			$this->load->model('account');

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Otros Cargos');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/add_otros_cargos';


			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
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



	public function edit_otros_cargos($idcargo)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$cargos = $this->account->get_cargos_by_id($idcargo);

			//$vars['existe'] = count($cargos) > 0 ? true : false;
			$vars['existe'] = !is_null($cargos) ? true : false;

			if(!$vars['existe']){
				$vars['message'] = "Cargo no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}			

			$datos_form = array(
							'proveedor' => is_null($cargos) ? 0 : $cargos->nombreproveedor,
							'fecpago' => is_null($cargos) ? '' : $cargos->fecpago,
							'monto' => is_null($cargos) ? '' : $cargos->monto,
							'abonado' => is_null($cargos) ? '' : $cargos->abonado,
							'descripcion' => is_null($cargos) ? '' : $cargos->descripcion,
							'nombrearchivo' => is_null($cargos) ? '' : $cargos->nombrearchivo,
							'idcargo' => is_null($cargos) ? '' : $cargos->id,
							);



			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Otros Cargos');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/edit_otros_cargos';


			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['datos_form'] = $datos_form;
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



	public function submit_otros_cargos()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
	        $config['upload_path'] = "./uploads/cuentas/".$this->session->userdata('comunidadid')."/"	;

			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'],0777,true);
			}

	        $config['file_name'] = date("Ymd")."_".date("His")."_".randomstring(5)."_".randomstring(3);
	        $config['allowed_types'] = "*";
	        $config['max_size'] = "10240";
	        //$config['max_width'] = "2000";
	        //$config['max_height'] = "2000";

	        $this->load->library('upload', $config);
	       	$this->upload->do_upload("userfile");
	        /*if (!$this->upload->do_upload("comprobante")) {

	            //*** ocurrio un error
	            $data['uploadError'] = $this->upload->display_errors();
	            redirect('accounts/add_otros_cargos/2');
	            //return;
	        }*/
       		$dataupload = $this->upload->data();
       		$parametros = array(
       						'idcargo' => $this->input->post("idcargo"),
       						'nombreproveedor' => $this->input->post("proveedor"),
       						'fecpago' => $this->input->post("fecpago"),
       						'monto' => str_replace(".","",$this->input->post("monto")),
       						'descripcion' => $this->input->post("descripcion"),
       						'nombrearchivo' => $dataupload['orig_name'],
       						'nombrerealarchivo' => $dataupload['client_name']
			       			);

       		$this->load->model('account');
			$this->account->add_otros_cargos($parametros);


			if($this->input->post("idcargo") == 0){
				
				$this->session->set_flashdata('editar_otros_cargos_result',7);
				redirect('accounts/editar_otros_cargos');				
			}else{

				if($this->input->post('tipoguarda') == ''){
					$this->session->set_flashdata('editar_otros_cargos_result', 1);
					redirect('accounts/editar_otros_cargos');				
				}else{
					if($this->input->post('tipoguarda') == 'ver_detalle_periodo'){
						$this->session->set_flashdata('desautorizacion_result', 4);
						redirect('reports/ver_detalle_periodo/0/'.$this->input->post('idperiodo'));	
					}else if($this->input->post('tipoguarda') == 'pagos_cuentas'){
						$this->session->set_flashdata('pagos_cuentas_result', 5);
						redirect('accounts/pagos_cuentas');	
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



	public function add_cuenta_individual($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('add_cuenta_individual_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta Individual Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al editar cuenta individual.  Debe indicar cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			

			//$propiedades = $this->payment->get_deuda_by_comunidad($this->session->userdata('comunidadid'));
			

			$this->load->model('admin');
			$propiedades = $this->admin->get_propiedades_comunidad();
			$conceptos = $this->admin->get_cuentas_individuales_by_id();


			$this->load->model('payment');
			$datosperiodo = $this->payment->get_periodos_activos($this->session->userdata('comunidadid'));
			$this->load->model('account');

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar Cuenta Individual');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/add_cuenta_individual';
			$vars['propiedades'] = $propiedades;
			$vars['datosperiodo'] = $datosperiodo;
			$vars['conceptos'] = $conceptos;

			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['multipleSelect'] = true;
			$vars['mask'] = true;
			//$vars['jQuery213'] = false;
			//$vars['jQuery191'] = true;			
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



	public function edit_cuenta_individual($idcuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if(is_null($idcuenta)){
				$this->session->set_flashdata('editar_individual_result', 9);
				redirect('accounts/editar_individual');
			}
			

			$this->load->model('account');
			$cuentas = $this->account->get_cuentas_individuales_by_id($idcuenta);

			//var_dump($cuentas); exit;

			if(is_null($cuentas)){
				$this->session->set_flashdata('editar_individual_result', 10);
				redirect('accounts/editar_individual');				
			}



			$datos_form = array(
							'idcuenta' => is_null($cuentas) ? 0 : $cuentas->id,
							'concepto' => is_null($cuentas) ? 0 : $cuentas->tipo_concepto.'-'.$cuentas->idconcepto,
							'propiedad' => is_null($cuentas) ? 0 : $cuentas->idpropiedad,
							'periodo' => is_null($cuentas) ? '' : $cuentas->idperiodo,
							'fechadeuda' => is_null($cuentas) ? '' : $cuentas->fechadeuda,
							'monto' => is_null($cuentas) ? '' : ($cuentas->idconcepto == 8 ? $cuentas->monto*(-1) : $cuentas->monto),
							'descripcion' => is_null($cuentas) ? '' : $cuentas->descripcion,
							'nombrearchivo' => is_null($cuentas) ? '' : $cuentas->nombrearchivo,
							);

			$this->load->model('admin');
			$propiedades = $this->admin->get_propiedades_comunidad();
			$conceptos = $this->admin->get_cuentas_individuales_by_id();


			$this->load->model('payment');
			$datosperiodo = $this->payment->get_periodos_activos($this->session->userdata('comunidadid'));
			$this->load->model('account');

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Editar Cuenta Individual');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/edit_cuenta_individual';
			$vars['propiedades'] = $propiedades;
			$vars['datosperiodo'] = $datosperiodo;
			$vars['conceptos'] = $conceptos;
			$vars['datos_form'] = $datos_form;

			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['multipleSelect'] = true;
			$vars['mask'] = true;
			//$vars['jQuery213'] = false;
			//$vars['jQuery191'] = true;			
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

	public function submit_cuenta_individual()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			if($this->input->post("idcuenta")){
				$this->load->model('account');
				$cuentas = $this->account->get_cuentas_individuales_by_id($this->input->post("idcuenta"));

				if(is_null($cuentas)){
					$this->session->set_flashdata('editar_individual_result', 10);
					redirect('accounts/editar_individual');				
				}			

			}




	        $config['upload_path'] = "./uploads/cuentas/".$this->session->userdata('comunidadid')."/"	;

			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'],0777,true);
			}

	        $config['file_name'] = date("Ymd")."_".date("His")."_".randomstring(5)."_".randomstring(3);
	        $config['allowed_types'] = "*";
	        $config['max_size'] = "10240";
	        //$config['max_width'] = "2000";
	        //$config['max_height'] = "2000";

	        $this->load->library('upload', $config);
	        $this->upload->do_upload("userfile");
	        /*if (!$this->upload->do_upload("comprobante")) {

	            //*** ocurrio un error
	            $data['uploadError'] = $this->upload->display_errors();
	            redirect('accounts/add_cuenta_individual/2');
	            //return;
	        }*/
       		$dataupload = $this->upload->data();

       		$lista_propiedades = $this->input->post('select');
       		$array_propiedades = explode(",",$lista_propiedades);

       		
       		//if($this->input->post("propiedad") == '' &&  $this->input->post("concepto") == 9){ //cuotas especiales seleccionando todas las propiedades
       		//	$this->load->model('payment');
       		//	$propiedades = $this->payment->get_deuda_by_comunidad($this->session->userdata('comunidadid'));
       			$this->load->model('account');
       			//foreach ($propiedades as $propiedad) {
       			foreach ($array_propiedades as $propiedad) {

       				if($propiedad != ''){

       					$array_concepto = explode('-',$this->input->post("concepto"));

			       		$parametros = array(
			       						'idcuentaindividual' => $this->input->post("idcuenta"),
			       						'idpropiedad' => $propiedad,
			       						'idperiodo' => $this->input->post("periodo"),
			       						'tipo_concepto' => $array_concepto[0],
			       						'concepto' => $array_concepto[1],
			       						'fecuso' => $this->input->post("fecha"),
			       						'monto' => str_replace(".","",$this->input->post("monto")),
			       						'descripcion' => $this->input->post("descripcion"),
			       						'nombrearchivo' => $dataupload['orig_name'],
			       						'nombrerealarchivo' => $dataupload['client_name']
						       			);       		

			       		
						$this->account->add_cuenta_individual($parametros);
					}

       			}
       		/*}else{ // se aplica solo a una 
       		
       			$this->load->model('account');
	       		$parametros = array(
	       						'idpropiedad' => $this->input->post("propiedad"),
	       						'idperiodo' => $this->input->post("periodo"),
	       						'concepto' => $this->input->post("concepto"),
	       						'fecuso' => $this->input->post("fecha"),
	       						'monto' => $this->input->post("monto"),
	       						'descripcion' => $this->input->post("descripcion"),
	       						'nombrearchivo' => $dataupload['orig_name'],
	       						'nombrerealarchivo' => $dataupload['client_name']
				       			);       		

	       		
				$this->account->add_cuenta_individual($parametros);
			}*/


			$resultid = $this->session->set_flashdata('editar_individual_result',7);
			redirect('accounts/editar_individual');				

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


	public function add_adm_esp_comunes($resultid = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('add_adm_esp_comunes_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta Administraci&oacute;n de Espacios Comunes Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('payment');

			

			$this->load->model('admin');
			$propiedades = $this->admin->get_propiedades_comunidad();			
			$conceptos = $this->admin->get_cuentas_espacios_comunes_comunidad_by_id();
			$unidades_medidas = $this->admin->get_um_esp_comun_by_id();

			$datosperiodo = $this->payment->get_periodos_activos($this->session->userdata('comunidadid'));
			$this->load->model('account');

			$cuentas = $this->account->get_cuentas_espacios_comunes_by_id();
			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar Cuenta de Utilizaci&oacute;n de Espacios Comunes');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/add_adm_esp_comunes';
			$vars['propiedades'] = $propiedades;
			$vars['datosperiodo'] = $datosperiodo;
			$vars['conceptos'] = $conceptos;
			$vars['cuentas'] = $cuentas;
			$vars['unidades_medidas'] = $unidades_medidas;

			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['maleta'] = true;	
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



	public function submit_adm_esp_comunes()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
	        $config['upload_path'] = "./uploads/cuentas/".$this->session->userdata('comunidadid')."/"	;

			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'],0777,true);
			}

	        $config['file_name'] = date("Ymd")."_".date("His")."_".randomstring(5)."_".randomstring(3);
	        $config['allowed_types'] = "*";
	        $config['max_size'] = "10240";
	        //$config['max_width'] = "2000";
	        //$config['max_height'] = "2000";

	        $this->load->library('upload', $config);
	        $this->upload->do_upload("userfile");
	        /*if (!$this->upload->do_upload("comprobante")) {

	            //*** ocurrio un error
	            $data['uploadError'] = $this->upload->display_errors();
	            redirect('accounts/add_adm_esp_comunes/2');
	            //return;
	        }*/
       		$dataupload = $this->upload->data();
       		$parametros = array(
       						'idpropiedad' => $this->input->post("propiedad"),
       						'idperiodo' => $this->input->post("periodo"),
       						'concepto' => $this->input->post("concepto"),
       						'fecuso' => $this->input->post("fecuso"),
       						'unidadmedida' => $this->input->post("unidadmedida"),
       						'ummonto' => $this->input->post("ummonto"),
       						'cantidadum' => $this->input->post("cantidadum"),       						
       						'monto' => str_replace(".","",$this->input->post("monto")),
       						'descripcion' => $this->input->post("descripcion"),
       						'nombrearchivo' => $dataupload['orig_name'],
       						'nombrerealarchivo' => $dataupload['client_name']
			       			);

       		$this->load->model('account');
			$this->account->add_cuenta_individual($parametros);



			$resultid = $this->session->set_flashdata('editar_adm_esp_comunes_result',7);
			redirect('accounts/editar_adm_esp_comunes');				

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




	public function autoriza_ggcc()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('autorizacion_result');
			if($resultid == 1){
				$vars['message'] = "Gasto Com&uacute;n Autorizado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al autorizar Gasto Com&uacute;n";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('payment');

			$datosperiodo = $this->payment->get_periodos_activos($this->session->userdata('comunidadid'));
			$this->load->model('account');

			//$cuentas = $this->account->get_cuentas_individuales_by_id();
			$cuentas = $this->account->get_cuentas_no_autoriza_by_id();
			$cargos = $this->account->get_cargos_gc_by_id();
			$remuneraciones = $this->account->get_cuentas_remuneraciones_by_id();
			$remuneraciones_anticipos = $this->account->get_cuentas_remuneraciones_anticipos_by_id();
			$ingresos = $this->account->get_ingresos_by_id(NULL,TRUE);
			$notas_credito = $this->account->get_nc_no_autoriza_by_id();



			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Autoriza Cuentas para Gasto Com&uacute;n');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/autoriza_ggcc';
			$vars['datosperiodo'] = $datosperiodo;
			$vars['cuentas'] = $cuentas;
			$vars['cargos'] = $cargos;
			$vars['remuneraciones'] = $remuneraciones;
			$vars['remuneraciones_anticipos'] = $remuneraciones_anticipos;
			$vars['ingresos'] = $ingresos;
			$vars['notas_credito'] = $notas_credito;

			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['icheck'] = true;
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



	public function validate_autoriza_ggcc($data = '')
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$array_elem = $this->input->post(NULL,true);
			$array_cuentas_autorizadas = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'cuenta' || $arr_el[0] == 'cargo' || $arr_el[0] == 'remuneracion' || $arr_el[0] == 'nc'){
					array_push($array_cuentas_autorizadas, $arr_el[1]);
				}
			}



			$array_elem = $this->input->post(NULL,true);
			$array_ingresos_autorizados = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'ingreso'){
					array_push($array_ingresos_autorizados, $arr_el[1]);
				}
			}


			$data = array();
			if(count($array_cuentas_autorizadas) == 0 && count($array_ingresos_autorizados) == 0){
				$data['result'] = "error";
				$data['fields']['periodo'] = "Debe autorizar al menos una cuenta y/o ingreso";	
			}else{
				$data['result'] = "ok";
			}

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

	public function submit_autoriza_ggcc()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$idperiodo = $this->input->post('periodo');

			$array_elem = $this->input->post(NULL,true);
			$array_cuentas_autorizadas = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'cuenta' || $arr_el[0] == 'cargo' || $arr_el[0] == 'remuneracion'){
					array_push($array_cuentas_autorizadas, $arr_el[1]);
				}
			}


			$array_elem = $this->input->post(NULL,true);
			$array_ingresos_autorizados = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'ingreso'){
					array_push($array_ingresos_autorizados, $arr_el[1]);
				}
			}


			$array_elem = $this->input->post(NULL,true);
			$array_nc_autorizados = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'nc'){
					array_push($array_nc_autorizados, $arr_el[1]);
				}
			}


			$this->load->model('account');

			$autorizacion = $this->account->autoriza_ggcc($idperiodo,$array_cuentas_autorizadas,$array_ingresos_autorizados,$array_nc_autorizados);

			if($autorizacion){
				$this->session->set_flashdata('autorizacion_result', 1);
			}else{
				$this->session->set_flashdata('autorizacion_result', 2);
			}

			redirect('accounts/autoriza_ggcc');	

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



	public function desautoriza_cuenta($tipo,$ggccid,$idcuenta)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');


			$desautoriza = $tipo == 'cuenta' ? $this->account->desautoriza_cuenta($ggccid,$idcuenta) : $this->account->desautoriza_ingreso($ggccid,$idcuenta);

			if($desautoriza){
				$this->session->set_flashdata('desautorizacion_result', 1);
			}else{
				$this->session->set_flashdata('desautorizacion_result', 2);
			}
			redirect('reports/ver_detalle_periodo/'.$ggccid);	

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



	public function pagos_cuentas($tipo_cuentas = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('pagos_cuentas_result');
			if($resultid == 1){
				$vars['message'] = "Cuentas Abonadas/Pagadas Correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al Pagar Cuentas";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}else if($resultid == 3){
				$vars['message'] = "Cuenta Activada/Desactivada Correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al Activar/Desactivar Cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$vars['message'] = "Comprobante cargado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 6){
				$vars['message'] = "Error al subir comprobante.  Cuenta no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('admin');
			$datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));

			$formas_pago = $this->admin->get_forma_pago('pago');
			$bancos = $this->admin->get_banco();

			$this->load->model('account');

			$consulta_cuentas = is_null($tipo_cuentas) ? 'activos' : $tipo_cuentas;
			$cuentas = $this->account->get_cuentas_impagas_by_id(null,$consulta_cuentas);

			$notas_credito = $this->account->get_notas_credito();


			$deuda = $this->account->get_saldo_cuentas_impagas_by_id()->saldo;


			$this->load->model('payment');
			$saldo_disponible = $this->payment->get_saldo_disponible_by_comunidad($this->session->userdata('comunidadid'));
			$upload = $this->session->userdata('level') == 1 ? true : false;

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Pagos de Cuentas');

			if($tipo_cuentas == 'todos'){
				$title_button = 'Mostrar Todos';
			}else if($tipo_cuentas == 'inactivos'){
				$title_button = 'Mostrar Inactivos';
			}else{
				$title_button = 'Mostrar Activos';
			}

			$vars['classinfo_saldo'] = $deuda > 0 ? 'bg-red' : 'bg-green';
			$vars['classinfo_caja'] = $saldo_disponible > 0 ? 'bg-green' : 'bg-red';
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/pagos_cuentas';
			$vars['datoscomunidad'] = $datoscomunidad;
			$vars['cuentas'] = $cuentas;
			$vars['notas_credito'] = $notas_credito;
			$vars['formas_pago'] = $formas_pago;
			$vars['bancos'] = $bancos;
			$vars['saldo_disponible'] = $saldo_disponible;
			$vars['deuda_comunidad'] = $deuda;
			$vars['title_button'] = $title_button;
			$vars['tipo_cuentas'] = is_null($tipo_cuentas) ? '' : $tipo_cuentas;
			$vars['upload'] = $upload;
			$vars['origen'] = 'pagos_cuentas';


			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['icheck'] = true;
			$vars['mask'] = true;

			$vars['datetimepicker'] = true;
			$vars['jqueryRut'] = true;			
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


	public function submit_pagos_cuentas()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$array_elem = $this->input->post(NULL,true);

			$array_cuenta_pago = array();
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				$monto_abono = 0;
				if($arr_el[0] == 'cuenta'){
					if($value_elem == 'total'){
						$monto_abono = $this->input->post('saldo-'.$arr_el[1]);
					}else if($value_elem == 'abono'){
						$monto_abono = str_replace(".","",$this->input->post('monto_abono-'.$arr_el[1]));
					}else{
						$monto_abono = 0;
					}

					if($monto_abono > 0){
						array_push($array_cuenta_pago, array(
															'id' => $arr_el[1],
															'monto_abono' => $monto_abono
														));
					}
				}


				if($arr_el[0] == 'selnc'){
					//$monto_abono = $this->input->post('saldonc-'.$value_elem);
					$monto_abono = $this->input->post('saldonc-'.$arr_el[1]);
					array_push($array_cuenta_pago, array(
														'id' => $value_elem,
														'monto_abono' => $monto_abono
													));

				}
			}


   		$ruttitular = str_replace(".","",$this->input->post("ruttitular"));
		$arrayRut = explode("-",$ruttitular);

   		$parametros = array(
   						'fechapago' => $this->input->post("fechapago"),
   						'idformapago' => $this->input->post("formas_pago"),
   						'cheque' => $this->input->post("cheque"),
   						'paguesea' => $this->input->post("paguesea"),
		       			);
				
			$this->load->model('account');
			$propiedades = $this->account->pago_cuenta($array_cuenta_pago,$parametros);
			$this->session->set_flashdata('pagos_cuentas_result',1);
			redirect('accounts/pagos_cuentas');	

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


	public function editar_cuenta()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('editar_cuenta_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Cuenta Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al editar/eliminar cuenta.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$vars['message'] = "Error al editar/eliminar cuenta.  Ya se han realizado abonos";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 6){
				$vars['message'] = "Error al editar/eliminar cuenta.  Cuenta desactivada";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}else if($resultid == 7){
				$vars['message'] = "Cuenta Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 8){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 9){
				$vars['message'] = "Error al crear cuenta individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 10){
				$vars['message'] = "Error al crear cuenta individual.  Medici&oacute;n sin cambios. Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 11){
				$vars['message'] = "Lectura no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}elseif($resultid == 12){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 13){
				$vars['message'] = "Lectura Individual Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 14){
				$vars['message'] = "Error al eliminar Lectura individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 15){
				$vars['message'] = "Error al editar Lectura individual.  Debe indicar cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 16){
				$vars['message'] = "Lectura Individual Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}elseif($resultid == 17){
				$vars['message'] = "Error al editar cuenta.  Debe indicar cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 18){
				$vars['message'] = "Error al eliminar cuenta.  Debe indicar cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}



			$this->load->model('account');

			// no se consideran aun los cobros individuales
			$cuentas = $this->account->get_cuentas_impagas_no_autorizada_by_id(null,false,false);
			$lecturas_individuales = $this->account->get_lecturas_individuales_by_id();
			$cuentas_cuotas = $this->account->get_cuentas_cuotas_by_id();

			//print_r($cuentas_cuotas); exit;
			

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar/Editar Cuentas');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/editar_cuenta';
			$vars['cuentas'] = $cuentas;
			$vars['cuentas_cuotas'] = $cuentas_cuotas;
			$vars['lecturas_individuales'] = $lecturas_individuales;

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


	public function honorarios_condominio()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('editar_cuenta_honorarios_result');
			if($resultid == 1){
				$vars['message'] = "Boleta Honorarios Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Boleta Honorarios Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al editar/eliminar Boleta Honorarios.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$vars['message'] = "Error al editar/eliminar Boleta Honorarios.  Ya se han realizado abonos";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 6){
				$vars['message'] = "Error al editar/eliminar Boleta Honorarios.  Boleta desactivada";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}else if($resultid == 7){
				$vars['message'] = "Boleta Honorarios Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 8){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 9){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 10){
				$vars['message'] = "Error al editar.  Debe indicar Boleta Honorarios";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('account');

			// no se consideran aun los cobros individuales
			$cuentas = $this->account->get_cuentas_impagas_no_autorizada_by_id(null,true);

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar/Editar Boletas Honorarios');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/editar_honorarios';
			$vars['cuentas'] = $cuentas;

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


	public function add_honorarios_condominio()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('cuenta_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Error al crear cuenta individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 4){
				$vars['message'] = "Error al crear cuenta individual.  Medici&oacute;n sin cambios. Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('admin');

			$proveedores = $this->admin->get_proveedor_comunidad_by_id();
			$tipodoc = $this->admin->get_tipodoc_tributario_by_id();
			$conceptos = $this->admin->get_tipos_cuentas_comunidad_by_id();


			$this->load->model('account');

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar Boleta Honorarios');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/add_honorarios_condominio';
			$vars['proveedores'] = $proveedores;
			$vars['conceptos'] = $conceptos;

			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['maleta'] = true;		
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



	public function edit_honorarios_condominio($idcuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			if(is_null($idcuenta)){
				$this->session->set_flashdata('editar_cuenta_honorarios_result', 10);
				redirect('accounts/honorarios_condominio');
			}


			$this->load->model('account');
			$cuentas = $this->account->get_cuentas_by_id($idcuenta,true);

			//$vars['existe'] = count($cuentas) > 0 ? true : false;
			$vars['existe'] = !is_null($cuentas) ? true : false;

			if(!$vars['existe']){ //  no se consideran aun los cobros individuales
				$this->session->set_flashdata('editar_cuenta_honorarios_result', 4);
				redirect('accounts/honorarios_condominio');
			}

			if($cuentas->abonado > 0){
				$this->session->set_flashdata('editar_cuenta_honorarios_result', 5);
				redirect('accounts/honorarios_condominio');
			}



			if($cuentas->active == 0){
				$this->session->set_flashdata('editar_cuenta_honorarios_result', 6);
				redirect('accounts/honorarios_condominio');
			}


			$datos_form = array(
							'formapago' => is_null($cuentas) ? 0 : $cuentas->formapago,
							'proveedor' => is_null($cuentas) ? 0 : $cuentas->idproveedor,
							'tipodoc' => is_null($cuentas) ? '' : $cuentas->idtipodoctotrib,
							'documento' => is_null($cuentas) ? '' : $cuentas->nrodocumento,
							'tiporetencion' => is_null($cuentas) ? '' : $cuentas->tiporetencion,
							'fecdocumento' => is_null($cuentas) ? '' : $cuentas->fecdocumento,
							'concepto' => is_null($cuentas) ? '' : $cuentas->idconcepto,
							'monto' => is_null($cuentas) ? '' : $cuentas->monto,
							'retencion' => is_null($cuentas) ? '' : $cuentas->retencion,
							'abonado' => is_null($cuentas) ? '' : $cuentas->abonado,
							'fecvencimiento' => is_null($cuentas) ? '' : $cuentas->fecvencimiento,
							'descripcion' => is_null($cuentas) ? '' : $cuentas->descripcion,
							'nombrearchivo' => is_null($cuentas) ? '' : $cuentas->nombrearchivo,
							'idcuenta' => is_null($cuentas) ? '' : $cuentas->id,
							'idretencion' => is_null($cuentas) ? '' : $cuentas->idretencion,
							);

			

			$datos_form['bruto'] = $datos_form['monto'] + $datos_form['retencion'];
			$datos_form['idretencion'] = is_null($datos_form['idretencion']) ? 0 : $datos_form['idretencion'];

			$this->load->model('admin');

			$proveedores = $this->admin->get_proveedor_comunidad_by_id();
			$tipodoc = $this->admin->get_tipodoc_tributario_by_id();
			$conceptos = $this->admin->get_tipos_cuentas_comunidad_by_id();

			$this->load->model('account');

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Editar Boleta de Honorarios');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/edit_honorarios_condominio';
			$vars['proveedores'] = $proveedores;
			$vars['tipodoc'] = $tipodoc;
			$vars['conceptos'] = $conceptos;
			$vars['datos_form'] = $datos_form;


			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['mask'] = true;
			$vars['maleta'] = true;		
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


	public function editar_otros_cargos()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('editar_otros_cargos_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Cuenta Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar cuenta.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$vars['message'] = "Error al editar/eliminar cuenta.  Ya se han realizado abonos";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 6){
				$vars['message'] = "Error al editar/eliminar cuenta.  Cuenta desactivada";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 7){
				$vars['message'] = "Cargo Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 8){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}


			$this->load->model('account');


			$cargos = $this->account->get_cargos_by_id();

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar/Editar Otros Cargos');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/editar_otros_cargos';
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


	public function delete_cuenta_cuotas($idcuentacuotas = null){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if(is_null($idcuentacuotas)){
				redirect('main/dashboard');
			}

			$this->load->model('account');

			if(is_null($idcuentacuotas)){
				$this->session->set_flashdata('editar_cuenta_result', 18);
				redirect('accounts/editar_cuenta');
			}			

			$cuentacuotas = $this->account->get_cuentas_cuotas_by_id($idcuentacuotas);
			if(is_null($cuentacuotas)){
				$this->session->set_flashdata('editar_cuenta_result', 4);
				redirect('accounts/editar_cuenta');
			}else{

				$result = $this->account->delete_cuenta_cuotas($idcuentacuotas);
				if($result){
					$this->session->set_flashdata('editar_cuenta_result', 3);
					redirect('accounts/editar_cuenta');				
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



	public function edit_cuenta_cuotas($idcuentacuotas){

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');

			if(is_null($idcuentacuotas)){
				$this->session->set_flashdata('editar_cuenta_result', 17);
				redirect('accounts/editar_cuenta');
			}			


			$cuentacuotas = $this->account->get_cuentas_cuotas_by_id($idcuentacuotas);
			//var_dump($cuentacuotas); exit;
			if(is_null($cuentacuotas)){
				$this->session->set_flashdata('editar_cuenta_result', 4);
				redirect('accounts/editar_cuenta');
			}

			//$result = $this->account->delete_cuenta_cuotas($idcuentacuotas);

			//$vars['existe'] = count($cuentacuotas) > 0 ? true : false;
			$vars['existe'] = !is_null($cuentacuotas) ? true : false;


			$datos_form = array(
						'formapago' => is_null($cuentacuotas) ? 0 : $cuentacuotas->formapago,
						'proveedor' => is_null($cuentacuotas) ? 0 : $cuentacuotas->idproveedor,
						'tipodoc' => is_null($cuentacuotas) ? '' : $cuentacuotas->idtipodoctotrib,
						'documento' => is_null($cuentacuotas) ? '' : $cuentacuotas->nrodocumento,
						'fecdocumento' => is_null($cuentacuotas) ? '' : $cuentacuotas->fecdocumento,
						'concepto' => is_null($cuentacuotas) ? '' : $cuentacuotas->idconcepto,
						'monto' => is_null($cuentacuotas) ? '' : $cuentacuotas->monto,
						'fecvencimiento' => is_null($cuentacuotas) ? '' : $cuentacuotas->fecvencimiento,
						'descripcion' => is_null($cuentacuotas) ? '' : $cuentacuotas->descripcion,
						'nombrearchivo' => is_null($cuentacuotas) ? '' : $cuentacuotas->nombrearchivo,
						'idcuenta' => is_null($cuentacuotas) ? '' : $cuentacuotas->id,
						'numcuotas' => is_null($cuentacuotas) ? '' : $cuentacuotas->numcuotas,
						);


			$this->load->model('admin');

			$proveedores = $this->admin->get_proveedor_comunidad_by_id();
			$tipodoc = $this->admin->get_tipodoc_tributario_by_id();
			$conceptos = $this->admin->get_tipos_cuentas_comunidad_by_id();

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Editar Cuenta');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/edit_cuenta_cuotas';
			$vars['proveedores'] = $proveedores;
			$vars['tipodoc'] = $tipodoc;
			$vars['conceptos'] = $conceptos;
			$vars['datos_form'] = $datos_form;


			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
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


	public function delete_cuenta($idcuenta,$tipocuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			
			$this->load->model('account');
			if(is_null($idcuenta)){
				redirect('main/dashboard');
			}

			$cuentas = $tipocuenta == 'c' ? $this->account->get_cargos_by_id($idcuenta) : $this->account->get_cuentas_by_id($idcuenta);


			if(is_null($cuentas) || !isset($cuentas->abonado)){
				$this->session->set_flashdata('editar_cuenta_result', 4);
				redirect('accounts/editar_cuenta');
			}

			if(is_null($tipocuenta)){
				if($cuentas->idtipodoctotrib == 15){ #NO SE PUEDEN ELIMINAR BOLETAS DE HONORARIOS MEDIANTE ESTA OPCIÓN
					$this->session->set_flashdata('editar_cuenta_result', 4);
					redirect('accounts/editar_cuenta');
				}
			}

			if($cuentas->abonado > 0){
				$this->session->set_flashdata('editar_cuenta_result', 5);
				redirect('accounts/editar_cuenta');
			}

       		
			$result = $this->account->delete_cuenta($idcuenta,$tipocuenta);


			if($result){
				if($tipocuenta == 'c'){
					$this->session->set_flashdata('editar_otros_cargos_result', 3);
					redirect('accounts/editar_otros_cargos');		
				}else{
					$this->session->set_flashdata('editar_cuenta_result', 3);
					redirect('accounts/editar_cuenta');		
				}

			}else{
				if($tipocuenta == 'c'){
					$this->session->set_flashdata('editar_otros_cargos_result', 4);
					redirect('accounts/editar_otros_cargos');		
				}else{
					$this->session->set_flashdata('editar_cuenta_result', 4);
					redirect('accounts/editar_cuenta');					
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



	public function delete_honorarios_condominio($idcuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			if(is_null($idcuenta)){
				redirect('main/dashboard');
			}


			$this->load->model('account');

			$cuentas = $this->account->get_cuentas_by_id($idcuenta,true);

			if(is_null($cuentas) || !isset($cuentas->abonado)){
				$this->session->set_flashdata('editar_cuenta_honorarios_result', 4);
				redirect('accounts/honorarios_condominio');
			}


			if($cuentas->abonado > 0 || $cuentas->abonado_retencion > 0){
				$this->session->set_flashdata('editar_cuenta_honorarios_result', 5);
				redirect('accounts/honorarios_condominio');
			}


			$result2 = true;
			if(!is_null($cuentas->idretencion)){
				$result2  = $this->account->delete_cuenta_retencion($cuentas->idretencion);
			}

			$result = $this->account->delete_cuenta($idcuenta,null,true);

			


			if($result && $result2){
					$this->session->set_flashdata('editar_cuenta_honorarios_result', 3);
					redirect('accounts/honorarios_condominio');		
			}else{
					$this->session->set_flashdata('editar_cuenta_honorarios_result', 4);
					redirect('accounts/honorarios_condominio');					
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




	public function delete_ingreso($idingreso = null) 
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			if(is_null($idingreso)){
				redirect('main/dashboard');
			}

			$this->load->model('account');
			$ingresos = $this->account->get_ingresos_by_id($idingreso,null,null,true);
			if(is_null($ingresos)){
				$this->session->set_flashdata('editar_ingreso_result', 4);
				redirect('accounts/editar_ingresos');
			}


       		
			$result = $this->account->delete_ingreso($idingreso);

			if($result){
				$this->session->set_flashdata('editar_ingreso_result', 3);
				redirect('accounts/editar_ingresos');
			}else{
				$this->session->set_flashdata('editar_ingreso_result', 4);
				redirect('accounts/editar_ingresos');
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



	public function editar_ingresos()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('editar_ingreso_result');
			if($resultid == 1){
				$vars['message'] = "Ingreso Editado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Ingreso Eliminado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al editar/eliminar ingreso.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$vars['message'] = "Error al editar/eliminar ingreso.  Ingreso ya autorizado";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 6){
				$vars['message'] = "Ingreso Agregado correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 7){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 8){
				$vars['message'] = "Error al crear cuenta individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}

			$this->load->model('account');

			$ingresos = $this->account->get_ingresos_by_id(null,null,null,true);

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Editar Ingresos');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/editar_ingresos';
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

	public function edit_ingreso($idingreso)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$ingreso = $this->account->get_ingresos_by_id($idingreso);
			//$vars['existe'] = count($ingreso) > 0 ? true : false;
			$vars['existe'] = !is_null($ingreso) ? true : false;

			if(!$vars['existe']){ 
				$this->session->set_flashdata('editar_ingreso_result', 4);
				redirect('accounts/editar_ingresos');
			}

			$datos_form = array(
							'proveedor' => is_null($ingreso) ? 0 : $ingreso->idproveedor,
							'tipodoc' => is_null($ingreso) ? '' : $ingreso->idtipodoctotrib,
							'documento' => is_null($ingreso) ? '' : $ingreso->nrodocumento,
							'fecdocumento' => is_null($ingreso) ? '' : $ingreso->fecdocumento,
							'concepto' => is_null($ingreso) ? '' : $ingreso->idconcepto,
							'monto' => is_null($ingreso) ? '' : $ingreso->monto,
							'fecvencimiento' => is_null($ingreso) ? '' : $ingreso->fecvencimiento,
							'descripcion' => is_null($ingreso) ? '' : $ingreso->descripcion,
							'nombrearchivo' => is_null($ingreso) ? '' : $ingreso->nombrearchivo,
							'idingreso' => is_null($ingreso) ? '' : $ingreso->id,
							'tipoingreso' => is_null($ingreso) ? '' : $ingreso->tipoingreso,
							'habilitagasto' => is_null($ingreso) ? '' : $ingreso->habilitagasto,
							);

			$this->load->model('admin');

			$proveedores = $this->admin->get_proveedor_comunidad_by_id();
			$tipodoc = $this->admin->get_tipodoc_tributario_by_id();
			//$conceptos = $this->admin->get_tipos_cuentas_comunidad_by_id();
			$conceptos = $this->admin->get_ingresos_comunidad_by_id();
			$this->load->model('account');

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Editar Ingreso');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/edit_ingreso_comunidad';
			$vars['proveedores'] = $proveedores;
			$vars['tipodoc'] = $tipodoc;
			$vars['conceptos'] = $conceptos;
			$vars['datos_form'] = $datos_form;


			$vars['datetimepicker'] = true;
			$vars['dataTables'] = true;
			$vars['formValidation'] = true;
			$vars['mask'] = true;
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

	public function delete_cuenta_individual($idcuenta = null,$tipocuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			if(is_null($idcuenta)){
				redirect('main/dashboard');
			}

       		$this->load->model('account');
			$result = $this->account->delete_cuenta_individual($idcuenta);

			if($result){
				if($tipocuenta == 'ec'){
					$this->session->set_flashdata('editar_adm_esp_comunes_result', 3);
					redirect('accounts/editar_adm_esp_comunes');		
				}else{
					$this->session->set_flashdata('editar_individual_result', 3);
					redirect('accounts/editar_individual');					
				}

			}else{
				if($tipocuenta == 'ec'){
					$this->session->set_flashdata('editar_adm_esp_comunes_result', 4);
					redirect('accounts/editar_adm_esp_comunes');		
				}else{
					$this->session->set_flashdata('editar_individual_result', 4);
					redirect('accounts/editar_individual');					
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


	public function delete_cobro_individual($idcuenta = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			if(is_null($idcuenta)){
				redirect('main/dashboard');
			}

       		$this->load->model('account');
			$result = $this->account->delete_cobro_individual($idcuenta);
			if($result){
				$this->session->set_flashdata('editar_cuenta_result', 13);
				redirect('accounts/editar_cuenta');
			}else{
				$this->session->set_flashdata('editar_cuenta_result', 14);
				redirect('accounts/editar_cuenta');				
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



	public function editar_individual()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('editar_individual_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta Individual Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Cuenta Individual Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar cuenta individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$ctas_eliminadas = $this->session->flashdata('editar_individual_cantidad_result');
				if($ctas_eliminadas > 0){
					$vars['message'] = "Se han eliminado " . $ctas_eliminadas . " cuentas individuales correctamente";
					$vars['classmessage'] = 'success';
					$vars['icon'] = 'fa-check';				
				}else{
					$vars['message'] = "No se han eliminado cuentas individuales.  Favor intentar nuevamente";
					$vars['classmessage'] = 'danger';
					$vars['icon'] = 'fa-ban';					
				}
			}elseif($resultid == 6){
				$ctas_eliminadas = $this->session->flashdata('editar_individual_cantidad_result');
				if($ctas_eliminadas > 0){
					$vars['message'] = "Se han eliminado " . $ctas_eliminadas . " cuentas de espacios comunes correctamente";
					$vars['classmessage'] = 'success';
					$vars['icon'] = 'fa-check';				
				}else{
					$vars['message'] = "No se han eliminado cuentas de espacios comunes.  Favor intentar nuevamente";
					$vars['classmessage'] = 'danger';
					$vars['icon'] = 'fa-ban';					
				}
			}else if($resultid == 7){ 
				$vars['message'] = "Cuenta Individual Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 8){ 
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 9){ 
				$vars['message'] = "Error al editar cuenta individual.  Debe indicar cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 10){ 
				$vars['message'] = "Error al editar cuenta individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}



			$this->load->model('account');

			$cuentas_individuales = $this->account->get_cuentas_individuales_by_id();



			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar/Editar Cuentas Individuales');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/editar_individual';
			$vars['cuentas_individuales'] = $cuentas_individuales;

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




	public function editar_adm_esp_comunes()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('editar_adm_esp_comunes_result');
			if($resultid == 1){
				$vars['message'] = "Cuenta de espacios comunes Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Cuenta de espacios comunes Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar cuenta de espacios comunes.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$ctas_eliminadas = $this->session->flashdata('editar_individual_cantidad_result');
				if($ctas_eliminadas > 0){
					$vars['message'] = "Se han eliminado " . $ctas_eliminadas . " cuentas individuales correctamente";
					$vars['classmessage'] = 'success';
					$vars['icon'] = 'fa-check';				
				}else{
					$vars['message'] = "No se han eliminado cuentas individuales.  Favor intentar nuevamente";
					$vars['classmessage'] = 'danger';
					$vars['icon'] = 'fa-ban';					
				}
			}elseif($resultid == 6){
				$ctas_eliminadas = $this->session->flashdata('editar_adm_esp_comunes_cantidad_result');
				if($ctas_eliminadas > 0){
					$vars['message'] = "Se han eliminado " . $ctas_eliminadas . " cuentas de espacios comunes correctamente";
					$vars['classmessage'] = 'success';
					$vars['icon'] = 'fa-check';				
				}else{
					$vars['message'] = "No se han eliminado cuentas de espacios comunes.  Favor intentar nuevamente";
					$vars['classmessage'] = 'danger';
					$vars['icon'] = 'fa-ban';					
				}
			}elseif($resultid == 7){
				$vars['message'] = "Cuenta Administraci&oacute;n de Espacios Comunes Agregada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 8){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}

			$this->load->model('account');

			$cuentas_espacios_comunes = $this->account->get_cuentas_espacios_comunes_by_id();


			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Agregar/Editar Cuentas Espacios Comunes');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/editar_adm_esp_comunes';
			$vars['cuentas_espacios_comunes'] = $cuentas_espacios_comunes;

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



	public function editar_lectura_individual()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
			$resultid = $this->session->flashdata('editar_lectura_individual_result');
			if($resultid == 1){
				$vars['message'] = "Lectura no existe";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';				
			}elseif($resultid == 2){
				$vars['message'] = "Error al cargar comprobante.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 3){
				$vars['message'] = "Lectura Individual Eliminada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';				
			}elseif($resultid == 4){
				$vars['message'] = "Error al eliminar Lectura individual.  Favor intentar nuevamente";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 5){
				$vars['message'] = "Error al editar Lectura individual.  Debe indicar cuenta";
				$vars['classmessage'] = 'danger';
				$vars['icon'] = 'fa-ban';
			}elseif($resultid == 6){
				$vars['message'] = "Lectura Individual Editada correctamente";
				$vars['classmessage'] = 'success';
				$vars['icon'] = 'fa-check';		
			}


			$this->load->model('account');


			$cuentas = $this->account->get_lecturas_individuales_by_id();

			$content = array(
						'menu' => 'Cuentas',
						'title' => 'Cuentas',
						'subtitle' => 'Editar Cuentas por Lectura Individual');

			
			$vars['content_menu'] = $content;				
			$vars['content_view'] = 'accounts/editar_lectura_individual';
			$vars['cuentas'] = $cuentas;

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




	public function download_egreso($idegreso)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$this->load->model('account');
			$datosegreso = $this->account->get_datos_egreso($idegreso);		
			

			if(!is_null($datosegreso)){
				$datosdetalle = $this->account->generar_egreso($idegreso);						
			}else{
				redirect('main/dashboard');	 
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

	public function cuenta_servicios_basicos(){
		$this->load->model('account');
		$es_servicio_basico = $this->account->es_servicio_basico($this->input->post('concepto'));
		echo json_encode(array(
		    'valid' => !$es_servicio_basico && $this->input->post('formapago') == 'ci' ? false : true
		));
	}	



	public function put_baja_activo_fijo($idcuenta,$status){
		$this->load->model('account');
		return $this->account->put_baja_activo_fijo($idcuenta,$status);
	}	




	public function get_cobros_periodo($idperiodo){
	 	$this->session->keep_flashdata('cuenta');
		$this->load->model('account');
		$cuentas = $this->account->get_lecturas_individuales_by_periodo($idperiodo);
		echo json_encode($cuentas);
	}


	public function get_lectura_cuenta($idcuenta){
	 	$this->session->keep_flashdata('cuenta');
		$this->load->model('account');
		$lectura = $this->account->get_detalle_lectura_by_cuenta($idcuenta);
		echo json_encode($lectura);
	}	



	public function desactiva_cuenta($idcuenta,$tipo_cuentas = null)
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
				
			$this->load->model('account');
			$result = $this->account->desactiva_cuenta($idcuenta);

			if($result){
				$this->session->set_flashdata('pagos_cuentas_result',3);
			}else{
				$this->session->set_flashdata('pagos_cuentas_result',4);
			}
			
			redirect('accounts/pagos_cuentas/'.$tipo_cuentas);	
		
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



	public function delete_cuenta_individual_massive()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$array_elem = $this->input->post(NULL,true);

			$this->load->model('account');
			$ctas_eliminadas = 0;
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'cindividual'){
					$result = $this->account->delete_cuenta_individual($arr_el[1]);
					if($result){
						$ctas_eliminadas++;
					}
				}
			}


			$this->session->set_flashdata('editar_individual_result', 5);
			$this->session->set_flashdata('editar_individual_cantidad_result', $ctas_eliminadas);
			redirect('accounts/editar_individual');

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


	public function delete_adm_esp_comunes_massive()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){

			$array_elem = $this->input->post(NULL,true);

			$this->load->model('account');
			$ctas_eliminadas = 0;
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'cadmespcomunes'){
					$result = $this->account->delete_cuenta_individual($arr_el[1]);
					if($result){
						$ctas_eliminadas++;
					}
				}
			}


			$this->session->set_flashdata('editar_adm_esp_comunes_result', 6);
			$this->session->set_flashdata('editar_adm_esp_comunes_cantidad_result', $ctas_eliminadas);
			redirect('accounts/editar_adm_esp_comunes');

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




	public function desautoriza_cuenta_masivo()
	{

		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){


			$ggccid = $this->input->post('ggccid');
			$array_elem = $this->input->post(NULL,true);

			$this->load->model('account');
			$ctas_desautorizadas = 0;
			foreach($array_elem as $elem => $value_elem){
				$arr_el = explode("-",$elem);
				if($arr_el[0] == 'descuenta'){

					if(isset($arr_el[1]) && isset($arr_el[2])){ //verificamos que vengan todos los datos
						$tipo = $arr_el[1];
						$idcuenta = $arr_el[2];

						$desautoriza = $tipo == 'cuenta' ? $this->account->desautoriza_cuenta($ggccid,$idcuenta) : $this->account->desautoriza_ingreso($ggccid,$idcuenta);

						if($desautoriza){
							$ctas_desautorizadas++;
						}
					}
				}
			}


			$this->session->set_flashdata('desautorizacion_result', 5);
			$this->session->set_flashdata('desautorizacion_cantidad_result', $ctas_desautorizadas);

			redirect('reports/ver_detalle_periodo/'.$ggccid);	

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
