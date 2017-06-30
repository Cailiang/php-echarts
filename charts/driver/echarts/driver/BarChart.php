<?php
/**
 * Created by IntelliJ IDEA.
 * User: fang.cai.liang@aliyun.com
 * Date: 2017/6/30
 * Time: 15:13
 */

namespace charts\driver\echarts\driver;

use charts\driver\echarts\Echart;

class BarChart  extends Echart 
{

    /**
     * 画 bar, line图 需要的配置信息, 实例化的时候需要传入
     * @var
     */
    private $config;

    /**
     * MapChart constructor.
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
     * @param array $data
     * 数据结构
     *  array(
     *      '2015-07-01' => array(
     *          'dst' => 10,
     *          '网络' => 16,
     *          'oldMember' => 20
     *      ),
     *      '2015-07-02' => array(
     *          'dst' => 7,
     *          '网络' => 13,
     *          'oldMember' => 2
     *      )
     *      ...
     *  )
     * @param array $titleOptions
     * @return string
     */
    public function genOption(array $data, array $titleOptions)
    {
        $this->genTitle($titleOptions);
        $this->genLegend($data);
        $this->genXaxis($data);
        $this->genSeries($data);
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
     * @param array $data
     */
    private function genLegend(array $data){
        $legendData = array_keys(current($data));
        $this->config['legend']['data'] = $legendData;
    }

    /**
     * 生成 xAxis 中的 data
     * @param array $data
     */
    private function genXaxis(array $data){
        $xAxisData = array_keys($data);
        $this->config['xAxis']['data'] = $xAxisData;
    }

    /**
     * 生成 series
     * @param array $data
     */
    private function genSeries(array $data){
        $seriesDataArr = [];
        $legendDatas = $this->config['legend']['data'];
        foreach ($legendDatas as $col) {
            $seriesData['name'] = $col;
            $seriesData['type'] = $this->config['default'];
            $seriesData['data'] = [];
            foreach ($data as $series) {
                $seriesData['data'][] = $series[$col];
            }
            $seriesDataArr[] = $seriesData;
        }
        $this->config['series'] = $seriesDataArr;
    }
}