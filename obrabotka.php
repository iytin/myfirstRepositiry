<?php
require_once 'conectdb.php';
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' and $_POST[query]=='all') {
  $resso = mysql_query("SELECT coordinates, id_user, id_section FROM temp_data ");
  $mar= mysql_fetch_assoc($resso);
  if($mar){
  do{
      $coord=explode(',',$mar[coordinates]);
        $json =  array(id=>$mar['id_user'],x=>$coord[0], y=>$coord[1],id_s=>$mar['id_section']);
        $markers[] = $json;  
  }while($mar = mysql_fetch_assoc($resso));
  }else{
      $markers[] = array(Status=>'No'); 
      $points = array(markers=>$markers); 
  }
$points = array(markers=>$markers); 
echo json_encode($points);
}
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' and $_POST[id_firm]!='') {
  $resso = mysql_query("SELECT * FROM temp_data Where id_user='$_POST[id_firm]'");
  $mar= mysql_fetch_assoc($resso);
  if($mar){
  do{
      $coord=explode(',',$mar[coordinates]);
        $json =  array(id=>$mar['id_user'], x=>$coord[0], y=>$coord[1],id_s=>$mar['id_section'],id_r=>$mar['id_region'],
            f_n=>$mar['firm_name'],s_d=>$mar['shot_description'],ads=>$mar['address'],ph=>$mar['phone']
            ,eil=>$mar['email'],sk=>$mar['skype'],w_h=>$mar['working_hours'],lin=>$mar['links']);
        $markers[] = $json;  
  }while($mar = mysql_fetch_assoc($resso));
  }else{
      $markers[] = array(Status=>'No'); 
      $points = array(markers=>$markers); 
  }
$points = array(markers=>$markers); 
echo json_encode($points);
}
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' and $_POST[idget]!='') {
  $resso = mysql_query("SELECT * FROM temp_data Where id_user='$_POST[idget]'");
  $mar= mysql_fetch_assoc($resso);
  if($mar){
  do{
        $json =  array(f_n=>$mar['firm_name'],s_d=>$mar['shot_description'],ads=>$mar['address'],ph=>$mar['phone']
            ,eil=>$mar['email'],sk=>$mar['skype'],w_h=>$mar['working_hours'],lin=>$mar['links']);
        $markers[] = $json;  
  }while($mar = mysql_fetch_assoc($resso));
  }else{
      $markers[] = array(Status=>'No'); 
      $points = array(markers=>$markers); 
  }
$points = array(markers=>$markers); 
echo json_encode($points);
}

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' and $_POST[search]!='') {
  $resso = mysql_query("SELECT * FROM temp_data Where firm_name LIKE '%$_POST[search]%' 
          or shot_description LIKE '%$_POST[search]%'
          or address LIKE '%$_POST[search]%'
        ");
  $mar= mysql_fetch_assoc($resso);
  if($mar){
  do{
      $coord=explode(',',$mar[coordinates]);
        $json =  array(id=>$mar['id_user'], x=>$coord[0], y=>$coord[1],id_s=>$mar['id_section']);
        $markers[] = $json;  
  }while($mar = mysql_fetch_assoc($resso));
  }else{
      $markers[] = array(Status=>'No'); 
      $points = array(markers=>$markers); 
  }
$points = array(markers=>$markers); 
echo json_encode($points);
}
?>
