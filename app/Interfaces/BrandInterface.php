<?php

namespace App\Interfaces;

interface BrandInterface
{
    public function all();

    public function getList(array $data);

    public function find(int $id);

    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);

    public function restore(int $id);

    public function existData(array $data);

    public function getBrandWithCategory(int $id);
}
