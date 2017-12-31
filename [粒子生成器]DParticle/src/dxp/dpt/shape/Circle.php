<?php
namespace dxp\dpt\shape;
/*
圆算法
 By:aabbcc872[dxp]
 最后一次优化:2017.10.11
参数说明:
 r=半径[double|int|float]
 data=array(密度)[建议为90因数，设为-1弹性计算]
*/
class Circle extends ShapeBase{
 public static function getVector3($r=2,$data=array()){
  $pos=array();
  //密度控制
  if(!isset($data[0])){
   $k=-1;
  }
  if($data[0]==0){
   return array(0,0,0);
  }
  if($data[0]==-1){
   
  $ar=round($r,1);
  $c=0;
  for($a=0;$a<=$ar;$a+=0.1){
   $c++;
  }
  $b=360/($c*4);
  if($b>90 || $b<0){
   $b=3;
  }
  for($i=0;$i<=90;$i+=$b){
   $x=$r*cos(deg2rad($i));
   $z=$r*sin(deg2rad($i));
   $pos[]=array($x,0,$z);
   $pos[]=array(-$z,0,$x);
   $pos[]=array(-$x,0,-$z);
   $pos[]=array($z,0,-$x);
  }
  unset($r,$data,$i,$x,$z);
  return $pos;
 }
}
?>