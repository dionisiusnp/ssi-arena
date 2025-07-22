<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CodeBlock;
use App\Services\CodeBlockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CodeBlockController extends Controller
{
    protected $codeBlockService;

    public function __construct(CodeBlockService $codeBlockService)
    {
        $this->codeBlockService = $codeBlockService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('q') ?? null,
            'user_id' => Auth::id(), // Only show code blocks for the current user
        ];
        $data = $this->codeBlockService->paginate($filters);
        return view('admin.code_blocks.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.code_blocks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code_content' => 'required',
            'language' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $this->codeBlockService->store($request->all(), Auth::user());
            return redirect()->route('code-blocks.index')->with('success', 'Code block created successfully.');
        } catch (\Throwable $th) {
            return back()->withInput()->withErrors(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CodeBlock $codeBlock)
    {
        // Ensure user owns the code block
        if ($codeBlock->user_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.code_blocks.show', compact('codeBlock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CodeBlock $codeBlock)
    {
        // Ensure user owns the code block
        if ($codeBlock->user_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.code_blocks.edit', compact('codeBlock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CodeBlock $codeBlock)
    {
        // Ensure user owns the code block
        if ($codeBlock->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'code_content' => 'required',
            'language' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $this->codeBlockService->update($request->all(), $codeBlock);
            return redirect()->route('code-blocks.index')->with('success', 'Code block updated successfully.');
        } catch (\Throwable $th) {
            return back()->withInput()->withErrors(['error' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CodeBlock $codeBlock)
    {
        // Ensure user owns the code block
        if ($codeBlock->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->codeBlockService->destroy($codeBlock);
            return redirect()->route('code-blocks.index')->with('success', 'Code block deleted successfully.');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource for API consumption.
     */
    public function showApi(CodeBlock $codeBlock)
    {
        return response()->json($codeBlock);
    }
}
