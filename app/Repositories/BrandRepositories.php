<?php

namespace App\Repositories;

use App\Interfaces\BrandInterface;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;

class BrandRepositories implements BrandInterface
{
    public function getList(array $data): array
    {
        $query = Brand::with('category');

        if (isset($data['search']['name']) && $data['search']['name'] != '') {
            $query->where('name', 'LIKE', '%'.trim($data['search']['name'].'%'));
        }

        if (isset($data['search']['category_id']) && $data['search']['category_id'] != '') {
            $query->where('category_id', $data['search']['category_id']);
        }

        if (isset($data['search']['status']) && $data['search']['status'] != '') {
            $query->where('status', $data['search']['status']);
        }

        if (isset($data['search']['popular']) && $data['search']['popular'] != '') {
            $query->where('popular', $data['search']['popular']);
        }

        if (isset($data['search']['onlyTrashed'])) {
            $query->onlyTrashed();
        }

        $result['total'] = $query->count();

        if (isset($data['iSortCol_0'])) {
            $sorting_mapping_array = [
                '5' => 'brand.created_at',
                '6' => 'brand.updated_at',
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
        return Brand::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Brand::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $category = Brand::findOrFail($id);

        return $category?->fill($data)->save();
    }

    public function delete(int $id): bool
    {
        $category = Brand::findOrFail($id);

        return $category->delete();
    }

    public function restore(int $id): bool|int
    {
        $category = Brand::findOrFail($id);

        return $category->restore();
    }

    public function existData(array $data): bool
    {
        return Brand::where('slug', $data['slug'])->exists();
    }
}
