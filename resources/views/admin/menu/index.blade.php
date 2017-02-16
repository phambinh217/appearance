@extends('Admin::layouts.default',[
	'active_admin_menu' 	=> ['setting', 'setting.appearance', 'setting.appearance.menu'],
	'breadcrumbs' 			=> [
		'title'	=> ['Cài đặt', 'Giao diện', 'Menu'],
		'url'	=> [
			route('admin.setting.general', route('admin.appearance.menu.index'))
		],
	],
])

@section('page_title', 'Danh sách menu')

@section('content')
<div class="row">
	<div class="col-sm-4">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<span class="caption-subject bold">Thêm menu mới</span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="collapse"> </a>
					<a href="" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<form class="form-horizontal ajax-form" method="POST" action="{{ route('admin.appearance.menu.store') }}">
					{{ csrf_field() }}
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-sm-3 pull-left">
								Tên menu
							</label>
							<div class="col-sm-9">
								<input type="text" name="menu[name]" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3 pull-left">
								Slug
							</label>
							<div class="col-sm-9">
								<input type="text" name="menu[slug]" class="form-control" />
								<label class="checkbox-inline">
									<input type="checkbox" value="true" checked="" id="create-slug">
									Từ tên menu
								</label>
							</div>
						</div>
					</div>
					<div class="form-actions util-btn-margin-bottom-5">
						<button class="btn btn-primary full-width-xs">
							<i class="fa fa-save"></i> Thêm
						</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="table-function-container">
			<div class="table-responsive main">
				<table class="master-table table table-striped table-hover table-checkable order-column pb-items">
					<thead>
						<tr>
							<th width="50" class="table-checkbox text-center">
								<div class="checker">
									<input type="checkbox" class="icheck check-all">
								</div>
							</th>
							<th class="text-center">ID</th>
							<th>Tên menu</th>
							<th class="hidden-xs">Vị trí</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($menus as $menu_item)
						<tr>
							<td width="50" class="table-checkbox text-center">
								<div class="checker">
									<input type="checkbox" class="icheck" value="{{ $menu_item->id }}">
								</div>
							</td>
							<td class="text-center"><strong>{{ $menu_item->id }}</strong></td>
							<td>
								<a href="{{ route('admin.appearance.menu.edit', ['id' => $menu_item->id]) }}"><strong>{{ $menu_item->name }}</strong></a>
							</td>
							<td class="hidden-xs">{{ $menu_item->location('name') }}</td>
							<td>
								<div class="btn-group pull-right" table-function>
									<a href="" class="btn btn-circle btn-xs grey-salsa btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
										<span class="hidden-xs">
											Chức năng
											<span class="fa fa-angle-down"> </span>
										</span>
										<span class="visible-xs">
											<span class="fa fa-cog"> </span>
										</span>
									</a>
									<ul class="dropdown-menu pull-right">
										<li><a href="{{ route('admin.appearance.menu.edit', ['id' => $menu_item->id]) }}"><i class="fa fa-pencil"></i> Sửa</a></li>
										<li class="divider"></li>
										<li><a data-function="destroy" data-method="delete" href="{{ route('admin.appearance.menu.destroy', ['id' => $menu_item->id]) }}"><i class="fa fa-times"></i> Xóa</a></li>
									</ul>
								</div>
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


@push('css')
	<link href="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ url('assets/admin/global/plugins/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js_footer')
	<script type="text/javascript" src="{{ url('assets/admin/global/plugins/jquery-form/jquery.form.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('assets/admin/global/plugins/icheck/icheck.min.js')}} "></script>
	<script type="text/javascript">
		$(function(){
			$('#create-slug').click(function() {
				if(this.checked) {
					var title = $('input[name="menu[name]"]').val();
					var slug = strSlug(title);
					$('input[name="menu[slug]"]').val(slug);
				}
			});

			$('input[name="menu[name]"]').keyup(function() {
				if ($('#create-slug').is(':checked')) {
					var title = $(this).val();
					var slug = strSlug(title);
					$('input[name="menu[slug]"]').val(slug);	
				}
			});
		});
	</script>
@endpush
