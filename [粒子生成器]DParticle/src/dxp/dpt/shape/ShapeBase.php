<?php
namespace dxp\dpt\shape;

use dxp\dpt\DParticle;

class ShapeBase{
 public function __construct(DParticle $plugin){
  $this->cache=array();
  $this->cachetime=array();
  $this->plugin=$plugin;
 }
 
 protected function run(Array $info){
  $name=$info['R'].$info['α'].$info['β'].$info['γ'].$info['Deg'];
  if(isset($this->cache[$name])){
   if($this->cachetime[$name]<=0){
    $data=$this->cache[$name];
    unset($this->cache[$name],$this->cachetime[$name]);
   }else{
    $data=$this->cache[$name];
   }
  }else{
   if(!isset($info['Deg'])){
    $info['Deg']=0;
   }
   $deg=$info['Deg'];
   if($deg<0){
    $deg=$this->plugin->r;
   }
   if($info['Shape']=='Wing'){
    $v3pos=$this->getVector3($info['R'],$info['Wing']);
    $this->cache[$name]=$data=array($this->turn($info['α'],$info['β'],$info['γ'],$v3pos),$this->getParticleMap($info['R'],$info['Wing']));
    $this->cachetime[$name]=12000;
   }elseif(in_array($info['Shape'],array('Random'))){
    $v3pos=$this->getVector3($info['R'],$deg);
    $data=array($this->turn($info['α'],$info['β'],$info['γ'],$v3pos),$this->getParticleMap($info['R'],$info['Deg']));
   }else{
    $v3pos=$this->getVector3($info['R'],$deg);
    $this->cache[$name]=$data=array($this->turn($info['α'],$info['β'],$info['γ'],$v3pos),$this->getParticleMap($info['R'],$info['Deg']));
    $this->cachetime[$name]=12000;
   }
  }
  return $data;
 }
 
 public static function getVector3($r,Array $data){
  return null;
 }
 
 public static function turn($a=0,$b=0,$c=0,$pos=array()){
  if($pos==array()){
   return $pos;
  }
  $k=array(array(1,1),array(1,1),array(1,1));
  $deg=array($a,$b,$c);
  
  if($deg[0]==0 && $deg[1]==0 && $deg[2]==0){
   return $pos;
  }
  
  if($a>180){
   $deg[0]-=180;
   $k[1][0]=-1;
   $k[2][0]=-1;
  }
  if($b>180){
   $deg[1]-=180;
   $k[0][0]=-1;
   $k[2][1]=-1;
  }
  if($c>180){
   $deg[2]-=180;
   $k[0][1]=-1;
   $k[1][1]=-1;
  }
  
  $pos1=array();
  
  foreach($pos as $p){
   $x=$p[0];
   $y=$p[1];
   $z=$p[2];
   $yy=$k[1][0]*($y*cos(deg2rad($deg[0]))+$z*sin(deg2rad($deg[0])));
   $zz=$k[2][0]*($z*cos(deg2rad($deg[0]))-$y*sin(deg2rad($deg[0])));
   $y=$yy;
   $z=$zz;
   $zz=$k[2][1]*($z*cos(deg2rad($deg[1]))+$x*sin(deg2rad($deg[1])));
   $xx=$k[0][0]*($x*cos(deg2rad($deg[1]))-$z*sin(deg2rad($deg[1])));
   $x=$xx;
   $z=$zz;
   
   $xx=$k[0][1]*($x*cos(deg2rad($deg[2]))+$y*sin(deg2rad($deg[2])));
   $yy=$k[1][1]*($y*cos(deg2rad($deg[2]))-$x*sin(deg2rad($deg[2])));
   $pos1[]=array($xx,$yy,$zz);
  }
  unset($x,$y,$z,$nx,$ny,$nz,$deg,$a,$b,$c,$k,$pl,$pos);
  return $pos1;
  
 }
 
 public static function getParticleMap($r,$deg){
  return array();
 }
 
 public function taskManager(){
  foreach($this->cachetime as $cache=>$time){
   if($time>0){
    $time--;
    $this->cachetime[$cache]=$time;
   }else{
    unset($this->cachetime[$cache]);
   }
  }
 }
}
?>