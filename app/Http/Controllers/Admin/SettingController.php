<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Setting::query();

        // Filter by group if provided
        if ($request->has('group') && $request->group !== '') {
            $query->where('group', $request->group);
        }

        $settings = $query->orderBy('group')
            ->orderBy('_key')
            ->get();

        // Get all unique groups for filter
        $groups = Setting::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        $selectedGroup = $request->get('group', '');

        return view('admin.content.setting.index', compact('settings', 'groups', 'selectedGroup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Get all unique groups for dropdown
        $groups = Setting::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->toArray();

        return view('admin.content.setting.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingRequest $request)
    {
        $validated = $request->validated();

        Setting::create($validated);

        return redirect()->route('admin.content.setting.index', ['group' => $validated['group']])
            ->with('success', 'Setting created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting): View
    {
        return view('admin.content.setting.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting): View
    {
        // Get all unique groups for dropdown
        $groups = Setting::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->toArray();

        return view('admin.content.setting.edit', compact('setting', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, Setting $setting)
    {
        $validated = $request->validated();

        $setting->update($validated);

        return redirect()->route('admin.content.setting.index', ['group' => $validated['group']])
            ->with('success', 'Setting updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        $group = $setting->group;
        $setting->delete();

        return redirect()->route('admin.content.setting.index', ['group' => $group])
            ->with('success', 'Setting deleted successfully!');
    }
}
