<?php

namespace Phambinh\Appearance\Http\Controllers\Admin;

use Illuminate\Http\Request;
use AdminController;
use Validator;
use Phambinh\Appearance\Models\Menu;
use Phambinh\Appearance\Models\MenuItem;

class MenuController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $menus = Menu::get();
        $this->data['menus'] = $menus;

        \Metatag::set('title', 'Danh sách menu');
        $this->authorize('admin.appearance.menu.index');
        return view('Appearance::admin.menu.index', $this->data);
    }

    public function menuEdit($id)
    {
        $menu = Menu::with('items')->findOrFail($id);
        
        $this->data['menu'] = $menu;
        $this->data['menu_id'] = $id;
        
        \Metatag::set('title', 'Chỉnh sửa menu');
        $this->authorize('admin.appearance.menu.edit', $menu);
        return view('Appearance::admin.menu.edit', $this->data);
    }

    public function menuUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'menu.name' => 'required',
            'menu.slug' => '',
            'menu.location' => '',
        ]);

        $menu = Menu::find($id);

        $this->authorize('admin.appearance.menu.edit', $menu);

        $menu->fill($request->input('menu'));

        if (!empty($menu->slug)) {
            $menu->slug = str_slug($menu->name);
        }
        
        $menu->save();

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã lưu menu',
            ]);
        }

        return redirect()->back();
    }

    public function menuUpdateStruct(Request $request, $id)
    {
        $this->validate($request, [
            'menu.struct' => 'required',
        ]);
        
        $menu = Menu::find($id);
        
        $this->authorize('admin.appearance.menu.edit', $menu);

        $menu->updateStruct(json_decode($request->input('menu.struct'), true));

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã lưu cấu trúc mới',
            ]);
        }

        return redirect()->back();
    }

    public function menuStore(Request $request)
    {
    	$this->checkAuthorize('admin.appearance.menu.create');
        $this->validate($request, [
            'menu.name' => 'required',
            'menu.slug' => '',
        ]);

        $menu = new Menu();
        $menu->fill($request->input('menu'));
        
        if (!empty($menu->slug)) {
            $menu->slug = str_slug($menu->name);
        }

        $menu->save();

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã thêm menu mới',
                'redirect' => route('admin.appearance.menu.index', ['menu_id' => $menu->id]),
            ]);
        }

        return reidrect()->back();
    }

    public function menuAdd(Request $request, $id)
    {
        $this->validate($request, [
            'object_id' => 'required',
            'type' => 'required',
        ]);

        $menu = Menu::find($id);
        $this->authorize('admin.appearance.menu.edit', $menu);

        $type = $request->input('type');
        $objects = $type::whereIn('id', $request->input('object_id'))->get();

        foreach ($objects as $object) {
            $object->addToMenu($id);
        }

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã thêm vào menu',
                'redirect' => route('admin.appearance.menu.edit', ['menu_id' => $id]),
            ]);
        }

        return redirect()->back();
    }

    public function menuAddByDefault(Request $request, $id)
    {
        $this->validate($request, [
            'menu_item.title' => 'required',
            'menu_item.url' => '',
        ]);

        $menu = Menu::find($id);

        $this->authorize('admin.appearance.menu.edit', $menu);

        $menu->items()->create($request->input('menu_item'));

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã thêm vào menu',
                'redirect' => route('admin.appearance.menu.edit', ['menu_id' => $id]),
            ]);
        }

        return redirect()->back();
    }

    public function menuItemUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'menu_item.title' => 'required',
            'menu_item.url' => '',
        ]);

        $menu_item = MenuItem::find($id);
        
        $this->authorize('admin.appearance.menu.edit');

        $menu_item->fill($request->input('menu_item'))->save();

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã lưu menu',
            ]);
        }

        return redirect()->back();
    }

    public function menuItemDestroy(Request $request, $id)
    {
        $menu_item = MenuItem::find($id);
        $this->authorize('admin.appearance.menu.edit');
        MenuItem::where('parent_id', $menu_item->id)->update(['parent_id' => $menu_item->parent_id]);
        $menu_item->delete();

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã xóa menu',
                'redirect' => route('admin.appearance.menu.edit', ['id' => $menu_item->menu_id])
            ]);
        }

        return redirect()->back();
    }

    public function menuDestroy(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $this->authorize('admin.appearance.menu.edit');
        $menu->items()->delete();
        $menu->delete();

        return response()->json([
            'title' => 'Thành công',
            'message' => 'Đã xóa menu',
        ]);

        return redirect()->back();
    }
}
