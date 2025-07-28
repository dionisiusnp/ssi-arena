<?php

namespace App\Services;

use App\Models\CodeBlock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CodeBlockService
{
    protected Model $model;

    public function __construct(CodeBlock $codeBlock)
    {
        $this->model = $codeBlock;
    }

    public function model()
    {
        return $this->model;
    }

    public function paginate(array $filter = [], $auth, int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        return $this->model
            ->where('changed_by', $auth->id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function store(array $data, $auth)
    {
        try {
            $data['changed_by'] = $auth->id;
            return $this->model->create($data);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, $auth, CodeBlock $codeBlock)
    {
        try {
            $data['changed_by'] = $auth->id;
            $codeBlock->update($data);
            return $codeBlock;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(CodeBlock $codeBlock): bool
    {
        try {
            return $codeBlock->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
