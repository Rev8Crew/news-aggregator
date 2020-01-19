<?php


namespace App\Models\repositories;


use App\Models\entities\Category\Category;
use App\Models\entities\EntityID;
use App\Models\Hydrator;
use Carbon\Carbon;
use App\Models\entities\User\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    static $table = 'user';
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var NewsRepository
     */
    private $newsRepository;
    /**
     * @var ViewRepository
     */
    private $viewRepository;

    public function __construct(Hydrator $hydrate, CategoryRepository $categoryRepository, NewsRepository $newsRepository, ViewRepository $viewRepository)
    {
        $this->hydrate = $hydrate;
        $this->categoryRepository = $categoryRepository;
        $this->newsRepository = $newsRepository;
        $this->viewRepository = $viewRepository;
    }

    public function findByToken($token)
    {
        $duplicate = DB::table(static::$table)->where('token', $token)->first();
        return $duplicate ? $this->convertFromDb($duplicate) : null;
    }

    public function findByName($title)
    {
        $duplicate = DB::table(static::$table)->where('name', $title)->first();
        return $duplicate ? $this->convertFromDb($duplicate) : null;
    }

    public function get(EntityID $id): User
    {
        $result = DB::table(static::$table)->where('entity_id', $id->getId())->first();
        return $this->convertFromDb($result);
    }

    public function convertFromDb($result): User
    {
        return $this->hydrate->hydrate(User::class, [
            'entityID'      => new EntityID($result->entity_id),
            'name'          => $result->name,
            'password'      => $result->password,
            'categories'    => $this->extractCategories($result->categories),
            'news'          => $this->extractNews($result->news),
            'views'         => $this->extractViews($result->views),
            'token'         => $result->token,
        ]);
    }

    public function extractViews($json) {
        $data = [];
        foreach (json_decode($json) as $item) {
            $data[] = $this->viewRepository->get(new EntityID($item));
        }

        return $data;
    }

    public function extractCategories($json)
    {
        $data = [];
        foreach (json_decode($json) as $item) {
            $data[] = $this->categoryRepository->get(new EntityID($item));
        }

        return $data;
    }

    public function extractNews($json)
    {
        $data = [];
        foreach (json_decode($json) as $item) {
            $data[] = $this->newsRepository->get(new EntityID($item));
        }

        return $data;
    }

    public function add(User $item): void
    {
        DB::table(static::$table)->insert(
            [
                'entity_id'     => $item->getEntityID()->getId(),
                'name'          => $item->getName(),
                'password'      => $item->getPassword(),
                'categories'    => $this->compactArray($item->getCategories()),
                'news'          => $this->compactArray($item->getNews()),
                'views'         => $this->compactArray($item->getViews()),
                'token'         => $item->getToken()
            ]);
    }

    private function compactArray(array $items) {
        $data = [];
        /** @var Category $item */
        foreach ($items as $item) {
            $data[] = $item->getEntityID()->getId();
        }

        return json_encode($data);
    }

    public function save(User $item): void
    {

        DB::table(static::$table)->where('entity_id', $item->getEntityID()->getId())->update([
            'name'          => $item->getName(),
            'password'      => $item->getPassword(),
            'categories'    => $this->compactArray($item->getCategories()),
            'news'          => $this->compactArray($item->getNews()),
            'views'         => $this->compactArray($item->getViews()),
            'token'         => $item->getToken()
        ]);
    }
}
