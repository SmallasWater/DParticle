<?php
namespace dxp\dpt\shape;
/*
球体坐标算法
 By:aabbcc872[dxp]
 最后一次优化:2017.10.11
参数说明:
 r=半径[double|int|float]
 data=array(经密度,纬密度)[建议为90因数]
*/
class Ball1 extends ShapeBase{
 public static function getVector3($r,$data){
  $pos=array();
  
  $a=array();
  for($i=0;$i<=90;$i+=3){
   $x=$r*cos(deg2rad($i));
   $y=$r*sin(deg2rad($i));
   $a[]=array($x,$y);
   $a[]=array($x,-$y);
  }
  foreach($a as $b){
   $x=$b[0]*cos(deg2rad($ro));
   $z=$b[0]*sin(deg2rad($ro));
   $pos[]=array($x,$b[1],$z);
   $pos[]=array(+$z,$b[1],-$x);
   $pos[]=array(-$x,$b[1],-$z);
   $pos[]=array(-$z,$b[1],$x);
  }
  return $pos;
  unset($pos,$r,$ro,$a,$i,$b,$x,$z);
 }
}
?>