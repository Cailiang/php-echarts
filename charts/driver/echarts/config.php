<?php

return [

    // +--------------------------------------------------------------------------------------------------
    // | MapChart 设置  注意所有的 false 必须用 0 来表示, 否则在后面的模板拼接中会丢失, 导致json解析失败
    // +--------------------------------------------------------------------------------------------------

    'map' => [
        'title' => ['left' => 'center'],

        'tooltip' => ['trigger' => 'item'],

        'legend' => ['orient' => 'vertical', 'left' => 'left'],

        'visualMap' => ['left' => 'right', 'top' => 'bottom', 'text' => ['高', '低'], 'calculable' => true],

        'toolbox' => ['show' => 0, 'orient' => 'vertical', 'left' => 'right', 'top' => 'center',
            'feature' => [
                'dataView' => ['readOnly' => 0]
            ]
        ],

        // showLegendSymbol 是否显示 圆点, normal 是否显示 省份的名称, emphasis 选中或鼠标移动上去时的样式
        'series' => ['roam' => true, 'type' => 'map', 'showLegendSymbol' => 0,
            'label' => [
                'normal' => ['show' => 0],
                'emphasis' => ['show' => 0, 'textStyle' => ['fontSize' => 18]]
            ]
        ],
    ],

    'bar' => [

        'default' => 'bar',

        'title' => ['left' => 'left'],

        'tooltip' => ['trigger' => 'axis'],

        'legend' => ['orient' => 'vertical', 'left' => 'center', 'data' => []],

        'grid' => [
            'left' => '1%',
            'right' => '1%',
            'bottom' => '1%',
            'containLabel' => true
        ],

        'toolbox' => [
            'show' => 1,
            'itemSize' => 20,
            'itemGap' => 15,
            'feature' => ['mark' => ['show' => 1], 'restore' => ['show' => 0],
                'dataView' => ['show' => 0, 'readOnly' => 1], 'magicType' => ['show' => 1, 'type' => ['line', 'bar'], 'title' => ['line' => '折线图', 'bar' => '柱状图']],
            ]
        ],

        'xAxis' => [
            'type' => 'category',
            'boundaryGap' => 0,
            'data' => []
        ],

        'yAxis' => [
            'type' => 'value'
        ],
        
        'series' => [
            
        ]
    ],

    'pie' => [

        'title' => ['left' => 'center'],

        'tooltip' => ['trigger' => 'item', 'formatter' => '{a} <br/>{b}: {c} ({d}%)'],

        'legend' => ['orient' => 'vertical', 'x' => 'left', 'data' => []],

        'series' => [
            'name' => '',
            'type' => 'pie',
            'radius' => ['40%', '70%'],
            'avoidLabelOverlap' => 0,
            'label' => [
                'normal' => ['show' => 0, 'position' => 'center'],
                'emphasis' => ['show' => 1, 'textStyle' => ['fontSize' => '30', 'fontWeight' => 'bold']]
            ],
            'labelLine' => ['normal' => ['show' => 0]],
            'data' => [],
        ]
    ],
];
