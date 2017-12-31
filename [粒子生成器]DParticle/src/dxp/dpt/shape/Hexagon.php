<?php
namespace dxp\dpt\shape;

class Hexagon extends ShapeBase{
 public static function getVector3($r,$data){
  //正六边形算法
  $pos=array();
  $sin=sin(deg2rad(30));
  $cos=cos(deg2rad(30));
  
  $l=2*$r*$sin;
  for($i=-$r*$sin;$i<=$r*$sin;$i+=0.1){
   $pos[]=array($r*$cos,0,$i);
   $pos[]=array(-$r*$cos,0,$i);
  }
  
  for($i=0;$i<=$l;$i+=0.1){
   $x=$i*$cos;
   $z=$i*$sin;
   $pos[]=array(-$x,0,$r-$z);
   $pos[]=array($x,0,$r-$z);
   $pos[]=array(-$x,0,-$r+$z);
   $pos[]=array($x,0,-$r+$z);
  }
  return $pos;
  unset($pos,$r,$i,$x,$z);
 }
}
?>