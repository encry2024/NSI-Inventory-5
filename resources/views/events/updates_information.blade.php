<div class="col-lg-2">
	<label data-toggle="tooltip" data-placement="right" data-html="true" title="{{ date('F d, Y h:i a', strtotime($event->created_at))  }}">
		<i>{{ $event->created_at->diffForHumans() }}</i>
	</label>
</div>
<div class="col-lg-8">
@if($event->user_id != 0)
<a href="{{ route('user.index', $event->user->id) }}">{{ $event->user->name }}</a> 
updates <a href="{{ route('device.edit', $event->subject->device->slug) }}">{{ $event->subject->device->name }}</a> 
{{ class_basename($event->subject->field->category_label) }} from 
{{ $event->device }} 
{!! $event->old_value == '' ? '-' : '<b>'. $event->old_value . '<b/> to ' . $event->new_value !!}
@endif
</div>