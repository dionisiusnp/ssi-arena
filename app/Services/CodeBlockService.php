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

    public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $userId = $filter['user_id'] ?? null;

        return $this->model
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'LIKE', "%{$search}%")
                      ->orWhere('code_content', 'LIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage);
    }

    public function store(array $data, $auth)
    {
        $data['user_id'] = $auth->id;
        return $this->model->create($data);
    }

    public function update(array $data, CodeBlock $codeBlock)
    {
        $codeBlock->update($data);
        return $codeBlock;
    }

    public function destroy(CodeBlock $codeBlock): bool
    {
        return $codeBlock->delete();
    }
}
