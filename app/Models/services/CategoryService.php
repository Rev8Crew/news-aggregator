<?php


namespace App\Models\services;


use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\repositories\CategoryRepository;

class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function findOrNull($param) {
        $duplicate = $this->categoryRepository->findByID($param);

        if ($duplicate) {
            return $duplicate;
        }

        $duplicate = $this->categoryRepository->findByName($param);

        if ($duplicate) {
            return $duplicate;
        }

        return null;
    }

    public function createOrFirst($name): Category
    {
        $duplicate = $this->findOrNull($name);

        if ($duplicate) {
            return $duplicate;
        }

        $category = new Category(
            EntityID::nextId(),
            $name
        );

        $this->categoryRepository->add($category);
        return $category;
    }

    public function all()
    {
        return $this->categoryRepository->all();
    }
}
