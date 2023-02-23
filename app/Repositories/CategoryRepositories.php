<?php

namespace App\Repositories;

use App\Interfaces\CategoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CategoryRepositories implements CategoryInterface
{
    public function all()
    {
    }

    public function getList(array $data): array
    {
        $query = Category::leftJoin('category as parent', 'parent.id', '=', 'category.parent_id');

        if (isset($input['search']['name']) && $input['search']['name'] != '') {
            $query->where('category.name', 'LIKE', '%'.trim($input['search']['name'].'%'));
        }

        if (isset($input['search']['parent_id']) && $input['search']['parent_id'] != '') {
            $query->where('category.parent_id', $input['search']['parent_id']);
        }

        if (isset($input['search']['status']) && $input['search']['status'] != '') {
            $query->where('category.status', $input['search']['status']);
        }

        if (isset($input['search']['popular']) && $input['search']['popular'] != '') {
            $query->where('category.popular', $input['search']['popular']);
        }

        if (isset($input['search']['onlyTrashed'])) {
            $query->onlyTrashed();
        }

        $result['total'] = $query->count();

        if (isset($input['iSortCol_0'])) {
            $sorting_mapping_array = [
                '1' => 'category.name',
                '2' => 'parent.name',
                '3' => 'category.status',
                '4' => 'category.popular',
                '5' => 'category.created_at',
                '6' => 'category.updated_at',
            ];

            $order = 'desc';
            if (isset($input['sSortDir_0'])) {
                $order = $input['sSortDir_0'];
            }

            if (isset($sorting_mapping_array[$input['iSortCol_0']])) {
                $query->orderBy($sorting_mapping_array[$input['iSortCol_0']], $order);
            }
        }

        $result['model'] = $query->get([
            'category.id',
            'category.name',
            'category.image_uri',
            'category.status',
            'category.popular',
            'category.created_at',
            'category.updated_at',
            'parent.name as parentName',
            'parent.id as parentID',
            'parent.image_uri as parentImage',
        ]);

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
        $category = Category::findOrFail($id);

        return $category->restore();
    }

    public function getCategoryRecursive(): array|\Illuminate\Database\Eloquent\Collection|Collection
    {
        return Category::whereNull('parent_id')->latest()->get(['id', 'name']);
    }

    public function existData(array $data): bool
    {
        return Category::where('slug', $data['slug'])->exists();
    }
}
