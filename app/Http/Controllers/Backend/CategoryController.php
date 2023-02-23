<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Interfaces\CategoryInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private string $path;

    private string $error;

    private string $success;

    public function __construct(
        private readonly CategoryInterface $categoryInterface
    ) {
        $this->success = config('constant.message.success');
        $this->error = config('constant.message.error');
        $this->path = config('constant.route.category');
    }

    public function index(): View
    {
        $getCategoryList = $this->getCategoryList();

        return view('backend.category.index', compact('getCategoryList'));
    }

    public function recycle(): View
    {
        $isRecyclePage = true;
        $getCategoryList = $this->getCategoryList();

        return view('backend.category.index', compact('getCategoryList', 'isRecyclePage'));
    }

    public function create(): View
    {
        $router = route('admin.category.store');
        $getCategoryList = $this->getCategoryList();

        return view('backend.category.create_edit', compact('router', 'getCategoryList'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            $input = $request->validated();
            $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

            $this->categoryInterface->create($input);

            return to_route('admin.category.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.category.index')->with($this->error, $e->getMessage());
        }
    }

    public function edit(int $id): View
    {
        $row = $this->categoryInterface->find($id);
        $getCategoryList = $this->getCategoryList();
        $router = route('admin.category.update', $id);

        return view('backend.category.create_edit', compact('row', 'router', 'getCategoryList'));
    }

    public function update(CategoryRequest $request, int $id): RedirectResponse
    {
        try {
            $input = $request->validated();
            $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

            $this->categoryInterface->update($input, $id);

            return to_route('admin.category.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.category.index')->with($this->error, $e->getMessage());
        }
    }

    public function getList(Request $request): JsonResponse
    {
        $input = $request->input();
        $results = $this->categoryInterface->getList($input);

        $data = [];
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = [];

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'id' => $item->id,
                    'image_uri' => getFile($item->image_uri),
                    'imageUriParent' => getFile($item->parentImage),
                    'name' => e(str()->limit($item->name, 20)),
                    'parentName' => e(str()->limit($item->parentName, 20)) ?? '-',
                    'status' => $item->status,
                    'popular' => $item->popular,
                    'created_at' => $item->created_at->format('d-m-Y'),
                    'updated_at' => $item->updated_at->format('d-m-Y'),
                    'edit_pages' => route('admin.category.edit', $item->id),
                    'edit_pages_parent' => $item->parentID ? route('admin.category.edit', $item->parentID) : '',
                    'delete' => route('admin.category.delete', $item->id),
                    'restore' => route('admin.category.restore', $item->id),
                ];
            }
        }

        return response()->json($data);
    }

    public function delete(int $id): RedirectResponse
    {
        try {
            $category = $this->categoryInterface->delete($id);

            if ($category) {
                return to_route('admin.category.index')->with(config('constant.message.success'), __('trans.message.success'));
            }

            return to_route('admin.category.index')->with(config('constant.message.error'), __('trans.message.error'));
        } catch (Exception $e) {
            return to_route('admin.category.index')->with('failed', $e->getMessage());
        }
    }

    public function restore(int $id): RedirectResponse
    {
        try {
            $category = $this->categoryInterface->restore($id);

            if ($category) {
                return to_route('admin.category.index')->with(config('constant.message.success'), __('trans.message.success'));
            }

            return to_route('admin.category.index')->with(config('constant.message.error'), __('trans.message.error'));
        } catch (Exception $e) {
            return to_route('admin.category.index')->with('failed', $e->getMessage());
        }
    }

    public function checkExistData(Request $request): JsonResponse
    {
        $input = $request->only(['name']);
        $input['slug'] = str()->slug($input['name']);
        $result = $this->categoryInterface->existData($input);

        return response()->json([
            'valid' => $result,
        ]);
    }

    private function getCategoryList(): array
    {
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();
        $option = ['' => __('trans.category.parent')];

        $dash = '';

        foreach ($getCategoryList as $category) {
            $option[$category->id] = e($category->name);

            if (count($category->children) > 0) {
                $option = $this->getCategoryRecursive($category->children, $option, $dash);
            }
        }

        return $option;
    }

    private function getCategoryRecursive($child, $option, $dash): mixed
    {
        $dash .= '|--- ';
        foreach ($child as $category) {
            $option[$category->id] = $dash.e($category->name);

            if (count($category->children) > 0) {
                return $this->getCategoryRecursive($category->children, $option, $dash);
            }
        }

        return $option;
    }
}
