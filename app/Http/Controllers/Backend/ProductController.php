<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Interfaces\CategoryInterface;
use App\Interfaces\BrandInterface;
use App\Interfaces\ProductInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private string $path;

    private string $error;

    private string $success;

    public function __construct(
        private readonly ProductInterface $productInterface,
        private readonly BrandInterface $brandInterface,
        private readonly CategoryInterface $categoryInterface
    ) {
        $this->success = config('constant.message.success');
        $this->error = config('constant.message.error');
        $this->path = config('constant.route.category');
    }

    public function index(): View
    {
        $getBrandList = $this->brandInterface->all();
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();

        return view('backend.product.index', compact('getCategoryList', 'getBrandList'));
    }

    public function getList(Request $request): JsonResponse
    {
        $input = $request->input();
        $results = $this->productInterface->getList($input);

        $data = [];
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = [];

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'id' => $item->id,
                    'image_uri' => getFile($item->image_uri),
                    'imageUriBrand' => getFile($item->brand?->image_uri),
                    'imageUriCategory' => getFile($item->category?->image_uri),
                    'name' => e(str()->limit($item->name, 20)),
                    'brandName' => e(str()->limit($item->brand?->name, 20)) ?? '-',
                    'categoryName' => e(str()->limit($item->category?->name, 20)) ?? '-',
                    'status' => $item->status,
                    'price' => $item->price,
                    'price_discount' => $item->price_discount,
                    'popular' => $item->popular,
                    'created_at' => $item->created_at->format('d-m-Y'),
                    'updated_at' => $item->updated_at->format('d-m-Y'),
                    'edit_pages' => route('admin.product.edit', $item->id),
                    'edit_pages_brand' => $item->brand?->id ? route('admin.brand.edit', $item->brand?->id) : '',
                    'edit_pages_category' => $item->category?->id ? route('admin.category.edit', $item->category?->id) : '',
                    'delete' => route('admin.product.delete', $item->id),
                    'restore' => route('admin.product.restore', $item->id),
                ];
            }
        }

        return response()->json($data);
    }

    public function create(): View
    {
        $router = route('admin.product.store');
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();

        return view('backend.product.create_edit', compact('router', 'getCategoryList'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        try {
            $input = $request->validated();
            $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

            $this->productInterface->create($input);

            return to_route('admin.product.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.product.index')->with($this->error, $e->getMessage());
        }
    }

    public function edit(int $id): View
    {
        $row = $this->productInterface->find($id);
        $getCategoryList = $this->categoryInterface->getCategoryRecursive();
        $router = route('admin.product.update', $id);

        return view('backend.product.create_edit', compact('row', 'router', 'getCategoryList'));
    }

    public function update(ProductRequest $request, int $id): RedirectResponse
    {
        try {
            $input = $request->validated();

            if ($request->hasFile('image_uri')) {
                $input['image_uri'] = uploadFile($this->path, $input['image_uri']);
            }

            $this->productInterface->update($input, $id);

            return to_route('admin.product.index')->with($this->success, __('trans.message.success'));
        } catch (Exception $e) {
            return to_route('admin.product.index')->with($this->error, $e->getMessage());
        }
    }

    public function checkExistData(Request $request): JsonResponse
    {
        $input = $request->only(['id', 'name']);
        $input['slug'] = str()->slug($input['name']);
        $result = $this->productInterface->existData($input);

        return response()->json([
            'valid' => ! $result,
        ]);
    }
}
