<?php

namespace App\Http\Controllers\Account;

use App\Theme;
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
     * Show the themes list page
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
     * Show the themes create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Create',
            'method' => 'post',
            'action' => url('account/themes'),
            'theme' => null
        ];
        return view('content.account.themes.create-edit', $data);
    }

    /**
     * Show the themes create page
     *
     * @return view
     */
    public function edit($id)
    {
        $theme = Theme::findOrFail($id);
        $data = [
            'title' => 'Edit',
            'method' => 'put',
            'action' => url('account/themes/' . $id),
            'theme' => $theme,
        ];
        return view('content.account.themes.create-edit', $data);
    }

    /**
     * Show an theme
     *
     * @return view
     */
    public function show($id)
    {
        $theme = Theme::findOrFail($id);
        $data = [
            'theme' => $theme,
        ];
        return view('content.account.themes.show', $data);
    }

    /**
     * Create new theme record
     *
     * @return redirect
     */
    public function store()
    {
        $data = array_except(\Request::all(), ['_token', '_method']);
        $data['company_id'] = app('company')->id;
        $theme = $this->themeService->create($data);
        \Msg::success('Theme has been created successfully!');
        return redir('account/themes');
    }

    /**
     * Create new theme record
     *
     * @return redirect
     */
    public function update($id)
    {
        $data = array_except(\Request::all(), ['_token', '_method']);
        $theme = $this->themeService->update($id, $data);
        \Msg::success($theme->title . ' theme has been updated successfully!');
        return redir('account/themes');
    }

    /**
     * Delete an theme record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $theme = $this->themeService->destroy($id);
        \Msg::success($theme->title . ' theme has been deleted successfully! ' . \Html::undoLink('account/themes/' . $theme->id));
        return redir('account/themes');
    }

    /**
     * Restore a theme record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $theme = $this->themeService->restore($id);
        \Msg::success($theme->title . ' has been restored successfully!');
        return redir('account/themes');
    }


}