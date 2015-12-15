<?php

namespace Rmtram\TextDatabase\Repository\Query;

use Rmtram\TextDatabase\Entity\BaseEntity;
use Rmtram\TextDatabase\Repository\BaseRepository;
use Rmtram\TextDatabase\Writer\StorageWriter;

class Save
{
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
        $operator = $this->substitution($entity);
        if ($this->write()) {
            return true;
        }
        if (false === $this->rollback($operator)) {
            throw new \RuntimeException('save failed');
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
        $this->repository->getFields();
        $uniqueFields = $this->generatorUniqueFields();
        foreach ($uniqueFields as $fieldName) {
            $index = $this->repository->find()
                ->where($fieldName, $entity->$fieldName)
                ->indexNumber();
            if (false !== $index && isset($this->data[$index])) {
                $this->lastIndex  = $index;
                $this->lastEntity = $this->data[$index];
                $this->data[$index] = $entity();
                return static::OPERATOR_UPDATE;
            }
        }
        $this->data[] = $entity();
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
     * @return \Generator
     */
    private function generatorUniqueFields()
    {
        $fields = $this->repository->getFields();
        foreach ($fields as $field) {
            $_field = $field();
            if ($this->isUnique($_field)) {
                yield $_field['name'];
            }
        }
    }

    /**
     * @param array $field
     * @return bool
     */
    private function isUnique(array $field)
    {
        $attr = $field['attributes'];
        foreach ($this->unique as $key) {
            if (isset($attr[$key]) && true === $attr[$key]) {
                return true;
            }
        }
        return false;
    }
}