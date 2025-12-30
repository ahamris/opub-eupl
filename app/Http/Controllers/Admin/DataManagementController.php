<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\DataManagementRequest;
use App\Models\DataManagement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataManagementController extends AdminBaseController
{
    /**
     * Display Typesense sync status dashboard.
     */
    public function index(): View
    {
        return view('admin.content.data-management.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.content.data-management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataManagementRequest $request)
    {
        $validated = $request->validated();

        DataManagement::create($validated);

        return redirect()->route('admin.content.data-management.index')
            ->with('success', 'Data entry created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DataManagement $dataManagement): View
    {
        return view('admin.content.data-management.show', compact('dataManagement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataManagement $dataManagement): View
    {
        return view('admin.content.data-management.edit', compact('dataManagement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DataManagementRequest $request, DataManagement $dataManagement)
    {
        $validated = $request->validated();

        $dataManagement->update($validated);

        return redirect()->route('admin.content.data-management.index')
            ->with('success', 'Data entry updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataManagement $dataManagement)
    {
        $dataManagement->delete();

        return redirect()->route('admin.content.data-management.index')
            ->with('success', 'Data entry deleted successfully!');
    }
}
