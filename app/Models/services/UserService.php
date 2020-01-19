<?php


namespace App\Models\services;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\NewsResource;
use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\entities\News\News;
use App\Models\entities\NewsSite\NewsSite;
use App\Models\entities\User\User;
use App\Models\entities\View\View;
use App\Models\repositories\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ViewService
     */
    private $viewService;

    public function __construct(UserRepository $userRepository, ViewService $viewService)
    {
        $this->userRepository = $userRepository;
        $this->viewService = $viewService;
    }

    public function findOrNull($param) {
        $duplicate = $this->userRepository->findByID($param);

        if ($duplicate) {
            return $duplicate;
        }

        $duplicate = $this->userRepository->findByName($param);

        if ($duplicate) {
            return $duplicate;
        }

        $duplicate = $this->userRepository->findByToken($param);

        if ($duplicate) {
            return $duplicate;
        }

        return null;
    }

    public function createOrFirst($name, $password, array $categories, array $news, array $views = []): User
    {
        $duplicate = $this->findOrNull($name);

        if ($duplicate) {
            return $duplicate;
        }

        $password = \Hash::make($password);

        $item = new User(
            EntityID::nextId(),
            $name,
            $password,
            $categories,
            $news,
            $views
        );

        $this->userRepository->add($item);
        return $item;
    }
    public function addCategory(User $user, Category $category) {
        $user->addCategory($category);
        $this->userRepository->save($user);
    }

    public function addNews(User $user, News $news) {

        /** @var News $value */
        foreach ($user->getNews() as $key=> $value) {

            if ($value->getEntityID()->getId() != $news->getEntityID()->getId()) {
                continue;
            }

            return false;
        }

        $user->addNews($news);
        $this->userRepository->save($user);
        return true;
    }

    public function removeNews(User $user, News $news) {
        $user->removeNews($news);
        $this->userRepository->save($user);
    }

    public function filter( array $columns, string $order = '', string $order_type = 'ASC') {
        return $this->userRepository->filter($columns, $order, $order_type);
    }

    public function getDelayedNews(User $user) {
        $data = [];

        /** @var News $news */
        foreach ($user->getNews() as $news) {
            $data[] = new NewsResource($news);
        }

        return $data;
    }

    public function getFavoriteCategories(User $user) {
        $data = [];

        /** @var Category $category */
        foreach ($user->getCategories() as $category) {
            $data[] = new CategoryResource($category);
        }

        return $data;
    }

    private function findViewByID(User $user, NewsSite $newsSite) {
        /** @var View $view */
        foreach ($user->getViews() as $k=>&$view) {
            if ($view->getNewsSite()->getEntityID()->getId() == $newsSite->getEntityID()->getId()) {
                return $view;
            }
        }

        return false;
    }

    public function addToViews(User $user, NewsSite $newsSite) {
        $view = $this->findViewByID($user, $newsSite);

        if ($view === false) {
            $view = $this->viewService->create($newsSite);
            $user->addViews($view);
        }

        $view->addCount();
        $this->viewService->save($view);
        $this->userRepository->save($user);
    }

    public function getViews(User $user) {

        $data = [];
        /** @var View $view */
        foreach ($user->getViews() as $view) {
            $data[$view->getNewsSite()->getEntityID()->getId()] = $view;
        }

        return $data;
    }
}
