<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
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

        if(MANTENCION){
            //echo 'asdasda'; exit;

            redirect('main/dashboard');            

        }else{

            $this->load->model('ion_auth_model');
            redirect('main/dashboard');


        }



    }



    public function mantencion()
    {

        $content = array(
                'menu' => 'Error 403',
                'title' => 'Error 403',
                'subtitle' => '403 error'
            );


            $vars['content_menu'] = $content;
            $vars['content_view'] = 'mantencion';
            $this->load->view('template_guest', $vars);
    }



    //TODOS TIENEN ACCESO AL DASHBOARD
    public function dashboard($unidad_id = '')
    {
        $this->load->model('ion_auth_model');
        $this->load->model('admin');

        $content = array(
            'menu' => 'Dashboard',
            'title' => 'Dashboard',
            'subtitle' => 'Panel de Control'
        );



        $vars['content_menu'] = $content;
        $vars['content_view'] = 'dashboard';
        $vars['content_menu'] = $content;


        //corrige saldos positivos
        $this->load->model('admin');
        $result = $this->admin->asocia_saldos_positivos();



        $template = "template";
        $this->session->set_userdata('diasvencsuscripcion', 0);


        if ($this->session->userdata('level') == 4) { // SI ES ADM DE SISTEMA, PASA DIRECTO

            $vars['highchartsGraph'] = true;


            $vars['content_view'] = 'dashboard3';

            $this->session->set_userdata('comunidadid', '');
            $this->session->set_userdata('comunidadnombre', '');
            $comunidades = $this->admin->get_comunidades();
            $propiedades = $this->admin->get_propiedades();

            /****** GRAFICO COMUNIDADES ACTIVAS **********/
            $this->load->model('report');
            $datos_comunidades_activas = $this->report->gc_comunidades_activas();
            $array_meses = array();
            $array_cantidad = array();

            foreach ($datos_comunidades_activas as $dato_mensual) {
                array_push($array_meses, date2string($dato_mensual['mes'], $dato_mensual['anno']));
                array_push($array_cantidad, (int)$dato_mensual['numcomunidad']);
            }

            $listado_meses = implode("','", $array_meses);
            $listado_cantidad = implode(",", $array_cantidad);

            $vars['listado_meses'] = $listado_meses;
            $vars['listado_cantidad'] = $listado_cantidad;

            /*********************************************/

            $datos_dashboard = array(
                'numcomunidades' => count($comunidades),
                'numpropiedades' => count($propiedades)
            );
            $vars['datos_dashboard'] = $datos_dashboard;
        } else if ($this->session->userdata('level') == 1) { // ADM COMUNIDAD
            $vars['highchartsGraph'] = true;

            // SI YA SELECCIONO COMUNIDAD, NO ES NECESARIO ELEGIR NUEVAMENTE
            $unidad_id = $unidad_id == '' && $this->session->userdata('comunidadid') ? $this->session->userdata('comunidadid') : $unidad_id;

            $comunidades_asignadas = $unidad_id != '' ? $this->admin->comunidades_asignadas($this->session->userdata('user_id'), $this->session->userdata('level'), $unidad_id) : $this->admin->comunidades_asignadas($this->session->userdata('user_id'), $this->session->userdata('level'));
            $comunidades_asociadas = $this->admin->comunidades_asignadas($this->session->userdata('user_id'), $this->session->userdata('level'));

            if(is_array($comunidades_asociadas)){

                $num_comunidades = count($comunidades_asociadas);                
            }else if(isset($comunidades_asociadas->id)){

                $num_comunidades = 1;
            }else{
                $num_comunidades = 0;
            }




            if (is_array($comunidades_asignadas) && $num_comunidades > 0) { // EN CASO DE TENER MÁS DE UNA COMUNIDAD LO ENVÍA A LA PÁGINA DE SELECCIÓN
                $content = array(
                    'menu' => 'Selecci&oacute;n Comunidad',
                    'title' => 'Comunidades',
                    'subtitle' => 'Selecci&oacute;n de Comunidad'
                );

                $vars['content_menu'] = $content;
                $vars['comunidades'] = $comunidades_asignadas;
                $vars['content_view'] = 'admin/asigna_comunidad';
                $template = "template_lock";
                //$this->load->view('template_lock',$vars);
            //} else if (count(get_object_vars($comunidades_asignadas)) == 1) { // SE ASOCIA COMUNIDAD
            } else if (isset($comunidades_asignadas->id)  && $num_comunidades > 0) { // SE ASOCIA COMUNIDAD
                $this->session->set_userdata('comunidadid', $comunidades_asignadas->id);
                $this->session->set_userdata('comunidadnombre', $comunidades_asignadas->nombre);
                $this->session->set_userdata('diasvencsuscripcion', $comunidades_asignadas->vencsuscripcion);

                /****** GRAFICO CONSOLIDADO **********/
                $this->load->model('report');
                $datos_consolidado_mensual = $this->report->consolidado_mensual_adm($this->session->userdata('comunidadid'));
                $array_meses = array();
                $array_deuda = array();
                $array_reserva = array();
                $array_individual = array();
                foreach ($datos_consolidado_mensual as $dato_mensual) {
                    array_push($array_meses, date2string($dato_mensual['mes'], $dato_mensual['anno']));
                    array_push($array_deuda, (int)$dato_mensual['deuda']);
                    array_push($array_reserva, (int)$dato_mensual['reserva']);
                    array_push($array_individual, (int)$dato_mensual['individual']);
                }

                $listado_meses = implode("','", $array_meses);
                $listado_deuda = implode(",", $array_deuda);
                $listado_reserva = implode(",", $array_reserva);
                $listado_individual = implode(",", $array_individual);

                $vars['listado_meses'] = $listado_meses;
                $vars['listado_deuda'] = $listado_deuda;
                $vars['listado_reserva'] = $listado_reserva;
                $vars['listado_individual'] = $listado_individual;

                /*********************************************/




                $propiedades = $this->admin->get_propiedades_comunidad();
                $this->load->model('payment');
                $this->load->model('account');
                $deuda_sin_cancelar = $this->payment->get_deuda_sin_cancelar_by_comunidad();
                $ranking_morosos = $this->report->get_ranking_morosos();
                $medios_pago = $this->report->get_resumen_medios_pago();


                $datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));

                $deuda_total = $deuda_sin_cancelar->deuda == 0 ? 1 : $deuda_sin_cancelar->deuda;


                $datos_dashboard = array(
                    'numpropiedades' => count($propiedades),
                    'deuda_sin_cancelar' => $deuda_sin_cancelar->saldo,
                    'porc_sin_cancelar' => round(($deuda_sin_cancelar->saldo / $deuda_total) * 100, 2),
                    'deudatotal' => $this->account->get_saldo_cuentas_impagas_by_id()->saldo,
                    'caja' => $datoscomunidad->caja,
                    'fondo_reserva' => $datoscomunidad->fondoreserva,
                    'ranking_morosos' => $ranking_morosos,
                    'medios_pago' => $medios_pago,
                    'saldo_disponible' => $this->payment->get_saldo_disponible_by_comunidad($this->session->userdata('comunidadid')),
                    'saldo_contable' => $this->payment->get_saldo_contable_by_comunidad($this->session->userdata('comunidadid'))
                );
                $vars['datos_dashboard'] = $datos_dashboard;
                $vars['datos_consolidado_mensual'] = $datos_consolidado_mensual;


                $vars['num_comunidades'] = $num_comunidades;

                if (!$this->session->userdata('preloader')) {
                    $this->session->set_userdata('preloader', true);
                    $vars['fakeLoader'] = true;
                }
            } else { // BOTAR
                redirect('auth/logout');
            }
        } else if ($this->session->userdata('level') == 3 || $this->session->userdata('level') == 2) { //PROPIETARIO O COMITE
            $vars['highchartsGraph'] = true;
            // SI YA SELECCIONO COMUNIDAD, NO ES NECESARIO ELEGIR NUEVAMENTE
            $unidad_id = $unidad_id == '' && $this->session->userdata('propiedadid') ? $this->session->userdata('propiedadid') : $unidad_id;


            $propiedades_asignadas = $unidad_id != '' ? $this->admin->propiedades_asignadas($this->session->userdata('user_id'), $unidad_id) : $this->admin->propiedades_asignadas($this->session->userdata('user_id'));
            $num_propiedades = count($this->admin->propiedades_asignadas($this->session->userdata('user_id')));
           // if (count($propiedades_asignadas) > 1) { // EN CASO DE TENER MÁS DE UNA COMUNIDAD LO ENVÍA A LA PÁGINA DE SELECCIÓN
             if (is_array($propiedades_asignadas)) {
                $content = array(
                    'menu' => 'Selecci&oacute;n Comunidad',
                    'title' => 'Comunidades',
                    'subtitle' => 'Selecci&oacute;n de Comunidad'
                );

                $vars['content_menu'] = $content;
                $vars['propiedades'] = $propiedades_asignadas;
                $vars['content_view'] = 'admin/asigna_propiedad';
                $template = "template_lock";
                //$this->load->view('template_lock',$vars);
            //} else if (count($propiedades_asignadas) == 1) {
             } else if (isset($propiedades_asignadas->id)) { // SE ASOCIA COMUNIDAD

                $this->session->set_userdata('comunidadid', $propiedades_asignadas->idcomunidad);
                $this->session->set_userdata('comunidadnombre', $propiedades_asignadas->nombrecomunidad);
                $this->session->set_userdata('propiedadid', $propiedades_asignadas->id);
                $this->session->set_userdata('comunidadnumero', $propiedades_asignadas->numero);


                $propiedades = $this->admin->get_propiedades_comunidad();
                $datos_propiedad = $this->admin->get_propiedad_by_id($propiedades_asignadas->id);
                $datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));



                /***** GRAFICO PROPIEDAD *******/

                $this->load->model('report');
                $datos_gc_propiedad = $this->report->gc_mensual_prop($this->session->userdata('comunidadid'), $this->session->userdata('propiedadid'));
                $array_meses = array();
                $array_deuda_prop = array();
                $array_abonos = array();
                foreach ($datos_gc_propiedad as $dato_mensual) {
                    array_push($array_meses, date2string($dato_mensual['mes'], $dato_mensual['anno']));
                    array_push($array_deuda_prop, (int)$dato_mensual['monto']);
                    array_push($array_abonos, (int)$dato_mensual['abonado']);
                }

                $listado_meses = implode("','", $array_meses);
                $listado_deuda_prop = implode(",", $array_deuda_prop);
                $listado_abonos = implode(",", $array_abonos);
                $vars['listado_meses'] = $listado_meses;
                $vars['listado_deuda_prop'] = $listado_deuda_prop;
                $vars['listado_abonos'] = $listado_abonos;



                /***********************************/

                /****** GRAFICO CONSOLIDADO **********/
                $datos_consolidado_mensual = $this->report->consolidado_mensual_adm($this->session->userdata('comunidadid'));
                $array_meses = array();
                $array_deuda = array();
                $array_reserva = array();
                $array_individual = array();
                foreach ($datos_consolidado_mensual as $dato_mensual) {
                    array_push($array_meses, date2string($dato_mensual['mes'], $dato_mensual['anno']));
                    array_push($array_deuda, (int)$dato_mensual['deuda']);
                    array_push($array_reserva, (int)$dato_mensual['reserva']);
                    array_push($array_individual, (int)$dato_mensual['individual']);
                }

                $listado_meses = implode("','", $array_meses);
                $listado_deuda = implode(",", $array_deuda);
                $listado_reserva = implode(",", $array_reserva);
                $listado_individual = implode(",", $array_individual);

                $vars['listado_meses'] = $listado_meses;
                $vars['listado_deuda'] = $listado_deuda;
                $vars['listado_reserva'] = $listado_reserva;
                $vars['listado_individual'] = $listado_individual;

                $this->load->model('payment');
                $deuda_sin_cancelar = $this->payment->get_deuda_sin_cancelar_by_comunidad();
                $deuda_total = $deuda_sin_cancelar->deuda == 0 ? 1 : $deuda_sin_cancelar->deuda;

                /*********************************************/
                $this->load->model('payment');
                $this->load->model('account');
                $datos_dashboard = array(
                    'numpropiedades' => count($propiedades),
                    'deudapropiedad' => $datos_propiedad->saldo_publicado,
                    'deuda_sin_cancelar' => $deuda_sin_cancelar->saldo,
                    'porc_sin_cancelar' => round(($deuda_sin_cancelar->saldo / $deuda_total) * 100, 2),
                    //'deudatotal' => $datoscomunidad->saldo,
                    'deudatotal' => $this->account->get_saldo_cuentas_impagas_by_id()->saldo,
                    'caja' => $datoscomunidad->caja,
                    'fondo_reserva' => $datoscomunidad->fondoreserva,
                    'saldo_disponible' => $this->payment->get_saldo_disponible_by_comunidad($this->session->userdata('comunidadid')),
                    'saldo_contable' => $this->payment->get_saldo_contable_by_comunidad($this->session->userdata('comunidadid')),
                    'level' => $this->session->userdata('level')
                );
                $vars['datos_dashboard'] = $datos_dashboard;
                $vars['pagoonline'] = $datoscomunidad->pagoonline;


                $vars['content_view'] = 'dashboard2';

                $vars['num_propiedades'] = $num_propiedades;

                if (!$this->session->userdata('preloader')) {
                    $this->session->set_userdata('preloader', true);
                    $vars['fakeLoader'] = true;
                }
            } else { // BOTAR

                redirect('auth/logout');
            }
        } else if ($this->session->userdata('level') == 5) { //
            $this->load->model('remuneracion');
            $unidad_id = $unidad_id == '' && $this->session->userdata('comunidadid') ? $this->session->userdata('comunidadid') : $unidad_id;
            $comunidades_asignadas = $unidad_id != '' ? $this->admin->comunidades_asignadas($this->session->userdata('user_id'), $this->session->userdata('level'), $unidad_id) : $this->admin->comunidades_asignadas($this->session->userdata('user_id'), $this->session->userdata('level'));

            if (count($comunidades_asignadas) > 1) { // EN CASO DE TENER MÁS DE UNA COMUNIDAD LO ENVÍA A LA PÁGINA DE SELECCIÓN
                $content = array(
                    'menu' => 'Selecci&oacute;n Comunidad',
                    'title' => 'Comunidades',
                    'subtitle' => 'Selecci&oacute;n de Comunidad'
                );

                $vars['content_menu'] = $content;
                $vars['comunidades'] = $comunidades_asignadas;
                $vars['content_view'] = 'admin/asigna_comunidad';
                $template = "template_lock";
            } else if (count($comunidades_asignadas) == 1) { // SE ASOCIA COMUNIDAD
                $personal = $this->remuneracion->get_personal_by_iduser($this->session->userdata('user_id'), $comunidades_asignadas->id);
                $this->session->set_userdata('comunidadid', $comunidades_asignadas->id);
                $this->session->set_userdata('comunidadnombre', $comunidades_asignadas->nombre);
                $this->session->set_userdata('diasvencsuscripcion', $comunidades_asignadas->vencsuscripcion);

                $vars['personal'] = $personal;
                $vars['content_view'] = 'dashboard4';
            }
        }

        /*** SI YA SE HABIA SELECCIONADO UN MODULO, REDIRECCIONA ****/
        /*if (count($this->session->userdata('uri_array')) > 0) {
            $uri_array = $this->session->userdata('uri_array');
            $url = $uri_array[1] . '/' . $uri_array[2];
            for ($i = 3; $i <= count($uri_array); $i++) {
                $url .= "/" . $uri_array[$i];
            }
            $this->session->unset_userdata('uri_array');
            redirect($url);
        }*/
        $this->load->view($template, $vars);
    }



    public function destroy_data_session()
    {
        $this->session->unset_userdata('comunidadid');
        $this->session->unset_userdata('comunidadnombre');
        $this->session->unset_userdata('propiedadid');
        $this->session->unset_userdata('comunidadnumero');
        $this->session->unset_userdata('preloader');
        redirect('main/dashboard');
    }
}
