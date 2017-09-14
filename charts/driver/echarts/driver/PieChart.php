<?php
/**
 * Created by IntelliJ IDEA.
 * User: fang.cai.liang@aliyun.com
 * Date: 2017/9/4
 * Time: 13:53
 */

namespace charts\driver\echarts\driver;


use charts\driver\echarts\Echart;

class PieChart extends Echart
{
    /**
     * 画 pie 图 需要的配置信息, 实例化的时候需要传入
     * @var
     */
    private $config;

    /**
     * PieChart constructor.
     * @param $config
     */
    function __construct($config){
        $this->setConfig($config);
    }

    /**
     * @return mixed
     */
    public function getConfig(){
        return $this->config;
    }

    /**
     * @param $config
     */
    public function setConfig($config){
        if($config && is_array($config) && !empty($config)){
            $this->config = $config;
        }
    }

    /**
     * [
     *   ['value' => 100, 'name' => '生态']
     *   ['value' => 300, 'name' => '品牌']
     *   ......
     * ]
     * @param array $datas 传入的数据
     * @param array $titleOptions 传入的标题
     * @return mixed
     */
    public function genOption(array $datas, array $titleOptions)
    {
        $this->genTitle($titleOptions);
        $this->genLegend($datas);
        $seriesName = isset($titleOptions['seriesName']) ? $titleOptions['seriesName'] : '';
        $this->genSeries($datas, $seriesName);
        return json_encode($this->config, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 生成 title
     * @param $titleOptions
     */
    private function genTitle($titleOptions){
        $this->config['title'] = array_merge($this->config['title'], $titleOptions);
    }

    /**
     * 生成 Legend 中的 data
     * @param array $datas
     */
    private function genLegend(array $datas){
        $legend = [];
        foreach ($datas as $data) {
            $legend[] = $data['name'];
        }
        $this->config['legend']['data'] = $legend;
    }

    /**
     * 生成 series
     * @param array $datas
     * @param $seriesName
     */
    private function genSeries(array $datas, $seriesName){
        $series['name'] = $seriesName;
        $series['data'] = $datas;
        $this->config['series'] = array_merge($this->config['series'], $series);
    }
}