<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected string $path;

    protected Slider $slider;

    public function __construct(Slider $slider)
    {
        $this->path = config('constant.route.slider');
        $this->slider = $slider;
    }

    public function index()
    {
        return view('backend.slider.index');
    }

    public function recycle()
    {
        $data['isRecyclePage'] = true;

        return view('backend.slider.index', $data);
    }

    public function create()
    {
        $data['router'] = route('admin.slider.store');

        return view('backend.slider.create_edit', $data);
    }

    public function store(SliderRequest $request)
    {
        $input = $request->validated();
        $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

        if ($this->slider->fill($input)->save()) {
            return to_route('admin.slider.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.slider.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function edit($id)
    {
        $data['router'] = route('admin.slider.update', $id);
        $data['row'] = $this->slider->getSliderDetail($id);

        return view('backend.slider.create_edit', $data);
    }

    public function update(SliderRequest $request, int $id)
    {
        $input = $request->validated();
        $slider = $this->slider->getSliderFind($id);
        $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

        if ($slider->fill($input)->save()) {
            return to_route('admin.slider.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.slider.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function getList(Request $request)
    {
        $input = $request->input();
        $results = $this->slider->getList($input);

        $data = [];
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = [];

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'id' => $item->id,
                    'image_uri' => getFile($item->image_uri),
                    'name' => e(str()->limit($item->name, 20)),
                    'status' => $item->status,
                    'start_date' => $item->start_date,
                    'end_date' => $item->end_date,
                    'created_at' => $item->created_at->format('d-m-Y'),
                    'updated_at' => $item->updated_at->format('d-m-Y'),
                    'edit_pages' => route('admin.slider.edit', $item->id),
                    'delete' => route('admin.slider.delete', $item->id),
                    'restore' => route('admin.slider.restore', $item->id),
                ];
            }
        }

        return response()->json($data);
    }

    public function delete(int $id)
    {
        $slider = $this->slider->getSliderFind($id);

        if ($slider->delete()) {
            return to_route('admin.slider.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.slider.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function restore(int $id)
    {
        $slider = $this->slider->getSliderFind($id);

        if ($slider->restore()) {
            return to_route('admin.slider.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.slider.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function checkExistData(Request $request)
    {
        $input = $request->only(['url']);
        $result = $this->slider->checkExistData($input);
        $isValid = ! ($result > 0);

        return response()->json([
            'valid' => var_export($isValid, 1),
        ]);
    }
}
