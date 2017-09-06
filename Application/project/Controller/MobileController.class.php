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
    /*�ֻ����û�ע���̨*/
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
            $table1=M("user");//�û���Ϣ��
            $table2=M("childBaseInfo");//��ͯ��Ϣ��
            $table3=M("measureData");//��ͯ���ݱ�
            $check1=$table1->where("userAccount='$userAccount'")->find();
            if($check1){
                $back=array("flag"=>2);//����2��ʾע���˺��Ѵ���
            }else{
                //�����û�������Ϣ
                $condition1["userAccount"]=$userAccount;
                $condition1["userPassword"]=md5(md5($userPassword));
                $condition1["userName"]=$userName;
//                $condition1["userIcon"]=$userIcon;
                $result1=$table1->add($condition1);
                //����Ĭ�϶�ͯ�Ļ�����Ϣ
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
                //�ڶ�ͯ���ݱ�����ӳ���ʱ�ĳ�ʼ����
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
                    $back=array("flag"=>3);//����3��ʾ��ͯ�˺��Ѵ���
                }else{
                    $result3=$table3->add($condition3);
                    $result2=$table2->add($condition2);
                    if($result1&&$result2&&$result3){
                        $back=array("flag"=>1);//����1��ʾע��ɹ�
                    }else{
                        $back=array("flag"=>0);//����0��ʾע��ʧ��
                    }
                }

            }
            $this->ajaxReturn($back,"json");//�����json��ʽ���ظ�ǰ��
         }

    /*�и��û�ע���̨*/
         public function pregRegister(){
            $userAccount=I("post.userAccount");
            $userPassword=I("post.userPassword");
            $userName=I("post.userName");
//          $Icon=I("post.icon");
            $name=I("post.name");
            $pregnantTime=I("post.pregnantTime");
            $expectedChildbirth=I("post.expectedChildbirth");
            $fatherHeight=I("post.fatherHeight");
            $motherHeight=I("post.motherHeight");
            $normalWeight=I("post.normalWeight");
            $table1=M("user");//�û���Ϣ��
            $table2=M("pregBaseInfo");//�и���Ϣ��
            $check1=$table1->where("userAccount='$userAccount'")->find();
            if($check1){
                $back=array("flag"=>2);//����2��ʾע���˺��Ѵ���
            }else{
                //�����û�������Ϣ
                $condition1["userAccount"]=$userAccount;
                $condition1["userPassword"]=md5(md5($userPassword));
                $condition1["userName"]=$userName;
//                $condition1["userIcon"]=$userIcon;
                $result1=$table1->add($condition1);
                //�����и��Ļ�����Ϣ
                $condition2["userAccount"]=$userAccount;
                $condition2["name"]=$name;
                $condition2["pregnantTime"]=$pregnantTime;
                $condition2["expectedChildbirth"]=$expectedChildbirth;
                $condition2["fatherHeight"]=$fatherHeight;
//                $condition2["icon"]=$icon;
                $condition2["motherHeight"]=$motherHeight;
                $condition2["normalWeight"]=$normalWeight;

                $check2=$table2->where("userAccount='$userAccount'")->find();
               if($check2){
                    $back=array("flag"=>3);//����3��ʾ�и���Ϣ�Ѵ���
                }else{
                     $result2=$table2->add($condition2);
                    if($result1&&$result2){
                        $back=array("flag"=>1);//����1��ʾע��ɹ�
                      }else{
                        $back=array("flag"=>0);//����0��ʾע��ʧ��
                    }
                }
            }
             $this->ajaxReturn($back,"json");//�����json��ʽ���ظ�ǰ��
        }

    /*�ֻ����û���½��̨*/
        public function login(){
            $userAccount=I("post.userAccount");
            $userPassword=I("post.userPassword");
            $table=M("user");
            $condition["userAccount"]=$userAccount;
            $condition["userPassword"]=md5(md5($userPassword));
            $check=$table->where($condition)->find();
            $time=date('y-m-d h:i:s',time());//��ȡ��ǰʱ��
            $salt="qazwsx";//token�ļ��α���
            if($check){
                $token=md5($userAccount.$userPassword.$time.md5($salt));//���ظ��ֻ��˵�token����֮��ʹ��
                $_SESSION["currentLogin"]=$token;//�������˽�token�浽session�й�֮����֤
                $infoTable1=M("childBaseInfo");
                $infoQuery1=$infoTable1->where("userAccount='$userAccount'")->getField("childID,childIcon,childName,childBirthdate,childSex,birthCity,fatherHeight,motherHeight,fullMonth,bearingAge");//��ѯ�����ֻ��û������Ķ�ͯ����Ϣ
                $dataTable1=M("measureData");
                $dataQuery1=$dataTable1->where("userAccount='$userAccount'")->getField("importTime,childID,childHeight,childWeight,childHeadc,childBMI");//��ѯ�����ֻ��û������Ķ�ͯ�˺ŵ��������ͷΧBMI��Ϣ
                $infoTable2=M("pregBaseInfo");
                $infoQuery2=$infoTable2->where("userAccount='$userAccount'")->getField("name,pregnantTime,expectedChildbirth,fatherHeight,motherHeight,sex,birthdate,birthHeight,birthWeight,birthHeadc,normalWeight,icon");//��ѯ�����ֻ��û��������и�����Ϣ
                $dataTable2=M("pregWeight");
                $dataQuery2=$dataTable2->where("userAccount='$userAccount'")->getField("time,weight");//��ѯ�����ֻ��û��������и������ز�����Ϣ
                $dataTable3=M("bmodeData");
                $dataQuery3=$dataTable3->where("userAccount='$userAccount'")->getField("time,crl,hc,bpd,ac,fl,ofd,wg");//��ѯ�����ֻ��û��������и�������ָ��Ĳ�����Ϣ
                $back=array("flag"=>1,"token"=>$token,"childInfo"=>$infoQuery1,"childData"=>$dataQuery1,"pregInfo"=>$infoQuery2,"pregWeightData"=>$dataQuery2,"pregBmodeData"=>$dataQuery3);//����1��ʾ��¼�ɹ�,token:������������
            }else{
                $back=array("flag"=>0);//����0��ʾ�û������������
            }
            $this->ajaxReturn($back,"json");
        }

        /*�ֻ�����ߡ����ء�ͷΧ��BMI����Ӻ�̨*/
        public function dataAdd(){
            $token=I("post.token");//��ȡ�������
            $parm=I("post.parm");//�����ж�
            $data=I("post.data");
            $userAccount=I("post.userAccount");
            $childID=I("post.childID");
            $importTime=I("post.importTime");
            $childBirthdate=I("post.childBirthdate");
//            $time=date("Y-m-d",time());
            $table=M("measureData");
            $condition["importTime"]=$importTime;//�ϴ����ݵ�����
            $condition["userAccount"]=$userAccount;//�û��˺�
            $condition["childID"]=$childID;//��Ӧ�Ķ�ͯID
            $condition["childBirthdate"]=$childBirthdate;//��Ӧ�Ķ�ͯ�ĳ������ڣ�����ͬ�������������Ҫ��
            $condition["childAge"]=$this->timeInterval($childBirthdate,$importTime);//��Ӧ�Ķ�ͯ�����䣬����Ϊ��λ
            if($token==$_SESSION["currentLogin"]){
                if(isset($parm)&&isset($data)&&isset($userAccount)&&isset($childID)&&isset($importTime)&&isset($childBirthdate)){
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
                    $result=$table->add($condition);//���ֻ����û���Ҫ��ӵ����ݴ������ݿ�
                    if($result){
                        $back=array("flag"=>1);//����1��ʾ��ӳɹ�
                    }else{
                        $back=array("flag"=>0);//����0��ʾ���ʧ��
                    }
                }else{
                    $back=array("flag"=>2);//����0��ʾ���ܵ��û������п�ֵ
                }
            }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
            $this->ajaxReturn($back, "json");
        }

    /*�ֻ����и�B���������ݵ���Ӻ�̨*/
    public function BmodeDataAdd(){
        $token=I("post.token");//��ȡ�������
        $userAccount=I("post.userAccount");//�����ж�
        $time=I("post.time");
        $crl=I("post.crl");
        $hc=I("post.hc");
        $bpd=I("post.bpd");
        $ac=I("post.ac");
        $fl=I("post.fl");
        $ofd=I("post.ofd");
        $wg=I("post.wg");
        $table=M("bmodeData");
        if($token==$_SESSION["currentLogin"]){
           $check = $table->where("userAccount='$userAccount' and time='$time'")->find();
           if($check){
             $back=array("flag"=>2);//����2��ʾ���յ������Ѿ����ڣ������ظ����
           }else{
             $condition["userAccount"]=$userAccount;//�û��˺�
             $condition["time"]=$time;//ʱ��
             $condition["crl"]=$crl;
             $condition["hc"]=$hc;
             $condition["bpd"]=$bpd;
             $condition["ac"]=$ac;
             $condition["fl"]=$fl;
             $condition["ofd"]=$ofd;
             $condition["wg"]=$wg;
             $result=$table->add($condition);//���и����Ե�B��������ӵ����ݿ�
              if($result){
                        $back=array("flag"=>1);//����1��ʾ��ӳɹ�
                    }else{
                        $back=array("flag"=>0);//����0��ʾ���ʧ��
                    }
           }

        }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
      $this->ajaxReturn($back, "json");
    }

    /*�ֻ����и����ز������ݵ���Ӻ�̨*/
    public function pregWeightAdd(){
        $token=I("post.token");//��ȡ�������
        $userAccount=I("post.userAccount");//�����ж�
        $time=I("post.time");
        $weight=I("post.weight");
        $table=M("pregWeight");
       if($token==$_SESSION["currentLogin"]){
           $check = $table->where("userAccount='$userAccount' and time='$time'")->find();
           if($check){
             $back=array("flag"=>2);//����2��ʾ���յ������Ѿ����ڣ������ظ����
           }else{
              $condition["userAccount"]=$userAccount;//�û��˺�
              $condition["time"]=$time;//ʱ��
              $condition["weight"]=$weight;
              $result=$table->add($condition);//���и����Ե�����������ӵ����ݿ�
              if($result){
                        $back=array("flag"=>1);//����1��ʾ��ӳɹ�
                    }else{
                        $back=array("flag"=>0);//����0��ʾ���ʧ��
                    }
            }
         }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
        $this->ajaxReturn($back, "json");
    }


    /*�ֻ�����ߡ����ء�ͷΧ��BMI���޸ĺ�̨*/
    public function dataEdit(){
        $token=I("post.token");//��ȡ�������
        $parm=I("post.parm");
        $data=I("post.data");
        $importTime=I("post.importTime");
        $childID=I("post.childID");
//        $time=date("Y-m-d",time());
        $table=M("measureData");
        if($token==$_SESSION["currentLogin"]) {
            if (isset($parm) && isset($data) && isset($importTime) && isset($childID)) {
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
                $result = $table->where("childID='$childID'and importTime='$importTime'")->save($condition);//���ֻ����û���Ҫ�޸ĵ����ݸ������ݿ�
                if ($result!==false) {//���³ɹ����ط����仯������������ʧ�ܻ᷵��false�������������δ�����仯�᷵��0�����������false�ж�
                    $back = array("flag" => 1);//����1��ʾ�޸ĳɹ�
                } else {
                    $back = array("flag" => 0);//����0��ʾ�޸�ʧ��
                }
            } else {
                $back = array("flag" => 2);//����2��ʾ�յ��������п�ֵ
            }
        }else{
            $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
        }
        $this->ajaxReturn($back, "json");
    }
     
    /*�ֻ����и�B���������ݵ��޸ĺ�̨*/
    public function BmodeDataEdit(){
        $token=I("post.token");//��ȡ�������
        $userAccount=I("post.userAccount");
        $time=I("post.time");
        $crl=I("post.crl");
        $hc=I("post.hc");
        $bpd=I("post.bpd");
        $ac=I("post.ac");
        $fl=I("post.fl");
        $ofd=I("post.ofd");
        $wg=I("post.wg");
        $table=M("bmodeData");
        if($token==$_SESSION["currentLogin"]){
           if (isset($userAccount )&& isset($time) && isset($crl)&& isset($hc)&& isset($bpd)&& isset($ac)&& isset($fl)&& isset($ofd)&& isset($wg)) {
             $condition["crl"]=$crl;
             $condition["hc"]=$hc;
             $condition["bpd"]=$bpd;
             $condition["ac"]=$ac;
             $condition["fl"]=$fl;
             $condition["ofd"]=$ofd;
             $condition["wg"]=$wg;
             $result = $table->where("userAccount='$userAccount'and time='$time'")->save($condition);//���и��޸ĵ�B�����ݸ������ݿ�
                if ($result!==false) {//���³ɹ����ط����仯������������ʧ�ܻ᷵��false�������������δ�����仯�᷵��0�����������false�ж�
                    $back = array("flag" => 1);//����1��ʾ�޸ĳɹ�
                } else {
                    $back = array("flag" => 0);//����0��ʾ�޸�ʧ��
                }
           }else{
               $back = array("flag" => 2);//����2��ʾ�յ��������п�ֵ
           }

        }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
      $this->ajaxReturn($back, "json");
    }

       /*�ֻ����и����ز������ݵ��޸ĺ�̨*/
    public function pregWeightEdit(){
        $token=I("post.token");//��ȡ�������
        $userAccount=I("post.userAccount");
        $time=I("post.time");
        $weight=I("post.weight");
        $table=M("pregWeight");
       if($token==$_SESSION["currentLogin"]){
         if (isset($userAccount) && isset($time) && isset($weight)) {
              $condition["weight"]=$weight;
              $result = $table->where("userAccount='$userAccount'and time='$time'")->save($condition);//���и��޸ĵ�B�����ݸ������ݿ�
                if ($result!==false) {//���³ɹ����ط����仯������������ʧ�ܻ᷵��false�������������δ�����仯�᷵��0�����������false�ж�
                    $back = array("flag" => 1);//����1��ʾ�޸ĳɹ�
                } else {
                    $back = array("flag" => 0);//����0��ʾ�޸�ʧ��
                }
            }else{ 
               $back = array("flag" => 2);//����2��ʾ�յ��������п�ֵ
             }
         }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
        $this->ajaxReturn($back, "json");
    }


        /*�ֻ�����ߡ����ء�ͷΧ��BMI��ɾ����̨*/
        public function dataDelete()
        {
            $token=I("post.token");//��ȡ�������
            $childID=I("post.childID");
            $importTime=I("post.importTime");
            $table=M("measureData");
            if($token==$_SESSION["currentLogin"]) {
                if ($childID && $importTime) {
                    $result = $table->where("childID='$childID'and importTime='$importTime'")->delete();//���ֻ����û���Ҫɾ�������ݽ���ɾ��
                    if ($result) {
                        $back = array("flag" => 1);//����1��ʾɾ���ɹ�
                    } else {
                        $back = array("flag" => 0);//����0��ʾɾ��ʧ��
                    }
                } else {
                    $back = array("flag" => 2);//����2��ʾ�յ��������п�ֵ
                }
            }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
            $this->ajaxReturn($back,"json");
        }

    /*�ֻ����и�B���������ݵ�ɾ����̨*/
    public function BmodeDataDelete(){
        $token=I("post.token");//��ȡ�������
        $userAccount=I("post.userAccount");
        $time=I("post.time");
        $table=M("bmodeData");
        if($token==$_SESSION["currentLogin"]){
          if ($userAccount && $time) {
              $result = $table->where("userAccount='$userAccount'and time='$time'")->delete();//����Ҫɾ�����и�B���������ݽ���ɾ��
                    if ($result) {
                        $back = array("flag" => 1);//����1��ʾɾ���ɹ�
                    } else {
                        $back = array("flag" => 0);//����0��ʾɾ��ʧ��
                    }
           }else{
               $back = array("flag" => 2);//����2��ʾ�յ��������п�ֵ
           }

        }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
      $this->ajaxReturn($back, "json");
    }

    /*�ֻ����и����ز������ݵ�ɾ����̨*/
    public function pregWeightDelete(){
        $token=I("post.token");//��ȡ�������
        $userAccount=I("post.userAccount");
        $time=I("post.time");
        $table=M("pregWeight");
        if($token==$_SESSION["currentLogin"]){
          if ($userAccount && $time) {
              $result = $table->where("userAccount='$userAccount'and time='$time'")->delete();//����Ҫɾ�����и����ز������ݽ���ɾ��
                    if ($result) {
                        $back = array("flag" => 1);//����1��ʾɾ���ɹ�
                    } else {
                        $back = array("flag" => 0);//����0��ʾɾ��ʧ��
                    }
           }else{
               $back = array("flag" => 2);//����2��ʾ�յ��������п�ֵ
           }

        }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
      $this->ajaxReturn($back, "json");
    }

        /*�ֻ��˶�ͯ������Ϣ�޸�*/
        public function childInfoEdit(){
            $token=I("post.token");//��ȡ�������
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
                    $back=array("flag"=>1);//����1��ʾ�޸ĳɹ�
                }else {
                    $back = array("flag" => 0);//����0��ʾ�޸�ʧ��
                }
            }else{
              $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
            $this->ajaxReturn($back,"json");
        }

        /*�ֻ����û��������µĶ�ͯ�˺�*/
        public function addChildID(){
            $token=I("post.token");//��ȡ�������
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
            $birthHeight=I("post.birthHeight");
            $birthWeight=I("post.birthWeight");
            $birthHeadc=I("post.birthHeadc");
            $table1=M("childBaseInfo");
            $table2=M("measureData");
            if($token==$_SESSION["currentLogin"]) {
                $check = $table1->where("childID='$childID'")->find();
                if ($check) {
                    $back = array("flag" => 2);//����2��ʾ�����Ķ�ͯID�Ѵ���
                } else {
                    //������ͯID������Ϣ
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
    
                    //�ڶ�ͯ���ݱ�����ӳ���ʱ�ĳ�ʼ����
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
                        $back=array("flag"=>1);//����1��ʾ������ͯID�ɹ�
                    }else{
                        $back=array("flag"=>0);//����0��ʾ����ʧ��
                    }
                }
            }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
            $this->ajaxReturn($back,"json");
        }

        /*�ֻ����û��������µ��и��˺�*/
         public function addPregID(){
            $token=I("post.token");//��ȡ�������
            $userAccount=I("post.userAccount");
            $userName=I("post.userName");
//          $Icon=I("post.icon");
            $name=I("post.name");
            $pregnantTime=I("post.pregnantTime");
            $expectedChildbirth=I("post.expectedChildbirth");
            $fatherHeight=I("post.fatherHeight");
            $motherHeight=I("post.motherHeight");
            $normalWeight=I("post.normalWeight"); 
            $table=M("pregBaseInfo");
           if($token==$_SESSION["currentLogin"]) {
              $check=$table->where("userAccount='$userAccount'")->find();
               if ($check) {
                    $back = array("flag" => 2);//����2��ʾ�������и��ʺ��Ѵ���
                } else {
                      //�����и��Ļ�����Ϣ
                      $condition["name"]=$name;
                      $condition["pregnantTime"]=$pregnantTime;
                      $condition["expectedChildbirth"]=$expectedChildbirth;
                      $condition["fatherHeight"]=$fatherHeight;
//                    $condition["icon"]=$icon;
                      $condition["motherHeight"]=$motherHeight;
                      $condition["normalWeight"]=$normalWeight;
                     
                      $result=$table->add($condition);
                      if($result){
                           $back=array("flag"=>1);//����1��ʾ�����и��ʺųɹ�
                        }else{
                           $back=array("flag"=>0);//����0��ʾ����ʧ��
                        }
                    }
            }else{
                $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
            }
            $this->ajaxReturn($back,"json");
        }  



      /*�ֻ����û�ɾ�����µĶ�ͯ�˺�*/
       public function deleteChildID(){
           $token=I("post.token");//��ȡ�������
           $childID=I("post.childID");
           $table1=M("childBaseInfo");
           $table2=M("measureData");
           if($token==$_SESSION["currentLogin"]) {
               if($childID){
                   $result1=$table1->where("childID='$childID'")->delete();//ɾ����ͯID������Ϣ
                   $result2=$table2->where("childID='$childID'")->delete();//ɾ����ͯID��������
                   if($result1&&$result2){
                       $back=array("flag"=>1);//����1��ʾɾ���ɹ�
                   }else{
                       $back=array("flag"=>0);//����0��ʾɾ��ʧ��
                   }
               }else{
                   $back=array("flag"=>2);//����2��ʾ�յ��Ķ�ͯIDΪ��
               }
           }else{
               $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
           }
           $this->ajaxReturn($back,"json");
       }

     // �ֻ����и���baby�����������
       public function addBirthData(){
            $userAccount=I("post.userAccount");
            $token=I("post.token");//��ȡ�������
            $sex=I("post.sex");
            $birthdate=I("post.birthdate");
            $birthHeight=I("post.birthHeight");
            $birthWeight=I("post.birthWeight");
            $birthHeadc=I("post.birthHeadc");
            if($token==$_SESSION["currentLogin"]) {
                 $table=M("pregBaseInfo");//�и���Ϣ��
                 $condition["sex"]=$sex;
                 $condition["birthdate"]=$birthdate;
                 $condition["birthHeight"]=$birthHeight;
                 $condition["birthWeight"]=$birthWeight;
                 $condition["birthHeadc"]=$birthHeadc;
                 $result = $table->where("userAccount='$userAccount'")->save($condition);//�������
                if ($result!==false) {//���³ɹ����ط����仯������������ʧ�ܻ᷵��false�������������δ�����仯�᷵��0�����������false�ж�
                    $back = array("flag" => 1);//����1��ʾ�޸ĳɹ�
                } else {
                    $back = array("flag" => 0);//����0��ʾ�޸�ʧ��
                }
            }else{
               $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
              }
       }
       
    /*�ֻ��˶�ͯ�������ͷΧBMI��ͬ���ͯ�е�����*/
       public function childDataOrder(){
           $childID=I("post.childID");
           $childBirthdate=I("post.childBirthdate");
           $latestDate=I("post.latestDate");
           $token=I("post.token");//��ȡ�������
           //if($token==$_SESSION["currentLogin"]) {
               if(isset($childID)&&isset($childBirthdate)){
                   $table=M("measureData");
                   $childAge=$this->timeInterval($childBirthdate,$latestDate);
                   $latestData=$table->where("importTime='$latestDate'and childID='$childID'")->getField("childAge,childHeight,childWeight,childHeadc,childBMI");
                   $choiceHeight=$latestData[$childAge]['childheight'];//�ӵ�һ�β�ѯ�Ķ�ͯ�����зֱ���ȡ����ߡ����ء�ͷΧ��BMI
                   $choiceWeight=$latestData[$childAge]['childweight'];
                   $choiceHeadc=$latestData[$childAge]['childheadc'];
                   $choiceBMI=$latestData[$childAge]['childbmi'];

                   $allSuitChild=$table->where("childAge-'$childAge' between -180 and 180")->count();//�����ͯ����

                   $resultHeight=$table->where("(childAge-'$childAge' between -180 and 180) and childHeight > '$choiceHeight'")->count();//���ݵ�����
                   $resultWeight=$table->where("(childAge-'$childAge' between -180 and 180) and childWeight > '$choiceWeight'")->count();
                   $resultHeadc=$table->where("(childAge-'$childAge' between -180 and 180) and childHeadc > '$choiceHeadc'")->count();
                   $resultBMI=$table->where("(childAge-'$childAge' between -180 and 180) and childBMI > '$choiceBMI'")->count();

                   $back=array("flag"=>1,"heightRand"=>($allSuitChild-($resultHeight+1))/$allSuitChild,"weightRand"=>($allSuitChild-($resultWeight+1))/$allSuitChild,"headcRand"=>($allSuitChild-($resultHeadc+1))/$allSuitChild,"bmiRand"=>($allSuitChild-($resultBMI+1))/$allSuitChild);
//                     $back=array("flag"=>1,"data"=>$latestData[$childAge]['childheight']);
               }else{
                   $back=array("flag"=>2);//����2��ʾ�յ��Ķ�ͯID���߳������ڴ��ڿ�ֵ
               }
         //  }else{
          //     $back=array("flag"=>4);//����4��ʾtoken����ȷ���߹��ڣ���Ҫ�ͻ������µ�¼
          // }
           $this->ajaxReturn($back,"json");
       }
    /*ʱ���е������滻Ϊ-*/
    private function timeReplace(){
//        mb_regex_encoding('utf-8');//���������滻���õ��ı���
//        return mb_ereg_replace('[^0-9]', '-', $parm);
        echo date('Y-m-d', strtotime('2016��05��29��'));
    }
    /*��������ʱ��ֵ֮��ļ��*/
    private function timeInterval($str1,$str2){
        $year=substr($str2,0,4)-substr($str1,0,4);
        $month=substr($str2,5,2)-substr($str1,5,2);
        $day=substr($str2,8,2)-substr($str1,8,2);
        return $year*365+$month*30+$day;
    }

//    public function setUserIcon()
//    {
//        //���������notice��waring��Ϣ
//        error_reporting(E_ALL ^ E_NOTICE);
//        error_reporting(E_ALL ^ E_WARNING);
//        $userAccount = $_SESSION["medicalID"];
//        $dataCheck = A("DataCheck");
//        define("fileStore", "Public/image/userIcon");//�ļ��洢����Ŀ¼(��thinkphp�����λ�úͺ�������ݿ�洢��ַ��һ��������)
//        date_default_timezone_set("PRC");
//        //���������ϴ����ļ��ĸ�ʽ
//        //$imageCount = count($_FILES['photo']['name']);//��ȡ�ϴ�ͼƬ������
//        //for($i=0;$i<$imageCount;$i++){
//        if (is_uploaded_file($_FILES["photo"]["tmp_name"])) {
//
//            if (($_FILES["photo"]["type"] == "image/gif")
//                || ($_FILES["photo"]["type"] == "image/jpeg")
//                || ($_FILES["photo"]["type"] == "image/pjpeg")
//            ) {
//                //���������ϴ����ļ��Ĵ�С
//                if (($_FILES["photo"]["size"] < 500000)) {
//                    chdir(fileStore);
//                    $mainPath = $medicalID;
//                    $path = date('Ymd', time());
//                    if (!is_dir($mainPath)) { //���û����������ļ���
//                        mkdir($mainPath);
//                    }
//                    chdir($mainPath);
//                    if (!is_dir($path)) { //�����ڴ���ͼƬ�洢�ļ��У��������ڣ��������ļ��У������и����⣬��Ϊ�������ϴ���ÿ�εڶ���ͼƬ��Ȼ���жϳ�Ŀ¼�����ڣ�
//                        mkdir($path);
//                    }
//                    chdir($path);
//                    $date = date("-H-i-s.");
//                    $photoType = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION); //��ȡͼƬ��׺��
//                    //  $photoType=substr($_FILES["photo"]["name"],-3);//��ȡͼƬ��׺��
//                    $photoNameSave = $path . $date . $photoType;//���°��ϴ�ʱ������ͼƬ��
//                    $result = move_uploaded_file($_FILES["photo"]["tmp_name"], $photoNameSave);
//
//
//                    //��ͼƬ�ϴ��ɹ������ͼƬurl���������ݿ��У�����鿴
//                    if ($result == 1) {
//                        //echo "ͼƬ�Ѿ��ɹ��ϴ�!";
//                        $photoURL = "../../public/others/teenagerGrow/userUploadPhoto/" . $mainPath . "/" . $path . "/" . $photoNameSave; //���ݿ�洢��ͼƬ�����·��
//                        $photoSavePlace=fileStore."/". $mainPath . "/" . $path . "/" . $photoNameSave;
//                        $conn = $dataCheck->data_connect();
//                        if (!$conn) {
//                            E('Could not connect to database.');
//                        }
//
//                        mysqli_query($conn, "SET NAMES 'utf8'");//�������ݿ����ݵı��룬������һ�������ַ��޷�������ʾ
//                        $mess = "insert into tg_child_image (account,imageUrl,uploadTime,imageSavePlace) VALUES ('$medicalID','$photoURL','$path','$photoSavePlace')";
//                        $last = mysqli_query($conn, $mess);
//                        if (!$last) {
//                            E('Could not register you in database - please try again later.');
//                        } else {
//                            $this->success("ͼƬ�Ѿ��ɹ��ϴ�");
//                        }
//                    }
//
//                } else {
//                    $this->error("�ϴ�ʧ�ܣ��ļ���С��������");
//                }
//
//            } else {
//                $this->error("�ϴ�ͼƬ�ĸ�ʽ������ѡ����ȷ���ļ���ʽ");
//            }
//        }
//        if ($_FILES["photo"]["error"] > 0) {
//            $this->error("�ϴ�ͼƬ����������");
//        }
//    }

    /*�û��˳���¼*/
    public function loginOut(){
         session("[start]");
         session("currentLogin",null);
         session("[destroy]");
        }


}