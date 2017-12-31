<?php
namespace dxp\dpt\shape;

class Pentagon extends ShapeBase{
 public static function getVector3($r,$data){
  //正五边形算法
  $pos=array();
  $sin=sin(deg2rad(36));
  $cos=cos(deg2rad(36));
  
  $l=2*$r*$sin;
  for($i=-$r*$sin;$i<=$r*$sin;$i+=0.1){
   $pos[]=array($i,0,-$r*$cos);
  }
  
  for($i=0;$i<=$l;$i+=0.1){
   $x=$i*sin(deg2rad(18));
   $z=$i*cos(deg2rad(18));
   $pos[]=array(-$cos*$i,0,$r-$sin*$i);
   $pos[]=array($cos*$i,0,$r-$sin*$i);
   $pos[]=array(-$r*$sin-$x,0,-$r*$cos+$z);
   $pos[]=array($r*$sin+$x,0,-$r*$cos+$z);
  }
  return $pos;
  unset($pos,$r,$i,$x,$z,$sina,$sinb);
 }
}
?>