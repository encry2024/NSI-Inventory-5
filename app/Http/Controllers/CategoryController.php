<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Category;
use App\Field;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreateFieldRequest;

use Illuminate\Support\Facades\Input;

class CategoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct(Category $category) {
        $this->category = $category;
    }


	public function index()
	{
		//
       $fetch_categories = Category::fetchCategories();

		return $fetch_categories;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        return view('category.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateFieldRequest $f_requests, CreateCategoryRequest $request, Category $category )
	{
		//
        $store_category = Category::storeCategory($f_requests, $request, $category);

		return $store_category;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Category $category)
	{
        return view('category.show', compact('category'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($slug) {
		$slug->delete();

		return redirect('/')->with('success_msg', 'Category was successfully deleted');
	}

	public function openExcel() {
		$import_excel = Category::importCategory();

		return $import_excel;
	}

	public function excelIndex() {
		return view('import.excel');
	}

}
