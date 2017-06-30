# php-echarts
echarts 后端

用于 tp5 的步骤

1. 将charts目录复制到extend目录下。

2. 在代码中直接调用genOption方法就能获取 echarts 所需的 json 数据

   $option = Chart::genOption('map', $params['mapType'], $dataList, $title);

3. 前端
    var barChart = echarts.init(document.getElementById('disaster_bar'));  // 实例化 echarts

    var options = $.parseJSON(res.info);  // 获取 后端返回的 json 串 （res.info 是我返回的 json 串）

    barChart.setOption(options);

画柱状图就不需要 mapType这个参数了

$option = Chart::genOption('bar', null, $dataList, $title);