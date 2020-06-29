<?php

use PageInfos;


abstract class AbstractRepository implements RepositoryInterface
{
    protected $client;

    protected $serializer;

    protected $provider;

    protected $PageInfos;

    public function __construct(

        ClientInterface $client,
        SerializerInterface $serializer,
        RepositoryProvider $provider

    )
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->provider = $provider;
    }

    public function findByPk(string $identifier, array $options = [])
    {

        $uri = $this->getApiResourceUri($identifier);
        $response = $this->client->get($uri);
        $content = $response->getBody()->getContents();
        $responseData = $this->serializer->decode($content, 'json');
        if ($this->hasRawResponseOption($options)) {
            return $responseData;
        }
        return $this->normalize($responseData);
    }

    protected function getApiResourceUri(?string $identifier = null)
    {
        if ($identifier) {
            return sprintf('/%s/%s', $this->getApiResourceName(), $identifier);
        }
        return '/' . $this->getApiResourceName();
    }

    protected function hasRawResponseOption(array $options)
    {
        if (isset($options['raw_response']) && $options['raw_response'] === true) {
            return true;
        }
        return false;
    }

    protected function normalize(array $data, array $context = [])
    {
        if (isset($data['items']) && isset($data['pagination'])) {
            $result = $this->serializer->denormalize($data['items'], $this->getModelClass() . '[]', 'json', $context);
            return new ArrayCollection($result);
        } else {
            return $this->serializer->denormalize($data, $this->getModelClass(), 'json');
        }
    }

    public function create(ModelInterface $object, array $options = [])
    {

        $data = $this->serializer->normalize($object, 'json', ['create_or_update' => true]);

        $uri = $this->getApiResourceUri();
        $requestBody = $this->serializer->encode($data, 'json');
        $response = $this->client->post($uri, $requestBody);
        $content = $response->getBody()->getContents();
        $responseData = $this->serializer->decode($content, 'json');
        if ($this->hasRawResponseOption($options)) {
            return $responseData;
        }

        return $this->normalize($responseData);
    }

    public function update(ModelInterface $object, array $options = [])
    {

        $data = $this->serializer->normalize($object, 'json', ['create_or_update' => true]);
        $uri = $this->getApiResourceUri($data['id']);
        $requestBody = $this->serializer->encode($data, 'json');
        $response = $this->client->update($uri, $requestBody);
        $responseBody = $response->getBody()->getContents();
        $responseData = $this->serializer->decode($responseBody, 'json');

        if ($this->hasRawResponseOption($options)) {
            return $responseData;
        }

        return $this->normalize($responseData);
    }

    public function findOne(array $query = [], array $options = [])
    {
        $query['limit'] = 1;
        $response = $this->find($query, $options);


        if ($this->hasRawResponseOption($options)) {
            return $response['items'][0] ?? null;
        }

        return $response->isEmpty() ? null : $response->first();
    }

    public function find(array $query = [], array $options = [])
    {

        if (isset($query['filter'])) {
            $query['filter'] = $this->serializer->encode($query['filter'], 'json');
        }

        if (!isset($query['limit'])) {
            $query['limit'] = 10;
        }

        if (!isset($query['page'])) {
            $query['page'] = 1;
        }

        $uri = $this->getApiResourceUri();
        $response = $this->client->get($uri, $query);
        $content = $response->getBody()->getContents();
        $data = $this->serializer->decode($content, 'json');


        if ($this->hasRawResponseOption($options)) {
            return $data;
        }

        $total = $data['pagination']['totalItems'];
        $lastPage = $data['pagination']['totalPages'];

        $this->PageInfos = (new PageInfos())
            ->setLimit($query['limit'])
            ->setCurrentPage($query['page'])
            ->setTotal($total)
            ->setLastPage($lastPage);

        $context = $options['normalization_context'] ?? [];

        return $this->normalize($data, $context);
    }

    public function delete(ModelInterface $object, array $options = [])
    {
        return $this->deleteByPk($object->getId());
    }

    public function deleteByPk(string $identifier, array $options = [])
    {
        $uri = $this->getApiResourceUri($identifier);
        $data = $this->client->delete($uri);

        if ($this->hasRawResponseOption($options)) {
            return $data;
        }

        return null;
    }

    public function getPaginationInfo(): PageInfos
    {
        return $this->paginationInfo;
    }

}

?>