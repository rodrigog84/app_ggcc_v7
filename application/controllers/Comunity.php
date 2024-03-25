<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comunity extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->helper('format');
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_userdata('uri_array', $this->uri->rsegment_array());
            redirect('auth/login', 'refresh');
        } else {
            if (!$this->session->userdata('menu_list')) {
                $this->session->set_userdata('menu_list', json_decode($this->ion_auth_model->get_menu($this->session->userdata('user_id'))));
            }
            if ($this->router->fetch_class() . "/" . $this->router->fetch_method() != "main/dashboard" && !$this->session->userdata('comunidadid') && ($this->session->userdata('level') == 1 || $this->session->userdata('level') == 3)) {
                redirect('main/dashboard');
            }
        }
    }


    public function index()
    {

        $this->load->model('ion_auth_model');
        redirect('main/dashboard');
    }

    public function calculo_ggcc($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('calculo_ggcc_result');
            if ($resultid == 1) {
                $vars['message'] = "Generací&oacute;n de Gasto Com&uacute;n realizado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } else if ($resultid == 2) {
                $vars['message'] = "No es posible prorratear per&iacute;odo indicado";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } else if ($resultid == 3) {
                $vars['message'] = "Debe seleccionar un per&iacute;odo a prorratear";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $this->load->model('admin');
            $datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));

            $this->load->model('payment');
            $datosdeuda = $this->payment->get_ggcc_prorrateo_by_comunidad($this->session->userdata('comunidadid'));

            $datospublicacion = $this->payment->get_ggcc_publicacion_by_comunidad($this->session->userdata('comunidadid'));


            $pendiente_publicacion = count($datospublicacion) > 0 ? true : false;
            //$pendiente_publicacion = !is_null($datospublicacion) ? true : false;
            //var_dump($pendiente_publicacion); exit;

            $content = array(
                'menu' => 'Gasto Com&uacute;n',
                'title' => 'Gasto Com&uacute;n',
                'subtitle' => 'Prorratear'
            );

            $vars['classinfo'] = $datoscomunidad->saldo > 0 ? 'bg-red' : 'bg-green';
            $vars['datetimepicker'] = true;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'comunity/ver_comunidad';
            $vars['datoscomunidad'] = $datoscomunidad;
            $vars['datosdeuda'] = $datosdeuda;
            $vars['pendiente_publicacion'] = $pendiente_publicacion;


            if ($pendiente_publicacion && $resultid == '' && !is_null($datosdeuda)) {
                $vars['message'] = "No es posible prorratear.  Existe Gasto Com&uacute;n pendiente de publicar";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $template = "template";


            $this->load->view($template, $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }



    public function generar_ggcc()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            set_time_limit(0);

            $ggcc_valido = $this->input->post('ggccid');

            $this->load->model('payment');

            $tipo_fr = $this->input->post('fr');
            $tipo_cap = $this->input->post('tipo_cap');
            $interes = $this->input->post('interes');

            if ($tipo_fr == 'pesos') {
                $monto_fr = str_replace(".", "", $this->input->post("monto"));
            } else if ($tipo_fr == 'porcentaje') {
                $monto_fr = $this->input->post('porc');
            } else {
                $monto_fr = 0;
            }





            $array_fr = array(
                'tipo_fr' => $tipo_fr,
                'monto_fr' => $monto_fr,
                'tipo_cap' => $tipo_cap,
                'interes' => $interes
            );

            $propiedades = $this->payment->prorratear_ggcc($this->session->userdata('comunidadid'), $ggcc_valido, $array_fr);
            $resultid = $this->session->set_flashdata('calculo_ggcc_result', 1);
            redirect('comunity/calculo_ggcc');
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function prorrateo($idperiodo = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if (is_null($idperiodo)) {
                $resultid = $this->session->set_flashdata('calculo_ggcc_result', 3);
                redirect('comunity/calculo_ggcc');
            }


            $content = array(
                'menu' => 'Gasto Com&uacute;n',
                'title' => 'Gasto Com&uacute;n',
                'subtitle' => 'Prorrateo'
            );

            $this->load->model('payment');
            $datosdeuda = $this->payment->get_ggcc_prorrateo_by_comunidad($this->session->userdata('comunidadid'), $idperiodo);

            if (is_null($datosdeuda)) { // en caso de querer prorratear un período inválido
                $resultid = $this->session->set_flashdata('calculo_ggcc_result', 2);
                redirect('comunity/calculo_ggcc');
            }

            $vars['datetimepicker'] = true;
            $vars['formValidation'] = true;
            $vars['icheck'] = true;
            $vars['mask'] = true;
            $vars['classinfo'] = $datosdeuda->monto > 0 ? 'bg-red' : 'bg-green';

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'comunity/prorrateo';

            $vars['datosdeuda'] = $datosdeuda;

            $this->load->view('template', $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function publicar_ggcc($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('publicar_ggcc_result');
            if ($resultid == 1) {
                $vars['message'] = "Publicaci&oacute;n de Gasto Com&uacute;n exitosa";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al publicar Gasto Com&uacute;n.  Favor intentar nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Reversa de Gasto Com&uacute;n exitosa";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al reversar Gasto Com&uacute;n.  Favor intentar nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }


            $this->load->model('payment');
            $datosdeuda = $this->payment->get_ggcc_publicacion_by_comunidad($this->session->userdata('comunidadid'));

            $content = array(
                'menu' => 'Gasto Com&uacute;n',
                'title' => 'Gasto Com&uacute;n',
                'subtitle' => 'Publicar'
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'comunity/publicar_ggcc';
            $vars['datosdeuda'] = $datosdeuda;

            $template = "template";


            $this->load->view($template, $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }




    public function submit_publicar($idperiodo)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            set_time_limit(0);


            $this->load->model('payment');
            $publicar = $this->payment->publicar_ggcc($idperiodo);


            if ($publicar == 1) {

                /*$this->load->model('admin');
				$propiedades = $this->admin->get_propiedades_comunidad();

				foreach ($propiedades as $propiedad) { // ENVIO DE MAIL
					$this->payment->generar_contenido_comprobante($this->session->userdata('comunidadid'),$idperiodo,$propiedad->id);
					if($propiedad->suscrito == 1){
						//$propiedades = $this->payment->generar_mail($this->session->userdata('comunidadid'),$idperiodo,$propiedad);
						$this->payment->generar_mail($this->session->userdata('comunidadid'),$idperiodo,$propiedad);
					}
				}*/

                $this->session->set_flashdata('publicar_ggcc_result', 1);
            } else {
                $this->session->set_flashdata('publicar_ggcc_result', 2);
            }

            redirect('comunity/publicar_ggcc');
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }




    public function reenvio_ggcc_mail($idperiodo)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            set_time_limit(0);
            $this->load->model('admin');
            $datosperiodo = $this->admin->get_periodos($this->session->userdata('comunidadid'), $idperiodo);


            if (!is_null($datosperiodo->publica)) {



                $this->load->model('payment');
                $propiedades = $this->admin->get_propiedades_comunidad();
                $i = 1;
                foreach ($propiedades as $propiedad) { // ENVIO DE MAIL
                    if ($propiedad->mail != '' and $propiedad->suscrito == 1) {
                        //$propiedades = $this->payment->generar_mail($this->session->userdata('comunidadid'),$idperiodo,$propiedad);
                        $this->payment->generar_mail($this->session->userdata('comunidadid'), $idperiodo, $propiedad);
                        echo $i . ".- envio a propiedad nro :" . $propiedad->numero . " , mail : " . $propiedad->mail . "<br>";
                        $i++;
                    }
                }

                //$this->session->set_flashdata('publicar_ggcc_result', 1);
            } else {
                echo "no entra";
                //$this->session->set_flashdata('publicar_ggcc_result', 2);

            }
            exit;
            redirect('comunity/publicar_ggcc');
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function reversar_ggcc($idperiodo)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            set_time_limit(0);


            $this->load->model('payment');
            $reversar = $this->payment->reversar_ggcc($idperiodo);


            if ($reversar) {
                $this->session->set_flashdata('publicar_ggcc_result', 3);
            } else {
                $this->session->set_flashdata('publicar_ggcc_result', 4);
            }

            redirect('comunity/publicar_ggcc');
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function libro_visitas()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $result = $this->session->flashdata('registro_visita');

            if ($result == 1) {
                $vars['message'] = "Registro Agregado correctamente.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 2) {
                $vars['message'] = "Registro Editado correctamente.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 3) {
                $vars['message'] = "Error al agregar Registro. Estacionamiento se encuentra ocupado.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 4) {
                $vars['message'] = "Registro Archivado correctamente.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 5) {
                $vars['message'] = "Error al archivar Registro.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Libro de Visitas'
            );

            $this->load->model('comunity_model');
            // Si es Personal o Admin manda comunidad
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 5 ? $this->session->userdata('comunidadid') : null;
            $active = $this->session->userdata('level') == 1 ? 0 : 1;
            $registros = $this->comunity_model->get_registros_visitas($idcomunidad, $active);

            // Permite editar al Personal y al SysAdmin
            $vars['permite_editar'] = $this->session->userdata('level') === '5' || $this->session->userdata('level') == '4' ? true : false;
            $vars['registros'] = $registros;
            $vars['content_menu'] = $content;
            $vars['titulo'] = 'Listado de Visitas';
            $vars['content_view'] = 'comunity/libro_visitas';
            $vars['dataTables'] = true;

            $template = 'template';

            $this->load->view($template, $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );

            $template = 'template';

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view($template, $vars);
        }
    }

    public function libro_novedades()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $result = $this->session->flashdata('bitacora');

            if ($result == 1) {
                $vars['message'] = "Bitacora Agregada correctamente.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 2) {
                $vars['message'] = "Bitacora Editada correctamente.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 3) {
                $vars['message'] = "Error al agregar Bitacora.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 4) {
                $vars['message'] = "Bitacora Archivada correctamente.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 5) {
                $vars['message'] = "Error al archivar Bitacora.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 6) {
                $vars['message'] = "Error al editar Bitacora.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Libro de Novedades'
            );

            $this->load->model('comunity_model');
            // Si es Personal, Admin o Comite se manda la comunidad
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 5 || $this->session->userdata('level') == 2 ? $this->session->userdata('comunidadid') : null;
            $active = $this->session->userdata('level') === '1' ? 0 : 1;
            $bitacoras = $this->comunity_model->get_bitacoras($idcomunidad, $active);

            $vars['permite_editar'] = $this->session->userdata('level') === '5' || $this->session->userdata('level') == '4' ? true : false;
            $vars['bitacoras'] = $bitacoras;
            $vars['content_menu'] = $content;
            $vars['titulo'] = 'Listado de bitacoras';
            $vars['content_view'] = 'comunity/libro_novedades';
            $vars['dataTables'] = true;

            $template = 'template';

            $this->load->view($template, $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );

            $template = 'template';

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view($template, $vars);
        }
    }

    public function historial_visitas()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $result = $this->session->flashdata('historial_visitas');

            if ($result == 1) {
                $vars['message'] = "Se ha eliminado definitivamente el Registro.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 2) {
                $vars['message'] = "Error al eliminar Registro.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 3) {
                $vars['message'] = "No se ha seleccionado un Registro.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 4) {
                $vars['message'] = "Se ha limpiado el Historial.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 5) {
                $vars['message'] = "No se ha encontrado ningun Registro en el Historial.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 6) {
                $vars['message'] = "Error al limpiar el Historial.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => $this->session->userdata('level') != 1 ? 'Historial de Visitas' : 'Pendientes de Traspaso',
            );

            $this->load->model('comunity_model');
            // Si es Personal o Admin manda comunidad
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 5 ? $this->session->userdata('comunidadid') : null;
            $active = $this->session->userdata('level') == 1 ? 1 : 0;
            $registros = $this->comunity_model->get_registros_visitas($idcomunidad, $active);

            $vars['permite_editar'] = false;
            $vars['registros'] = $registros;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'comunity/historial_visitas';
            $vars['dataTables'] = true;

            $template = 'template';

            $this->load->view($template, $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );

            $template = 'template';

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view($template, $vars);
        }
    }

    public function historial_novedades()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $result = $this->session->flashdata('bitacora');

            if ($result == 1) {
                $vars['message'] = "Se ha eliminado definitivamente la Bitacora.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 2) {
                $vars['message'] = "Error al eliminar Bitacora.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 3) {
                $vars['message'] = "No se ha seleccionado una Bitacora.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 4) {
                $vars['message'] = "Se ha limpiado el Historial.";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($result == 5) {
                $vars['message'] = "No se ha encontrado ninguna Bitacora en el Historial.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($result == 6) {
                $vars['message'] = "Error al limpiar el Historial.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => $this->session->userdata('level') != 1 ? 'Historial de Visitas' : 'Pendientes de Traspaso',
            );

            $this->load->model('comunity_model');
            // Si es Personal, Admin o Comite se manda la comunidad
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 5 || $this->session->userdata('level') == 2 ? $this->session->userdata('comunidadid') : null;
            $active = $this->session->userdata('level') === '1' ? 1 : 0;
            $bitacoras = $this->comunity_model->get_bitacoras($idcomunidad, $active);

            $vars['permite_editar'] = false;
            $vars['bitacoras'] = $bitacoras;
            $vars['content_menu'] = $content;
            $vars['titulo'] = 'Listado de bitacoras';
            $vars['content_view'] = 'comunity/historial_novedades';
            $vars['dataTables'] = true;

            $template = 'template';

            $this->load->view($template, $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );

            $template = 'template';

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view($template, $vars);
        }
    }

    public function add_registro_visita($idregistro = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('comunity_model');
            $this->load->model('admin');

            // Si ya existe el registro se obtiene
            $registro = $this->comunity_model->get_registro_visita_by_id($idregistro);
            $propiedades = $this->admin->get_propiedades_comunidad();
            $estacionamientos = $this->comunity_model->get_estacionamiento_visita_comunidad($this->session->userdata('comunidadid'));

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Libro de Visitas'
            );

            $datos_form = array(
                'idregistro' => is_null($registro) ? 0 : $registro->id,
                'idcomunidad' => is_null($registro) ? $this->session->userdata('comunidadid') : $registro->idcomunidad,
                'idpropiedad' => is_null($registro) ? '' : $registro->idpropiedad,
                'idestacionamiento' => is_null($registro) ? '' : $registro->idestacionamiento,
                'nombre' => is_null($registro) ? '' : $registro->nombre,
                'apellidos' => is_null($registro) ? '' : $registro->apellidos,
                'rut' => is_null($registro) ? '' : number_format($registro->rut, 0, ".", ".") . "-" . $registro->dv,
                'propiedad' => is_null($registro) ? '' : $registro->propiedad,
                'responsable' => is_null($registro) ? '' : $registro->responsable,
                'estacionamiento' => is_null($registro) ? '' : $registro->estacionamiento,
                'patente' => is_null($registro) ? '' : $registro->patente,
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'comunity/add_registro_visita';
            $vars['titulo'] = $idregistro == '' ? "Agregar Registro" : "Editar Registro";
            $vars['datos_form'] = $datos_form;
            $vars['estacionamientos'] = $estacionamientos;
            $vars['propiedades'] = $propiedades;
            $vars['formValidation'] = true;
            $vars['jqueryRut'] = true;

            $template = 'template';

            $this->load->view($template, $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function add_bitacora($idbitacora = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('comunity_model');
            $this->load->model('admin');

            // Si ya existe la bitacora, se obtiene
            $bitacora = $this->comunity_model->get_bitacora_by_id($idbitacora);

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Libro de Novedades'
            );

            $datos_form = array(
                'idbitacora' => is_null($bitacora) ? 0 : $idbitacora,
                'idcomunidad' => is_null($bitacora) ? $this->session->userdata('comunidadid') : $bitacora->idcomunidad,
                'iduser' => is_null($bitacora) ? $this->session->userdata('user_id') : $bitacora->iduser,
                'accion' => is_null($bitacora) ? '' : $bitacora->accion,
                'descripcion' => is_null($bitacora) ? '' : $bitacora->descripcion,
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'comunity/add_bitacora';
            $vars['titulo'] = $idbitacora == 0 ? "Agregar Bitacora" : "Editar Bitacora";
            $vars['datos_form'] = $datos_form;
            $vars['formValidation'] = true;

            $template = 'template';

            $this->load->view($template, $vars);
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function submit_registro_visita()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $idcomunidad = $this->input->post('idcomunidad');
            $idestacionamiento = $this->input->post('estvisita');
            $idpropiedad = $this->input->post('propiedad');
            $nombre = $this->input->post('nombre');
            $apellidos = $this->input->post('apellidos');
            $rut = str_replace(".", "", $this->input->post("rut"));
            $arrayRut = explode("-", $rut);
            $patente = $this->input->post('patente');
            $idregistro = $this->input->post('idregistro');

            $array_datos = array(
                'idcomunidad' => $idcomunidad,
                'idpropiedad' => $idpropiedad,
                'idestacionamiento' => empty($idestacionamiento) ? null : $idestacionamiento,
                'rut' => $arrayRut[0],
                'dv' => $arrayRut[1],
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'patente' => $patente
            );

            $this->load->model('comunity_model');
            $result = $this->comunity_model->add_registro_visita($array_datos, $idregistro);
            // Si el estacionamiento esta desocupado, lo ocupa
            $this->comunity_model->estado_estacionamiento($idestacionamiento, 0);

            if ($result == -1) {
                $this->session->set_flashdata('registro_visita', 3);
                redirect('comunity/libro_visitas');
            } else if ($result == 1) {
                $this->session->set_flashdata('registro_visita', 2);
                redirect('comunity/libro_visitas');
            } else {
                $this->session->set_flashdata('registro_visita', 1);
                redirect('comunity/libro_visitas');
            }
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function submit_bitacora()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $idbitacora = $this->input->post('idbitacora');
            $idcomunidad = $this->input->post('idcomunidad');
            $iduser = $this->input->post('iduser');
            $accion = $this->input->post('accion');
            $descripcion = $this->input->post('descripcion');

            $array_datos = array(
                'iduser' => $iduser,
                'idcomunidad' => $idcomunidad,
                'accion' => $accion,
                'descripcion' => $descripcion
            );

            $this->load->model('comunity_model');
            $result = $this->comunity_model->add_bitacora($array_datos, $idbitacora);

            if ($result == -1) {
                // Error al agregar
                $this->session->set_flashdata('bitacora', 3);
                redirect('comunity/libro_novedades');
            } else if ($result == 1) {
                // Se edita con exito
                $this->session->set_flashdata('bitacora', 2);
                redirect('comunity/libro_novedades');
            } else if ($result == -2) {
                // Error al editar
                $this->session->set_flashdata('bitacora', 6);
                redirect('comunity/libro_novedades');
            } else {
                // Se agrega con exito
                $this->session->set_flashdata('bitacora', 1);
                redirect('comunity/libro_novedades');
            }
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function add_salida_visita($idregistro)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('comunity_model');
            $registro = $this->comunity_model->get_registro_visita_by_id($idregistro);
            $result = $this->comunity_model->marca_salida($idregistro);

            // Desocupa estacionamiento
            $this->comunity_model->estado_estacionamiento($registro->idestacionamiento, 1);

            if ($result == 1) {
                $this->session->set_flashdata('registro_visita', 4);
                redirect('comunity/libro_visitas');
            } else {
                $this->session->set_flashdata('registro_visita', 5);
                redirect('comunity/libro_visitas');
            }
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function archive_bitacora($idbitacora)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('comunity_model');
            $result = $this->comunity_model->archive_bitacora($idbitacora);

            if ($result === 1) {
                // Se archiva correctamente
                $this->session->set_flashdata('bitacora', 4);
                redirect('comunity/libro_novedades');
            } else if ($result === 0) {
                // Error al archivar, no existe
                $this->session->set_flashdata('bitacora', 5);
                redirect('comunity/libro_novedades');
            } else if ($result === -1) {
                // Error al archivar, se encuentra archivada
                $this->session->set_flashdata('bitacora', 7);
                redirect('comunity/libro_novedades');
            }
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function delete_registro_visita($idregistro = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('comunity_model');
            $result = $this->comunity_model->delete_registro_visita($idregistro);

            if ($result === 1) {
                $this->session->set_flashdata('historial_visitas', 1);
                redirect('comunity/historial_visitas');
            } else if ($result === 0) {
                $this->session->set_flashdata('historial_visitas', 2);
                redirect('comunity/historial_visitas');
            } else if ($result === -1) {
                $this->session->set_flashdata('historial_visitas', 3);
                redirect('comunity/historial_visitas');
            }
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function delete_bitacora($idbitacora = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('comunity_model');
            $result = $this->comunity_model->delete_bitacora($idbitacora);

            if ($result === 1) {
                $this->session->set_flashdata('bitacora', 1);
                redirect('comunity/historial_novedades');
            } else if ($result === 0) {
                $this->session->set_flashdata('bitacora', 2);
                redirect('comunity/historial_novedades');
            } else if ($result === -1) {
                $this->session->set_flashdata('bitacora', 3);
                redirect('comunity/historial_novedades');
            }
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function clean_historial_visitas($idcomunidad = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('comunity_model');
            $idcomunidad = $this->session->userdata('level') === '5' ? $this->session->userdata('comunidadid') : null;
            $result = $this->comunity_model->clean_registros_visitas($idcomunidad);

            if ($result === 1) {
                // Se limpio el historial
                $this->session->set_flashdata('historial_visitas', 4);
                redirect('comunity/historial_visitas');
            } else if ($result === 0) {
                // No se encontraron registros
                $this->session->set_flashdata('historial_visitas', 5);
                redirect('comunity/historial_visitas');
            } else if ($result === -1) {
                // Error al limpiar historial
                $this->session->set_flashdata('historial_visitas', 6);
                redirect('comunity/historial_visitas');
            }
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }

    public function clean_historial_novedades($idcomunidad = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('comunity_model');
            $idcomunidad = $this->session->userdata('level') === '5' ? $this->session->userdata('comunidadid') : null;
            $result = $this->comunity_model->clean_bitacoras($idcomunidad);

            if ($result === 1) {
                // Se limpio el historial
                $this->session->set_flashdata('bitacora', 4);
                redirect('comunity/historial_novedades');
            } else if ($result === 0) {
                // No se encontraron bitacoras
                $this->session->set_flashdata('bitacora', 5);
                redirect('comunity/historial_novedades');
            } else if ($result === -1) {
                // Error al limpiar historial
                $this->session->set_flashdata('bitacora', 6);
                redirect('comunity/historial_novedades');
            }
        } else {
            $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }
}
