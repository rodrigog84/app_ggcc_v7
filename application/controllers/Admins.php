<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Admins extends CI_Controller
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


    //TODOS TIENEN ACCESO AL DASHBOARD
    public function edita_comunidad($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('edita_comunidad_result');
            if ($resultid == 1) {
                $vars['message'] = "Comunidad Actualizada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }

            $this->load->model('admin');
            $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));
            $regiones = $this->admin->get_regiones();

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Editar Comunidad'
            );

            $datos_form = array(
                'idcomunidad' => is_null($comunidad) ? 0 : $comunidad->id,
                'nombre' => is_null($comunidad) ? '' : $comunidad->nombre,
                'rut' => is_null($comunidad) ? '' : number_format(substr($comunidad->rut . $comunidad->dv, 0, -1), 0, "", ".") . '-' . substr($comunidad->rut . $comunidad->dv, strlen($comunidad->rut . $comunidad->dv) - 1, 1),
                'direccion' => is_null($comunidad) ? '' : $comunidad->direccion,
                'idregion' => is_null($comunidad) ? '' : $comunidad->idregion,
                'idcomuna' => is_null($comunidad) ? '' : $comunidad->idcomuna,
                'fono' => is_null($comunidad) ? '' : $comunidad->fono,
                'fono2' => is_null($comunidad) ? '' : $comunidad->fono2,
                'email' => is_null($comunidad) ? '' : $comunidad->email,
                'saldo' => is_null($comunidad) ? '' : $comunidad->saldo,
                'caja' => is_null($comunidad) ? '' : $comunidad->caja,
                'cajainicial' => is_null($comunidad) ? '' : $comunidad->cajainicial,
                'fondoreserva' => is_null($comunidad) ? '' : $comunidad->fondoreserva,
                'fondoreservainicial' => is_null($comunidad) ? '' : $comunidad->fondoreservainicial,
                'logo' => is_null($comunidad) ? '' : $comunidad->logo,
                'firma' => is_null($comunidad) ? '' : $comunidad->firma,
                'descripcion' => is_null($comunidad) ? '' : $comunidad->obscomprobante,
            );



            $vars['content_menu'] = $content;
            $vars['regiones'] = $regiones;
            $vars['content_view'] = 'admin/edita_comunidad';
            $vars['datos_form'] = $datos_form;
            $vars['formValidation'] = true;
            $vars['jqueryRut'] = true;
            $vars['mask'] = true;
            $vars['maleta'] = true;
            $vars['icheck'] = true;



            $template = "template";
            $this->load->view($template, $vars);
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function get_comunas($idregion)
    {

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

    public function put_conciliacion()
    {


        $fecha_conciliacion = $this->input->post("fecha_conciliacion");
        $movimiento = $this->input->post("movimiento");
        $array_conciliacion = array(
            'fecha_conciliacion' => $fecha_conciliacion,
            'movimiento' => $movimiento
        );


        $this->load->model('admin');
        $result = $this->admin->put_conciliacion($array_conciliacion);
        echo json_encode($result);
        //echo form_dropdown('comuna',$arrayComunas ,'',"class='form-control' id='comuna'");

    }



    public function get_propiedades_by_comunidad($idcomunidad = null)
    {


        if (!is_null($idcomunidad)) {
            $this->load->model('admin');
            $propiedades = $this->admin->get_propiedad_by_comunidad($idcomunidad);
        } else {
            $propiedades = array();
        }

        echo json_encode($propiedades);
    }


    public function get_conceptos($idconcepto = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $conceptos = $this->admin->get_concepto_by_id($idconcepto);
            echo $conceptos;
        } else {
            echo "Access forbidden";
        }
    }




    public function submit_comunidad()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->library('upload');
            /*** elementos logos *****/
            $config['upload_path'] = "./uploads/logos/" . $this->session->userdata('comunidadid') . "/";


            if (!file_exists($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $config['file_name'] = date("Ymd") . "_" . date("His") . "_" . randomstring(5) . "_" . $this->session->userdata('comunidadid');
            $config['allowed_types'] = "*";
            $config['max_size'] = "10240";



            $this->upload->initialize($config);


            $this->upload->do_upload("userfile");

            $dataupload = $this->upload->data();


            /****** elementos firmas ***********************/
            $config['upload_path'] = "./uploads/firmas/" . $this->session->userdata('comunidadid') . "/";


            if (!file_exists($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $config['file_name'] = date("Ymd") . "_firmas_" . date("His") . "_" . randomstring(5) . "_" . $this->session->userdata('comunidadid');
            $config['allowed_types'] = "*";
            $config['max_size'] = "10240";

            $this->upload->initialize($config);
            $this->upload->do_upload("userfilesignature");

            $dataupload2 = $this->upload->data();


            /*****************************************/



            $direccion = $this->input->post('direccion');
            $idregion = $this->input->post('region');
            $idcomuna = $this->input->post('comuna');
            $fono = $this->input->post('fono');
            $fono2 = $this->input->post('fono2');
            $email = $this->input->post('email');
            $idcomunidad = $this->input->post('idcomunidad');
            $cajainicial = str_replace(".", "", $this->input->post('cajainicial'));
            $fondoreservainicial = str_replace(".", "", $this->input->post('fondoreservainicial'));
            $borrarlogo = $this->input->post('borrarlogo') == 'on' ? 1 : 0;
            $borrarfirma = $this->input->post('borrarfirma') == 'on' ? 1 : 0;
            $descripcion = $this->input->post('descripcion');

            $ruttitular = str_replace(".", "", $this->input->post("rutcomunidad"));
            $arrayRut = explode("-", $ruttitular);

            $array_datos = array(
                //'comunidad' => $comunidad,
                'rut' => $arrayRut[0],
                'dv' => $arrayRut[1],
                'direccion' => $direccion,
                'idregion' => $idregion,
                'idcomuna' => $idcomuna,
                'fono' => $fono,
                'fono2' => $fono2,
                'email' => $email,
                'cajainicial' => $cajainicial,
                'fondoreservainicial' => $fondoreservainicial,
                'idcomunidad' => $idcomunidad,
                'logo' => $dataupload['orig_name'],
                'firma' => $dataupload2['orig_name'],
                'obscomprobante' => $descripcion,
                'borrarlogo' => $borrarlogo,
                'borrarfirma' => $borrarfirma
            );


            $this->load->model('admin');
            $result = $this->admin->save_comunidad($array_datos);

            if ($result == -1) {
                $this->session->set_flashdata('edita_comunidad_result', 2);
            } else {
                $this->session->set_flashdata('edita_comunidad_result', 1);
            }
            redirect('admins/edita_comunidad');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function admin_periodo()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('periodo_result');
            if ($resultid == 1) {
                $vars['message'] = "Per&iacute;odo Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Per&iacute;odo. Per&iacute;odo ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Per&iacute;odo Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Per&iacute;odo. Per&iacute;odo no est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Per&iacute;odo Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al intentar Editar Per&iacute;odo. Per&iacute;odo no est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 7) {
                $vars['message'] = "Error al intentar Editar Per&iacute;odo. Per&iacute;odo ya fue autorizado";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 8) {
                $vars['message'] = "Error al agregar Per&iacute;odo. Favor Intente nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 9) {
                $vars['message'] = "Error al agregar Per&iacute;odo. Per&iacute;odo anterior a incorporaci&oacute;n de comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $this->load->model('admin');

            $datosperiodo = $this->admin->get_periodos($this->session->userdata('comunidadid'));

            $periodo_inicial = $this->admin->get_periodo_inicial();

            $ultimo_periodo = $this->admin->get_ultimo_periodo()->idperiodo;

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Per&iacute;odos'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_periodo';
            $vars['datosperiodo'] = $datosperiodo;
            $vars['ultimo_periodo'] = $ultimo_periodo;
            $vars['periodo_inicial'] = $periodo_inicial;


            $vars['dataTables'] = true;

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


    public function add_periodo()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Agregar Per&iacute;odo'
            );

            $meses = array(
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            );


            $anno_actual =  date("Y", strtotime('-7 year', strtotime(date('Y-m-d'))));
            //$anno_actual--;
            $annos = array();
            for ($i = 0; $i < 10; $i++) {
                array_push($annos, $anno_actual);
                $anno_actual++;
            }


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_periodo';
            $vars['meses'] = $meses;
            $vars['annos'] = $annos;

            $vars['titulo'] = "Agregar Per&iacute;odo";
            $vars['formValidation'] = true;
            $vars['datetimepicker'] = true;

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

    public function permite_periodo($tipo_status = null)
    {
        $this->load->model('admin');
        $valid = $this->admin->get_permite_periodo($this->input->post('mes'), $this->input->post('anno'));

        echo json_encode(array(
            'valid' => $valid
        ));
    }


    public function edit_periodo($idperiodo = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $this->load->model('admin');
            $periodo = $this->admin->get_periodo_by_id($idperiodo);

            if (is_null($periodo)) { // No se puede editar si le doy un periodo inexistente
                $this->session->set_flashdata('periodo_result', 6);
                redirect('admins/admin_periodo');
            } else {
                $ultimo_periodo = $this->admin->get_ultimo_periodo()->idperiodo;
                $periodo_inicial = $this->admin->get_periodo_inicial();

                if (($periodo->genera != '' && $periodo->id != $ultimo_periodo) || ($periodo->id == $periodo_inicial->id)) {
                    $this->session->set_flashdata('periodo_result', 7);
                    redirect('admins/admin_periodo');
                }
            }

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Editar Per&iacute;odo'
            );

            $datos_form = array(
                'idperiodo' => is_null($periodo) ? 0 : $periodo->id,
                'mes' => is_null($periodo) ? '' : $periodo->mes,
                'anno' => is_null($periodo) ? '' : $periodo->anno,
                'fecvencimiento' => is_null($periodo) ? date('d/m/Y') : $periodo->fecha_vencimiento,
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/edit_periodo';

            $vars['titulo'] = "Editar Per&iacute;odo";
            $vars['datos_form'] = $datos_form;
            $vars['formValidation'] = true;
            $vars['datetimepicker'] = true;

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



    public function delete_periodo($idperiodo = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            if ($idperiodo == 0) {
                redirect('main/dashboard');
            }



            $this->load->model('admin');
            $result = $this->admin->delete_periodo($idperiodo);
            if ($result == -1) {
                $this->session->set_flashdata('periodo_result', 4);
                redirect('admins/admin_periodo');
            } else {
                $this->session->set_flashdata('periodo_result', 5);
                redirect('admins/admin_periodo');
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



    public function submit_periodo()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $mes = $this->input->post('mes');
            $anno = $this->input->post('anno');

            if ($mes == '' || $anno == '') {
                $this->session->set_flashdata('periodo_result', 8);
                redirect('admins/admin_periodo');
            }

            $this->load->model('admin');

            /*if(!$this->admin->get_permite_periodo($mes,$anno)){
				$this->session->set_flashdata('periodo_result',9);
				redirect('admins/admin_periodo');
			}*/

            $fecvencimiento = $this->input->post('fecvencimiento');
            $idperiodo = $this->input->post('idperiodo');
            $array_datos = array(
                'idperiodo' => $idperiodo,
                'mes' => $mes,
                'anno' => $anno,
                'fecvencimiento' => $fecvencimiento
            );


            $result = $this->admin->add_periodo($array_datos);

            if ($result == -1) {
                $this->session->set_flashdata('periodo_result', 2);
                redirect('admins/admin_periodo');
            } else {
                if ($idperiodo == 0) {
                    $this->session->set_flashdata('periodo_result', 1);
                    redirect('admins/admin_periodo');
                } else {
                    $this->session->set_flashdata('periodo_result', 3);
                    redirect('admins/admin_periodo');
                }
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


    public function admin_proveedor($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Proveedor Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Proveedor. Proveedor ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al agregar Proveedor. Proveedor ya est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Proveedor. Proveedor no est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Proveedor Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Proveedor Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('admin');

            $proveedores_comunidad = $this->admin->get_proveedor_comunidad_by_id();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Proveedores'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_proveedor';
            $vars['proveedores_comunidad'] = $proveedores_comunidad;
            $vars['dataTables'] = true;
            $vars['formValidation'] = true;


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


    public function add_proveedor($idproveedor = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $proveedor = $this->admin->get_proveedor_by_id($idproveedor);

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Proveedores'
            );

            $datos_form = array(
                'idproveedor' => is_null($proveedor) ? 0 : $proveedor->id,
                'nombre' => is_null($proveedor) ? '' : $proveedor->nombre,
                'rut' => is_null($proveedor) ? '' : number_format(substr($proveedor->rut . $proveedor->dv, 0, -1), 0, "", ".") . '-' . substr($proveedor->rut . $proveedor->dv, strlen($proveedor->rut . $proveedor->dv) - 1, 1),
                'direccion' => is_null($proveedor) ? '' : $proveedor->direccion,
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_proveedor';
            $vars['titulo'] = $idproveedor == '' ? "Agregar Proveedor" : "Editar Proveedor";
            $vars['datos_form'] = $datos_form;
            $vars['formValidation'] = true;
            $vars['jqueryRut'] = true;


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



    public function submit_proveedor()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            //$nuevo_proveedor = $this->input->post('proveedor');
            $proveedor = $this->input->post('proveedor');
            $idproveedor = $this->input->post('idproveedor');
            $direccion = $this->input->post('direccion');



            $rut = str_replace(".", "", $this->input->post("rut"));
            $arrayRut = explode("-", $rut);


            $array_datos = array(
                'idproveedor' => $idproveedor,
                'proveedor' => $proveedor,
                'rut' => $arrayRut[0],
                'dv' => $arrayRut[1],
                'direccion' => $direccion,
            );

            $this->load->model('admin');
            $result = $this->admin->add_proveedor($array_datos);

            if ($result == -1) {
                redirect('admins/admin_proveedor/2');
            } else {
                if ($idproveedor == 0) {
                    redirect('admins/admin_proveedor/1');
                } else {
                    redirect('admins/admin_proveedor/6');
                }
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


    public function delete_proveedor($idproveedor = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_proveedor($idproveedor);
            if ($result == -1) {
                redirect('admins/admin_proveedor/4');
            } else {
                redirect('admins/admin_proveedor/5');
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




    public function admin_esp_comunes($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Espacio Com&uacute;n Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Espacio Com&uacute;n. Espacio Com&uacute;n ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al agregar Espacio Com&uacute;n. Espacio Com&uacute;n ya est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Espacio Com&uacute;n. Espacio Com&uacute;n no est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Espacio Com&uacute;n Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Espacio Com&uacute;n Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('admin');

            $espacios_comunes = $this->admin->get_cuentas_espacios_comunes_comunidad_by_id();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Espacios Comunes'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_esp_comunes';
            $vars['espacios_comunes'] = $espacios_comunes;
            $vars['dataTables'] = true;
            $vars['formValidation'] = true;


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



    public function add_esp_comun($idespaciocomun = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $espacio_comun = $this->admin->get_cuentas_espacios_comunes_comunidad_by_id($idespaciocomun);

            $unidades_medidas = $this->admin->get_um_esp_comun_by_id();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Espacios Comunes'
            );

            $datos_form = array(
                'idespaciocomun' => is_null($espacio_comun) ? 0 : $espacio_comun->id,
                'nombre' => is_null($espacio_comun) ? '' : $espacio_comun->nombre,
                'unidadmedida' => is_null($espacio_comun) ? '' : $espacio_comun->idumespcomun,
                'monto' => is_null($espacio_comun) ? '' : $espacio_comun->monto
            );



            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_esp_comun';
            $vars['titulo'] = $idespaciocomun == '' ? "Agregar Espacio Com&uacute;n" : "Editar Espacio Com&uacute;n";
            $vars['datos_form'] = $datos_form;
            $vars['unidades_medidas'] = $unidades_medidas;
            $vars['formValidation'] = true;
            $vars['mask'] = true;


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


    public function submit_esp_comun()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $espaciocomun = $this->input->post('espaciocomun');
            $idespaciocomun = $this->input->post('idespaciocomun');
            $unidadmedida = $this->input->post('unidadmedida');
            $monto = str_replace(".", "", $this->input->post("monto"));

            $array_datos = array(
                'idespaciocomun' => $idespaciocomun,
                'espaciocomun' => $espaciocomun,
                'unidadmedida' => $unidadmedida,
                'monto' => $monto,
            );

            $this->load->model('admin');
            $result = $this->admin->add_esp_comun($array_datos);

            if ($result == -1) {
                redirect('admins/admin_esp_comunes/2');
            } else {
                if ($idespaciocomun == 0) {
                    redirect('admins/admin_esp_comunes/1');
                } else {
                    redirect('admins/admin_esp_comunes/6');
                }
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


    public function delete_esp_comun($idespaciocomun = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_esp_comun($idespaciocomun);
            if ($result == -1) {
                redirect('admins/admin_esp_comunes/4');
            } else {
                redirect('admins/admin_esp_comunes/5');
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



    public function admin_um_esp_comunes($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Unidad de Medida Agregada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Unidad de Medida. Ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al agregar Tipo de Cuenta. Tipo de Cuenta ya est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Unidad de Medida. No existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Unidad de Medida Eliminada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Unidad de Medida Editada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 7) {
                $vars['message'] = "Error al editar Unidad de Medida. S&oacute;lo se pueden editar Unidades de Medida creados para la comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }


            $this->load->model('admin');

            $unidades_medidas = $this->admin->get_um_esp_comun_by_id();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Unidades de Medidas Espacios Comunes'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_um_esp_comunes';
            $vars['unidades_medidas'] = $unidades_medidas;
            $vars['dataTables'] = true;
            $vars['formValidation'] = true;


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



    public function add_um_esp_comunes($idunidadmedida = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $unidadmedida = $this->admin->get_um_esp_comun_by_id($idunidadmedida);

            if ($idunidadmedida != 0) {
                if (!$unidadmedida) {
                    redirect('admins/admin_um_esp_comunes/7');
                } else {
                    if ($unidadmedida->idcomunidad != $this->session->userdata('comunidadid')) {
                        redirect('admins/admin_um_esp_comunes/7');
                    }
                }
            }

            /*if($idunidadmedida != 0 && $unidadmedida->idcomunidad != $this->session->userdata('comunidadid')){
				redirect('admins/admin_um_esp_comunes/7');
			}*/

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Unidades de Medida para Espacios Comunes'
            );

            $datos_form = array(
                'idunidadmedida' => is_null($unidadmedida) ? 0 : $unidadmedida->id,
                'nombre' => is_null($unidadmedida) ? '' : $unidadmedida->nombre,
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_um_esp_comunes';
            $vars['titulo'] = $idunidadmedida == '' ? "Agregar Unidad de Medida" : "Editar Unidad de Medida";
            $vars['boton'] = $idunidadmedida == '' ? "Agregar" : "Editar";
            $vars['datos_form'] = $datos_form;
            $vars['formValidation'] = true;


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



    public function submit_um_esp_comunes()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $unidadmedida = $this->input->post('unidadmedida');
            $idunidadmedida = $this->input->post('idunidadmedida');

            $array_datos = array(
                'idunidadmedida' => $idunidadmedida,
                'unidadmedida' => $unidadmedida
            );
            $this->load->model('admin');
            $result = $this->admin->add_um_esp_comunes($array_datos);

            if ($result == -1) {
                redirect('admins/admin_um_esp_comunes/2');
            } else {
                if ($idunidadmedida == 0) {
                    redirect('admins/admin_um_esp_comunes/1');
                } else {
                    redirect('admins/admin_um_esp_comunes/6');
                }
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

    public function delete_um_esp_comunes($idunidadmedida = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');


            $result = $this->admin->delete_um_esp_comun($idunidadmedida);
            //var_dump($result); exit;
            if ($result == -1) {
                redirect('admins/admin_um_esp_comunes/4');
            } else {
                redirect('admins/admin_um_esp_comunes/5');
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



    public function admin_tipos_cuenta($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Tipo de Cuenta Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Tipo de Cuenta. Tipo de Cuenta ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al agregar Tipo de Cuenta. Tipo de Cuenta ya est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Tipo de Cuenta. Tipo de Cuenta asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Tipo de Cuenta Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Tipo de Cuenta Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 7) {
                $vars['message'] = "Error al editar Tipo de Cuenta. S&oacute;lo se pueden editar Tipos de Cuenta de la comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }


            $this->load->model('admin');

            $tipos_cuenta = $this->admin->get_tipos_cuentas_comunidad_by_id();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Tipos de Cuenta'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_tipos_cuenta';
            $vars['tipos_cuenta'] = $tipos_cuenta;
            $vars['dataTables'] = true;
            $vars['formValidation'] = true;


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


    public function add_tipos_cuenta($idtipocuenta = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $tipo_cuenta = $this->admin->get_tipos_cuentas_comunidad_by_id($idtipocuenta);
            $conceptos = $this->admin->get_tipos_cuentas_comunidad_padres_by_id();
            $tipos_cuenta = $this->admin->get_clasif_tipos_cuentas_comunidad();



            /*if($idtipocuenta != 0 && $tipo_cuenta->idcomunidad != $this->session->userdata('comunidadid')){
				redirect('admins/admin_tipos_cuenta/7');
			}*/

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Tipos de Cuenta'
            );

            $datos_form = array(
                'idtipocuenta' => is_null($tipo_cuenta) ? 0 : $tipo_cuenta->id,
                'nombre' => is_null($tipo_cuenta) ? '' : $tipo_cuenta->nombre,
                'concepto' => is_null($tipo_cuenta) ? '' : $tipo_cuenta->idpadre,
                'nombreconcepto' => is_null($tipo_cuenta) ? '' : $tipo_cuenta->nombrepadre,
                'tipo_cuenta' => is_null($tipo_cuenta) ? '' : $tipo_cuenta->idclasifcuenta,
                'idcomunidad' => is_null($tipo_cuenta) ? '' : $tipo_cuenta->idcomunidad
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_tipos_cuenta';
            $vars['titulo'] = $idtipocuenta == '' ? "Agregar Tipo de Cuenta" : "Editar Tipo de Cuenta";
            $vars['datos_form'] = $datos_form;
            $vars['conceptos'] = $conceptos;
            $vars['tipos_cuenta'] = $tipos_cuenta;
            $vars['formValidation'] = true;


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


    public function submit_tipos_cuenta()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $tipocuenta = $this->input->post('tipocuenta');
            $idtipocuenta = $this->input->post('idtipocuenta');
            $concepto = $this->input->post('concepto');
            $tipo_cuenta = $this->input->post('tipo_cuenta');

            $array_datos = array(
                'idtipocuenta' => $idtipocuenta,
                'tipocuenta' => $tipocuenta,
                'concepto' => $concepto == '' ? null : $concepto,
                'idclasifcuenta' => $tipo_cuenta == '' ? null : $tipo_cuenta
            );

            $this->load->model('admin');
            $result = $this->admin->add_tipo_cuenta($array_datos);

            if ($result == -1) {
                redirect('admins/admin_tipos_cuenta/2');
            } else {
                if ($idtipocuenta == 0) {
                    redirect('admins/admin_tipos_cuenta/1');
                } else {
                    redirect('admins/admin_tipos_cuenta/6');
                }
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



    public function delete_tipos_cuenta($idtipocuenta = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_tipo_cuenta($idtipocuenta);
            if ($result == -1) {
                redirect('admins/admin_tipos_cuenta/4');
            } else {
                redirect('admins/admin_tipos_cuenta/5');
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




    public function admin_cargos()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('cargos_result');
            if ($resultid == 1) {
                $vars['message'] = "Cargo Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Cargo. Cargo ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al agregar Cargo. Cargo ya est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Cargo";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Cargo Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Cargo Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 7) {
                $vars['message'] = "Error al editar Cargo. S&oacute;lo se pueden editar cargos de la comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }


            $this->load->model('remuneracion');

            $cargos = $this->remuneracion->get_cargos();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Cargos'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_cargos';
            $vars['cargos'] = $cargos;
            $vars['dataTables'] = true;
            $vars['formValidation'] = true;


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


    public function add_cargos($idcargo = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('remuneracion');
            $cargo = $this->remuneracion->get_cargos($idcargo);
            $cargos_padres = $this->remuneracion->get_cargos_padres_by_id();

            if ($idcargo != 0 && $cargo->idcomunidad != $this->session->userdata('comunidadid')) {
                $this->session->set_flashdata('cargos_result', 7);
                redirect('admins/admin_cargos');
            }
            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Cargos'
            );

            $datos_form = array(
                'idcargo' => is_null($cargo) ? 0 : $cargo->id,
                'nombre' => is_null($cargo) ? '' : $cargo->nombre,
                'padre' => is_null($cargo) ? '' : $cargo->idpadre,
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_cargos';
            $vars['titulo'] = $idcargo == '' ? "Agregar Cargo" : "Editar Cargo";
            $vars['datos_form'] = $datos_form;
            $vars['cargos_padres'] = $cargos_padres;
            $vars['formValidation'] = true;


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


    public function submit_cargos()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $cargo = $this->input->post('cargo');
            $idcargo = $this->input->post('idcargo');
            $tipocargo = $this->input->post('tipocargo');

            $array_datos = array(
                'idcargo' => $idcargo,
                'cargo' => $cargo,
                'tipocargo' => $tipocargo == '' ? null : $tipocargo
            );

            $this->load->model('remuneracion');
            $result = $this->remuneracion->add_cargo($array_datos);

            if ($result == -1) {
                $this->session->set_flashdata('cargos_result', 2);
            } else {
                if ($idcargo == 0) {
                    $this->session->set_flashdata('cargos_result', 1);
                } else {
                    $this->session->set_flashdata('cargos_result', 6);
                }
            }
            redirect('admins/admin_cargos');
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


    public function delete_cargos($idcargo = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $result = $this->remuneracion->delete_cargo($idcargo);
            if ($result == -1) {
                $this->session->set_flashdata('cargos_result', 4);
            } else {
                $this->session->set_flashdata('cargos_result', 5);
            }

            redirect('admins/admin_cargos');
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



    public function admin_propiedades($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Propiedad Agregada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Propiedad. Propiedad ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al agregar Propiedad. Propiedad ya est&aacute; asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Propiedad. Propiedad asociado a Comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Propiedad Eliminada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Propiedad Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('admin');

            $propiedades = $this->admin->get_propiedades_comunidad();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Propiedades'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_propiedades';
            $vars['propiedades'] = $propiedades;
            $vars['dataTables'] = true;


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




    public function admin_comunidades($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('admin_comunidades_result');
            if ($resultid == 1) {
                $vars['message'] = "Comunidad Agregada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Comunidad. Comunidad ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Comunidad Editada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Comunidad. Comunidad no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Comunidad Eliminada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Claves de acceso enviadas correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 7) {
                $vars['message'] = "Error al enviar accesos. Debe indicar comunidad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 8) {
                $vars['message'] = "Pago registrado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al registrar pago.  Favor intentar nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }


            $this->load->model('admin');

            $comunidades = $this->admin->get_comunidades();


            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Comunidades'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_comunidades';
            $vars['comunidades'] = $comunidades;
            $vars['dataTables'] = true;


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


    public function add_comunidad($idcomunidad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $comunidad = $this->admin->get_comunidades($idcomunidad);
            $regiones = $this->admin->get_regiones();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Comunidades'
            );

            $datos_form = array(
                'idcomunidad' => is_null($comunidad) ? 0 : $comunidad->id,
                'nombre' => is_null($comunidad) ? '' : $comunidad->nombre,
                'rut' => is_null($comunidad) ? '' : number_format(substr($comunidad->rut . $comunidad->dv, 0, -1), 0, "", ".") . '-' . substr($comunidad->rut . $comunidad->dv, strlen($comunidad->rut . $comunidad->dv) - 1, 1),
                'direccion' => is_null($comunidad) ? '' : $comunidad->direccion,
                'idregion' => is_null($comunidad) ? '' : $comunidad->idregion,
                'idcomuna' => is_null($comunidad) ? '' : $comunidad->idcomuna,
                'fono' => is_null($comunidad) ? '' : $comunidad->fono,
                'fono2' => is_null($comunidad) ? '' : $comunidad->fono2,
                'email' => is_null($comunidad) ? '' : $comunidad->email,
                'saldo' => is_null($comunidad) ? '' : $comunidad->saldo,
                'caja' => is_null($comunidad) ? '' : $comunidad->caja,
                'fondoreserva' => is_null($comunidad) ? '' : $comunidad->fondoreserva,
                'fecinicio' => is_null($comunidad) ? date("d/m/Y") : $comunidad->fecinicio
            );

            $vars['content_menu'] = $content;
            $vars['regiones'] = $regiones;
            $vars['content_view'] = 'admin/add_comunidad';
            $vars['titulo'] = $idcomunidad == '' ? "Agregar Comunidad" : "Editar Comunidad";
            $vars['datos_form'] = $datos_form;
            $vars['formValidation'] = true;
            $vars['jqueryRut'] = true;
            $vars['mask'] = true;
            $vars['datetimepicker'] = true;


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



    public function submit_comunidades()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            //$nuevo_proveedor = $this->input->post('proveedor');
            $comunidad = $this->input->post('comunidad');
            $direccion = $this->input->post('direccion');
            $idregion = $this->input->post('region');
            $idcomuna = $this->input->post('comuna');
            $fono = $this->input->post('fono');
            $fono2 = $this->input->post('fono2');
            $email = $this->input->post('email');
            $saldo = str_replace(".", "", $this->input->post('saldo'));
            $caja = str_replace(".", "", $this->input->post('caja'));
            $fondoreserva = str_replace(".", "", $this->input->post('fondoreserva'));
            $fecinicio = $this->input->post("fecinicio");
            $idcomunidad = $this->input->post('idcomunidad');
            $fecvencimiento = date('Y-m-d', strtotime('+' . PERIODOS_GRATIS . ' month', strtotime(date("Y-m-d"))));


            $ruttitular = str_replace(".", "", $this->input->post("rutcomunidad"));
            $arrayRut = explode("-", $ruttitular);


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
                'idcomunidad' => $idcomunidad
            );

            $this->load->model('admin');
            $result = $this->admin->add_comunidad($array_datos);

            if ($result == -1) {
                $this->session->set_flashdata('admin_comunidades_result', 2);
            } else {
                if ($idcomunidad == 0) {
                    $this->session->set_flashdata('admin_comunidades_result', 1);
                } else {
                    $this->session->set_flashdata('admin_comunidades_result', 3);
                }
            }

            redirect('admins/admin_comunidades');
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


    public function delete_comunidad($idcomunidad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_comunidad($idcomunidad);
            if ($result == -1) {
                $this->session->set_flashdata('admin_comunidades_result', 4);
            } else {
                $this->session->set_flashdata('admin_comunidades_result', 5);
            }
            redirect('admins/admin_comunidades');
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



    public function admin_propiedad($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_elem = $this->input->post(NULL, true);
            $array_propiedades = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("-", $elem);
                if ($arr_el[0] == 'insuscribe') {
                    $array_propiedades[$arr_el[1]] = $value_elem;
                }
            }

            $this->load->model('admin');
            if (count($array_propiedades) > 0) {
                $this->admin->suscribir_propiedades($array_propiedades);
                $resultid = 8;
            } else {
                $resultid = $this->session->flashdata('admin_propiedad_result');
            }



            if ($resultid == 1) {
                $vars['message'] = "Propiedad Agregada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Propiedad. Propiedad ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Propiedad Editada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Propiedad. Propiedad no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Propiedad Eliminada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 7) {
                $vars['message'] = "Debe indicar propiedad";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 8) {
                $vars['message'] = "Suscripci&oacute;n actualizada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 9) {
                $vars['message'] = "Error al editar Propiedad. Favor intentar nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }






            //echo $this->session->userdata('level'); exit;
            if ($this->session->userdata('level') == 4) {

                $idcomunidad = $this->input->post('comunidad') == '' ? null : $this->input->post('comunidad');
                $comunidades = $this->admin->get_comunidades();
                $vars['comunidades'] = $comunidades;
                $vars['idcomunidad'] = $idcomunidad;
            } else {
                $idcomunidad = $this->session->userdata('level') == 1 ? $this->session->userdata('comunidadid') : null;
            }


            $propiedades = is_null($idcomunidad) ? array() : $this->admin->get_propiedades($idcomunidad);


            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Propiedades'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_propiedad';
            /*$vars['content_view'] = $this->session->userdata('level') == 4 ? 'admin/admin_propiedad_total' : 'admin/admin_propiedad';*/
            $vars['propiedades'] = $propiedades;

            $vars['dataTables'] = true;
            $vars['icheck'] = true;


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



    public function add_propiedad($idpropiedad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            /*if($idpropiedad == 0 && $this->session->userdata('level') == 1){ #SO ES ADM CONDOMINIO, NO PUEDA AGREGAR PROPIEDADES
				$this->session->set_flashdata('admin_propiedad_result', 7);
				redirect('admins/admin_propiedad');
			}*/

            $this->load->model('admin');
            $propiedad = $this->admin->get_propiedad_by_id($idpropiedad);

           // var_dump(count($propiedad)); //exit;

            $lista_email = $this->admin->get_propiedad_email_by_id($idpropiedad);

            $array_email = array();
            foreach ($lista_email as $email) {
                if ($email->email != $propiedad->mail) {
                    array_push($array_email, $email->email);
                }
            }


            $permite_editar_saldo = $this->admin->permite_editar_saldo($idpropiedad);

            $comunidades = $this->admin->get_comunidades();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Propiedades'
            );

            if($idpropiedad == 0 || $permite_editar_saldo == 1){

                $datos_form = array(
                    'idpropiedad' => 0,
                    'idcomunidad' => '',
                    'numero' => '',
                    'direccion' => '',
                    'responsable' => '',
                    'rutresponsable' => "",
                    'mail' => '',
                    'fono' => '',
                    'suscrito' => '',
                    'prorrateo' => '',
                    'saldo' => '',
                    'saldoinicial' => 0
                );



            }else{



                $datos_form = array(
                    'idpropiedad' => is_null($propiedad) ? 0 : $propiedad->id,
                    'idcomunidad' => is_null($propiedad) ? '' : $propiedad->idcomunidad,
                    'numero' => is_null($propiedad)  ? '' : $propiedad->numero,
                    'direccion' => is_null($propiedad) ? '' : $propiedad->direccion,
                    'responsable' => is_null($propiedad) ? '' : $propiedad->responsable,
                    'rutresponsable' => is_null($propiedad) ? "" : number_format($propiedad->rutresponsable, 0, ".", ".") . "-" . $propiedad->dvresponsable,
                    'mail' => is_null($propiedad) ? '' : $propiedad->mail,
                    'fono' => is_null($propiedad) ? '' : $propiedad->fono,
                    'suscrito' => is_null($propiedad) ? '' : $propiedad->suscrito,
                    'prorrateo' => is_null($propiedad) ? '' : $propiedad->prorrateo_propiedad,
                    'saldo' => is_null($propiedad) ? '' : $propiedad->saldo_publicado,
                    'saldoinicial' => is_null($propiedad) ? 0 : $propiedad->saldoinicial
                );


            }



            $vars['content_menu'] = $content;
            $vars['propiedad'] = $propiedad;
            $vars['array_email'] = $array_email;
            $vars['comunidades'] = $comunidades;
            $vars['content_view'] = 'admin/add_propiedad';
            $vars['titulo'] = $idpropiedad == 0 ? "Agregar" : "Editar";
            $vars['datos_form'] = $datos_form;
            $vars['permite_editar'] = $this->session->userdata('level') == 4 ? true : false;
            $vars['permite_editar_prop'] = $this->session->userdata('level') == 4 || ($this->session->userdata('level') == 1 && $idpropiedad == 0) ? true : false;
            $vars['permite_editar_saldo'] = $permite_editar_saldo;

            $vars['formValidation'] = true;
            $vars['icheck'] = true;
            $vars['mask'] = true;
            $vars['maleta'] = true;
            $vars['jqueryRut'] = true;

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







    public function envio_masivo_mails($idcomunidad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            set_time_limit(0);
            if ($idcomunidad == 0) {
                $this->session->set_flashdata('admin_comunidades_result', 7);
            } else {
                $this->load->model('admin');
                $cantidad_envios = $this->admin->envio_masivo_mails($idcomunidad);
                $this->session->set_flashdata('admin_comunidades_result', 6);
            }




            redirect('admins/admin_comunidades');
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

    public function envio_masivo_mails_usuarios($idcomunidad = 0, $all = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            set_time_limit(0);


            if ($all == 1) {
                $array_usuarios = null;
            } else {

                $array_elem = $this->input->post(NULL, true);
                $array_usuarios = array();
                foreach ($array_elem as $elem => $value_elem) {
                    $arr_el = explode("-", $elem);
                    if ($arr_el[0] == 'user') {
                        array_push($array_usuarios, $arr_el[1]);
                    }
                }
            }



            $this->load->model('admin');
            $cantidad_envios = $this->admin->envio_masivo_mails($this->session->userdata('comunidadid'), $array_usuarios);

            $this->session->set_flashdata('add_user_result', 6);
            redirect('admins/admin_users');
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


    public function submit_propiedad()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            //$nuevo_proveedor = $this->input->post('proveedor');



            $comunidad = $this->input->post('comunidad');
            $numpropiedad = $this->input->post('numpropiedad');
            $direccion = $this->input->post('direccion');
            $responsable = $this->input->post('responsable');
            $email = $this->input->post('email');
            $fono = $this->input->post('fono');
            $prorrateo = $this->input->post('prorrateo');
            $saldo = str_replace(".", "", $this->input->post('saldo'));
            $suscrito = $this->input->post('suscrito') == 'on' ? 1 : 0;
            $idpropiedad = $this->input->post('idpropiedad');
            $lista_email = $this->input->post('emailnuevo');
            $rutresponsablecompleto = str_replace(".", "", $this->input->post("rutresponsable"));


            if($rutresponsablecompleto != ''){
                    $arrayRutresponsable = explode("-", $rutresponsablecompleto);
                    $rutresponsable = $arrayRutresponsable[0];
                    $dvresponsable = $arrayRutresponsable[1];
            }else{
                    $rutresponsable = '';
                    $dvresponsable ='';

            }
            


            $array_email = array();
            array_push($array_email, $email);
            foreach ($lista_email as $dato_email) {
                if (!in_array($dato_email, $array_email) && $dato_email != '') {
                    array_push($array_email, $dato_email);
                }
            }

            $this->load->model('admin');
            $permite_editar_saldo = $this->admin->permite_editar_saldo($idpropiedad);

            if (!$permite_editar_saldo && $saldo != '') {
                $this->session->set_flashdata('admin_propiedad_result', 9);
                redirect('admins/admin_propiedad');
            }


            $array_datos = array(
                'comunidad' => $comunidad,
                'numpropiedad' => $numpropiedad,
                'direccion' => $direccion,
                'responsable' => $responsable,
                'rutresponsable' => $rutresponsable,
                'dvresponsable' => $dvresponsable,                
                'email' => $email,
                'fono' => $fono,
                'prorrateo' => $prorrateo,
                'saldo' => $saldo,
                'suscrito' => $suscrito,
                'idpropiedad' => $idpropiedad
            );

            $this->load->model('admin');
            $result = $this->admin->add_propiedad($array_datos, $array_email);

            if ($result == -1) {
                $this->session->set_flashdata('admin_propiedad_result', 2);
                redirect('admins/admin_propiedad');
            } else {
                if ($idpropiedad == 0) {
                    $this->session->set_flashdata('admin_propiedad_result', 1);
                    redirect('admins/admin_propiedad');
                } else {
                    $this->session->set_flashdata('admin_propiedad_result', 3);
                    redirect('admins/admin_propiedad');
                }
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



    public function carga_propiedades()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $comunidades = $this->admin->get_comunidades();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Carga Masiva de Propiedades'
            );

            $lista_propiedades = array();
            $lista_usuarios = array();
            $lista_prop_sin_confirmar = array();

            set_time_limit(0); // quita limite de tiempo al hacer carga


            //print_r($this->input->post)
            if ($this->input->post('comunidad')) {
                $comunidad_defecto = $this->input->post('comunidad');

                //print_r($this->input->post(NULL,true));exit;
                $idcomunidad = $this->input->post('comunidad');
                $tipo = $this->input->post('tipo');



                // ciclo de validacion


                if ($tipo == 'validacion') {


                    $config['upload_path'] = "./uploads/cargas/";

                    if (!file_exists($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $config['file_name'] = date("Ymd") . "_" . date("His") . "_" . randomstring(5) . "_" . $this->input->post('comunidad');
                    $config['allowed_types'] = "*";
                    $config['max_size'] = "10240";

                    $this->load->library('upload', $config);
                    $this->upload->do_upload("userfile");
                    $dataupload = $this->upload->data();

                    $extension = $dataupload['file_ext'];

                    $error_carga = false;


                    if ($extension != '.csv') {
                        $error_carga = true;
                        $msg = "Archivo debe tener formato csv";

                        $vars['message'] = $msg;
                        $vars['classmessage'] = 'danger';
                        $vars['icon'] = 'fa-ban';
                    }

                    if (!$error_carga) {
                        //file_ext contiene la extensión.  Puede servir para validar

                        /******* LECTURA DE EXCEL ********/

                          $fila = 1;
                          $encabezado = true;
                          if (($gestor = fopen($config['upload_path'] . $dataupload['orig_name'], "r")) !== FALSE) {
                              while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {

                                $numero = count($datos);

                                if(!$encabezado){

                                    $numpropiedad = isset($datos[0]) ? $datos[0] : "";
                                    $direccion = isset($datos[1]) ? $datos[1] : "";
                                    $nombre_responsable =  isset($datos[2]) ? $datos[2] : "";
                                    $apellido_responsable = isset($datos[3]) ? $datos[3] : "";
                                    $email = isset($datos[4]) ? trim($datos[4]) : "";
                                    $fono = isset($datos[5]) ? $datos[5] : 0;

                                    $prorrateo = isset($datos[6]) ? (float)str_replace(',', '.',$datos[6]) : "";
                                    $saldo_inicial = isset($datos[7]) ? (int)$datos[7] : "";
                                    if (isset($datos[8])) {
                                        $suscrito = strtoupper($datos[8]) == 'SI' ? 'SI' : 'NO';
                                    }



                                    $email = $email == '' ? 'sincorreo@notiene.cl' : $email;
                                    $ingresa = 1;

                                    if ($numpropiedad == '') {
                                        $error_carga = true;
                                        $dato_error = "N&uacute;mero Propiedad";
                                        $tipo_error = "es requerido";
                                    } else if ($nombre_responsable == '') {
                                        $error_carga = true;
                                        $dato_error = "Nombre Responsable";
                                        $tipo_error = "es requerido";
                                    } else if ($email == '') {
                                        $error_carga = true;
                                        $dato_error = "Email";
                                        $tipo_error = "es requerido";
                                    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
                                        $error_carga = true;
                                        $dato_error = "Email";
                                        $tipo_error = "no es un email v&aacute;lido";

                                        
                                    } else if (!is_numeric($prorrateo)) {
                                        $error_carga = true;
                                        $dato_error = "Prorrateo";
                                        $tipo_error = "debe ser num&eacute;rico";

                                    } else if ($prorrateo == '' && $prorrateo != '0') {

                                        $error_carga = true;
                                        $dato_error = "Prorrateo";
                                        $tipo_error = "es requerido";
                                    } else if (!is_numeric($datos[7])) {
                                        $error_carga = true;
                                        $dato_error = "Saldo Inicial";
                                        $tipo_error = "debe ser num&eacute;rico";
                                    } else if ($saldo_inicial === '') {
                                        $error_carga = true;
                                        $dato_error = "Saldo Inicial";
                                        $tipo_error = "es requerido";
                                    } else if (strtoupper($datos[8]) != 'SI' && strtoupper($datos[8]) != 'NO') {
                                        $error_carga = true;
                                        $dato_error = "Suscrito";
                                        $tipo_error = "debe indicar SI o NO";
                                    } else if ($suscrito == '') {
                                        $error_carga = true;
                                        $dato_error = "Suscrito";
                                        $tipo_error = "es requerido";
                                    }


                                    if ($error_carga) {
                                        $msg = "Error en fila " . $fila . ": Campo '" . $dato_error . "' " . $tipo_error;

                                        $vars['message'] = $msg;
                                        $vars['classmessage'] = 'danger';
                                        $vars['icon'] = 'fa-ban';
                                        break;
                                    } else {
                                        $array_datos = array(
                                            'propiedad' => $numpropiedad,
                                            'direccion' => $direccion,
                                            'nombre_responsable' => $nombre_responsable,
                                            'apellido_responsable' => $apellido_responsable,
                                            'email' => $email,
                                            'fono' => $fono,
                                            'prorrateo' => $prorrateo,
                                            'saldo' => $saldo_inicial,
                                            'suscrito' => $suscrito
                                        );
                                        array_push($lista_prop_sin_confirmar, $array_datos);
                                        $fila++;
                                    }                                    

                                    
                                }else{
                                    $encabezado = false;
                                }

                              }
                          }

                    }

                    $lista_prop_sin_confirmar = $error_carga ? array() : $lista_prop_sin_confirmar;

                    if (!$error_carga) {
                        $msg = "Informaci&oacute;n a&uacute;n no ha sido cargada.  Favor confirmar para realizar carga de propiedades";

                        $vars['message'] = $msg;
                        $vars['classmessage'] = 'warning';
                        $vars['icon'] = 'fa-warning';
                        $this->session->set_flashdata('lista_prop_sin_confirmar', $lista_prop_sin_confirmar);
                    }
                }



                //exit;
            } else { // end carga
                $lista_propiedades = $this->session->flashdata('lista_propiedades');
                $lista_usuarios = $this->session->flashdata('lista_usuarios');
                $comunidad_defecto = $this->session->userdata('comunidadid') != '' ? $this->session->userdata('comunidadid') : 0;
                if (!is_null($lista_propiedades)) {

                    $msg = "Propiedades creadas correctamente";

                    $vars['message'] = $msg;
                    $vars['classmessage'] = 'success';
                    $vars['icon'] = 'fa-check';
                }
            }




            $vars['content_menu'] = $content;
            $vars['comunidades'] = $comunidades;
            $vars['lista_propiedades'] = $lista_propiedades;
            $vars['lista_prop_sin_confirmar'] = $lista_prop_sin_confirmar;
            $vars['lista_usuarios'] = $lista_usuarios;
            $vars['content_view'] = 'admin/carga_propiedades';
            $vars['formValidation'] = true;
            $vars['dataTables'] = true;
            $vars['comunidad_defecto'] = $comunidad_defecto;
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


    public function confirma_carga_propiedades($idcomunidad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $arr_data = $this->session->flashdata('lista_prop_sin_confirmar');

            $lista_propiedades = array();
            $lista_usuarios = array();
            /******** ANALISIS DE DATOS************/
            foreach ($arr_data as $propiedad) {



                $array_email = array();
                $numpropiedad = $propiedad['propiedad'];
                $direccion = $propiedad['direccion'];
                $nombre_responsable =  $propiedad['nombre_responsable'];
                $apellido_responsable = $propiedad['apellido_responsable'];
                $email = $propiedad['email'];
                $fono = $propiedad['fono'];
                $prorrateo = $propiedad['prorrateo'];
                $saldo_inicial = $propiedad['saldo'];
                $suscrito = $propiedad['suscrito'] == 'SI' ? 1 : 0;

                $array_datos = array(
                    'comunidad' => $idcomunidad,
                    'numpropiedad' => $numpropiedad,
                    'direccion' => $direccion,
                    'responsable' => $nombre_responsable . " " . $apellido_responsable,
                    'email' => $email,
                    'fono' => $fono,
                    'prorrateo' => $prorrateo,
                    'saldo' => $saldo_inicial,
                    'suscrito' => $suscrito,
                    'idpropiedad' => 0,
                    'rutresponsable' => 0,
                    'dvresponsable' => ''
                );

                $this->load->model('admin');
                array_push($array_email, $email);
                $idpropiedad = $this->admin->add_propiedad($array_datos, $array_email);
                // si $idpropiedad == -1 no debe continuar

                //echo "idpropiedad: ".$idpropiedad."<br>";
                if ($idpropiedad != -1) { // sólo en caso de crear propiedad
                    $array_propiedades = array();
                    array_push($array_propiedades, $idpropiedad);
                    array_push($lista_propiedades, $array_datos);
                    //$email = 'rodrigog.84@gmail.com';
                    //echo $email."<br>";
                    // validar si existe mail
                    $usuario_mail = $this->admin->valida_existe_mail_user($email);

                    if (!$usuario_mail) { // si no existe se crea

                        //creacion de password
                        $password = randomstring_mm(10);

                        $additional_data = array(
                            'first_name' => $nombre_responsable,
                            'last_name'  => $apellido_responsable,
                            'company'    => '',
                            'phone'      => '',
                            'inicpass'   => $password
                        );

                        //$this->load->model('admin');
                        $userid = $this->ion_auth->register($email, $password, $email, $additional_data); // creacion de usuario
                        //echo "usuario creado: ".$userid."<br>";
                        $result = $this->ion_auth->update_level($userid, 3); //actualiza perfil

                        $this->ion_auth->asocia_propiedad($userid, $array_propiedades, false);
                        $array_user = array(
                            'nombre' => $nombre_responsable,
                            'apellido' => $apellido_responsable,
                            'email' => $email
                        );
                        array_push($lista_usuarios, $array_user);

                        // envio de mail
                        //$this->admin->mail_creacion_usuario($userid,$password);


                    } else { // si ya existe se asocia
                        $replace = false;
                        if ($usuario_mail->active == 0) {
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
                            $result = $this->ion_auth->update_level($usuario_mail->id, 3);
                            $this->ion_auth->update_password($usuario_mail->id, $password);
                            $this->ion_auth->activate($usuario_mail->id);

                            $array_user = array(
                                'nombre' => $nombre_responsable,
                                'apellido' => $apellido_responsable,
                                'email' => $email
                            );
                            array_push($lista_usuarios, $array_user);

                            // envio de mail
                            //$this->admin->mail_creacion_usuario($usuario_mail->id,$password);

                        }
                        //print_r($array_propiedades);
                        //echo "usuario asociado: ".$usuario_mail->id."<br>";
                        $this->ion_auth->asocia_propiedad($usuario_mail->id, $array_propiedades, $replace);
                    }
                } // end propiedad != -1
            } //end foreach

            $this->session->set_flashdata('lista_propiedades', $lista_propiedades);
            $this->session->set_flashdata('lista_usuarios', $lista_usuarios);
            redirect('admins/carga_propiedades');
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





    public function carga_bodegas()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $comunidades = $this->admin->get_comunidades();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Carga Masiva de Bodegas'
            );

            $lista_bodegas = array();
            $lista_usuarios = array();
            $lista_prop_sin_confirmar = array();

            set_time_limit(0); // quita limite de tiempo al hacer carga


            //print_r($this->input->post)
            if ($this->input->post('comunidad')) {
                $comunidad_defecto = $this->input->post('comunidad');

                //print_r($this->input->post(NULL,true));exit;
                $idcomunidad = $this->input->post('comunidad');
                $tipo = $this->input->post('tipo');



                // ciclo de validacion


                if ($tipo == 'validacion') {


                    $config['upload_path'] = "./uploads/cargas/";

                    if (!file_exists($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $config['file_name'] = date("Ymd") . "_" . date("His") . "_" . randomstring(5) . "_BOD_" . $this->input->post('comunidad');
                    $config['allowed_types'] = "*";
                    $config['max_size'] = "10240";

                    $this->load->library('upload', $config);
                    $this->upload->do_upload("userfile");
                    $dataupload = $this->upload->data();

                    $extension = $dataupload['file_ext'];

                    $error_carga = false;


                    if ($extension != '.xls') {
                        $error_carga = true;
                        $msg = "Archivo debe tener formato xls";

                        $vars['message'] = $msg;
                        $vars['classmessage'] = 'danger';
                        $vars['icon'] = 'fa-ban';
                    }

                    if (!$error_carga) {
                        //file_ext contiene la extensión.  Puede servir para validar

                        /******* LECTURA DE EXCEL ********/

                        $this->load->library('PHPExcel');
                        //read file from path
                        $objPHPExcel = PHPExcel_IOFactory::load($config['upload_path'] . $dataupload['orig_name']);
                        //get only the Cell Collection
                        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

                        //extract to a PHP readable array format
                        foreach ($cell_collection as $cell) {
                            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                            //header will/should be in row 1 only. of course this can be modified to suit your need.
                            if ($row == 1) {
                                $header[$row][$column] = $data_value;
                            } else {
                                $arr_data[$row][$column] = $data_value;
                            }
                        }
                        $fila = 1;


                        $dato_error = "";
                        $tipo_error = "";
                        foreach ($arr_data as $bodega) {
                            //print_r($propiedad);
                            $numbodega = isset($bodega['A']) ? $bodega['A'] : "";
                            $numpropiedad = isset($bodega['B']) ? $bodega['B'] : "";
                            $prorrateo = isset($bodega['C']) ? (float)$bodega['C'] : "";
                            $ingresa = 1;
                            //echo $fila . " : " ;

                            $propiedad = $this->admin->get_propiedad_by_numero($numpropiedad);

                            if ($numpropiedad == '') {
                                $error_carga = true;
                                $dato_error = "N&uacute;mero Propiedad";
                                $tipo_error = "es requerido";
                            } else if ($numbodega == '') {
                                $error_carga = true;
                                $dato_error = "N&uacute;mero Bodega";
                                $tipo_error = "es requerido";
                            } else if (!is_numeric($bodega['C'])) {
                                $error_carga = true;
                                $dato_error = "Prorrateo";
                                $tipo_error = "debe ser num&eacute;rico";
                            } else if ($prorrateo === '') {
                                $error_carga = true;
                                $dato_error = "Prorrateo";
                                $tipo_error = "es requerido";
                            } else if (is_null($propiedad)) {
                                $error_carga = true;
                                $dato_error = "N&uacute;mero Propiedad";
                                $tipo_error = "Propiedad No Existe";
                            }

                            if ($error_carga) {
                                $msg = "Error en fila " . $fila . ": Campo '" . $dato_error . "' " . $tipo_error;

                                $vars['message'] = $msg;
                                $vars['classmessage'] = 'danger';
                                $vars['icon'] = 'fa-ban';
                                break;
                            } else {
                                $array_datos = array(
                                    'bodega' => $numbodega,
                                    'propiedad' => $numpropiedad,
                                    'prorrateo' => $prorrateo
                                );
                                array_push($lista_prop_sin_confirmar, $array_datos);
                                $fila++;
                            }
                        }
                    }

                    $lista_prop_sin_confirmar = $error_carga ? array() : $lista_prop_sin_confirmar;

                    if (!$error_carga) {
                        $msg = "Informaci&oacute;n a&uacute;n no ha sido cargada.  Favor confirmar para realizar carga de bodegas";

                        $vars['message'] = $msg;
                        $vars['classmessage'] = 'warning';
                        $vars['icon'] = 'fa-warning';
                        $this->session->set_flashdata('lista_bod_sin_confirmar', $lista_prop_sin_confirmar);
                    }
                }



                //exit;
            } else { // end carga
                $lista_bodegas = $this->session->flashdata('lista_bodegas');
                $comunidad_defecto = $this->session->userdata('comunidadid') != '' ? $this->session->userdata('comunidadid') : 0;
                if (count($lista_bodegas) > 0) {

                    $msg = "Bodegas creadas correctamente";

                    $vars['message'] = $msg;
                    $vars['classmessage'] = 'success';
                    $vars['icon'] = 'fa-check';
                }
            }




            $vars['content_menu'] = $content;
            $vars['comunidades'] = $comunidades;
            $vars['lista_bodegas'] = $lista_bodegas;
            $vars['lista_prop_sin_confirmar'] = $lista_prop_sin_confirmar;
            $vars['lista_usuarios'] = $lista_usuarios;
            $vars['content_view'] = 'admin/carga_bodegas';
            $vars['formValidation'] = true;
            $vars['dataTables'] = true;
            $vars['comunidad_defecto'] = $comunidad_defecto;
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



    public function confirma_carga_bodegas($idcomunidad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $arr_data = $this->session->flashdata('lista_bod_sin_confirmar');
            $lista_bodegas = array();
            /******** ANALISIS DE DATOS************/
            $this->load->model('admin');
            foreach ($arr_data as $bodega) {

                $numbodega = $bodega['bodega'];
                $numpropiedad = $bodega['propiedad'];
                $prorrateo = $bodega['prorrateo'];

                $propiedades = $this->admin->get_propiedad_by_numero($numpropiedad);
                $idpropiedad = 0;
                foreach ($propiedades as $propiedad) {
                    $idpropiedad = $propiedad->id;
                }

                $array_datos = array(
                    'idbodega' => 0,
                    'idcomunidad' => $idcomunidad,
                    'idpropiedad' => $idpropiedad,
                    'numpropiedad' => $numpropiedad,
                    'nombre' => $numbodega,
                    'prorrateo' => $prorrateo
                );

                $idbodega = $this->admin->add_bodega($array_datos);
                // si $idpropiedad == -1 no debe continuar

                //echo "idpropiedad: ".$idpropiedad."<br>";
                if ($idbodega != -1) { // sólo en caso de crear propiedad
                    //$array_bodegas = array();
                    //array_push($array_bodegas, $idbodega);
                    array_push($lista_bodegas, $array_datos);
                } // end propiedad != -1
            } //end foreach

            $this->session->set_flashdata('lista_bodegas', $lista_bodegas);
            redirect('admins/carga_bodegas');
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



    public function carga_estacionamientos()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $comunidades = $this->admin->get_comunidades();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Carga Masiva de Estacionamientos'
            );

            $lista_estacionamientos = array();
            $lista_usuarios = array();
            $lista_prop_sin_confirmar = array();

            set_time_limit(0); // quita limite de tiempo al hacer carga


            //print_r($this->input->post)
            if ($this->input->post('comunidad')) {
                $comunidad_defecto = $this->input->post('comunidad');

                //print_r($this->input->post(NULL,true));exit;
                $idcomunidad = $this->input->post('comunidad');
                $tipo = $this->input->post('tipo');



                // ciclo de validacion


                if ($tipo == 'validacion') {


                    $config['upload_path'] = "./uploads/cargas/";

                    if (!file_exists($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $config['file_name'] = date("Ymd") . "_" . date("His") . "_" . randomstring(5) . "_EST_" . $this->input->post('comunidad');
                    $config['allowed_types'] = "*";
                    $config['max_size'] = "10240";

                    $this->load->library('upload', $config);
                    $this->upload->do_upload("userfile");
                    $dataupload = $this->upload->data();

                    $extension = $dataupload['file_ext'];

                    $error_carga = false;


                    if ($extension != '.xls') {
                        $error_carga = true;
                        $msg = "Archivo debe tener formato xls";

                        $vars['message'] = $msg;
                        $vars['classmessage'] = 'danger';
                        $vars['icon'] = 'fa-ban';
                    }

                    if (!$error_carga) {
                        //file_ext contiene la extensión.  Puede servir para validar

                        /******* LECTURA DE EXCEL ********/

                        $this->load->library('PHPExcel');
                        //read file from path
                        $objPHPExcel = PHPExcel_IOFactory::load($config['upload_path'] . $dataupload['orig_name']);
                        //get only the Cell Collection
                        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

                        //extract to a PHP readable array format
                        foreach ($cell_collection as $cell) {
                            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                            //header will/should be in row 1 only. of course this can be modified to suit your need.
                            if ($row == 1) {
                                $header[$row][$column] = $data_value;
                            } else {
                                $arr_data[$row][$column] = $data_value;
                            }
                        }
                        $fila = 1;


                        $dato_error = "";
                        $tipo_error = "";
                        foreach ($arr_data as $estacionamiento) {
                            //print_r($propiedad);
                            $numestacionamiento = isset($estacionamiento['A']) ? $estacionamiento['A'] : "";
                            $numpropiedad = isset($estacionamiento['B']) ? $estacionamiento['B'] : "";
                            $prorrateo = isset($estacionamiento['C']) ? (float)$estacionamiento['C'] : "";
                            $ingresa = 1;
                            //echo $fila . " : " ;

                            $propiedad = $this->admin->get_propiedad_by_numero($numpropiedad);

                            if ($numpropiedad == '') {
                                $error_carga = true;
                                $dato_error = "N&uacute;mero Propiedad";
                                $tipo_error = "es requerido";
                            } else if ($numestacionamiento == '') {
                                $error_carga = true;
                                $dato_error = "N&uacute;mero Estacionamiento";
                                $tipo_error = "es requerido";
                            } else if (!is_numeric($estacionamiento['C'])) {
                                $error_carga = true;
                                $dato_error = "Prorrateo";
                                $tipo_error = "debe ser num&eacute;rico";
                            } else if ($prorrateo === '') {
                                $error_carga = true;
                                $dato_error = "Prorrateo";
                                $tipo_error = "es requerido";
                            } else if (is_null($propiedad)) {
                                $error_carga = true;
                                $dato_error = "N&uacute;mero Propiedad";
                                $tipo_error = "Propiedad No Existe";
                            }

                            if ($error_carga) {
                                $msg = "Error en fila " . $fila . ": Campo '" . $dato_error . "' " . $tipo_error;

                                $vars['message'] = $msg;
                                $vars['classmessage'] = 'danger';
                                $vars['icon'] = 'fa-ban';
                                break;
                            } else {
                                $array_datos = array(
                                    'estacionamiento' => $numestacionamiento,
                                    'propiedad' => $numpropiedad,
                                    'prorrateo' => $prorrateo
                                );
                                array_push($lista_prop_sin_confirmar, $array_datos);
                                $fila++;
                            }
                        }
                    }

                    $lista_prop_sin_confirmar = $error_carga ? array() : $lista_prop_sin_confirmar;

                    if (!$error_carga) {
                        $msg = "Informaci&oacute;n a&uacute;n no ha sido cargada.  Favor confirmar para realizar carga de estacionamientos";

                        $vars['message'] = $msg;
                        $vars['classmessage'] = 'warning';
                        $vars['icon'] = 'fa-warning';
                        $this->session->set_flashdata('lista_est_sin_confirmar', $lista_prop_sin_confirmar);
                    }
                }



                //exit;
            } else { // end carga
                $lista_estacionamientos = $this->session->flashdata('lista_estacionamientos');
                $comunidad_defecto = $this->session->userdata('comunidadid') != '' ? $this->session->userdata('comunidadid') : 0;
                if (count($lista_estacionamientos) > 0) {

                    $msg = "Estacionamientos creados correctamente";

                    $vars['message'] = $msg;
                    $vars['classmessage'] = 'success';
                    $vars['icon'] = 'fa-check';
                }
            }




            $vars['content_menu'] = $content;
            $vars['comunidades'] = $comunidades;
            $vars['lista_estacionamientos'] = $lista_estacionamientos;
            $vars['lista_prop_sin_confirmar'] = $lista_prop_sin_confirmar;
            $vars['lista_usuarios'] = $lista_usuarios;
            $vars['content_view'] = 'admin/carga_estacionamientos';
            $vars['formValidation'] = true;
            $vars['dataTables'] = true;
            $vars['comunidad_defecto'] = $comunidad_defecto;
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



    public function confirma_carga_estacionamientos($idcomunidad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $arr_data = $this->session->flashdata('lista_est_sin_confirmar');
            $lista_estacionamientos = array();
            /******** ANALISIS DE DATOS************/
            $this->load->model('admin');
            foreach ($arr_data as $estacionamiento) {

                $numestacionamiento = $estacionamiento['estacionamiento'];
                $numpropiedad = $estacionamiento['propiedad'];
                $prorrateo = $estacionamiento['prorrateo'];

                $propiedades = $this->admin->get_propiedad_by_numero($numpropiedad);
                $idpropiedad = 0;
                foreach ($propiedades as $propiedad) {
                    $idpropiedad = $propiedad->id;
                }

                $array_datos = array(
                    'idestacionamiento' => 0,
                    'idcomunidad' => $idcomunidad,
                    'idpropiedad' => $idpropiedad,
                    'numpropiedad' => $numpropiedad,
                    'nombre' => $numestacionamiento,
                    'prorrateo' => $prorrateo
                );

                $idbodega = $this->admin->add_estacionamiento($array_datos);
                // si $idpropiedad == -1 no debe continuar

                //echo "idpropiedad: ".$idpropiedad."<br>";
                if ($idbodega != -1) { // sólo en caso de crear propiedad
                    //$array_bodegas = array();
                    //array_push($array_bodegas, $idbodega);
                    array_push($lista_estacionamientos, $array_datos);
                } // end propiedad != -1
            } //end foreach

            $this->session->set_flashdata('lista_estacionamientos', $lista_estacionamientos);
            redirect('admins/carga_estacionamientos');
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


    public function delete_propiedad($idpropiedad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_propiedad($idpropiedad);
            if ($result == -1) {
                $this->session->set_flashdata('admin_propiedad_result', 4);
                redirect('admins/admin_propiedad');
            } else {
                $this->session->set_flashdata('admin_propiedad_result', 5);
                redirect('admins/admin_propiedad');
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


    public function delete_movimiento($tipo_movimiento, $idmovimiento = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            //$this->load->model('account');
            //$movimiento = $this->account->get_movimiento_by_id($idmovimiento);
            $this->load->model('account');
            $movimiento = $tipo_movimiento == 'p' ? $this->account->get_mov_pago_by_id($idmovimiento) : $this->account->get_mov_abono_by_id($idmovimiento);

            if (is_null($movimiento)) {
                redirect('main/dashboard');
            }

            //var_dump($movimiento); exit;
            if (strpos($movimiento->glosa, 'Protesto') !== false) {
                $this->session->set_flashdata('movcaja_result', 3);
                redirect('payments/conciliacion');
            }


            $this->session->keep_flashdata('tipoconciliacion_conc');
            $this->session->keep_flashdata('fechadesde_conc');
            $this->session->keep_flashdata('fechahasta_conc');


            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Eliminaci&oacute;n de Movimiento'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/delete_movimiento';
            $vars['movimiento'] = $movimiento;
            $vars['formValidation'] = true;
            $vars['icheck'] = true;
            $vars['datetimepicker'] = true;


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


    public function submit_delete_movimiento($idpropiedad = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_datos = array(
                'motivo' => $this->input->post('motivo'),
                'folio' => $this->input->post('folio'),
                'idmovimiento' => $this->input->post('movimientoid'),
                'fecprotesto' => $this->input->post('fecprotesto'),
                'descripcion' => $this->input->post('descripcion'),
                'tipo_movimiento' => $this->input->post("tipo_movimiento")
            );

            $this->load->model('admin');
            $result = $this->admin->delete_movimiento($array_datos);

            $this->session->keep_flashdata('tipoconciliacion_conc');
            $this->session->keep_flashdata('fechadesde_conc');
            $this->session->keep_flashdata('fechahasta_conc');

            if ($result == 1) {
                $this->session->set_flashdata('movcaja_result', 1);
            } else if ($result == -1) {
                $this->session->set_flashdata('movcaja_result', 5);
            } else {
                $this->session->set_flashdata('movcaja_result', 2);
            }

            redirect('payments/conciliacion');
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


    public function submit_conciliacion_movimiento()
    {



        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $fecha_conciliacion = $this->input->post("fecconciliacion");
            $movimiento = $this->input->post("movimientoid");
            $tipo_movimiento = $this->input->post("tipo_movimiento");
            $eliminar = $this->input->post("eliminar");
            $array_conciliacion = array(
                'fecha_conciliacion' => $eliminar == 'on' ? null : $fecha_conciliacion,
                'movimiento' => $movimiento,
                'tipo_movimiento' => $tipo_movimiento
            );

            $this->session->keep_flashdata('tipoconciliacion_conc');
            $this->session->keep_flashdata('fechadesde_conc');
            $this->session->keep_flashdata('fechahasta_conc');

            $this->load->model('admin');
            $result = $this->admin->put_conciliacion($array_conciliacion);


            $this->session->set_flashdata('movcaja_result', 4);
            redirect('payments/conciliacion');
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


    public function conciliar_movimiento($tipo_movimiento, $idmovimiento = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('account');

            if ($tipo_movimiento == 'p') {
                $movimiento = $this->account->get_mov_pago_by_id($idmovimiento);
            } else if ($tipo_movimiento == 'a') {
                $movimiento = $this->account->get_mov_abono_by_id($idmovimiento);
            } else {
                $movimiento = $this->account->get_movimiento_by_id($idmovimiento);
            }


            $this->session->keep_flashdata('tipoconciliacion_conc');
            $this->session->keep_flashdata('fechadesde_conc');
            $this->session->keep_flashdata('fechahasta_conc');


            //$movimiento = $tipo_movimiento == 'p' ? $this->account->get_mov_pago_by_id($idmovimiento) : $this->account->get_mov_abono_by_id($idmovimiento);
            //var_dump($movimiento); exit;

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Conciliaci&oacute;n de Movimiento'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/conciliar_movimiento';
            $vars['movimiento'] = $movimiento;
            $vars['formValidation'] = true;
            $vars['datetimepicker'] = true;
            $vars['icheck'] = true;


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



    public function pay_account($idcomunidad)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Pago de Cuenta'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/pay_account';
            $vars['idcomunidad'] = $idcomunidad;
            $vars['formValidation'] = true;
            $vars['datetimepicker'] = true;
            $vars['icheck'] = true;


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


    public function submit_pay_account()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            //$nuevo_proveedor = $this->input->post('proveedor');

            $fecpago = $this->input->post('fecpago');
            $numpagos = $this->input->post('numpagos');
            $idcomunidad = $this->input->post('idcomunidad');

            $array_datos = array(
                'fecpago' => $fecpago,
                'numpagos' => $numpagos,
                'idcomunidad' => $idcomunidad
            );

            $this->load->model('admin');
            $result = $this->admin->add_pay($array_datos);
            $result = 1;

            if ($result == 1) {
                $this->session->set_flashdata('admin_comunidades_result', 8);
            } else {
                $this->session->set_flashdata('admin_comunidades_result', 9);
            }

            redirect('admins/admin_comunidades');
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

    public function admin_bodega($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Bodega Agregada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Bodega. Bodega ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Bodega Editada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Bodega. Bodega no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Bodega Eliminada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('admin');

            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 5 ? $this->session->userdata('comunidadid') : null;
            $bodegas = $this->admin->get_bodegas($idcomunidad);


            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Bodegas'
            );

            $vars['permite_editar'] = $this->session->userdata('level') === '1' || $this->session->userdata('level') === '4' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_bodega';
            $vars['bodegas'] = $bodegas;
            $vars['dataTables'] = true;


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



    public function add_bodega($idbodega = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $bodega = $this->admin->get_bodega_by_id($idbodega);
            $comunidades = $this->admin->get_comunidades();



            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Bodegas'
            );

            $datos_form = array(
                'idbodega' => is_null($bodega) ? 0 : $bodega->id,
                'nombre' => is_null($bodega) ? '' : $bodega->nombre,
                'idpropiedad' => is_null($bodega) ? 0 : $bodega->idpropiedad,
                'idcomunidad' => is_null($bodega) ? '' : $bodega->idcomunidad,
                'prorrateo' => is_null($bodega) ? '' : $bodega->prorrateo,
            );

            $vars['content_menu'] = $content;
            $vars['bodega'] = $bodega;
            $vars['comunidades'] = $comunidades;
            $vars['content_view'] = 'admin/add_bodega';
            $vars['titulo'] = $idbodega == '' ? "Agregar Bodega" : "Editar Bodega";
            $vars['datos_form'] = $datos_form;
            $vars['permite_editar'] = $this->session->userdata('level') == 4 ? true : false;
            $vars['formValidation'] = true;


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



    public function submit_bodega()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            //$nuevo_proveedor = $this->input->post('proveedor');
            $idbodega = $this->input->post('idbodega');
            $comunidad = $this->input->post('comunidad');
            $propiedad = $this->input->post('propiedad');
            $nombre = $this->input->post('nombre');
            $prorrateo = $this->input->post('prorrateo');

            $array_datos = array(
                'idbodega' => $idbodega,
                'idcomunidad' => $comunidad,
                'idpropiedad' => $propiedad,
                'nombre' => $nombre,
                'prorrateo' => $prorrateo
            );

            $this->load->model('admin');
            $result = $this->admin->add_bodega($array_datos);

            if ($result == -1) {
                redirect('admins/admin_bodega/2');
            } else {
                if ($idbodega == 0) {
                    redirect('admins/admin_bodega/1');
                } else {
                    redirect('admins/admin_bodega/3');
                }
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


    public function delete_bodega($idbodega = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_bodega($idbodega);
            if ($result == -1) {
                redirect('admins/admin_bodega/4');
            } else {
                redirect('admins/admin_bodega/5');
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




    public function admin_estacionamiento($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Estacionamiento Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Estacionamiento. Estacionamiento ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Estacionamiento Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Estacionamiento. Estacionamiento no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Estacionamiento Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Estacionamiento se encontraba desactivado. Ha sido activado";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('admin');
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 5 ? $this->session->userdata('comunidadid') : null;
            $estacionamientos = $this->admin->get_estacionamientos($idcomunidad);

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Estacionamientos'
            );

            $vars['permite_editar'] = $this->session->userdata('level') === '1' || $this->session->userdata('level') == '4' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_estacionamiento';
            $vars['estacionamientos'] = $estacionamientos;
            $vars['dataTables'] = true;

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



    public function admin_fondos($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Fondo Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Fondo. Fondo ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Fondo Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Fondo. Fondo no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Fondo Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Fondo se encontraba desactivado. Ha sido activado";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('admin');
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 5 ? $this->session->userdata('comunidadid') : null;
            $fondos = $this->admin->get_fondos($idcomunidad);

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Fondos'
            );

            $vars['permite_editar'] = $this->session->userdata('level') === '1' || $this->session->userdata('level') == '4' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_fondos';
            $vars['fondos'] = $fondos;
            $vars['dataTables'] = true;

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



    public function add_fondo($idfondo = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $fondo = $this->admin->get_fondo_by_id($idfondo);

            if(count($fondo) > 0){

                $fondo = $fondo[0];
            }

            //var_dump($fondo); exit;

            $comunidades = $this->admin->get_comunidades();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Fondos'
            );

            $datos_form = array(
                'idfondo' => isset($fondo->id) == 0 ? 0 : $fondo->id,
                'nombre' => isset($fondo->id) == 0 ? '' : $fondo->nombre,
                'idcomunidad' => isset($fondo->id) == 0 ? '' : $fondo->idcomunidad
            );

            $vars['content_menu'] = $content;
            $vars['fondo'] = $fondo;
            $vars['comunidades'] = $comunidades;
            $vars['content_view'] = 'admin/add_fondo';
            $vars['titulo'] = $idfondo == '' ? "Agregar Fondo" : "Editar Fondo";
            $vars['datos_form'] = $datos_form;
            $vars['permite_editar'] = $this->session->userdata('level') == 4 ? true : false;
            $vars['formValidation'] = true;


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


public function submit_fondo()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            //$nuevo_proveedor = $this->input->post('proveedor');
            $idfondo = $this->input->post('idfondo');
            $comunidad = $this->input->post('comunidad');
            $nombre = $this->input->post('nombre');

            $array_datos = array(
                'idfondo' => $idfondo,
                'idcomunidad' => $comunidad,
                'nombre' => $nombre,
            );

            $this->load->model('admin');
            $result = $this->admin->add_fondo($array_datos);

            if ($result == -1) {
                redirect('admins/admin_fondos/2');
            } elseif ($result == -2) {
                redirect('admins/admin_fondos/6');
            } else {
                if ($idfondo == 0) {
                    redirect('admins/admin_fondos/1');
                } else {
                    redirect('admins/admin_fondos/3');
                }
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


    public function add_estacionamiento($idestacionamiento = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $estacionamiento = $this->admin->get_estacionamiento_by_id($idestacionamiento);
            $comunidades = $this->admin->get_comunidades();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Estacionamientos'
            );

            $datos_form = array(
                'idestacionamiento' => is_null($estacionamiento) ? 0 : $estacionamiento->id,
                'nombre' => is_null($estacionamiento) ? '' : $estacionamiento->nombre,
                'idpropiedad' => is_null($estacionamiento) ? 0 : $estacionamiento->idpropiedad,
                'idcomunidad' => is_null($estacionamiento) ? '' : $estacionamiento->idcomunidad,
                'prorrateo' => is_null($estacionamiento) ? '' : $estacionamiento->prorrateo,
            );

            $vars['content_menu'] = $content;
            $vars['estacionamiento'] = $estacionamiento;
            $vars['comunidades'] = $comunidades;
            $vars['content_view'] = 'admin/add_estacionamiento';
            $vars['titulo'] = $idestacionamiento == '' ? "Agregar Estacionamiento" : "Editar Estacionamiento";
            $vars['datos_form'] = $datos_form;
            $vars['permite_editar'] = $this->session->userdata('level') == 4 ? true : false;
            $vars['formValidation'] = true;


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



    public function submit_estacionamiento()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            //$nuevo_proveedor = $this->input->post('proveedor');
            $idestacionamiento = $this->input->post('idestacionamiento');
            $comunidad = $this->input->post('comunidad');
            $propiedad = $this->input->post('propiedad');
            $nombre = $this->input->post('nombre');
            $prorrateo = $this->input->post('prorrateo');

            $array_datos = array(
                'idestacionamiento' => $idestacionamiento,
                'idcomunidad' => $comunidad,
                'idpropiedad' => $propiedad,
                'nombre' => $nombre,
                'prorrateo' => $prorrateo
            );

            $this->load->model('admin');
            $result = $this->admin->add_estacionamiento($array_datos);

            if ($result == -1) {
                redirect('admins/admin_estacionamiento/2');
            } elseif ($result == 1) {
                redirect('admins/admin_estacionamiento/6');
            } else {
                if ($idbodega == 0) {
                    redirect('admins/admin_estacionamiento/1');
                } else {
                    redirect('admins/admin_estacionamiento/3');
                }
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


public function delete_fondo($idfondo = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_fondo($idfondo);
            if ($result == -1) {
                redirect('admins/admin_fondos/4');
            } else {
                redirect('admins/admin_fondos/5');
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

    public function delete_estacionamiento($idestacionamiento = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_estacionamiento($idestacionamiento);
            if ($result == -1) {
                redirect('admins/admin_estacionamiento/4');
            } else {
                redirect('admins/admin_estacionamiento/5');
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

    public function admin_estacionamiento_visita()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('resultid');

            if ($resultid == 1) {
                $vars['message'] = "Estacionamiento Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Estacionamiento. Estacionamiento ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Estacionamiento Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Estacionamiento. Estacionamiento no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Estacionamiento Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Estacionamiento se encontraba desactivado. Ha sido activado";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }

            $this->load->model('admin');
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level') == 5 ? $this->session->userdata('comunidadid') : null;
            $estacionamientos = $this->admin->get_estacionamientos_visitas($idcomunidad);

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Estacionamientos de Visitas'
            );

            $vars['permite_editar'] = $this->session->userdata('level') === '1' || $this->session->userdata('level') == '4' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_estacionamiento_visita';
            $vars['estacionamientos'] = $estacionamientos;
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



    public function add_estacionamiento_visita($idestacionamiento = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $estacionamiento = $this->admin->get_estacionamiento_visita_by_id($idestacionamiento);
            $comunidades = $this->admin->get_comunidades();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Estacionamientos de Visitas'
            );

            $datos_form = array(
                'idestacionamiento' => is_null($estacionamiento) ? 0 : $estacionamiento->id,
                'nombre' => is_null($estacionamiento) ? '' : $estacionamiento->nombre,
                'idcomunidad' => is_null($estacionamiento) ? '' : $estacionamiento->idcomunidad,
            );

            $vars['content_menu'] = $content;
            $vars['estacionamiento'] = $estacionamiento;
            $vars['comunidades'] = $comunidades;
            $vars['content_view'] = 'admin/add_estacionamiento_visita';
            $vars['titulo'] = $idestacionamiento == '' ? "Agregar Estacionamiento" : "Editar Estacionamiento";
            $vars['datos_form'] = $datos_form;
            $vars['permite_editar'] = $this->session->userdata('level') == 4 ? true : false;
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



    public function submit_estacionamiento_visita()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $idestacionamiento = $this->input->post('idestacionamiento');
            $comunidad = $this->input->post('comunidad');
            $nombre = $this->input->post('nombre');

            $array_datos = array(
                'idestacionamiento' => $idestacionamiento,
                'idcomunidad' => $comunidad,
                'nombre' => $nombre,
            );

            $this->load->model('admin');
            $result = $this->admin->add_estacionamiento_visita($array_datos);

            if ($result == -1) {
                $this->session->set_flashdata('resultid', 2);
                redirect('admins/admin_estacionamiento_visita');
            } elseif ($result == 1) {
                $this->session->set_flashdata('resultid', 6);
                redirect('admins/admin_estacionamiento_visita');
            } else {
                if ($idestacionamiento == 0) {
                    $this->session->set_flashdata('resultid', 1);
                    redirect('admins/admin_estacionamiento_visita');
                } else {
                    $this->session->set_flashdata('resultid', 3);
                    redirect('admins/admin_estacionamiento_visita');
                }
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


    public function delete_estacionamiento_visita($idestacionamiento = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_estacionamiento_visita($idestacionamiento);
            if ($result == -1) {
                $this->session->set_flashdata('resultid', 4);
                redirect('admins/admin_estacionamiento_visita');
            } else {
                $this->session->set_flashdata('resultid', 5);
                redirect('admins/admin_estacionamiento_visita');
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

    public function admin_users($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('add_user_result');
            if ($resultid == 1) {
                $vars['message'] = "Usuario Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Usuario. Usuario ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Usuario Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Usuario. Usuario no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Usuario Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Datos de accesos enviados correctamente a usuarios seleccionados";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('admin');

            $users = $this->admin->get_users(null, null, false);
            $users_sin_notificar = $this->admin->get_users(null, false, false);

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Usuarios'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_users';
            $vars['users'] = $users;
            $vars['users_sin_notificar'] = $users_sin_notificar;
            $vars['dataTables'] = true;
            $vars['icheck'] = true;

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



    //create a new user
    public function add_user($iduser = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            //$user = $this->admin->get_user_by_id($iduser);
            $user = $this->admin->get_users($iduser);
            $comunidades = $this->admin->get_comunidades();

            $idcomunidad = '';
            $array_comunidades = array();
            if (!is_null($user)) {
                if (isset($user->level)) {
                    if ($user->level == 1) {
                        $lista_comunidad = $this->admin->comunidades_asignadas($user->id, $user->level);
                        if (isset($lista_comunidad->id)) {
                            array_push($array_comunidades, $lista_comunidad->id);
                        } else if (count($lista_comunidad) > 1) {
                            foreach ($lista_comunidad as $comunidad) {
                                array_push($array_comunidades, $comunidad->id);
                            }
                            //$comunidad = $comunidad[0];
                            //$idcomunidad = $comunidad->id;
                        }
                    }
                }
            }

            $idpropiedad = '';
            $array_propiedades = array();
            if (!is_null($user)) {
                if (isset($user->level)) {
                    if ($user->level == 3 || $user->level == 2) {
                        $propiedades = $this->admin->propiedades_asignadas($user->id);
                        if (isset($propiedades->id)) {
                            array_push($array_propiedades, array(
                                'idcomunidad' => $propiedades->idcomunidad,
                                'idpropiedad' => $propiedades->id
                            ));
                            //$idpropiedad = $propiedad->id;
                        } else if (count($propiedades) > 1) {
                            foreach ($propiedades as $propiedad) {
                                array_push($array_propiedades, array(
                                    'idcomunidad' => $propiedad->idcomunidad,
                                    'idpropiedad' => $propiedad->id
                                ));
                            }

                            //$propiedad = $propiedad[0];
                            //$idpropiedad = $propiedad->id;
                        }
                    }
                }
            }


            $perfiles = $this->admin->get_perfiles();

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Propiedades'
            );


            $datos_form = array(
                'iduser' => is_null($user) ? 0 : $user->id,
                'nombre' => is_null($user) ? '' : $user->first_name,
                'apellido' => is_null($user) ? '' : $user->last_name,
                'email' => is_null($user) ? '' : $user->email,
                'perfil' => is_null($user) ? '' : $user->level,
                'idcomunidad' => $idcomunidad,
                'idpropiedad' => $idpropiedad
            );



            $array_comunidades_2 = array();
            if($this->session->userdata('comunidadid') == ''){
                $array_comunidades_2 = $array_comunidades;
            }else{
                foreach($array_comunidades as $list_comunidad){
                    if($list_comunidad == $this->session->userdata('comunidadid')){
                        array_push($array_comunidades_2,$list_comunidad);    
                    }
                    
                }
            }
            
            $array_propiedades_2 = array();
           // var_dump($this->session->userdata('comunidadid'));

            if($this->session->userdata('comunidadid') == ''){
                $array_propiedades_2 = $array_propiedades;
            }else{
                foreach($array_propiedades as $list_propiedad){
                    if($list_propiedad['idcomunidad'] == $this->session->userdata('comunidadid')){
                        array_push($array_propiedades_2,$list_propiedad);    
                    }
                    
                }
            }


            //var_dump($array_propiedades_2); exit;
            $vars['content_menu'] = $content;
            $vars['comunidades'] = $comunidades;
            $vars['listado_comunidades'] = $array_comunidades_2;
            $vars['listado_propiedades'] = $array_propiedades_2;
            $vars['perfiles'] = $perfiles;
            $vars['content_view'] = 'admin/add_user';
            $vars['titulo'] = $iduser == '' ? "Agregar Usuario" : "Editar Usuario";
            $vars['datos_form'] = $datos_form;
            $vars['formValidation'] = true;

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




    public function submit_user()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            // agregar guardado de cuenta asociado al monto
            $nombre = $this->input->post('nombre');
            $apellido = $this->input->post('apellido');
            $email = $this->input->post('email');
            $perfil = $this->input->post('perfil');
            //$idcomunidad = $this->input->post('comunidad');
            //$idpropiedad = $this->input->post('propiedad');
            $password = $this->input->post('password');
            $iduser = $this->input->post('iduser');

            $array_elem = $this->input->post(NULL, true); 

            $array_comunidades = array();
            $array_propiedades = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("-", $elem);

                if ($perfil == 1) { // Administrador comunidad (asociar condominios)
                    if ($arr_el[0] == 'comunidad') {
                        if ($value_elem != 0) {
                            array_push($array_comunidades, $value_elem);
                        }
                    }
                } else if ($perfil == 3 || $perfil == 2) { // Propietario (asociar propiedades)

                    if ($arr_el[0] == 'propiedad') {
                        if ($value_elem != 0) {
                            array_push($array_propiedades, $value_elem);
                        }
                    }
                }
            }


            // en caso de crear un usuario asociado a un mail ya existente, se obtiene el id de ese usuario
            $this->load->model('admin');
            $existe_mail = $this->admin->valida_existe_mail_user($email);

            if (!$existe_mail) {
                $iduser = $iduser;
                $activa_antiguo = false;
            } else {
                $iduser = $existe_mail->id;
                $activa_antiguo = true;
            }


            $additional_data = array(
                'first_name' => $nombre,
                'last_name'  => $apellido,
                'company'    => '',
                'phone'      => '',
            );

            //$this->load->model('admin');
            $userid = $iduser == 0 ? $this->ion_auth->register($email, $password, $email, $additional_data) : $this->ion_auth->update($iduser, $additional_data); // creacion/actualizacion de usuario

            $userid = $iduser == 0 ? $userid : $iduser;

            $result = $this->ion_auth->update_level($userid, $perfil); //actualiza perfil

            //en caso de crear un usuario asociado a un mail ya existente, aparte de activarlo, se debe actualizar password
            if ($activa_antiguo) {
                $this->ion_auth->update_password($iduser, $password);
                $this->ion_auth->activate($iduser);
            }

            // envio de mail
            if ($iduser == 0 || $activa_antiguo) {
                $this->load->model('admin');
                $this->admin->mail_creacion_usuario($userid, $password);
            }

            if ($perfil == 1) { // asocia comunidad
                $this->ion_auth->asocia_comunidad($userid, $array_comunidades);
            } else if ($perfil == 3 || $perfil == 2) { // asocia comunidad y propidad

               // $this->ion_auth->asocia_comunidad($userid, [$this->session->userdata('comunidadid')]);
                $this->ion_auth->asocia_propiedad($userid, $array_propiedades);
            }

            if (!$userid) {
                $this->session->set_flashdata('add_user_result', 2);
                redirect('admins/admin_users');
            } else {
                if ($iduser == 0) {
                    $this->session->set_flashdata('add_user_result', 1);
                    redirect('admins/admin_users');
                } else {
                    $this->session->set_flashdata('add_user_result', 3);
                    redirect('admins/admin_users');
                }
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


    public function delete_user($userid = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $result = $this->ion_auth->delete_user($userid);
            if ($result == -1) {
                redirect('admins/admin_users/4');
            } else {
                redirect('admins/admin_users/5');
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


    public function validate_email_user($data = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $email = $this->input->post('email');
            $iduser = $this->input->post('iduser');

            $this->load->model('admin');
            $existe = $this->admin->valida_existe_mail($email, $iduser);
            $data = array();
            if ($existe) {
                $data['result'] = "error";
                $data['fields']['email'] = "Email ya est&aacute; asociado a otro usuario.    Favor contactar con el administrador";
            } else {
                $data['result'] = "ok";
            }

            echo json_encode($data);
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



    public function validate_password_user($data = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $result = $this->ion_auth->hash_password_db($this->session->userdata('user_id'), $this->input->post('password_actual'));
            $data = array();
            if (!$result) {
                $data['result'] = "error";
                $data['fields']['password_actual'] = "Clave actual es incorrecta";
            } else {
                $data['result'] = "ok";
            }

            echo json_encode($data);
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

    public function validate_property_number($data = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $comunidadid = $this->input->post('comunidad');
            $numpropiedad = $this->input->post('numpropiedad');
            $idpropiedad = $this->input->post('idpropiedad');



            $this->load->model('admin');
            $existe = $this->admin->valida_existe_propiedad($comunidadid, $numpropiedad, $idpropiedad);
            $data = array();
            if ($existe) {
                $data['result'] = "error";
                $data['fields']['numpropiedad'] = "Propiedad ya existe en la comunidad";
            } else {
                $data['result'] = "ok";
            }

            echo json_encode($data);
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

    public function ver_unidades_asociadas($idpropiedad)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $this->load->model('admin');
            $propiedad = $this->admin->get_propiedad_by_id($idpropiedad);
            $estacionamientos = $this->admin->get_estacionamientos_by_propiedad($idpropiedad);
            $bodegas = $this->admin->get_bodegas_by_propiedad($idpropiedad);


            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Ver Unidades Propiedad'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/ver_unidades_asociadas';
            $vars['propiedad'] = $propiedad;
            $vars['estacionamientos'] = $estacionamientos;
            $vars['bodegas'] = $bodegas;
            $vars['dataTables'] = true;


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



    public function cambio_clave($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($resultid == 1) {
                $vars['message'] = "Clave actualizada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }

            $content = array(
                'menu' => 'Mi Cuenta',
                'title' => 'Mi Cuenta',
                'subtitle' => 'Cambio de clave'
            );



            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/cambio_clave';
            $vars['formValidation'] = true;

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



    public function submit_clave()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            // agregar guardado de cuenta asociado al monto

            $password = $this->input->post('password_nueva');
            $userid = $this->ion_auth->update_password($this->session->userdata('user_id'), $password);

            redirect('admins/cambio_clave/1');
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


    public function profile($iduser = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $this->load->model('admin');
            //$idusuario = is_null($iduser) ?  $this->session->userdata('user_id') : $iduser;
            $idusuario = $this->session->userdata('user_id');
            //$user = $this->admin->get_user_by_id($iduser);
            $user = $this->admin->get_users($idusuario);

            $idcomunidad = '';
            $array_comunidades = array();
            if (!is_null($user)) {
                if (isset($user->level)) {
                    if ($user->level == 1) {
                        $lista_comunidad = $this->admin->comunidades_asignadas($user->id, $user->level);
                        if (count($lista_comunidad) == 1) {
                            array_push($array_comunidades, $lista_comunidad->nombre);
                        } else if (count($lista_comunidad) > 1) {
                            foreach ($lista_comunidad as $comunidad) {
                                array_push($array_comunidades, $comunidad->nombre);
                            }
                        }
                    }
                }
            }

            $idpropiedad = '';
            $array_propiedades = array();
            if (!is_null($user)) {
                if (isset($user->level)) {
                    if ($user->level == 3) {
                        $propiedades = $this->admin->propiedades_asignadas($user->id);
                        if (count($propiedades) == 1) {
                            array_push($array_propiedades, array(
                                'comunidad' => $propiedades->nombrecomunidad,
                                'propiedadnumero' => $propiedades->numero
                            ));
                            //$idpropiedad = $propiedad->id;
                        } else if (count($propiedades) > 1) {
                            foreach ($propiedades as $propiedad) {
                                array_push($array_propiedades, array(
                                    'comunidad' => $propiedad->nombrecomunidad,
                                    'propiedadnumero' => $propiedad->numero
                                ));
                            }
                        }
                    }
                }
            }

            $content = array(
                'menu' => 'Mi Cuenta',
                'title' => 'Mi Cuenta',
                'subtitle' => 'Perfil'
            );


            $datos_form = array(
                'iduser' => is_null($user) ? 0 : $user->id,
                'nombre' => is_null($user) ? '' : $user->first_name,
                'apellido' => is_null($user) ? '' : $user->last_name,
                'email' => is_null($user) ? '' : $user->email,
                'perfil' => is_null($user) ? '' : $user->level,
                'idcomunidad' => $idcomunidad,
                'idpropiedad' => $idpropiedad
            );

            $vars['content_menu'] = $content;
            $vars['user'] = $user;
            $vars['path_photo'] = $user->photo == '' ? base_url() . 'dist/img/user9-128x128.jpg' : base_url() . 'dist/img/' . $user->photo;

            $vars['listado_comunidades'] = $array_comunidades;
            $vars['listado_propiedades'] = $array_propiedades;
            $vars['kartik'] = true;
            $vars['content_view'] = 'admin/profile';

            $vars['kartik'] = true;


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


    public function submit_profile()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $userid = $this->input->post("userid");
            $config['upload_path'] = "./dist/img/";

            if (!file_exists($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $config['file_name'] = "prop_" . $userid . "_" . randomstring_mm(5);
            $config['allowed_types'] = "*";
            $config['max_size'] = "10240";


            $this->load->library('upload', $config);
            $this->upload->do_upload("avatar");

            $dataupload = $this->upload->data();
            //print_r($dataupload);
            //print_r($this->input->post(NULL,true));
            //exit;



            $parametros = array(
                'photo' => $dataupload['orig_name'],
            );

            $this->load->model('admin');
            $this->admin->edit_profile($parametros, $userid);

            $this->session->set_userdata('photo', $dataupload['orig_name']);




            redirect('admins/profile');
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





    public function ver_comprobante_muestra()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $this->admin->ver_comprobante_muestra();
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



    public function comunicados($resultid = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('add_comunicado_result');
            if ($resultid == 1) {
                $vars['message'] = "Comunicado Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al editar comunicado. Intente nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Comunicado Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al enviar Comunicado. Intente nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Solicitud de env&iacute;o de comunicado realizado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al anular Comunicado. Intente nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 7) {
                $vars['message'] = "Anulaci&oacute;n de comunicado realizado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 8) {
                $vars['message'] = "Error al eliminar Comunicado. Intente nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 9) {
                $vars['message'] = "Eliminaci&oacute;n de comunicado realizada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 10) {
                $vars['message'] = "Error al ver comunicado. Intente nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }


            $this->load->model('admin');

            $agrega = $this->session->userdata('level') == 1 ? true : false;



            $comunicados = $this->admin->get_comunicados();
            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Administraci&oacute;n de Comunicados'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/comunicados';
            $vars['comunicados'] = $comunicados;
            $vars['agrega'] = $agrega;
            $vars['dataTables'] = true;

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




    public function add_comunicado($idcomunicado = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_archivoscomunicados = array();
            if (!is_null($idcomunicado)) {
                $this->load->model('admin');
                $comunicados = $this->admin->get_comunicados($idcomunicado);
                $array_archivoscomunicados = $this->admin->get_archivos_comunicados($idcomunicado);

                //var_dump($archivoscomunicados); exit;

                if (is_null($comunicados)) {
                    $this->session->set_flashdata('add_comunicado_result', 2);
                    redirect('admins/comunicados');
                }

                $array_datos = array(
                    'id' => $comunicados->id,
                    'titulo' => $comunicados->titulo,
                    'txt_comunicado' => $comunicados->txt_comunicado,
                    'estado' => $comunicados->estado,
                    'txt_encabeza' => 'Editar Comunicado',
                    'txt_button' => 'Editar'
                );
            } else {

                $array_archivoscomunicados = array();

                $array_datos = array(
                    'id' => 0,
                    'titulo' => '',
                    'txt_comunicado' => '',
                    'estado' => '',
                    'txt_encabeza' => 'Agregar Comunicado',
                    'txt_button' => 'Agregar'
                );
            }


            $agrega =  true;
            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => is_null($idcomunicado) ? 'Agregar Comunicado' : 'Editar Comunicado'
            );

            $vars['wysihtml5'] = true;
            $vars['formValidation'] = true;
            $vars['content_menu'] = $content;
            $vars['agrega'] = $agrega;
            $vars['datos_comunicado'] = $array_datos;
            $vars['archivos_comunicados'] = $array_archivoscomunicados;
            $vars['content_view'] = 'admin/add_comunicado';
            $vars['permite'] = true;


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



public function deletefile_comunicado($data = '')
    {

        if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){
            $idfile = $this->input->post('idfile');
            $idcomunicado = $this->input->post('idcomunicado');
            $this->load->model('admin');
            $this->admin->deletefile_comunicado($idcomunicado,$idfile);

            $data = "ok";

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


 public function editar_mail_vencimiento($tipo = 1)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');

            $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));


           // var_dump($comunidad); exit;
            //$comunicados = $this->admin->get_comunicados($idcomunicado);


            $tipo_mail = $tipo == 1 ? 'Antes' : 'Despu&eacute;s';
            $txt_mail = $tipo == 1 ? $comunidad->txt_mail_antes_vencimiento : $comunidad->txt_mail_despues_vencimiento;


            $array_datos = array(
                'tipo' => $tipo,
                'txt_mail' => $txt_mail,
                'txt_encabeza' => 'Editar Mail ' . $tipo_mail . ' Vencimiento ',
                'txt_button' => 'Editar'
            );



            $agrega =  true;
            $content = array(
                'menu' => 'Configuraci&oacute;n',
                'title' => 'Configuraci&oacute;n',
                'subtitle' => 'Editar Mail '. $tipo_mail . ' Vencimiento'
            );

            $vars['wysihtml5'] = true;
            $vars['formValidation'] = true;
            $vars['content_menu'] = $content;
            $vars['agrega'] = $agrega;
            $vars['datos_comunicado'] = $array_datos;
            $vars['content_view'] = 'admin/editar_mail_vencimiento';
            $vars['permite'] = true;


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



  public function submit_envio_mail_vencimiento()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $txt_mail = $this->input->post('txt_mail');
            $tipo = $this->input->post('tipo');

            $datos_mail = array(
                'txt_mail' => $txt_mail,
                'tipo' => $tipo
            );



            $this->load->model('admin');
            $this->admin->save_mail_vencimiento($datos_mail);

            $this->session->set_flashdata('accion_mora_result', 3);


            redirect('admins/accion_mora');
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

    public function ver_envio_comunicado($idcomunicado = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_archivoscomunicados = array();
            if (!is_null($idcomunicado)) {
                $this->load->model('admin');
                $comunicados = $this->admin->get_comunicados($idcomunicado);
                $array_archivoscomunicados = $this->admin->get_archivos_comunicados($idcomunicado);



                if (is_null($comunicados)) {
                    $this->session->set_flashdata('add_comunicado_result', 10);
                    redirect('admins/comunicados');
                }

                $array_datos = array(
                    'id' => $comunicados->id,
                    'titulo' => $comunicados->titulo,
                    'txt_comunicado' => $comunicados->txt_comunicado,
                    'estado' => $comunicados->estado,
                    'txt_encabeza' => 'Editar Comunicado',
                    'txt_button' => 'Editar'
                );
            } else {
                $this->session->set_flashdata('add_comunicado_result', 10);
                redirect('admins/comunicados');
            }



            $agrega = $this->session->userdata('level') == 1 ? true : false;

            $content = array(
                'menu' => 'Administraci&oacute;n',
                'title' => 'Administraci&oacute;n',
                'subtitle' => 'Ver Comunicado'
            );

            $vars['wysihtml5'] = true;
            $vars['formValidation'] = true;
            $vars['content_menu'] = $content;
            $vars['datos_comunicado'] = $array_datos;
            $vars['archivos_comunicados'] = $array_archivoscomunicados;
            $vars['agrega'] = $agrega;
            $vars['content_view'] = 'admin/add_comunicado';
            $vars['permite'] = false;


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


    public function submit_comunicados()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

  

            $files = $_FILES;

            $this->load->library('upload');
            $config['upload_path'] = "./uploads/comunicados/" . $this->session->userdata('comunidadid') . "/"; //una carpeta por comunicado
            $config['allowed_types'] = "*";
            $config['max_size'] = "10240";

            if (!file_exists($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }


            $number_of_files = isset($_FILES['userfile']['name']) ? count($_FILES['userfile']['name']) : 0;
            $array_archivos = array();

            for ($i = 0; $i < $number_of_files; $i++) {
                $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                $_FILES['userfile']['size'] = $files['userfile']['size'][$i];


                $filename = date("Ymd") . "_" . date("His") . "_" . randomstring(5) . "_" . $this->session->userdata('comunidadid');
                $array_filename = explode('.',$_FILES['userfile']['name']);
                $extension_filename = $array_filename[count($array_filename) - 1];
                $config['file_name'] = $filename; 

                //poner nombre unico para no topar con otros archivos

                $this->upload->initialize($config);

                if ($this->upload->do_upload('userfile')) {
                    $data = $this->upload->data();
                    //echo "El archivo " . htmlspecialchars($data['file_name']) . " se ha subido correctamente.<br>";
                    $array_archivo = array(
                                                    'name' => $_FILES['userfile']['name'],
                                                    'tmp_name' => $filename.'.'.$extension_filename
                                        );
                    array_push($array_archivos,$array_archivo);
                } else {
                    $errors[] = $this->upload->display_errors();

                }
            }


         // echo '<pre>';
           // var_dump($_FILES); exit;
            //exit;


            $txt_comunicado = $this->input->post('txt_comunicado');
            $idcomunicado = $this->input->post('idcomunicado');
            $titulo = $this->input->post('titulo');

            $datos_comunicado = array(
                'txt_comunicado' => $txt_comunicado,
                'idcomunicado' => $idcomunicado,
                'titulo' => $titulo,
                'archivos' => $array_archivos
            );

            $this->load->model('admin');
            $this->admin->save_comunicado($datos_comunicado);

            if ($idcomunicado == 0) {
                $this->session->set_flashdata('add_comunicado_result', 1);
            } else {
                $this->session->set_flashdata('add_comunicado_result', 3);
            }


            redirect('admins/comunicados');
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


    public function send_comunicado($idcomunicado)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->send_comunicado($idcomunicado);

            if ($result) {
                $this->session->set_flashdata('add_comunicado_result', 5);
            } else {
                $this->session->set_flashdata('add_comunicado_result', 4);
            }


            redirect('admins/comunicados');
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

    public function anular_envio_comunicado($idcomunicado)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->anular_comunicado($idcomunicado);

            if ($result) {
                $this->session->set_flashdata('add_comunicado_result', 7);
            } else {
                $this->session->set_flashdata('add_comunicado_result', 6);
            }


            redirect('admins/comunicados');
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


    public function delete_comunicado($idcomunicado)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');
            $result = $this->admin->delete_comunicado($idcomunicado);

            if ($result) {
                $this->session->set_flashdata('add_comunicado_result', 9);
            } else {
                $this->session->set_flashdata('add_comunicado_result', 8);
            }


            redirect('admins/comunicados');
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



    public function accion_mora($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('accion_mora_result');
            if ($resultid == 1) {
                $vars['message'] = "Par&aacute;metros Acci&oacute;n Mora actualizados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['classmessage'] = 'danger';
                $vars['message'] = "Error al ingresar Par&aacute;metros Acci&oacute;n. Se ingresaron valores repetidos";
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 3) {
                $vars['message'] = "Mail Vencimiento editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }

            $this->load->model('admin');
            $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));
            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Acci&oacute;n Mora'
            );


            $datos_form = array(
                'mes_aldia' => is_null($comunidad) ? '-1' : $comunidad->mes_aldia,
                'mes_moroso' => is_null($comunidad) ? '-1' : $comunidad->mes_moroso,
                'mes_corteluz' => is_null($comunidad) ? '-1' : $comunidad->mes_corteluz,
                'mes_prejudicial' => is_null($comunidad) ? '-1' : $comunidad->mes_prejudicial,
                'mes_judicial' => is_null($comunidad) ? '-1' : $comunidad->mes_judicial,
                'mail_morosidad_antes_vencimiento' => is_null($comunidad) ? '-1' : $comunidad->mail_morosidad_antes_vencimiento,
                'mail_morosidad_despues_vencimiento' => is_null($comunidad) ? '-1' : $comunidad->mail_morosidad_despues_vencimiento,
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/accion_mora';
            $vars['formValidation'] = true;
            $vars['mask'] = true;
            $vars['datos_form'] = $datos_form;

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

    public function submit_accion_mora()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $array_cantidad[0] = 0;
            $array_cantidad[1] = 0;
            $array_cantidad[2] = 0;
            $array_cantidad[3] = 0;
            $array_cantidad[4] = 0;
            $array_cantidad[5] = 0;
            $array_cantidad[6] = 0;

            if ($this->input->post('mes_aldia') != '') {
                $array_cantidad[$this->input->post('mes_aldia')]++;
            }

            if ($this->input->post('mes_moroso') != '') {
                $array_cantidad[$this->input->post('mes_moroso')]++;
            }

            if ($this->input->post('mes_corteluz') != '') {
                $array_cantidad[$this->input->post('mes_corteluz')]++;
            }

            if ($this->input->post('mes_prejudicial') != '') {
                $array_cantidad[$this->input->post('mes_prejudicial')]++;
            }


            if ($this->input->post('mes_judicial') != '') {
                $array_cantidad[$this->input->post('mes_judicial')]++;
            }
            //Array ( [0] => 2 [1] => 0 [2] => 0 [3] => 1 [4] => 0 [5] => 1 [6] => 1 )

            //print_r($array_cantidad); exit;

            if (
                $array_cantidad[0] > 1
                || $array_cantidad[1] > 1
                || $array_cantidad[2] > 1
                || $array_cantidad[3] > 1
                || $array_cantidad[4] > 1
                || $array_cantidad[5] > 1
                || $array_cantidad[6] > 1
            ) {

                $this->session->set_flashdata('accion_mora_result', 2);
                redirect('admins/accion_mora');
            }

            $parametros = array(

                'mes_aldia' => $this->input->post('mes_aldia') == '' ? -1 : $this->input->post('mes_aldia'),
                'mes_moroso' => $this->input->post('mes_moroso') == '' ? -1 : $this->input->post('mes_moroso'),
                'mes_corteluz' => $this->input->post('mes_corteluz') == '' ? -1 : $this->input->post('mes_corteluz'),
                'mes_prejudicial' => $this->input->post('mes_prejudicial') == '' ? -1 : $this->input->post('mes_prejudicial'),
                'mes_judicial' => $this->input->post('mes_judicial') == '' ? -1 : $this->input->post('mes_judicial'),
                'mail_morosidad_antes_vencimiento' => $this->input->post('mail_morosidad_antes_vencimiento') == '' ? -1 : $this->input->post('mail_morosidad_antes_vencimiento'),
                'mail_morosidad_despues_vencimiento' => $this->input->post('mail_morosidad_despues_vencimiento') == '' ? -1 : $this->input->post('mail_morosidad_despues_vencimiento'),
            );
            $this->load->model('admin');
            $this->admin->edit_accion_mora($parametros);

            //$result = $this->admin->save_comunidad($array_datos);


            $this->session->set_flashdata('accion_mora_result', 1);
            redirect('admins/accion_mora');
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

    public function admin_comite()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('admin_miembro_result');

            if ($resultid == 1) {
                $vars['message'] = "Miembro agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar miembro. Email ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Miembro editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar miembro. Miembro no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Miembro eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }

            $this->load->model('admin');
            $idcomunidad = $this->session->userdata('level') === '1' || $this->session->userdata('level')  === '2' ? $this->session->userdata('comunidadid') : null;
            $comite = $this->admin->get_comite($idcomunidad);

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Comite'
            );

            $vars['permite_editar'] = $this->session->userdata('level') === '1' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_comite';
            $vars['comite'] = $comite;
            $vars['dataTables'] = true;
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
            $template = 'template';

            $this->load->view($template, $vars);
        }
    }

    public function add_miembro_comite($idmiembro = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('admin');

            $miembro = $this->admin->get_miembro_by_id($idmiembro);
            $cargos_comite = $this->admin->get_cargo_comite();
            $idcomunidad = $this->session->userdata('level') == 1 ? $this->session->userdata('comunidadid') : null;
            $users = $this->admin->get_users(null, null, false);

           // echo '<pre>'; 
           // var_dump($users); exit;

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Comite'
            );

            $datos_form = array(
                'idcomunidad' => is_null($miembro) ? 0 : $miembro->idcomunidad,
                'iduser' => is_null($miembro) ? 0 : $miembro->iduser,
                'nombre' => is_null($miembro) ? '' : $miembro->first_name,
                'apellido' => is_null($miembro) ? '' : $miembro->last_name,
                'email' => is_null($miembro) ? '' : $miembro->email,
                'idcargo' => is_null($miembro) ? 0 : $miembro->id,
                'cargo' => is_null($miembro) ? 'Seleccione cargo' : $miembro->cargo
            );

            $vars['cargos_comite'] = $cargos_comite;
            $vars['datos_form'] = $datos_form;
            $vars['idmiembro'] = $idmiembro;
            $vars['users'] = $users;
            $vars['permite_editar'] = $this->session->userdata('level') === '1' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_miembro_comite';
            $vars['icheck'] = true;
            $vars['formValidation'] = true;
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

    public function historial_documentos()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('admin');

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Historial de Documentos'
            );

            // Si es Admin manda comunidad
            $idcomunidad = $this->session->userdata('level') === '1' || $this->session->userdata('level') === '2'|| $this->session->userdata('level') === '3' ? $this->session->userdata('comunidadid') : null;
            $documentos = $this->admin->get_documentos($idcomunidad, 0);

            $vars['permite_editar'] = false;
            $vars['documentos'] = $documentos;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'comunity/historial_documentos';
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

    public function submit_miembro_comite()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('admin');

            $nombre = $this->input->post('first_name');
            $apellido = $this->input->post('last_name');
            $email = $this->input->post('email');
            $idcargo = $this->input->post('cargo');
            $idmiembro = $this->input->post('idmiembro');
            $idcomunidad = $this->session->userdata('level') == 1 ? $this->session->userdata('comunidadid') : null;
            $iduser = $this->input->post('iduser');
            $user = !is_null($iduser) ? $this->admin->get_users($iduser) : null;

            $datos_miembro = array(
                'nombre' => !is_null($iduser) ? $user->first_name : $nombre,
                'apellido' => !is_null($iduser) ? $user->last_name : $apellido,
                'email' => !is_null($iduser) ? $user->email : $email,
                'idcargo' => $idcargo,
                'idmiembro' => $idmiembro,
                'idcomunidad' => $idcomunidad,
                'iduser' => $iduser,
            );

            $existe = !is_null($iduser) ? $this->admin->valida_existe_usuario_comite($user->email, $idmiembro) : $this->admin->valida_existe_usuario_comite($email, $idmiembro);

            if (!$existe) {
                $result = $this->admin->save_miembro($datos_miembro);

                if ($result && $idmiembro === '0') {
                    // Se crea miembro
                    $this->session->set_flashdata('admin_miembro_result', 1);
                } else if ($result && $idmiembro !== '0') {
                    // Se edita miembro y su correo
                    $this->session->set_flashdata('admin_miembro_result', 3);
                }
            } else {
                // Error al agregar. Email ya existe.
                $this->session->set_flashdata('admin_miembro_result', 2);
            }

            redirect('admins/admin_comite');
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

    public function delete_miembro_comite($idmiembro = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');

            $result = $this->admin->delete_miembro($idmiembro);

            if ($result == 1) {
                $this->session->set_flashdata('admin_miembro_result', 5);
            } else {
                $this->session->set_flashdata('admin_miembro_result', 4);
            }

            redirect('admins/admin_comite');
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

    public function admin_documentos()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('admin_documento_result');

            if ($resultid == 1) {
                $vars['message'] = "Documento Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Documento";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Documento Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al Archivar documento";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Documento Archivado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al editar Documento.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $this->load->model('admin');
            $idcomunidad = $this->session->userdata('level') === '1' || $this->session->userdata('level') === '2' || $this->session->userdata('level') === '3'  ? $this->session->userdata('comunidadid') : null;
            $documentos = $this->admin->get_documentos($idcomunidad);

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Documentos'
            );

            $vars['permite_editar'] = $this->session->userdata('level') === '1' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_documentos';
            $vars['documentos'] = $documentos;
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

    public function add_documento($iddocumento = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('admin');

            $documento = $this->admin->get_documento_by_id($iddocumento);
            $tipos_documento = $this->admin->get_tipo_documento();
            $idcomunidad = $this->session->userdata('level') == 1 ? $this->session->userdata('comunidadid') : null;

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Documentos'
            );

            $datos_form = array(
                'idcomunidad' => is_null($documento) ? 0 : $documento->idcomunidad,
                'descripcion' => is_null($documento) ? '' : $documento->descripcion,
                'path' => is_null($documento) ? '' : $documento->path,
                'tipo' => is_null($documento) ? 'Seleccione tipo' : $documento->tipo,
                'idtipodocumento' => is_null($documento) ? 0 : $documento->idtipodocumento
            );

            $vars['tipos_documento'] = $tipos_documento;
            $vars['datos_form'] = $datos_form;
            $vars['iddocumento'] = $iddocumento;
            $vars['permite_editar'] = $this->session->userdata('level') === '1' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_documento';
            $vars['dataTables'] = true;
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
            $template = 'template';

            $this->load->view($template, $vars);
        }
    }

    public function submit_documento()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $descripcion = $this->input->post('descripcion');
            $descripcionMod = str_replace(" ", "_", $descripcion);
            $idtipodocumento = $this->input->post('tipo_documento');
            $iddocumento = $this->input->post('iddocumento');
            $idcomunidad = $this->session->userdata('level') == 1 ? $this->session->userdata('comunidadid') : null;

            $path = './files/documentos/' . $idcomunidad;
            $this->load->model('admin');

            $config['upload_path'] = $path;
            $config['file_name'] = $descripcionMod;
            $config['allowed_types'] = 'xlsx|xls|jpeg|png|doc|docx|ppt|pptx|txt|pdf';
            $config['max_size'] = 10240;
            $config['max_width'] = 1500;
            $config['max_height'] = 1500;

            $this->load->library('upload', $config);

            if (!file_exists($path)) {
                mkdir('./files/documentos/' . $idcomunidad, 0777, true);
            }

            $datos_documento = array(
                'descripcion' => $descripcion,
                'idtipo' => $idtipodocumento,
                'iddocumento' => $iddocumento,
                'idcomunidad' => $idcomunidad
            );

            if ($this->upload->do_upload('documento')) {

                $datos_documento['path'] = $path . '/' . $this->upload->data('file_name');

                $this->load->model('admin');
                $result = $this->admin->save_documento($datos_documento);

                if ($result) {
                    if ($iddocumento === '0') {
                        $this->session->set_flashdata('admin_documento_result', 1);
                    } else {
                        $this->session->set_flashdata('admin_documento_result', 3);
                    }
                } else {
                    if ($iddocumento === '0') {
                        $this->session->set_flashdata('admin_documento_result', 2);
                    } else {
                        $this->session->set_flashdata('admin_documento_result', 6);
                    }
                }
            } else if ($iddocumento !== '0' && is_null($this->input->post('documento'))) {
                $result = $this->admin->save_documento($datos_documento);

                if ($result) {
                    // Se edita sin documento
                    $this->session->set_flashdata('admin_documento_result', 3);
                } else {
                    $this->session->set_flashdata('admin_documento_result', 6);
                }
            } else {
                $this->session->set_flashdata('admin_documento_result', 2);
            }

            redirect('admins/admin_documentos');
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

    public function delete_documento($iddocumento = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');

            $result = $this->admin->delete_documento($iddocumento);

            if ($result == 1) {
                $this->session->set_flashdata('admin_documento_result', 5);
            } else {
                $this->session->set_flashdata('admin_documento_result', 4);
            }

            redirect('admins/admin_documentos');
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

    public function admin_asambleas()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('admin_asamblea_result');

            if ($resultid == 1) {
                $vars['message'] = "Asamblea registrada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al registrar asamblea";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Asamblea modificada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar asamblea";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Asamblea eliminada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al editar Asamblea";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $this->load->model('admin');
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level')  === '2' ? $this->session->userdata('comunidadid') : null;
            $asambleas = $this->admin->get_asambleas($idcomunidad);

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Asambleas'
            );

            $vars['permite_editar'] = $this->session->userdata('level') === '1' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/admin_asambleas';
            $vars['asambleas'] = $asambleas;
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

    public function add_asamblea($idasamblea = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('admin');

            $asamblea = $this->admin->get_asamblea_by_id($idasamblea);
            $tipos_asamblea = $this->admin->get_tipo_asamblea();
            $idcomunidad = $this->session->userdata('level') == 1 ? $this->session->userdata('comunidadid') : null;

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Asambleas'
            );

            $datos_form = array(
                'idcomunidad' => is_null($asamblea) ? 0 : $asamblea->idcomunidad,
                'asunto' => is_null($asamblea) ? '' : $asamblea->asunto,
                'fecha' => is_null($asamblea) ? '' : $asamblea->fecha,
                'path' => is_null($asamblea) ? '' : $asamblea->path,
                'tipo' => is_null($asamblea) ? 'Seleccione tipo' : $asamblea->tipo,
                'idtipoasamblea' => is_null($asamblea) ? 0 : $asamblea->idtipoasamblea
            );

            $vars['tipos_asamblea'] = $tipos_asamblea;
            $vars['datos_form'] = $datos_form;
            $vars['idasamblea'] = $idasamblea;
            $vars['permite_editar'] = $this->session->userdata('level') === '1' ? true : false;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'admin/add_asamblea';
            $vars['dataTables'] = true;
            $vars['formValidation'] = true;
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

    public function submit_asamblea()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('admin');

            $asunto = $this->input->post('asunto');
            $asuntoMod = str_replace(" ", "_", $asunto);
            $fecha = $this->input->post('fecha');
            $idtipoasamblea = $this->input->post('tipo_asamblea');
            $idasamblea = $this->input->post('idasamblea');
            $idcomunidad = $this->session->userdata('level') == 1 ? $this->session->userdata('comunidadid') : null;
            $path = './files/asambleas/' . $idcomunidad;

            if (!file_exists($path)) {
                mkdir('./files/asambleas/' . $idcomunidad, 0777, true);
            }

            $config['upload_path'] = $path;
            $config['file_name'] = $asuntoMod . '_' . $fecha;
            $config['allowed_types'] = 'xlsx|xls|jpeg|png|doc|docx|ppt|pptx|txt|pdf';
            $config['max_size'] = 2048;
            $config['max_width'] = 1500;
            $config['max_height'] = 1500;

            $this->load->library('upload', $config);

            $datos_asamblea = array(
                'asunto' => $asunto,
                'fecha' => $fecha,
                'idtipo' => $idtipoasamblea,
                'idasamblea' => $idasamblea,
                'idcomunidad' => $idcomunidad
            );

            if ($this->upload->do_upload('documento')) {
                $datos_asamblea['path'] = $path . '/' . $this->upload->data('file_name');

                $result = $this->admin->save_asamblea($datos_asamblea);

                if ($result) {
                    if ($idasamblea === '0') {
                        // Se agrega con documento
                        $this->session->set_flashdata('admin_asamblea_result', 1);
                    } else {
                        // Se edita y se reemplaza el documento
                        $this->session->set_flashdata('admin_asamblea_result', 3);
                    }
                } else {
                    if ($idasamblea === '0') {
                        // Error al agregar documento
                        $this->session->set_flashdata('admin_asamblea_result', 2);
                    } else {
                        // Error al editar documento
                        $this->session->set_flashdata('admin_asamblea_result', 6);
                    }
                }
            } else if ($idasamblea !== '0' && is_null($this->input->post('documento'))) {
                $result = $this->admin->save_asamblea($datos_asamblea);

                if ($result) {
                    // Se edita sin documento
                    $this->session->set_flashdata('admin_asamblea_result', 3);
                } else {
                    // Error al editar documento
                    $this->session->set_flashdata('admin_asamblea_result', 6);
                }
            } else {
                $this->session->set_flashdata('admin_asamblea_result', 2);
            }

            redirect('admins/admin_asambleas');
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

    public function historial_asambleas()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('admin');

            $content = array(
                'menu' => 'Comunidad',
                'title' => 'Comunidad',
                'subtitle' => 'Historial de Asambleas'
            );

            // Si es Admin manda comunidad
            $idcomunidad = $this->session->userdata('level') == 1 || $this->session->userdata('level')  === '2' ? $this->session->userdata('comunidadid') : null;
            $asambleas = $this->admin->get_asambleas($idcomunidad, 0);

            $vars['permite_editar'] = false;
            $vars['asambleas'] = $asambleas;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'comunity/historial_asambleas';
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

    public function delete_asamblea($idasamblea = 0)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('admin');

            $result = $this->admin->delete_asamblea($idasamblea);

            if ($result == 1) {
                $this->session->set_flashdata('admin_asamblea_result', 5);
            } else {
                $this->session->set_flashdata('admin_asamblea_result', 4);
            }

            redirect('admins/admin_asambleas');
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
