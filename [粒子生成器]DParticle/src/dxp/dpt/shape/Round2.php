<?php
namespace dxp\dpt\shape;

use dxp\dpt\DParticle;
use dxp\dpt\shape\Round as Rd;

class Round2 extends ShapeBase{	

 public static function getVector3($r,$data){
  //动态平面四行星环绕算法(正反)
  $pos=array();
  
  $pos=Rd::getVector3($r,$data);
  $pos=array_merge($pos,Round1::getVector3($r,$data));
  unset($r,$data);
  return $pos;
 }
}
?>