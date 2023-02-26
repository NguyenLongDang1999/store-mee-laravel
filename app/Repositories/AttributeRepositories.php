<?php

namespace App\Repositories;

use App\Interfaces\AttributeInterface;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AttributeRepositories implements AttributeInterface
{
    public function all(): array
    {
        $getAttributeList = Attribute::latest()->get(['id', 'name']);
        $option = ['' => __('trans.empty')];

        foreach ($getAttributeList as $item) {
            $option[$item->id] = e($item->name);
        }

        return $option;
    }

    public function getList(array $data): array
    {
        $query = Attribute::with('category')
            ->when(isset($data['name']), function (Builder $query) use ($data) {
                $query->where('name', 'LIKE', '%' . trim($data['name'] . '%'));
            })
            ->when(isset($data['category_id']), function (Builder $query) use ($data) {
                $query->where('category_id', $data['category_id']);
            })
            ->when($data['onlyTrashed'], function (Builder $query) {
                $query->onlyTrashed();
            });

        $result['total'] = $query->count();

        if (isset($data['iSortCol_0'])) {
            $sorting_mapping_array = [
                '3' => 'created_at',
                '4' => 'updated_at',
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
        return Attribute::with('variations')->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Attribute::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $brand = Attribute::findOrFail($id);

        return $brand?->fill($data)->save();
    }

    public function delete(int $id): bool
    {
        $brand = Attribute::findOrFail($id);

        return $brand->delete();
    }

    public function restore(int $id): bool|int
    {
        $brand = Attribute::onlyTrashed()->findOrFail($id);

        return $brand->restore();
    }

    public function existData(array $data): bool
    {
        return Attribute::where('slug', $data['slug'])
            ->when($data['id'], function (Builder $query) use ($data) {
                $query->where('id', '!=', $data['id']);
            })
            ->exists();
    }
}
