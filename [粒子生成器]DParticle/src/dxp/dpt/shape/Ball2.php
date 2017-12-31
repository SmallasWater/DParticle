<?php
namespace dxp\dpt\shape;

class Ball2 extends ShapeBase{
 public static function getVector3($r,$data){
  //高密圆算法
  $pos=array();
  $info=$this->info;
  
  $info['α']=0;
  $info['β']=0;
  $info['γ']=0;
  $obj=new Circle($info,$pl);
  $pos=$obj->pos;
  unset($obj);
  $pos1=array_merge($pos,$this->turn(90,0,0,$pos));
  $pos1=array_merge($pos1,$this->turn(0,0,90,$pos));
  return $pos1;
  unset($pos,$r,$pl,$info,$obj);
 }
}
?>