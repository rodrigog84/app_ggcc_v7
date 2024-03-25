<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Remuneracion extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->config('ion_auth', TRUE);
        $this->load->helper('cookie');
        $this->load->helper('date');
        $this->lang->load('ion_auth');
        $this->load->helper('format');
    }


    public function get_parametros_generales()
    {

        $comunidades_data = $this->db->select('uf , sueldominimo, csimples, cinvalidas, cmaternales, tasasis, topeimponible, topeimponibleips, topeimponibleafc ')
            ->from('gc_parametros_generales');
        $query = $this->db->get();
        return $query->row();
    }


    public function edit_parametros_generales($parametros)
    {


        $this->db->update('gc_parametros_generales', $parametros);
        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return -1;
        }
    }


    public function edit_tabla_impuesto($array_impuesto)
    {

        foreach ($array_impuesto as $key => $impuesto) {
            $datos = array(
                'desde' => str_replace(".", "", $impuesto['desde']),
                'hasta' => isset($impuesto['hasta']) ? str_replace(".", "", $impuesto['hasta']) : 999999999,
                'factor' => str_replace(",", ".", $impuesto['factor']),
                'rebaja' => str_replace(".", "", $impuesto['rebaja']),
            );

            $this->db->where('id', $key);
            $this->db->update('gc_tabla_impuesto', $datos);
        }

        return 1;
    }




    public function edit_tabla_correccion_monetaria($anno, $array_factores)
    {

        foreach ($array_factores as $key => $mes) {

               $this->db->select('dic')
                ->from('gc_tabla_correccion_monetaria')
                ->where('anno',$anno)
                ->where('mes_orig',$key);
                $query = $this->db->get();
                $data_factor =  $query->result();         

                if(count($data_factor) > 0){

                    $array_data_factor = array(
                                                'dic' => $mes
                                        );

                    $this->db->where('anno', $anno);
                    $this->db->where('mes_orig', $key);
                    $this->db->update('gc_tabla_correccion_monetaria', $array_data_factor);


                }else{


                    $array_data_factor = array(
                                                'anno' => $anno,
                                                'mes_orig' => $key,
                                                'dic' => $mes
                                        );

                    $this->db->insert('gc_tabla_correccion_monetaria', $array_data_factor);

                }

        }

        return 1;
    }



    public function edit_tabla_asig_familiar($array_asig_familiar)
    {

        foreach ($array_asig_familiar as $key => $asig_familiar) {
            $datos = array(
                'desde' => str_replace(".", "", $asig_familiar['desde']),
                'hasta' => isset($asig_familiar['hasta']) ? str_replace(".", "", $asig_familiar['hasta']) : 999999999,
                'monto' => str_replace(".", "", $asig_familiar['monto'])
            );

            $this->db->where('id', $key);
            $this->db->update('gc_tabla_asig_familiar', $datos);
        }

        return 1;
    }


    public function get_estado_civil()
    {

        $this->db->select('id , nombre ')
            ->from('gc_estado_civil')
            ->where('activo = 1')
            ->order_by('nombre asc');
        $query = $this->db->get();

        return $query->result();
    }


    public function get_causal_finiquito()
    {

        $this->db->select('id , motivo, articulo ')
            ->from('gc_causal_finiquito')
            ->where('activo = 1')
            ->order_by('articulo asc');
        $query = $this->db->get();

        return $query->result();
    }    


    public function get_cargos($idcargo = null)
    {
        $cargos_data = $this->db->select('c.id , c.idcomunidad, c.nombre, c.idpadre, c2.nombre as nombrepadre,  (select count(*) from gc_cargos where idpadre = c.id) as hijos ', false)
            ->from('gc_cargos c')
            ->join('gc_cargos c2', 'c.idpadre = c2.id', 'left')
            ->where('(c.idcomunidad = ' . $this->session->userdata('comunidadid') . ' or c.idcomunidad is null)')
            ->where('c.activo = 1')
            ->order_by('c2.id asc');
        $cargos_data = is_null($idcargo) ? $cargos_data : $cargos_data->where('c.id', $idcargo);
        $query = $this->db->get();
        $datos = is_null($idcargo) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_cargos_padres_by_id($idcargo = null)
    {

        $cargo_data = $this->db->select('id , nombre ')
            ->from('gc_cargos')
            ->where('idpadre is null')
            ->where('(idcomunidad = ' . $this->session->userdata('comunidadid') . ' or idcomunidad is null)')
            ->where('activo = 1')
            ->order_by('nombre asc');
        $cargo_data = is_null($idcargo) ? $cargo_data : $cargo_data->where('id', $idcargo);
        $query = $this->db->get();
        $datos = is_null($idcargo) ? $query->result() : $query->row();
        return $datos;
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }


    public function get_cartola_vacaciones($idpersonal = null, $idcartola = null)
    {
        $cargos_data = $this->db->select('c.id , c.idpersonal, c.fecinicio, c.fecfin, c.dias, c.comentarios, c.created_at')
            ->from('gc_cartola_vacaciones c')
            ->join('gc_personal p', 'c.idpersonal = p.id')
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('c.idpersonal', $idpersonal)
            ->where('c.active = 1')
            ->order_by('c.fecinicio');
        $cargos_data = is_null($idcartola) ? $cargos_data : $cargos_data->where('c.id', $idcartola);
        $query = $this->db->get();
        return  is_null($idcartola) ? $query->result() : $query->row();
    }


    public function get_dias_progresivos($idpersonal = null, $iddia = null)
    {
        $cargos_data = $this->db->select('id , idpersonal, fechainicio, dias')
            ->from('gc_dias_progresivos')
            ->where('idpersonal', $idpersonal)
            ->where('active', 1)
            ->order_by('fechainicio');

        $cargos_data = is_null($iddia) ? $cargos_data : $cargos_data->where('id', $iddia);
        $query = $this->db->get();
        return  is_null($iddia) ? $query->result() : $query->row();
    }


    public function add_cargo($array_datos)
    {


        $this->db->select('c.id')
            ->from('gc_cargos as c')
            ->where('upper(c.nombre)', strtoupper($array_datos['cargo']))
            ->where('c.activo = 1')
            ->where('(idcomunidad = ' . $this->session->userdata('comunidadid') . ' or idcomunidad is null)');
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // nuevo proveedor no existe
            if ($array_datos['idcargo'] == 0) {
                $data = array(
                    'nombre' => $array_datos['cargo'],
                    'idpadre' => $array_datos['tipocargo'],
                    'idcomunidad' => $this->session->userdata('comunidadid'),
                    'activo' => 1
                );

                $this->db->insert('gc_cargos', $data);

                $idcargo = $this->db->insert_id();
                return 1;
            } else {
                $data = array(
                    'nombre' => $array_datos['cargo'],
                    'idpadre' => $array_datos['tipocargo'],
                );

                $this->db->where('id', $array_datos['idcargo']);
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_cargos', $data);
                return 1;
            }
        } else { // ya existe proveedor nuevo
            if ($array_datos['idcargo'] != 0) {
                $data = array(
                    'nombre' => $array_datos['cargo'],
                    'idpadre' => $array_datos['tipocargo'],
                );

                $this->db->where('id', $array_datos['idcargo']);
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_cargos', $data);
            } else {
                return -1;
            }
        }
    }



    public function delete_cargo($idcargo)
    {


        $this->db->where('id', $idcargo);
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_cargos', array('activo' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó el padre.  Se eliminan los hijos
            $this->db->where('idpadre', $idcargo);
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_cargos', array('activo' => '0'));
            return 1;
        } else { // no hubo eliminación de cargo
            return -1;
        }
    }



    public function delete_vacaciones($idpersonal, $idcartola)
    {

        $this->db->trans_start();
        $cartola = $this->get_cartola_vacaciones($idpersonal, $idcartola);


        if (is_null($cartola)) {
            $this->db->trans_complete();
            return false;
        } else {

            $this->db->where('id', $idcartola);
            $this->db->where('idpersonal', $idpersonal);
            $this->db->update('gc_cartola_vacaciones', array('active' => '0'));

            $this->db->query('update gc_personal set diasvactomados = diasvactomados - ' . $cartola->dias . ' where id = ' . $idpersonal);


            $this->db->trans_complete();
            return true;
        }
    }



    public function delete_dias_progresivos($idpersonal, $idcartola)
    {

        $this->db->trans_start();
        $cartola = $this->get_dias_progresivos($idpersonal, $idcartola);


        if (is_null($cartola)) {
            $this->db->trans_complete();
            return 2;
        } else {






            $this->db->where('id', $idcartola);
            $this->db->where('idpersonal', $idpersonal);
            $this->db->update('gc_dias_progresivos', array('active' => '0'));

            $personal = $this->remuneracion->get_personal($idpersonal);
            $dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones);
            $dias_progresivos = $this->remuneracion->get_dias_progresivos($idpersonal);
            $num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);
            $saldo_vacaciones = $dias_vacaciones + $num_dias_progresivos - $personal->diasvactomados;


            if ($saldo_vacaciones < 0) {
                $this->db->where('id', $idcartola);
                $this->db->where('idpersonal', $idpersonal);
                $this->db->update('gc_dias_progresivos', array('active' => '1'));
                $this->db->trans_complete();
                return 3;
            }

            $this->db->query('update gc_personal set diasprogresivos = diasprogresivos - ' . $cartola->dias . ' where id = ' . $idpersonal);


            $this->db->trans_complete();
            return 1;
        }
    }

    public function get_personal_total($idtrabajador = null, $tipo_colaborador = null)
    {

        $personal_data = $this->db->select('u.email as uemail, p.id, p.iduser, p.idcomunidad, p.rut, p.dv, p.nombre, p.apaterno, p.amaterno, date_format(p.fecnacimiento,"%d/%m/%Y") as fecnacimiento, date_format(p.fecfiniquito,"%d/%m/%Y") as fecfiniquito, p.sexo, p.idecivil, p.nacionalidad, p.direccion, p.idregion, p.idcomuna, p.fono, p.email, date_format(p.fecingreso,"%d/%m/%Y") as fecingreso, p.idcargo, p.tipocontrato, p.parttime, p.segcesantia, date_format(p.fecafc,"%d/%m/%Y") as fecafc, p.diastrabajo, p.horasdiarias, p.horassemanales, p.sueldobase, p.tipogratificacion, p.gratificacion, p.asigfamiliar, p.cargassimples, p.cargasinvalidas, p.cargasmaternales, p.cargasretroactivas, p.idasigfamiliar, p.movilizacion, p.colacion, p.pensionado, p.idafp, p.adicafp, p.tipoahorrovol, p.ahorrovol, p.instapv, p.nrocontratoapv, p.tipocotapv, p.cotapv, p.formapagoapv, p.depconvapv, p.idisapre, p.valorpactado, date_format(p.fecinicvacaciones,"%d/%m/%Y") as fecinicvacaciones, p.fecinicvacaciones as fecinicvacaciones_sformato, p.saldoinicvacaciones, p.diasvactomados, p.saldoinicvacprog, p.active, p.indmesaviso, p.indannoservicio, p.indferiadolegal, p.indvoluntaria, p.indtotal, p.causalfiniquito, COALESCE((select sum(monto) as monto from gc_bonos_personal where idpersonal = p.id and fijo = 1 and imponible = 1),0) as bonos_fijos, ROUND(TIMESTAMPDIFF(MONTH,fecingreso,CURDATE())/12,0) as annos_empresa',false)
            ->from('gc_personal p')
            ->join('gc_users u', 'p.iduser = u.id', 'left')
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            ->order_by('p.active', 'desc')
            ->order_by('p.nombre');
        $personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('p.id', $idtrabajador);


        if (!is_null($tipo_colaborador)) {
            if ($tipo_colaborador == 'activos') {
                $personal_data = $personal_data->where('p.active', 1);
            } else if ($tipo_colaborador == 'inactivos') {
                $personal_data = $personal_data->where('p.active', 0);
            }
        }


        $query = $this->db->get();
        $datos = is_null($idtrabajador) ? $query->result() : $query->row();
        return $datos;
    }

    public function get_personal($idtrabajador = null, $tipo_colaborador = null)
    {
        $array_campos = array(
            'id',
            'idcomunidad',
            'rut',
            'dv',
            'nombre',
            'apaterno',
            'amaterno',
            'date_format(fecnacimiento,"%d/%m/%Y") as fecnacimiento',
            'sexo',
            'idecivil',
            'nacionalidad',
            'direccion',
            'idregion',
            'idcomuna',
            'fono',
            'email',
            'date_format(fecingreso,"%d/%m/%Y") as fecingreso',
            'fecingreso as fecingreso_sformat',
            'idcargo',
            'tipocontrato',
            'parttime',
            'segcesantia',
            'pensionado',
            'diastrabajo',
            'horasdiarias',
            'horassemanales',
            'sueldobase',
            'tipogratificacion',
            'gratificacion',
            'asigfamiliar',
            'cargassimples',
            'cargasinvalidas',
            'cargasmaternales',
            'cargasretroactivas',
            'idasigfamiliar',
            'movilizacion',
            'colacion',
            'idafp',
            'adicafp',
            'tipoahorrovol',
            'ahorrovol',
            'tipocotapv',
            'cotapv',
            'idisapre',
            'valorpactado',
            'COALESCE((select sum(monto) as monto from gc_bonos_personal where idpersonal = p.id and fijo = 1 and imponible = 1),0) as bonos_fijos',
            'TIMESTAMPDIFF(year,fecafc,curdate()) + 1 as annos_afc,
            ROUND(TIMESTAMPDIFF(MONTH,fecingreso,CURDATE())/12,0) as annos_empresa,
				TIMESTAMPDIFF(month,fecinicvacaciones,curdate()) as meses_vac,
				fecinicvacaciones,
				saldoinicvacaciones,
				diasvactomados,
				diasprogresivos,
				diasprogtomados,
				saldoinicvacprog,
				active'
        );
        //'COALESCE(COALESCE((select sum(monto) as monto from gc_bonos_personal where idpersonal = p.id and fijo = 1 and imponible = 1),0) + p.movilizacion + p.colacion,0) as bonos_fijos'
        //$personal_data = $this->db->select('id, idcomunidad, rut, dv, nombre, apaterno, amaterno, date_format(fecnacimiento,"%d/%m/%Y") as fecnacimiento, sexo, idecivil, nacionalidad, direccion, idregion, idcomuna, fono, email, date_format(fecingreso,"%d/%m/%Y") as fecingreso, idcargo, tipocontrato, parttime, segcesantia, diastrabajo, horasdiarias, horassemanales, sueldobase, tipogratificacion, gratificacion, asigfamiliar, cargassimples, cargasinvalidas, cargasmaternales, cargasretroactivas, movilizacion, colacion, idafp, adicafp, tipoahorrovol, ahorrovol, tipocotapv, cotapv, idisapre, valorpactado, COALESCE(COALESCE((select sum(monto) as monto from gc_bonos_personal where idpersonal = p.id and fijo = 1),0) + p.movilizacion + p.colacion,0) as bonos_fijos', false)
        $personal_data = $this->db->select($array_campos)
            ->from('gc_personal p')
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            // ->where('p.active = 1')
            ->order_by('p.nombre');
        $personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('p.id', $idtrabajador);

        if (!is_null($tipo_colaborador)) {
            if ($tipo_colaborador == 'todos') {
                $personal_data = $personal_data;
                //$personal_data = $personal_data->order_by('p.nombre');
            } else if ($tipo_colaborador == 'inactivos') {
                $personal_data = $personal_data->where('p.active', 0);
            } else {
                $personal_data = $personal_data->where('p.active', 1);
            }
        } else {

            $personal_data = $personal_data->where('p.active', 1);
        }

        $query = $this->db->get();
        //echo $this->db->last_query(); 
        $datos = is_null($idtrabajador) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_datos_remuneracion($mes, $anno, $idtrabajador = null)
    {

        $personal_data = $this->db->select('r.idpersonal, r.idperiodo, r.diastrabajo, r.horasdescuento, r.montodescuento, r.valorhorasextras50, r.horasextras50, r.montohorasextras50, r.valorhorasextras100, r.horasextras100, r.montohorasextras100, r.anticipo, r.aguinaldo, r.sueldobase, r.gratificacion, r.movilizacion, r.valorhora')
            ->from('gc_remuneracion r')
            ->join('gc_personal pe', 'r.idpersonal = pe.id')
            ->join('gc_periodo p', 'r.idperiodo = p.id')
            ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('pe.active = 1')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno)
            ->order_by('pe.nombre');
        $personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('r.idpersonal', $idtrabajador);
        $query = $this->db->get();
        $datos = is_null($idtrabajador) ? $query->result() : $query->row();
        return $datos;
    }



    public function get_datos_descuentos($mes, $anno, $idtrabajador = null)
    {

        $personal_data = $this->db->select('dp.id, td.tipo, td.nombre as nombre_tipo, pe.id as idtrabajador, pe.rut, pe.dv, pe.nombre, pe.apaterno, pe.amaterno, dp.monto')
            ->from('gc_descuentos_personal dp')
            ->join('gc_tipo_descuento td', 'dp.tipodescuento = td.id')
            ->join('gc_personal pe', 'dp.idpersonal = pe.id')
            ->join('gc_periodo p', 'dp.idperiodo = p.id')
            ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('pe.active = 1')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno)
            ->order_by('pe.nombre');
        $personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('pe.id', $idtrabajador);
        $query = $this->db->get();
        $datos = is_null($idtrabajador) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_descuentos_by_id($iddescuento)
    {

        $this->db->select('dp.id, td.id as idtipodescuento, td.tipo, td.nombre as nombre_tipo, pe.id as idtrabajador, pe.rut, pe.dv, pe.nombre, pe.apaterno, pe.amaterno, dp.monto, dp.descripcion, p.mes, p.anno')
            ->from('gc_descuentos_personal dp')
            ->join('gc_tipo_descuento td', 'dp.tipodescuento = td.id')
            ->join('gc_personal pe', 'dp.idpersonal = pe.id')
            ->join('gc_periodo p', 'dp.idperiodo = p.id')
            ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('pe.active = 1')
            ->where('dp.id', $iddescuento);

        $query = $this->db->get();
        return $query->row();
    }


    public function get_datos_remuneracion_by_periodo($idperiodo, $idtrabajador = null)
    {

        $personal_data = $this->db->select('r.id, r.idpersonal, r.idperiodo, r.diastrabajo, r.horasdescuento, r.montodescuento, r.horasextras50, r.montohorasextras50, r.horasextras100, r.montohorasextras100, r.anticipo, r.aguinaldo, r.sueldobase, r.gratificacion, r.movilizacion, r.sueldonoimponible, r.totalleyessociales, r.otrosdescuentos')
            ->from('gc_remuneracion r')
            ->join('gc_personal pe', 'r.idpersonal = pe.id')
            ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('pe.active = 1')
            ->where('r.idperiodo', $idperiodo)
            ->order_by('pe.nombre');
        $personal_data = is_null($idtrabajador) ? $personal_data : $personal_data->where('r.idpersonal', $idtrabajador);
        $query = $this->db->get();
        $datos = is_null($idtrabajador) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_bonos($idtrabajador = null)
    {

        $bonos_data = $this->db->select('id, idpersonal, descripcion, monto, date_format(fecha,"%d/%m/%Y") as fecha, proporcional, imponible, fijo')
            ->from('gc_bonos_personal b')
            ->where('b.idpersonal', $idtrabajador)
            ->order_by('b.id');
        $query = $this->db->get();
        return $query->result();
    }



    public function get_bonos_by_remuneracion($idremuneracion, $imponible = null)
    {

        if (!is_null($imponible)) {
            $campo_imponible = $imponible == true ? 1 : 0;
        }

        $bonos_data = $this->db->select('id, descripcion, imponible, monto')
            ->from('gc_bonos_remuneracion')
            ->where('idremuneracion', $idremuneracion)
            ->order_by('id');

        $bonos_data = is_null($imponible) ? $bonos_data : $bonos_data->where('imponible', $campo_imponible);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_afp($idafp = null)
    {

        $afp_data = $this->db->select('id, nombre, porc, exregimen, codprevired')
            ->from('gc_afp a')
            ->where('a.active = 1')
            ->order_by('a.exregimen')
            ->order_by('a.nombre');
        $afp_data = is_null($idafp) ? $afp_data : $afp_data->where('a.id', $idafp);
        $query = $this->db->get();
        $datos = is_null($idafp) ? $query->result() : $query->row();
        return $datos;
    }



    public function get_apv($idapv = null)
    {

        $apv_data = $this->db->select('id, nombre, codprevired')
            ->from('gc_apv a')
            ->where('a.active = 1')
            ->order_by('a.nombre');
        $apv_data = is_null($idapv) ? $apv_data : $apv_data->where('a.id', $idapv);
        $query = $this->db->get();
        $datos = is_null($idapv) ? $query->result() : $query->row();
        return $datos;
    }

    public function get_tipo_descuento($idtipodescuento = null)
    {

        $tipo_descuento_data = $this->db->select('id, nombre, tipo')
            ->from('gc_tipo_descuento td')
            ->where('td.active = 1')
            ->order_by('td.tipo')
            ->order_by('td.nombre');
        $tipo_descuento_data = is_null($idtipodescuento) ? $tipo_descuento_data : $tipo_descuento_data->where('td.id', $idtipodescuento);
        $query = $this->db->get();
        $datos = is_null($idtipodescuento) ? $query->result() : $query->row();
        return $datos;
    }

    public function get_isapre($idisapre = null)
    {

        $isapre_data = $this->db->select('id, nombre, codprevired')
            ->from('gc_isapre i')
            ->where('i.active = 1')
            ->order_by('i.id');
        $isapre_data = is_null($idisapre) ? $isapre_data : $isapre_data->where('i.id', $idisapre);
        $query = $this->db->get();
        $datos = is_null($idisapre) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_feriado($idferiado = null)
    {

        $feriado_data = $this->db->select('id, date_format(fecha,"%d/%m/%Y") as fecha, fecha as fecha_sformat', false)
            ->from('gc_feriado f')
            ->where('f.active = 1')
            ->order_by('f.fecha', 'desc');
        $feriado_data = is_null($idferiado) ? $feriado_data : $feriado_data->where('f.id', $idferiado);
        $query = $this->db->get();
        $datos = is_null($idferiado) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_movimiento($idmovimiento = null)
    {

        $movimiento_data = $this->db->select('id, nombre, rango, codprevired')
            ->from('gc_movimientos_personal')
            ->where('active = 1')
            ->order_by('codprevired', 'asc');
        $movimiento_data = is_null($idmovimiento) ? $movimiento_data : $movimiento_data->where('id', $idmovimiento);
        $query = $this->db->get();
        $datos = is_null($idmovimiento) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_cajas_compensacion($idcaja = null)
    {

        $caja_data = $this->db->select('id, nombre, codprevired')
            ->from('gc_cajas_compensacion c')
            ->where('c.active = 1')
            ->order_by('c.id');
        $caja_data = is_null($idcaja) ? $caja_data : $caja_data->where('c.id', $idcaja);
        $query = $this->db->get();
        $datos = is_null($idcaja) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_mutual_seguridad($idmutual = null)
    {

        $mutual_data = $this->db->select('id, nombre, codprevired')
            ->from('gc_mutual_seguridad m')
            ->where('m.active = 1')
            ->order_by('m.id');
        $mutual_data = is_null($idmutual) ? $mutual_data : $mutual_data->where('m.id', $idmutual);
        $query = $this->db->get();
        $datos = is_null($idmutual) ? $query->result() : $query->row();
        return $datos;
    }


    private function cambio_estado($idtrabajador, $mensaje, $active, $fecfiniquito = null)
    {

        $array_datos = array(
            'idpersonal' => $idtrabajador,
            'observacion' => $mensaje,
            'active' => $active,
            'fecfiniquito' => $fecfiniquito
        );

        //echo "<pre>";
        //print_r($array_datos); exit;
        $this->db->insert('gc_log_personal', $array_datos);
    }

    public function add_personal($array_datos, $array_bonos, $idtrabajador)
    {
        $this->db->trans_start();

        $this->db->select('p.id, p.active')
            ->from('gc_personal as p')
            ->where('p.rut', $array_datos['rut'])
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // nuevo trabajador no existe
            if ($idtrabajador == 0) {
                $array_datos['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('gc_personal', $array_datos);
                $idpersonal = $this->db->insert_id();


                foreach ($array_bonos as $bono) {
                    $bono['idpersonal'] = $idpersonal;
                    $this->db->insert('gc_bonos_personal', $bono);
                }

                $this->db->trans_complete();
                return $idpersonal;
            } else {
                $this->db->select('p.id, p.active')
                    ->from('gc_personal as p')
                    ->where('p.id', $idtrabajador)
                    ->where('p.idcomunidad', $this->session->userdata('comunidadid'));
                $query = $this->db->get();
                $trabajador = $query->row();
                $cambio_estado = false;


                if ($trabajador->active == 1 && $array_datos['active'] == 0) {
                    $cambio_estado = true;
                    $mensaje = "Desactivación Trabajador";
                    $fecfiniquito = $array_datos['fecfiniquito'];
                } else if ($trabajador->active == 0 && $array_datos['active'] == 1) {
                    $cambio_estado = true;
                    $mensaje = "Activación Trabajador";
                    $fecfiniquito = null;
                } else {
                    $cambio_estado = false;
                }


                unset($array_datos['rut']);
                unset($array_datos['dv']);
                $this->db->where('id', $idtrabajador);
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_personal', $array_datos);




                $this->db->delete('gc_bonos_personal', array('idpersonal' => $idtrabajador));
                foreach ($array_bonos as $bono) {
                    $bono['idpersonal'] = $idtrabajador;
                    $this->db->insert('gc_bonos_personal', $bono);
                }


                if ($cambio_estado) {
                    $this->cambio_estado($idtrabajador, $mensaje, $array_datos['active'], $fecfiniquito);
                }

                $this->db->trans_complete();
                return 1;
            }
        } else { // ya existe trabajador
            if ($idtrabajador != 0) {
                unset($array_datos['rut']);
                unset($array_datos['dv']);
                $this->db->where('id', $idtrabajador);
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_personal', $array_datos);

                $this->db->delete('gc_bonos_personal', array('idpersonal' => $idtrabajador));
                foreach ($array_bonos as $bono) {
                    $bono['idpersonal'] = $idtrabajador;
                    $this->db->insert('gc_bonos_personal', $bono);
                }

                $this->db->trans_complete();
                return 1;
            } else {
                $this->db->trans_complete();
                return -1;
            }
        }
    }


    public function update_personal_leyes_sociales($array_trabajadores)
    {


        $this->db->trans_start();

        foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

            $trabajador_data = array(
                'idafp' => $info_trabajador['afp'] == '' ? null : $info_trabajador['afp'],
                'adicafp' => $info_trabajador['cotadic'],
                'tipoahorrovol' => $info_trabajador['tipcotvol'],
                'ahorrovol' => $info_trabajador['tipcotvol'] == 'pesos' ? str_replace(".", "", $info_trabajador['cotvol']) : $info_trabajador['cotvol']
            );

            $this->db->where('id', $idtrabajador);
            $this->db->update('gc_personal', $trabajador_data);
        }

        $this->db->trans_complete();
        return 1;
    }




    public function update_personal_apv($array_trabajadores)
    {


        $this->db->trans_start();

        foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

            if (isset($info_trabajador['tipoapv'])) {
                if ($info_trabajador['tipoapv'] == 'pesos') {
                    $info_trabajador['apv'] = str_replace(".", "", $info_trabajador['apv']);
                } else if ($info_trabajador['tipoapv'] == 'uf') {
                    $info_trabajador['apv'] = str_replace(".", "", $info_trabajador['apv']);
                    $info_trabajador['apv'] = str_replace(",", ".", $info_trabajador['apv']);
                }
            }

            if (isset($info_trabajador['depconvapv'])) {
                $info_trabajador['depconvapv'] = str_replace(".", "", $info_trabajador['depconvapv']);
            }



            $trabajador_data = array(
                'instapv' => $info_trabajador['instapv'] != '' ?  $info_trabajador['instapv'] : null,
                'nrocontratoapv' => isset($info_trabajador['nrocontratoapv']) ? $info_trabajador['nrocontratoapv'] : 0,
                'tipocotapv' => isset($info_trabajador['tipoapv']) ? $info_trabajador['tipoapv'] : 'pesos',
                'cotapv' => isset($info_trabajador['apv']) ? $info_trabajador['apv'] : 0,
                'formapagoapv' => isset($info_trabajador['formapagoapv']) ? $info_trabajador['formapagoapv'] : null,
                'depconvapv' => isset($info_trabajador['depconvapv']) ? $info_trabajador['depconvapv'] : 0,
            );

            $this->db->where('id', $idtrabajador);
            $this->db->update('gc_personal', $trabajador_data);
        }

        $this->db->trans_complete();
        return 1;
    }

    public function update_caja_mutual($array_datos)
    {


        $this->db->where('id', $this->session->userdata('comunidadid'));
        $this->db->update('gc_comunidad', $array_datos);
        return 1;
    }





    public function update_personal_salud($array_trabajadores)
    {


        $this->db->trans_start();

        foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {
            $info_trabajador['pactado'] = $info_trabajador['isapre'] == 1 ? 0 : str_replace(",", ".", $info_trabajador['pactado']);

            $trabajador_data = array(
                'idisapre' => $info_trabajador['isapre'] == '' ? null : $info_trabajador['isapre'],
                'valorpactado' => $info_trabajador['pactado']
            );

            $this->db->where('id', $idtrabajador);
            $this->db->update('gc_personal', $trabajador_data);
        }
        $this->db->trans_complete();
        return 1;
    }




    public function save_asistencia($array_trabajadores, $mes, $anno)
    {


        $this->db->trans_start();

        // evaluar si existe periodo
        $this->db->select('p.id')
            ->from('gc_periodo as p')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno);
        $query = $this->db->get();
        $datos_periodo = $query->row();
        $idperiodo = 0;
        if (is_null($datos_periodo)) { // si no existe periodo, se crea
            $data = array(
                'mes' => $mes,
                'anno' =>  $anno
            );
            $this->db->insert('gc_periodo', $data);
            $idperiodo = $this->db->insert_id();
        } else {
            $idperiodo = $datos_periodo->id;
        }


        // evaluar si existe periodo remuneraciones
        $this->db->select('r.idperiodo')
            ->from('gc_periodo_remuneracion as r')
            ->where('r.idperiodo', $idperiodo)
            ->where('r.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos_periodo_remuneracion = $query->row();
        if (is_null($datos_periodo_remuneracion)) { // si no existe periodo, se crea
            $data = array(
                'idperiodo' => $idperiodo,
                'idcomunidad' => $this->session->userdata('comunidadid')
            );
            $this->db->insert('gc_periodo_remuneracion', $data);
        }




        foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

            $this->db->select('r.idperiodo')
                ->from('gc_remuneracion as r')
                ->where('r.idpersonal', $idtrabajador)
                ->where('r.idperiodo', $idperiodo);
            $query = $this->db->get();
            $datos_remuneracion = $query->row();
            if (is_null($datos_remuneracion)) { // si no existe periodo, se crea

                $data = array(
                    'idpersonal' => $idtrabajador,
                    'idperiodo' => $idperiodo,
                    'diastrabajo' => $info_trabajador,
                    'created_at' => date("Y-m-d H:i:s")

                );
                $this->db->insert('gc_remuneracion', $data);
            } else {
                $data = array(
                    'diastrabajo' => $info_trabajador
                );
                $this->db->where('idpersonal', $idtrabajador);
                $this->db->where('idperiodo', $idperiodo);
                $this->db->update('gc_remuneracion', $data);
            }
        }

        $this->db->trans_complete();
        return 1;
    }



    public function save_horas_descuentos($array_trabajadores, $mes, $anno)
    {

        $this->db->trans_start();

        // evaluar si existe periodo
        $this->db->select('p.id')
            ->from('gc_periodo as p')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno);
        $query = $this->db->get();
        $datos_periodo = $query->row();
        $idperiodo = 0;
        if (is_null($datos_periodo)) { // si no existe periodo, se crea
            $data = array(
                'mes' => $mes,
                'anno' =>  $anno
            );
            $this->db->insert('gc_periodo', $data);
            $idperiodo = $this->db->insert_id();
        } else {
            $idperiodo = $datos_periodo->id;
        }


        // evaluar si existe periodo remuneraciones
        $this->db->select('r.idperiodo')
            ->from('gc_periodo_remuneracion as r')
            ->where('r.idperiodo', $idperiodo)
            ->where('r.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos_periodo_remuneracion = $query->row();
        if (is_null($datos_periodo_remuneracion)) { // si no existe periodo, se crea
            $data = array(
                'idperiodo' => $idperiodo,
                'idcomunidad' => $this->session->userdata('comunidadid')
            );
            $this->db->insert('gc_periodo_remuneracion', $data);
        }




        foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

            $this->db->select('r.idperiodo')
                ->from('gc_remuneracion as r')
                ->where('r.idpersonal', $idtrabajador)
                ->where('r.idperiodo', $idperiodo);
            $query = $this->db->get();
            $datos_remuneracion = $query->row();
            if (is_null($datos_remuneracion)) { // si no existe periodo, se crea
                $data = array(
                    'idpersonal' => $idtrabajador,
                    'idperiodo' => $idperiodo,
                    'horasdescuento' => $info_trabajador['horasdescuento'],
                    'montodescuento' => $info_trabajador['valordescuento'],
                    'created_at' => date("Y-m-d H:i:s")

                );
                $this->db->insert('gc_remuneracion', $data);
            } else {
                $data = array(
                    'horasdescuento' => $info_trabajador['horasdescuento'],
                    'montodescuento' => $info_trabajador['valordescuento'],
                );
                $this->db->where('idpersonal', $idtrabajador);
                $this->db->where('idperiodo', $idperiodo);
                $this->db->update('gc_remuneracion', $data);
            }
        }

        $this->db->trans_complete();
        return 1;
    }

    public function save_horas_extraordinarias($array_trabajadores, $mes, $anno)
    {

        $this->db->trans_start();

        // evaluar si existe periodo
        $this->db->select('p.id')
            ->from('gc_periodo as p')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno);
        $query = $this->db->get();
        $datos_periodo = $query->row();
        $idperiodo = 0;
        if (is_null($datos_periodo)) { // si no existe periodo, se crea
            $data = array(
                'mes' => $mes,
                'anno' =>  $anno
            );
            $this->db->insert('gc_periodo', $data);
            $idperiodo = $this->db->insert_id();
        } else {
            $idperiodo = $datos_periodo->id;
        }


        // evaluar si existe periodo remuneraciones
        $this->db->select('r.idperiodo')
            ->from('gc_periodo_remuneracion as r')
            ->where('r.idperiodo', $idperiodo)
            ->where('r.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos_periodo_remuneracion = $query->row();
        if (is_null($datos_periodo_remuneracion)) { // si no existe periodo, se crea
            $data = array(
                'idperiodo' => $idperiodo,
                'idcomunidad' => $this->session->userdata('comunidadid')
            );
            $this->db->insert('gc_periodo_remuneracion', $data);
        }




        foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

            $this->db->select('r.idperiodo')
                ->from('gc_remuneracion as r')
                ->where('r.idpersonal', $idtrabajador)
                ->where('r.idperiodo', $idperiodo);
            $query = $this->db->get();
            $datos_remuneracion = $query->row();
            if (is_null($datos_remuneracion)) { // si no existe periodo, se crea
                $data = array(
                    'idpersonal' => $idtrabajador,
                    'idperiodo' => $idperiodo,
                    'horasextras50' => $info_trabajador['horas50'],
                    'montohorasextras50' => $info_trabajador['monto50'],
                    'horasextras100' => $info_trabajador['horas100'],
                    'montohorasextras100' => $info_trabajador['monto100'],
                    'created_at' => date("Y-m-d H:i:s")

                );
                $this->db->insert('gc_remuneracion', $data);
            } else {
                $data = array(
                    'horasextras50' => $info_trabajador['horas50'],
                    'montohorasextras50' => $info_trabajador['monto50'],
                    'horasextras100' => $info_trabajador['horas100'],
                    'montohorasextras100' => $info_trabajador['monto100']
                );
                $this->db->where('idpersonal', $idtrabajador);
                $this->db->where('idperiodo', $idperiodo);
                $this->db->update('gc_remuneracion', $data);
            }
        }

        $this->db->trans_complete();
        return 1;
    }



    public function traspasa_anticipo($mes, $anno)
    {

        $this->db->trans_start();

        // evaluar si existe periodo
        $this->db->select('p.id')
            ->from('gc_periodo as p')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno);
        $query = $this->db->get();
        $datos_periodo = $query->row();
        $idperiodo = 0;
        if (is_null($datos_periodo)) { // si no existe periodo, se crea
            $data = array(
                'mes' => $mes,
                'anno' =>  $anno
            );
            $this->db->insert('gc_periodo', $data);
            $idperiodo = $this->db->insert_id();
        } else {
            $idperiodo = $datos_periodo->id;
        }


        // evaluar si existe periodo remuneraciones
        $this->db->select('r.idperiodo')
            ->from('gc_periodo_remuneracion as r')
            ->where('r.idperiodo', $idperiodo)
            ->where('r.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos_periodo_remuneracion = $query->row();
        if (is_null($datos_periodo_remuneracion)) { // si no existe periodo, se crea
            $data = array(
                'idperiodo' => $idperiodo,
                'idcomunidad' => $this->session->userdata('comunidadid')
            );
            $this->db->insert('gc_periodo_remuneracion', $data);
        }


        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->where('idperiodo', $idperiodo);
        $this->db->update('gc_periodo_remuneracion', array('anticipo' => date("Y-m-d H:i:s")));

        $periodo =  $this->get_periodos($this->session->userdata('comunidadid'), $idperiodo);
        $personal = $this->get_personal();
        $this->load->model('account');
        $dia_mes =  $periodo->mes == 2 ? 28 : 30;
        foreach ($personal as $trabajador) {
            $datos_remuneracion = $this->get_datos_remuneracion_by_periodo($idperiodo, $trabajador->id);
            if ($datos_remuneracion->anticipo > 0) { // AGREGA CUENTA POR ANTICIPO
                $datos_cuenta = array(
                    'formapago' => 'gc',
                    'nombreproveedor' => $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno,
                    'documento' =>  date("Ym") . $trabajador->id,
                    'tipodoc' =>  9,
                    'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-15",
                    'concepto' =>  53, //revisar
                    'descripcion' => "Anticipo Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                    'monto' => $datos_remuneracion->anticipo,
                    'idperiodo' => $idperiodo
                );
                $this->account->add_cuenta_remuneracion($datos_cuenta);
            }

            if ($datos_remuneracion->aguinaldo > 0) {

                $datos_cuenta = array(
                    'formapago' => 'gc',
                    'nombreproveedor' => $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno,
                    'documento' =>  date("Ym") . $trabajador->id,
                    'tipodoc' =>  10,
                    'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                    'concepto' =>  54, //revisar
                    'descripcion' => "Aguinaldo Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                    'monto' => $datos_remuneracion->aguinaldo,
                    'idperiodo' => $idperiodo
                );
                $this->account->add_cuenta_remuneracion($datos_cuenta);
            }
        }

        $this->db->trans_complete();
        return 1;
    }



    public function save_anticipo($array_trabajadores, $mes, $anno)
    {

        $this->db->trans_start();

        // evaluar si existe periodo
        $this->db->select('p.id')
            ->from('gc_periodo as p')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno);
        $query = $this->db->get();
        $datos_periodo = $query->row();
        $idperiodo = 0;
        if (is_null($datos_periodo)) { // si no existe periodo, se crea
            $data = array(
                'mes' => $mes,
                'anno' =>  $anno
            );
            $this->db->insert('gc_periodo', $data);
            $idperiodo = $this->db->insert_id();
        } else {
            $idperiodo = $datos_periodo->id;
        }


        // evaluar si existe periodo remuneraciones
        $this->db->select('r.idperiodo')
            ->from('gc_periodo_remuneracion as r')
            ->where('r.idperiodo', $idperiodo)
            ->where('r.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos_periodo_remuneracion = $query->row();
        if (is_null($datos_periodo_remuneracion)) { // si no existe periodo, se crea
            $data = array(
                'idperiodo' => $idperiodo,
                'idcomunidad' => $this->session->userdata('comunidadid')
            );
            $this->db->insert('gc_periodo_remuneracion', $data);
        }




        foreach ($array_trabajadores as $idtrabajador => $info_trabajador) {

            $this->db->select('r.idperiodo')
                ->from('gc_remuneracion as r')
                ->where('r.idpersonal', $idtrabajador)
                ->where('r.idperiodo', $idperiodo);
            $query = $this->db->get();
            $datos_remuneracion = $query->row();
            if (is_null($datos_remuneracion)) { // si no existe periodo, se crea
                $data = array(
                    'idpersonal' => $idtrabajador,
                    'idperiodo' => $idperiodo,
                    'anticipo' => $info_trabajador['anticipo'],
                    'aguinaldo' => $info_trabajador['aguinaldo'],
                    'created_at' => date("Y-m-d H:i:s")

                );
                $this->db->insert('gc_remuneracion', $data);
            } else {
                $data = array(
                    'anticipo' => $info_trabajador['anticipo'],
                    'aguinaldo' => $info_trabajador['aguinaldo'],
                );
                $this->db->where('idpersonal', $idtrabajador);
                $this->db->where('idperiodo', $idperiodo);
                $this->db->update('gc_remuneracion', $data);
            }
        }

        $this->db->trans_complete();
        return 1;
    }


    public function get_valida_vacaciones($idpersonal, $fechadesde, $fechahasta)
    {

        $this->db->select('id')
            ->from('gc_cartola_vacaciones')
            ->where('idpersonal', $idpersonal)
            ->where('active', 1)
            ->where("('" . $fechadesde . "' between fecinicio and fecfin
		                  			or '" . $fechahasta . "' between fecinicio and fecfin
		                  			or fecinicio between '" . $fechadesde . "' and '" . $fechahasta . "'
		                  			or fecfin between '" . $fechadesde . "' and '" . $fechahasta . "')");
        $query = $this->db->get();
        //echo $this->db->last_query()." ---- ";
        $datos_periodo = $query->result();
        //var_dump($datos_periodo);
        if (count($datos_periodo) >  0) {
            return false;
        } else {
            return true;
        }
    }


    public function get_estado_periodo($mes, $anno)
    {

        $this->db->select('pr.anticipo, pr.cierre')
            ->from('gc_periodo_remuneracion as pr')
            ->join('gc_periodo as p', 'pr.idperiodo = p.id')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno)
            ->where('pr.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos_periodo = $query->row();
        if (is_null($datos_periodo)) {
            return 2;
        } else {

            if (is_null($datos_periodo->cierre)) {
                return is_null($datos_periodo->anticipo) ? 1 : 3;  #EL 3 aplica sólo en cálculo de anticipo
            } else {
                return 0;
            }
        }
    }



    public function get_periodos_remuneracion_abiertos($idperiodo = null)
    {
        $data_periodo = $this->db->select('p.id, p.mes, p.anno, pr.cierre, pr.aprueba, pr.anticipo')
            ->from('gc_periodo as p')
            ->join('gc_periodo_remuneracion as pr', 'p.id = pr.idperiodo')
            ->where('pr.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('pr.aprueba is null')
            ->order_by('p.anno', 'desc')
            ->order_by('p.mes', 'desc');

        $data_periodo = is_null($idperiodo)    ? $data_periodo : $data_periodo->where('pr.idperiodo', $idperiodo);

        $query = $this->db->get();
        return is_null($idperiodo) ? $query->result() : $query->row();
    }



    public function get_periodos_remuneracion_cerrados_sin_abonos()
    {
        $query = $this->db->query('select p.id, p.mes, p.anno, pr.cierre, pr.aprueba, pr.anticipo
										from gc_periodo as p
										inner join gc_periodo_remuneracion as pr on p.id = pr.idperiodo and pr.idcomunidad = ' . $this->session->userdata('comunidadid') . '
										where p.id in (
											select idperiodoremuneracion from
											(
											select sum(autorizacion) autoriza, sum(abonado) abonado, idperiodoremuneracion from
											(select
											id,
											case when idggcc is null then 0 else 1 end as autorizacion,
											abonado,
											idperiodoremuneracion
											from gc_cuenta where idcomunidad = ' . $this->session->userdata('comunidadid') . ' and idperiodoremuneracion is not null
												and idtipodeudadetalle not in (53,54)
											) t
											group by idperiodoremuneracion
											) t2
											where autoriza = 0 and abonado = 0
										)
										and pr.aprueba is not null');


        return $query->result();
    }


    public function get_tabla_impuesto()
    {

        $this->db->select('id, desde, hasta, factor, rebaja, tasa_maxima')
            ->from('gc_tabla_impuesto')
            ->order_by('desde', 'asc');

        $query = $this->db->get();
        return $query->result();
    }


    public function get_tabla_correccion_monetaria($anno)
    {

        $this->db->select('id, anno, mes_orig, dic')
            ->from('gc_tabla_correccion_monetaria')
            ->where('anno',$anno)
            ->order_by('anno', 'asc')
            ->order_by('mes_orig', 'asc');

        $query = $this->db->get();
        return $query->result();
    }    


    public function get_tabla_asig_familiar($idtramo = null)
    {

        $tramo_data = $this->db->select('id, tramo, desde, hasta, monto')
            ->from('gc_tabla_asig_familiar')
            ->order_by('desde', 'asc');
        $tramo_data = is_null($idtramo) ? $tramo_data : $tramo_data->where('id', $idtramo);
        $query = $this->db->get();
        return is_null($idtramo) ? $query->result() : $query->row();
        //return $query->result();
    }


    public function get_descuento($idperiodo, $tipo, $idpersonal = null)
    {

        $this->db->select('dp.monto, dp.descripcion, dp.tipodescuento, td.tipo')
            ->from('gc_descuentos_personal dp')
            ->join('gc_tipo_descuento td', 'dp.tipodescuento = td.id')
            ->join('gc_personal pe', 'dp.idpersonal = pe.id')
            ->where('td.tipo', $tipo)
            ->where('dp.idperiodo', $idperiodo)
            ->where('pe.id', $idpersonal)
            ->where('pe.idcomunidad', $this->session->userdata('comunidadid'));

        $query = $this->db->get();
        return $query->result();
    }

    public function calcular_remuneraciones($idperiodo)
    {

        $this->db->trans_start();

        $periodo =  $this->get_periodos($this->session->userdata('comunidadid'), $idperiodo);
        $this->load->model('admin');
        //$periodo = $this->admin->get_periodo_by_id($idperiodo);
        $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

        $tabla_impuesto = $this->get_tabla_impuesto();



        $parametros = $this->get_parametros_generales();
        $monto_total_sueldos = 0;
        $tope_legal_gratificacion = ($parametros->sueldominimo * 4.75) / 12;

        $this->load->model('account');

        $array_pago_afp = array();
        $array_pago_isapre = array();
        $array_descuentos = array();
        $array_prestamos = array();
        $dia_mes =  $periodo->mes == 2 ? 28 : 30;
        $suma_aporte_patronal = 0;
        $suma_asig_familiar = 0;
        $suma_ips = 0;
        $suma_impuesto = 0;
        $tope_imponible = (int)($parametros->uf * $parametros->topeimponible);
        $tope_imponible_ips = (int)($parametros->uf * $parametros->topeimponibleips);
        $tope_imponible_afc = (int)($parametros->uf * $parametros->topeimponibleafc);

        $this->db->query('update gc_remuneracion r
						  inner join gc_personal p on r.idpersonal = p.id
						  set r.active = 0
						  where p.idcomunidad = ' . $this->session->userdata('comunidadid') . ' and r.idperiodo = ' . $idperiodo);

        $personal = $this->get_personal();
        foreach ($personal as $trabajador) { // calculo de sueldos por cada trabajador
            $datos_remuneracion = $this->get_datos_remuneracion_by_periodo($idperiodo, $trabajador->id);
            $datos_bonos = $this->get_bonos($trabajador->id);
            $bonos_imponibles = 0;
            $bonos_no_imponibles = 0;

            $diastrabajo = $trabajador->parttime == 1 ? $trabajador->diastrabajo : 30;
            $sueldo_base_mes = round(($trabajador->sueldobase / $diastrabajo) * $datos_remuneracion->diastrabajo, 0);

            /*if($trabajador->id == 208){
						echo $diastrabaj." -- ".$trabajador->sueldobase." -- ".$datos_remuneracion->diastrabajo." -- ".$sueldo_base_mes; exit;
					}*/
            $movilizacion_mes = round(($trabajador->movilizacion / $diastrabajo) * $datos_remuneracion->diastrabajo, 0);
            $colacion_mes = round(($trabajador->colacion / $diastrabajo) * $datos_remuneracion->diastrabajo, 0);



            foreach ($datos_bonos as $bono) {
                $tiene_bono = false;
                if ($bono->fijo == 1) { // se suma siempre
                    $tiene_bono = true;
                } else { // validar si corresponde al período
                    $array_fecha_bono = explode("/", $bono->fecha);
                    $mes_bono = (int)$array_fecha_bono[1];
                    $anno_bono = $array_fecha_bono[2];
                    $tiene_bono = $mes_bono == $periodo->mes && $anno_bono == $periodo->anno ? true : false; // el bono corresponde al periodo que estamos calculando.  Entonces si aplica el bono
                }

                if ($tiene_bono) {

                    $valor_bono = $bono->proporcional == 1 ? round(($bono->monto / $diastrabajo) * $datos_remuneracion->diastrabajo, 0) : $bono->monto;
                    if ($bono->imponible == 1) {
                        $bonos_imponibles += $valor_bono;
                    } else {
                        $bonos_no_imponibles += $valor_bono;
                    }
                    $data_bono = array(
                        'idremuneracion' => $datos_remuneracion->id,
                        'descripcion' => $bono->descripcion,
                        'imponible' => $bono->imponible,
                        'monto' => $valor_bono
                    );
                    $this->db->insert('gc_bonos_remuneracion', $data_bono);
                }
            }

            $datos_afp = $this->get_afp($trabajador->idafp);
            //$valor_hora = $trabajador->parttime == 1 ? ((($trabajador->sueldobase + $trabajador->bonos_fijos)/$trabajador->diastrabajo)/$trabajador->horasdiarias) : ((($trabajador->sueldobase + $trabajador->bonos_fijos)/30)*7)/45;
            $valor_hora = $trabajador->parttime == 1 ? ((($trabajador->sueldobase) / $trabajador->diastrabajo) / $trabajador->horasdiarias) : ((($trabajador->sueldobase) / 30) * 7) / 45;
            $valor_hora = round($valor_hora, 0);
            //calculo total haberes
            $valor_hora50 =  round($valor_hora * 1.5, 0);
            $valor_hora100 = round($valor_hora * 2, 0);
            $monto_horas50 = $datos_remuneracion->horasextras50 * $valor_hora50;
            $monto_horas100 = $datos_remuneracion->horasextras100 * $valor_hora100;






            $porc_com_afp = $datos_afp->porc > 0 ? $datos_afp->porc - 10 : 0;
            $porc_cot_oblig = $datos_afp->exregimen == 2 ? 0 : 0.1;


            //$gratificacion = $trabajador->sueldobase*0.25;


            //Calculo asignación familiar
            $num_cargas_simples = $trabajador->cargassimples;
            $num_cargas_maternales = $trabajador->cargasmaternales;

            $num_cargas = $num_cargas_simples + $num_cargas_maternales;
            $monto_ingresos = $trabajador->sueldobase + $trabajador->bonos_fijos;

            $asig_familiar = $trabajador->asigfamiliar;


            $movimientos = $this->get_lista_movimientos($trabajador->id, null, $idperiodo, 3);

            $dias_licencia = 0;
            foreach ($movimientos as $movimiento) {
                
                $dias = dias_transcurridos($movimiento->fecmovimiento,$movimiento->fechastamovimiento) + 1; // se agrega uno porque se considera el día inicial
                $dias_licencia += $dias;
            }
            



            if (!is_null($trabajador->idasigfamiliar)) { //BUSCA MONTO DE ASIGNACION FAMILIAR EN BASE A TRAMO SELECCIONADO
                $tramo_asig_familiar = $this->get_tabla_asig_familiar($trabajador->idasigfamiliar);
                $asig_familiar += $tramo_asig_familiar->monto * $num_cargas;

                $dias_calculo_asig = $datos_remuneracion->diastrabajo + $dias_licencia;
                //https://www.dt.gob.cl/portal/1628/w3-article-95276.html
                if ($dias_calculo_asig < 25) {
                    $asig_familiar = round(($asig_familiar / 30) * $dias_calculo_asig, 0);
                }
            }


            /*$tramo_asig_familiar = $this->get_tabla_asig_familiar($trabajador->idasigfamiliar);
			foreach ($tabla_asig_familiar as $rango_asig_familiar) {

				if($monto_ingresos >= $rango_asig_familiar->desde && $monto_ingresos <= $rango_asig_familiar->hasta){

					$asig_familiar += $rango_asig_familiar->monto*$num_cargas;

					break;
				}
			}*/

            $suma_asig_familiar += $asig_familiar;


            #AGUINALDO INGRESADO EN LÍQUIDO.  SE NECESITA ALMACENAR EL BRUTO
            $aguinaldo_bruto = round($datos_remuneracion->aguinaldo * 1.25, 0);


            $gratificacion = 0;
            if ($trabajador->tipogratificacion == 'SG') {
                $gratificacion = 0;
            } else if ($trabajador->tipogratificacion == 'MF') {
                $gratificacion = $trabajador->gratificacion;
            } else if ($trabajador->tipogratificacion == 'TL') {
                $monto_calculo_gratificacion = $sueldo_base_mes +  $bonos_imponibles + $monto_horas50 + $monto_horas100;
                //$gratificacion_esperada = round($sueldo_base_mes/4,0);

                $gratificacion_esperada = round($monto_calculo_gratificacion / 4, 0);


                $gratificacion = $gratificacion_esperada > $tope_legal_gratificacion ? $tope_legal_gratificacion : $gratificacion_esperada;
            }


            $total_haberes = $sueldo_base_mes + $gratificacion + $movilizacion_mes + $colacion_mes + $bonos_imponibles + $bonos_no_imponibles + $monto_horas50 + $monto_horas100 + $aguinaldo_bruto + $asig_familiar;
            $sueldo_imponible = $sueldo_base_mes + $gratificacion + $bonos_imponibles + $monto_horas50 + $monto_horas100 + $aguinaldo_bruto;

            $sueldo_no_imponible = $total_haberes - $sueldo_imponible;



            #CALCULA SUELDO SOBRE EL CUAL SE CALCULARÁN LAS IMPOSICIONES, CONSIDERANDO EL TOPE LEGAL
            $sueldo_imponible_imposiciones = $sueldo_imponible > $tope_imponible ? $tope_imponible : $sueldo_imponible;
            $sueldo_imponible_afc = $sueldo_imponible > $tope_imponible_afc ? $tope_imponible_afc : $sueldo_imponible;
            $sueldo_imponible_ips = $sueldo_imponible > $tope_imponible_ips ? $tope_imponible_ips : $sueldo_imponible;


            $sueldo_imponible_afp = $datos_afp->exregimen == 1 ? $sueldo_imponible_ips : $sueldo_imponible_imposiciones;

            $cot_obligatoria = round($sueldo_imponible_afp * $porc_cot_oblig, 0);
            $comision_afp = round($sueldo_imponible_afp * ($porc_com_afp / 100), 0);
            $adic_afp = round($sueldo_imponible * ($trabajador->adicafp / 100), 0);


            // SOLO SE PAGA POR 11 AÑOS
            $segcesantia = $trabajador->tipocontrato == 'I' && $trabajador->segcesantia == 1 && $trabajador->annos_afc <= 11 ? round($sueldo_imponible_afc * 0.006, 0) : 0;


            $cot_salud_oblig = $trabajador->idisapre != 1 ? round($sueldo_imponible_imposiciones * 0.07, 0) : 0;

            if ($trabajador->idisapre == 1) { //FONASA
                $salud_total = round($sueldo_imponible_imposiciones * 0.07, 0);
                //$cot_fonasa = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones*0.064,0) : 0;
                //$cot_inp = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones*0.006,0) : 0;
                /*$cot_fonasa = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones * 0.039, 0) : 0;
                $cot_inp = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones * 0.031, 0) : 0;
                */

                //$cot_fonasa = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones * 0.0055, 0) : 0;
                //$cot_inp = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones * 0.0645, 0) : 0;
                
                $cot_fonasa = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones * PORCT_FONASA, 0) : 0;
                $cot_inp = $trabajador->idisapre == 1 ? round($sueldo_imponible_imposiciones * PORCT_INP, 0) : 0;
                

                $dif_salud = $salud_total - ($cot_fonasa + $cot_inp);
                $cot_fonasa += $dif_salud;
            } else {
                $cot_fonasa = 0;
                $cot_inp = 0;
            }




            if ($trabajador->idisapre == 1) {
                $adic_isapre = 0;
                $cot_adic_isapre = 0; // tributable
                $adic_salud = 0;
            } else {
                $dif_isapre = $datos_remuneracion->diastrabajo > 0 ? (round($trabajador->valorpactado * $parametros->uf, 0) - $cot_salud_oblig) : 0;
                $adic_isapre = $dif_isapre > 0 ? $dif_isapre : 0;

                if ($adic_isapre > 0) {
                    $tope_salud_tributable = round(($parametros->topeimponible * 0.07) * $parametros->uf, 0);
                    $sobre_tope = ($cot_salud_oblig + $adic_isapre) - $tope_salud_tributable;
                    if ($sobre_tope > 0) { // nos pasamos del tope
                        $cot_adic_isapre = $adic_isapre - $sobre_tope; // tributable
                        $adic_salud = $sobre_tope;
                    } else {
                        $cot_adic_isapre = 0; // tributable
                        $adic_salud = $adic_isapre;
                    }
                } else {
                    $cot_adic_isapre = 0; // tributable
                    $adic_salud = 0;
                }
            }

            $ahorrovol = 0;
            if ($trabajador->tipoahorrovol == 'pesos') {
                $ahorrovol = $trabajador->ahorrovol;
            } else if ($trabajador->tipoahorrovol == 'porcentaje') {
                $ahorrovol = round($sueldo_imponible * ($trabajador->ahorrovol / 100), 0);
            }

            $cotapv = 0;
            //echo $trabajador->cotapv." - ". $parametros->uf . " -  ". $trabajador->tipocotapv."<br>";
            //print_r($parametros);
            //echo $parametros->uf; exit;
            if ($trabajador->tipocotapv == 'pesos') {
                $cotapv = $trabajador->cotapv;
            } else if ($trabajador->tipocotapv == 'porcentaje') {
                $cotapv = round($sueldo_imponible * ($trabajador->cotapv / 100), 0);
            } else if ($trabajador->tipocotapv == 'uf') {
                $cotapv = round($trabajador->cotapv * $parametros->uf, 0);
            }


            $descuentos = round($valor_hora * $datos_remuneracion->horasdescuento, 0);




            $base_tributaria = $sueldo_imponible - $cot_obligatoria - $comision_afp - $adic_afp - $segcesantia - $cot_salud_oblig - $cot_adic_isapre - $cot_fonasa - $cot_inp;

            $impuesto = 0;
            foreach ($tabla_impuesto as $rango) {
                //echo $base_tributaria." - ".$rango->desde." - ".$rango->hasta." - ".$rebaja."<br>";
                $rango_desde = round(($rango->desde / $diastrabajo) * $datos_remuneracion->diastrabajo, 0);
                $rango_hasta = round(($rango->hasta / $diastrabajo) * $datos_remuneracion->diastrabajo, 0);
                $rango_rebaja = round(($rango->rebaja / $diastrabajo) * $datos_remuneracion->diastrabajo, 0);
                //if($base_tributaria >= $rango->desde && $base_tributaria <= $rango->hasta){
                if ($base_tributaria >= $rango_desde && $base_tributaria <= $rango_hasta) {

                    //$impuesto = round($base_tributaria*$rango->factor - $rango->rebaja,0);
                    $impuesto = round($base_tributaria * $rango->factor - $rango_rebaja, 0);

                    break;
                }
            }

            //exit;


            $datos_descuentos = $this->get_descuento($idperiodo, 'D', $trabajador->id);
            $monto_descuento = 0;
            foreach ($datos_descuentos as $info_descuento) {
                $monto_descuento += $info_descuento->monto;
                if (!array_key_exists($info_descuento->tipodescuento, $array_descuentos)) {
                    $array_descuentos[$info_descuento->tipodescuento] = 0;
                }
                $array_descuentos[$info_descuento->tipodescuento] += $info_descuento->monto; // suma montos por tipo de descuento
            }


            $datos_prestamos = $this->get_descuento($idperiodo, 'P', $trabajador->id);
            $monto_prestamos = 0;
            foreach ($datos_prestamos as $info_prestamos) {
                $monto_prestamos += $info_prestamos->monto;
                if (!array_key_exists($info_prestamos->tipodescuento, $array_prestamos)) {
                    $array_prestamos[$info_prestamos->tipodescuento] = 0;
                }
                $array_prestamos[$info_prestamos->tipodescuento] += $info_prestamos->monto; // suma montos por tipo de descuento
            }



            $total_descuentos = $cot_obligatoria + $comision_afp + $adic_afp + $segcesantia + $cot_salud_oblig + $cot_fonasa + $cot_inp + $adic_isapre + $impuesto + $ahorrovol + $cotapv + $datos_remuneracion->anticipo + $descuentos + $monto_descuento + $monto_prestamos + $datos_remuneracion->aguinaldo;
            $total_leyes_sociales = $cot_obligatoria + $comision_afp + $adic_afp + $segcesantia + $cot_salud_oblig + $cot_fonasa + $cot_inp + $adic_isapre + $impuesto + $ahorrovol + $cotapv;
            $otros_descuentos = $total_descuentos - $total_leyes_sociales;

            $sueldo_liquido = $total_haberes - $total_descuentos;

            if ($trabajador->pensionado == 1) {
                $seginvalidez = 0;
            } else {
                if ($datos_remuneracion->diastrabajo < 30) {

                    //$sueldo_calculo_sis = $trabajador->sueldobase + $aguinaldo_bruto + $bonos_imponibles;

                    /*	if($trabajador->id == 294){
						echo $sueldo_calculo_sis; exit;
					}*/

                    $sueldo_calculo_sis = $sueldo_base_mes + $aguinaldo_bruto + $bonos_imponibles  + $monto_horas50 + $monto_horas100;

                    /*if($datos_remuneracion->idpersonal == 293){
						var_dump($trabajador->sueldobase);
						var_dump($aguinaldo_bruto);
						var_dump($bonos_imponibles);
						var_dump($sueldo_calculo_sis); exit;

					}*/
                } else {
                    $sueldo_calculo_sis = $sueldo_imponible_imposiciones;
                }

                $sueldo_calculo_sis = $sueldo_calculo_sis > $sueldo_imponible_afp ? $sueldo_imponible_afp : $sueldo_calculo_sis;
                $seginvalidez = round($sueldo_calculo_sis * ($parametros->tasasis / 100), 0);
            }

            /*if($trabajador->id == 208){
						echo $seginvalidez; exit;
					}*/



            #$seginvalidez = $trabajador->pensionado == 1 ? 0 : round($sueldo_imponible*($parametros->tasasis/100),0);
            #SI TRABAJADOR TIENE LICENCIA MEDIDA, ENTONCES SE CALCULA POR SUELDO IMPONIBLE PROPORCIONAL A DIAS TRABAJADOS
            #Y POR DIAS NO TRABAJADOS, EL PROPORCIONAL AL SUELDO IMPONIBLE ANTEIOR.  SI NO EXISTE, EN BASE AL CONTRATO

            #1.- VERIFICAR SI TIENE LICENCIA EN EL PERÍODO
            $movimientos = $this->get_lista_movimientos($trabajador->id, null, $idperiodo, 3);
            $tiene_licencia = count($movimientos) > 0 ? true : false;




            //ocupo esta query para sacar el ultimo sueldo imponible, sino tomar suedo base según contrato.
            /*select r.sueldoimponible from gc_remuneracion r
inner join gc_periodo p on r.idperiodo = p.id
where idpersonal = 41 and diastrabajo > 0
order by p.anno desc, p.mes desc
limit 1		*/
            $aportesegcesantia = 0;
            if ($trabajador->segcesantia == 1) {
                if ($trabajador->annos_afc <= 11) {
                    $aportesegcesantia = $trabajador->tipocontrato == 'F' ? round($sueldo_imponible * 0.03, 0) : round($sueldo_imponible * 0.024, 0);
                } else {
                    $aportesegcesantia = $trabajador->tipocontrato == 'F' ? round($sueldo_imponible * 0.002, 0) : round($sueldo_imponible * 0.008, 0);
                }
            } else {
                $aportesegcesantia = 0;
            }
            //echo $aportesegcesantia; exit;


            if ($tiene_licencia && $datos_remuneracion->diastrabajo < 30) { // SI TIENE LICENCIA SE DEBE SUMAR AL SEGURO LOS DÍAS NO TRABAJADOS POR EL PROPORCIONAL
                $imponibles_no_trabajo = round((($trabajador->sueldobase + $aguinaldo_bruto + $bonos_imponibles + $gratificacion) / $diastrabajo) * ($diastrabajo - $datos_remuneracion->diastrabajo), 0);

                if ($trabajador->segcesantia == 1) {
                    if ($trabajador->annos_afc <= 11) {

                        $aportesegcesantia += $trabajador->tipocontrato == 'F' ? round($imponibles_no_trabajo * 0.03, 0) : round($imponibles_no_trabajo * 0.024, 0);
                    } else {
                        $aportesegcesantia += $trabajador->tipocontrato == 'F' ? round($imponibles_no_trabajo * 0.002, 0) : round($imponibles_no_trabajo * 0.008, 0);
                    }
                } else {
                    $aportesegcesantia = 0;
                }


                $seginvalidez += round($imponibles_no_trabajo * ($parametros->tasasis / 100), 0);
            }


            /*if($trabajador->id == 208){
						echo $seginvalidez; exit;
					}*/

            $aportepatronal = is_null($comunidad->idmutual) ? 0 : round($sueldo_imponible_afp * ($comunidad->porcmutual / 100), 0);
            $suma_aporte_patronal += $aportepatronal;
            $suma_impuesto += $impuesto;

            $data_remuneracion = array(
                'ufperiodo' => $parametros->uf,
                'sueldobase' => $sueldo_base_mes,
                'valorhora' => $valor_hora,
                'montodescuento' => $descuentos,
                'tipogratificacion' => $trabajador->tipogratificacion,
                'gratificacion' => $gratificacion,
                'movilizacion' => $movilizacion_mes,
                'colacion' => $colacion_mes,
                'bonosimponibles' => $bonos_imponibles,
                'bonosnoimponibles' => $bonos_no_imponibles,
                'valorhorasextras50' => $valor_hora50,
                'montohorasextras50' => $monto_horas50,
                'valorhorasextras100' => $valor_hora100,
                'montohorasextras100' => $monto_horas100,
                'aguinaldobruto' => $aguinaldo_bruto,
                'cargasretroactivas' => $trabajador->cargasretroactivas,
                'montocargaretroactiva' => $trabajador->asigfamiliar,
                'asigfamiliar' => $asig_familiar,
                'totalhaberes' => $total_haberes,
                'sueldoimponible' => $sueldo_imponible,
                'sueldonoimponible' => $sueldo_no_imponible,
                'sueldoimponibleimposiciones' => $sueldo_imponible_imposiciones,
                'sueldoimponibleafc' => $sueldo_imponible_afc,
                'sueldoimponibleips' => $sueldo_imponible_ips,
                'cotizacionobligatoria' => $cot_obligatoria,
                'comisionafp' => $comision_afp,
                'porccomafp' => $porc_com_afp,
                'porcadicafp' => $trabajador->adicafp,
                'adicafp' => $adic_afp,
                'segcesantia' => $segcesantia,
                'cotizacionsalud' => $cot_salud_oblig,
                'fonasa' => $cot_fonasa,
                'inp' => $cot_inp,
                'valorpactado' => $trabajador->valorpactado,
                'adicisapre' => $adic_isapre,
                'cotadicisapre' => $cot_adic_isapre,
                'adicsalud' => $adic_salud,
                'basetributaria' => $base_tributaria,
                'impuesto' => $impuesto,
                'tipoahorrovol' => $trabajador->tipoahorrovol,
                'ahorrovol' => $trabajador->ahorrovol,
                'montoahorrovol' => $ahorrovol,
                'tipocotapv' => $trabajador->tipocotapv,
                'cotapv' => $trabajador->cotapv,
                'montocotapv' => $cotapv,
                'descuentos' => $monto_descuento,
                'prestamos' => $monto_prestamos,
                'totalleyessociales' => $total_leyes_sociales,
                'otrosdescuentos' => $otros_descuentos,
                'totaldescuentos' => $total_descuentos,
                'sueldoliquido' => $sueldo_liquido,
                'seginvalidez' => $seginvalidez,
                'aportesegcesantia' => $aportesegcesantia,
                'aportepatronal' => $aportepatronal,
                'pdf_content' => null,
                'active' => 1
            );
            $this->db->where('idpersonal', $datos_remuneracion->idpersonal);
            $this->db->where('idperiodo', $datos_remuneracion->idperiodo);
            $this->db->update('gc_remuneracion', $data_remuneracion);

            /*if($trabajador->id == 294){
			echo $this->db->last_query(); exit;
		};*/
            // VUELVE A CERO LA ASIGNACION FAMILIAR POR CARGAS RETROACTIVAS
            $this->db->where('id', $trabajador->id);
            $this->db->update('gc_personal', array(
                'asigfamiliar' => 0,
                'cargasretroactivas' => 0
            ));


            // AGREGA CUENTA CON SUELDO LIQUIDO
            //$cuenta_sueldo = $sueldo_liquido - $datos_remuneracion->aguinaldo;
            $cuenta_sueldo = $sueldo_liquido;

            $datos_cuenta = array(
                'formapago' => 'gc',
                'nombreproveedor' => $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno,
                'documento' =>  date("Ym") . $trabajador->id,
                'tipodoc' =>  $cuenta_sueldo >= 0 ? 8 : 4, //SI ES NEGATIVO ES NOTA DE CRÉDITO
                'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                'concepto' =>  52, //revisar
                'descripcion' => "Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                'monto' => abs($cuenta_sueldo),
                'idperiodo' => $idperiodo
            );
            $this->account->add_cuenta_remuneracion($datos_cuenta);



            if (is_null($periodo->anticipo)) {  #SOLO SE CREAN LAS CUENTAS SI NO SE TRASPASARON DATOS
                if ($datos_remuneracion->anticipo > 0) { // AGREGA CUENTA POR ANTICIPO
                    $datos_cuenta = array(
                        'formapago' => 'gc',
                        'nombreproveedor' => $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno,
                        'documento' =>  date("Ym") . $trabajador->id,
                        'tipodoc' =>  9,
                        'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-15",
                        'concepto' =>  53, //revisar
                        'descripcion' => "Anticipo Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                        'monto' => $datos_remuneracion->anticipo,
                        'idperiodo' => $idperiodo
                    );
                    $this->account->add_cuenta_remuneracion($datos_cuenta);
                }

                if ($datos_remuneracion->aguinaldo > 0) {

                    $datos_cuenta = array(
                        'formapago' => 'gc',
                        'nombreproveedor' => $trabajador->nombre . " " . $trabajador->apaterno . " " . $trabajador->amaterno,
                        'documento' =>  date("Ym") . $trabajador->id,
                        'tipodoc' =>  10,
                        'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                        'concepto' =>  54, //revisar
                        'descripcion' => "Aguinaldo Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                        'monto' => $datos_remuneracion->aguinaldo,
                        'idperiodo' => $idperiodo
                    );
                    $this->account->add_cuenta_remuneracion($datos_cuenta);
                }
            }

            //calculamos los montos detinados a afp

            if ($datos_afp->exregimen != 2) { // omitimos No Cotiza y Pensionado
                $monto_afp = $cot_obligatoria + $comision_afp;
                if (!array_key_exists($trabajador->idafp, $array_pago_afp)) {
                    $array_pago_afp[$trabajador->idafp]['monto_afp'] = 0;
                    $array_pago_afp[$trabajador->idafp]['monto_sis'] = 0;
                    $array_pago_afp[$trabajador->idafp]['monto_afc'] = 0;
                    $array_pago_afp[$trabajador->idafp]['nombre'] = $datos_afp->nombre;
                }

                $array_pago_afp[$trabajador->idafp]['monto_afp'] += $monto_afp;
                $array_pago_afp[$trabajador->idafp]['monto_sis'] += $seginvalidez;
                $array_pago_afp[$trabajador->idafp]['monto_afc'] += $aportesegcesantia + $segcesantia; //SE SUMA APORTE EMPRESA + APORTE EMPLEADOR
            }


            // calculamos montos destinados a isapre y ips
            if ($trabajador->idisapre == 1) {
                $suma_ips += $cot_fonasa + $cot_inp;
            } else {
                if (!array_key_exists($trabajador->idisapre, $array_pago_isapre)) {
                    $datos_isapre = $this->get_isapre($trabajador->idisapre);

                    $array_pago_isapre[$trabajador->idisapre]['monto'] = 0;
                    $array_pago_isapre[$trabajador->idisapre]['nombre'] = $datos_isapre->nombre;
                }

                $array_pago_isapre[$trabajador->idisapre]['monto'] += $cot_salud_oblig + $adic_isapre;
            }

            // CALCULA TOTAL A PAGAR POR CONDOMINIO.  LA SUMA PASARÁ A GGCC
            $monto_total_sueldos += $sueldo_imponible;
        }


        // AGREGAR DESCUENTOS A GASTO COMUN
        foreach ($array_descuentos as $idtipodescuento => $monto_otros_descuentos) {

            $tipo_descuento = $this->get_tipo_descuento($idtipodescuento);

            $datos_cuenta = array(
                'formapago' => 'gc',
                'nombreproveedor' => "Otros Descuentos",
                'documento' =>  date("Ym") . $idtipodescuento,
                'tipodoc' =>  12,
                'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                'concepto' =>  80, //revisar
                'descripcion' => "Otros Descuentos " . date2string($periodo->mes, $periodo->anno),
                'monto' => $monto_otros_descuentos,
                'idperiodo' => $idperiodo
            );
            $this->account->add_cuenta_remuneracion($datos_cuenta);
        }


        // AGREGAR PRESTAMOS A GASTO COMUN
        foreach ($array_prestamos as $idtipodescuento => $monto_descto_prestamos) {

            $tipo_descuento = $this->get_tipo_descuento($idtipodescuento);

            $datos_cuenta = array(
                'formapago' => 'gc',
                'nombreproveedor' => "Prestamos " . $tipo_descuento->nombre,
                'documento' =>  date("Ym") . $idtipodescuento,
                'tipodoc' =>  12,
                'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                'concepto' =>  80, //revisar
                'descripcion' => "Prestamos " . $tipo_descuento->nombre . " " . date2string($periodo->mes, $periodo->anno),
                'monto' => $monto_descto_prestamos,
                'idperiodo' => $idperiodo
            );
            $this->account->add_cuenta_remuneracion($datos_cuenta);
        }




        foreach ($array_pago_afp as $idafp => $pagoafp) {

            if ($pagoafp['monto_afp'] > 0) {
                $datos_cuenta = array(
                    'formapago' => 'gc',
                    'nombreproveedor' => "AFP " . $pagoafp['nombre'],
                    'documento' =>  date("Ym") . $idafp,
                    'tipodoc' =>  11,
                    'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                    'concepto' =>  55, //revisar
                    'descripcion' => "Pagos Previsionales AFP Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                    'monto' => $pagoafp['monto_afp'],
                    'idperiodo' => $idperiodo
                );
                $this->account->add_cuenta_remuneracion($datos_cuenta);
            }

            if ($pagoafp['monto_afc'] > 0) {
                $datos_cuenta = array(
                    'formapago' => 'gc',
                    'nombreproveedor' => "AFP " . $pagoafp['nombre'] . " (AFC)",
                    'documento' =>  date("Ym"),
                    'tipodoc' =>  11,
                    'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                    'concepto' =>  55, //revisar
                    'descripcion' => "Pagos Previsionales Seguro de Cesant&iacute;a Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                    'monto' => $pagoafp['monto_afc'],
                    'idperiodo' => $idperiodo
                );
                $this->account->add_cuenta_remuneracion($datos_cuenta);
            }

            if ($pagoafp['monto_sis'] > 0) {
                $datos_cuenta = array(
                    'formapago' => 'gc',
                    'nombreproveedor' => "AFP " . $pagoafp['nombre'] . " (SIS)",
                    'documento' =>  date("Ym"),
                    'tipodoc' =>  11,
                    'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                    'concepto' =>  55, //revisar
                    'descripcion' => "Pagos Previsionales Seguro de Invalidez y Sobrevivencia Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                    'monto' => $pagoafp['monto_sis'],
                    'idperiodo' => $idperiodo
                );
                $this->account->add_cuenta_remuneracion($datos_cuenta);
            }
        }



        foreach ($array_pago_isapre as $idisapre => $pagoisapre) {

            if ($pagoisapre['monto'] > 0) {
                $datos_cuenta = array(
                    'formapago' => 'gc',
                    'nombreproveedor' => "Isapre " . $pagoisapre['nombre'],
                    'documento' =>  date("Ym") . $idisapre,
                    'tipodoc' =>  11,
                    'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                    'concepto' =>  55, //revisar
                    'descripcion' => "Pagos Previsionales Isapre Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                    'monto' => $pagoisapre['monto'],
                    'idperiodo' => $idperiodo
                );
                $this->account->add_cuenta_remuneracion($datos_cuenta);
            }
        }

        $cargo_ips = $suma_ips - $suma_asig_familiar;

        if ($cargo_ips > 0) {

            $datos_cuenta = array(
                'formapago' => 'gc',
                'nombreproveedor' => 'IPS',
                'documento' =>  date("Ym"),
                'tipodoc' =>  11,
                'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                'concepto' =>  55, //revisar
                'descripcion' => "Pagos Previsionales IPS Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                'monto' => $cargo_ips,
                'idperiodo' => $idperiodo
            );
            $this->account->add_cuenta_remuneracion($datos_cuenta);
        }

        if ($suma_aporte_patronal > 0) {

            $datos_cuenta = array(
                'formapago' => 'gc',
                'nombreproveedor' => 'Mutual de Seguridad',
                'documento' =>  date("Ym"),
                'tipodoc' =>  11,
                'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                'concepto' =>  55, //revisar
                'descripcion' => "Pagos Previsionales Mutual de Seguridad Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                'monto' => $suma_aporte_patronal,
                'idperiodo' => $idperiodo
            );
            $this->account->add_cuenta_remuneracion($datos_cuenta);
        }



        if ($suma_impuesto > 0) {

            $datos_cuenta = array(
                'formapago' => 'gc',
                'nombreproveedor' => 'Impuesto Segunda Categoría',
                'documento' =>  date("Ym"),
                'tipodoc' =>  14,
                'fecdocumento' => $periodo->anno . "-" . str_pad($periodo->mes, 2, "0", STR_PAD_LEFT) . "-" . $dia_mes,
                'concepto' =>  123, //revisar
                'descripcion' => "Pago Impuesto Remuneraciones " . date2string($periodo->mes, $periodo->anno),
                'monto' => $suma_impuesto,
                'idperiodo' => $idperiodo
            );
            $this->account->add_cuenta_remuneracion($datos_cuenta);
        }

        /*$cargo_ips = $suma_seg_invalidez - $suma_asig_familiar;
   		if($cargo_ips > 0){

	       		$datos_cuenta = array(
       						'formapago' => 'gc',
       						'nombreproveedor' => 'IPS',
       						'documento' =>  date("Ym"),
       						'tipodoc' =>  11,
       						'fecdocumento' => $periodo->anno."-".str_pad($periodo->mes,2,"0",STR_PAD_LEFT)."-".$dia_mes,
       						'concepto' =>  55, //revisar
       						'descripcion' => "Pagos Previsionales IPS Remuneraciones " .date2string($periodo->mes,$periodo->anno),
       						'monto' => $cargo_ips
			       			);
       		$this->account->add_cuenta_remuneracion($datos_cuenta);
   		}	*/


        /*if($suma_seg_cesantia > 0){

	       		$datos_cuenta = array(
       						'formapago' => 'gc',
       						'nombreproveedor' => 'AFC Chile',
       						'documento' =>  date("Ym"),
       						'tipodoc' =>  11,
       						'fecdocumento' => $periodo->anno."-".str_pad($periodo->mes,2,"0",STR_PAD_LEFT)."-".$dia_mes,
       						'concepto' =>  55, //revisar
       						'descripcion' => "Pagos Previsionales Seguro de Cesant&iacute;a Remuneraciones " .date2string($periodo->mes,$periodo->anno),
       						'monto' => $suma_seg_cesantia
			       			);
       		$this->account->add_cuenta_remuneracion($datos_cuenta);
   		}*/

        /*if($monto_total_sueldos > 0){ // AGREGAR CUENTA EN GGCC

       		$parametros = array(
       						'idcargo' => 0,
       						'nombreproveedor' => "Remuneraciones " .date2string($periodo->mes,$periodo->anno),
       						'fecpago' => date("Y-m-d"),
       						'monto' => $monto_total_sueldos,
       						'descripcion' => "Remuneraciones " .date2string($periodo->mes,$periodo->anno),
       						'nombrearchivo' => '',
       						'nombrerealarchivo' => ''
			       			);

       		$this->load->model('account');
			$this->account->add_otros_cargos($parametros);



		}*/

        // CERRAR PERIODO
        $this->db->where('idperiodo', $idperiodo);
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_periodo_remuneracion', array('cierre' => date("Y-m-d H:i:s")));

        $this->db->trans_complete();
        return 1;
    }

    public function aprobar_remuneracion($idperiodo)
    {

        $this->db->where('idperiodo', $idperiodo);
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_periodo_remuneracion', array('aprueba' => date("Y-m-d H:i:s")));
        return 1;
    }


    public function reversa_anticipo($mes, $anno)
    {


        $this->db->trans_start();
        #es necesario ver que el periodo tenga anticipo calculado
        $estado_remuneraciones = $this->get_estado_periodo($mes, $anno);

        $this->load->model('admin');
        $datos_periodo = $this->admin->get_periodo_by_mes($mes, $anno);
        $idperiodo = $datos_periodo->id;


        if ($estado_remuneraciones == 3) { // SÓLO REALIZA REVERSA EN CASO DE QUE EL PERÍODO CORRESPONDA

            #borramos todas las cuentas generadas
            $this->db->select('id, abonado, idggcc')
                ->from('gc_cuenta')
                ->where('idcomunidad', $this->session->userdata('comunidadid'))
                ->where('idperiodoremuneracion', $idperiodo)
                ->where('idtipodeudadetalle in (53,54)');

            $query = $this->db->get();
            $cuentas_remuneracion = $query->result();

            $this->load->model('account');

            foreach ($cuentas_remuneracion as $cuenta) {
                if ($cuenta->abonado > 0) {
                    return 2;
                }


                if (!is_null($cuenta->idggcc)) {
                    return 4;
                }
            }


            foreach ($cuentas_remuneracion as $cuenta) {
                $this->account->delete_cuenta_remuneraciones($cuenta->id);
            }

            #quitamos la marca de remuneracion calculada (permite volver a calcular)
            $this->db->where('idperiodo', $idperiodo);
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_periodo_remuneracion', array('anticipo' => null));
        } else {
            return 3;
        }


        $this->db->trans_complete();

        return 1;
    }



    public function reversar_aprobacion_remuneracion($idperiodo)
    {

        $this->db->trans_start();
        #obtengo remuneraciones del periodo para la comunidad (me aseguro que sea un periodo ya calculado y no aprobado)
        $remuneraciones = $this->get_periodos_remuneracion_cerrados_sin_abonos();


        if (count($remuneraciones) > 0) { // SÓLO REALIZA REVERSA EN CASO DE QUE EL PERÍODO CORRESPONDA

            foreach ($remuneraciones as $remuneracion) {

                if ($remuneracion->id == $idperiodo) {
                    $this->db->where('idperiodo', $idperiodo);
                    $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                    $this->db->update('gc_periodo_remuneracion', array('aprueba' => null));
                }
            }
        }

        $this->db->trans_complete();

        return 1;
    }

    public function rechazar_remuneracion($idperiodo)
    {


        $this->db->trans_start();
        #obtengo remuneraciones del periodo para la comunidad (me aseguro que sea un periodo ya calculado y no aprobado)
        $remuneraciones = $this->get_remuneraciones_reversa($idperiodo);


        if (count($remuneraciones) > 0) { // SÓLO REALIZA REVERSA EN CASO DE QUE EL PERÍODO CORRESPONDA

            foreach ($remuneraciones as $remuneracion) {
                #elimino los bonos cargados a la remuneracion
                $this->db->delete('gc_bonos_remuneracion', array('idremuneracion' => $remuneracion->id));

                #devuelvo los valores de las cargas retroactivas
                $this->db->query("update gc_personal p
								  inner join gc_remuneracion r on p.id = r.idpersonal
								  set p.asigfamiliar = r.montocargaretroactiva,
								  p.cargasretroactivas = r.cargasretroactivas
								  where r.id = " . $remuneracion->id);
            }

            #borramos todas las cuentas generadas

            $periodo_data =  $this->get_periodos($this->session->userdata('comunidadid'), $idperiodo); #buscamos el periodo para saber si debemos eliminar las cuentas de anticipo y aguinaldo

            $cuentas_data = $this->db->select('id')
                ->from('gc_cuenta')
                ->where('idcomunidad', $this->session->userdata('comunidadid'))
                ->where('idperiodoremuneracion', $idperiodo);

            #SI NO SE TRASPASARON DATOS DE ANTICIPO,
            $cuentas_data = is_null($periodo_data->anticipo) ? $cuentas_data : $cuentas_data->where('idtipodeudadetalle not in (53,54)');

            $query = $this->db->get();
            $cuentas_remuneracion = $query->result();
            $this->load->model('account');
            foreach ($cuentas_remuneracion as $cuenta) {
                $this->account->delete_cuenta_remuneraciones($cuenta->id);
            }


            /*$this->db->where('idcomunidad',$this->session->userdata('comunidadid'));
			$this->db->where('idperiodoremuneracion',$idperiodo);
			$this->db->delete('gc_cuenta');*/


            #quitamos la marca de remuneracion calculada (permite volver a calcular)
            $this->db->where('idperiodo', $idperiodo);
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_periodo_remuneracion', array('cierre' => null));
        }


        $this->db->trans_complete();

        return 1;
    }

    public function get_periodos($comunidadid, $idperiodo = null)
    {

        $periodo_data = $this->db->select('p.id, p.mes, p.anno, pr.anticipo, pr.cierre, pr.aprueba, date_format(pr.cierre,"%d/%m/%Y") as cierre,  (select count(*) from gc_remuneracion r inner join gc_personal pe on r.idpersonal = pe.id where r.idperiodo = p.id and pe.idcomunidad = ' . $comunidadid . ' and r.active = 1) as numtrabajadores, (select sum(sueldoimponible) from gc_remuneracion r inner join gc_personal pe on r.idpersonal = pe.id where r.idperiodo = p.id and pe.idcomunidad = ' . $comunidadid . ' and r.active = 1) as sueldoimponible ', false)
            ->from('gc_periodo as p')
            ->join('gc_periodo_remuneracion as pr', 'p.id = pr.idperiodo')
            ->where('pr.idcomunidad', $comunidadid)
            ->order_by('p.updated_at desc');
        $comunidades_data = is_null($idperiodo) ? $periodo_data : $periodo_data->where('pr.idperiodo', $idperiodo);
        $query = $this->db->get();
        $datos = is_null($idperiodo) ? $query->result() : $query->row();
        return $datos;
    }

    public function get_periodos_cerrados($comunidadid, $idperiodo = null)
    {

        $periodo_data = $this->db->select('p.id, p.mes, p.anno, pr.cierre, pr.aprueba, date_format(pr.cierre,"%d/%m/%Y") as cierre,  (select count(*) from gc_remuneracion r inner join gc_personal pe on r.idpersonal = pe.id where r.idperiodo = p.id and pe.idcomunidad = ' . $comunidadid . ' and r.active = 1) as numtrabajadores, (select sum(sueldoimponible) from gc_remuneracion r inner join gc_personal pe on r.idpersonal = pe.id where r.idperiodo = p.id and pe.idcomunidad = ' . $comunidadid . ' and r.active = 1) as sueldoimponible, (select sum(sueldoliquido) from gc_remuneracion r inner join gc_personal pe on r.idpersonal = pe.id where r.idperiodo = p.id and pe.idcomunidad = ' . $comunidadid . ' and r.active = 1) as sueldoliquido ', false)
            ->from('gc_periodo as p')
            ->join('gc_periodo_remuneracion as pr', 'p.id = pr.idperiodo')
            ->where('pr.idcomunidad', $comunidadid)
            ->where('pr.cierre is not null')
            ->order_by('p.updated_at desc');
        $comunidades_data = is_null($idperiodo) ? $periodo_data : $periodo_data->where('pr.idperiodo', $idperiodo);
        $query = $this->db->get();
        $datos = is_null($idperiodo) ? $query->result() : $query->row();
        return $datos;
    }


     public function get_trabajador_by_userid($userid)
    {

        $periodo_data = $this->db->select('p.id', false)
            ->from('gc_personal as p')
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('p.iduser', $userid);
        $query = $this->db->get();
        $datos = $query->result();
        return $datos;
    }


    public function get_periodos_cerrados_personal($idtrabajador,$comunidadid)
    {
        $periodo_data = $this->db->select('p.id, 
                                            p.mes, 
                                            p.anno, 
                                            pr.cierre, 
                                            pr.aprueba, 
                                            r.sueldoimponible,
                                            r.sueldoliquido,
                                            r.id as idremuneracion,
                                            date_format(pr.cierre, "%d/%m/%Y") as cierre', false)
            ->from('gc_periodo as p')
            ->join('gc_periodo_remuneracion as pr', 'p.id = pr.idperiodo')
            ->join('gc_remuneracion as r', 'pr.idperiodo = r.idperiodo AND r.idpersonal = ' . $idtrabajador)
            ->where('pr.idcomunidad', $comunidadid)
            ->where('pr.cierre is not null')
            ->where('pr.aprueba is not null')
            ->order_by('p.updated_at desc');
        $query = $this->db->get();
        $datos = $query->result();
        return $datos;
    }

    public function get_remuneraciones_by_periodo($idperiodo, $sinsueldo = null)
    {
        //$periodo_data = $this->db->select('r.id, pe.id as idtrabajador, pe.nombre, pe.apaterno, pe.amaterno, r.sueldobase, r.totalhaberes, r.totaldescuentos, r.sueldoliquido')
        //$periodo_data = $this->db->select('r.id, pe.id as idtrabajador, p.mes, p.anno, pe.nombre, pe.apaterno, pe.amaterno, date_format(pe.fecingreso,"%d/%m/%Y") as fecingreso, pe.rut, pe.dv, i.nombre as prev_salud, pe.idisapre, pe.valorpactado, c.nombre as cargo, a.nombre as afp, a.porc, r.sueldobase, r.gratificacion, r.bonosimponibles, r.valorhorasextras50, r.montohorasextras50, r.valorhorasextras100, r.montohorasextras100, r.aguinaldo, r.diastrabajo, r.totalhaberes, r.totaldescuentos, r.sueldoliquido, r.horasextras50, r.horasextras100, r.horasdescuento, pe.cargassimples, pe.cargasinvalidas, pe.cargasmaternales, pe.cargasretroactivas, r.sueldoimponible, r.movilizacion, r.colacion, r.bonosnoimponibles, r.asigfamiliar, r.totalhaberes, r.cotizacionobligatoria, r.comisionafp, r.adicafp, r.segcesantia, r.cotizacionsalud, r.fonasa, r.inp, r.adicisapre, r.impuesto, r.montoahorrovol, r.montocotapv, r.anticipo, r.montodescuento, pr.cierre')
        $periodo_data = $this->db->select('r.id, r.idperiodo, pe.id as idtrabajador, p.mes, p.anno, pe.nombre, pe.apaterno, pe.amaterno, pe.sexo, pe.nacionalidad, date_format(pe.fecingreso,"%d/%m/%Y") as fecingreso, pe.rut, pe.dv, i.nombre as prev_salud, pe.idisapre, pe.valorpactado, c.nombre as cargo, a.id as idafp, a.nombre as afp, a.porc, r.sueldobase, r.gratificacion, r.bonosimponibles, r.valorhorasextras50, r.montohorasextras50, r.valorhorasextras100, r.montohorasextras100, r.aguinaldo, r.aguinaldobruto, r.diastrabajo, r.totalhaberes, r.totaldescuentos, r.sueldoliquido, r.horasextras50, r.horasextras100, r.horasdescuento, pe.cargassimples, pe.cargasinvalidas, pe.cargasmaternales, pe.cargasretroactivas, r.sueldoimponible, r.movilizacion, r.colacion, r.bonosnoimponibles, r.asigfamiliar, r.totalhaberes, r.cotizacionobligatoria, r.comisionafp, r.adicafp, r.segcesantia, r.cotizacionsalud, r.fonasa, r.inp, r.adicisapre, r.cotadicisapre, r.adicsalud, r.impuesto, r.montoahorrovol, r.montocotapv, r.anticipo, r.montodescuento, pr.cierre, r.sueldonoimponible, r.totalleyessociales, r.otrosdescuentos, r.montocargaretroactiva, r.seginvalidez, pe.idasigfamiliar, r.valorpactado as valorpactadoperiodo, ap.id as idapv, pe.nrocontratoapv, pe.formapagoapv, pe.depconvapv, co.idmutual, r.aportepatronal, co.idcaja, pe.segcesantia as afilsegcesantia, r.aportesegcesantia, r.sueldoimponibleimposiciones, r.sueldoimponibleafc, r.sueldoimponibleips, pe.idregion, pe.idcomuna, pe.parttime, a.codlre, i.codlre as codlreisapre, ccaf.codlre as codlrecaja, m.codprevired as codlremutual, f.tramo as tramo_asig_familiar')
            ->from('gc_periodo as p')
            ->join('gc_remuneracion as r', 'r.idperiodo = p.id')
            ->join('gc_personal as pe', 'pe.id = r.idpersonal')
            ->join('gc_comunidad as co', 'pe.idcomunidad = co.id')
            ->join('gc_periodo_remuneracion as pr', 'r.idperiodo = pr.idperiodo')
            ->join('gc_isapre as i', 'pe.idisapre = i.id')
            ->join('gc_cargos as c', 'pe.idcargo = c.id')
            ->join('gc_afp as a', 'pe.idafp = a.id')
            ->join('gc_apv as ap', 'pe.instapv = ap.id', 'left')
            ->join('gc_cajas_compensacion as ccaf', 'co.idcaja = ccaf.id', 'left')
            ->join('gc_mutual_seguridad as m', 'co.idmutual = m.id', 'left')
            ->join('gc_tabla_asig_familiar as f', 'pe.idasigfamiliar = f.id', 'left')
            ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('pr.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('r.idperiodo', $idperiodo)
            ->where('r.active = 1')
            //->where('r.sueldoliquido <> 0')  //valida que se haya creado sueldo
            ->order_by('pe.nombre asc');

        $periodo_data = is_null($sinsueldo) ? $periodo_data->where('r.sueldoliquido <> 0') : $periodo_data;
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $query->result();
    }



    public function get_remuneraciones_reversa($idperiodo)
    {
        $periodo_data = $this->db->select('r.id')
            ->from('gc_remuneracion as r')
            ->join('gc_personal as p', 'p.id = r.idpersonal')
            ->join('gc_periodo_remuneracion as pr', 'r.idperiodo = pr.idperiodo and pr.idcomunidad = ' . $this->session->userdata('comunidadid'))
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('r.idperiodo', $idperiodo)
            ->where('pr.cierre is not null')
            ->where('pr.aprueba is null')
            ->order_by('r.id asc');
        $query = $this->db->get();
        return $query->result();
    }



    public function get_remuneraciones_by_id($idremuneracion)
    {
        $periodo_data = $this->db->select('r.id, r.idperiodo, pe.id as idtrabajador, p.mes, p.anno, pe.nombre, pe.apaterno, pe.amaterno, date_format(pe.fecingreso,"%d/%m/%Y") as fecingreso, pe.rut, pe.dv, i.nombre as prev_salud, pe.idisapre, pe.valorpactado, c.nombre as cargo, a.nombre as afp, a.porc, r.sueldobase, r.gratificacion, r.bonosimponibles, r.valorhorasextras50, r.montohorasextras50, r.valorhorasextras100, r.montohorasextras100, r.aguinaldo, r.aguinaldobruto, r.diastrabajo, r.totalhaberes, r.totaldescuentos, r.sueldoliquido, r.horasextras50, r.horasextras100, r.horasdescuento, pe.cargassimples, pe.cargasinvalidas, pe.cargasmaternales, pe.cargasretroactivas, r.sueldoimponible, r.movilizacion, r.colacion, r.bonosnoimponibles, r.asigfamiliar, r.totalhaberes, r.cotizacionobligatoria, r.comisionafp, r.adicafp, r.segcesantia, r.cotizacionsalud, r.fonasa, r.inp, r.adicisapre, r.cotadicisapre, r.adicsalud, r.impuesto, r.montoahorrovol, r.montocotapv, r.anticipo, r.montodescuento, pr.cierre, r.sueldonoimponible, r.totalleyessociales, r.otrosdescuentos, r.descuentos, r.prestamos')
            ->from('gc_periodo as p')
            ->join('gc_remuneracion as r', 'r.idperiodo = p.id')
            ->join('gc_personal as pe', 'pe.id = r.idpersonal')
            ->join('gc_periodo_remuneracion as pr', 'r.idperiodo = pr.idperiodo and pr.idcomunidad = ' . $this->session->userdata('comunidadid'))
            ->join('gc_isapre as i', 'pe.idisapre = i.id')
            ->join('gc_cargos as c', 'pe.idcargo = c.id')
            ->join('gc_afp as a', 'pe.idafp = a.id')
            ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('r.id', $idremuneracion);
        $query = $this->db->get();
        return $query->row();
    }


    private function get_pdf_content($idremuneracion)
    {

        $this->db->select('pdf_content ')
            ->from('gc_remuneracion ')
            ->where('id', $idremuneracion);
        $query = $this->db->get();
        return $query->row();
    }


    public function generar_contenido_comprobante($datos_remuneracion)
    {

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



        $this->load->model('admin');
        $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

        $firma = $comunidad->firma == '' || is_null($comunidad->firma) ? '&nbsp;' : '<img src="uploads/firmas/' . $this->session->userdata('comunidadid') . '/' . $comunidad->firma . '" width="150px"> ';


        $monto_prevision = $datos_remuneracion->idisapre == 1 ? ' 7% ' : $datos_remuneracion->valorpactado . ' UF ';
        $html .= '
						<p><h4 class="header4">Liquidaci&oacute;n de Remuneraciones ' . date2string($datos_remuneracion->mes, $datos_remuneracion->anno) . '<!--br><br><img src="img/logo4_1_80p_color.png" width="100px"--></h4></p>
						<hr>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="100%" colspan="4"><p>Datos Trabajador</p></th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" ><b><i>Nombre:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->nombre . ' ' . $datos_remuneracion->apaterno . ' ' . $datos_remuneracion->amaterno . '</td>
						<td class="tdClass" ><b><i>Fecha Contrato:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->fecingreso . '</td>
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Rut:</i></b></td>
						<td class="tdClass" >' . number_format($datos_remuneracion->rut, 0, ".", ".") . '-' . $datos_remuneracion->dv . '</td>
						<td class="tdClass" ><b><i>Previsi&oacute;n Salud:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->prev_salud . ' ' . $monto_prevision . ' </td>
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Cargo:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->cargo . '</td>
						<td class="tdClass" ><b><i>AFP:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->afp . ' ' . $datos_remuneracion->porc . '% </td>
						</tr>
						</tbody>
						</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="100%" colspan="4"><p>Datos Complementarios</p></th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" ><b><i>Nro. d&iacute;as trabajados:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->diastrabajo . '</td>
						<td class="tdClass" ><b><i>Horas Extras 50%:</i></b></td>
						<td class="tdClass" >' . round($datos_remuneracion->horasextras50, 1) . ' </td>
						</tr>
						<tr>
						<td class="tdClass" ><b><i>Horas Descuento:</i></b></td>
						<td class="tdClass" >' . $datos_remuneracion->horasdescuento . ' </td>
						<td class="tdClass" ><b><i>Horas Extras 100%:</i></b></td>
						<td class="tdClass" >' . round($datos_remuneracion->horasextras100, 1) . ' </td>
						</tr>
						</tbody>
						</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="70%" ><p>Detalle Haberes</p></th>
						<th width="30%" ><p>&nbsp;</p></th>
						</tr>
						</thead>
						<tbody>';

        if ($datos_remuneracion->sueldobase > 0) {
            $html .= '<tr>
									<td class="tdClass" >Sueldo Base</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->sueldobase, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->gratificacion > 0) {
            $html .= '<tr>
									<td class="tdClass" >Gratificaci&oacute;n Legal</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->gratificacion, 0, ".", ".") . '</td>
									</tr>';
        }


        $datos_bonos_imponibles = $this->get_bonos_by_remuneracion($datos_remuneracion->id, true);

        foreach ($datos_bonos_imponibles as $bono_imponible) {

            $html .= '<tr>
									<td class="tdClass" >' . $bono_imponible->descripcion . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($bono_imponible->monto, 0, ".", ".") . '</td>
									</tr>';
        }

        /*if($datos_remuneracion->bonosimponibles > 0){
							$html .= '<tr>
									<td class="tdClass" >Bonos Imponibles</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->bonosimponibles,0,".",".") . '</td>
									</tr>';
						}*/

        if ($datos_remuneracion->montohorasextras50 > 0) {
            $html .= '<tr>
									<td class="tdClass" >Horas Extras (50%)</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montohorasextras50, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->montohorasextras100 > 0) {
            $html .= '<tr>
									<td class="tdClass" >Horas Extras (100%)</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montohorasextras100, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->aguinaldo > 0) {
            $html .= '<tr>
									<td class="tdClass" >Aguinaldo</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->aguinaldobruto, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->sueldoimponible > 0) {
            $html .= '<tr>
									<td class="tdClass" ><b>Total Imponible</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->sueldoimponible, 0, ".", ".") . '</b></td>
									</tr>';
        }

        $html .= '<tr>
						<td class="tdClass">&nbsp;</td>
						<td class="tdClass">&nbsp;</td>
						</tr>';

        if ($datos_remuneracion->movilizacion > 0) {
            $html .= '<tr>
									<td class="tdClass" >Movilizaci&oacute;n</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->movilizacion, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->colacion > 0) {
            $html .= '<tr>
									<td class="tdClass" >Colaci&oacute;n</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->colacion, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->asigfamiliar > 0) {
            $html .= '<tr>
									<td class="tdClass" >Asignaci&oacute;n Familiar</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->asigfamiliar, 0, ".", ".") . '</td>
									</tr>';
        }

        /*if($datos_remuneracion->bonosnoimponibles > 0){
							$html .= '<tr>
									<td class="tdClass" >Bonos No Imponibles</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->bonosnoimponibles,0,".",".") . '</td>
									</tr>';
						}*/

        $datos_bonos_no_imponibles = $this->get_bonos_by_remuneracion($datos_remuneracion->id, false);

        foreach ($datos_bonos_no_imponibles as $bono_no_imponible) {

            $html .= '<tr>
									<td class="tdClass" >' . $bono_no_imponible->descripcion . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($bono_no_imponible->monto, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->sueldonoimponible > 0) {
            $html .= '<tr>
									<td class="tdClass" ><b>Total No Imponible</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->sueldonoimponible, 0, ".", ".") . '</b></td>
									</tr>';
        }


        if ($datos_remuneracion->totalhaberes > 0) {
            $html .= '<tr>
									<td class="tdClass" ><b>Total Haberes</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->totalhaberes, 0, ".", ".") . '</b></td>
									</tr>';
        }

        $html .=    '</tbody>
						</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="70%" ><p>Detalle Descuentos</p></th>
						<th width="30%" ><p>&nbsp;</p></th>
						</tr>
						</thead>
						<tbody>';

        if ($datos_remuneracion->cotizacionobligatoria > 0) {
            $html .= '<tr>
									<td class="tdClass" >Cotizaci&oacute;n AFP Obligatoria</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->cotizacionobligatoria, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->comisionafp > 0) {
            $html .= '<tr>
									<td class="tdClass" >Comisi&oacute;n AFP</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->comisionafp, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->adicafp > 0) {
            $html .= '<tr>
									<td class="tdClass" >Adicional AFP</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->adicafp, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->montoahorrovol > 0) {
            $html .= '<tr>
									<td class="tdClass" >Ahorro Voluntario</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montoahorrovol, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->montocotapv > 0) {
            $html .= '<tr>
									<td class="tdClass" >APV</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montocotapv, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->cotizacionsalud > 0) {
            $html .= '<tr>
									<td class="tdClass" >Cotizaci&oacute;n Salud Obligatoria</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->cotizacionsalud, 0, ".", ".") . '</td>
									</tr>';
        }

        if ($datos_remuneracion->cotadicisapre > 0) {
            $html .= '<tr>
									<td class="tdClass" >Cotizaci&oacute;n Adicional Isapre</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->cotadicisapre, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->adicsalud > 0) {
            $html .= '<tr>
									<td class="tdClass" >Adicional Salud</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->adicsalud, 0, ".", ".") . '</td>
									</tr>';
        }


        if (($datos_remuneracion->fonasa + $datos_remuneracion->inp) > 0) {
            $html .= '<tr>
									<td class="tdClass" >Fonasa</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->fonasa + $datos_remuneracion->inp, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->segcesantia > 0) {
            $html .= '<tr>
									<td class="tdClass" >Seguro de Cesant&iacute;a</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->segcesantia, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->impuesto > 0) {
            $html .= '<tr>
									<td class="tdClass" >Impuesto</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->impuesto, 0, ".", ".") . '</td>
									</tr>';
        }



        if ($datos_remuneracion->totalleyessociales > 0) {
            $html .= '<tr>
									<td class="tdClass" ><b>Total Leyes Sociales</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->totalleyessociales, 0, ".", ".") . '</b></td>
									</tr>';
        }

        $html .= '<tr>
								<td class="tdClass">&nbsp;</td>
								<td class="tdClass">&nbsp;</td>
								</tr>';





        if ($datos_remuneracion->anticipo > 0) {
            $html .= '<tr>
									<td class="tdClass" >Anticipo</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->anticipo, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->aguinaldo > 0) {
            $html .= '<tr>
									<td class="tdClass" >Descuento por Aguinaldo</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->aguinaldo, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->montodescuento > 0) {
            $html .= '<tr>
									<td class="tdClass" >Horas Descuento</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($datos_remuneracion->montodescuento, 0, ".", ".") . '</td>
									</tr>';
        }

        $datos_descuentos = $this->get_descuento($datos_remuneracion->idperiodo, 'D', $datos_remuneracion->idtrabajador);

        foreach ($datos_descuentos as $info_descuento) {

            $html .= '<tr>
									<td class="tdClass" >' . $info_descuento->descripcion . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($info_descuento->monto, 0, ".", ".") . '</td>
									</tr>';
        }


        $datos_prestamos = $this->get_descuento($datos_remuneracion->idperiodo, 'P', $datos_remuneracion->idtrabajador);

        foreach ($datos_prestamos as $info_prestamos) {

            $html .= '<tr>
									<td class="tdClass" >' . $info_prestamos->descripcion . '</td>
									<td class="tdClass tdClassNumber" >$ ' . number_format($info_prestamos->monto, 0, ".", ".") . '</td>
									</tr>';
        }


        if ($datos_remuneracion->otrosdescuentos > 0) {
            $html .= '<tr>
									<td class="tdClass" ><b>Total Otros Descuentos</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->otrosdescuentos, 0, ".", ".") . '</b></td>
									</tr>';
        }

        if ($datos_remuneracion->totaldescuentos > 0) {
            $html .= '<tr>
									<td class="tdClass" ><b>Total Descuentos</b></td>
									<td class="tdClass tdClassNumber" ><b>$ ' . number_format($datos_remuneracion->totaldescuentos, 0, ".", ".") . '</b></td>
									</tr>';
        }

        $html .=    '</tbody>
						</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="70%" ><p>L&iacute;quido a Pagar (Total Haberes - Total Descuentos)</p></th>
						<th width="30%" class="tdClassNumber" style="text-align: right;"><b>$ ' . number_format($datos_remuneracion->sueldoliquido, 0, ".", ".") . '</b></th>
						</tr>
						</thead>
						</table>
						</div>
						<hr>
						<p style="text-align:left;font-size: 12px;" ><b>Son: ' . valorEnLetras($datos_remuneracion->sueldoliquido) . '</b></p>
						<br>
						<table width="100%" border="0">
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">&nbsp;</td>
								<td width="40%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">' . $firma . '</td>
								<td width="10%">&nbsp;</td>
							</tr>
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Trabajador</td>
								<td width="40%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Empleador</td>
								<td width="10%">&nbsp;</td>
							</tr>
						</table>

		';

        $html .=    "</body>
						</html>";

        //	echo $html; exit;

        $this->db->where('id', $datos_remuneracion->id);
        $this->db->update('gc_remuneracion', array('pdf_content' => $html));
    }

    public function liquidacion($datos_remuneracion)
    {

        $this->load->model('admin');
        $datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));
        $content = $this->get_pdf_content($datos_remuneracion->id);

        if ($content->pdf_content == '') { // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
            $this->generar_contenido_comprobante($datos_remuneracion);
            $content = $this->get_pdf_content($datos_remuneracion->id);
        }

        $mpdf = new \Mpdf\Mpdf(['default_font_size' => 8,
                                'margin-top' => 16,
                                'margin-bottom' => 16,
                                'margin-header' => 9,
                                'margin-footer' => 9,
                                'margin-left' => 10,
                                'margin-right' => 5,
                                ]);        

       /* $this->load->library("mpdf");
        $mpdf->mPDF(
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
        );*/
        //echo $html; exit;
        $mpdf->SetTitle('Tu Gasto Común - Liquidación de Sueldos');
        $mpdf->SetHeader('Condominio ' . $datos_comunidad->nombre . ' - ' . $datos_comunidad->comuna . ' - RUT: ' . number_format($datos_comunidad->rut, 0, ".", ".") . '-' . $datos_comunidad->dv);
        $mpdf->WriteHTML($content->pdf_content);
        $mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');


        // SE ALMACENA EL ARCHIVO
        $nombre_archivo = date("Y") . "_" . date("m") . "_" . date("d") . "_sueldos_" . $datos_remuneracion->id . ".pdf";
        $mpdf->Output($nombre_archivo, "I");
    }




    public function generar_contenido_comprobante_solicitud($idpersonal, $idcartola)
    {


        $cartola = $this->remuneracion->get_cartola_vacaciones($idpersonal, $idcartola);
        $personal = $this->remuneracion->get_personal($idpersonal, 'todos');

        $this->load->model('admin');
        $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));


        $logo = $comunidad->logo == '' || is_null($comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/' . $this->session->userdata('comunidadid') . '/' . $comunidad->logo;



        $firma = $comunidad->firma == '' || is_null($comunidad->firma) ? '&nbsp;' : '<img src="uploads/firmas/' . $this->session->userdata('comunidadid') . '/' . $comunidad->firma . '" width="150px"> ';

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
						font-size: 11pt;
						color: #080636;
						font-family: DejaVuSansCondensed, sans-serif;
						margin-top: 10pt;
						margin-bottom: 7pt;
						text-align: center;
						margin-collapse:collapse; page-break-after:avoid; }

						punteada {
    						border: 1px dashed #278e79;
  						}
					</style>
			</head>
					<body>';


        $contenido_comprobante = '
						<p><h4 class="header4"><br>Comprobante de Vacaciones<br><br><img src="' . $logo . '" width="100px"></h4></p>
						<hr>
						<br>
						<div class="recto">
							<h4><b>Fecha:</b> ' . substr($cartola->created_at, 8, 2) . '/' . substr($cartola->created_at, 5, 2) . '/' . substr($cartola->created_at, 0, 4) . '</h4><br>
							<p align="justify">En cumplimiento a las disposiciones legales vigentes se deja constancia que a contar de las fechas que se indican el (la)
								trabajador (a): ' . $personal->nombre . ' ' . $personal->apaterno . ' ' . $personal->amaterno . ', cédula de Identidad ' . number_format($personal->rut, 0, ".", ".") . '-' . $personal->dv . ',  hará uso de: ' . $cartola->dias . ' días hábiles de feriado anual con remuneración íntegra. Esto se hará efectivo entre los días <b>' . substr($cartola->fecinicio, 8, 2) . ' de ' . month2string(substr($cartola->fecinicio, 5, 2)) . ' de ' . substr($cartola->fecinicio, 0, 4) . '</b> y <b>' . substr($cartola->fecfin, 8, 2) . ' de ' . month2string(substr($cartola->fecfin, 5, 2)) . ' de ' . substr($cartola->fecfin, 0, 4) . '</b> inclusive.</p>
						</div>
		';


        if ($firma == '&nbsp;') {
            $contenido_comprobante .= '<br><hr><br>
						<br>
						<br>
						<br>';
        } else {
            $contenido_comprobante .= '<br><hr>';
        }

        $contenido_comprobante .= '
						<table width="100%" border="0">
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">&nbsp;</td>
								<td width="40%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">' . $firma . '</td>
								<td width="10%">&nbsp;</td>
							</tr>
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Trabajador</td>
								<td width="40%">&nbsp;</td>
								<td width="20%" style="text-align:center">Firma Empleador</td>
								<td width="10%">&nbsp;</td>
							</tr>
						</table>
						';



        $html .= $contenido_comprobante;
        $html .= '<br>
						  <p align="left" style="font-size:8px">COPIA EMPLEADOR</p>
						<hr class="punteada" />';
        $html .= $contenido_comprobante;
        $html .= '<br>
						  <p align="left" style="font-size:8px">COPIA TRABAJADOR</p>';



        $html .=    "</body>
						</html>";

        //echo $html; exit;

        //$this->db->where('id',$idegreso);
        //$this->db->update('gc_listado_pagos', array('pdf_content' => $html));
        return $html;
    }


    public function comprobante_solicitud($idpersonal, $idcartola)
    {

        $this->load->model('admin');
        $datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

        $content = $this->generar_contenido_comprobante_solicitud($idpersonal, $idcartola);

        $mpdf = new \Mpdf\Mpdf(['default_font_size' => 8,
                                'margin-top' => 16,
                                'margin-bottom' => 16,
                                'margin-header' => 9,
                                'margin-footer' => 9,
                                'margin-left' => 10,
                                'margin-right' => 5,
                                ]);


        /*$this->load->library("mpdf");
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
        );*/
        //echo $html; exit;
        $mpdf->SetTitle('Tu Gasto Común - Comprobante Solicitud Vacaciones');
        $mpdf->SetHeader('Condominio ' . $datos_comunidad->nombre . ' - ' . $datos_comunidad->comuna . ' - RUT: ' . number_format($datos_comunidad->rut, 0, ".", ".") . '-' . $datos_comunidad->dv);
        $mpdf->WriteHTML($content);
        $mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');


        // SE ALMACENA EL ARCHIVO
        $nombre_archivo = date("Y") . "_" . date("m") . "_" . date("d") . "_vacaciones_" . $idpersonal . ".pdf";
        $mpdf->Output($nombre_archivo, "I");
    }



    public function liquidaciones($datos_remuneracion)
    {

        $this->load->model('admin');
        $datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

        $mpdf = new \Mpdf\Mpdf(['default_font_size' => 8,
                                'margin-top' => 16,
                                'margin-bottom' => 16,
                                'margin-header' => 9,
                                'margin-footer' => 9,
                                'margin-left' => 10,
                                'margin-right' => 5,
                                ]);

        /*$this->load->library("mpdf");
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
        );*/
        $mpdf->SetTitle('Tu Gasto Común - Liquidaciones de Sueldos');
        $mpdf->SetHeader('Condominio ' . $datos_comunidad->nombre . ' - ' . $datos_comunidad->comuna . ' - RUT: ' . number_format($datos_comunidad->rut, 0, ".", ".") . '-' . $datos_comunidad->dv);
        $mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');
        $cantidad = count($datos_remuneracion);
        $i = 0;
        foreach ($datos_remuneracion as $remuneracion) {
            $content = $this->get_pdf_content($remuneracion->id);

            if ($content->pdf_content == '') { // EN CASO QUE POR ALGUN MOTIVO FALLARA LA EJECUCION INICIAL, SE CREA AHORA
                $this->generar_contenido_comprobante($remuneracion);
                $content = $this->get_pdf_content($remuneracion->id);
            }
            $mpdf->WriteHTML($content->pdf_content);

            $i++;

            if ($i < $cantidad) {
                $mpdf->AddPage();
            }
        }





        // SE ALMACENA EL ARCHIVO
        $nombre_archivo = date("Y") . "_" . date("m") . "_" . date("d") . "_sueldos_.pdf";




        $mpdf->Output($nombre_archivo, "I");
    }



    public function previred($datos_remuneracion)
    {

        $nombre_archivo = $this->session->userdata('comunidadid') . "_previred_" . date("Ymd") . ".txt";
        $path_archivo = "./uploads/tmp/";
        $file = fopen($path_archivo . $nombre_archivo, "w");

        foreach ($datos_remuneracion as $remuneracion) {



            $idperiodo = $remuneracion->idperiodo;
            $idtrabajador = $remuneracion->idtrabajador;


            $movimientos_personal = $this->get_lista_movimientos($idtrabajador, null, $idperiodo);
            $cod_mov_personal = "00";
            $array_lineas_trabajador = array();
            $i = 0;
            foreach ($movimientos_personal as $movimiento_personal) {
                if (count($array_lineas_trabajador) == 0) {
                    $array_lineas_trabajador[$i]['tipo_linea'] = "00";
                    $array_lineas_trabajador[$i]['codprevired'] = str_pad($movimiento_personal->codprevired, 2, "0", STR_PAD_LEFT);
                    $array_lineas_trabajador[$i]['fechadesde'] = formato_fecha($movimiento_personal->fecmovimiento, 'Y-m-d', 'd-m-Y');
                    $array_lineas_trabajador[$i]['fechahasta'] = formato_fecha($movimiento_personal->fechastamovimiento, 'Y-m-d', 'd-m-Y');
                } else {
                    $array_lineas_trabajador[$i]['tipo_linea'] = "01";
                    $array_lineas_trabajador[$i]['codprevired'] = str_pad($movimiento_personal->codprevired, 2, "0", STR_PAD_LEFT);
                    $array_lineas_trabajador[$i]['fechadesde'] = formato_fecha($movimiento_personal->fecmovimiento, 'Y-m-d', 'd-m-Y');
                    $array_lineas_trabajador[$i]['fechahasta'] = formato_fecha($movimiento_personal->fechastamovimiento, 'Y-m-d', 'd-m-Y');
                }

                $i++;
            }

            if (count($array_lineas_trabajador) == 0) {
                $array_lineas_trabajador[0]['tipo_linea'] = "00";
                $array_lineas_trabajador[0]['codprevired'] = "00";
                $array_lineas_trabajador[$i]['fechadesde'] = "00-00-0000";
                $array_lineas_trabajador[$i]['fechahasta'] = "00-00-0000";
            }

            /*$rut = str_pad($remuneracion->rut,11,"0",STR_PAD_LEFT);
				$dv = $remuneracion->dv;
				$apaterno = str_pad(substr($remuneracion->apaterno,0,30),30," ",STR_PAD_RIGHT);
				$amaterno = str_pad(substr($remuneracion->amaterno,0,30),30," ",STR_PAD_RIGHT);
				$nombres = str_pad(substr($remuneracion->nombre,0,30),30," ",STR_PAD_RIGHT);*/
            $asigfamiliar = $remuneracion->asigfamiliar - $remuneracion->montocargaretroactiva;


            $dato_afp = $this->get_afp($remuneracion->idafp);

            $codprev_apv = is_null($remuneracion->idapv) ? 0 : $this->get_apv($remuneracion->idapv)->codprevired;
            $codprev_mutual = is_null($remuneracion->idmutual) ? 0 : $this->get_mutual_seguridad($remuneracion->idmutual)->codprevired;
            $codprev_ccaf = is_null($remuneracion->idcaja) ? 0 : $this->get_cajas_compensacion($remuneracion->idcaja)->codprevired;

            if ($dato_afp->exregimen == 0) {
                $reg_previsional = "AFP";
                $tipo_trabajador = 0;
            } else if ($dato_afp->exregimen == 1) {
                $reg_previsional = "INP";
                $tipo_trabajador = 0;
            } else if ($dato_afp->exregimen == 2) {
                $reg_previsional = "SIP";
                $tipo_trabajador = 2;
            } else {
                $reg_previsional = "   ";
                $tipo_trabajador = 0;
            }


            $dato_isapre = $this->get_isapre($remuneracion->idisapre);



            $tramo_asig_familiar = is_null($remuneracion->idasigfamiliar) ? "D" : $this->get_tabla_asig_familiar($remuneracion->idasigfamiliar)->tramo;
            $formapagoapv = is_null($remuneracion->formapagoapv) ? "0" : $remuneracion->formapagoapv;


            if ($dato_afp->exregimen == 1) {
                $sueldoimponible_afp = ($remuneracion->cotizacionobligatoria + $remuneracion->comisionafp + $remuneracion->seginvalidez) > 0 ? $remuneracion->sueldoimponibleips : 0;
            } else {
                $sueldoimponible_afp = ($remuneracion->cotizacionobligatoria + $remuneracion->comisionafp + $remuneracion->seginvalidez) > 0 ? $remuneracion->sueldoimponibleimposiciones : 0;
            }



            //$sueldoimponible_afp = ($remuneracion->cotizacionobligatoria+$remuneracion->comisionafp+$remuneracion->seginvalidez) > 0 ? $remuneracion->sueldoimponibleimposiciones : 0;
            $sueldoimponible_fonasa = ($remuneracion->fonasa + $remuneracion->inp) > 0 ? $remuneracion->sueldoimponibleimposiciones : 0;
            $sueldoimponible_isapre = $remuneracion->cotizacionsalud > 0 ? $remuneracion->sueldoimponibleimposiciones : 0;
            $sueldoimponible_mutual = $codprev_mutual != 0 ? $remuneracion->sueldoimponible : 0;
            //$sueldoimponible_mutual = $remuneracion->sueldoimponibleimposiciones;
            $sueldoimponible_ccaf = $codprev_ccaf != 0 ? $remuneracion->sueldoimponibleimposiciones : 0;
            $sueldoimponible_segcesantia = $remuneracion->afilsegcesantia == 1 ? $remuneracion->sueldoimponibleafc : 0;
            $cotccaffon = $codprev_ccaf == 0 ? 0 : $remuneracion->inp;
            $aportepatronal = $codprev_mutual == 0 ? 0 : $remuneracion->aportepatronal;

            $cotizacion_isl = $remuneracion->aportepatronal > 0 && $codprev_mutual == 0 ? str_pad($remuneracion->aportepatronal, 8, "0", STR_PAD_LEFT) : "00000000";
            //$aportepatronal = $remuneracion->aportepatronal;
            $asigfamiliar_ccaf = $codprev_ccaf != 0 ? $asigfamiliar : 0;
            $asigfamiliar_mes = $codprev_ccaf != 0 ? $remuneracion->asigfamiliar : 0;



            $cotizacion_fonasa = $codprev_ccaf == 0 ? $remuneracion->fonasa + $remuneracion->inp : $remuneracion->fonasa;

            $monto_prestamos = 0;
            $prestamos = $this->get_descuento($remuneracion->idperiodo, 'P', $remuneracion->idtrabajador);
            foreach ($prestamos as $prestamo) {
                $monto_prestamos += $prestamo->tipodescuento == 2 ? $prestamo->monto : 0;
            }


            foreach ($array_lineas_trabajador as $linea_trabajador) {


                $diastrabajo = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->diastrabajo : 0;
                $tramo_asig_familiar = $linea_trabajador['tipo_linea'] == "00" ? $tramo_asig_familiar : " ";
                $cargassimples = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cargassimples : 0;
                $cargasmaternales = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cargasmaternales : 0;
                $cargasinvalidas = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cargasinvalidas : 0;
                $asigfamiliar  = $linea_trabajador['tipo_linea'] == "00" ? $asigfamiliar : 0;
                $montocargaretroactiva = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->montocargaretroactiva : 0;
                $solicitud_trabajador_joven = $linea_trabajador['tipo_linea'] == "00" ? "N" : " ";

                $sueldoimponible_afp  = $linea_trabajador['tipo_linea'] == "00" ? $sueldoimponible_afp : 0;
                $cot_obligatoria_afp  = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cotizacionobligatoria + $remuneracion->comisionafp : 0;
                $seginvalidez = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->seginvalidez : 0;
                $montoahorrovol = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->montoahorrovol : 0;
                $fecdesdeafp = $linea_trabajador['tipo_linea'] == "00" ? "00-00-0000" : "          ";
                $fechastaafp = $linea_trabajador['tipo_linea'] == "00" ? "00-00-0000" : "          ";


                $dv_afiliado_voluntario = $linea_trabajador['tipo_linea'] == "00" ? "0" : " ";
                $fecdesdeafilvol = $linea_trabajador['tipo_linea'] == "00" ? "00-00-0000" : "          ";
                $fechastaafilvol = $linea_trabajador['tipo_linea'] == "00" ? "00-00-0000" : "          ";
                $cotizacion_fonasa = $linea_trabajador['tipo_linea'] == "00" ? $cotizacion_fonasa : 0;

                $moneda_plan_pactado = $linea_trabajador['tipo_linea'] == "00" ? "2" : "0";
                $valorpactadoperiodo = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->valorpactadoperiodo : 0;
                $cotizacionsalud = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->cotizacionsalud : 0;
                $adicisapre = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->adicisapre : 0;

                $monto_prestamos = $linea_trabajador['tipo_linea'] == "00" ? $monto_prestamos : 0;
                $cotccaffon = $linea_trabajador['tipo_linea'] == "00" ? $cotccaffon : 0;
                $asigfamiliar_mes = $linea_trabajador['tipo_linea'] == "00" ? $asigfamiliar_mes : 0;
                $asigfamiliar_ccaf = $linea_trabajador['tipo_linea'] == "00" ? $asigfamiliar_ccaf : 0;

                $aportepatronal = $linea_trabajador['tipo_linea'] == "00" ? $aportepatronal : 0;

                $sueldoimponible_segcesantia = $linea_trabajador['tipo_linea'] == "00" ? $sueldoimponible_segcesantia : 0;
                $segcesantia = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->segcesantia : 0;
                $aportesegcesantia = $linea_trabajador['tipo_linea'] == "00" ? $remuneracion->aportesegcesantia : 0;





                // DATOS DEL TRABAJADOR
                $linea  = str_pad($remuneracion->rut, 11, "0", STR_PAD_LEFT); // rut
                $linea .= $remuneracion->dv; // dv
                $linea .= str_pad(substr(sanear_string($remuneracion->apaterno), 0, 30), 30, " ", STR_PAD_RIGHT); //apaterno
                $linea .= str_pad(substr(sanear_string($remuneracion->amaterno), 0, 30), 30, " ", STR_PAD_RIGHT); //amaterno
                $linea .= str_pad(substr(sanear_string($remuneracion->nombre), 0, 30), 30, " ", STR_PAD_RIGHT); //nombre
                $linea .= $remuneracion->sexo; //sexo
                $linea .= $remuneracion->nacionalidad == "C" ? 0 : 1; //nacionalidad
                $linea .= "01"; //tipo pago
                $linea .= str_pad($remuneracion->mes, 2, "0", STR_PAD_LEFT) . $remuneracion->anno; //periodo desde
                $linea .= str_pad($remuneracion->mes, 2, "0", STR_PAD_LEFT) . $remuneracion->anno; //periodo hasta
                $linea .= $reg_previsional; //regimen previsional
                $linea .= $tipo_trabajador; //tipo trabajador (ver que pasa con pensionados activos y pensionados y cotiza)
                $linea .= str_pad($diastrabajo, 2, "0", STR_PAD_LEFT); //dias trabajados
                $linea .= $linea_trabajador['tipo_linea']; //tipo de linea ***** VER PARA MOVIMIENTOS DEL PERSONAL
                $linea .= $linea_trabajador['codprevired']; //Código Movimiento de Personal
                $linea .= $linea_trabajador['fechadesde']; //Fecha Desde Código Movimiento de Personal
                $linea .= $linea_trabajador['fechahasta']; //Fecha Hasta Código Movimiento de Personal
                $linea .= $tramo_asig_familiar; //Tramo asignacion familiar
                $linea .= str_pad($cargassimples, 2, "0", STR_PAD_LEFT); //cargas simples
                $linea .= substr($cargasmaternales, -1); //cargas maternales
                $linea .= substr($cargasinvalidas, -1); //cargas inválidas
                $linea .= str_pad(substr($asigfamiliar, -6), 6, "0", STR_PAD_LEFT); //monto asignacion familiar
                $linea .= str_pad(substr($montocargaretroactiva, -6), 6, "0", STR_PAD_LEFT); //monto asignacion retroactiva
                $linea .= "000000"; //monto reintegro de cargas familiares
                $linea .= $solicitud_trabajador_joven; //Solicitud Trabajador Joven


                // DATOS AFP
                $linea .= str_pad($dato_afp->codprevired, 2, "0", STR_PAD_LEFT); //cod afp
                $linea .= str_pad($sueldoimponible_afp, 8, "0", STR_PAD_LEFT); //sueldo imponible
                $linea .= str_pad($cot_obligatoria_afp, 8, "0", STR_PAD_LEFT); //cotizacion
                $linea .= str_pad($seginvalidez, 8, "0", STR_PAD_LEFT); //seguro invalidez
                $linea .= str_pad($montoahorrovol, 8, "0", STR_PAD_LEFT); //monto ahorro voluntario
                $linea .= "00000000"; //Renta imponible sustituta
                $linea .= "00,00"; //tasa pactada
                $linea .= "000000000"; //aporte indemnizacion
                $linea .= "00"; //nro. periodos
                $linea .= $fecdesdeafp; //periodo desde
                $linea .= $fechastaafp; //periodo hasta
                $linea .= "                                        "; //puesto de trabajo pesado
                $linea .= "00,00"; //cotizacion trabajo pesado
                $linea .= "000000"; //monto cotizacion trabajo pesado


                //Datos Ahorro Previsional Voluntario Individual (PENDIENTE HASTA IMPLEMENTAR OPCIONES DE APV)
                //$linea .= str_pad($dato_afp->codprevired,3,"0",STR_PAD_LEFT); //cod institucion APVI (se asume que es la misma de la APV??)
                $linea .= str_pad($codprev_apv, 3, "0", STR_PAD_LEFT); //cod institucion APVI
                $linea .= str_pad(substr($remuneracion->nrocontratoapv, -20), 20, "0", STR_PAD_LEFT); //nro contrato apvi
                $linea .= $formapagoapv; //forma de pago apv
                $linea .= str_pad(substr($remuneracion->montocotapv, -8), 8, "0", STR_PAD_LEFT); //monto cotizacion apvi
                //$linea .= "00000000"; //monto cotizacion apvi
                $linea .= str_pad(substr($remuneracion->depconvapv, -8), 8, "0", STR_PAD_LEFT);; //Cotización Depósitos Convenidos  *****************


                //Datos Ahorro Previsional Voluntario Colectivo
                $linea .= "000"; //Código Institución Autorizada APVC
                $linea .= "                    "; //nro contrato APVC
                $linea .= "0"; //forma de pago apvc
                $linea .= "00000000"; //Cotización Trabajador APVC
                $linea .= "00000000"; //Cotización Empleador APVC


                //Datos Afiliado Voluntario
                $linea .= "00000000000"; // RUT Afiliado Voluntario
                $linea .= $dv_afiliado_voluntario; // DV Afiliado Voluntario
                $linea .= "                              "; //Apellido Paterno
                $linea .= "                              "; //Apellido Materno
                $linea .= "                              "; //Nombres
                $linea .= "00"; // Código Movimiento de Personal
                $linea .= $fecdesdeafilvol; //Fecha desde
                $linea .= $fechastaafilvol; //Fecha hasta
                $linea .= "00"; // Código de la AFP
                $linea .= "00000000"; //Monto Capitalización Voluntaria
                $linea .= "00000000"; //Monto Ahorro Voluntario
                $linea .= "00"; // Número de periodos de cotización


                //Datos IPS - ISL - FONASA  (FALTA ANALIZAR DE AQUI HACIA ABAJO)
                $linea .= "0000"; // Código EX-Caja Régimen
                $linea .= "00,00"; //Tasa Cotización Ex-Caja Previsión
                $linea .= str_pad($sueldoimponible_fonasa, 8, "0", STR_PAD_LEFT); //Renta Imponible IPS ******REVISAR, al parecer hay un tope
                $linea .= "00000000"; //Cotización Obligatoria IPS
                $linea .= "00000000"; //Renta Imponible Desahucio
                $linea .= "0000"; // Código Ex-Caja Régimen Desahucio
                $linea .= "00,00"; //Tasa Cotización Desahucio Ex-Cajas de Previsión
                $linea .= "00000000"; //Cotización Desahucio
                $linea .= str_pad($cotizacion_fonasa, 8, "0", STR_PAD_LEFT); //Cotización Fonasa
                //$linea .= str_pad($remuneracion->fonasa,8,"0",STR_PAD_LEFT); //Cotización Fonasa
                $linea .= $cotizacion_isl; //Cotización Acc. Trabajo (ISL) *****************
                $linea .= "00000000"; //Bonificación Ley 15.386
                $linea .= "00000000"; //Descuento por cargas familiares de ISL
                $linea .= "00000000"; //Bonos Gobierno


                //Datos Salud
                $linea .= str_pad($dato_isapre->codprevired, 2, "0", STR_PAD_LEFT); // Código Institución de Salud
                $linea .= "                "; // Número del FUN (REVISAR SI SON BLANCOS O VACÍOS)
                $linea .= str_pad($sueldoimponible_isapre, 8, "0", STR_PAD_LEFT); //Renta Imponible Isapre
                $linea .= $moneda_plan_pactado; //Moneda del plan pactado Isapre
                $linea .= str_pad(str_replace(".", ",", $valorpactadoperiodo), 8, "0", STR_PAD_LEFT); //Cotización Pactada
                $linea .= str_pad($cotizacionsalud, 8, "0", STR_PAD_LEFT); //Cotización Obligatoria Isapre
                $linea .= str_pad($adicisapre, 8, "0", STR_PAD_LEFT); //Cotización Adicional Voluntaria
                $linea .= "00000000"; //Monto Garantía Explícita de Salud GES (Uso Futuro)



                //Datos Caja de Compensación (AQUI VOY)


                $linea .= str_pad($codprev_ccaf, 2, "0", STR_PAD_LEFT);; // Código CCAF
                $linea .= str_pad($sueldoimponible_ccaf, 8, "0", STR_PAD_LEFT); //Renta Imponible CCAF
                $linea .= str_pad($monto_prestamos, 8, "0", STR_PAD_LEFT); //Créditos Personales CCAF
                $linea .= "00000000"; //Descuento Dental CCAF *****************
                $linea .= "00000000"; //Descuentos por Leasing (Programa Ahorro) *****************
                $linea .= "00000000"; //Descuentos por seguro de vida CCAF*****************
                $linea .= "00000000"; //Otros descuentos CCAF *****************
                $linea .= str_pad($cotccaffon, 8, "0", STR_PAD_LEFT); //Cotización a CCAF de no afiliados a Isapres
                $linea .= str_pad($asigfamiliar_mes, 8, "0", STR_PAD_LEFT); //Descuento Cargas Familiares CCAF
                $linea .= "00000000"; //Otros descuentos CCAF 1 (Uso Futuro) *****************
                $linea .= "00000000"; //Otros descuentos CCAF 2 (Uso Futuro) *****************
                $linea .= "00000000"; //Bonos Gobierno (Uso Futuro) *****************
                $linea .= "                    "; //Código de Sucursal (Uso Futuro) (VER SI ES BLANCO O CEROS) *****************



                //Datos Mutualidad
                $linea .= str_pad($codprev_mutual, 2, "0", STR_PAD_LEFT);; // Código Mutualidad
                $linea .= str_pad($sueldoimponible_mutual, 8, "0", STR_PAD_LEFT); //Renta Imponible Mutual
                $linea .= str_pad($aportepatronal, 8, "0", STR_PAD_LEFT);; //Cotización Accidente del Trabajo (MUTUAL)
                $linea .= "000"; // Código Mutualidad (VER QUE PASA EN LINEAS ADICIONALES POR MOV PERSONAL) *****************

                //Datos Administradora de Seguro de Cesantía

                $linea .= str_pad($sueldoimponible_segcesantia, 8, "0", STR_PAD_LEFT); //Renta Imponible Seguro Cesantía (Informar Renta Total Imponible)
                $linea .= str_pad($segcesantia, 8, "0", STR_PAD_LEFT); //Aporte Trabajador Seguro Cesantía
                $linea .= str_pad($aportesegcesantia, 8, "0", STR_PAD_LEFT); //Aporte Empleador Seguro Cesantía

                //Datos Pagador de Subsidios
                $linea .= "00000000000"; //Rut Pagadora Subsidio
                $linea .= " "; //Rut Pagadora Subsidio


                //Otros Datos de la Empresa
                $linea .= "                    "; //Centro de Costos, Sucursal, Agencia, Obra, Región




                $linea .= "\r\n";
                //$linea = $rut.$dv.$apaterno.$amaterno.$nombres."\r\n";
                fputs($file, $linea);
            }
        }


        fclose($file);

        $data_archivo = basename($path_archivo . $nombre_archivo);
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $data_archivo);
        header('Content-Length: ' . filesize($path_archivo . $nombre_archivo));
        readfile($path_archivo . $nombre_archivo);


        unlink($path_archivo . $nombre_archivo);
    }




public function lre($datos_remuneracion,$periodo)
    {
        $this->load->model('admin');
         $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));
         //echo "<pre>";
         //var_dump($datos_remuneracion); exit;

        $nombre_archivo = $comunidad->rut . $comunidad->dv . "_" . $periodo->anno . $periodo->mes .".csv";

        $path_archivo = "./uploads/tmp/";
        $file = fopen($path_archivo . $nombre_archivo, "w");

        
        $encabezado = "Rut trabajador(1101);Fecha inicio contrato(1102);Fecha término de contrato(1103);Causal término de contrato(1104);Región prestación de servicios(1105);Comuna prestación de servicios(1106);Tipo impuesto a la renta(1170);Técnico extranjero exención cot. previsionales(1146);Código tipo de jornada(1107);Persona con Discapacidad - Pensionado por Invalidez(1108);Pensionado por vejez(1109);AFP(1141);IPS (ExINP)(1142);FONASA - ISAPRE(1143);AFC(1151);CCAF(1110);Org. administrador ley 16.744(1152);Nro cargas familiares legales autorizadas(1111);Nro de cargas familiares maternales(1112);Nro de cargas familiares invalidez(1113);Tramo asignación familiar(1114);Rut org sindical 1(1171);Rut org sindical 2(1172);Rut org sindical 3(1173);Rut org sindical 4(1174);Rut org sindical 5(1175);Rut org sindical 6(1176);Rut org sindical 7(1177);Rut org sindical 8(1178);Rut org sindical 9(1179);Rut org sindical 10(1180);Nro días trabajados en el mes(1115);Nro días de licencia médica en el mes(1116);Nro días de vacaciones en el mes(1117);Subsidio trabajador joven(1118);Puesto Trabajo Pesado(1154);APVI(1155);APVC(1157);Indemnización a todo evento(1131);Tasa indemnización a todo evento(1132);Sueldo(2101);Sobresueldo(2102);Comisiones(2103);Semana corrida(2104);Participación(2105);Gratificación(2106);Recargo 30% día domingo(2107);Remun. variable pagada en vacaciones(2108);Remun. variable pagada en clausura(2109);Aguinaldo(2110);Bonos u otras remun. fijas mensuales(2111);Tratos(2112);Bonos u otras remun. variables mensuales o superiores a un mes(2113);Ejercicio opción no pactada en contrato(2114);Beneficios en especie constitutivos de remun(2115);Remuneraciones bimestrales(2116);Remuneraciones trimestrales(2117);Remuneraciones cuatrimestral(2118);Remuneraciones semestrales(2119);Remuneraciones anuales(2120);Participación anual(2121);Gratificación anual(2122);Otras remuneraciones superiores a un mes(2123);Pago por horas de trabajo sindical(2124);Sueldo empresarial (2161);Subsidio por incapacidad laboral por licencia médica(2201);Beca de estudio(2202);Gratificaciones de zona(2203);Otros ingresos no constitutivos de renta(2204);Colación(2301);Movilización(2302);Viáticos(2303);Asignación de pérdida de caja(2304);Asignación de desgaste herramienta(2305);Asignación familiar legal(2311);Gastos por causa del trabajo(2306);Gastos por cambio de residencia(2307);Sala cuna(2308);Asignación trabajo a distancia o teletrabajo(2309);Depósito convenido hasta UF 900(2347);Alojamiento por razones de trabajo(2310);Asignación de traslación(2312);Indemnización por feriado legal(2313);Indemnización años de servicio(2314);Indemnización sustitutiva del aviso previo(2315);Indemnización fuero maternal(2316);Pago indemnización a todo evento(2331);Indemnizaciones voluntarias tributables(2417);Indemnizaciones contractuales tributables(2418);Cotización obligatoria previsional (AFP o IPS)(3141);Cotización obligatoria salud 7%(3143);Cotización voluntaria para salud(3144);Cotización AFC - trabajador(3151);Cotizaciones técnico extranjero para seguridad social fuera de Chile(3146);Descuento depósito convenido hasta UF 900 anual(3147);Cotización APVi Mod A(3155);Cotización APVi Mod B hasta UF50(3156);Cotización APVc Mod A(3157);Cotización APVc Mod B hasta UF50(3158);Impuesto retenido por remuneraciones(3161);Impuesto retenido por indemnizaciones(3162);Mayor retención de impuestos solicitada por el trabajador(3163);Impuesto retenido por reliquidación remun. devengadas otros períodos(3164);Diferencia impuesto reliquidación remun. devengadas en este período(3165);Retención préstamo clase media 2020 (Ley 21.252) (3166);Rebaja zona extrema DL 889 (3167);Cuota sindical 1(3171);Cuota sindical 2(3172);Cuota sindical 3(3173);Cuota sindical 4(3174);Cuota sindical 5(3175);Cuota sindical 6(3176);Cuota sindical 7(3177);Cuota sindical 8(3178);Cuota sindical 9(3179);Cuota sindical 10(3180);Crédito social CCAF(3110);Cuota vivienda o educación(3181);Crédito cooperativas de ahorro(3182);Otros descuentos autorizados y solicitados por el trabajador(3183);Cotización adicional trabajo pesado - trabajador(3154);Donaciones culturales y de reconstrucción(3184);Otros descuentos(3185);Pensiones de alimentos(3186);Descuento mujer casada(3187);Descuentos por anticipos y préstamos(3188);AFC - Aporte empleador(4151);Aporte empleador seguro accidentes del trabajo y Ley SANNA(4152);Aporte empleador indemnización a todo evento(4131);Aporte adicional trabajo pesado - empleador(4154);Aporte empleador seguro invalidez y sobrevivencia(4155);APVC - Aporte Empleador(4157);Total haberes(5201);Total haberes imponibles y tributables(5210);Total haberes imponibles no tributables(5220);Total haberes no imponibles y no tributables(5230);Total haberes no imponibles y tributables(5240);Total descuentos(5301);Total descuentos impuestos a las remuneraciones(5361);Total descuentos impuestos por indemnizaciones(5362);Total descuentos por cotizaciones del trabajador(5341);Total otros descuentos(5302);Total aportes empleador(5410);Total líquido(5501);Total indemnizaciones(5502);Total indemnizaciones tributables(5564);Total indemnizaciones no tributables(5565)\r\n"; //  total indemnizaciones no tributables 

        fputs($file, utf8_decode($encabezado));


        foreach ($datos_remuneracion as $remuneracion) {

               // echo $remuneracion->codlrecaja."<br>";
                /***************/
                $tipojornada = $remuneracion->parttime == 0 ? 101 : 201;
                $pensionadovejez = $remuneracion->idafp == 7 ? 1 : 0;


                $movimientos = $this->get_lista_movimientos($remuneracion->idtrabajador, null, $remuneracion->idperiodo, 3);

                $dias_licencia = 0;
                foreach ($movimientos as $movimiento) {
                    
                    $dias = dias_transcurridos($movimiento->fecmovimiento,$movimiento->fechastamovimiento) + 1; // se agrega uno porque se considera el día inicial
                    $dias_licencia += $dias;
                }                


                if(($dias_licencia + $remuneracion->diastrabajo ) > 30){
                        $dias_licencia = 30 - $remuneracion->diastrabajo;
                }


                $ahorrovol = $remuneracion->montoahorrovol > 0 ? 1 : 0;



                // Categoría 1: Identificación del Trabajador
                $linea  = $remuneracion->rut."-".$remuneracion->dv.";"; // rut
                $linea .= $remuneracion->fecingreso.";";// fecha inicio contrato
                $linea .= ";"; // fecha termino contrato
                $linea .= ";"; // causal termino contrato
                $linea .= $remuneracion->idregion.";"; // region prestacion servicios
                $linea .= $remuneracion->idcomuna.";"; // comuna prestacion servicios
                $linea .= "1;"; // tipo impuesto a la renta 
                $linea .= "0;"; // Técnico extranjero exención de cotizaciones previsionales (ley 18.156)
                $linea .= $tipojornada.";"; // Código tipo de jornada
                $linea .= "0;"; //  Persona con discapacidad/pensionado por invalidez
                $linea .= $pensionadovejez.";"; //  Pensionado por vejez
                $linea .= $remuneracion->codlre == '' ? "0;" : $remuneracion->codlre .";"; //  AFP
                $linea .= "0;"; //  IPS (ExINP) (*)
                $linea .= $remuneracion->codlreisapre == '' ? "0;" : $remuneracion->codlreisapre.";"; //   FONASA / ISAPRE 
                $linea .= $remuneracion->afilsegcesantia.";"; //  AFC
                $linea .= $remuneracion->codlrecaja == '' ? "0;" : $remuneracion->codlrecaja .";"; //  CCAF
                $linea .= $remuneracion->codlremutual == '' ? "0;" : $remuneracion->codlremutual.";"; //  Org. administrador ley 16.744
                $linea .= $remuneracion->cargassimples.";"; //  Número cargas familiares legales autorizadas
                $linea .= $remuneracion->cargasmaternales.";"; //  Número de cargas familiares maternales 
                $linea .= $remuneracion->cargasinvalidas.";"; //  Número de cargas familiares invalidez
                $linea .= ($remuneracion->tramo_asig_familiar == '' ? 'S' : $remuneracion->tramo_asig_familiar).";"; //  Tramo asignación familiar
                $linea .= ";"; //  Rut organización sindical 1
                $linea .= ";"; //  Rut organización sindical 2
                $linea .= ";"; //  Rut organización sindical 3
                $linea .= ";"; //  Rut organización sindical 4
                $linea .= ";"; //  Rut organización sindical 5
                $linea .= ";"; //  Rut organización sindical 6
                $linea .= ";"; //  Rut organización sindical 7
                $linea .= ";"; //  Rut organización sindical 8
                $linea .= ";"; //  Rut organización sindical 9
                $linea .= ";"; //  Rut organización sindical 10
                $linea .= $remuneracion->diastrabajo.";"; //  Número días trabajados en el mes
                $linea .= $dias_licencia.";"; //  Número días de licencia médica en el mes 
                $linea .= "0;"; //  Número días de vacaciones en el mes ******
                $linea .= "0;"; //  Subsidio trabajador joven
                $linea .= "0;"; //  Puesto trabajo pesado 
                $linea .= $ahorrovol.";"; //  Ahorro previsional voluntario individual 
                $linea .= "0;"; //   Ahorro previsional voluntario colectivo
                $linea .= "0;"; //  Indemnización a todo evento (Art. 164)
                $linea .= ";"; //  Tasa indemnización a todo evento (Art. 164) 

                // Categoría 2: Haberes
                //Subcategoría N°1: Haberes imponibles y tributables

                $linea .= $remuneracion->sueldobase.";"; //  Sueldo
                $linea .= ($remuneracion->montohorasextras50 + $remuneracion->montohorasextras100).";"; //  Sobresueldo
                $linea .= "0;"; //  Comisiones (mensual) 
                $linea .= "0;"; //  Semana corrida mensual (Art. 45) 
                $linea .= "0;"; //  Participación (mensual) 
                $linea .= $remuneracion->gratificacion.";"; //  Gratificación (mensual)  
                $linea .= "0;"; //  Recargo 30% día domingo (Art. 38) 
                $linea .= "0;"; //  Remuneración variable pagada en vacaciones (Art. 71)
                $linea .= "0;"; //  Remuneración variable pagada en clausura (Art. 38 DFL 2) 
                $linea .= $remuneracion->aguinaldobruto.";"; //  Aguinaldo
                $linea .= $remuneracion->bonosimponibles.";"; //  Bonos u otras remuneraciones fijas mensuales
                $linea .= "0;"; //  Tratos (mensual)
                $linea .= "0;"; //  Bonos u otras remuneraciones variables mensuales o superiores a un mes 
                $linea .= "0;"; //  Ejercicio opción no pactada en contrato (Art. 17 N°8 LIR)  
                $linea .= "0;"; //  Beneficios en especie constitutivos de remuneración 
                $linea .= "0;"; //  Remuneraciones bimestrales (devengo en dos meses)  
                $linea .= "0;"; //  Remuneraciones trimestrales (devengo en tres meses) 
                $linea .= "0;"; //  Remuneraciones cuatrimestral (devengo en cuatro meses
                $linea .= "0;"; //  Remuneraciones semestrales (devengo en seis meses)  
                $linea .= "0;"; //  Remuneraciones anuales (devengo en doce meses)
                $linea .= "0;"; //  Participación anual (devengo en doce meses) 
                $linea .= "0;"; //  Gratificación anual (devengo en doce meses) 
                $linea .= "0;"; //  Otras remuneraciones superiores a un mes 
                $linea .= "0;"; //  Pago por horas de trabajo sindical
                $linea .= "0;";  //  Sueldo empresarial (2161)

                //Subcategoría N°2: Haberes imponibles y no tributables
  
                $linea .= "0;"; //  Subsidio por incapacidad laboral por licencia médica - total mensual  
                $linea .= "0;"; //  Beca de estudio (Art. 17 N°18 LIR) 
                $linea .= "0;"; //  Gratificaciones de zona (Art. 17 N°27) 
                $linea .= "0;"; //  Otros ingresos no constitutivos de renta (Art. 17 N°29 LIR)

                //Subcategoría N°3: Haberes no imponibles y no tributables

                $linea .= $remuneracion->colacion.";"; //  Colación total mensual (Art. 41)
                $linea .= $remuneracion->movilizacion.";"; //  Movilización total mensual (Art. 41)
                $linea .= "0;"; //  Viáticos total mensual (Art. 41)
                $linea .= "0;"; //  Asignación de pérdida de caja total mensual (Art. 41)
                $linea .= "0;"; //  Asignación de desgaste herramienta total mensual (Art. 41)
                $linea .= $remuneracion->asigfamiliar.";"; //  Asignación familiar legal total mensual (Art. 41) 
                $linea .= "0;"; //  Gastos por causa del trabajo (Art. 41)  
                $linea .= "0;"; //  Gastos por cambio de residencia (Art. 53)
                $linea .= "0;"; //  Sala cuna (Art. 203)  
                $linea .= "0;"; //  Asignación trabajo a distancia o teletrabajo
                $linea .= "0;"; //  Depósito convenido hasta UF 900 
                $linea .= "0;"; //  Alojamiento por razones de trabajo (Art. 17 N°14 LIR)
                $linea .= "0;"; //  Asignación de traslación (Art. 17 N°15 LIR)  
                $linea .= "0;"; //  BIndemnización por feriado legal 
                $linea .= "0;"; //  Indemnización años de servicio 
                $linea .= "0;"; //  Indemnización sustitutiva del aviso previo
                $linea .= "0;"; //  Indemnización fuero maternal (Art. 163 bis)
                $linea .= "0;"; //  Indemnización a todo evento (Art. 164)  


                // Subcategoría N°4: Haberes no imponibles y tributables
                $linea .= "0;"; //  Indemnizaciones voluntarias tributables
                $linea .= "0;"; //  Indemnizaciones contractuales tributables

                //Categoría 3: Descuentos
                $linea .= ($remuneracion->cotizacionobligatoria + $remuneracion->comisionafp).";"; //  Cotización obligatoria previsional (AFP o IPS
                $linea .= ($remuneracion->cotizacionsalud + $remuneracion->fonasa + $remuneracion->inp).";"; //  Cotización obligatoria salud 7%  
                $linea .= $remuneracion->adicsalud.";"; //  Cotización voluntaria para salud
                $linea .= $remuneracion->segcesantia.";"; //  Cotización AFC - trabajador
                $linea .= "0;"; //  Cotizaciones técnico extranjero para seguridad social fuera de Chile
                $linea .= "0;"; //  Descuento depósito convenido hasta UF 900 anual 
                $linea .= "0;"; //  Cotización ahorro previsional voluntario individual modalidad A
                $linea .= "0;"; //   Cotización ahorro previsional voluntario individual modalidad B hasta UF 50
                $linea .= "0;"; //  Cotización ahorro previsional voluntario colectivo modalidad A 
                $linea .= "0;"; //  Cotización ahorro previsional voluntario colectivo modalidad B hasta UF 50 
                $linea .= $remuneracion->impuesto.";"; //  Impuesto retenido por remuneraciones 
                $linea .= "0;"; //  Impuesto retenido por indemnizaciones
                $linea .= "0;"; //  Mayor retención de impuestos solicitada por el trabajador
                $linea .= "0;"; //  Impuesto retenido por reliquidación remuneraciones devengadas en otros períodos
                $linea .= "0;"; //  Diferencia de impuesto por reliquidación remuneraciones devengadas en este período
                $linea .= "0;"; //  Retención préstamo clase media 2020 (Ley 21.252) (3166)
                $linea .= "0;"; //  Rebaja zona extrema DL 889 (3167)
                $linea .= "0;"; //  Cuota sindical 1
                $linea .= "0;"; //  Cuota sindical 2 
                $linea .= "0;"; //  Cuota sindical 3 
                $linea .= "0;"; //  Cuota sindical 4 
                $linea .= "0;"; //  Cuota sindical 5 
                $linea .= "0;"; //  Cuota sindical 6 
                $linea .= "0;"; //  Cuota sindical 7 
                $linea .= "0;"; //  Cuota sindical 8 
                $linea .= "0;"; //  Cuota sindical 9 
                $linea .= "0;"; //  Cuota sindical 10 
                $linea .= "0;"; //  Crédito social CCAF 
                $linea .= "0;"; //  Cuota vivienda o educación (Art. 58) 
                $linea .= "0;"; //  Crédito cooperativas de ahorro (Art 54. Ley Coop.)
                $linea .= "0;"; //  Otros descuentos autorizados y solicitados por el trabajador 
                $linea .= "0;"; //  Cotización adicional trabajo pesado - trabajador
                $linea .= "0;"; //  Donaciones culturales y de reconstrucción 
                $linea .= "0;"; //  Otros descuentos (Art. 58)  
                $linea .= "0;"; //  Pensiones de alimentos 
                $linea .= "0;"; //  Descuento mujer casada (Art. 59) 
                $linea .= $remuneracion->otrosdescuentos.";"; //  Descuentos por anticipos y préstamos 

                //Categoría 4: Aportes del Empleador
                $linea .= $remuneracion->aportesegcesantia.";"; //  Aporte AFC - empleador 
                $linea .= $remuneracion->aportepatronal.";"; //  Aporte empleador seguro accidentes del trabajo y Ley SANNA (Ley 16.744)
                $linea .= "0;"; //  Aporte empleador indemnización a todo evento (Art. 164)
                $linea .= "0;"; //  Aporte adicional trabajo pesado - empleador 
                $linea .= $remuneracion->seginvalidez.";"; //  Aporte empleador seguro invalidez y sobrevivencia
                $linea .= "0;"; //  Aporte empleador ahorro previsional voluntario colectivo

                //Categoría 5: Totales
                //Subcategoría N°1: Haberes
                $linea .= $remuneracion->totalhaberes.";"; //  Total haberes
                $linea .= $remuneracion->sueldoimponible.";"; //  Total haberes imponibles y tributables
                $linea .= "0;"; //  Total haberes imponibles no tributables
                $linea .= ($remuneracion->totalhaberes - $remuneracion->sueldoimponible).";"; //  Total haberes no imponibles y no tributables
                $linea .= "0;"; //  Total haberes no imponibles y tributables

                //Subcategoría N°2: Descuentos
                $linea .= $remuneracion->totaldescuentos.";"; //  Total descuentos
                $linea .= $remuneracion->impuesto.";"; //  Total descuentos impuestos a las remuneraciones
                $linea .= "0;"; //  Total descuentos impuestos por indemnizaciones
                $linea .= $remuneracion->totalleyessociales.";"; //  Total descuentos por cotizaciones del trabajador
                $linea .= $remuneracion->otrosdescuentos.";"; //  Total otros descuentos

                //Subcategoría N°3: Aportes
                $linea .= ($remuneracion->aportepatronal + $remuneracion->aportesegcesantia + $remuneracion->seginvalidez).";"; //  Total aportes empleador

                //Subcategoría N°4: Otros resultados totales
                $linea .= $remuneracion->sueldoliquido.";"; //  Total líquido
                $linea .= "0;"; //  Total indemnizaciones
                $linea .= "0;"; //  Total indemnizaciones tributables
                $linea .= "0;"; //  total indemnizaciones no tributables 


                $linea .= "\r\n";
                //$linea = $rut.$dv.$apaterno.$amaterno.$nombres."\r\n";
                fputs($file, utf8_decode($linea));
               // echo $linea."<br>";
        }

        //exit;
        fclose($file);

        $data_archivo = basename($path_archivo . $nombre_archivo);
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $data_archivo);
        header('Content-Length: ' . filesize($path_archivo . $nombre_archivo));
        readfile($path_archivo . $nombre_archivo);


        unlink($path_archivo . $nombre_archivo);
    }



    public function libro($datos_remuneracion)
    {

        //$this->load->library('PHPExcel');
        //$this->phpexcel->setActiveSheetIndex(0);
        //$sheet = $this->phpexcel->getActiveSheet();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle("libro_remuneraciones");


        $this->load->model('admin');
        $datos_comunidad = $this->admin->datos_comunidad($this->session->userdata('comunidadid'));

        /********* COMIENZA A CREAR EXCEL *******/
        // DATOS INICIALES
        $sheet->getColumnDimension('A')->setWidth(5);


        $sheet->mergeCells('B2:D2');
        $sheet->setCellValue('B2', 'Libro Remuneraciones');
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->setCellValue('B3', 'Nombre Comunidad');
        $sheet->setCellValue('C3', html_entity_decode($this->session->userdata('comunidadnombre')));
        $sheet->mergeCells('C3:D3');
        $sheet->setCellValue('B4', 'Rut Comunidad');
        $sheet->setCellValue('C4', number_format($datos_comunidad->rut, 0, ".", ".") . '-' . $datos_comunidad->dv);
        $sheet->mergeCells('C4:D4');
        $sheet->setCellValue('B5', 'Direccion Comunidad');
        $sheet->setCellValue('C5', $datos_comunidad->direccion . ", " . $datos_comunidad->comuna);
        $sheet->mergeCells('C5:D5');
        $sheet->setCellValue('B6', 'Fecha emision Reporte');
        $sheet->setCellValue('C6', date('d/m/Y'));
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
        $sheet->setCellValue('B' . $i, 'Datos Personales');
        $sheet->mergeCells('B' . $i . ':E' . $i);


        $sheet->setCellValue('F' . $i, 'Resumen');
        $sheet->mergeCells('F' . $i . ':K' . $i);


        $sheet->setCellValue('L' . $i, 'Detalle');
        $sheet->mergeCells('L' . $i . ':AS' . $i);
        $i++;



        //ENCABEZADO REPORTE

        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->setCellValue('B' . $i, '#');
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('C' . $i, 'Rut');
        $sheet->getColumnDimension('D')->setWidth(35);
        $sheet->setCellValue('D' . $i, 'Nombre');
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->setCellValue('E' . $i, 'Fecha Ingreso');

        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->setCellValue('F' . $i, 'Total AFP');
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->setCellValue('G' . $i, 'Total Salud');
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->setCellValue('H' . $i, 'Total Imponible');
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->setCellValue('I' . $i, 'Total Haberes');
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->setCellValue('J' . $i, 'Total Descuento');
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->setCellValue('K' . $i, 'Líquido a Pagar');

        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->setCellValue('L' . $i, 'Sueldo Base');
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->setCellValue('M' . $i, 'Gratificación');
        $sheet->getColumnDimension('N')->setWidth(15);
        $sheet->setCellValue('N' . $i, 'Movilización');
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->setCellValue('O' . $i, 'Colación');
        $sheet->getColumnDimension('P')->setWidth(15);
        $sheet->setCellValue('P' . $i, 'Bonos Imponibles');
        $sheet->getColumnDimension('Q')->setWidth(15);
        $sheet->setCellValue('Q' . $i, 'Bonos No Imponibles');
        $sheet->getColumnDimension('R')->setWidth(15);
        $sheet->setCellValue('R' . $i, 'Horas Extras 50%');
        $sheet->getColumnDimension('S')->setWidth(15);
        $sheet->setCellValue('S' . $i, 'Horas Extras 100%');
        $sheet->getColumnDimension('T')->setWidth(15);
        $sheet->setCellValue('T' . $i, 'Aguinaldo');
        $sheet->getColumnDimension('U')->setWidth(15);
        $sheet->setCellValue('U' . $i, 'Asignación Familiar');
        $sheet->getColumnDimension('V')->setWidth(15);
        $sheet->setCellValue('V' . $i, 'Total Haberes');
        $sheet->getColumnDimension('W')->setWidth(15);
        $sheet->setCellValue('W' . $i, 'Cotización Obligatoria');
        $sheet->getColumnDimension('X')->setWidth(15);
        $sheet->setCellValue('X' . $i, 'Comisión AFP');
        $sheet->getColumnDimension('Y')->setWidth(15);
        $sheet->setCellValue('Y' . $i, 'Adicional AFP');
        $sheet->getColumnDimension('Z')->setWidth(15);
        $sheet->setCellValue('Z' . $i, 'Ahorro Voluntario');
        $sheet->getColumnDimension('AA')->setWidth(15);
        $sheet->setCellValue('AA' . $i, 'APV');
        $sheet->getColumnDimension('AB')->setWidth(15);
        $sheet->setCellValue('AB' . $i, 'Cotización Salud Obligatoria');
        $sheet->getColumnDimension('AC')->setWidth(15);
        $sheet->setCellValue('AC' . $i, 'Cotización Adicional Isapre');
        $sheet->getColumnDimension('AD')->setWidth(15);
        $sheet->setCellValue('AD' . $i, 'Adicional Salud');
        $sheet->getColumnDimension('AE')->setWidth(15);
        $sheet->setCellValue('AE' . $i, 'Fonasa');
        $sheet->getColumnDimension('AF')->setWidth(15);
        $sheet->setCellValue('AF' . $i, 'Seguro Cesantía');
        $sheet->getColumnDimension('AG')->setWidth(15);
        $sheet->setCellValue('AG' . $i, 'Impuesto');
        $sheet->getColumnDimension('AH')->setWidth(15);
        $sheet->setCellValue('AH' . $i, 'Total Leyes Sociales');
        $sheet->getColumnDimension('AI')->setWidth(15);
        $sheet->setCellValue('AI' . $i, 'Anticipo');
        $sheet->getColumnDimension('AJ')->setWidth(15);
        $sheet->setCellValue('AJ' . $i, 'Descuento por Aguinaldo');
        $sheet->getColumnDimension('AK')->setWidth(15);
        $sheet->setCellValue('AK' . $i, 'Horas Descuento');
        $sheet->getColumnDimension('AL')->setWidth(15);
        $sheet->setCellValue('AL' . $i, 'Otros Descuentos');
        $sheet->getColumnDimension('AM')->setWidth(15);
        $sheet->setCellValue('AM' . $i, 'Préstamos');
        $sheet->getColumnDimension('AN')->setWidth(15);
        $sheet->setCellValue('AN' . $i, 'Total Otros Descuentos');
        $sheet->getColumnDimension('AO')->setWidth(15);
        $sheet->setCellValue('AO' . $i, 'Líquido a Pagar');
        $sheet->getColumnDimension('AP')->setWidth(15);
        $sheet->setCellValue('AP' . $i, 'Aporte Seguro Cesantía');
        $sheet->getColumnDimension('AQ')->setWidth(15);
        $sheet->setCellValue('AQ' . $i, 'Aporte SIS');
        $sheet->getColumnDimension('AR')->setWidth(15);
        $sheet->setCellValue('AR' . $i, 'Mutual de Seguridad');
        $sheet->getColumnDimension('AS')->setWidth(20);
        $sheet->setCellValue('AS' . $i, 'Total Aportes Patronales');



        $columnaFinal = 44;
        $mergeTotal = 37;
        $columnaTotales = 44;
        $sheet->getStyle("B" . ($i - 1) . ":" . ordenLetrasExcel($columnaFinal) . $i)->getFont()->setBold(true);

        $filaInicio = $i - 1;
        $i++;
        //$sheet->getStyle("B7:I7")->getFont()->setSize(11);
        $linea = 1;
        foreach ($datos_remuneracion as $remuneracion) {

            $datos_bonos_imponibles = $this->get_bonos_by_remuneracion($remuneracion->id, true);
            $bonos_imponibles = 0;
            foreach ($datos_bonos_imponibles as $bono_imponible) {
                $bonos_imponibles += $bono_imponible->monto;
            }


            $datos_bonos_no_imponibles = $this->get_bonos_by_remuneracion($remuneracion->id, false);
            $bonos_no_imponibles = 0;
            foreach ($datos_bonos_no_imponibles as $bono_no_imponible) {
                $bonos_no_imponibles += $bono_no_imponible->monto;
            }

            $datos_descuentos = $this->get_descuento($remuneracion->idperiodo, 'D', $remuneracion->idtrabajador);
            $monto_descuento = 0;
            foreach ($datos_descuentos as $dato_descuento) {
                $monto_descuento += $dato_descuento->monto;
            }

            $datos_prestamos = $this->get_descuento($remuneracion->idperiodo, 'P', $remuneracion->idtrabajador);
            $monto_prestamo = 0;
            foreach ($datos_prestamos as $dato_prestamo) {
                $monto_prestamo += $dato_prestamo->monto;
            }

            $sheet->setCellValue("B" . $i, $linea);
            $sheet->setCellValue("C" . $i, $remuneracion->rut . "-" . $remuneracion->dv);
            $sheet->setCellValue("D" . $i, $remuneracion->nombre . " " . $remuneracion->apaterno . " " . $remuneracion->amaterno);
            $sheet->setCellValue("E" . $i, $remuneracion->fecingreso);

            $sheet->setCellValue("F" . $i, $remuneracion->cotizacionobligatoria + $remuneracion->comisionafp);
            $sheet->getStyle('F' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("G" . $i, $remuneracion->cotizacionsalud + $remuneracion->fonasa + $remuneracion->inp + $remuneracion->adicisapre);
            $sheet->getStyle('G' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("H" . $i, $remuneracion->sueldoimponible);
            $sheet->getStyle('H' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("I" . $i, $remuneracion->totalhaberes);
            $sheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("J" . $i, $remuneracion->totaldescuentos);
            $sheet->getStyle('J' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("K" . $i, $remuneracion->sueldoliquido);
            $sheet->getStyle('K' . $i)->getNumberFormat()->setFormatCode('#,##0');


            $sheet->setCellValue("L" . $i, $remuneracion->sueldobase);
            $sheet->getStyle('L' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("M" . $i, $remuneracion->gratificacion);
            $sheet->getStyle('M' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("N" . $i, $remuneracion->movilizacion);
            $sheet->getStyle('N' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("O" . $i, $remuneracion->colacion);
            $sheet->getStyle('O' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("P" . $i, $bonos_imponibles);
            $sheet->getStyle('P' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("Q" . $i, $bonos_no_imponibles);
            $sheet->getStyle('Q' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("R" . $i, $remuneracion->montohorasextras50);
            $sheet->getStyle('R' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("S" . $i, $remuneracion->montohorasextras100);
            $sheet->getStyle('S' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("T" . $i, $remuneracion->aguinaldobruto);
            $sheet->getStyle('T' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("U" . $i, $remuneracion->asigfamiliar);
            $sheet->getStyle('U' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("V" . $i, $remuneracion->totalhaberes);
            $sheet->getStyle('V' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("W" . $i, $remuneracion->cotizacionobligatoria);
            $sheet->getStyle('W' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("X" . $i, $remuneracion->comisionafp);
            $sheet->getStyle('X' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("Y" . $i, $remuneracion->adicafp);
            $sheet->getStyle('Y' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("Z" . $i, $remuneracion->montoahorrovol);
            $sheet->getStyle('Z' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AA" . $i, $remuneracion->montocotapv);
            $sheet->getStyle('AA' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AB" . $i, $remuneracion->cotizacionsalud);
            $sheet->getStyle('AB' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AC" . $i, $remuneracion->cotadicisapre);
            $sheet->getStyle('AC' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AD" . $i, $remuneracion->adicsalud);
            $sheet->getStyle('AD' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AE" . $i, $remuneracion->fonasa + $remuneracion->inp);
            $sheet->getStyle('AE' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AF" . $i, $remuneracion->segcesantia);
            $sheet->getStyle('AF' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AG" . $i, $remuneracion->impuesto);
            $sheet->getStyle('AG' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AH" . $i, $remuneracion->totalleyessociales);
            $sheet->getStyle('AH' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AI" . $i, $remuneracion->anticipo);
            $sheet->getStyle('AI' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AJ" . $i, $remuneracion->aguinaldo);
            $sheet->getStyle('AJ' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AK" . $i, $remuneracion->montodescuento);
            $sheet->getStyle('AK' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AL" . $i, $monto_descuento);
            $sheet->getStyle('AL' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AM" . $i, $monto_prestamo);
            $sheet->getStyle('AM' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AN" . $i, $remuneracion->otrosdescuentos);
            $sheet->getStyle('AN' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AO" . $i, $remuneracion->sueldoliquido);
            $sheet->getStyle('AO' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AP" . $i, $remuneracion->aportesegcesantia);
            $sheet->getStyle('AP' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AQ" . $i, $remuneracion->seginvalidez);
            $sheet->getStyle('AQ' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AR" . $i, $remuneracion->aportepatronal);
            $sheet->getStyle('AR' . $i)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue("AS" . $i, $remuneracion->aportesegcesantia + $remuneracion->seginvalidez + $remuneracion->aportepatronal);
            $sheet->getStyle('AS' . $i)->getNumberFormat()->setFormatCode('#,##0');

            if ($i % 2 != 0) {
                //echo "consulta 4: -- i : ".$i. "  -- mod : ". ($i % 2)."<br>";
                $sheet->getStyle("B" . $i . ":" . ordenLetrasExcel($columnaFinal) . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle("B" . $i . ":" . ordenLetrasExcel($columnaFinal) . $i)->getFill()->getStartColor()->setRGB('F7F9FD');
            }
            $i++;
            $linea++;
        }
        $i--;




        $sheet->getStyle("B" . $filaInicio . ":" . ordenLetrasExcel($columnaFinal) . $i)->getFont()->setSize(10);

        /*************************todos los bordes internos *************************************/
        $sheet->getStyle("B" . $filaInicio . ":" . ordenLetrasExcel($columnaFinal) . $i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


        /*************************bordes cuadro principal (externo) *************************************/
        for ($j = 1; $j <= $columnaFinal; $j++) { //borde superior
            $sheet->getStyle(ordenLetrasExcel($j) . $filaInicio)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        }

        for ($j = 1; $j <= $columnaFinal; $j++) { //borde inferior
            $sheet->getStyle(ordenLetrasExcel($j) . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        }

        for ($n = $filaInicio; $n <= $i; $n++) { //borde izquierdo
            $sheet->getStyle("B" . $n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        }

        for ($n = $filaInicio; $n <= $i; $n++) { //borde derecho
            $sheet->getStyle(ordenLetrasExcel($columnaFinal) . $n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        }

        /**********************************************************************************************************/


        /***************************** Segundo borde superior********************************************************/

        for ($j = 1; $j <= $columnaFinal; $j++) { //borde inferior
            $sheet->getStyle(ordenLetrasExcel($j) . $filaInicio)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        }

        for ($j = 1; $j <= $columnaFinal; $j++) { //borde inferior
            $sheet->getStyle(ordenLetrasExcel($j) . ($filaInicio + 1))->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        }

        /******************************************************************************************************/


        /***************************** Penultimo borde izquierdo ********************************************************/

        /*	for($n=$filaInicio+1;$n<=$i;$n++){ //borde derecho
						$sheet->getStyle("B".$n)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
					}*/

        /******************************************************************************************************/



        /***************************** Penultimo borde derecho ********************************************************/

        for ($n = $filaInicio; $n <= $i; $n++) { //borde derecho
            $sheet->getStyle(ordenLetrasExcel($columnaFinal) . $n)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        }

        /******************************************************************************************************/

        /***************************** Color fila superior********************************************************/

        for ($j = 1; $j <= $columnaFinal; $j++) { //color fondo inferior
            $sheet->getStyle(ordenLetrasExcel($j) . $filaInicio)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle(ordenLetrasExcel($j) . $filaInicio)->getFill()->getStartColor()->setRGB('E8EDFF');
        }


        for ($j = 1; $j <= $columnaFinal; $j++) { //color fondo inferior
            $sheet->getStyle(ordenLetrasExcel($j) . ($filaInicio + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle(ordenLetrasExcel($j) . ($filaInicio + 1))->getFill()->getStartColor()->setRGB('E8EDFF');
        }

        /******************************************************************************************************/


        /***************************** Color primera columna ********************************************************/
        $sheet->getStyle("B" . $filaInicio . ":B" . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle("B" . $filaInicio . ":B" . $i)->getFill()->getStartColor()->setRGB('E8EDFF');

        /******************************************************************************************************/


        /***************************** Color montos ********************************************************/

        $sheet->getStyle("V" . $filaInicio . ":V" . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle("V" . $filaInicio . ":V" . $i)->getFill()->getStartColor()->setRGB('E8EDFF');

        $sheet->getStyle("AH" . $filaInicio . ":AH" . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle("AH" . $filaInicio . ":AH" . $i)->getFill()->getStartColor()->setRGB('E8EDFF');

        $sheet->getStyle("AN" . $filaInicio . ":AN" . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle("AN" . $filaInicio . ":AN" . $i)->getFill()->getStartColor()->setRGB('E8EDFF');
        $sheet->getStyle("AO" . $filaInicio . ":AO" . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle("AO" . $filaInicio . ":AO" . $i)->getFill()->getStartColor()->setRGB('E8EDFF');
        $sheet->getStyle("AS" . $filaInicio . ":AS" . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle("AS" . $filaInicio . ":AS" . $i)->getFill()->getStartColor()->setRGB('E8EDFF');
        /******************************************************************************************************/



        //Algunos bordes
        $sheet->getStyle("F" . $filaInicio)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        $sheet->getStyle("F" . ($filaInicio + 1))->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);



        $sheet->getStyle("L" . $filaInicio)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
        $sheet->getStyle("L" . ($filaInicio + 1))->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);



        $sheet->setSelectedCells('E1'); //celda seleccionada



        header("Content-Type: application/vnd.ms-excel");
        $nombreArchivo = 'libro_remuneraciones';
        header("Content-Disposition: attachment; filename=\"$nombreArchivo.xlsx\"");
        header("Cache-Control: max-age=0");
        // Genera Excel
        $writer = new Xlsx($spreadsheet); //objeto de PHPExcel, para escribir en el excel
        //$writer = new PHPExcel_Writer_Excel2007($this->phpexcel); //objeto de PHPExcel, para escribir en el excel
        // Escribir
        //$writer->setIncludeCharts(TRUE);
        $writer->save('php://output');
        exit;
    }



    public function add_afp($array_datos)
    {


        $this->db->select('a.id')
            ->from('gc_afp as a')
            ->where('upper(a.nombre)', strtoupper($array_datos['nombre']))
            ->where('a.active = 1');

        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // nueva afp  no existe
            if ($array_datos['idafp'] == 0) {
                $data = array(
                    'nombre' => $array_datos['nombre'],
                    'porc' => $array_datos['porc'],
                    'exregimen' => $array_datos['exregimen']
                );

                $this->db->insert('gc_afp', $data);
                $idafp = $this->db->insert_id();

                return 1;
            } else {
                $data = array(
                    'nombre' => $array_datos['nombre'],
                    'porc' => $array_datos['porc'],
                    'exregimen' => $array_datos['exregimen']
                );


                $this->db->where('id', $array_datos['idafp']);
                $this->db->update('gc_afp', $data);
                return 1;
            }
        } else { // ya existe proveedor nuevo

            if ($array_datos['idafp'] != 0) {
                $data = array(
                    'nombre' => $array_datos['nombre'],
                    'porc' => $array_datos['porc'],
                    'exregimen' => $array_datos['exregimen']
                );


                $this->db->where('id', $array_datos['idafp']);
                $this->db->update('gc_afp', $data);
                return 1;
            } else {
                return -1;
            }
        }
    }


    public function delete_afp($idafp)
    {


        $this->db->where('id', $idafp);
        $this->db->update('gc_afp', array('active' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente
            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }


    public function add_isapre($array_datos)
    {


        $this->db->select('i.id')
            ->from('gc_isapre as i')
            ->where('upper(i.nombre)', strtoupper($array_datos['nombre']))
            ->where('i.active = 1');

        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // nueva afp  no existe
            if ($array_datos['idisapre'] == 0) {
                $data = array(
                    'nombre' => $array_datos['nombre']
                );

                $this->db->insert('gc_isapre', $data);
                $idafp = $this->db->insert_id();

                return 1;
            } else {
                $data = array(
                    'nombre' => $array_datos['nombre']
                );


                $this->db->where('id', $array_datos['idisapre']);
                $this->db->update('gc_isapre', $data);
                return 1;
            }
        } else { // ya existe proveedor nuevo

            if ($array_datos['idisapre'] != 0) {
                $data = array(
                    'nombre' => $array_datos['nombre']
                );


                $this->db->where('id', $array_datos['idisapre']);
                $this->db->update('gc_isapre', $data);
                return 1;
            } else {
                return -1;
            }
        }
    }


    public function add_feriado($array_datos)
    {


        $this->db->select('f.id')
            ->from('gc_feriado as f')
            ->where('f.fecha', strtoupper($array_datos['fecha']))
            ->where('f.active = 1');

        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // nueva afp  no existe
            if ($array_datos['idferiado'] == 0) {
                $data = array(
                    'fecha' => $array_datos['fecha']
                );

                $this->db->insert('gc_feriado', $data);
                $idferiado = $this->db->insert_id();

                return 1;
            } else {
                $data = array(
                    'fecha' => $array_datos['fecha']
                );


                $this->db->where('id', $array_datos['idferiado']);
                $this->db->update('gc_feriado', $data);
                return 1;
            }
        } else { // ya existe feriado

            if ($array_datos['idferiado'] != 0) {
                $data = array(
                    'fecha' => $array_datos['fecha']
                );


                $this->db->where('id', $array_datos['idferiado']);
                $this->db->update('gc_feriado', $data);
                return 1;
            } else {
                return -1;
            }
        }
    }


    public function delete_feriado($idferiado)
    {


        $this->db->where('id', $idferiado);
        $this->db->update('gc_feriado', array('active' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente
            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }


    public function delete_isapre($idisapre)
    {


        $this->db->where('id', $idisapre);
        $this->db->update('gc_isapre', array('active' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente
            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }


    public function add_descuento($array_datos, $mes, $anno)
    {

        $this->db->trans_start();

        // evaluar si existe periodo
        $this->db->select('p.id')
            ->from('gc_periodo as p')
            ->where('p.mes', $mes)
            ->where('p.anno', $anno);
        $query = $this->db->get();
        $datos_periodo = $query->row();
        $idperiodo = 0;
        if (is_null($datos_periodo)) { // si no existe periodo, se crea
            $data = array(
                'mes' => $mes,
                'anno' =>  $anno
            );
            $this->db->insert('gc_periodo', $data);
            $idperiodo = $this->db->insert_id();
        } else {
            $idperiodo = $datos_periodo->id;
        }


        // evaluar si existe periodo remuneraciones
        /*$this->db->select('r.idperiodo')
						  ->from('gc_periodo_remuneracion as r')
		                  ->where('r.idperiodo', $idperiodo)
		                  ->where('r.idcomunidad', $this->session->userdata('comunidadid'));
		$query = $this->db->get();
		$datos_periodo_remuneracion = $query->row();
		if(count($datos_periodo_remuneracion) == 0){ // si no existe periodo, se crea
				$data = array(
			      	'idperiodo' => $idperiodo,
			      	'idcomunidad' => $this->session->userdata('comunidadid')
				);
				$this->db->insert('gc_periodo_remuneracion', $data);
		}*/
        $array_datos['idperiodo'] = $idperiodo;

        $this->db->insert('gc_descuentos_personal', $array_datos);

        $this->db->trans_complete();
        return 1;
    }



    public function edit_descuento($iddescuento, $array_datos)
    {

        $this->db->trans_start();

        $this->db->where('id', $iddescuento);
        $this->db->update('gc_descuentos_personal', $array_datos);

        $this->db->trans_complete();
        return 1;
    }

    public function delete_descuento($iddescuento)
    {
        $this->db->where('id', $iddescuento);
        $this->db->delete('gc_descuentos_personal');
        return true;
    }


    public function solicita_vacaciones($array_datos)
    {

        $this->db->trans_start();


        $personal = $this->remuneracion->get_personal($array_datos['idpersonal']);
        $dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones);


        $dias_progresivos = $this->remuneracion->get_dias_progresivos($array_datos['idpersonal']);
        $num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);

        $saldo_vacaciones = $dias_vacaciones + $num_dias_progresivos - $personal->diasvactomados;


        $num_dias = (int)$saldo_vacaciones;
        if ($array_datos['idcartola'] == 0) {

            if ($array_datos['dias'] > $num_dias) {
                $this->db->trans_complete();
                return false;
            }

            $array_insert = array(
                'idpersonal' => $array_datos['idpersonal'],
                'fecinicio' => $array_datos['fecinicio'],
                'fecfin' => $array_datos['fecfin'],
                'dias' => $array_datos['dias'],
                'comentarios' => $array_datos['comentarios'],
                'created_at' => $array_datos['created_at']
            );

            #CREA CARTOLAS
            $this->db->insert('gc_cartola_vacaciones', $array_insert);

            $this->db->query('update gc_personal set diasvactomados = diasvactomados + ' . $array_datos['dias'] . ' where id = ' . $array_datos['idpersonal']);
        } else {

            $cartola = $this->remuneracion->get_cartola_vacaciones($array_datos['idpersonal'], $array_datos['idcartola']);

            if (is_null($cartola)) {
                $this->db->trans_complete();
                return false;
            } else {

                $diff_dias = $array_datos['dias'] - $cartola->dias;
                if ($diff_dias > $num_dias) {
                    $this->db->trans_complete();
                    return false;
                }

                $array_update = array(
                    'fecinicio' => $array_datos['fecinicio'],
                    'fecfin' => $array_datos['fecfin'],
                    'dias' => $array_datos['dias'],
                    'comentarios' => $array_datos['comentarios']
                );

                $this->db->where('id', $array_datos['idcartola']);
                $this->db->where('idpersonal', $array_datos['idpersonal']);
                $this->db->update('gc_cartola_vacaciones', $array_update);


                $this->db->query('update gc_personal set diasvactomados = diasvactomados + ' . $diff_dias . ' where id = ' . $array_datos['idpersonal']);
            }
        }

        $this->db->trans_complete();
        return true;
    }


    public function add_dia_progresivo($array_datos)
    {

        $this->db->trans_start();

        if ($array_datos['idcartola'] == 0) {

            $this->db->query("update gc_personal set diasprogresivos = diasprogresivos + " . $array_datos['dias'] . " where id = " . $array_datos['idpersonal']);
            /*$personal = $this->get_personal($array_datos['idpersonal']);
			$diasprogresivos =  $personal->diasprogresivos;*/

            $array_dia_progresivo = array(
                'idpersonal' => $array_datos['idpersonal'],
                'fechainicio' => $array_datos['periodo'],
                'dias' => $array_datos['dias'],
                'created_at' => $array_datos['created_at']
            );
            $this->db->insert('gc_dias_progresivos', $array_dia_progresivo);
        } else {

            $cartola = $this->remuneracion->get_dias_progresivos($array_datos['idpersonal'], $array_datos['idcartola']);

            if (is_null($cartola)) {
                $this->db->trans_complete();
                return 2;
            } else {

                $diff_dias = $array_datos['dias'] - $cartola->dias;
                /*if($diff_dias > $num_dias){
					$this->db->trans_complete();
					return false;
				}*/

                $array_update = array(
                    'fechainicio' => $array_datos['periodo'],
                    'idpersonal' => $array_datos['idpersonal'],
                    'dias' => $array_datos['dias'],
                );

                $this->db->where('id', $array_datos['idcartola']);
                $this->db->where('idpersonal', $array_datos['idpersonal']);
                $this->db->update('gc_dias_progresivos', $array_update);


                $personal = $this->remuneracion->get_personal($array_datos['idpersonal']);
                $dias_vacaciones = dias_vacaciones($personal->fecinicvacaciones, $personal->saldoinicvacaciones);
                $dias_progresivos = $this->remuneracion->get_dias_progresivos($array_datos['idpersonal']);
                $num_dias_progresivos = num_dias_progresivos($personal->fecinicvacaciones, $personal->saldoinicvacprog, $dias_progresivos);
                $saldo_vacaciones = $dias_vacaciones + $num_dias_progresivos - $personal->diasvactomados;


                if ($saldo_vacaciones < 0) {

                    $array_update['dias'] = $cartola->dias;
                    $this->db->where('id', $array_datos['idcartola']);
                    $this->db->where('idpersonal', $array_datos['idpersonal']);
                    $this->db->update('gc_dias_progresivos', $array_update);
                    $this->db->trans_complete();
                    return 3;
                }

                $this->db->query('update gc_personal set diasprogresivos = diasprogresivos + ' . $diff_dias . ' where id = ' . $array_datos['idpersonal']);
            }
        }

        $this->db->trans_complete();
        return 1;
    }




    public function add_movimiento_personal($array_datos)
    {

        $this->db->trans_start();

        # YA SEA PARA EDITAR O AGREGAR, EL PERIODO AGREGADO NO PUEDE SER DE UN PERIODO CERRADO
        $mes = substr($array_datos['fecmovimiento'], 5, 2);
        $anno = substr($array_datos['fecmovimiento'], 0, 4);
        $this->load->model('admin');
        $periodo = $this->admin->get_periodo_by_mes($mes, $anno);
        if (!is_null($periodo)) {
            $idperiodo = $periodo->id;
            $periodo_cerrado = $this->get_periodos_cerrados($this->session->userdata('comunidadid'), $idperiodo);

            if (!is_null($periodo_cerrado)) {
                $this->db->trans_complete();
                return 5;
            }
        }

        $mes_hasta = substr($array_datos['fechastamovimiento'], 5, 2);
        $anno_hasta = substr($array_datos['fechastamovimiento'], 0, 4);


        if ($anno . $mes != $anno_hasta . $mes_hasta) {
            return 6;
        }




        if ($array_datos['idmovimiento'] == 0) {


            // validar si movimiento corresponde a un período ya cerrado

            $array_movimiento = array(
                'idpersonal' => $array_datos['idpersonal'],
                'idmovimiento' => $array_datos['movimientos'],
                'comentario' => $array_datos['comentarios'],
                //'fecmovimiento' => formato_fecha($array_datos['fecmovimiento'],'d/m/Y','Y-m-d'),
                'fecmovimiento' => $array_datos['fecmovimiento'],
                'fechastamovimiento' => $array_datos['fechastamovimiento'],
                'created_at' => $array_datos['created_at']
            );
            $this->db->insert('gc_lista_movimiento_personal', $array_movimiento);


            $this->db->trans_complete();
            return 1;
        } else {

            $movimiento_realizado = $this->get_lista_movimientos($array_datos['idpersonal'], $array_datos['idmovimiento']);

            if (is_null($movimiento_realizado)) {
                $this->db->trans_complete();
                return 3;
            } else {

                // validar si movimiento corresponde a un período ya cerrado
                $mes = substr($movimiento_realizado->fecmovimiento, 5, 2);
                $anno = substr($movimiento_realizado->fecmovimiento, 0, 4);

                $this->load->model('admin');
                $periodo = $this->admin->get_periodo_by_mes($mes, $anno);

                if (!is_null($periodo)) {
                    $idperiodo = $periodo->id;
                    $periodo_cerrado = $this->get_periodos_cerrados($this->session->userdata('comunidadid'), $idperiodo);

                    if (!is_null($periodo_cerrado)) {
                        $this->db->trans_complete();
                        return 4;
                    }
                }




                $array_movimiento = array(
                    'idpersonal' => $array_datos['idpersonal'],
                    'idmovimiento' => $array_datos['movimientos'],
                    'comentario' => $array_datos['comentarios'],
                    'fecmovimiento' => $array_datos['fecmovimiento'],
                    'fechastamovimiento' => $array_datos['fechastamovimiento']
                );

                $this->db->where('id', $array_datos['idmovimiento']);
                $this->db->where('idpersonal', $array_datos['idpersonal']);
                $this->db->update('gc_lista_movimiento_personal', $array_movimiento);
                $this->db->trans_complete();
                return 2;
            }
        }
        return 1;
    }


    public function get_lista_movimientos($idpersonal = null, $idmovimiento = null, $idperiodo = null, $tipomovimiento = null)
    {


        #SI BUSCO POR PERIODO
        if (!is_null($idperiodo)) {
            $datos_periodo = $this->get_periodos($this->session->userdata('comunidadid'), $idperiodo);
            $mes = $datos_periodo->mes;
            $anno = $datos_periodo->anno;
        }

        $movimiento_data = $this->db->select('lm.id, lm.idmovimiento, mp.nombre as movimiento, lm.fecmovimiento, lm.fechastamovimiento, lm.comentario, mp.rango, mp.codprevired')
            ->from('gc_lista_movimiento_personal lm')
            ->join('gc_movimientos_personal mp', 'lm.idmovimiento = mp.id')
            ->join('gc_personal p', 'lm.idpersonal = p.id')
            ->where('lm.idpersonal', $idpersonal)
            ->where('lm.active', 1)
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            ->order_by('lm.fecmovimiento', 'asc');

        $movimiento_data = is_null($idmovimiento) ? $movimiento_data : $movimiento_data->where('lm.id', $idmovimiento);
        $movimiento_data = is_null($tipomovimiento) ? $movimiento_data : $movimiento_data->where('mp.id', $tipomovimiento);


        #SI BUSCO POR PERIODO
        if (!is_null($idperiodo)) {
            $movimiento_data = $movimiento_data->where('month(lm.fecmovimiento)', $mes);
            $movimiento_data = $movimiento_data->where('year(lm.fecmovimiento)', $anno);
        }


        $query = $this->db->get();
        return is_null($idmovimiento) ? $query->result() : $query->row();
    }




    public function delete_movimiento_personal($idpersonal, $idmovimiento)
    {
        $this->db->trans_start();

        $movimiento = $this->get_lista_movimientos($idpersonal, $idmovimiento);

        if (is_null($movimiento)) { // movimiento no existe
            $this->db->trans_complete();
            return 2;
        }

        // validar si movimiento corresponde a un período ya cerrado
        $mes = substr($movimiento->fecmovimiento, 5, 2);
        $anno = substr($movimiento->fecmovimiento, 0, 4);

        $this->load->model('admin');
        $periodo = $this->admin->get_periodo_by_mes($mes, $anno);

        if (!is_null($periodo)) {
            $idperiodo = $periodo->id;
            $periodo_cerrado = $this->get_periodos_cerrados($this->session->userdata('comunidadid'), $idperiodo);

            if (!is_null($periodo_cerrado)) {
                $this->db->trans_complete();
                return 3;
            }
        }


        $this->db->where('id', $idmovimiento);
        $this->db->where('idpersonal', $idpersonal);
        $this->db->update('gc_lista_movimiento_personal', array('active' => '0'));

        $this->db->trans_complete();

        return 1;
    }





    public function get_decjurada_honorarios($anno)
    {


        $movimiento_data = $this->db->select('p.id,  p.rut, p.dv, p.nombre, sum(c2.monto) as retencion, c.fecdocumento,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 1 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_enero,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 2 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_febrero,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 3 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_marzo,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 4 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_abril,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 5 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_mayo,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 6 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_junio,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 7 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_julio,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 8 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_agosto,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 9 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_septiembre,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 10 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_octubre,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 11 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_noviembre,
						if((select count(id) as cantidad from gc_cuenta where year(fecdocumento) = ' . $anno . ' and month(fecdocumento) = 12 and idtipodoctrib = 15 and idproveedor is not null and idcomunidad = ' . $this->session->userdata('comunidadid') . ' and active = 1) > 0,"C","") as renta_diciembre
									', false)
            ->from('gc_cuenta c')
            ->join('gc_proveedor p', 'c.idproveedor = p.id')
            ->join('gc_cuenta c2', 'c2.retencionidctaasoc = c.id', 'left')
            ->where('c.idtipodoctrib', 15)
            ->where('c.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('year(c.fecdocumento)', $anno)
            ->where('c.active', 1)
            ->group_by('p.id')
            ->order_by('p.nombre');

        //$movimiento_data = is_null($idmovimiento) ? $movimiento_data : $movimiento_data->where('lm.id',$idmovimiento);


        $query = $this->db->get();
        return $query->result();
    }



    public function get_decjurada_rentas($anno)
    {


        $movimiento_data = $this->db->select('p.id, 
                                             p.rut, 
                                             p.dv, 
                                             p.nombre, 
                                             p.apaterno, 
                                             p.amaterno, 
                                             p.horassemanales,
                                             sum(r.sueldoliquido) as sueldoliquidonoactualiza, 
                                             sum(r.totalleyessociales - r.montoahorrovol - r.impuesto) as leyessociales, 
                                             sum((r.sueldoimponible - (r.totalleyessociales - r.montoahorrovol - r.impuesto))) as rentatotalsinactualizar, 
                                             round(sum((r.sueldoimponible - (r.totalleyessociales - r.montoahorrovol - r.impuesto))*(SELECT       1 + (t.dic/100) 
                                            FROM            gc_periodo p
                                            left JOIN   gc_tabla_correccion_monetaria t  ON p.mes = t.mes_orig AND p.anno = t.anno
                                            WHERE       p.id = r.idperiodo  )),0) as rentatotalneta, 
                                             sum(r.sueldoimponible) as sueldoimponible, 
                                             sum(r.impuesto) as impuesto, 
                                            ROUND(sum(r.impuesto*(SELECT       1 + (t.dic/100) 
                                            FROM            gc_periodo p
                                            left JOIN   gc_tabla_correccion_monetaria t  ON p.mes = t.mes_orig AND p.anno = t.anno
                                            WHERE       p.id = r.idperiodo  )),0) as impuestoactualizado,                                              
                                            round(SUM((IFNULL((SELECT    sum(monto)
                                                    from gc_bonos_remuneracion                      
                                                    WHERE idremuneracion = r.id
                                                    AND     imponible = 0),0) + IFNULL(r.movilizacion,0) + IFNULL(r.colacion,0) + IFNULL(r.asigfamiliar,0))*(SELECT       1 + (t.dic/100) 
                                                                        FROM            gc_periodo p
                                                                        left JOIN   gc_tabla_correccion_monetaria t  ON p.mes = t.mes_orig AND p.anno = t.anno
                                                                        WHERE       p.id = r.idperiodo  )),0) AS bonosnoimponibles,  
                                            round(SUM((IFNULL((SELECT    sum(monto)
                                                    from gc_bonos_remuneracion                      
                                                    WHERE idremuneracion = r.id
                                                    AND     imponible = 0),0) + IFNULL(r.movilizacion,0) + IFNULL(r.colacion,0) + IFNULL(r.asigfamiliar,0))),0) AS bonosnoimponiblessinactualizar,                                                                          

                                             r.active,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 1)) > 0,"C","") as renta_enero_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 2)) > 0,"C","") as renta_febrero_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 3)) > 0,"C","") as renta_marzo_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 4)) > 0,"C","") as renta_abril_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 5)) > 0,"C","") as renta_mayo_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 6)) > 0,"C","") as renta_junio_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 7)) > 0,"C","") as renta_julio_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 8)) > 0,"C","") as renta_agosto_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 9)) > 0,"C","") as renta_septiembre_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 10)) > 0,"C","") as renta_octubre_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 11)) > 0,"C","") as renta_noviembre_ind,
						if((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 12)) > 0,"C","") as renta_diciembre_ind,
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 1)),0) as renta_enero, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 2)),0) as renta_febrero, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 3)),0) as renta_marzo, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 4)),0) as renta_abril, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 5)),0) as renta_mayo, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 6)),0) as renta_junio, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 7)),0) as renta_julio, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 8)),0) as renta_agosto,
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 9)),0) as renta_septiembre, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 10)),0) as renta_octubre, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 11)),0) as renta_noviembre, 
                        IFNULL((select sum(sueldoimponible - (totalleyessociales - montoahorrovol - impuesto)) as cantidad from gc_remuneracion where idpersonal = p.id and idperiodo = (select id from gc_periodo where anno = ' . $anno . ' and mes = 12)),0) as renta_diciembre                                                                                                                                                                                                                                                                         
									', false)
            ->from('gc_personal p')
            ->join('gc_remuneracion r', 'p.id = r.idpersonal')
            ->join('gc_periodo pe', 'r.idperiodo = pe.id')
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('pe.anno', $anno)
            ->group_by('p.id')
            ->order_by('p.nombre');

        //$movimiento_data = is_null($idmovimiento) ? $movimiento_data : $movimiento_data->where('lm.id',$idmovimiento);
             

        $query = $this->db->get();
              // echo $this->db->last_query(); exit;
        return $query->result();
    }



    public function calculo_declaracion_jurada($anno){


        $this->db->trans_start();

        $this->db->select("id")
            ->from('gc_declaracion_jurada d')
            ->where("d.anno", $anno)
            ->where("d.idcomunidad", $this->session->userdata('comunidadid'));

        $query = $this->db->get();
        $declaracion_existe = $query->result();

        //eliminar y calcular 
        if(count($declaracion_existe) > 0){

            $declaracion = $declaracion_existe[0];     
            $this->db->where('iddeclaracion', $declaracion->id);
            $this->db->delete('gc_declaracion_jurada_detalle');            


            $this->db->where('id', $declaracion->id);
            $this->db->delete('gc_declaracion_jurada');   


        }     

        $descjurada_data = $this->remuneracion->get_decjurada_rentas($anno);

        $array_declaracion_jurada = array(
                                            'anno' => $anno,
                                            'idcomunidad' => $this->session->userdata('comunidadid')

                                         );

        $this->db->insert('gc_declaracion_jurada',$array_declaracion_jurada);
        $declaracion_id = $this->db->insert_id();
        $i = 1;
        $rentatotalsinactualizar = 0;
        $rentatotalneta = 0;
        $impuestosinactualizar = 0;
        $impuestoactualizado = 0;
        $bonosnoimponiblessinactualizar = 0;
        $bonosnoimponibles = 0;
        $leyessociales = 0;
         foreach ($descjurada_data as $data) {
            $array_detalle_declaracion_jurada = array(
                                                    'iddeclaracion' => $declaracion_id,
                                                    'idpersonal' => $data->id,
                                                    'rut' => $data->rut,
                                                    'dv' => $data->dv,
                                                    'rentatotalsinactualizar' => $data->rentatotalsinactualizar,
                                                    'rentatotalneta' => $data->rentatotalneta,
                                                    'impuestosinactualizar' => $data->impuesto,
                                                    'impuestoactualizado' => $data->impuestoactualizado,
                                                    'bonosnoimponibles' => $data->bonosnoimponibles,
                                                    'bonosnoimponiblessinactualizar' => $data->bonosnoimponiblessinactualizar,
                                                    'leyessociales' => $data->leyessociales,
                                                    'eneroind' => $data->renta_enero_ind,
                                                    'febreroind' => $data->renta_febrero_ind,
                                                    'marzoind' => $data->renta_marzo_ind,
                                                    'abrilind' => $data->renta_abril_ind,
                                                    'mayoind' => $data->renta_mayo_ind,
                                                    'junioind' => $data->renta_junio_ind,
                                                    'julioind' => $data->renta_julio_ind,
                                                    'agostoind' => $data->renta_agosto_ind,
                                                    'septiembreind' => $data->renta_septiembre_ind,
                                                    'octubreind' => $data->renta_octubre_ind,
                                                    'noviembreind' => $data->renta_noviembre_ind,
                                                    'diciembreind' => $data->renta_diciembre_ind,
                                                    'correlativo' => $i,
                                                    'enerorenta' => $data->renta_enero,
                                                    'febrerorenta' => $data->renta_febrero,
                                                    'marzorenta' => $data->renta_marzo,
                                                    'abrilrenta' => $data->renta_abril,
                                                    'mayorenta' => $data->renta_mayo,
                                                    'juniorenta' => $data->renta_junio,
                                                    'juliorenta' => $data->renta_julio,
                                                    'agostorenta' => $data->renta_agosto,
                                                    'septiembrerenta' => $data->renta_septiembre,
                                                    'octubrerenta' => $data->renta_octubre,
                                                    'noviembrerenta' => $data->renta_noviembre,
                                                    'diciembrerenta' => $data->renta_diciembre,
                                                    'horassemanales' => $data->horassemanales
                                                );
                $this->db->insert('gc_declaracion_jurada_detalle',$array_detalle_declaracion_jurada);

                $impuestosinactualizar += $data->impuesto;
                $rentatotalsinactualizar += $data->rentatotalsinactualizar;
                $rentatotalneta += $data->rentatotalneta;
                $impuestoactualizado += $data->impuestoactualizado;
                $bonosnoimponibles += $data->bonosnoimponibles;
                $bonosnoimponiblessinactualizar += $data->bonosnoimponiblessinactualizar;
                $leyessociales += $data->leyessociales;
                $i++;


        }

        $array_actualiza_dj = array(
                                    'rentatotalsinactualizar' => $rentatotalsinactualizar,
                                    'rentatotalneta' => $rentatotalneta,
                                    'impuestorentasinactualizar' => $impuestosinactualizar,
                                    'impuestorentapagada' => $impuestoactualizado,
                                    'rentanogravada' => $bonosnoimponibles,
                                    'rentanogravadasinactualizar' => $bonosnoimponiblessinactualizar,
                                    'leyessociales' => $leyessociales
                                );
        $this->db->where('id',$declaracion_id);
        $this->db->update('gc_declaracion_jurada', $array_actualiza_dj);

        $this->db->trans_complete();


    }



     public function get_decjurada_rentas_encabezado($anno){

            $this->db->select("anno, rentatotalsinactualizar, rentatotalneta, impuestorentapagada, impuestorentasinactualizar, impuestorentaaccesoria, rentanogravada, rentanogravadasinactualizar, rentaexenta, rebajazonasextremas, leyessociales")
                ->from('gc_declaracion_jurada d')
                ->where("d.anno", $anno)
                ->where("d.idcomunidad", $this->session->userdata('comunidadid'));

            $query = $this->db->get();
            $declaracion = $query->result();

            return $declaracion;
     }



     public function get_decjurada_rentas_detalle($anno){

            $this->db->select("d.rut, d.dv, d.rentatotalneta, d.impuestoactualizado, d.bonosnoimponibles, d.eneroind, d.febreroind, d.marzoind, d.abrilind, d.mayoind, d.junioind, d.julioind, d.agostoind, d.septiembreind, d.octubreind, d.noviembreind, d.diciembreind, d.correlativo, d.enerorenta, d.febrerorenta, d.marzorenta, d.abrilrenta, d.mayorenta, d.juniorenta, d.juliorenta, d.agostorenta, d.septiembrerenta, d.octubrerenta, d.noviembrerenta, d.diciembrerenta, d.horassemanales ")
                ->from('gc_declaracion_jurada_detalle d')
                ->join('gc_declaracion_jurada j', 'd.iddeclaracion = j.id')
                ->where("j.anno", $anno)
                ->where("j.idcomunidad", $this->session->userdata('comunidadid'));

            $query = $this->db->get();
            $declaracion = $query->result();

            return $declaracion;
     }


     public function archivo_decjurada_rentas($anno){

        $nombre_archivo = $this->session->userdata('comunidadid') . "_dj_" . $anno . ".csv";
        $path_archivo = "./uploads/tmp/";
        $file = fopen($path_archivo . $nombre_archivo, "w");





        $this->load->model('remuneracion');
        $descjurada_data = $this->remuneracion->get_decjurada_rentas_detalle($anno);
       // echo '<pre>';
       // var_dump($descjurada_data); exit;
        $i = 1;

        foreach ($descjurada_data as $data) {
                $linea  = $data->rut.';'; // rut.dv
                $linea .= $data->dv.';'; // rut.dv
                $linea .= $data->rentatotalneta.';'; // sueldo liquido
                $linea .= $data->impuestoactualizado.';'; // impuesto
                $linea .= '0;'; // mayor retencion
                $linea .= $data->bonosnoimponibles.';'; // renta total no gravada
                $linea .= '0;'; // renta total exenta
                $linea .= '0;'; // rebaja zonas extremas
                $linea .= '0;'; // 3% prestamos
                $linea .= $data->eneroind.';'; // IND ENERO
                $linea .= $data->febreroind.';'; // IND FEBRERO
                $linea .= $data->marzoind.';'; // IND MARZO
                $linea .= $data->abrilind.';'; // IND ABRIL
                $linea .= $data->mayoind.';'; // IND MAYO
                $linea .= $data->junioind.';'; // IND JUNIO
                $linea .= $data->julioind.';'; // IND JULIO
                $linea .= $data->agostoind.';'; // IND AGOSTO
                $linea .= $data->septiembreind.';'; // IND SEPTIEMBRE
                $linea .= $data->octubreind.';'; // IND OCTUBRE
                $linea .= $data->noviembreind.';'; // IND NOVIEMBRE
                $linea .= $data->diciembreind.';'; // IND DICIEMBRE
                $linea .= $data->correlativo. ';'; // correlativo      
                $linea .= $data->enerorenta.';'; // IND ENERO
                $linea .= $data->febrerorenta.';'; // IND FEBRERO
                $linea .= $data->marzorenta.';'; // IND MARZO
                $linea .= $data->abrilrenta.';'; // IND ABRIL
                $linea .= $data->mayorenta.';'; // IND MAYO
                $linea .= $data->juniorenta.';'; // IND JUNIO
                $linea .= $data->juliorenta.';'; // IND JULIO
                $linea .= $data->agostorenta.';'; // IND AGOSTO
                $linea .= $data->septiembrerenta.';'; // IND SEPTIEMBRE
                $linea .= $data->octubrerenta.';'; // IND OCTUBRE
                $linea .= $data->noviembrerenta.';'; // IND NOVIEMBRE
                $linea .= $data->diciembrerenta.';'; // IND DICIEMBRE
                $linea .= $data->horassemanales.';'; // IND DICIEMBRE

                $i++;

                $linea .= "\r\n";
                //$linea = $rut.$dv.$apaterno.$amaterno.$nombres."\r\n";
                fputs($file, $linea);


        }




        fclose($file);

        $data_archivo = basename($path_archivo . $nombre_archivo);
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $data_archivo);
        header('Content-Length: ' . filesize($path_archivo . $nombre_archivo));
        readfile($path_archivo . $nombre_archivo);


        unlink($path_archivo . $nombre_archivo);



     }

    public function asocia_id_personal($iduser, $idpersonal)
    {
        $this->db->where('id', $idpersonal);
        $this->db->update('gc_personal', array('iduser' => $iduser));
    }

    public function desactiva_comunidad_user($iduser, $idcomunidad)
    {
        $this->db->where('idusuario', $iduser)
            ->where('idcomunidad', $idcomunidad)
            ->delete('gc_usuario_comunidad');
    }

    public function get_personal_by_iduser($iduser, $idcomunidad = null)
    {
        $result = $this->db->select('p.nombre, p.apaterno, p.amaterno, co.nombre as comunidad, ca.nombre as cargo')
            ->from('gc_personal p')
            ->join('gc_comunidad co', 'p.idcomunidad = co.id')
            ->join('gc_cargos ca', 'p.idcargo = ca.id')
            ->where('p.iduser', $iduser);
        $result = is_null($idcomunidad) || $idcomunidad == '' ? $result : $result->where('p.idcomunidad', $idcomunidad);
        $query = $this->db->get();
        return $query->row();
    }
}
