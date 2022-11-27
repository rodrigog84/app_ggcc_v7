<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');


if (!function_exists('month2string'))
{

  function month2string($month)
  {
    $text = '';

      switch ($month)
      {
        case 1:
          $text = 'Enero';
          break;
        case 2:
          $text = 'Febrero';
          break;
        case 3:
          $text = 'Marzo';
          break;
        case 4:
          $text = 'Abril';
          break;
        case 5:
          $text = 'Mayo';
          break;
        case 6:
          $text = 'Junio';
          break;
        case 7:
          $text = 'Julio';
          break;
        case 8:
          $text = 'Agosto';
          break;
        case 9:
          $text = 'Septiembre';
          break;
        case 10:
          $text = 'Octubre';
          break;
        case 11:
          $text = 'Noviembre';
          break;
        case 12:
          $text = 'Diciembre';
          break;                                                                      
        default:
          $text = '';
          break;
      }

    return $text;
  }

}


if (!function_exists('randomstring'))
{

  function randomstring($large)
  {
    $pattern1 = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $key = "";
    for($i=0;$i<$large;$i++){
      $key .= $pattern1[rand(0,35)];
    }
    return $key;
  }

}

if (!function_exists('randomstring_mm'))
{

  function randomstring_mm($large)
  {
    $pattern1 = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $key = "";
    for($i=0;$i<$large;$i++){
      $key .= $pattern1[rand(0,61)];
    }
    return $key;
  }

}



if (!function_exists('trackid'))
{

  function trackid($valor)
  {
    return str_pad($valor, 5, "0", STR_PAD_LEFT); ;
  }

}



if (!function_exists('format_rut'))
{

  function format_rut($rut)
  {

    return $rut == '' ? '' : number_format( substr ($rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen( $rut) -1 , 1 );
  }

}



if (!function_exists('date2string'))
{

  function date2string($month,$year)
  {

    $text = '';

      switch ($month)
      {
        case 1:
          $text = 'Enero';
          break;
        case 2:
          $text = 'Febrero';
          break;
        case 3:
          $text = 'Marzo';
          break;
        case 4:
          $text = 'Abril';
          break;
        case 5:
          $text = 'Mayo';
          break;
        case 6:
          $text = 'Junio';
          break;
        case 7:
          $text = 'Julio';
          break;
        case 8:
          $text = 'Agosto';
          break;
        case 9:
          $text = 'Septiembre';
          break;
        case 10:
          $text = 'Octubre';
          break;
        case 11:
          $text = 'Noviembre';
          break;
        case 12:
          $text = 'Diciembre';
          break;                                                                      
        default:
          $text = '';
          break;
      }

      return $text." de ".$year;
    }
    

}


  if (!function_exists('ordenLetrasExcel'))
  {

    function ordenLetrasExcel($valor)
    {

      $orden_letras[0] = "A";
      $orden_letras[1] = "B";
      $orden_letras[2] = "C";
      $orden_letras[3] = "D";
      $orden_letras[4] = "E";
      $orden_letras[5] = "F";
      $orden_letras[6] = "G";
      $orden_letras[7] = "H";
      $orden_letras[8] = "I";
      $orden_letras[9] = "J";
      $orden_letras[10] = "K";
      $orden_letras[11] = "L";
      $orden_letras[12] = "M";
      $orden_letras[13] = "N";
      $orden_letras[14] = "O";
      $orden_letras[15] = "P";
      $orden_letras[16] = "Q";
      $orden_letras[17] = "R";
      $orden_letras[18] = "S";
      $orden_letras[19] = "T";
      $orden_letras[20] = "U";
      $orden_letras[21] = "V";
      $orden_letras[22] = "W";
      $orden_letras[23] = "X";
      $orden_letras[24] = "Y";
      $orden_letras[25] = "Z";
      $orden_letras[26] = "AA";
      $orden_letras[27] = "AB";
      $orden_letras[28] = "AC";
      $orden_letras[29] = "AD";
      $orden_letras[30] = "AE";
      $orden_letras[31] = "AF";
      $orden_letras[32] = "AG";
      $orden_letras[33] = "AH";
      $orden_letras[34] = "AI";
      $orden_letras[35] = "AJ";
      $orden_letras[36] = "AK";
      $orden_letras[37] = "AL";
      $orden_letras[38] = "AM";
      $orden_letras[39] = "AN";
      $orden_letras[40] = "AO";
      $orden_letras[41] = "AP";
      $orden_letras[42] = "AQ";
      $orden_letras[43] = "AR";
      $orden_letras[44] = "AS";
      $orden_letras[45] = "AT";
      $orden_letras[46] = "AU";
      $orden_letras[47] = "AV";
      $orden_letras[48] = "AW";
      $orden_letras[49] = "AX";
      $orden_letras[50] = "AY";
      $orden_letras[50] = "AZ";
      $orden_letras[51] = "BA";
      $orden_letras[52] = "BB";
      $orden_letras[53] = "BC";
      $orden_letras[54] = "BD";
      $orden_letras[55] = "BE";
      $orden_letras[56] = "BF";
      $orden_letras[57] = "BG";
      $orden_letras[58] = "BH";
      $orden_letras[59] = "BI";
      $orden_letras[60] = "BJ";
      $orden_letras[61] = "BK";
      $orden_letras[62] = "BL";
      $orden_letras[63] = "BM";
      $orden_letras[64] = "BN";
      $orden_letras[65] = "BO";
      $orden_letras[66] = "BP";
      $orden_letras[67] = "BQ";
      $orden_letras[68] = "BR";
      $orden_letras[69] = "BS";
      $orden_letras[70] = "BT";
      $orden_letras[71] = "BU";
      $orden_letras[72] = "BV";
      $orden_letras[73] = "BW";
      $orden_letras[74] = "BX";
      $orden_letras[75] = "BY";
      $orden_letras[76] = "BZ";
      $orden_letras[77] = "CA";
      $orden_letras[78] = "CB";
      $orden_letras[79] = "CC";
      $orden_letras[80] = "CD";
      $orden_letras[81] = "CE";
      $orden_letras[82] = "CF";
      $orden_letras[83] = "CG";
      $orden_letras[84] = "CH";
      $orden_letras[85] = "CI";
      $orden_letras[86] = "CJ";
      $orden_letras[87] = "CK";
      $orden_letras[88] = "CL";
      $orden_letras[89] = "CM";
      $orden_letras[90] = "CN";
      $orden_letras[91] = "CO";
      $orden_letras[92] = "CP";
      $orden_letras[93] = "CQ";
      $orden_letras[94] = "CR";
      $orden_letras[95] = "CS";
      $orden_letras[96] = "CT";
      $orden_letras[97] = "CU";
      $orden_letras[98] = "CV";
      








      return $orden_letras[$valor];
    }

  }


if (!function_exists('valorEnLetras'))
{


  function valorEnLetras($x) 
  { 
  if ($x<0) { $signo = "menos ";} 
  else      { $signo = "";} 
  $x = abs ($x); 
  $C1 = $x; 

  $G6 = floor($x/(1000000));  // 7 y mas 

  $E7 = floor($x/(100000)); 
  $G7 = $E7-$G6*10;   // 6 

  $E8 = floor($x/1000); 
  $G8 = $E8-$E7*100;   // 5 y 4 

  $E9 = floor($x/100); 
  $G9 = $E9-$E8*10;  //  3 

  $E10 = floor($x); 
  $G10 = $E10-$E9*100;  // 2 y 1 


  $G11 = round(($x-$E10)*100,0);  // Decimales 
  ////////////////////// 

  $H6 = unidades($G6); 

  if($G7==1 AND $G8==0) { $H7 = "Cien "; } 
  else {    $H7 = decenas($G7); } 

  $H8 = unidades($G8); 

  if($G9==1 AND $G10==0) { $H9 = "Cien "; } 
  else {    $H9 = decenas($G9); } 

  $H10 = unidades($G10); 

  if($G11 < 10) { $H11 = "0".$G11; } 
  else { $H11 = $G11; } 

  ///////////////////////////// 
      if($G6==0) { $I6=" "; } 
  elseif($G6==1) { $I6="Millón "; } 
           else { $I6="Millones "; } 
            
  if ($G8==0 AND $G7==0) { $I8=" "; } 
           else { $I8="Mil "; } 
            
  $I10 = "Pesos "; 
  $I11 = "/100 M.N. "; 

  //$C3 = $signo.$H6.$I6.$H7.$I7.$H8.$I8.$H9.$I9.$H10.$I10.$H11.$I11; 
  $C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10.$I10; 

  return $C3; //Retornar el resultado 

  } 

  function unidades($u) 
  { 
      if ($u==0)  {$ru = " ";} 
  elseif ($u==1)  {$ru = "Un ";} 
  elseif ($u==2)  {$ru = "Dos ";} 
  elseif ($u==3)  {$ru = "Tres ";} 
  elseif ($u==4)  {$ru = "Cuatro ";} 
  elseif ($u==5)  {$ru = "Cinco ";} 
  elseif ($u==6)  {$ru = "Seis ";} 
  elseif ($u==7)  {$ru = "Siete ";} 
  elseif ($u==8)  {$ru = "Ocho ";} 
  elseif ($u==9)  {$ru = "Nueve ";} 
  elseif ($u==10) {$ru = "Diez ";} 

  elseif ($u==11) {$ru = "Once ";} 
  elseif ($u==12) {$ru = "Doce ";} 
  elseif ($u==13) {$ru = "Trece ";} 
  elseif ($u==14) {$ru = "Catorce ";} 
  elseif ($u==15) {$ru = "Quince ";} 
  elseif ($u==16) {$ru = "Dieciseis ";} 
  elseif ($u==17) {$ru = "Decisiete ";} 
  elseif ($u==18) {$ru = "Dieciocho ";} 
  elseif ($u==19) {$ru = "Diecinueve ";} 
  elseif ($u==20) {$ru = "Veinte ";} 

  elseif ($u==21) {$ru = "Veintiun ";} 
  elseif ($u==22) {$ru = "Veintidos ";} 
  elseif ($u==23) {$ru = "Veintitres ";} 
  elseif ($u==24) {$ru = "Veinticuatro ";} 
  elseif ($u==25) {$ru = "Veinticinco ";} 
  elseif ($u==26) {$ru = "Veintiseis ";} 
  elseif ($u==27) {$ru = "Veintisiente ";} 
  elseif ($u==28) {$ru = "Veintiocho ";} 
  elseif ($u==29) {$ru = "Veintinueve ";} 
  elseif ($u==30) {$ru = "Treinta ";} 

  elseif ($u==31) {$ru = "Treinta y un ";} 
  elseif ($u==32) {$ru = "Treinta y dos ";} 
  elseif ($u==33) {$ru = "Treinta y tres ";} 
  elseif ($u==34) {$ru = "Treinta y cuatro ";} 
  elseif ($u==35) {$ru = "Treinta y cinco ";} 
  elseif ($u==36) {$ru = "Treinta y seis ";} 
  elseif ($u==37) {$ru = "Treinta y siete ";} 
  elseif ($u==38) {$ru = "Treinta y ocho ";} 
  elseif ($u==39) {$ru = "Treinta y nueve ";} 
  elseif ($u==40) {$ru = "Cuarenta ";} 

  elseif ($u==41) {$ru = "Cuarenta y un ";} 
  elseif ($u==42) {$ru = "Cuarenta y dos ";} 
  elseif ($u==43) {$ru = "Cuarenta y tres ";} 
  elseif ($u==44) {$ru = "Cuarenta y cuatro ";} 
  elseif ($u==45) {$ru = "Cuarenta y cinco ";} 
  elseif ($u==46) {$ru = "Cuarenta y seis ";} 
  elseif ($u==47) {$ru = "Cuarenta y siete ";} 
  elseif ($u==48) {$ru = "Cuarenta y ocho ";} 
  elseif ($u==49) {$ru = "Cuarenta y nueve ";} 
  elseif ($u==50) {$ru = "Cincuenta ";} 

  elseif ($u==51) {$ru = "Cincuenta y un ";} 
  elseif ($u==52) {$ru = "Cincuenta y dos ";} 
  elseif ($u==53) {$ru = "Cincuenta y tres ";} 
  elseif ($u==54) {$ru = "Cincuenta y cuatro ";} 
  elseif ($u==55) {$ru = "Cincuenta y cinco ";} 
  elseif ($u==56) {$ru = "Cincuenta y seis ";} 
  elseif ($u==57) {$ru = "Cincuenta y siete ";} 
  elseif ($u==58) {$ru = "Cincuenta y ocho ";} 
  elseif ($u==59) {$ru = "Cincuenta y nueve ";} 
  elseif ($u==60) {$ru = "Sesenta ";} 

  elseif ($u==61) {$ru = "Sesenta y un ";} 
  elseif ($u==62) {$ru = "Sesenta y dos ";} 
  elseif ($u==63) {$ru = "Sesenta y tres ";} 
  elseif ($u==64) {$ru = "Sesenta y cuatro ";} 
  elseif ($u==65) {$ru = "Sesenta y cinco ";} 
  elseif ($u==66) {$ru = "Sesenta y seis ";} 
  elseif ($u==67) {$ru = "Sesenta y siete ";} 
  elseif ($u==68) {$ru = "Sesenta y ocho ";} 
  elseif ($u==69) {$ru = "Sesenta y nueve ";} 
  elseif ($u==70) {$ru = "Setenta ";} 

  elseif ($u==71) {$ru = "Setenta y un ";} 
  elseif ($u==72) {$ru = "Setenta y dos ";} 
  elseif ($u==73) {$ru = "Setenta y tres ";} 
  elseif ($u==74) {$ru = "Setenta y cuatro ";} 
  elseif ($u==75) {$ru = "Setenta y cinco ";} 
  elseif ($u==76) {$ru = "Setenta y seis ";} 
  elseif ($u==77) {$ru = "Setenta y siete ";} 
  elseif ($u==78) {$ru = "Setenta y ocho ";} 
  elseif ($u==79) {$ru = "Setenta y nueve ";} 
  elseif ($u==80) {$ru = "Ochenta ";} 

  elseif ($u==81) {$ru = "Ochenta y un ";} 
  elseif ($u==82) {$ru = "Ochenta y dos ";} 
  elseif ($u==83) {$ru = "Ochenta y tres ";} 
  elseif ($u==84) {$ru = "Ochenta y cuatro ";} 
  elseif ($u==85) {$ru = "Ochenta y cinco ";} 
  elseif ($u==86) {$ru = "Ochenta y seis ";} 
  elseif ($u==87) {$ru = "Ochenta y siete ";} 
  elseif ($u==88) {$ru = "Ochenta y ocho ";} 
  elseif ($u==89) {$ru = "Ochenta y nueve ";} 
  elseif ($u==90) {$ru = "Noventa ";} 

  elseif ($u==91) {$ru = "Noventa y un ";} 
  elseif ($u==92) {$ru = "Noventa y dos ";} 
  elseif ($u==93) {$ru = "Noventa y tres ";} 
  elseif ($u==94) {$ru = "Noventa y cuatro ";} 
  elseif ($u==95) {$ru = "Noventa y cinco ";} 
  elseif ($u==96) {$ru = "Noventa y seis ";} 
  elseif ($u==97) {$ru = "Noventa y siete ";} 
  elseif ($u==98) {$ru = "Noventa y ocho ";} 
  else            {$ru = "Noventa y nueve ";} 
  return $ru; //Retornar el resultado 
  } 

  function decenas($d) 
  { 
      if ($d==0)  {$rd = "";} 
  elseif ($d==1)  {$rd = "Ciento ";} 
  elseif ($d==2)  {$rd = "Doscientos ";} 
  elseif ($d==3)  {$rd = "Trescientos ";} 
  elseif ($d==4)  {$rd = "Cuatrocientos ";} 
  elseif ($d==5)  {$rd = "Quinientos ";} 
  elseif ($d==6)  {$rd = "Seiscientos ";} 
  elseif ($d==7)  {$rd = "Setecientos ";} 
  elseif ($d==8)  {$rd = "Ochocientos ";} 
  else            {$rd = "Novecientos ";} 
  return $rd; //Retornar el resultado 
  } 
    

}


if (!function_exists('extraecaracteres'))
{

  function extraecaracteres($str,$number)
  {

    $cant_caracteres = strlen($str);
    if($cant_caracteres > $number){
      return substr($str,0,$number)." ...";
    }else{
      return $str;
    }

    
  }

}  



if (!function_exists('sanear_string'))
{

  function sanear_string($string)
  {

    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "'",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );
 
 
    return $string;

    
  }


if (!function_exists('dias_transcurridos'))
{

  function dias_transcurridos($fecha_i,$fecha_f)
  {
    $dias = (strtotime($fecha_i)-strtotime($fecha_f))/86400;
    $dias   = abs($dias); $dias = floor($dias);   
    return $dias;
  }

}  


if (!function_exists('sumar_dias'))
{

  function sumar_dias($fecha_i,$num_dias)
  {

    return date("d-m-Y",strtotime($fecha_i."+ ". $num_dias ." days"));
  }

}  



if (!function_exists('bussiness_days'))
{

  function bussiness_days($begin_date, $end_date, $type_day, $type = 'array') {
    $date_1 = date_create($begin_date);
    $date_2 = date_create($end_date);
    if ($date_1 > $date_2) return FALSE;
    $bussiness_days = array();
    while ($date_1 <= $date_2) {
      $day_week = $date_1->format('w');

      if($type_day == 'habil'){
        if ($day_week > 0 && $day_week < 6) {
          $bussiness_days[$date_1->format('Y-m')][] = $date_1->format('d');
        }

      }else if($type_day == 'inhabil'){
        if ($day_week == 0 || $day_week == 6) {
          $bussiness_days[$date_1->format('Y-m')][] = $date_1->format('d');
        }

      }else if($type_day == 'domingos'){
        if ($day_week == 0) {
          $bussiness_days[$date_1->format('Y-m')][] = $date_1->format('d');
        }        
      }

      date_add($date_1, date_interval_create_from_date_string('1 day'));
    }
    if (strtolower($type) === 'sum') {
        array_map(function($k) use(&$bussiness_days) {
            $bussiness_days[$k] = count($bussiness_days[$k]);
        }, array_keys($bussiness_days));
    }
    return $bussiness_days;
  }

}  



if (!function_exists('meses_transcurridos'))
{

  function meses_transcurridos($fecha_i,$fecha_f)
  {

    $fechainicial = new DateTime($fecha_i);
    $fechafinal = new DateTime($fecha_f);
   // var_dump($fechainicial);
   // var_dump($fechafinal);
    $diferencia = $fechainicial->diff($fechafinal);

    $meses = ( $diferencia->y * 12 ) + $diferencia->m;
    return $meses;
  }

} 


if (!function_exists('tipo_ingreso'))
{

  function tipo_ingreso($tipo_ingreso)
  {

    $tipo_ingreso_result = "";
    switch ($tipo_ingreso) {
        case 'cc':
            $tipo_ingreso_result = "Cuenta Corriente";
            break;
        case 'fr':
            $tipo_ingreso_result = "Fondo de Reserva";
            break;
        case 'na':
            $tipo_ingreso_result = "No Afecta Banco";
            break;
    }

    return $tipo_ingreso_result;
  }

}



if (!function_exists('ultimo_dia_mes'))
{

  function ultimo_dia_mes($month,$year)
  {
    return date("d",(mktime(0,0,0,$month+1,1,$year)-1));;
  }

}


if (!function_exists('formato_fecha'))
{

  function formato_fecha($fecha,$formato_origen,$formato_destino)
  {
    if($formato_origen == 'd/m/Y' && $formato_destino == 'Y-m-d'){
      return substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);

    }else if($formato_origen == 'Y-m-d' && $formato_destino == 'd/m/Y'){
      return substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
    }else if($formato_origen == 'Y-m-d' && $formato_destino == 'd-m-Y'){
      return substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);
    }

  }
}



if (!function_exists('dias_vacaciones'))
{

  function dias_vacaciones($fecinicio,$saldoinicvac)
  {


    $anno_inicio = substr($fecinicio,0,4);
    $mes_inicio = substr($fecinicio,5,2);

    $fecinicio_mesinicio = $anno_inicio."-".$mes_inicio."-01";

    $meses = meses_transcurridos($fecinicio_mesinicio,date("Y-m-d"));

    #MESES DONDE SE CALCULARÁN VACACIONES COMPLETAS
    $meses_completos = ($meses - 1) <= 0 ? 0 : ($meses - 1);



    #MESES PROPORCIONALES (PRIMERO Y ULTIMO)

   // $dias_mes_inic = $meses == 0 ? dias_transcurridos($fecinicio,date("Y-m-d")) : dias_transcurridos($fecinicio,$anno_inicio."-".$mes_inicio."-".ultimo_dia_mes($anno_inicio,$mes_inicio));
     $dias_mes_inic = $meses_completos == 0 ? dias_transcurridos($fecinicio,date("Y-m-d")) : dias_transcurridos($fecinicio,$anno_inicio."-".$mes_inicio."-".ultimo_dia_mes($anno_inicio,$mes_inicio));
    #$mes_prop_mes_inic = round($dias_mes_inic/30,2);
    $mes_prop_mes_inic = $dias_mes_inic/30;


    //$dias_mes_fin = $meses == 0 ? 0 : dias_transcurridos(date("Y-m")."-01",date("Y-m-d"));
    $dias_mes_fin = $meses_completos == 0 ? 0 : dias_transcurridos(date("Y-m")."-01",date("Y-m-d"));
    #$mes_prop_mes_fin = round($dias_mes_fin/30,2);
    $mes_prop_mes_fin = $dias_mes_fin/30;

    $meses_totales = $meses_completos + $mes_prop_mes_inic + $mes_prop_mes_fin;

    $vacaciones_totales = round($meses_totales*1.25,2) + $saldoinicvac;
   
    return $vacaciones_totales;




  }
}



if (!function_exists('dias_vacaciones_fechas'))
{

  function dias_vacaciones_fechas($fecinicio,$fecfin,$saldoinicvac)
  {


    $anno_inicio = substr($fecinicio,0,4);
    $mes_inicio = substr($fecinicio,5,2);

    $fecinicio_mesinicio = $anno_inicio."-".$mes_inicio."-01";

    $meses = meses_transcurridos($fecinicio_mesinicio,$fecfin);
    //var_dump($meses);
    $meses = 0;
    #MESES DONDE SE CALCULARÁN VACACIONES COMPLETAS
    $meses_completos = ($meses - 1) <= 0 ? 0 : ($meses - 1);



    #MESES PROPORCIONALES (PRIMERO Y ULTIMO)

    $dias_mes_inic = $meses_completos == 0 ? dias_transcurridos($fecinicio,$fecfin) : dias_transcurridos($fecinicio,$anno_inicio."-".$mes_inicio."-".ultimo_dia_mes($anno_inicio,$mes_inicio));

    //var_dump($dias_mes_inic); 
    #$mes_prop_mes_inic = round($dias_mes_inic/30,2);
    $mes_prop_mes_inic = $dias_mes_inic/30;

   // var_dump($mes_prop_mes_inic); exit;
    $dias_mes_fin = $meses_completos == 0 ? 0 : dias_transcurridos(substr($fecfin,0,7)."-01",$fecfin);
    #$mes_prop_mes_fin = round($dias_mes_fin/30,2);
    $mes_prop_mes_fin = $dias_mes_fin/30;
    //var_dump($meses);
    //var_dump($dias_mes_fin);
    //var_dump($mes_prop_mes_fin);
    $meses_totales = $meses_completos + $mes_prop_mes_inic + $mes_prop_mes_fin;
    //var_dump($meses_totales);
    $vacaciones_totales = round($meses_totales*1.25,2) + $saldoinicvac;
    //var_dump($vacaciones_totales); exit;
    return $vacaciones_totales;




  }
}



if (!function_exists('periodos_entre_fechas'))
{

  function periodos_entre_fechas($fecinicio,$fecfin)
  {


    $anno_inicio = substr($fecinicio,0,4);
    $mes_inicio = substr($fecinicio,5,2);

    $anno_fin = substr($fecfin,0,4);
    $mes_fin = substr($fecfin,5,2);


    
 

    if($anno_inicio.$mes_inicio != $anno_fin.$mes_fin){

       $array_periodos = array();
        $array_datos_periodos = array(
                                  'periodo' => $anno_inicio.$mes_inicio,
                                  'fecha_desde' => $fecinicio,
                                  'fecha_hasta' => $anno_inicio."-".$mes_inicio."-".ultimo_dia_mes($anno_inicio,$mes_inicio) //ultimo día del mes
                                );
        array_push($array_periodos,$array_datos_periodos);      

        $flag = true;
        $mes_evalua = $mes_inicio;
        $anno_evalua = $anno_inicio;
        while($flag){

          //obtiene el período siguiente
          $anno_evalua = (int) $mes_evalua < 12 ? $anno_evalua : $anno_evalua + 1;
          $mes_evalua = (int) $mes_evalua < 12 ? $mes_evalua + 1 : 1;          
          $mes_evalua = str_pad($mes_evalua, 2, "0", STR_PAD_LEFT);


          //si periodo siguiente es igual al final, considera sólo hasta la fecha fin
          if($anno_evalua.$mes_evalua == $anno_fin.$mes_fin){
              $array_datos_periodos = array(
                                        'periodo' => $anno_evalua.$mes_evalua,
                                        'fecha_desde' => $anno_evalua."-".$mes_evalua."-01",
                                        'fecha_hasta' => $fecfin //ultimo día del mes
                                      );
              array_push($array_periodos,$array_datos_periodos);          
              $flag = false;
          }else{ // en caso contrario se considera todo el período

              $array_datos_periodos = array(
                                        'periodo' => $anno_evalua.$mes_evalua,
                                        'fecha_desde' => $anno_evalua."-".$mes_evalua."-01",
                                        'fecha_hasta' => $anno_evalua."-".$mes_evalua."-".ultimo_dia_mes($anno_evalua,$mes_evalua) //ultimo día del mes
                                      );
              array_push($array_periodos,$array_datos_periodos);  

          }


        }



    }else{


        $array_periodos = array();
        $array_datos_periodos = array(
                                  'periodo' => $anno_inicio.$mes_inicio,
                                  'fecha_desde' => $fecinicio,
                                  'fecha_hasta' => $fecfin
                                );
        array_push($array_periodos,$array_datos_periodos);


    }

    

    //var_dump($vacaciones_totales); exit;
    return $array_periodos;




  }
}

if (!function_exists('num_dias_progresivos'))
{

  function num_dias_progresivos($fecinicio,$saldoinicvac,$array_progresivos)
  {

    $cartola = cartola_dias_progresivos($fecinicio,$saldoinicvac,$array_progresivos);
    //var_dump($cartola); exit;
    $sum = array_sum(array_column($cartola, 'dias'));
    return $sum;
  }

}

if (!function_exists('var_dump_new'))
{

  function var_dump_new($var)
  {

      echo "<pre>";
      var_dump($var);
  }
}


if (!function_exists('dia_progresivo'))
{

  function dia_progresivo($fecinicio)
  {

    $array_rangos = array();
    $fecha_hoy = date("Y-m-d");
    $timestamp_hoy = strtotime($fecha_hoy);

    //$fecinicio = formato_fecha($fecinicio,'d/m/Y','Y-m-d');
    $feciniccalculo = $fecinicio;


    $timestamp_annos_despues = strtotime('+3 year',strtotime($feciniccalculo));

    return $timestamp_annos_despues <= $timestamp_hoy ?  date("Y-m-d",$timestamp_annos_despues) : false;

  }
}


if (!function_exists('cartola_vacaciones'))
{

  function cartola_vacaciones($fecinicio,$saldoinicvac,$cartola_progresivos)
  {


    $dias_progresivos = 0;
    foreach ($cartola_progresivos as $progresivos) {
      $dias_progresivos += $progresivos['fecinicio'] == 'Saldo Inicial' ? $progresivos['dias'] : 0;
    }


    $array_cartola = array();

      $array_cartola[] = array('fecinicio' => "Saldo Inicial",
                               'fecfin' => "",
                                'dias' => $saldoinicvac,
                                'diasprogresivos' => $dias_progresivos);


    $fecha_hoy = date("Y-m-d");

    $timestamp_hoy = strtotime($fecha_hoy);

    $feciniccalculo = $fecinicio;


    $timestamp_anno_despues = strtotime('+1 year',strtotime($feciniccalculo));
    $timestamp_ult_anno = strtotime('-1 day',$timestamp_anno_despues);
    $fecha_ult_anno = date("Y-m-d",$timestamp_ult_anno); 


    while($timestamp_ult_anno < $timestamp_hoy){


      $dias_progresivos = 0;
      foreach ($cartola_progresivos as $progresivos) {
        $dias_progresivos += $progresivos['fecinicio'] == $fecinicio ? $progresivos['dias'] : 0;
      }

      $array_cartola[] = array('fecinicio' => $fecinicio,
                               'fecfin' => $fecha_ult_anno,
                                'dias' => round(12*1.25,2),
                                'diasprogresivos' => $dias_progresivos);

       $fecinicio = date("Y-m-d",$timestamp_anno_despues); 
       $timestamp_anno_despues = strtotime('+1 year',$timestamp_anno_despues);
       $timestamp_ult_anno = strtotime('-1 day',$timestamp_anno_despues);
       $fecha_ult_anno = date("Y-m-d",$timestamp_ult_anno); 
       #$fecinicio = date("Y-m-d",$timestamp_ult_anno); 
       

      
    }

      $dias_progresivos = 0;
      foreach ($cartola_progresivos as $progresivos) {
        $dias_progresivos += $progresivos['fecinicio'] == $fecinicio ? $progresivos['dias'] : 0;
      }


    $dias_final = dias_vacaciones($fecinicio,0);
      $array_cartola[] = array('fecinicio' => $fecinicio,
                               'fecfin' => $fecha_hoy,
                                'dias' => $dias_final,
                                'diasprogresivos' => $dias_progresivos);

      return $array_cartola;

  }
}



if (!function_exists('cartola_dias_progresivos'))
{

  function cartola_dias_progresivos($fecinicio,$saldoinicvac,$array_progresivos)
  {

    $array_cartola = array();

      $array_cartola[] = array('fecinicio' => "Saldo Inicial",
                               'fecfin' => "",
                                'dias' => $saldoinicvac);    

    $fecha_hoy = date("Y-m-d");
    $timestamp_hoy = strtotime($fecha_hoy);


    $feciniccalculo = $fecinicio;


    $timestamp_anno_despues = strtotime('+1 year',strtotime($feciniccalculo));
    $timestamp_ult_anno = strtotime('-1 day',$timestamp_anno_despues);
    $fecha_ult_anno = date("Y-m-d",$timestamp_ult_anno); 

    $dias_acum = 0;
    while($timestamp_ult_anno < $timestamp_hoy){

      $dias_progresivos_periodo = 0;
      foreach ($array_progresivos as $progresivos) {
        $timestamp_prog = strtotime($progresivos->fechainicio);
        $timestamp_fecinicio = strtotime($fecinicio);
        if($progresivos->fechainicio == substr($fecinicio,0,4)){
            $dias_progresivos_periodo = $progresivos->dias;  
        }
      }
      $dias_acum += $dias_progresivos_periodo;

      $array_cartola[] = array('fecinicio' => $fecinicio,
                               'fecfin' => $fecha_ult_anno,
                                'dias' => $dias_acum);

       $fecinicio = date("Y-m-d",$timestamp_anno_despues); 
       $timestamp_anno_despues = strtotime('+1 year',$timestamp_anno_despues);
       $timestamp_ult_anno = strtotime('-1 day',$timestamp_anno_despues);
       $fecha_ult_anno = date("Y-m-d",$timestamp_ult_anno); 
       #$fecinicio = date("Y-m-d",$timestamp_ult_anno); 
       

      
    }

      $dias_progresivos_periodo = 0;
      foreach ($array_progresivos as $progresivos) {
        $timestamp_prog = strtotime($progresivos->fechainicio);
        $timestamp_fecinicio = strtotime($fecinicio);
        if($progresivos->fechainicio == substr($fecinicio,0,4)){
            $dias_progresivos_periodo = $progresivos->dias;  
        }

      }
      $dias_acum += $dias_progresivos_periodo;

      $array_cartola[] = array('fecinicio' => $fecinicio,
                               'fecfin' => $fecha_hoy,
                                'dias' => $dias_acum);

      //var_dump($array_cartola); exit;

      return $array_cartola;
  }
}



/*if (!function_exists('cartola_dias_progresivos'))
{

  function cartola_dias_progresivos($fecinicio,$array_progresivos)
  {

    $array_cartola = array();

    $fecha_hoy = date("Y-m-d");
    $timestamp_hoy = strtotime($fecha_hoy);


    $feciniccalculo = $fecinicio;


    $timestamp_anno_despues = strtotime('+1 year',strtotime($feciniccalculo));
    $timestamp_ult_anno = strtotime('-1 day',$timestamp_anno_despues);
    $fecha_ult_anno = date("Y-m-d",$timestamp_ult_anno); 


    while($timestamp_ult_anno < $timestamp_hoy){

      $dias_progresivos_periodo = 0;
      foreach ($array_progresivos as $progresivos) {
        $timestamp_prog = strtotime($progresivos->fechacumple);
        if($timestamp_ult_anno < $timestamp_prog){
            break;
        }else{
          $dias_progresivos_periodo = $progresivos->acumulado;
        }
      }

      $array_cartola[] = array('fecinicio' => $fecinicio,
                               'fecfin' => $fecha_ult_anno,
                                'dias' => $dias_progresivos_periodo);

       $fecinicio = date("Y-m-d",$timestamp_anno_despues); 
       $timestamp_anno_despues = strtotime('+1 year',$timestamp_anno_despues);
       $timestamp_ult_anno = strtotime('-1 day',$timestamp_anno_despues);
       $fecha_ult_anno = date("Y-m-d",$timestamp_ult_anno); 
       #$fecinicio = date("Y-m-d",$timestamp_ult_anno); 
       

      
    }

      $dias_progresivos_periodo = 0;
      foreach ($array_progresivos as $progresivos) {
        $timestamp_prog = strtotime($progresivos->fechacumple);
        if($timestamp_hoy < $timestamp_prog){
            break;
        }else{
          $dias_progresivos_periodo = $progresivos->acumulado;
        }
      }

      $array_cartola[] = array('fecinicio' => $fecinicio,
                               'fecfin' => $fecha_hoy,
                                'dias' => $dias_progresivos_periodo);

      return $array_cartola;
  }
}*/




if (!function_exists('get_periodos_vacaciones'))
{

  function get_periodos_vacaciones($fecinicio)
  {

    $array_cartola = array();

    $fecha_hoy = date("Y-m-d");
    $timestamp_hoy = strtotime($fecha_hoy);


    $feciniccalculo = $fecinicio;


    $timestamp_anno_despues = strtotime('+1 year',strtotime($feciniccalculo));
    $timestamp_ult_anno = strtotime('-1 day',$timestamp_anno_despues);
    $fecha_ult_anno = date("Y-m-d",$timestamp_ult_anno); 


    while($timestamp_ult_anno < $timestamp_hoy){

      $array_cartola[] = array('fecinicio' => $fecinicio,
                               'fecfin' => $fecha_ult_anno);

       $fecinicio = date("Y-m-d",$timestamp_anno_despues); 
       $timestamp_anno_despues = strtotime('+1 year',$timestamp_anno_despues);
       $timestamp_ult_anno = strtotime('-1 day',$timestamp_anno_despues);
       $fecha_ult_anno = date("Y-m-d",$timestamp_ult_anno); 
      
    }


      $array_cartola[] = array('fecinicio' => $fecinicio,
                               'fecfin' => $fecha_ult_anno);

      return $array_cartola;
  }
}



if (!function_exists('primera_mayuscula'))
{

  function primera_mayuscula($palabra)
  {

 
      return ucwords(strtolower($palabra));
  }
}




if (!function_exists('dias_mes_rango'))
{

  function dias_mes_rango($fecinicio,$fecfin,$mes_analisis)
  {
   // echo $fecinicio."- ".$fecfin."-".$mes_analisis."<br>";
    $fecinicio_format = str_replace("-","",substr($fecinicio,0,7));
    $fecfin_format = str_replace("-","",substr($fecfin,0,7));
    $mes_an = substr($mes_analisis,4,2);
    $anno_an = substr($mes_analisis,0,4);
    $dias_transcurridos_mes = -1;
    if($fecinicio_format == $mes_analisis && $fecfin_format == $mes_analisis){

        $fechainicial = new DateTime($fecinicio);
        $fechafinal = new DateTime($fecfin);
        $diferencia = $fechainicial->diff($fechafinal);
        $dias_transcurridos_mes = $diferencia->days;

    }

    if($fecinicio_format != $mes_analisis && $fecfin_format != $mes_analisis){

        $dias_transcurridos_mes = $dias_transcurridos_mes;

    }


    if($fecinicio_format == $mes_analisis && $fecfin_format != $mes_analisis){


        $ult_dia_mes =  ultimo_dia_mes($mes_an,$anno_an); 
        $fechainicial = new DateTime($fecinicio);
       // echo $fecinicio."  ".$anno_an."-".$mes_an."-".$ult_dia_mes; 
        $fechafinal = new DateTime($anno_an."-".$mes_an."-".$ult_dia_mes);
        $diferencia = $fechainicial->diff($fechafinal);
       // var_dump($diferencia); 
        $dias_transcurridos_mes = $diferencia->days;

    }    


    if($fecinicio_format != $mes_analisis && $fecfin_format == $mes_analisis){


        $fechainicial = new DateTime($anno_an."-".$mes_an."-01");
        $fechafinal = new DateTime($fecfin);
        $diferencia = $fechainicial->diff($fechafinal);
        $dias_transcurridos_mes = $diferencia->days;

    }  

 
      return $dias_transcurridos_mes+1;
  }
}



} 

           

      