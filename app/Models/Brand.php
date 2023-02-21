<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'brand';

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'image_uri',
        'status',
        'popular'
    ];

    public function getList(array $input = []): array
    {
        $query = Brand::leftJoin('category', 'category.id', '=', 'brand.category_id');

        if (isset($input['search']['name']) && $input['search']['name'] != '') {
            $query->where('brand.name', 'LIKE', '%'.trim($input['search']['name'].'%'));
        }

        if (isset($input['search']['category_id']) && $input['search']['category_id'] != '') {
            $query->where('brand.category_id', $input['search']['category_id']);
        }

        if (isset($input['search']['status']) && $input['search']['status'] != '') {
            $query->where('brand.status', $input['search']['status']);
        }

        if (isset($input['search']['popular']) && $input['search']['popular'] != '') {
            $query->where('brand.popular', $input['search']['popular']);
        }

        if (isset($input['search']['onlyTrashed'])) $query->onlyTrashed();

        $result['total'] = $query->count();

        if (isset($input['iSortCol_0'])) {
            $sorting_mapping_array = [
                '1' => 'brand.name',
                '2' => 'category.name',
                '3' => 'brand.status',
                '4' => 'brand.popular',
                '5' => 'brand.created_at',
                '6' => 'brand.updated_at',
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
            'brand.id',
            'brand.name',
            'brand.image_uri',
            'brand.status',
            'brand.popular',
            'brand.created_at',
            'brand.updated_at',
            'category.name as categoryName',
            'category.id as categoryID',
            'category.image_uri as categoryImage',
        ]);

        return $result;
    }

    public function getBrandFind(int $id)
    {
        return Brand::select('id')->withTrashed()->find($id);
    }

    public function checkExistData($input): int
    {
        return Brand::select('id')->where(['slug' => $input['slug']])->count();
    }

    public function getBrandDetail(int $id): Brand
    {
        return Brand::where(['id' => $id])->first([
            'id',
            'name',
            'slug',
            'image_uri',
            'description',
            'category_id',
            'status',
            'popular'
        ]);
    }
}
