@extends('Cms::layouts.default',[
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
									<label class="control-label">Tên menu</label>
									<input name="menu_item[title]" type="text" class="form-control" />
								</div>
								<div class="form-group">
									<label class="control-label">Url</label>
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
			<div class="portlet light bordered form-fit">
				<div class="portlet-title with-tab">
					<div class="tab-default">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#menu-struct" data-toggle="tab" aria-expanded="true"> Cấu trúc menu </a>
							</li>
							<li class="">
								<a href="#menu-info" data-toggle="tab" aria-expanded="false"> Menu </a>
							</li>
						</ul>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="tab-content">
						<div class="tab-pane active" id="menu-struct">
							<div class="dd" id="menu-structor" style="padding: 15px">
								<ol class="dd-list">
									@include('Appearance::admin.components.menu-item', [
										'menu_items' => $menu->items->sortBy('order'),
									])
								</ol>
							</div>
							<div class="form-actions util-btn-margin-bottom-5" style="padding: 15px">
								<form class="ajax-form form-horizontal form-bordered" action="{{ route('admin.appearance.menu.update.struct', ['id' => $menu_id]) }}" method="POST">
									{{ method_field('PUT') }}
									{{ csrf_field() }}
									<input type="hidden" name="menu[struct]" value="" />
									<button class="btn btn-primary full-width-xs">
										<i class="fa fa-save"></i> Lưu cấu trúc
									</button>
								</form>
							</div>
						</div>
						<div class="tab-pane" id="menu-info">
							<form class="ajax-form form-horizontal form-bordered" action="{{ route('admin.appearance.menu.update', ['id' => $menu_id]) }}" method="POST">
								{{ method_field('PUT') }}
								{{ csrf_field() }}
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-sm-3">
											Tên menu
										</label>
										<div class="col-sm-9">
											<input type="text" name="menu[name]" class="form-control" value="{{ $menu->name }}" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">
											Slug
										</label>
										<div class="col-sm-9">
											<input type="text" name="menu[slug]" class="form-control" value="{{ $menu->slug }}" />
											<label class="checkbox-inline">
												<input type="checkbox" value="true" checked="" id="create-slug">
												Từ tên menu
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-3">
											Vị trí
										</label>
										<div class="col-sm-9">
											@include('Appearance::admin.components.form-select-location', [
												'name' => 'menu[location]',
												'locations' => \Menu::locationAll(),
												'selected' => $menu->location,
											])
										</div>
									</div>
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
    		$('#menu-structor').nestable().on('change', function(e){
    			$('input[name="menu[struct]"]').val(getMenuStruct());
    		});
    		function getMenuStruct() {
    			return JSON.stringify($('#menu-structor').nestable('serialize'));
    		}
    		function updateStruct() {
    			$('input[name="menu[struct]"]').val(getMenuStruct());
    		}
    		updateStruct();
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

			$('.delete-menu-item').click(function(e){
				e.preventDefault();
				if (!confirm('Bạn có chắc muốn xóa')) {
					return false;
				}
				var url = $(this).attr('href');
				$.ajax({
					url: url,
					dataType: 'json',
					type: 'post',
					data: {
						_token: csrfToken(),
						_method: 'DELETE',
					},
				});
			});
    	});
    </script>
@endpush
