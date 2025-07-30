<?php

namespace App\Services;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TopicService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Topic $topic)
    {
        $this->model = $topic;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('topics.id as id'),
                DB::raw('topics.name as text'),
            ])
            ->when(count($filters), function ($q) use ($filters) {
                $q->where($filters);
            })
            ->limit(10);
        return $data;
    }

    public function paginate(array $filter = [], $auth, int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $lessonId = $filter['lesson_id'] ?? null;
        $visibility = $filter['visibility'] ?? null;

        return $this->model
            ->where('changed_by', $auth->id)
            ->when($lessonId, function ($query) use ($lessonId) {
                $query->where('lesson_id', $lessonId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($visibility, function ($query) use ($visibility) {
                $query->where('visibility', $visibility);
            })
            ->orderBy('sequence')
            ->paginate($perPage);
    }

    public function byLesson($lessonId)
    {
        try {
            return $this->model
            ->where('lesson_id',$lessonId)
            ->orderBy('sequence')->get();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function underSequence(int $lessonId, int $sequence)
    {
        try {
            return $this->model
                ->where('lesson_id', $lessonId)
                ->where('sequence', '>=', $sequence)
                ->orderBy('sequence', 'desc')
                ->get();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function store(array $data, $auth)
    {
        try {
            $lessonId = $data['lesson_id'];
            $targetSequence = (int) $data['sequence'];
            $maxSequence = $this->byLesson($lessonId)->count() + 1;
            if ($targetSequence < $maxSequence) {
                $topicsToShift = $this->underSequence($lessonId, $targetSequence);
                foreach ($topicsToShift as $topic) {
                    $topic->update([
                        'sequence' => $topic->sequence + 1,
                    ]);
                }
            } else {
                $data['sequence'] = $maxSequence;
            }
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

    public function update(array $data, $auth, Topic $topic)
    {
        try {
            $data['changed_by'] = $auth->id;
            $oldSequence = $topic->sequence;
            $newSequence = (int) $data['sequence'];
            $lessonId = $topic->lesson_id;
            if ($oldSequence !== $newSequence) {
                if ($newSequence < $oldSequence) {
                    $this->model
                        ->where('lesson_id', $lessonId)
                        ->where('sequence', '>=', $newSequence)
                        ->where('sequence', '<', $oldSequence)
                        ->orderBy('sequence', 'desc')
                        ->get()
                        ->each(function ($t) {
                            $t->update(['sequence' => $t->sequence + 1]);
                        });
                } elseif ($newSequence > $oldSequence) {
                    $this->model
                        ->where('lesson_id', $lessonId)
                        ->where('sequence', '<=', $newSequence)
                        ->where('sequence', '>', $oldSequence)
                        ->orderBy('sequence', 'asc')
                        ->get()
                        ->each(function ($t) {
                            $t->update(['sequence' => $t->sequence - 1]);
                        });
                }
            }
            $topic->update($data);
            return $topic;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(Topic $topic): bool
    {
        try {
            return $topic->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
