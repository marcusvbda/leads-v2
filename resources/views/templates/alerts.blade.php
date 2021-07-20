@if(session('quick.alerts'))
    <show-alert :messages="{{json_encode(session('quick.alerts'))}}"></show-message>
	<?php Session(["quick"=>null]); ?>
@endif