<?php
namespace dxp\dpt\shape;

class Round3 extends ShapeBase{
 public static function getVector3($r,$data){
  //动态平面双行星环绕算法(正)
  $pos=array();
  $h=$data[1];
  
  $x=$r*cos(deg2rad($deg));
  $z=$r*sin(deg2rad($deg));
  $pos[]=array($x,-2+$h['h'],$z);
  $pos[]=array(-$x,-2+$h['h'],-$z);
  if($h['s1']!==0){
   $pos1=array();
   $x=$r*cos(deg2rad($data[0]));
   $z=$r*sin(deg2rad($data[0]));
   $pos1[]=array(-$x,-2+$h['h1'],-$z);
   $pos1[]=array($x,-2+$h['h1'],$z);
   $pos=array_merge($pos,$pos1);
  }
  return $pos;
  unset($pos,$pos1,$r,$pl,$h,$deg,$x,$z);
 }
}
?>