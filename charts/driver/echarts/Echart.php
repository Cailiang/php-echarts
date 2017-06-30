<?php
/**
 * Created by IntelliJ IDEA.
 * User: fang.cai.liang@aliyun.com
 * Date: 2016/12/14
 * Time: 16:53
 */

namespace charts\driver\echarts;


abstract class Echart
{

    /**
     * echarts 画图组件,画每种图形都需要一个 json 串, 每一种图都需要实现该方法来获得自己的 json 串
     * @param array $datas 传入的数据, 每种图的数据结构不一样
     * @param array $titleOptions 传入的标题
     * @return mixed
     */
    abstract public function genOption(array $datas, array $titleOptions);
    
}