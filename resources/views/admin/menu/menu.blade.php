@extends('Cms::layouts.default',[
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
				<div id="app-menu">
					<div class="row" v-if="all_menu.length != 0">
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
											<div class="dd" id="menu-structor">
												<ol class="dd-list">
													<li v-for="item_item in getMenuItems(menu_id)" v-on:removemenuitem="removemenuitem(item_item.id)" :menu_item="item_item" :item="getMenuItems(menu_id)" v-if="item_item.parent_id == '0'" is="menuItemCpn"></li>
												</ol>
											</div>
										</div>
									</div>
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
	<link href="{{ asset_url('admin', 'global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset_url('admin', 'global/plugins/jquery-nestable/jquery.nestable.css')}}" rel="stylesheet" type="text/css" />
@endpush

@push('html_footer')
	<script type="text/x-template" id="menu-item-cpn">
		<li class="dd-item dd3-item" :data-id="menu_item.id">
			<div class="dd-handle dd3-handle"></div>
			<div class="dd3-content hover-display-container" data-toggle="collapse" :data-target="'#menu-item-'+menu_item.id">
				@{{ menu_item.title }}
				<span class="hover-display pl-15 hidden-xs pull-right">
					<a href="" @click.prevent="removeMenuItem">Xóa</a>
				</span>
			</div>
			<div :id="'menu-item-'+menu_item.id" class="collapse">
				<form class="form-horizontal ajax-form" :action="'{{ admin_url('setting/appearance/menu-item') }}/'+menu_item.id" method="post">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<div class="form-group">
						<label class="control-lalel col-sm-3">Tên menu</label>
						<div class="col-sm-9">
							<input name="menu_item[title]" type="text" class="form-control input-sm" :value="menu_item.title" @keyup="updateMenuItemTitle($event)"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-lalel col-sm-3">Url</label>
						<div class="col-sm-9">
							<input name="menu_item[url]" type="text" class="form-control input-sm" :value="menu_item.url" />
						</div>
					</div>
					<div class="form-actions util-btn-margin-bottom-5 text-right">
						<button class="btn btn-primary btn-sm">
							Lưu
						</button>
					</div>
				</form>
			</div>
			<ol v-if="hasChild()" class="dd-list">
				<li v-for="item_item in item" v-on:removemenuitem="removemenuitem(item_item.id)" :menu_item="item_item" :item="item" v-if="item_item.parent_id == menu_item.id" is="menuItemCpn"></li>
			</ol>
		</li>
	</script>
@endpush

@push('js_footer')
	<script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/jquery-form/jquery.form.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/jquery-nestable/jquery.nestable.js') }}"></script>
	<script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/vuejs/js/vue.min.js') }}"></script>
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

		Vue.component('menuItemCpn', {
			template: '#menu-item-cpn',
			props: ['menu_item', 'item'],
			data: function() {
				return {};
			},
			methods: {
				hasChild: function() {
					var has = false;
					var item_id = this.menu_item.id;
					this.item.forEach(function(item, index){
						if (item.parent_id == item_id) {
							has = true;
						}
					});
					return has;
				},
				
				updateMenuItemTitle: function(e){
					var title = e.target.value;
					this.menu_item.title = title;
				},

				removeMenuItem: function() {
					if (! confirm('Bạn có chắc muốn xóa')) {
						return false;
					}

					var item_id = this.menu_item.id;
					var app = this;
					$.ajax({
						url: '{{ admin_url('setting/appearance/menu-item') }}/'+item_id,
						type: 'post',
						dataType: 'json',
						data: {
							_method: 'DELETE',
							_token: csrfToken(),
						},
						success: function() {
							app.$emit('removemenuitem');
						},
					});
				},
				removemenuitem: function() {
					this.$emit('removemenuitem');
				},
			},
		});

		var appMenu = new Vue({
			el: '#app-menu',
			data: {
				menu: {!! ! $menus->isEmpty() ? $menu->toJson() : "[]" !!},
				item: [],
				menu_id: {!! $menu_id !!},
				all_menu: {!! ! $menus->isEmpty() ? $menus->toJson() : "[]" !!},
				all_item: {!! ! $menu_items->isEmpty() ? $menu_items->toJson() : "[]" !!},
			},
			mounted: function(){
			    var self = this;
			    self.$nextTick(function(){
			    	$('#menu-structor').nestable({
			    		group: 1,
			    	}).on('change', function (e) {
			    		// var struct = $('#menu-structor').nestable('serialize');
			    		// var data = JSON.stringify(struct);
			    		// self.menuUpdateStruct(struct);
			    		// $.ajax({
			    		// 	url: '{{ admin_url('setting/appearance/menu') }}/'+self.menu_id+'/struct',
			    		// 	dataType: 'json',
			    		// 	type: 'post',
			    		// 	data: {
			    		// 		_token: csrfToken(),
			    		// 		_method: 'PUT',
			    		// 		struct: data,
			    		// 	}
			    		// });
					});
			    });
			},
			methods: {
				changeMenu: function(e) {
					var menu_id = e.target.value;
					this.menu = this.all_menu.filter(function(item){
						if (item.id == menu_id) {
							return item;
						}
					})[0];
					this.menu_id = this.menu.id;
				},

				getMenuItems: function(menu_id) {
					var item = this.all_item.filter(function(item, index){
						if (item.menu_id == menu_id) {
							return item;
						}
					});
					this.item = item;
					return item;
				},

				removemenuitem: function(item_id){
					var app = this;
					var parent_id = this.all_item.filter(function(item, index){
						if (item.id == item_id && item.menu_id == app.menu_id) {
							app.all_item.splice(index, 1);
							return item;
						}
					})[0].parent_id;

					this.all_item.forEach(function(item, index){
						if (item.parent_id == item_id && item.menu_id == app.menu_id) {
							app.all_item[index].parent_id = parent_id;
						}
					});
				},

				menuUpdateStruct: function(menu_items, parent_id){
					this.all_item[3].parent_id = 0;

					var app = this;

					if (!parent_id) {
						parent_id = 0;
					}

					menu_items.forEach(function(item, index){
						if (typeof item['children'] !== 'undefined') {
							app.menuUpdateStruct(item['children'], item.id);
						}
					});

					var same_parent = array_pluck(menu_items, 'id');

					this.all_item.forEach(function(item, index){
						if (same_parent.indexOf(item.id) != -1) {
							// console.log("index", index);
							console.log("Item id", app.all_item[index].id, " needed to update ", parent_id);
							app.all_item[index].parent_id = parent_id;
							console.log("item", app.all_item[index]);
						}
					});
				},
			},
		});
	</script>
@endpush
