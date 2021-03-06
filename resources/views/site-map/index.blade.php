@extends('layouts.index')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Site Map Generator</div>
				<div class="panel-body">
					@if (!empty($task))
						@include('site-map.partial.task-status')
					@endif
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="GET" action="{{ route('site-map.submit') }}">
						<div class="form-group">
							<label class="col-md-4 control-label">Url</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="url" value="{{ old('url') }}" placeholder="http://example.com">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Generate</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
