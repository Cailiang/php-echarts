<?php
/**
 * Created by IntelliJ IDEA.
 * User: fang.cai.liang@aliyun.com
 * Date: 2016/12/14
 * Time: 16:50
 */

namespace charts;

use \Exception;

/**
 * 画图的对外接口, 持有一个真正的画图组件
 * Class Chart
 * @package charts
 */
class Chart
{

    /**
     * @var 真正的画图组件
     */
    protected static $chart;


    /**
     * 实例化一个画图组件 
     * @param $chartName 图的名称 如 map, bar, pie
     * @param null $type 组件名称 如 echarts
     * @param null $config 画图对应的配置信息
     * @throws Exception
     */
    public static function init($chartName, $type = null, $config = null){
        if(is_null($chartName)){
            throw new Exception('请指定需要画什么图, 如 map, bar, pie ....... ', 99999);
        }
        $class = '\\charts\\driver\\'.$type.'\\driver\\'.ucwords($chartName).'Chart';

        self::$chart = new $class($config[$chartName]);
    }

    /**
     * 调用真正实现画图功能的组件, 注意组件的命名规则 和 命名空间
     * @param $chartName 图的名称 如 map, bar, pie 
     * @param $mapType 当图的名称为 map 时, $mapType表示的是画全国图(china), 还是画某个省份的图(beijing, anhui, jiangxi ...)
     * @param array $datas 画图需要的数据
     * @param array $title 图的标题信息
     * @param null $type 组件名称 如 echarts
     * @param null $config 画图对应的配置信息
     * @return mixed
     * @throws Exception
     */
    public static function genOption($chartName, $mapType, array $datas, array $title, $type = null, $config = null){
        if(is_null(self::$chart)){
            if(is_null($type)){
                $type = 'echarts';
            }
            if(null == $config){
                $path = __DIR__.'/driver/'.$type.'/config.php';
                $config = include $path;
            }
            self::processConfig($config, $mapType, $chartName);
            self::init($chartName, $type, $config);
        }
        return self::$chart->genOption($datas, $title);
    }
    
    private static function processConfig(&$config, $mapType, $chartName){
        if((null == $config) || (null == $config[$chartName])){
            throw new Exception($chartName.' 的配置信息为空!', 99988);
        }
        if(!is_null($mapType) && is_array($config[$chartName]['series'])){
            $config[$chartName]['series']['mapType'] = $mapType;
        }
    }
}