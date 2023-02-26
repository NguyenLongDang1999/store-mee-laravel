<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use App\Interfaces\AttributeInterface;
use App\Interfaces\CategoryInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    private string $error;

    private string $success;

    public function __construct(
        private readonly CategoryInterface  $categoryInterface,
        private readonly AttributeInterface $attributeInterface
    )
    {
        $this->success = config('constant.message.success');
        $this->error = config('constant.message.error');
    }

    public function index(): View
    {
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();

        return view('backend.attribute.index', compact('getCategoryList'));
    }

    public function recycle(): View
    {
        $isRecyclePage = true;
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();

        return view('backend.attribute.index', compact('getCategoryList', 'isRecyclePage'));
    }

    public function create(): View
    {
        $router = route('admin.attribute.store');
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();

        return view('backend.attribute.create_edit', compact('router', 'getCategoryList'));
    }

    public function store(AttributeRequest $request): RedirectResponse
    {
        try {
            $input = $request->validated();
            $this->attributeInterface->create($input);

            return to_route('admin.attribute.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.attribute.index')->with($this->error, $e->getMessage());
        }
    }

    public function edit(int $id): View
    {
        $row = $this->attributeInterface->find($id);
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();
        $router = route('admin.attribute.update', $id);

        return view('backend.attribute.create_edit', compact('row', 'router', 'getCategoryList'));
    }

    public function update(AttributeRequest $request, int $id): RedirectResponse
    {
        try {
            $input = $request->validated();
            $this->attributeInterface->update($input, $id);

            return to_route('admin.attribute.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.attribute.index')->with($this->error, $e->getMessage());
        }
    }

    public function getList(Request $request): JsonResponse
    {
        $input = $request->input();
        $results = $this->attributeInterface->getList($input);

        $data = [];
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = [];

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'id' => $item->id,
                    'imageUriCategory' => getFile($item->category?->image_uri),
                    'name' => e(str()->limit($item->name, 20)),
                    'categoryName' => e(str()->limit($item->category?->name, 20)) ?? '-',
                    'created_at' => $item->created_at->format('d-m-Y'),
                    'updated_at' => $item->updated_at->format('d-m-Y'),
                    'edit_pages' => route('admin.attribute.edit', $item->id),
                    'edit_pages_category' => $item->category?->id ? route('admin.category.edit', $item->category?->id) : '',
                    'delete' => route('admin.attribute.delete', $item->id),
                    'restore' => route('admin.attribute.restore', $item->id),
                ];
            }
        }

        return response()->json($data);
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $attribute = $this->attributeInterface->delete($id);

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

    public function restore(int $id): JsonResponse
    {
        try {
            $attribute = $this->attributeInterface->restore($id);

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

    public function checkExistData(Request $request): JsonResponse
    {
        $input = $request->only(['id', 'name']);
        $input['slug'] = str()->slug($input['name']);
        $result = $this->attributeInterface->existData($input);

        return response()->json([
            'valid' => !$result,
        ]);
    }
}
