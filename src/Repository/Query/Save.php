<?php

namespace Rmtram\TextDatabase\Repository\Query;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Repository\BaseRepository;
use Rmtram\TextDatabase\Repository\Traits\ValidateTrait;
use Rmtram\TextDatabase\Writer\StorageWriter;

class Save
{

    use ValidateTrait;

    /**
     * save update operator
     */
    const OPERATOR_ADD    = 1;

    /**
     * save add operator.
     */
    const OPERATOR_UPDATE = 2;

    /**
     * @var BaseRepository
     */
    private $repository;

    /**
     * @var array
     */
    private $unique = ['primary', 'unique'];

    /**
     * @var int
     */
    private $lastIndex;

    /**
     * @var array
     */
    private $lastEntity;

    /**
     * @var array
     */
    private $data;

    /**
     * @param BaseRepository $repository
     * @param array $data
     */
    public function __construct(BaseRepository $repository, array &$data)
    {
        $this->repository = $repository;
        $this->data = &$data;
    }

    /**
     * @param BaseEntity $entity
     * @return mixed
     */
    public function save(BaseEntity $entity)
    {
        if (false === $this->validate($entity)) {
            return false;
        }
        $operator = $this->substitution($entity);
        if ($this->write()) {
            return true;
        }
        if (false === $this->rollback($operator)) {
            throw new \RuntimeException('fail rollback.');
        }
        return false;
    }

    /**
     * @param $operator
     * @return bool
     */
    private function rollback($operator)
    {
        if (!is_null($this->lastIndex)) {
            if ($operator === static::OPERATOR_ADD) {
                unset($this->data[$this->lastIndex]);
                return true;
            }
            else if ($operator === static::OPERATOR_UPDATE) {
                $this->data[$this->lastIndex] = $this->lastEntity;
                return true;
            }
        }
        return false;
    }

    /**
     * @param BaseEntity $entity
     * @return int
     */
    private function substitution(BaseEntity $entity)
    {
        $fields = $this->repository->getFields();
        $row = $entity();
        foreach ($fields as $field) {
            $property = $field();
            $name = $property['name'];
            if ($this->isUnique($property)) {
                if (!empty($entity->$name)) {
                    $index = $this->repository->find()
                        ->where($name, $entity->$name)
                        ->index();
                    if (false !== $index && isset($this->data[$index])) {
                        $this->lastIndex  = $index;
                        $this->lastEntity = $this->data[$index];
                        $this->data[$index] = $row;
                        return static::OPERATOR_UPDATE;
                    }
                }
            }
            if ($this->isAutoIncrement($property)) {
                $last = $this->repository->find()
                    ->order([$name => 'desc'])
                    ->first();
                if (empty($last)) {
                    $row[$name] = 1;
                }
                else {
                    $row[$name] = $last->$name + 1;
                }
            }
        }
        $this->data[] = $row;
        end($this->data);
        $this->lastIndex = key($this->data);
        return static::OPERATOR_ADD;
    }

    /**
     * @return bool
     */
    private function write()
    {
        $writer = new StorageWriter($this
            ->repository
            ->getTable(), $this->data);
        return $writer->write(true);
    }

    /**
     * @param array $property
     * @return bool
     */
    private function isAutoIncrement(array $property)
    {
        if (!isset($property['attributes']['autoIncrement'])) {
            return false;
        }
        if (true === $property['attributes']['autoIncrement']) {
            return true;
        }
        return false;
    }

    /**
     * @param array $property
     * @return bool
     */
    private function isUnique(array $property)
    {
        $attr = $property['attributes'];
        foreach ($this->unique as $key) {
            if (isset($attr[$key]) && true === $attr[$key]) {
                return true;
            }
        }
        return false;
    }
}