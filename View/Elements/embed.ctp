<?php
if (!isset($width)) {
	$width = 640;
}
if (!isset($height)) {
	$height = 480;
}
if (!isset($autoplay)) {
	$autoplay = false;
}
$options = array(
	'title'=>0, 'byline'=>0, 'portrait'=>0, 'js_api'=>'1', 
	'js_swf_id'=>"vimeo-$id", 'autoplay' => $autoplay 
);
if (isset($color)) {
	$options['color'] = $color;
}
$vimeo_src = sprintf(
	'http://player.vimeo.com/video/%s?%s', 
	$id, http_build_query($options)
);
$iframe_options = array(
	'src'=>$vimeo_src, 'width'=>$width, 'height'=>$height, 'frameborder'=>'0',
	'id'=>"vimeo-$id", 'class'=>'vimeo'
);
echo $this->Html->tag('iframe', '', $iframe_options);
