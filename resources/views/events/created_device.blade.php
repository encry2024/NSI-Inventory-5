<div class="col-lg-2">
	<label data-toggle="tooltip" data-placement="right" data-html="true" title="{{ date('F d, Y h:i a', strtotime($event->created_at))  }}">
		<i>{{ $event->created_at->diffForHumans() }}</i>
	</label>
</div>
<div class="col-lg-8">
@if($event->user_id != 0)
<a href="{{ route('user.index', $event->user->id) }}">{{ $event->user->name }}</a> added <a href="{{ route('device.edit', $event->subject->slug) }}">{{ $event->old_value == '' ? '<code>information not given</code>' : $event->old_value }}</a> {{ class_basename($event->subject_type) }}
@endif
</div>