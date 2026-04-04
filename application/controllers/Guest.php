<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guest extends CI_Controller {


	function __construct(){
	  parent::__construct();
	  $this->load->library('ion_auth');

      
   }


	/*public function add_comunidad($idcomunidad = 0)
	{

		if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

			$this->load->model('admin');
			$comunidad = $this->admin->get_comunidades($idcomunidad);
			$regiones = $this->admin->get_regiones();

			$content = array(
						'menu' => 'Administraci&oacute;n',
						'title' => 'Administraci&oacute;n',
						'subtitle' => 'Administraci&oacute;n de Comunidades');
			
			$vars['content_menu'] = $content;				
			$vars['regiones'] = $regiones;
			$vars['content_view'] = 'guest/add_comunidad';
			$vars['titulo'] = "Registra tu comunidad y prueba nuestro servicio por 2 meses";
			$vars['formValidation'] = true;
			$vars['jqueryRut'] = true;
			$vars['mask'] = true;
			$vars['datetimepicker'] = true;
			
			
			$template = "template_guest";
			

			$this->load->view($template,$vars);	
		} else {
            redirect('auth/login', 'refresh');
        }

	}*/


	public function get_comunas($idregion){

		$this->load->model('admin');
		$comunas = $this->admin->get_comunas_by_region($idregion);
		//$arrayComunas = array();
		//$arrayComunas[''] = "Seleccione Comuna";
		//foreach ($comunas as $comuna) {
		//	$arrayComunas[$comuna->idcomuna] = $comuna->nombre;
		//}
		echo json_encode($comunas);
		//echo form_dropdown('comuna',$arrayComunas ,'',"class='form-control' id='comuna'"); 

	}	



/*public function submit_comunidades(){

		if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

			//$nuevo_proveedor = $this->input->post('proveedor');
			$comunidad = $this->input->post('comunidad');
			$direccion = $this->input->post('direccion');	
			$idregion = $this->input->post('region');	
			$idcomuna = $this->input->post('comuna');	
			$fono = $this->input->post('fono');	
			$fono2 = $this->input->post('fono2');	
			$email = $this->input->post('email');	
			$saldo = 0;	
			$caja = 0;	
			$fondoreserva = 0;	
			$fecinicio = date("d/m/Y");

			//VER SI SE DEFINE UN DIA DEL MES ESPECÍFICO PARA EL VENCIMIENTO, O DIAS CORRIDOS DESDE INICIO
			$fecvencimiento = date('Y-m-d', strtotime('+' . PERIODOS_GRATIS . ' month', strtotime(date("Y-m-d"))));

			$idcomunidad = $this->input->post('idcomunidad');

			$idcomunidad = $idcomunidad == NULL ? 0 : $idcomunidad;
			$nombre_responsable = $this->input->post('nombre');
			$apellido_responsable = $this->input->post('apellido');			


       		$ruttitular = str_replace(".","",$this->input->post("rutcomunidad"));
			$arrayRut = explode("-",$ruttitular);


			$array_datos = array(
								'comunidad' => $comunidad,
	       						'rut' => $arrayRut[0],
	       						'dv' => $arrayRut[1],
								'direccion' => $direccion,
								'idregion' => $idregion,
								'idcomuna' => $idcomuna,
								'fono' => $fono,
								'fono2' => $fono2,
								'email' => $email,
								'saldo' => $saldo,
								'caja' => $caja,
								'fondoreserva' => $fondoreserva,
								'fecinicio' => $fecinicio,
								'fecvencimiento' => $fecvencimiento,
								'idcomunidad' => $idcomunidad);

			$this->load->model('admin');
			$this->load->model('ion_auth_model');
			//var_dump($array_datos);
			$idcomunidad = $this->admin->add_comunidad($array_datos);

			//var_dump($idcomunidad); 
			$array_comunidades = array($idcomunidad);
			//var_dump($array_comunidades);  exit;
			$usuario_mail = $this->admin->valida_existe_mail_user($email);

			if(!$usuario_mail){ // si no existe se crea

					//creacion de password
					$password = randomstring_mm(10);

					$additional_data = array(
									'first_name' => $nombre_responsable,
									'last_name'  => $apellido_responsable,
									'company'    => '',
									'phone'      => '',
									//'inicpass'   => $password
								);

					//$this->load->model('admin');
					$userid = $this->ion_auth->register($email, $password, $email, $additional_data);// creacion de usuario
					//echo "usuario creado: ".$userid."<br>";
					$result = $this->ion_auth->update_level($userid,1); //actualiza perfil

					//$this->ion_auth->asocia_propiedad($userid,$array_propiedades,false);
					
					$this->ion_auth->asocia_comunidad($userid,$array_comunidades);

					$this->admin->mail_creacion_usuario($userid,$password);		


					$this->session->set_flashdata('message', 'Se han enviado los datos de acceso al mail indicado en el registro');
					redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the 


			}else{// si ya existe se asocia
				$replace = false;
				if($usuario_mail->active == 0){
					$replace = true;
					$password = randomstring_mm(10);

					$additional_data = array(
									'first_name' => $nombre_responsable,
									'last_name'  => $apellido_responsable,
									'company'    => '',
									'phone'      => '',
									'inicpass'   => $password
								);									
					$this->ion_auth->update($usuario_mail->id, $additional_data);
					$result = $this->ion_auth->update_level($usuario_mail->id,1);
					$this->ion_auth->update_password($usuario_mail->id, $password); 
					$this->ion_auth->activate($usuario_mail->id);	

					// envio de mail
					$this->admin->mail_creacion_usuario($usuario_mail->id,$password);	
					$this->ion_auth->asocia_comunidad($usuario_mail->id,$array_comunidades,false);							
					$this->session->set_flashdata('message', 'Se han enviado los datos de acceso al mail indicado en el registro');
					redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the 


				}else{
					//var_dump($usuario_mail->id);
					//var_dump($array_comunidades); exit;
					if($idcomunidad != -1){
						$this->ion_auth->asocia_comunidad($usuario_mail->id,$array_comunidades,false);
						$this->session->set_flashdata('message', 'Email indicado ya existe.  Se asoció la comunidad registrada anteriormente');
						redirect("auth/login", 'refresh'); 
					}else{
						$this->session->set_flashdata('message', 'Comunidad Ingresada ya existe');
						redirect("auth/login", 'refresh'); 
					}
						
					//we should display a confirmation page here instead of the 

				}
				//print_r($array_propiedades);
				//echo "usuario asociado: ".$usuario_mail->id."<br>";
				
			}			

			if($result == -1){
				$this->session->set_flashdata('admin_comunidades_result', 2);
			}else{
				if($idcomunidad == 0){
					$this->session->set_flashdata('admin_comunidades_result', 1);
				}else{
					$this->session->set_flashdata('admin_comunidades_result', 3);
						
				}
			}

		} else {
            redirect('auth/login', 'refresh');
        }


	}	*/



	public function enviar_comunicados_pendientes()
	{
			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$result = $this->admin->enviar_comunicados_pendientes();


			exit;
	
	}	



	public function enviar_notificaciones_pendientes()
	{
			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$result = $this->admin->enviar_notificaciones_pendientes();


			exit;
	
	}	


	public function enviar_aviso_vencimiento()
	{
			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$result = $this->admin->enviar_aviso_vencimiento();


			exit;
	
	}	


	public function enviar_aviso_pago_webpay()
	{


			/*$fecvencimientoactual = '2019-06-31';
			$fecvencimientonuevo = date('Y-m-d', strtotime('first day of + 1 month', strtotime($fecvencimientoactual)));
			echo $fecvencimientoactual." -- ". $fecvencimientonuevo; exit;*/


			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$result = $this->admin->enviar_aviso_pago_webpay();


			exit;
	
	}	




	public function enviar_comprobante_multa()
	{

			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$result = $this->admin->enviar_comprobante_multa();


			exit;
	
	}	




	public function revision_pagos_online()
	{

			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$comunidades = $this->admin->get_comunidades(null,true);
			//$fecproceso = date('Y-m-d');
			$fecproceso = '2024-01-19';
			
			$this->load->model('payment');
			foreach ($comunidades as $comunidad) {
				$comunidades = $this->payment->revisa_pagos_online($comunidad,$fecproceso);
			}

			exit;
	
	}	




	public function asocia_saldos_positivos()
	{

			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$result = $this->admin->asocia_saldos_positivos();


			exit;
	
	}	


	public function redistribuye_saldos_erroneos()
	{

			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$result = $this->admin->redistribuye_saldos_erroneos();


			exit;
	
	}	



	public function enviar_aviso_vencimiento_ggcc()
	{

			set_time_limit(0); // quita limite de tiempo al hacer carga
			$this->load->model('admin');
			$result = $this->admin->enviar_aviso_vencimiento_ggcc();

			
			exit;
	
	}	

	


	public function download_ggcc($idcomunidad,$idpropiedad,$idperiodo)
	{

			$this->load->model('admin');

			$datos_periodo = $this->admin->get_periodo_by_id_guest($idperiodo,$idcomunidad);
			//var_dump($datos_periodo); exit;
			if(is_null($datos_periodo->genera)){
				redirect('main/dashboard/');				
			}


			$datos_propiedad = $this->admin->get_prop_comunidad_guest($idpropiedad,$idcomunidad);
			if(is_null($datos_propiedad)){
				redirect('main/dashboard/');
			}

			$this->load->model('payment');
			$datosdetalle = $this->payment->generar_comprobante($idcomunidad,$idperiodo,$idpropiedad);						




	}	




public function pagonotifyprop($tokentgc = null)
	{

		$tokentgc = $this->input->get('orderClient');


		$this->load->model('admin');
		//$datos_result = $this->admin->accept_payprop(null,$tokentgc);


		exit;


		/*$this->load->database();

		$string_get = implode(',',$_GET);
		$string_post = implode(',',$_POST);

		$array_datos = array(
								'get' => $string_get,
								'post' => $string_post

						);
		$this->db->insert('gc_tabla_prueba_pago', $array_datos);

		$f_archivo = fopen('./sql/archivo.txt','w');
		fwrite($f_archivo,$string_get.'-'.$string_post);
		fclose($f_archivo);		


		var_dump($_GET);
		var_dump($_POST);
		exit;

		*/

/*
		$content = array(
					'menu' => 'Pago Online',
					'title' => 'Pago Online',
					'subtitle' => 'Resultado Pago');		

		$vars['content_menu'] = $content;				
		$vars['content_view'] = "payment/pagonotify";

		$template = "template_guest";
		$this->load->view($template,$vars);	*/
	}


public function prueba_envio_correo(){

		$this->load->model('admin');
		$array_email = array('rodrigog.84@gmail.com');
		$messageBody = 'Prueba Envio';
		$this->admin->envia_mail_prueba('robot@tugastocomun.cl', $array_email, 'Prueba envio', $messageBody, 'html');


}


public function pagonotify($tokentgc = null)
	{

		$tokentgc = $this->input->get('orderClient');

		//al terminar llama a esto
		$this->load->model('admin');
		$datos_result = $this->admin->accept_pay(null,$tokentgc);


		exit;


		/*$this->load->database();

		$string_get = implode(',',$_GET);
		$string_post = implode(',',$_POST);

		$array_datos = array(
								'get' => $string_get,
								'post' => $string_post

						);
		$this->db->insert('gc_tabla_prueba_pago', $array_datos);

		$f_archivo = fopen('./sql/archivo.txt','w');
		fwrite($f_archivo,$string_get.'-'.$string_post);
		fclose($f_archivo);		


		var_dump($_GET);
		var_dump($_POST);
		exit;

		*/

/*
		$content = array(
					'menu' => 'Pago Online',
					'title' => 'Pago Online',
					'subtitle' => 'Resultado Pago');		

		$vars['content_menu'] = $content;				
		$vars['content_view'] = "payment/pagonotify";

		$template = "template_guest";
		$this->load->view($template,$vars);	*/
	}


	public function webpay()
	{
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
						//var_dump($datos_result);		

						$datos_comunidad = $this->admin->get_comunidades($datos_result['idcomunidad']);
						//var_dump($datos_comunidad);
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
			            $message = "Transacion Finalizada.  Ingresar nuevamente al sistema para aplicar cambios.";
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


			$template = "template_guest";
			

			$this->load->view($template,$vars);	


	}	
}