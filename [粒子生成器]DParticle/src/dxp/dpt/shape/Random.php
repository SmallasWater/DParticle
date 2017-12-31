<?php
namespace dxp\dpt\shape;

class Random extends ShapeBase{
 public static function getVector3($r,$data){
  //随机坐标算法
  $pos=array();
  
  $x=rand(-10*$r,10*$r)/10;
  $z=rand(-10*$r,10*$r)/10;
  $pos[]=array($x,0,$z);
  $pos[]=array(-$x,0,-$z);
  
  unset($r,$x,$z);
  return $pos;
 }
}
?>