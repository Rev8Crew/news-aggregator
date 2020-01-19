<?php


namespace App\Models\entities;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class EntityID
{
    protected $id;
    public function __construct($id)
    {
        Assert::notEmpty($id);

        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function isEqualTo( self $other) : bool {
        return $this->getId() == $other->getId();
    }

    public static function nextId(): EntityID
    {
        return new self(Uuid::uuid4()->toString());
    }

}
