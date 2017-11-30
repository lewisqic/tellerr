<?php

namespace App\Http\Controllers\Account;

use App\Them;
use App\Services\ThemeService;
use App\Http\Controllers\Controller;


class AccountThemeController extends Controller
{

    /**
     * declare our services to be injected
     */
    protected $themeService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ThemeService $ts)
    {
        $this->themeService = $ts;
    }

    /**
     * Show the forms list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.account.themes.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = $this->themeService->dataTables(\Request::all(), app('company')->id);
        return response()->json($data);
    }

    /**
     * Show the forms create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Create',
            'method' => 'post',
            'action' => url('account/forms'),
            'form' => null
        ];
        return view('content.account.themes.create-edit', $data);
    }

    /**
     * Show the forms create page
     *
     * @return view
     */
    public function edit($id)
    {
        $theme = Theme::findOrFail($id);
        $theme = form_accessor($theme);
        $data = [
            'title' => 'Edit',
            'method' => 'put',
            'action' => url('account/forms/' . $id),
            'form' => $theme,
            'component_name_map' => array_merge(Theme::$componentNameMap['left'], Theme::$componentNameMap['right'])
        ];
        return view('content.account.themes.create-edit', $data);
    }

    /**
     * Show an form
     *
     * @return view
     */
    public function show($id)
    {
        $theme = Theme::findOrFail($id);
        $data = [
            'form' => $theme,
        ];
        return view('content.account.themes.show', $data);
    }

    /**
     * Create new form record
     *
     * @return redirect
     */
    public function store()
    {
        $data = array_except(\Request::all(), ['_token', '_method']);
        $data['company_id'] = app('company')->id;
        $data['unique_id'] = uniqid();
        $data['status'] = 'active';
        $theme = $this->themeService->create($data);
        \Msg::success('Payment form has been created successfully!');
        return redir('account/forms');
    }

    /**
     * Create new form record
     *
     * @return redirect
     */
    public function update($id)
    {
        $data = array_except(\Request::all(), ['_token', '_method']);
        $theme = $this->themeService->update($id, $data);
        \Msg::success($theme->title . ' form has been updated successfully!');
        return redir('account/forms');
    }

    /**
     * Delete an form record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $theme = $this->themeService->destroy($id);
        \Msg::success($theme->title . ' form has been deleted successfully! ' . \Html::undoLink('account/forms/' . $theme->id));
        return redir('account/forms');
    }

    /**
     * Restore a form record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $theme = $this->themeService->restore($id);
        \Msg::success($theme->title . ' has been restored successfully!');
        return redir('account/forms');
    }


}