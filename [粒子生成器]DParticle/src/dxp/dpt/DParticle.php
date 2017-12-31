<?php
namespace dxp\dpt;

use pocketmine\event\Listener;
use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\BlockForceFieldParticle;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\EnchantmentTableParticle;
use pocketmine\level\particle\EnchantParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\particle\HugeExplodeSeedParticle;
use pocketmine\level\particle\InkParticle;
use pocketmine\level\particle\InstantEnchantParticle;
use pocketmine\level\particle\ItemBreakParticle;
use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\Particle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\RainSplashParticle;
use pocketmine\level\particle\RedstoneParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\SplashParticle;
use pocketmine\level\particle\SporeParticle;
use pocketmine\level\particle\TerrainParticle;
use pocketmine\level\particle\WaterDripParticle;
use pocketmine\level\particle\WaterParticle;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\CallbackTask;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;

use dxp\dpt\shape\Square;
use dxp\dpt\shape\Circle;
use dxp\dpt\shape\Circle1;
use dxp\dpt\shape\Circle2;
use dxp\dpt\shape\Round as Rd;
use dxp\dpt\shape\Round1;
use dxp\dpt\shape\Round2;
use dxp\dpt\shape\Ball;
use dxp\dpt\shape\Ball1;
use dxp\dpt\shape\Ball2;
use dxp\dpt\shape\Star;
use dxp\dpt\shape\Star1;
use dxp\dpt\shape\Random;
use dxp\dpt\shape\Ring;
use dxp\dpt\shape\Triangle;
use dxp\dpt\shape\Pentagon;
use dxp\dpt\shape\Hexagon;
use dxp\dpt\shape\Wing;

class DParticle extends PluginBase implements Listener{
 private static $obj = null;
 public static function getInstance(){
  return self::$obj;
 }
 public function onLoad(){
  $this->getServer()->getLogger()->info('§e粒子生成器正在加载...');
  $this->reLoad();
 }
 
 public function onEnable(){	
  $this->getServer()->getPluginManager()->registerEvents($this,$this);
  
  $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this,'taskManager']),1);
  
  $this->getServer()->getLogger()->info('§a粒子生成器加载完成!');
 }
 
 public function onDisable(){
  $this->p->save();
  $this->getServer()->getLogger()->info('§a粒子生成器卸载完成!');
 }
 
 public function reLoad(){
  if(!self::$obj instanceof DParticle){
   self::$obj = $this;
  }
  
  @mkdir($this->getDataFolder(),0777,true);
  $this->p=new Config($this->getDataFolder().'pos.json',Config::JSON,array());
  
  $this->shapes=array(
   //圆
   'Circle'=>new Circle($this),
   'Circle1'=>new Circle1($this),
   //正多边形
   'Triangle'=>new Triangle($this),
   'Square'=>new Square($this),
   'Pentagon'=>new Pentagon($this),
   'Hexagon'=>new Hexagon($this),
   //球体
   'Ball'=>new Ball($this),
   'Ball1'=>new Ball1($this),
   //五角星
   'Star'=>new Star($this),
   //环绕
   'Round'=>new Rd($this),
   'Round1'=>new Round1($this),
   'Round2'=>new Round2($this),
   //特殊
   'Random'=>new Random($this),
   'Wing'=>new Wing($this)
  );
  
  $this->color=array('r'=>255,'g'=>0,'b'=>0,'s'=>1);
  $this->cf=1;
  $this->h=array('h'=>0,'s'=>1,'h1'=>0,'s1'=>0,'maxh'=>4,'pos'=>array(),'pos'=>array(),'hh'=>0,'hh1'=>0);
  $this->r=0;
  $this->Timber=0;
  $this->d=0;
  $this->ro=0;
 }
 
 public function addPos($info){
  if(count($info)!==10){
   return false;
  }
  if(!isset($info['ID'])){
   return false;
  }
  if($this->p->exists($info['ID'])){
   return false;
  }
  $id=$info['ID'];
  $this->p->set($id,$info);
  $this->p->save();
  unset($id,$info);
  return true;
 }
 
 public function setPos($id,$type,$i){
  if(!$this->p->exists($id)){
   return false;
  }
  $info=$this->p->get($id);
  if(!isset($info[$type])){
   return false;
  }
  $info[$type]=$i;
  $this->p->set($id,$info);
  $this->p->save();
  unset($id,$type,$i);
  return true;
 }
 
 public function delPos($id){
  if(!$this->p->exists($id)){
   return false;
  }
  $this->p->remove($id);
  $this->p->save();
  unset($id);
  return true;
 }
 
 public function playParticle($info){
  if(!isset($info['Deg'])){
   $info['Deg']=0;
  }
  $name=$info['R'].$info['α'].$info['β'].$info['γ'].$info['Deg'];
  $obj=$this->shapes[$info['Shape']];
  if($obj==null){
   return false;
  }
  $data=$obj->run($info);
  if($data==null || $data[0]==null){
   return false;
  }
  $level=$this->getServer()->getLevelByName($info['Level']);
  if(!isset($level) || $level==null){
   return false;
  }
  $n=0;
  foreach($data[0] as $p){
   if(is_array($data[1]) && isset($data[1][$n])){
    $particle=$data[1][$n];
   }else{
    $particle=$info['Particle'];
   }
   $pt=$this->getParticle($particle,new Vector3($info['X']+$p[0],$info['Y']+$p[1],$info['Z']+$p[2]));
   if(!isset($pt)){
    return false;
   }
   $level->addParticle($pt);
   $n++;
  }
  unset($info,$n,$p,$data,$level,$pt,$particle);
  return true;
 }
 
 public function sendParticle(){
  foreach($this->p->getAll() as $info){
   switch($info['Shape']){
    case 'Square':
    case 'Circle':
    case 'Circle1':
    case 'Ball':
    case 'Ball2':
    case 'Ring':
    case 'Star':
     if($this->Timber==10){
      $this->playParticle($info);
     }
     break;
    default:
     $this->playParticle($info);
     break;
   }
  }
  unset($info);
 }
 
 public function getParticleList(){
  return array('Lava','Lavadrip','Waterdrip','Red','Orange','Yellow','Green','Blue','Green-blue','Purple','White','Pink','Colorful','Shadow','Redstone','Flame','Smoke','Enchant','HappyVillager','Portal','WhiteSmoke','AngryVillager');
 }
 
 public function getShapeList(){
  return array('Circle','Circle1','Round','Round1','Round2','Round3','Triangle','Square','Hexagon','Pentagon','Ball','Ball1','Ball2','Star','Star1','Wing','Ring','Random');
 }
  
 /*public function curve($info){
  //反比例函数双曲线算法
  $r=$info['R'];
  $level=$this->getServer()->getLevelByName($info['Level']);
  $pos=array();
  if($r==0){
   return;
  }
  for($i=0.1;$i<=$r;$i+=0.1){
   $x=$i;
   $z=$r/$x;
   $pos[]=new Vector3($info['X']+$x,$info['Y'],$info['Z']+$z);
   $pos[]=new Vector3($info['X']+$x,$info['Y'],$info['Z']-$z);
   $pos[]=new Vector3($info['X']-$x,$info['Y'],$info['Z']+$z);
   $pos[]=new Vector3($info['X']-$x,$info['Y'],$info['Z']-$z);
  }
  return $pos;
  unset($info,$r,$i,$level,$x,$z,$v3a,$v3b,$v3c,$v3d);
 }*/
 

 //直线算法
 public function line($info){
  $pos=array();
  $sd=pow($info['X']-$info['X1'],2)+pow($info['Y']-$info['Y1'],2)+pow($info['Z']-$info['Z1'],2);
  $dis=(int)sqrt($sd);
  for($t=0;$t<=1;$t+=(1/($dis))){
   $pos[]=array($info['X']+($info['X1']-$info['X'])*$t,$info['Y']+($info['Y1']-$info['Y'])*$t,$z=$info['Z']+($info['Z1']-$info['Z'])*$t);
  }
  return $pos;
 }

 public function arc($x,$y,$r,$b,$d1,$d2){
  //弧算法
  $pos=array();
  if($d2<$d1){
   $d2+=360;
  }
  for($ii=$d1;$ii<=$d2;$ii+=$b){
   if($ii>360){
    $i=($ii-360)%180;
   }else{
    $i=$ii%180;
   }
   $xx=$r*cos(deg2rad($i));
   $yy=$r*sin(deg2rad($i));
   if($ii<=180){
    $pos[]=array($x+$xx,$y+$yy);
   }elseif($ii<=360 && $ii>180){
    $pos[]=array($x-$xx,$y-$yy);
   }
  }
  return $pos;
  unset($x,$y,$r,$b,$d1,$d2,$ii,$i,$xx,$yy);
 }
 
 public function arc_port($x,$y,$r,$d){
  //弧取点算法
   $xx=$r*cos(deg2rad($d%180));
   $yy=$r*sin(deg2rad($d%180));
   if($d<=180){
    $pos=array($x+$xx,$y+$yy);
   }elseif($d<=360 && $d>180){
    $pos=array($x-$xx,$y-$yy);
   }
  return $pos;
  unset($x,$y,$r,$d,$xx,$yy);
 }
 
 public function curve_circle($info){
  //曲线圆算法
  $r=$info['R'];
  $pos=array();
  $r1=3/8*$r;
  $l=2*$r*sin(deg2rad(15));
  $deg=2*rad2deg(asin($l/2/$r1));
  
  
  //全部坐标算法
  for($i=0;$i<360;$i+=30){
   $x=cos(deg2rad($i%180));
   $z=sin(deg2rad($i%180));
   if($i>=180){
    $x=-$x;
    $z=-$z;
   }
   if($i%60==30){
    $pos1[$i/30]=$this->arc($info['X']+$r*5/4*$x,$info['Z']+$r*5/4*$z,$r1,1,180-0.5*$deg+$i,180+0.5*$deg+$i);
    $pos2=$pos1[$i/30];
    $pos1[$i/30]=array();
    for($ii=count($pos2)-1;$ii>=0;$ii--){
     $pos1[$i/30][]=$pos2[$ii];
    }
   }else{
    $pos1[$i/30]=$this->arc($info['X']+$r*3/4*$x,$info['Z']+$r*3/4*$z,$r1,1,$i,$i+$deg);
   }
  }
  //最终坐标算法
  /*$ro=$this->r;
  $a=(int)$ro/36;
  $b=$ro%36;
  $pos2=$pos1[$a];
  if(!isset($pos1[$a])){
   return null;
  }
  for($i=$b*$deg/36;$i<=($b+1)*$deg/36;$i+=1){
   $pos[]=array($pos1[$a][(int)$i][0],$info['Y'],$pos1[$a][(int)$i][1]);
  }*/
  foreach($pos1 as $p1){
   foreach($p1 as $p2){
    $pos[]=array($p2[0],$info['Y'],$p2[1]);
   }
  }
  return $pos;
  unset($info,$level,$v3a,$v3b,$v3c,$v3d,$i,$r,$x,$z);
 }
 
 public function star1($info){
  $pos=$this->star($info);
  $info1=$info;
  $info2=$info;
  $info3=$info;
  $info4=$info;
  $info5=$info;
  $info1['X']-=$info['R']*cos(18*pi()/180);
  $info1['Z']+=$info['R']*sin(18*pi()/180);
  
  $info2['X']+=$info['R']*cos(18*pi()/180);
  $info2['Z']+=$info['R']*sin(18*pi()/180);
  
  $info3['X']-=$info['R']*cos(54*pi()/180);
  $info3['Z']-=$info['R']*sin(54*pi()/180);
  
  $info4['X']+=$info['R']*cos(54*pi()/180);
  $info4['Z']-=$info['R']*sin(54*pi()/180);
  $info5['Z']+=$info['R'];
  $info1['R']=$info['R']*0.2;
  $info2['R']=$info['R']*0.2;
  $info3['R']=$info['R']*0.2;
  $info4['R']=$info['R']*0.2;
  $info5['R']=$info['R']*0.2;
  $pos=array_merge($pos,$this->circle($info1));
  $pos=array_merge($pos,$this->circle($info2));
  $pos=array_merge($pos,$this->circle($info3));
  $pos=array_merge($pos,$this->circle($info4));
  $pos=array_merge($pos,$this->circle($info5));
  return $pos;
 }
 
 public function star2($info){
  $this->star1($info);
  $this->circle($info);
  $info['R']=$info['R']*0.8;
  $this->circle($info);
 }
 
 public function star3($info){
  $r=$info['R'];
  $info1=$info;
  $pos=array();
  
  $pos=$this->circle($info1);
  $pos=array_merge($pos,$this->hexagon($info1));
  
  $a=$info1;
  $b=$info1;
  $c=$info1;
  $info1['R']=$r*0.9;
  $a['X']-=$info1['R']*sin(deg2rad(60));
  $a['Z']-=$info1['R']*cos(deg2rad(60));
  $b['X']+=$info1['R']*sin(deg2rad(60));
  $b['Z']-=$info1['R']*cos(deg2rad(60));
  $c['Z']+=$info1['R'];
  $a['R']=$r*0.3;
  $b['R']=$r*0.3;
  $c['R']=$r*0.3;
  $pos=array_merge($pos,$this->triangle($a));
  $pos=array_merge($pos,$this->triangle($b));
  $pos=array_merge($pos,$this->triangle($c));
  
  $info1['R']=$r*0.72;
  $pos=array_merge($pos,$this->circle($info1));
  $info1['R']=$r*0.7;
  $pos=array_merge($pos,$this->triangle($info1));
  $pos=array_merge($pos,$this->triangle1($info1));
  
  $a=$info1;
  $b=$info1;
  $c=$info1;
  $d=$info1;
  $e=$info1;
  $f=$info1;
  //算坐标
  $a['X']-=$info1['R']*cos(deg2rad(30));
  $a['Z']+=$info1['R']*sin(deg2rad(30));
  
  $b['X']+=$info1['R']*cos(deg2rad(30));
  $b['Z']+=$info1['R']*sin(deg2rad(30));
  
  $c['Z']-=$info1['R'];
  
$d['X']+=$info1['R']*cos(deg2rad(30));
  $d['Z']-=$info1['R']*sin(deg2rad(30));
  
  $e['X']-=$info1['R']*cos(deg2rad(30));
  $e['Z']-=$info1['R']*sin(deg2rad(30));
  
  $f['Z']+=$info1['R'];
  //修改半径
  $a['R']=$r*0.1;
  $b['R']=$r*0.1;
  $c['R']=$r*0.1;
  $d['R']=$r*0.1;
  $e['R']=$r*0.1;
  $f['R']=$r*0.1;
  //生成
  $pos=array_merge($pos,$this->circle1($a));
  $pos=array_merge($pos,$this->circle1($b));
  $pos=array_merge($pos,$this->circle1($c));
  $pos=array_merge($pos,$this->circle1($d));
  $pos=array_merge($pos,$this->circle1($e));
  $pos=array_merge($pos,$this->circle1($f));
  
  $info1['R']=$r*0.5;
  $pos=array_merge($pos,$this->triangle($info1));
  $pos=array_merge($pos,$this->triangle1($info1));
  return $pos;
 }
 
/* public function turn($a=0,$b=0,$c=0,$pos=array()){
  if($pos==array()){
   return null;
  }
  
  if($a<0){
   $rx=$this->ro;
  }elseif($a>=0 && $a<=180){
   $rx=$a;
  }
  if($b<0){
   $ry=$this->ro;
  }elseif($b>=0 && $b<=180){
   $ry=$b;
  }
  if($c<0){
   $rz=$this->ro;
  }elseif($c>=0 && $c<=180){
   $rz=$c;
  }
  if(!isset($rx) || !isset($ry) || !isset($rz)){
   return $pos;
  }
  
  $pos1=array();
  
  foreach($pos as $p){
   $x=$p[0]-$info['X'];
   $y=$p[1]-$info['Y'];
   $z=$p[2]-$info['Z'];
   $nx1=$x*cos(deg2rad($ry))-$z*sin(deg2rad($ry));
   $ny1=$z*sin(deg2rad($rx))+$y*cos(deg2rad($rx));
   $nz1=$z*cos(deg2rad($rx))-$y*sin(deg2rad($rx));
   $x=$nx1;
   $y=$ny1;
   $z=$nz1;
   $nx2=$x*cos(deg2rad($rz))-$y*sin(deg2rad($rz));
   $ny2=$x*sin(deg2rad($rz))+$y*cos(deg2rad($rz));
   $nz2=$x*sin(deg2rad($ry))+$z*cos(deg2rad($ry));
   $pos1[]=array($info['X']+$nx2,$info['Y']+$ny2,$info['Z']+$nz2);
  }
  return $pos1;
 }
 */
 public function Shadow(){
  switch($this->color['s']){
   case 1:
    $this->color['g']+=3;
    if($this->color['g']>=255){
     $this->color['g']=255;
     $this->color['s']=2;
    }
    break;
   case 2:
    $this->color['r']-=3;
    if($this->color['r']<=0){
     $this->color['r']=0;
     $this->color['s']=3;
    }
    break;
   case 3:
    $this->color['b']+=3;
    if($this->color['b']>=255){
     $this->color['b']=255;
     $this->color['s']=4;
    }
    break;
   case 4:
    $this->color['g']-=3;
    if($this->color['g']<=0){
     $this->color['g']=0;
     $this->color['s']=5;
    }
    break;
   case 5:
    $this->color['r']+=3;
    if($this->color['r']>=255){
     $this->color['r']=255;
     $this->color['s']=6;
    }
    break;
   case 6:
    $this->color['b']-=3;
    if($this->color['b']<=0){
     $this->color['b']=0;
     $this->color['s']=1;
    }
    break;
  }
 }
 
 public function getParticle($name,Vector3 $pos){
  switch($name){
   case 'Lava':
    return new LavaParticle($pos);
    break;
   case 'Bubble':
    return new BubbleParticle($pos);
    break;
   case 'Lavadrip':
    return new LavaDripParticle($pos);
    break;
   case 'Waterdrip':
    return new WaterDripParticle($pos);
    break;
   case 'Enchant':
    return new EnchantmentTableParticle($pos);
    break;
   case 'Flame':
    return new FlameParticle($pos);
    break;
   case 'Redstone':
    return new RedstoneParticle($pos);
    break;
   case 'Smoke':
    return new SmokeParticle($pos);
    break;
   case 'WhiteSmoke':
    return new WhiteSmokeParticle($pos);
    break;
   case 'Spell':
    return new SpellParticle($pos);
    break;
   case 'Splash':
    return new SplashParticle($pos);
    break;
   case 'Spore':
    return new SporeParticle($pos);
    break;
   case 'HappyVillager':
    return new HappyVillagerParticle($pos);
    break;
   case 'AngryVillager':
    return new AngryVillagerParticle($pos);
    break;
   case 'Heart':
    return new HeartParticle($pos);
    break;
   case 'Portal':
    return new PortalParticle($pos);
    break;
   case 1:
   case 'Red':
    return new DustParticle($pos,250,0,0,250);
    break;
   case 2:
   case 'Green':
    return new DustParticle($pos,0,250,0,250);
    break;
   case 3:
   case 'Blue':
    return new DustParticle($pos,0,0,250,250);
    break;
   case 4:
   case 'Yellow':
    return new DustParticle($pos,250,250,0,250);
    break;
   case 5:
   case 'Orange':
    return new DustParticle($pos,250,125,0,250);
    break;
   case 6:
   case 'Pink':
    return new DustParticle($pos,250,125,250,250);
    break;
   case 7:
   case 'Purple':
    return new DustParticle($pos,125,0,250,250);
    break;
   case 8:
   case 'White':
    return new DustParticle($pos,250,250,250,250);
    break;
   case 9:
   case 'Green-blue':
    return new DustParticle($pos,0,250,250,250);
    break;
   case 'Shadow':
    return new DustParticle($pos,$this->color['r'],$this->color['g'],$this->color['b'],255);
    break;
   case 'Colorful':
    return $this->getParticle($this->cf,$pos);
    break;
   default:
    return $this->getParticle('Red',$pos);
    break;
  }
  unset($name,$pos);
 }
  
 public function taskManager(){
  $this->sendParticle();
  if($this->Timber==10){
   $this->Timber=0;
  }
  $this->Timber++;
  if($this->r>=180){
   $this->r=0;
  }
  $this->r+=3;
  if($this->d==100){
   $this->d=0;
  }
  $this->d+=10;
  if($this->ro==180){
   $this->ro=0;
  }
  $this->ro+=1;
  $this->Height();
  $this->Shadow();
  $this->cf=rand(1,9);
 }
 
 public function Height(){
  if($this->h['pos']==array()){
   $r=$this->h['maxh']/2;
   $pos=array();
   $pos1=array();
   for($i=0;$i<90;$i+=5){
    $x=$r*cos(deg2rad($i));
    $xx=$r*cos(deg2rad($i+1));
    $pos[]=$x-$xx;
   }
   for($i=0;$i<=90;$i+=5){
    $z=$r*sin(deg2rad($i));
    if($i!=0){
     $zz=$r*sin(deg2rad($i-1));
    }else{
     $zz=0;
    }
    $pos[]=$z-$zz;
   }
   for($i=0;$i<count($pos);$i+=1){
    if($i%2==0){
     $pos1[]=$pos[$i];
    }
   }
   $this->h['pos']=$pos;
   $this->h['pos1']=$pos1;
  }
  switch($this->h['s']){
   case 1:
    $this->h['h']+=$this->h['pos'][$this->h['hh']];
    $this->h['hh']+=1;
    if($this->h['hh']>=count($this->h['pos'])){
     $this->h['s']=2;
     $this->h['hh']-=1;
    }
    break;
   case 2:
    $this->h['h']-=$this->h['pos'][$this->h['hh']];
    $this->h['hh']-=1;
    if($this->h['hh']<0){
     $this->h['s']=1;
     $this->h['hh']=0;
    }
    if($this->h['h']<=$this->h['maxh']/2 && $this->h['s1']==0){
     $this->h['s1']=1;
     $this->h['h1']=0;
     $this->h['hh1']=0;
    }
    break;
  }
  
  switch($this->h['s1']){
   case 1:
    $this->h['h1']+=$this->h['pos1'][$this->h['hh1']];
    $this->h['hh1']+=1;
    if($this->h['hh1']>=count($this->h['pos1'])){
     $this->h['s1']=2;
     $this->h['hh1']-=1;
    }
   break;
   case 2:
    $this->h['h1']-=$this->h['pos1'][$this->h['hh1']];
    $this->h['hh1']-=1;
    if($this->h['hh1']<0){
     $this->h['s1']=0;
    }
   break;
  }
 }
}
?>
