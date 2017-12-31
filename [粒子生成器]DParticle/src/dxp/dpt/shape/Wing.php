<?php
namespace dxp\dpt\shape;

class Wing extends ShapeBase{
 public static function getVector3($r,$wing=null){
  //粒子翼算法
  $pos=array();
  $w1=array();
  
  if(!is_numeric($wing[0]) || !is_numeric($wing[1]) || !is_array($wing[2])){
   return null;
  }
  
  for($a=0;$a<count($wing[2]);$a++){
   $w=$wing[2][$a];
   for($i=0;$i<count($w);$i++){
    $b=$w[$i];
    for($ii=0;$ii<count($b);$ii++){
     $c=$b[$ii];
     if($c!==0){
      $pos[]=array((count($b)-$ii)*0.15,(count($w)-$i)*0.15,-0.15*$a);
     }
     if($ii==0 && $w1==array()){
      $w1[]=array((count($b)-$ii)*0.15,0,0);
     }
    }
   }
  }
  $pos1=ShapeBase::turn(0,$r,0,$pos);
  $w1=ShapeBase::turn(0,$r,0,$w1);
  
  $pos=array();
  if($wing[0]==1){
   if(!isset($wing[3]) || !is_array($wing[3])){
    return null;
   }
   for($a=0;$a<count($wing[3]);$a++){
    $w=$wing[3][$a];
    for($i=0;$i<count($w);$i++){
     $b=$w[$i];
     for($ii=0;$ii<count($b);$ii++){
      $c=$b[$ii];
      if($c!==0){
       $pos[]=array((count($b)-$ii)*0.15,(count($w)-$i)*0.15,(-0.15*$a));
      }
     }
    }
   }
   if($r<=90){
    $r=$r*3;
   }else{
    $rr=360-$r;
    $r=360-($rr*3);
   }
   $pos2=ShapeBase::turn(0,$r,0,$pos);
   $pos3=array();
   foreach($pos2 as $ppp){
    $ppp[0]+=$w1[0][0];
    $ppp[2]+=$w1[0][2];
    $pos3[]=$ppp;
   }
   $pos1=array_merge($pos1,$pos3);
  }
  $pos=array();
  foreach($pos1 as $p){
   $p[2]-=0.5;
   $pos[]=$p;
   $pp=$p;
   $pp[0]=-$p[0];
   $pos[]=$pp;
  }
  unset($r,$i,$x,$z,$w,$pos1,$pos2,$pp,$a,$b,$c,$deg);
  return $pos;
 }

 public static function getParticleMap($r,$wing=null){
  if(!is_numeric($wing[0]) || !isset($wing[1]) || !is_array($wing[2])){
   return array();
  }
  $particle=array();
  for($a=0;$a<count($wing[2]);$a++){
   $w=$wing[2][$a];
   for($i=0;$i<count($w);$i++){
    $b=$w[$i];
    for($ii=0;$ii<count($b);$ii++){
     $c=$b[$ii];
     if($c!==0){
      $particle[]=$c;
     }
    }
   }
  }
  
  if($wing[0]==1){
   if(!isset($wing[3]) || !is_array($wing[3])){
    return array();
   }
   for($a=0;$a<count($wing[3]);$a++){
    $w=$wing[3][$a];
    for($i=0;$i<count($w);$i++){
     $b=$w[$i];
     for($ii=0;$ii<count($b);$ii++){
      $c=$b[$ii];
      if($c!==0){
       $particle[]=$c;
      }
     }
    }
   }
  }
  
  $par=array();
  foreach($particle as $pp){
   $par[]=$pp;
   $par[]=$pp;
  }
  unset($pos,$r,$i,$x,$z,$w,$b,$c,$deg);
  $pt=array();
  foreach($par as $p){
   switch($p){
    case 1:
     $pt[]='Flame';
     break;
    case 2:
     $pt[]='Portal';
     break;
    case 3:
     $pt[]='HappyVillager';
     break;
    case 4:
     $pt[]='Redstone';
     break;
    case 5:
     $pt[]='Smoke';
     break;
    case 6:
     $pt[]='Heart';
     break;
    case 7:
     $pt[]='Bubble';
     break;
    default:
     $pt[]=null;
     break;
   }
  }
  return $pt;
 }
}
?>