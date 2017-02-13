@extends('Admin::layouts.default',[
	'active_admin_menu' 	=> ['setting', 'setting.appearance', 'setting.appearance.menu'],
	'breadcrumbs' 			=> [
		'title'	=> ['Cài đặt', 'Giao diện', 'Menu'],
		'url'	=> [
			route('admin.appearance.menu'),
			route('admin.appearance.menu'),
		],
	],
])

@section('page_title', 'Cài đặt menu')

@section('content')
	<div class="tabbable-line">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#menus" data-toggle="tab"> Menus </a>
			</li>
			<li>
				<a href="#new-menu" data-toggle="tab"> Thêm mới </a>
			</li>
			<li>
				<a href="#set-menu" data-toggle="tab"> Đặt vị trí </a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="menus">
				<div class="row">
					<div class="col-sm-4">
						<form class="form-horizontal ajax-form" method="POST" action="">
							<div class="form-body">
								<div class="form-group">
									<label class="control-lalel col-sm-4 pull-left">
										Chọn menu
									</label>
									<div class="col-sm-8">
										<select class="form-control" @change="changeMenu($event)">
											<option v-for="menu_item in all_menu" :selected="menu_item.id == menu_id" :value="menu_item.id">@{{ menu_item.name }}</option>
										</select>
									</div>
								</div>
							</div>
							<div class="mb-10">
								<div class="text-right">
									<span data-toggle="collapse" class="text-primary cur-pointer" data-target="#edit-menu">Chỉnh sửa</span> / 
									<span class="text-danger cur-pointer">Xóa</span>
								</div>
								<div id="edit-menu" class="collapse">
									<div class="form-body">
										<div class="form-group">
											<label class="control-lalel col-sm-4 pull-left">
												Tên menu
											</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" :value="menu.name">
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-lalel col-sm-4 pull-left">
												Slug
											</label>
											<div class="col-sm-8">
												<input type="text" class="form-control" :value="menu.slug">
											</div>
										</div>
									</div>
									<div class="form-actions util-btn-margin-bottom-5">
										<button class="btn btn-primary full-width-xs">
											<i class="fa fa-save"></i> Cập nhật menu
										</button>
									</div>
								</div>
							</div>
						</form>
						<div class="portlet-group">
							@foreach(\Menu::all() as $menu_item)
								<div class="portlet light bordered">
									<div class="portlet-title">
										<div class="caption">
											<span class="caption-subject bold">{{ $menu_item['name'] }}</span>
										</div>
										<div class="tools">
	                                        <a href="javascript:;" class="collapse"> </a>
	                                        <a href="" class="fullscreen"> </a>
	                                    </div>
									</div>
									<div class="portlet-body">
										<form class="form-horizontal ajax-form" method="POST" :action="'{{ admin_url('setting/appearance/menu') }}/'+menu.id">
											<div class="form-body"  style="margin: 15px 0;">
												<div class="scroller" style="height:200px;" data-rail-visible="1" data-rail-color="yellow" data-handle-color="#a1b2bd">
													{{ csrf_field() }}
													<input type="hidden" name="type" value="{{ $menu_item['type'] }}">
													@include('Appearance::admin.components.form-checkbox-menu-item', [
														'items' => $menu_item['type']::get(),
														'name' => 'object_id[]',
													])
												</div>
											</div>
											<div class="form-actions util-btn-margin-bottom-5">
												<button class="btn btn-primary full-width-xs">
													<i class="fa fa-plus"></i> Thêm
												</button>
											</div>
										</form>
									</div>
								</div>
							@endforeach
							<div class="portlet light bordered">
								<div class="portlet-title">
									<div class="caption">
										<span class="caption-subject bold">Liên kết tùy chỉnh</span>
									</div>
									<div class="tools">
	                                    <a href="javascript:;" class="collapse"> </a>
	                                    <a href="" class="fullscreen"> </a>
	                                </div>
								</div>
								<div class="portlet-body">
									<form class="ajax-form" method="POST" :action="'{{ admin_url('setting/appearance/menu') }}/'+menu.id+'/default'">
										<div class="form-body">
											{{ csrf_field() }}
											<input type="hidden" name="type" value="custom-link">
											<div class="form-group">
												<label class="control-lalel">Tên menu</label>
												<input name="menu_item[title]" type="text" class="form-control" />
											</div>
											<div class="form-group">
												<label class="control-lalel">Url</label>
												<input name="menu_item[url]" type="text" class="form-control" />
											</div>
										</div>
										<div class="form-actions util-btn-margin-bottom-5">
											<button class="btn btn-primary full-width-xs">
												<i class="fa fa-plus"></i> Thêm
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="new-menu">
				<form class="form-horizontal ajax-form" method="POST" action="{{ route('admin.appearance.menu.store') }}">
					{{ csrf_field() }}
					<div class="form-body">
						<div class="form-group">
							<label class="control-lalel col-sm-3 pull-left">
								Tên menu
							</label>
							<div class="col-sm-3">
								<input type="text" name="menu[name]" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-lalel col-sm-3 pull-left">
								Slug
							</label>
							<div class="col-sm-3">
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
			<div class="tab-pane fade" id="set-menu">
				<form class="form-horizontal ajax-form" method="POST" action="">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<div class="form-body">
						@foreach(\Menu::locationAll() as $location_item)
							<div class="form-group">
								<label class="control-lalel col-sm-3 pull-left">
									{{ $location_item['name'] }}
								</label>
								<div class="col-sm-3">
									@include('Appearance::admin.components.form-select-menu', [
										'class' => 'widh-auto',
										'menus' => $menus,
										'name' => 'menuname',
										'selected' => '',
									])
								</div>
							</div>
						@endforeach
					</div>
					<div class="form-actions util-btn-margin-bottom-5">
						<button class="btn btn-primary full-width-xs">
							<i class="fa fa-save"></i> Lưu
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('css')
	<link href="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ url('assets/admin/global/plugins/jquery-nestable/jquery.nestable.css')}}" rel="stylesheet" type="text/css" />
@endpush

@push('js_footer')
	<script type="text/javascript" src="{{ url('assets/admin/global/plugins/jquery-form/jquery.form.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/jquery-nestable/jquery.nestable.js') }}"></script>
@endpush
