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

class Admin extends CI_Model
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




    public function get_comunidades($idcomunidad = null, $pagoonline = null)
    {

        $comunidades_data = $this->db->select("c.id , c.nombre, c.rut, c.dv, c.direccion, c.fono, c.fono2, c.idregion, c.idcomuna, c.email, c.saldo, c.caja, c.fondoreserva, c.fondoreservainicial, c.idcaja, c.idmutual, c.porcmutual, c.cajainicial, date_format(c.fecinicio,'%d/%m/%Y') as fecinicio, date_format(c.fecvencimiento,'%d/%m/%Y') as fecvencimiento, fecvencimiento as fecvencimiento_sformat, c.fecinicio as fecinicio_sformat, c.idperiodoinicio, c.logo, c.firma, c.obscomprobante, mes_aldia, mes_moroso, mes_corteluz, mes_prejudicial, mes_judicial, mail_morosidad_antes_vencimiento, txt_mail_antes_vencimiento, mail_morosidad_despues_vencimiento, txt_mail_despues_vencimiento, token_pagoonline, 
				(
					select count(u.id) from gc_users u
					inner join gc_usuario_propiedad up on u.id = up.idusuario
					inner join gc_propiedad p on up.idpropiedad = p.id
					where u.inicpass <> '' and p.idcomunidad = c.id
					and u.active = 1 and p.active = 1) as envios_pendientes


			 ", false)
            ->from('gc_comunidad c')
            ->where('c.active = 1')
            ->order_by('c.nombre asc');

        $comunidades_data = is_null($idcomunidad) ? $comunidades_data : $comunidades_data->where('id', $idcomunidad);
        $comunidades_data = is_null($pagoonline) ? $comunidades_data : $comunidades_data->where('pagoonline', 1);
        $query = $this->db->get();
        $datos = is_null($idcomunidad) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_usuarios_sin_envio($idcomunidad)
    {
        $comunidades_data = $this->db->select("u.id, u.inicpass")
            ->from('gc_users u')
            ->join("gc_usuario_propiedad up", "u.id = up.idusuario")
            ->join("gc_propiedad p", "up.idpropiedad = p.id")
            ->join("gc_comunidad c", "p.idcomunidad = c.id")
            ->where("u.inicpass <> ''")
            ->where("p.idcomunidad", $idcomunidad)
            ->where("u.active", 1)
            ->where("p.active", 1)
            ->where("c.active", 1);

        $query = $this->db->get();
        return $query->result();
    }


    public function envio_masivo_mails($idcomunidad, $array_usuarios = null)
    {

        //$this->db->trans_start();
        $users = $this->get_usuarios_sin_envio($idcomunidad);
        foreach ($users as $user) {

            $envia = false;
            if (is_null($array_usuarios)) {
                $envia = true;
            } else {
                if (in_array($user->id, $array_usuarios)) {
                    $envia = true;
                } else {
                    $envia = false;
                }
            }

            if ($envia) {
                $this->admin->mail_creacion_usuario($user->id, $user->inicpass);
                $this->db->where('id', $user->id);
                $this->db->update('gc_users', array('inicpass' => ''));
            }
        }

        //$this->db->trans_complete();
    }



    public function comunidades_asignadas($userid, $levelid, $comunidadid = null)
    {

        if ($levelid == 3) {
            $comunidad_data = $this->db->select('c.id, c.nombre, datediff(c.fecvencimiento,now()) as vencsuscripcion', false)
                ->from('gc_comunidad as c')
                ->join('gc_propiedad as p', 'c.id = p.idcomunidad')
                ->join('gc_usuario_propiedad as up', 'p.id = up.idpropiedad')
                ->where('up.idusuario', $userid)
                ->where('c.active = 1')
                ->where("c.fecvencimiento >= '" . date("Y-m-d") . "'")
                ->order_by('c.nombre asc');
            $comunidad_data = is_null($comunidadid) ? $comunidad_data : $comunidad_data->where('c.id', $comunidadid);
            $query = $this->db->get();
            $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();
        } else {

            $comunidad_data = $this->db->select('c.id, c.nombre, datediff(c.fecvencimiento,now()) as vencsuscripcion', false)
                ->from('gc_comunidad as c')
                ->join('gc_usuario_comunidad as uc', 'c.id = uc.idcomunidad')
                ->where('uc.idusuario', $userid)
                ->where('c.active = 1')
                ->where("c.fecvencimiento >= '" . date("Y-m-d") . "'")
                ->order_by('c.nombre asc');
            $comunidad_data = is_null($comunidadid) ? $comunidad_data : $comunidad_data->where('c.id', $comunidadid);
            $query = $this->db->get();
            $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();
        }
        return $datos;
    }


    public function datos_comunidad($comunidadid)
    {

        $this->db->select('c.id, c.nombre, c.rut, c.dv, c.direccion, co.nombre as comuna, c.maxfolioabono, c.maxfoliopago, c.textoggcc, c.logo, c.firma')
            ->from('gc_comunidad as c')
            ->join('gc_comuna as co', 'c.idcomuna = co.idcomuna', 'left')
            ->where('c.id', $comunidadid)
            ->order_by('c.nombre asc');
        $query = $this->db->get();
        $datos = $query->row();
        return $datos;
    }

    public function propiedades_asignadas($userid, $propiedadid = null)
    {

        $propiedad_data = $this->db->select('p.idcomunidad, c.nombre as nombrecomunidad, p.id, p.numero ')
            ->from('gc_propiedad as p')
            ->join('gc_usuario_propiedad as up', 'p.id = up.idpropiedad')
            ->join('gc_comunidad as c', 'p.idcomunidad = c.id')
            ->where('up.idusuario', $userid)
            ->where('p.active = 1')
            ->order_by('c.id asc, p.numero asc');
        $propiedad_data = is_null($propiedadid) ? $propiedad_data : $propiedad_data->where('p.id', $propiedadid);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }


    public function get_regiones()
    {

        $this->db->select('idregion , nombre ')
            ->from('gc_region')
            ->order_by('idregion asc');
        $query = $this->db->get();
        $datos = $query->result();

        return $datos;
    }


    public function get_propiedades_comunidad()
    {

        $this->db->select('p.id, p.numero, p.responsable, p.mail, p.suscrito, p.prorrateo, p.prorrateo_propiedad, p.saldo')
            ->from('gc_propiedad as p')
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('p.active', 1)
            ->order_by('p.numero');
        // ->order_by('ORDER BY LPAD(lower(p.numero), 10,0', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }


    public function get_propiedades($idcomunidad = null)
    {

        $comunidades_data = $this->db->select('p.id, p.numero, p.responsable, p.mail, p.suscrito, p.prorrateo, p.prorrateo_propiedad, p.saldo, c.nombre as comunidad, ((select count(*) from gc_estacionamiento where idpropiedad = p.id and active = 1) + (select count(*) from gc_bodega where idpropiedad = p.id and active = 1)) as unidades_asociadas ', false)
            ->from('gc_propiedad as p')
            ->join('gc_comunidad as c', 'p.idcomunidad = c.id')
            ->where('c.active = 1')
            ->where('p.active = 1')
            ->order_by('c.nombre asc, p.numero asc');

        $comunidades_data = is_null($idcomunidad) ? $comunidades_data : $comunidades_data->where('c.id', $idcomunidad);
        $query = $this->db->get();
        return $query->result();
    }


    public function asocia_saldos_positivos()
    {

        //obtenemos todos los movimientos sin periodo
        $this->db->trans_start();
        $this->db->select('c.id, c.monto, c.idlistado, c.fechapago, c.idformapago, c.idbanco, c.cheque, c.ruttitular, c.dvtitular, c.fechadeposito, c.observacion, c.created_at, l.idprotesto, l2.id as idlistadoprotesto,c.idpropiedad')
            ->from('gc_cartola_propiedad c')
            ->join('gc_listado_abonos l', 'c.idlistado = l.id')
            ->join('gc_listado_abonos l2', 'l2.idprotesto = l.id', 'left')
            ->join('gc_propiedad p', 'c.idpropiedad = p.id')
            ->where('c.idperiodo is null')
            ->where('c.activo = 1')
            ->where('l2.id is null')
            ->where('c.monto > 0')
            ->where('(select sum(saldo)
		                  			from 			gc_ggcc_propiedad
									where 		idpropiedad = c.idpropiedad) > 0')
            //->where('c.idpropiedad = 3309')
            //->where('p.saldo > 0')
            ->order_by('c.created_at');


        $query_cartola = $this->db->get();
        $data_cartola = $query_cartola->result();
        //echo $this->db->last_query();
        //exit;
        //echo "<pre>";
        //print_r($data_cartola); exit;

        foreach ($data_cartola as $cartola) {

            //print_r($cartola);
            $monto_excedente = $cartola->monto;
            // buscamos todos los períodos donde se debe
            $this->db->select('id, idperiodo, saldo')
                ->from('gc_ggcc_propiedad')
                ->where('idpropiedad', $cartola->idpropiedad)
                ->where('saldo > 0')
                ->order_by('idperiodo');
            $query_ggcc = $this->db->get();
            $data_ggcc = $query_ggcc->result();
            $idcartola = $cartola->id;
            //print_r($data_ggcc);
            // exit;
            foreach ($data_ggcc as $ggcc) {
                //print_r($ggcc);
                $compara_saldo = abs($ggcc->saldo);
                $monto_cartola = $monto_excedente;
                $dif_abono_cartola = $compara_saldo - $monto_cartola;

                if ($dif_abono_cartola < 0) { # CARTOLA CUBRE TOTALMENTE LA DEUDA, HAY QUE DIVIDIR
                    //$monto_abonado = $compara_saldo;
                    //echo "1<p>";
                    $this->db->where('id', $idcartola);
                    $this->db->update('gc_cartola_propiedad', array(
                        'idperiodo' => $ggcc->idperiodo,
                        'monto' => $compara_saldo
                    ));

                    //echo $this->db->last_query();

                    //SE CREA UNA CARTOLA POR LA DIFERENCIA
                    $data_ncartola = array(
                        'idlistado' => $cartola->idlistado,
                        'idpropiedad' => $cartola->idpropiedad,
                        'idperiodo' =>  null,
                        'fechapago' => $cartola->fechapago,
                        'monto' =>  abs($dif_abono_cartola),
                        'idformapago' =>  $cartola->idformapago,
                        'idbanco' =>  $cartola->idbanco,
                        'cheque' =>  $cartola->cheque,
                        'ruttitular' =>  $cartola->ruttitular,
                        'dvtitular' =>  $cartola->dvtitular,
                        'fechadeposito' => $cartola->fechadeposito,
                        'observacion' => $cartola->observacion
                    );
                    // guarda cartola
                    $this->db->insert('gc_cartola_propiedad', $data_ncartola);
                    $idcartola = $this->db->insert_id();
                    //	echo $idcartola."<br>";

                    $this->db->query("update gc_ggcc_propiedad set
																	abonado = abonado + " . $compara_saldo . ",
																	saldo = saldo - " . $compara_saldo . "
																	where id = " . $ggcc->id);


                    /*$this->db->query("update gc_propiedad set
														saldo = saldo - " . $monto_abonado . ",
														saldo_publicado = saldo_publicado - " . $monto_abonado . "
														where id = " . $cartola->idpropiedad);*/

                    //break;
                    $monto_excedente = $monto_excedente - $compara_saldo;
                } else if ($dif_abono_cartola >= 0) { #MARCAMOS CARTOLA PARA EL PERIODO Y REBAJAMOS SALDO
                    //	echo "2<p>";
                    $this->db->where('id', $idcartola);
                    $this->db->update('gc_cartola_propiedad', array('idperiodo' => $ggcc->idperiodo));
                    //	echo $this->db->last_query();

                    $this->db->query("update gc_ggcc_propiedad set
															abonado = abonado + " . $monto_excedente . ",
															saldo = saldo - " . $monto_excedente . "
															where id = " . $ggcc->id);


                    //$compara_saldo = $compara_saldo - $monto_cartola;
                    break;
                }/*else if($dif_abono_cartola == 0){  #SE REBAJA Y SE CIERRA EL CICLO
						$this->db->where('id',$cartola->id);
						$this->db->update('gc_cartola_propiedad', array('idperiodo' => $idperiodo));
						$compara_saldo = $compara_saldo - $monto_cartola;
						break;

                 	}*/
            }
        }

        $this->db->trans_complete();
        //print_r($data_cartola); exit;
    }




    public function redistribuye_saldos_erroneos()
    {

        //obtenemos todos los movimientos sin periodo
        $this->db->trans_start();

        /*

-- OBTENEMOS LOS CASOS DONDE:
-- LA DIFERENCIA ENTRE ABONOS DE GGCC Y PAGOS ES LA MISMA QUE DIFERENCIA ENTRE SALDOS GGC Y SALDO PROPIEDAD
-- SI A SALDO GGCC LE ABONAMOS LOS PAGOS RESTANTES, QUEDARÁ CON EL MISMO SALDO PROPIEDAD

*/


        $queryQuestion = $this->db->query('
			select 		*
			from 			(
								select 		c.idpropiedad
												,p.numero as propiedad
												,c.monto as cartola_pagos
												,g.abonado as abono_gc
												,(g.abonado - c.monto) as diferencia_pagos_abonos
												,g.saldo as saldo_gc
												,p.saldo_publicado
												,p.saldo
											#	,(g.saldo - p.saldo_publicado) as dif_saldo
												,(g.saldo - p.saldo) as dif_saldo
												,p.idcomunidad
												,co.nombre as comunidad
								from 			(
												select 		c.idpropiedad
																,sum(c.monto) as monto
												from 			gc_cartola_propiedad c
												inner join 	gc_propiedad p on c.idpropiedad = p.id
												where 		c.activo = 1
												#and 			p.idcomunidad in (17,19,25)
												group by 	idpropiedad
												) c
								inner join 	(
												select 		c.idpropiedad
																,sum(c.abonado) as abonado
																,sum(c.saldo) as saldo
												from			gc_ggcc_propiedad c
												inner join 	gc_propiedad p on c.idpropiedad = p.id
												#where			p.idcomunidad in (17,19,25)
												group by		c.idpropiedad
												) g on c.idpropiedad = g.idpropiedad
								inner join 	gc_propiedad p on c.idpropiedad = p.id
								inner join 	gc_comunidad co on p.idcomunidad = co.id
								where 		c.monto <> g.abonado
								) b
				inner join  gc_comunidad c on b.idcomunidad = c.id
				where 		abs(b.diferencia_pagos_abonos) = abs(b.dif_saldo)
				and 			b.diferencia_pagos_abonos < 0
				and 			(b.saldo_gc + b.diferencia_pagos_abonos) =  b.saldo
				and			c.active = 1
				and			curdate() <= c.fecvencimiento
				and 			b.saldo_gc > 0
				order by 	b.comunidad
								,b.propiedad

						');


        //$query_cartola = $this->db->get();
        $casos_erroneos = $queryQuestion->result();
        //exit;
        echo "<pre>";
        //print_r($data_cartola); exit;

        foreach ($casos_erroneos as $caso_erroneo) {
            //print_r($caso_erroneo);
            $idpropiedad = $caso_erroneo->idpropiedad;
            $dif_pagos_abonos = abs($caso_erroneo->diferencia_pagos_abonos);


            /*

//BUSCO LAS DIFERENCIAS


// VOY A BUSCAR LOS CASOS DONDE TENGA UN PAGO (CAARTOLA) MAYOR AL ABONO( GGCC)
// REARMARÉ ESAS LINEAS Y DEJARÉ SOLO LO ABONADO, EL RESTO LO DEJO EN NULO

*/

            $queryQuestionDif = $this->db->query('
													select 		*
													from 			(
																	select 		gc.idperiodo
																					,p.mes
																					,p.anno
																					,coalesce(c.monto,0) as pago
																					,gc.abonado as abono_gc
																					,gc.saldo
																	#select 		*
																	from 			(
																					select 	gc.idperiodo
																								,gc.abonado
																								,gc.saldo
																					from 		gc_ggcc_propiedad gc
																					where 	idpropiedad = ' . $idpropiedad . '
																					) gc
																	left 	join 	(
																					select 	c.idperiodo
																								,sum(monto) as monto
																					from 		gc_cartola_propiedad c
																					where 	c.idpropiedad = ' . $idpropiedad . '
																					and 		c.activo = 1
																					group by c.idperiodo
																					) c	 on c.idperiodo = gc.idperiodo
																	left join	gc_periodo p on gc.idperiodo = p.id
																	) b
													where 		pago <> abono_gc
													and 		pago > abono_gc
													order by		idperiodo
													');


            echo $idpropiedad . "-" . $dif_pagos_abonos . "<br>";

            $dif_saldos = $queryQuestionDif->result();

            foreach ($dif_saldos as $dif_saldo) {
                $idperiodo = $dif_saldo->idperiodo;
                $abono_gc = $dif_saldo->abono_gc;
                echo $idperiodo . "-" . $abono_gc . "<br>";

                $this->db->select('id, idlistado, idpropiedad, fechapago, idformapago, idbanco, cheque, ruttitular, dvtitular, fechadeposito, observacion, idperiodo, monto')
                    ->from('gc_cartola_propiedad')
                    ->where('idpropiedad', $idpropiedad)
                    ->where('idperiodo', $idperiodo)
                    ->where('activo', 1)
                    ->order_by('id');
                $query_cartola = $this->db->get();
                $data_cartola = $query_cartola->result();

                foreach ($data_cartola as $cartola) {
                    $monto_pago = $cartola->monto;
                    $idcartola = $cartola->id;
                    echo $monto_pago . "<br>";




                    $compara_saldo = $abono_gc;
                    $monto_cartola = $monto_pago;
                    $dif_abono_cartola = $compara_saldo - $monto_cartola;

                    if ($dif_abono_cartola < 0) { # CARTOLA CUBRE TOTALMENTE LA DEUDA, HAY QUE DIVIDIR
                        //$monto_abonado = $compara_saldo;
                        //echo "1<p>";
                        $this->db->where('id', $idcartola);
                        $this->db->update('gc_cartola_propiedad', array(
                            'idperiodo' => $idperiodo,
                            'monto' => $compara_saldo
                        ));

                        //echo $this->db->last_query();

                        //SE CREA UNA CARTOLA POR LA DIFERENCIA
                        $data_ncartola = array(
                            'idlistado' => $cartola->idlistado,
                            'idpropiedad' => $cartola->idpropiedad,
                            'idperiodo' =>  null,
                            'fechapago' => $cartola->fechapago,
                            'monto' =>  abs($dif_abono_cartola),
                            'idformapago' =>  $cartola->idformapago,
                            'idbanco' =>  $cartola->idbanco,
                            'cheque' =>  $cartola->cheque,
                            'ruttitular' =>  $cartola->ruttitular,
                            'dvtitular' =>  $cartola->dvtitular,
                            'fechadeposito' => $cartola->fechadeposito,
                            'observacion' => $cartola->observacion
                        );
                        // guarda cartola
                        $this->db->insert('gc_cartola_propiedad', $data_ncartola);
                        $idcartola = $this->db->insert_id();
                        //	echo $idcartola."<br>";


                        /*$this->db->query("update gc_propiedad set
																		saldo = saldo - " . $monto_abonado . ",
																		saldo_publicado = saldo_publicado - " . $monto_abonado . "
																		where id = " . $cartola->idpropiedad);*/

                        //break;
                        $monto_excedente = $monto_excedente - $compara_saldo;
                    } else if ($dif_abono_cartola >= 0) { #MARCAMOS CARTOLA PARA EL PERIODO Y REBAJAMOS SALDO
                        //	echo "2<p>";
                        $this->db->where('id', $idcartola);
                        $this->db->update('gc_cartola_propiedad', array('idperiodo' => $ggcc->idperiodo));
                        //	echo $this->db->last_query();

                        $this->db->query("update gc_ggcc_propiedad set
																			abonado = abonado + " . $monto_excedente . ",
																			saldo = saldo - " . $monto_excedente . "
																			where id = " . $ggcc->id);


                        //$compara_saldo = $compara_saldo - $monto_cartola;
                        break;
                    }
                }
            }

            //print_r($cartola);
            /*$monto_excedente = $cartola->monto;
				// buscamos todos los períodos donde se debe

				$idcartola = $cartola->id;*/
            //print_r($data_ggcc);
            // exit;
            /*foreach ($data_ggcc as $ggcc) {
					//print_r($ggcc);
					$compara_saldo = abs($ggcc->saldo);
					$monto_cartola = $monto_excedente;
					$dif_abono_cartola = $compara_saldo - $monto_cartola;

					if($dif_abono_cartola < 0){ # CARTOLA CUBRE TOTALMENTE LA DEUDA, HAY QUE DIVIDIR
								//$monto_abonado = $compara_saldo;
								//echo "1<p>";
								$this->db->where('id',$idcartola);
								$this->db->update('gc_cartola_propiedad', array('idperiodo' => $ggcc->idperiodo,
																				'monto' => $compara_saldo));

								//echo $this->db->last_query();

								//SE CREA UNA CARTOLA POR LA DIFERENCIA
								$data_ncartola = array(
									'idlistado' => $cartola->idlistado,
							      	'idpropiedad' => $cartola->idpropiedad,
							      	'idperiodo' =>  null,
							        'fechapago' => $cartola->fechapago,
							        'monto' =>  abs($dif_abono_cartola),
							        'idformapago' =>  $cartola->idformapago,
							        'idbanco' =>  $cartola->idbanco,
							        'cheque' =>  $cartola->cheque,
							        'ruttitular' =>  $cartola->ruttitular,
							        'dvtitular' =>  $cartola->dvtitular,
							        'fechadeposito' => $cartola->fechadeposito,
							        'observacion' => $cartola->observacion
								);
								// guarda cartola
								$this->db->insert('gc_cartola_propiedad', $data_ncartola);
								$idcartola = $this->db->insert_id();
							//	echo $idcartola."<br>";

								$this->db->query("update gc_ggcc_propiedad set
																	abonado = abonado + " . $compara_saldo . ",
																	saldo = saldo - " . $compara_saldo . "
																	where id = " . $ggcc->id);



								$monto_excedente = $monto_excedente - $compara_saldo;

                 	}else if($dif_abono_cartola >= 0){ #MARCAMOS CARTOLA PARA EL PERIODO Y REBAJAMOS SALDO
                 			//	echo "2<p>";
						$this->db->where('id',$idcartola);
						$this->db->update('gc_cartola_propiedad', array('idperiodo' => $ggcc->idperiodo));
					//	echo $this->db->last_query();

						$this->db->query("update gc_ggcc_propiedad set
															abonado = abonado + " . $monto_excedente . ",
															saldo = saldo - " . $monto_excedente . "
															where id = " . $ggcc->id);


						break;
                 	}


				}*/
        }

        $this->db->trans_complete();
        //print_r($data_cartola); exit;
    }


    public function get_bodegas($idcomunidad = null)
    {

        $bodegas_data = $this->db->select('b.id, b.nombre, c.nombre as comunidad, p.numero as propiedad, b.prorrateo')
            ->from('gc_bodega as b')
            ->join('gc_propiedad as p', 'b.idpropiedad = p.id')
            ->join('gc_comunidad as c', 'p.idcomunidad = c.id')
            ->where('b.active = 1')
            ->where('c.active = 1')
            ->where('p.active = 1')
            ->order_by('c.nombre asc, p.numero asc, b.nombre');

        $bodegas_data = is_null($idcomunidad) ? $bodegas_data : $bodegas_data->where('c.id', $idcomunidad);
        $query = $this->db->get();
        return $query->result();
    }


    public function get_bodegas_by_propiedad($idpropiedad)
    {

        $this->db->select('b.id, b.nombre, c.nombre as comunidad, p.numero as propiedad, b.prorrateo')
            ->from('gc_bodega as b')
            ->join('gc_propiedad as p', 'b.idpropiedad = p.id')
            ->join('gc_comunidad as c', 'p.idcomunidad = c.id')
            ->where('b.idpropiedad', $idpropiedad)
            ->where('b.active = 1')
            ->where('c.active = 1')
            ->where('p.active = 1')
            ->order_by('c.nombre asc, p.numero asc, b.nombre');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_bodega_by_id($bodegaid)
    {

        $this->db->select('b.id, b.nombre, p.idcomunidad, p.id as idpropiedad, b.prorrateo')
            ->from('gc_bodega as b')
            ->join('gc_propiedad as p', 'b.idpropiedad = p.id')
            ->where('b.id', $bodegaid);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }


    public function get_estacionamientos($idcomunidad = null)
    {

        $estacionamiento_data = $this->db->select('e.id, e.nombre, c.nombre as comunidad, p.numero as propiedad, e.prorrateo')
            ->from('gc_estacionamiento as e')
            ->join('gc_propiedad as p', 'e.idpropiedad = p.id')
            ->join('gc_comunidad as c', 'p.idcomunidad = c.id')
            ->where('e.active = 1')
            ->where('c.active = 1')
            ->where('p.active = 1')
            ->order_by('c.nombre asc, p.numero asc, e.nombre');

        $estacionamiento_data = is_null($idcomunidad) ? $estacionamiento_data : $estacionamiento_data->where('c.id', $idcomunidad);
        $query = $this->db->get();
        return $query->result();
    }


    public function get_fondos($idcomunidad = null)
    {

        $fondos_data = $this->db->select('e.id, e.idcomunidad, e.nombre, c.nombre as comunidad')
            ->from('gc_fondos as e')
            ->join('gc_comunidad as c', 'e.idcomunidad = c.id','LEFT')
            ->where('e.active = 1')
            //->where('c.active = 1')
            ->order_by('c.nombre asc, e.nombre asc');

        $fondos_data = is_null($idcomunidad) ? $fondos_data : $fondos_data->where('(c.id = ' . $idcomunidad . ' or e.idcomunidad = 0)');
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $query->result();
    }


public function get_fondo_by_id($fondoid)
    {

        $this->db->select('e.id, e.nombre, e.idcomunidad')
            ->from('gc_fondos as e')
            ->where('e.id', $fondoid)
            ->where('e.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos = $query->result();

        return $datos;
    }



    public function get_estacionamientos_by_propiedad($idpropiedad)
    {

        $this->db->select('e.id, e.nombre, c.nombre as comunidad, p.numero as propiedad, e.prorrateo')
            ->from('gc_estacionamiento as e')
            ->join('gc_propiedad as p', 'e.idpropiedad = p.id')
            ->join('gc_comunidad as c', 'p.idcomunidad = c.id')
            ->where('e.idpropiedad', $idpropiedad)
            ->where('e.active = 1')
            ->where('c.active = 1')
            ->where('p.active = 1')
            ->order_by('c.nombre asc, p.numero asc, e.nombre');
        $query = $this->db->get();
        return $query->result();
    }


    public function get_estacionamiento_by_id($estacionamientoid)
    {

        $this->db->select('e.id, e.nombre, p.idcomunidad, p.id as idpropiedad, e.prorrateo')
            ->from('gc_estacionamiento as e')
            ->join('gc_propiedad as p', 'e.idpropiedad = p.id')
            ->where('e.id', $estacionamientoid);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }

    public function get_estacionamientos_visitas($idcomunidad = null)
    {

        $estacionamiento_data = $this->db->select('e.id, e.nombre, c.nombre as comunidad')
            ->from('gc_estacionamiento_visita as e')
            ->join('gc_comunidad as c', 'e.idcomunidad = c.id')
            ->where('e.active = 1')
            ->where('c.active = 1')
            ->order_by('c.nombre asc, e.nombre');

        $estacionamiento_data = is_null($idcomunidad) ? $estacionamiento_data : $estacionamiento_data->where('c.id', $idcomunidad);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_estacionamiento_visita_by_id($estacionamientoid)
    {

        $this->db->select('e.id, e.nombre, e.idcomunidad')
            ->from('gc_estacionamiento_visita as e')
            ->where('e.id', $estacionamientoid);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }

    public function get_users($iduser = null, $notificado = null, $user_inic = true)
    {
        $usuario_data = $this->db->select('u.id, u.first_name, u.last_name, concat(u.first_name," ",u.last_name) as nombre, u.email, u.level, l.description as levelname, u.photo ', false)
            ->from('gc_users as u')
            ->join('gc_level as l', 'u.level = l.id')
            ->join('gc_usuario_comunidad as uc', 'u.id = uc.idusuario', 'left')
            ->join('gc_usuario_propiedad as up', 'u.id = up.idusuario', 'left')
            ->join('gc_propiedad as p', 'up.idpropiedad = p.id', 'left')
            ->where('u.active', 1)
            ->where('l.valid', 1)
            ->group_by('u.id')
            ->order_by('u.first_name asc, u.last_name asc');

        $usuario_data = is_null($iduser) ? $usuario_data : $usuario_data->where('u.id', $iduser);
        $usuario_data = $user_inic ? $usuario_data : $usuario_data->where('u.id <> 1');
        $usuario_data = $this->session->userdata('comunidadid') === '' ? $usuario_data : $usuario_data->where_in('u.level', array(1, 2, 3, 5))->where('if(u.level in (2,3),p.idcomunidad=' . $this->session->userdata('comunidadid') . ',uc.idcomunidad= ' . $this->session->userdata('comunidadid') . ')', NULL, FALSE);

        if (!is_null($notificado)) {
            $usuario_data = $notificado ? $usuario_data->where("(inicpass is null or inicpass = '')") : $usuario_data->where("inicpass <> ''");
        }

        $query = $this->db->get();
        
       // echo $this->db->last_query(); exit;
        $datos = is_null($iduser) ? $query->result() : $query->row();

        return $datos;
    }


    public function get_perfiles($idperfil = null)
    {

        $perfil_data = $this->db->select('l.id, l.name, l.description')
            ->from('gc_level as l')
            ->where('l.valid = 1')
            ->order_by('l.description asc');

        $perfil_data = is_null($idperfil) ? $perfil_data : $perfil_data->where('l.id', $idperfil);
        $perfil_data = $this->session->userdata('comunidadid') == '' ? $perfil_data : $perfil_data->where('l.id <> 4');
        $query = $this->db->get();
        $datos = is_null($idperfil) ? $query->result() : $query->row();

        return $query->result();
    }



    public function get_proveedor_by_id($idproveedor = null)
    {

        $proveedores_data = $this->db->select('id , nombre, rut, dv, direccion ')
            ->from('gc_proveedor')
            ->order_by('nombre asc');
        $proveedores_data = is_null($idproveedor) ? $proveedores_data : $proveedores_data->where('id', $idproveedor);
        $query = $this->db->get();
        $datos = is_null($idproveedor) ? $query->result() : $query->row();
        return $datos;
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }


    /*public function get_proveedor_comunidad_by_id($idproveedor = null){

		$proveedores_data = $this->db->select('p.id , p.nombre ')
						  ->from('gc_proveedor p')
						  ->join('gc_proveedor_comunidad pc','p.id = pc.idproveedor')
						  ->where('pc.idcomunidad',$this->session->userdata('comunidadid'))
		                  ->order_by('p.nombre asc');
		$proveedores_data = is_null($idproveedor) ? $proveedores_data : $proveedores_data->where('id',$idproveedor);
		$query = $this->db->get();
		return $query->result();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}*/

    public function get_proveedor_comunidad_by_id($idproveedor = null)
    {

        $proveedores_data = $this->db->select('p.id , p.nombre, p.rut, p.dv, p.direccion ')
            ->from('gc_proveedor p')
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('p.activo = 1')
            ->order_by('p.nombre asc');
        $proveedores_data = is_null($idproveedor) ? $proveedores_data : $proveedores_data->where('id', $idproveedor);
        $query = $this->db->get();
        return $query->result();
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }

    public function get_concepto_by_id($idconcepto = null)
    {

        $concepto_data = $this->db->select('id , nombre ')
            ->from('gc_tipo_deuda_detalle')
            ->where('idtipodeuda not in (10,11)')
            ->order_by('nombre asc');
        $concepto_data = is_null($idconcepto) ? $concepto_data : $concepto_data->where('id', $idconcepto);
        $query = $this->db->get();
        return is_null($idconcepto) ? $query->result() : $query->row();
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }




    public function get_um_esp_comun_by_id($idum = null)
    {

        $um_data = $this->db->select('id , nombre, idcomunidad ')
            ->from('gc_um_espacio_comun')
            ->where('activo', 1)
            ->where('(idcomunidad = ' . $this->session->userdata('comunidadid') . ' or idcomunidad is null)')
            ->order_by('nombre asc');
        $um_data = is_null($idum) ? $um_data : $um_data->where('id', $idum);
        $query = $this->db->get();
        $datos = is_null($idum) ? $query->result() : $query->row();
        return $datos;
    }



    public function get_tipodoc_tributario_by_id($idtipodoc = null)
    {

        $tipodoc_data = $this->db->select('id , nombre ')
            ->from('gc_tipo_documento_tributario')
            ->where('active = 1')
            ->order_by('id asc');
        $proveedores_data = is_null($idtipodoc) ? $tipodoc_data : $tipodoc_data->where('id', $idtipodoc);
        $query = $this->db->get();
        $datos = is_null($idtipodoc) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_cuentas_espacios_comunes_by_id($idconcepto = null)
    {

        $concepto_data = $this->db->select('id , nombre ')
            ->from('gc_tipo_deuda_detalle')
            ->where('idtipodeuda = 10')
            ->order_by('nombre asc');
        $concepto_data = is_null($idconcepto) ? $concepto_data : $concepto_data->where('id', $idconcepto);
        $query = $this->db->get();
        return $query->result();
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }


    public function get_ingresos_comunidad_by_id($idconcepto = null)
    {

        $concepto_data = $this->db->select('id , nombre ')
            ->from('gc_tipo_deuda_detalle')
            ->where('idtipodeuda = 13')
            ->order_by('nombre asc');
        $concepto_data = is_null($idconcepto) ? $concepto_data : $concepto_data->where('id', $idconcepto);
        $query = $this->db->get();
        return is_null($idconcepto) ? $query->result() : $query->row();
    }



    public function get_cuentas_espacios_comunes_comunidad_by_id($idconcepto = null)
    {

        $concepto_data = $this->db->select('tdd.id , tdd.nombre, tdd.idumespcomun, ec.nombre as unidadmedida, tdd.monto ')
            ->from('gc_tipo_deuda_detalle tdd')
            ->join('gc_um_espacio_comun ec', 'tdd.idumespcomun = ec.id', 'left')
            ->where('tdd.idtipodeuda = 10')
            ->where('tdd.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('tdd.activo = 1')
            ->order_by('nombre asc');
        $concepto_data = is_null($idconcepto) ? $concepto_data : $concepto_data->where('tdd.id', $idconcepto);
        $query = $this->db->get();
        $datos = is_null($idconcepto) ? $query->result() : $query->row();
        return $datos;
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }

    public function get_tipos_cuentas_comunidad_by_id($idconcepto = null)
    {

        $concepto_data = $this->db->select('d.id , d.idcomunidad, d.nombre, d.idpadre, d2.nombre as nombrepadre,  (select count(*) from gc_tipo_deuda_detalle where idpadre = d.id) as hijos, cc.idclasif as idclasifcuenta, d.idcomunidad ', false)
            ->from('gc_tipo_deuda_detalle d')
            ->join('gc_tipo_deuda_detalle d2', 'd.idpadre = d2.id', 'left')
            ->join('gc_tipo_deuda_clasif_comunidad cc', 'd.id = cc.idtipodeuda and cc.idcomunidad = ' . $this->session->userdata('comunidadid'), 'left')
            ->where('d.idtipodeuda = 1')
            ->where('(d.idcomunidad = ' . $this->session->userdata('comunidadid') . ' or d.idcomunidad is null)')
            ->where('d.activo = 1')
            ->order_by('d2.id asc');
        $concepto_data = is_null($idconcepto) ? $concepto_data : $concepto_data->where('d.id', $idconcepto);
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        $datos = is_null($idconcepto) ? $query->result() : $query->row();
        return $datos;
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }



    public function get_tipos_cuentas_comunidad_padres_by_id($idconcepto = null)
    {

        $concepto_data = $this->db->select('id , nombre ')
            ->from('gc_tipo_deuda_detalle')
            ->where('idtipodeuda = 1')
            ->where('idpadre is null')
            ->where('(idcomunidad = ' . $this->session->userdata('comunidadid') . ' or idcomunidad is null)')
            ->where('activo = 1')
            ->order_by('nombre asc');
        $concepto_data = is_null($idconcepto) ? $concepto_data : $concepto_data->where('id', $idconcepto);
        $query = $this->db->get();
        $datos = is_null($idconcepto) ? $query->result() : $query->row();
        return $datos;
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }




    public function get_clasif_tipos_cuentas_comunidad()
    {

        $concepto_data = $this->db->select('id , nombre ')
            ->from('gc_clasifica_tipo_cuenta')
            ->where('idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        return $query->result();
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }


    public function get_cuentas_individuales_by_id($idconcepto = null)
    {

        $concepto_data = $this->db->select('id , "td" as tipo_concepto,  nombre ',false)
            ->from('gc_tipo_deuda_detalle')
            ->where('idtipodeuda = 11')
            ->where('activo = 1')
            ->order_by('nombre asc');
        $concepto_data = is_null($idconcepto) ? $concepto_data : $concepto_data->where('id', $idconcepto);
        $query = $this->db->get();

        $data_conceptos1 = $query->result();

        //echo '<pre>';
        
        $data_conceptos2 = $this->get_fondos($this->session->userdata('comunidadid'));


        foreach ($data_conceptos2 as $obj) {

            $obj->tipo_concepto = "f";
            unset($obj->idcomunidad);
            unset($obj->comunidad);
        }

        $data_conceptos = array_merge($data_conceptos1,$data_conceptos2);
        //echo '<pre>';
        //var_dump($data_conceptos);
        return $data_conceptos;




        //return $query->result();
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }



    public function get_cuentas_by_id($idcuenta = null)
    {

        $cuentas_data = $this->db->select('c.id , if(p.nombre is null,c.nombreproveedor,p.nombre) as proveedor, c.idtipodoctrib, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, c.monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion, c.unidadmedida,  c.montounidad, c2.monto as retencion ', false)
            ->from('gc_cuenta c')
            ->join('gc_proveedor p', 'c.idproveedor = p.id', 'left')
            ->join('gc_tipo_deuda_detalle d', 'c.idtipodeudadetalle = d.id')
            ->join('gc_tipo_documento_tributario tdt', 'c.idtipodoctrib = tdt.id', 'left')
            ->join('gc_cuenta c2', 'c2.retencionidctaasoc = c.id', 'left')
            ->where('c.idcomunidad', $this->session->userdata('comunidadid'))
            ->order_by('c.updated_at desc');
        $cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id', $idcuenta);
        $query = $this->db->get();
        //return $query->result();
        return is_null($idcuenta) ? $query->result() : $query->row();
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }


    public function get_ingresos_by_id($idingreso = null)
    {

        $ingresos_data = $this->db->select('i.id , p.id as idproveedor, p.nombre as proveedor, tdt.id as idtipodoctotrib, tdt.nombre as tipodocumentotributario, i.nrodocumento, date_format(i.fecdocumento,"%d/%m/%Y") as fecdocumento, d.id as idconcepto, d.nombre as concepto, i.monto, date_format(i.fecvencimiento,"%d/%m/%Y") as fecvencimiento, i.nombrearchivo, i.updated_at, i.descripcion ')
            ->from('gc_ingresos i')
            ->join('gc_proveedor p', 'i.idproveedor = p.id')
            ->join('gc_tipo_deuda_detalle d', 'i.idtipodeudadetalle = d.id')
            ->join('gc_tipo_documento_tributario tdt', 'i.idtipodoctrib = tdt.id', 'left')
            ->where('i.idcomunidad', $this->session->userdata('comunidadid'))
            ->order_by('i.updated_at desc');
        $ingresos_data = is_null($idingreso) ? $ingresos_data : $ingresos_data->where('i.id', $idingreso);
        $query = $this->db->get();
        //return $query->result();
        return is_null($idingreso) ? $query->result() : $query->row();
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }


    /*public function get_cuentas_by_id_no_autorizada($idcuenta = null){

		$cuentas_data = $this->db->select('c.id , p.nombre as proveedor, tdt.nombre as tipodocumentotributario, c.nrodocumento, date_format(c.fecdocumento,"%d/%m/%Y") as fecdocumento, d.nombre as concepto, c.monto, date_format(c.fecvencimiento,"%d/%m/%Y") as fecvencimiento, c.nombrearchivo, c.updated_at, c.descripcion ')
						  ->from('gc_cuenta c')
						  ->join('gc_proveedor p','c.idproveedor = p.id')
						  ->join('gc_tipo_deuda_detalle d','c.idtipodeudadetalle = d.id')
						  ->join('gc_tipo_documento_tributario tdt','c.idtipodoctrib = tdt.id','left')
						  ->join('gc_ggcc_comunidad gc','c.idggcc = gc.id and gc.tipo = "D"')
						  ->join('gc_periodo_estado pe','gc.idperiodo = pe.idperiodo and pe.idcomunidad = ' . $this->session->userdata('comunidadid'))
						  ->where('c.idcomunidad',$this->session->userdata('comunidadid'))
						  ->where('pe.autoriza is')
		                  ->order_by('c.updated_at desc');
		$cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id',$idcuenta);
		$query = $this->db->get();
		//return $query->result();
		return is_null($idcuenta) ? $query->result() : $query->row();
		//return json_encode($query->result());
		//return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

	}*/


    public function get_cargos_by_id($idcuenta = null)
    {

        $cuentas_data = $this->db->select('c.id , c.nombreproveedor, date_format(c.fecdocumento,"%d/%m/%Y") as fecpago, c.monto, c.nombrearchivo, c.descripcion ')
            ->from('gc_cuenta c')
            ->where('c.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('c.idproveedor is null')
            ->order_by('c.updated_at desc');
        $cuentas_data = is_null($idcuenta) ? $cuentas_data : $cuentas_data->where('c.id', $idcuenta);
        $query = $this->db->get();
        return is_null($idcuenta) ? $query->result() : $query->row();
        //return json_encode($query->result());
        //return is_null($idproveedor) ? json_encode($query->result()) : json_encode($query->row());

    }



    public function get_comunas_by_region($idregion)
    {

        $this->db->select('c.idcomuna , c.nombre ')
            ->from('gc_comuna as c')
            ->join('gc_provincia as p', 'c.idprovincia = p.idprovincia')
            ->join('gc_region as r', 'p.idregion = r.idregion')
            ->where('r.idregion', $idregion)
            ->order_by('c.nombre asc');
        $query = $this->db->get();
        $datos = $query->result_array();


        return $datos;
    }


    public function get_comunidad_by_id($comunidadid)
    {

        $this->db->select('c.id, c.nombre, c.rut,  c.dv, c.direccion, c.fono, c.fono2, c.idregion, c.idcomuna, c.fax, date_format(c.fecconstitucion,"%d/%m/%Y") as fecconstitucion, c.email, c.saldo, c.caja, c.fondoreserva, c.pagoonline, c.token_pagoonline ')
            ->from('gc_comunidad as c')
            ->where('c.id', $comunidadid);
        $query = $this->db->get();
        $datos = $query->row();


        return $datos;
    }

    public function save_comunidad($array_datos)
    {


        $this->db->trans_start();

        #OBTENEMOS DATOS DE COMUNIDAD PARA SABER SI CAMBIÓ EL SALDO INICIAL

        $idcomunidad = $this->session->userdata('comunidadid') == '' ? $array_datos['idcomunidad'] :  $this->session->userdata('comunidadid');
        $comunidad = $this->admin->get_comunidades($array_datos['idcomunidad']);
        $caja_inicial_actual = $comunidad->cajainicial;
        $caja_actual = $comunidad->caja;
        $fr_inicial_actual = $comunidad->fondoreservainicial;
        $fr_actual = $comunidad->fondoreserva;

        $data = array(
            'rut' => $array_datos['rut'],
            'dv' => $array_datos['dv'],
            'direccion' => $array_datos['direccion'],
            'idregion' => $array_datos['idregion'],
            'idcomuna' => $array_datos['idcomuna'],
            'fono' => $array_datos['fono'],
            'fono2' => $array_datos['fono2'],
            'email' => $array_datos['email'],
            'obscomprobante' => $array_datos['obscomprobante'],
        );

        if ($array_datos['logo'] != '') {
            $data['logo'] = $array_datos['logo'];
        } else {
            if ($array_datos['borrarlogo'] == 1) {
                $data['logo'] = '';
            }
        }


        if ($array_datos['firma'] != '') {
            $data['firma'] = $array_datos['firma'];
        } else {
            if ($array_datos['borrarfirma'] == 1) {
                $data['firma'] = '';
            }
        }



        $this->db->where('id', $array_datos['idcomunidad']);
        $this->db->update('gc_comunidad', $data);


        #SI CAJA INICIAL CAMBIA, DEBEMOS ACTUALIZAR LOS SALDOS
        if ($caja_inicial_actual != $array_datos['cajainicial']) {
            $diff_caja = (int)($array_datos['cajainicial'] - $caja_inicial_actual);

            $data_caja = array(
                'cajainicial' => $array_datos['cajainicial'],
                'caja' => ($caja_actual + $diff_caja)
            );

            $this->db->where('id', $array_datos['idcomunidad']);
            $this->db->update('gc_comunidad', $data_caja);


            $this->db->query('update gc_cartola_caja set saldo = saldo + ' . $diff_caja . ' where idcomunidad = ' . $array_datos['idcomunidad']);
        }



        #SI FONDO RESERVA INICIAL CAMBIA, DEBEMOS ACTUALIZAR EL SALDO
        if ($fr_inicial_actual != $array_datos['fondoreservainicial']) {
            $diff_fr = (int)($array_datos['fondoreservainicial'] - $fr_inicial_actual);

            $data_fr = array(
                'fondoreservainicial' => $array_datos['fondoreservainicial'],
                'fondoreserva' => ($fr_actual + $diff_fr)
            );

            $this->db->where('id', $array_datos['idcomunidad']);
            $this->db->update('gc_comunidad', $data_fr);


            $this->db->query('update gc_cartola_fondo_reserva set saldo = saldo + ' . $diff_fr . ' where idcomunidad = ' . $array_datos['idcomunidad']);
        }

        $this->db->trans_complete();
        return 1;
    }


    public function get_propiedad_by_id($propiedadid)
    {

        $this->db->select('p.id, p.idcomunidad, p.numero, p.direccion, p.responsable, p.rutresponsable, p.dvresponsable, p.mail, p.fono, p.suscrito, p.prorrateo, p.prorrateo_propiedad, p.saldo, p.saldo_publicado, p.saldoinicial, c.codigo_comercio, c.enviroment, c.private_key, c.public_cert, c.webpay_cert')
            ->from('gc_propiedad as p')
            ->join('gc_comunidad as c', 'p.idcomunidad = c.id')
            ->where('p.id', $propiedadid);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }


    public function get_propiedad_by_numero($propiedadnum)
    {

        $this->db->select('p.id, p.idcomunidad, p.numero, p.direccion, p.responsable, p.mail, p.fono, p.suscrito, p.prorrateo, p.prorrateo_propiedad, p.saldo, p.saldo_publicado, p.saldoinicial')
            ->from('gc_propiedad as p')
            ->where('p.numero', $propiedadnum)
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        return $query->result();
    }


    public function permite_editar_saldo($idpropiedad)
    {


        //PERMITE EDITAR SOLO SI NO VIENE PROPIEDAD O SI SOLO TIENE SALDO INICIAL SIN ABONOS
        if ($idpropiedad == 0) {

            return 1;
        } else {


            $this->db->select('abonado')
                ->from('gc_ggcc_propiedad as p')
                ->where('p.idpropiedad', $idpropiedad)
                ->where('p.idperiodo', 8);
            $query = $this->db->get();
            $abonado_saldo_inicial = $query->row();

            if(!is_null($abonado_saldo_inicial)){
                if ($abonado_saldo_inicial->abonado > 0) {
                    return 0;
                } else {
                    $this->db->select('count(*) as cantidad', false)
                        ->from('gc_ggcc_propiedad as p')
                        ->where('p.idpropiedad', $idpropiedad)
                        ->where('p.idperiodo <> 8');
                    $query = $this->db->get();
                    $cant_ggcc = $query->row();
                    if ($cant_ggcc->cantidad > 0) {
                        return 0;
                    } else {
                        return 1;
                    }
                }    
            }else{

                return 1;
                
            }


        }
    }

    public function get_propiedad_email_by_id($propiedadid)
    {

        $this->db->select('id, idpropiedad, email')
            ->from('gc_email_propiedad')
            ->where('idpropiedad', $propiedadid);
        $query = $this->db->get();
        $datos = $query->result();

        return $datos;
    }

    public function get_comunidad_adm_email_by_id($comunidadid)
    {

        $this->db->select('u.id, u.email')
            ->from('gc_users u')
            ->join('gc_usuario_comunidad c', 'u.id = c.idusuario')
            ->where('u.level', 1)
            ->where('c.idcomunidad', $comunidadid);
        $query = $this->db->get();
        $datos = $query->result();

        return $datos;
    }

    public function get_propiedad_by_comunidad($comunidadid)
    {

        $this->db->select('p.id, p.idcomunidad, p.numero, p.responsable, p.prorrateo, p.saldo ')
            ->from('gc_propiedad as p')
            ->where('p.idcomunidad', $comunidadid)
            ->where('p.active = 1');
        $query = $this->db->get();
        return $query->result();
    }



    public function get_periodo_by_id($idperiodo)
    {

        $this->db->select('p.id, p.mes, p.anno, date_format(pe.fecha_vencimiento,"%d/%m/%Y") as fecha_vencimiento, date_format(pe.autoriza,"%d/%m/%Y") as autoriza, date_format(pe.genera,"%d/%m/%Y") as genera, date_format(pe.publica,"%d/%m/%Y") as publica, pe.interes ')
            ->from('gc_periodo as p')
            ->join('gc_periodo_estado as pe', 'p.id = pe.idperiodo')
            ->where('pe.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('p.id', $idperiodo);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }


    public function get_periodo_by_id_guest($idperiodo, $idcomunidad)
    {

        $this->db->select('p.id, p.mes, p.anno, date_format(pe.fecha_vencimiento,"%d/%m/%Y") as fecha_vencimiento, date_format(pe.autoriza,"%d/%m/%Y") as autoriza, date_format(pe.genera,"%d/%m/%Y") as genera, date_format(pe.publica,"%d/%m/%Y") as publica, pe.interes ')
            ->from('gc_periodo as p')
            ->join('gc_periodo_estado as pe', 'p.id = pe.idperiodo')
            ->where('pe.idcomunidad', $idcomunidad)
            ->where('p.id', $idperiodo);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }


    public function get_prop_comunidad_guest($idpropiedad, $idcomunidad)
    {

        $this->db->select('p.id')
            ->from('gc_propiedad as p')
            ->where('p.idcomunidad', $idcomunidad)
            ->where('p.id', $idpropiedad);
        $query = $this->db->get();
        $datos = $query->num_rows() == 1 ? $datos = $query->row() : $query->result();

        return $datos;
    }



    public function get_datos_periodo_by_id($idperiodo)
    {

        $this->db->select('p.id, p.mes, p.anno  ')
            ->from('gc_periodo as p')
            ->where('p.id', $idperiodo);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_periodos($comunidadid, $idperiodo = null)
    {

        $periodo_data = $this->db->select("p.id, p.mes, p.anno, date_format(pe.fecha_vencimiento,'%d/%m/%Y') as fecha_vencimiento, date_format(pe.autoriza,'%d/%m/%Y') as autoriza, date_format(pe.genera,'%d/%m/%Y') as genera, date_format(pe.publica,'%d/%m/%Y') as publica, interes, gc.monto as deuda, gc2.monto as fondo_reserva, pe.tipo_interes, case when tipo_interes = 'cd' then 'Diaria' else 'Mensual' end as capitalizacion ", false)
            ->from('gc_periodo as p')
            ->join('gc_periodo_estado as pe', 'p.id = pe.idperiodo')
            ->join('gc_ggcc_comunidad as gc', "gc.idperiodo = pe.idperiodo and gc.idcomunidad = " . $comunidadid . " and gc.tipo = 'D'", 'left')
            ->join('gc_ggcc_comunidad as gc2', "gc2.idperiodo = pe.idperiodo and gc2.idcomunidad = " . $comunidadid . " and gc2.tipo = 'FR'", 'left')
            ->where('pe.idcomunidad', $comunidadid)
            // ->order_by('p.updated_at desc');
            ->order_by('pe.fecha_vencimiento desc');
        $comunidades_data = is_null($idperiodo) ? $periodo_data : $periodo_data->where('pe.idperiodo', $idperiodo);
        $query = $this->db->get();
        $datos = is_null($idperiodo) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_periodos_publicar($comunidadid)
    {

        $this->db->select('p.id, p.mes, p.anno, date_format(pe.fecha_vencimiento,"%d/%m/%Y") as fecha_vencimiento, date_format(pe.autoriza,"%d/%m/%Y") as autoriza, date_format(pe.genera,"%d/%m/%Y") as genera, date_format(pe.publica,"%d/%m/%Y") as publica ')
            ->from('gc_periodo as p')
            ->join('gc_periodo_estado as pe', 'p.id = pe.idperiodo')
            ->where('pe.idcomunidad', $comunidadid)
            ->where('pe.genera is not null')
            ->where('pe.publica is null')
            ->order_by('pe.fecha_vencimiento desc');
        $query = $this->db->get();
        return $query->result();
    }



    public function add_periodo($array_datos)
    {

        $this->db->trans_start();
        $fecvencimiento = substr($array_datos['fecvencimiento'], 6, 4) . "-" . substr($array_datos['fecvencimiento'], 3, 2) . "-" . substr($array_datos['fecvencimiento'], 0, 2);
        $this->db->select('p.id')
            ->from('gc_periodo as p')
            ->where('p.mes', $array_datos['mes'])
            ->where('p.anno', $array_datos['anno']);
        $query = $this->db->get();
        $datos = $query->row();

        if (is_null($datos)) { // nadie a agregado periodo, no hay nada más que validar.  Se crea

            if ($array_datos['idperiodo'] == 0) { // es agregar
                $data = array(
                    'mes' => $array_datos['mes'],
                    'anno' =>  $array_datos['anno']
                );
                // guarda cartola
                $this->db->insert('gc_periodo', $data);

                $idperiodo = $this->db->insert_id();

                $data = array(
                    'idperiodo' => $idperiodo,
                    'idcomunidad' =>  $this->session->userdata('comunidadid'),
                    'fecha_vencimiento' => $fecvencimiento
                );

                $this->db->insert('gc_periodo_estado', $data);
                $this->db->trans_complete();
                return 1;
            }
        } else {
            if ($array_datos['idperiodo'] == 0) { // es agregar
                $idperiodo = $datos->id;
                $this->db->select('pe.idperiodo')
                    ->from('gc_periodo_estado as pe')
                    ->where('pe.idperiodo', $idperiodo)
                    ->where('pe.idcomunidad', $this->session->userdata('comunidadid'));
                $query = $this->db->get();
                $datos = $query->row();
                if (is_null($datos)) { //periodo ya existe, pero no asociado a la comunidad

                    $data = array(
                        'idperiodo' => $idperiodo,
                        'idcomunidad' =>  $this->session->userdata('comunidadid'),
                        'fecha_vencimiento' => $fecvencimiento
                    );

                    $this->db->insert('gc_periodo_estado', $data);
                    $this->db->trans_complete();
                    return 1;
                } else { // periodo existe y asociado a la comunidad
                    $this->db->trans_complete();
                    return -1;
                }
            } else { // estoy editando

                $this->db->where('idperiodo', $array_datos['idperiodo']);
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_periodo_estado', array('fecha_vencimiento' => $fecvencimiento));
                $this->db->trans_complete();
            }
        }
    }



    public function deletefile_comunicado($idcomunicado,$idfile)
    {


        $this->db->where('idcomunicado', $idcomunicado);
        $this->db->where('id', $idfile);
        $this->db->delete('gc_archivos_comunicado');


        if ($this->db->affected_rows() > 0) { // se eliminó el archivo correctamente
            return 1;
        } else { // no hubo eliminación de archivo
            return -1;
        }
    }


    public function delete_periodo($idperiodo)
    {


        $this->db->where('idperiodo', $idperiodo);
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->where('autoriza is null');
        $this->db->delete('gc_periodo_estado');


        if ($this->db->affected_rows() > 0) { // se eliminó periodo correctamente
            return 1;
        } else { // no hubo eliminación de periodo
            return -1;
        }
    }


    public function add_pay($array_datos)
    {


        $this->db->trans_start();
        $comunidad = $this->get_comunidades($array_datos['idcomunidad']);
        $fecpago = substr($array_datos['fecpago'], 6, 4) . "-" . substr($array_datos['fecpago'], 3, 2) . "-" . substr($array_datos['fecpago'], 0, 2);
        $fecvencimientoactual = $comunidad->fecvencimiento_sformat;
        $fecvencimientonuevo = date('Y-m-d', strtotime('+' . $array_datos['numpagos'] . ' month', strtotime($fecvencimientoactual)));
        $data = array(
            'idcomunidad' => $array_datos['idcomunidad'],
            'numpagos' => $array_datos['numpagos'],
            'fecvencimientoactual' => $fecvencimientoactual,
            //'fecvencimientonuevo' => $fecvencimientonuevo,
            //'fecvencimientonuevo' => "DATE_ADD('" . $fecvencimientoactual . "',INTERVAL " . $array_datos['numpagos'] . " MONTH)",
            'fechapago' => $fecpago,
            'montopago' => isset($array_datos['montopago']) ? $array_datos['montopago'] : 0,
            'tokentranskbank' => isset($array_datos['tokentranskbank']) ? $array_datos['tokentranskbank'] : null,
            'tokentgc' => isset($array_datos['tokentgc']) ? $array_datos['tokentgc'] : null
        );

        $this->db->set('fecvencimientonuevo', "DATE_ADD('" . $fecvencimientoactual . "',INTERVAL " . $array_datos['numpagos'] . " MONTH)", false);
        $this->db->insert('gc_log_pagos', $data);
        $idpago = $this->db->insert_id();

        if (!isset($array_datos['tokentranskbank'])) {


            $this->db->select('fecvencimientonuevo')
                ->from('gc_log_pagos')
                ->where('id', $idpago);
            $query = $this->db->get();
            $datos = $query->row();
            $fecvencimientonuevo = $datos->fecvencimientonuevo;
            $data_comunidad = array(
                'fecvencimiento' => $fecvencimientonuevo
            );

            $this->db->where('id', $array_datos['idcomunidad']);
            $this->db->update('gc_comunidad', $data_comunidad);



            $this->generar_mail_pago_servicio($array_datos['idcomunidad'], $fecvencimientonuevo);
        }


        $this->db->trans_complete();


        return $idpago;
    }



public function add_payprop($array_datos)
    {


        $this->db->trans_start();
        //$fecpago = substr($array_datos['fecpago'], 6, 4) . "-" . substr($array_datos['fecpago'], 3, 2) . "-" . substr($array_datos['fecpago'], 0, 2);
        $data = array(
            'idcomunidad' => $array_datos['idcomunidad'],
            'idpropiedad' => $array_datos['idpropiedad'],
            'idperiodo' => $array_datos['idperiodo'],
            'deudatotal' => $array_datos['deudatotal'],
            'fechapago' => $array_datos['fecpago'],
            'pagototal' => $array_datos['pagototal'],
            'montopago' => isset($array_datos['montopago']) ? $array_datos['montopago'] : 0,
            'comision' => isset($array_datos['comision']) ? $array_datos['comision'] : 0,
            'total' => isset($array_datos['total']) ? $array_datos['total'] : 0,
            'tokentranskbank' => isset($array_datos['tokentranskbank']) ? $array_datos['tokentranskbank'] : null,
            'tokentgc' => isset($array_datos['tokentgc']) ? $array_datos['tokentgc'] : null
        );

        $this->db->insert('gc_log_pagos_prop', $data);
        $idpago = $this->db->insert_id();

        if (!isset($array_datos['tokentranskbank'])) {


            //aca se deberia guardar el pago en bd y descontar deuda
            //$this->generar_mail_pago_servicio($array_datos['idcomunidad'], $fecvencimientonuevo);
        }


        $this->db->trans_complete();


        return $idpago;
    }



    public function add_pay_info($token,$payment)
    {

        $this->db->trans_start();
  
            $this->db->where('tokentranskbank', $token);
            $this->db->update('gc_log_pagos', array('paymentinfo' => json_encode($payment)));


        $this->db->trans_complete();


        return 1;
    }


 public function add_payprop_info($token,$payment)
    {

        $this->db->trans_start();
  
            $this->db->where('tokentranskbank', $token);
            $this->db->update('gc_log_pagos_prop', array('paymentinfo' => json_encode($payment)));


        $this->db->trans_complete();


        return 1;
    }


    public function add_trans_abono($array_datos)
    {


        $this->db->trans_start();

        $data = array(
            'idpropiedad' => $array_datos['idpropiedad'],
            'montopago' => isset($array_datos['montopago']) ? $array_datos['montopago'] : 0,
            'periodo' => $array_datos['periodo'],
            'pagototal' => $array_datos['pagototal'],
            'tokentranskbank' => isset($array_datos['tokentranskbank']) ? $array_datos['tokentranskbank'] : null,
        );

        $this->db->insert('gc_log_trans_abonos', $data);
        $this->db->trans_complete();


        return 1;
    }


    public function accept_trans_abono($token)
    {


        $this->db->trans_start();


        $data_comunidad = array(
            'aceptacionpago' => date('Y-m-d H:i:s')
        );

        $this->db->where('tokentranskbank', $token);
        $this->db->update('gc_log_trans_abonos', $data_comunidad);


        $this->db->select('montopago, idpropiedad, periodo, pagototal')
            ->from('gc_log_trans_abonos')
            ->where('tokentranskbank', $token);
        $query = $this->db->get();
        $datos = $query->row();
        $montopago = $datos->montopago;
        $idpropiedad = $datos->idpropiedad;
        $periodo = $datos->periodo;
        $pagototal = $datos->pagototal;
        /*$data_comunidad = array(
								'fecvencimiento' => $fecvencimientonuevo
							);


			$this->db->where('id',$this->session->userdata('comunidadid'));
			$this->db->update('gc_comunidad', $data_comunidad);*/


        $parametros = array(
            'pagototal' => $pagototal,
            'idpropiedad' => $idpropiedad,
            'idperiodo' => $periodo == null ? null : $periodo,
            'monto' => $montopago,
            'fechapago' => date('d/m/Y'),
            'idformapago' => 4,
            'idbanco' =>  null,
            'cheque' =>  null,
            'ruttitular' =>  null,
            'dvtitular' =>  null,
            'fechadeposito' => '00/00/0000',
            'nombrearchivo' => '',
            'nombrerealarchivo' => ''
        );


        $this->load->model('payment');
        $this->payment->add_abono($parametros);


        $this->db->trans_complete();


        return 1;
    }


    public function generar_mail_pago_servicio($comunidadid, $fecvencimientonuevo)
    {


        //hacer código de mail
        $this->load->model('admin');
        $datos_comunidad = $this->admin->datos_comunidad($comunidadid);


        $logo = $datos_comunidad->logo == '' || is_null($datos_comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/' . $this->session->userdata('comunidadid') . '/' . $datos_comunidad->logo;


        $this->load->library('email');

        $messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>A Simple Responsive HTML Email</title>

</head>
<body yahoo="" bgcolor="#f6f8f1">
<table border="1" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="10" cellspacing="0" width="100%" id="emailContainer">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="emailHeader">
                            <tr>
                                <td width="20%" align="center" valign="top" style="font-size: 8px;text-align:left;vertical-align:text-top">
								    <p><b>Se habilit&oacute; servicio para comunidad ' .  $datos_comunidad->nombre . ' hasta ' . $fecvencimientonuevo . '</p>
                                </td>
                                <td width="60%" align="center" valign="middle">
                                    <img class="fix" src="http://www.tugastocomun.cl/app/' . $logo . '" border="0" alt="" style="height: auto;" />
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>';
        //echo $messageBody; exit;

        $array_email = array('rgonzalez@tugastocomun.cl');
        $lista_email = $this->admin->get_comunidad_adm_email_by_id($comunidadid);
        foreach ($lista_email as $lista) {
            array_push($array_email, $lista->email);
        }
        //$this->admin->envia_mail('robot@tugastocomun.cl',$datos_propiedad->mail,'Comprobante de Pago',$messageBody,'html');
        $this->admin->envia_mail('robot@tugastocomun.cl', $array_email, 'Pago Servicio Comunidad ' . $datos_comunidad->nombre, $messageBody, 'html');
    }


public function get_pay_for_token($tokentgc){


            $this->db->select('gp.pagototal, gp.idpropiedad, gp.idperiodo, gp.deudatotal, gp.montopago, gp.tokentgc, gp.aceptacionpago')
                              ->from('gc_log_pagos_prop as gp')
                              ->where('gp.tokentgc', $tokentgc);
            $query = $this->db->get();
            $response = $query->row(); 
            return $response;
}


public function accept_payprop($token = null,$tokentgc = null)
    {


        $this->db->trans_start();


        if(!is_null($token)){
            $data_comunidad = array(
                'aceptacionpago' => date('Y-m-d H:i:s')
            );

            $this->db->where('tokentranskbank', $token);
            $this->db->update('gc_log_pagos_prop', $data_comunidad);

            $this->db->select('gp.pagototal, gp.idpropiedad, gp.idperiodo, gp.deudatotal, gp.montopago')
                              ->from('gc_log_pagos_prop as gp')
                              ->where('gp.tokentranskbank', $token);
            $query = $this->db->get();
            $ggcc_log = $query->row(); 

            $pagototal = $ggcc_log->pagototal;
            $idpropiedad = $ggcc_log->idpropiedad;
            $idperiodo = $ggcc_log->idperiodo;
            $deudatotal = $ggcc_log->deudatotal;  
            $montopago = $ggcc_log->montopago;           

        }else if(!is_null($tokentgc)){

              $data_comunidad = array(
                'aceptacionpago' => date('Y-m-d H:i:s')
            );

            $this->db->where('tokentgc', $tokentgc);
            $this->db->update('gc_log_pagos_prop', $data_comunidad);

            $this->db->select('gp.pagototal, gp.idpropiedad, gp.idperiodo, gp.deudatotal, gp.montopago')
                              ->from('gc_log_pagos_prop as gp')
                              ->where('gp.tokentgc', $tokentgc);
            $query = $this->db->get();
            $ggcc_log = $query->row(); 

            $pagototal = $ggcc_log->pagototal;
            $idpropiedad = $ggcc_log->idpropiedad;
            $idperiodo = $ggcc_log->idperiodo;
            $deudatotal = $ggcc_log->deudatotal; 
            $montopago = $ggcc_log->montopago;             

        }

        //pagototal
        //idpropiedad
        //periododeudatotal

        $parametros = array(
                        'pagototal' => $pagototal,
                        'idpropiedad' => $idpropiedad,
                        'idperiodo' => $idperiodo == null ? null : $idperiodo,
                        'monto' => $pagototal == 'on' ? $deudatotal : $montopago,
                        'fechapago' => date('d/m/Y'),
                        'idformapago' => 4,
                        'idbanco' => null,
                        'cheque' => null,
                        'ruttitular' => null,
                        'dvtitular' => null,
                        'fechadeposito' => '00/00/0000',
                        'nombrearchivo' => '',
                        'nombrerealarchivo' => ''
                        );
        

        $this->load->model('payment');
        $this->payment->add_abono($parametros);



        $this->db->trans_complete();

        return 1;
    }

    public function accept_pay($token = null,$tokentgc = null)
    {


        $this->db->trans_start();


        if(!is_null($token)){
            $data_comunidad = array(
                'aceptacionpago' => date('Y-m-d H:i:s')
            );

            $this->db->where('tokentranskbank', $token);
            $this->db->update('gc_log_pagos', $data_comunidad);


            $this->db->select('fecvencimientonuevo, idcomunidad')
                ->from('gc_log_pagos')
                ->where('tokentranskbank', $token);
            $query = $this->db->get();
            $datos = $query->row();
            $fecvencimientonuevo = $datos->fecvencimientonuevo;
            $idcomunidad = $datos->idcomunidad;


        }else if(!is_null($tokentgc)){

              $data_comunidad = array(
                'aceptacionpago' => date('Y-m-d H:i:s')
            );

            $this->db->where('tokentgc', $tokentgc);
            $this->db->update('gc_log_pagos', $data_comunidad);


            $this->db->select('fecvencimientonuevo, idcomunidad')
                ->from('gc_log_pagos')
                ->where('tokentgc', $tokentgc);
            $query = $this->db->get();
            $datos = $query->row();
            $fecvencimientonuevo = $datos->fecvencimientonuevo;
            $idcomunidad = $datos->idcomunidad;

        }



        $data_comunidad = array(
            'fecvencimiento' => $fecvencimientonuevo
        );


        $this->db->where('id', $idcomunidad);
        $this->db->update('gc_comunidad', $data_comunidad);


        $this->db->trans_complete();
        $datos_result = array('idcomunidad' => $idcomunidad,
                               'fecvencimiento' => $fecvencimientonuevo);

        return $datos_result;
    }



    public function add_proveedor($array_datos)
    {


        $this->db->select('p.id')
            ->from('gc_proveedor as p')
            ->where('upper(p.nombre)', strtoupper($array_datos['proveedor']))
            ->where('p.activo = 1')
            ->where('p.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // nuevo proveedor no existe
            if ($array_datos['idproveedor'] == 0) {
                $data = array(
                    'idcomunidad' => $this->session->userdata('comunidadid'),
                    'nombre' => $array_datos['proveedor'],
                    'rut' => $array_datos['rut'],
                    'dv' => $array_datos['dv'],
                    'direccion' => $array_datos['direccion'],
                    'activo' => 1
                );

                $this->db->insert('gc_proveedor', $data);

                $idproveedor = $this->db->insert_id();

                return 1;
            } else {
                $data = array(
                    'nombre' => $array_datos['proveedor'],
                    'rut' => $array_datos['rut'],
                    'dv' => $array_datos['dv'],
                    'direccion' => $array_datos['direccion'],
                );


                $this->db->where('id', $array_datos['idproveedor']);
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_proveedor', $data);
                return 1;
            }
        } else { // ya existe proveedor nuevo

            if ($array_datos['idproveedor'] != 0) {
                $data = array(
                    'nombre' => $array_datos['proveedor'],
                    'rut' => $array_datos['rut'],
                    'dv' => $array_datos['dv'],
                    'direccion' => $array_datos['direccion'],
                );


                $this->db->where('id', $array_datos['idproveedor']);
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_proveedor', $data);
                return 1;
            } else {
                return -1;
            }
        }
    }



    /*public function add_proveedor($array_datos){

		if($array_datos['nuevo_proveedor'] != ''){ // nuevo proveedor
			$this->db->select('p.id')
							  ->from('gc_proveedor as p')
			                  ->where('upper(p.nombre)', $array_datos['nuevo_proveedor']);
			$query = $this->db->get();
			$datos = $query->row();
			if(count($datos) == 0){ // nuevo proveedor no existe
				$data = array(
			      	'nombre' => $array_datos['nuevo_proveedor']
				);

				$this->db->insert('gc_proveedor', $data);

				$idproveedor = $this->db->insert_id();

				$data = array(
			      	'idproveedor' => $idproveedor,
			      	'idcomunidad' =>  $this->session->userdata('comunidadid')
				);

				$this->db->insert('gc_proveedor_comunidad', $data);
				return 1;
			}else{ // ya existe proveedor nuevo
				return -1;
			}
		}else{  //seleccion de proveedores

			$this->db->select('pc.idproveedor')
							  ->from('gc_proveedor_comunidad as pc')
			                  ->where('pc.idproveedor', $array_datos['proveedor'])
			                  ->where('pc.idcomunidad', $this->session->userdata('comunidadid'));
			$query = $this->db->get();
			$datos = $query->row();

			if(count($datos) == 0){ // proveedor seleccionado no existe

				$data = array(
			      	'idproveedor' => $array_datos['proveedor'],
			      	'idcomunidad' =>  $this->session->userdata('comunidadid')
				);

				$this->db->insert('gc_proveedor_comunidad', $data);
				return 1;

			}else{ // proveedor seleccionado existe
				return -2;

			}

		}


	}	*/





    public function delete_proveedor($idproveedor)
    {


        $this->db->where('id', $idproveedor);
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_proveedor', array('activo' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente
            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }


    public function add_esp_comun($array_datos)
    {


        $this->db->select('td.id')
            ->from('gc_tipo_deuda_detalle as td')
            ->where('upper(td.nombre)', strtoupper($array_datos['espaciocomun']))
            ->where('td.idtipodeuda = 10')
            ->where('td.activo = 1')
            ->where('td.idcomunidad', $this->session->userdata('comunidadid'));
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // nuevo proveedor no existe
            if ($array_datos['idespaciocomun'] == 0) {
                $data = array(
                    'idtipodeuda' => 10,
                    'nombre' => $array_datos['espaciocomun'],
                    'idumespcomun' => $array_datos['unidadmedida'],
                    'monto' => $array_datos['monto'],
                    'idcomunidad' => $this->session->userdata('comunidadid'),
                    'activo' => 1
                );

                $this->db->insert('gc_tipo_deuda_detalle', $data);

                $idespaciocomun = $this->db->insert_id();
                return 1;
            } else {

                $data = array(
                    'nombre' => $array_datos['espaciocomun'],
                    'idumespcomun' => $array_datos['unidadmedida'],
                    'monto' => $array_datos['monto'],
                );

                $this->db->where('id', $array_datos['idespaciocomun']);
                $this->db->where('idtipodeuda = 10');
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_tipo_deuda_detalle', array('nombre' => $data));
                return 1;
            }
        } else { // ya existe proveedor nuevo

            if ($array_datos['idespaciocomun'] != 0) {

                $espacio_comun = $this->admin->get_cuentas_espacios_comunes_comunidad_by_id($array_datos['idespaciocomun']);
                if (strtoupper($array_datos['espaciocomun']) == strtoupper($espacio_comun->nombre)) { #se está corrigiendo el mismo
                    $data = array(
                        'idumespcomun' => $array_datos['unidadmedida'],
                        'monto' => $array_datos['monto'],
                    );

                    $this->db->where('id', $array_datos['idespaciocomun']);
                    $this->db->where('idtipodeuda = 10');
                    $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                    $this->db->update('gc_tipo_deuda_detalle', $data);
                } else {
                    return -1;
                }
            } else {
                return -1;
            }
        }
    }



    public function add_tipo_cuenta($array_datos)
    {

        $this->db->trans_start();
        $this->db->select('td.id')
            ->from('gc_tipo_deuda_detalle as td')
            ->where('upper(td.nombre)', strtoupper($array_datos['tipocuenta']))
            ->where('td.idtipodeuda = 1')
            ->where('td.activo = 1')
            ->where('(td.idcomunidad = ' . $this->session->userdata('comunidadid') . ' or td.idcomunidad is null)');
        $query = $this->db->get();
        $datos = $query->row();

        if (is_null($datos)) { // nuevo proveedor no existe
            if ($array_datos['idtipocuenta'] == 0) {
                $data = array(
                    'idtipodeuda' => 1,
                    'nombre' => $array_datos['tipocuenta'],
                    'idpadre' => $array_datos['concepto'],
                    //	'idclasifcuenta' => $array_datos['idclasifcuenta'],
                    'idcomunidad' => $this->session->userdata('comunidadid'),
                    'activo' => 1
                );

                $this->db->insert('gc_tipo_deuda_detalle', $data);

                $idespaciocomun = $this->db->insert_id();


                if ($array_datos['idclasifcuenta'] != null) {
                    $data_clasif = array(
                        'idtipodeuda' => $idespaciocomun,
                        'idclasif' => $array_datos['idclasifcuenta'],
                        'idcomunidad' => $this->session->userdata('comunidadid')
                    );

                    $this->db->insert('gc_tipo_deuda_clasif_comunidad', $data_clasif);
                }


                $this->db->trans_complete();
                return 1;
            } else {


                $this->db->select('td.idcomunidad')
                    ->from('gc_tipo_deuda_detalle as td')
                    ->where('id', $array_datos['idtipocuenta']);
                $query_comunidad = $this->db->get();

                //echo $this->db->last_query();
                $datos_comunidad = $query_comunidad->row();
                //var_dump($datos_comunidad); exit;

                if (!is_null($datos_comunidad->idcomunidad)) {
                    $data = array(
                        'nombre' => $array_datos['tipocuenta'],
                        'idpadre' => $array_datos['concepto'],
                        //'idclasifcuenta' => $array_datos['idclasifcuenta']
                    );

                    $this->db->where('id', $array_datos['idtipocuenta']);
                    $this->db->where('idtipodeuda = 1');
                    $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                    $this->db->update('gc_tipo_deuda_detalle', $data);
                }



                if ($array_datos['idclasifcuenta'] != null) { // SE ELIMINA E INSERTA NUEVAMENTE
                    $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                    $this->db->where('idtipodeuda', $array_datos['idtipocuenta']);
                    $this->db->delete('gc_tipo_deuda_clasif_comunidad');

                    $data_clasif = array(
                        'idtipodeuda' => $array_datos['idtipocuenta'],
                        'idclasif' => $array_datos['idclasifcuenta'],
                        'idcomunidad' => $this->session->userdata('comunidadid')
                    );

                    $this->db->insert('gc_tipo_deuda_clasif_comunidad', $data_clasif);
                } else {

                    $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                    $this->db->where('idtipodeuda', $array_datos['idtipocuenta']);
                    $this->db->delete('gc_tipo_deuda_clasif_comunidad');
                }
                $this->db->trans_complete();
                return 1;
            }
        } else { // ya existe proveedor nuevo
            if ($array_datos['idtipocuenta'] != 0) {


                $this->db->select('td.idcomunidad')
                    ->from('gc_tipo_deuda_detalle as td')
                    ->where('id', $array_datos['idtipocuenta']);
                $query_comunidad = $this->db->get();
                $datos_comunidad = $query_comunidad->row();

                if (!is_null($datos_comunidad->idcomunidad)) {
                    $data = array(
                        'nombre' => $array_datos['tipocuenta'],
                        'idpadre' => $array_datos['concepto'],
                        //'idclasifcuenta' => $array_datos['idclasifcuenta'],
                    );

                    $this->db->where('id', $array_datos['idtipocuenta']);
                    $this->db->where('idtipodeuda = 1');
                    $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                    $this->db->update('gc_tipo_deuda_detalle', $data);
                }

                if ($array_datos['idclasifcuenta'] != null) { // SE ELIMINA E INSERTA NUEVAMENTE
                    $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                    $this->db->where('idtipodeuda', $array_datos['idtipocuenta']);
                    $this->db->delete('gc_tipo_deuda_clasif_comunidad');

                    $data_clasif = array(
                        'idtipodeuda' => $array_datos['idtipocuenta'],
                        'idclasif' => $array_datos['idclasifcuenta'],
                        'idcomunidad' => $this->session->userdata('comunidadid')
                    );

                    $this->db->insert('gc_tipo_deuda_clasif_comunidad', $data_clasif);
                } else {

                    $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                    $this->db->where('idtipodeuda', $array_datos['idtipocuenta']);
                    $this->db->delete('gc_tipo_deuda_clasif_comunidad');
                }


                $this->db->trans_complete();
            } else {
                $this->db->trans_complete();
                return -1;
            }
        }
    }



    public function add_um_esp_comunes($array_datos)
    {


        $this->db->select('id')
            ->from('gc_um_espacio_comun')
            ->where('upper(nombre)', strtoupper($array_datos['unidadmedida']))
            ->where('activo = 1')
            ->where('(idcomunidad = ' . $this->session->userdata('comunidadid') . ' or idcomunidad is null)');
        $query = $this->db->get();
        $datos = $query->row();

        if (is_null($datos)) { // nuevo proveedor no existe

            if ($array_datos['idunidadmedida'] == 0) {
                $data = array(
                    'nombre' => $array_datos['unidadmedida'],
                    'idcomunidad' => $this->session->userdata('comunidadid'),
                    'activo' => 1
                );

                $this->db->insert('gc_um_espacio_comun', $data);

                $idespaciocomun = $this->db->insert_id();
                return 1;
            } else {
                $data = array(
                    'nombre' => $array_datos['unidadmedida']
                );

                $this->db->where('id', $array_datos['idunidadmedida']);
                $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
                $this->db->update('gc_um_espacio_comun', $data);
                return 1;
            }
        } else { // ya existe proveedor nuevo
            if ($array_datos['idunidadmedida'] != 0) {
                $unidadmedida = $this->admin->get_um_esp_comun_by_id($array_datos['idunidadmedida']);
                if ($array_datos['unidadmedida'] != $unidadmedida->nombre) {
                    return -1;
                }
            } else {
                return -1;
            }
        }
    }

    public function delete_esp_comun($idespaciocomun)
    {


        $this->db->where('id', $idespaciocomun);
        $this->db->where('idtipodeuda = 10');
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_tipo_deuda_detalle', array('activo' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó espacio comun correctamente
            return 1;
        } else { // no hubo eliminación de espacio comun
            return -1;
        }
    }



    public function delete_tipo_cuenta($idtipocuenta)
    {


        $this->db->where('id', $idtipocuenta);
        $this->db->where('idtipodeuda = 1');
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_tipo_deuda_detalle', array('activo' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó el tipo de cuenta.  Se eliminan los hijos
            $this->db->where('idpadre', $idtipocuenta);
            $this->db->where('idtipodeuda = 1');
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_tipo_deuda_detalle', array('activo' => '0'));
            return 1;
        } else { // no hubo eliminación de espacio comun
            return -1;
        }
    }


    #http://localhost/app_ggcc_v3/admins/delete_um_esp_comunes/10

    public function delete_um_esp_comun($idunidadmedida)
    {

        $unidadmedida = $this->admin->get_um_esp_comun_by_id($idunidadmedida);
        //var_dump($unidadmedida);
        if (is_null($unidadmedida)) {
            //echo "asdasdasd"; exit;
            return -1;
        } else {
            $this->db->where('id', $idunidadmedida);
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_um_espacio_comun', array('activo' => '0'));
            return 1;
        }
    }

    public function get_tipo_concepto()
    {

        $concepto_data = $this->db->select('id , nombre ')
            ->from('gc_tipo_deuda')
            ->where('id in (1,10)')
            ->order_by('nombre asc');
        $query = $this->db->get();
        return $query->result();
    }


    public function get_forma_pago($tipo = null)
    {



        $fc_data = $this->db->select('id , nombre ')
            ->from('gc_forma_pago')
            ->where('activo = 1')
            ->order_by('nombre asc');

        if (is_null($tipo)) {
            $fc_data = $fc_data;
        } else if ($tipo == 'abono') {
            $fc_data = $fc_data->where('abono', 1);
        } else if ($tipo == 'pago') {
            $fc_data = $fc_data->where('pago', 1);
        } else {
            $fc_data = $fc_data;
        }

        $query = $this->db->get();
        return $query->result();
    }



    public function get_banco()
    {

        $this->db->select('id , nombre ')
            ->from('gc_banco')
            ->where('activo = 1')
            ->order_by('id asc');
        $query = $this->db->get();
        return $query->result();
    }


    public function get_periodo_inicial()
    {

        $this->db->select('id ')
            ->from('gc_periodo')
            ->where('inicial = 1')
            ->limit(1);
        $query = $this->db->get();
        return $query->row();
    }


    public function add_comunidad($array_datos)
    {

        $this->db->trans_start();
        $objperiodoinicio = $this->get_periodo_by_mes(substr($array_datos['fecinicio'], 3, 2), substr($array_datos['fecinicio'], 6, 4));
        $fecinicio = substr($array_datos['fecinicio'], 6, 4) . "-" . substr($array_datos['fecinicio'], 3, 2) . "-" . substr($array_datos['fecinicio'], 0, 2);

        $idperiodoinicio = isset($objperiodoinicio->id) ? $objperiodoinicio->id : 1;

        $this->db->select('c.id')
            ->from('gc_comunidad as c')
            ->where('upper(c.nombre)', strtoupper($array_datos['comunidad']))
            ->where('c.active = 1');
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // nueva comunidad no existe
            if ($array_datos['idcomunidad'] == 0) {
                $data = array(
                    'nombre' => $array_datos['comunidad'],
                    'rut' => $array_datos['rut'],
                    'dv' => $array_datos['dv'],
                    'direccion' => $array_datos['direccion'],
                    'idregion' => $array_datos['idregion'],
                    'idcomuna' => $array_datos['idcomuna'],
                    'fono' => $array_datos['fono'],
                    'fono2' => $array_datos['fono2'],
                    'email' => $array_datos['email'],
                    'saldo' => $array_datos['saldo'],
                    'cajainicial' => $array_datos['caja'],
                    'caja' => $array_datos['caja'],
                    'fondoreservainicial' => $array_datos['fondoreserva'],
                    'fondoreserva' => $array_datos['fondoreserva'],
                    'fecinicio' => $fecinicio,
                    'idperiodoinicio' => $idperiodoinicio,
                    'fecvencimiento' => $array_datos['fecvencimiento'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'active' => 1
                );

                $this->db->insert('gc_comunidad', $data);

                $idcomunidad = $this->db->insert_id();

                // GENERACION DE PERIODO PARA SALDO INICIAL
                $periodo_inicial = $this->get_periodo_inicial();

                $data = array(
                    'idperiodo' => $periodo_inicial->id,
                    'idcomunidad' => $idcomunidad,
                    'fecha_vencimiento' => date("Y-m-d"),
                    'autoriza' => date("Y-m-d H:i:s"),
                    'genera' => date("Y-m-d H:i:s"),
                    'publica' => date("Y-m-d H:i:s")
                );

                $this->db->insert('gc_periodo_estado', $data);


                // ALMACENA INFORMACION DE SALDOS INICIALES
                $data = array(
                    'tipo' => 'D',
                    'idcomunidad' => $idcomunidad,
                    'idperiodo' => $periodo_inicial->id,
                    'monto' => $array_datos['saldo'],
                    'abonado' => 0,
                    'saldo' => $array_datos['saldo'],
                );

                $this->db->insert('gc_ggcc_comunidad', $data);


                $data = array(
                    'tipo' => 'FR',
                    'idcomunidad' => $idcomunidad,
                    'idperiodo' => $periodo_inicial->id,
                    'monto' => $array_datos['fondoreserva'],
                    'abonado' => 0,
                    'saldo' => $array_datos['fondoreserva'],
                );

                $this->db->insert('gc_ggcc_comunidad', $data);
                $fondo_reserva_id = $this->db->insert_id();


                $data = array(
                    'idcomunidad' => $idcomunidad,
                    'idggcc' => $fondo_reserva_id,
                    'glosa' =>  'Abono a Fondo de Reserva por Saldo Inicial',
                    'monto' => $array_datos['fondoreserva'],
                    'saldo' =>  $array_datos['fondoreserva'],
                    'created_at' => date("Y-m-d h:i:s")
                );

                $this->db->insert('gc_cartola_fondo_reserva', $data);

                $this->db->trans_complete();
                return $idcomunidad;
            } else {

                $data = array(
                    'nombre' => $array_datos['comunidad'],
                    'rut' => $array_datos['rut'],
                    'dv' => $array_datos['dv'],
                    'direccion' => $array_datos['direccion'],
                    'idregion' => $array_datos['idregion'],
                    'idcomuna' => $array_datos['idcomuna'],
                    'fono' => $array_datos['fono'],
                    'fono2' => $array_datos['fono2'],
                    'email' => $array_datos['email'],
                    'fecinicio' => $fecinicio,
                    'idperiodoinicio' => $idperiodoinicio,
                );

                $this->db->where('id', $array_datos['idcomunidad']);
                $this->db->update('gc_comunidad', $data);
                $this->db->trans_complete();

                return 1;
            }
        } else { // ya existe comunidad nuevo

            if ($array_datos['idcomunidad'] != 0) {
                $data = array(
                    'nombre' => $array_datos['comunidad'],
                    'rut' => $array_datos['rut'],
                    'dv' => $array_datos['dv'],
                    'direccion' => $array_datos['direccion'],
                    'idregion' => $array_datos['idregion'],
                    'idcomuna' => $array_datos['idcomuna'],
                    'fono' => $array_datos['fono'],
                    'fono2' => $array_datos['fono2'],
                    'email' => $array_datos['email'],
                    'fecinicio' => $fecinicio,
                    'idperiodoinicio' => $idperiodoinicio,
                );

                $this->db->where('id', $array_datos['idcomunidad']);
                $this->db->update('gc_comunidad', $data);

                $this->db->trans_complete();
                return $array_datos['idcomunidad'];
            } else {
                $this->db->trans_complete();
                return -1;
            }
        }
    }



    public function delete_comunidad($idcomunidad)
    {


        $this->db->where('id', $idcomunidad);
        $this->db->update('gc_comunidad', array('active' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente
            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }



    public function add_propiedad($array_datos, $array_email = array())
    {

        $this->db->trans_start();


        $this->db->select('p.id')
            ->from('gc_propiedad as p')
            ->join('gc_comunidad c', 'p.idcomunidad = c.id')
            ->where('upper(p.numero)', strtoupper($array_datos['numpropiedad']))
            ->where('p.idcomunidad', strtoupper($array_datos['comunidad']))
            ->where('p.active', 1);

        $query = $this->db->get();
        $datos = $query->row();
        //echo "<pre>";
        //print_r($array_datos); exit;
        if (is_null($datos)) { // propiedad no existe
            if ($array_datos['idpropiedad'] == 0) {
                $data = array(
                    'idcomunidad' => $array_datos['comunidad'],
                    'numero' => $array_datos['numpropiedad'],
                    'direccion' => $array_datos['direccion'],
                    'responsable' => $array_datos['responsable'],
                    'rutresponsable' => $array_datos['rutresponsable'],
                    'dvresponsable' => $array_datos['dvresponsable'],
                    'mail' => $array_datos['email'],
                    'fono' => $array_datos['fono'],
                    'suscrito' => $array_datos['suscrito'],
                    'prorrateo' => $array_datos['prorrateo'],
                    'prorrateo_propiedad' => $array_datos['prorrateo'],
                    'saldo' => $array_datos['saldo'],
                    'saldo_publicado' => $array_datos['saldo'],
                    'saldoinicial' => $array_datos['saldo'],
                    'active' => 1
                );
                
                $this->db->insert('gc_propiedad', $data);

                $idpropiedad = $this->db->insert_id();

                if (count($array_email) > 0) {
                    $this->db->where('idpropiedad', $idpropiedad);
                    $this->db->delete('gc_email_propiedad');
                    foreach ($array_email as $key => $info_email) {
                        $data = array(
                            'idpropiedad' => $idpropiedad,
                            'email' => $info_email
                        );
                        $this->db->insert('gc_email_propiedad', $data);
                    }
                }


                $periodo_inicial = $this->get_periodo_inicial();
                $data = array(
                    'idpropiedad' => $idpropiedad,
                    'idperiodo' => $periodo_inicial->id,
                    'monto' => $array_datos['saldo'] < 0 ? 0 : $array_datos['saldo'],
                    'abonado' => 0,
                    'saldo' => $array_datos['saldo'] < 0 ? 0 : $array_datos['saldo'],
                    'created_at' => date("Y-m-d H:i:s")
                );

                $this->db->insert('gc_ggcc_propiedad', $data);


                $idggcc = $this->db->insert_id();

                $data = array(
                    'idggcc' => $idggcc,
                    'monto' => $array_datos['saldo'],
                    'intereses' => 0
                );

                $this->db->insert('gc_ggcc_saldo', $data);
                $this->db->trans_complete();

                return $idpropiedad;
            } else {

                $this->db->select('p.id, p.prorrateo_propiedad')
                    ->from('gc_propiedad as p')
                    ->where('p.id', $array_datos['idpropiedad']);
                $query = $this->db->get();
                $datos = $query->row();


                $this->db->query("update gc_propiedad set prorrateo = prorrateo - " . $datos->prorrateo_propiedad . " where id = " . $array_datos['idpropiedad']);
                $this->db->query("update gc_propiedad set prorrateo = prorrateo + " . $array_datos['prorrateo'] . " where id = " . $array_datos['idpropiedad']);

                $data = array(
                    'idcomunidad' => $array_datos['comunidad'],
                    'numero' => $array_datos['numpropiedad'],
                    'direccion' => $array_datos['direccion'],
                    'responsable' => $array_datos['responsable'],
                    'rutresponsable' => $array_datos['rutresponsable'],
                    'dvresponsable' => $array_datos['dvresponsable'],                    
                    'mail' => $array_datos['email'],
                    'fono' => $array_datos['fono'],
                    'suscrito' => $array_datos['suscrito'],
                    'prorrateo_propiedad' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->where('id', $array_datos['idpropiedad']);
                $this->db->update('gc_propiedad', $data);

                if (count($array_email) > 0) {
                    $this->db->where('idpropiedad', $array_datos['idpropiedad']);
                    $this->db->delete('gc_email_propiedad');
                    foreach ($array_email as $key => $info_email) {
                        $data = array(
                            'idpropiedad' => $array_datos['idpropiedad'],
                            'email' => $info_email
                        );
                        $this->db->insert('gc_email_propiedad', $data);
                    }
                }



                if ($array_datos['saldo'] != '') { //HUBO CORRECCIÓN DE SALDO (HACER CAMBIOS)
                    $array_data_act = array(
                        'saldo' => $array_datos['saldo'],
                        'saldo_publicado' => $array_datos['saldo'],
                        'saldoinicial' => $array_datos['saldo']
                    );


                    $this->db->where('id', $array_datos['idpropiedad']);
                    $this->db->update('gc_propiedad', $array_data_act);

                    $this->db->select('p.id')
                        ->from('gc_ggcc_propiedad as p')
                        ->where('p.idpropiedad', $array_datos['idpropiedad'])
                        ->where('p.idperiodo', 8)
                        ->limit(1);
                    $query = $this->db->get();
                    $datos = $query->row();
                    $idggcc = $datos->id;

                    $array_data_ggcc = array(
                        'monto' => $array_datos['saldo'] < 0 ? 0 : $array_datos['saldo'],
                        'abonado' => 0,
                        'saldo' => $array_datos['saldo'] < 0 ? 0 : $array_datos['saldo']
                    );

                    $this->db->where('id', $idggcc);
                    $this->db->update('gc_ggcc_propiedad', $array_data_ggcc);


                    $array_data_saldo = array(
                        'monto' => $array_datos['saldo'],
                        'intereses' => 0
                    );
                    $this->db->where('idggcc', $idggcc);
                    $this->db->update('gc_ggcc_saldo', $array_data_saldo);
                }

                $this->db->trans_complete();
                return $array_datos['idpropiedad'];
            }
        } else { // ya existe comunidad nuevo

            if ($array_datos['idpropiedad'] != 0) {

                $this->db->select('p.id, p.prorrateo_propiedad')
                    ->from('gc_propiedad as p')
                    ->where('p.id', $array_datos['idpropiedad']);
                $query = $this->db->get();
                $datos = $query->row();


                $this->db->query("update gc_propiedad set prorrateo = prorrateo - " . $datos->prorrateo_propiedad . " where id = " . $array_datos['idpropiedad']);
                $this->db->query("update gc_propiedad set prorrateo = prorrateo + " . $array_datos['prorrateo'] . " where id = " . $array_datos['idpropiedad']);



                $data = array(
                    'idcomunidad' => $array_datos['comunidad'],
                    'numero' => $array_datos['numpropiedad'],
                    'direccion' => $array_datos['direccion'],
                    'responsable' => $array_datos['responsable'],
                    'rutresponsable' => $array_datos['rutresponsable'],
                    'dvresponsable' => $array_datos['dvresponsable'],                    
                    'mail' => $array_datos['email'],
                    'fono' => $array_datos['fono'],
                    'suscrito' => $array_datos['suscrito'],
                    'prorrateo_propiedad' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->where('id', $array_datos['idpropiedad']);
                $this->db->update('gc_propiedad', $data);


                if (count($array_email) > 0) {
                    $this->db->where('idpropiedad', $array_datos['idpropiedad']);
                    $this->db->delete('gc_email_propiedad');
                    foreach ($array_email as $key => $info_email) {
                        $data = array(
                            'idpropiedad' => $array_datos['idpropiedad'],
                            'email' => $info_email
                        );
                        $this->db->insert('gc_email_propiedad', $data);
                    }
                }


                if ($array_datos['saldo'] != '') { //HUBO CORRECCIÓN DE SALDO (HACER CAMBIOS)
                    $array_data_act = array(
                        'saldo' => $array_datos['saldo'],
                        'saldo_publicado' => $array_datos['saldo'],
                        'saldoinicial' => $array_datos['saldo']
                    );


                    $this->db->where('id', $array_datos['idpropiedad']);
                    $this->db->update('gc_propiedad', $array_data_act);

                    $this->db->select('p.id')
                        ->from('gc_ggcc_propiedad as p')
                        ->where('p.idpropiedad', $array_datos['idpropiedad'])
                        ->where('p.idperiodo', 8)
                        ->limit(1);
                    $query = $this->db->get();
                    $datos = $query->row();
                    $idggcc = $datos->id;

                    $array_data_ggcc = array(
                        'monto' => $array_datos['saldo'] < 0 ? 0 : $array_datos['saldo'],
                        'abonado' => 0,
                        'saldo' => $array_datos['saldo'] < 0 ? 0 : $array_datos['saldo']
                    );

                    $this->db->where('id', $idggcc);
                    $this->db->update('gc_ggcc_propiedad', $array_data_ggcc);


                    $array_data_saldo = array(
                        'monto' => $array_datos['saldo'],
                        'intereses' => 0
                    );
                    $this->db->where('idggcc', $idggcc);
                    $this->db->update('gc_ggcc_saldo', $array_data_saldo);
                }


                $this->db->trans_complete();
                return $array_datos['idpropiedad'];
            } else {
                $this->db->trans_complete();
                return -1;
            }
        }
    }



    public function delete_propiedad($idpropiedad)
    {


        $this->db->where('id', $idpropiedad);
        $this->db->update('gc_propiedad', array('active' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente
            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }



    public function add_bodega($array_datos)
    {

        $this->db->select('b.id')
            ->from('gc_bodega as b')
            ->join('gc_propiedad p', 'b.idpropiedad = p.id')
            ->where('upper(b.nombre)', strtoupper($array_datos['nombre']))
            ->where('p.idcomunidad', strtoupper($array_datos['idcomunidad']));
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // propiedad no existe
            if ($array_datos['idbodega'] == 0) {
                $data = array(
                    'idpropiedad' => $array_datos['idpropiedad'],
                    'nombre' => $array_datos['nombre'],
                    'prorrateo' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->insert('gc_bodega', $data);
                $idbodega = $this->db->insert_id();

                $this->db->query("update gc_propiedad set prorrateo = prorrateo + " . $array_datos['prorrateo'] . " where id = " . $array_datos['idpropiedad']);

                return $idbodega;
            } else {
                $this->db->select('b.idpropiedad, b.prorrateo')
                    ->from('gc_bodega as b')
                    ->where('b.id', $array_datos['idbodega']);
                $query = $this->db->get();
                $datos = $query->row();

                //if($datos->idpropiedad != $array_datos['idpropiedad']){ // si se edita la propiedad, debo cambiar el prorrateo
                $this->db->query("update gc_propiedad set prorrateo = prorrateo - " . $datos->prorrateo . " where id = " . $datos->idpropiedad);
                $this->db->query("update gc_propiedad set prorrateo = prorrateo + " . $array_datos['prorrateo'] . " where id = " . $array_datos['idpropiedad']);
                //}

                $data = array(
                    'idpropiedad' => $array_datos['idpropiedad'],
                    'nombre' => $array_datos['nombre'],
                    'prorrateo' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->where('id', $array_datos['idbodega']);
                $this->db->update('gc_bodega', $data);

                return $array_datos['idbodega'];
            }
        } else { // ya existe bodega nuevo

            if ($array_datos['idbodega'] != 0) {
                $this->db->select('b.idpropiedad, b.prorrateo')
                    ->from('gc_bodega as b')
                    ->where('b.id', $array_datos['idbodega']);
                $query = $this->db->get();
                $datos = $query->row();

                //if($datos->idpropiedad != $array_datos['idpropiedad']){ // si se edita la propiedad, debo cambiar el prorrateo
                $this->db->query("update gc_propiedad set prorrateo = prorrateo - " . $datos->prorrateo . " where id = " . $datos->idpropiedad);
                $this->db->query("update gc_propiedad set prorrateo = prorrateo + " . $array_datos['prorrateo'] . " where id = " . $array_datos['idpropiedad']);
                //}

                $data = array(
                    'idpropiedad' => $array_datos['idpropiedad'],
                    'nombre' => $array_datos['nombre'],
                    'prorrateo' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->where('id', $array_datos['idbodega']);
                $this->db->update('gc_bodega', $data);


                return $array_datos['idbodega'];
            } else {
                return -1;
            }
        }
    }


    public function delete_bodega($idbodega)
    {


        $this->db->where('id', $idbodega);
        $this->db->where('idpropiedad in (select id from gc_propiedad where idcomunidad = ' . $this->session->userdata('comunidadid') . ')');
        $this->db->update('gc_bodega', array('active' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente

            $this->db->select('b.idpropiedad, b.prorrateo')
                ->from('gc_bodega as b')
                ->where('b.id', $idbodega);
            $query = $this->db->get();
            $datos = $query->row();

            $this->db->query("update gc_propiedad set prorrateo = prorrateo - " . $datos->prorrateo . " where id = " . $datos->idpropiedad);


            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }


    public function add_estacionamiento($array_datos)
    {

        $this->db->select('e.id, e.active')
            ->from('gc_estacionamiento as e')
            ->join('gc_propiedad p', 'e.idpropiedad = p.id')
            ->where('upper(e.nombre)', strtoupper($array_datos['nombre']))
            ->where('p.idcomunidad', strtoupper($array_datos['idcomunidad']));
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // propiedad no existe
            if ($array_datos['idestacionamiento'] == 0) {
                $data = array(
                    'idpropiedad' => $array_datos['idpropiedad'],
                    'nombre' => $array_datos['nombre'],
                    'prorrateo' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->insert('gc_estacionamiento', $data);
                $idbodega = $this->db->insert_id();

                $this->db->query("update gc_propiedad set prorrateo = prorrateo + " . $array_datos['prorrateo'] . " where id = " . $array_datos['idpropiedad']);

                return $idbodega;
            } else {
                $this->db->select('e.idpropiedad, e.prorrateo')
                    ->from('gc_estacionamiento as e')
                    ->where('e.id', $array_datos['idestacionamiento']);
                $query = $this->db->get();
                $datos = $query->row();

                //if($datos->idpropiedad != $array_datos['idpropiedad']){ // si se edita la propiedad, debo cambiar el prorrateo
                $this->db->query("update gc_propiedad set prorrateo = prorrateo - " . $datos->prorrateo . " where id = " . $datos->idpropiedad);
                $this->db->query("update gc_propiedad set prorrateo = prorrateo + " . $array_datos['prorrateo'] . " where id = " . $array_datos['idpropiedad']);
                //}

                $data = array(
                    'idpropiedad' => $array_datos['idpropiedad'],
                    'nombre' => $array_datos['nombre'],
                    'prorrateo' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->where('id', $array_datos['idestacionamiento']);
                $this->db->update('gc_estacionamiento', $data);

                return $array_datos['idestacionamiento'];
            }
        } else { // ya existe bodega nuevo

            if ($array_datos['idestacionamiento'] != 0) {
                $this->db->select('e.idpropiedad, e.prorrateo')
                    ->from('gc_estacionamiento as e')
                    ->where('e.id', $array_datos['idestacionamiento']);
                $query = $this->db->get();
                $datos = $query->row();

                //if($datos->idpropiedad != $array_datos['idpropiedad']){ // si se edita la propiedad, debo cambiar el prorrateo
                $this->db->query("update gc_propiedad set prorrateo = prorrateo - " . $datos->prorrateo . " where id = " . $datos->idpropiedad);
                $this->db->query("update gc_propiedad set prorrateo = prorrateo + " . $array_datos['prorrateo'] . " where id = " . $array_datos['idpropiedad']);
                //}

                $data = array(
                    'idpropiedad' => $array_datos['idpropiedad'],
                    'nombre' => $array_datos['nombre'],
                    'prorrateo' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->where('id', $array_datos['idestacionamiento']);
                $this->db->update('gc_estacionamiento', $data);


                return $array_datos['idestacionamiento'];
            } else if ($datos->active == 0) {
                $data = array(
                    'idpropiedad' => $array_datos['idpropiedad'],
                    'nombre' => $array_datos['nombre'],
                    'prorrateo' => $array_datos['prorrateo'],
                    'active' => 1
                );

                $this->db->where('id', $datos->id);
                $this->db->update('gc_estacionamiento', $data);

                return 1;
            } else {
                return -1;
            }
        }
    }





    public function add_fondo($array_datos)
    {

        $this->db->select('e.id, e.active')
            ->from('gc_fondos as e')
            ->where('upper(e.nombre)', strtoupper($array_datos['nombre']))
            ->where('e.idcomunidad', strtoupper($array_datos['idcomunidad']));
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // propiedad no existe
            if ($array_datos['idfondo'] == 0) {
                $data = array(
                    'idcomunidad' => $array_datos['idcomunidad'],
                    'nombre' => $array_datos['nombre'],
                    'active' => 1
                );

                $this->db->insert('gc_fondos', $data);
                $idfondo = $this->db->insert_id();

                return $idfondo;
            } else {

                $data = array(
                    'idcomunidad' => $array_datos['idcomunidad'],
                    'nombre' => $array_datos['nombre'],
                    'active' => 1
                );

                $this->db->where('id', $array_datos['idfondo']);
                $this->db->update('gc_fondos', $data);

                return $array_datos['idfondo'];
            }
        } else { // ya existe fondo

            if ($array_datos['idfondo'] != 0) {

                $data = array(
                    'idcomunidad' => $array_datos['idcomunidad'],
                    'nombre' => $array_datos['nombre'],
                    'active' => 1
                );

                $this->db->where('id', $array_datos['idfondo']);
                $this->db->update('gc_fondos', $data);


                return $array_datos['idestacionamiento'];
            } else if ($datos->active == 0) {
                $data = array(
                    'idcomunidad' => $array_datos['idcomunidad'],
                    'nombre' => $array_datos['nombre'],
                    'active' => 1
                );

                $this->db->where('id', $datos->id);
                $this->db->update('gc_fondos', $data);

                return -2;
            } else {
                return -1;
            }
        }
    }    

    public function add_estacionamiento_visita($array_datos)
    {

        $this->db->select('e.id, e.active')
            ->from('gc_estacionamiento_visita as e')
            ->where('upper(e.nombre)', strtoupper($array_datos['nombre']))
            ->where('e.idcomunidad', strtoupper($array_datos['idcomunidad']));
        $query = $this->db->get();
        $datos = $query->row();
        if (is_null($datos)) { // propiedad no existe
            if ($array_datos['idestacionamiento'] == 0) {
                $data = array(
                    'idcomunidad' => $array_datos['idcomunidad'],
                    'nombre' => $array_datos['nombre'],
                    'active' => 1,
                    'valid' => 1,
                    'created_at' => date("Y-m-d H:i:s")
                );

                $this->db->insert('gc_estacionamiento_visita', $data);
                $idestacionamiento = $this->db->insert_id();

                return $idestacionamiento;
            } else {
                $data = array(
                    'idcomunidad' => $array_datos['idcomunidad'],
                    'nombre' => $array_datos['nombre'],
                    'active' => 1,
                    'valid' => 1
                );

                $this->db->where('id', $array_datos['idestacionamiento']);
                $this->db->update('gc_estacionamiento_visitas', $data);

                return $array_datos['idestacionamiento'];
            }
        } else {

            if ($array_datos['idestacionamiento'] != 0) {

                $data = array(
                    'idcomunidad' => $array_datos['idcomunidad'],
                    'nombre' => $array_datos['nombre'],
                    'active' => 1,
                    'valid' => 1
                );

                $this->db->where('id', $array_datos['idestacionamiento']);
                $this->db->update('gc_estacionamiento_visita', $data);

                return $array_datos['idestacionamiento'];
            } else if ($datos->active == 0) {
                $data = array(
                    'idcomunidad' => $array_datos['idcomunidad'],
                    'nombre' => $array_datos['nombre'],
                    'active' => 1,
                    'valid' => 1
                );

                $this->db->where('id', $datos->id);
                $this->db->update('gc_estacionamiento_visita', $data);

                return 1;
            } else {
                return -1;
            }
        }
    }

    public function delete_estacionamiento($idestacionamiento)
    {


        $this->db->where('id', $idestacionamiento);
        $this->db->where('idpropiedad in (select id from gc_propiedad where idcomunidad = ' . $this->session->userdata('comunidadid') . ')');
        $this->db->update('gc_estacionamiento', array('active' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente

            $this->db->select('e.idpropiedad, e.prorrateo')
                ->from('gc_estacionamiento as e')
                ->where('e.id', $idestacionamiento);
            $query = $this->db->get();
            $datos = $query->row();

            $this->db->query("update gc_propiedad set prorrateo = prorrateo - " . $datos->prorrateo . " where id = " . $datos->idpropiedad);


            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }



    public function delete_fondo($idfondo)
    {


        $this->db->where('id', $idfondo);
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_fondos', array('active' => '0'));


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente

            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }

    public function delete_estacionamiento_visita($idestacionamiento)
    {


        $this->db->where('id', $idestacionamiento);
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_estacionamiento_visita', array('active' => '0'));


        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return -1;
        }
    }

    public function put_conciliacion($array_conciliacion)
    {

        $fecha_conciliacion = is_null($array_conciliacion['fecha_conciliacion']) ? null : substr($array_conciliacion['fecha_conciliacion'], 6, 4) . "-" . substr($array_conciliacion['fecha_conciliacion'], 3, 2) . "-" . substr($array_conciliacion['fecha_conciliacion'], 0, 2);
        $data = array(
            'fechaconciliacion' => $fecha_conciliacion,
        );

        $sql_fec_conciliacion = is_null($fecha_conciliacion) ? " = null" : " = '" . $fecha_conciliacion . "'";
        if ($array_conciliacion['tipo_movimiento'] == 'p') {

            $this->db->query("update gc_listado_pagos l
										 inner join gc_cartola_pagos cp on l.id = cp.idlistado
										 inner join gc_cartola_caja cc on cp.id = cc.idpago
										 set l.fechaconciliacion " . $sql_fec_conciliacion . ",
										 	 cc.fechaconciliacion " . $sql_fec_conciliacion . "
										 where l.id = " . $array_conciliacion['movimiento'] . " and cc.idcomunidad = " . $this->session->userdata('comunidadid'));
        } else if ($array_conciliacion['tipo_movimiento'] == 'a') {
            $this->db->query("update gc_listado_abonos l
										 inner join gc_cartola_propiedad cp on l.id = cp.idlistado
										 inner join gc_cartola_caja cc on cp.id = cc.idabono
										 set l.fechaconciliacion " . $sql_fec_conciliacion . ",
										 	 cc.fechaconciliacion " . $sql_fec_conciliacion . "
										 where l.id = " . $array_conciliacion['movimiento'] . " and cc.idcomunidad = " . $this->session->userdata('comunidadid'));
        } else {

            $this->db->where('id', $array_conciliacion['movimiento']);
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_cartola_caja', $data);
        }


        return strtotime($fecha_conciliacion) > strtotime(date("Y-m-d")) ? 0 : 1;
    }



    public function delete_movimiento($array_datos)
    {
        $this->db->trans_start();
        $fecha_protesto = substr($array_datos['fecprotesto'], 6, 4) . "-" . substr($array_datos['fecprotesto'], 3, 2) . "-" . substr($array_datos['fecprotesto'], 0, 2);
        $this->load->model('account');
        //$movimiento = $this->account->get_movimiento_by_id($array_datos['idmovimiento']);
        $movimiento = $array_datos['tipo_movimiento'] == 'p' ? $this->account->get_mov_pago_by_id($array_datos['idmovimiento']) : $this->account->get_mov_abono_by_id($array_datos['idmovimiento']);
        //$tipo_movimiento = is_null($movimiento->idpago) ?  'abono' : 'pago';

        if (!is_null($movimiento->fechaconciliacion)) { #NO ES POSIBLE ELIMINAR UN MOVIMIENTO QUE YA SE CONCILIÓ
            return -1;
        }


        if ($array_datos['motivo'] == 'error' || $array_datos['motivo'] == 'reemplazo') { // eliminar movimiento y todos los datos asociados
            if ($array_datos['tipo_movimiento'] == 'p') { // PAGO

                $datoscomunidad = $this->get_comunidad_by_id($this->session->userdata('comunidadid'));
                $saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;


                $ggcc = $this->db->select('c.id, cp.id as idpago, c.idggcc, c.formapago, cp.monto, cp.idlistado, c.idtipodoctrib')
                    ->from('gc_cartola_pagos cp')
                    ->join('gc_cuenta c', 'cp.idcuenta = c.id')
                    ->where('cp.idlistado', $array_datos['idmovimiento']);
                $query = $this->db->get();
                $datos_cartola = $query->result();

                foreach ($datos_cartola as $result) { // VA ELIMINANDO CADA DETALLE DEL MOVIMIENTO


                    $idpago = $result->idpago;
                    $idggcc = $result->idggcc;
                    $formapago = $result->formapago;
                    $idcuenta = $result->id;
                    $monto_pago = $result->monto;
                    $idlistado = $result->idlistado;
                    $idtipodoctrib = $result->idtipodoctrib;

                    // quita abono a cuenta
                    $this->db->query("update gc_cuenta set
																abonado = abonado - " . abs($monto_pago) . ",
																saldo = saldo + " . abs($monto_pago) . "
																where id = " . $idcuenta);


                    if (!is_null($idggcc)) { // aumenta deuda de gasto comun, ya que el pago se anula

                        # NO RECUERDO PORQUÉ ESTABA CON VALOR ABSOLUTO ACÁ, PERO CREO QUE NO CORRESPONDE
                        /*$this->db->query("update gc_ggcc_comunidad set
																	abonado = abonado - " . abs($monto_pago) . ",
																	saldo = saldo + " . abs($monto_pago) . "
																	where id = " . $idggcc);	*/

                        $this->db->query("update gc_ggcc_comunidad set
																	abonado = abonado - " . $monto_pago . ",
																	saldo = saldo + " . $monto_pago . "
																	where id = " . $idggcc);
                    }

                    if ($formapago == 'fr') { // si es fondo de reserva, se devuelve la plata al fondo de reserva
                        $this->db->query("update gc_comunidad set
																	fondoreserva = fondoreserva + " . $monto_pago . "
																	where id = " . $this->session->userdata('comunidadid'));


                        $saldo_fondo_reserva_actual = ($saldo_fondo_reserva_actual + $monto_pago);
                        $data_fr = array(
                            'idcomunidad' => $this->session->userdata('comunidadid'),
                            'idcuenta' => $idcuenta,
                            'glosa' =>  'Eliminación de pago asociado a movimiento ' . trackid($array_datos['idmovimiento']),
                            'monto' =>  $monto_pago,
                            'saldo' =>  $saldo_fondo_reserva_actual,
                            'created_at' => date("Y-m-d h:i:s")
                        );

                        $this->db->insert('gc_cartola_fondo_reserva', $data_fr);
                    }

                    // aumenta deuda y caja en comunidad
                    $this->db->query("update gc_comunidad set
																saldo = saldo + " . $monto_pago . ",
																caja = caja + " . $monto_pago . "
																where id = " . $this->session->userdata('comunidadid'));



                    // anula movimiento
                    $this->db->where('idpago', $idpago);
                    $this->db->update('gc_cartola_caja', array('activo' => 0));
                }

                // anula pago
                $this->db->where('idlistado', $array_datos['idmovimiento']);
                $this->db->update('gc_cartola_pagos', array(
                    'activo' => 0,
                    'updated_at' => date("Y-m-d H:i:s")
                ));

                // anula movimiento
                $this->db->where('id', $array_datos['idmovimiento']);
                $this->db->update('gc_listado_pagos', array('activo' => 0));

                /**** rebaja monto del listado asociado al pago ********/

                /*$this->db->query("update gc_listado_pagos set
															monto = monto - " . abs($monto_pago) . "
															where id = " . $idlistado);				*/
            } else if ($array_datos['tipo_movimiento'] == 'a') { // ABONOS
                $ggcc = $this->db->select('id as idabono, idpropiedad, idperiodo, monto, idlistado')
                    ->from('gc_cartola_propiedad')
                    ->where('idlistado', $array_datos['idmovimiento'])
                    ->order_by('idperiodo desc');
                $query = $this->db->get();
                $datos_cartola = $query->result();


                foreach ($datos_cartola as $result) {
                    $idabono = $result->idabono;
                    $idpropiedad = $result->idpropiedad;
                    $idperiodo = $result->idperiodo;
                    $monto_ggcc = $result->monto;
                    $idlistado = $result->idlistado;

                    //aumenta saldo gasto común propiedad al quitar abono realizado
                    if (!is_null($idperiodo)) {
                        $this->db->query("update gc_ggcc_propiedad set
																	abonado = abonado - " . abs($monto_ggcc) . ",
																	saldo = saldo + " . abs($monto_ggcc) . "
																	where idpropiedad = " . $idpropiedad . " and idperiodo = " . $idperiodo);
                    }

                    // aumenta deuda propiedad al quitar abono realizado
                    $this->db->query("update gc_propiedad set
																saldo = saldo + " . abs($monto_ggcc) . ",
																saldo_publicado = saldo_publicado + " . abs($monto_ggcc) . "
																where id = " . $idpropiedad);

                    //rebaja flujo de caja de comunidad
                    $this->db->query("update gc_comunidad set
																caja = caja - " . abs($monto_ggcc) . "
																where id = " . $this->session->userdata('comunidadid'));



                    // anula movimiento
                    $this->db->where('idabono', $idabono);
                    $this->db->update('gc_cartola_caja', array('activo' => 0));
                }


                // anula abono
                $this->db->where('idlistado', $array_datos['idmovimiento']);
                $this->db->update('gc_cartola_propiedad', array(
                    'activo' => 0,
                    'updated_at' => date("Y-m-d H:i:s")
                ));

                // anula movimiento
                $this->db->where('id', $array_datos['idmovimiento']);
                $this->db->update('gc_listado_abonos', array(
                    'activo' => 0,
                    'updated_at' => date("Y-m-d H:i:s")
                ));

                /**** rebaja monto del listado asociado al abono ********/
                /*$this->db->query("update gc_listado_abonos set
															monto = monto - " . abs($monto_ggcc) . "
															where id = " . $idlistado);
				*/
            }
        } else if ($array_datos['motivo'] == 'protesto') { // generar movimiento negativo

            if ($array_datos['tipo_movimiento'] == 'p') { // PAGO

                $datoscomunidad = $this->get_comunidad_by_id($this->session->userdata('comunidadid'));
                $saldo_caja_actual = $datoscomunidad->caja;
                $saldo_fondo_reserva_actual = $datoscomunidad->fondoreserva;


                $fechapago = date("Y-m-d");
                $this->load->model('payment');

                $data = array(
                    'idcomunidad' => $this->session->userdata('comunidadid'),
                    'folio' => $this->payment->get_correlativo_pago(),
                    'monto' => $movimiento->monto * (-1),
                    'fechapago' => $fecha_protesto,
                    'idformapago' =>  $movimiento->idformapago,
                    'cheque' =>  $movimiento->cheque,
                    'paguesea' =>  'Protesto de Documento en movimiento ' . trackid($array_datos['folio']),
                    'observacion' => $array_datos['descripcion'],
                    'idprotesto' => $array_datos['idmovimiento']
                );

                $this->db->insert('gc_listado_pagos', $data);
                $listado_pagos_id = $this->db->insert_id();


                $this->db->select('c.id, cp.id as idpago, c.idggcc, c.formapago, cp.idformapago, cp.idbanco, cp.cheque, cp.ruttitular, cp.dvtitular, cp.monto, cp.idlistado, c.idtipodoctrib')
                    ->from('gc_cartola_pagos cp')
                    ->join('gc_cuenta c', 'cp.idcuenta = c.id')
                    ->where('cp.idlistado', $array_datos['idmovimiento']);
                $query = $this->db->get();
                $datos_cartola = $query->result();

                foreach ($datos_cartola as $result) { // VA ELIMINANDO CADA DETALLE DEL MOVIMIENTO


                    $idpago = $result->idpago;
                    $idggcc = $result->idggcc;
                    $formapago = $result->formapago;
                    $idcuenta = $result->id;
                    $monto_pago = $result->monto;
                    $idlistado = $result->idlistado;
                    $idtipodoctrib = $result->idtipodoctrib;

                    $this->db->query("update gc_cuenta set
																abonado = abonado - " . abs($monto_pago) . ",
																saldo = saldo + " . abs($monto_pago) . "
																where id = " . $idcuenta);

                    if (!is_null($idggcc)) { // aumenta deuda de gasto comun, ya que el pago se anula
                        $this->db->query("update gc_ggcc_comunidad set
																	abonado = abonado - " . $monto_pago . ",
																	saldo = saldo + " . $monto_pago . "
																	where id = " . $idggcc);
                    }

                    if ($formapago == 'fr') { // si es fondo de reserva, se devuelve la plata al fondo de reserva
                        $this->db->query("update gc_comunidad set
																	fondoreserva = fondoreserva + " . $monto_pago . "
																	where id = " . $this->session->userdata('comunidadid'));


                        $saldo_fondo_reserva_actual = ($saldo_fondo_reserva_actual + $monto_pago);
                        $data_fr = array(
                            'idcomunidad' => $this->session->userdata('comunidadid'),
                            'idcuenta' => $idcuenta,
                            'glosa' =>  'Protesto de pago asociado a movimiento ' . trackid($array_datos['folio']),
                            'monto' =>  $monto_pago,
                            'saldo' =>  $saldo_fondo_reserva_actual,
                            'created_at' => date("Y-m-d h:i:s")
                        );

                        $this->db->insert('gc_cartola_fondo_reserva', $data_fr);
                    }

                    // aumenta deuda y caja en comunidad
                    $this->db->query("update gc_comunidad set
																saldo = saldo + " . $monto_pago . ",
																caja = caja + " . $monto_pago . "
																where id = " . $this->session->userdata('comunidadid'));





                    $data = array(
                        'idcuenta' => $idcuenta,
                        'idlistado' =>  $listado_pagos_id,
                        'monto' => (-1) * $monto_pago,
                        'fechapago' => $fecha_protesto,
                        'idformapago' =>  $result->idformapago,
                        'idbanco' =>  $result->idbanco,
                        'cheque' =>  $result->cheque,
                        'ruttitular' =>  $result->ruttitular,
                        'dvtitular' =>  $result->dvtitular,
                        'observacion' => $array_datos['descripcion']
                    );

                    $this->db->insert('gc_cartola_pagos', $data);

                    $cartola_pagos_id = $this->db->insert_id();
                    $saldo_caja_actual    += $monto_pago;


                    $data = array(
                        'idcomunidad' => $this->session->userdata('comunidadid'),
                        'idpago' => $cartola_pagos_id,
                        'glosa' =>  'Protesto de Documento en movimiento ' . trackid($array_datos['folio']),
                        'monto' => $monto_pago,
                        'saldo' =>  $saldo_caja_actual,
                        'fechapago' => $fecha_protesto,
                        'created_at' => date("Y-m-d h:i:s")
                    );

                    $this->db->insert('gc_cartola_caja', $data);
                }






                /**** rebaja monto del listado asociado al pago ********/
                /*$this->db->query("update gc_listado_pagos set
															monto = monto - " . abs($monto_pago) . "
															where id = " . $idlistado);				*/



                /*******  marca protesto *********/
                // anula movimiento
                $this->db->where('id', $array_datos['idmovimiento']);
                $this->db->update('gc_listado_pagos', array('protesto' => 1));


                $this->db->query("update gc_cartola_caja cc
										 inner join gc_cartola_pagos cp on cc.idpago = cp.id
										 set cc.protesto = 1
										 where cp.idlistado  = " . $array_datos['idmovimiento']);
            } else if ($array_datos['tipo_movimiento'] == 'a') { //ABONO

                $datoscomunidad = $this->admin->get_comunidad_by_id($this->session->userdata('comunidadid'));
                $saldo_caja_actual = $datoscomunidad->caja;

                $this->load->model('payment');
                $fechapago = date("Y-m-d");
                $data = array(
                    'idcomunidad' => $this->session->userdata('comunidadid'),
                    'folio' => $this->payment->get_correlativo_abono(),
                    'idpropiedad' => $movimiento->idpropiedad,
                    'monto' => $movimiento->monto * (-1),
                    'fechapago' => $fecha_protesto,
                    'idformapago' =>  $movimiento->idformapago,
                    'idbanco' =>  $movimiento->idbanco,
                    'cheque' =>  $movimiento->cheque,
                    'ruttitular' =>  $movimiento->ruttitular,
                    'dvtitular' =>  $movimiento->dvtitular,
                    'fechadeposito' => $movimiento->fechadeposito,
                    'idprotesto' => $array_datos['idmovimiento'],
                    'created_at' => date("Y-m-d H:i:s")
                );

                $this->db->insert('gc_listado_abonos', $data);
                $listado_abonos_id = $this->db->insert_id();

                $ggcc = $this->db->select('id as idabono, idpropiedad, idperiodo, idformapago, idbanco, cheque, ruttitular, dvtitular, monto, idlistado')
                    ->from('gc_cartola_propiedad')
                    ->where('idlistado', $array_datos['idmovimiento']);
                $query = $this->db->get();
                $datos_cartola = $query->result();

                foreach ($datos_cartola as $result) { // VA ELIMINANDO CADA DETALLE DEL MOVIMIENTO


                    $idabono = $result->idabono;
                    $idpropiedad = $result->idpropiedad;
                    $idperiodo = $result->idperiodo;
                    $monto_ggcc = $result->monto;
                    $idlistado = $result->idlistado;

                    //aumenta saldo gasto común propiedad al quitar abono realizado

                    if (!is_null($idperiodo)) {
                        $this->db->query("update gc_ggcc_propiedad set
																	abonado = abonado - " . abs($monto_ggcc) . ",
																	saldo = saldo + " . abs($monto_ggcc) . "
																	where idpropiedad = " . $idpropiedad . " and idperiodo = " . $idperiodo);
                    }


                    // aumenta deuda propiedad al quitar abono realizado
                    $this->db->query("update gc_propiedad set
																saldo = saldo + " . abs($monto_ggcc) . ",
																saldo_publicado = saldo_publicado + " . abs($monto_ggcc) . "
																where id = " . $idpropiedad);

                    //rebaja flujo de caja de comunidad
                    $this->db->query("update gc_comunidad set
																caja = caja - " . abs($monto_ggcc) . "
																where id = " . $this->session->userdata('comunidadid'));

                    $fechapago = date("Y-m-d");
                    $data = array(
                        'idlistado' => $listado_abonos_id,
                        'idpropiedad' => $idpropiedad,
                        'idperiodo' =>  $idperiodo,
                        'fechapago' => $fecha_protesto,
                        'monto' =>  $monto_ggcc * (-1),
                        'idformapago' =>  $result->idformapago,
                        'idbanco' =>  $result->idbanco,
                        'cheque' =>  $result->cheque,
                        'ruttitular' =>  $result->ruttitular,
                        'dvtitular' =>  $result->dvtitular,
                        'observacion' => $array_datos['descripcion']
                    );
                    // guarda cartola
                    $this->db->insert('gc_cartola_propiedad', $data);
                    $saldo_caja_actual += $movimiento->monto * (-1);

                    // guarda cartola caja
                    $cartola_propiedad_id = $this->db->insert_id();

                    $data = array(
                        'idcomunidad' => $this->session->userdata('comunidadid'),
                        'idabono' => $cartola_propiedad_id,
                        'glosa' =>  'Protesto de Documento en movimiento ' . trackid($array_datos['folio']),
                        'monto' => $monto_ggcc * (-1),
                        'saldo' =>  $saldo_caja_actual,
                        'fechapago' => $fecha_protesto,
                        'created_at' => date("Y-m-d h:i:s")
                    );

                    $this->db->insert('gc_cartola_caja', $data);
                }



                /*******  marca protesto *********/
                // anula movimiento
                $this->db->where('id', $array_datos['idmovimiento']);
                $this->db->update('gc_listado_abonos', array('protesto' => 1));


                $this->db->query("update gc_cartola_caja cc
										 inner join gc_cartola_propiedad cp on cc.idabono = cp.id
										 set cc.protesto = 1
										 where cp.idlistado  = " . $array_datos['idmovimiento']);
            }
        }

        $this->db->trans_complete();
        return 1;
    }



    public function valida_existe_mail($email, $iduser)
    {

        $user_data = $this->db->select('u.id ')
            ->from('gc_users u')
            ->where('u.email', $email)
            ->where('u.active', 1);

        $user_data = $iduser == 0 ? $user_data : $user_data->where('u.id <>', $iduser);
        $query = $this->db->get();
        return count($query->result()) > 0 ? true : false;
    }



    public function valida_existe_mail_user($email)
    {

        $this->db->select('u.id, u.level, u.active ')
            ->from('gc_users u')
            ->where('u.email', $email);

        $query = $this->db->get();
        $usuario = $query->row();

        return !is_null($usuario) ? $usuario : false;
    }

    public function valida_existe_mail_personal($email)
    {
        $this->db->select('u.id, u.active, p.id as idpersonal, p.rut, p.dv')
            ->from('gc_users u')
            ->join('gc_personal p', 'u.id = p.iduser')
            ->where('u.email', $email);
        $query = $this->db->get();
        $result = $query->row();

        return $result;
    }


    public function mail_creacion_usuario($userid, $password)
    {


        $this->load->library('email');


        $datos_usuario = $this->get_users($userid);

        if (isset($datos_usuario->nombre) && isset($datos_usuario->email)) {
            $messageBody = 'Estimado(a)';
            $messageBody .= ' ' . $datos_usuario->nombre . ":<br>";
            $messageBody .= "<br>Hemos creado un usuario para que ud. pueda acceder a nuestra plataforma a revisar toda la información asociada a su comunidad<br>";
            $messageBody .= "<br>Para ingresar, debe dirigirse a:<br>";
            $messageBody .= "http://www.tugastocomun.cl/app<br><br>";
            $messageBody .= "y allí colocar sus datos:<br><br>";
            $messageBody .= "Nombre de usuario: " . $datos_usuario->email . "<br>Contraseña: " . $password . "<br><br>";
            $messageBody .= "Asegúrese de guardar estos datos, y por su seguridad modificar su clave lo antes posible.<br><br>";
            $messageBody .= "Esto último lo puede realizar a través de la opción \"Mi Cuenta\", ingresando su clave actual y posteriormente la nueva que ud. desee.<br><br>";
            $messageBody .= "Saludos cordiales,<br>Equipo de Tu Gasto Común.";


            $this->envia_mail('robot@tugastocomun.cl', $datos_usuario->email, 'Proceso Creación de Usuario', $messageBody, 'html');
        }

        //$this->email->set_mailtype("html");
        /*$this->email->from('robot@tugastocomun.cl', 'Tu Gasto Común');
	      $this->email->to($datos_usuario->email);
	      //$this->email->bcc('rgonzalez@aurbana.cl');
	      //$this->email->bcc('adolfo@aurbana.cl');
	      //$this->email->bcc(array('rgonzalez@aurbana.cl','adolfo@aurbana.cl','rodrigog.84@gmail.com'));
	      $this->email->subject('Proceso Creación de Usuario');
	      $this->email->message($messageBody);
	      try {
	        $this->email->send();
	        //var_dump($this->email->print_debugger());
	        	        //exit;
	      } catch (Exception $e) {
	        echo $e->getMessage() . '<br />';
	        echo $e->getCode() . '<br />';
	        echo $e->getFile() . '<br />';
	        echo $e->getTraceAsString() . '<br />';
	        echo "no";

	      }  */
    }

    public function valida_existe_propiedad($comunidadid, $numpropiedad, $idpropiedad)
    {

        $propiedad_data = $this->db->select('p.id ')
            ->from('gc_propiedad p')
            ->where('p.numero', $numpropiedad)
            ->where('p.idcomunidad', $comunidadid)
            ->where('p.active', 1);

        $propiedad_data = $idpropiedad == 0 ? $propiedad_data : $propiedad_data->where('p.id <>', $idpropiedad);
        $query = $this->db->get();
        return count($query->result()) > 0 ? true : false;

        
    }


    public function edit_profile($parametros, $userid)
    {


        $this->db->where('id', $userid);
        $this->db->update('gc_users', $parametros);


        if ($this->db->affected_rows() > 0) { // se eliminó proveedor correctamente
            return 1;
        } else { // no hubo eliminación de proveedor
            return -1;
        }
    }


    public function get_ultimo_periodo()
    {

        $queryQuestion = $this->db->query("select idperiodo from gc_periodo_estado
						where idcomunidad = " . $this->session->userdata('comunidadid') . " and genera = (select max(genera) from gc_periodo_estado where idcomunidad = " . $this->session->userdata('comunidadid') . ") limit 1");
        return $queryQuestion->row();
    }



    public function get_periodo_by_mes($mes, $anno)
    {

        $this->db->select('id ')
            ->from('gc_periodo')
            ->where('mes', $mes)
            ->where('anno', $anno);

        $query = $this->db->get();
        return $query->row();
    }


    public function get_permite_periodo($mes, $anno)
    {

        $this->db->trans_start();
        $datos_comunidad = $this->get_comunidades($this->session->userdata('comunidadid'));
        $idperiodoinicio = isset($datos_comunidad->idperiodoinicio) ? $datos_comunidad->idperiodoinicio : 1;
        $datos_periodo = $this->get_datos_periodo_by_id($idperiodoinicio);
        $periodo_seleccionado = $anno . "-" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "-01";
        $periodo_inicio = $datos_periodo->anno . "-" . str_pad($datos_periodo->mes, 2, "0", STR_PAD_LEFT) . "-01";
        $fecha_seleccionada = strtotime($periodo_seleccionado);
        $fecha_inicio = strtotime($periodo_inicio);
        $this->db->trans_complete();
        if ($fecha_seleccionada < $fecha_inicio) {
            return false;
        } else {
            return true;
        }
    }




    public function ruta_turbosmtp()
    {
        $base_path = __DIR__;
        $base_path = str_replace("\\", "/", $base_path);
        $path = $base_path . "/../libraries/TurboApiClient.php";
        return $path;
    }



    public function ruta_sendinblue()
    {
        $base_path = __DIR__;
        $base_path = str_replace("\\", "/", $base_path);
        $path = $base_path . "/../libraries/sendinblue.php";
        return $path;
    }    


    public function envia_mail_turbosmtp($from, $toList, $subject, $content, $type, $alias = "Tu Gasto Común")
    {
        if (ENVIO_MAIL) {
            include_once $this->ruta_turbosmtp();
            //$toList = array('rodrigog.84@gmail.com');
            if (is_array($toList)) {
                //array_push($toList,'rodrigog.84@gmail.com');
                $toList = array_unique($toList);
                foreach ($toList as $destiny) {

                    $email = new Email();
                    $email->setFrom($alias . " <" . $from . ">");
                    $email->setToList($destiny);
                    //$email->setCcList("dd@domain.com,ee@domain.com");
                    //$email->setBccList("ffi@domain.com,rr@domain.com");
                    $email->setSubject($subject);
                    //$email->setContent("content");

                    if ($type == 'html') {
                        $email->setHtmlContent($content);
                    } else {
                        $email->setContent($content);
                    }

                    $email->addCustomHeader('X-FirstHeader', "value");
                    $email->addCustomHeader('X-SecondHeader', "value");
                    $email->addCustomHeader('X-Header-da-rimuovere', 'value');
                    $email->removeCustomHeader('X-Header-da-rimuovere');

                    $turboApiClient = new TurboApiClient(TURBOSMTP_USER, TURBOSMTP_PASS);
                    //var_dump($turboApiClient);
                    // $response = $turboApiClient->sendEmail($email);
                    //var_dump($response);
                    try {
                        $response = $turboApiClient->sendEmail($email);
                    } catch (Exception $e) {
                        echo "";
                    }
                }
            } else {


                $email = new Email();
                $email->setFrom("Tu Gasto Común <" . $from . ">");
                $email->setToList($toList);
                //$email->setCcList("dd@domain.com,ee@domain.com");
                //$email->setBccList("ffi@domain.com,rr@domain.com");
                $email->setSubject($subject);
                //$email->setContent("content");

                if ($type == 'html') {
                    $email->setHtmlContent($content);
                } else {
                    $email->setContent($content);
                }

                $email->addCustomHeader('X-FirstHeader', "value");
                $email->addCustomHeader('X-SecondHeader', "value");
                $email->addCustomHeader('X-Header-da-rimuovere', 'value');
                $email->removeCustomHeader('X-Header-da-rimuovere');

                $turboApiClient = new TurboApiClient(TURBOSMTP_USER, TURBOSMTP_PASS);
                //var_dump($turboApiClient);
                $response = $turboApiClient->sendEmail($email);
                //var_dump($response);
                try {
                    $response = $turboApiClient->sendEmail($email);
                } catch (Exception $e) {
                    echo "";
                }
            }
        }
    }



public function envia_mail($from, $toList, $subject, $content, $type, $alias = "Tu Gasto Común", $attachments = null)
    {

        if (ENVIO_MAIL) {

                // Configure API key authorization: api-key
                $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', 'xkeysib-'.API_KEY_MAIL);

                $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(),$credentials);



                if (is_array($toList)) {

                    $toList = array_unique($toList);
                    foreach ($toList as $destiny) {


                          $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $destiny]],
                             'htmlContent' => $content
                        ]);                     

                         

                        $array_attachments = array();
                        if(!is_null($attachments)){
                            foreach ($attachments as $attachment) {
                                $array_archivo = explode('/',$attachment['archivo']);
                                //$array_fila = array('content' => chunk_split(base64_encode(file_get_contents($attachment))),'name' => $array_archivo[count($array_archivo)-1]);
                                $array_fila = array('content' => chunk_split(base64_encode(file_get_contents($attachment['archivo']))),'name' => $attachment['name']);
                                array_push($array_attachments,$array_fila);
                            }
                                
                        }     

                        if(count($array_attachments) > 0){
                            $sendSmtpEmail['attachment'] = $array_attachments;  
                        }
                        


                        try {
                            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);

                          $data_envio = array(
                                'email' => $destiny,
                                'messageid' => $result['messageId'],
                                'idcomunidad' => $this->session->userdata('comunidadid')
                            );

                            $this->db->insert('gc_log_envio_mail', $data_envio);    


                        } catch (Exception $e) {
                            //echo $e->getMessage(),PHP_EOL;
                        }

                    }
                } else {

                        $sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $toList]],
                             'htmlContent' => $content
                        ]);


                        $array_attachments = array();
                        if(!is_null($attachments)){
                            foreach ($attachments as $attachment) {
                                $array_archivo = explode('/',$attachment);
                                $array_fila = array('content' => chunk_split(base64_encode(file_get_contents($attachment))),'name' => $array_archivo[count($array_archivo)-1]);
                                array_push($array_attachments,$array_fila);
                            }
                                
                        }     

                        if(count($array_attachments) > 0){
                            $sendSmtpEmail['attachment'] = $array_attachments;  
                        }
                        
                        


                    try {
                            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);

                          $data_envio = array(
                                'email' => $destiny,
                                'messageid' => $result['messageId'],
                                'idcomunidad' => $this->session->userdata('comunidadid')
                            );

                            $this->db->insert('gc_log_envio_mail', $data_envio);    

                        } catch (Exception $e) {
                            //echo $e->getMessage(),PHP_EOL;
                        }


                }



        }


    }




public function envia_mail_prueba($from, $toList, $subject, $content, $type, $alias = "Tu Gasto Común", $attachments = null)
    {


                // Configure API key authorization: api-key
                $credentials = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', 'xkeysib-'.API_KEY_MAIL);

                $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(new GuzzleHttp\Client(),$credentials);



                if (is_array($toList)) {

                    $toList = array_unique($toList);
                    foreach ($toList as $destiny) {


                          $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $destiny]],
                             'htmlContent' => $content
                        ]);                     

                        $array_attachments = array();
                        if(!is_null($attachments)){
                            foreach ($attachments as $attachment) {
                                $array_archivo = explode('/',$attachment);
                                $array_fila = array('content' => chunk_split(base64_encode(file_get_contents($attachment))),'name' => $array_archivo[count($array_archivo)-1]);
                                array_push($array_attachments,$array_fila);
                            }
                                
                        }     

                        if(count($array_attachments) > 0){
                            $sendSmtpEmail['attachment'] = $array_attachments;  
                        }
                        


                        try {
                            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);

                          $data_envio = array(
                                'email' => $destiny,
                                'messageid' => $result['messageId'],
                                'idcomunidad' => $this->session->userdata('comunidadid')
                            );

                            $this->db->insert('gc_log_envio_mail', $data_envio);    


                        } catch (Exception $e) {
                            echo $e->getMessage(),PHP_EOL;
                        }

                    }
                } else {

                        $sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $toList]],
                             'htmlContent' => $content
                        ]);


                        $array_attachments = array();
                        if(!is_null($attachments)){
                            foreach ($attachments as $attachment) {
                                $array_archivo = explode('/',$attachment);
                                $array_fila = array('content' => chunk_split(base64_encode(file_get_contents($attachment))),'name' => $array_archivo[count($array_archivo)-1]);
                                array_push($array_attachments,$array_fila);
                            }
                                
                        }     

                        if(count($array_attachments) > 0){
                            $sendSmtpEmail['attachment'] = $array_attachments;  
                        }
                        
                        


                    try {
                            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);

                          $data_envio = array(
                                'email' => $toList,
                                'messageid' => $result['messageId'],
                                'idcomunidad' => $this->session->userdata('comunidadid')
                            );

                            $this->db->insert('gc_log_envio_mail', $data_envio);   





                        } catch (Exception $e) {
                            echo $e->getMessage(),PHP_EOL;
                        }


                }



    }



 /*public function envia_mail($from, $toList, $subject, $content, $type, $alias = "Tu Gasto Común")
    {


        if (ENVIO_MAIL) {


                include_once $this->ruta_sendinblue();

                // Configure API key authorization: api-key
                SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key','xkeysib-'.API_KEY_MAIL);

                $api_instance = new SendinBlue\Client\Api\AccountApi();

               
                $smtp_instance = new SendinBlue\Client\Api\SMTPApi();


                if (is_array($toList)) {
                    //array_push($toList,'rodrigog.84@gmail.com');
                    $toList = array_unique($toList);



                    foreach ($toList as $destiny) {


                        $sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $destiny]],
                             'htmlContent' => $content
                        ]);



                        try {
                            $result = $smtp_instance->sendTransacEmail($sendSmtpEmail);


                            $data_envio = array(
                                'email' => $destiny,
                                'messageid' => $result['messageId'],
                                'idcomunidad' => $this->session->userdata('comunidadid')
                            );

                            $this->db->insert('gc_log_envio_mail', $data_envio);

                        } catch (Exception $e) {
                            echo $e->getMessage(),PHP_EOL;
                        }

                    }
                } else {

                        $sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $toList]],
                             'htmlContent' => $content
                        ]);

                    try {
                        $result = $smtp_instance->sendTransacEmail($sendSmtpEmail);

                        $data_envio = array(
                            'email' => $destiny,
                            'messageid' => $result['messageId'],
                            'idcomunidad' => $this->session->userdata('comunidadid')
                        );

                        $this->db->insert('gc_log_envio_mail', $data_envio);




                    } catch (Exception $e) {
                        echo $e->getMessage(),PHP_EOL;
                    }


                }



        }


    }*/




 public function envia_mail_sb($from, $toList, $subject, $content, $type, $alias = "Tu Gasto Común")
    {


        if (ENVIO_MAIL) {


                include_once $this->ruta_sendinblue();

                // Configure API key authorization: api-key
                SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key','xkeysib-'.API_KEY_MAIL);

                $api_instance = new SendinBlue\Client\Api\AccountApi();

               
                $smtp_instance = new SendinBlue\Client\Api\SMTPApi();


                if (is_array($toList)) {
                    //array_push($toList,'rodrigog.84@gmail.com');
                    $toList = array_unique($toList);
                    foreach ($toList as $destiny) {


                        $sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $destiny]],
                             'htmlContent' => $content
                        ]);

                        try {
                            $result = $smtp_instance->sendTransacEmail($sendSmtpEmail);


                           $data_envio = array(
                                'email' => $destiny,
                                'messageid' => $result['messageId'],
                                'idcomunidad' => $this->session->userdata('comunidadid')
                            );

                            $this->db->insert('gc_log_envio_mail', $data_envio);                            

                        } catch (Exception $e) {
                            echo $e->getMessage(),PHP_EOL;
                        }

                    }
                } else {

                        $sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $toList]],
                             'htmlContent' => $content
                        ]);

                    try {
                        $result = $smtp_instance->sendTransacEmail($sendSmtpEmail);

                        $data_envio = array(
                            'email' => $destiny,
                            'messageid' => $result['messageId'],
                            'idcomunidad' => $this->session->userdata('comunidadid')
                        );

                        $this->db->insert('gc_log_envio_mail', $data_envio);




                    } catch (Exception $e) {
                        echo $e->getMessage(),PHP_EOL;
                    }


                }



        }


    }



    public function ver_comprobante_muestra()
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
        $mpdf->SetTitle('Tu Gasto Común - Detalle Cobros');
        $mpdf->SetHeader('Condominio ' . $datos_comunidad->nombre . ' - ' . $datos_comunidad->comuna . ' - RUT: ' . number_format($datos_comunidad->rut, 0, ".", ".") . '-' . $datos_comunidad->dv);
        $mpdf->SetFooter('Para más información visite: http://www.tugastocomun.cl');
        $content_comprobante =  $this->generar_contenido_comprobante_muestra();
        $content_detalle =  $this->generar_contenido_detalle_muestra();




        $mpdf->WriteHTML($content_comprobante);

        $mpdf->AddPage();
        $mpdf->WriteHTML($content_detalle);
        // SE ALMACENA EL ARCHIVO
        $nombre_archivo = date("Y") . "_" . date("m") . "_" . date("d") . "_" . $datos_propiedad->numero . ".pdf";

        $mpdf->Output($nombre_archivo, "I");
    }


    public function generar_contenido_comprobante_muestra()
    {

        $this->load->model('admin');
        $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

        $logo = $comunidad->logo == '' || is_null($comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/' . $this->session->userdata('comunidadid') . '/' . $comunidad->logo;



        $firma = $comunidad->firma == '' || is_null($comunidad->firma) ? '&nbsp;' : '<img src="uploads/firmas/' . $this->session->userdata('comunidadid') . '/' . $comunidad->firma . '" width="150px"> ';

        $content_detalle = '<html>
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
					<body>
						<p><h4 class="header4"><br>Gastos Comunes Individuales de Octubre de 2017<br><br><img src="' . $logo . '" width="100px"></h4></p>
						<hr>
						<br>
						<div class="recto">
							<h4><b>Nombre Copropietario:</b> Klemens Wessel</h4><br>
							<table width="60%" >
								<thead class="theadClass">
								<tr class="headerRow">
									<th><p>Unidad</p></th>
									<th><p>Nombre</p></th>
									<th><p>Direcci&oacute;n</p></th>
									<th>Prorrateo</th>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td class="tdClassCenter" >Propiedad</td>
										<td class="tdClassCenter" >A01</td>
										<td class="tdClassCenter" >AGUSTIN LARA NO. 69-B</td>
										<td class="tdClassCenter" >0.6061 % </td>
									</tr>
									</tbody>
							</table>
						</div>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="80%"><p>Item</p></th>
						<th width="20%">Valor</th>
						</tr>
						</thead>
						<tbody>
						<tr>
						<td class="tdClass" ><b>Total Gasto Com&uacute;n</b></td>
						<td class="tdClass tdClassNumber" ><b>$ 23.334.562</b></td>
						</tr><tr>
									<td class="tdClass" >Cobro Individual (Prorrateo 0.6061 %) </td>
									<td class="tdClass tdClassNumber">$ 141.430</td>
								  </tr>	<tr>
									<td class="tdClass" >Fondo de Reserva ( 5 % ) </td>
									<td class="tdClass tdClassNumber">$ 7.071</td>
								  </tr>	<tr>
										<td class="tdClass" >Intereses ( 2.12 % ) </td>
										<td class="tdClass tdClassNumber">$ 3.149</td>
									  </tr>
						<tr>
						<td class="tdClass" ><b>Subtotal Cobro del Mes</b></td>
						<td class="tdClass tdClassNumber"><b>$ 151.650</b></td>
						</tr>
						<tr>
						<td class="tdClass">&nbsp;</td>
						<td class="tdClass">&nbsp;</td>
						</tr>
						<tr>
						<td class="tdClass" ><b>Subtotal Otros Cobros</b></td>
						<td class="tdClass tdClassNumber"><b>$ 0</b></td>
						</tr>
						<tr>
						<td class="tdClass">&nbsp;</td>
						<td class="tdClass">&nbsp;</td>
						</tr>

						<tr>
						<td class="tdClass" >Saldo Anterior</td>
						<td class="tdClass tdClassNumber">$ 148.524</td>
						</tr>
						<tr >
						<td class="tdClass " ><b>Total a Pagar del Mes</b></td>
						<td class="tdClass tdClassNumber"><b>$ 300.174</b></td>
						</tr>
						</tbody>
						</table>
						<br><br>
						</div>
						<br>
						<hr>
						<div class="recto">
							<h4><b>Ultimos Cobros</b></h4>
							<table width="100%">
								<tr>
									<td width="40%">
										<table>
											<tr><td><img src="graph/ggcc/1123/graph_32.png" width="40%"></td>/tr>
										</table>
									</td>
									<td align="center" width="60%">
										<table width="50%">
											<thead class="theadClass">
											<tr class="headerRow">
												<th><p>Periodo</p></th>
												<th><p>Monto</p></th>
											</tr>
											</thead>
											<tbody>
																<tr>
																	<td class="tdClassCenter" >Septiembre de 2017</td>
																	<td class="tdClass tdClassNumber" >$ 148.524</td>
																</tr>
																<tr>
																	<td class="tdClassCenter" >Agosto de 2017</td>
																	<td class="tdClass tdClassNumber" >$ 149.701</td>
																</tr>
																<tr>
																	<td class="tdClassCenter" >Julio de 2017</td>
																	<td class="tdClass tdClassNumber" >$ 172.707</td>
																</tr></tbody>
										</table>
									</td>
								</tr>
							</table>


						</div>';

        if ($firma == '&nbsp;') {
            $content_detalle .= '<br><br>
						<br>
						<br>
						<br>';
        }


        $content_detalle .= '<table width="100%" border="0">
							<tr>
								<td width="10%">&nbsp;</td>
								<td width="20%" style="border-bottom:1pt solid black;">' . $firma . '</td>
								<td width="10%">&nbsp;</td>
								<td width="10%" >&nbsp;</td>
								<td width="40%" style="text-align:right"><b>Fecha Emisión de Pago: 20 de Noviembre de 2017</b><br><b>Pagar Hasta: 30 de Noviembre de 2017</b></td>
								<td width="10%" >&nbsp;</td>
							</tr>
							<tr>
								<td width="10%"></td>
								<td width="20%" style="text-align:center">Firma Administrador</td>
								<td width="10%">&nbsp;</td>
								<td width="10%" ></td>
								<td width="40%" style="text-align:right">&nbsp;</td>
								<td width="10%" >&nbsp;</td>
							</tr>
						</table>
		</body>
						</html>';





        return $content_detalle;
    }



    public function generar_contenido_detalle_muestra()
    {

        $this->load->model('admin');
        $comunidad = $this->admin->get_comunidades($this->session->userdata('comunidadid'));

        $obscomunidad = $comunidad->obscomprobante != '' ? $comunidad->obscomprobante : '<CENTER>SIN OBSERVACIONES DE LA ADMINISTRACION</CENTER>';

        $logo = $comunidad->logo == '' || is_null($comunidad->logo) ? 'img/logo4_1_80p_color.png' : 'uploads/logos/' . $this->session->userdata('comunidadid') . '/' . $comunidad->logo;

        $content_detalle = '<html>
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
					<body>
						<p><h4 class="header4"><br>Detalle Gasto Com&uacute;n<br><br><img src="' . $logo . '" width="100px"></h4></p>
						<hr>
						<br>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="40%"><p>Concepto</p></th>
						<th width="40%"><p>Proveedor</p></th>
						<th width="20%">Valor</th>
						</tr>
						</thead>
						<tbody><tr>
							<td class="tdClass"><b>Servicios de Administración</b></td>
							<td>&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 3.202.453</b></td>
						</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Servicio de Administración</td>
													<td class="tdClass">Lorem ipsum dolor sit.</td>
													<td class="tdClass tdClassNumber">$ 1.003.122</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Servicio de Administración</td>
													<td class="tdClass">Neque porro quisquam est</td>
													<td class="tdClass tdClassNumber">$ 2.199.331</td>
												</tr><tr>
							<td class="tdClass"><b>Servicios Básicos</b></td>
							<td>&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 3.992.434</b></td>
						</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Agua</td>
													<td class="tdClass">Li Europan lingues es.</td>
													<td class="tdClass tdClassNumber">$ 65.767</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Agua</td>
													<td class="tdClass">sed quia non numquam</td>
													<td class="tdClass tdClassNumber">$ 2.434.600</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Agua</td>
													<td class="tdClass">voluptate velit esse</td>
													<td class="tdClass tdClassNumber">$ 1.492.067</td>
												</tr><tr>
							<td class="tdClass"><b>Seguridad</b></td>
							<td>&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 10.037.631</b></td>
						</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Control  de Acceso</td>
													<td class="tdClass">vel illum qui dolorem</td>
													<td class="tdClass tdClassNumber">$ 2.056.407</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Control  de Acceso</td>
													<td class="tdClass">Et harum quidem rerum</td>
													<td class="tdClass tdClassNumber">$ 824.267</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Guardias de seguridad</td>
													<td class="tdClass">At vero eos et accusamus</td>
													<td class="tdClass tdClassNumber">$ 175.000</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Guardias de seguridad</td>
													<td class="tdClass">corrupti quos dolores</td>
													<td class="tdClass tdClassNumber">$ 6.981.957</td>
												</tr><tr>
							<td class="tdClass"><b>Reparaciones</b></td>
							<td>&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 199.710</b></td>
						</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Reparaciones Varias</td>
													<td class="tdClass">debitis aut rerum</td>
													<td class="tdClass tdClassNumber">$ 143.150</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Reparaciones Varias</td>
													<td class="tdClass">similique sunt in culpa</td>
													<td class="tdClass tdClassNumber">$ 56.560</td>
												</tr><tr>
							<td class="tdClass"><b>Remuneraciones</b></td>
							<td>&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 5.103.293</b></td>
						</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Anticipo</td>
													<td class="tdClass">anim id est laborum</td>
													<td class="tdClass tdClassNumber">$ 701.098</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Anticipo</td>
													<td class="tdClass">dolor in reprehenderit </td>
													<td class="tdClass tdClassNumber">$ 701.098</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Anticipo</td>
													<td class="tdClass">incididunt ut labore</td>
													<td class="tdClass tdClassNumber">$ 3.700.897</td>
												</tr><tr>
							<td class="tdClass"><b>Otras Cuentas</b></td>
							<td>&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 799.041</b></td>
						</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Otros Cargos</td>
													<td class="tdClass">dolore eu fugiat</td>
													<td class="tdClass tdClassNumber">$ 18.485</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Otros Cargos</td>
													<td class="tdClass">cupidatat non proident</td>
													<td class="tdClass tdClassNumber">$ 10.004</td>
												</tr><tr>
													<td class="tdClass">&nbsp;&nbsp;&nbsp;&nbsp;Otros Cargos</td>
													<td class="tdClass">velit esse cillum</td>
													<td class="tdClass tdClassNumber">$ 770.552</td>
												</tr>
						<tr>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass">&nbsp;</td>
						</tr>
						<tr>
							<td class="tdClass"><b>Total Gasto Com&uacute;n</b></td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 23.334.562</b></td>
						</tr>
						<tr>
							<td class="tdClass"><b>Fondo de Reserva</b></td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 1.166.728</b></td>
						</tr>
						<tr>
							<td class="tdClass"><b>Total</b></td>
							<td class="tdClass">&nbsp;</td>
							<td class="tdClass tdClassNumber"><b>$ 24.501.290</b></td>
						</tr>
						</tbody>
						</table>
						<br><br>
						</div>
						<br>
						<hr>
						<div class="recto">
						<table class="" width="100%"  >
						<thead class="theadClass">
						<tr class="headerRow">
						<th width="100%"><p>Observaciones de la Administraci&oacute;n</p></th>
						</tr>
						</thead>
						<tbody><tr>
							<td class="tdClass">' . $obscomunidad . '</td>
						</tr>
						</tbody>
						</table>



						</div>
		</body>
						</html>';


        return $content_detalle;
    }


    public function suscribir_propiedades($array_propiedades)
    {

        $this->db->trans_start();
        foreach ($array_propiedades as $idpropiedad => $value) {
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->where('id', $idpropiedad);
            $this->db->update('gc_propiedad', array('suscrito' => $value));
        }
        $this->db->trans_complete();
    }

    public function get_comunicados($idcomunicado = null)
    {

        $comunicados_data = $this->db->select("c.id , c.titulo, c.txt_comunicado,
												c.estado as estadoid,
											case when c.estado = 1 then 'Pendiente'
												 when c.estado = 2 then 'Solicitud Envío'
												 when c.estado = 3 then 'Enviado'
												 else 'No Enviado'
												 end as estado,
											date_format(c.fec_marca_envio,'%d/%m/%Y %H:%i:%s') as fec_marca_envio,
											date_format(c.fec_envio,'%d/%m/%Y %H:%i:%s') as fec_envio,
					 ", false)
            ->from('gc_comunicados c')
            ->where('c.idcomunidad', $this->session->userdata('comunidadid'))
            ->where('active', 1)
            ->order_by('id', 'desc');

        $comunicados_data = is_null($idcomunicado) ? $comunicados_data : $comunicados_data->where('id', $idcomunicado);
        $query = $this->db->get();
        $datos = is_null($idcomunicado) ? $query->result() : $query->row();
        return $datos;
    }



    public function get_archivos_comunicados($idcomunicado = null)
    {

        $comunicados_data = $this->db->select("c.id, c.idcomunicado, c.nomarchivo , c.nomtemparchivo
                     ", false)
            ->from('gc_archivos_comunicado c')
            ->where('c.idcomunicado', $idcomunicado)
            ->order_by('id', 'desc');

        $query = $this->db->get();
        $datos = $query->result();
        return $datos;
    }


    public function save_comunicado($datos_comunicado)
    {


        $this->db->trans_start();

        $idcomunicado = 0;

        if ($datos_comunicado['idcomunicado'] == 0) {

            $array_datos = array(
                'idcomunidad' => $this->session->userdata('comunidadid'),
                'titulo' => $datos_comunicado['titulo'],
                'txt_comunicado' => $datos_comunicado['txt_comunicado'],
                'created_at' => date('Y-m-d H:i:s')
            );

            $this->db->insert('gc_comunicados', $array_datos);


            $idcomunicado = $this->db->insert_id();
        } else {

            $array_datos = array(
                'titulo' => $datos_comunicado['titulo'],
                'txt_comunicado' => $datos_comunicado['txt_comunicado']
            );

            $this->db->where('id', $datos_comunicado['idcomunicado']);
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_comunicados', $array_datos);


            $idcomunicado = $datos_comunicado['idcomunicado'];
        }


        $array_archivos = $datos_comunicado['archivos'];
        foreach($array_archivos as $archivo){

            $array_archivos_table = array(
                'idcomunicado' => $idcomunicado,
                'nomarchivo' => $archivo['name'],
                'nomtemparchivo' => $archivo['tmp_name']
            );

            $this->db->insert('gc_archivos_comunicado', $array_archivos_table);


        }




        $this->db->trans_complete();
        return 1;
    }



  public function save_mail_vencimiento($datos_mail)
    {


        $this->db->trans_start();



        if($datos_mail['tipo'] == 1){


            $array_datos = array(
                'txt_mail_antes_vencimiento' => $datos_mail['txt_mail']
            );

        }else if($datos_mail['tipo'] == 2){

            $array_datos = array(
                'txt_mail_despues_vencimiento' => $datos_mail['txt_mail']
            );



        }

        $this->db->where('id', $this->session->userdata('comunidadid'));
        $this->db->update('gc_comunidad', $array_datos);




        $this->db->trans_complete();
        //echo $this->db->last_query(); exit;
        return 1;
    }



    public function send_comunicado($idcomunicado)
    {


        $this->db->trans_start();

        $datos_comunicados = $this->get_comunicados($idcomunicado);
        if ($datos_comunicados->estadoid == 1) {

            $array_datos = array(
                'delay_envio_min' => 10,
                'fec_marca_envio' => date('Y-m-d H:i:s'),
                'estado' => 2
            );

            $this->db->where('id', $idcomunicado);
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_comunicados', $array_datos);
            $this->db->trans_complete();
            return true;
        } else {
            $this->db->trans_complete();
            return false;
        }
    }



    public function anular_comunicado($idcomunicado)
    {


        $this->db->trans_start();

        $datos_comunicados = $this->get_comunicados($idcomunicado);
        if ($datos_comunicados->estadoid == 2) {

            $array_datos = array(
                'delay_envio_min' => 0,
                'fec_marca_envio' => null,
                'estado' => 1
            );

            $this->db->where('id', $idcomunicado);
            $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
            $this->db->update('gc_comunicados', $array_datos);
            $this->db->trans_complete();
            return true;
        } else {
            $this->db->trans_complete();
            return false;
        }
    }

    public function delete_comunicado($idcomunicado)
    {


        $this->db->trans_start();

        $datos_comunicados = $this->get_comunicados($idcomunicado);

        $array_datos = array('active' => 0);

        $this->db->where('id', $idcomunicado);
        $this->db->where('idcomunidad', $this->session->userdata('comunidadid'));
        $this->db->update('gc_comunicados', $array_datos);
        $this->db->trans_complete();
        return true;
    }


    public function get_comunicados_pendientes_envio($idcomunicado = null)
    {

        $comunicados_data = $this->db->select("c.id , c.titulo, c.txt_comunicado, c.idcomunidad,
												c.estado as estadoid,
											case when c.estado = 1 then 'Pendiente'
												 when c.estado = 2 then 'Solicitud Envío'
												 when c.estado = 3 then 'Enviado'
												 else 'No Enviado'
												 end as estado,
											date_format(c.fec_marca_envio,'%d/%m/%Y %H:%i:%s') as fec_marca_envio,
											date_format(c.fec_envio,'%d/%m/%Y %H:%i:%s') as fec_envio,
					 ", false)
            ->from('gc_comunicados c')
            ->where('c.estado', '2')
            ->where('active', 1);
        $comunicados_data = is_null($idcomunicado) ? $comunicados_data : $comunicados_data->where('id', $idcomunicado);
        $query = $this->db->get();
        $datos = is_null($idcomunicado) ? $query->result() : $query->row();
        return $datos;
    }


    public function get_notificaciones_pendientes_envio()
    {

        $notificaciones_data = $this->db->select("p.idperiodo, p.idcomunidad, c.nombre")
            ->from('gc_periodo_estado p')
            ->join('gc_comunidad c', 'p.idcomunidad = c.id')
            ->where('publica is not null')
            ->where('envia is null');
        //$notificaciones_data = is_null($idcomunicado) ? $comunicados_data : $comunicados_data->where('id',$idcomunicado);
        $query = $this->db->get();
        //$datos = is_null($idcomunicado) ? $query->result() : $query->row();
        $datos = $query->result();
        return $datos;
    }


    public function get_comunidades_prox_vencimiento()
    {

        $notificaciones_data = $this->db->select("id, nombre, date_format(fecvencimiento,'%d/%m/%Y') as fecvencimiento", false)
            ->from('gc_comunidad')
            ->where('active', 1)
            ->where('datediff(fecvencimiento,now()) in (10,5,3,2,1,0)');

        $query = $this->db->get();

        $datos = $query->result();
        return $datos;
    }



    public function get_propiedades_prox_vencimiento($idcomunidad)
    {

        $notificaciones_data = $this->db->select("p.id, p.numero, p.responsable, p.saldo_publicado as saldo", false)
            ->from('gc_propiedad as p')
            ->where('p.idcomunidad', $idcomunidad)
            ->where('p.active', 1)
            ->where('p.saldo_publicado > 0');

        $query = $this->db->get();

        $datos = $query->result();
        return $datos;
    }


    public function get_comunidades_pagos_webpay()
    {

        $notificaciones_data = $this->db->select("p.id, p.idcomunidad, c.nombre as comunidad, p.montopago, p.fechapago, p.fecvencimientonuevo, date_format(p.aceptacionpago,'%d/%m/%Y') as aceptacionpago", false)
            ->from('gc_log_pagos p')
            ->join('gc_comunidad c', 'p.idcomunidad = c.id')
            ->where('p.aceptacionpago is not null')
            ->where('p.envia_comprobante', 0);

        $query = $this->db->get();

        $datos = $query->result();
        return $datos;
    }



    public function get_multa_sin_envio($idcuenta = null)
    {

        $notificaciones_data = $this->db->select("d.id
												,p.numero
												,p.idcomunidad
												,c.nombre as comunidad
												,pe.mes
												,pe.anno
												,d.descripcion
												,d.monto
												,date_format(d.fechadeuda,'%d/%m/%Y') fechadeuda", false)
            ->from('gc_deuda_propiedad d')
            ->join('gc_propiedad p', 'd.idpropiedad = p.id')
            ->join('gc_comunidad c', 'p.idcomunidad = c.id')
            ->join('gc_periodo pe', 'd.idperiodo = pe.id')
            ->where('d.idtipodeudadetalle', 7)
            ->where('d.enviacorreo', 0);

        $notificaciones_data = is_null($idcuenta) ? $notificaciones_data : $notificaciones_data->where('d.id', $idcuenta);
        $query = $this->db->get();
        $datos = is_null($idcuenta) ? $query->result() : $query->row();
        //$datos = $query->result();
        return $datos;
    }

    public function generar_mail_comunicado($comunidadid, $comunicado, $propiedad)
    {

        $this->load->model('admin');



        $comunidad = $this->admin->get_comunidades($comunidadid);
        /*print_r($comunidad);echo "<br><br><br>";
						  print_r($propiedad);echo "<br><br><br>";
						  print_r($comunicado); exit;*/

        $this->load->library('email');




        $messageBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
		  <head>
		    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		    <title>A Simple Responsive HTML Email</title>
		  </head>
		  <body yahoo="" bgcolor="#f6f8f1" style="min-width: 100% !important; margin: 0; padding: 0;">&#13;
		<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td>&#13;
		    <!--[if (gte mso 9)|(IE)]>
		      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
		        <tr>
		          <td>
		    <![endif]-->     &#13;
		    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 900px;"><tbody><tr><td bgcolor="#605ca8" align="center" class="header" style="padding: 40px 30px 20px;">&#13;<center>
		          <!--[if (gte mso 9)|(IE)]>
		            <table width="425" align="center" cellpadding="0" cellspacing="0" border="0">
		              <tr>
		                <td>
		          <![endif]--><table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;"><tbody><tr><td height="70">&#13;
		                <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="subhead" style="font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px; padding: 0 0 0 3px;">&#13;
		                      <center>Nuevo Comunicado&#13;</center>
		                    </td>&#13;
		                  </tr><tr><td class="h1" style="color: #153643; font-family: sans-serif; font-size: 33px; line-height: 38px; font-weight: bold; padding: 5px 0 0;">&#13;
		                      <center><img class="fix" src="http://www.tugastocomun.cl/app/img/logo4_1.png" border="0" alt="" style="height: auto;" /></center>&#13;
		                    </td>&#13;
		                  </tr></tbody></table></td>&#13;
		            </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
		                </td>
		              </tr>
		          </table></center>
		          <![endif]--></td>&#13;
		      </tr><tr><td class="innerpadding borderbottom" style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
		          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="h2" style="color: #153643; font-family: sans-serif; font-size: 16px; line-height: 28px; font-weight: bold; padding: 0 0 12px;">&#13;
		                Comunidad ' . $comunidad->nombre . '
		              </td>&#13;
		            </tr><tr><td class="bodycopy" style="color: #153643; font-family: sans-serif; font-size: 13px; line-height: 22px;">&#13;
		                El administrador ha publicado el siguiente comunicado:</b>  &#13;
		              </td>&#13;
		            </tr></tbody></table></td>&#13;
		      </tr><tr><td class="innerpadding borderbottom" style="border-bottom-width: 1px; border-bottom-color: #f2eeed; border-bottom-style: solid; padding: 30px;">&#13;
		          ' . $comunicado->txt_comunicado . '
		      </tr><!--tr>
		        <td class="innerpadding borderbottom">
		          <img class="fix" src="./mail_completo_files/wide.png" width="100%" border="0" alt="">
		        </td>
		      </tr--><tr>&#13;
		      </tr><tr><td class="footer" bgcolor="#44525f" style="padding: 20px 30px 15px;">&#13;
		          <table width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td align="center" class="footercopy" style="font-family: sans-serif; font-size: 14px; color: #ffffff;">&#13;
		                Copyright © 2014-2015 Tu Gasto Común.<br />
		               ';
        if ($propiedad) {
            $messageBody .= '<span class="hide">Si no desea seguir recibiendo correos de Tu Gasto Comun, por favor </span>&#13;
		                <a href="' . base_url() . 'admins/unsubscribe/' . $propiedad->id . '" class="unsubscribe" ><font color="#ffffff">haz click aquí</font></a>                &#13;';
        }
        $messageBody .= '</td>&#13;
		            </tr><tr><td align="center" style="padding: 20px 0 0;">&#13;
		                <table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
		                      <a href="http://www.facebook.com/">&#13;
		                        <img src="http://www.tugastocomun.cl/app/img/facebook.png" width="37" height="37" alt="Facebook" border="0" style="height: auto;" /></a>&#13;
		                    </td>&#13;
		                    <td width="37" style="text-align: center; padding: 0 10px;" align="center">&#13;
		                      <a href="http://www.twitter.com/">&#13;
		                        <img src="http://www.tugastocomun.cl/app/img/twitter.png" width="37" height="37" alt="Twitter" border="0" style="height: auto;" /></a>&#13;
		                    </td>&#13;
		                  </tr></tbody></table></td>&#13;
		            </tr></tbody></table></td>&#13;
		      </tr></tbody></table><!--[if (gte mso 9)|(IE)]>
		          </td>
		        </tr>
		    </table>
		    <![endif]--></td>&#13;
		  </tr></tbody></table><!--analytics--><script type="text/javascript" async="" src="http://www.tugastocomun.cl/app/js/ga.js"></script><script src="http://www.tugastocomun.cl/app/js/jquery-1.10.1.min.js"></script><script src="http://www.tugastocomun.cl/app/js/ga-tracking.min.js"></script></body>
		</html>';

        $array_email = array();
        if (!$propiedad) {
            $lista_email = $this->admin->get_comunidad_adm_email_by_id($comunidadid);
        } else {
            $lista_email = $this->admin->get_propiedad_email_by_id($propiedad->id);
            $array_email = array($propiedad->mail);
        }


        $files = $this->admin->get_archivos_comunicados($comunicado->id);       
        $attachments = array();        
        foreach($files as $file){
                $archivo = './uploads/comunicados/' . $comunidadid .'/'.$file->nomtemparchivo;
                $array_archivo = array('archivo' => $archivo,
                                       'name' => $file->nomarchivo);
                array_push($attachments,$array_archivo);

        }


       // echo '<pre>';
       // var_dump($attachments); exit;
        //$array_email = array('rodrigog.84@gmail.com');


        foreach ($lista_email as $lista) {
            if (!in_array($lista->email, $array_email) && $lista->email != '') {
                array_push($array_email, $lista->email);
            }
        }


        //$array_email = array('rodrigog.84@gmail.com');
        //echo '<pre>';
        //var_dump($array_email); exit;

        // echo $messageBody; exit;
        $this->admin->envia_mail('robot@tugastocomun.cl', $array_email, $comunidad->nombre . " - " . $comunicado->titulo, $messageBody, 'html', 'Comunicado Condominio',$attachments);
    }

    public function enviar_comunicados_pendientes()
    {


        $this->db->trans_start();

        $datos_comunicados = $this->get_comunicados_pendientes_envio();
        //print_r($datos_comunicados); exit;
        foreach ($datos_comunicados as $comunicado) {

            $idcomunidad = $comunicado->idcomunidad;
            $propiedades = $this->get_propiedades($idcomunidad);
            //echo '<pre>';
            //var_dump($propiedades); exit;
            foreach ($propiedades as $propiedad) {
                if ($propiedad->suscrito == 1) {
                    $this->generar_mail_comunicado($idcomunidad, $comunicado, $propiedad);

                }
            }


            $this->generar_mail_comunicado($idcomunidad, $comunicado, false);

            $array_datos = array(
                'fec_envio' => date('Y-m-d H:i:s'),
                'estado' => 3
            );

            $this->db->where('id', $comunicado->id);
            $this->db->update('gc_comunicados', $array_datos);
        }

        $this->db->trans_complete();
    }



    public function get_propiedades_comunidad_guest($idcomunidad)
    {

        $query = $this->db->query("SELECT p.id, p.numero, p.responsable, p.mail, p.suscrito, p.prorrateo, p.prorrateo_propiedad, p.saldo FROM
							gc_propiedad as p
							WHERE p.idcomunidad = " . $this->session->userdata('comunidadid') . " AND
		                    active = 1 ORDER BY LPAD(lower(p.numero), 10,0) asc");
        //$query = $this->db->get();
        return $query->result();
    }




    public function get_pagos_webpay($idpago)
    {

        $notificaciones_data = $this->db->select("p.id, p.idcomunidad, c.nombre as comunidad, p.montopago, p.fechapago, date_format(p.fecvencimientonuevo,'%d/%m/%Y') as fecvencimientonuevo, date_format(p.aceptacionpago,'%d/%m/%Y') as aceptacionpago", false)
            ->from('gc_log_pagos p')
            ->join('gc_comunidad c', 'p.idcomunidad = c.id')
            ->where('p.id', $idpago);

        $query = $this->db->get();

        $datos = $query->row();
        return $datos;
    }



    public function get_pagos_webpay_by_tokentgc($tokentgc)
    {

        $notificaciones_data = $this->db->select("p.id, p.idcomunidad, c.nombre as comunidad, p.montopago, p.fechapago, date_format(p.fecvencimientonuevo,'%d/%m/%Y') as fecvencimientonuevo, date_format(p.aceptacionpago,'%d/%m/%Y') as aceptacionpago, tokentranskbank", false)
            ->from('gc_log_pagos p')
            ->join('gc_comunidad c', 'p.idcomunidad = c.id')
            ->where('p.tokentgc', $tokentgc);

        $query = $this->db->get();

        $datos = $query->row();
        return $datos;
    }




    public function get_pagos_webpayprop_by_tokentgc($tokentgc)
    {

        $notificaciones_data = $this->db->select("p.id, p.idcomunidad, c.nombre as comunidad, p.idpropiedad, pr.numero as numpropiedad, p.montopago, p.fechapago, date_format(p.aceptacionpago,'%d/%m/%Y') as aceptacionpago, tokentranskbank", false)
            ->from('gc_log_pagos_prop p')
            ->join('gc_comunidad c', 'p.idcomunidad = c.id')
            ->join('gc_propiedad pr', 'p.idpropiedad = pr.id')
            ->where('p.tokentgc', $tokentgc);

        $query = $this->db->get();

        $datos = $query->row();
        return $datos;
    }



    public function enviar_notificaciones_pendientes()
    {


        $this->db->trans_start();
        $this->load->model('payment');

        $datos_notificaciones = $this->get_notificaciones_pendientes_envio();

        foreach ($datos_notificaciones as $notificacion) {

            $idcomunidad = $notificacion->idcomunidad;
            $idperiodo = $notificacion->idperiodo;
            $nombre = $notificacion->nombre;
            $this->session->set_userdata('comunidadid', $idcomunidad);
            $this->session->set_userdata('comunidadnombre', $nombre);



            $propiedades = $this->admin->get_propiedades_comunidad();

            foreach ($propiedades as $propiedad) { // ENVIO DE MAIL
                $this->payment->generar_contenido_comprobante($idcomunidad, $idperiodo, $propiedad->id);
                if ($propiedad->suscrito == 1) {
                    //$propiedades = $this->payment->generar_mail($this->session->userdata('comunidadid'),$idperiodo,$propiedad);
                    $this->payment->generar_mail($idcomunidad, $idperiodo, $propiedad);
                }
            }

            $this->payment->generar_mail_adm_ggcc($idcomunidad, $idperiodo);

            $array_datos = array('envia' => date('Y-m-d H:i:s'));

            $this->db->where('idperiodo', $idperiodo);
            $this->db->where('idcomunidad', $idcomunidad);
            $this->db->update('gc_periodo_estado', $array_datos);
        }

        $this->db->trans_complete();
    }


    public function enviar_aviso_vencimiento()
    {

        //ENVIAR SÓLO UNA VEZ AL DIA
        $this->db->trans_start();
        $this->load->model('payment');

        $datos_vencimiento = $this->get_comunidades_prox_vencimiento();


        foreach ($datos_vencimiento as $notificacion) {

            $idcomunidad = $notificacion->id;
            $nombre = $notificacion->nombre;
            $this->session->set_userdata('comunidadid', $idcomunidad);
            $this->session->set_userdata('comunidadnombre', $nombre);

            $this->payment->generar_mail_aviso_vencimiento($idcomunidad);
        }

        $this->db->trans_complete();
    }

    public function enviar_aviso_vencimiento_ggcc()
    {
        //ENVIAR SÓLO UNA VEZ AL DIA
        //echo "<pre>";
        $this->db->trans_start();
        $this->load->model('payment');

        $com_prox_vencer = $this->get_comunidad_prox_vencer();


        foreach ($com_prox_vencer as $comunidad) {
            $datos_propiedades = $this->get_propiedades_prox_vencimiento($comunidad->idcomunidad);
            //echo '<pre>';
            //print_r($comunidad);
            //print_r($datos_propiedades); exit;
            foreach ($datos_propiedades as $propiedad) {
                $this->payment->generar_mail_vencimiento_propiedad($comunidad, $propiedad);
                exit;
                //print_r($propiedad);
            }
        }



        $com_prox_vencida = $this->get_comunidad_prox_vencer('despues');
        foreach ($com_prox_vencida as $comunidad) {
            $datos_propiedades = $this->get_propiedades_prox_vencimiento($comunidad->idcomunidad);
            //echo '<pre>';
            //print_r($comunidad);
            //print_r($datos_propiedades); exit;
            foreach ($datos_propiedades as $propiedad) {
                $this->payment->generar_mail_vencimiento_propiedad($comunidad, $propiedad,'despues');
                exit;
                //print_r($propiedad);
            }
        }




        $this->db->trans_complete();
    }

    public function get_comunidad_prox_vencer($tipo = 'antes')
    {
  
        if($tipo == 'antes'){

            $ggcc_data = $this->db->select("distinct p.idcomunidad,  date_format(p.fecha_vencimiento,'%d/%m/%Y') fecha_vencimiento, r.mes, r.anno ", false)
                ->from('gc_periodo_estado p')
                ->join('gc_periodo r','p.idperiodo = r.id')
                ->join('gc_comunidad g','p.idcomunidad = g.id AND g.active = 1 AND g.mail_morosidad_antes_vencimiento != -1')
                ->where('p.fecha_vencimiento > CURDATE()')
                ->where('datediff(p.fecha_vencimiento,CURDATE()) = g.mail_morosidad_antes_vencimiento')
                ->where('p.publica IS NOT null');

        }else if($tipo == 'despues'){
     
            $ggcc_data = $this->db->select("distinct p.idcomunidad,  date_format(p.fecha_vencimiento,'%d/%m/%Y') fecha_vencimiento, r.mes, r.anno ", false)
                ->from('gc_periodo_estado p')
                ->join('gc_periodo r','p.idperiodo = r.id')
                ->join('gc_comunidad g','p.idcomunidad = g.id AND g.active = 1 AND g.mail_morosidad_despues_vencimiento != -1')
                ->where('p.fecha_vencimiento < CURDATE()')
                ->where('datediff(CURDATE(),p.fecha_vencimiento) = g.mail_morosidad_despues_vencimiento')
                ->where('p.publica IS NOT null');

              
        }
   



        $query = $this->db->get();

        //var_dump($query->result()); exit;
        return $query->result();
    }

    public function enviar_aviso_pago_webpay()
    {

        //ENVIAR SÓLO UNA VEZ AL DIA
        $this->db->trans_start();
        $this->load->model('payment');

        $datos_pagos = $this->get_comunidades_pagos_webpay();
        foreach ($datos_pagos as $notificacion) {

            $idpago = $notificacion->id;
            $idcomunidad = $notificacion->idcomunidad;
            $nombre = $notificacion->comunidad;
            $this->session->set_userdata('comunidadid', $idcomunidad);
            $this->session->set_userdata('comunidadnombre', $nombre);

            $this->payment->generar_mail_aviso_pago($idpago);
        }

        $this->db->trans_complete();
    }

    public function enviar_comprobante_multa()
    {

        //ENVIAR SÓLO UNA VEZ AL DIA
        $this->db->trans_start();
        //$this->load->model('payment');

        $datos_multas = $this->get_multa_sin_envio();
        //echo "<pre>";
        //print_r($datos_multas); exit;
        foreach ($datos_multas as $multa) {

            $idmulta = $multa->id;
            $idcomunidad = $multa->idcomunidad;
            $nombre = $multa->comunidad;
            $this->session->set_userdata('comunidadid', $idcomunidad);
            $this->session->set_userdata('comunidadnombre', $nombre);

            $this->payment->generar_mail_aviso_pago($idpago);
        }

        $this->db->trans_complete();
    }

    public function edit_accion_mora($array_datos)
    {
        $this->db->trans_start();


        $this->db->where('id', $this->session->userdata('comunidadid'));
        $this->db->update('gc_comunidad', $array_datos);

        $this->db->trans_complete();
        return 1;
    }

    public function get_comite($idcomunidad)
    {
        $this->db->select('c.id, c.first_name, c.last_name, c.email, c.active, cc.cargo')
            ->from('gc_comite c')
            ->join('gc_cargo_comite cc', 'c.idcargo = cc.id')
            ->where('c.idcomunidad', $idcomunidad)
            ->order_by('c.active', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_cargo_comite()
    {
        $this->db->select('c.id, c.cargo,')
            ->from('gc_cargo_comite c');

        $query = $this->db->get();
        return $query->result();
    }

    public function save_miembro($datos_miembro)
    {
        $this->db->trans_start();
        $array_datos = array(
            'idcomunidad' => $datos_miembro['idcomunidad'],
            'first_name' => $datos_miembro['nombre'],
            'last_name' => $datos_miembro['apellido'],
            'email' => $datos_miembro['email'],
            'idcargo' => $datos_miembro['idcargo'],
        );

        if (!is_null($datos_miembro['iduser'])) {
            $array_datos['iduser'] = $datos_miembro['iduser'];
        }

        if ($datos_miembro['idmiembro'] == 0) {
            $array_datos['created_at'] = date('Y-m-d H:i:s');

            $this->db->insert('gc_comite', $array_datos);
        } else {
            $array_datos['updated_at'] = date('Y-m-d H:i:s');

            $this->db->where('id', $datos_miembro['idmiembro']);
            $this->db->where('idcomunidad', $datos_miembro['idcomunidad']);
            $this->db->update('gc_comite', $array_datos);
        }

        $this->db->trans_complete();
        return 1;
    }

    public function get_miembro_by_id($idmiembro)
    {
        $this->db->select('c.idcomunidad, c.iduser, c.first_name, c.last_name, c.email, cc.id, cc.cargo')
            ->from('gc_comite c')
            ->join('gc_cargo_comite cc', 'c.idcargo = cc.id')
            ->where('c.id', $idmiembro);

        $query = $this->db->get();
        return $query->row();
    }

    public function delete_miembro($idmiembro)
    {
        $this->db->where('id', $idmiembro);
        $this->db->update('gc_comite', array('active' => 0));

        if ($this->db->affected_rows() > 0) { // se eliminó periodo correctamente
            return 1;
        } else { // no hubo eliminación de periodo
            return -1;
        }
    }

    public function valida_existe_usuario_comite($email, $idmiembro)
    {
        $this->db->select('c.id')
            ->from('gc_comite c')
            ->where('c.email', $email)
            ->where('c.id !=', $idmiembro);
        $query = $this->db->get();

        return $query->row() ? true : false;
    }

    public function get_documentos($idcomunidad, $active = 1)
    {
        $data = $this->db->select("d.id, d.descripcion, d.path, date_format(d.updated_at,'%d/%m/%Y %H:%i:%s') as updated_at, date_format(d.archived_at,'%d/%m/%Y %H:%i:%s') as archived_at, date_format(d.created_at,'%d/%m/%Y %H:%i:%s') as created_at, td.tipo")
            ->from('gc_documento d')
            ->join('gc_tipo_documento td', 'd.idtipodocumento = td.id')
            ->where('d.active', $active)
            ->where('d.idcomunidad', $idcomunidad);
        $data = $active === 1 ? $data->order_by('d.created_at', 'DESC') : $data->order_by('d.archived_at', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_tipo_documento()
    {
        $this->db->select('id, tipo')
            ->from('gc_tipo_documento');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_documento_by_id($iddocumento)
    {
        $this->db->select('d.idcomunidad, d.descripcion, d.path, d.idtipodocumento, td.tipo')
            ->from('gc_documento d')
            ->join('gc_tipo_documento td', 'd.idtipodocumento = td.id')
            ->where('d.active', 1)
            ->where('d.id', $iddocumento);

        $query = $this->db->get();
        return $query->row();
    }

    public function save_documento($datos_documento)
    {
        $this->db->trans_start();

        $this->db->select('d.id')
            ->from('gc_documento as d')
            ->where('d.active', 1)
            ->where('d.id', $datos_documento['iddocumento']);
        $query = $this->db->get();
        $documento = $query->row();

        $array_datos = array(
            'descripcion' => $datos_documento['descripcion'],
            'idcomunidad' => $datos_documento['idcomunidad'],
            'idtipodocumento' => $datos_documento['idtipo'],
        );

        if (isset($datos_documento['path'])) {
            $array_datos['path'] = $datos_documento['path'];
        }

        if ($datos_documento['iddocumento'] === '0') {
            $array_datos['created_at'] = date('Y-m-d H:i:s');

            $result = $this->db->insert('gc_documento', $array_datos);
        } else if (!is_null($documento)) {
            $array_datos['updated_at'] = date('Y-m-d H:i:s');

            $this->db->where('id', $datos_documento['iddocumento']);
            $this->db->where('idcomunidad', $datos_documento['idcomunidad']);
            $result = $this->db->update('gc_documento', $array_datos);
        }

        $this->db->trans_complete();

        return isset($result) ? $result : false;
    }

    public function delete_documento($iddocumento)
    {
        $array_datos = array(
            'active' => 0,
            'archived_at' => date('Y-m-d H:i:s'),
        );

        $this->db->where('id', $iddocumento);
        $this->db->update('gc_documento', $array_datos);

        if ($this->db->affected_rows() > 0) { // se eliminó periodo correctamente
            return 1;
        } else { // no hubo eliminación de periodo
            return -1;
        }
    }

    public function get_asambleas($idcomunidad, $active = 1)
    {
        $data = $this->db->select("a.id, a.asunto, a.fecha, a.path, date_format(a.updated_at,'%d/%m/%Y %H:%i:%s') as updated_at, date_format(a.archived_at,'%d/%m/%Y %H:%i:%s') as archived_at, date_format(a.created_at,'%d/%m/%Y %H:%i:%s') as created_at, ta.tipo")
            ->from('gc_asamblea a')
            ->join('gc_tipo_asamblea ta', 'a.idtipoasamblea = ta.id')
            ->where('a.active', $active)
            ->where('a.idcomunidad', $idcomunidad);
        $data = $active === 1 ? $data->order_by('a.created_at', 'DESC') : $data->order_by('a.archived_at', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_tipo_asamblea()
    {
        $this->db->select('id, tipo')
            ->from('gc_tipo_asamblea');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_asamblea_by_id($idasamblea)
    {
        $this->db->select('a.idcomunidad, a.asunto, a.fecha, a.path, a.idtipoasamblea, ta.tipo')
            ->from('gc_asamblea a')
            ->join('gc_tipo_asamblea ta', 'a.idtipoasamblea = ta.id')
            ->where('a.active', 1)
            ->where('a.id', $idasamblea);

        $query = $this->db->get();
        return $query->row();
    }

    public function save_asamblea($datos_asamblea)
    {
        $this->db->trans_start();

        $this->db->select('a.id')
            ->from('gc_asamblea as a')
            ->where('a.active', 1)
            ->where('a.id', $datos_asamblea['idasamblea']);
        $query = $this->db->get();
        $asamblea = $query->row();

        $array_datos = array(
            'asunto' => $datos_asamblea['asunto'],
            'fecha' => $datos_asamblea['fecha'],
            'idcomunidad' => $datos_asamblea['idcomunidad'],
            'idtipoasamblea' => $datos_asamblea['idtipo']
        );

        if (isset($datos_asamblea['path'])) {
            $array_datos['path'] = $datos_asamblea['path'];
        }

        if ($datos_asamblea['idasamblea'] == 0) {
            $array_datos['created_at'] = date('Y-m-d H:i:s');

            $result = $this->db->insert('gc_asamblea', $array_datos);
        } else if (!is_null($asamblea)) {
            $array_datos['updated_at'] = date('Y-m-d H:i:s');

            $this->db->where('id', $datos_asamblea['idasamblea']);
            $this->db->where('idcomunidad', $datos_asamblea['idcomunidad']);
            $result = $this->db->update('gc_asamblea', $array_datos);
        }

        $this->db->trans_complete();

        return isset($result) ? $result : false;
    }

    public function delete_asamblea($idasamblea)
    {
        $array_datos = array(
            'active' => 0,
            'archived_at' => date('Y-m-d H:i:s'),
        );

        $this->db->where('id', $idasamblea);
        $this->db->update('gc_asamblea', $array_datos);

        if ($this->db->affected_rows() > 0) { // se eliminó periodo correctamente
            return 1;
        } else { // no hubo eliminación de periodo
            return -1;
        }
    }
}
