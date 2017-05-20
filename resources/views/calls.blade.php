@extends('layouts.layout')

@section('content')
<div ng-app="myApp" ng-controller="myCtrl">
	<div class="center" style="width: 960px">
		<h2>Calls data</h2>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Date</th>
					<th>Time</th>
					<th>Direction</th>
					<th>Phone Number</th>
					<th>Duration</th>
					<th>Line</th>
					<th>Answered by</th>
					<th>Voicemail</th>
					<th>Archived</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="call in calls | itemsPerPage: 10">
					<td>@{{ formatDate(call.started_at) }}</td>
					<td>@{{ formatTime(call.started_at) }}</td>
					<td style="text-transform: capitalize">@{{ call.direction }}</td>
					<td>@{{ call.number.digits }}</td>
					<td class="text-center">@{{ callDuration(call) }}</td>
					<td>@{{ call.number.name }}</td>
					<td></td>
					<td>
						<span ng-if="call.voicemail !== null">
							Yes
						</span>
						<span ng-if="call.voicemail === null">
							No
						</span>
					</td>
					<td>
						<span ng-if="call.archived">
							Yes
						</span>
						<span ng-if="!call.archived">
							No
						</span>
					</td>
				</tr>
			</tbody>
		</table>

			<dir-pagination-controls max-size="5"></dir-pagination-controls>

	</div>
</div>

@stop

@section('js')

<script type="text/javascript">
	window.calls = {!! $calls !!};
</script>
<script type="text/javascript" src="{{ asset('js/calls.js') }}"></script>

@stop