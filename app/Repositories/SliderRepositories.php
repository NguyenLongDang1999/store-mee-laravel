<?php

namespace App\Repositories;

use App\Interfaces\SliderInterface;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Model;

class SliderRepositories implements SliderInterface
{
    public function getList(array $data): array
    {
        $query = SLider::query();

        if (isset($data['search']['name']) && $data['search']['name'] != '') {
            $query->where('name', 'LIKE', '%'.trim($data['search']['name'].'%'));
        }

        if (isset($data['search']['status']) && $data['search']['status'] != '') {
            $query->where('status', $data['search']['status']);
        }

        if (isset($data['search']['onlyTrashed'])) {
            $query->onlyTrashed();
        }

        $result['total'] = $query->count();

        if (isset($data['iSortCol_0'])) {
            $sorting_mapping_array = [
                '6' => 'created_at',
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
        return Slider::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Slider::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $slider = Slider::findOrFail($id);

        return $slider?->fill($data)->save();
    }

    public function delete(int $id): bool
    {
        $slider = Slider::findOrFail($id);

        return $slider->delete();
    }

    public function restore(int $id): bool|int
    {
        $slider = Slider::findOrFail($id);

        return $slider->restore();
    }

    public function existData(array $data): bool
    {
        return Slider::where('url', $data['url'])->exists();
    }
}
