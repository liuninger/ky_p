<?php
/**
 * INfxShopConfig.php
 *
 * Niushop商城系统 - 团队十年电商经验汇集巨献!
 * =========================================================
 * Copy right 2015-2025 山西牛酷信息科技有限公司, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: http://www.niushop.com.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 * @desc : 佣金设置接口
 * @author : niuteam
 * @date : 2015.1.17
 * @version : v1.0.0.0
 */
namespace data\api;

interface INfxShopConfig
{
    /**
     * 店铺是否开启分销及推广员是否需要审核及推广员是否需要开启申请
     * @param unknown $shop
     * @param unknown $is_distribution_enable
     * * @param unknown $is_audit
     */
    function modifyShopConfigIsDistributionOrPromoterIsAudit($shop_id, $is_distribution_enableopen,$is_audit,$is_start,$is_set,$distribution_use,$distribution_commission_rate, $partner_commission_rate, $regionagent_commission_rate);
    /**
     * 是否开启区域代理
     * @param unknown $shop_id
     * @param unknown $is_open
     */
    function modifyShopConfigIsRegionalAgent($shop_id, $is_open);
    /**
     * 股东分红是否开启
     * @param unknown $shop_id
     * @param unknown $is_open
     */
    function modifyShopConfigIsPartnerEnable($shop_id, $is_open);
    /**
     * 全球分红
     * @param unknown $shop_id
     * @param unknown $is_open
     */
    function modifyShopConfigIsGlobalEnable($shop_id, $is_open);
    /**
     * 店铺分销设置
     * @param unknown $shop_id
     */
    function getShopConfigDetail($shop_id);
}