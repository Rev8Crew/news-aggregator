<?php


namespace App\Models\services;


use App\Models\entities\EntityID;
use App\Models\entities\NewsSite\NewsSite;
use App\Models\entities\User\User;
use App\Models\entities\View\View;
use App\Models\repositories\ViewRepository;

class ViewService
{
    /**
     * @var ViewRepository
     */
    private $viewRepository;

    public function __construct(ViewRepository $viewRepository)
    {
        $this->viewRepository = $viewRepository;
    }

    public function create(NewsSite $newsSite, int $count = 0) : View {

        $item = new View(
            EntityID::nextId(),
            $newsSite,
            $count
        );

        $this->viewRepository->add($item);
        return $item;
    }

    public function all()
    {
        return $this->viewRepository->all();
    }

    public function save(View $view) {
        $this->viewRepository->save($view);
        return true;
    }
}
