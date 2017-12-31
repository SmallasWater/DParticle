<?php
namespace dxp\dpt\shape;

class Triangle extends ShapeBase{
 public static function getVector3($r,$deg){
  //正三角形算法
  $pos=array();
  $sin=sin(deg2rad(30));
  $cos=cos(deg2rad(30));
  
  $l=2*$r*$cos;
  for($i=-$r*$cos;$i<=$r*$cos;$i+=0.1){
   $pos[]=array($i,0,-$r*$sin);
  }
  
  for($i=0;$i<=$l;$i+=0.1){
   $x=$i*$cos;
   $z=$i*$sin;
   $pos[]=array(-$z,0,$r-$x);
   $pos[]=array($z,0,$r-$x);
  }
  return $pos;
  unset($pos,$r,$deg,$sin,$cos,$i,$x,$z);
 }
}
?>