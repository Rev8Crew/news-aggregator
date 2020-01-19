<?php

namespace App\Http\Controllers\Api;

use App\Models\entities\EntityID;
use App\Models\services\CategoryService;
use App\Models\services\NewsService;
use App\Models\services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var NewsService
     */
    private $newsService;

    public function __construct(UserService $userService, CategoryService $categoryService, NewsService $newsService)
    {
        $this->userService = $userService;
        $this->categoryService = $categoryService;
        $this->newsService = $newsService;
    }

    /** Add User: name|password */
    public function add() {
        $this->validate( \request(), [
            'name' => 'required',
            'password' => 'required|min:4',
        ]);

        $user = $this->userService->createOrFirst(\request('name'), \request('password'), [], []);
        return response( "Token: ".$user->getToken());

    }

    /**
     *      category - entityID
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addCategory() {
        $token = \request()->header('token');

        $this->validate( \request(), [
           'category' => 'required'
        ]);

        $user = $this->userService->findOrNull($token);
        $category = $this->categoryService->findOrNull(\request('category'));

        if ( !$user || !$category) {
            return response('Error in params');
        }

        $this->userService->addCategory($user, $category);
        return response( "OK...");
    }

    /** Add news to User
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addNews() {
        $token = \request()->header('token');
        $this->validate( \request(), [
            'news' => 'required'
        ]);

        $user = $this->userService->findOrNull( $token );
        $news = $this->newsService->findOrNull( \request('news'));

        $result = $this->userService->addNews($user, $news);
        $result =  $result ? "OK..." : "News already used by user";
        return response( $result);
    }

    public function listDelayedNews() {
        $token = \request()->header('token');

        $user = $this->userService->findOrNull( $token );
        return response()->json($this->userService->getDelayedNews($user), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function listFavoriteCategories() {
        $token = \request()->header('token');

        $user = $this->userService->findOrNull($token);
        return response()->json($this->userService->getFavoriteCategories($user), 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }


}
