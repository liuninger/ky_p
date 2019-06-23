<?php
/**
 * Created by PhpStorm.
 * User: 9Smile
 * Date: 2019/6/1
 * Time: 10:05
 */
namespace app\wap\controller;

use data\extend\upgrade\Http;
use data\model\UserModel;
use data\service\Config;
use data\service\NfxPromoter;
use data\service\NfxUser;
use data\service\Shop;
use data\service\User;
use think\Controller;
use think\Log;

class SystemOther extends Controller
{
    private $secret_key = '';
    private $other_host = '';
    private $data = [];
    private $is_self = '';

    function __construct($is_self = '')
    {
        parent::__construct();
        $this->secret_key = 'de6b8a4ba36a40a4bd755a7589d2f5399d68ff0a6b9330f90f87b70c6218410f';
        $this->is_self = empty($is_self) ? 0 : 1;
        $this->initValidate();
        $this->initData();

    }

    private function initValidate()
    {
        $source_secret_key = $this->request->post('source_secret_key');
        $request_source = $this->request->post('request_source');
        if($request_source == 'self' || $this->is_self == 1) return true;
        if($source_secret_key != $this->secret_key){
            echo json_encode(AjaxReturn(-100,[],'秘钥不正确'));
            exit();
        }
    }

    private function initData()
    {
        $config_service = new Config();

        $this->other_host =  'http://ky.tlrgj.shop/index.php?s=/wap/SystemOther/';
//        $this->other_host =  'http://127.0.0.1/ky/index.php?s=/wap/SystemOther/';
        $this->data = [
            'source_secret_key' => $this->secret_key
        ];
    }

    /**
     * 获取分销等级
     * @return string
     */
    public function getPromoterLevelList()
    {
        $nfx_promoter_service = new NfxPromoter();
        $list = $nfx_promoter_service->getPromoterLevelList(1,0);
        return json_encode(AjaxReturn(1,$list,'获取线下分销等级'));
    }

    /**
     * 添加门店
     */
    public function addPickupAndShop()
    {
        $name = $this->request->post('name','');
        $address = $this->request->post('address','');
        $contact = $this->request->post('contact','');
        $phone = $this->request->post('phone','');
        $province_id = $this->request->post('province_id','');
        $city_id = $this->request->post('city_id','');
        $district_id = $this->request->post('district_id','');
        $longitude = $this->request->post('longitude','');
        $latitude = $this->request->post('latitude','');
        $promoter_level = $this->request->post('promoter_level','');
        $p_mobile = $this->request->post('p_mobile','');
        $member_service = new \data\service\Member();
        $shop_service = new Shop();
        //添加门店会员账号
        $uid = $member_service->addMember($name,'Ky123456@...','','',1,$phone,'');
        if($uid <= 0) return json_encode(AjaxReturn(-3001,'','线下系统添加会员失败'));
        $nfx_user_service = new NfxUser();
        $edit_promoter = $nfx_user_service->editUserPromoterLevel($uid,$promoter_level,$p_mobile);
        //添加门店
        $pickup_id = $shop_service->addPickupPoint($this->instance_id,$name,$address,$contact,$phone,$province_id,$city_id,$district_id,$longitude,$latitude,$promoter_level, $uid, $p_mobile);
        if($pickup_id <= 0) return json_encode(AjaxReturn(-3003,'','添加线下门店失败'));
        return json_encode(AjaxReturn(1,['x_id'=>$pickup_id],'添加线下门店成功'));
    }

    /**
     * 添加门店
     */
    public function updatePickupAndShop()
    {
        $x_id = $this->request->post('x_id','');
        $name = $this->request->post('name','');
        $address = $this->request->post('address','');
        $contact = $this->request->post('contact','');
        $phone = $this->request->post('phone','');
        $province_id = $this->request->post('province_id','');
        $city_id = $this->request->post('city_id','');
        $district_id = $this->request->post('district_id','');
        $longitude = $this->request->post('longitude','');
        $latitude = $this->request->post('latitude','');
        $promoter_level = $this->request->post('promoter_level','');
        $p_mobile = $this->request->post('p_mobile','');
        $member_service = new \data\service\Member();
        $shop_service = new Shop();
        //添加门店会员账号
        $pickup_info = $shop_service->getPickupPointDetail($x_id);
        $uid = $member_service->addMember($name,'Ky123456@...','','',1,$phone,'');
        $nfx_user_service = new NfxUser();
        $edit_promoter = $nfx_user_service->editUserPromoterLevel($uid,$promoter_level,$p_mobile);
        //添加门店
        $pickup_id = $shop_service->updatePickupPoint($x_id,$this->instance_id,$name,$address,$contact,$phone,$province_id,$city_id,$district_id,$longitude,$latitude,$promoter_level, $uid, $p_mobile);
        if($pickup_id <= 0) return json_encode(AjaxReturn(-3003,'','修改线下门店失败'));
        return json_encode(AjaxReturn(1,'','修改线下门店成功'));
    }

    public function deletePickupPoint()
    {
        $x_id = $this->request->post('x_id','');
        $shop_service = new Shop();
        $shop_service->deletePickupPoint($x_id);
        return json_encode(AjaxReturn(1,'','删除线下门店成功'));
    }



    public function otherUser($uid)
    {
        $url = $this->other_host . 'getPointUser';
        $shop_service = new Shop();
        $user_data = ['uid'=>$uid];
        $http_class = new Http();
        $http_class::setWay(1);
        $this->data = array_merge($this->data, $user_data);
        $list = $http_class::doPost($url,$this->data);
        $list = json_decode($list,true);

        if($list['code'] > 0){
            $member_service = new \data\service\Member();
            $data = $list['data'];
            // 48 中心  49社区
            $member_level = 0;
            if($list['data']['point_info']['promoter_level'] == 4){
                $member_level = 49;
            }elseif($list['data']['point_info']['promoter_level']){
                $member_level = 48;
            }
            if(empty($member_level)){
                return 0;
            }
            $uid = $member_service->addMember($data['user_name'],$data['user_password'],$data['user_email'],$data['sex'],$data['user_status'],$data['user_tel'],$member_level);
            return $uid;
        }else{
            return 0;
        }

    }




}
