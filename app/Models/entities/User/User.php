<?php


namespace App\Models\entities\User;


use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\entities\News\News;
use App\Models\entities\View\View;

class User
{
    /**
     * @var EntityID
     */
    private $entityID;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $password;
    /**
     * @var array
     */
    private $categories;
    /**
     * @var array
     */
    private $news;
    /**
     * @var array
     */
    private $views;
    /**
     * @var string
     */
    private $token;

    public function __construct(EntityID $entityID, string $name, string $password, array $categories, array $news, array $views, string $token = '')
    {
        $this->entityID = $entityID;
        $this->name = $name;
        $this->password = $password;
        $this->categories = $categories;
        $this->news = $news;
        $this->views = $views;
        $this->token = $token;

        if (!$token) {
            $this->token = bin2hex(random_bytes(16));
        }
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return EntityID
     */
    public function getEntityID(): EntityID
    {
        return $this->entityID;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
    }

    /**
     * @return array
     */
    public function getNews(): array
    {
        return $this->news;
    }

    /**
     * @param array $news
     */
    public function addNews(News $news): void
    {
        $this->news[$news->getEntityID()->getId()] = $news;
    }

    public function removeNews(News $news) : bool {
        if ( !array_key_exists($news->getEntityID()->getId(), $this->news) ) {
            return false;
        }

        unset($this->news[$news->getEntityID()->getId()]);
        return true;
    }

    /**
     * @return array
     */
    public function getViews(): array
    {
        return $this->views;
    }

    public function addViews(View $view) {
        $this->views[] = $view;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
