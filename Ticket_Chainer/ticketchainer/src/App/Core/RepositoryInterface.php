<?php


interface RepositoryInterface
{
    public function findByPk(string $identifier, array $options = []);

    public function find(array $item = [], array $options = []);

    public function findOne(array $item = [], array $options = []);

    public function create(ModelInterface $model, array $options = []);

    public function update(ModelInterface $model, array $options = []);

    public function deleteByPk(string $identifier, array $options = []);

    public function delete(ModelInterface $model, array $options = []);

    public function getApiResourceName(): string;

    public function getModelClass(): string;

}

?>