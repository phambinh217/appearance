@if(!isset($parent_id)) 
	@php $parent_id = 0; @endphp
@endif

@foreach($menu_items as $item)
	@if($item->parent_id == $parent_id)
		<li class="dd-item dd3-item" data-id="{{ $item->id }}">
			<div class="dd-handle dd3-handle"></div>
			<div class="dd3-content hover-display-container" data-parent="#menu-structor" data-toggle="collapse" data-target="#menu-item-{{ $item->id }}">
				{{ $item->title }}
				<span class="hover-display pl-15 hidden-xs pull-right">
					<a href="">Xóa</a>
				</span>
			</div>
			<div id="menu-item-{{ $item->id }}" class="collapse">
				<form class="form-horizontal ajax-form" action="{{ route('admin.appearance.menu-item.update', ['id' => $item->id]) }}" method="post">
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<div class="form-group">
						<label class="control-lalel col-sm-3">Tên menu</label>
						<div class="col-sm-9">
							<input name="menu_item[title]" type="text" class="form-control input-sm" value="{{ $item->title }}" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-lalel col-sm-3">Url</label>
						<div class="col-sm-9">
							<input name="menu_item[url]" type="text" class="form-control input-sm" value="{{ $item->url }}" />
						</div>
					</div>
					<div class="form-actions util-btn-margin-bottom-5 text-right">
						<button class="btn btn-primary btn-sm">
							Lưu
						</button>
					</div>
				</form>
			</div>
			@if($item->hasChild())
				<ol class="dd-list">
					@include('Appearance::admin.components.menu-item', [
						'menu_items' => $menu_items,
						'parent_id' => $item->id,
					])
				</ol>
			@endif
		</li>
	@endif
@endforeach