<?php
namespace dxp\dpt\shape;

class Circle1 extends ShapeBase{
 public static function getVector3($r,$data){
  //高密圆算法
  $pos=array();
  
  for($t=0;$t<=sqrt(2)*$r;$t+=0.2){
   $x=sqrt(2)*$r*0.5-$t;
   $z=sqrt(pow(sqrt(2)*$r*0.5,2)-pow(sqrt(2)*$r*0.5-$t,2));
   $pos[]=array($x,0,$z);
   $pos[]=array($x,0,-$z);
   $pos[]=array($z,0,$x);
   $pos[]=array(-$z,0,$x);
  }
  unset($r,$x,$z,$t);
  return $pos;
 }
}
?>