<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use App\Traits\General;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use General;
    public function staticMenu()
    {
        $data['title'] = 'Static Menu List';
        $data['navMenuParentActiveClass'] = 'mm-active';
        $data['subNavStaticMenuIndexActiveClass'] = 'mm-active';
        $data['menus'] = Menu::where('type', 1)->paginate(25);

        return view('admin.menu.static-menu-list', $data);
    }

    public function staticMenuUpdate(Request $request, $slug)
    {
        $menu = Menu::where('slug', $slug)->firstOrFail();
        $menu->name = $request->name;
        $menu->status = $request->status ;
        $menu->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function dynamicMenu()
    {
        $data['title'] = 'Dynamic Menu List';
        $data['navMenuParentActiveClass'] = 'mm-active';
        $data['subNavDynamicMenuIndexActiveClass'] = 'mm-active';
        $data['menus'] = Menu::where('type', 2)->paginate(25);
        $data['urls'] = Page::all();

        return view('admin.menu.dynamic-menu-list', $data);
    }

    public function dynamicMenuStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,NULL,id,type,2',
        ]);

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->type = 2;
        $menu->status = $request->status;
        $menu->save();

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function dynamicMenuUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,'.$id.',id,type,2',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->type = 2;
        $menu->status = $request->status;
        $menu->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function dynamicMenuDelete($id)
    {
        Menu::findOrFail($id)->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }
   
    public function footerCompanyMenu()
    {
        $data['title'] = 'Footer Company Menu List';
        $data['navMenuParentActiveClass'] = 'mm-active';
        $data['subNavFooterCompanyMenuIndexActiveClass'] = 'mm-active';
        $data['menus'] = Menu::where('type', 3)->paginate(25);
        $data['urls'] = Page::all();

        return view('admin.menu.footer-company-menu-list', $data);
    }

    public function footerCompanyMenuStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,NULL,id,type,3',
        ]);

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->type = 3;
        $menu->status = $request->status;
        $menu->save();

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function footerCompanyMenuUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,'.$id.',id,type,3',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->type = 3;
        $menu->status = $request->status;
        $menu->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function footerCompanyMenuDelete($id)
    {
        Menu::findOrFail($id)->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }
   
    public function footerSupportMenu()
    {
        $data['title'] = 'Footer Support Menu List';
        $data['navMenuParentActiveClass'] = 'mm-active';
        $data['subNavFooterSupportMenuIndexActiveClass'] = 'mm-active';
        $data['menus'] = Menu::where('type', 4)->paginate(25);
        $data['urls'] = Page::all();

        return view('admin.menu.footer-support-menu-list', $data);
    }

    public function footerSupportMenuStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,NULL,id,type,4',
        ]);

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->type = 4;
        $menu->status = $request->status;
        $menu->save();

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function footerSupportMenuUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,'.$id.',id,type,4',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->type = 4;
        $menu->status = $request->status;
        $menu->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function footerSupportMenuDelete($id)
    {
        Menu::findOrFail($id)->delete();

        $this->showToastrMessage('success', __('Deleted Successful'));
        return redirect()->back();
    }


}
