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
class Ball extends ShapeBase{
 public static function getVector3($r=3,$data=array()){
  $pos=array();
  //密度控制
  if(!isset($data[0])){
   $k1=9;
  }else{
   $k1=$data[0];
  }
  if(!isset($data[1])){
   $k2=9;
  }else{
   $k2=$data[1];
  }
  //经坐标计算
  $a=array();
  for($i=0;$i<=90;$i+=$k1){
   $x=$r*cos(deg2rad($i));
   $y=$r*sin(deg2rad($i));
   $a[]=array($x,+$y);
   $a[]=array($x,-$y);
  }
  //纬坐标计算
  foreach($a as $b){
   for($i=0;$i<=90;$i+=$k2){
    $x=$b[0]*cos(deg2rad($i));
    $z=$b[0]*sin(deg2rad($i));
    $pos[]=array($x,$b[1],$z);
    $pos[]=array(-$z,$b[1],$x);
    $pos[]=array(-$x,$b[1],-$z);
    $pos[]=array($z,$b[1],-$x);
   }
  }
  unset($r,$data,$k1,$k2,$a,$i,$x,$y,$b,$z);
  return $pos;
 }
}
?>