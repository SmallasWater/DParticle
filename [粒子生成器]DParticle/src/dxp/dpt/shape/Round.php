<?php
namespace dxp\dpt\shape;

class Round extends ShapeBase{
 public static function getVector3($r,$data){
  //动态平面双行星环绕算法(正)
  $pos=array();
  
  $x=$r*cos(deg2rad($data[0]));
  $z=$r*sin(deg2rad($data[0]));
  $pos[]=array($x,0,$z);
  $pos[]=array(-$x,0,-$z);
  return $pos;
  unset($pos,$deg,$x,$z);
 }
}
?>