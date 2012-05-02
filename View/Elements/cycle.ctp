<?php
$this->Html->script('/vimeo/js/jquery/jquery.cycle.all.js', array('inline' => false));
$this->Js->buffer('
	$("'.$selector.'").cycle();
	VimeoEmbed.init();
');
echo $this->Html->script('http://a.vimeocdn.com/js/froogaloop.min.js');
echo $this->Html->scriptBlock("
	var VimeoEmbed = {};
	var player = $('$selector');
	
	//Called on document ready
	VimeoEmbed.init = function(e) {
		//Listen to the load event for all the iframes on the page
		$('iframe').each(function(index, iframe){
			iframe.addEvent('onLoad', VimeoEmbed.vimeo_player_loaded);
		});
	};

	//EVENT CALLBACKS
	/*
	 * Called when the player finishes loading. The JavaScript API is only available
	 * after this event fires.
	 *
	 * @param player_id (String): ID of the iframe which has finished loading.
	 */
	VimeoEmbed.vimeo_player_loaded = function(player_id) {
		// events
		var iframe = $('#'+player_id).get(0);
		iframe.addEvent('onPlay', function(e){
			player.cycle('pause');
		});
		iframe.addEvent('onFinish', function(e){
			player.cycle('resume');
		});
		//iframe.addEvent('onPause', function(e){
			//player.cycle('resume');
		//});
	};
");
