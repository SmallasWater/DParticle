<?php
namespace dxp\dpt\shape;

class Round1 extends ShapeBase{
 public static function getVector3($r,$data){
  //动态平面双行星环绕算法(反)
  $pos=array();
  
  $x=$r*cos(deg2rad($data[0]));
  $z=$r*sin(deg2rad($data[0]));
  $pos[]=array(-$x,0,$z);
  $pos[]=array($x,0,-$z);
  
  unset($r,$data,$x,$z);
  return $pos;
 }
}
?>