<?php
namespace Logshub\SearchClient\Response;

class Priorities extends ResponseAbstract
{
    /**
     * @return int|null
     */
    public function getTotal()
    {
        $data = $this->getBodyArray();

        if (empty($data['total'])) {
            return null;
        }

        return (int)$data['total'];
    }

    public function getDocuments()
    {
        $data = $this->getBodyArray();
        if (empty($data['docs'])) {
            return [];
        }

        $docs = [];
        foreach ($data['docs'] as $prod) {
            $docs[] = new \Logshub\SearchClient\Model\Product($prod['id'], $prod);
        }

        return $docs;
    }
}
