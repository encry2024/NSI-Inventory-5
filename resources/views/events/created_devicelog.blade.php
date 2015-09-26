<div class="col-lg-2">
	<label data-toggle="tooltip" data-placement="right" data-html="true" title="{{ date('F d, Y h:i a', strtotime($event->created_at))  }}">
		<i>{{ $event->created_at->diffForHumans() }}</i>
	</label>
</div>
<div class="col-lg-10">
@if($event->user_id != 0)
	<a href="{{ route('user.index', $event->user->id) }}">{{ $event->user->name }}</a>
	{{ $event->old_value }}
	<a href="{{ route('device.edit', $event->subject->device->slug) }}">{{ $event->subject->device->name }}</a>
	to
	<a href="{{ route('owner.show', $event->subject->owner->slug) }}">{{ $event->subject->owner->fullName() }}</a>
@endif
</div>