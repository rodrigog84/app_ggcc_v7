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

class Comunity_model extends CI_Model
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

    public function get_registros_visitas($idcomunidad = null, $active = 1)
    {
        $registros = $this->db->select("l.id, l.nombre, l.apellidos, l.rut, l.dv, l.patente, date_format(l.registro_entrada,'%d/%m/%Y %H:%i:%s') as entrada, date_format(l.registro_salida,'%d/%m/%Y %H:%i:%s') as salida, e.nombre as estacionamiento, p.numero as propiedad")
            ->from('gc_libro_visitas as l')
            ->join('gc_estacionamiento_visita as e', 'e.id = l.idestacionamiento', 'left')
            ->join('gc_propiedad as p', 'p.id = l.idpropiedad')
            ->where('l.active', $active)
            ->order_by('l.registro_entrada', 'DESC');

        $registros = is_null($idcomunidad) ? $registros : $registros->where('l.idcomunidad', $idcomunidad);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_registro_visita_by_id($idregistro)
    {

        $this->db->select("l.id, l.nombre, l.apellidos, l.rut, l.dv, l.patente, l.idestacionamiento, e.nombre as estacionamiento, l.idpropiedad, p.responsable, p.numero as propiedad, l.idcomunidad")
            ->from('gc_libro_visitas as l')
            ->join('gc_estacionamiento_visita as e', 'e.id = l.idestacionamiento', 'left')
            ->join('gc_propiedad as p', 'p.id = l.idpropiedad')
            ->where('l.active', 1)
            ->where('l.id', $idregistro);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }

    public function get_bitacoras($idcomunidad = null, $active = 1)
    {
        $bitacoras = $this->db->select("l.id, l.iduser, l.idcomunidad, l.accion, l.descripcion, date_format(l.created_at,'%d/%m/%Y %H:%i:%s') as created_at, date_format(l.updated_at,'%d/%m/%Y %H:%i:%s') as updated_at, date_format(l.archived_at,'%d/%m/%Y %H:%i:%s') as archived_at, u.first_name as nombre, u.last_name as apellidos, c.nombre as comunidad")
            ->from('gc_libro_novedades as l')
            ->join('gc_users as u', 'u.id = l.iduser')
            ->join('gc_comunidad as c', 'c.id = l.idcomunidad')
            ->where('l.active', $active)
            ->order_by('l.created_at', 'DESC');

        $bitacoras = is_null($idcomunidad) ? $bitacoras : $bitacoras->where('l.idcomunidad', $idcomunidad);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_bitacora_by_id($idbitacora, $active = 1)
    {
        $this->db->select("l.iduser, l.idcomunidad, l.accion, l.descripcion, l.active")
            ->from('gc_libro_novedades as l')
            ->where('l.id', $idbitacora)
            ->where('l.active', $active);

        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }

    public function get_estacionamiento_visita_comunidad($idcomunidad)
    {

        $this->db->select('e.id, e.nombre')
            ->from('gc_estacionamiento_visita as e')
            ->where('e.idcomunidad', $idcomunidad)
            ->where('e.valid', 1)
            ->order_by('e.nombre', 'ASC');
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }

    public function add_registro_visita($array_datos, $idregistro = 0)
    {
        $array_datos['active'] = is_null($array_datos['idestacionamiento']) ? 0 : 1;
        if ($idregistro == 0) {
            // Si no existe el registro
            $array_datos['registro_entrada'] = date("Y-m-d H:i:s");
            if (!empty($array_datos['idestacionamiento'])) {
                // Checkea si se ocupa estacionamiento
                $this->db->select('e.valid')
                    ->from('gc_estacionamiento_visita as e')
                    ->where('e.id', $array_datos['idestacionamiento']);
                $query = $this->db->get();
                $valid = $query->row();

                if ($valid->valid == 1) {
                    // Si el estacionamiento esta desocupado, se insertan los datos
                    $this->db->insert('gc_libro_visitas', $array_datos);
                    $idregistro = $this->db->insert_id();

                    return $idregistro;
                }
            } else {
                $this->db->insert('gc_libro_visitas', $array_datos);
                $idregistro = $this->db->insert_id();

                return $idregistro;
            }
        } else if ($idregistro != 0) {
            // Si existe el registro, se actualiza
            $this->db->select('l.idestacionamiento')
                ->from('gc_libro_visitas as l')
                ->where('l.id', $idregistro);
            $query = $this->db->get();
            $registro = $query->row();

            // Se libera el estacionamiento anterior
            is_null($registro->idestacionamiento) ? false : $this->estado_estacionamiento($registro->idestacionamiento, 1);

            $this->db->where('id', $idregistro);
            $this->db->update('gc_libro_visitas', $array_datos);

            return 1;
        } else {
            return -1;
        }
    }

    public function add_bitacora($array_datos, $idbitacora = 0)
    {
        // Se verifica si existe la bitacora
        $this->db->select('l.id')
            ->from('gc_libro_novedades as l')
            ->where('l.active', 1)
            ->where('l.id', $idbitacora);
        $query = $this->db->get();
        $bitacora = $query->row();

        if (!is_null($bitacora)) {
            // Si existe la bitacora se actualiza
            $array_datos['archived_at'] = date("Y-m-d H:i:s");
            $this->db->where('id', $idbitacora);
            $this->db->update('gc_libro_novedades', $array_datos);

            return 1;
        } else if ($idbitacora == 0) {
            // Si no existe se inserta
            $array_datos['created_at'] = date("Y-m-d H:i:s");
            $this->db->insert('gc_libro_novedades', $array_datos);
            $result = $this->db->insert_id();

            return $result;
        } else if ($idbitacora != 0 && is_null($bitacora)) {
            return -2;
        } else {
            return -1;
        }
    }

    public function estado_estacionamiento($idestacionamiento, $valid)
    {
        $this->db->set('valid', $valid)
            ->where('id', $idestacionamiento)
            ->update('gc_estacionamiento_visita');

        if ($this->db->affected_rows() > 0) {
            return 1;
        }
    }

    public function marca_salida($idregistro)
    {
        $this->db->select('lv.active')
            ->from('gc_libro_visitas as lv')
            ->where('lv.id', $idregistro);
        $query = $this->db->get();
        $registro = $query->row();

        if ($registro->active === '1') {
            $this->db->set('active', 0)
                ->where('id', $idregistro)
                ->update('gc_libro_visitas');

            return 1;
        }
    }

    public function archive_bitacora($idbitacora)
    {
        $this->db->select('l.id, l.active')
            ->from('gc_libro_novedades as l')
            ->where('l.active', 1)
            ->where('l.id', $idbitacora);
        $query = $this->db->get();
        $bitacora = $query->row();

        if (!is_null($bitacora)) {
            $this->db->set(array('active' => 0, 'archived_at' => date("Y-m-d H:i:s")))
                ->where('id', $idbitacora)
                ->update('gc_libro_novedades');

            return 1;
        } else {
            return 0;
        }
    }

    public function delete_registro_visita($idregistro)
    {
        if ($idregistro) {
            $this->db->select('l.active')
                ->from('gc_libro_visitas as l')
                ->where('l.active', 0)
                ->where('l.id', $idregistro);
            $query = $this->db->get();
            $registro = $query->row();

            if (!is_null($registro)) {
                // Si no se encuentra activa se elimina
                $this->db->where('id', $idregistro)
                    ->delete('gc_libro_visitas');

                return 1;
            } else {
                // Si no existe manda error
                return -0;
            }
        } else {
            // Si no se manda idregistro manda error
            return -1;
        }
    }

    public function delete_bitacora($idbitacora)
    {
        if ($idbitacora) {
            $this->db->select('l.active')
                ->from('gc_libro_novedades as l')
                ->where('l.active', 0)
                ->where('l.id', $idbitacora);
            $query = $this->db->get();
            $bitacora = $query->row();

            if (!is_null($bitacora)) {
                // Si no se encuentra activa se elimina
                $this->db->where('id', $idbitacora)
                    ->delete('gc_libro_novedades');

                return 1;
            } else {
                // Si no existe manda error
                return -0;
            }
        } else {
            // Si no se manda idbitacora manda error
            return -1;
        }
    }

    public function clean_registros_visitas($idcomunidad)
    {
        if ($idcomunidad) {
            $this->db->select('l.id')
                ->from('gc_libro_visitas as l')
                ->join('gc_propiedad as p', 'l.idpropiedad = p.id')
                ->where('p.idcomunidad', $idcomunidad)
                ->where('l.active', 0);
            $query = $this->db->get();
            $registros = $query->num_rows() == 1 ? $query->row() : $query->result();

            if (count($registros) > 0) {
                $this->db->where('idcomunidad', $idcomunidad)
                    ->where('active', 0)
                    ->delete('gc_libro_visitas');

                if ($this->db->affected_rows() > 0) {
                    return 1;
                }
            } else {
                return 0;
            }
        } else {
            return -1;
        }
    }

    public function clean_bitacoras($idcomunidad)
    {
        if ($idcomunidad) {
            $this->db->select('l.id')
                ->from('gc_libro_novedades as l')
                ->where('l.idcomunidad', $idcomunidad)
                ->where('l.active', 0);
            $query = $this->db->get();
            $bitacoras = $query->num_rows() == 1 ? $query->row() : $query->result();

            if (count($bitacoras) > 0) {
                $this->db->where('idcomunidad', $idcomunidad)
                    ->where('active', 0)
                    ->delete('gc_libro_novedades');

                if ($this->db->affected_rows() > 0) {
                    return 1;
                }
            } else {
                return 0;
            }
        } else {
            return -1;
        }
    }
}
