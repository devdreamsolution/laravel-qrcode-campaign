@extends('layouts/contentLayoutMaster')

@section('title', trans('locale.user.list'))

@section('vendor-style')
	{{-- vendor css files --}}
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection


@section('page-style')
	<style>
		.custom-select {
			height: auto;
		}
	</style>
@endsection

@section('content')
	@if (session()->get('message'))
	<div class="alert alert-primary alert-dismissible fade show" role="alert">
		<p class="mb-0">
			{{ session()->get('message') }}
		</p>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif

	<div class="card">
		<div class="card-header">
			<a href="{{ route('users.create') }}" class="btn btn-primary mr-1 mb-1 waves-effect waves-light"><i class="fa fa-plus"></i> @lang('locale.user.create')</a>
		</div>
		<div class="card-content">
			<div class="card-body">
				<div class="table-responsive">
					<table id="userTable" class="table table-striped">
						<thead>
							<tr>
								<th>@lang('locale.id')</th>
								<th>@lang('locale.Name')</th>
								<th>@lang('locale.Surname')</th>
								<th>@lang('locale.Email')</th>
								<th>@lang('locale.user.lock')</th>
								<th>@lang('locale.CreatedAt')</th>
								<th>@lang('locale.UpdatedAt')</th>
								<th>@lang('locale.Actions')</th>
							</tr>
						</thead>
						<tbody>
							@foreach($users as $user)
							<tr>
								<td>{{ $user->id }}</td>
								<td>{{ $user->name }}</td>
								<td>{{ $user->surname }}</td>
								<td>{{ $user->email }}</td>
								<td>
									<div class="custom-control custom-switch custom-switch-danger switch-lg mr-2">
										<input id="locked_{{ $user->id }}" class="custom-control-input" type="checkbox" {{ $user->islocked == 1 ? "checked" : "" }} onchange="lockUser(this.checked, {{ $user->id }})" {{ Auth::user()->id == $user->id ? "disabled" : "" }}>
										<label class="custom-control-label" for="locked_{{ $user->id }}">
											<span class="switch-text-left">Locked</span>
											<span class="switch-text-right">Unlocked</span>
										</label>
									</div>
							  	</td>
								<th>{{ $user->created_at }}</th>
								<th>{{ $user->updated_at }}</th>
								<td>
									<form id="deleteForm{{ $user->id }}" action="{{ route('roles.destroy', $user->id) }}" method="POST" style="display: none;">
										@csrf
										@method('DELETE')
									</form>
									<a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon rounded-circle btn-flat-success waves-effect waves-light">
										<i class="feather icon-edit"></i>
									</a>
									@if (Auth::user()->id != $user->id)
									<a href="javascript:deleteUser({{ $user->id }})" class="btn btn-icon rounded-circle btn-flat-danger waves-effect waves-light">
										<i class="feather icon-trash-2"></i>
									</a>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('vendor-script')
	{{-- vendor files --}}
	<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
	<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
	<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection

@section('page-script')
	<script>
		$(document).ready(function() {
			$('#userTable').DataTable();
		});

		function deleteUser(userId) {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!',
				confirmButtonClass: 'btn btn-primary',
				cancelButtonClass: 'btn btn-danger ml-1',
				buttonsStyling: false,
			}).then(function (result) {
				if (result.value) {
					$('#deleteForm'+userId).submit();
				}
			})
		}
	</script>
@endsection