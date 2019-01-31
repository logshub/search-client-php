<?php
namespace Logshub\SearchClient\Response;

class SearchProducts extends ResponseAbstract
{
    /**
     * @return int|null
     */
    public function getProductsTotal()
    {
        $data = $this->getBodyArray();

        if (empty($data['products']['total'])) {
            return null;
        }

        return (int)$data['products']['total'];
    }

    public function getProducts()
    {
        $data = $this->getBodyArray();
        if (empty($data['products']['docs'])) {
            return [];
        }

        $products = [];
        foreach ($data['products']['docs'] as $prod) {
            $products[] = new \Logshub\SearchClient\Model\Product($prod['id'], $prod);
        }

        return $products;
    }

    public function getCategories()
    {
        $data = $this->getBodyArray();
        if (empty($data['categories']['docs'])) {
            return [];
        }

        $categories = [];
        foreach ($data['categories']['docs'] as $cat) {
            $categories[] = new \Logshub\SearchClient\Model\Category($cat['id'], $cat);
        }

        return $categories;
    }

    /**
     * Array (
     *  [0] => Array (
     *      [key] => Raspberry Pi
     *      [value] => 6
     *  )
     *  [1] => Array (
     *      [key] => Raspberry Pi 2 B+ A+
     *      [value] => 1
     *  )
     * )
     * @return array
     */
    public function getProductAggregations()
    {
        $data = $this->getBodyArray();
        if (empty($data['products']['agg_categories'])) {
            return [];
        }

        return $data['products']['agg_categories'];
    }
}
