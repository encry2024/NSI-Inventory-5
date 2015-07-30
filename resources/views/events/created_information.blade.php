<div class="col-lg-2">
	<label data-toggle="tooltip" data-placement="right" data-html="true" title="{{ date('F d, Y h:i a', strtotime($event->created_at))  }}">
		<i>{{ $event->created_at->diffForHumans() }}</i>
	</label>
</div>
<div class="col-lg-8">
@if($event->user_id != 0)
	<a href="{{ route('user.index', $event->user->id) }}">{{ $event->user->name }}</a>
	@if ($event->old_value != '')
		added <a href="{{ route('device.edit', $event->subject->device->slug) }}">{{ $event->subject->value }} </a>
		{{ class_basename($event->subject_type) }} to {{ $event->subject->field->category_label }} {{ class_basename($event->subject->field) }}
	@else
		didn't provide <code>{{ class_basename($event->subject_type) }}</code> to {{ $event->subject->field->category_label }} {{ class_basename($event->subject->field) }}
	@endif
@endif
</div>