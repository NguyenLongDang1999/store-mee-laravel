<?php

namespace App\Repositories;

use App\Interfaces\VariationInterface;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VariationRepositories implements VariationInterface
{
    public function getList(array $data): array
    {
        $query = Variation::with('attribute')
            ->when(isset($data['name']), function (Builder $query) use ($data) {
                $query->where('name', 'LIKE', '%' . trim($data['name'] . '%'));
            })->when(isset($data['attribute_id']), function (Builder $query) use ($data) {
                $query->where('attribute_id', $data['attribute_id']);
            });

        $result['total'] = $query->count();
        $result['model'] = $query->get();

        return $result;
    }

    public function find(int $id): Model
    {
        return Variation::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Variation::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $variation = Variation::findOrFail($id);

        return $variation?->fill($data)->save();
    }

    public function delete(int $id): bool
    {
        $variation = Variation::findOrFail($id);

        return $variation->delete();
    }
}
