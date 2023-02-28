<?php

namespace App\Repositories;

use App\Interfaces\CategoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CategoryRepositories implements CategoryInterface
{
    public function getList(array $data): array
    {
        $query = Category::with('parent')
            ->when(isset($data['name']), function (Builder $query) use ($data) {
                $query->where('name', 'LIKE', '%'.trim($data['name'].'%'));
            })
            ->when(isset($data['parent_id']), function (Builder $query) use ($data) {
                $query->where('parent_id', $data['parent_id']);
            })
            ->when(isset($data['status']), function (Builder $query) use ($data) {
                $query->where('status', $data['status']);
            })
            ->when(isset($data['popular']), function (Builder $query) use ($data) {
                $query->where('popular', $data['popular']);
            })
            ->when($data['onlyTrashed'], function (Builder $query) {
                $query->onlyTrashed();
            });

        $result['total'] = $query->count();

        if (isset($data['iSortCol_0'])) {
            $sorting_mapping_array = [
                '5' => 'created_at',
                '6' => 'updated_at',
            ];

            $order = 'desc';
            if (isset($data['sSortDir_0'])) {
                $order = $data['sSortDir_0'];
            }

            if (isset($sorting_mapping_array[$data['iSortCol_0']])) {
                $query->orderBy($sorting_mapping_array[$data['iSortCol_0']], $order);
            }
        }

        $result['model'] = $query->get();

        return $result;
    }

    public function find(int $id): Model
    {
        return Category::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Category::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $category = Category::findOrFail($id);

        return $category?->fill($data)->save();
    }

    public function delete(int $id): bool
    {
        $category = Category::findOrFail($id);

        return $category->delete();
    }

    public function restore(int $id): bool|int
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        return $category->restore();
    }

    public function getCategoryRecursive()
    {
        $getCategoryList = Category::whereNull('parent_id')->latest()->get(['id', 'name']);
        $option = ['' => __('trans.category.parent')];

        $dash = '';

        foreach ($getCategoryList as $category) {
            $option[$category->id] = e($category->name);

            if (count($category->children) > 0) {
                $option = $this->categoryRecursive($category->children, $option, $dash);
            }
        }

        return $option;
    }

    private function categoryRecursive($child, $option, $dash): mixed
    {
        $dash .= '|--- ';
        foreach ($child as $category) {
            $option[$category->id] = $dash.e($category->name);

            if (count($category->children) > 0) {
                return $this->categoryRecursive($category->children, $option, $dash);
            }
        }

        return $option;
    }

    public function existData(array $data): bool
    {
        return Category::where('slug', $data['slug'])
            ->when($data['id'], function (Builder $query) use ($data) {
                $query->where('id', '!=', $data['id']);
            })
            ->exists();
    }
}
