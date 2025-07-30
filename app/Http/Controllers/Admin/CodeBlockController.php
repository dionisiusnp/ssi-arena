<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CodeBlock;
use App\Models\Schedule;
use App\Services\CodeBlockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CodeBlockController extends Controller
{
    public $codeBlockService;

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

    public function list(Request $request)
    {
        $auth = Auth::user();
        $query = CodeBlock::query()->where('changed_by', $auth->id);
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }
        return response()->json($query->select('id', 'language', 'description')->orderBy('description')->get());
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
            $data = $this->codeBlockService->store($request->toArray(), $auth);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuat',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CodeBlock $syntax)
    {
        return response()->json($syntax);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CodeBlock $syntax)
    {
        $auth = Auth::user();
        if ($syntax->changed_by !== $auth->id) {
            abort(403, 'Akses tidak diizinkan untuk kode ini.');
        }
        return view('admin.sintaks.edit', compact('syntax'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CodeBlock $syntax)
    {
        try {
            $auth = Auth::user();
            $data = $this->codeBlockService->update($request->toArray(), $auth, $syntax);
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
    public function destroy(Schedule $schedule)
    {
        // 
    }
}
