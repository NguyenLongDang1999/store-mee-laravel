<?php

namespace App\Interfaces;

interface VariationInterface
{
    public function find(int $id);

    public function create(array $data);

    public function insertMany(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);
}
