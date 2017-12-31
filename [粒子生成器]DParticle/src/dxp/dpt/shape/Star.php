<?php
namespace dxp\dpt\shape;

class Star extends ShapeBase{
 public static function getVector3($r,$deg){
  //五角星算法
  $pos=array();
  $sin1=sin(deg2rad(18));
  $cos1=cos(deg2rad(18));
  $sin2=sin(deg2rad(36));
  $cos2=cos(deg2rad(36));
  
  $l=2*$r*$cos1;
  for($i=-$r*$cos1;$i<=$r*$cos1;$i+=0.1){
   $pos[]=array($i,0,$r*$sin1);
  }
  
  for($i=0;$i<=$l;$i+=0.1){
   $x=$i*$cos1;
   $z=$i*$sin1;
   $pos[]=array(-$z,0,$r-$x);
   $pos[]=array($z,0,$r-$x);
   
   $x=$i*$cos2;
   $z=$i*$sin2;
   $pos[]=array($r*$cos1-$x,0,$r*$sin1-$z);
   $pos[]=array(-$r*$cos1+$x,0,$r*$sin1-$z);
  }
  unset($r,$deg,$sin1,$cos1,$sin2,$cos2,$i,$x,$z,$l);
  return $pos;
 }
}
?>