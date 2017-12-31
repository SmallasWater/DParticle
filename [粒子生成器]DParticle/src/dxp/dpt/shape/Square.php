<?php
namespace dxp\dpt\shape;

class Square extends ShapeBase{
 public static function getVector3($r,$deg){
  //正方形算法
  $pos=array();
  
  $r/=sqrt(2);
  for($i=-$r;$i<=$r;$i+=0.2){
   $pos[]=array($i,0,$r);
   $pos[]=array($i,0,-$r);
   $pos[]=array($r,0,$i);
   $pos[]=array(-$r,0,$i);
  }
  return $pos;
  unset($pos,$r,$i);
 }
}
?>