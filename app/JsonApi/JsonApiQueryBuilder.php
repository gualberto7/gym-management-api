<?php

namespace App\JsonApi;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
class JsonApiQueryBuilder
{
    public function applySorts(): Closure
    {
        /** @var Builder $this */
        return function ($allowedSorts) {
            return $this->when(request()->filled('sort'), function ($query) use ($allowedSorts) {
                $sortField = request()->input('sort');
                $sortDirection = 'asc';

                if (Str::of($sortField)->startsWith('-')) {
                    $sortDirection = 'desc';
                    $sortField = Str::of($sortField)->substr(1);
                }

                if (!in_array($sortField, $allowedSorts)) {
                    abort(400, 'Invalid sort field');
                }

                return $query->orderBy($sortField, $sortDirection);
            });
        };
    }

    public function applyFilters(): Closure
    {
        /** @var Builder $this */
        return function ($allowedFilters) {
            foreach (request('filter', []) as $filter => $value) {
                abort_unless(in_array($filter, $allowedFilters), 400, 'Invalid filter field');

                if (Str::contains($filter, '.')) {
                    [$relation, $relationFilter] = explode('.', $filter);
                    $this->whereHas($relation, function ($query) use ($relationFilter, $value) {
                        $query->where($relationFilter, 'LIKE', "%$value%");
                    });
                } else {
                    $this->hasNamedScope($filter)
                        ? $this->{$filter}($value)
                        : $this->where($filter, 'LIKE', "%$value%");
                }
            }
            return $this;
        };
    }

    public function jsonPaginate(): Closure
    {
        return function () {
            /** @var Builder $this */
            return $this->paginate(
                $perPage = request()->input('page.size', 15),
                $columns = ['*'],
                $pageName = 'page[number]',
                $page = request()->input('page.number', 1)
            )->appends(request()->only('sort', 'filter', 'page.size'));
        };
    }
}
