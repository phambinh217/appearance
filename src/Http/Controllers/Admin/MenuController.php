<?php

namespace Phambinh\Appearance\Http\Controllers\Admin;

use Illuminate\Http\Request;
use AdminController;
use Validator;
use Phambinh\Appearance\Models\Menu;
use Phambinh\Appearance\Models\MenuItem;

class MenuController extends AdminController
{
    public function index()
    {
        $menus = Menu::get();
        $this->data['menus'] = $menus;

        \Metatag::set('title', 'Danh sách menu');
        return view('Appearance::admin.menu.index', $this->data);
    }

    public function menuEdit($id)
    {
        $menu = Menu::findOrFail($id);
        $this->data['menu'] = $menu;
        $this->data['menu_id'] = $id;
        
        \Metatag::set('title', 'Chỉnh sửa menu');
        return view('Appearance::admin.menu.edit', $this->data);
    }

    public function menuUpdate()
    {
    }

    public function menuStore(Request $request)
    {
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

    public function menuUpdateStruct(Request $request, $id)
    {
        $this->validate($request, [
            'struct' => 'required',
        ]);
        
        $menu = Menu::find($id);
        $menu->updateStruct(json_decode($request->input('struct'), true));

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã lưu cấu trúc mới',
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
        
        MenuItem::where('parent_id', $menu_item->id)->update(['parent_id' => $menu_item->parent_id]);
        $menu_item->delete();

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Thành công',
                'message' => 'Đã xóa menu',
            ]);
        }

        return redirect()->back();
    }
}