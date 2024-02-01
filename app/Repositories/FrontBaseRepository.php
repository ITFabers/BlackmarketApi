<?php
// app/Repositories/BaseRepository.php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class FrontBaseRepository {
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    // Get all records
    public function all() {
        return $this->model::all();
    }

    public function allWhere($where, $sort) {
        $query = $this->model::query();

        // Apply the "where" condition
        foreach ($where as $column => $value) {
            $query->where($column, $value);
        }

        // Apply sorting
        if (!empty($sort['column']) && !empty($sort['direction'])) {
            $query->orderBy($sort['column'], $sort['direction']);
        }

        // Retrieve the results
        return $query->get();
    }


    // Find a record by ID
    public function find($id) {
        return $this->model::find($id);
    }

    public function takeWhere($columns, $relations = [], $limit = '') {
        $query = $this->model::query();

        foreach ($columns as $column => $value) {
            $query->where($column, $value);
        }

        if(!empty($relations)) {
            $query->with($relations);
        }

        if (!empty($limit)) {
            $query->take($limit);
        }

        return $query->get();
    }

    public function takeOneWhere($columns, $relations = [], $limit = '') {
        $query = $this->model::query();

        foreach ($columns as $column => $value) {
            $query->where($column, $value);
        }

        if(!empty($relations)) {
            $query->with($relations);
        }

        if (!empty($limit)) {
            $query->take($limit);
        }

        return $query->first();
    }

    public function paginate($page = 1, $perPage, $columns = ['*']) {
        $offset = ($page - 1) * $perPage;

        return $this->model::query()
            ->skip($offset)
            ->take($perPage)
            ->get($columns);
    }

    public function paginateWhere($columns, $page = 1, $perPage = 15) {
        $query = $this->model::query();

        foreach ($columns as $column => $value) {
            $query->where($column, $value);
        }

        $offset = ($page - 1) * $perPage;

        $results = $query->skip($offset)
            ->take($perPage)
            ->get();

        return $results;
    }


    public function paginateRelation($columns, $page = 1, $perPage = 15, $relations = [])
    {
        $query = $this->model::query();

        foreach ($columns as $column => $value) {
            $query->where($column, $value);
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        $result = $query->first(); // Fetch the main model instance
        // dd($relations);
        $relationName = key(array_flip($relations)); // Assuming only one relation for simplicity
        $relation = $result->$relationName(); // Get the relation instance

        $offset = ($page - 1) * $perPage;

        $results = $relation->paginate($perPage, ['*'], 'page', $page);

        return $results;
    }


}
