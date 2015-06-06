<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreateFieldRequest;
use Illuminate\Support\Facades\Input;
use App\Category;
use App\Field;
use App\DeviceLog;
use App\Device;

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
		$deleted_device = Device::onlyTrashed()->where('category_id', $category->id)->get();
		
		return view('category.show', compact('category', 'deleted_device'));
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

	public function openExcel(Category $category) {
		$import_excel = $category->importCategory();

		return $import_excel;
	}

	public function excelIndex() {
		$ctr = 0;

		return view('import.excel', compact('ctr'));
	}

	public function categoryHistory( $category_slug ) {
		$category_history = Category::fetch_history( $category_slug );

		return $category_history;
	}

	public function viewCategoryHistory( $category_slug ) {
		$category = Category::whereSlug( $category_slug )->first();

		return view('category.history', compact('category'));
	}

	public function categoryStatusHistory( $category_slug ) {
		$category_status_history = Category::fetch_status_history( $category_slug );

		return $category_status_history;
	}

	public function viewCategoryStatusesHistory($category_slug) {
		$category = Category::whereSlug($category_slug)->first();
		return view('category.device_statuses', compact('category'));
	}

}
