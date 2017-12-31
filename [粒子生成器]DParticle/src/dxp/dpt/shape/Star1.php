<?php
namespace dxp\dpt\shape;

use dxp\dpt\DParticle;

class Star1 extends ShapeBase{	

 public function run(){
  //五角星阵算法
  $pos=array();
  $r=$this->r;
  $pl=$this->pl;
  $info=$this->info;
  
  $info['α']=0;
  $info['β']=0;
  $info['γ']=0;
  $o=new Star($info,$pl);
  $pos=$o->pos;
  $info1=$info;
  $info1['R']=$r*0.2;
  $oo=new Circle($info1,$pl);
  $poss=$oo->pos;
  foreach($poss as $p){
   $pp=$p;
   $pp[0]=$p[0]-$r*cos(deg2rad(18));
   $pp[2]=$p[2]+$r*sin(deg2rad(18));
   $pos[]=$pp;
   
   $pp[0]=$p[0]+$r*cos(deg2rad(18));
   $pp[2]=$p[2]+$r*sin(deg2rad(18));
   $pos[]=$pp;
   
   $pp[0]=$p[0]-$r*cos(deg2rad(54));
   $pp[2]=$p[2]-$r*sin(deg2rad(54));
   $pos[]=$pp;
   
   $pp[0]=$p[0]+$r*cos(deg2rad(54));
   $pp[2]=$p[2]-$r*sin(deg2rad(54));
   $pos[]=$pp;
   
   $pp[0]=$p[0];
   $pp[2]=$p[2]+$r; 
   $pos[]=$pp;
  }
  return $pos;
  unset($pos,$r,$pl,$info,$info1,$poss,$p,$pp);
 }
}
?>