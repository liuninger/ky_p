<?php
/**
 * Address.php
 *
 * Niushop商城系统 - 团队十年电商经验汇集巨献!
 * =========================================================
 * Copy right 2015-2025 山西牛酷信息科技有限公司, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: http://www.niushop.com.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 * @author : niuteam
 * @date : 2015.4.24
 * @version : v1.0.0.0
 */
namespace data\service;

/**
 * 数据分析
 */

use data\model\NfxCommissionDistributionModel;
use data\model\NfxCommissionPartnerModel;
use data\model\NfxCommissionRegionAgentModel;
use data\model\NsOrderModel;
use think\Cache;

class DataAnalyse extends BaseService
{
    private $order_model = ''; //订单
    private $ncd_model = ''; //推广员分销
    private $ncp_model = ''; //股东分红
    private $nca_model = ''; //区域代理

    public function __construct()
    {
        $this->initModel();
        $this->order_model = new NsOrderModel();
        $this->ncd_model = new NfxCommissionDistributionModel();
        $this->ncp_model = new NfxCommissionPartnerModel();
        $this->nca_model = new NfxCommissionRegionAgentModel();
    }

    private function initModel()
    {

    }


    /**
     * 订单总交易金额
     */
    public function getOrderMoneySum($condition = [])
    {
        $where = [
            'order_status' => 4
        ];
        $condition = array_merge($condition,$where);
        $data = [];
        $data['sum'] = $this->order_model->getSum($condition,'order_money');
        $data['count'] = $this->order_model->getCount($condition);
        return $data;
    }

    /**
     * 获取推广员总佣金
     * @param array $condition
     * @return \data\model\unknown|number
     */
    public function getNcdMoneySum($condition = [])
    {
        $where = [
            'is_issue' => 1
        ];
        $condition = array_merge($condition,$where);
        $data = [];
        $data['sum'] = $this->ncd_model->getSum($condition,'commission_money');
        $data['count'] = $this->ncd_model->getCount($condition);
        return $data;
    }

    /**
     * 获取股东总佣金
     * @param array $condition
     * @return \data\model\unknown|number
     */
    public function getNcpMoneySum($condition = [])
    {
        $where = [
            'is_issue' => 1
        ];
        $condition = array_merge($condition,$where);
        $data = [];
        $data['sum'] = $this->ncp_model->getSum($condition,'commission_money');
        $data['count'] = $this->ncp_model->getCount($condition);
        return $data;
    }

    /**
     * 获取区代总佣金
     * @param array $condition
     * @return \data\model\unknown|number
     */
    public function getNcaMoneySum($condition = [])
    {
        $where = [
             'is_issue' => 1
        ];
        $condition = array_merge($condition,$where);
        $data = [];
        $data['sum'] = $this->nca_model->getSum($condition,'commission');
        $data['count'] = $this->nca_model->getCount($condition);
        return $data;
    }


}