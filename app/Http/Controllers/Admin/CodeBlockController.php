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
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
        ];
        $data = $this->codeBlockService->paginate($filters, $auth);
        return view('admin.sintaks.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sintaks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $data = $this->codeBlockService->store($request->all(), $auth);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuat',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {            
            throw new \ErrorException($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CodeBlock $syntax)
    {
        if ($syntax->changed_by !== Auth::id()) {
            abort(403);
        }
        return response()->json($syntax);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CodeBlock $syntax)
    {
        if ($syntax->changed_by !== Auth::id()) {
            abort(403);
        }
        return view('admin.sintaks.edit', compact('codeBlock'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CodeBlock $syntax)
    {
        try {
            if ($syntax->changed_by !== Auth::id()) {
                abort(403);
            }
            $auth = Auth::user();
            $data = $this->codeBlockService->update($request->all(), $auth, $syntax);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CodeBlock $syntax)
    {
        if ($syntax->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->codeBlockService->destroy($syntax);
            return redirect()->route('syntax.index')->with('success', 'Code block deleted successfully.');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()]);
        }
    }
}
