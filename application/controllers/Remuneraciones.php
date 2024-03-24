<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Remuneraciones extends CI_Controller
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



    public function parametros_generales($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('parametros_result');
            if ($resultid == 1) {
                $vars['message'] = "Parametros Generales actualizados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }

            $this->load->model('remuneracion');
            $parametros_generales = $this->remuneracion->get_parametros_generales();
            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Par&aacutemetros Generales'
            );



            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/parametros_generales';
            $vars['formValidation'] = true;
            $vars['mask'] = true;
            $vars['parametros_generales'] = $parametros_generales;

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


    public function submit_parametros_generales()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $parametros = array(
                'uf' => str_replace(",", ".", str_replace(".", "", $this->input->post('uf'))),
                'sueldominimo' => str_replace(".", "", $this->input->post('sueldominimo')),
                'csimples' => str_replace(".", "", $this->input->post('cf_simple')),
                'cinvalidas' => str_replace(".", "", $this->input->post('cf_invalidas')),
                'cmaternales' => str_replace(".", "", $this->input->post('cf_maternales')),
                'tasasis' => $this->input->post('tasasis'),
                'topeimponible' => str_replace(",", ".", str_replace(".", "", $this->input->post('topeimponible'))),
                'topeimponibleips' => str_replace(",", ".", str_replace(".", "", $this->input->post('topeimponibleips'))),
                'topeimponibleafc' => str_replace(",", ".", str_replace(".", "", $this->input->post('topeimponibleafc')))
            );
            $this->load->model('remuneracion');
            $this->remuneracion->edit_parametros_generales($parametros);

            $this->session->set_flashdata('parametros_result', 1);
            redirect('remuneraciones/parametros_generales');
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


    public function personal($tipo_colaborador = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $vars['mantencion_personal'] = 'active';
            $vars['leyes_sociales'] = '';
            $vars['salud'] = '';
            $vars['otros'] = '';
            $vars['apv'] = '';
            $resultid = $this->session->flashdata('personal_result');
            if ($resultid == 1) {
                $vars['message'] = "Trabajador Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
                $vars['mantencion_personal'] = 'active';
                $vars['leyes_sociales'] = '';
                $vars['apv'] = '';
                $vars['salud'] = '';
                $vars['otros'] = '';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Trabajador. Trabajador ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
                $vars['mantencion_personal'] = 'active';
                $vars['leyes_sociales'] = '';
                $vars['apv'] = '';
                $vars['salud'] = '';
                $vars['otros'] = '';
            } elseif ($resultid == 3) {
                $vars['message'] = "Leyes sociales actualizadas correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
                $vars['mantencion_personal'] = '';
                $vars['apv'] = '';
                $vars['leyes_sociales'] = 'active';
                $vars['salud'] = '';
                $vars['otros'] = '';
            } elseif ($resultid == 4) {
                $vars['message'] = "Datos de Cotizaciones de Salud actualizados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
                $vars['mantencion_personal'] = '';
                $vars['apv'] = '';
                $vars['leyes_sociales'] = '';
                $vars['salud'] = 'active';
                $vars['otros'] = '';
            } elseif ($resultid == 5) {
                $vars['message'] = "Mutual de Seguridad/Caja de Compensaci&oacute;n actualizados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
                $vars['mantencion_personal'] = '';
                $vars['apv'] = '';
                $vars['leyes_sociales'] = '';
                $vars['salud'] = '';
                $vars['otros'] = 'active';
            } elseif ($resultid == 6) {
                $vars['message'] = "Trabajador Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
                $vars['mantencion_personal'] = 'active';
                $vars['apv'] = '';
                $vars['leyes_sociales'] = '';
                $vars['salud'] = '';
                $vars['otros'] = '';
            } elseif ($resultid == 7) {
                $vars['message'] = "A.P.V. Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
                $vars['mantencion_personal'] = '';
                $vars['apv'] = 'active';
                $vars['leyes_sociales'] = '';
                $vars['salud'] = '';
                $vars['otros'] = '';
            } elseif ($resultid == 8) {
                $vars['message'] = "Error al";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-danger';
                $vars['mantencion_personal'] = '';
                $vars['apv'] = 'active';
                $vars['leyes_sociales'] = '';
                $vars['salud'] = '';
                $vars['otros'] = '';
            } elseif ($resultid == 9) {
                $vars['message'] = "Error al asociar usuario. Usuario ya existe.";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-danger';
                $vars['mantencion_personal'] = 'active';
                $vars['apv'] = '';
                $vars['leyes_sociales'] = '';
                $vars['salud'] = '';
                $vars['otros'] = '';
            }

            $this->load->model('admin');
            $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));


            $this->load->model('remuneracion');


            if ($tipo_colaborador == 'activos') {
                $title_button = 'Mostrar Activos';
            } else if ($tipo_colaborador == 'inactivos') {
                $title_button = 'Mostrar Inactivos';
            } else {
                $title_button = 'Mostrar Todos';
            }


            $consulta_colaboradores = is_null($tipo_colaborador) ? 'todos' : $tipo_colaborador;

            $personal = $this->remuneracion->get_personal_total(null, $consulta_colaboradores);
            $afps = $this->remuneracion->get_afp();
            $apvs = $this->remuneracion->get_apv();
            $isapres = $this->remuneracion->get_isapre();
            $cajas = $this->remuneracion->get_cajas_compensacion();
            $mutuales = $this->remuneracion->get_mutual_seguridad();

            $parametros_generales = $this->remuneracion->get_parametros_generales();


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Personal'
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/personal';
            $vars['dataTables'] = true;
            $vars['mask'] = true;
            $vars['formValidation'] = true;
            $vars['comunidad'] = $comunidad;
            $vars['personal'] = $personal;
            $vars['afps'] = $afps;
            $vars['apvs'] = $apvs;
            $vars['isapres'] = $isapres;
            $vars['cajas'] = $cajas;
            $vars['mutuales'] = $mutuales;
            $vars['title_button'] = $title_button;
            $vars['parametros_generales'] = $parametros_generales;

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


    public function add_trabajador($idtrabajador = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            /***** CARGA DE DATOS PARA FORMULARIO ***/
            $this->load->model('admin');
            $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));
            $regiones = $this->admin->get_regiones();
            $this->load->model('remuneracion');
            $causales_finiquito = $this->remuneracion->get_causal_finiquito();
            $estados_civiles = $this->remuneracion->get_estado_civil();
            $cargos = $this->remuneracion->get_cargos();
            $tramos_asig_familiar = $this->remuneracion->get_tabla_asig_familiar();

            //echo '<pre>';
            //var_dump($causales_finiquito); exit;
            /**** CARGA DE DATOS TRABAJADOR ****/
            $trabajador = is_null($idtrabajador) ?  array() : $this->remuneracion->get_personal_total($idtrabajador);

            if (!is_null($idtrabajador) && is_null($trabajador)) { // si estoy editando, pero ingreso un trabajador que no está, vuelvo al principio
                redirect('remuneraciones/personal');
            }
            $bonos = is_null($idtrabajador) ?  array() : $this->remuneracion->get_bonos($idtrabajador);
            //$parametros = $this->remuneracion->get_parametros_generales();

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Agregar Trabajador'
            );


            $datos_form = array(
                'iduser' => is_null($idtrabajador) ? 0 : ($trabajador->iduser ? $trabajador->iduser : 0),
                'uemail' => is_null($idtrabajador) ? 0 : $trabajador->uemail,
                'idtrabajador' =>  is_null($idtrabajador) ? 0 : $trabajador->id,
                'rut' => is_null($idtrabajador) ? "" : number_format($trabajador->rut, 0, ".", ".") . "-" . $trabajador->dv,
                'nombre' => is_null($idtrabajador) ? "" : $trabajador->nombre,
                'apaterno' => is_null($idtrabajador) ? "" : $trabajador->apaterno,
                'amaterno' => is_null($idtrabajador) ? "" : $trabajador->amaterno,
                'fecnacimiento' => is_null($idtrabajador) ? "" : $trabajador->fecnacimiento,
                'fecfiniquito' => is_null($idtrabajador) ? "" : $trabajador->fecfiniquito,
                'sexo' => is_null($idtrabajador) ? "" : $trabajador->sexo,
                'idecivil' => is_null($idtrabajador) ? "" : $trabajador->idecivil,
                'nacionalidad' => is_null($idtrabajador) ? "" : $trabajador->nacionalidad,
                'direccion' => is_null($idtrabajador) ? "" : $trabajador->direccion,
                'idregion' => is_null($idtrabajador) ? "" : $trabajador->idregion,
                'idcomuna' => is_null($idtrabajador) ? "" : $trabajador->idcomuna,
                'fono' => is_null($idtrabajador) ? "" : $trabajador->fono,
                'email' => is_null($idtrabajador) ? "" : $trabajador->email,
                'fecingreso' => is_null($idtrabajador) ? "" : $trabajador->fecingreso,
                'fecafc' => is_null($idtrabajador) ? "" : $trabajador->fecafc,
                'fecinicvacaciones' => is_null($idtrabajador) ? "" : $trabajador->fecinicvacaciones,
                'saldoinicvacaciones' => is_null($idtrabajador) ? "" : $trabajador->saldoinicvacaciones,
                'saldoinicvacprog' => is_null($idtrabajador) ? "" : $trabajador->saldoinicvacprog,
                'idcargo' => is_null($idtrabajador) ? "" : $trabajador->idcargo,
                'pensionado' => is_null($idtrabajador) ? "" : $trabajador->pensionado,
                'tipocontrato' => is_null($idtrabajador) ? "" : $trabajador->tipocontrato,
                'parttime' => is_null($idtrabajador) ? "" : $trabajador->parttime,
                'diastrabajo' => is_null($idtrabajador) ? "" : $trabajador->diastrabajo,
                'horasdiarias' => is_null($idtrabajador) ? "" : $trabajador->horasdiarias,
                'horassemanales' => is_null($idtrabajador) ? "" : $trabajador->horassemanales,
                'sueldobase' => is_null($idtrabajador) ? "" : number_format($trabajador->sueldobase, 0, ".", "."),
                'tipogratificacion' => is_null($idtrabajador) ? "" : $trabajador->tipogratificacion,
                'gratificacion' => is_null($idtrabajador) ? "" : number_format($trabajador->gratificacion, 0, ".", "."),
                'cargassimples' => is_null($idtrabajador) ? "" : $trabajador->cargassimples,
                'cargasinvalidas' => is_null($idtrabajador) ? "" : $trabajador->cargasinvalidas,
                'cargasmaternales' => is_null($idtrabajador) ? "" : $trabajador->cargasmaternales,
                'cargasretroactivas' => is_null($idtrabajador) ? "" : $trabajador->cargasretroactivas,
                'causalfiniquito' => is_null($idtrabajador) ? "" : $trabajador->causalfiniquito,
                'indmesaviso' => is_null($idtrabajador) ? "" : number_format($trabajador->indmesaviso, 0, ".", "."),
                'indannoservicio' => is_null($idtrabajador) ? "" : number_format($trabajador->indannoservicio, 0, ".", "."),
                'indferiadolegal' => is_null($idtrabajador) ? "" : number_format($trabajador->indferiadolegal, 0, ".", "."),
                'indvoluntaria' => is_null($idtrabajador) ? "" : number_format($trabajador->indvoluntaria, 0, ".", "."),
                'indtotal' => is_null($idtrabajador) ? "" : number_format($trabajador->indtotal, 0, ".", "."),

                'idasigfamiliar' => is_null($idtrabajador) ? "" : $trabajador->idasigfamiliar,
                'asigfamiliar' => is_null($idtrabajador) ? "" : number_format($trabajador->asigfamiliar, 0, ".", "."),
                'segcesantia' => is_null($idtrabajador) ? "" : $trabajador->segcesantia,
                'movilizacion' => is_null($idtrabajador) ? "" : number_format($trabajador->movilizacion, 0, ".", "."),
                'colacion' => is_null($idtrabajador) ? "" : number_format($trabajador->colacion, 0, ".", "."),
                'active' => is_null($idtrabajador) ? "1" : $trabajador->active,
            );

            $vars['content_menu'] = $content;
            $vars['regiones'] = $regiones;
            $vars['estados_civiles'] = $estados_civiles;
            $vars['causales_finiquito'] = $causales_finiquito;
            $vars['cargos'] = $cargos;
            $vars['tramos_asig_familiar'] = $tramos_asig_familiar;
            $vars['content_view'] = 'remuneraciones/add_trabajador';
            $vars['datos_form'] = $datos_form;
            $vars['bonos'] = $bonos;
            $vars['formValidation'] = true;
            $vars['datetimepicker'] = true;
            $vars['icheck'] = true;
            $vars['jqueryRut'] = true;
            $vars['mask'] = true;
            $vars['inputmask'] = true;
            $vars['maleta'] = true;


            $template = "template";
            $this->load->view($template, $vars);
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }



    public function submit_personal_afp()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_elem = $this->input->post(NULL, true);
            $array_trabajadores = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("_", $elem);
                if ($arr_el[0] == 'afp' || $arr_el[0] == 'cotadic'  || $arr_el[0] == 'tipcotvol'  || $arr_el[0] == 'cotvol') {
                    $array_trabajadores[$arr_el[1]][$arr_el[0]] = $value_elem;
                }
            }

            $this->load->model('remuneracion');
            $this->remuneracion->update_personal_leyes_sociales($array_trabajadores);

            $this->session->set_flashdata('personal_result', 3);
            redirect('remuneraciones/personal');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function submit_personal_apv()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_elem = $this->input->post(NULL, true);

            $array_trabajadores = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("_", $elem);
                if ($arr_el[0] == 'instapv'  || $arr_el[0] == 'nrocontratoapv'  || $arr_el[0] == 'formapagoapv'  || $arr_el[0] == 'depconvapv'  || $arr_el[0] == 'tipoapv'  || $arr_el[0] == 'apv') {
                    $array_trabajadores[$arr_el[1]][$arr_el[0]] = $value_elem;
                }
            }

            $this->load->model('remuneracion');
            $this->remuneracion->update_personal_apv($array_trabajadores);

            $this->session->set_flashdata('personal_result', 7);
            redirect('remuneraciones/personal');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function submit_salud()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_elem = $this->input->post(NULL, true);
            $array_trabajadores = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("_", $elem);
                if ($arr_el[0] == 'isapre' || $arr_el[0] == 'pactado') {
                    $array_trabajadores[$arr_el[1]][$arr_el[0]] = $value_elem;
                }
            }

            $this->load->model('remuneracion');
            $this->remuneracion->update_personal_salud($array_trabajadores);

            $this->session->set_flashdata('personal_result', 4);
            redirect('remuneraciones/personal');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function submit_otros()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_datos = array(
                'idcaja' => $this->input->post('caja') == '' ? null :  $this->input->post('caja'),
                'idmutual' => $this->input->post('mutual') == '' ? null :  $this->input->post('mutual'),
                'porcmutual' => $this->input->post('porcmutual') == '' ? null :  $this->input->post('porcmutual')
            );

            $this->load->model('remuneracion');
            $this->remuneracion->update_caja_mutual($array_datos);

            $this->session->set_flashdata('personal_result', 5);
            redirect('remuneraciones/personal');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function submit_trabajador()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('admin');

            $array_elem = $this->input->post(NULL, true);

            $array_bonos = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("_", $elem);
                if ($arr_el[0] == 'descbono') {
                    $array_bono = array(
                        'descripcion' => $this->input->post('descbono_' . $arr_el[1]) == '' ? 'Sin Descripción' : $this->input->post('descbono_' . $arr_el[1]),
                        'monto' => str_replace(".", "", $this->input->post('montobono_' . $arr_el[1])),
                        'fecha' => substr($this->input->post('fecbono_' . $arr_el[1]), 6, 4) . "-" . substr($this->input->post('fecbono_' . $arr_el[1]), 3, 2) . "-" . substr($this->input->post('fecbono_' . $arr_el[1]), 0, 2),
                        'proporcional' => $this->input->post('propbono_' . $arr_el[1]) == 'on' ? 1 : 0,
                        'imponible' => $this->input->post('impbono_' . $arr_el[1]) == 'on' ? 1 : 0,
                        'fijo' => $this->input->post('fijobono_' . $arr_el[1]) == 'on' ? 1 : 0,
                        'created_at' => date("Y-m-d H:i:s")
                    );

                    $monto_bono = (int)$array_bono['monto'];
                    if ($monto_bono > 0) {
                        array_push($array_bonos, $array_bono);
                    }
                }
            }

            $perfil = 5;
            $registro = $this->input->post('registro');
            $iduser = $this->input->post('iduser');
            $emailuser = $this->input->post('emailuser');
            $password = $this->input->post('password');
            $idtrabajador = $this->input->post("idtrabajador");
            $rut = str_replace(".", "", $this->input->post("rut"));
            $arrayRut = explode("-", $rut);
            $nombre = $this->input->post('nombre');
            $apaterno = $this->input->post('apaterno');
            $amaterno = $this->input->post('amaterno');
            $fecnacimiento = $this->input->post('fechanacimiento');
            
            $sexo = $this->input->post('sexo');
            $idecivil = $this->input->post('ecivil');
            $nacionalidad = $this->input->post('nacionalidad');
            $direccion = $this->input->post('direccion');
            $idregion = $this->input->post('region');
            $idcomuna = $this->input->post('comuna');
            $fono = $this->input->post('fono');
            $emailcontacto = $this->input->post('emailcontacto');
            $fecingreso = $this->input->post('fechaingreso');
            $fecafc = $this->input->post('fechaafc');
            $idcargo = $this->input->post('cargo');
            $fecinicvacaciones = $this->input->post('fecinicvacaciones');
            $saldoinicvacaciones = $this->input->post('saldoinicvacaciones');
            $saldoinicvacprog = $this->input->post('saldoinicvacprog');
            $pensionado = $this->input->post('pensionado') == 'on' ? 1 : 0;
            $tipocontrato = $this->input->post('tipocontrato');
            $parttime = $this->input->post('parttime') == 'on' ? 1 : 0;
            $segcesantia = $this->input->post('segcesantia') == 'on' ? 1 : 0;
            $diastrabajo = $this->input->post('diastrabajo');
            $horasdiarias = $this->input->post('horasdiarias');
            $horassemanales = $this->input->post('horassemanales');
            $sueldobase = str_replace(".", "", $this->input->post('sueldobase'));
            $tipogratificacion = $this->input->post('tipogratificacion');
            $gratificacion = str_replace(".", "", $this->input->post('gratificacion'));
            $cargassimples = $this->input->post('cargassimples');
            $cargasinvalidas = $this->input->post('cargasinvalidas');
            $cargasmaternales = $this->input->post('cargasmaternales');
            $cargasretroactivas = $this->input->post('cargasretroactivas');
            $tramo_asigfamiliar = $this->input->post('tramo_asigfamiliar') == '' ? null : $this->input->post('tramo_asigfamiliar');


            $fecfiniquito = $this->input->post('fechafiniquito');
            $causalfiniquito = $this->input->post('causalfiniquito');
            $indmesaviso = str_replace(".", "", $this->input->post('indmesaviso')); 
            $indannoservicio = str_replace(".", "", $this->input->post('indannoservicio')); 
            $indferiadolegal = str_replace(".", "", $this->input->post('indferiadolegal')); 
            $indvoluntaria = str_replace(".", "", $this->input->post('indvoluntaria'));
            $indtotal = str_replace(".", "", $this->input->post('indtotal'));

            $asigfamiliar = str_replace(".", "", $this->input->post('asigfamiliar'));
            $movilizacion = str_replace(".", "", $this->input->post('movilizacion'));
            $colacion = str_replace(".", "", $this->input->post('colacion'));
            $activo = $this->input->post('activo') == 'on' ? 1 : 0;

            $array_datos = array(
                'idcomunidad' => $this->session->userdata('comunidadid'),
                'rut' => $idtrabajador == 0 ? $arrayRut[0] : "",
                'dv' => $idtrabajador == 0 ? $arrayRut[1] : "",
                'nombre' => $nombre,
                'apaterno' => $apaterno,
                'amaterno' => $amaterno,
                'fecnacimiento' => substr($fecnacimiento, 6, 4) . "-" . substr($fecnacimiento, 3, 2) . "-" . substr($fecnacimiento, 0, 2),
                'fecfiniquito' => $fecfiniquito == '' ? null : substr($fecfiniquito, 6, 4) . "-" . substr($fecfiniquito, 3, 2) . "-" . substr($fecfiniquito, 0, 2),
                'sexo' => $sexo,
                'idecivil' => $idecivil,
                'nacionalidad' => $nacionalidad,
                'direccion' => $direccion,
                'idregion' => $idregion,
                'idcomuna' => $idcomuna,
                'fono' => $fono,
                'email' => $emailcontacto,
                'fecingreso' => substr($fecingreso, 6, 4) . "-" . substr($fecingreso, 3, 2) . "-" . substr($fecingreso, 0, 2),
                'fecafc' => $segcesantia == 1 ? substr($fecafc, 6, 4) . "-" . substr($fecafc, 3, 2) . "-" . substr($fecafc, 0, 2) : null,
                'idcargo' => $idcargo,
                'fecinicvacaciones' => substr($fecinicvacaciones, 6, 4) . "-" . substr($fecinicvacaciones, 3, 2) . "-" . substr($fecinicvacaciones, 0, 2),
                'saldoinicvacaciones' => $saldoinicvacaciones,
                'saldoinicvacprog' => $saldoinicvacprog,
                'tipocontrato' => $tipocontrato,
                'parttime' => $parttime,
                'segcesantia' => $segcesantia,
                'pensionado' => $pensionado,
                'diastrabajo' => $diastrabajo,
                'horasdiarias' => $horasdiarias,
                'horassemanales' => $horassemanales,
                'sueldobase' => $sueldobase,
                'tipogratificacion' => $tipogratificacion,
                'gratificacion' => $gratificacion,
                'cargassimples' => $cargassimples,
                'cargasinvalidas' => $cargasinvalidas,
                'cargasmaternales' => $cargasmaternales,
                'cargasretroactivas' => $cargasretroactivas,
                'causalfiniquito' => $causalfiniquito,    
                'indmesaviso' => $indmesaviso,
                'indannoservicio' => $indannoservicio,
                'indferiadolegal' => $indferiadolegal,
                'indvoluntaria' => $indvoluntaria,
                'indtotal' => $indtotal,

                'idasigfamiliar' => $tramo_asigfamiliar,
                'asigfamiliar' => $asigfamiliar,
                'movilizacion' => $movilizacion,
                'colacion' => $colacion,
                'active' => $activo
            );
            $this->load->model('remuneracion');
            $idpersonal = $this->remuneracion->add_personal($array_datos, $array_bonos, $idtrabajador);

            if ($idpersonal == -1) {
                $this->session->set_flashdata('personal_result', 2);
            } else if (!is_null($registro)) {
                // Valida si existe el email
                $existe = $this->admin->valida_existe_mail_personal($emailuser);
                $valide_rut = true;
                $activate = false;

                if ($existe) {
                    // Activa si se encuentra desactivada
                    $activate = $activo === 1 && $existe->active === '0' ? true : false;
                    // Valida si rut ingresado es igual al rut del usuario que ya existe
                    $valide_rut = $existe->rut === $arrayRut[0] && $existe->dv === $arrayRut[1] ? true : false;
                    // Si el rut es valido se asigna el mismo id de usuario
                    $iduser = $valide_rut ? $existe->id : $iduser;
                }

                if ($valide_rut) {

                    $additional_data = array(
                        'first_name' => $nombre,
                        'last_name'  => $apaterno . ' ' . $amaterno,
                        'company'    => '',
                        'phone'      => '',
                    );

                    if ($iduser != 0) {
                        $additional_data['email'] = $emailuser;
                        $additional_data['username'] = $emailuser;
                        $additional_data['password'] = '';
                    }

                    $userid = $iduser === '0' && !$existe ? $this->ion_auth->register($emailuser, $password, $emailuser, $additional_data) : $this->ion_auth->update($iduser, $additional_data); // creacion/actualizacion de usuario

                    $userid = $iduser === '0' ? $userid : $iduser;

                    //Actualiza perfil
                    $this->ion_auth->update_level($userid, $perfil);
                    // Asocia comunidad
                    $this->ion_auth->asocia_comunidad($userid, [$this->session->userdata('comunidadid')]);
                    // Asocia iduser a personal
                    $idtrabajador = $idtrabajador == 0 ? $idpersonal : $idtrabajador;
                    $this->remuneracion->asocia_id_personal($userid, $idtrabajador);
                    // Activa usuario si se encuentra desactivado
                    if ($activate) $this->ion_auth->activate($iduser);

                    // envio de mail
                    if ($iduser == 0 || $activate) {
                        $this->load->model('admin');
                        $this->admin->mail_creacion_usuario($userid, $password);
                    }

                    if ($iduser == 0 && $userid != 0) {
                        $this->session->set_flashdata('personal_result', 1);
                    } else if ($iduser != 0) {
                        $this->session->set_flashdata('personal_result', 6);
                    } else {
                        $this->session->set_flashdata('personal_result', 8);
                    }
                } else {
                    $this->session->set_flashdata('personal_result', 9);
                }
            } else {
                if ($activo == 0 && $iduser != 0) {
                    $comunidades_asignadas = $this->admin->comunidades_asignadas($iduser, $perfil);
                    if (count($comunidades_asignadas) > 1) {
                        $this->remuneracion->desactiva_comunidad_user($iduser, $this->session->userdata('comunidadid'));
                    } else {
                        $this->ion_auth->deactivate($iduser);
                    }
                }
                if ($idtrabajador == 0) {
                    $this->session->set_flashdata('personal_result', 1);
                } else {
                    $this->session->set_flashdata('personal_result', 6);
                }
            }
            redirect('remuneraciones/personal');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function validate_sueldo_minimo($data = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $sueldobase = str_replace(".", "", $this->input->post('sueldobase'));
            $horassemanales = $this->input->post('horassemanales');

            $parttime = $this->input->post('parttime');

            $this->load->model('remuneracion');
            $parametros_generales = $this->remuneracion->get_parametros_generales();

            $valor_hora = $parametros_generales->sueldominimo / 45;
            $sueldominimo_proporcional = (int)($valor_hora * $horassemanales);

            if ($parttime == 'on') {
                $data['result'] = "ok";
            } else {
                //if($sueldobase < $parametros_generales->sueldominimo){
                if ($sueldobase < $sueldominimo_proporcional) {
                    $data['result'] = "error";
                    $data['fields']['sueldobase'] = "Sueldo Base no puede ser menor a Sueldo M&iacute;nimo";
                } else {
                    $data['result'] = "ok";
                }
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



    public function get_datos_finiquito($idtrabajador = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');

            $personal = $this->remuneracion->get_personal_total($idtrabajador);
           // var_dump($personal->fecinicvacaciones_sformato); exit;
            $dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones_sformato, $personal->saldoinicvacaciones);
            
            $dias_progresivos = $this->remuneracion->get_dias_progresivos($idtrabajador);
            $num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones_sformato, $personal->saldoinicvacprog, $dias_progresivos);         


            $dias_tomados =   $personal->diasvactomados;
            
            $vacaciones_totales =  $dias_vacaciones+$num_dias_progresivos - $dias_tomados;
            //    echo '<pre>';
            //    var_dump($personal);
           //     var_dump($dias_vacaciones);
             //   var_dump($num_dias_progresivos);

            if($this->session->userdata('identity') == 'csandoval@aurbana.cl' || $this->session->userdata('identity') == 'fsandoval@aurbana.cl'|| $this->session->userdata('identity') == 'admin@admin.com'){
                $datos_finiquito['mes_aviso'] = number_format($personal->sueldobase + $personal->bonos_fijos + $personal->movilizacion + $personal->colacion,0,".",".");
                $datos_finiquito['renta_antiguedad'] = number_format(($personal->sueldobase + $personal->bonos_fijos + $personal->movilizacion + $personal->colacion)*$personal->annos_empresa,0,".",".");
                $datos_finiquito['renta_vacaciones'] = number_format(($personal->sueldobase + $personal->bonos_fijos + $personal->movilizacion + $personal->colacion)/30*($vacaciones_totales)*1.4,0,".",".");

            }else{
                $datos_finiquito['mes_aviso'] = 0;
                $datos_finiquito['renta_antiguedad'] = 0;
                $datos_finiquito['renta_vacaciones'] = 0;
            }




            echo json_encode($datos_finiquito);
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

    public function get_cot_obligatoria($idafp)
    {

        $this->load->model('remuneracion');
        $afp = $this->remuneracion->get_afp($idafp);
        echo json_encode($afp);
    }


    public function asistencia($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $resultid = $this->session->flashdata('asistencia_result');
            if ($resultid == 1) {
                $vars['message'] = "Asistencia agregada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar asistencia";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            //$this->load->model('admin');
            //$comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

            $mes = $this->session->flashdata('asistencia_mes') == '' ? date('m') : $this->session->flashdata('asistencia_mes');
            $anno = $this->session->flashdata('asistencia_anno') == '' ? date('Y') : $this->session->flashdata('asistencia_anno');



            $this->load->model('remuneracion');
            $personal = $this->remuneracion->get_personal();
            $datos_remuneracion = $this->remuneracion->get_datos_remuneracion($mes, $anno);

            $array_remuneracion_trabajador = array();
            foreach ($datos_remuneracion as $remuneracion) {
                $array_remuneracion_trabajador[$remuneracion->idpersonal] = $remuneracion->diastrabajo;
            }


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Asistencia'
            );

            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['datos_remuneracion'] = $array_remuneracion_trabajador;
            $vars['mes'] = $mes;
            $vars['anno'] = $anno;
            $vars['content_view'] = 'remuneraciones/asistencia';
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


    public function submit_asistencia()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $mes = $this->input->post('mes');
            $anno = $this->input->post('anno');

            //if($mes == '' || $anno == ''){
            if (empty($mes) && empty($anno)) {
                $this->session->set_flashdata('asistencia_result', 2);
                redirect('remuneraciones/asistencia');
            }


            $array_elem = $this->input->post(NULL, true);
            $array_trabajadores = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("_", $elem);
                if ($arr_el[0] == 'diastrabajo') {
                    $array_trabajadores[$arr_el[1]] = $value_elem;
                }
            }




            $this->load->model('remuneracion');
            $this->remuneracion->save_asistencia($array_trabajadores, $mes, $anno);

            $this->session->set_flashdata('asistencia_result', 1);
            $this->session->set_flashdata('asistencia_mes', $mes);
            $this->session->set_flashdata('asistencia_anno', $anno);
            redirect('remuneraciones/asistencia');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function estado_periodo($tipo_status = null)
    {

        $this->load->model('admin');
        $valid = $this->admin->get_permite_periodo($this->input->post('mes'), $this->input->post('anno'));
        if ($valid) {
            $this->load->model('remuneracion');
            $estado_periodo = $this->remuneracion->get_estado_periodo($this->input->post('mes'), $this->input->post('anno'));

            if (is_null($tipo_status)) {
                $valid = $estado_periodo == 1 || $estado_periodo == 2 || $estado_periodo == 3 ? true : false;
            } else {
                $valid = $estado_periodo == 1 || $estado_periodo == 2 ? true : false;
            }
        }


        echo json_encode(array(
            'valid' => $valid
        ));
    }



    public function valida_vacaciones($tipo_status = null)
    {

        $this->load->model('remuneracion');
        $idpersonal = $this->input->post('idpersonal');
        $fechadesde = $this->input->post('fechadesde');
        $fechahasta = $this->input->post('fechahasta');
        $valid = $this->remuneracion->get_valida_vacaciones($idpersonal, $fechadesde, $fechahasta);

        echo json_encode(array(
            'valid' => $valid
        ));
    }



    public function get_datos_remuneracion($mes, $anno)
    {
        $this->load->model('remuneracion');
        $datos_remuneracion = $this->remuneracion->get_datos_remuneracion($mes, $anno);
        $array_remuneracion_trabajador = array();
        foreach ($datos_remuneracion as $remuneracion) {
            $array_remuneracion_trabajador["valorhora_" . $remuneracion->idpersonal] = $remuneracion->valorhora;
            $array_remuneracion_trabajador["diastrabajo_" . $remuneracion->idpersonal] = $remuneracion->diastrabajo;
            $array_remuneracion_trabajador["horasdescuento_" . $remuneracion->idpersonal] = $remuneracion->horasdescuento;
            $array_remuneracion_trabajador["montodescuento_" . $remuneracion->idpersonal] = $remuneracion->montodescuento;
            $array_remuneracion_trabajador["valorhorasextras50_" . $remuneracion->idpersonal] = $remuneracion->valorhorasextras50;
            $array_remuneracion_trabajador["horasextras50_" . $remuneracion->idpersonal] = $remuneracion->horasextras50;
            $array_remuneracion_trabajador["montohorasextras50_" . $remuneracion->idpersonal] = $remuneracion->montohorasextras50;
            $array_remuneracion_trabajador["valorhorasextras100_" . $remuneracion->idpersonal] = $remuneracion->valorhorasextras100;
            $array_remuneracion_trabajador["horasextras100_" . $remuneracion->idpersonal] = $remuneracion->horasextras100;
            $array_remuneracion_trabajador["montohorasextras100_" . $remuneracion->idpersonal] = $remuneracion->montohorasextras100;
            $array_remuneracion_trabajador["anticipo_" . $remuneracion->idpersonal] = $remuneracion->anticipo;
            $array_remuneracion_trabajador["aguinaldo_" . $remuneracion->idpersonal] = $remuneracion->aguinaldo;
        }
        echo json_encode($array_remuneracion_trabajador);
    }



    public function get_datos_descuentos($mes, $anno)
    {
        $this->load->model('remuneracion');
        $datos_descuentos = $this->remuneracion->get_datos_descuentos($mes, $anno);

        echo json_encode($datos_descuentos);
    }

    public function get_status_rem($tipo_status, $mes, $anno)
    {
        $this->load->model('remuneracion');
        $estado_periodo = $this->remuneracion->get_estado_periodo($mes, $anno);

        //OPCIONES:  ES NUEVO, YA SE CREO, YA ESTÁ CERRADO

        if ($estado_periodo == 2) { // NO EXISTE, ES NUEVO
            $array_result['label_style'] = 'label-primary';
            $array_result['label_text'] = 'Per&iacute;odo Nuevo (sin datos)';
            $array_result['status'] = 'nuevo';
        } else if ($estado_periodo == 0) { // NO EXISTE, ES NUEVO
            $array_result['label_style'] = 'label-danger';
            $array_result['label_text'] = 'Per&iacute;odo Cerrado';
            $array_result['status'] = 'cerrado';
        } else {
            $datos_pendientes = false;
            $personal = $this->remuneracion->get_personal();
            foreach ($personal as $trabajador) {
                $datos_remuneracion = $this->remuneracion->get_datos_remuneracion($mes, $anno, $trabajador->id);
                if (is_null($datos_remuneracion)) {
                    $datos_pendientes = true;
                    break;
                } else {
                    if ($tipo_status == 'asistencia') {
                        if (is_null($datos_remuneracion->diastrabajo)) {
                            $datos_pendientes = true;
                            break;
                        }
                    } else if ($tipo_status == 'horas_descuentos') {
                        if (
                            is_null($datos_remuneracion->horasdescuento) ||
                            is_null($datos_remuneracion->montodescuento)
                        ) {
                            $datos_pendientes = true;
                            break;
                        }
                    } else if ($tipo_status == 'horas_extraordinarias') {
                        if (
                            is_null($datos_remuneracion->horasextras50) ||
                            is_null($datos_remuneracion->montohorasextras50) ||
                            is_null($datos_remuneracion->horasextras100) ||
                            is_null($datos_remuneracion->montohorasextras100)
                        ) {
                            $datos_pendientes = true;
                            break;
                        }
                    } else if ($tipo_status == 'anticipos') {
                        if (
                            is_null($datos_remuneracion->anticipo) ||
                            is_null($datos_remuneracion->aguinaldo)
                        ) {
                            $datos_pendientes = true;
                            break;
                        }
                    }
                }
            }

            if ($datos_pendientes) {

                if ($tipo_status == 'anticipos' && $estado_periodo == 3) {
                    $array_result['label_style'] = 'label-danger';
                    $array_result['label_text'] = 'Datos de Anticipo ya traspasados';
                    $array_result['status'] = 'cerrado';
                } else {
                    $array_result['label_style'] = 'label-warning';
                    $array_result['label_text'] = 'Per&iacute;odo con datos pendientes ';
                    $array_result['status'] = 'pendiente';
                }
            } else {

                if ($tipo_status == 'anticipos') {
                    if ($estado_periodo == 1) {
                        $array_result['label_style'] = 'label-success';
                        $array_result['label_text'] = 'Datos ingresados (puede editar informaci&oacute;n)';
                        $array_result['status'] = 'ingresado';
                    } else {
                        $array_result['label_style'] = 'label-danger';
                        $array_result['label_text'] = 'Datos de Anticipo ya traspasados';
                        $array_result['status'] = 'cerrado';
                    }
                } else {
                    $array_result['label_style'] = 'label-success';
                    $array_result['label_text'] = 'Datos ingresados (puede editar informaci&oacute;n)';
                    $array_result['status'] = 'ingresado';
                }
            }
        }


        $array_result['estado'] = $estado_periodo;

        echo json_encode($array_result);
    }




    public function horas_descuentos($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $resultid = $this->session->flashdata('horas_descuentos_result');
            if ($resultid == 1) {
                $vars['message'] = "Horas de descuento agregadas correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Horas de Descuento";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            //$this->load->model('admin');
            //$comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

            $mes = $this->session->flashdata('horas_descuentos_mes') == '' ? date('m') : $this->session->flashdata('horas_descuentos_mes');
            $anno = $this->session->flashdata('horas_descuentos_anno') == '' ? date('Y') : $this->session->flashdata('horas_descuentos_anno');



            $this->load->model('remuneracion');
            $personal = $this->remuneracion->get_personal();
            $datos_remuneracion = $this->remuneracion->get_datos_remuneracion($mes, $anno);
            $array_remuneracion_trabajador = array();
            foreach ($datos_remuneracion as $remuneracion) {
                $array_remuneracion_trabajador['valorhora'][$remuneracion->idpersonal] = $remuneracion->valorhora;
                $array_remuneracion_trabajador['horasdescuento'][$remuneracion->idpersonal] = $remuneracion->horasdescuento;
                $array_remuneracion_trabajador['montodescuento'][$remuneracion->idpersonal] = $remuneracion->montodescuento;
            }

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Horas de Descuento'
            );

            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['datos_remuneracion'] = $array_remuneracion_trabajador;
            $vars['mes'] = $mes;
            $vars['anno'] = $anno;
            $vars['content_view'] = 'remuneraciones/horas_descuentos';
            $vars['formValidation'] = true;
            $vars['maleta'] = true;

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


    public function submit_horas_descuentos()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $mes = $this->input->post('mes');
            $anno = $this->input->post('anno');

            //if($mes == '' || $anno == ''){
            if (empty($mes) && empty($anno)) {
                $this->session->set_flashdata('horas_descuentos_result', 2);
                redirect('remuneraciones/horas_descuentos');
            }


            $array_elem = $this->input->post(NULL, true);
            $array_trabajadores = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("_", $elem);
                if ($arr_el[0] == 'horasdescuento') {
                    $array_trabajadores[$arr_el[1]]['horasdescuento'] = $value_elem;
                }

                if ($arr_el[0] == 'valordescuento') {
                    $array_trabajadores[$arr_el[1]]['valordescuento'] = $value_elem;
                }
            }


            $this->load->model('remuneracion');
            $this->remuneracion->save_horas_descuentos($array_trabajadores, $mes, $anno);

            $this->session->set_flashdata('horas_descuentos_result', 1);
            $this->session->set_flashdata('horas_descuentos_mes', $mes);
            $this->session->set_flashdata('horas_descuentos_anno', $anno);
            redirect('remuneraciones/horas_descuentos');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }



    public function horas_extraordinarias($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $resultid = $this->session->flashdata('horas_extraordinarias_result');
            if ($resultid == 1) {
                $vars['message'] = "Horas Extraordinarias agregadas correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Horas Extraordinarias";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            //$this->load->model('admin');
            //$comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

            $mes = $this->session->flashdata('horas_extraordinarias_mes') == '' ? date('m') : $this->session->flashdata('horas_extraordinarias_mes');
            $anno = $this->session->flashdata('horas_extraordinarias_anno') == '' ? date('Y') : $this->session->flashdata('horas_extraordinarias_anno');



            $this->load->model('remuneracion');
            $personal = $this->remuneracion->get_personal();

            $datos_remuneracion = $this->remuneracion->get_datos_remuneracion($mes, $anno);
            $array_remuneracion_trabajador = array();
            foreach ($datos_remuneracion as $remuneracion) {
                $array_remuneracion_trabajador['horasextras50'][$remuneracion->idpersonal] = $remuneracion->horasextras50;
                $array_remuneracion_trabajador['montohorasextras50'][$remuneracion->idpersonal] = $remuneracion->montohorasextras50;

                $array_remuneracion_trabajador['horasextras100'][$remuneracion->idpersonal] = $remuneracion->horasextras100;
                $array_remuneracion_trabajador['montohorasextras100'][$remuneracion->idpersonal] = $remuneracion->montohorasextras100;
            }

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Horas Extraordinarias'
            );

            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['datos_remuneracion'] = $array_remuneracion_trabajador;
            $vars['mes'] = $mes;
            $vars['anno'] = $anno;
            $vars['content_view'] = 'remuneraciones/horas_extraordinarias';
            $vars['formValidation'] = true;
            $vars['maleta'] = true;

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


    public function submit_horas_extraordinarias()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $mes = $this->input->post('mes');
            $anno = $this->input->post('anno');

            //if($mes == '' || $anno == ''){
            if (empty($mes) && empty($anno)) {
                $this->session->set_flashdata('horas_extraordinarias_result', 2);
                redirect('remuneraciones/horas_extraordinarias');
            }


            $array_elem = $this->input->post(NULL, true);
            $array_trabajadores = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("_", $elem);
                if ($arr_el[0] == 'horas50') {
                    $array_trabajadores[$arr_el[1]]['horas50'] = $value_elem;
                }

                if ($arr_el[0] == 'monto50') {
                    $array_trabajadores[$arr_el[1]]['monto50'] = $value_elem;
                }

                if ($arr_el[0] == 'horas100') {
                    $array_trabajadores[$arr_el[1]]['horas100'] = $value_elem;
                }

                if ($arr_el[0] == 'monto100') {
                    $array_trabajadores[$arr_el[1]]['monto100'] = $value_elem;
                }
            }


            $this->load->model('remuneracion');
            $this->remuneracion->save_horas_extraordinarias($array_trabajadores, $mes, $anno);

            $this->session->set_flashdata('horas_extraordinarias_result', 1);
            $this->session->set_flashdata('horas_extraordinarias_mes', $mes);
            $this->session->set_flashdata('horas_extraordinarias_anno', $anno);
            redirect('remuneraciones/horas_extraordinarias');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function anticipos($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $resultid = $this->session->flashdata('anticipos_result');
            if ($resultid == 1) {
                $vars['message'] = "Anticipos agregados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Anticipos";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al traspasar datos anticipos";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Datos de Anticipos traspasados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 5) {
                $vars['message'] = "Error al reversar traspaso de anticipos";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 6) {
                $vars['message'] = "Traspaso de Anticipos reversados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 7) {
                $vars['message'] = "Error al reversar traspaso de anticipos.  Ya existen pagos asociados";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 8) {
                $vars['message'] = "Error al reversar traspaso de anticipos.  Cuentas ya autorizadas en gasto com&uacute;n";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            //$this->load->model('admin');
            //$comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

            $mes = $this->session->flashdata('anticipos_mes') == '' ? date('m') : $this->session->flashdata('anticipos_mes');
            $anno = $this->session->flashdata('anticipos_anno') == '' ? date('Y') : $this->session->flashdata('anticipos_anno');



            $this->load->model('remuneracion');
            $personal = $this->remuneracion->get_personal();
            $datos_remuneracion = $this->remuneracion->get_datos_remuneracion($mes, $anno);
            $array_remuneracion_trabajador = array();
            foreach ($datos_remuneracion as $remuneracion) {
                $array_remuneracion_trabajador['anticipo'][$remuneracion->idpersonal] = $remuneracion->anticipo;
                $array_remuneracion_trabajador['aguinaldo'][$remuneracion->idpersonal] = $remuneracion->aguinaldo;
            }

            $parametros_generales = $this->remuneracion->get_parametros_generales();


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Anticipos'
            );

            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['datos_remuneracion'] = $array_remuneracion_trabajador;
            $vars['mes'] = $mes;
            $vars['anno'] = $anno;
            $vars['content_view'] = 'remuneraciones/anticipos';
            $vars['formValidation'] = true;
            $vars['maleta'] = true;
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


    public function traspasa_anticipos($mes, $anno)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            if (empty($mes) && empty($anno)) {
                $this->session->set_flashdata('anticipos_result', 3);
                redirect('remuneraciones/anticipos');
            }


            $this->load->model('remuneracion');
            $this->remuneracion->traspasa_anticipo($mes, $anno);

            $this->session->set_flashdata('anticipos_result', 4);
            $this->session->set_flashdata('anticipos_mes', $mes);
            $this->session->set_flashdata('anticipos_anno', $anno);
            redirect('remuneraciones/anticipos');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function reversa_anticipos($mes, $anno)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            if (empty($mes) && empty($anno)) {
                $this->session->set_flashdata('anticipos_result', 5);
                redirect('remuneraciones/anticipos');
            }

            $this->load->model('remuneracion');
            $result = $this->remuneracion->reversa_anticipo($mes, $anno);

            if ($result == 1) {
                $status = 6;
            } else if ($result == 2) {
                $status = 7;
            } else if ($result == 4) {
                $status = 8;
            } else {
                $status = 5;
            }


            $this->session->set_flashdata('anticipos_result', $status);
            $this->session->set_flashdata('anticipos_mes', $mes);
            $this->session->set_flashdata('anticipos_anno', $anno);
            redirect('remuneraciones/anticipos');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function submit_anticipos()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $mes = $this->input->post('mes');
            $anno = $this->input->post('anno');

            //if($mes == '' || $anno == ''){
            if (empty($mes) && empty($anno)) {
                $this->session->set_flashdata('anticipos_result', 2);
                redirect('remuneraciones/anticipos');
            }


            $array_elem = $this->input->post(NULL, true);
            $array_trabajadores = array();
            foreach ($array_elem as $elem => $value_elem) {
                $arr_el = explode("_", $elem);
                if ($arr_el[0] == 'anticipo') {
                    $array_trabajadores[$arr_el[1]]['anticipo'] = str_replace(".", "", $value_elem);
                }

                if ($arr_el[0] == 'aguinaldo') {
                    $array_trabajadores[$arr_el[1]]['aguinaldo'] = str_replace(".", "", $value_elem);
                }
            }


            $this->load->model('remuneracion');
            $this->remuneracion->save_anticipo($array_trabajadores, $mes, $anno);

            $this->session->set_flashdata('anticipos_result', 1);
            $this->session->set_flashdata('anticipos_mes', $mes);
            $this->session->set_flashdata('anticipos_anno', $anno);
            redirect('remuneraciones/anticipos');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }



    public function calculo_remuneraciones($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $resultid = $this->session->flashdata('calculo_remuneraciones_result');
            if ($resultid == 1) {
                $vars['message'] = "Remuneraciones calculadas correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al Calcular Remuneraciones. Falta informaci&oacute;n para per&iacute;odo seleccionado";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } else if ($resultid == 3) {
                $vars['message'] = "Remuneracion aprobada";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } else if ($resultid == 4) {
                $vars['message'] = "Remuneracion rechazada.  Puede corregir los valores necesarios para calcular nuevamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } else if ($resultid == 4) {
                $vars['message'] = "Aprobaci&oacute;n Reversada.  Puede rechazar remuneraci&oacute;n para modificar valores";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('remuneracion');
            $periodos_remuneracion = $this->remuneracion->get_periodos_remuneracion_abiertos();
            $periodos_remuneracion_sin_abonos = $this->remuneracion->get_periodos_remuneracion_cerrados_sin_abonos();

            $personal = $this->remuneracion->get_personal();

            $array_remuneracion_trabajador = array();
            $mensajes = array();
            $mensaje_html = array();
            foreach ($periodos_remuneracion as $periodos) {
                $mensajes[$periodos->id] = array();


                $estado = "Informaci&oacute;n Completa";
                if (is_null($periodos->cierre)) {
                    foreach ($personal as $trabajador) {
                        if (is_null($trabajador->idafp)) {
                            if (!in_array("Informaci&oacute;n Afp", $mensajes[$periodos->id])) {
                                array_push($mensajes[$periodos->id], "Informaci&oacute;n Afp");
                            }
                            $estado = "Falta Informaci&oacute;n";
                            //break;
                        }

                        if (is_null($trabajador->tipoahorrovol)) {

                            if (!in_array("Informaci&oacute;n Afp", $mensajes[$periodos->id])) {
                                array_push($mensajes[$periodos->id], "Informaci&oacute;n Afp");
                            }
                            $estado = "Falta Informaci&oacute;n";
                            //break;
                        }

                        /*if(is_null($trabajador->tipocotapv)){
		                   	   if(!in_array("Informaci&oacute;n Afp", $mensajes[$periodos->id])){
		                   	   		array_push($mensajes[$periodos->id],"Informaci&oacute;n Afp");
		                   	   }
		                       $estado = "Falta Informaci&oacute;n";
		                   	   //break;
						}*/

                        if (is_null($trabajador->idisapre)) {
                            if (!in_array("Informaci&oacute;n Cotizaci&oacute;n de Salud", $mensajes[$periodos->id])) {
                                array_push($mensajes[$periodos->id], "Informaci&oacute;n Cotizaci&oacute;n de Salud");
                            }
                            $estado = "Falta Informaci&oacute;n";
                            //break;
                        }

                        $datos_remuneracion = $this->remuneracion->get_datos_remuneracion($periodos->mes, $periodos->anno, $trabajador->id);
                        if (is_null($datos_remuneracion)) {
                            if (!in_array("Informaci&oacute;n Asistencia", $mensajes[$periodos->id])) {
                                array_push($mensajes[$periodos->id], "Informaci&oacute;n Asistencia");
                            }
                            if (!in_array("Informaci&oacute;n Descuentos", $mensajes[$periodos->id])) {
                                array_push($mensajes[$periodos->id], "Informaci&oacute;n Descuentos");
                            }
                            if (!in_array("Informaci&oacute;n Horas Extras", $mensajes[$periodos->id])) {
                                array_push($mensajes[$periodos->id], "Informaci&oacute;n Horas Extras");
                            }
                            if (!in_array("Informaci&oacute;n Anticipos/Aguinaldo", $mensajes[$periodos->id])) {
                                array_push($mensajes[$periodos->id], "Informaci&oacute;n Anticipos/Aguinaldo");
                            }
                            $estado = "Falta Informaci&oacute;n";
                            //break;
                        } else {
                            if (is_null($datos_remuneracion->diastrabajo)) {
                                if (!in_array("Informaci&oacute;n Asistencia", $mensajes[$periodos->id])) {
                                    array_push($mensajes[$periodos->id], "Informaci&oacute;n Asistencia");
                                }
                                $estado = "Falta Informaci&oacute;n";
                                //break;
                            }

                            if (
                                is_null($datos_remuneracion->horasdescuento) ||
                                is_null($datos_remuneracion->montodescuento)
                            ) {
                                if (!in_array("Informaci&oacute;n Descuentos", $mensajes[$periodos->id])) {
                                    array_push($mensajes[$periodos->id], "Informaci&oacute;n Descuentos");
                                }
                                $estado = "Falta Informaci&oacute;n";
                                //break;
                            }

                            if (
                                is_null($datos_remuneracion->horasextras50) ||
                                is_null($datos_remuneracion->montohorasextras50) ||
                                is_null($datos_remuneracion->horasextras100) ||
                                is_null($datos_remuneracion->montohorasextras100)
                            ) {
                                if (!in_array("Informaci&oacute;n Horas Extras", $mensajes[$periodos->id])) {
                                    array_push($mensajes[$periodos->id], "Informaci&oacute;n Horas Extras");
                                }
                                $estado = "Falta Informaci&oacute;n";
                                //break;
                            }

                            if (is_null($periodos->anticipo)) {
                                if (
                                    is_null($datos_remuneracion->anticipo) ||
                                    is_null($datos_remuneracion->aguinaldo)
                                ) {
                                    if (!in_array("Informaci&oacute;n Anticipos/Aguinaldo", $mensajes[$periodos->id])) {
                                        array_push($mensajes[$periodos->id], "Informaci&oacute;n Anticipos/Aguinaldo");
                                    }
                                    $estado = "Falta Informaci&oacute;n";
                                    //break;
                                }
                            }
                        } // end else


                    } // end foreach

                }


                $periodos->estado = $estado;

                $mensaje_html[$periodos->id] = "";
                if (count($mensajes[$periodos->id]) > 0) {
                    $mensaje_html[$periodos->id] .= "<ul>";
                    foreach ($mensajes[$periodos->id] as $mensaje) {
                        $mensaje_html[$periodos->id] .= "<li>" . $mensaje . "</li>";
                    }
                    $mensaje_html[$periodos->id] .= "</ul>";
                }
            }

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Calculo Remuneraci&oacute;n'
            );

            $vars['content_menu'] = $content;
            $vars['periodos_remuneracion'] = $periodos_remuneracion;
            $vars['periodos_remuneracion_sin_abonos'] = $periodos_remuneracion_sin_abonos;
            $vars['mensaje_html'] = $mensaje_html;
            $vars['content_view'] = 'remuneraciones/calculo_remuneraciones';

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


    public function submit_calculo_remuneraciones($idperiodo)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            set_time_limit(0);
            $this->load->model('remuneracion');
            $datos_remuneracion = $this->remuneracion->get_datos_remuneracion_by_periodo($idperiodo);
            $periodo_remuneracion = $this->remuneracion->get_periodos_remuneracion_abiertos($idperiodo);
            if (is_null($periodo_remuneracion->cierre)) {
                $estado = "Informaci&oacute;n Completa";
                foreach ($datos_remuneracion as $remuneracion) {
                    if (
                        is_null($remuneracion->diastrabajo) ||
                        is_null($remuneracion->horasdescuento) ||
                        is_null($remuneracion->montodescuento) ||
                        is_null($remuneracion->horasextras50) ||
                        is_null($remuneracion->montohorasextras50) ||
                        is_null($remuneracion->horasextras100) ||
                        is_null($remuneracion->montohorasextras100) ||
                        (is_null($remuneracion->anticipo) && is_null($periodo_remuneracion->anticipo)) ||
                        is_null($remuneracion->aguinaldo) && is_null($periodo_remuneracion->anticipo)
                    ) {
                        $estado = "Falta Informaci&oacute;n";
                        break;
                    }
                }


                if ($estado == 'Falta Informaci&oacute;n') { // no permite calcular remuneraciones
                    $this->session->set_flashdata('calculo_remuneraciones_result', 2);
                } else {
                    $this->remuneracion->calcular_remuneraciones($idperiodo);
                    $this->session->set_flashdata('calculo_remuneraciones_result', 1);
                }
            }
            redirect('remuneraciones/calculo_remuneraciones');
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


    public function aprueba_remuneraciones($idperiodo)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $publicar = $this->remuneracion->aprobar_remuneracion($idperiodo);

            $this->session->set_flashdata('calculo_remuneraciones_result', 3);
            redirect('remuneraciones/calculo_remuneraciones');
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

    public function rechaza_remuneraciones($idperiodo)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            set_time_limit(0);
            $this->load->model('remuneracion');
            $publicar = $this->remuneracion->rechazar_remuneracion($idperiodo);

            $this->session->set_flashdata('calculo_remuneraciones_result', 4);
            redirect('remuneraciones/calculo_remuneraciones');
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


    public function reversar_aprobacion_remuneraciones($idperiodo)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            set_time_limit(0);
            $this->load->model('remuneracion');
            $publicar = $this->remuneracion->reversar_aprobacion_remuneracion($idperiodo);

            $this->session->set_flashdata('calculo_remuneraciones_result', 5);
            redirect('remuneraciones/calculo_remuneraciones');
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




    public function periodos($idperiodo = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');

            $datosperiodo = $this->remuneracion->get_periodos_cerrados($this->session->userdata('comunidadid'));

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Detalle Remuneraciones'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/periodos';
            $vars['datosperiodo'] = $datosperiodo;
            $vars['idperiodo'] = $idperiodo;


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


    public function remuneraciones_personal($idperiodo = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

           // var_dump($this->session->userdata('user_id')); exit;
            $this->load->model('remuneracion');

            $userid = $this->session->userdata('user_id');
            $personal = $this->remuneracion->get_trabajador_by_userid($userid);

            if(count($personal) > 0){
                $trabajador = $personal[0];
                $idtrabajador = $trabajador->id;

              //  var_dump($idtrabajador); exit;
                $datosperiodo = $this->remuneracion->get_periodos_cerrados_personal($idtrabajador,$this->session->userdata('comunidadid'));
               // echo '<pre>';
               // var_dump($datosperiodo); exit;
            }else{
                $datosperiodo = array();
            }

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Detalle Remuneraciones'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/remuneraciones_personal';
            $vars['datosperiodo'] = $datosperiodo;
            $vars['idperiodo'] = $idperiodo;


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


    public function ver_remuneraciones_periodo($idperiodo = '')
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $remuneraciones = $this->remuneracion->get_remuneraciones_by_periodo($idperiodo,true);
           // echo "<pre>";
           // var_dump($remuneraciones); exit;
            $datosperiodo = $this->remuneracion->get_periodos($this->session->userdata('comunidadid'), $idperiodo);

            $content = array(
                'menu' => 'Ver',
                'title' => 'Ver',
                'subtitle' => 'Propiedades'
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/ver_remuneraciones_periodo';
            $vars['remuneraciones'] = $remuneraciones;
            $vars['datosperiodo'] = $datosperiodo;

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


    public function liquidacion($idremuneracion = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $remuneracion = $this->remuneracion->get_remuneraciones_by_id($idremuneracion);


            if (is_null($remuneracion)) { // SI NO ENCUENTRO NINGUNA REMUNERACION (CORRESPONDE A OTRA COMUNIDAD POR EJEMPLO)
                redirect('main/dashboard/');
            } else if (is_null($remuneracion->cierre)) {
                redirect('main/dashboard/'); // SI NO ES UN PERIODO CERRADO, SE ENVÍA AL DASHBOARD
            } else {
                $datosdetalle = $this->remuneracion->liquidacion($remuneracion);
            }

            exit;
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



    public function liquidaciones($idperiodo = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            set_time_limit(0);

            $this->load->model('remuneracion');
            $periodo = $this->remuneracion->get_periodos($this->session->userdata('comunidadid'), $idperiodo);

            if (is_null($periodo->cierre)) {
                redirect('main/dashboard/');
            } else {
                $remuneraciones = $this->remuneracion->get_remuneraciones_by_periodo($idperiodo);
                if (is_null($remuneraciones)) { // SI NO ENCUENTRO NINGUNA REMUNERACION (CORRESPONDE A OTRA COMUNIDAD POR EJEMPLO)
                    redirect('main/dashboard/');
                } else {
                    $datosdetalle = $this->remuneracion->liquidaciones($remuneraciones);
                }
            }


            exit;
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



    public function previred($idperiodo = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            set_time_limit(0);

            $this->load->model('remuneracion');
            $periodo = $this->remuneracion->get_periodos($this->session->userdata('comunidadid'), $idperiodo);

            if (is_null($periodo->cierre)) {
                redirect('main/dashboard/');
            } else {
                $remuneraciones = $this->remuneracion->get_remuneraciones_by_periodo($idperiodo, true);
                if (is_null($remuneraciones)) { // SI NO ENCUENTRO NINGUNA REMUNERACION (QUIERE DECIR QUE NO EXISTIAN TRABAJADORES EN ESE PERIODO)
                    redirect('main/dashboard/');
                } else {
                    $datosdetalle = $this->remuneracion->previred($remuneraciones);
                }
            }


            exit;
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



    public function libro($idperiodo = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            set_time_limit(0);

            $this->load->model('remuneracion');
            $periodo = $this->remuneracion->get_periodos($this->session->userdata('comunidadid'), $idperiodo);

            if (is_null($periodo->cierre)) {
                redirect('main/dashboard/');
            } else {
                $remuneraciones = $this->remuneracion->get_remuneraciones_by_periodo($idperiodo, true);
                if (is_null($remuneraciones)) { // SI NO ENCUENTRO NINGUNA REMUNERACION (QUIERE DECIR QUE NO EXISTIAN TRABAJADORES EN ESE PERIODO)
                    redirect('main/dashboard/');
                } else {
                    $datosdetalle = $this->remuneracion->libro($remuneraciones);
                }
            }


            exit;
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


    public function lre($idperiodo = null)
    {

         if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            set_time_limit(0);
            
            $this->load->model('remuneracion');
            $periodo = $this->remuneracion->get_periodos($this->session->userdata('comunidadid'), $idperiodo);

            if (is_null($periodo->cierre)) {
                redirect('main/dashboard/');
            } else {
                $remuneraciones = $this->remuneracion->get_remuneraciones_by_periodo($idperiodo, true);
                if (is_null($remuneraciones)) { // SI NO ENCUENTRO NINGUNA REMUNERACION (QUIERE DECIR QUE NO EXISTIAN TRABAJADORES EN ESE PERIODO)
                    redirect('main/dashboard/');
                } else {
                    $datosdetalle = $this->remuneracion->lre($remuneraciones,$periodo);
                }
            }


            exit;
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


    public function ver_parametros()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $parametros_generales = $this->remuneracion->get_parametros_generales();
            $tabla_impuesto = $this->remuneracion->get_tabla_impuesto();
            $tabla_asig_familiar = $this->remuneracion->get_tabla_asig_familiar();

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Par&aacutemetros'
            );



            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/ver_parametros';
            $vars['parametros_generales'] = $parametros_generales;
            $vars['tabla_impuesto'] = $tabla_impuesto;
            $vars['tabla_asig_familiar'] = $tabla_asig_familiar;

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

    public function correccion_monetaria()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('correccion_monetaria_result');
            if ($resultid == 1) {
                $vars['message'] = "Correcci&oacute;n Monetaria actualizada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $anno_guarda = $this->session->flashdata('correccion_monetaria_anno');



            $this->load->model('remuneracion');


            $anno = $anno_guarda == '' ? date('Y') - 1 : $anno_guarda;

            $tabla_correccion_monetaria = $this->remuneracion->get_tabla_correccion_monetaria($anno);

          //    echo '<pre>';
            $array_tabla_correccion_monetaria = array();
            foreach ($tabla_correccion_monetaria as $tabla) {


                $array_tabla_correccion_monetaria[$tabla->mes_orig] = $tabla->dic;


              //  var_dump($tabla);
             //   var_dump($array_tabla_correccion_monetaria);


            }

           
            //var_dump($anno);
           // var_dump($tabla_correccion_monetaria); 
           //  var_dump($array_tabla_correccion_monetaria);exit;
            $meses = array(1 => 'Enero',
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
                           12 => 'Diciembre',
                          );

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Correcci&oacute;n Monetaria'
            );


            $vars['formValidation'] = true;
            $vars['mask'] = true;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/correccion_monetaria';
            $vars['tabla_correccion_monetaria'] = $array_tabla_correccion_monetaria;
            $vars['anno'] = $anno;
            $vars['meses'] = $meses;
            $vars['maleta'] = true;

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



    public function get_correccion_monetaria()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
         
            
            $anno = $this->input->post('anno');
            $this->load->model('remuneracion');
            $tabla_correccion_monetaria = $this->remuneracion->get_tabla_correccion_monetaria($anno);

            echo json_encode($tabla_correccion_monetaria);


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


    public function submit_correccion_monetaria()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_datos = $this->input->post(NULL, true);
            $anno = $this->input->post('anno');


            $array_factores = array();
            foreach ($array_datos as $key => $dato) {
                if($key != 'anno'){

                    $array_elem = explode("_", $key);
                    $mes = $array_elem[1];
                    $array_factores[$mes] = $dato;

                }

            }

            //echo '<pre>';
            //var_dump($array_factores); exit;

            $this->load->model('remuneracion');
            $this->remuneracion->edit_tabla_correccion_monetaria($anno, $array_factores);
            $this->session->set_flashdata('correccion_monetaria_result', 1);
            $this->session->set_flashdata('correccion_monetaria_anno', $anno);
            redirect('remuneraciones/correccion_monetaria');
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




    public function impuesto_unico()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('impuesto_result');
            if ($resultid == 1) {
                $vars['message'] = "Impuesto &Uacute;nico actualizado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }

            $this->load->model('remuneracion');
            $tabla_impuesto = $this->remuneracion->get_tabla_impuesto();

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Impuesto &Uacute;nico'
            );


            $vars['formValidation'] = true;
            $vars['mask'] = true;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/impuesto_unico';
            $vars['tabla_impuesto'] = $tabla_impuesto;

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


    public function submit_impuesto_unico()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_datos = $this->input->post(NULL, true);
            $array_impuesto = array();
            foreach ($array_datos as $key => $dato) {
                $array_elem = explode("_", $key);
                $id_impuesto = $array_elem[1];
                $tipo_valor = $array_elem[0];
                $array_impuesto[$id_impuesto][$tipo_valor] = $dato;
            }

            $this->load->model('remuneracion');
            $this->remuneracion->edit_tabla_impuesto($array_impuesto);
            $this->session->set_flashdata('impuesto_result', 1);
            redirect('remuneraciones/impuesto_unico');
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


    public function asignacion_familiar()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $resultid = $this->session->flashdata('asig_familiar_result');
            if ($resultid == 1) {
                $vars['message'] = "Asignaci&oacute;n Familiar actualizada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }

            $this->load->model('remuneracion');
            $tabla_asig_familiar = $this->remuneracion->get_tabla_asig_familiar();

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Asignaci&oacute;n Familiar'
            );


            $vars['formValidation'] = true;
            $vars['mask'] = true;
            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/asignacion_familiar';
            $vars['tabla_asig_familiar'] = $tabla_asig_familiar;

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



    public function submit_asignacion_familiar()
    {


        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $array_datos = $this->input->post(NULL, true);
            $array_asig_familiar = array();
            foreach ($array_datos as $key => $dato) {
                $array_elem = explode("_", $key);
                $id_asig_familiar = $array_elem[1];
                $tipo_valor = $array_elem[0];
                $array_asig_familiar[$id_asig_familiar][$tipo_valor] = $dato;
            }

            $this->load->model('remuneracion');
            $this->remuneracion->edit_tabla_asig_familiar($array_asig_familiar);
            $this->session->set_flashdata('asig_familiar_result', 1);
            redirect('remuneraciones/asignacion_familiar');
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



    public function afp()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('afp_result');
            if ($resultid == 1) {
                $vars['message'] = "AFP Agregada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar AFP. AFP ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "AFP Editada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar AFP. AFP no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "AFP Eliminada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('remuneracion');

            $afps = $this->remuneracion->get_afp();

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Administraci&oacute;n de Afp'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/admin_afp';
            $vars['afps'] = $afps;
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


    public function add_afp($idafp = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $afp = $this->remuneracion->get_afp($idafp);

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Administraci&oacute;n de Afp'
            );

            $datos_form = array(
                'idafp' => is_null($afp) ? 0 : $afp->id,
                'nombre' => is_null($afp) ? '' : $afp->nombre,
                'porc' => is_null($afp) ? '' : $afp->porc,
                'exregimen' => is_null($afp) ? 0 : $afp->exregimen
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/add_afp';
            $vars['titulo'] = $idafp == '' ? "Agregar Afp" : "Editar Afp";
            $vars['datos_form'] = $datos_form;
            $vars['formValidation'] = true;
            $vars['mask'] = true;
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



    public function submit_afp()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $nombre = $this->input->post('nombre');
            $porc = $this->input->post('porc');
            $exregimen = $this->input->post('exregimen') == 'on' ? 1 : 0;
            $idafp = $this->input->post('idafp');

            $array_datos = array(
                'nombre' => $nombre,
                'porc' => $porc,
                'exregimen' => $exregimen,
                'idafp' => $idafp
            );


            $this->load->model('remuneracion');
            $result = $this->remuneracion->add_afp($array_datos);

            if ($result == -1) {
                $this->session->set_flashdata('afp_result', 2);
            } else {
                if ($idafp == 0) {
                    $this->session->set_flashdata('afp_result', 1);
                } else {
                    $this->session->set_flashdata('afp_result', 3);
                }
            }


            redirect('remuneraciones/afp');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function delete_afp($idafp = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $result = $this->remuneracion->delete_afp($idafp);
            if ($result == -1) {
                $this->session->set_flashdata('afp_result', 4);
            } else {
                $this->session->set_flashdata('afp_result', 5);
            }

            redirect('remuneraciones/afp');
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


    public function isapres()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('isapre_result');
            if ($resultid == 1) {
                $vars['message'] = "Isapre Agregada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Isapre. Isapre ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Isapre Editada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Isapre. Isapre no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Isapre Eliminada correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('remuneracion');

            $isapres = $this->remuneracion->get_isapre();

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Administraci&oacute;n de Isapre'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/admin_isapre';
            $vars['isapres'] = $isapres;
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


    public function feriados()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('feriado_result');
            if ($resultid == 1) {
                $vars['message'] = "Feriado Agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar Feriado. Feriado ya existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Feriado Editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar Feriado. Feriado no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Feriado Eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $this->load->model('remuneracion');

            $feriados = $this->remuneracion->get_feriado();


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Administraci&oacute;n de Feriados'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/admin_feriado';
            $vars['feriados'] = $feriados;
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


    public function add_feriado($idferiado = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $feriado = $this->remuneracion->get_feriado($idferiado);

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Administraci&oacute;n de Feriados'
            );

            $datos_form = array(
                'idferiado' => is_null($feriado) ? 0 : $feriado->id,
                'fecha' => is_null($feriado) ? date("d/m/Y") : $feriado->fecha
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/add_feriado';
            $vars['datetimepicker'] = true;

            $vars['titulo'] = $idferiado == '' ? "Agregar Feriado" : "Editar Feriado";
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


    public function submit_feriado()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $fecha = $this->input->post('fecha');
            $idferiado = $this->input->post('idferiado');

            $array_datos = array(
                'fecha' => formato_fecha($fecha, 'd/m/Y', 'Y-m-d'),
                'idferiado' => $idferiado
            );


            $this->load->model('remuneracion');
            $result = $this->remuneracion->add_feriado($array_datos);

            if ($result == -1) {
                $this->session->set_flashdata('feriado_result', 2);
            } else {
                if ($idferiado == 0) {
                    $this->session->set_flashdata('feriado_result', 1);
                } else {
                    $this->session->set_flashdata('feriado_result', 3);
                }
            }


            redirect('remuneraciones/feriados');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function delete_feriado($idferiado = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $result = $this->remuneracion->delete_feriado($idferiado);
            if ($result == -1) {
                $this->session->set_flashdata('feriado_result', 4);
            } else {
                $this->session->set_flashdata('feriado_result', 5);
            }

            redirect('remuneraciones/feriados');
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


    public function add_isapre($idisapre = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $isapre = $this->remuneracion->get_isapre($idisapre);

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Administraci&oacute;n de Isapres'
            );

            $datos_form = array(
                'idisapre' => is_null($isapre) ? 0 : $isapre->id,
                'nombre' => is_null($isapre) ? '' : $isapre->nombre
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/add_isapre';
            $vars['titulo'] = $idisapre == '' ? "Agregar Isapre" : "Editar Isapre";
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


    public function submit_isapre()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $nombre = $this->input->post('nombre');
            $idisapre = $this->input->post('idisapre');

            $array_datos = array(
                'nombre' => $nombre,
                'idisapre' => $idisapre
            );


            $this->load->model('remuneracion');
            $result = $this->remuneracion->add_isapre($array_datos);

            if ($result == -1) {
                $this->session->set_flashdata('isapre_result', 2);
            } else {
                if ($idisapre == 0) {
                    $this->session->set_flashdata('isapre_result', 1);
                } else {
                    $this->session->set_flashdata('isapre_result', 3);
                }
            }


            redirect('remuneraciones/isapres');
        } else {
            $vars['content_view'] = 'forbidden';
            $this->load->view('template', $vars);
        }
    }


    public function delete_isapre($idisapre = 0)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $result = $this->remuneracion->delete_isapre($idisapre);
            if ($result == -1) {
                $this->session->set_flashdata('isapre_result', 4);
            } else {
                $this->session->set_flashdata('isapre_result', 5);
            }

            redirect('remuneraciones/isapres');
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



    public function descuentos()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $resultid = $this->session->flashdata('descuento_result');
            if ($resultid == 1) {
                $vars['message'] = "Descuento agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Debe asociar descuento a un per&iacute;odo v&aacute;lido";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "No es posible editar/eliminar descuento.  Descuento no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Descuento editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 5) {
                $vars['message'] = "No es posible editar/eliminar descuento.  Per&iacute;odo ya est&aacute; cerrado";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 6) {
                $vars['message'] = "Descuento eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            }


            $mes = $this->session->flashdata('descuentos_mes') == '' ? date('m') : $this->session->flashdata('descuentos_mes');
            $anno = $this->session->flashdata('descuentos_anno') == '' ? date('Y') : $this->session->flashdata('descuentos_anno');

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Descuentos/Prestamos'
            );

            $vars['content_menu'] = $content;
            $vars['mes'] = $mes;
            $vars['anno'] = $anno;
            $vars['content_view'] = 'remuneraciones/descuentos';
            $vars['formValidation'] = true;
            $vars['maleta'] = true;
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



    public function add_descuento()
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {



            $mes = $this->input->post('mes');
            $anno = $this->input->post('anno');

            if (empty($mes) && empty($anno)) {
                $this->session->set_flashdata('descuento_result', 2);
                redirect('remuneraciones/descuentos');
            } else {

                $this->load->model('remuneracion');

                $tipo_descuentos = $this->remuneracion->get_tipo_descuento();
                $personal = $this->remuneracion->get_personal();


                $content = array(
                    'menu' => 'Remuneraciones',
                    'title' => 'Remuneraciones',
                    'subtitle' => 'Agregar Descuento/Prestamo'
                );


                $vars['content_menu'] = $content;
                $vars['content_view'] = 'remuneraciones/add_descuento';
                $vars['tipo_descuentos'] = $tipo_descuentos;
                $vars['personal'] = $personal;
                $vars['mes'] = $mes;
                $vars['anno'] = $anno;

                $vars['formValidation'] = true;
                $vars['mask'] = true;
                //$vars['angular'] = true;
                //$vars['angular_controller'] = 'accounts/add_cuenta.js';;


                $template = "template";


                $this->load->view($template, $vars);
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



    public function submit_descuento()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $tipo_descuento = $this->input->post('tipo_descuento');
            $trabajador = $this->input->post('trabajador');
            $monto = str_replace(".", "", $this->input->post('monto'));
            $descripcion = $this->input->post('descripcion');

            $mes = $this->input->post('mes');
            $anno = $this->input->post('anno');

            $array_datos = array(
                'tipodescuento' => $tipo_descuento,
                'idpersonal' => $trabajador,
                'monto' => $monto,
                'descripcion' => $descripcion,
                'created_at' => date("Y-m-d H:i:s")
            );


            $this->load->model('remuneracion');
            $result = $this->remuneracion->add_descuento($array_datos, $mes, $anno);

            $this->session->set_flashdata('descuento_result', 1);
            $this->session->set_flashdata('descuentos_mes', $mes);
            $this->session->set_flashdata('descuentos_anno', $anno);
            redirect('remuneraciones/descuentos');

            /*if($result == -1){
				$this->session->set_flashdata('descuento_result', 2);
			}else{
				if($idisapre == 0){
					$this->session->set_flashdata('isapre_result', 1);
				}else{
					$this->session->set_flashdata('isapre_result', 3);
				}
			}*/
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



    public function ver_descuento($iddescuento)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');
            $descuento = $this->remuneracion->get_descuentos_by_id($iddescuento);


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Ver Descuento'
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/ver_descuento';
            //$vars['existe'] = count($descuento) > 0 ? true : false;
            $vars['existe'] = !is_null($descuento) ? true : false;

            if (!$vars['existe']) {
                $vars['message'] = "Descuento no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $vars['descuento'] = $descuento;

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



    public function edit_descuento($iddescuento)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $this->load->model('remuneracion');
            $descuento = $this->remuneracion->get_descuentos_by_id($iddescuento);
            $tipo_descuentos = $this->remuneracion->get_tipo_descuento();
            $personal = $this->remuneracion->get_personal();


            $estado_periodo = $this->remuneracion->get_estado_periodo($descuento->mes, $descuento->anno);
            if ($estado_periodo == 0) {
                $this->session->set_flashdata('descuento_result', 5);
                $this->session->set_flashdata('descuentos_mes', $descuento->mes);
                $this->session->set_flashdata('descuentos_anno', $descuento->anno);
                redirect('remuneraciones/descuentos');
            }


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Editar Descuento'
            );

            $vars['content_menu'] = $content;
            $vars['content_view'] = 'remuneraciones/edit_descuento';
            if (is_null($descuento)) {
                $this->session->set_flashdata('descuento_result', 3);
                redirect('remuneraciones/descuentos');
            }

            $vars['tipo_descuentos'] = $tipo_descuentos;
            $vars['personal'] = $personal;
            $vars['descuento'] = $descuento;
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



    public function submit_edit_descuento()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $tipo_descuento = $this->input->post('tipo_descuento');
            $trabajador = $this->input->post('trabajador');
            $monto = str_replace(".", "", $this->input->post('monto'));
            $descripcion = $this->input->post('descripcion');
            $iddescuento = $this->input->post('iddescuento');

            $this->load->model('remuneracion');
            $descuento = $this->remuneracion->get_descuentos_by_id($iddescuento);

            $array_datos = array(
                'tipodescuento' => $tipo_descuento,
                'idpersonal' => $trabajador,
                'monto' => $monto,
                'descripcion' => $descripcion
            );



            $result = $this->remuneracion->edit_descuento($iddescuento, $array_datos);

            $this->session->set_flashdata('descuento_result', 4);
            $this->session->set_flashdata('descuentos_mes', $descuento->mes);
            $this->session->set_flashdata('descuentos_anno', $descuento->anno);
            redirect('remuneraciones/descuentos');
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



    public function delete_descuento($iddescuento = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            if (is_null($iddescuento)) {
                redirect('main/dashboard');
            }

            $this->load->model('remuneracion');
            $descuento = $this->remuneracion->get_descuentos_by_id($iddescuento);

            $estado_periodo = $this->remuneracion->get_estado_periodo($descuento->mes, $descuento->anno);
            if ($estado_periodo == 0) {
                $this->session->set_flashdata('descuento_result', 5);
                $this->session->set_flashdata('descuentos_mes', $descuento->mes);
                $this->session->set_flashdata('descuentos_anno', $descuento->anno);
                redirect('remuneraciones/descuentos');
            }

            if (is_null($descuento)) {
                $this->session->set_flashdata('descuento_result', 3);
                redirect('remuneraciones/descuentos');
            }

            $result = $this->remuneracion->delete_descuento($iddescuento);
            $this->session->set_flashdata('descuento_result', 6);
            redirect('remuneraciones/descuentos');
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




    public function vacaciones($tipo_colaborador = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $resultid = $this->session->flashdata('vacaciones_result');
            if ($resultid == 1) {
                $vars['message'] = "Vacaciones solicitadas correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al solicitar vacaciones.  Debe indicar trabajador";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al solicitar vacaciones.  Solicita m&aacutes de lo permitido";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error en visualizaci&oacute;n de cartola.  Debe indicar trabajador";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Error al eliminar vacaciones.  Favor intente nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al agregar dia progresivo.  Debe indicar trabajador";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 7) {
                $vars['message'] = "Error al solicitar vacaciones.  Trabajador no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 8) {
                $vars['message'] = "Error al agregar dia progresivo.  Trabajador no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 9) {
                $vars['message'] = "D&iacute;a progresivo agregado/editardo correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 10) {
                $vars['message'] = "Error al eliminar/editar d&iacute;as progresivos autorizados.  Favor intente nuevamente";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 11) {
                $vars['message'] = "Error al eliminar/editar d&iacute;as progresivos autorizados.  D&iacute;as ya fueron tomados";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $this->load->model('remuneracion');
            $consulta_colaboradores = is_null($tipo_colaborador) ? 'activos' : $tipo_colaborador;


            $personal = $this->remuneracion->get_personal(null, $consulta_colaboradores);
            //echo "<pre>";
            //print_r($personal);
            $array_progresivos = array();
            foreach ($personal as $trabajador) {
                $dias_progresivos = $this->remuneracion->get_dias_progresivos($trabajador->id);
                $num_dias_progresivos = num_dias_progresivos($trabajador->fecinicvacaciones, $trabajador->saldoinicvacprog, $dias_progresivos);
                $array_progresivos[$trabajador->id] = $num_dias_progresivos;
            }

            if ($tipo_colaborador == 'todos') {
                $title_button = 'Mostrar Todos';
                $link = 'todos';
            } else if ($tipo_colaborador == 'inactivos') {
                $title_button = 'Mostrar Inactivos';
                $link = 'inactivos';
            } else {
                $title_button = 'Mostrar Activos';
                $link = '';
            }


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Vacaciones'
            );

            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['progresivos'] = $array_progresivos;
            $vars['content_view'] = 'remuneraciones/vacaciones';
            $vars['title_button'] = $title_button;
            $vars['link'] = $link;
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


    public function solicita_vacaciones($idpersonal = '', $idcartola = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($idpersonal == '') {
                $this->session->set_flashdata('vacaciones_result', 2);
                redirect('remuneraciones/vacaciones');
            }

            $this->load->model('remuneracion');

            $personal = $this->remuneracion->get_personal($idpersonal);

            if (is_null($personal)) {
                $this->session->set_flashdata('vacaciones_result', 7);
                redirect('remuneraciones/vacaciones');
            }

            $feriados = $this->remuneracion->get_feriado();

            $dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones);
            //$saldo_vacaciones = $dias_vacaciones - $personal->diasvactomados;

            $dias_progresivos = $this->remuneracion->get_dias_progresivos($idpersonal);
            $num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);
            $saldo_vacaciones = $dias_vacaciones + $num_dias_progresivos - $personal->diasvactomados;

            $vars['num_dias_progresivos'] = $num_dias_progresivos;

            if (!is_null($idcartola)) {
                $cartola = $this->remuneracion->get_cartola_vacaciones($idpersonal, $idcartola);

                if (is_null($cartola)) {
                    $this->session->set_flashdata('vacaciones_result', 2);
                    redirect('remuneraciones/cartola_vacaciones');
                } else {

                    $vars['fechadesde'] = $cartola->fecinicio;
                    $vars['fechahasta'] = $cartola->fecfin;
                    $vars['diassolicita'] = $cartola->dias;
                    $vars['comentario'] = $cartola->comentarios;
                    $vars['titulo'] = "Editar Solicitud";
                    $vars['max_vacaciones'] = $saldo_vacaciones + $cartola->dias;
                }
            } else {
                $vars['fechadesde'] = date("Y-m-d", strtotime("+ 1 day"));
                $vars['fechahasta'] = date("Y-m-d", strtotime("+ 1 day"));
                $vars['diassolicita'] = 0;
                $vars['comentario'] = "";
                $vars['titulo'] = "Solicitar";
                $vars['max_vacaciones'] = $saldo_vacaciones;
            }


            $array_feriados = array();
            foreach ($feriados as $feriado) {
                array_push($array_feriados, $feriado->fecha_sformat);
            }

            $string_feriados = "'" . implode("','", $array_feriados) . "'";

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Solicita Vacaciones'
            );





            //$saldo_vacaciones = 0;
            $vars['classinfo'] = $saldo_vacaciones <= 0 ? 'danger' : 'success';
            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['dias_vacaciones'] = $dias_vacaciones;
            $vars['saldo_vacaciones'] = $saldo_vacaciones;
            $vars['string_feriados'] = $string_feriados;
            $vars['idcartola'] = is_null($idcartola) ? 0 : $idcartola;
            $vars['content_view'] = 'remuneraciones/solicita_vacaciones';
            $vars['formValidation'] = true;
            $vars['daterangepicker2'] = true;
            //$vars['confirmation'] = true;
            //$vars['moment'] = true;

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


    public function submit_solicita_vacaciones()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $diassolicita = $this->input->post('diassolicita');
            $comentarios = $this->input->post('comentarios');
            $idpersonal = $this->input->post('idpersonal');
            $fechadesde = $this->input->post('fechadesde');
            $fechahasta = $this->input->post('fechahasta');
            $idcartola = $this->input->post('idcartola');

            $array_datos = array(
                'idpersonal' => $idpersonal,
                'fecinicio' => $fechadesde,
                'fecfin' => $fechahasta,
                'dias' => $diassolicita,
                'comentarios' => $comentarios,
                'idcartola' => $idcartola,
                'created_at' => date("Y-m-d H:i:s")
            );


            $this->load->model('remuneracion');
            $result = $this->remuneracion->solicita_vacaciones($array_datos);

            /*
			$this->session->set_flashdata('descuentos_mes', $mes);
			$this->session->set_flashdata('descuentos_anno', $anno);*/

            if ($result) {
                $this->session->set_flashdata('vacaciones_result', 1);
            } else {
                $this->session->set_flashdata('vacaciones_result', 3);
            }

            redirect('remuneraciones/vacaciones');
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



public function cartola_vacaciones_personal()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('cartola_vacaciones_result');
            if ($resultid == 1) {
                $vars['message'] = "Solicitud de Vacaciones eliminadas correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al eliminar vacaciones.  Solicitud no existe o no corresponde";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al editar vacaciones.  Solicitud no existe o no corresponde";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "D&iacute;as progresivos eliminados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 5) {
                $vars['message'] = "Error al eliminar d&iacute;as progresivos.  Cartola no existe no existe o no corresponde";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al eliminar d&iacute;as progresivos.  D&iacute;as ya fueron tomados";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            $this->load->model('remuneracion');

            $userid = $this->session->userdata('user_id');
            $personal_trabajo = $this->remuneracion->get_trabajador_by_userid($userid);



            if(!is_null($personal_trabajo)){
                $trabajador = $personal_trabajo[0];
                $idpersonal = $trabajador->id;



                            
                $personal = $this->remuneracion->get_personal($idpersonal, 'todos');
                $cartola = $this->remuneracion->get_cartola_vacaciones($idpersonal);
                $dias_progresivos = $this->remuneracion->get_dias_progresivos($idpersonal);

                //echo "<pre>";
                //print_r($personal); exit;
                $content = array(
                    'menu' => 'Remuneraciones',
                    'title' => 'Remuneraciones',
                    'subtitle' => 'Cartola Vacaciones'
                );

                $dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones);

                $cartola_progresivos = cartola_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);



                $cartola_devengada = cartola_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones, $cartola_progresivos);

                $num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);

                $saldo_vacaciones = $dias_vacaciones - $personal->diasvactomados;

            }else{
                $cartola = array();
                $dias_vacaciones = 0;
                $num_dias_progresivos = 0;
                $dias_progresivos = 0;
                $saldo_vacaciones = 0;
                $vars['oculta'] = true;
            }

            
            //$saldo_vacaciones = 0;
            $vars['classinfo'] = $saldo_vacaciones <= 0 ? 'danger' : 'success';
            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['cartola'] = $cartola;
            $vars['cartola_devengada'] = $cartola_devengada;
            $vars['dias_vacaciones'] = $dias_vacaciones;
            $vars['num_dias_progresivos'] = $num_dias_progresivos;

            $vars['cartola_dias_progresivos'] = $dias_progresivos;
            $vars['saldo_vacaciones'] = $saldo_vacaciones;
            $vars['content_view'] = 'remuneraciones/cartola_vacaciones';
            $vars['formValidation'] = true;
            $vars['link'] = '';
            //$vars['moment'] = true;

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


    public function cartola_vacaciones($idpersonal = '', $link = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('cartola_vacaciones_result');
            if ($resultid == 1) {
                $vars['message'] = "Solicitud de Vacaciones eliminadas correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al eliminar vacaciones.  Solicitud no existe o no corresponde";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al editar vacaciones.  Solicitud no existe o no corresponde";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "D&iacute;as progresivos eliminados correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 5) {
                $vars['message'] = "Error al eliminar d&iacute;as progresivos.  Cartola no existe no existe o no corresponde";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al eliminar d&iacute;as progresivos.  D&iacute;as ya fueron tomados";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            if ($idpersonal == '') {
                $this->session->set_flashdata('vacaciones_result', 4);
                redirect('remuneraciones/vacaciones/' . $link);
            }

            $this->load->model('remuneracion');
            $personal = $this->remuneracion->get_personal($idpersonal, 'todos');
            $cartola = $this->remuneracion->get_cartola_vacaciones($idpersonal);
            $dias_progresivos = $this->remuneracion->get_dias_progresivos($idpersonal);

            //echo "<pre>";
            //print_r($personal); exit;
            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Cartola Vacaciones'
            );

            $dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones);

            $cartola_progresivos = cartola_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);



            $cartola_devengada = cartola_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones, $cartola_progresivos);

            $num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);

            $saldo_vacaciones = $dias_vacaciones - $personal->diasvactomados;
            //$saldo_vacaciones = 0;
            $vars['classinfo'] = $saldo_vacaciones <= 0 ? 'danger' : 'success';
            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['cartola'] = $cartola;
            $vars['cartola_devengada'] = $cartola_devengada;
            $vars['dias_vacaciones'] = $dias_vacaciones;
            $vars['num_dias_progresivos'] = $num_dias_progresivos;

            $vars['cartola_dias_progresivos'] = $dias_progresivos;
            $vars['saldo_vacaciones'] = $saldo_vacaciones;
            $vars['link'] = $link;
            $vars['content_view'] = 'remuneraciones/cartola_vacaciones';
            $vars['formValidation'] = true;
            //$vars['moment'] = true;

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


    public function delete_vacaciones($idpersonal = '', $idcartola = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {



            if ($idpersonal == '' || $idcartola == '') {
                $this->session->set_flashdata('vacaciones_result', 5);
                redirect('remuneraciones/vacaciones');
            }

            $this->load->model('remuneracion');
            $result = $this->remuneracion->delete_vacaciones($idpersonal, $idcartola);


            if ($result) {
                $this->session->set_flashdata('cartola_vacaciones_result', 1);
            } else {
                $this->session->set_flashdata('cartola_vacaciones_result', 2);
            }
            redirect('remuneraciones/cartola_vacaciones/' . $idpersonal);
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


    public function delete_dias_progresivos($idpersonal = '', $idcartola = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {



            if ($idpersonal == '' || $idcartola == '') {
                $this->session->set_flashdata('vacaciones_result', 10);
                redirect('remuneraciones/vacaciones');
            }

            $this->load->model('remuneracion');
            $result = $this->remuneracion->delete_dias_progresivos($idpersonal, $idcartola);


            if ($result == 1) {
                $this->session->set_flashdata('cartola_vacaciones_result', 4);
            } else if ($result == 2) {
                $this->session->set_flashdata('cartola_vacaciones_result', 5);
            } else if ($result == 3) {
                $this->session->set_flashdata('cartola_vacaciones_result', 6);
            }
            redirect('remuneraciones/cartola_vacaciones/' . $idpersonal);
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

    /*public function add_dia_progresivo($idpersonal = '')
	{
		if($this->ion_auth->is_allowed($this->router->fetch_class(),$this->router->fetch_method())){



			if($idpersonal == ''){
				$this->session->set_flashdata('vacaciones_result', 6);
				redirect('remuneraciones/vacaciones');
			}

			$this->load->model('remuneracion');

			$personal = $this->remuneracion->get_personal($idpersonal);

			if(is_null($personal)){
				$this->session->set_flashdata('vacaciones_result', 8);
				redirect('remuneraciones/vacaciones');
			}

			$dia_calculo = is_null($personal->fecultdiaprogresivo) ? $personal->fecingreso_sformat : $personal->fecultdiaprogresivo;

            $dia_progresivo = dia_progresivo($dia_calculo);


			$dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones,$personal->saldoinicvacaciones);
			$saldo_vacaciones = $dias_vacaciones - $personal->diasvactomados;

			$vars['dia_progresivo'] = $dia_progresivo;


			$dias_progresivos = $this->remuneracion->get_dias_progresivos($idpersonal);
			$num_dias_progresivos = num_dias_progresivos($personal->fecingreso_sformat,$dias_progresivos);


			$vars['num_dias_progresivos'] = $num_dias_progresivos;



			$content = array(
						'menu' => 'Remuneraciones',
						'title' => 'Remuneraciones',
						'subtitle' => 'Agregar d&iacute;a progresivo');






			$vars['classinfo'] = $saldo_vacaciones <= 0 ? 'danger' : 'success';
			$vars['content_menu'] = $content;
			$vars['personal'] = $personal;
			$vars['content_view'] = 'remuneraciones/add_dia_progresivo';

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

	}	*/



    public function add_dia_progresivo($idpersonal = '', $idcartola = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {



            if ($idpersonal == '') {
                $this->session->set_flashdata('vacaciones_result', 6);
                redirect('remuneraciones/vacaciones');
            }

            $this->load->model('remuneracion');

            $personal = $this->remuneracion->get_personal($idpersonal);


            if (is_null($personal)) {
                $this->session->set_flashdata('vacaciones_result', 8);
                redirect('remuneraciones/vacaciones');
            }

            $periodos = get_periodos_vacaciones($personal->fecinicvacaciones);


            $dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones);
            $saldo_vacaciones = $dias_vacaciones - $personal->diasvactomados;

            //$vars['dia_progresivo'] = $dia_progresivo;


            $dias_progresivos = $this->remuneracion->get_dias_progresivos($idpersonal);
            $num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);



            if (!is_null($idcartola)) {
                $dia_prog_selec = $this->remuneracion->get_dias_progresivos($idpersonal, $idcartola);
                $titulo_guardar = "Editar";
                $url_back = base_url() . "remuneraciones/cartola_vacaciones/" . $idpersonal;
            } else {
                $idcartola = 0;
                $dia_prog_selec = array();
                $titulo_guardar = "Agregar";
                $url_back = base_url() . "remuneraciones/vacaciones";
            }

            $vars['num_dias_progresivos'] = $num_dias_progresivos;



            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Agregar d&iacute;a progresivo'
            );






            $vars['classinfo'] = $saldo_vacaciones <= 0 ? 'danger' : 'success';
            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['periodos'] = $periodos;
            $vars['idcartola'] = $idcartola;
            $vars['dias_progresivos'] = $dias_progresivos;

            $vars['dia_prog_selec'] = $dia_prog_selec;
            $vars['titulo_guardar'] = $titulo_guardar;
            $vars['url_back'] = $url_back;
            $vars['formValidation'] = true;
            $vars['content_view'] = 'remuneraciones/add_dia_progresivo';

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

    public function submit_dia_progresivo()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $idpersonal = $this->input->post('idpersonal');
            $periodo = $this->input->post('periodo');
            $diassolicita = $this->input->post('diassolicita');
            $idcartola = $this->input->post('idcartola');


            $array_datos = array(
                'idpersonal' => $idpersonal,
                'periodo' => $periodo,
                'dias' => $diassolicita,
                'idcartola' => $idcartola,
                'created_at' => date("Y-m-d H:i:s")
            );


            $this->load->model('remuneracion');
            $result = $this->remuneracion->add_dia_progresivo($array_datos);


            if ($result == 1) {
                $this->session->set_flashdata('vacaciones_result', 9);
            } else if ($result == 2) {
                $this->session->set_flashdata('vacaciones_result', 10);
            } else if ($result == 3) {
                $this->session->set_flashdata('vacaciones_result', 11);
            }

            redirect('remuneraciones/vacaciones');
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


    public function movimientos_personal($resultid = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {


            $resultid = $this->session->flashdata('movimientos_personal_result');
            if ($resultid == 1) {
                $vars['message'] = "Movimiento agregado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al agregar movimiento.  Trabajador no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al ver movimientos.  Trabajador no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Error al eliminar movimiento.  Movimiento no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 5) {
                $vars['message'] = "Error al agregar/editar movimiento.  Per&iacute;odo asociado ya se encuentra cerrado";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al agregar/editar movimiento.  Fechas del movimiento deben corresponder al mismo per&iacute;odo";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 7) {
                $vars['message'] = "Error al agregar movimiento.  Debe indicar per&iacute;odo";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }


            $this->load->model('remuneracion');
            $personal = $this->remuneracion->get_personal();



            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Movimientos del Personal'
            );

            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['content_view'] = 'remuneraciones/movimientos_personal';

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


    public function add_movimiento_personal($idpersonal = null, $idmovimiento = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            if (is_null($idpersonal)) {
                $this->session->set_flashdata('movimientos_personal_result', 2);
                redirect('remuneraciones/movimientos_personal');
            }
            $this->load->model('remuneracion');

            $personal = $this->remuneracion->get_personal($idpersonal);

            if (is_null($personal)) {
                $this->session->set_flashdata('movimientos_personal_result', 2);
                redirect('remuneraciones/movimientos_personal');
            }





            $movimientos = $this->remuneracion->get_movimiento();
            if (!is_null($idmovimiento)) {
                $movimiento_realizado = $this->remuneracion->get_lista_movimientos($idpersonal, $idmovimiento);

                if (is_null($movimiento_realizado)) {
                    $this->session->set_flashdata('ver_movimientos_personal_result', 2);
                    redirect('remuneraciones/ver_movimiento_personal/' . $idpersonal);
                }
                $url_back = "remuneraciones/ver_movimiento_personal/" . $idpersonal;
                $vars['fechadesde'] = $movimiento_realizado->fecmovimiento;
                $vars['fechahasta'] = $movimiento_realizado->fechastamovimiento;

                $mes = substr($movimiento_realizado->fecmovimiento, 5, 2);
                $anno = substr($movimiento_realizado->fecmovimiento, 0, 4);
                $vars['minDate'] = "01/" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "/" . $anno;
                $vars['maxDate'] = ultimo_dia_mes($mes, $anno) . "/" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "/" . $anno;
            } else {

                $mes = $this->input->post('mes');
                $anno = $this->input->post('anno');
                if (empty($mes) || empty($anno)) {
                    $this->session->set_flashdata('movimientos_personal_result', 7);
                    redirect('remuneraciones/movimientos_personal');
                }



                $movimiento_realizado = array();
                $url_back = "remuneraciones/ver_movimiento_personal/" . $idpersonal;
                $vars['fechadesde'] = date("Y-m-d");
                $vars['fechahasta'] = date("Y-m-d");
                $vars['minDate'] = "01/" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "/" . $anno;
                $vars['maxDate'] = ultimo_dia_mes($mes, $anno) . "/" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "/" . $anno;
            }


            $vars['mes'] = $mes;
            $vars['anno'] = $anno;

            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Agregar movimiento del Personal'
            );





            //$saldo_vacaciones = 0;
            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['movimientos'] = $movimientos;
            $vars['movimiento_realizado'] = $movimiento_realizado;
            $vars['url_back'] = $url_back;
            $vars['content_view'] = 'remuneraciones/add_movimiento_personal';
            $vars['formValidation'] = true;
            $vars['datetimepicker'] = true;
            $vars['daterangepicker2'] = true;

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


    public function submit_movimiento_personal()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {
            $idpersonal = $this->input->post('idpersonal');
            $comentarios = $this->input->post('comentarios');
            $movimientos = $this->input->post('movimientos');
            $idmovimiento = $this->input->post('idmovimiento');

            //print_r($this->input->post(NULL,true)); exit;

            $array_datos = array(
                'idpersonal' => $idpersonal,
                'idmovimiento' => $idmovimiento,
                'idpersonal' => $idpersonal,
                'movimientos' => $movimientos,
                'comentarios' => $comentarios,
                'fecmovimiento' => $this->input->post("fechadesde"),
                'fechastamovimiento' => $this->input->post("fechahasta"),
                'created_at' => date("Y-m-d H:i:s")
            );


            $this->load->model('remuneracion');
            $result = $this->remuneracion->add_movimiento_personal($array_datos);


            if ($result == 1) {
                $this->session->set_flashdata('ver_movimientos_personal_result', 4);
                #$this->session->set_flashdata('movimientos_personal_result', 1);
                #redirect('remuneraciones/movimientos_personal');
                redirect('remuneraciones/ver_movimiento_personal/' . $idpersonal);
            } else if ($result == 2) {
                $this->session->set_flashdata('ver_movimientos_personal_result', 4);
                redirect('remuneraciones/ver_movimiento_personal/' . $idpersonal);
            } else if ($result == 3) {
                $this->session->set_flashdata('ver_movimientos_personal_result', 2);
                redirect('remuneraciones/ver_movimiento_personal/' . $idpersonal);
            } else if ($result == 4) {
                $this->session->set_flashdata('ver_movimientos_personal_result', 3);
                redirect('remuneraciones/ver_movimiento_personal/' . $idpersonal);
            } else if ($result == 5) {
                #$this->session->set_flashdata('movimientos_personal_result', 5);
                #redirect('remuneraciones/movimientos_personal');
                $this->session->set_flashdata('ver_movimientos_personal_result', 5);
                redirect('remuneraciones/ver_movimiento_personal/' . $idpersonal);
            } else if ($result == 6) {
                #$this->session->set_flashdata('movimientos_personal_result', 6);
                #redirect('remuneraciones/movimientos_personal');
                $this->session->set_flashdata('ver_movimientos_personal_result', 6);
                redirect('remuneraciones/ver_movimiento_personal/' . $idpersonal);
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



    public function ver_movimiento_personal($idpersonal = null)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $resultid = $this->session->flashdata('ver_movimientos_personal_result');
            if ($resultid == 1) {
                $vars['message'] = "Movimiento eliminado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 2) {
                $vars['message'] = "Error al eliminar/editar movimiento.  Movimiento no existe";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 3) {
                $vars['message'] = "Error al eliminar/editar movimiento.  Per&iacute;odo asociado ya se encuentra cerrado";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 4) {
                $vars['message'] = "Movimiento agregado/editado correctamente";
                $vars['classmessage'] = 'success';
                $vars['icon'] = 'fa-check';
            } elseif ($resultid == 5) {
                $vars['message'] = "Error al agregar/editar movimiento.  Per&iacute;odo asociado ya se encuentra cerrado";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            } elseif ($resultid == 6) {
                $vars['message'] = "Error al agregar/editar movimiento.  Fechas del movimiento deben corresponder al mismo per&iacute;odo";
                $vars['classmessage'] = 'danger';
                $vars['icon'] = 'fa-ban';
            }

            if (is_null($idpersonal)) {
                $this->session->set_flashdata('movimientos_personal_result', 3);
                redirect('remuneraciones/movimientos_personal');
            }

            $this->load->model('remuneracion');

            $personal = $this->remuneracion->get_personal($idpersonal);

            if (is_null($personal)) {
                $this->session->set_flashdata('movimientos_personal_result', 3);
                redirect('remuneraciones/movimientos_personal');
            }

            $movimientos = $this->remuneracion->get_lista_movimientos($idpersonal);


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Agregar movimiento del Personal'
            );




            $mes = $this->session->flashdata('descuentos_mes') == '' ? date('m') : $this->session->flashdata('descuentos_mes');
            $anno = $this->session->flashdata('descuentos_anno') == '' ? date('Y') : $this->session->flashdata('descuentos_anno');

            $vars['mes'] = $mes;
            $vars['anno'] = $anno;

            //$saldo_vacaciones = 0;
            $vars['content_menu'] = $content;
            $vars['personal'] = $personal;
            $vars['movimientos'] = $movimientos;
            $vars['content_view'] = 'remuneraciones/ver_movimiento_personal';
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


    public function delete_movimiento_personal($idpersonal = '', $idmovimiento = '')
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {



            if ($idpersonal == '' || $idmovimiento == '') {
                $this->session->set_flashdata('movimientos_personal_result', 4);
                redirect('remuneraciones/movimientos_personal');
            }

            $this->load->model('remuneracion');
            $result = $this->remuneracion->delete_movimiento_personal($idpersonal, $idmovimiento);


            if ($result == 1) {
                $this->session->set_flashdata('ver_movimientos_personal_result', 1);
            } else if ($result == 2) {
                $this->session->set_flashdata('ver_movimientos_personal_result', 2);
            } else if ($result == 3) {
                $this->session->set_flashdata('ver_movimientos_personal_result', 3);
            }
            redirect('remuneraciones/ver_movimiento_personal/' . $idpersonal);
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



    public function decjurada_honorarios()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if ($this->input->post('anno') != '') {
                $anno = $this->input->post('anno');
                $this->load->model('remuneracion');
                $descjurada_data = $this->remuneracion->get_decjurada_honorarios($anno);
                $vars['descjurada_data'] = $descjurada_data;
            } else {
                $anno = date('Y');
            }


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Declaraci&oacute;n Jurada Honorarios'
            );

            $vars['content_menu'] = $content;

            $vars['anno'] = $anno;
            $vars['content_view'] = 'remuneraciones/decjurada_honorarios';
            $vars['formValidation'] = true;
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


    public function decjurada_rentas()
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $encabezado = array();
            if ($this->input->post('anno') != '') {
                $anno = $this->input->post('anno');
                $this->load->model('remuneracion');

                $descjurada_data = $this->remuneracion->calculo_declaracion_jurada($anno);
                $encabezado = $this->remuneracion->get_decjurada_rentas_encabezado($anno);
                //$descjurada_data = $this->remuneracion->archivo_decjurada_rentas($anno);
                
            } else {
                $anno = date('Y') - 1 ;
            }


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Declaraci&oacute;n Jurada Rentas	'
            );

            $vars['content_menu'] = $content;

            $vars['anno'] = $anno;
            $vars['encabezado'] = $encabezado;
            $vars['content_view'] = 'remuneraciones/decjurada_rentas';
            $vars['formValidation'] = true;
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


    public function decjurada_rentas_exportar($anno)
    {
        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            $this->load->model('remuneracion');

            $descjurada_data = $this->remuneracion->archivo_decjurada_rentas($anno);


            $content = array(
                'menu' => 'Remuneraciones',
                'title' => 'Remuneraciones',
                'subtitle' => 'Declaraci&oacute;n Jurada Rentas '
            );

            $vars['content_menu'] = $content;

            $vars['anno'] = $anno;
            $vars['encabezado'] = $encabezado;
            $vars['content_view'] = 'remuneraciones/decjurada_rentas';
            $vars['formValidation'] = true;
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


    public function comprobante_solicitud($idpersonal = null, $idcartola = null)
    {

        if ($this->ion_auth->is_allowed($this->router->fetch_class(), $this->router->fetch_method())) {

            if (is_null($idpersonal) || is_null($idcartola)) {
                redirect('main/dashboard/');
            }

            $this->load->model('remuneracion');
            $cartola = $this->remuneracion->get_cartola_vacaciones($idpersonal, $idcartola);
            //print_r($cartola); exit;


            if (is_null($cartola)) { // SI NO ENCUENTRO NINGUNA CARTOLA (CORRESPONDE A OTRA COMUNIDAD POR EJEMPLO)
                redirect('main/dashboard/');
            } else {
                $datosdetalle = $this->remuneracion->comprobante_solicitud($idpersonal, $idcartola);
                //print_r($datosdetalle); exit;
            }

            exit;
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
