<?php
/**
 * Created by IntelliJ IDEA.
 * User: fang.cai.liang@aliyun.com
 * Date: 2016/12/14
 * Time: 17:01
 */

namespace charts\driver\echarts\driver;

use charts\driver\echarts\Echart;

/**
 * 实现画全国图的功能
 * Class MapChart
 * @package charts\driver\echarts\driver
 */
class MapChart extends Echart
{

    /**
     * 画 全国图 需要的配置信息, 实例化的时候需要传入
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
     * 实现 genOperation 方法, 生成 echarts 需要的 json 字符串
     * @param array $datas 
     * 数据结构
     * $datas => array(
     *     "冰雹" => array(
     *          array(
     *              "name" => "北京",
     *              "value" => 12
     *          )
     *          ...
     *      )
     *      ...
     * );
     * @param array $titleOptions
     * @return string
     */
    public function genOption(array $datas, Array $titleOptions){
        $series = $this->genSeries($datas);

        $legend = json_encode(array_keys($datas), JSON_UNESCAPED_UNICODE);

        $visualMap = $this->genVisualMap($datas);

        $title = json_encode($this->genTitle($titleOptions), JSON_UNESCAPED_UNICODE);
        
        return $this->optionTemplate($legend, $series, $title, $visualMap);
    }

    /**
     * 生成模板中的 series
     * @param array $titleOptions
     * @return array
     */
    private function genTitle(Array $titleOptions)
    {
        //$mapConfig = $this->config['map'];
        $left = is_null($this->config['title']['left']) ? 'center' : $this->config['title']['left'];
        $text = is_null($titleOptions['text']) ? '' : $titleOptions['text'];
        $subtext = is_null($titleOptions['subtext']) ? '' : $titleOptions['subtext'];
        $title = ['text' => $text, 'subtext' => $subtext, 'left' => $left];
        return $title;
    }

    /**
     * 生成模板中 visualMap 的 min 和 max, 最小值是0, 最大值是灾害发生频率最高的省份
     * @param $datas
     * @return mixed
     */
    private function genVisualMap($datas){
        $tmp = array();
        foreach($datas as $key => $dataArray){
            foreach ($dataArray as $data){
                if(array_key_exists($data['name'], $tmp)){
                    $tmp[$data['name']] = $tmp[$data['name']] + $data['value'];
                }else{
                    $tmp[$data['name']] = $data['value'];
                }
            }
        }
        arsort($tmp);
        $visualMap['max'] = current($tmp);
        $visualMap['min'] = 0;

        return $visualMap;
    }

    /**
     * 生成模板中的 series
     * @param $datas
     * @return string
     */
    private function genSeries($datas){
        $series = '';
        $seriesConfig = $this->config['series'];
        $type = is_null($seriesConfig['type']) ? 'map' : $seriesConfig['type'];
        $mapType = is_null($seriesConfig['mapType']) ? 'china' : $seriesConfig['mapType'];
        $roam = is_null($seriesConfig['roam']) ? true : $seriesConfig['roam'];
        $label_normal_show = is_null($seriesConfig['label']['normal']['show']) ? true : $seriesConfig['label']['normal']['show'];
        $label_emphasis_show = is_null($seriesConfig['label']['emphasis']['show']) ? true : $seriesConfig['label']['emphasis']['show'];

        $showLegendSymbol = is_null($seriesConfig['showLegendSymbol']) ? true : $seriesConfig['showLegendSymbol'];

        $label_emphasis_textStyle = is_null($seriesConfig['label']['emphasis']['textStyle']) ? null : $seriesConfig['label']['emphasis']['textStyle'];
        $label_emphasis_textStyle_fontSize = '16';
        if((null != $label_emphasis_textStyle)){
            $label_emphasis_textStyle_fontSize = $label_emphasis_textStyle['fontSize'];
        }

        foreach ($datas as $key => $data){
            $dataStr = json_encode($data, JSON_UNESCAPED_UNICODE);
            $seriesTmpl = <<<EOT
{
    "name": "$key", 
    "type": "$type", 
    "mapType": "$mapType", 
    "roam": $roam, 
    "showLegendSymbol": $showLegendSymbol,
    "label": {
        "normal": {
            "show": $label_normal_show
        }, 
        "emphasis": {
            "show": $label_emphasis_show,
            "textStyle": {
                "fontSize": $label_emphasis_textStyle_fontSize
            }
        }
    }, 
    "data": $dataStr
},
EOT;
            $series = $series.$seriesTmpl;
        }
        
        return rtrim($series, ',');
    }

    /**
     * 根据模板,生成 echarts 需要的 json 字符串
     * @param $legend
     * @param $series
     * @param $title
     * @param $visualMap
     * @return string
     */
    private function optionTemplate($legend, $series, $title, $visualMap){
        $mapConfig = $this->config;

        $tooltip_trigger = is_null($mapConfig['tooltip']['trigger']) ? 'item' : $mapConfig['tooltip']['trigger'];

        $legend_orient = is_null($mapConfig['legend']['orient']) ? 'vertical' : $mapConfig['legend']['orient'];
        $legend_left = is_null($mapConfig['legend']['left']) ? 'left' : $mapConfig['legend']['left'];

        $visualMap_left = is_null($mapConfig['visualMap']['left']) ? 'left' : $mapConfig['visualMap']['left'];
        $visualMap_top = is_null($mapConfig['visualMap']['top']) ? 'bottom' : $mapConfig['visualMap']['top'];
        $visualMap_text = json_encode(is_null($mapConfig['visualMap']['text']) ? ['高','低'] : $mapConfig['visualMap']['text'], JSON_UNESCAPED_UNICODE);
        $visualMap_calculable = is_null($mapConfig['visualMap']['calculable']) ? true : $mapConfig['visualMap']['calculable'];
        $visualMapMin = $visualMap['min'];
        $visualMapMax = $visualMap['max'];

        $toolbox_show = is_null($mapConfig['toolbox']['show']) ? true : $mapConfig['toolbox']['show'];
        $toolbox_orient = is_null($mapConfig['toolbox']['orient']) ? 'vertical' : $mapConfig['toolbox']['orient'];
        $toolbox_left = is_null($mapConfig['toolbox']['left']) ? 'right' : $mapConfig['toolbox']['left'];
        $toolbox_top = is_null($mapConfig['toolbox']['top']) ? 'center' : $mapConfig['toolbox']['top'];
        $toolbox_feature_dataView_readOnly = is_null($mapConfig['toolbox']['feature']['dataView']['readOnly']) ? true : $mapConfig['toolbox']['feature']['dataView']['readOnly'];

        $option = <<<EOT
{
    "title": $title,
    "tooltip": {
        "trigger": "$tooltip_trigger"
    }, 
    "legend": {
        "orient": "$legend_orient",
        "left": "$legend_left",
        "data": $legend
    }, 
    "visualMap": {
        "min": $visualMapMin, 
        "max": $visualMapMax, 
        "left": "$visualMap_left",
        "top": "$visualMap_top",
        "text": $visualMap_text,
        "calculable": $visualMap_calculable
    }, 
    "toolbox": {
        "show": $toolbox_show,
        "orient": "$toolbox_orient",
        "left": "$toolbox_left",
        "top": "$toolbox_top",
        "feature": {
            "dataView": {
                "readOnly": $toolbox_feature_dataView_readOnly
            }, 
            "restore": { }, 
            "saveAsImage": { }
        }
    }, 
    "series": [$series]
}
EOT;
        return $option;
    }

}