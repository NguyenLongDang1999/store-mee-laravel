<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Interfaces\BrandInterface;
use App\Interfaces\CategoryInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    private string $path;

    private string $error;

    private string $success;

    public function __construct(
        private readonly CategoryInterface $categoryInterface,
        private readonly BrandInterface $brandInterface
    ) {
        $this->success = config('constant.message.success');
        $this->error = config('constant.message.error');
        $this->path = config('constant.route.brand');
    }

    public function index(): View
    {
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();

        return view('backend.brand.index', compact('getCategoryList'));
    }

    public function recycle(): View
    {
        $isRecyclePage = true;
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();

        return view('backend.brand.index', compact('getCategoryList', 'isRecyclePage'));
    }

    public function create(): View
    {
        $router = route('admin.brand.store');
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();

        return view('backend.brand.create_edit', compact('router', 'getCategoryList'));
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        try {
            $input = $request->validated();
            $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

            $this->brandInterface->create($input);

            return to_route('admin.brand.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.brand.index')->with($this->error, $e->getMessage());
        }
    }

    public function edit(int $id): View
    {
        $row = $this->brandInterface->find($id);
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();
        $router = route('admin.brand.update', $id);

        return view('backend.brand.create_edit', compact('row', 'router', 'getCategoryList'));
    }

    public function update(BrandRequest $request, int $id): RedirectResponse
    {
        try {
            $input = $request->validated();

            if ($request->hasFile('image_uri')) {
                $input['image_uri'] = uploadFile($this->path, $input['image_uri']);
            }

            $this->brandInterface->update($input, $id);

            return to_route('admin.brand.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.brand.index')->with($this->error, $e->getMessage());
        }
    }

    public function getList(Request $request): JsonResponse
    {
        $input = $request->input();
        $results = $this->brandInterface->getList($input);

        $data = [];
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = [];

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'id' => $item->id,
                    'image_uri' => getFile($item->image_uri),
                    'imageUriCategory' => getFile($item->category?->image_uri),
                    'name' => e(str()->limit($item->name, 20)),
                    'categoryName' => e(str()->limit($item->category?->name, 20)) ?? '-',
                    'status' => $item->status,
                    'popular' => $item->popular,
                    'created_at' => $item->created_at->format('d-m-Y'),
                    'updated_at' => $item->updated_at->format('d-m-Y'),
                    'edit_pages' => route('admin.brand.edit', $item->id),
                    'edit_pages_category' => $item->category?->id ? route('admin.category.edit', $item->category?->id) : '',
                    'delete' => route('admin.brand.delete', $item->id),
                    'restore' => route('admin.brand.restore', $item->id),
                ];
            }
        }

        return response()->json($data);
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $category = $this->brandInterface->delete($id);

            if ($category) {
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
            $category = $this->brandInterface->restore($id);

            if ($category) {
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
        $result = $this->brandInterface->existData($input);

        return response()->json([
            'valid' => !$result,
        ]);
    }
}
