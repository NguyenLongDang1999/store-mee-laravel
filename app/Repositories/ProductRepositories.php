<?php

namespace App\Repositories;

use App\Interfaces\ProductInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductRepositories implements ProductInterface
{
    public function getList(array $data): array
    {
        $query = Product::with('brand', 'category')
            ->when(isset($data['name']), function (Builder $query) use ($data) {
                $query->where('name', 'LIKE', '%'.trim($data['name'].'%'));
            })
            ->when(isset($data['brand_id']), function (Builder $query) use ($data) {
                $query->where('brand_id', $data['brand_id']);
            })
            ->when(isset($data['category_id']), function (Builder $query) use ($data) {
                $query->where('category_id', $data['category_id']);
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
        return Product::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Product::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $product = Product::findOrFail($id);

        return $product?->fill($data)->save();
    }

    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);

        return $product->delete();
    }

    public function restore(int $id): bool|int
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        return $product->restore();
    }

    public function existData(array $data): bool
    {
        return Product::where('slug', $data['slug'])
            ->when($data['id'], function (Builder $query) use ($data) {
                $query->where('id', '!=', $data['id']);
            })
            ->exists();
    }
}
