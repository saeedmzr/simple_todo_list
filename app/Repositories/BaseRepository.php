<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;


    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function allWithoutItSelf(int $model_id, array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->where('id', '!=', $model_id)->get($columns);

    }

    public function paginate($numberPerPage = 8)
    {
        return $this->model->paginate($numberPerPage);
    }

    public function sortAndPaginate($model, string $sort, string $type, int $numberPerPage = 10)
    {

        $query = $model->orderby($sort, $type);
        return $query->paginate($numberPerPage);
    }

    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function findById(
        int   $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model
    {
        $object = $this->model->select($columns)->with($relations)->findOrFail($modelId)->append($appends);

        return $object;
    }

    public function findTrashedById(int $modelId): ?Model
    {

        return $this->model->withTrashed()->findOrFail($modelId);
    }

    public function findOnlyTrashedById(int $modelId): ?Model
    {

        return $this->model->onlyTrashed()->findOrFail($modelId);
    }

    public function create(array $payload): ?Model
    {

        $model = $this->model->create($payload);
        return $model;
    }


    public function update(int $modelId, array $payload): bool
    {

        $model = $this->findById($modelId);
        $new_obj = $model->update($payload);
        return $new_obj;
    }

    public function deleteById(int $modelId): bool
    {
        $deleted_item = $this->findById($modelId);
        return $this->findById($modelId)->delete();
    }

    public function restoreById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->restore();
    }

    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->findTrashedById($modelId)->forceDelete();
    }
}
