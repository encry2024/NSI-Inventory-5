<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreateFieldRequest;
use App\Category;
use App\Field;
use App\Device;
use Illuminate\Http\Response;


class CategoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public $total_rows;

	public function __construct(Category $category) {
		$this->category = $category;
		$this->middleware('auth');
	}

	public function index(Request $request)
	{

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
	public function store(CreateFieldRequest $f_requests, CreateCategoryRequest $request,
						  Category $category ) {
		$store_category = Category::storeCategory($f_requests, $request, $category);

		return $store_category;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Category $category, Request $request)
	{
		$deleted_device = Device::onlyTrashed()->where('category_id', $category->id)->get();
		$devices = Device::with(['information.field', 'owner', 'status'])->where('category_id', $category->id)->latest('updated_at');

		$devices = $devices->where('name', 'LIKE', '%'.$request->get('filter').'%')->paginate(25);
		$devices->setPath($category->slug);

		return view('category.show', compact('category', 'deleted_device', 'devices'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($category) {
		return view('category.edit', compact('category'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		//
		//return $id;;
		//return $request->get('category_name');
		$category = Category::whereSlug($id->slug)->first();
		$category->name = $request->get('category_name');
		//if success
		if($category->save()){
			return 1;
		}
		//if not success
		else{
			return 0;
		}


	}

	public function fetchCatName($category) {
		$category = Category::whereSlug($category)->first();

		return $category->name;
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

	public function openExcel(Request $request) {
		$import_excel = Category::importCategory($request);

		return $import_excel;
	}

	public function excelIndex() {
		return view('import.excel');
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

	public function view_deletedCategory() {
		return view('category.deleted_category');
	}

	public function fetch_deleted_categories() {
		$fetchDeletedCategories = Category::fetch_del_cat();

		return $fetchDeletedCategories;
	}

	public function fetch_devices_infoValue( $info_id, $category_id ) {
		$return_fetch = Category::fetch_devices_info_value($info_id, $category_id);

		return $return_fetch;
	}

	public function viewTester() {
		return view('function_tester.test');
	}

	public function testImport(Request $request) {
		$new_category = new Field;
		$new_category->category_id = $request->get("category_id");
		$new_category->category_label = $request->get("category_label");
		$new_category->save();
	}
}
