<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariationRequest;
use App\Interfaces\AttributeInterface;
use App\Interfaces\VariationInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    private string $error;

    private string $success;

    public function __construct(
        private readonly VariationInterface  $variationInterface,
        private readonly AttributeInterface $attributeInterface
    )
    {
        $this->success = config('constant.message.success');
        $this->error = config('constant.message.error');
    }

    public function index(): View
    {
        $getAttributeList = $this->attributeInterface->all();

        return view('backend.variation.index', compact('getAttributeList'));
    }

    public function create(): View
    {
        $router = route('admin.variation.store');
        $getAttributeList = $this->attributeInterface->all();

        return view('backend.variation.create_edit', compact('router', 'getAttributeList'));
    }

    public function store(VariationRequest $request): RedirectResponse
    {
        try {
            $input = $request->validated();
            $this->variationInterface->create($input);

            return to_route('admin.variation.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.variation.index')->with($this->error, $e->getMessage());
        }
    }

    public function edit(int $id): View
    {
        $row = $this->variationInterface->find($id);
        $getAttributeList = $this->attributeInterface->all();
        $router = route('admin.variation.update', $id);

        return view('backend.variation.create_edit', compact('row', 'router', 'getAttributeList'));
    }

    public function update(VariationRequest $request, int $id): RedirectResponse
    {
        try {
            $input = $request->validated();
            $this->variationInterface->update($input, $id);

            return to_route('admin.variation.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.variation.index')->with($this->error, $e->getMessage());
        }
    }

    public function getList(Request $request): JsonResponse
    {
        $input = $request->input();
        $results = $this->variationInterface->getList($input);

        $data = [];
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = [];

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'id' => $item->id,
                    'name' => e(str()->limit($item->name, 20)),
                    'attributeName' => e(str()->limit($item->attribute?->name, 20)) ?? '-',
                    'edit_pages' => route('admin.variation.edit', $item->id),
                    'edit_pages_attribute' => $item->attribute?->id ? route('admin.attribute.edit', $item->attribute?->id) : '',
                    'delete' => route('admin.variation.delete', $item->id),
                ];
            }
        }

        return response()->json($data);
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $attribute = $this->variationInterface->delete($id);

            if ($attribute) {
                return response()->json([
                    'result' => true,
                    'title' => __('trans.message.title.success'),
                    'message' => __('trans.message.success')
                ]);
            }

            return response()->json([
                'result' => false,
                'title' => __('trans.message.title.error'),
                'message' => __('trans.message.error')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'result' => false,
                'title' => __('trans.message.title.error'),
                'message' => $e->getMessage()
            ]);
        }
    }
}
