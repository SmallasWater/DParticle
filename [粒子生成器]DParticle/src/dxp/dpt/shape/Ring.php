<?php
namespace dxp\dpt\shape;

class Ring extends ShapeBase{
 public static function getVector3($r,$data){
  //环形隧道算法
  $pos=array();
  
  $a=array();
  $rr=$r*0.2;
  for($i=0;$i<=90;$i+=10){
   $x=$rr*cos(deg2rad($i));
   $y=$rr*sin(deg2rad($i));
   $a[]=array($x,$y);
   $a[]=array($x,-$y);
   $a[]=array(-$x,$y);
   $a[]=array(-$x,-$y);
  }
  foreach($a as $b){
   for($i=0;$i<=90;$i+=10){
    $x=($r-$b[0])*cos(deg2rad($i));
    $z=($r-$b[0])*sin(deg2rad($i));
    $pos[]=array($x,$b[1],$z);
    $pos[]=array(-$z,$b[1],$x);
    $pos[]=array(-$x,$b[1],-$z);
    $pos[]=array($z,$b[1],-$x);
   }
  }
  return $pos;
  unset($pos,$r,$i,$x,$z);
 }
}
?>