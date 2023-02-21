<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected string $path;

    protected Brand $brand;
    protected Category $category;

    public function __construct(Brand $brand, Category $category)
    {
        $this->path = config('constant.route.brand');
        $this->brand = $brand;
        $this->category = $category;
    }

    public function index()
    {
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend.brand.index', $data);
    }

    public function recycle()
    {
        $data['isRecyclePage'] = true;
        $data['getCategoryList'] = $this->getCategoryList();

        return view('backend.brand.index', $data);
    }

    public function create()
    {
        $data['router'] = route('admin.brand.store');
        $data['getCategoryList'] = $this->getCategoryList();

        return view('backend.brand.create_edit', $data);
    }

    public function store(BrandRequest $request)
    {
        $input = $request->validated();
        $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

        if ($this->brand->fill($input)->save()) {
            return to_route('admin.brand.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.brand.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function edit($id)
    {
        $data['router'] = route('admin.brand.update', $id);
        $data['row'] = $this->brand->getBrandDetail($id);
        $data['getCategoryList'] = $this->getCategoryList();

        return view('backend.brand.create_edit', $data);
    }

    public function update(BrandRequest $request, int $id)
    {
        $input = $request->validated();
        $brand = $this->brand->getBrandFind($id);
        $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : null;

        if ($brand->fill($input)->save()) {
            return to_route('admin.brand.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.brand.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function getList(Request $request)
    {
        $input = $request->input();
        $results = $this->brand->getList($input);

        $data = [];
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = [];

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'id' => $item->id,
                    'image_uri' => getFile($item->image_uri),
                    'imageUriCategory' => getFile($item->categoryImage),
                    'name' => e(str()->limit($item->name, 20)),
                    'categoryName' => e(str()->limit($item->categoryName, 20)) ?? '-',
                    'status' => $item->status,
                    'popular' => $item->popular,
                    'created_at' => $item->created_at->format('d-m-Y'),
                    'updated_at' => $item->updated_at->format('d-m-Y'),
                    'edit_pages' => route('admin.brand.edit', $item->id),
                    'edit_pages_category' => $item->categoryID ? route('admin.category.edit', $item->categoryID) : '',
                    'delete' => route('admin.brand.delete', $item->id),
                    'restore' => route('admin.brand.restore', $item->id),
                ];
            }
        }

        return response()->json($data);
    }

    public function delete(int $id)
    {
        $brand = $this->brand->getBrandFind($id);

        if ($brand->delete()) {
            return to_route('admin.brand.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.brand.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function restore(int $id)
    {
        $brand = $this->brand->getBrandFind($id);

        if ($brand->restore()) {
            return to_route('admin.brand.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.brand.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function checkExistData(Request $request)
    {
        $input = $request->only(['name']);
        $input['slug'] = str()->slug($input['name']);
        $result = $this->brand->checkExistData($input);
        $isValid = ! ($result > 0);

        return response()->json([
            'valid' => var_export($isValid, 1),
        ]);
    }

    private function getCategoryList(): array
    {
        $getCategoryList = $this->category->getCategoryRecursive();
        $option = ['' => __('trans.empty')];

        $dash = '';

        foreach ($getCategoryList as $category) {
            $option[$category->id] = e($category->name);

            if (count($category->children) > 0) {
                $option = $this->getCategoryRecursive($category->children, $option, $dash);
            }
        }

        return $option;
    }

    private function getCategoryRecursive($child, $option, $dash)
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
