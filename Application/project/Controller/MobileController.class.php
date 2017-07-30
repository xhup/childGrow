<?php
/**
 * Created by PhpStorm.
 * User: XH
 * Date: 2017/1/13
 * Time: 14:30
 */

namespace project\Controller;
use Think\Controller;

class MobileController extends Controller
{
    /*手机端用户注册后台*/
        public function register()
        {
            $userAccount=I("post.userAccount");
            $userPassword=I("post.userPassword");
            $userName=I("post.userName");
//            $userIcon=I("post.userIcon");
            $childID=I("post.childID");
            $childName=I("post.childName");
//            $childIcon=I("post.childIcon");
            $childBirthdate=I("post.childBirthdate");
            $childSex=I("post.childSex");
            $birthCity=I("post.birthCity");
            $bearingAge=I("post.bearingAge");
            $fullMonth=I("post.fullMonth");
            $fatherHeight=I("post.fatherHeight");
            $motherHeight=I("post.motherHeight");
            $birthHeight=I("post.birthHeight");
            $birthWeight=I("post.birthWeight");
            $birthHeadc=I("post.birthHeadc");
            $table1=M("user");//用户信息表
            $table2=M("childBaseInfo");//儿童信息表
            $table3=M("measureData");//儿童数据表
            $check1=$table1->where("userAccount='$userAccount'")->find();
            if($check1){
                $back=array("flag"=>2);//返回2表示注册账号已存在
            }else{
                //建立用户基本信息
                $condition1["userAccount"]=$userAccount;
                $condition1["userPassword"]=md5(md5($userPassword));
                $condition1["userName"]=$userName;
//                $condition1["userIcon"]=$userIcon;
                $result1=$table1->add($condition1);
                //建立默认儿童的基本信息
                $condition2["userAccount"]=$userAccount;
                $condition2["childID"]=$childID;
                $condition2["childName"]=$childName;
//                $condition2["childIcon"]=$childIcon;
                $condition2["childBirthdate"]=$childBirthdate;
                $condition2["childSex"]=$childSex;
                $condition2["birthCity"]=$birthCity;
                $condition2["bearingAge"]=$bearingAge;
                $condition2["fullMonth"]=$fullMonth;
                $condition2["fatherHeight"]=$fatherHeight;
                $condition2["motherHeight"]=$motherHeight;
                $check2=$table2->where("childID='$childID'")->find();
                //在儿童数据表中添加出生时的初始数据
                $condition3["childID"]=$childID;
                $condition3["userAccount"]=$userAccount;
                $condition3["childHeight"]=$birthHeight;
                $condition3["childWeight"]=$birthWeight;
                $condition3["childHeadc"]=$birthHeadc;
                $condition3["childBMI"]=$birthWeight/(($birthHeight/100)*($birthHeight/100));
                $condition3["childBirthdate"]=$childBirthdate;
                $condition3["importTime"]=$childBirthdate;
                $condition3["childAge"]=1;
                if($check2){
                    $back=array("flag"=>3);//返回3表示儿童账号已存在
                }else{
                    $result3=$table3->add($condition3);
                    $result2=$table2->add($condition2);
                    if($result1&&$result2&&$result3){
                        $back=array("flag"=>1);//返回1表示注册成功
                    }else{
                        $back=array("flag"=>0);//返回0表示注册失败
                    }
                }

            }
            $this->ajaxReturn($back,"json");//将结果json形式返回给前端
         }

    /*孕妇用户注册后台*/
         public function pregRegister(){
            $userAccount=I("post.userAccount");
            $userPassword=I("post.userPassword");
            $userName=I("post.userName");
//          $Icon=I("post.icon");
            $pregnantTime=I("post.pregnantTime");
            $expectedChildbirth=I("post.expectedChildbirth");
            $fatherHeight=I("post.fatherHeight");
            $motherHeight=I("post.motherHeight");
            $sex=I("post.sex");
            $birthdate=I("post.birthdate");
            $birthHeight=I("post.birthHeight");
            $birthWeight=I("post.birthWeight");
            $birthHeadc=I("post.birthHeadc");
            $normalWeight=I("post.normalWeight");
            $table1=M("user");//用户信息表
            $table2=M("pregBaseInfo");//孕妇信息表
            $check1=$table1->where("userAccount='$userAccount'")->find();
            if($check1){
                $back=array("flag"=>2);//返回2表示注册账号已存在
            }else{
                //建立用户基本信息
                $condition1["userAccount"]=$userAccount;
                $condition1["userPassword"]=md5(md5($userPassword));
                $condition1["userName"]=$userName;
//                $condition1["userIcon"]=$userIcon;
                $result1=$table1->add($condition1);
                //建立孕妇的基本信息
                $condition2["name"]=$name;
                $condition2["pregnantTime"]=$pregnantTime;
                $condition2["expectedChildbirth"]=$expectedChildbirth;
                $condition2["fatherHeight"]=$fatherHeight;
//                $condition2["icon"]=$icon;
                $condition2["motherHeight"]=$motherHeight;
                $condition2["sex"]=$sex;
                $condition2["birthdate"]=$birthdate;
                $condition2["birthHeight"]=$birthHeight;
                $condition2["birthWeight"]=$birthWeight;
                $condition2["birthHeadc"]=$birthHeadc;
                $condition2["normalWeight"]=$normalWeight;

                $check2=$table2->where("userAccount='$userAccount'")->find();
               if($check2){
                    $back=array("flag"=>3);//返回3表示孕妇信息已存在
                }else{
                     $result2=$table2->add($condition2);
                    if($result1&&$result2){
                        $back=array("flag"=>1);//返回1表示注册成功
                      }else{
                        $back=array("flag"=>0);//返回0表示注册失败
                    }
                }
            }
             $this->ajaxReturn($back,"json");//将结果json形式返回给前端
        }

    /*手机端用户登陆后台*/
        public function login(){
            $userAccount=I("post.userAccount");
            $userPassword=I("post.userPassword");
            $table=M("user");
            $condition["userAccount"]=$userAccount;
            $condition["userPassword"]=md5(md5($userPassword));
            $check=$table->where($condition)->find();
            $time=date('y-m-d h:i:s',time());//获取当前时间
            $salt="qazwsx";//token的加盐保护
            if($check){
                $token=md5($userAccount.$userPassword.$time.md5($salt));//返回给手机端的token，供之后使用
                $_SESSION["currentLogin"]=$token;//服务器端将token存到session中供之后验证
                $infoTable=M("childBaseInfo");
                $infoQuery=$infoTable->where("userAccount='$userAccount'")->getField("childID,childIcon,childName,childBirthdate,childSex,birthCity,fatherHeight,motherHeight,fullMonth,bearingAge");//查询出该手机用户关联的儿童的信息
                $dataTable=M("measureData");
                $dataQuery=$dataTable->where("userAccount='$userAccount'")->getField("importTime,childID,childHeight,childWeight,childHeadc,childBMI");//查询出该手机用户关联的儿童账号的身高体重头围BMI信息
                $back=array("flag"=>1,"token"=>$token,"info"=>$infoQuery,"data"=>$dataQuery);//返回1表示登录成功,token:后续访问令牌,info:儿童基本信息，data:身高体重头围BMI数据
            }else{
                $back=array("flag"=>0);//返回0表示用户名或密码错误
            }
            $this->ajaxReturn($back,"json");
        }

        /*手机端身高、体重、头围、BMI的添加后台*/
        public function dataAdd(){
            $token=I("post.token");//获取身份令牌
            $parm=I("post.parm");//参数判断
            $data=I("post.data");
            $userAccount=I("post.userAccount");
            $childID=I("post.childID");
            $importTime=I("post.importTime");
            $childBirthdate=I("post.childBirthdate");
//            $time=date("Y-m-d",time());
            $table=M("measureData");
            $condition["importTime"]=$importTime;//上传数据的日期
            $condition["userAccount"]=$userAccount;//用户账号
            $condition["childID"]=$childID;//对应的儿童ID
            $condition["childBirthdate"]=$childBirthdate;//对应的儿童的出生日期，后续同年龄段数据排序要用
            $condition["childAge"]=$this->timeInterval($childBirthdate,$importTime);//对应的儿童的年龄，以天为单位
            if($token==$_SESSION["currentLogin"]){
                if($parm&&$data&&$userAccount&&$childID&&$importTime&&$childBirthdate){
                    switch($parm){
                        case "height":{
                            $condition["childHeight"]=$data;
                            break;
                        }
                        case "weight":{
                            $condition["childWeight"]=$data;
                            break;
                        }
                        case "headc":{
                            $condition["childHeadc"]=$data;
                            break;
                        }
                        case "bmi":{
                            $condition["childBMI"]=$data;
                            break;
                        }
                    };
                    $result=$table->add($condition);//将手机端用户需要添加的数据存入数据库
                    if($result){
                        $back=array("flag"=>1);//返回1表示添加成功
                    }else{
                        $back=array("flag"=>0);//返回0表示添加失败
                    }
                }else{
                    $back=array("flag"=>2);//返回0表示接受到用户数据有空值
                }
            }else{
                $back=array("flag"=>4);//返回4表示token不正确或者过期，需要客户端重新登录
            }
            $this->ajaxReturn($back, "json");
        }

    /*手机端身高、体重、头围、BMI的修改后台*/
    public function dataEdit(){
        $token=I("post.token");//获取身份令牌
        $parm=I("post.parm");
        $data=I("post.data");
        $importTime=I("post.importTime");
        $childID=I("post.childID");
//        $time=date("Y-m-d",time());
        $table=M("measureData");
        if($token==$_SESSION["currentLogin"]) {
            if ($parm && $data && $importTime && $childID) {
                switch ($parm) {
                    case "height": {
                        $condition["childHeight"] = $data;
                        break;
                    }
                    case "weight": {
                        $condition["childWeight"] = $data;
                        break;
                    }
                    case "headc": {
                        $condition["childHeadc"] = $data;
                        break;
                    }
                    case "bmi": {
                        $condition["childBMI"] = $data;
                        break;
                    }
                };
                $result = $table->where("childID='$childID'and importTime='$importTime'")->save($condition);//将手机端用户需要修改的数据更新数据库
                if ($result!==false) {//更新成功返回发生变化的行数，更新失败会返回false，所以如果数据未发生变化会返回0，因此这里用false判断
                    $back = array("flag" => 1);//返回1表示修改成功
                } else {
                    $back = array("flag" => 0);//返回0表示修改失败
                }
            } else {
                $back = array("flag" => 2);//返回0表示收到的数据有空值
            }
        }else{
            $back=array("flag"=>4);//返回4表示token不正确或者过期，需要客户端重新登录
        }
        $this->ajaxReturn($back, "json");
    }


        /*手机端身高、体重、头围、BMI的删除后台*/
        public function dataDelete()
        {
            $token=I("post.token");//获取身份令牌
            $childID=I("post.childID");
            $importTime=I("post.importTime");
            $table=M("measureData");
            if($token==$_SESSION["currentLogin"]) {
                if ($childID && $importTime) {
                    $result = $table->where("childID='$childID'and importTime='$importTime'")->delete();//将手机端用户需要删除的数据进行删除
                    if ($result) {
                        $back = array("flag" => 1);//返回1表示删除成功
                    } else {
                        $back = array("flag" => 0);//返回0表示删除失败
                    }
                } else {
                    $back = array("flag" => 2);//返回2表示收到的数据有空值
                }
            }else{
                $back=array("flag"=>4);//返回4表示token不正确或者过期，需要客户端重新登录
            }
            $this->ajaxReturn($back,"json");
        }
        /*手机端儿童基本信息修改*/
        public function childInfoEdit(){
            $token=I("post.token");//获取身份令牌
            $childID=I("post.childID");
            $childName=I("post.childName");
//            $childIcon=I("post.childIcon");
            $childBirthdate=I("post.childBirthdate");
            $childSex=I("post.childSex");
            $table=M("childBaseInfo");
            if($token==$_SESSION["currentLogin"]) {
                $condition["childName"]=$childName;
//                $condition["childIcon"]=$childIcon;
                $condition["childBirthdate"]=$childBirthdate;
                $condition["childSex"]=$childSex;
                $result=$table->where("childID=$childID")->save($condition);
                if($result!==false){
                    $back=array("flag"=>1);//返回1表示修改成功
                }else {
                    $back = array("flag" => 0);//返回0表示修改失败
                }
            }else{
              $back=array("flag"=>4);//返回4表示token不正确或者过期，需要客户端重新登录
            }
            $this->ajaxReturn($back,"json");
        }

        /*手机端用户新增名下的儿童账号*/
        public function addChildID(){
            $token=I("post.token");//获取身份令牌
            $childID=I("post.childID");
            $userAccount=I("post.userAccount");
            $childName=I("post.childName");
//            $childIcon=I("post.childIcon");
            $childBirthdate=I("post.childBirthdate");
            $childSex=I("post.childSex");
            $birthCity=I("post.birthCity");
            $bearingAge=I("post.bearingAge");
            $fullMonth=I("post.fullMonth");
            $fatherHeight=I("post.fatherHeight");
            $motherHeight=I("post.motherHeight");
            $table1=M("childBaseInfo");
            $table2=M("measureData");
            if($token==$_SESSION["currentLogin"]) {
                $check = $table1->where("childID='$childID'")->find();
                if ($check) {
                    $back = array("flag" => 2);//返回2表示新增的儿童ID已存在
                } else {
                    //建立儿童ID基本信息
                    $condition["userAccount"]=$userAccount;
                    $condition["childID"]=$childID;
                    $condition["childName"]=$childName;
//                $condition["childIcon"]=$childIcon;
                    $condition["childBirthdate"]=$childBirthdate;
                    $condition["childSex"]=$childSex;
                    $condition["birthCity"]=$birthCity;
                    $condition["bearingAge"]=$bearingAge;
                    $condition["fullMonth"]=$fullMonth;
                    $condition["fatherHeight"]=$fatherHeight;
                    $condition["motherHeight"]=$motherHeight;
                    $birthHeight=I("post.birthHeight");
                    $birthWeight=I("post.birthWeight");
                    $birthHeadc=I("post.birthHeadc");
                    //在儿童数据表中添加出生时的初始数据
                    $condition2["childID"]=$childID;
                    $condition2["userAccount"]=$userAccount;
                    $condition2["childHeight"]=$birthHeight;
                    $condition2["childWeight"]=$birthWeight;
                    $condition2["childHeadc"]=$birthHeadc;
                    $condition2["childBMI"]=$birthWeight/(($birthHeight/100)*($birthHeight/100));
                    $condition2["childBirthdate"]=$childBirthdate;
                    $condition2["importTime"]=$childBirthdate;
                    $condition2["childAge"]=1;
                    $result1=$table1->add($condition);
                    $result2=$table2->add($condition2);
                    if($result1&&$result2){
                        $back=array("flag"=>1);//返回1表示新增儿童ID成功
                    }else{
                        $back=array("flag"=>0);//返回0表示新增失败
                    }
                }
            }else{
                $back=array("flag"=>4);//返回4表示token不正确或者过期，需要客户端重新登录
            }
            $this->ajaxReturn($back,"json");
        }

      /*手机端用户删除名下的儿童账号*/
       public function deleteChildID(){
           $token=I("post.token");//获取身份令牌
           $childID=I("post.childID");
           $table1=M("childBaseInfo");
           $table2=M("measureData");
           if($token==$_SESSION["currentLogin"]) {
               if($childID){
                   $result1=$table1->where("childID='$childID'")->delete();//删除儿童ID基本信息
                   $result2=$table2->where("childID='$childID'")->delete();//删除儿童ID测量数据
                   if($result1&&$result2){
                       $back=array("flag"=>1);//返回1表示删除成功
                   }else{
                       $back=array("flag"=>0);//返回0表示删除失败
                   }
               }else{
                   $back=array("flag"=>2);//返回2表示收到的儿童ID为空
               }
           }else{
               $back=array("flag"=>4);//返回4表示token不正确或者过期，需要客户端重新登录
           }
           $this->ajaxReturn($back,"json");
       }
    /*手机端儿童身高体重头围BMI在同龄儿童中的排序*/
       public function childDataOrder(){
           $childID=I("post.childID");
           $childBirthdate=I("post.childBirthdate");
           $latestDate=I("post.latestDate");
           $token=I("post.token");//获取身份令牌
           if($token==$_SESSION["currentLogin"]) {
               if($childID&&$childBirthdate){
                   $table=M("measureData");
                   $childAge=$this->timeInterval($childBirthdate,$latestDate);
                   $latestData=$table->where("importTime='$latestDate'and childID='$childID'")->getField("childAge,childHeight,childWeight,childHeadc,childBMI");
                   $choiceHeight=$latestData[$childAge]['childheight'];//从第一次查询的儿童数据中分别提取出身高、体重、头围、BMI
                   $choiceWeight=$latestData[$childAge]['childweight'];
                   $choiceHeadc=$latestData[$childAge]['childheadc'];
                   $choiceBMI=$latestData[$childAge]['childbmi'];

                   $allSuitChild=$table->where("childAge-'$childAge' between -180 and 180")->count();//适龄儿童总数

                   $resultHeight=$table->where("(childAge-'$childAge' between -180 and 180) and childHeight > '$choiceHeight'")->count();//数据的排名
                   $resultWeight=$table->where("(childAge-'$childAge' between -180 and 180) and childWeight > '$choiceWeight'")->count();
                   $resultHeadc=$table->where("(childAge-'$childAge' between -180 and 180) and childHeadc > '$choiceHeadc'")->count();
                   $resultBMI=$table->where("(childAge-'$childAge' between -180 and 180) and childBMI > '$choiceBMI'")->count();

                   $back=array("flag"=>1,"heightRand"=>($allSuitChild-($resultHeight+1))/$allSuitChild,"weightRand"=>($allSuitChild-($resultWeight+1))/$allSuitChild,"headcRand"=>($allSuitChild-($resultHeadc+1))/$allSuitChild,"bmiRand"=>($allSuitChild-($resultBMI+1))/$allSuitChild);
//                     $back=array("flag"=>1,"data"=>$latestData[$childAge]['childheight']);
               }else{
                   $back=array("flag"=>2);//返回2表示收到的儿童ID或者出生日期存在空值
               }
           }else{
               $back=array("flag"=>4);//返回4表示token不正确或者过期，需要客户端重新登录
           }
           $this->ajaxReturn($back,"json");
       }
    /*时间中的年月替换为-*/
    private function timeReplace(){
//        mb_regex_encoding('utf-8');//设置正则替换所用到的编码
//        return mb_ereg_replace('[^0-9]', '-', $parm);
        echo date('Y-m-d', strtotime('2016年05月29日'));
    }
    /*计算两个时间值之间的间隔*/
    private function timeInterval($str1,$str2){
        $year=substr($str2,0,4)-substr($str1,0,4);
        $month=substr($str2,5,2)-substr($str1,5,2);
        $day=substr($str2,8,2)-substr($str1,8,2);
        return $year*365+$month*30+$day;
    }

//    public function setUserIcon()
//    {
//        //隐藏输出的notice和waring信息
//        error_reporting(E_ALL ^ E_NOTICE);
//        error_reporting(E_ALL ^ E_WARNING);
//        $userAccount = $_SESSION["medicalID"];
//        $dataCheck = A("DataCheck");
//        define("fileStore", "Public/image/userIcon");//文件存储的主目录(在thinkphp框架中位置和后面的数据库存储地址不一样。无语)
//        date_default_timezone_set("PRC");
//        //设置允许上传的文件的格式
//        //$imageCount = count($_FILES['photo']['name']);//获取上传图片的数量
//        //for($i=0;$i<$imageCount;$i++){
//        if (is_uploaded_file($_FILES["photo"]["tmp_name"])) {
//
//            if (($_FILES["photo"]["type"] == "image/gif")
//                || ($_FILES["photo"]["type"] == "image/jpeg")
//                || ($_FILES["photo"]["type"] == "image/pjpeg")
//            ) {
//                //设置允许上传的文件的大小
//                if (($_FILES["photo"]["size"] < 500000)) {
//                    chdir(fileStore);
//                    $mainPath = $medicalID;
//                    $path = date('Ymd', time());
//                    if (!is_dir($mainPath)) { //按用户名创建主文件夹
//                        mkdir($mainPath);
//                    }
//                    chdir($mainPath);
//                    if (!is_dir($path)) { //按日期创建图片存储文件夹，若不存在，先生成文件夹（这里有个问题，因为有两个上传框，每次第二张图片仍然会判断成目录不存在）
//                        mkdir($path);
//                    }
//                    chdir($path);
//                    $date = date("-H-i-s.");
//                    $photoType = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION); //获取图片后缀名
//                    //  $photoType=substr($_FILES["photo"]["name"],-3);//获取图片后缀名
//                    $photoNameSave = $path . $date . $photoType;//重新按上传时间生成图片名
//                    $result = move_uploaded_file($_FILES["photo"]["tmp_name"], $photoNameSave);
//
//
//                    //若图片上传成功，则把图片url保存在数据库中，方便查看
//                    if ($result == 1) {
//                        //echo "图片已经成功上传!";
//                        $photoURL = "../../public/others/teenagerGrow/userUploadPhoto/" . $mainPath . "/" . $path . "/" . $photoNameSave; //数据库存储的图片的相对路径
//                        $photoSavePlace=fileStore."/". $mainPath . "/" . $path . "/" . $photoNameSave;
//                        $conn = $dataCheck->data_connect();
//                        if (!$conn) {
//                            E('Could not connect to database.');
//                        }
//
//                        mysqli_query($conn, "SET NAMES 'utf8'");//设置数据库数据的编码，不加这一句中文字符无法正常显示
//                        $mess = "insert into tg_child_image (account,imageUrl,uploadTime,imageSavePlace) VALUES ('$medicalID','$photoURL','$path','$photoSavePlace')";
//                        $last = mysqli_query($conn, $mess);
//                        if (!$last) {
//                            E('Could not register you in database - please try again later.');
//                        } else {
//                            $this->success("图片已经成功上传");
//                        }
//                    }
//
//                } else {
//                    $this->error("上传失败，文件大小超过限制");
//                }
//
//            } else {
//                $this->error("上传图片的格式错误，请选择正确的文件格式");
//            }
//        }
//        if ($_FILES["photo"]["error"] > 0) {
//            $this->error("上传图片出错，请重试");
//        }
//    }

    /*用户退出登录*/
    public function loginOut(){
         session("[start]");
         session("currentLogin",null);
         session("[destroy]");
        }


}