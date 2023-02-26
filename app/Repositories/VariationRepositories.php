<?php

namespace App\Repositories;

use App\Interfaces\VariationInterface;
use App\Models\Variation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class VariationRepositories implements VariationInterface
{
    public function find(int $id): Model
    {
        return Variation::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Variation::create($data);
    }

    public function insertMany(array $data): bool
    {
        return Variation::insert($data);
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
