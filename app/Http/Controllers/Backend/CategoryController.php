<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    private string $path;
    private Category $category;

    public function __construct(Category $category)
    {
        $this->path = config('constant.route.category');
        $this->category = $category;
    }

    public function index()
    {
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend.category.index', $data);
    }

    public function create()
    {
        $data['router'] = route('admin.category.store');
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend.category.create_edit', $data);
    }

    public function store(CategoryRequest $request)
    {
        $input = $request->validated();
        $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : NULL;

        if ($this->category->fill($input)->save()) {
            return to_route('admin.category.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.category.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function edit($id)
    {
        $data['router'] = route('admin.category.update', $id);
        $data['row'] = $this->category->getCategoryDetail($id);
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend.category.create_edit', $data);
    }

    public function update(CategoryRequest $request, int $id)
    {
        $input = $request->validated();
        $category = $this->category->getCategoryFind($id);
        $input['image_uri'] = $request->hasFile('image_uri') ? uploadFile($this->path, $input['image_uri']) : NULL;

        if ($category->fill($input)->save()) {
            return to_route('admin.category.index')->with(config('constant.message.success'), __('trans.message.success'));
        }

        return to_route('admin.category.index')->with(config('constant.message.error'), __('trans.message.error'));
    }

    public function getList(Request $request)
    {
        $input = $request->input();
        $results = $this->category->getList($input);

        $data = array();
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = array();

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
                    'delete' => '',
                ];
            }
        }

        return response()->json($data);
    }

    public function checkExistData(Request $request)
    {
        $input = $request->only(['name']);
        $input['slug'] = str()->slug($input['name']);
        $result = $this->category->checkExistData($input);
        $isValid = !($result > 0);

        return response()->json([
            'valid' => var_export($isValid, 1)
        ]);
    }

    private function getCategoryList(): array
    {
        $getCategoryList = $this->category->getCategoryRecursive();
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

    private function getCategoryRecursive($child, $option, $dash)
    {
        $dash .= '|--- ';
        foreach ($child as $category) {
            $option[$category->id] = $dash . e($category->name);

            if (count($category->children) > 0) {
                return $this->getCategoryRecursive($category->children, $option, $dash);
            }
        }
        return $option;
    }
}
