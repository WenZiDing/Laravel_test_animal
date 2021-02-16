<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;


class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth:api',['except'=>['index','show']]);
    }
    public function index(Request $request)
    {
        $url = $request->url();
        $queryParams = $request->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";

        if(Cache::has($fullUrl)){
            return Cache::get($fullUrl);
        }
				//
			// $animal = Animal::get();
            // return response(['data' => $animal], Response::HTTP_OK);
            $limit = $request->limit ?? 10;

            $query = Animal::query();

            //多重排序
            if(isset($request->sorts)){
                $Sorts = explode(',',$request->sorts);
                foreach ($Sorts as $key => $value){
                    list($key0, $value0) = explode(':',$value);
                    if($value0 == 'asc' || $value0 == 'desc'){
                        $query->orderBy($key0,$value0);
                    }
                }
            }else{
                $query->orderBy('id','desc');
            }
            //過濾
            if(isset($request->filters)){
                $filters = explode(',', $request->filters);

                foreach ($filters as $key => $filter) {
                    list($key1, $value) = explode(':', $filter);
                    $query->where($key1, 'like', "%$value%")->orderBy('id','asc');
                }
            }
            $animal = $query->paginate($limit)->appends($request->query());
            return Cache::remember($fullUrl,60,function ()use($animal){
                return response($animal, Response::HTTP_OK);
            });

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
           'type_id'    =>'nullable|integer',
           'name'       =>'required|string|max:255',
           'birthday'   =>'nullable|date',
           'area'       =>'nullable|string|max:255',
           'fix'        =>'required|boolean',
           'description'=>'nullable',
           'personality'=>'nullable'
        ]);
        $request['user_id'] = 1;
        //
        $animal = Animal::create($request->all());
        $animal = $animal->refresh();
        return response($animal, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function show(Animal $animal)
    {
				//
			return response($animal, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function edit(Animal $animal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        //
			$animal->update($request->all());
			return response($animal, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animal $animal)
    {
        $animal->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
