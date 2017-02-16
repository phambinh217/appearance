@extends('Admin::layouts.default',[
	'active_admin_menu' 	=> ['setting', 'setting.appearance', 'setting.appearance.menu'],
	'breadcrumbs' 			=> [
		'title'	=> ['Cài đặt', 'Menu', 'Chỉnh sửa'],
		'url'	=> [
			route('admin.setting.general', route('admin.appearance.menu.index'))
		],
	],
])

@section('page_title', 'Chỉnh sửa menu')
@section('page_sub_title', $menu->name)

@section('content')
	<div class="row">
		<div class="col-sm-4">
			<div class="portlet-group">
				@foreach(\Menu::all() as $menu_item)
				<div class="portlet light bordered">
					<div class="portlet-title">
						<div class="caption">
							<span class="caption-subject bold">{{ $menu_item['name'] }}</span>
						</div>
						<div class="tools">
							<a href="javascript:;" class="expand"> </a>
							<a href="" class="fullscreen"> </a>
						</div>
					</div>
					<div class="portlet-body" style="display: none;">
						<form class="form-horizontal ajax-form" method="POST" action="{{ route('admin.appearance.menu.add', ['id' => $menu_id]) }}">
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
							<form class="ajax-form" method="POST"  action="{{ route('admin.appearance.menu.add-default', ['id' => $menu_id]) }}">
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
		<div class="col-sm-8">
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-list"></i>
						<span class="caption-subject bold uppercase"> Cấu trúc menu </span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="form-body" style="margin-bottom: 15px">
						<div class="dd" id="menu-structor">
							<ol class="dd-list">
								@include('Appearance::admin.components.menu-item', [
									'menu_items' => $menu->items,
								])
							</ol>
						</div>
					</div>
				</div>
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
    <script type="text/javascript" src="{{ url('assets/admin/global/plugins/jquery-nestable/jquery.nestable.js') }}"></script>
    <script type="text/javascript">
    	$(function(){
    		$('#menu-structor').nestable();
    	});
    </script>
@endpush
