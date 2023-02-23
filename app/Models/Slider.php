<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'slider';

    protected $fillable = [
        'name',
        'url',
        'description',
        'image_uri',
        'status',
        'start_date',
        'end_date',
    ];

    public function getList(array $input = []): array
    {
        $query = Slider::query();

        if (isset($input['search']['name']) && $input['search']['name'] != '') {
            $query->where('name', 'LIKE', '%'.trim($input['search']['name'].'%'));
        }

        if (isset($input['search']['status']) && $input['search']['status'] != '') {
            $query->where('status', $input['search']['status']);
        }

        if (isset($input['search']['onlyTrashed'])) {
            $query->onlyTrashed();
        }

        $result['total'] = $query->count();

        if (isset($input['iSortCol_0'])) {
            $sorting_mapping_array = [
                '2' => 'name',
                '3' => 'status',
                '4' => 'start_date',
                '5' => 'created_at',
                '6' => 'updated_at',
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
            'id',
            'name',
            'image_uri',
            'status',
            'start_date',
            'end_date',
            'created_at',
            'updated_at',
        ]);

        return $result;
    }

    public function getSliderFind(int $id)
    {
        return Slider::select('id')->withTrashed()->find($id);
    }

    public function checkExistData($input): int
    {
        return Slider::select('id')->where(['url' => $input['url']])->count();
    }

    public function getSliderDetail(int $id): Brand
    {
        return Slider::where(['id' => $id])->first([
            'id',
            'name',
            'url',
            'image_uri',
            'description',
            'status',
            'start_date',
            'end_date',
        ]);
    }
}
