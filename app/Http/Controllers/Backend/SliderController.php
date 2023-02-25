<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Interfaces\SliderInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    private string $path;

    private string $error;

    private string $success;

    public function __construct(
        private readonly SliderInterface $sliderInterface,
    )
    {
        $this->success = config('constant.message.success');
        $this->error = config('constant.message.error');
        $this->path = config('constant.route.slider');
    }

    public function index(): View
    {
        return view('backend.slider.index');
    }

    public function recycle(): View
    {
        $isRecyclePage = true;

        return view('backend.slider.index', compact('isRecyclePage'));
    }

    public function create(): View
    {
        $router = route('admin.slider.store');

        return view('backend.slider.create_edit', compact('router'));
    }

    public function store(SliderRequest $request): RedirectResponse
    {
        try {
            $input = $request->validated();
            $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

            $this->sliderInterface->create($input);

            return to_route('admin.slider.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.slider.index')->with($this->error, $e->getMessage());
        }
    }

    public function edit(int $id): View
    {
        $row = $this->sliderInterface->find($id);
        $router = route('admin.slider.update', $id);

        return view('backend.slider.create_edit', compact('row', 'router'));
    }

    public function update(SliderRequest $request, int $id): RedirectResponse
    {
        try {
            $input = $request->validated();

            if ($request->hasFile('image_uri')) {
                $input['image_uri'] = uploadFile($this->path, $input['image_uri']);
            }

            $this->sliderInterface->update($input, $id);

            return to_route('admin.slider.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.slider.index')->with($this->error, $e->getMessage());
        }
    }

    public function getList(Request $request): JsonResponse
    {
        $input = $request->input();
        $results = $this->sliderInterface->getList($input);

        $data = [];
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = [];

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'id' => $item->id,
                    'image_uri' => getFile($item->image_uri),
                    'name' => e(str()->limit($item->name, 20)),
                    'start_date' => $item->start_date,
                    'end_date' => $item->end_date,
                    'status' => $item->status,
                    'created_at' => $item->created_at->format('d-m-Y'),
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
        try {
            $brand = $this->sliderInterface->delete($id);

            if ($brand) {
                return to_route('admin.slider.index')->with($this->success, __('trans.message.success'));
            }

            return to_route('admin.slider.index')->with($this->error, __('trans.message.error'));
        } catch (Exception $e) {
            return to_route('admin.slider.index')->with($this->error, $e->getMessage());
        }
    }

    public function restore(int $id)
    {
        try {
            $brand = $this->sliderInterface->restore($id);

            if ($brand) {
                return to_route('admin.slider.index')->with($this->success, __('trans.message.success'));
            }

            return to_route('admin.slider.index')->with($this->error, __('trans.message.error'));
        } catch (Exception $e) {
            return to_route('admin.slider.index')->with($this->error, $e->getMessage());
        }
    }

    public function checkExistData(Request $request)
    {
        $input = $request->only(['url']);
        $result = $this->sliderInterface->existData($input);

        return response()->json([
            'valid' => $result,
        ]);
    }
}
